<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Item;
use App\Models\Repair;
use App\Models\RepairAccessory;
use App\Models\SparePart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RepairController extends Controller
{
    /**
     * Display branch-scoped repair records and statistics.
     */
    public function index(Request $request): View
    {
        $query = $this->scopedRepairsQuery($request->user())
            ->with([
                'item.itemType',
                'employee',
                'collection',
                'accessories',
                'spareParts',
            ]);

        $statisticsQuery = $this->scopedRepairsQuery($request->user());

        $totalRepairs = (clone $statisticsQuery)->count();

        $pendingRepairs = (clone $statisticsQuery)
            ->where('status', 'pending')->count();

        $sentForRepair = (clone $statisticsQuery)
            ->where('status', 'sent_for_repair')->count();

        $underRepair = (clone $statisticsQuery)
            ->where('status', 'under_repair')->count();

        $completedRepairs = (clone $statisticsQuery)
            ->where('status', 'completed')->count();

        $returnedRepairs = (clone $statisticsQuery)
            ->where('status', 'returned')->count();

        $cancelledRepairs = (clone $statisticsQuery)
            ->where('status', 'cancelled')->count();

        $accessoryQuery = RepairAccessory::query()
            ->whereHas('repair', function ($repairQuery) use ($request) {
                $this->applyBranchScopeToRepairQuery(
                    $repairQuery,
                    $request->user()
                );
            });

        $totalAccessories = (clone $accessoryQuery)->count();

        $returnedAccessories = (clone $accessoryQuery)
            ->where('returned', true)->count();

        $pendingAccessories = (clone $accessoryQuery)
            ->where('returned', false)->count();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('repair_reference', 'like', "%{$search}%")
                    ->orWhere('carrier', 'like', "%{$search}%")
                    ->orWhere('technician', 'like', "%{$search}%")
                    ->orWhere('problem_description', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('epf_no', 'like', "%{$search}%");
                    })
                    ->orWhereHas('item', function ($itemQuery) use ($search) {
                        $itemQuery
                            ->where('asset_number', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%")
                            ->orWhere('ip_address', 'like', "%{$search}%")
                            ->orWhere('workstation_id', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate(
                'date_sent',
                '>=',
                $request->input('date_from')
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'date_sent',
                '<=',
                $request->input('date_to')
            );
        }

        $repairs = $query->latest()->paginate(15)->withQueryString();

        return view(
            'admin.inventory.repairs.index',
            compact(
                'repairs',
                'totalRepairs',
                'pendingRepairs',
                'sentForRepair',
                'underRepair',
                'completedRepairs',
                'returnedRepairs',
                'cancelledRepairs',
                'totalAccessories',
                'returnedAccessories',
                'pendingAccessories'
            )
        );
    }

    /**
     * Show the create repair form with branch-scoped choices.
     */
    public function create(): View
    {
        $user = Auth::user();

        $itemsQuery = Item::query()
            ->with(['itemType', 'currentEmployee'])
            ->orderBy('asset_number');

        $employeesQuery = Employee::query()
            ->where('is_active', true)
            ->orderBy('name');

        $branchId = $this->resolveUserBranchId($user);

        if (! $this->isGlobalSuperAdmin($user)) {
            $this->ensureAssignedBranch($branchId);

            $itemsQuery->where('branch_id', $branchId);
            $employeesQuery->where('branch_id', $branchId);
        }

        $items = $itemsQuery->get();
        $employees = $employeesQuery->get();

        return view(
            'admin.inventory.repairs.create',
            compact('items', 'employees')
        );
    }

    /**
     * Create a repair and its accessories.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->repairValidationRules());

        $repair = DB::transaction(function () use ($validated, $request) {
            $item = Item::whereKey($validated['item_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureItemBranchAccess($item);

            $this->ensureEmployeeMatchesItem(
                $validated['employee_id'] ?? null,
                $item
            );

            $repair = Repair::create([
                'item_id' => $item->id,
                'repair_reference' => $validated['repair_reference'],
                'date_sent' => $validated['date_sent'],
                'employee_id' => $validated['employee_id'] ?? null,
                'carrier' => $validated['carrier'] ?? null,
                'problem_description' => $validated['problem_description'],
                'technician' => $validated['technician'] ?? null,
                'status' => $validated['status'],
                'date_returned' => $validated['date_returned'] ?? null,
                'repair_cost' => $validated['repair_cost'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            foreach ($validated['accessories'] ?? [] as $accessory) {
                $repair->accessories()->create([
                    'accessory_name' => $accessory['accessory_name'],
                    'quantity' => $accessory['quantity'],
                    'issued_date' => $accessory['issued_date'],
                    'returned' => false,
                    'returned_date' => null,
                    'returned_by' => null,
                    'return_remarks' => null,
                ]);
            }

            $this->syncItemStatus($item, $repair->status);

            return $repair;
        });

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Repair record created successfully.');
    }

    /**
     * Display a repair only if its linked item is in the user's branch.
     */
    public function show(Repair $repair): View
    {
        $this->ensureRepairBranchAccess($repair);

        $repair->load([
            'item.itemType',
            'item.branch',
            'item.department',
            'item.functionUnit',
            'item.currentEmployee',
            'employee',
            'collection',
            'accessories',
            'repairSpareParts.sparePart',
        ]);

        // spare_parts does not have an is_active column.
        $activeSpareParts = SparePart::query()
            ->orderBy('part_name')
            ->get();

        return view(
            'admin.inventory.repairs.show',
            compact('repair', 'activeSpareParts')
        );
    }

    /**
     * Show the edit form for an accessible repair.
     */
    public function edit(Repair $repair): View
    {
        $this->ensureRepairBranchAccess($repair);

        $repair->load([
            'item.itemType',
            'item.currentEmployee',
            'employee',
            'collection',
            'accessories',
            'repairSpareParts.sparePart',
        ]);

        $user = Auth::user();
        $branchId = $this->resolveUserBranchId($user);

        $itemsQuery = Item::query()
            ->with(['itemType', 'currentEmployee'])
            ->orderBy('asset_number');

        $employeesQuery = Employee::query()
            ->where('is_active', true)
            ->orderBy('name');

        if (! $this->isGlobalSuperAdmin($user)) {
            $this->ensureAssignedBranch($branchId);

            $itemsQuery->where('branch_id', $branchId);
            $employeesQuery->where('branch_id', $branchId);
        }

        $items = $itemsQuery->get();
        $employees = $employeesQuery->get();

        return view(
            'admin.inventory.repairs.edit',
            compact('repair', 'items', 'employees')
        );
    }

    /**
     * Update a repair without allowing cross-branch item or employee links.
     */
    public function update(
        Request $request,
        Repair $repair
    ): RedirectResponse {
        $this->ensureRepairBranchAccess($repair);

        $validated = $request->validate(
            $this->repairValidationRules(true)
        );

        DB::transaction(function () use ($validated, $repair) {
            $lockedRepair = Repair::whereKey($repair->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureRepairBranchAccess($lockedRepair);

            $oldItem = Item::whereKey($lockedRepair->item_id)
                ->lockForUpdate()
                ->first();

            $newItem = Item::whereKey($validated['item_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureItemBranchAccess($newItem);

            $this->ensureEmployeeMatchesItem(
                $validated['employee_id'] ?? null,
                $newItem
            );

            $oldItemId = (int) $lockedRepair->item_id;

            $lockedRepair->update([
                'item_id' => $newItem->id,
                'repair_reference' => $validated['repair_reference'],
                'date_sent' => $validated['date_sent'],
                'employee_id' => $validated['employee_id'] ?? null,
                'carrier' => $validated['carrier'] ?? null,
                'problem_description' => $validated['problem_description'],
                'technician' => $validated['technician'] ?? null,
                'status' => $validated['status'],
                'date_returned' => $validated['date_returned'] ?? null,
                'repair_cost' => $validated['repair_cost'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            if ($oldItem && $oldItemId !== (int) $newItem->id) {
                $this->restoreItemStatus($oldItem);
            }

            if (array_key_exists('accessories', $validated)) {
                $this->syncAccessories(
                    $lockedRepair,
                    $validated['accessories'] ?? []
                );
            }

            $this->syncItemStatus($newItem, $lockedRepair->status);
        });

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Repair record updated successfully.');
    }

    /**
     * Delete an accessible repair and restore its item's status.
     */
    public function destroy(Repair $repair): RedirectResponse
    {
        $this->ensureRepairBranchAccess($repair);

        DB::transaction(function () use ($repair) {
            $lockedRepair = Repair::whereKey($repair->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureRepairBranchAccess($lockedRepair);

            $item = Item::whereKey($lockedRepair->item_id)
                ->lockForUpdate()
                ->first();

            $lockedRepair->delete();

            if ($item) {
                $this->restoreItemStatus($item);
            }
        });

        return redirect()
            ->route('admin.inventory.repairs.index')
            ->with('success', 'Repair record deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    private function repairValidationRules(bool $updating = false): array
    {
        $rules = [
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'repair_reference' => ['required', 'string', 'max:100'],
            'date_sent' => ['required', 'date'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'carrier' => ['nullable', 'string', 'max:150'],
            'problem_description' => ['required', 'string'],
            'technician' => ['nullable', 'string', 'max:150'],
            'status' => [
                'required',
                'in:pending,sent_for_repair,under_repair,completed,returned,cancelled',
            ],
            'date_returned' => ['nullable', 'date'],
            'repair_cost' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
            'accessories' => ['sometimes', 'nullable', 'array'],
            'accessories.*.accessory_name' => [
                'required',
                'string',
                'max:150',
            ],
            'accessories.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'accessories.*.issued_date' => ['required', 'date'],
        ];

        if ($updating) {
            $rules['accessories.*.id'] = ['nullable', 'integer'];
        }

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORY MANAGEMENT
    |--------------------------------------------------------------------------
    */

    private function syncAccessories(
        Repair $repair,
        array $accessories
    ): void {
        foreach ($accessories as $accessoryData) {
            if (! empty($accessoryData['id'])) {
                $accessory = $repair->accessories()
                    ->whereKey($accessoryData['id'])
                    ->first();

                // Ignore IDs that do not belong to this repair.
                if (! $accessory || $accessory->returned) {
                    continue;
                }

                $accessory->update([
                    'accessory_name' => $accessoryData['accessory_name'],
                    'quantity' => $accessoryData['quantity'],
                    'issued_date' => $accessoryData['issued_date'],
                ]);

                continue;
            }

            $repair->accessories()->create([
                'accessory_name' => $accessoryData['accessory_name'],
                'quantity' => $accessoryData['quantity'],
                'issued_date' => $accessoryData['issued_date'],
                'returned' => false,
                'returned_date' => null,
                'returned_by' => null,
                'return_remarks' => null,
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INVENTORY STATUS
    |--------------------------------------------------------------------------
    */

    private function syncItemStatus(
        ?Item $item,
        string $repairStatus
    ): void {
        if (! $item) {
            return;
        }

        if (in_array(
            $repairStatus,
            ['pending', 'sent_for_repair', 'under_repair'],
            true
        )) {
            $item->update(['status' => 'under_repair']);

            return;
        }

        $this->restoreItemStatus($item);
    }

    private function restoreItemStatus(Item $item): void
    {
        $item->update([
            'status' => $item->current_employee_id
                ? 'in_use'
                : 'available',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BRANCH ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    /**
     * Query repairs through their linked item's branch.
     */
    private function scopedRepairsQuery($user)
    {
        $query = Repair::query();

        $this->applyBranchScopeToRepairQuery($query, $user);

        return $query;
    }

    private function applyBranchScopeToRepairQuery($query, $user): void
    {
        if (! $user) {
            abort(403);
        }

        if ($this->isGlobalSuperAdmin($user)) {
            return;
        }

        $branchId = $this->resolveUserBranchId($user);

        $this->ensureAssignedBranch($branchId);

        $query->whereHas('item', function ($itemQuery) use ($branchId) {
            $itemQuery->where('branch_id', $branchId);
        });
    }

    private function ensureRepairBranchAccess(Repair $repair): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if ($this->isGlobalSuperAdmin($user)) {
            return;
        }

        $branchId = $this->resolveUserBranchId($user);

        $this->ensureAssignedBranch($branchId);

        $itemBranchId = $repair->item()->value('branch_id');

        if (
            $itemBranchId === null
            || (int) $itemBranchId !== $branchId
        ) {
            abort(404);
        }
    }

    private function ensureItemBranchAccess(Item $item): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if ($this->isGlobalSuperAdmin($user)) {
            return;
        }

        $branchId = $this->resolveUserBranchId($user);

        $this->ensureAssignedBranch($branchId);

        if (
            $item->branch_id === null
            || (int) $item->branch_id !== $branchId
        ) {
            abort(404);
        }
    }

    /**
     * An associated employee must belong to the item's branch.
     */
    private function ensureEmployeeMatchesItem(
        ?int $employeeId,
        Item $item
    ): void {
        if ($employeeId === null) {
            return;
        }

        $employee = Employee::findOrFail($employeeId);

        if (
            $item->branch_id === null
            || (int) $employee->branch_id !== (int) $item->branch_id
        ) {
            abort(
                422,
                'The selected employee must belong to the same branch as the inventory item.'
            );
        }
    }

    private function resolveUserBranchId($user): ?int
    {
        if (! $user) {
            return null;
        }

        $branchId = $user->branch_id
            ?? $user->employee?->branch_id;

        return $branchId === null ? null : (int) $branchId;
    }

    /**
     * Only an unassigned Super Admin has system-wide access.
     */
    private function isGlobalSuperAdmin($user): bool
    {
        return $user !== null
            && strtolower((string) $user->role) === 'super_admin'
            && $this->resolveUserBranchId($user) === null;
    }

    private function ensureAssignedBranch(?int $branchId): void
    {
        if ($branchId === null) {
            abort(
                403,
                'Your account must be assigned to a branch to access these records.'
            );
        }
    }
}
