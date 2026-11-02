
@extends('layouts.admin')

@section('title', 'Register Employee | CPC Inventory Management System')

@section('content')

<style>
    .employee-create-page {
        --cpc-blue: #0d6efd;
        --cpc-dark: #1f2937;
        --cpc-muted: #6b7280;
        --cpc-border: #e5e7eb;
    }

    .employee-create-page .page-header { margin-bottom: 24px; }
    .employee-create-page .page-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--cpc-dark);
        margin-bottom: 4px;
    }
    .employee-create-page .page-subtitle {
        color: var(--cpc-muted);
        font-size: .92rem;
        margin-bottom: 0;
    }
    .employee-create-page .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .employee-create-page .card {
        border: 1px solid var(--cpc-border);
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, .05);
        overflow: hidden;
    }
    .employee-create-page .card-header {
        background: #fff;
        border-bottom: 1px solid var(--cpc-border);
        padding: 18px 22px;
    }
    .employee-create-page .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--cpc-dark);
        margin: 0;
    }
    .employee-create-page .section-description {
        font-size: .82rem;
        color: var(--cpc-muted);
        margin: 4px 0 0;
    }
    .employee-create-page .card-body { padding: 24px; }
    .employee-create-page .form-label {
        font-size: .84rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }
    .employee-create-page .required-mark {
        color: #dc3545;
        margin-left: 2px;
    }
    .employee-create-page .form-control,
    .employee-create-page .form-select {
        min-height: 44px;
        border: 1px solid #d7dce2;
        border-radius: 9px;
        font-size: .9rem;
        color: #374151;
        padding: 9px 12px;
        transition: all .2s ease;
    }
    .employee-create-page .form-control:focus,
    .employee-create-page .form-select:focus {
        border-color: rgba(13, 110, 253, .55);
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .10);
    }
    .employee-create-page .form-control::placeholder { color: #9ca3af; }
    .employee-create-page .field-help {
        display: block;
        margin-top: 5px;
        color: #8a94a6;
        font-size: .76rem;
    }
    .employee-create-page .invalid-feedback {
        font-size: .78rem;
        margin-top: 5px;
    }
    .employee-create-page .form-control.is-invalid,
    .employee-create-page .form-select.is-invalid {
        border-color: #dc3545;
        padding-right: 2.5rem;
        background-image: none;
    }
    .employee-create-page .form-control.is-valid,
    .employee-create-page .form-select.is-valid {
        border-color: #198754;
        padding-right: 2.5rem;
        background-image: none;
    }
    .employee-create-page .status-box {
        background: #f8fafc;
        border: 1px solid var(--cpc-border);
        border-radius: 10px;
        padding: 14px 16px;
    }
    .employee-create-page .status-box .form-check {
        margin: 0;
        min-height: auto;
    }
    .employee-create-page .status-box .form-check-input {
        cursor: pointer;
        width: 1.05rem;
        height: 1.05rem;
        margin-top: .15rem;
    }
    .employee-create-page .status-box .form-check-label {
        cursor: pointer;
        font-weight: 600;
        font-size: .88rem;
        color: #374151;
    }
    .employee-create-page .status-description {
        margin-left: 28px;
        margin-top: 4px;
        font-size: .77rem;
        color: var(--cpc-muted);
    }
    .employee-create-page .form-section { margin-bottom: 24px; }
    .employee-create-page .form-section:last-child { margin-bottom: 0; }
    .employee-create-page .section-divider {
        height: 1px;
        background: var(--cpc-border);
        margin: 4px 0 24px;
    }
    .employee-create-page .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 18px 24px;
        background: #f8fafc;
        border-top: 1px solid var(--cpc-border);
    }
    .employee-create-page .btn {
        border-radius: 8px;
        font-size: .86rem;
        font-weight: 600;
        padding: 9px 16px;
    }
    .employee-create-page .btn-primary {
        box-shadow: 0 3px 8px rgba(13, 110, 253, .18);
    }
    .employee-create-page .btn-light {
        background: #fff;
        border: 1px solid #d7dce2;
        color: #4b5563;
    }
    .employee-create-page .btn-light:hover {
        background: #f3f4f6;
        border-color: #cfd4da;
    }
    .employee-create-page .info-card {
        background: linear-gradient(135deg, #f8fafc 0%, #eef5ff 100%);
        border: 1px solid #dbe7f7;
        border-radius: 12px;
        padding: 18px;
        height: 100%;
    }
    .employee-create-page .info-icon {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(13, 110, 253, .10);
        color: var(--cpc-blue);
        font-size: 1.1rem;
        margin-bottom: 12px;
    }
    .employee-create-page .info-title {
        font-size: .9rem;
        font-weight: 700;
        color: var(--cpc-dark);
        margin-bottom: 6px;
    }
    .employee-create-page .info-text {
        font-size: .78rem;
        line-height: 1.6;
        color: var(--cpc-muted);
        margin: 0;
    }
    .employee-create-page .alert {
        border-radius: 10px;
        font-size: .85rem;
        margin-bottom: 20px;
    }
    .employee-create-page .live-error {
        display: none;
        color: #dc3545;
        font-size: .78rem;
        margin-top: 5px;
        line-height: 1.45;
    }
    .employee-create-page .live-error.show { display: block; }
    .employee-create-page .field-hint-invalid { color: #dc3545; }
    .employee-create-page .field-hint-valid { color: #198754; }

    .employee-create-page .phone-input-group {
        display: flex;
        align-items: stretch;
        width: 100%;
    }
    .employee-create-page .phone-country-code {
        flex: 0 0 145px;
        max-width: 145px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        background-color: #f8fafc;
        cursor: pointer;
    }
    .employee-create-page .phone-number-input {
        flex: 1 1 auto;
        min-width: 0;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    .employee-create-page .phone-country-code:focus,
    .employee-create-page .phone-number-input:focus {
        position: relative;
        z-index: 2;
    }

    @media (max-width: 767.98px) {
        .employee-create-page .page-header {
            align-items: flex-start !important;
            gap: 15px;
        }
        .employee-create-page .header-actions { width: 100%; }
        .employee-create-page .header-actions .btn { width: 100%; }
        .employee-create-page .card-body { padding: 18px; }
        .employee-create-page .form-actions {
            padding: 16px 18px;
            flex-direction: column-reverse;
        }
        .employee-create-page .form-actions .btn { width: 100%; }
        .employee-create-page .phone-country-code {
            flex: 0 0 135px;
            max-width: 135px;
        }
    }
</style>

<div class="employee-create-page">

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.employees.index') }}"
                   class="text-decoration-none text-secondary"
                   title="Back to Employee Directory">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="page-title">Register Employee</h1>
            </div>
            <p class="page-subtitle">
                Add a new employee to the CPC Inventory Management System.
            </p>
        </div>

        <div class="header-actions">
            <a href="{{ route('admin.employees.index') }}" class="btn btn-light">
                <i class="bi bi-people me-1"></i>
                Employee Directory
            </a>
        </div>
    </div>

    {{-- Server-side Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">

        {{-- Main Form --}}
        <div class="col-lg-9">
            <form method="POST"
                  action="{{ route('admin.employees.store') }}"
                  id="employeeCreateForm"
                  novalidate>
                @csrf

                <div class="card">

                    {{-- Employee Information --}}
                    <div class="card-header">
                        <h2 class="section-title">
                            <i class="bi bi-person-badge me-2 text-primary"></i>
                            Employee Information
                        </h2>
                        <p class="section-description">
                            Enter the employee's basic identification and employment details.
                        </p>
                    </div>

                    <div class="card-body">

                        <div class="form-section">
                            <div class="row g-3">

                                {{-- EPF Number --}}
                                <div class="col-md-6">
                                    <label for="epf_no" class="form-label">
                                        EPF Number <span class="required-mark">*</span>
                                    </label>
                                    <input type="text"
                                           id="epf_no"
                                           name="epf_no"
                                           value="{{ old('epf_no') }}"
                                           class="form-control @error('epf_no') is-invalid @enderror"
                                           placeholder="e.g. 12345"
                                           maxlength="5"
                                           inputmode="numeric"
                                           autocomplete="off"
                                           required>

                                    <div id="epfLiveError" class="live-error"></div>

                                    @error('epf_no')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @else
                                        <span id="epfHelp" class="field-help">
                                            Exactly 5 numeric digits. Sequential and repeated-digit numbers are not allowed.
                                        </span>
                                    @enderror
                                </div>

                                {{-- Employee Name --}}
                                <div class="col-md-6">
                                    <label for="name" class="form-label">
                                        Employee Name <span class="required-mark">*</span>
                                    </label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Employee name from CPC HAPIS"
                                           maxlength="255"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <span class="field-help">
                                        Automatically filled when a matching CPC master record is found.
                                    </span>
                                </div>

                                {{-- Designation --}}
                                <div class="col-md-6">
                                    <label for="designation" class="form-label">Designation</label>
                                    <input type="text"
                                           id="designation"
                                           name="designation"
                                           value="{{ old('designation') }}"
                                           class="form-control @error('designation') is-invalid @enderror"
                                           placeholder="Designation from CPC HAPIS"
                                           maxlength="255">
                                    @error('designation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Official Email</label>
                                    <input type="text"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="employee@cpc.lk"
                                           maxlength="255"
                                           autocomplete="off">

                                    <div id="emailLiveError" class="live-error"></div>

                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Contact Number --}}
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Contact Number</label>

                                    <div class="phone-input-group">
                                        <select id="phone_country_code"
                                                name="phone_country_code"
                                                class="form-select phone-country-code"
                                                aria-label="Country code">
                                            <option value="+94"
                                                @selected(old('phone_country_code', '+94') === '+94')>
                                                +94 Sri Lanka
                                            </option>
                                        </select>

                                        <input type="text"
                                               id="phone_number"
                                               name="phone_number"
                                               value="{{ old('phone_number') }}"
                                               class="form-control phone-number-input @error('phone') is-invalid @enderror"
                                               placeholder="712345678"
                                               maxlength="9"
                                               inputmode="numeric"
                                               autocomplete="tel-national"
                                               aria-describedby="phoneHelp phoneLiveError">
                                    </div>

                                    <input type="hidden"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}">

                                    <div id="phoneLiveError" class="live-error"></div>

                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <span id="phoneHelp" class="field-help">
                                        Enter exactly 9 digits after the country code. Example: +94 712345678.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- CPC HAPIS Master Employee Details --}}
                        <div class="form-section">
                            <div class="card border-primary-subtle shadow-none">
                                <div class="card-header">
                                    <h2 class="section-title">
                                        <i class="bi bi-database-check me-2 text-primary"></i>
                                        CPC Master Employee Details
                                    </h2>
                                    <p class="section-description">
                                        Read-only employee details from the CPC HAPIS master-data cache.
                                        Enter an EPF number to search automatically.
                                    </p>
                                </div>

                                <div class="card-body">
                                    <div id="cpcMasterStatus"
                                         class="alert alert-info mb-3"
                                         role="status"
                                         aria-live="polite">
                                        Enter a valid five-digit EPF number and leave the EPF field to search CPC master data.
                                    </div>

                                    <div id="cpcMasterDetails" class="row g-3" hidden>
                                        <div class="col-md-6">
                                            <label for="cpc_name_with_initials" class="form-label">CPC Name with Initials</label>
                                            <input type="text" id="cpc_name_with_initials" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cpc_full_name" class="form-label">CPC Full Name</label>
                                            <input type="text" id="cpc_full_name" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cpc_designation" class="form-label">CPC Designation</label>
                                            <input type="text" id="cpc_designation" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cpc_function" class="form-label">CPC Function</label>
                                            <input type="text" id="cpc_function" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cpc_sub_function" class="form-label">CPC Sub-function</label>
                                            <input type="text" id="cpc_sub_function" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cpc_location" class="form-label">CPC Source Location</label>
                                            <input type="text" id="cpc_location" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cpc_employee_status" class="form-label">CPC Employee Status</label>
                                            <input type="text" id="cpc_employee_status" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cpc_branch_mapping" class="form-label">Local Branch Mapping</label>
                                            <input type="text" id="cpc_branch_mapping" class="form-control" readonly>
                                        </div>

                                        <div class="col-12">
                                            <small class="text-muted d-block">
                                                The employee name and designation above are filled automatically from CPC HAPIS.
                                                Email, phone, branch, department, function unit and local employment status are not changed.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- Organizational Information --}}
                        <div class="form-section">
                            <div class="mb-3">
                                <h2 class="section-title">
                                    <i class="bi bi-diagram-3 me-2 text-primary"></i>
                                    Organizational Assignment
                                </h2>
                                <p class="section-description">
                                    Select the employee's current organizational placement.
                                </p>
                            </div>

                            <div class="row g-3">

                                {{-- Branch --}}
                                <div class="col-md-4">
                                    <label for="branch_id" class="form-label">
                                        Branch <span class="required-mark">*</span>
                                    </label>

                                    <select id="branch_id"
                                            name="branch_id"
                                            class="form-select @error('branch_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Branch</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                @selected(old('branch_id') == $branch->id)>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div id="branchLiveError" class="live-error"></div>

                                    @error('branch_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Department --}}
                                <div class="col-md-4">
                                    <label for="department_id" class="form-label">
                                        Department <span class="required-mark">*</span>
                                    </label>

                                    <select id="department_id"
                                            name="department_id"
                                            class="form-select @error('department_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                    data-branch-id="{{ $department->branch_id }}"
                                                @selected(old('department_id') == $department->id)>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div id="departmentLiveError" class="live-error"></div>

                                    @error('department_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Function Unit --}}
                                <div class="col-md-4">
                                    <label for="function_unit_id" class="form-label">Function Unit</label>

                                    <select id="function_unit_id"
                                            name="function_unit_id"
                                            class="form-select @error('function_unit_id') is-invalid @enderror">
                                        <option value="">Select Function Unit</option>
                                        @foreach ($functionUnits as $functionUnit)
                                            <option value="{{ $functionUnit->id }}"
                                                    data-department-id="{{ $functionUnit->department_id }}"
                                                @selected(old('function_unit_id') == $functionUnit->id)>
                                                {{ $functionUnit->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('function_unit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
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
                                    Control whether the employee is currently active in the system.
                                </p>
                            </div>

                            <div class="status-box">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_active"
                                           id="is_active"
                                           value="1"
                                           @checked(old('is_active', true))>
                                    <label class="form-check-label" for="is_active">
                                        Active Employee
                                    </label>
                                </div>

                                <div class="status-description">
                                    Active employees can be associated with inventory assignments and remain visible in normal employee operations.
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Form Actions --}}
                    <div class="form-actions">
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-light">
                            <i class="bi bi-x-lg me-1"></i>
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary" id="registerEmployeeButton">
                            <i class="bi bi-person-plus-fill me-1"></i>
                            Register Employee
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Information Panel --}}
        <div class="col-lg-3">
            <div class="info-card">
                <div class="info-icon"><i class="bi bi-info-circle"></i></div>

                <div class="info-title">Employee & Inventory</div>
                <p class="info-text">
                    Employee records are maintained separately from inventory items.
                    Inventory assets can be assigned to registered employees through the asset assignment workflow.
                </p>

                <hr class="my-3">

                <div class="info-title">Required Information</div>
                <p class="info-text">
                    EPF Number, Employee Name, Branch and Department are required.
                    Function Unit is optional.
                </p>

                <hr class="my-3">

                <div class="info-title">EPF Number Rules</div>
                <p class="info-text">
                    EPF numbers must contain exactly 5 digits. Sequential numbers such as
                    12345 or 54321 and repeated numbers such as 11111 or 77777 are not accepted.
                </p>

                <hr class="my-3">

                <div class="info-title">Contact Number Rules</div>
                <p class="info-text">
                    Sri Lankan mobile numbers use +94 followed by 9 digits.
                    Example: <strong>+94 712345678</strong>.
                </p>

                <hr class="my-3">

                <div class="info-title">Asset Tracking</div>
                <p class="info-text">
                    Computers, peripherals and other inventory items are managed in the Inventory section
                    and linked to employees through assignment records.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const form = document.getElementById('employeeCreateForm');

    const epfInput = document.getElementById('epf_no');
    const epfLiveError = document.getElementById('epfLiveError');
    const epfHelp = document.getElementById('epfHelp');

    const nameInput = document.getElementById('name');
    const designationInput = document.getElementById('designation');

    const emailInput = document.getElementById('email');
    const emailLiveError = document.getElementById('emailLiveError');

    const phoneCountryCode = document.getElementById('phone_country_code');
    const phoneNumberInput = document.getElementById('phone_number');
    const phoneInput = document.getElementById('phone');
    const phoneLiveError = document.getElementById('phoneLiveError');
    const phoneHelp = document.getElementById('phoneHelp');

    const branchSelect = document.getElementById('branch_id');
    const departmentSelect = document.getElementById('department_id');
    const functionUnitSelect = document.getElementById('function_unit_id');

    const branchLiveError = document.getElementById('branchLiveError');
    const departmentLiveError = document.getElementById('departmentLiveError');

    /*
    |--------------------------------------------------------------------------
    | CPC HAPIS Master Employee Lookup
    |--------------------------------------------------------------------------
    */

    const cpcMasterStatus = document.getElementById('cpcMasterStatus');
    const cpcMasterDetails = document.getElementById('cpcMasterDetails');

    let cpcLookupRequest = 0;
    let cpcEmployeeResult = null;

    function setCpcMasterStatus(message, type = 'info') {
        cpcMasterStatus.className = 'alert alert-' + type + ' mb-3';
        cpcMasterStatus.textContent = message;
    }

    function setCpcMasterField(id, value) {
        const field = document.getElementById(id);

        if (field) {
            field.value = value === null || value === undefined || value === ''
                ? 'Not available'
                : String(value);
        }
    }

    function clearCpcMasterDetails() {
        cpcEmployeeResult = null;
        cpcMasterDetails.hidden = true;

        [
            'cpc_name_with_initials',
            'cpc_full_name',
            'cpc_designation',
            'cpc_function',
            'cpc_sub_function',
            'cpc_location',
            'cpc_employee_status',
            'cpc_branch_mapping'
        ].forEach(function (id) {
            const field = document.getElementById(id);
            if (field) {
                field.value = '';
            }
        });
    }

    /*
     * Automatically fill the editable employee name and designation.
     * Other local form fields are deliberately left unchanged.
     */
    function autofillEmployeeInformation(employee) {
        const employeeName =
            employee.full_name ||
            employee.name_with_initials ||
            '';

        if (employeeName) {
            nameInput.value = employeeName;
            nameInput.dispatchEvent(new Event('input', { bubbles: true }));
            nameInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (employee.designation) {
            designationInput.value = employee.designation;
            designationInput.dispatchEvent(new Event('input', { bubbles: true }));
            designationInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    async function lookupCpcEmployee() {
        const epf = epfInput.value.trim();

        clearCpcMasterDetails();

        if (!/^[0-9]{5}$/.test(epf)) {
            setCpcMasterStatus(
                'Enter exactly five numeric EPF digits to search CPC master data.',
                'info'
            );
            return;
        }

        if (isRepeatedDigit(epf) || isSequentialDigits(epf)) {
            setCpcMasterStatus(
                'This EPF number does not meet this form’s validation rules.',
                'warning'
            );
            return;
        }

        const requestId = ++cpcLookupRequest;

        setCpcMasterStatus('Searching CPC master data…', 'info');

        try {
            const url = @json(route('admin.cpc-master.employee-by-epf'))
                + '?epf=' + encodeURIComponent(epf);

            const response = await fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json().catch(function () {
                return {};
            });

            // Ignore stale results if another EPF lookup has started.
            if (
                requestId !== cpcLookupRequest ||
                epfInput.value.trim() !== epf
            ) {
                return;
            }

            if (!response.ok) {
                const messages = {
                    401: 'Your login session may have expired. Please sign in again.',
                    403: 'Your account is not permitted to view this employee’s master data.',
                    404: 'No matching employee was found in your permitted CPC master-data scope.',
                    422: 'Enter a valid five-digit EPF number.'
                };

                setCpcMasterStatus(
                    result.message ||
                    messages[response.status] ||
                    'The CPC master-data lookup failed. Please try again.',
                    response.status === 404 ? 'warning' : 'danger'
                );

                return;
            }

            if (!result.employee) {
                setCpcMasterStatus(
                    'The lookup returned no employee details.',
                    'warning'
                );
                return;
            }

            const employee = result.employee;
            cpcEmployeeResult = employee;

            setCpcMasterField(
                'cpc_name_with_initials',
                employee.name_with_initials
            );
            setCpcMasterField('cpc_full_name', employee.full_name);
            setCpcMasterField('cpc_designation', employee.designation);
            setCpcMasterField('cpc_function', employee.function);
            setCpcMasterField('cpc_sub_function', employee.sub_function);
            setCpcMasterField('cpc_location', employee.location);

            setCpcMasterField(
                'cpc_employee_status',
                employee.is_active
                    ? 'Active in CPC master data'
                    : 'Inactive in CPC master data'
            );

            setCpcMasterField(
                'cpc_branch_mapping',
                employee.branch_mapped
                    ? 'Mapped to local branch ID ' + employee.local_branch_id
                    : 'Not mapped to a local branch'
            );

            cpcMasterDetails.hidden = false;

            // The requested automatic form fill happens here.
            autofillEmployeeInformation(employee);

            setCpcMasterStatus(
                'CPC master details loaded. Employee Name and Designation were filled automatically. Review them before saving.',
                'success'
            );

        } catch (error) {
            if (requestId !== cpcLookupRequest) {
                return;
            }

            setCpcMasterStatus(
                'Unable to contact the lookup endpoint. Check your login session and application connection.',
                'danger'
            );
        }
    }

    epfInput.addEventListener('blur', lookupCpcEmployee);

    epfInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            lookupCpcEmployee();
        }
    });

    epfInput.addEventListener('input', function () {
        cpcLookupRequest++;
        clearCpcMasterDetails();

        setCpcMasterStatus(
            'Enter a five-digit EPF number, then leave the field to search.',
            'info'
        );
    });

    /*
    |--------------------------------------------------------------------------
    | EPF Validation Helpers
    |--------------------------------------------------------------------------
    */

    function isRepeatedDigit(value) {
        return value.length === 5 && /^(\d)\1{4}$/.test(value);
    }

    function isSequentialDigits(value) {
        for (let i = 0; i <= value.length - 4; i++) {
            const part = value.substring(i, i + 4);

            let ascending = true;
            let descending = true;

            for (let j = 1; j < part.length; j++) {
                const previous = Number(part[j - 1]);
                const current = Number(part[j]);

                if (current !== previous + 1) ascending = false;
                if (current !== previous - 1) descending = false;
            }

            if (ascending || descending) {
                return true;
            }
        }

        return false;
    }

    function validateEpf() {
        let value = epfInput.value.replace(/[^0-9]/g, '').substring(0, 5);
        epfInput.value = value;

        if (value === '') {
            epfInput.classList.remove('is-invalid', 'is-valid');
            epfLiveError.classList.remove('show');
            epfLiveError.textContent = '';

            if (epfHelp) {
                epfHelp.classList.remove('field-hint-invalid', 'field-hint-valid');
            }

            return false;
        }

        let message = '';

        if (!/^[0-9]{5}$/.test(value)) {
            message = 'EPF number must contain exactly 5 numeric digits.';
        } else if (isRepeatedDigit(value)) {
            message = 'Repeated-digit EPF numbers such as 11111 or 77777 are not allowed.';
        } else if (isSequentialDigits(value)) {
            message = 'Sequential EPF numbers such as 12345, 23456 or 54321 are not allowed.';
        }

        if (message) {
            epfInput.classList.remove('is-valid');
            epfInput.classList.add('is-invalid');
            epfLiveError.textContent = message;
            epfLiveError.classList.add('show');

            if (epfHelp) {
                epfHelp.classList.remove('field-hint-valid');
                epfHelp.classList.add('field-hint-invalid');
            }

            return false;
        }

        epfInput.classList.remove('is-invalid');
        epfInput.classList.add('is-valid');
        epfLiveError.classList.remove('show');
        epfLiveError.textContent = '';

        if (epfHelp) {
            epfHelp.classList.remove('field-hint-invalid');
            epfHelp.classList.add('field-hint-valid');
        }

        return true;
    }

    epfInput.addEventListener('input', validateEpf);
    epfInput.addEventListener('blur', validateEpf);

    /*
    |--------------------------------------------------------------------------
    | Email Validation
    |--------------------------------------------------------------------------
    */

    function validateEmail() {
        const value = emailInput.value.trim();

        if (value === '') {
            emailInput.classList.remove('is-invalid', 'is-valid');
            emailLiveError.classList.remove('show');
            emailLiveError.textContent = '';
            return true;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(value)) {
            emailInput.classList.remove('is-valid');
            emailInput.classList.add('is-invalid');
            emailLiveError.textContent = 'Please enter a valid email address.';
            emailLiveError.classList.add('show');
            return false;
        }

        emailInput.classList.remove('is-invalid');
        emailInput.classList.add('is-valid');
        emailLiveError.classList.remove('show');
        emailLiveError.textContent = '';
        return true;
    }

    emailInput.addEventListener('input', validateEmail);
    emailInput.addEventListener('blur', validateEmail);

    /*
    |--------------------------------------------------------------------------
    | Phone Validation
    |--------------------------------------------------------------------------
    */

    function validatePhone() {
        let number = phoneNumberInput.value.replace(/[^0-9]/g, '').substring(0, 9);
        phoneNumberInput.value = number;

        const countryCode = phoneCountryCode.value;

        if (number === '') {
            phoneInput.value = '';

            phoneNumberInput.classList.remove('is-invalid', 'is-valid');
            phoneCountryCode.classList.remove('is-invalid', 'is-valid');
            phoneLiveError.classList.remove('show');
            phoneLiveError.textContent = '';

            if (phoneHelp) {
                phoneHelp.classList.remove('field-hint-invalid', 'field-hint-valid');
            }

            return true;
        }

        if (!/^[0-9]{9}$/.test(number)) {
            phoneInput.value = '';
            phoneNumberInput.classList.remove('is-valid');
            phoneNumberInput.classList.add('is-invalid');
            phoneCountryCode.classList.remove('is-valid');
            phoneCountryCode.classList.add('is-invalid');
            phoneLiveError.textContent = 'Contact number must contain exactly 9 numeric digits after the country code.';
            phoneLiveError.classList.add('show');

            if (phoneHelp) {
                phoneHelp.classList.remove('field-hint-valid');
                phoneHelp.classList.add('field-hint-invalid');
            }

            return false;
        }

        if (countryCode === '+94' && number.charAt(0) !== '7') {
            phoneInput.value = '';
            phoneNumberInput.classList.remove('is-valid');
            phoneNumberInput.classList.add('is-invalid');
            phoneCountryCode.classList.remove('is-valid');
            phoneCountryCode.classList.add('is-invalid');
            phoneLiveError.textContent = 'Sri Lankan mobile numbers should start with 7 after +94. Example: 712345678.';
            phoneLiveError.classList.add('show');

            if (phoneHelp) {
                phoneHelp.classList.remove('field-hint-valid');
                phoneHelp.classList.add('field-hint-invalid');
            }

            return false;
        }

        phoneInput.value = countryCode + number;

        phoneNumberInput.classList.remove('is-invalid');
        phoneNumberInput.classList.add('is-valid');
        phoneCountryCode.classList.remove('is-invalid');
        phoneCountryCode.classList.add('is-valid');
        phoneLiveError.classList.remove('show');
        phoneLiveError.textContent = '';

        if (phoneHelp) {
            phoneHelp.classList.remove('field-hint-invalid');
            phoneHelp.classList.add('field-hint-valid');
        }

        return true;
    }

    phoneNumberInput.addEventListener('input', validatePhone);
    phoneNumberInput.addEventListener('blur', validatePhone);
    phoneCountryCode.addEventListener('change', validatePhone);

    /*
    |--------------------------------------------------------------------------
    | Restore Existing/Old Phone Value
    |--------------------------------------------------------------------------
    */

    const oldPhone = @json(old('phone'));
    const oldPhoneNumber = @json(old('phone_number'));

    if (oldPhoneNumber) {
        phoneNumberInput.value = String(oldPhoneNumber).replace(/[^0-9]/g, '');
    } else if (oldPhone) {
        const phoneValue = String(oldPhone).trim();

        if (phoneValue.startsWith('+94') && phoneValue.length === 12) {
            phoneCountryCode.value = '+94';
            phoneNumberInput.value = phoneValue.substring(3);
        } else if (/^0[0-9]{9}$/.test(phoneValue)) {
            phoneCountryCode.value = '+94';
            phoneNumberInput.value = phoneValue.substring(1);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Preserve Original Department and Function Unit Options
    |--------------------------------------------------------------------------
    */

    const originalDepartmentOptions = Array.from(departmentSelect.options).map(function (option) {
        return {
            value: option.value,
            text: option.text,
            branchId: option.dataset.branchId || ''
        };
    });

    const originalFunctionUnitOptions = Array.from(functionUnitSelect.options).map(function (option) {
        return {
            value: option.value,
            text: option.text,
            departmentId: option.dataset.departmentId || ''
        };
    });

    /*
    |--------------------------------------------------------------------------
    | Branch -> Department
    |--------------------------------------------------------------------------
    */

    function populateDepartments(selectedDepartmentId = '') {
        const selectedBranchId = branchSelect.value;

        departmentSelect.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = selectedBranchId ? 'Select Department' : 'Select Branch First';
        departmentSelect.appendChild(defaultOption);

        if (!selectedBranchId) {
            departmentSelect.disabled = true;
            populateFunctionUnits('');
            return;
        }

        departmentSelect.disabled = false;

        originalDepartmentOptions.forEach(function (item) {
            if (!item.value || String(item.branchId) !== String(selectedBranchId)) {
                return;
            }

            const option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.text;
            option.selected = selectedDepartmentId &&
                String(selectedDepartmentId) === String(item.value);

            departmentSelect.appendChild(option);
        });

        if (
            selectedDepartmentId &&
            !Array.from(departmentSelect.options).some(function (option) {
                return String(option.value) === String(selectedDepartmentId);
            })
        ) {
            departmentSelect.value = '';
        }

        populateFunctionUnits('');
    }

    /*
    |--------------------------------------------------------------------------
    | Department -> Function Unit
    |--------------------------------------------------------------------------
    */

    function populateFunctionUnits(selectedFunctionUnitId = '') {
        const selectedDepartmentId = departmentSelect.value;

        functionUnitSelect.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = selectedDepartmentId
            ? 'Select Function Unit'
            : 'Select Department First';

        functionUnitSelect.appendChild(defaultOption);
        functionUnitSelect.disabled = !selectedDepartmentId;

        if (!selectedDepartmentId) {
            return;
        }

        originalFunctionUnitOptions.forEach(function (item) {
            if (!item.value || String(item.departmentId) !== String(selectedDepartmentId)) {
                return;
            }

            const option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.text;
            option.selected = selectedFunctionUnitId &&
                String(selectedFunctionUnitId) === String(item.value);

            functionUnitSelect.appendChild(option);
        });

        if (
            selectedFunctionUnitId &&
            !Array.from(functionUnitSelect.options).some(function (option) {
                return String(option.value) === String(selectedFunctionUnitId);
            })
        ) {
            functionUnitSelect.value = '';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Branch and Department Change Events
    |--------------------------------------------------------------------------
    */

    branchSelect.addEventListener('change', function () {
        branchSelect.classList.remove('is-invalid');

        branchLiveError.classList.remove('show');
        branchLiveError.textContent = '';

        populateDepartments('');

        departmentSelect.classList.remove('is-invalid');
        departmentLiveError.classList.remove('show');
        departmentLiveError.textContent = '';
    });

    departmentSelect.addEventListener('change', function () {
        departmentSelect.classList.remove('is-invalid');
        departmentLiveError.classList.remove('show');
        departmentLiveError.textContent = '';

        populateFunctionUnits('');
    });

    /*
    |--------------------------------------------------------------------------
    | Restore Old Organizational Values
    |--------------------------------------------------------------------------
    */

    const initialBranchId = @json(old('branch_id'));
    const initialDepartmentId = @json(old('department_id'));
    const initialFunctionUnitId = @json(old('function_unit_id'));

    if (initialBranchId) {
        branchSelect.value = String(initialBranchId);
        populateDepartments(initialDepartmentId);

        if (initialDepartmentId) {
            departmentSelect.value = String(initialDepartmentId);
            populateFunctionUnits(initialFunctionUnitId);

            if (initialFunctionUnitId) {
                functionUnitSelect.value = String(initialFunctionUnitId);
            }
        }
    } else {
        departmentSelect.disabled = true;
        functionUnitSelect.disabled = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Initialize Phone Validation
    |--------------------------------------------------------------------------
    */

    if (phoneNumberInput.value !== '') {
        validatePhone();
    }

    /*
    |--------------------------------------------------------------------------
    | Form Submit Validation
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', function (event) {
        const epfValid = validateEpf();
        const emailValid = validateEmail();
        const phoneValid = validatePhone();

        let branchValid = true;
        let departmentValid = true;

        if (!branchSelect.value) {
            branchValid = false;
            branchSelect.classList.add('is-invalid');
            branchLiveError.textContent = 'Please select a branch.';
            branchLiveError.classList.add('show');
        } else {
            branchSelect.classList.remove('is-invalid');
            branchLiveError.classList.remove('show');
            branchLiveError.textContent = '';
        }

        if (!departmentSelect.value) {
            departmentValid = false;
            departmentSelect.classList.add('is-invalid');
            departmentLiveError.textContent = 'Please select a department.';
            departmentLiveError.classList.add('show');
        } else {
            departmentSelect.classList.remove('is-invalid');
            departmentLiveError.classList.remove('show');
            departmentLiveError.textContent = '';
        }

        if (
            !epfValid ||
            !emailValid ||
            !phoneValid ||
            !branchValid ||
            !departmentValid
        ) {
            event.preventDefault();

            const firstInvalid = form.querySelector('.is-invalid');

            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }
    });
});
</script>

@endsection
