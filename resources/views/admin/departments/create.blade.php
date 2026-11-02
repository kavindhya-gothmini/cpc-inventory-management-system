@extends('layouts.admin')

@section('title', 'Add Department | CPC Inventory Management System')

@section('content')

<style>
    .department-create-page {
        padding-bottom: 30px;
    }

    /* ================================
       PAGE HEADER
    ================================= */
    .department-page-header {
        margin-bottom: 24px;
    }

    .department-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .department-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .department-header-icon {
        width: 50px;
        height: 50px;
        flex: 0 0 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(13, 110, 253, 0.10);
        color: #0d6efd;
        font-size: 22px;
    }

    .department-page-title {
        margin: 0;
        color: #1f2937;
        font-size: 25px;
        font-weight: 700;
        line-height: 1.25;
    }

    .department-page-subtitle {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.5;
    }

    .department-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 15px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #374151;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .department-back-btn:hover {
        background: #f8fafc;
        border-color: #9ca3af;
        color: #111827;
        text-decoration: none;
    }

    /* ================================
       BREADCRUMB
    ================================= */
    .department-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 20px;
        color: #6b7280;
        font-size: 13px;
    }

    .department-breadcrumb a {
        color: #0d6efd;
        text-decoration: none;
    }

    .department-breadcrumb a:hover {
        text-decoration: underline;
    }

    .department-breadcrumb i {
        color: #9ca3af;
        font-size: 10px;
    }

    /* ================================
       ACCESS RESTRICTED
    ================================= */
    .department-access-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
    }

    .department-access-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .department-access-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(255, 193, 7, 0.12);
        color: #b58105;
        font-size: 19px;
    }

    .department-access-title {
        margin: 0;
        color: #1f2937;
        font-size: 17px;
        font-weight: 700;
    }

    .department-access-body {
        padding: 26px 24px;
    }

    .department-access-message {
        margin: 0;
        color: #4b5563;
        font-size: 14px;
        line-height: 1.7;
    }

    .department-access-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 18px;
        padding: 13px 15px;
        border: 1px solid #dbeafe;
        border-radius: 8px;
        background: #eff6ff;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.6;
    }

    .department-access-note i {
        flex: 0 0 auto;
        margin-top: 2px;
    }

    .department-access-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 24px;
    }

    /* ================================
       ERROR BOX
    ================================= */
    .department-error-box {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 22px;
        padding: 15px 17px;
        background: #fff5f5;
        border: 1px solid #f5c2c7;
        border-left: 4px solid #dc3545;
        border-radius: 9px;
        color: #842029;
    }

    .department-error-icon {
        flex: 0 0 auto;
        margin-top: 1px;
        font-size: 18px;
    }

    .department-error-content {
        min-width: 0;
    }

    .department-error-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
    }

    .department-error-list {
        margin: 7px 0 0;
        padding-left: 18px;
        font-size: 13px;
        line-height: 1.5;
    }

    .department-error-list li {
        margin-bottom: 3px;
    }

    /* ================================
       FORM CARD
    ================================= */
    .department-form-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
    }

    .department-card-header {
        padding: 20px 24px;
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
    }

    .department-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: #0b3d62;
        font-size: 17px;
        font-weight: 700;
    }

    .department-section-title i {
        color: #0d6efd;
        font-size: 18px;
    }

    .department-section-description {
        margin: 5px 0 0 28px;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.5;
    }

    .department-card-body {
        padding: 26px 24px;
    }

    /* ================================
       FORM
    ================================= */
    .department-form-group {
        margin-bottom: 21px;
    }

    .department-form-group:last-child {
        margin-bottom: 0;
    }

    .department-form-label {
        display: block;
        margin-bottom: 7px;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
    }

    .department-required {
        color: #dc3545;
    }

    .department-input,
    .department-select,
    .department-textarea {
        display: block;
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #1f2937;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.5;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background-color 0.2s ease;
    }

    .department-input::placeholder,
    .department-textarea::placeholder {
        color: #9ca3af;
    }

    .department-input:hover,
    .department-select:hover,
    .department-textarea:hover {
        border-color: #9ca3af;
    }

    .department-input:focus,
    .department-select:focus,
    .department-textarea:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
    }

    .department-select {
        min-height: 44px;
        cursor: pointer;
    }

    .department-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .department-help-text {
        display: block;
        margin-top: 6px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    /* ================================
       REQUIRED BRANCH NOTICE
    ================================= */
    .department-branch-info {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 10px;
        padding: 11px 13px;
        border: 1px solid #dbeafe;
        border-radius: 8px;
        background: #eff6ff;
        color: #1e40af;
        font-size: 12px;
        line-height: 1.5;
    }

    .department-branch-info i {
        flex: 0 0 auto;
        margin-top: 1px;
    }

    /* ================================
       ACTIVE STATUS
    ================================= */
    .department-status-box {
        padding: 15px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        background: #f8fafc;
    }

    .department-checkbox-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .department-checkbox {
        width: 17px;
        height: 17px;
        flex: 0 0 17px;
        margin-top: 1px;
        cursor: pointer;
        accent-color: #0d6efd;
    }

    .department-checkbox-label {
        margin: 0;
        color: #374151;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        cursor: pointer;
    }

    .department-status-help {
        margin: 5px 0 0 27px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    /* ================================
       FORM ACTIONS
    ================================= */
    .department-form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .department-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 10px 18px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .department-btn-secondary {
        background: #ffffff;
        border-color: #d1d5db;
        color: #4b5563;
    }

    .department-btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #1f2937;
    }

    .department-btn-primary {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
        box-shadow: 0 2px 5px rgba(13, 110, 253, 0.18);
    }

    .department-btn-primary:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 9px rgba(13, 110, 253, 0.22);
    }

    /* ================================
       VALIDATION
    ================================= */
    .department-input.is-invalid,
    .department-select.is-invalid,
    .department-textarea.is-invalid {
        border-color: #dc3545;
    }

    .department-input.is-invalid:focus,
    .department-select.is-invalid:focus,
    .department-textarea.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.10);
    }

    /* ================================
       TABLET
    ================================= */
    @media (max-width: 768px) {

        .department-header-content {
            align-items: stretch;
        }

        .department-header-left {
            align-items: flex-start;
        }

        .department-back-btn {
            width: 100%;
        }

        .department-card-header,
        .department-card-body,
        .department-access-header,
        .department-access-body {
            padding: 20px;
        }

        .department-page-title {
            font-size: 22px;
        }
    }

    /* ================================
       MOBILE
    ================================= */
    @media (max-width: 576px) {

        .department-create-page {
            padding-bottom: 20px;
        }

        .department-page-title {
            font-size: 20px;
        }

        .department-page-subtitle {
            font-size: 13px;
        }

        .department-header-left {
            gap: 11px;
        }

        .department-header-icon {
            width: 44px;
            height: 44px;
            flex-basis: 44px;
            font-size: 19px;
            border-radius: 10px;
        }

        .department-card-header,
        .department-card-body,
        .department-access-header,
        .department-access-body {
            padding: 17px;
        }

        .department-section-title {
            font-size: 16px;
        }

        .department-section-description {
            margin-left: 0;
            margin-top: 7px;
        }

        .department-form-group {
            margin-bottom: 18px;
        }

        .department-form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .department-btn {
            width: 100%;
        }

        .department-error-box {
            padding: 13px;
        }

        .department-breadcrumb {
            font-size: 12px;
        }

        .department-access-actions {
            justify-content: stretch;
        }

        .department-access-actions .department-btn {
            width: 100%;
        }
    }
