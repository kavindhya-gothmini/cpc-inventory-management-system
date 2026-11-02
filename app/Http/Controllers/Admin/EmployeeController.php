<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FunctionUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display employees.
     *
     * Only a global Super Admin can view employees across all branches.
     * Every other user is restricted to their own branch.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $branchId = $this->resolveUserBranchId($user);

        $query = Employee::query();

        if (! $this->isGlobalSuperAdmin($user)) {
            if ($branchId === null) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('branch_id', $branchId);
            }
        }

        $employees = $query
            ->with([
                'branch',
                'department',
                'functionUnit',
                'itemAssignments.item',
            ])
            ->latest()
            ->paginate(15);

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show the employee creation form.
     * Only Super Admin can create employees.
     */
    public function create(): View
    {
        $this->ensureSuperAdmin();

        $user = Auth::user();
        $branchId = $this->resolveUserBranchId($user);

        if ($this->isGlobalSuperAdmin($user)) {
            $branches = Branch::orderBy('name')->get();
            $departments = Department::orderBy('name')->get();
            $functionUnits = FunctionUnit::orderBy('name')->get();
        } else {
            $this->ensureAssignedBranch($branchId);

            $branches = Branch::whereKey($branchId)->get();

            $departments = Department::where('branch_id', $branchId)
                ->orderBy('name')
                ->get();

            $functionUnits = FunctionUnit::whereIn(
                'department_id',
                $departments->pluck('id')
            )
                ->orderBy('name')
                ->get();
        }

        return view(
            'admin.employees.create',
            compact('branches', 'departments', 'functionUnits')
        );
    }

    /**
     * Register a new employee.
     * Branch-scoped Super Admins cannot submit another branch ID.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();
        $this->enforceRequestBranch($request);

        $validated = $request->validate([
            'epf_no' => [
                'required',
                'string',
                'regex:/^[0-9]{5}$/',
                'unique:employees,epf_no',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],

            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')
                    ->where(function ($query) use ($request) {
                        $query->where(
                            'branch_id',
                            $request->input('branch_id')
                        );
                    }),
            ],

            // Function Unit is optional.
            'function_unit_id' => [
                'nullable',
                'integer',
                Rule::exists('function_units', 'id')
                    ->where(function ($query) use ($request) {
                        $query->where(
                            'department_id',
                            $request->input('department_id')
                        );
                    }),
            ],

            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
            ],

            // Sri Lankan mobile number, e.g. +94712345678.
            'phone' => [
                'nullable',
                'string',
                'regex:/^\+947[0-9]{8}$/',
            ],
        ], [
            'epf_no.required' =>
                'EPF number is required.',

            'epf_no.regex' =>
                'EPF number must contain exactly 5 numeric digits.',

            'epf_no.unique' =>
                'This EPF number is already registered.',

            'department_id.exists' =>
                'The selected department does not belong to the selected branch.',

            'function_unit_id.exists' =>
                'The selected function unit does not belong to the selected department.',

            'email.email' =>
                'Please enter a valid email address containing @.',

            'phone.regex' =>
                'Contact number must be a valid Sri Lankan mobile number with +94 followed by exactly 9 digits.',
        ]);

        Employee::create($validated);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee registered successfully.');
    }

    /**
     * Display an employee's details.
     */
    public function show(Employee $employee): View
    {
        $this->ensureBranchAccess($employee);

        $employee->load([
            'branch',
            'department',
            'functionUnit',
            'itemAssignments.item',
            'itemAssignments.branch',
            'itemAssignments.department',
            'itemAssignments.functionUnit',
        ]);

        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show the employee edit form.
     * Only Super Admin can edit employees in their permitted scope.
     */
    public function edit(Employee $employee): View
    {
        $this->ensureSuperAdmin();
        $this->ensureBranchAccess($employee);

        $employee->load([
            'branch',
            'department',
            'functionUnit',
        ]);

        $user = Auth::user();
        $branchId = $this->resolveUserBranchId($user);

        if ($this->isGlobalSuperAdmin($user)) {
            $branches = Branch::orderBy('name')->get();
            $departments = Department::orderBy('name')->get();
            $functionUnits = FunctionUnit::orderBy('name')->get();
        } else {
            $this->ensureAssignedBranch($branchId);

            $branches = Branch::whereKey($branchId)->get();

            $departments = Department::where('branch_id', $branchId)
                ->orderBy('name')
                ->get();

            $functionUnits = FunctionUnit::whereIn(
                'department_id',
                $departments->pluck('id')
            )
                ->orderBy('name')
                ->get();
        }

        return view(
            'admin.employees.edit',
            compact('employee', 'branches', 'departments', 'functionUnits')
        );
    }

    /**
     * Update an employee.
     * Branch-scoped Super Admins cannot move employees to another branch.
     */
    public function update(
        Request $request,
        Employee $employee
    ): RedirectResponse {
        $this->ensureSuperAdmin();
        $this->ensureBranchAccess($employee);
        $this->enforceRequestBranch($request);

        $validated = $request->validate([
            'epf_no' => [
                'required',
                'string',
                'regex:/^[0-9]{5}$/',
                Rule::unique('employees', 'epf_no')
                    ->ignore($employee->id),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],

            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')
                    ->where(function ($query) use ($request) {
                        $query->where(
                            'branch_id',
                            $request->input('branch_id')
                        );
                    }),
            ],

            // Function Unit remains optional.
            'function_unit_id' => [
                'nullable',
                'integer',
                Rule::exists('function_units', 'id')
                    ->where(function ($query) use ($request) {
                        $query->where(
                            'department_id',
                            $request->input('department_id')
                        );
                    }),
            ],

            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'regex:/^\+947[0-9]{8}$/',
            ],
        ], [
            'epf_no.required' =>
                'EPF number is required.',

            'epf_no.regex' =>
                'EPF number must contain exactly 5 numeric digits.',

            'epf_no.unique' =>
                'This EPF number is already registered by another employee.',

            'department_id.exists' =>
                'The selected department does not belong to the selected branch.',

            'function_unit_id.exists' =>
                'The selected function unit does not belong to the selected department.',

            'email.email' =>
                'Please enter a valid email address containing @.',

            'phone.regex' =>
                'Contact number must be a valid Sri Lankan mobile number with +94 followed by exactly 9 digits.',
        ]);

        $employee->update($validated);

        return redirect()
            ->route('admin.employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Delete an employee if no inventory assignment history is linked.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        $this->ensureSuperAdmin();
        $this->ensureBranchAccess($employee);

        // Preserve inventory assignment history.
        if ($employee->itemAssignments()->exists()) {
            return redirect()
                ->route('admin.employees.show', $employee)
                ->with(
                    'error',
                    'This employee cannot be deleted because inventory assignment records are linked to this employee.'
                );
        }

        $employeeName = $employee->name;

        $employee->delete();

        return redirect()
            ->route('admin.employees.index')
            ->with(
                'success',
                "Employee {$employeeName} was permanently deleted."
            );
    }

    /*
    |--------------------------------------------------------------------------
    | BRANCH ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    /**
     * Resolve the user's effective branch.
     */
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
     * Only a Super Admin without an assigned branch has global access.
     */
    private function isGlobalSuperAdmin($user): bool
    {
        return $user !== null
            && strtolower((string) $user->role) === 'super_admin'
            && $this->resolveUserBranchId($user) === null;
    }

    /**
     * Force branch-scoped Super Admin submissions to their own branch.
     *
     * A submitted branch_id from the browser is not trusted.
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

        $this->ensureAssignedBranch($branchId);

        $request->merge([
            'branch_id' => $branchId,
        ]);
    }

    /**
     * Ensure the employee belongs to the user's permitted branch.
     */
    private function ensureBranchAccess(Employee $employee): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if ($this->isGlobalSuperAdmin($user)) {
            return;
        }

        $branchId = $this->resolveUserBranchId($user);

        if (
            $branchId === null
            || (int) $employee->branch_id !== $branchId
        ) {
            abort(404);
        }
    }

    /**
     * A branch-scoped user must have an assigned branch.
     */
    private function ensureAssignedBranch(?int $branchId): void
    {
        if ($branchId === null) {
            abort(
                403,
                'Your account must be assigned to a branch to perform this action.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE PROTECTION
    |--------------------------------------------------------------------------
    */

    /**
     * Only Super Admin can create, edit, update, or delete employees.
     */
    private function ensureSuperAdmin(): void
    {
        $user = Auth::user();

        if (
            ! $user
            || strtolower((string) $user->role) !== 'super_admin'
        ) {
            abort(
                403,
                'Only the Super Admin can perform this action.'
            );
        }
    }
}
