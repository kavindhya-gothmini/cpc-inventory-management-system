@extends('layouts.admin')

@section('title', 'Department Details | CPC Inventory Management System')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Department Details Role Access
    |--------------------------------------------------------------------------
    | SUPER ADMIN:
    |   - View
    |   - Search
    |   - Edit
    |   - Manage department
    |
    | ADMIN:
    |   - View only
    |
    | EMPLOYEE:
    |   - View only
    |--------------------------------------------------------------------------
    */

    $currentUser = auth()->user();

    $isSuperAdmin = $currentUser
        && $currentUser->role === 'super_admin';

    /*
     * Only Super Admin can edit department master data.
     */
    $canManageDepartments = $isSuperAdmin;
@endphp

<style>

/* =========================================================
   CPC DEPARTMENT DETAILS PAGE
========================================================= */

.department-show-page {
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
    align-items: flex-start;
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
   HEADER ACTIONS
========================================================= */

.header-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 9px 15px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all .2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-primary {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #ffffff;
    box-shadow: 0 3px 8px rgba(13, 110, 253, .16);
}

.btn-primary:hover {
    background: #0b5ed7;
    border-color: #0b5ed7;
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

/* =========================================================
   READ-ONLY NOTICE
========================================================= */

.department-readonly-notice {
    display: flex;
    align-items: center;
    gap: 9px;

    margin-bottom: 20px;

    padding: 10px 13px;

    border: 1px solid #dbe7f8;
    border-radius: 9px;

    background: #f5f9ff;

    color: #55708f;

    font-size: 11px;
    line-height: 1.5;
}

.department-readonly-notice i {
    color: #2f80ed;
    font-size: 14px;
}

/* =========================================================
   DISABLED ACTION
========================================================= */

.department-disabled-action {
    opacity: 0.48;
    cursor: not-allowed !important;
    filter: grayscale(10%);
    transform: none !important;
    box-shadow: none !important;
    user-select: none;
}

.department-disabled-action:hover,
.department-disabled-action:focus {
    opacity: 0.48;
    transform: none !important;
    box-shadow: none !important;
}

/* =========================================================
   MAIN GRID
========================================================= */

.content-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.7fr) minmax(280px, .8fr);
    gap: 22px;
    max-width: 1200px;
    margin: 0 auto;
}

/* =========================================================
   CARD
========================================================= */

.card {
    overflow: hidden;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 4px 18px rgba(15, 23, 42, .05);
}

.card-header {
    padding: 20px 22px;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(
        135deg,
        #ffffff 0%,
        #f8fbff 100%
    );
}

.card-header-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 9px;
    background: #eaf2ff;
    color: #0d6efd;
    font-size: 19px;
}

.card-header h2 {
    margin: 0;
    color: #172033;
    font-size: 17px;
    font-weight: 700;
}

.card-header p {
    margin: 3px 0 0;
    color: #6b7280;
    font-size: 12px;
}

.card-body {
    padding: 22px;
}

/* =========================================================
   DEPARTMENT IDENTITY
========================================================= */

.identity {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding: 18px;
    border: 1px solid #e0e7ff;
    border-radius: 10px;
    background: linear-gradient(
        135deg,
        #f5f8ff 0%,
        #ffffff 100%
    );
}

.identity-icon {
    width: 58px;
    height: 58px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 12px;
    background: #0d6efd;
    color: #ffffff;
    font-size: 25px;
    box-shadow: 0 5px 12px rgba(13, 110, 253, .18);
}

.identity-info {
    min-width: 0;
}

.identity-info h2 {
    margin: 0 0 5px;
    color: #172033;
    font-size: 20px;
    font-weight: 700;
    word-break: break-word;
}

.identity-code {
    color: #6b7280;
    font-size: 13px;
}

/* =========================================================
   STATUS BADGE
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 9px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-inactive {
    background: #f3f4f6;
    color: #4b5563;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

/* =========================================================
   INFORMATION LIST
========================================================= */

.info-list {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    border-top: 1px solid #edf0f4;
    border-left: 1px solid #edf0f4;
}

