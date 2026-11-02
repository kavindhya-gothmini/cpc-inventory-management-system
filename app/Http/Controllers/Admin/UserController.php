<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $users = User::with([
            'employee.branch',
            'employee.department',
            'employee.functionUnit',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'admin.users.index',
            compact('users')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $employees = Employee::with([
            'branch',
            'department',
            'functionUnit',
        ])
            ->whereDoesntHave('user')
            ->orderBy('name')
            ->get();

        return view(
            'admin.users.create',
            compact('employees')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE
            |--------------------------------------------------------------------------
            */

            'employee_id' => [
                'required',
                'integer',
                'exists:employees,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | EMAIL
            |--------------------------------------------------------------------------
            */

            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                'unique:users,email',
            ],


            /*
            |--------------------------------------------------------------------------
            | COUNTRY CODE
            |--------------------------------------------------------------------------
            */

            'country_code' => [
                'required',
                'string',
                'regex:/^\+[1-9][0-9]{0,3}$/',
            ],


            /*
            |--------------------------------------------------------------------------
            | CONTACT NUMBER
            |--------------------------------------------------------------------------
            |
            | Exactly 9 numeric digits after the country code.
            |
            | Example:
            | Country Code: +94
            | Number:       712345678
            | Stored as:    +94712345678
            |
            */

            'phone_number' => [
                'required',
                'string',
                'regex:/^[0-9]{9}$/',
            ],


            /*
            |--------------------------------------------------------------------------
            | ROLE
            |--------------------------------------------------------------------------
            */

            'role' => [
                'required',
                Rule::in([
                    'employee',
                    'admin',
                    'super_admin',
                ]),
            ],


            /*
            |--------------------------------------------------------------------------
            | PASSWORD
            |--------------------------------------------------------------------------
            */

            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
                'confirmed',
            ],
        ], [

            /*
            |--------------------------------------------------------------------------
            | CUSTOM VALIDATION MESSAGES
            |--------------------------------------------------------------------------
            */

            'email.required' =>
                'Email address is required.',

            'email.email' =>
                'Please enter a valid email address containing @.',

            'email.unique' =>
                'This email address is already registered.',

            'country_code.required' =>
                'Please select a country code.',

            'country_code.regex' =>
                'Please select a valid country code.',

            'phone_number.required' =>
                'Contact number is required.',

            'phone_number.regex' =>
                'Contact number must contain exactly 9 numeric digits after the country code.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | FIND EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $employee = Employee::findOrFail(
            $validated['employee_id']
        );


        /*
        |--------------------------------------------------------------------------
        | CHECK EMPLOYEE ACCOUNT
        |--------------------------------------------------------------------------
        */

        if ($employee->user()->exists()) {

            return back()
                ->withInput()
                ->withErrors([
                    'employee_id' =>
                        'This employee already has a login account.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | BUILD FULL PHONE NUMBER
        |--------------------------------------------------------------------------
        */

        $phone =
            $validated['country_code'] .
            $validated['phone_number'];


        /*
        |--------------------------------------------------------------------------
        | CHECK DUPLICATE PHONE NUMBER
        |--------------------------------------------------------------------------
        */

        if (
            User::where('phone', $phone)
                ->exists()
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'phone_number' =>
                        'This contact number is already registered.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK DUPLICATE EPF NUMBER
        |--------------------------------------------------------------------------
        |
        | EPF comes from the linked Employee record.
        |
        */

        if (
            User::where(
                'epf_number',
                $employee->epf_no
            )->exists()
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'employee_id' =>
                        'A login account already exists for this EPF number.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE EMPLOYEE EPF
        |--------------------------------------------------------------------------
        */

        $epfError = $this->validateEpfNumber(
            $employee->epf_no
        );

        if ($epfError !== null) {

            return back()
                ->withInput()
                ->withErrors([
                    'employee_id' => $epfError,
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE USER ACCOUNT
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $employee,
            $phone
        ) {

            User::create([

                'employee_id' => $employee->id,

                'epf_number' => $employee->epf_no,

                'name' => $employee->name,

                'email' => strtolower(
                    trim($validated['email'])
                ),

                'phone' => $phone,

                'role' => $validated['role'],

                'password' => $validated['password'],
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                "Login account created successfully for {$employee->name}."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(User $user): View
    {
        $user->load([
            'employee.branch',
            'employee.department',
            'employee.functionUnit',
        ]);

        return view(
            'admin.users.show',
            compact('user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(User $user): View
    {
        $user->load([
            'employee.branch',
            'employee.department',
            'employee.functionUnit',
        ]);

        return view(
            'admin.users.edit',
            compact('user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $user
    ): RedirectResponse {

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | NAME
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | EPF NUMBER
            |--------------------------------------------------------------------------
            */

            'epf_number' => [
                'required',
                'string',
                'regex:/^[0-9]{5}$/',
                Rule::unique(
                    'users',
                    'epf_number'
                )->ignore($user->id),
            ],


            /*
            |--------------------------------------------------------------------------
            | EMAIL
            |--------------------------------------------------------------------------
            */

            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],


            /*
            |--------------------------------------------------------------------------
            | COUNTRY CODE
            |--------------------------------------------------------------------------
            */

            'country_code' => [
                'required',
                'string',
                'regex:/^\+[1-9][0-9]{0,3}$/',
            ],


            /*
            |--------------------------------------------------------------------------
            | CONTACT NUMBER
            |--------------------------------------------------------------------------
            */

            'phone_number' => [
                'required',
                'string',
                'regex:/^[0-9]{9}$/',
            ],


            /*
            |--------------------------------------------------------------------------
            | PASSWORD
            |--------------------------------------------------------------------------
            */

            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
                'confirmed',
            ],


            /*
            |--------------------------------------------------------------------------
            | PROFILE PICTURE
            |--------------------------------------------------------------------------
            */

            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],


            /*
            |--------------------------------------------------------------------------
            | REMOVE PROFILE PICTURE
            |--------------------------------------------------------------------------
            */

            'remove_profile_picture' => [
                'nullable',
                'boolean',
            ],

        ], [

            /*
            |--------------------------------------------------------------------------
            | CUSTOM VALIDATION MESSAGES
            |--------------------------------------------------------------------------
            */

            'epf_number.required' =>
                'EPF number is required.',

            'epf_number.regex' =>
                'EPF number must contain exactly 5 numeric digits.',

            'epf_number.unique' =>
                'This EPF number is already registered.',

            'email.required' =>
                'Email address is required.',

            'email.email' =>
                'Please enter a valid email address containing @.',

            'email.unique' =>
                'This email address is already registered.',

            'country_code.required' =>
                'Please select a country code.',

            'country_code.regex' =>
                'Please select a valid country code.',

            'phone_number.required' =>
                'Contact number is required.',

            'phone_number.regex' =>
                'Contact number must contain exactly 9 numeric digits after the country code.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | EPF SEQUENTIAL / REPEATED-DIGIT VALIDATION
        |--------------------------------------------------------------------------
        */

        $epfError = $this->validateEpfNumber(
            $validated['epf_number']
        );

        if ($epfError !== null) {

            return back()
                ->withInput()
                ->withErrors([
                    'epf_number' => $epfError,
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | BUILD FULL PHONE NUMBER
        |--------------------------------------------------------------------------
        */

        $phone =
            $validated['country_code'] .
            $validated['phone_number'];


        /*
        |--------------------------------------------------------------------------
        | CHECK DUPLICATE PHONE NUMBER
        |--------------------------------------------------------------------------
        */

        $duplicatePhone = User::where(
            'phone',
            $phone
        )
            ->where(
                'id',
                '!=',
                $user->id
            )
            ->exists();


        if ($duplicatePhone) {

            return back()
                ->withInput()
                ->withErrors([
                    'phone_number' =>
                        'This contact number is already registered.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PREPARE UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $data = [

            'name' =>
                trim($validated['name']),

            'epf_number' =>
                trim($validated['epf_number']),

            'email' =>
                strtolower(
                    trim($validated['email'])
                ),

            'phone' =>
                $phone,
        ];


        /*
        |--------------------------------------------------------------------------
        | UPDATE PASSWORD
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['password']
            )
        ) {

            $data['password'] =
                $validated['password'];
        }


        /*
        |--------------------------------------------------------------------------
        | PROFILE PICTURE
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'profile_picture'
            )
        ) {

            if (
                !empty(
                    $user->profile_picture
                ) &&
                Storage::disk('public')->exists(
                    $user->profile_picture
                )
            ) {

                Storage::disk('public')->delete(
                    $user->profile_picture
                );
            }


            $data['profile_picture'] =
                $request
                    ->file('profile_picture')
                    ->store(
                        'profile-pictures',
                        'public'
                    );

        } elseif (
            $request->boolean(
                'remove_profile_picture'
            ) &&
            !empty(
                $user->profile_picture
            )
        ) {

            if (
                Storage::disk('public')->exists(
                    $user->profile_picture
                )
            ) {

                Storage::disk('public')->delete(
                    $user->profile_picture
                );
            }


            $data['profile_picture'] =
                null;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */

        $user->update($data);


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                "Login account for {$user->name} updated successfully."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOCK
    |--------------------------------------------------------------------------
    */

    public function lock(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {

            return back()->with(
                'error',
                'You cannot lock your own login account.'
            );
        }


        if ($user->role === 'super_admin') {

            $superAdminCount =
                User::where(
                    'role',
                    'super_admin'
                )->count();


            if ($superAdminCount <= 1) {

                return back()->with(
                    'error',
                    'The last Super Admin account cannot be locked.'
                );
            }
        }


        if ($user->isLocked()) {

            return back()->with(
                'error',
                "The login account for {$user->name} is already locked."
            );
        }


        $user->lockAccount();


        return back()->with(
            'success',
            "The login account for {$user->name} has been locked successfully."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UNLOCK
    |--------------------------------------------------------------------------
    */

    public function unlock(User $user): RedirectResponse
    {
        if (!$user->isLocked()) {

            return back()->with(
                'error',
                "The login account for {$user->name} is not locked."
            );
        }


        $user->unlockAccount();


        return back()->with(
            'success',
            "The login account for {$user->name} has been unlocked successfully."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {

            return back()->with(
                'error',
                'You cannot delete your own login account.'
            );
        }


        if ($user->role === 'super_admin') {

            $superAdminCount =
                User::where(
                    'role',
                    'super_admin'
                )->count();


            if ($superAdminCount <= 1) {

                return back()->with(
                    'error',
                    'The last Super Admin account cannot be deleted.'
                );
            }
        }


        if (
            !empty(
                $user->profile_picture
            ) &&
            Storage::disk('public')->exists(
                $user->profile_picture
            )
        ) {

            Storage::disk('public')->delete(
                $user->profile_picture
            );
        }


        $userName =
            $user->name;


        $user->delete();


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                "Login account for {$userName} was deleted successfully."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EPF VALIDATION HELPER
    |--------------------------------------------------------------------------
    |
    | Rules:
    |
    | 1. Exactly 5 numeric digits.
    | 2. All repeated digits are rejected.
    |    Examples: 11111, 22222, 99999
    |
    | 3. Consecutive ascending numbers are rejected.
    |    Examples: 12345, 23456, 34567
    |
    | 4. Consecutive descending numbers are rejected.
    |    Examples: 54321, 65432, 98765
    |
    */

    private function validateEpfNumber(
        $epf
    ): ?string {

        $epf = trim(
            (string) $epf
        );


        /*
        |--------------------------------------------------------------------------
        | EXACTLY 5 NUMERIC DIGITS
        |--------------------------------------------------------------------------
        */

        if (
            !preg_match(
                '/^[0-9]{5}$/',
                $epf
            )
        ) {

            return
                'EPF number must contain exactly 5 numeric digits.';
        }


        /*
        |--------------------------------------------------------------------------
        | REPEATED DIGITS
        |--------------------------------------------------------------------------
        |
        | Reject:
        | 11111
        | 22222
        | 55555
        | 99999
        |
        */

        if (
            preg_match(
                '/^([0-9])\1{4}$/',
                $epf
            )
        ) {

            return
                'Sequential and repeated-digit EPF numbers are not allowed. Please enter a valid 5-digit EPF number.';
        }


        /*
        |--------------------------------------------------------------------------
        | CONVERT TO DIGITS
        |--------------------------------------------------------------------------
        */

        $digits =
            array_map(
                'intval',
                str_split($epf)
            );


        /*
        |--------------------------------------------------------------------------
        | ASCENDING / DESCENDING SEQUENCE
        |--------------------------------------------------------------------------
        |
        | Reject:
        |
        | 01234
        | 12345
        | 23456
        | 34567
        | 45678
        | 56789
        |
        | and:
        |
        | 98765
        | 87654
        | 76543
        | 65432
        | 54321
        | 43210
        |
        */

        $ascending = true;

        $descending = true;


        for (
            $i = 1;
            $i < count($digits);
            $i++
        ) {

            if (
                $digits[$i] !==
                $digits[$i - 1] + 1
            ) {

                $ascending = false;
            }


            if (
                $digits[$i] !==
                $digits[$i - 1] - 1
            ) {

                $descending = false;
            }
        }


        if (
            $ascending ||
            $descending
        ) {

            return
                'Sequential and repeated-digit EPF numbers are not allowed. Please enter a valid 5-digit EPF number.';
        }


        /*
        |--------------------------------------------------------------------------
        | VALID EPF
        |--------------------------------------------------------------------------
        */

        return null;
    }
}