</style>

@php
    $currentUser = auth()->user();
    $isSuperAdmin = $currentUser && $currentUser->role === 'super_admin';
@endphp

<div class="department-create-page">

    {{-- Breadcrumb --}}
    <div class="department-breadcrumb">

        <a href="{{ route('admin.departments.index') }}">
            Department Management
        </a>

        <i class="bi bi-chevron-right"></i>

        <span>Add Department</span>

    </div>

    {{-- Page Header --}}
    <div class="department-page-header">

        <div class="department-header-content">

            <div class="department-header-left">

                <div class="department-header-icon">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>

                <div>

                    <h1 class="department-page-title">
                        Add New Department
                    </h1>

                    <p class="department-page-subtitle">
                        Register a department under a CPC branch.
                    </p>

                </div>

            </div>

            <a
                href="{{ route('admin.departments.index') }}"
                class="department-back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Departments
            </a>

        </div>

    </div>

    {{-- ============================================================
         SUPER ADMIN ONLY
    ============================================================= --}}
    @if(!$isSuperAdmin)

        <div class="department-access-card">

            <div class="department-access-header">

                <div class="department-access-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <div>

                    <h3 class="department-access-title">
                        Access Restricted
                    </h3>

                </div>

            </div>

            <div class="department-access-body">

                <p class="department-access-message">
                    Department master data can only be added or managed by a
                    <strong>Super Admin</strong>.
                    Your current account has view and search access only.
                </p>

                <div class="department-access-note">

                    <i class="bi bi-info-circle-fill"></i>

                    <span>
                        <strong>Permission:</strong>
                        Admin and Employee users can view and search departments,
                        but only Super Admin can create, edit, activate, or deactivate
                        department records.
                    </span>

                </div>

                <div class="department-access-actions">

                    <a
                        href="{{ route('admin.departments.index') }}"
                        class="department-btn department-btn-secondary"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Back to Departments
                    </a>

                </div>

            </div>

        </div>

    @else

        {{-- ========================================================
             VALIDATION ERRORS
        ========================================================= --}}

        @if($errors->any())

            <div class="department-error-box">

                <div class="department-error-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <div class="department-error-content">

                    <p class="department-error-title">
                        Please correct the following errors:
                    </p>

                    <ul class="department-error-list">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif

        {{-- ========================================================
             MAIN FORM CARD
        ========================================================= --}}

        <div class="department-form-card">

            <div class="department-card-header">

                <h3 class="department-section-title">

                    <i class="bi bi-diagram-3"></i>

                    Department Information

                </h3>

                <p class="department-section-description">
                    Enter the department's branch, identification and organizational details.
                </p>

            </div>

            <div class="department-card-body">

                <form
                    action="{{ route('admin.departments.store') }}"
                    method="POST"
                >

                    @csrf

                    {{-- Branch --}}
                    <div class="department-form-group">

                        <label
                            for="branch_id"
                            class="department-form-label"
                        >
                            Branch
                            <span class="department-required">*</span>
                        </label>

                        <select
                            id="branch_id"
                            name="branch_id"
                            class="department-select @error('branch_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Select Branch --
                            </option>

                            @foreach($branches as $branch)

                                <option
                                    value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                >
                                    {{ $branch->name }}

                                    @if($branch->code)
                                        ({{ $branch->code }})
                                    @endif
                                </option>

                            @endforeach

                        </select>

                        <span class="department-help-text">
                            Select the CPC branch where this department belongs.
                        </span>

                        <div class="department-branch-info">

                            <i class="bi bi-info-circle-fill"></i>

                            <span>
                                A department must be associated with a branch before it can be registered.
                            </span>

                        </div>

                        @error('branch_id')

                            <span class="text-danger small mt-1 d-block">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                    {{-- Department Name --}}
                    <div class="department-form-group">

                        <label
                            for="name"
                            class="department-form-label"
                        >
                            Department Name
                            <span class="department-required">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="department-input @error('name') is-invalid @enderror"
                            placeholder="Enter department name"
                            maxlength="255"
                            autocomplete="organization"
                            required
                        >

                        @error('name')

                            <span class="text-danger small mt-1 d-block">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                    {{-- Department Code --}}
                    <div class="department-form-group">

                        <label
                            for="code"
                            class="department-form-label"
                        >
                            Department Code
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code') }}"
                            class="department-input @error('code') is-invalid @enderror"
                            placeholder="Example: IT"
                            maxlength="50"
                            autocomplete="off"
                        >

                        <span class="department-help-text">
                            Use a short unique code where applicable.
                        </span>

                        @error('code')

                            <span class="text-danger small mt-1 d-block">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                    {{-- Description --}}
                    <div class="department-form-group">

                        <label
                            for="description"
                            class="department-form-label"
                        >
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="department-textarea @error('description') is-invalid @enderror"
                            placeholder="Enter additional information about this department"
                            maxlength="1000"
                        >{{ old('description') }}</textarea>

                        @error('description')

                            <span class="text-danger small mt-1 d-block">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                    {{-- Active Status --}}
                    <div class="department-form-group">

                        <div class="department-status-box">

                            <div class="department-checkbox-wrapper">

                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    class="department-checkbox"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                >

                                <label
                                    for="is_active"
                                    class="department-checkbox-label"
                                >
                                    Department is Active
                                </label>

                            </div>

                            <p class="department-status-help">
                                Inactive departments will not normally be used for new assignments.
                            </p>

                        </div>

                        @error('is_active')

                            <span class="text-danger small mt-1 d-block">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                    {{-- Form Actions --}}
                    <div class="department-form-actions">

                        <a
                            href="{{ route('admin.departments.index') }}"
                            class="department-btn department-btn-secondary"
                        >
                            <i class="bi bi-x-lg"></i>
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="department-btn department-btn-primary"
                        >
                            <i class="bi bi-diagram-3-fill"></i>
                            Register Department
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif

</div>

@endsection