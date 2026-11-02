@extends('layouts.admin')

@section('title', 'Add Branch | CPC Inventory Management System')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Branch Creation Access
    |--------------------------------------------------------------------------
    | ONLY SUPER ADMIN CAN CREATE BRANCHES
    |--------------------------------------------------------------------------
    */

    $currentUser = auth()->user();

    $isSuperAdmin = $currentUser
        && $currentUser->role === 'super_admin';
@endphp

<style>
    .branch-create-page {
        padding-bottom: 30px;
    }

    /* Page Header */
    .branch-page-header {
        margin-bottom: 24px;
    }

    .branch-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .branch-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .branch-header-icon {
        width: 50px;
        height: 50px;
        flex: 0 0 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, 0.10);
        color: #0d6efd;
        font-size: 22px;
    }

    .branch-page-title {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.25;
    }

    .branch-page-subtitle {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.5;
    }

    .branch-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 15px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #374151;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .branch-back-btn:hover {
        background: #f8fafc;
        border-color: #9ca3af;
        color: #111827;
        text-decoration: none;
    }

    /* Breadcrumb */
    .branch-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #6b7280;
    }

    .branch-breadcrumb a {
        color: #0d6efd;
        text-decoration: none;
    }

    .branch-breadcrumb a:hover {
        text-decoration: underline;
    }

    .branch-breadcrumb i {
        font-size: 11px;
        color: #9ca3af;
    }

    /* Error Alert */
    .branch-error-box {
        display: flex;
        gap: 12px;
        background: #fff5f5;
        border: 1px solid #f5c2c7;
        border-left: 4px solid #dc3545;
        border-radius: 9px;
        padding: 15px 17px;
        margin-bottom: 22px;
        color: #842029;
    }

    .branch-error-icon {
        font-size: 18px;
        flex: 0 0 auto;
        margin-top: 1px;
    }

    .branch-error-content {
        min-width: 0;
    }

    .branch-error-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
    }

    .branch-error-list {
        margin: 7px 0 0;
        padding-left: 18px;
        font-size: 13px;
    }

    .branch-error-list li {
        margin-bottom: 3px;
    }

    /* Main Card */
    .branch-form-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .branch-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .branch-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: #0b3d62;
        font-size: 17px;
        font-weight: 700;
    }

    .branch-section-title i {
        color: #0d6efd;
        font-size: 18px;
    }

    .branch-section-description {
        margin: 5px 0 0 28px;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.5;
    }

    .branch-card-body {
        padding: 26px 24px;
    }

    /* Form */
    .branch-form-group {
        margin-bottom: 21px;
    }

    .branch-form-group:last-child {
        margin-bottom: 0;
    }

    .branch-form-label {
        display: block;
        margin-bottom: 7px;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
    }

    .branch-required {
        color: #dc3545;
    }

    .branch-input,
    .branch-textarea {
        width: 100%;
        display: block;
        padding: 11px 13px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #1f2937;
        font-size: 14px;
        font-family: inherit;
        line-height: 1.5;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .branch-input::placeholder,
    .branch-textarea::placeholder {
        color: #9ca3af;
    }

    .branch-input:hover,
    .branch-textarea:hover {
        border-color: #9ca3af;
    }

    .branch-input:focus,
    .branch-textarea:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
    }

    .branch-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .branch-help-text {
        display: block;
        margin-top: 6px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    /* Active Status */
    .branch-status-box {
        padding: 15px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        background: #f8fafc;
    }

    .branch-checkbox-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .branch-checkbox {
        width: 17px;
        height: 17px;
        flex: 0 0 17px;
        margin-top: 1px;
        cursor: pointer;
        accent-color: #0d6efd;
    }

    .branch-checkbox-label {
        margin: 0;
        color: #374151;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        line-height: 1.4;
    }

    .branch-status-help {
        margin: 5px 0 0 27px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    /* Actions */
    .branch-form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .branch-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 10px 18px;
        border-radius: 8px;
        border: 1px solid transparent;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .branch-btn-secondary {
        background: #ffffff;
        border-color: #d1d5db;
        color: #4b5563;
    }

    .branch-btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #1f2937;
    }

    .branch-btn-primary {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
        box-shadow: 0 2px 5px rgba(13, 110, 253, 0.18);
    }

    .branch-btn-primary:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 9px rgba(13, 110, 253, 0.22);
    }

    /* Responsive */
    @media (max-width: 768px) {

        .branch-header-content {
            align-items: stretch;
        }

        .branch-header-left {
            align-items: flex-start;
        }

        .branch-back-btn {
            width: 100%;
        }

        .branch-card-header,
        .branch-card-body {
            padding: 20px;
        }

        .branch-page-title {
            font-size: 22px;
        }
    }

    @media (max-width: 576px) {

        .branch-page-title {
            font-size: 20px;
        }

        .branch-page-subtitle {
            font-size: 13px;
        }

        .branch-header-icon {
            width: 44px;
            height: 44px;
            flex-basis: 44px;
            font-size: 19px;
        }

        .branch-card-header,
        .branch-card-body {
            padding: 17px;
        }

        .branch-section-title {
            font-size: 16px;
        }

        .branch-section-description {
            margin-left: 0;
            margin-top: 7px;
        }

        .branch-form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .branch-btn {
            width: 100%;
        }

        .branch-error-box {
            padding: 13px;
        }
    }