.info-item {
    min-width: 0;
    padding: 16px;
    border-right: 1px solid #edf0f4;
    border-bottom: 1px solid #edf0f4;
}

.info-label {
    display: block;
    margin-bottom: 6px;
    color: #8a94a6;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.info-value {
    color: #1f2937;
    font-size: 14px;
    font-weight: 600;
    word-break: break-word;
}

.info-value.muted {
    color: #9ca3af;
    font-weight: 400;
}

.description-box {
    margin-top: 18px;
    padding: 16px;
    border: 1px solid #edf0f4;
    border-radius: 9px;
    background: #fafbfc;
}

.description-box .info-label {
    margin-bottom: 8px;
}

.description-text {
    color: #4b5563;
    font-size: 13px;
    line-height: 1.7;
}

/* =========================================================
   STATISTICS
========================================================= */

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.stat-card {
    padding: 17px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 9px;
    background: #ffffff;
    text-align: center;
}

.stat-number {
    margin-bottom: 5px;
    color: #172033;
    font-size: 24px;
    font-weight: 700;
}

.stat-label {
    color: #7b8494;
    font-size: 11px;
    font-weight: 600;
}

/* =========================================================
   SIDE SUMMARY
========================================================= */

.summary-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 14px 0;
    border-bottom: 1px solid #edf0f4;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    color: #7b8494;
    font-size: 12px;
}

.summary-value {
    color: #1f2937;
    font-size: 13px;
    font-weight: 600;
    text-align: right;
}

/* =========================================================
   QUICK ACTIONS
========================================================= */

.quick-actions {
    display: grid;
    gap: 9px;
}

.quick-action {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #ffffff;
    color: #374151;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all .2s ease;
}

.quick-action:hover {
    border-color: #bfdbfe;
    background: #f8fbff;
    color: #0d6efd;
}

.quick-action-icon {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background: #eff6ff;
    color: #0d6efd;
}

/* =========================================================
   RELATED RECORDS
========================================================= */

.records-card {
    margin-top: 22px;
}

.records-table-wrapper {
    overflow-x: auto;
}

