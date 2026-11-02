<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FunctionUnit;
use App\Models\Item;
use App\Models\ItemAssignment;
use App\Models\ItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemAssignmentController extends Controller
{
    /**
     * Show the assignment form with branch-scoped inventory filters.
     */
    public function create(Employee $employee)
    {
        $this->ensureAssignmentPermission();
        $this->ensureEmployeeBranchAccess($employee);

        if ($this->isInactiveEmployee($employee)) {
            return redirect()
                ->route('admin.employees.show', $employee)
                ->with(
                    'error',
                    'Inventory cannot be assigned to an inactive employee.'
                );
        }

        if (! $employee->branch_id) {
            abort(422, 'The employee must be assigned to a branch first.');
        }

        $employee->load([
            'branch',
            'department',
            'functionUnit',
        ]);

        $branchId = (int) $employee->branch_id;

        /*
         * Validate optional filters against the employee's branch.
         */
        $filters = request()->validate([
            'department_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($branchId) {
                    if ($value === null || $value === '') {
                        return;
                    }

                    $exists = Department::query()
                        ->whereKey($value)
                        ->where('branch_id', $branchId)
                        ->exists();

                    if (! $exists) {
                        $fail(
                            "Please select a department from the employee's branch."
                        );
                    }
                },
            ],
            'function_unit_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($branchId) {
                    if ($value === null || $value === '') {
                        return;
                    }

                    $exists = FunctionUnit::query()
                        ->whereKey($value)
                        ->whereHas(
                            'department',
                            function ($query) use ($branchId) {
                                $query->where('branch_id', $branchId);
                            }
                        )
                        ->exists();

                    if (! $exists) {
                        $fail(
                            "Please select a function unit from the employee's branch."
                        );
                    }
                },
            ],
        ]);

        /*
         * Show only available, unassigned items from the employee's branch.
         */
        $itemsQuery = Item::query()
            ->with([
                'itemType',
                'make',
                'itemModel',
                'branch',
                'department',
                'functionUnit',
            ])
            ->where('branch_id', $branchId)
            ->where('status', 'available')
            ->whereNull('current_employee_id')
            ->whereDoesntHave('assignments', function ($query) {
                $query->whereNull('returned_date');
            });

        if (! empty($filters['department_id'])) {
            $itemsQuery->where(
                'department_id',
                $filters['department_id']
            );
        }

        if (! empty($filters['function_unit_id'])) {
            $itemsQuery->where(
                'function_unit_id',
                $filters['function_unit_id']
            );
        }

        $items = $itemsQuery
            ->orderBy('asset_number')
            ->get();

        $branches = Branch::query()
            ->whereKey($branchId)
            ->orderBy('name')
            ->get();

        $departments = Department::query()
            ->where('branch_id', $branchId)
            ->orderBy('name')
            ->get();

        $functionUnits = FunctionUnit::query()
            ->whereHas('department', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->orderBy('name')
            ->get();

        $itemTypes = ItemType::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.item-assignments.create',
            compact(
                'employee',
                'items',
                'branches',
                'departments',
                'functionUnits',
                'itemTypes'
            )
        );
    }

    /**
     * Assign an available inventory item to an employee.
     */
    public function store(Request $request, Employee $employee)
    {
        $this->ensureAssignmentPermission();
        $this->ensureEmployeeBranchAccess($employee);

        if ($this->isInactiveEmployee($employee)) {
            return back()
                ->withInput()
                ->withErrors([
                    'item_id' =>
                        'Inventory cannot be assigned to an inactive employee.',
                ]);
        }

        if (! $employee->branch_id) {
            return back()
                ->withInput()
                ->withErrors([
                    'item_id' =>
                        'The employee must have a branch before inventory can be assigned.',
                ]);
        }

        $validated = $request->validate([
            'item_id' => [
                'required',
                'integer',
                'exists:items,id',
            ],
            'workstation_id' => [
                'nullable',
                'string',
                'max:255',
            ],
            'assigned_date' => [
                'required',
                'date',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ], [
            'item_id.required' => 'Please select an inventory item.',
            'item_id.exists' => 'The selected inventory item does not exist.',
            'assigned_date.required' => 'Please select the assignment date.',
            'assigned_date.date' => 'Please enter a valid assignment date.',
            'workstation_id.max' =>
                'Workstation ID cannot exceed 255 characters.',
            'remarks.max' => 'Remarks cannot exceed 5000 characters.',
        ]);

        try {
            DB::transaction(function () use ($validated, $employee) {
                /*
                 * Reload and lock the employee to check its current branch.
                 */
                $lockedEmployee = Employee::query()
                    ->whereKey($employee->id)
                    ->lockForUpdate()
                    ->first();

                if (
                    ! $lockedEmployee
                    || ! $lockedEmployee->branch_id
                ) {
                    throw new \RuntimeException(
                        'The employee could not be found or has no assigned branch.'
                    );
                }

                $this->ensureEmployeeBranchAccess($lockedEmployee);

                if ($this->isInactiveEmployee($lockedEmployee)) {
                    throw new \RuntimeException(
                        'Inventory cannot be assigned to an inactive employee.'
                    );
                }

                /*
                 * Lock the selected item to prevent concurrent assignments.
                 */
                $item = Item::query()
                    ->whereKey($validated['item_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $item) {
                    throw new \RuntimeException(
                        'The selected inventory item could not be found.'
                    );
                }

                /*
                 * The item must belong to the employee's branch,
                 * regardless of the logged-in user's role.
                 */
                if (
                    (int) $item->branch_id
                    !== (int) $lockedEmployee->branch_id
                ) {
                    throw new \RuntimeException(
                        "The selected item must belong to the employee's branch."
                    );
                }

                if ($item->status !== 'available') {
                    throw new \RuntimeException(
                        'This inventory item is not available for assignment.'
                    );
                }

                if ($item->current_employee_id !== null) {
                    throw new \RuntimeException(
                        'This inventory item is already assigned to an employee.'
                    );
                }

                $existingAssignment = ItemAssignment::query()
                    ->where('item_id', $item->id)
                    ->whereNull('returned_date')
                    ->exists();

                if ($existingAssignment) {
                    throw new \RuntimeException(
                        'This inventory item already has an active assignment.'
                    );
                }

                ItemAssignment::create([
                    'item_id' => $item->id,
                    'employee_id' => $lockedEmployee->id,
                    'branch_id' => $lockedEmployee->branch_id,
                    'department_id' => $lockedEmployee->department_id,
                    'function_unit_id' => $lockedEmployee->function_unit_id,
                    'workstation_id' =>
                        $validated['workstation_id'] ?? null,
                    'assigned_date' => $validated['assigned_date'],
                    'returned_date' => null,
                    'remarks' => $validated['remarks'] ?? null,
                ]);

                $item->update([
                    'current_employee_id' => $lockedEmployee->id,
                    'status' => 'in_use',
                ]);
            });
        } catch (\RuntimeException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'item_id' => $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route('admin.employees.show', $employee)
            ->with(
                'success',
                'Inventory item assigned to the employee successfully.'
            );
    }

    /**
     * Return an assigned inventory item.
     */
    public function returnItem(
        Request $request,
        Employee $employee,
        ItemAssignment $itemAssignment
    ) {
        $this->ensureAssignmentPermission();
        $this->ensureEmployeeBranchAccess($employee);

        if (
            (int) $itemAssignment->employee_id !== (int) $employee->id
        ) {
            abort(404);
        }

        if (
            (int) $itemAssignment->branch_id !== (int) $employee->branch_id
        ) {
            abort(404);
        }

        if ($itemAssignment->returned_date !== null) {
            return back()->with(
                'error',
                'This inventory item has already been returned.'
            );
        }

        $validated = $request->validate([
            'returned_date' => [
                'required',
                'date',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ], [
            'returned_date.required' => 'Please select the return date.',
            'returned_date.date' => 'Please enter a valid return date.',
            'remarks.max' => 'Remarks cannot exceed 5000 characters.',
        ]);

        try {
            DB::transaction(function () use (
                $validated,
                $itemAssignment,
                $employee
            ) {
                $lockedEmployee = Employee::query()
                    ->whereKey($employee->id)
                    ->lockForUpdate()
                    ->first();

                if (
                    ! $lockedEmployee
                    || ! $lockedEmployee->branch_id
                ) {
                    throw new \RuntimeException(
                        'The employee could not be found or has no assigned branch.'
                    );
                }

                $this->ensureEmployeeBranchAccess($lockedEmployee);

                $assignment = ItemAssignment::query()
                    ->whereKey($itemAssignment->id)
                    ->lockForUpdate()
                    ->first();

                if (! $assignment) {
                    throw new \RuntimeException(
                        'The inventory assignment could not be found.'
                    );
                }

                if (
                    (int) $assignment->employee_id
                        !== (int) $lockedEmployee->id
                    || (int) $assignment->branch_id
                        !== (int) $lockedEmployee->branch_id
                ) {
                    throw new \RuntimeException(
                        'The assignment does not belong to this employee and branch.'
                    );
                }

                if ($assignment->returned_date !== null) {
                    throw new \RuntimeException(
                        'This inventory item has already been returned.'
                    );
                }

                $item = Item::query()
                    ->whereKey($assignment->item_id)
                    ->lockForUpdate()
                    ->first();

                if (
                    ! $item
                    || (int) $item->branch_id
                        !== (int) $lockedEmployee->branch_id
                ) {
                    throw new \RuntimeException(
                        "The inventory item does not belong to the employee's branch."
                    );
                }

                $assignment->update([
                    'returned_date' => $validated['returned_date'],
                    'remarks' => $validated['remarks']
                        ?? $assignment->remarks,
                ]);

                /*
                 * Clear the employee reference only if this employee
                 * is still the item's current assignee.
                 */
                if (
                    (int) $item->current_employee_id
                    === (int) $lockedEmployee->id
                ) {
                    $item->update([
                        'current_employee_id' => null,
                        'status' => 'available',
                    ]);
                }
            });
        } catch (\RuntimeException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'returned_date' => $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route('admin.employees.show', $employee)
            ->with(
                'success',
                'Inventory item returned successfully.'
            );
    }

    /**
     * Only Admin and Super Admin can assign or return inventory.
     */
    private function ensureAssignmentPermission(): void
    {
        $user = auth()->user();

        if (
            ! $user
            || ! in_array(
                strtolower((string) $user->role),
                ['admin', 'super_admin'],
                true
            )
        ) {
            abort(
                403,
                'Only Admin and Super Admin can assign or return inventory items.'
            );
        }
    }

    /**
     * Resolve the branch assigned directly to the user or through
     * the user's employee record.
     */
    private function resolveUserBranchId($user): ?int
    {
        $branchId = $user->branch_id
            ?? $user->employee?->branch_id;

        return $branchId !== null ? (int) $branchId : null;
    }

    /**
     * Only an unassigned Super Admin has global access.
     */
    private function isGlobalSuperAdmin($user): bool
    {
        return strtolower((string) $user->role) === 'super_admin'
            && $this->resolveUserBranchId($user) === null;
    }

    /**
     * Prevent branch-assigned Super Admins and other users from
     * accessing employees belonging to another branch.
     */
    private function ensureEmployeeBranchAccess(Employee $employee): void
    {
        $user = auth()->user();

        if (! $user) {
            abort(403, 'Authentication is required.');
        }

        $userBranchId = $this->resolveUserBranchId($user);

        if ($this->isGlobalSuperAdmin($user)) {
            return;
        }

        if ($userBranchId === null) {
            abort(403, 'Your account is not assigned to a branch.');
        }

        if (
            $employee->branch_id === null
            || (int) $employee->branch_id !== $userBranchId
        ) {
            abort(404);
        }
    }

    /**
     * Determine whether the employee is inactive.
     */
    private function isInactiveEmployee(Employee $employee): bool
    {
        if (
            $employee->is_active !== null
            && ! (bool) $employee->is_active
        ) {
            return true;
        }

        if (
            $employee->status !== null
            && in_array(
                strtolower((string) $employee->status),
                ['inactive', 'terminated', 'disabled'],
                true
            )
        ) {
            return true;
        }

        return false;
    }
}
