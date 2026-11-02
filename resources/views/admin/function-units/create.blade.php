@extends('layouts.admin')

@section('title', 'Create Function Unit | CPC Inventory Management System')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | ROLE & PERMISSION CONTROL
    |--------------------------------------------------------------------------
    | Super Admin : Can create Function Units
    | Admin       : View + Search only
    | Employee    : View + Search only
    |--------------------------------------------------------------------------
    */

    $currentUser = auth()->user();

    $isSuperAdmin = $currentUser
        && $currentUser->role === 'super_admin';

    $isAdmin = $currentUser
        && $currentUser->role === 'admin';

    $isEmployee = $currentUser
        && $currentUser->role === 'employee';
@endphp

<style>
    /* =========================================================
       CREATE FUNCTION UNIT PAGE
    ========================================================= */

    .fu-create-page {
        padding-bottom: 2rem;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .fu-create-header {
        background: linear-gradient(135deg, #ffffff 0%, #f6f9ff 100%);
        border: 1px solid #e7ebf3;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 18px rgba(30, 55, 90, 0.05);
    }

    .fu-header-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 14px;
        background: rgba(13, 110, 253, 0.10);
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
    }

    .fu-page-title {
        font-size: 25px;
        font-weight: 700;
        color: #172033;
        margin: 0;
    }

    .fu-page-subtitle {
        color: #788397;
        font-size: 14px;
        margin: 5px 0 0;
        line-height: 1.5;
    }

    /* =========================================================
       BACK BUTTON
    ========================================================= */

    .fu-back-btn {
        border-radius: 10px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #526174;
        border: 1px solid #dfe4ec;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .fu-back-btn:hover {
        background: #f7f9fc;
        border-color: #cbd3df;
        color: #263449;
        transform: translateY(-1px);
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .fu-form-card {
        background: #ffffff;
        border: 1px solid #e7ebf3;
        border-radius: 18px;
        box-shadow: 0 5px 22px rgba(30, 55, 90, 0.055);
        overflow: hidden;
    }

    .fu-form-card-header {
        padding: 22px 26px;
        border-bottom: 1px solid #edf0f5;
        background: #ffffff;
    }

    .fu-form-card-title {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 700;
    }

    .fu-form-card-description {
        margin: 5px 0 0;
        color: #8a94a6;
        font-size: 12px;
        line-height: 1.5;
    }

    .fu-form-body {
        padding: 28px 26px;
    }

    /* =========================================================
       INFORMATION BOX
    ========================================================= */

    .fu-info-box {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px 17px;
        margin-bottom: 25px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 11px;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.55;
    }

    .fu-info-icon {
        width: 28px;
        height: 28px;
        min-width: 28px;
        border-radius: 8px;
        background: rgba(13, 110, 253, 0.10);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0d6efd;
        font-size: 14px;
    }

    .fu-info-box strong {
        display: block;
        margin-bottom: 2px;
        color: #173f8a;
    }

    /* =========================================================
       ACCESS RESTRICTED
    ========================================================= */

    .fu-access-card {
        text-align: center;
        padding: 65px 30px;
        background: #ffffff;
    }

    .fu-access-icon {
        width: 88px;
        height: 88px;
        margin: 0 auto 22px;
        border-radius: 50%;
        background: #fff4e5;
        color: #f59e0b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.10);
    }

    .fu-access-title {
        color: #273142;
        font-size: 21px;
        font-weight: 750;
        margin-bottom: 9px;
    }

    .fu-access-text {
        max-width: 540px;
        margin: 0 auto 22px;
        color: #7b8799;
        font-size: 13px;
        line-height: 1.7;
    }

    .fu-access-notice {
        max-width: 580px;
        margin: 0 auto 25px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        text-align: left;
        padding: 14px 16px;
        border: 1px solid #dbe7f8;
        border-radius: 10px;
        background: #f5f9ff;
        color: #55708f;
        font-size: 12px;
        line-height: 1.6;
    }

    .fu-access-notice i {
        color: #2f80ed;
        font-size: 15px;
        margin-top: 1px;
    }

    .fu-access-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 18px;
        border-radius: 9px;
        border: 1px solid #0d6efd;
        background: #0d6efd;
        color: #ffffff;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        box-shadow: 0 5px 14px rgba(13, 110, 253, 0.16);
        transition: all 0.2s ease;
    }

    .fu-access-back:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(13, 110, 253, 0.22);
    }

    /* =========================================================
       ERROR BOX
    ========================================================= */

    .fu-error-box {
        padding: 15px 17px;
        margin-bottom: 25px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 11px;
        color: #991b1b;
        font-size: 13px;
    }

    .fu-error-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .fu-error-box ul {
        margin: 0;
        padding-left: 27px;
        line-height: 1.7;
    }

    /* =========================================================
       FORM SECTION
    ========================================================= */

    .fu-section {
        margin-bottom: 28px;
    }

    .fu-section:last-child {
        margin-bottom: 0;
    }

    .fu-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e8ecf2;
        color: #273142;
        font-size: 15px;
        font-weight: 700;
    }

    .fu-section-title-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #edf5ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    /* =========================================================
       FORM FIELDS
    ========================================================= */

    .fu-form-group {
        margin-bottom: 20px;
    }

    .fu-form-label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 13px;
        font-weight: 650;
    }

    .fu-required {
        color: #dc3545;
    }

    .fu-input,
    .fu-select,
    .fu-textarea {
        width: 100%;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        background: #ffffff;
        color: #344054;
        font-size: 13px;
        padding: 11px 13px;
        transition: all 0.2s ease;
    }

    .fu-input,
    .fu-select {
        min-height: 43px;
    }

    .fu-textarea {
        min-height: 125px;
        resize: vertical;
        line-height: 1.55;
    }

    .fu-input::placeholder,
    .fu-textarea::placeholder {
        color: #a0a8b5;
    }

    .fu-input:hover,
    .fu-select:hover,
    .fu-textarea:hover {
        border-color: #c8d0dc;
    }

    .fu-input:focus,
    .fu-select:focus,
    .fu-textarea:focus {
        outline: none;
        border-color: #86b7fe;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
    }

    .fu-field-help {
        margin-top: 6px;
        color: #98a2b3;
        font-size: 11px;
        line-height: 1.45;
    }

    /* =========================================================
       ACTIVE CHECKBOX
    ========================================================= */

    .fu-active-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 16px;
        background: #f8fafc;
        border: 1px solid #e7ebf1;
        border-radius: 10px;
    }

    .fu-active-box input {
        width: 18px;
        height: 18px;
        margin: 0;
        cursor: pointer;
        accent-color: #0d6efd;
    }

    .fu-active-content {
        cursor: pointer;
        margin: 0;
    }

    .fu-active-title {
        color: #344054;
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 2px;
    }

    .fu-active-description {
        color: #8a94a6;
        font-size: 11px;
    }

    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .fu-form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 20px 26px;
        border-top: 1px solid #edf0f5;
        background: #fafbfc;
    }

    .fu-btn {
        min-height: 42px;
        padding: 10px 18px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 650;
        transition: all 0.2s ease;
    }

    .fu-btn-primary {
        border: 1px solid #0d6efd;
        background: #0d6efd;
        color: #ffffff;
        box-shadow: 0 5px 14px rgba(13, 110, 253, 0.16);
    }

    .fu-btn-primary:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(13, 110, 253, 0.22);
    }

    .fu-btn-secondary {
        border: 1px solid #dfe4ec;
        background: #ffffff;
        color: #526174;
    }

    .fu-btn-secondary:hover {
        background: #f5f7fa;
        border-color: #cbd3df;
        color: #344054;
        transform: translateY(-1px);
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .fu-create-header {
            padding: 21px 22px;
        }

        .fu-form-body {
            padding: 24px 22px;
        }

        .fu-form-card-header {
            padding: 20px 22px;
        }

        .fu-form-actions {
            padding: 18px 22px;
        }

        .fu-access-card {
            padding: 55px 25px;
        }
    }

    @media (max-width: 767px) {

        .fu-create-page {
            padding-bottom: 1rem;
        }

        .fu-create-header {
            padding: 18px;
            border-radius: 14px;
        }

        .fu-create-header > .d-flex {
            align-items: flex-start !important;
        }

        .fu-header-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 11px;
            font-size: 19px;
        }

        .fu-page-title {
            font-size: 21px;
        }

        .fu-page-subtitle {
            font-size: 12px;
        }

        .fu-back-btn {
            width: 100%;
            margin-top: 4px;
        }

        .fu-form-card {
            border-radius: 14px;
        }

        .fu-form-card-header {
            padding: 18px;
        }

        .fu-form-body {
            padding: 20px 18px;
        }

        .fu-form-actions {
            padding: 16px 18px;
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .fu-form-actions .fu-btn {
            width: 100%;
            justify-content: center;
        }

        .fu-info-box {
            padding: 13px;
        }

        .fu-section-title {
            font-size: 14px;
        }

        .fu-form-group {
            margin-bottom: 18px;
        }

        .fu-access-card {
            padding: 45px 18px;
        }

        .fu-access-icon {
            width: 76px;
            height: 76px;
            font-size: 31px;
        }

        .fu-access-title {
            font-size: 19px;
        }

        .fu-access-text {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {

        .fu-create-header {
            padding: 16px;
        }

        .fu-create-header .d-flex.align-items-center {
            gap: 10px !important;
        }

        .fu-header-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 17px;
        }

        .fu-page-title {
            font-size: 19px;
        }

        .fu-page-subtitle {
            font-size: 11px;
        }

        .fu-form-body {
            padding: 18px 15px;
        }

        .fu-form-card-header {
            padding: 17px 15px;
        }

        .fu-form-actions {
            padding: 15px;
        }

        .fu-info-box {
            font-size: 12px;
        }

        .fu-input,
        .fu-select,
        .fu-textarea {
            font-size: 13px;
        }

        .fu-access-card {
            padding: 40px 15px;
        }
    }
</style>

<div class="fu-create-page">


{{-- =====================================================
     PAGE HEADER
====================================================== --}}
<div class="fu-create-header">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div class="d-flex align-items-center gap-3">

            <div class="fu-header-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>

            <div>

                <h1 class="fu-page-title">
                    Create Function Unit
                </h1>

                <p class="fu-page-subtitle">
                    Register a Function Unit under a CPC Department.
                </p>

            </div>

        </div>

        <a
            href="{{ route('admin.function-units.index') }}"
            class="btn fu-back-btn"
        >
            <i class="bi bi-arrow-left me-2"></i>
            Back to Function Units
        </a>

    </div>

</div>


{{-- =====================================================
     SUPER ADMIN — CREATION FORM
====================================================== --}}
@if($isSuperAdmin)

    <div class="fu-form-card">

        {{-- CARD HEADER --}}
        <div class="fu-form-card-header">

            <h5 class="fu-form-card-title">
                Function Unit Information
            </h5>

            <p class="fu-form-card-description">
                Enter the organizational details required to register
                this Function Unit in the CPC system.
            </p>

        </div>


        {{-- FORM BODY --}}
        <div class="fu-form-body">

            {{-- =================================================
                 INFORMATION MESSAGE
            ================================================== --}}
            <div class="fu-info-box">

                <div class="fu-info-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>

                <div>

                    <strong>Department Relationship</strong>

                    A Function Unit belongs to a Department.
                    Select the appropriate active Department before
                    registering the Function Unit.

                </div>

            </div>


            {{-- =================================================
                 VALIDATION ERRORS
            ================================================== --}}
            @if ($errors->any())

                <div class="fu-error-box">

                    <div class="fu-error-header">

                        <i class="bi bi-exclamation-triangle-fill"></i>

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


            <form
                action="{{ route('admin.function-units.store') }}"
                method="POST"
            >

                @csrf


                {{-- =================================================
                     FUNCTION UNIT DETAILS
                ================================================== --}}
                <div class="fu-section">

                    <div class="fu-section-title">

                        <div class="fu-section-title-icon">
                            <i class="bi bi-building"></i>
                        </div>

                        Function Unit Details

                    </div>


                    <div class="row g-3">

                        {{-- DEPARTMENT --}}
                        <div class="col-12">

                            <div class="fu-form-group mb-0">

                                <label
                                    for="department_id"
                                    class="fu-form-label"
                                >
                                    Department
                                    <span class="fu-required">*</span>
                                </label>

                                <select
                                    id="department_id"
                                    name="department_id"
                                    class="fu-select"
                                    required
                                >

                                    <option value="">
                                        -- Select Department --
                                    </option>

                                    @foreach($departments as $department)

                                        <option
                                            value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}
                                        >
                                            {{ $department->name }}

                                            @if($department->code)
                                                ({{ $department->code }})
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                <div class="fu-field-help">
                                    Select the CPC Department to which this Function Unit belongs.
                                </div>

                            </div>

                        </div>


                        {{-- FUNCTION UNIT NAME --}}
                        <div class="col-lg-8 col-md-7 col-12">

                            <div class="fu-form-group mb-0">

                                <label
                                    for="name"
                                    class="fu-form-label"
                                >
                                    Function Unit Name
                                    <span class="fu-required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="fu-input"
                                    value="{{ old('name') }}"
                                    placeholder="Example: Network & Infrastructure"
                                    maxlength="255"
                                    required
                                >

                                <div class="fu-field-help">
                                    Enter the official name of the CPC Function Unit.
                                </div>

                            </div>

                        </div>


                        {{-- CODE --}}
                        <div class="col-lg-4 col-md-5 col-12">

                            <div class="fu-form-group mb-0">

                                <label
                                    for="code"
                                    class="fu-form-label"
                                >
                                    Function Unit Code
                                </label>

                                <input
                                    type="text"
                                    id="code"
                                    name="code"
                                    class="fu-input"
                                    value="{{ old('code') }}"
                                    placeholder="Example: NET"
                                    maxlength="100"
                                >

                                <div class="fu-field-help">
                                    Optional internal identification code.
                                </div>

                            </div>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="col-12">

                            <div class="fu-form-group mb-0">

                                <label
                                    for="description"
                                    class="fu-form-label"
                                >
                                    Description
                                </label>

                                <textarea
                                    id="description"
                                    name="description"
                                    class="fu-textarea"
                                    placeholder="Enter the purpose and responsibilities of this Function Unit..."
                                >{{ old('description') }}</textarea>

                                <div class="fu-field-help">
                                    Provide a brief description of the Function Unit's
                                    responsibilities and purpose.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     STATUS
                ================================================== --}}
                <div class="fu-section">

                    <div class="fu-section-title">

                        <div class="fu-section-title-icon">
                            <i class="bi bi-toggle-on"></i>
                        </div>

                        Unit Status

                    </div>

                    <div class="fu-active-box">

                        <input
                            type="checkbox"
                            id="is_active"
                            name="is_active"
                            value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                        >

                        <label
                            for="is_active"
                            class="fu-active-content"
                        >

                            <div class="fu-active-title">
                                Active Function Unit
                            </div>

                            <div class="fu-active-description">
                                This Function Unit will be available for inventory
                                assignment and asset management.
                            </div>

                        </label>

                    </div>

                </div>


                {{-- =================================================
                     ACTIONS
                ================================================== --}}
                <div class="fu-form-actions">

                    <a
                        href="{{ route('admin.function-units.index') }}"
                        class="btn fu-btn fu-btn-secondary"
                    >
                        <i class="bi bi-x-lg me-2"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn fu-btn fu-btn-primary"
                    >
                        <i class="bi bi-check-lg me-2"></i>
                        Register Function Unit
                    </button>

                </div>

            </form>

        </div>

    </div>

@else

    {{-- =====================================================
         ADMIN / EMPLOYEE — ACCESS RESTRICTED
    ====================================================== --}}
    <div class="fu-form-card">

        <div class="fu-access-card">

            <div class="fu-access-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <h2 class="fu-access-title">
                Function Unit Creation Restricted
            </h2>

            <p class="fu-access-text">
                You do not have permission to create a new Function Unit.
                Function Unit master records can only be created by a
                Super Admin.
            </p>

            <div class="fu-access-notice">

                <i class="bi bi-info-circle-fill"></i>

                <span>
                    <strong>View and Search Access:</strong>
                    Your account can view Function Unit records and use
                    the search and status filters, but adding, editing,
                    activating and deactivating Function Units is restricted
                    to Super Admin users.
                </span>

            </div>

            <a
                href="{{ route('admin.function-units.index') }}"
                class="fu-access-back"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Function Units
            </a>

        </div>

    </div>

@endif


</div>

@endsection