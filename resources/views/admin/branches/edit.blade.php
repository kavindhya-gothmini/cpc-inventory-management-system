@extends('layouts.admin')

@section('title', 'Edit Branch | CPC Inventory Management System')

@section('content')

<style>
/* =========================================================
   CPC BRANCH EDIT PAGE
========================================================= */

.branch-edit-page {
    padding: 28px;
    background: #f5f7fb;
    min-height: calc(100vh - 72px);
}

/* ---------- Page Header ---------- */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.page-header-left {
    min-width: 0;
}

.breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 8px;
    font-size: 13px;
    color: #6b7280;
}

.breadcrumb a {
    color: #0d6efd;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb-separator {
    color: #9ca3af;
}

.page-header h1 {
    margin: 0;
    color: #172033;
    font-size: 27px;
    font-weight: 700;
    letter-spacing: -0.3px;
}

.page-header p {
    margin: 6px 0 0;
    color: #6b7280;
    font-size: 14px;
}

/* ---------- Back Button ---------- */

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    border: 1px solid #dbe1e8;
    border-radius: 8px;
    background: #ffffff;
    color: #374151;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.back-button:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0d6efd;
}

/* ---------- Main Card ---------- */

.form-card {
    max-width: 1000px;
    margin: 0 auto;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
    overflow: hidden;
}

/* ---------- Card Header ---------- */

.form-card-header {
    padding: 22px 26px;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(
        135deg,
        #ffffff 0%,
        #f8fbff 100%
    );
}

.form-card-title {
    display: flex;
    align-items: center;
    gap: 13px;
}

.form-card-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #eaf2ff;
    color: #0d6efd;
    font-size: 19px;
}

.form-card-header h2 {
    margin: 0;
    color: #172033;
    font-size: 18px;
    font-weight: 700;
}

.form-card-header p {
    margin: 4px 0 0;
    color: #6b7280;
    font-size: 13px;
}

/* ---------- Error Box ---------- */

.error-box {
    margin: 24px 26px 0;
    padding: 15px 17px;
    border: 1px solid #f5c2c7;
    border-left: 4px solid #dc3545;
    border-radius: 8px;
    background: #fff5f5;
    color: #842029;
}

.error-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 14px;
}

.error-box ul {
    margin: 9px 0 0;
    padding-left: 22px;
    font-size: 13px;
}

.error-box li {
    margin-bottom: 4px;
}

/* ---------- Form ---------- */

.form-body {
    padding: 28px 26px;
}

.form-section {
    margin-bottom: 28px;
}

.form-section:last-child {
    margin-bottom: 0;
}

.section-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #edf0f4;
}

.section-heading-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #f0f6ff;
    color: #0d6efd;
    font-size: 13px;
}

.section-heading h3 {
    margin: 0;
    color: #1f2937;
    font-size: 15px;
    font-weight: 700;
}

.section-heading p {
    margin: 2px 0 0;
    color: #9ca3af;
    font-size: 12px;
}

/* ---------- Grid ---------- */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.form-group {
    min-width: 0;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

/* ---------- Labels ---------- */

.form-label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
}

.required {
    color: #dc3545;
    margin-left: 2px;
}

/* ---------- Inputs ---------- */

.form-control {
    width: 100%;
    min-height: 43px;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #ffffff;
    color: #1f2937;
    font-family: inherit;
    font-size: 14px;
    outline: none;
    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        background 0.2s ease;
}

.form-control:hover {
    border-color: #b8c0cc;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.11);
}

textarea.form-control {
    min-height: 115px;
    resize: vertical;
}

.form-help {
    display: block;
    margin-top: 6px;
    color: #8a94a6;
    font-size: 11.5px;
    line-height: 1.5;
}

.field-error {
    margin-top: 5px;
    color: #dc3545;
    font-size: 12px;
}

/* ---------- Checkbox ---------- */

.status-box {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: #f8fafc;
}

.status-checkbox {
    width: 18px;
    height: 18px;
    margin: 0;
    cursor: pointer;
    accent-color: #0d6efd;
}

.status-content {
    min-width: 0;
}

.status-label {
    display: block;
    margin: 0;
    color: #1f2937;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.status-description {
    display: block;
    margin-top: 3px;
    color: #7b8494;
    font-size: 12px;
}

/* ---------- Form Footer ---------- */

.form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 20px 26px;
    border-top: 1px solid #e5e7eb;
    background: #fafbfc;
}

/* ---------- Buttons ---------- */

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 18px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition:
        background 0.2s ease,
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        transform 0.15s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-secondary {
    background: #ffffff;
    border-color: #d1d5db;
    color: #374151;
}

.btn-secondary:hover {
    background: #f8fafc;
    border-color: #b8c0cc;
}

.btn-primary {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #ffffff;
    box-shadow: 0 3px 8px rgba(13, 110, 253, 0.18);
}