.records-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.records-table th {
    padding: 12px 15px;
    border-bottom: 1px solid #e5e7eb;
    background: #f8fafc;
    color: #6b7280;
    font-size: 11px;
    font-weight: 700;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.records-table td {
    padding: 13px 15px;
    border-bottom: 1px solid #edf0f4;
    color: #374151;
    vertical-align: middle;
}

.records-table tr:last-child td {
    border-bottom: none;
}

.records-table tr:hover td {
    background: #fafcff;
}

.empty-state {
    padding: 30px 20px;
    color: #9ca3af;
    font-size: 13px;
    text-align: center;
}

.table-badge {
    display: inline-flex;
    padding: 4px 8px;
    border-radius: 15px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 10px;
    font-weight: 700;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1000px) {

    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {

    .department-show-page {
        padding: 20px 15px;
    }

    .page-header {
        flex-direction: column;
    }

    .header-actions {
        width: 100%;
    }

    .header-actions .btn {
        flex: 1;
    }

    .info-list {
        grid-template-columns: 1fr;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .card-body,
    .card-header {
        padding: 18px;
    }

    .identity {
        align-items: flex-start;
    }
}

@media (max-width: 480px) {

    .department-show-page {
        padding: 15px 10px;
    }

    .page-header h1 {
        font-size: 22px;
    }

    .header-actions {
        flex-direction: column;
    }

    .header-actions .btn {
        width: 100%;
    }

    .identity-icon {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }

    .identity-info h2 {
        font-size: 17px;
    }
}

</style>

<div class="department-show-page">


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

            <span>Details</span>

        </div>

        <h1>
            Department Details
        </h1>

        <p>
            View department information and related CPC records.
        </p>

    </div>


    <div class="header-actions">

        <a
            href="{{ route('admin.departments.index') }}"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Back
        </a>


        {{-- =================================================
             EDIT - SUPER ADMIN ONLY
        ================================================== --}}

        @if($canManageDepartments)

            <a
                href="{{ route('admin.departments.edit', $department) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil"></i>
                Edit Department
            </a>

        @else

            <button
                type="button"
                class="btn btn-primary department-disabled-action"
                onclick="departmentReadOnlyMessage()"
                title="Only Super Admin can edit departments"
                aria-disabled="true"
            >
                <i class="bi bi-pencil"></i>
                Edit Department
            </button>

        @endif

    </div>

</div>


{{-- =====================================================
     READ-ONLY NOTICE
====================================================== --}}

@if(!$canManageDepartments)

    <div class="department-readonly-notice">

        <i class="bi bi-info-circle-fill"></i>

        <span>
            This department is available for viewing only.
            Only Super Admin can edit or manage department master data.
        </span>

    </div>

@endif


{{-- =====================================================
     CONTENT GRID
====================================================== --}}

<div class="content-grid">


    {{-- =================================================
         MAIN INFORMATION
    ================================================== --}}

    <div>

        <div class="card">

            <div class="card-header">

                <div class="card-header-content">

                    <div class="card-icon">
                        <i class="bi bi-building-fill"></i>
                    </div>

                    <div>

                        <h2>
                            Department Information
                        </h2>

                        <p>
                            Registered department details
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body">

                {{-- Identity --}}

                <div class="identity">

                    <div class="identity-icon">
                        <i class="bi bi-building-fill"></i>
                    </div>

                    <div class="identity-info">

                        <h2>
                            {{ $department->name }}
                        </h2>

                        <div class="identity-code">

                            @if($department->code)

                                Department Code:
                                <strong>{{ $department->code }}</strong>

                            @else

                                No department code assigned

                            @endif

                        </div>


                        @if($department->is_active)

                            <span class="status-badge status-active">
                                <span class="status-dot"></span>
                                Active Department
                            </span>

                        @else

                            <span class="status-badge status-inactive">
                                <span class="status-dot"></span>
                                Inactive Department
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Information Grid --}}

                <div class="info-list">

                    <div class="info-item">

                        <span class="info-label">
                            Department Name
                        </span>

                        <div class="info-value">
                            {{ $department->name }}
                        </div>

                    </div>


                    <div class="info-item">

                        <span class="info-label">
                            Department Code
                        </span>

                        <div class="info-value
                            {{ !$department->code ? 'muted' : '' }}"
                        >
                            {{ $department->code ?: 'Not assigned' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <span class="info-label">
                            Branch
                        </span>

                        <div class="info-value">

                            {{ $department->branch?->name ?? 'Not assigned' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <span class="info-label">
                            Status
                        </span>

                        <div class="info-value">

                            {{ $department->is_active ? 'Active' : 'Inactive' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <span class="info-label">
                            Created
                        </span>

                        <div class="info-value">

                            {{ $department->created_at?->format('d M Y, h:i A') }}

                        </div>

                    </div>


                    <div class="info-item">

                        <span class="info-label">
                            Last Updated
                        </span>

                        <div class="info-value">

                            {{ $department->updated_at?->format('d M Y, h:i A') }}

                        </div>

                    </div>

                </div>


                {{-- Description --}}

                <div class="description-box">

                    <span class="info-label">
                        Description
                    </span>

                    <div class="description-text">

                        @if($department->description)

                            {{ $department->description }}

                        @else

                            <span style="color:#9ca3af;">
                                No description has been provided for this department.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             RELATED RECORDS
        ================================================== --}}

        <div class="card records-card">

            <div class="card-header">

                <div class="card-header-content">

                    <div class="card-icon">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>

                    <div>

                        <h2>
                            Inventory Items
                        </h2>

                        <p>
                            Items currently associated with this department
                        </p>

                    </div>

                </div>

            </div>


            <div class="records-table-wrapper">

                @if($department->items->count())

                    <table class="records-table">

                        <thead>

                            <tr>

                                <th>
                                    Asset Number
                                </th>

                                <th>
                                    Item Type
                                </th>

                                <th>
                                    Make / Model
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($department->items as $item)

                                <tr>

                                    <td>

                                        <strong>
                                            {{ $item->asset_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        {{ $item->itemType?->name ?? '—' }}

                                    </td>


                                    <td>

                                        @if($item->make || $item->model)

                                            {{ $item->make ?: '' }}

                                            @if($item->make && $item->model)
                                                /
                                            @endif

                                            {{ $item->model ?: '' }}

                                        @else

                                            <span style="color:#9ca3af;">
                                                Not specified
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="table-badge">

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $item->status
                                                )
                                            ) }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    <div class="empty-state">

                        No inventory items are currently assigned
                        to this department.

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =================================================
         RIGHT SIDEBAR
    ================================================== --}}

    <div>


        {{-- =================================================
             STATISTICS
        ================================================== --}}

        <div class="card">

            <div class="card-header">

                <div class="card-header-content">

                    <div class="card-icon">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>

                    <div>

                        <h2>
                            Department Summary
                        </h2>

                        <p>
                            Related record counts
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="stats-grid">

                    <div class="stat-card">

                        <div class="stat-number">
                            {{ $department->items->count() }}
                        </div>

                        <div class="stat-label">
                            Items
                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-number">
                            {{ $department->employees->count() }}
                        </div>

                        <div class="stat-label">
                            Employees
                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-number">
                            {{ $department->functionUnits->count() }}
                        </div>

                        <div class="stat-label">
                            Function Units
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             BRANCH SUMMARY
        ================================================== --}}

        <div
            class="card"
            style="margin-top:22px;"
        >

            <div class="card-header">

                <div class="card-header-content">

                    <div class="card-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div>

                        <h2>
                            Branch
                        </h2>

                        <p>
                            Department location
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="summary-item">

                    <span class="summary-label">
                        Branch Name
                    </span>

                    <span class="summary-value">

                        {{ $department->branch?->name ?? 'Not assigned' }}

                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Branch Code
                    </span>

                    <span class="summary-value">

                        {{ $department->branch?->code ?? '—' }}

                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Branch Status
                    </span>

                    <span class="summary-value">

                        @if($department->branch?->is_active)

                            Active

                        @elseif($department->branch)

                            Inactive

                        @else

                            —

                        @endif

                    </span>

                </div>

            </div>

        </div>


        {{-- =================================================
             QUICK ACTIONS
        ================================================== --}}

        <div
            class="card"
            style="margin-top:22px;"
        >

            <div class="card-header">

                <div class="card-header-content">

                    <div class="card-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>

                    <div>

                        <h2>
                            Quick Actions
                        </h2>

                        <p>
                            Department management
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="quick-actions">


                    {{-- EDIT --}}

                    @if($canManageDepartments)

                        <a
                            href="{{ route(
                                'admin.departments.edit',
                                $department
                            ) }}"
                            class="quick-action"
                        >

                            <span class="quick-action-icon">
                                <i class="bi bi-pencil"></i>
                            </span>

                            Edit Department

                        </a>

                    @else

                        <button
                            type="button"
                            class="quick-action department-disabled-action"
                            onclick="departmentReadOnlyMessage()"
                            title="Only Super Admin can edit departments"
                            aria-disabled="true"
                        >

                            <span class="quick-action-icon">
                                <i class="bi bi-pencil"></i>
                            </span>

                            Edit Department

                        </button>

                    @endif


                    {{-- ALL DEPARTMENTS --}}

                    <a
                        href="{{ route('admin.departments.index') }}"
                        class="quick-action"
                    >

                        <span class="quick-action-icon">
                            <i class="bi bi-list-ul"></i>
                        </span>

                        All Departments

                    </a>


                    {{-- BRANCH MANAGEMENT --}}

                    <a
                        href="{{ route('admin.branches.index') }}"
                        class="quick-action"
                    >

                        <span class="quick-action-icon">
                            <i class="bi bi-building"></i>
                        </span>

                        Branch Management

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</div>


{{-- =========================================================
   READ-ONLY MESSAGE
========================================================= --}}

@push('scripts')

<script>

function departmentReadOnlyMessage()
{
    alert(
        'Only Super Admin can edit or manage department master data. Admin and Employee users have view access only.'
    );
}

</script>

@endpush

@endsection