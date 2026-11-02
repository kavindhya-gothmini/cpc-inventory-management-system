<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\RddHistory;
use App\Models\RddTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RddController extends Controller
{
    private const STATUSES = [
        'pending',
        'approved',
        'dispatched',
        'received',
        'inspection',
        'in_progress',
        'completed',
        'returned',
        'beyond_repair',
        'rejected',
        'cancelled',
    ];

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = $this->scopeRddQuery(
            RddTransaction::query()
        )->with([
            'item.itemType',
            'item.branch',
            'item.department',
            'employee',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function (Builder $q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhere('recommendation', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%")
                    ->orWhereHas('item', function (Builder $itemQuery) use ($search) {
                        $itemQuery->where('asset_number', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%")
                            ->orWhere('ip_address', 'like', "%{$search}%")
                            ->orWhere('workstation_id', 'like', "%{$search}%");
                    })
                    ->orWhereHas('employee', function (Builder $employeeQuery) use ($search) {
                        $employeeQuery->where('epf_no', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
        }

        if (
            $request->filled('status')
            && in_array($request->input('status'), self::STATUSES, true)
        ) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('rdd_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('rdd_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('active')) {
            $query->where(
                'is_active',
                $request->input('active') === '1'
            );
        }

        // Statistics use the same branch scope as the displayed records.
        $statisticsQuery = fn () => $this->scopeRddQuery(
            RddTransaction::query()
        );

        $totalRdd = $statisticsQuery()->count();

        $pendingRdd = $statisticsQuery()
            ->whereIn('status', ['pending', 'approved', 'dispatched'])
            ->count();

        $receivedRdd = $statisticsQuery()
            ->whereIn('status', ['received', 'inspection', 'in_progress'])
            ->count();

        $completedRdd = $statisticsQuery()
            ->whereIn('status', ['completed', 'returned', 'beyond_repair'])
            ->count();

        $rejectedRdd = $statisticsQuery()
            ->whereIn('status', ['rejected', 'cancelled'])
            ->count();

        $rddTransactions = $query
            ->latest('rdd_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.inventory.rdd.index', compact(
            'rddTransactions',
            'totalRdd',
            'pendingRdd',
            'receivedRdd',
            'completedRdd',
            'rejectedRdd'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(Request $request): View
    {
        $itemsQuery = $this->scopeItemQuery(
            Item::query()
        )
            ->with([
                'itemType',
                'branch',
                'department',
                'currentEmployee',
            ])
            ->where('status', '!=', 'sent_to_rdd')
            ->whereDoesntHave('rddTransactions', function (Builder $query) {
                $query->where('is_active', true);
            });

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $itemsQuery->where(function (Builder $query) use ($search) {
                $query->where('asset_number', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('workstation_id', 'like', "%{$search}%");
            });
        }

        $items = $itemsQuery->orderBy('asset_number')->get();

        $employees = $this->scopeEmployeeQuery(
            Employee::query()
        )
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.inventory.rdd.create', compact(
            'items',
            'employees'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'rdd_date' => ['required', 'date'],
            'reason' => ['required', 'string', 'min:5'],
            'recommendation' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
        ], [
            'item_id.required' => 'Please select an inventory item.',
            'reason.required' => 'A valid reason is required before sending an item to RDD.',
            'reason.min' => 'The RDD reason must contain at least 5 characters.',
            'rdd_date.required' => 'Please select the RDD date.',
        ]);

        DB::transaction(function () use ($validated) {
            $item = Item::query()
                ->whereKey($validated['item_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureItemBranchAccess($item);

            if ($item->status === 'sent_to_rdd') {
                abort(422, 'This inventory item has already been sent to RDD.');
            }

            $existingActiveRdd = RddTransaction::query()
                ->where('item_id', $item->id)
                ->where('is_active', true)
                ->exists();

            if ($existingActiveRdd) {
                abort(422, 'This inventory item already has an active RDD transaction.');
            }

            $employeeId = $validated['employee_id']
                ?? $item->current_employee_id;

            if ($employeeId !== null) {
                $employee = Employee::query()
                    ->whereKey($employeeId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->ensureEmployeeBranchAccess($employee);

                // The employee and inventory item must belong to the same branch.
                abort_unless(
                    (int) $employee->branch_id === (int) $item->branch_id,
                    422,
                    'The selected employee must belong to the same branch as the inventory item.'
                );
            }

            $referenceNo = $this->generateReferenceNumber();

            $oldStatus = $item->status;

            $rdd = RddTransaction::create([
                'reference_no' => $referenceNo,
                'item_id' => $item->id,
                'employee_id' => $employeeId,
                'rdd_date' => $validated['rdd_date'],
                'reason' => trim($validated['reason']),
                'recommendation' => $validated['recommendation'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'status' => 'pending',
                'is_active' => true,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $item->update(['status' => 'sent_to_rdd']);

            ItemHistory::create([
                'item_id' => $item->id,
                'event_type' => 'sent_to_rdd',
                'from_status' => $oldStatus,
                'to_status' => 'sent_to_rdd',
                'description' => 'Item sent to RDD. Reference: '
                    . $referenceNo . '. Reason: ' . trim($validated['reason']),
                'employee_id' => $employeeId,
                'event_date' => now(),
                'created_by' => Auth::id(),
            ]);

            RddHistory::create([
                'rdd_transaction_id' => $rdd->id,
                'old_status' => null,
                'new_status' => 'pending',
                'remarks' => 'RDD transaction created.',
                'recommendation' => $validated['recommendation'] ?? null,
                'changed_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.inventory.rdd.index')
            ->with('success', 'Item successfully sent to RDD and inventory status updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(RddTransaction $rdd): View
    {
        $this->ensureRddBranchAccess($rdd);

        $rdd->load([
            'item.itemType',
            'item.branch',
            'item.department',
            'item.functionUnit',
            'item.specification',
            'item.currentEmployee',
            'employee',
            'histories',
        ]);

        return view('admin.inventory.rdd.show', compact('rdd'));
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(RddTransaction $rdd): View
    {
        $this->ensureRddBranchAccess($rdd);

        $rdd->load([
            'item.itemType',
            'item.branch',
            'item.department',
            'item.currentEmployee',
            'employee',
        ]);

        $employees = $this->scopeEmployeeQuery(
            Employee::query()
        )
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.inventory.rdd.edit', compact(
            'rdd',
            'employees'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        RddTransaction $rdd
    ): RedirectResponse {
        $validated = $request->validate([
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'rdd_date' => ['required', 'date'],
            'reason' => ['required', 'string', 'min:5'],
            'recommendation' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
            'decision' => ['nullable', 'string', 'max:30'],
            'received_date' => ['nullable', 'date'],
            'completed_date' => ['nullable', 'date', 'after_or_equal:rdd_date'],
            'work_performed' => ['nullable', 'string'],
            'final_condition' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($validated, $rdd) {
            $lockedRdd = RddTransaction::query()
                ->whereKey($rdd->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureRddBranchAccess($lockedRdd);

            $item = Item::query()
                ->whereKey($lockedRdd->item_id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureItemBranchAccess($item);

            $employeeId = $validated['employee_id'] ?? null;

            if ($employeeId !== null) {
                $employee = Employee::query()
                    ->whereKey($employeeId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->ensureEmployeeBranchAccess($employee);

                abort_unless(
                    (int) $employee->branch_id === (int) $item->branch_id,
                    422,
                    'The selected employee must belong to the same branch as the inventory item.'
                );
            }

            $lockedRdd->update([
                'employee_id' => $employeeId,
                'rdd_date' => $validated['rdd_date'],
                'reason' => trim($validated['reason']),
                'recommendation' => $validated['recommendation'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'decision' => $validated['decision'] ?? null,
                'received_date' => $validated['received_date'] ?? null,
                'completed_date' => $validated['completed_date'] ?? null,
                'work_performed' => $validated['work_performed'] ?? null,
                'final_condition' => $validated['final_condition'] ?? null,
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.inventory.rdd.show', $rdd)
            ->with('success', 'RDD record updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        RddTransaction $rdd
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                'in:' . implode(',', self::STATUSES),
            ],
            'remarks' => ['nullable', 'string'],
            'recommendation' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $rdd) {
            $lockedRdd = RddTransaction::query()
                ->whereKey($rdd->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureRddBranchAccess($lockedRdd);

            $item = Item::query()
                ->whereKey($lockedRdd->item_id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureItemBranchAccess($item);

            $oldStatus = $lockedRdd->status;
            $newStatus = $validated['status'];

            if ($oldStatus === $newStatus) {
                return;
            }

            $lockedRdd->update([
                'status' => $newStatus,
                'remarks' => $validated['remarks'] ?? $lockedRdd->remarks,
                'recommendation' => $validated['recommendation']
                    ?? $lockedRdd->recommendation,
                'updated_by' => Auth::id(),
            ]);

            if ($newStatus === 'received' && ! $lockedRdd->received_date) {
                $lockedRdd->update([
                    'received_date' => now()->toDateString(),
                ]);
            }

            if (
                in_array($newStatus, ['completed', 'returned', 'beyond_repair'], true)
                && ! $lockedRdd->completed_date
            ) {
                $lockedRdd->update([
                    'completed_date' => now()->toDateString(),
                ]);
            }

            if (in_array($newStatus, [
                'completed',
                'returned',
                'beyond_repair',
                'rejected',
                'cancelled',
            ], true)) {
                $lockedRdd->update(['is_active' => false]);
            }

            RddHistory::create([
                'rdd_transaction_id' => $lockedRdd->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'remarks' => $validated['remarks'] ?? $lockedRdd->remarks,
                'recommendation' => $validated['recommendation']
                    ?? $lockedRdd->recommendation,
                'changed_by' => Auth::id(),
            ]);

            ItemHistory::create([
                'item_id' => $lockedRdd->item_id,
                'event_type' => 'rdd_status_updated',
                'from_status' => 'sent_to_rdd',
                'to_status' => 'sent_to_rdd',
                'description' => 'RDD status changed from '
                    . Str::headline($oldStatus) . ' to '
                    . Str::headline($newStatus)
                    . '. Reference: ' . $lockedRdd->reference_no,
                'employee_id' => $lockedRdd->employee_id,
                'event_date' => now(),
                'created_by' => Auth::id(),
            ]);
        });

        return back()->with('success', 'RDD status updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    */

    public function history(RddTransaction $rdd): View
    {
        $this->ensureRddBranchAccess($rdd);

        $rdd->load([
            'item',
            'employee',
            'histories',
        ]);

        return view('admin.inventory.rdd.history', compact('rdd'));
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel RDD Transaction
    |--------------------------------------------------------------------------
    */

    public function destroy(RddTransaction $rdd): RedirectResponse
    {
        $result = DB::transaction(function () use ($rdd) {
            $lockedRdd = RddTransaction::query()
                ->whereKey($rdd->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureRddBranchAccess($lockedRdd);

            if (! in_array($lockedRdd->status, [
                'pending',
                'rejected',
                'cancelled',
            ], true)) {
                return 'cannot_cancel';
            }

            if ($lockedRdd->status === 'cancelled') {
                return 'already_cancelled';
            }

            $item = Item::query()
                ->whereKey($lockedRdd->item_id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureItemBranchAccess($item);

            $oldStatus = $lockedRdd->status;

            $lockedRdd->update([
                'status' => 'cancelled',
                'is_active' => false,
                'updated_by' => Auth::id(),
            ]);

            RddHistory::create([
                'rdd_transaction_id' => $lockedRdd->id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'remarks' => 'RDD transaction cancelled.',
                'changed_by' => Auth::id(),
            ]);

            if ($item->status === 'sent_to_rdd') {
                $previousHistory = ItemHistory::query()
                    ->where('item_id', $item->id)
                    ->where('event_type', 'sent_to_rdd')
                    ->latest('event_date')
                    ->latest('id')
                    ->first();

                $previousStatus = $previousHistory?->from_status ?? 'available';

                $item->update(['status' => $previousStatus]);

                ItemHistory::create([
                    'item_id' => $item->id,
                    'event_type' => 'rdd_cancelled',
                    'from_status' => 'sent_to_rdd',
                    'to_status' => $previousStatus,
                    'description' => 'RDD transaction '
                        . $lockedRdd->reference_no
                        . ' was cancelled and the inventory item status was restored.',
                    'employee_id' => $lockedRdd->employee_id,
                    'event_date' => now(),
                    'created_by' => Auth::id(),
                ]);
            }

            return 'cancelled';
        });

        if ($result === 'cannot_cancel') {
            return back()->with(
                'error',
                'This RDD record cannot be cancelled because it has already progressed through the RDD workflow.'
            );
        }

        if ($result === 'already_cancelled') {
            return back()->with('error', 'This RDD record has already been cancelled.');
        }

        return redirect()
            ->route('admin.inventory.rdd.index')
            ->with(
                'success',
                'RDD transaction cancelled successfully and the inventory item status was restored.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Reference Number
    |--------------------------------------------------------------------------
    */

    private function generateReferenceNumber(): string
    {
        $year = now()->year;

        $lastRdd = RddTransaction::query()
            ->whereYear('created_at', $year)
            ->latest('id')
            ->first();

        $nextNumber = $lastRdd
            ? ((int) substr($lastRdd->reference_no, -4) + 1)
            : 1;

        do {
            $reference = sprintf('RDD-%d-%04d', $year, $nextNumber);

            $exists = RddTransaction::query()
                ->where('reference_no', $reference)
                ->exists();

            if ($exists) {
                $nextNumber++;
            }
        } while ($exists);

        return $reference;
    }

    /*
    |--------------------------------------------------------------------------
    | Branch Access Helpers
    |--------------------------------------------------------------------------
    */

    private function scopeRddQuery(Builder $query): Builder
    {
        if ($this->isGlobalSuperAdmin(Auth::user())) {
            return $query;
        }

        $branchId = $this->resolveUserBranchId(Auth::user());

        if ($branchId === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('item', function (Builder $itemQuery) use ($branchId) {
            $itemQuery->where('branch_id', $branchId);
        });
    }

    private function scopeItemQuery(Builder $query): Builder
    {
        if ($this->isGlobalSuperAdmin(Auth::user())) {
            return $query;
        }

        $branchId = $this->resolveUserBranchId(Auth::user());

        if ($branchId === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('branch_id', $branchId);
    }

    private function scopeEmployeeQuery(Builder $query): Builder
    {
        if ($this->isGlobalSuperAdmin(Auth::user())) {
            return $query;
        }

        $branchId = $this->resolveUserBranchId(Auth::user());

        if ($branchId === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('branch_id', $branchId);
    }

    private function ensureRddBranchAccess(RddTransaction $rdd): void
    {
        $this->ensureItemBranchAccess(
            $rdd->item()->firstOrFail()
        );
    }

    private function ensureItemBranchAccess(Item $item): void
    {
        if ($this->isGlobalSuperAdmin(Auth::user())) {
            return;
        }

        $branchId = $this->resolveUserBranchId(Auth::user());

        abort_if($branchId === null, 403);

        abort_unless(
            $item->branch_id !== null
                && (int) $item->branch_id === $branchId,
            404
        );
    }

    private function ensureEmployeeBranchAccess(Employee $employee): void
    {
        if ($this->isGlobalSuperAdmin(Auth::user())) {
            return;
        }

        $branchId = $this->resolveUserBranchId(Auth::user());

        abort_if($branchId === null, 403);

        abort_unless(
            $employee->branch_id !== null
                && (int) $employee->branch_id === $branchId,
            404
        );
    }

    private function resolveUserBranchId($user): ?int
    {
        if (! $user) {
            return null;
        }

        $branchId = $user->branch_id ?? $user->employee?->branch_id;

        return $branchId === null ? null : (int) $branchId;
    }

    private function isGlobalSuperAdmin($user): bool
    {
        return $user !== null
            && strtolower((string) $user->role) === 'super_admin'
            && $this->resolveUserBranchId($user) === null;
    }
}
