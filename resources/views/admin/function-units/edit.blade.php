@extends('layouts.admin')

@section('title', 'Edit Function Unit | CPC IT Asset Management')

@section('content')

@php
    $currentUser = auth()->user();
    $isSuperAdmin = $currentUser && $currentUser->role === 'super_admin';
@endphp

<style>
    /* =========================================================
       CPC FUNCTION UNIT - EDIT PAGE
    ========================================================= */

    .function-unit-page {
        width: 100%;
        padding: 24px;
        color: #1f2937;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .fu-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .fu-header-left {
        min-width: 0;
    }

    .fu-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 8px;
        color: #6b7280;
        font-size: 13px;
    }

    .fu-breadcrumb a {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 600;
    }

    .fu-breadcrumb a:hover {
        text-decoration: underline;
    }

    .fu-page-title {
        margin: 0;
        color: #172033;
        font-size: 28px;
        font-weight: 700;
        line-height: 1.25;
    }

    .fu-page-subtitle {
        margin: 7px 0 0;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
    }

    /* =========================================================
       BACK BUTTON
    ========================================================= */

    .fu-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 15px;
        border: 1px solid #dce2ea;
        border-radius: 8px;
        background: #ffffff;
        color: #374151;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        transition: all .2s ease;
    }

    .fu-back-btn:hover {
        border-color: #0d6efd;
        background: #f8fbff;
        color: #0d6efd;
    }

    /* =========================================================
       INFORMATION BANNER
    ========================================================= */

    .fu-info-banner {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        margin-bottom: 20px;
        border: 1px solid #dbeafe;
        border-left: 4px solid #0d6efd;
        border-radius: 10px;
        background: linear-gradient(
            135deg,
            #f8fbff 0%,
            #eef6ff 100%
        );
    }

    .fu-info-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #0d6efd;
        color: #ffffff;
        font-size: 17px;
        font-weight: 700;
    }

    .fu-info-content {
        min-width: 0;
    }

    .fu-info-title {
        margin: 0 0 3px;
        color: #172033;
        font-size: 14px;
        font-weight: 700;
    }

    .fu-info-text {
        margin: 0;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================================================
       READ ONLY NOTICE
    ========================================================= */

    .fu-readonly-notice {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 15px 17px;
        margin-bottom: 20px;
        border: 1px solid #fde68a;
        border-left: 4px solid #f59e0b;
        border-radius: 10px;
        background: #fffbeb;
        color: #78350f;
    }

    .fu-readonly-icon {
        width: 28px;
        height: 28px;
        flex: 0 0 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f59e0b;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
    }

    .fu-readonly-content {
        min-width: 0;
    }

    .fu-readonly-title {
        margin: 0 0 4px;
        font-size: 13px;
        font-weight: 700;
        color: #78350f;
    }

    .fu-readonly-text {
        margin: 0;
        color: #92400e;
        font-size: 12px;
        line-height: 1.55;
    }

    /* =========================================================
       ACCESS RESTRICTED CARD
    ========================================================= */

    .fu-access-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 390px;
        padding: 35px 25px;
        text-align: center;
    }

    .fu-access-icon {
        width: 68px;
        height: 68px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        border-radius: 18px;
        background: #fff3cd;
        color: #997404;
        font-size: 27px;
    }

    .fu-access-title {
        margin: 0 0 8px;
        color: #172033;
        font-size: 19px;
        font-weight: 700;
    }

    .fu-access-text {
        max-width: 500px;
        margin: 0;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.7;
    }

    .fu-access-role {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 17px;
        padding: 8px 13px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    /* =========================================================
       ERROR BOX
    ========================================================= */

    .fu-error-box {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px 17px;
        margin-bottom: 20px;
        border: 1px solid #f5c2c7;
        border-radius: 10px;
        background: #fff5f5;
        color: #842029;
    }

    .fu-error-icon {
        width: 25px;
        height: 25px;
        flex: 0 0 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #dc3545;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
    }

    .fu-error-content {
        min-width: 0;
    }

    .fu-error-title {
        margin: 0 0 7px;
        font-size: 13px;
        font-weight: 700;
    }

    .fu-error-box ul {
        margin: 0;
        padding-left: 20px;
        font-size: 12px;
        line-height: 1.7;
    }

    /* =========================================================
       MAIN GRID
    ========================================================= */

    .fu-content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 20px;
        align-items: start;
    }

    /* =========================================================
       CARDS
    ========================================================= */

    .fu-form-card,
    .fu-details-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .fu-card-header {
        padding: 19px 22px;
        border-bottom: 1px solid #edf0f3;
    }

    .fu-card-title {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 700;
    }

    .fu-card-description {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    .fu-card-body {
        padding: 24px 22px;
    }

    /* =========================================================
       FORM
    ========================================================= */

    .fu-form-group {
        margin-bottom: 21px;
    }

    .fu-form-group:last-child {
        margin-bottom: 0;
    }

    .fu-label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-size: 13px;
        font-weight: 700;
    }

    .fu-required {
        color: #dc3545;
        margin-left: 2px;
    }

    .fu-input,
    .fu-select,
    .fu-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d5dbe3;
        border-radius: 8px;
        background: #ffffff;
        color: #1f2937;
        font-family: inherit;
        font-size: 14px;
        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .fu-input,
    .fu-select {
        min-height: 44px;
        padding: 10px 13px;
    }

    .fu-textarea {
        min-height: 125px;
        padding: 11px 13px;
        resize: vertical;
        line-height: 1.5;
    }

    .fu-input::placeholder,
    .fu-textarea::placeholder {
        color: #9ca3af;
    }

    .fu-input:hover,
    .fu-select:hover,
    .fu-textarea:hover {
        border-color: #b8c1cc;
    }

    .fu-input:focus,
    .fu-select:focus,
    .fu-textarea:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .10);
    }

    .fu-input-error,
    .fu-select-error,
    .fu-textarea-error {
        border-color: #dc3545 !important;
    }

    .fu-input-error:focus,
    .fu-select-error:focus,
    .fu-textarea-error:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, .10);
    }

    .fu-help {
        margin-top: 6px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    .fu-field-error {
        margin-top: 6px;
        color: #dc3545;
        font-size: 12px;
        font-weight: 500;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .fu-status-box {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px;
        border: 1px solid #e3e7ec;
        border-radius: 9px;
        background: #f8fafc;
    }

    .fu-checkbox {
        width: 19px;
        height: 19px;
        flex: 0 0 19px;
        margin: 0;
        accent-color: #0d6efd;
        cursor: pointer;
    }

    .fu-status-content {
        min-width: 0;
    }

    .fu-status-label {
        display: block;
        margin: 0 0 3px;
        color: #1f2937;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .fu-status-help {
        margin: 0;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .fu-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding-top: 22px;
        margin-top: 22px;
        border-top: 1px solid #edf0f3;
    }

    .fu-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 9px 17px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .fu-btn-primary {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
        box-shadow: 0 2px 5px rgba(13, 110, 253, .18);
    }

    .fu-btn-primary:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        box-shadow: 0 4px 10px rgba(13, 110, 253, .22);
        transform: translateY(-1px);
    }

    .fu-btn-secondary {
        background: #ffffff;
        border-color: #d5dbe3;
        color: #4b5563;
    }

    .fu-btn-secondary:hover {
        background: #f8fafc;
        border-color: #9ca3af;
        color: #1f2937;
    }

    /* =========================================================
       DETAILS CARD
    ========================================================= */

    .fu-details-list {
        padding: 4px 0;
    }

    .fu-detail-row {
        padding: 14px 18px;
        border-bottom: 1px solid #edf0f3;
    }

    .fu-detail-row:last-child {
        border-bottom: none;
    }

    .fu-detail-label {
        display: block;
        margin-bottom: 4px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .fu-detail-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.5;
        word-break: break-word;
    }

    .fu-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .fu-status-active {
        background: #e8f7ee;
        color: #198754;
    }

    .fu-status-inactive {
        background: #f1f3f5;
        color: #6c757d;
    }

    .fu-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* =========================================================
       RESPONSIVE - TABLET
    ========================================================= */

    @media (max-width: 1000px) {

        .fu-content-grid {
            grid-template-columns: 1fr;
        }

        .fu-details-card {
            order: 2;
        }
    }

    /* =========================================================
       RESPONSIVE - MOBILE
    ========================================================= */

    @media (max-width: 768px) {

        .function-unit-page {
            padding: 18px;
        }

        .fu-page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .fu-back-btn {
            width: fit-content;
        }

        .fu-page-title {
            font-size: 24px;
        }

        .fu-card-header {
            padding: 17px 18px;
        }

        .fu-card-body {
            padding: 20px 18px;
        }

        .fu-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .fu-actions .fu-btn {
            width: 100%;
        }

        .fu-access-card {
            min-height: 320px;
        }
    }

    /* =========================================================
       RESPONSIVE - SMALL MOBILE
    ========================================================= */

    @media (max-width: 480px) {

        .function-unit-page {
            padding: 14px;
        }

        .fu-page-title {
            font-size: 21px;
        }

        .fu-page-subtitle {
            font-size: 13px;
        }

        .fu-info-banner {
            align-items: flex-start;
            gap: 10px;
            padding: 13px;
        }

        .fu-info-icon {
            width: 36px;
            height: 36px;
            flex-basis: 36px;
            font-size: 14px;
        }

        .fu-card-header {
            padding: 16px 15px;
        }

        .fu-card-body {
            padding: 18px 15px;
        }

        .fu-status-box {
            align-items: flex-start;
        }

        .fu-access-card {
            padding: 28px 18px;
        }
    }
</style>

<div class="function-unit-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="fu-page-header">

        <div class="fu-header-left">

            <div class="fu-breadcrumb">

                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>

                <span>/</span>

                <a href="{{ route('admin.function-units.index') }}">
                    Function Units
                </a>

                <span>/</span>

                <span>
                    {{ $isSuperAdmin ? 'Edit' : 'View' }}
                </span>

            </div>

            <h1 class="fu-page-title">
                {{ $isSuperAdmin ? 'Edit Function Unit' : 'View Function Unit' }}
            </h1>

            <p class="fu-page-subtitle">
                {{ $isSuperAdmin
                    ? 'Update the Function Unit information used within the CPC IT Asset Management System.'
                    : 'View the Function Unit information within the CPC IT Asset Management System.'
                }}
            </p>

        </div>

        <a
            href="{{ route('admin.function-units.index') }}"
            class="fu-back-btn"
        >
            ← Back to Function Units
        </a>

    </div>


    {{-- =====================================================
         INFORMATION BANNER
    ====================================================== --}}

    <div class="fu-info-banner">

        <div class="fu-info-icon">
            FU
        </div>

        <div class="fu-info-content">

            <p class="fu-info-title">
                {{ $isSuperAdmin ? 'Updating Function Unit' : 'Function Unit Information' }}
            </p>

            <p class="fu-info-text">
                @if($isSuperAdmin)
                    Modify the Function Unit details below and save the changes.
                    Required fields are marked with an asterisk.
                @else
                    You are viewing the current Function Unit information.
                    Editing and status changes are restricted to Super Admin users.
                @endif
            </p>

        </div>

    </div>


    {{-- =====================================================
         READ ONLY NOTICE
    ====================================================== --}}

    @if(!$isSuperAdmin)

        <div class="fu-readonly-notice">

            <div class="fu-readonly-icon">
                !
            </div>

            <div class="fu-readonly-content">

                <p class="fu-readonly-title">
                    Read-Only Access
                </p>

                <p class="fu-readonly-text">
                    Only Super Admin can add, edit, activate, or deactivate
                    Function Units. Admin and Employee users have view and
                    search access only.
                </p>

            </div>

        </div>

    @endif


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if ($errors->any() && $isSuperAdmin)

        <div class="fu-error-box">

            <div class="fu-error-icon">
                !
            </div>

            <div class="fu-error-content">

                <p class="fu-error-title">
                    Please correct the following errors:
                </p>

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
         CONTENT
    ====================================================== --}}

    <div class="fu-content-grid">

        {{-- =================================================
             SUPER ADMIN EDIT FORM
        ================================================== --}}

        <div class="fu-form-card">

            <div class="fu-card-header">

                <h2 class="fu-card-title">
                    {{ $isSuperAdmin
                        ? 'Function Unit Information'
                        : 'Function Unit Details'
                    }}
                </h2>

                <p class="fu-card-description">
                    {{ $isSuperAdmin
                        ? 'Review and update the organizational details.'
                        : 'Current organizational information.'
                    }}
                </p>

            </div>


            @if($isSuperAdmin)

                <div class="fu-card-body">

                    <form
                        action="{{ route('admin.function-units.update', $functionUnit->id) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PUT')


                        {{-- =================================================
                             DEPARTMENT
                        ================================================== --}}

                        <div class="fu-form-group">

                            <label
                                for="department_id"
                                class="fu-label"
                            >
                                Department
                                <span class="fu-required">*</span>
                            </label>

                            <select
                                name="department_id"
                                id="department_id"
                                class="fu-select @error('department_id') fu-select-error @enderror"
                                required
                            >

                                <option value="">
                                    -- Select Department --
                                </option>

                                @foreach ($departments as $department)

                                    <option
                                        value="{{ $department->id }}"
                                        {{ old('department_id', $functionUnit->department_id) == $department->id ? 'selected' : '' }}
                                    >

                                        {{ $department->name }}

                                        @if ($department->code)
                                            ({{ $department->code }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('department_id')

                                <div class="fu-field-error">
                                    {{ $message }}
                                </div>

                            @else

                                <div class="fu-help">
                                    Select the department responsible for this Function Unit.
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             FUNCTION UNIT NAME
                        ================================================== --}}

                        <div class="fu-form-group">

                            <label
                                for="name"
                                class="fu-label"
                            >
                                Function Unit Name
                                <span class="fu-required">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="fu-input @error('name') fu-input-error @enderror"
                                value="{{ old('name', $functionUnit->name) }}"
                                placeholder="Enter Function Unit name"
                                maxlength="255"
                                autocomplete="off"
                                required
                            >

                            @error('name')

                                <div class="fu-field-error">
                                    {{ $message }}
                                </div>

                            @else

                                <div class="fu-help">
                                    Enter the official name of the Function Unit.
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             FUNCTION UNIT CODE
                        ================================================== --}}

                        <div class="fu-form-group">

                            <label
                                for="code"
                                class="fu-label"
                            >
                                Function Unit Code
                            </label>

                            <input
                                type="text"
                                name="code"
                                id="code"
                                class="fu-input @error('code') fu-input-error @enderror"
                                value="{{ old('code', $functionUnit->code) }}"
                                placeholder="Example: IT-NET"
                                maxlength="100"
                                autocomplete="off"
                            >

                            @error('code')

                                <div class="fu-field-error">
                                    {{ $message }}
                                </div>

                            @else

                                <div class="fu-help">
                                    Use a unique internal code if your organization has one.
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             DESCRIPTION
                        ================================================== --}}

                        <div class="fu-form-group">

                            <label
                                for="description"
                                class="fu-label"
                            >
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                class="fu-textarea @error('description') fu-textarea-error @enderror"
                                placeholder="Enter a description for this Function Unit..."
                            >{{ old('description', $functionUnit->description) }}</textarea>

                            @error('description')

                                <div class="fu-field-error">
                                    {{ $message }}
                                </div>

                            @else

                                <div class="fu-help">
                                    Add any additional information about the Function Unit.
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             STATUS
                        ================================================== --}}

                        <div class="fu-form-group">

                            <label class="fu-label">
                                Function Unit Status
                            </label>

                            <div class="fu-status-box">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    id="is_active"
                                    value="1"
                                    class="fu-checkbox"
                                    {{ old('is_active', $functionUnit->is_active) ? 'checked' : '' }}
                                >

                                <div class="fu-status-content">

                                    <label
                                        for="is_active"
                                        class="fu-status-label"
                                    >
                                        Active Function Unit
                                    </label>

                                    <p class="fu-status-help">
                                        Active Function Units remain available for use
                                        throughout the CPC inventory system.
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             ACTION BUTTONS
                        ================================================== --}}

                        <div class="fu-actions">

                            <a
                                href="{{ route('admin.function-units.index') }}"
                                class="fu-btn fu-btn-secondary"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="fu-btn fu-btn-primary"
                            >
                                ✓ Update Function Unit
                            </button>

                        </div>

                    </form>

                </div>

            @else

                {{-- =================================================
                     READ ONLY DETAILS
                ================================================== --}}

                <div class="fu-card-body">

                    <div class="fu-form-group">

                        <label class="fu-label">
                            Department
                        </label>

                        <div class="fu-input">
                            @if($functionUnit->department)
                                {{ $functionUnit->department->name }}

                                @if($functionUnit->department->code)
                                    ({{ $functionUnit->department->code }})
                                @endif
                            @else
                                Not assigned
                            @endif
                        </div>

                    </div>


                    <div class="fu-form-group">

                        <label class="fu-label">
                            Function Unit Name
                        </label>

                        <div class="fu-input">
                            {{ $functionUnit->name }}
                        </div>

                    </div>


                    <div class="fu-form-group">

                        <label class="fu-label">
                            Function Unit Code
                        </label>

                        <div class="fu-input">
                            {{ $functionUnit->code ?: 'Not specified' }}
                        </div>

                    </div>


                    <div class="fu-form-group">

                        <label class="fu-label">
                            Description
                        </label>

                        <div class="fu-textarea">
                            {{ $functionUnit->description ?: 'No description provided.' }}
                        </div>

                    </div>


                    <div class="fu-form-group">

                        <label class="fu-label">
                            Function Unit Status
                        </label>

                        <div class="fu-status-box">

                            @if($functionUnit->is_active)

                                <span class="fu-status-badge fu-status-active">
                                    <span class="fu-status-dot"></span>
                                    Active
                                </span>

                            @else

                                <span class="fu-status-badge fu-status-inactive">
                                    <span class="fu-status-dot"></span>
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="fu-actions">

                        <a
                            href="{{ route('admin.function-units.index') }}"
                            class="fu-btn fu-btn-secondary"
                        >
                            ← Back to Function Units
                        </a>

                    </div>

                </div>

            @endif

        </div>


        {{-- =================================================
             CURRENT DETAILS
        ================================================== --}}

        <div class="fu-details-card">

            <div class="fu-card-header">

                <h2 class="fu-card-title">
                    Current Details
                </h2>

                <p class="fu-card-description">
                    Existing Function Unit information.
                </p>

            </div>


            <div class="fu-details-list">

                {{-- ID --}}

                <div class="fu-detail-row">

                    <span class="fu-detail-label">
                        Function Unit ID
                    </span>

                    <span class="fu-detail-value">
                        #{{ $functionUnit->id }}
                    </span>

                </div>


                {{-- NAME --}}

                <div class="fu-detail-row">

                    <span class="fu-detail-label">
                        Function Unit
                    </span>

                    <span class="fu-detail-value">
                        {{ $functionUnit->name }}
                    </span>

                </div>


                {{-- DEPARTMENT --}}

                <div class="fu-detail-row">

                    <span class="fu-detail-label">
                        Department
                    </span>

                    <span class="fu-detail-value">

                        @if($functionUnit->department)

                            {{ $functionUnit->department->name }}

                            @if($functionUnit->department->code)
                                ({{ $functionUnit->department->code }})
                            @endif

                        @else
                            Not assigned
                        @endif

                    </span>

                </div>


                {{-- CODE --}}

                <div class="fu-detail-row">

                    <span class="fu-detail-label">
                        Function Unit Code
                    </span>

                    <span class="fu-detail-value">
                        {{ $functionUnit->code ?: 'Not specified' }}
                    </span>

                </div>


                {{-- STATUS --}}

                <div class="fu-detail-row">

                    <span class="fu-detail-label">
                        Status
                    </span>

                    <span class="fu-detail-value">

                        @if($functionUnit->is_active)

                            <span class="fu-status-badge fu-status-active">
                                <span class="fu-status-dot"></span>
                                Active
                            </span>

                        @else

                            <span class="fu-status-badge fu-status-inactive">
                                <span class="fu-status-dot"></span>
                                Inactive
                            </span>

                        @endif

                    </span>

                </div>


                {{-- CREATED --}}

                <div class="fu-detail-row">

                    <span class="fu-detail-label">
                        Created
                    </span>

                    <span class="fu-detail-value">

                        {{ $functionUnit->created_at
                            ? $functionUnit->created_at->format('d M Y')
                            : '—'
                        }}

                    </span>

                </div>


                {{-- UPDATED --}}

                <div class="fu-detail-row">

                    <span class="fu-detail-label">
                        Last Updated
                    </span>

                    <span class="fu-detail-value">

                        {{ $functionUnit->updated_at
                            ? $functionUnit->updated_at->format('d M Y')
                            : '—'
                        }}

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection