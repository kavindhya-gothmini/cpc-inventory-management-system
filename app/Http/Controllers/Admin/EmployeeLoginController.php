<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Services\CpcHapisEmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Throwable;

class EmployeeLoginController extends Controller
{
    /**
     * Search HAPIS and local MySQL independently.
     */
    public function create(
        Request $request,
        CpcHapisEmployeeService $hapis
    ) {
        $request->validate([
            'epf_no' => ['nullable', 'regex:/^[0-9]{5}$/'],
        ], [
            'epf_no.regex' => 'Enter a valid five-digit EPF number.',
        ]);

        $employee = null;
        $existingUser = null;
        $sourceEmployee = null;
        $hapisLookupAttempted = $request->filled('epf_no');
        $hapisLookupError = false;

        if ($hapisLookupAttempted) {
            $epf = trim((string) $request->input('epf_no'));

            // Read-only search against CPC HAPIS.
            try {
                $sourceEmployee = $hapis->findByEpf($epf);
            } catch (Throwable $exception) {
                report($exception);
                $hapisLookupError = true;
            }

            // Separate search against local MySQL.
            $employee = Employee::with([
                'branch',
                'department',
                'functionUnit',
            ])
                ->where('epf_no', $epf)
                ->first();

            if ($employee) {
                $existingUser = $this->findEmployeeUser($employee);
            }
        }

        return view('admin.users.create-employee-login', [
            'employee' => $employee,
            'existingUser' => $existingUser,
            'sourceEmployee' => $sourceEmployee,
            'hapisLookupAttempted' => $hapisLookupAttempted,
            'hapisLookupError' => $hapisLookupError,
        ]);
    }

    /**
     * Create an Employee login only after HAPIS and local
     * employee records have been verified.
     */
    public function store(
        Request $request,
        CpcHapisEmployeeService $hapis
    ) {
        $validated = $request->validate([
            'epf_no' => [
                'required',
                'regex:/^[0-9]{5}$/',
            ],
            'branch_verified' => [
                'required',
                'accepted',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(12)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'epf_no.regex' =>
                'Enter a valid five-digit EPF number.',
            'branch_verified.accepted' =>
                'Confirm that the local branch and department were verified before creating the login.',
            'email.unique' =>
                'This email address is already linked to a login account.',
            'password.confirmed' =>
                'The password confirmation does not match.',
        ]);

        // Recheck HAPIS at submission time. If HAPIS is unavailable,
        // do not create an account based on an old search result.
        try {
            $sourceEmployee = $hapis->findByEpf($validated['epf_no']);
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'epf_no' =>
                    'CPC HAPIS could not be checked. No login was created. Please try again later.',
            ]);
        }

        if (! $sourceEmployee) {
            throw ValidationException::withMessages([
                'epf_no' =>
                    'This EPF was not found as a currently available employee in CPC HAPIS. No login was created.',
            ]);
        }

        $user = DB::transaction(function () use ($validated) {
            $employee = Employee::with([
                'branch',
                'department',
            ])
                ->where('epf_no', $validated['epf_no'])
                ->lockForUpdate()
                ->first();

            if (! $employee) {
                throw ValidationException::withMessages([
                    'epf_no' =>
                        'No matching local employee record exists. Register and verify the local employee record first.',
                ]);
            }

            if (! $employee->is_active) {
                throw ValidationException::withMessages([
                    'epf_no' =>
                        'This local employee record is inactive.',
                ]);
            }

            if (
                ! $employee->branch_id
                || ! $employee->branch
                || ! $employee->branch->is_active
            ) {
                throw ValidationException::withMessages([
                    'epf_no' =>
                        'The local employee must have a valid active branch before a login can be created.',
                ]);
            }

            if (
                ! $employee->department_id
                || ! $employee->department
                || (int) $employee->department->branch_id
                    !== (int) $employee->branch_id
            ) {
                throw ValidationException::withMessages([
                    'epf_no' =>
                        'The local employee must have a valid department belonging to the assigned branch.',
                ]);
            }

            if ($this->findEmployeeUser($employee)) {
                throw ValidationException::withMessages([
                    'epf_no' =>
                        'This employee already has a login. Search the EPF again to review the existing account.',
                ]);
            }

            return User::create([
                'employee_id' => $employee->id,
                'branch_id' => $employee->branch_id,
                'epf_number' => $employee->epf_no,
                'name' => $employee->name,
                'email' => strtolower(trim($validated['email'])),
                'phone' => $employee->phone,
                'role' => 'employee',
                'password' => $validated['password'],
                'is_locked' => false,
                'failed_login_attempts' => 0,
                'locked_at' => null,
            ]);
        });

        return redirect()
            ->route('admin.users.create-employee-login', [
                'epf_no' => $user->epf_number,
            ])
            ->with(
                'success',
                'Employee login account created successfully for '
                . $user->name . '.'
            );
    }

    /**
     * Update the login email and optionally the password
     * for an existing employee account.
     */
    public function update(Request $request)
    {
        $request->validate([
            'epf_no' => ['required', 'regex:/^[0-9]{5}$/'],
        ], [
            'epf_no.regex' => 'Enter a valid five-digit EPF number.',
        ]);

        $employee = Employee::where('epf_no', $request->input('epf_no'))
            ->first();

        if (! $employee) {
            throw ValidationException::withMessages([
                'epf_no' =>
                    'No existing employee was found with this EPF number.',
            ]);
        }

        $existingUser = $this->findEmployeeUser($employee);

        if (! $existingUser) {
            throw ValidationException::withMessages([
                'epf_no' =>
                    'No login account exists for this employee. Use the create-login form instead.',
            ]);
        }

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($existingUser->id),
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(12)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'email.unique' =>
                'This email address is already used by another account.',
            'password.confirmed' =>
                'The password confirmation does not match.',
        ]);

        DB::transaction(function () use (
            $employee,
            $existingUser,
            $validated
        ) {
            $lockedEmployee = Employee::whereKey($employee->id)
                ->lockForUpdate()
                ->firstOrFail();

            $user = User::whereKey($existingUser->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                ($user->employee_id !== null
                    && (int) $user->employee_id !== (int) $lockedEmployee->id)
                || (
                    $user->employee_id === null
                    && $user->epf_number !== $lockedEmployee->epf_no
                )
            ) {
                throw ValidationException::withMessages([
                    'epf_no' =>
                        'The login account association has changed. Search the EPF again and retry.',
                ]);
            }

            $updates = [
                'email' => strtolower(trim($validated['email'])),
            ];

            if (! empty($validated['password'])) {
                $updates['password'] = $validated['password'];
            }

            // Never reset lockout state as part of this update.
            $user->update($updates);
        });

        return redirect()
            ->route('admin.users.create-employee-login', [
                'epf_no' => $employee->epf_no,
            ])
            ->with(
                'success',
                'Login details updated successfully for '
                . $existingUser->name . '.'
            );
    }

    /**
     * Find the login linked by employee ID, or by EPF for
     * older accounts that do not yet have employee_id set.
     */
    private function findEmployeeUser(Employee $employee): ?User
    {
        $linkedUser = User::where('employee_id', $employee->id)->first();

        if ($linkedUser) {
            return $linkedUser;
        }

        return User::where('epf_number', $employee->epf_no)->first();
    }
}
