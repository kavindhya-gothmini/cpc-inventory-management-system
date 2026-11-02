@extends('layouts.admin')

@section('title', 'Edit Department | CPC Inventory Management System')

@section('content')

<style>
/* =========================================================
   CPC DEPARTMENT EDIT PAGE
========================================================= */

.department-edit-page {
    min-height: calc(100vh - 72px);
    padding: 28px;
    background: #f5f7fb;
}

/* =========================================================
   PAGE HEADER
========================================================= */

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
    color: #6b7280;
    font-size: 13px;
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

/* =========================================================
   BACK BUTTON
========================================================= */

.back-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 16px;
    border: 1px solid #dbe1e8;
    border-radius: 8px;
    background: #ffffff;
    color: #374151;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transition: all .2s ease;
}

.back-button:hover {
    border-color: #0d6efd;
    color: #0d6efd;
    background: #f8fbff;
}

/* =========================================================
   MAIN CARD
========================================================= */

.form-card {
    max-width: 1000px;
    margin: 0 auto;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
}

/* =========================================================
   CARD HEADER
========================================================= */

.form-card-header {
    padding: 22px 26px;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(
        135deg,
        #ffffff 0%,
        #f8fbff 100%
    );
}

.card-heading {
    display: flex;
    align-items: center;
    gap: 13px;
}

.card-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 10px;
    background: #eaf2ff;
    color: #0d6efd;
    font-size: 20px;
}

.card-heading h2 {
    margin: 0;
    color: #172033;
    font-size: 18px;
    font-weight: 700;
}

.card-heading p {
    margin: 4px 0 0;
    color: #6b7280;
    font-size: 13px;
}

/* =========================================================
   ACCESS RESTRICTED
========================================================= */

.access-restricted {
    margin: 24px 26px;
    padding: 18px 20px;
    border: 1px solid #fde68a;
    border-left: 4px solid #f59e0b;
    border-radius: 9px;
    background: #fffbeb;
}

.access-restricted-header {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #92400e;
    font-size: 14px;
    font-weight: 700;
}

.access-restricted-header i {
    font-size: 18px;
}

.access-restricted-message {
    margin: 8px 0 0 28px;
    color: #78350f;
    font-size: 13px;
    line-height: 1.6;
}

.access-restricted-note {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin: 15px 0 0 28px;
    padding: 11px 13px;
    border: 1px solid #dbeafe;
    border-radius: 8px;
    background: #eff6ff;
    color: #1e40af;
    font-size: 12px;
    line-height: 1.5;
}

.access-restricted-note i {
    flex-shrink: 0;
    margin-top: 1px;
}

/* =========================================================
   READ ONLY RECORD
========================================================= */

.read-only-body {
    padding: 28px 26px;
}

.read-only-record {
    margin-bottom: 25px;
    padding: 15px 17px;
    border: 1px solid #dbeafe;
    border-radius: 8px;
    background: #eff6ff;
    color: #1e40af;
    font-size: 13px;
}

.read-only-record strong {
    color: #1e3a8a;
}

.read-only-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.read-only-field {
    min-width: 0;
}

.read-only-field.full-width {
    grid-column: 1 / -1;
}

.read-only-label {
    display: block;
    margin-bottom: 7px;
    color: #6b7280;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.read-only-value {
    min-height: 43px;
    padding: 11px 13px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f8fafc;
    color: #1f2937;
    font-size: 14px;
    line-height: 1.5;
}

.read-only-value.description-value {
    min-height: 100px;
    white-space: pre-wrap;
}

.read-only-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

.read-only-status.active {
    background: #dcfce7;
    color: #166534;
}

.read-only-status.inactive {
    background: #f3f4f6;
    color: #6b7280;
}

.read-only-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

/* =========================================================
   ERROR BOX
========================================================= */

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
    font-size: 14px;
    font-weight: 700;
}

.error-box ul {
    margin: 9px 0 0;
    padding-left: 22px;
    font-size: 13px;
}

.error-box li {
    margin-bottom: 4px;
}

/* =========================================================
   FORM BODY
========================================================= */

.form-body {
    padding: 28px 26px;
}

.current-record {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px;
    padding: 13px 15px;
    border: 1px solid #dbeafe;
    border-radius: 8px;
    background: #eff6ff;
    color: #1e40af;
    font-size: 13px;
}

.current-record-icon {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 6px;
    background: #dbeafe;
    font-size: 13px;
}

.current-record strong {
    color: #1e3a8a;
}

/* =========================================================
   FORM SECTIONS
========================================================= */

