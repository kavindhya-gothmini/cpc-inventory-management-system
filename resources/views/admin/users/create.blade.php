
@extends('layouts.admin')

@section('title', 'Create Login Account | CPC Inventory Management System')

@section('content')

<style>
    /* =========================================================
       CPC USER ACCOUNT CREATION
    ========================================================= */

    .user-account-page {
        padding: 24px;
        min-height: calc(100vh - 72px);
        background: #f5f7fb;
    }

    .account-container {
        max-width: 1050px;
        margin: 0 auto;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-title-area {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .page-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 10px;
        background: #eff6ff;
        color: #0d6efd;
        font-size: 20px;
    }

    .page-title-area h1 {
        margin: 0;
        color: #172033;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -0.25px;
    }

    .page-title-area p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 12.5px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 14px;
        border: 1px solid #dfe3e8;
        border-radius: 6px;
        background: #ffffff;
        color: #374151;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;
    }

    .back-button:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0d6efd;
    }

    /* =========================================================
       INFORMATION BANNER
    ========================================================= */

    .account-info-banner {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
        padding: 14px 16px;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        background: #eff6ff;
        color: #1e3a8a;
    }

    .account-info-banner i {
        margin-top: 1px;
        flex-shrink: 0;
        font-size: 17px;
    }

    .account-info-banner strong {
        display: block;
        margin-bottom: 3px;
        font-size: 12.5px;
        font-weight: 700;
    }

    .account-info-banner span {
        display: block;
        font-size: 11.5px;
        line-height: 1.55;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .account-card {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .account-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #edf0f3;
        background: #ffffff;
    }

    .account-card-header h2 {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 700;
    }

    .account-card-header p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 11.5px;
    }

    .account-card-body {
        padding: 24px 22px;
    }

    /* =========================================================
       FORM GRID
    ========================================================= */

    .form-section {
        margin-bottom: 26px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        padding-bottom: 9px;
        border-bottom: 1px solid #edf0f3;
        color: #172033;
        font-size: 13px;
        font-weight: 700;
    }

    .section-heading i {
        color: #0d6efd;
        font-size: 14px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    /* =========================================================
       FORM CONTROLS
    ========================================================= */

    .form-label {
        display: block;
        margin-bottom: 6px;
        color: #374151;
        font-size: 12.5px;
        font-weight: 600;
    }

    .required-mark {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        min-height: 43px;
        border: 1px solid #dfe3e8;
        border-radius: 6px;
        color: #111827;
        background-color: #ffffff;
        font-size: 12.5px;
        box-shadow: none;
        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.09);
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    .form-help {
        margin-top: 5px;
        color: #8a94a3;
        font-size: 10.5px;
        line-height: 1.45;
    }

    .invalid-feedback {
        font-size: 10.5px;
    }

    /* =========================================================
       EMPLOYEE INFORMATION
    ========================================================= */

    .employee-select-wrapper {
        position: relative;
    }

    .employee-select-wrapper .form-select {
        padding-right: 38px;
    }

    .employee-summary {
        display: none;
        margin-top: 9px;
        padding: 10px 12px;
        border: 1px solid #dbeafe;
        border-radius: 6px;
        background: #f8fbff;
    }

    .employee-summary.active {
        display: block;
    }

    .employee-summary-title {
        margin-bottom: 5px;
        color: #1e3a8a;
        font-size: 11.5px;
        font-weight: 700;
    }

    .employee-summary-details {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 7px;
    }

    .employee-detail {
        color: #64748b;
        font-size: 10.5px;
    }

    .employee-detail strong {
        display: block;
        margin-bottom: 2px;
        color: #374151;
        font-size: 10px;
    }

    /* =========================================================
       EPF VALIDATION
    ========================================================= */

    .epf-validation-message {
        display: none;
        margin-top: 8px;
        padding: 8px 10px;
        border: 1px solid #fecaca;
        border-radius: 6px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 10.5px;
        line-height: 1.45;
    }

    .epf-validation-message.active {
        display: block;
    }

    .epf-validation-message i {
        margin-right: 4px;
    }

    .epf-valid-message {
        display: none;
        margin-top: 8px;
        color: #198754;
        font-size: 10.5px;
        font-weight: 600;
    }

    .epf-valid-message.active {
        display: block;
    }

    /* =========================================================
       CONTACT NUMBER
    ========================================================= */

    .phone-input-group {
        display: grid;
        grid-template-columns: 125px minmax(0, 1fr);
        gap: 8px;
    }

    .country-code-select {
        min-height: 43px;
        border: 1px solid #dfe3e8;
        border-radius: 6px;
        background: #ffffff;
        color: #111827;
        font-size: 12.5px;
        box-shadow: none;
        cursor: pointer;
    }

    .country-code-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.09);
        outline: none;
    }

    .phone-number-input {
        letter-spacing: 0.3px;
    }

    .phone-counter {
        margin-top: 5px;
        color: #8a94a3;
        font-size: 10.5px;
    }

    .phone-counter.valid {
        color: #198754;
    }

    .phone-counter.invalid {
        color: #dc3545;
    }

    .phone-validation-message {
        display: none;
        margin-top: 7px;
        color: #dc3545;
        font-size: 10.5px;
        line-height: 1.45;
    }

    .phone-validation-message.active {
        display: block;
    }

    /* =========================================================
       EMAIL VALIDATION
    ========================================================= */

    .email-validation-message {
        display: none;
        margin-top: 7px;
        color: #dc3545;
        font-size: 10.5px;
        line-height: 1.45;
    }

    .email-validation-message.active {
        display: block;
    }

    /* =========================================================
       ROLE SELECTION
    ========================================================= */

    .role-options {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .role-option {
        position: relative;
    }

    .role-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .role-card {
        display: block;
        height: 100%;
        padding: 14px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        background: #ffffff;
        cursor: pointer;
        transition:
            border-color 0.15s ease,
            background-color 0.15s ease,
            box-shadow 0.15s ease,
            transform 0.15s ease;
    }

    .role-card:hover {
        border-color: #b8c5d6;
        background: #fafcff;
    }

    .role-option input:checked + .role-card {
        border-color: #0d6efd;
        background: #f5f9ff;
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.08);
    }

    .role-card-header {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 7px;
    }

    .role-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 7px;
        background: #f1f5f9;
        color: #475569;
        font-size: 14px;
    }

    .role-option input:checked + .role-card .role-icon {
        background: #dbeafe;
        color: #0d6efd;
    }

    .role-name {
        color: #172033;
        font-size: 12.5px;
        font-weight: 700;
    }

    .role-description {
        min-height: 48px;
        color: #6b7280;
        font-size: 10.5px;
        line-height: 1.5;
    }

    .role-permission,
    .role-restriction {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-top: 5px;
        font-size: 10px;
        line-height: 1.4;
    }

    .role-permission {
        color: #4b5563;
    }

    .role-permission i {
        margin-top: 1px;
        color: #16a34a;
        font-size: 10px;
    }

    .role-restriction {
        color: #6b7280;
    }

    .role-restriction i {
        margin-top: 1px;
        color: #dc3545;
        font-size: 10px;
    }

    /* =========================================================
       PASSWORD SECTION
    ========================================================= */

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 44px;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 0;
        width: 42px;
        height: 42px;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #64748b;
        cursor: pointer;
    }

    .password-toggle:hover {
        color: #0d6efd;
    }

    .password-toggle:focus {
        outline: none;
        color: #0d6efd;
    }

    /* =========================================================
       SECURITY NOTE
    ========================================================= */

    .security-note {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        margin-top: 16px;
        padding: 11px 13px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #f8fafc;
        color: #64748b;
        font-size: 10.5px;
        line-height: 1.5;
    }

    .security-note i {
        margin-top: 1px;
        flex-shrink: 0;
        color: #0d6efd;
    }

    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        margin-top: 26px;
        padding-top: 18px;
        border-top: 1px solid #edf0f3;
    }

    .btn-cancel,
    .btn-create {
        min-height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease,
            transform 0.15s ease;
    }

    .btn-cancel {
        border: 1px solid #dfe3e8;
        background: #ffffff;
        color: #4b5563;
    }

    .btn-cancel:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #111827;
    }

    .btn-create {
        border: 1px solid #0d6efd;
        background: #0d6efd;
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(13, 110, 253, 0.14);
    }

    .btn-create:hover {
        border-color: #0b5ed7;
        background: #0b5ed7;
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* =========================================================
       VALIDATION SUMMARY
    ========================================================= */

    .validation-summary {
        margin-bottom: 20px;
        padding: 13px 15px;
        border: 1px solid #fecaca;
        border-radius: 7px;
        background: #fef2f2;
        color: #991b1b;
    }

    .validation-summary-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 5px;
        font-size: 12px;
        font-weight: 700;
    }

    .validation-summary ul {
        margin: 0;
        padding-left: 23px;
        font-size: 10.5px;
        line-height: 1.55;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .role-options {
            grid-template-columns: 1fr;
        }

        .role-description {
            min-height: auto;
        }

        .employee-summary-details {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767.98px) {

        .user-account-page {
            padding: 16px;
        }

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .account-card-body {
            padding: 18px 16px;
        }

        .account-card-header {
            padding: 16px;
        }

        .phone-input-group {
            grid-template-columns: 110px minmax(0, 1fr);
        }
    }

    @media (max-width: 575.98px) {

        .page-title-area h1 {
            font-size: 19px;
        }

        .page-title-area p {
            font-size: 11.5px;
        }

        .page-icon {
            width: 43px;
            height: 43px;
            font-size: 18px;
        }

        .back-button {
            width: 100%;
            justify-content: center;
        }

        .phone-input-group {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-create {
            width: 100%;
        }
    }
</style>


<div class="user-account-page">

    <div class="account-container">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="page-header">

            <div class="page-title-area">

                <div class="page-icon">
                    <i class="bi bi-person-plus"></i>
                </div>

                <div>

                    <h1>
                        Create Login Account
                    </h1>

                    <p>
                        Create a secure system account for an existing CPC employee.
                    </p>

                </div>

            </div>

            <a
                href="{{ route('admin.users.index') }}"
                class="back-button"
            >
                <i class="bi bi-arrow-left"></i>
                Back to User Accounts
            </a>

        </div>


        {{-- =====================================================
             INFORMATION BANNER
        ====================================================== --}}

        <div class="account-info-banner">

            <i class="bi bi-shield-lock"></i>

            <div>

                <strong>
                    Super Admin Account Management
                </strong>

                <span>
                    Login accounts are created and managed by authorized
                    Super Administrators. Each account must be linked to an
                    existing CPC employee record.
                </span>

            </div>

        </div>


        {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}

        @if ($errors->any())

            <div class="validation-summary">

                <div class="validation-summary-title">

                    <i class="bi bi-exclamation-triangle"></i>

                    Please correct the following errors:

                </div>

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
             ACCOUNT CARD
        ====================================================== --}}

        <div class="account-card">

            <div class="account-card-header">

                <h2>
                    Login Account Details
                </h2>

                <p>
                    Complete the required information below to create the account.
                </p>

            </div>


            <div class="account-card-body">

                <form
                    method="POST"
                    action="{{ route('admin.users.store') }}"
                    id="createUserForm"
                    novalidate
                >

                    @csrf


                    {{-- =================================================
                         EMPLOYEE INFORMATION
                    ================================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <i class="bi bi-person-badge"></i>

                            Employee Information

                        </div>


                        <div class="form-grid">

                            <div class="form-group full-width">

                                <label
                                    for="employee_id"
                                    class="form-label"
                                >
                                    CPC Employee
                                    <span class="required-mark">*</span>
                                </label>

                                <div class="employee-select-wrapper">

                                    <select
                                        name="employee_id"
                                        id="employee_id"
                                        class="form-select @error('employee_id') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Select an employee
                                        </option>

                                        @foreach ($employees as $employee)

                                            <option
                                                value="{{ $employee->id }}"
                                                data-epf="{{ $employee->epf_no }}"
                                                data-name="{{ $employee->name }}"
                                                data-designation="{{ $employee->designation }}"
                                                data-branch="{{ optional($employee->branch)->name }}"
                                                data-department="{{ optional($employee->department)->name }}"
                                                data-function-unit="{{ optional($employee->functionUnit)->name }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                                            >

                                                {{ $employee->epf_no }}
                                                -
                                                {{ $employee->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                @error('employee_id')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <div
                                    class="employee-summary"
                                    id="employeeSummary"
                                >

                                    <div class="employee-summary-title">
                                        Selected Employee
                                    </div>

                                    <div class="employee-summary-details">

                                        <div class="employee-detail">

                                            <strong>
                                                EPF Number
                                            </strong>

                                            <span id="summaryEpf">
                                                -
                                            </span>

                                        </div>

                                        <div class="employee-detail">

                                            <strong>
                                                Designation
                                            </strong>

                                            <span id="summaryDesignation">
                                                -
                                            </span>

                                        </div>

                                        <div class="employee-detail">

                                            <strong>
                                                Branch
                                            </strong>

                                            <span id="summaryBranch">
                                                -
                                            </span>

                                        </div>

                                        <div class="employee-detail">

                                            <strong>
                                                Department
                                            </strong>

                                            <span id="summaryDepartment">
                                                -
                                            </span>

                                        </div>

                                        <div class="employee-detail">

                                            <strong>
                                                Function Unit
                                            </strong>

                                            <span id="summaryFunctionUnit">
                                                -
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                {{-- EPF VALIDATION MESSAGE --}}

                                <div
                                    class="epf-validation-message"
                                    id="epfValidationMessage"
                                    role="alert"
                                >
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span id="epfValidationText"></span>
                                </div>


                                <div
                                    class="epf-valid-message"
                                    id="epfValidMessage"
                                >
                                    <i class="bi bi-check-circle-fill"></i>
                                    Valid 5-digit EPF number.
                                </div>


                                <div class="form-help">

                                    The EPF number is taken directly from the
                                    selected Employee Profile. It must contain
                                    exactly 5 numeric digits. Sequential and
                                    repeated-digit numbers are not allowed.

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         CONTACT INFORMATION
                    ================================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <i class="bi bi-envelope-paper"></i>

                            Account Contact Information

                        </div>


                        <div class="form-grid">

                            {{-- =========================================
                                 EMAIL
                            ========================================== --}}

                            <div class="form-group">

                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    Email Address
                                    <span class="required-mark">*</span>
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter account email address"
                                    autocomplete="email"
                                    maxlength="255"
                                    required
                                >

                                @error('email')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <div
                                    class="email-validation-message"
                                    id="emailValidationMessage"
                                    role="alert"
                                >
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    Please enter a valid email address containing @.
                                </div>


                                <div class="form-help">
                                    Enter a valid email address containing @.
                                </div>

                            </div>


                            {{-- =========================================
                                 PHONE
                            ========================================== --}}

                            <div class="form-group">

                                <label
                                    for="phone_number"
                                    class="form-label"
                                >
                                    Contact Number
                                    <span class="required-mark">*</span>
                                </label>

                                <div class="phone-input-group">

                                    <select
                                        name="country_code"
                                        id="country_code"
                                        class="country-code-select @error('country_code') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Select
                                        </option>

                                        <option
                                            value="+94"
                                            {{ old('country_code') === '+94' ? 'selected' : '' }}
                                        >
                                            +94 (Sri Lanka)
                                        </option>

                                        <option
                                            value="+91"
                                            {{ old('country_code') === '+91' ? 'selected' : '' }}
                                        >
                                            +91 (India)
                                        </option>

                                        <option
                                            value="+1"
                                            {{ old('country_code') === '+1' ? 'selected' : '' }}
                                        >
                                            +1 (USA / Canada)
                                        </option>

                                        <option
                                            value="+44"
                                            {{ old('country_code') === '+44' ? 'selected' : '' }}
                                        >
                                            +44 (UK)
                                        </option>

                                        <option
                                            value="+61"
                                            {{ old('country_code') === '+61' ? 'selected' : '' }}
                                        >
                                            +61 (Australia)
                                        </option>

                                        <option
                                            value="+65"
                                            {{ old('country_code') === '+65' ? 'selected' : '' }}
                                        >
                                            +65 (Singapore)
                                        </option>

                                        <option
                                            value="+60"
                                            {{ old('country_code') === '+60' ? 'selected' : '' }}
                                        >
                                            +60 (Malaysia)
                                        </option>

                                        <option
                                            value="+971"
                                            {{ old('country_code') === '+971' ? 'selected' : '' }}
                                        >
                                            +971 (UAE)
                                        </option>

                                        <option
                                            value="+974"
                                            {{ old('country_code') === '+974' ? 'selected' : '' }}
                                        >
                                            +974 (Qatar)
                                        </option>

                                        <option
                                            value="+966"
                                            {{ old('country_code') === '+966' ? 'selected' : '' }}
                                        >
                                            +966 (Saudi Arabia)
                                        </option>

                                        <option
                                            value="+92"
                                            {{ old('country_code') === '+92' ? 'selected' : '' }}
                                        >
                                            +92 (Pakistan)
                                        </option>

                                        <option
                                            value="+880"
                                            {{ old('country_code') === '+880' ? 'selected' : '' }}
                                        >
                                            +880 (Bangladesh)
                                        </option>

                                        <option
                                            value="+81"
                                            {{ old('country_code') === '+81' ? 'selected' : '' }}
                                        >
                                            +81 (Japan)
                                        </option>

                                        <option
                                            value="+82"
                                            {{ old('country_code') === '+82' ? 'selected' : '' }}
                                        >
                                            +82 (South Korea)
                                        </option>

                                        <option
                                            value="+86"
                                            {{ old('country_code') === '+86' ? 'selected' : '' }}
                                        >
                                            +86 (China)
                                        </option>

                                        <option
                                            value="+49"
                                            {{ old('country_code') === '+49' ? 'selected' : '' }}
                                        >
                                            +49 (Germany)
                                        </option>

                                        <option
                                            value="+33"
                                            {{ old('country_code') === '+33' ? 'selected' : '' }}
                                        >
                                            +33 (France)
                                        </option>

                                        <option
                                            value="+39"
                                            {{ old('country_code') === '+39' ? 'selected' : '' }}
                                        >
                                            +39 (Italy)
                                        </option>

                                        <option
                                            value="+27"
                                            {{ old('country_code') === '+27' ? 'selected' : '' }}
                                        >
                                            +27 (South Africa)
                                        </option>

                                        <option
                                            value="+7"
                                            {{ old('country_code') === '+7' ? 'selected' : '' }}
                                        >
                                            +7 (Russia / Kazakhstan)
                                        </option>

                                    </select>


                                    <input
                                        type="text"
                                        name="phone_number"
                                        id="phone_number"
                                        value="{{ old('phone_number') }}"
                                        class="form-control phone-number-input @error('phone_number') is-invalid @enderror"
                                        placeholder="712345678"
                                        inputmode="numeric"
                                        autocomplete="tel-national"
                                        maxlength="9"
                                        minlength="9"
                                        pattern="[0-9]{9}"
                                        required
                                    >

                                </div>


                                @error('country_code')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror


                                @error('phone_number')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <div
                                    class="phone-counter"
                                    id="phoneCounter"
                                >
                                    0 / 9 digits
                                </div>


                                <div
                                    class="phone-validation-message"
                                    id="phoneValidationMessage"
                                    role="alert"
                                >
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span id="phoneValidationText"></span>
                                </div>


                                <div class="form-help">

                                    Select the country code and enter exactly
                                    9 digits. Example:
                                    <strong>+94 712345678</strong>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         ROLE AND PERMISSIONS
                    ================================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <i class="bi bi-shield-check"></i>

                            Account Role & Permissions

                        </div>


                        <div class="role-options">

                            {{-- EMPLOYEE --}}

                            <div class="role-option">

                                <input
                                    type="radio"
                                    name="role"
                                    id="role_employee"
                                    value="employee"
                                    {{ old('role', 'employee') === 'employee' ? 'checked' : '' }}
                                >

                                <label
                                    for="role_employee"
                                    class="role-card"
                                >

                                    <div class="role-card-header">

                                        <div class="role-icon">
                                            <i class="bi bi-person"></i>
                                        </div>

                                        <div class="role-name">
                                            Employee
                                        </div>

                                    </div>

                                    <div class="role-description">

                                        Standard employee login with
                                        read-only access to permitted
                                        system information.

                                    </div>

                                    <div class="role-permission">

                                        <i class="bi bi-check-circle-fill"></i>

                                        <span>
                                            View and search permitted records.
                                        </span>

                                    </div>

                                    <div class="role-restriction">

                                        <i class="bi bi-x-circle-fill"></i>

                                        <span>
                                            Cannot create, edit or delete records.
                                        </span>

                                    </div>

                                </label>

                            </div>


                            {{-- ADMIN --}}

                            <div class="role-option">

                                <input
                                    type="radio"
                                    name="role"
                                    id="role_admin"
                                    value="admin"
                                    {{ old('role') === 'admin' ? 'checked' : '' }}
                                >

                                <label
                                    for="role_admin"
                                    class="role-card"
                                >

                                    <div class="role-card-header">

                                        <div class="role-icon">
                                            <i class="bi bi-person-gear"></i>
                                        </div>

                                        <div class="role-name">
                                            Standard Admin
                                        </div>

                                    </div>

                                    <div class="role-description">

                                        Administrative access for
                                        day-to-day inventory and
                                        operational management.

                                    </div>

                                    <div class="role-permission">

                                        <i class="bi bi-check-circle-fill"></i>

                                        <span>
                                            Manage permitted operational records.
                                        </span>

                                    </div>

                                    <div class="role-restriction">

                                        <i class="bi bi-x-circle-fill"></i>

                                        <span>
                                            No user-account or privileged access management.
                                        </span>

                                    </div>

                                </label>

                            </div>


                            {{-- SUPER ADMIN --}}

                            <div class="role-option">

                                <input
                                    type="radio"
                                    name="role"
                                    id="role_super_admin"
                                    value="super_admin"
                                    {{ old('role') === 'super_admin' ? 'checked' : '' }}
                                >

                                <label
                                    for="role_super_admin"
                                    class="role-card"
                                >

                                    <div class="role-card-header">

                                        <div class="role-icon">
                                            <i class="bi bi-person-lock"></i>
                                        </div>

                                        <div class="role-name">
                                            Super Admin
                                        </div>

                                    </div>

                                    <div class="role-description">

                                        Full system administration with
                                        access to privileged account
                                        management functions.

                                    </div>

                                    <div class="role-permission">

                                        <i class="bi bi-check-circle-fill"></i>

                                        <span>
                                            Full system and account-management access.
                                        </span>

                                    </div>

                                    <div class="role-permission">

                                        <i class="bi bi-check-circle-fill"></i>

                                        <span>
                                            Can manage administrator and employee accounts.
                                        </span>

                                    </div>

                                </label>

                            </div>

                        </div>


                        <div class="security-note">

                            <i class="bi bi-shield-lock-fill"></i>

                            <span>

                                <strong>Security:</strong>
                                Super Admin privileges should only be assigned
                                to authorized personnel. Standard Admin and
                                Employee accounts cannot access the User Account
                                Management area.

                            </span>

                        </div>

                    </div>


                    {{-- =================================================
                         PASSWORD
                    ================================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <i class="bi bi-key"></i>

                            Login Password

                        </div>


                        <div class="form-grid">

                            <div class="form-group">

                                <label
                                    for="password"
                                    class="form-label"
                                >
                                    Password
                                    <span class="required-mark">*</span>
                                </label>

                                <div class="password-wrapper">

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Enter a secure password"
                                        autocomplete="new-password"
                                        minlength="8"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        id="togglePassword"
                                        aria-label="Show password"
                                        title="Show password"
                                    >

                                        <i
                                            class="bi bi-eye"
                                            id="passwordIcon"
                                        ></i>

                                    </button>

                                </div>

                                @error('password')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="form-help">

                                    Minimum 8 characters with at least
                                    one uppercase letter, one lowercase
                                    letter, one number and one special character.

                                </div>

                            </div>


                            <div class="form-group">

                                <label
                                    for="password_confirmation"
                                    class="form-label"
                                >
                                    Confirm Password
                                    <span class="required-mark">*</span>
                                </label>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Re-enter the password"
                                    autocomplete="new-password"
                                    minlength="8"
                                    required
                                >

                                <div class="form-help">

                                    Enter the same password again to confirm it.

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         ACTIONS
                    ================================================== --}}

                    <div class="form-actions">

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="btn-cancel"
                        >

                            <i class="bi bi-x-lg"></i>

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="btn-create"
                            id="createAccountButton"
                        >

                            <i class="bi bi-person-plus"></i>

                            Create Login Account

                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>

</div>


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENTS
    ========================================================= */

    const createUserForm =
        document.getElementById('createUserForm');

    const employeeSelect =
        document.getElementById('employee_id');

    const employeeSummary =
        document.getElementById('employeeSummary');

    const summaryEpf =
        document.getElementById('summaryEpf');

    const summaryDesignation =
        document.getElementById('summaryDesignation');

    const summaryBranch =
        document.getElementById('summaryBranch');

    const summaryDepartment =
        document.getElementById('summaryDepartment');

    const summaryFunctionUnit =
        document.getElementById('summaryFunctionUnit');

    const epfValidationMessage =
        document.getElementById('epfValidationMessage');

    const epfValidationText =
        document.getElementById('epfValidationText');

    const epfValidMessage =
        document.getElementById('epfValidMessage');

    const email =
        document.getElementById('email');

    const emailValidationMessage =
        document.getElementById('emailValidationMessage');

    const countryCode =
        document.getElementById('country_code');

    const phoneNumber =
        document.getElementById('phone_number');

    const phoneCounter =
        document.getElementById('phoneCounter');

    const phoneValidationMessage =
        document.getElementById('phoneValidationMessage');

    const phoneValidationText =
        document.getElementById('phoneValidationText');


    /* =========================================================
       EPF VALIDATION
       =========================================================
       Rules:
       - Exactly 5 numeric digits
       - Repeated digits are not allowed
       - Ascending sequential numbers are not allowed
       - Descending sequential numbers are not allowed
       ========================================================= */

    function validateEpfNumber(epf) {

        epf = String(epf || '').trim();


        /*
        |----------------------------------------------------------
        | Exactly 5 numeric digits
        |----------------------------------------------------------
        */

        if (!/^[0-9]{5}$/.test(epf)) {

            return {
                valid: false,
                message: 'EPF number must contain exactly 5 numeric digits.'
            };

        }


        const digits =
            epf.split('').map(Number);


        /*
        |----------------------------------------------------------
        | Repeated digit validation
        | Examples:
        | 11111
        | 22222
        | 77777
        |----------------------------------------------------------
        */

        const allSame =
            digits.every(function (digit) {
                return digit === digits[0];
            });


        if (allSame) {

            return {
                valid: false,
                message: 'Repeated-digit EPF numbers such as 11111 or 77777 are not allowed.'
            };

        }


        /*
        |----------------------------------------------------------
        | Sequential ascending / descending validation
        | Examples:
        | 12345
        | 23456
        | 34567
        | 54321
        | 65432
        |----------------------------------------------------------
        */

        let ascending = true;
        let descending = true;


        for (let i = 1; i < digits.length; i++) {

            if (digits[i] !== digits[i - 1] + 1) {

                ascending = false;

            }

            if (digits[i] !== digits[i - 1] - 1) {

                descending = false;

            }

        }


        if (ascending || descending) {

            return {
                valid: false,
                message: 'Sequential EPF numbers such as 12345, 23456 or 54321 are not allowed.'
            };

        }


        return {
            valid: true,
            message: ''
        };

    }


    function updateEpfValidation(epf) {

        if (!epfValidationMessage || !epfValidationText) {
            return false;
        }


        epfValidationMessage.classList.remove('active');


        if (epfValidMessage) {
            epfValidMessage.classList.remove('active');
        }


        const result =
            validateEpfNumber(epf);


        if (!result.valid) {

            epfValidationText.textContent =
                result.message;

            epfValidationMessage.classList.add(
                'active'
            );

            if (employeeSelect) {

                employeeSelect.classList.add(
                    'is-invalid'
                );

            }

            return false;

        }


        if (employeeSelect) {

            employeeSelect.classList.remove(
                'is-invalid'
            );

        }


        if (epfValidMessage) {

            epfValidMessage.classList.add(
                'active'
            );

        }


        return true;

    }


    /* =========================================================
       EMPLOYEE SUMMARY + EPF VALIDATION
    ========================================================= */

    function updateEmployeeSummary() {

        if (
            !employeeSelect ||
            !employeeSummary
        ) {
            return false;
        }


        const selectedOption =
            employeeSelect.options[
                employeeSelect.selectedIndex
            ];


        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            employeeSummary.classList.remove(
                'active'
            );

            if (epfValidationMessage) {

                epfValidationMessage.classList.remove(
                    'active'
                );

            }

            if (epfValidMessage) {

                epfValidMessage.classList.remove(
                    'active'
                );

            }

            employeeSelect.classList.remove(
                'is-invalid'
            );

            return false;

        }


        const epf =
            selectedOption.dataset.epf || '';


        summaryEpf.textContent =
            epf || '-';

        summaryDesignation.textContent =
            selectedOption.dataset.designation || '-';

        summaryBranch.textContent =
            selectedOption.dataset.branch || '-';

        summaryDepartment.textContent =
            selectedOption.dataset.department || '-';

        summaryFunctionUnit.textContent =
            selectedOption.dataset.functionUnit || '-';


        employeeSummary.classList.add(
            'active'
        );


        return updateEpfValidation(epf);

    }


    if (employeeSelect) {

        employeeSelect.addEventListener(
            'change',
            updateEmployeeSummary
        );

        updateEmployeeSummary();

    }


    /* =========================================================
       EMAIL VALIDATION
       ========================================================= */

    function validateEmail() {

        if (!email) {
            return false;
        }


        const value =
            email.value.trim();


        /*
        |----------------------------------------------------------
        | Must contain @
        |----------------------------------------------------------
        */

        if (!value.includes('@')) {

            if (emailValidationMessage) {

                emailValidationMessage.textContent =
                    'Please enter a valid email address containing @.';

                emailValidationMessage.classList.add(
                    'active'
                );

            }

            email.classList.add(
                'is-invalid'
            );

            return false;

        }


        /*
        |----------------------------------------------------------
        | Basic valid email format
        |----------------------------------------------------------
        */

        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


        if (!emailPattern.test(value)) {

            if (emailValidationMessage) {

                emailValidationMessage.textContent =
                    'Please enter a valid email address containing @.';

                emailValidationMessage.classList.add(
                    'active'
                );

            }

            email.classList.add(
                'is-invalid'
            );

            return false;

        }


        if (emailValidationMessage) {

            emailValidationMessage.classList.remove(
                'active'
            );

        }


        email.classList.remove(
            'is-invalid'
        );


        return true;

    }


    if (email) {

        email.addEventListener(
            'input',
            validateEmail
        );

        email.addEventListener(
            'blur',
            validateEmail
        );

    }


    /* =========================================================
       COUNTRY CODE VALIDATION
    ========================================================= */

    function validateCountryCode() {

        if (!countryCode) {
            return false;
        }


        const value =
            countryCode.value;


        if (!/^\+[1-9][0-9]{0,3}$/.test(value)) {

            countryCode.classList.add(
                'is-invalid'
            );

            return false;

        }


        countryCode.classList.remove(
            'is-invalid'
        );


        return true;

    }


    if (countryCode) {

        countryCode.addEventListener(
            'change',
            function () {

                validateCountryCode();
                validatePhoneNumber();

            }
        );

    }


    /* =========================================================
       PHONE NUMBER VALIDATION
       ========================================================= */

    function validatePhoneNumber() {

        if (!phoneNumber) {
            return false;
        }


        /*
        |----------------------------------------------------------
        | Remove all non-numeric characters while typing.
        |----------------------------------------------------------
        */

        phoneNumber.value =
            phoneNumber.value.replace(/\D/g, '');


        /*
        |----------------------------------------------------------
        | Maximum 9 digits
        |----------------------------------------------------------
        */

        if (phoneNumber.value.length > 9) {

            phoneNumber.value =
                phoneNumber.value.substring(0, 9);

        }


        const length =
            phoneNumber.value.length;


        if (phoneCounter) {

            phoneCounter.textContent =
                length + ' / 9 digits';

            phoneCounter.classList.remove(
                'valid',
                'invalid'
            );


            if (length === 9) {

                phoneCounter.classList.add(
                    'valid'
                );

            } else if (length > 0) {

                phoneCounter.classList.add(
                    'invalid'
                );

            }

        }


        /*
        |----------------------------------------------------------
        | Country code must be selected
        |----------------------------------------------------------
        */

        if (
            !countryCode ||
            !countryCode.value
        ) {

            showPhoneValidation(
                'Select the country code and enter exactly 9 digits. Example: +94 712345678.'
            );

            return false;

        }


        /*
        |----------------------------------------------------------
        | Exactly 9 numeric digits
        |----------------------------------------------------------
        */

        if (length !== 9) {

            showPhoneValidation(
                'Contact number must contain exactly 9 numeric digits after the country code.'
            );

            return false;

        }


        hidePhoneValidation();


        phoneNumber.classList.remove(
            'is-invalid'
        );


        return true;

    }


    function showPhoneValidation(message) {

        if (phoneValidationText) {

            phoneValidationText.textContent =
                message;

        }

        if (phoneValidationMessage) {

            phoneValidationMessage.classList.add(
                'active'
            );

        }

        if (phoneNumber) {

            phoneNumber.classList.add(
                'is-invalid'
            );

        }

    }


    function hidePhoneValidation() {

        if (phoneValidationMessage) {

            phoneValidationMessage.classList.remove(
                'active'
            );

        }

    }


    if (phoneNumber) {

        phoneNumber.addEventListener(
            'input',
            validatePhoneNumber
        );

        phoneNumber.addEventListener(
            'blur',
            validatePhoneNumber
        );

        validatePhoneNumber();

    }


    /* =========================================================
       FORM SUBMISSION VALIDATION
       ========================================================= */

    if (createUserForm) {

        createUserForm.addEventListener(
            'submit',
            function (event) {

                let formIsValid = true;


                /*
                |--------------------------------------------------
                | Employee / EPF
                |--------------------------------------------------
                */

                const employeeValid =
                    updateEmployeeSummary();


                if (!employeeSelect.value) {

                    employeeSelect.classList.add(
                        'is-invalid'
                    );

                    formIsValid = false;

                } else if (!employeeValid) {

                    formIsValid = false;

                }


                /*
                |--------------------------------------------------
                | Email
                |--------------------------------------------------
                */

                const emailValid =
                    validateEmail();


                if (!emailValid) {

                    formIsValid = false;

                }


                /*
                |--------------------------------------------------
                | Country Code
                |--------------------------------------------------
                */

                const countryValid =
                    validateCountryCode();


                if (!countryValid) {

                    formIsValid = false;

                    showPhoneValidation(
                        'Select the country code and enter exactly 9 digits. Example: +94 712345678.'
                    );

                }


                /*
                |--------------------------------------------------
                | Phone
                |--------------------------------------------------
                */

                const phoneValid =
                    validatePhoneNumber();


                if (!phoneValid) {

                    formIsValid = false;

                }


                /*
                |--------------------------------------------------
                | Password
                |--------------------------------------------------
                */

                const password =
                    document.getElementById('password');

                const passwordConfirmation =
                    document.getElementById('password_confirmation');


                if (
                    password &&
                    password.value.length < 8
                ) {

                    password.classList.add(
                        'is-invalid'
                    );

                    formIsValid = false;

                }


                if (
                    password &&
                    passwordConfirmation &&
                    password.value !== passwordConfirmation.value
                ) {

                    passwordConfirmation.classList.add(
                        'is-invalid'
                    );

                    formIsValid = false;

                }


                /*
                |--------------------------------------------------
                | Stop submission when validation fails.
                |--------------------------------------------------
                */

                if (!formIsValid) {

                    event.preventDefault();


                    const firstInvalid =
                        createUserForm.querySelector(
                            '.is-invalid'
                        );


                    if (firstInvalid) {

                        firstInvalid.focus();

                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                    }

                    return false;

                }


                /*
                |--------------------------------------------------
                | Allow Laravel backend validation.
                |--------------------------------------------------
                */

                return true;

            }
        );

    }


    /* =========================================================
       PASSWORD VISIBILITY
    ========================================================= */

    const password =
        document.getElementById('password');

    const togglePassword =
        document.getElementById('togglePassword');

    const passwordIcon =
        document.getElementById('passwordIcon');


    if (
        password &&
        togglePassword &&
        passwordIcon
    ) {

        togglePassword.addEventListener(
            'click',
            function () {

                const isPassword =
                    password.type === 'password';


                password.type =
                    isPassword
                        ? 'text'
                        : 'password';


                passwordIcon.classList.toggle(
                    'bi-eye',
                    !isPassword
                );

                passwordIcon.classList.toggle(
                    'bi-eye-slash',
                    isPassword
                );


                togglePassword.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );


                togglePassword.setAttribute(
                    'title',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );

            }
        );

    }


    /* =========================================================
       PASSWORD CONFIRMATION VISUAL VALIDATION
    ========================================================= */

    const passwordConfirmation =
        document.getElementById(
            'password_confirmation'
        );


    function validatePasswordConfirmation() {

        if (
            !password ||
            !passwordConfirmation
        ) {
            return true;
        }


        if (
            passwordConfirmation.value &&
            password.value !== passwordConfirmation.value
        ) {

            passwordConfirmation.classList.add(
                'is-invalid'
            );

            return false;

        }


        passwordConfirmation.classList.remove(
            'is-invalid'
        );

        return true;

    }


    if (password) {

        password.addEventListener(
            'input',
            function () {

                password.classList.remove(
                    'is-invalid'
                );

                validatePasswordConfirmation();

            }
        );

    }


    if (passwordConfirmation) {

        passwordConfirmation.addEventListener(
            'input',
            validatePasswordConfirmation
        );

    }

});

</script>

@endpush

@endsection