</style>

<div class="branch-create-page">

    {{-- =====================================================
         ACCESS PROTECTION
         Controller already protects this route.
         This additional view guard prevents the form from
         being rendered for non-Super Admin users.
    ====================================================== --}}

    @if(!$isSuperAdmin)

        <div class="branch-form-card">

            <div class="branch-card-body text-center">

                <div
                    style="
                        width: 70px;
                        height: 70px;
                        margin: 0 auto 18px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 18px;
                        background: #fff4e5;
                        color: #d97706;
                        font-size: 28px;
                    "
                >
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <h3
                    style="
                        margin: 0 0 8px;
                        color: #344054;
                        font-size: 18px;
                        font-weight: 700;
                    "
                >
                    Access Restricted
                </h3>

                <p
                    style="
                        max-width: 500px;
                        margin: 0 auto 20px;
                        color: #667085;
                        font-size: 13px;
                        line-height: 1.6;
                    "
                >
                    Only Super Admin can create and manage Branch master data.
                    You have view and search access only.
                </p>

                <a
                    href="{{ route('admin.branches.index') }}"
                    class="branch-btn branch-btn-secondary"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Branches
                </a>

            </div>

        </div>

    @else

        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}

        <div class="branch-breadcrumb">

            <a href="{{ route('admin.branches.index') }}">
                Branch Management
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>
                Add Branch
            </span>

        </div>


        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="branch-page-header">

            <div class="branch-header-content">

                <div class="branch-header-left">

                    <div class="branch-header-icon">
                        <i class="bi bi-building-add"></i>
                    </div>

                    <div>

                        <h1 class="branch-page-title">
                            Add New Branch
                        </h1>

                        <p class="branch-page-subtitle">
                            Register a CPC branch or organizational location.
                        </p>

                    </div>

                </div>

                <a
                    href="{{ route('admin.branches.index') }}"
                    class="branch-back-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Branches
                </a>

            </div>

        </div>


        {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}

        @if($errors->any())

            <div class="branch-error-box">

                <div class="branch-error-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <div class="branch-error-content">

                    <p class="branch-error-title">
                        Please correct the following errors:
                    </p>

                    <ul class="branch-error-list">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif


        {{-- =====================================================
             FORM CARD
        ====================================================== --}}

        <div class="branch-form-card">

            <div class="branch-card-header">

                <h3 class="branch-section-title">

                    <i class="bi bi-building"></i>

                    Branch Information

                </h3>

                <p class="branch-section-description">
                    Enter the organizational and location details for this CPC branch.
                </p>

            </div>


            <div class="branch-card-body">

                <form
                    action="{{ route('admin.branches.store') }}"
                    method="POST"
                >

                    @csrf


                    {{-- =================================================
                         BRANCH NAME
                    ================================================== --}}

                    <div class="branch-form-group">

                        <label
                            for="name"
                            class="branch-form-label"
                        >
                            Branch Name
                            <span class="branch-required">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="branch-input @error('name') is-invalid @enderror"
                            placeholder="Enter branch name"
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


                    {{-- =================================================
                         BRANCH CODE
                    ================================================== --}}

                    <div class="branch-form-group">

                        <label
                            for="code"
                            class="branch-form-label"
                        >
                            Branch Code
                            <span class="branch-required">*</span>
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code') }}"
                            class="branch-input @error('code') is-invalid @enderror"
                            placeholder="Example: 0001"
                            inputmode="numeric"
                            pattern="[0-9]{4}"
                            minlength="4"
                            maxlength="4"
                            autocomplete="off"
                            required
                        >

                        <span class="branch-help-text">
                            Enter a unique 4-digit numeric branch code.
                            Example: 0001, 1001, 2500.
                        </span>

                        @error('code')

                            <span class="text-danger small mt-1 d-block">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- =================================================
                         LOCATION
                    ================================================== --}}

                    <div class="branch-form-group">

                        <label
                            for="location"
                            class="branch-form-label"
                        >
                            Location
                        </label>

                        <input
                            type="text"
                            id="location"
                            name="location"
                            value="{{ old('location') }}"
                            class="branch-input @error('location') is-invalid @enderror"
                            placeholder="Enter branch location"
                            maxlength="255"
                            autocomplete="street-address"
                        >

                        @error('location')

                            <span class="text-danger small mt-1 d-block">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- =================================================
                         DESCRIPTION
                    ================================================== --}}

                    <div class="branch-form-group">

                        <label
                            for="description"
                            class="branch-form-label"
                        >
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="branch-textarea @error('description') is-invalid @enderror"
                            placeholder="Enter additional information about this branch"
                            maxlength="1000"
                        >{{ old('description') }}</textarea>

                        @error('description')

                            <span class="text-danger small mt-1 d-block">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- =================================================
                         ACTIVE STATUS
                    ================================================== --}}

                    <div class="branch-form-group">

                        <div class="branch-status-box">

                            <div class="branch-checkbox-wrapper">

                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    class="branch-checkbox"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                >

                                <label
                                    for="is_active"
                                    class="branch-checkbox-label"
                                >
                                    Branch is Active
                                </label>

                            </div>

                            <p class="branch-status-help">
                                Inactive branches will not normally be used for new assignments.
                            </p>

                        </div>

                        @error('is_active')

                            <span class="text-danger small mt-1 d-block">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- =================================================
                         FORM ACTIONS
                    ================================================== --}}

                    <div class="branch-form-actions">

                        <a
                            href="{{ route('admin.branches.index') }}"
                            class="branch-btn branch-btn-secondary"
                        >

                            <i class="bi bi-x-lg"></i>

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="branch-btn branch-btn-primary"
                        >

                            <i class="bi bi-building-add"></i>

                            Register Branch

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif

</div>


{{-- =========================================================
     BRANCH CODE INPUT RESTRICTION
========================================================= --}}

@if($isSuperAdmin)

<script>
document.addEventListener('DOMContentLoaded', function () {

    const branchCodeInput = document.getElementById('code');

    if (!branchCodeInput) {
        return;
    }

    branchCodeInput.addEventListener('input', function () {

        // Remove anything that is not a number.
        this.value = this.value
            .replace(/\D/g, '')
            .slice(0, 4);

    });

});
</script>

@endif

@endsection