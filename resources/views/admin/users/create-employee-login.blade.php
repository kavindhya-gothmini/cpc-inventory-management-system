@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Create Employee Login</h2>
            <p class="text-muted mb-0">
                Verify the CPC HAPIS master record and the local employee record before creating an account.
            </p>
        </div>

        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to User Management
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-semibold mb-3">1. Search Employee Master</h5>

            <form method="GET"
                  action="{{ route('admin.users.create-employee-login') }}">
                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label for="epf_no" class="form-label fw-semibold">
                            Employee EPF Number
                        </label>
                        <input
                            type="text"
                            id="epf_no"
                            name="epf_no"
                            class="form-control"
                            value="{{ old('epf_no', request('epf_no')) }}"
                            inputmode="numeric"
                            pattern="[0-9]{5}"
                            maxlength="5"
                            minlength="5"
                            placeholder="Enter 5-digit EPF number"
                            required
                        >
                    </div>

                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search Both Databases
                        </button>
                    </div>
                </div>
                <div class="form-text mt-2">
                    Searches CPC HAPIS in read-only mode and checks the local MySQL employee record.
                </div>
            </form>
        </div>
    </div>

    @if ($hapisLookupAttempted)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3">2. CPC HAPIS Master Record</h5>

                @if ($hapisLookupError)
                    <div class="alert alert-danger mb-0">
                        <strong>HAPIS lookup failed.</strong>
                        The CPC source database could not be checked. Try again later.
                        No conclusion can be made about whether this employee exists in HAPIS.
                    </div>
                @elseif ($sourceEmployee)
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        An available employee record was found in CPC HAPIS.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Employee Name</div>
                            <div class="fw-semibold">
                                {{ $sourceEmployee->employee_name ?: 'Not recorded' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">EPF Number</div>
                            <div class="fw-semibold">{{ $sourceEmployee->epf_no }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Designation</div>
                            <div>{{ $sourceEmployee->designation ?: 'Not recorded' }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Function</div>
                            <div>{{ $sourceEmployee->function_name ?: 'Not recorded' }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Sub-function / Department</div>
                            <div>{{ $sourceEmployee->sub_function ?: 'Not recorded' }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">HAPIS Source Location</div>
                            <div>
                                {{ $sourceEmployee->source_location_name ?: 'Not recorded' }}
                                @if ($sourceEmployee->source_location_id)
                                    <span class="text-muted">
                                        ({{ $sourceEmployee->source_location_id }})
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        No currently available employee record was found in CPC HAPIS for EPF
                        <strong>{{ request('epf_no') }}</strong>.
                        Login creation is unavailable until the source record is verified.
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3">3. Local MySQL Employee Record</h5>

                @if ($employee)
                    <div class="alert alert-info">
                        A local employee record exists. Verify the local assignment against the HAPIS source details above.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Local Employee Name</div>
                            <div class="fw-semibold">{{ $employee->name }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Local EPF Number</div>
                            <div class="fw-semibold">{{ $employee->epf_no }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Local Designation</div>
                            <div>{{ $employee->designation ?: 'Not recorded' }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Local Branch</div>
                            <div>{{ $employee->branch?->name ?? 'Not assigned' }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Local Department</div>
                            <div>{{ $employee->department?->name ?? 'Not assigned' }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Local Function Unit</div>
                            <div>{{ $employee->functionUnit?->name ?? 'Not assigned' }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Local Employee Email</div>
                            <div>{{ $employee->email ?: 'Not recorded' }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small">Local Employee Status</div>
                            @if ($employee->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        No local MySQL employee record exists for EPF
                        <strong>{{ request('epf_no') }}</strong>.
                        HAPIS data is not automatically copied into MySQL. An authorized administrator must first register the employee locally and verify the correct branch and department.
                    </div>
                @endif
            </div>
        </div>

        @if ($hapisLookupError)
            <div class="alert alert-warning">
                Login creation is blocked because the HAPIS record could not be verified.
            </div>
        @elseif (!$sourceEmployee)
            <div class="alert alert-warning">
                Login creation is blocked because no available HAPIS employee was found.
            </div>
        @elseif (!$employee)
            <div class="alert alert-warning">
                Login creation is blocked until a matching local employee record is registered and verified.
            </div>
        @elseif ($existingUser)
            <div class="alert alert-info">
                <strong>Login already exists.</strong>
                This employee is linked to an account named
                <strong>{{ $existingUser->name }}</strong>.
                No duplicate login can be created here.
            </div>
        @elseif (!$employee->is_active)
            <div class="alert alert-warning">
                The local employee record is inactive. A login cannot be created.
            </div>
        @elseif (
            !$employee->branch_id
            || !$employee->branch
            || !$employee->branch->is_active
        )
            <div class="alert alert-warning">
                The local employee has no valid active branch. Verify the branch assignment before creating a login.
            </div>
        @elseif (
            !$employee->department_id
            || !$employee->department
            || (int) $employee->department->branch_id !== (int) $employee->branch_id
        )
            <div class="alert alert-warning">
                The local employee does not have a valid department belonging to the assigned branch. Correct the local record before creating a login.
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-2">4. Create Employee Login</h5>
                    <p class="text-muted">
                        Verify the employee identity and local branch/department assignment before proceeding.
                    </p>

                    <form method="POST"
                          action="{{ route('admin.users.store-employee-login') }}">
                        @csrf

                        <input type="hidden" name="epf_no"
                               value="{{ $employee->epf_no }}">

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                Employee Login Email
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', $employee->email) }}"
                                maxlength="255"
                                autocomplete="email"
                                required
                            >
                            <div class="form-text">
                                Use an email address verified for this employee.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                Initial Password
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                minlength="12"
                                autocomplete="new-password"
                                required
                            >
                            <div class="form-text">
                                At least 12 characters, including uppercase and lowercase letters, a number, and a symbol.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation"
                                   class="form-label fw-semibold">
                                Confirm Initial Password
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                minlength="12"
                                autocomplete="new-password"
                                required
                            >
                        </div>

                        <div class="alert alert-warning border">
                            <i class="bi bi-shield-check"></i>
                            HAPIS provides the source employee information. The local MySQL branch and department determine the account's local scope.
                            Do not proceed unless you have verified that the local assignment is correct.
                        </div>

                        <div class="form-check mb-4">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="branch_verified"
                                name="branch_verified"
                                value="1"
                                required
                                {{ old('branch_verified') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="branch_verified">
                                I have verified the employee's identity and confirmed that the local branch and department are correct for the HAPIS source location.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-person-check"></i>
                            Create Employee Login
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="btn btn-outline-secondary ms-2">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