.btn-primary:hover {
    background: #0b5ed7;
    border-color: #0b5ed7;
    box-shadow: 0 5px 12px rgba(13, 110, 253, 0.23);
}

/* ---------- Current Branch Info ---------- */

.current-record {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 22px;
    padding: 12px 14px;
    border: 1px solid #dbeafe;
    border-radius: 8px;
    background: #eff6ff;
    color: #1e40af;
    font-size: 13px;
}

.current-record strong {
    color: #1e3a8a;
}

/* ---------- Access Notice ---------- */

.access-notice {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin: 24px 26px 0;
    padding: 15px 17px;
    border: 1px solid #fde68a;
    border-left: 4px solid #f59e0b;
    border-radius: 8px;
    background: #fffbeb;
    color: #92400e;
}

.access-notice-icon {
    flex-shrink: 0;
    font-size: 17px;
    line-height: 1.4;
}

.access-notice-content {
    min-width: 0;
}

.access-notice-title {
    margin: 0 0 3px;
    font-size: 13px;
    font-weight: 700;
    color: #78350f;
}

.access-notice-text {
    margin: 0;
    font-size: 12px;
    line-height: 1.5;
    color: #92400e;
}

/* ---------- Responsive ---------- */

@media (max-width: 768px) {

    .branch-edit-page {
        padding: 20px 15px;
    }

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .back-button {
        width: 100%;
        justify-content: center;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: auto;
    }

    .form-card-header,
    .form-body,
    .form-footer {
        padding-left: 20px;
        padding-right: 20px;
    }

    .error-box,
    .access-notice {
        margin-left: 20px;
        margin-right: 20px;
    }

    .form-footer {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .form-footer .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {

    .branch-edit-page {
        padding: 15px 10px;
    }

    .page-header h1 {
        font-size: 22px;
    }

    .form-card {
        border-radius: 9px;
    }

    .form-card-title {
        align-items: flex-start;
    }

    .form-card-icon {
        flex-shrink: 0;
    }
}
</style>


@php
    $isSuperAdmin = auth()->check()
        && auth()->user()->role === 'super_admin';
@endphp


<div class="branch-edit-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="page-header">

        <div class="page-header-left">

            <div class="breadcrumb">

                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>

                <span class="breadcrumb-separator">/</span>

                <a href="{{ route('admin.branches.index') }}">
                    Branches
                </a>

                <span class="breadcrumb-separator">/</span>

                <span>Edit</span>

            </div>

            <h1>
                Edit Branch
            </h1>

            <p>
                Update branch information and operational status.
            </p>

        </div>

        <a
            href="{{ route('admin.branches.show', $branch) }}"
            class="back-button"
        >
            ←
            View Branch
        </a>

    </div>


    {{-- =====================================================
         ACCESS CONTROL
    ====================================================== --}}

    @if(!$isSuperAdmin)

        <div class="form-card">

            <div class="access-notice">

                <div class="access-notice-icon">
                    🔒
                </div>

                <div class="access-notice-content">

                    <p class="access-notice-title">
                        Read-Only Access
                    </p>

                    <p class="access-notice-text">
                        Only the Super Admin can edit branch master data.
                        Admin and Employee users can view and search branch
                        information but cannot modify it.
                    </p>

                </div>

            </div>

            <div class="form-body">

                <div class="current-record">

                    <span>●</span>

                    <span>
                        Branch:
                        <strong>{{ $branch->name }}</strong>
                    </span>

                </div>

                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-heading-icon">
                            01
                        </div>

                        <div>

                            <h3>
                                Branch Information
                            </h3>

                            <p>
                                Branch master data is managed by the Super Admin.
                            </p>

                        </div>

                    </div>

                    <div class="form-grid">

                        <div class="form-group">

                            <label class="form-label">
                                Branch Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $branch->name }}"
                                readonly
                            >

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Branch Code
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $branch->code ?: '—' }}"
                                readonly
                            >

                        </div>

                        <div class="form-group full-width">

                            <label class="form-label">
                                Location
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $branch->location ?: '—' }}"
                                readonly
                            >

                        </div>

                    </div>

                </div>


                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-heading-icon">
                            02
                        </div>

                        <div>

                            <h3>
                                Additional Information
                            </h3>

                            <p>
                                Optional branch description.
                            </p>

                        </div>

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            class="form-control"
                            readonly
                        >{{ $branch->description ?: '—' }}</textarea>

                    </div>

                </div>


                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-heading-icon">
                            03
                        </div>

                        <div>

                            <h3>
                                Branch Status
                            </h3>

                            <p>
                                Current operational status.
                            </p>

                        </div>

                    </div>

                    <div class="status-box">

                        <input
                            type="checkbox"
                            class="status-checkbox"
                            disabled
                            {{ $branch->is_active ? 'checked' : '' }}
                        >

                        <div class="status-content">

                            <span class="status-label">
                                {{ $branch->is_active ? 'Branch is Active' : 'Branch is Inactive' }}
                            </span>

                            <span class="status-description">
                                {{ $branch->is_active
                                    ? 'This branch is currently active.'
                                    : 'This branch is currently inactive.' }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @else

        {{-- =================================================
             SUPER ADMIN EDIT FORM
        ================================================== --}}

        <div class="form-card">

            {{-- Card Header --}}
            <div class="form-card-header">

                <div class="form-card-title">

                    <div class="form-card-icon">
                        🏢
                    </div>

                    <div>

                        <h2>
                            Branch Information
                        </h2>

                        <p>
                            Modify the registered CPC branch details.
                        </p>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 VALIDATION ERRORS
            ================================================== --}}

            @if($errors->any())

                <div class="error-box">

                    <div class="error-title">
                        ⚠
                        Please correct the following errors
                    </div>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =================================================
                 FORM
            ================================================== --}}

            <form
                action="{{ route('admin.branches.update', $branch) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="form-body">

                    {{-- =========================================
                         CURRENT RECORD
                    ========================================== --}}

                    <div class="current-record">

                        <span>●</span>

                        <span>
                            Editing branch:
                            <strong>{{ $branch->name }}</strong>
                        </span>

                    </div>


                    {{-- =========================================
                         BASIC INFORMATION
                    ========================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-heading-icon">
                                01
                            </div>

                            <div>

                                <h3>
                                    Basic Information
                                </h3>

                                <p>
                                    Core branch identification details.
                                </p>

                            </div>

                        </div>


                        <div class="form-grid">

                            {{-- Branch Name --}}
                            <div class="form-group">

                                <label
                                    for="name"
                                    class="form-label"
                                >
                                    Branch Name
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $branch->name) }}"
                                    placeholder="Enter branch name"
                                    required
                                    autofocus
                                >

                                @error('name')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Branch Code --}}
                            <div class="form-group">

                                <label
                                    for="code"
                                    class="form-label"
                                >
                                    Branch Code
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="code"
                                    name="code"
                                    class="form-control @error('code') is-invalid @enderror"
                                    value="{{ old('code', $branch->code) }}"
                                    placeholder="e.g. 0001"
                                    inputmode="numeric"
                                    pattern="[0-9]{4}"
                                    minlength="4"
                                    maxlength="4"
                                    autocomplete="off"
                                    required
                                >

                                <span class="form-help">
                                    Enter a unique 4-digit numeric branch code.
                                    Examples: 0001, 1001, 2500.
                                </span>

                                @error('code')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Location --}}
                            <div class="form-group full-width">

                                <label
                                    for="location"
                                    class="form-label"
                                >
                                    Location
                                </label>

                                <input
                                    type="text"
                                    id="location"
                                    name="location"
                                    class="form-control"
                                    value="{{ old('location', $branch->location) }}"
                                    placeholder="Enter branch location"
                                >

                                @error('location')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- =========================================
                         DESCRIPTION
                    ========================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-heading-icon">
                                02
                            </div>

                            <div>

                                <h3>
                                    Additional Information
                                </h3>

                                <p>
                                    Optional notes and branch description.
                                </p>

                            </div>

                        </div>


                        <div class="form-group">

                            <label
                                for="description"
                                class="form-label"
                            >
                                Description
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                class="form-control"
                                placeholder="Enter a description for this branch..."
                            >{{ old('description', $branch->description) }}</textarea>

                            @error('description')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- =========================================
                         STATUS
                    ========================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-heading-icon">
                                03
                            </div>

                            <div>

                                <h3>
                                    Branch Status
                                </h3>

                                <p>
                                    Control whether this branch is active.
                                </p>

                            </div>

                        </div>


                        <div class="status-box">

                            <input
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                class="status-checkbox"
                                {{ old('is_active', $branch->is_active) ? 'checked' : '' }}
                            >

                            <div class="status-content">

                                <label
                                    for="is_active"
                                    class="status-label"
                                >
                                    Branch is Active
                                </label>

                                <span class="status-description">
                                    Active branches can be used when assigning
                                    inventory items and related records.
                                </span>

                            </div>

                        </div>

                        @error('is_active')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =============================================
                     FORM ACTIONS
                ============================================== --}}

                <div class="form-footer">

                    <a
                        href="{{ route('admin.branches.show', $branch) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        ✓
                        Update Branch
                    </button>

                </div>

            </form>

        </div>

    @endif

</div>


@if($isSuperAdmin)
<script>
document.addEventListener('DOMContentLoaded', function () {

    const branchCodeInput = document.getElementById('code');

    if (!branchCodeInput) {
        return;
    }

    /*
     * Allow numbers only.
     * Maximum 4 digits.
     */
    branchCodeInput.addEventListener('input', function () {

        this.value = this.value
            .replace(/\D/g, '')
            .slice(0, 4);

    });

});
</script>
@endif

@endsection