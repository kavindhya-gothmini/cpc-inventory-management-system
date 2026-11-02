@extends('layouts.admin')

@section('title', 'Edit Employee | CPC Inventory Management System')

@section('content')

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css"
>

<style>
    .employee-edit-page {
        --cpc-blue: #0d6efd;
        --cpc-dark: #1f2937;
        --cpc-muted: #6b7280;
        --cpc-border: #e5e7eb;
        --cpc-soft: #f8fafc;
    }

    .employee-edit-page .page-header {
        margin-bottom: 24px;
    }

    .employee-edit-page .page-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--cpc-dark);
        margin-bottom: 4px;
    }

    .employee-edit-page .page-subtitle {
        color: var(--cpc-muted);
        font-size: 0.92rem;
        margin-bottom: 0;
    }

    .employee-edit-page .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .employee-edit-page .card {
        border: 1px solid var(--cpc-border);
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.05);
        overflow: visible;
    }

    .employee-edit-page .card-header {
        background: #ffffff;
        border-bottom: 1px solid var(--cpc-border);
        padding: 18px 22px;
        border-radius: 14px 14px 0 0;
    }

    .employee-edit-page .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--cpc-dark);
        margin: 0;
    }

    .employee-edit-page .section-description {
        font-size: 0.82rem;
        color: var(--cpc-muted);
        margin: 4px 0 0;
    }

    .employee-edit-page .card-body {
        padding: 24px;
    }

    .employee-edit-page .form-label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .employee-edit-page .required-mark {
        color: #dc3545;
        margin-left: 2px;
    }

    .employee-edit-page .form-control,
    .employee-edit-page .form-select {
        min-height: 44px;
        border: 1px solid #d7dce2;
        border-radius: 9px;
        font-size: 0.9rem;
        color: #374151;
        padding: 9px 12px;
        transition: all 0.2s ease;
    }

    .employee-edit-page .form-control:focus,
    .employee-edit-page .form-select:focus {
        border-color: rgba(13, 110, 253, 0.55);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.10);
    }

    .employee-edit-page .form-control::placeholder {
        color: #9ca3af;
    }

    .employee-edit-page .field-help {
        display: block;
        margin-top: 5px;
        color: #8a94a6;
        font-size: 0.76rem;
        line-height: 1.45;
    }

    .employee-edit-page .invalid-feedback {
        font-size: 0.78rem;
        margin-top: 5px;
    }

    .employee-edit-page .form-control.is-invalid,
    .employee-edit-page .form-select.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    .employee-edit-page .form-control.is-valid,
    .employee-edit-page .form-select.is-valid {
        border-color: #198754;
        background-image: none;
    }

    .employee-edit-page .live-error {
        display: none;
        color: #dc3545;
        font-size: 0.78rem;
        margin-top: 5px;
        line-height: 1.45;
    }

    .employee-edit-page .live-error.show {
        display: block;
    }

    .employee-edit-page .field-hint-invalid {
        color: #dc3545;
    }

    .employee-edit-page .field-hint-valid {
        color: #198754;
    }

    /* ---------------------------------------------------------------
       International Phone Input
    --------------------------------------------------------------- */

    .employee-edit-page .phone-wrapper {
        width: 100%;
    }

    .employee-edit-page .iti {
        width: 100%;
    }

    .employee-edit-page .iti__tel-input,
    .employee-edit-page .iti input[type="tel"] {
        width: 100%;
        min-height: 44px;
        border: 1px solid #d7dce2;
        border-radius: 9px;
        font-size: 0.9rem;
        color: #374151;
        padding-top: 9px;
        padding-bottom: 9px;
        transition: all 0.2s ease;
    }

    .employee-edit-page .iti input[type="tel"]:focus {
        border-color: rgba(13, 110, 253, 0.55);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.10);
        outline: none;
    }

    .employee-edit-page .iti input[type="tel"].is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    .employee-edit-page .iti input[type="tel"].is-valid {
        border-color: #198754;
        background-image: none;
    }

    .employee-edit-page .iti__selected-country {
        border-radius: 9px 0 0 9px;
    }

    .employee-edit-page .iti__dropdown-content {
        z-index: 9999;
    }

    /* ---------------------------------------------------------------
       Status Box
    --------------------------------------------------------------- */

    .employee-edit-page .status-box {
        background: #f8fafc;
        border: 1px solid var(--cpc-border);
        border-radius: 10px;
        padding: 14px 16px;
    }

    .employee-edit-page .status-box .form-check {
        margin: 0;
        min-height: auto;
    }

    .employee-edit-page .status-box .form-check-input {
        cursor: pointer;
        width: 1.05rem;
        height: 1.05rem;
        margin-top: 0.15rem;
    }

    .employee-edit-page .status-box .form-check-label {
        cursor: pointer;
        font-weight: 600;
        font-size: 0.88rem;
        color: #374151;
    }

    .employee-edit-page .status-description {
        margin-left: 28px;
        margin-top: 4px;
        font-size: 0.77rem;
        color: var(--cpc-muted);
    }

    /* ---------------------------------------------------------------
       Sections
    --------------------------------------------------------------- */

    .employee-edit-page .section-divider {
        height: 1px;
        background: var(--cpc-border);
        margin: 4px 0 24px;
    }

    .employee-edit-page .form-section {
        margin-bottom: 24px;
    }

    .employee-edit-page .form-section:last-child {
        margin-bottom: 0;
    }

    /* ---------------------------------------------------------------
       Actions
    --------------------------------------------------------------- */

    .employee-edit-page .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 18px 24px;
        background: #f8fafc;
        border-top: 1px solid var(--cpc-border);
        border-radius: 0 0 14px 14px;
    }

    .employee-edit-page .btn {
        border-radius: 8px;
        font-size: 0.86rem;
        font-weight: 600;
        padding: 9px 16px;
    }

    .employee-edit-page .btn-primary {
        box-shadow: 0 3px 8px rgba(13, 110, 253, 0.18);
    }

    .employee-edit-page .btn-light {
        background: #ffffff;
        border: 1px solid #d7dce2;
        color: #4b5563;
    }

    .employee-edit-page .btn-light:hover {
        background: #f3f4f6;
        border-color: #cfd4da;
    }

    /* ---------------------------------------------------------------
       Employee Summary
    --------------------------------------------------------------- */

    .employee-edit-page .employee-summary {
        background: linear-gradient(
            135deg,
            #f8fafc 0%,
            #eef5ff 100%
        );
        border: 1px solid #dbe7f7;
        border-radius: 12px;
        padding: 18px;
        height: 100%;
    }

    .employee-edit-page .summary-icon {
        width: 46px;
        height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(13, 110, 253, 0.10);
        color: var(--cpc-blue);
        font-size: 1.2rem;
        margin-bottom: 12px;
    }

    .employee-edit-page .summary-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--cpc-dark);
        margin-bottom: 3px;
    }

    .employee-edit-page .summary-epf {
        font-size: 0.78rem;
        color: var(--cpc-muted);
        margin-bottom: 14px;
    }

    .employee-edit-page .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 9px 0;
        border-top: 1px solid rgba(148, 163, 184, 0.25);
    }

    .employee-edit-page .summary-label {
        font-size: 0.76rem;
        color: var(--cpc-muted);
    }

    .employee-edit-page .summary-value {
        font-size: 0.78rem;
        font-weight: 600;
        color: #374151;
        text-align: right;
    }

    /* ---------------------------------------------------------------
       Alerts
    --------------------------------------------------------------- */

    .employee-edit-page .alert {
        border-radius: 10px;
        font-size: 0.85rem;
        margin-bottom: 20px;
    }

    /* ---------------------------------------------------------------
       Responsive
    --------------------------------------------------------------- */

    @media (max-width: 767.98px) {

        .employee-edit-page .page-header {
            align-items: flex-start !important;
            gap: 15px;
        }

        .employee-edit-page .header-actions {
            width: 100%;
        }

        .employee-edit-page .header-actions .btn {
            width: 100%;
        }

        .employee-edit-page .card-body {
            padding: 18px;
        }

        .employee-edit-page .form-actions {
            padding: 16px 18px;
            flex-direction: column-reverse;
        }

        .employee-edit-page .form-actions .btn {
            width: 100%;
        }

        .employee-edit-page .summary-row {
            align-items: flex-start;
        }
    }
