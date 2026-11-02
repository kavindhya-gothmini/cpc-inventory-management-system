<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\FunctionUnit;
use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemModel;
use App\Models\ItemSpecification;
use App\Models\ItemType;
use App\Models\Make;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ItemController extends Controller
{
    /**
     * Display inventory items within the user's authorized branch.
     *
     * Only an unassigned Super Admin has system-wide access.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $branchId = $user->branch_id
            ?? $user->employee?->branch_id;

        $isGlobalSuperAdmin =
            $user->role === 'super_admin'
            && $branchId === null;

        $query = Item::query();

        if (! $isGlobalSuperAdmin) {
            if ($branchId === null) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('branch_id', $branchId);
            }
        }

        $items = (clone $query)
            ->with([
                'itemType',
                'make',
                'itemModel',
                'branch',
                'department',
                'functionUnit',
                'currentEmployee',
            ])
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        $totalCount = (clone $query)->count();

        $availableCount = (clone $query)
            ->where('status', 'available')
            ->count();

        $inUseCount = (clone $query)
            ->where('status', 'in_use')
            ->count();

        $attentionCount = (clone $query)
            ->whereIn('status', [
                'under_repair',
                'sent_to_rdd',
            ])
            ->count();

        return view(
            'admin.items.index',
            compact(
                'items',
                'totalCount',
                'availableCount',
                'inUseCount',
                'attentionCount'
            )
        );
    }

    /**
     * Show the inventory registration form.
     */
    public function create(): View
    {
        $this->ensureSuperAdmin();

        $user = auth()->user();
        $branchId = $this->resolveUserBranchId($user);
        $isGlobalSuperAdmin = $this->isGlobalSuperAdmin($user);

        $this->ensureUsableBranch($branchId, $isGlobalSuperAdmin);

        $itemTypes = ItemType::where('is_active', true)
            ->orderBy('name')
            ->get();

        $makes = Make::where('is_active', true)
            ->orderBy('name')
            ->get();

        $itemModels = ItemModel::where('is_active', true)
            ->with('make')
            ->orderBy('name')
            ->get();

        $branches = Branch::query()
            ->where('is_active', true)
            ->when(
                ! $isGlobalSuperAdmin,
                fn ($query) => $query->where('id', $branchId)
            )
            ->orderBy('name')
            ->get();

        $departments = Department::query()
            ->where('is_active', true)
            ->when(
                ! $isGlobalSuperAdmin,
                fn ($query) => $query->where('branch_id', $branchId)
            )
            ->orderBy('name')
            ->get();

        $functionUnits = FunctionUnit::query()
            ->where('is_active', true)
            ->when(
                ! $isGlobalSuperAdmin,
                fn ($query) => $query->whereHas(
                    'department',
                    fn ($departmentQuery) => $departmentQuery
                        ->where('branch_id', $branchId)
                )
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.items.create',
            compact(
                'itemTypes',
                'makes',
                'itemModels',
                'branches',
                'departments',
                'functionUnits'
            )
        );
    }

    /**
     * Register a new inventory item.
     * New inventory always starts with Available status.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();

        // Enforce the user's branch BEFORE validation.
        $this->enforceRequestBranch($request);

        $validated = $request->validate(
            $this->itemValidationRules()
        );

        $this->validateDepartmentAndFunctionUnit($validated);

        // A new item must always start as available.
        $validated['status'] = 'available';

        $make = $this->getValidatedMake(
            $validated['make_id'] ?? null
        );

        $itemModel = $this->getValidatedItemModel(
            $validated['model_id'] ?? null,
            $validated['make_id'] ?? null
        );

        $makeName = $make?->name;
        $modelName = $itemModel?->name;

        DB::transaction(function () use (
            $validated,
            $makeName,
            $modelName
        ) {
            $item = Item::create([
                'item_type_id' => $validated['item_type_id'],
                'asset_number' => $validated['asset_number'],
                'serial_number' => $validated['serial_number'] ?? null,

                'make_id' => $validated['make_id'] ?? null,
                'model_id' => $validated['model_id'] ?? null,

                // Keep legacy text columns synchronized.
                'make' => $makeName,
                'model' => $modelName,

                'status' => 'available',

                'branch_id' => $validated['branch_id'] ?? null,
                'department_id' => $validated['department_id'] ?? null,
                'function_unit_id' => $validated['function_unit_id'] ?? null,

                'description' => $validated['description'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'ip_address' => $validated['ip_address'] ?? null,
                'workstation_id' => $validated['workstation_id'] ?? null,
            ]);

            ItemSpecification::create([
                'item_id' => $item->id,
                'ram' => $validated['ram'] ?? null,
                'storage' => $validated['storage'] ?? null,
                'processor' => $validated['processor'] ?? null,
                'graphics' => $validated['graphics'] ?? null,
                'operating_system' => $validated['operating_system'] ?? null,
            ]);

            ItemHistory::create([
                'item_id' => $item->id,
                'event_type' => 'registered',
                'event_date' => now(),
                'old_status' => null,
                'new_status' => 'available',
                'remarks' => 'Inventory item registered with available status.',
            ]);
        });

        return redirect()
            ->route('admin.items.index')
            ->with(
                'success',
                'Inventory item was registered successfully with Available status.'
            );
    }

    /**
     * Display inventory item details.
     */
    public function show(Item $item): View
    {
        $this->ensureBranchAccess($item);

        $item->load([
            'itemType',
            'make',
            'itemModel',
            'branch',
            'department',
            'functionUnit',
            'currentEmployee',
            'specification',
            'assignments.employee',
            'assignments.branch',
            'assignments.department',
            'assignments.functionUnit',
            'histories.employee',
        ]);

        return view('admin.items.show', compact('item'));
    }

    /**
     * Show the inventory edit form.
     */
    public function edit(Item $item): View
    {
        $this->ensureSuperAdmin();
        $this->ensureBranchAccess($item);

        $user = auth()->user();
        $branchId = $this->resolveUserBranchId($user);
        $isGlobalSuperAdmin = $this->isGlobalSuperAdmin($user);

        $itemTypes = ItemType::where('is_active', true)
            ->orderBy('name')
            ->get();

        $makes = Make::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Retain the currently selected model if it is inactive.
        $itemModels = ItemModel::query()
            ->where(function ($query) use ($item) {
                $query->where('is_active', true)
                    ->orWhere('id', $item->model_id);
            })
            ->with('make')
            ->orderBy('name')
            ->get();

        $branches = Branch::query()
            ->where(function ($query) use ($item) {
                $query->where('is_active', true)
                    ->orWhere('id', $item->branch_id);
            })
            ->when(
                ! $isGlobalSuperAdmin,
                fn ($query) => $query->where('id', $branchId)
            )
            ->orderBy('name')
            ->get();

        $departments = Department::query()
            ->where(function ($query) use ($item) {
                $query->where('is_active', true)
                    ->orWhere('id', $item->department_id);
            })
            ->when(
                ! $isGlobalSuperAdmin,
                fn ($query) => $query->where('branch_id', $branchId)
            )
            ->orderBy('name')
            ->get();

        $functionUnits = FunctionUnit::query()
            ->where(function ($query) use ($item) {
                $query->where('is_active', true)
                    ->orWhere('id', $item->function_unit_id);
            })
            ->when(
                ! $isGlobalSuperAdmin,
                fn ($query) => $query->whereHas(
                    'department',
                    fn ($departmentQuery) => $departmentQuery
                        ->where('branch_id', $branchId)
                )
            )
            ->orderBy('name')
            ->get();

        $item->load([
            'specification',
            'make',
            'itemModel',
        ]);

        return view(
            'admin.items.edit',
            compact(
                'item',
                'itemTypes',
                'makes',
                'itemModels',
                'branches',
                'departments',
                'functionUnits'
            )
        );
    }

    /**
     * Update an existing inventory item.
     */
    public function update(
        Request $request,
        Item $item
    ): RedirectResponse {
        $this->ensureSuperAdmin();
        $this->ensureBranchAccess($item);

        // Prevent a branch-scoped Super Admin from moving the item
        // into another branch through a manipulated request.
        $this->enforceRequestBranch($request);

        $validated = $request->validate(
            $this->itemValidationRules($item)
        );

        $this->validateDepartmentAndFunctionUnit($validated);

        $make = $this->getValidatedMake(
            $validated['make_id'] ?? null
        );

        $itemModel = $this->getValidatedItemModel(
            $validated['model_id'] ?? null,
            $validated['make_id'] ?? null
        );

        $makeName = $make?->name;
        $modelName = $itemModel?->name;

        DB::transaction(function () use (
            $validated,
            $item,
            $makeName,
            $modelName
        ) {
            $lockedItem = Item::query()
                ->lockForUpdate()
                ->findOrFail($item->id);

            // Recheck access on the locked record.
            $this->ensureBranchAccess($lockedItem);

            $oldStatus = $lockedItem->status;
            $newStatus = $validated['status'];

            $lockedItem->update([
                'item_type_id' => $validated['item_type_id'],
                'asset_number' => $validated['asset_number'],
                'serial_number' => $validated['serial_number'] ?? null,

                'make_id' => $validated['make_id'] ?? null,
                'model_id' => $validated['model_id'] ?? null,

                'make' => $makeName,
                'model' => $modelName,

                'status' => $newStatus,

                'branch_id' => $validated['branch_id'] ?? null,
                'department_id' => $validated['department_id'] ?? null,
                'function_unit_id' => $validated['function_unit_id'] ?? null,

                'description' => $validated['description'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'ip_address' => $validated['ip_address'] ?? null,
                'workstation_id' => $validated['workstation_id'] ?? null,
            ]);

            $lockedItem->specification()->updateOrCreate(
                ['item_id' => $lockedItem->id],
                [
                    'ram' => $validated['ram'] ?? null,
                    'storage' => $validated['storage'] ?? null,
                    'processor' => $validated['processor'] ?? null,
                    'graphics' => $validated['graphics'] ?? null,
                    'operating_system' => $validated['operating_system'] ?? null,
                ]
            );

            if ($oldStatus !== $newStatus) {
                ItemHistory::create([
                    'item_id' => $lockedItem->id,
                    'event_type' => 'status_changed',
                    'event_date' => now(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'remarks' => sprintf(
                        'Inventory status changed from %s to %s.',
                        $oldStatus,
                        $newStatus
                    ),
                ]);
            }
        });

        return redirect()
            ->route('admin.items.show', $item)
            ->with('success', 'Inventory item was updated successfully.');
    }

    /**
     * Permanently delete an inventory item and dependent records.
     */
    public function destroy(Item $item): RedirectResponse
    {
        $this->ensureSuperAdmin();
        $this->ensureBranchAccess($item);

        DB::transaction(function () use ($item) {
            $lockedItem = Item::query()
                ->lockForUpdate()
                ->findOrFail($item->id);

            // Recheck access on the locked record.
            $this->ensureBranchAccess($lockedItem);

            $lockedItem->assignments()->delete();
            $lockedItem->histories()->delete();
            $lockedItem->specification()->delete();

            DB::table('rdd_transactions')
                ->where('item_id', $lockedItem->id)
                ->delete();

            $lockedItem->delete();
        });

        return redirect()
            ->route('admin.items.index')
            ->with(
                'success',
                'Inventory item and related inventory records were permanently deleted.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    /**
     * Shared validation rules for creating and updating inventory.
     *
     * @return array<string, array<int, mixed>>
     */
    private function itemValidationRules(?Item $item = null): array
    {
        return [
            'item_type_id' => [
                'required',
                'integer',
                'exists:item_types,id',
            ],

            'asset_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('items', 'asset_number')
                    ->ignore($item?->id),
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:150',
                Rule::unique('items', 'serial_number')
                    ->ignore($item?->id),
            ],

            'make_id' => [
                'nullable',
                'integer',
                'exists:makes,id',
            ],

            'model_id' => [
                'nullable',
                'integer',
                'exists:item_models,id',
            ],

            'status' => [
                $item ? 'required' : 'nullable',
                Rule::in([
                    'available',
                    'in_use',
                    'under_repair',
                    'sent_to_rdd',
                    'scrapped',
                    'transferred',
                ]),
            ],

            'branch_id' => [
                'nullable',
                'integer',
                'exists:branches,id',
            ],

            'department_id' => [
                'nullable',
                'integer',
                'exists:departments,id',
            ],

            'function_unit_id' => [
                'nullable',
                'integer',
                'exists:function_units,id',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'ip_address' => [
                'nullable',
                'string',
                'max:45',
                'ip',
            ],

            'workstation_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ram' => [
                'nullable',
                'string',
                'max:100',
            ],

            'storage' => [
                'nullable',
                'string',
                'max:150',
            ],

            'processor' => [
                'nullable',
                'string',
                'max:150',
            ],

            'graphics' => [
                'nullable',
                'string',
                'max:150',
            ],

            'operating_system' => [
                'nullable',
                'string',
                'max:150',
            ],
        ];
    }

    /**
     * Ensure the selected department belongs to the selected branch
     * and the selected function unit belongs to that department.
     */
    private function validateDepartmentAndFunctionUnit(
        array $validated
    ): void {
        $branchId = $validated['branch_id'] ?? null;
        $departmentId = $validated['department_id'] ?? null;
        $functionUnitId = $validated['function_unit_id'] ?? null;

        if ($departmentId !== null) {
            $department = Department::query()
                ->whereKey($departmentId)
                ->first();

            if (
                ! $department
                || $branchId === null
                || (int) $department->branch_id !== (int) $branchId
            ) {
                abort(
                    422,
                    'The selected department does not belong to the selected branch.'
                );
            }
        }

        if ($functionUnitId !== null) {
            $functionUnit = FunctionUnit::query()
                ->whereKey($functionUnitId)
                ->first();

            if (
                ! $functionUnit
                || $departmentId === null
                || (int) $functionUnit->department_id !== (int) $departmentId
            ) {
                abort(
                    422,
                    'The selected function unit does not belong to the selected department.'
                );
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MAKE VALIDATION
    |--------------------------------------------------------------------------
    */

    /**
     * Validate and retrieve the selected active Make.
     */
    private function getValidatedMake(mixed $makeId): ?Make
    {
        if ($makeId === null || $makeId === '') {
            return null;
        }

        $make = Make::query()
            ->whereKey($makeId)
            ->where('is_active', true)
            ->first();

        if (! $make) {
            abort(422, 'The selected Make is invalid or inactive.');
        }

        return $make;
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL VALIDATION
    |--------------------------------------------------------------------------
    */

    /**
     * Ensure the selected Model belongs to the selected active Make.
     */
    private function getValidatedItemModel(
        mixed $modelId,
        mixed $makeId
    ): ?ItemModel {
        if ($modelId === null || $modelId === '') {
            return null;
        }

        if ($makeId === null || $makeId === '') {
            abort(
                422,
                'Please select a Make before selecting a Model.'
            );
        }

        $itemModel = ItemModel::query()
            ->whereKey($modelId)
            ->where('make_id', $makeId)
            ->where('is_active', true)
            ->with('make')
            ->first();

        if (! $itemModel) {
            abort(
                422,
                'The selected Model does not belong to the selected Make or is inactive.'
            );
        }

        return $itemModel;
    }

    /*
    |--------------------------------------------------------------------------
    | BRANCH ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    /**
     * Resolve the branch assigned directly to the user or via their employee.
     */
    private function resolveUserBranchId($user): ?int
    {
        $branchId = $user->branch_id
            ?? $user->employee?->branch_id;

        return $branchId !== null ? (int) $branchId : null;
    }

    /**
     * Only a Super Admin without an assigned branch has global access.
     */
    private function isGlobalSuperAdmin($user): bool
    {
        return $user->role === 'super_admin'
            && $this->resolveUserBranchId($user) === null;
    }

    /**
     * Ensure a branch-scoped Super Admin cannot choose another branch.
     *
     * This happens before request validation so related validation uses
     * the enforced branch rather than a branch submitted by the browser.
     */
    private function enforceRequestBranch(Request $request): void
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if ($this->isGlobalSuperAdmin($user)) {
            return;
        }

        $branchId = $this->resolveUserBranchId($user);

        if ($branchId === null) {
            abort(403, 'Your account has no assigned branch.');
        }

        $request->merge([
            'branch_id' => $branchId,
        ]);
    }

    /**
     * Ensure an item belongs to the user's authorized branch.
     */
    private function ensureBranchAccess(Item $item): void
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        if ($this->isGlobalSuperAdmin($user)) {
            return;
        }

        $branchId = $this->resolveUserBranchId($user);

        if (
            $branchId === null
            || (int) $item->branch_id !== $branchId
        ) {
            abort(404);
        }
    }

    /**
     * Ensure a branch-scoped account has a branch before showing forms.
     */
    private function ensureUsableBranch(
        ?int $branchId,
        bool $isGlobalSuperAdmin
    ): void {
        if (! $isGlobalSuperAdmin && $branchId === null) {
            abort(403, 'Your account has no assigned branch.');
        }
    }

    /**
     * Restrict inventory master-record management to Super Admin.
     */
    private function ensureSuperAdmin(): void
    {
        $user = auth()->user();

        if (! $user || $user->role !== 'super_admin') {
            abort(
                403,
                'Only Super Admin can add, edit, or delete inventory items.'
            );
        }
    }
}