.form-section {
    margin-bottom: 30px;
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

.section-number {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 7px;
    background: #f0f6ff;
    color: #0d6efd;
    font-size: 11px;
    font-weight: 700;
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

/* =========================================================
   FORM GRID
========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.form-group {
    min-width: 0;
}

.full-width {
    grid-column: 1 / -1;
}

/* =========================================================
   LABELS
========================================================= */

.form-label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
}

.required {
    margin-left: 2px;
    color: #dc3545;
}

/* =========================================================
   INPUTS
========================================================= */

.form-control {
    width: 100%;
    min-height: 43px;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    outline: none;
    background: #ffffff;
    color: #1f2937;
    font-family: inherit;
    font-size: 14px;
    transition:
        border-color .2s ease,
        box-shadow .2s ease,
        background .2s ease;
}

.form-control:hover {
    border-color: #b8c0cc;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, .11);
}

select.form-control {
    cursor: pointer;
}

textarea.form-control {
    min-height: 120px;
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

/* =========================================================
   STATUS
========================================================= */

.status-box {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: #f8fafc;
}

.status-checkbox {
    width: 18px;
    height: 18px;
    margin: 0;
    flex-shrink: 0;
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
    line-height: 1.5;
}

/* =========================================================
   FORM FOOTER
========================================================= */

.form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 20px 26px;
    border-top: 1px solid #e5e7eb;
    background: #fafbfc;
}

/* =========================================================
   BUTTONS
========================================================= */

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
        background .2s ease,
        border-color .2s ease,
        box-shadow .2s ease,
        transform .15s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-secondary {
    border-color: #d1d5db;
    background: #ffffff;
    color: #374151;
}

.btn-secondary:hover {
    border-color: #b8c0cc;
    background: #f8fafc;
}

.btn-primary {
    border-color: #0d6efd;
    background: #0d6efd;
    color: #ffffff;
    box-shadow: 0 3px 8px rgba(13, 110, 253, .18);
}

.btn-primary:hover {
    border-color: #0b5ed7;
    background: #0b5ed7;
    box-shadow: 0 5px 12px rgba(13, 110, 253, .23);
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .department-edit-page {
        padding: 20px 15px;
    }

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .back-button {
        width: 100%;
    }

    .form-grid,
    .read-only-grid {
        grid-template-columns: 1fr;
    }

    .full-width,
    .read-only-field.full-width {
        grid-column: auto;
    }

    .form-card-header,
    .form-body,
    .form-footer,
    .read-only-body {
        padding-left: 20px;
        padding-right: 20px;
    }

    .error-box,
    .access-restricted {
        margin-left: 20px;
        margin-right: 20px;
    }

    .form-footer,
    .read-only-footer {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .form-footer .btn,
    .read-only-footer .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {

    .department-edit-page {
        padding: 15px 10px;
    }

    .page-header h1 {
        font-size: 22px;
    }

    .form-card {
        border-radius: 9px;
    }

    .card-heading {
        align-items: flex-start;
    }

    .card-icon {
        flex-shrink: 0;
    }

    .access-restricted {
        padding: 15px;
    }

    .access-restricted-message,
    .access-restricted-note {
        margin-left: 0;
    }
}
</style>

@php
    $currentUser = auth()->user();
    $isSuperAdmin = $currentUser && $currentUser->role === 'super_admin';
@endphp

<div class="department-edit-page">

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

                <a href="{{ route('admin.departments.index') }}">
                    Departments
                </a>

                <span class="breadcrumb-separator">/</span>

                <span>Edit</span>

            </div>

            <h1>
                Edit Department
            </h1>

            <p>
                Update department information and operational status.
            </p>

        </div>

        <a
            href="{{ route('admin.departments.index') }}"
            class="back-button"
        >
            <i class="bi bi-arrow-left"></i>
            Back to Departments
        </a>

    </div>


    {{-- =====================================================
         MAIN CARD
    ====================================================== --}}

    <div class="form-card">

        {{-- Card Header --}}

        <div class="form-card-header">

            <div class="card-heading">

                <div class="card-icon">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>

                <div>

                    <h2>
                        Department Information
                    </h2>

                    <p>
                        Modify the registered CPC department details.
                    </p>

                </div>

            </div>

        </div>


        {{-- =================================================
             ADMIN / EMPLOYEE READ-ONLY VIEW
        ================================================== --}}

        @if(!$isSuperAdmin)

            <div class="access-restricted">

                <div class="access-restricted-header">

                    <i class="bi bi-shield-lock-fill"></i>

                    <span>
                        View Only — Department Master Data
                    </span>

                </div>

                <p class="access-restricted-message">
                    Only <strong>Super Admin</strong> users can edit department
                    master data. Your current account has view and search access only.
                </p>

                <div class="access-restricted-note">

                    <i class="bi bi-info-circle-fill"></i>

                    <span>
                        Admin and Employee users can view department information,
                        but cannot change the department name, code, branch,
                        description, or active status.
                    </span>

                </div>

            </div>


            {{-- Read-only department information --}}

            <div class="read-only-body">

                <div class="read-only-record">

                    <strong>Department:</strong>
                    {{ $department->name }}

                    @if($department->code)
                        <span>
                            ({{ $department->code }})
                        </span>
                    @endif

                </div>


                <div class="read-only-grid">

                    {{-- Department Name --}}

                    <div class="read-only-field">

                        <span class="read-only-label">
                            Department Name
                        </span>

                        <div class="read-only-value">
                            {{ $department->name ?: '—' }}
                        </div>

                    </div>


                    {{-- Department Code --}}

                    <div class="read-only-field">

                        <span class="read-only-label">
                            Department Code
                        </span>

                        <div class="read-only-value">
                            {{ $department->code ?: '—' }}
                        </div>

                    </div>


                    {{-- Branch --}}

                    <div class="read-only-field">

                        <span class="read-only-label">
                            Branch
                        </span>

                        <div class="read-only-value">

                            @if($department->branch)

                                {{ $department->branch->name }}

                                @if($department->branch->code)
                                    ({{ $department->branch->code }})
                                @endif

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    {{-- Status --}}

                    <div class="read-only-field">

                        <span class="read-only-label">
                            Status
                        </span>

                        <div class="read-only-value">

                            @if($department->is_active)

                                <span class="read-only-status active">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Active
                                </span>

                            @else

                                <span class="read-only-status inactive">
                                    <i class="bi bi-x-circle-fill"></i>
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Description --}}

                    <div class="read-only-field full-width">

                        <span class="read-only-label">
                            Description
                        </span>

                        <div class="read-only-value description-value">
                            {{ $department->description ?: 'No description provided.' }}
                        </div>

                    </div>

                </div>


                <div class="read-only-footer">

                    <a
                        href="{{ route('admin.departments.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Back to Departments
                    </a>

                </div>

            </div>

        @else

            {{-- =================================================
                 SUPER ADMIN EDIT FORM
            ================================================== --}}

            @if ($errors->any())

                <div class="error-box">

                    <div class="error-title">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Please correct the following errors
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
                action="{{ route('admin.departments.update', $department) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="form-body">

                    {{-- Current Record --}}

                    <div class="current-record">

                        <div class="current-record-icon">
                            <i class="bi bi-pencil-fill"></i>
                        </div>

                        <div>
                            Editing Department:
                            <strong>{{ $department->name }}</strong>
                        </div>

                    </div>


                    {{-- =========================================
                         SECTION 01
                    ========================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-number">
                                01
                            </div>

                            <div>

                                <h3>
                                    Department Details
                                </h3>

                                <p>
                                    Basic identification information.
                                </p>

                            </div>

                        </div>


                        <div class="form-grid">

                            {{-- Department Name --}}

                            <div class="form-group">

                                <label
                                    for="name"
                                    class="form-label"
                                >
                                    Department Name
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $department->name) }}"
                                    placeholder="Enter department name"
                                    required
                                    autofocus
                                >

                                @error('name')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Department Code --}}

                            <div class="form-group">

                                <label
                                    for="code"
                                    class="form-label"
                                >
                                    Department Code
                                </label>

                                <input
                                    type="text"
                                    id="code"
                                    name="code"
                                    class="form-control"
                                    value="{{ old('code', $department->code) }}"
                                    placeholder="e.g. IT"
                                    maxlength="100"
                                    autocomplete="off"
                                >

                                <span class="form-help">
                                    Use a unique code to identify this department.
                                </span>

                                @error('code')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Description --}}

                            <div class="form-group full-width">

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
                                    placeholder="Enter department description..."
                                    maxlength="1000"
                                >{{ old('description', $department->description) }}</textarea>

                                @error('description')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- =========================================
                         SECTION 02
                    ========================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-number">
                                02
                            </div>

                            <div>

                                <h3>
                                    Branch Assignment
                                </h3>

                                <p>
                                    Select the CPC branch associated with this department.
                                </p>

                            </div>

                        </div>


                        <div class="form-group">

                            <label
                                for="branch_id"
                                class="form-label"
                            >
                                Branch
                                <span class="required">*</span>
                            </label>

                            <select
                                id="branch_id"
                                name="branch_id"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    -- Select Branch --
                                </option>

                                @foreach ($branches as $branch)

                                    <option
                                        value="{{ $branch->id }}"
                                        {{ old('branch_id', $department->branch_id) == $branch->id ? 'selected' : '' }}
                                    >

                                        {{ $branch->name }}

                                        @if($branch->code)
                                            ({{ $branch->code }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <span class="form-help">
                                The selected branch determines where this department is registered.
                            </span>

                            @error('branch_id')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- =========================================
                         SECTION 03
                    ========================================== --}}

                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-number">
                                03
                            </div>

                            <div>

                                <h3>
                                    Department Status
                                </h3>

                                <p>
                                    Control whether this department is currently active.
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
                                {{ old('is_active', $department->is_active) ? 'checked' : '' }}
                            >

                            <div class="status-content">

                                <label
                                    for="is_active"
                                    class="status-label"
                                >
                                    Active Department
                                </label>

                                <span class="status-description">
                                    Active departments can be used for inventory
                                    assignments and related CPC IT records.
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


                {{-- =================================================
                     FORM ACTIONS
                ================================================== --}}

                <div class="form-footer">

                    <a
                        href="{{ route('admin.departments.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg"></i>
                        Update Department
                    </button>

                </div>

            </form>

        @endif

    </div>

</div>

@endsection