</style>


<div class="employee-edit-page">

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a
                    href="{{ route('admin.employees.show', $employee) }}"
                    class="text-decoration-none text-secondary"
                    title="Back to Employee Profile"
                >
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h1 class="page-title">
                    Edit Employee
                </h1>

            </div>

            <p class="page-subtitle">
                Update employee information and organizational details.
            </p>

        </div>

        <div class="header-actions">

            <a
                href="{{ route('admin.employees.show', $employee) }}"
                class="btn btn-light"
            >
                <i class="bi bi-person-vcard me-1"></i>
                Employee Profile
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="d-flex align-items-start gap-2">

                <i class="bi bi-exclamation-triangle-fill mt-1"></i>

                <div>

                    <strong>
                        Please correct the following errors:
                    </strong>

                    <ul class="mb-0 mt-2 ps-3">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    <div class="row g-4">

        {{-- Main Form --}}
        <div class="col-lg-9">

            <form
                method="POST"
                action="{{ route('admin.employees.update', $employee) }}"
                id="employeeEditForm"
                novalidate
            >

                @csrf

                @method('PUT')


                <div class="card">

                    {{-- Employee Information --}}
                    <div class="card-header">

                        <h2 class="section-title">
                            <i class="bi bi-person-badge me-2 text-primary"></i>
                            Employee Information
                        </h2>

                        <p class="section-description">
                            Update the employee's identification and contact
                            information.
                        </p>

                    </div>


                    <div class="card-body">

                        <div class="form-section">

                            <div class="row g-3">

                                {{-- EPF Number --}}
                                <div class="col-md-6">

                                    <label
                                        for="epf_no"
                                        class="form-label"
                                    >
                                        EPF Number
                                        <span class="required-mark">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="epf_no"
                                        name="epf_no"
                                        value="{{ old('epf_no', $employee->epf_no) }}"
                                        class="form-control @error('epf_no') is-invalid @enderror"
                                        placeholder="e.g. 12345"
                                        maxlength="5"
                                        inputmode="numeric"
                                        autocomplete="off"
                                        required
                                    >

                                    <div
                                        id="epfLiveError"
                                        class="live-error"
                                    ></div>

                                    @error('epf_no')

                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>

                                    @else

                                        <span
                                            id="epfHelp"
                                            class="field-help"
                                        >
                                            Exactly 5 numeric digits.
                                            Sequential and repeated-digit
                                            numbers are not allowed.
                                        </span>

                                    @enderror

                                </div>


                                {{-- Employee Name --}}
                                <div class="col-md-6">

                                    <label
                                        for="name"
                                        class="form-label"
                                    >
                                        Employee Name
                                        <span class="required-mark">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $employee->name) }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter employee full name"
                                        maxlength="255"
                                        required
                                    >

                                    @error('name')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- Designation --}}
                                <div class="col-md-6">

                                    <label
                                        for="designation"
                                        class="form-label"
                                    >
                                        Designation
                                    </label>

                                    <input
                                        type="text"
                                        id="designation"
                                        name="designation"
                                        value="{{ old('designation', $employee->designation) }}"
                                        class="form-control @error('designation') is-invalid @enderror"
                                        placeholder="e.g. IT Executive"
                                        maxlength="255"
                                    >

                                    @error('designation')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- Email --}}
                                <div class="col-md-6">

                                    <label
                                        for="email"
                                        class="form-label"
                                    >
                                        Official Email
                                    </label>

                                    <input
                                        type="text"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $employee->email) }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="employee@cpc.lk"
                                        maxlength="255"
                                        autocomplete="off"
                                    >

                                    <div
                                        id="emailLiveError"
                                        class="live-error"
                                    ></div>

                                    @error('email')

                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- Contact Number --}}
                                <div class="col-md-6">

                                    <label
                                        for="phone_number"
                                        class="form-label"
                                    >
                                        Contact Number
                                    </label>

                                    <div class="phone-wrapper">

                                        <input
                                            type="tel"
                                            id="phone_number"
                                            name="phone_number"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            autocomplete="tel"
                                            aria-describedby="phoneHelp phoneLiveError"
                                        >

                                    </div>

                                    {{-- Hidden E.164 value sent to Laravel --}}
                                    <input
                                        type="hidden"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', $employee->phone) }}"
                                    >

                                    <div
                                        id="phoneLiveError"
                                        class="live-error"
                                    ></div>

                                    @error('phone')

                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    <span
                                        id="phoneHelp"
                                        class="field-help"
                                    >
                                        Select the country code and enter a
                                        valid phone number.
                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="section-divider"></div>


                        {{-- Organizational Assignment --}}
                        <div class="form-section">

                            <div class="mb-3">

                                <h2 class="section-title">
                                    <i class="bi bi-diagram-3 me-2 text-primary"></i>
                                    Organizational Assignment
                                </h2>

                                <p class="section-description">
                                    Update the employee's current
                                    organizational placement.
                                </p>

                            </div>


                            <div class="row g-3">

                                {{-- Branch --}}
                                <div class="col-md-4">

                                    <label
                                        for="branch_id"
                                        class="form-label"
                                    >
                                        Branch
                                        <span class="required-mark">*</span>
                                    </label>

                                    <select
                                        id="branch_id"
                                        name="branch_id"
                                        class="form-select @error('branch_id') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Select Branch
                                        </option>

                                        @foreach ($branches as $branch)

                                            <option
                                                value="{{ $branch->id }}"
                                                @selected(old('branch_id', $employee->branch_id) == $branch->id)
                                            >
                                                {{ $branch->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                    <div
                                        id="branchLiveError"
                                        class="live-error"
                                    ></div>

                                    @error('branch_id')

                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- Department --}}
                                <div class="col-md-4">

                                    <label
                                        for="department_id"
                                        class="form-label"
                                    >
                                        Department
                                        <span class="required-mark">*</span>
                                    </label>

                                    <select
                                        id="department_id"
                                        name="department_id"
                                        class="form-select @error('department_id') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Select Department
                                        </option>

                                        @foreach ($departments as $department)

                                            <option
                                                value="{{ $department->id }}"
                                                data-branch-id="{{ $department->branch_id }}"
                                                @selected(old('department_id', $employee->department_id) == $department->id)
                                            >
                                                {{ $department->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                    <div
                                        id="departmentLiveError"
                                        class="live-error"
                                    ></div>

                                    @error('department_id')

                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- Function Unit --}}
                                <div class="col-md-4">

                                    <label
                                        for="function_unit_id"
                                        class="form-label"
                                    >
                                        Function Unit
                                    </label>

                                    <select
                                        id="function_unit_id"
                                        name="function_unit_id"
                                        class="form-select @error('function_unit_id') is-invalid @enderror"
                                    >

                                        <option value="">
                                            Select Function Unit
                                        </option>

                                        @foreach ($functionUnits as $functionUnit)

                                            <option
                                                value="{{ $functionUnit->id }}"
                                                data-department-id="{{ $functionUnit->department_id }}"
                                                @selected(old('function_unit_id', $employee->function_unit_id) == $functionUnit->id)
                                            >
                                                {{ $functionUnit->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('function_unit_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>


                        <div class="section-divider"></div>


                        {{-- Employment Status --}}
                        <div class="form-section">

                            <div class="mb-3">

                                <h2 class="section-title">
                                    <i class="bi bi-toggle-on me-2 text-primary"></i>
                                    Employment Status
                                </h2>

                                <p class="section-description">
                                    Maintain the employee's current system
                                    status.
                                </p>

                            </div>


                            <div class="status-box">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="is_active"
                                        id="is_active"
                                        value="1"
                                        @checked(old('is_active', $employee->is_active))
                                    >

                                    <label
                                        class="form-check-label"
                                        for="is_active"
                                    >
                                        Active Employee
                                    </label>

                                </div>

                                <div class="status-description">
                                    Uncheck this option to deactivate the
                                    employee. Existing inventory assignment
                                    history will remain preserved.
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Form Actions --}}
                    <div class="form-actions">

                        <a
                            href="{{ route('admin.employees.show', $employee) }}"
                            class="btn btn-light"
                        >
                            <i class="bi bi-x-lg me-1"></i>
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="updateEmployeeButton"
                        >
                            <i class="bi bi-check2-circle me-1"></i>
                            Update Employee
                        </button>

                    </div>

                </div>

            </form>

        </div>


        {{-- Employee Summary --}}
        <div class="col-lg-3">

            <div class="employee-summary">

                <div class="summary-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>

                <div class="summary-name">
                    {{ $employee->name }}
                </div>

                <div class="summary-epf">
                    EPF No: {{ $employee->epf_no }}
                </div>


                <div class="summary-row">

                    <span class="summary-label">
                        Status
                    </span>

                    <span class="summary-value">

                        @if ($employee->is_active)

                            <span class="badge bg-success-subtle text-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary-subtle text-secondary">
                                Inactive
                            </span>

                        @endif

                    </span>

                </div>


                <div class="summary-row">

                    <span class="summary-label">
                        Branch
                    </span>

                    <span class="summary-value">
                        {{ $employee->branch?->name ?? '—' }}
                    </span>

                </div>


                <div class="summary-row">

                    <span class="summary-label">
                        Department
                    </span>

                    <span class="summary-value">
                        {{ $employee->department?->name ?? '—' }}
                    </span>

                </div>


                <div class="summary-row">

                    <span class="summary-label">
                        Function Unit
                    </span>

                    <span class="summary-value">
                        {{ $employee->functionUnit?->name ?? '—' }}
                    </span>

                </div>


                <div class="summary-row">

                    <span class="summary-label">
                        Assigned Assets
                    </span>

                    <span class="summary-value">
                        {{ $employee->itemAssignments->where('assignment_status', 'issued')->count() }}
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Form Elements
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById('employeeEditForm');

    const epfInput =
        document.getElementById('epf_no');

    const epfLiveError =
        document.getElementById('epfLiveError');

    const epfHelp =
        document.getElementById('epfHelp');

    const emailInput =
        document.getElementById('email');

    const emailLiveError =
        document.getElementById('emailLiveError');

    const phoneNumberInput =
        document.getElementById('phone_number');

    const phoneInput =
        document.getElementById('phone');

    const phoneLiveError =
        document.getElementById('phoneLiveError');

    const phoneHelp =
        document.getElementById('phoneHelp');

    const branchSelect =
        document.getElementById('branch_id');

    const departmentSelect =
        document.getElementById('department_id');

    const functionUnitSelect =
        document.getElementById('function_unit_id');

    const branchLiveError =
        document.getElementById('branchLiveError');

    const departmentLiveError =
        document.getElementById('departmentLiveError');


    /*
    |--------------------------------------------------------------------------
    | Original Department Options
    |--------------------------------------------------------------------------
    */

    const originalDepartmentOptions =
        Array.from(
            departmentSelect.options
        ).map(function (option) {

            return {
                value: option.value,
                text: option.text,
                branchId:
                    option.dataset.branchId || ''
            };

        });


    /*
    |--------------------------------------------------------------------------
    | Original Function Unit Options
    |--------------------------------------------------------------------------
    */

    const originalFunctionUnitOptions =
        Array.from(
            functionUnitSelect.options
        ).map(function (option) {

            return {
                value: option.value,
                text: option.text,
                departmentId:
                    option.dataset.departmentId || ''
            };

        });


    /*
    |--------------------------------------------------------------------------
    | EPF Validation
    |--------------------------------------------------------------------------
    */

    function isRepeatedDigit(value) {

        if (value.length !== 5) {
            return false;
        }

        return /^(\d)\1{4}$/.test(value);
    }


    function isSequentialDigits(value) {

        if (value.length < 4) {
            return false;
        }

        for (
            let i = 0;
            i <= value.length - 4;
            i++
        ) {

            const part =
                value.substring(i, i + 4);

            let ascending = true;
            let descending = true;

            for (
                let j = 1;
                j < part.length;
                j++
            ) {

                const previous =
                    Number(part[j - 1]);

                const current =
                    Number(part[j]);

                if (current !== previous + 1) {
                    ascending = false;
                }

                if (current !== previous - 1) {
                    descending = false;
                }

            }

            if (ascending || descending) {
                return true;
            }
        }

        return false;
    }


    function validateEpf() {

        let value =
            epfInput.value.replace(
                /[^0-9]/g,
                ''
            );

        if (value.length > 5) {

            value =
                value.substring(0, 5);

        }

        epfInput.value = value;


        if (value.length === 0) {

            epfInput.classList.remove(
                'is-invalid',
                'is-valid'
            );

            epfLiveError.classList.remove(
                'show'
            );

            epfLiveError.textContent = '';

            if (epfHelp) {

                epfHelp.classList.remove(
                    'field-hint-invalid',
                    'field-hint-valid'
                );

            }

            return false;
        }


        if (!/^[0-9]{5}$/.test(value)) {

            epfInput.classList.remove(
                'is-valid'
            );

            epfInput.classList.add(
                'is-invalid'
            );

            epfLiveError.textContent =
                'EPF number must contain exactly 5 numeric digits.';

            epfLiveError.classList.add(
                'show'
            );

            return false;
        }


        if (isRepeatedDigit(value)) {

            epfInput.classList.remove(
                'is-valid'
            );

            epfInput.classList.add(
                'is-invalid'
            );

            epfLiveError.textContent =
                'Repeated-digit EPF numbers such as 11111 or 77777 are not allowed.';

            epfLiveError.classList.add(
                'show'
            );

            return false;
        }


        if (isSequentialDigits(value)) {

            epfInput.classList.remove(
                'is-valid'
            );

            epfInput.classList.add(
                'is-invalid'
            );

            epfLiveError.textContent =
                'Sequential EPF numbers such as 12345, 23456 or 54321 are not allowed.';

            epfLiveError.classList.add(
                'show'
            );

            return false;
        }


        epfInput.classList.remove(
            'is-invalid'
        );

        epfInput.classList.add(
            'is-valid'
        );

        epfLiveError.classList.remove(
            'show'
        );

        epfLiveError.textContent = '';

        if (epfHelp) {

            epfHelp.classList.remove(
                'field-hint-invalid'
            );

            epfHelp.classList.add(
                'field-hint-valid'
            );

        }

        return true;
    }


    epfInput.addEventListener(
        'input',
        validateEpf
    );

    epfInput.addEventListener(
        'blur',
        validateEpf
    );


    /*
    |--------------------------------------------------------------------------
    | Email Validation
    |--------------------------------------------------------------------------
    */

    function validateEmail() {

        const value =
            emailInput.value.trim();


        if (value === '') {

            emailInput.classList.remove(
                'is-invalid',
                'is-valid'
            );

            emailLiveError.classList.remove(
                'show'
            );

            emailLiveError.textContent = '';

            return true;
        }


        if (!value.includes('@')) {

            emailInput.classList.remove(
                'is-valid'
            );

            emailInput.classList.add(
                'is-invalid'
            );

            emailLiveError.textContent =
                'Please enter a valid email address containing @.';

            emailLiveError.classList.add(
                'show'
            );

            return false;
        }


        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


        if (!emailPattern.test(value)) {

            emailInput.classList.remove(
                'is-valid'
            );

            emailInput.classList.add(
                'is-invalid'
            );

            emailLiveError.textContent =
                'Please enter a valid email address.';

            emailLiveError.classList.add(
                'show'
            );

            return false;
        }


        emailInput.classList.remove(
            'is-invalid'
        );

        emailInput.classList.add(
            'is-valid'
        );

        emailLiveError.classList.remove(
            'show'
        );

        emailLiveError.textContent = '';

        return true;
    }


    emailInput.addEventListener(
        'input',
        validateEmail
    );

    emailInput.addEventListener(
        'blur',
        validateEmail
    );


    /*
    |--------------------------------------------------------------------------
    | International Phone Input
    |--------------------------------------------------------------------------
    */

    const iti =
        window.intlTelInput(
            phoneNumberInput,
            {
                initialCountry: 'lk',

                separateDialCode: true,

                countrySearch: true,

                countrySelectorMode: 'AUTO',

                showFlags: true,

                strictMode: true,

                formatAsYouType: true,

                placeholderNumberType: 'MOBILE',

                allowedNumberTypes: [
                    'MOBILE',
                    'FIXED_LINE'
                ],

                loadUtils: function () {

                    return import(
                        'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js'
                    );

                }
            }
        );


    /*
    |--------------------------------------------------------------------------
    | Existing Phone
    |--------------------------------------------------------------------------
    */

    const existingPhone =
        @json(old('phone', $employee->phone));


    /*
    |--------------------------------------------------------------------------
    | Restore Existing Phone
    |--------------------------------------------------------------------------
    */

    async function restorePhoneNumber() {

        if (
            existingPhone === null ||
            existingPhone === undefined ||
            String(existingPhone).trim() === ''
        ) {
            return;
        }


        const phoneValue =
            String(existingPhone).trim();


        try {

            await iti.promise;


            /*
            | Existing E.164 number
            |
            | Example:
            | +94712345678
            */

            if (
                phoneValue.startsWith('+')
            ) {

                iti.setNumber(
                    phoneValue
                );

            }


            /*
            | Backward compatibility
            |
            | Example:
            | 0712345678
            */

            else if (
                /^0[0-9]{9}$/.test(
                    phoneValue
                )
            ) {

                iti.setNumber(
                    '+94' +
                    phoneValue.substring(1)
                );

            }


            /*
            | Unknown old format
            */

            else {

                phoneNumberInput.value =
                    phoneValue;

            }

        } catch (error) {

            console.warn(
                'Unable to restore existing phone number.',
                error
            );

            phoneNumberInput.value =
                phoneValue;

        }

    }


    restorePhoneNumber();


    /*
    |--------------------------------------------------------------------------
    | Phone Validation
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | This function is NOT called during typing.
    |
    | Therefore:
    |
    | 0
    | 07
    | 076
    |
    | will NOT immediately display an error.
    |
    | Validation happens on:
    |
    | 1. Blur
    | 2. Form submit
    |
    */

    async function validatePhone() {

        const rawValue =
            phoneNumberInput.value.trim();


        /*
        |--------------------------------------------------------------------------
        | Empty Phone
        |--------------------------------------------------------------------------
        |
        | Phone number is optional.
        |
        */

        if (rawValue === '') {

            phoneInput.value = '';

            phoneNumberInput.classList.remove(
                'is-invalid',
                'is-valid'
            );

            phoneLiveError.classList.remove(
                'show'
            );

            phoneLiveError.textContent = '';

            if (phoneHelp) {

                phoneHelp.classList.remove(
                    'field-hint-invalid',
                    'field-hint-valid'
                );

            }

            return true;
        }


        try {

            await iti.promise;


            /*
            |--------------------------------------------------------------------------
            | Get Selected Country
            |--------------------------------------------------------------------------
            */

            const countryData =
                iti.getSelectedCountryData();

            const countryCode =
                countryData &&
                countryData.iso2
                    ? countryData.iso2.toLowerCase()
                    : '';


            /*
            |--------------------------------------------------------------------------
            | Sri Lankan Phone Validation
            |--------------------------------------------------------------------------
            |
            | Sri Lankan mobile numbers:
            |
            | +94 712345678
            |
            | National part:
            | 712345678
            |
            | Exactly 9 digits
            | First digit must be 7
            |
            */

            if (countryCode === 'lk') {

                const digits =
                    rawValue.replace(
                        /\D/g,
                        ''
                    );


                if (
                    !/^7[0-9]{8}$/.test(
                        digits
                    )
                ) {

                    phoneInput.value = '';

                    phoneNumberInput.classList.remove(
                        'is-valid'
                    );

                    phoneNumberInput.classList.add(
                        'is-invalid'
                    );


                    phoneLiveError.textContent =
                        'Sri Lankan mobile numbers should start with 7 after +94. Example: 712345678. Select the country code and enter exactly 9 digits. Example: +94 712345678.';


                    phoneLiveError.classList.add(
                        'show'
                    );


                    if (phoneHelp) {

                        phoneHelp.classList.remove(
                            'field-hint-valid'
                        );

                        phoneHelp.classList.add(
                            'field-hint-invalid'
                        );

                    }

                    return false;
                }

            }


            /*
            |--------------------------------------------------------------------------
            | General International Validation
            |--------------------------------------------------------------------------
            */

            const isValid =
                iti.isValidNumber();


            if (!isValid) {

                phoneInput.value = '';

                phoneNumberInput.classList.remove(
                    'is-valid'
                );

                phoneNumberInput.classList.add(
                    'is-invalid'
                );


                phoneLiveError.textContent =
                    'Please enter a valid contact number for the selected country.';


                phoneLiveError.classList.add(
                    'show'
                );


                if (phoneHelp) {

                    phoneHelp.classList.remove(
                        'field-hint-valid'
                    );

                    phoneHelp.classList.add(
                        'field-hint-invalid'
                    );

                }

                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | Get E.164 Number
            |--------------------------------------------------------------------------
            */

            const internationalNumber =
                iti.getNumber();


            if (
                !internationalNumber ||
                !internationalNumber.startsWith('+')
            ) {

                phoneInput.value = '';

                phoneNumberInput.classList.remove(
                    'is-valid'
                );

                phoneNumberInput.classList.add(
                    'is-invalid'
                );


                phoneLiveError.textContent =
                    'Please enter a valid contact number for the selected country.';


                phoneLiveError.classList.add(
                    'show'
                );

                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | Save E.164 Number
            |--------------------------------------------------------------------------
            */

            phoneInput.value =
                internationalNumber;


            /*
            |--------------------------------------------------------------------------
            | Valid State
            |--------------------------------------------------------------------------
            */

            phoneNumberInput.classList.remove(
                'is-invalid'
            );

            phoneNumberInput.classList.add(
                'is-valid'
            );

            phoneLiveError.classList.remove(
                'show'
            );

            phoneLiveError.textContent = '';


            if (phoneHelp) {

                phoneHelp.classList.remove(
                    'field-hint-invalid'
                );

                phoneHelp.classList.add(
                    'field-hint-valid'
                );

            }

            return true;

        } catch (error) {

            console.error(
                'Phone validation error:',
                error
            );


            phoneInput.value = '';

            phoneNumberInput.classList.remove(
                'is-valid'
            );

            phoneNumberInput.classList.add(
                'is-invalid'
            );


            phoneLiveError.textContent =
                'Unable to validate the contact number. Please check the number and selected country.';


            phoneLiveError.classList.add(
                'show'
            );


            return false;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Phone Input Event
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | We DO NOT call validatePhone() here.
    |
    | This prevents an error appearing while typing:
    |
    | 0
    | 07
    | 076
    |
    */

    phoneNumberInput.addEventListener(
        'input',
        function () {

            phoneNumberInput.classList.remove(
                'is-invalid',
                'is-valid'
            );


            phoneLiveError.classList.remove(
                'show'
            );

            phoneLiveError.textContent = '';


            if (phoneHelp) {

                phoneHelp.classList.remove(
                    'field-hint-invalid',
                    'field-hint-valid'
                );

            }


            /*
            | User is editing the number.
            | Therefore the previous E.164
            | value must not be submitted.
            */

            phoneInput.value = '';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Phone Blur
    |--------------------------------------------------------------------------
    */

    phoneNumberInput.addEventListener(
        'blur',
        function () {

            /*
            | Small delay allows intl-tel-input
            | country/input events to finish first.
            */

            setTimeout(
                function () {

                    validatePhone();

                },
                100
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Country Changed
    |--------------------------------------------------------------------------
    */

    phoneNumberInput.addEventListener(
        'countrychange',
        function () {

            /*
            | Do not validate automatically when
            | changing country.
            */

            phoneInput.value = '';


            phoneNumberInput.classList.remove(
                'is-invalid',
                'is-valid'
            );


            phoneLiveError.classList.remove(
                'show'
            );

            phoneLiveError.textContent = '';


            if (phoneHelp) {

                phoneHelp.classList.remove(
                    'field-hint-invalid',
                    'field-hint-valid'
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Branch -> Department
    |--------------------------------------------------------------------------
    */

    function populateDepartments(
        selectedDepartmentId = ''
    ) {

        const selectedBranchId =
            branchSelect.value;


        departmentSelect.innerHTML = '';


        const defaultOption =
            document.createElement(
                'option'
            );

        defaultOption.value = '';


        defaultOption.textContent =
            selectedBranchId
                ? 'Select Department'
                : 'Select Branch First';


        departmentSelect.appendChild(
            defaultOption
        );


        if (!selectedBranchId) {

            departmentSelect.disabled = true;

            populateFunctionUnits('');

            return;
        }


        departmentSelect.disabled = false;


        originalDepartmentOptions.forEach(
            function (item) {

                if (!item.value) {
                    return;
                }


                if (
                    String(item.branchId) ===
                    String(selectedBranchId)
                ) {

                    const option =
                        document.createElement(
                            'option'
                        );

                    option.value =
                        item.value;

                    option.textContent =
                        item.text;


                    if (
                        selectedDepartmentId &&
                        String(
                            selectedDepartmentId
                        ) ===
                        String(item.value)
                    ) {

                        option.selected = true;

                    }


                    departmentSelect.appendChild(
                        option
                    );

                }

            }
        );


        populateFunctionUnits(
            departmentSelect.value
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Department -> Function Unit
    |--------------------------------------------------------------------------
    */

    function populateFunctionUnits(
        selectedFunctionUnitId = ''
    ) {

        const selectedDepartmentId =
            departmentSelect.value;


        functionUnitSelect.innerHTML = '';


        const defaultOption =
            document.createElement(
                'option'
            );

        defaultOption.value = '';


        defaultOption.textContent =
            selectedDepartmentId
                ? 'Select Function Unit'
                : 'Select Department First';


        functionUnitSelect.appendChild(
            defaultOption
        );


        functionUnitSelect.disabled =
            !selectedDepartmentId;


        if (!selectedDepartmentId) {
            return;
        }


        originalFunctionUnitOptions.forEach(
            function (item) {

                if (!item.value) {
                    return;
                }


                if (
                    String(item.departmentId) ===
                    String(selectedDepartmentId)
                ) {

                    const option =
                        document.createElement(
                            'option'
                        );

                    option.value =
                        item.value;

                    option.textContent =
                        item.text;


                    if (
                        selectedFunctionUnitId &&
                        String(
                            selectedFunctionUnitId
                        ) ===
                        String(item.value)
                    ) {

                        option.selected = true;

                    }


                    functionUnitSelect.appendChild(
                        option
                    );

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Branch Change
    |--------------------------------------------------------------------------
    */

    branchSelect.addEventListener(
        'change',
        function () {

            branchSelect.classList.remove(
                'is-invalid'
            );


            if (branchLiveError) {

                branchLiveError.classList.remove(
                    'show'
                );

                branchLiveError.textContent = '';

            }


            populateDepartments('');


            departmentSelect.classList.remove(
                'is-invalid'
            );


            if (departmentLiveError) {

                departmentLiveError.classList.remove(
                    'show'
                );

                departmentLiveError.textContent = '';

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Department Change
    |--------------------------------------------------------------------------
    */

    departmentSelect.addEventListener(
        'change',
        function () {

            departmentSelect.classList.remove(
                'is-invalid'
            );


            if (departmentLiveError) {

                departmentLiveError.classList.remove(
                    'show'
                );

                departmentLiveError.textContent = '';

            }


            populateFunctionUnits('');

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Restore Existing Organizational Values
    |--------------------------------------------------------------------------
    */

    const initialBranchId =
        @json(
            old(
                'branch_id',
                $employee->branch_id
            )
        );


    const initialDepartmentId =
        @json(
            old(
                'department_id',
                $employee->department_id
            )
        );


    const initialFunctionUnitId =
        @json(
            old(
                'function_unit_id',
                $employee->function_unit_id
            )
        );


    if (initialBranchId) {

        branchSelect.value =
            String(initialBranchId);


        populateDepartments(
            initialDepartmentId
        );


        if (initialDepartmentId) {

            departmentSelect.value =
                String(initialDepartmentId);


            populateFunctionUnits(
                initialFunctionUnitId
            );

        }

    } else {

        departmentSelect.disabled = true;

        functionUnitSelect.disabled = true;

    }


    /*
    |--------------------------------------------------------------------------
    | Initial EPF Validation
    |--------------------------------------------------------------------------
    */

    if (epfInput.value !== '') {
        validateEpf();
    }


    /*
    |--------------------------------------------------------------------------
    | Initial Email Validation
    |--------------------------------------------------------------------------
    */

    if (emailInput.value !== '') {
        validateEmail();
    }


    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        async function (event) {

            event.preventDefault();


            /*
            | Validate fields on submit.
            */

            const epfValid =
                validateEpf();


            const emailValid =
                validateEmail();


            const phoneValid =
                await validatePhone();


            let branchValid = true;

            let departmentValid = true;


            /*
            |--------------------------------------------------------------------------
            | Branch Validation
            |--------------------------------------------------------------------------
            */

            if (!branchSelect.value) {

                branchValid = false;


                branchSelect.classList.add(
                    'is-invalid'
                );


                if (branchLiveError) {

                    branchLiveError.textContent =
                        'Please select a branch.';

                    branchLiveError.classList.add(
                        'show'
                    );

                }

            } else {

                branchSelect.classList.remove(
                    'is-invalid'
                );


                if (branchLiveError) {

                    branchLiveError.classList.remove(
                        'show'
                    );

                    branchLiveError.textContent = '';

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Department Validation
            |--------------------------------------------------------------------------
            */

            if (!departmentSelect.value) {

                departmentValid = false;


                departmentSelect.classList.add(
                    'is-invalid'
                );


                if (departmentLiveError) {

                    departmentLiveError.textContent =
                        'Please select a department.';

                    departmentLiveError.classList.add(
                        'show'
                    );

                }

            } else {

                departmentSelect.classList.remove(
                    'is-invalid'
                );


                if (departmentLiveError) {

                    departmentLiveError.classList.remove(
                        'show'
                    );

                    departmentLiveError.textContent = '';

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Stop if Invalid
            |--------------------------------------------------------------------------
            */

            if (
                !epfValid ||
                !emailValid ||
                !phoneValid ||
                !branchValid ||
                !departmentValid
            ) {

                const firstInvalid =
                    form.querySelector(
                        '.is-invalid'
                    );


                if (firstInvalid) {

                    firstInvalid.focus();

                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Final E.164 Phone Value
            |--------------------------------------------------------------------------
            */

            if (
                phoneNumberInput.value.trim() !== ''
            ) {

                phoneInput.value =
                    iti.getNumber();

            } else {

                phoneInput.value = '';

            }


            /*
            |--------------------------------------------------------------------------
            | Submit Form
            |--------------------------------------------------------------------------
            */

            form.submit();

        }
    );

});
</script>

@endsection