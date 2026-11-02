@extends('layouts.admin')

@section('title', $employee->name . ' | Employee Profile | CPC Inventory Management System')

@section('content')

<style>
    /* =========================================================
       CPC EMPLOYEE PROFILE
    ========================================================= */

    .employee-profile-page {
        --cpc-primary: #0d6efd;
        --cpc-primary-dark: #0b5ed7;
        --cpc-dark: #1f2937;
        --cpc-text: #374151;
        --cpc-muted: #6b7280;
        --cpc-light: #f8fafc;
        --cpc-border: #e5e7eb;
        --cpc-success: #198754;
        --cpc-warning: #f59e0b;
        --cpc-danger: #dc3545;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .employee-profile-page .page-header {
        margin-bottom: 24px;
    }

    .employee-profile-page .breadcrumb-area {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 8px;
        font-size: 0.76rem;
        color: var(--cpc-muted);
    }

    .employee-profile-page .breadcrumb-area a {
        color: var(--cpc-muted);
        text-decoration: none;
    }

    .employee-profile-page .breadcrumb-area a:hover {
        color: var(--cpc-primary);
    }

    .employee-profile-page .page-title {
        margin: 0;
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--cpc-dark);
    }

    .employee-profile-page .page-subtitle {
        margin: 5px 0 0;
        color: var(--cpc-muted);
        font-size: 0.88rem;
    }

    .employee-profile-page .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* =========================================================
       COMMON CARD
    ========================================================= */

    .employee-profile-page .cpc-card {
        background: #ffffff;
        border: 1px solid var(--cpc-border);
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.045);
        overflow: hidden;
    }

    .employee-profile-page .cpc-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid var(--cpc-border);
        background: #ffffff;
    }

    .employee-profile-page .cpc-card-body {
        padding: 20px;
    }

    .employee-profile-page .section-title {
        margin: 0;
        color: var(--cpc-dark);
        font-size: 0.98rem;
        font-weight: 700;
    }

    .employee-profile-page .section-subtitle {
        margin: 4px 0 0;
        color: var(--cpc-muted);
        font-size: 0.76rem;
    }

    /* =========================================================
       EMPLOYEE PROFILE CARD
    ========================================================= */

    .employee-profile-page .profile-card {
        height: 100%;
    }

    .employee-profile-page .profile-banner {
        position: relative;
        height: 105px;
        background:
            radial-gradient(
                circle at 85% 20%,
                rgba(13, 110, 253, 0.20),
                transparent 30%
            ),
            linear-gradient(
                135deg,
                #eaf3ff 0%,
                #f5f9ff 50%,
                #ffffff 100%
            );
        border-bottom: 1px solid #dce8f7;
    }

    .employee-profile-page .profile-banner::after {
        content: "";
        position: absolute;
        right: 24px;
        bottom: 18px;
        width: 95px;
        height: 95px;
        border: 1px solid rgba(13, 110, 253, 0.10);
        border-radius: 50%;
    }

    .employee-profile-page .profile-content {
        padding: 0 22px 22px;
    }

    .employee-profile-page .profile-avatar {
        width: 82px;
        height: 82px;
        margin-top: -41px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        border: 1px solid #dce3ec;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.10);
        color: var(--cpc-primary);
        font-size: 2rem;
        position: relative;
        z-index: 2;
    }

    .employee-profile-page .profile-name {
        margin-top: 15px;
        color: var(--cpc-dark);
        font-size: 1.28rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .employee-profile-page .profile-designation {
        margin-top: 4px;
        color: var(--cpc-muted);
        font-size: 0.86rem;
    }

    .employee-profile-page .epf-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 12px;
        padding: 6px 10px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid var(--cpc-border);
        color: #4b5563;
        font-size: 0.76rem;
    }

    .employee-profile-page .profile-status {
        margin-top: 12px;
    }

    /* =========================================================
       STATUS BADGES
    ========================================================= */

    .employee-profile-page .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 0.70rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .employee-profile-page .status-active {
        color: #157347;
        background: #eaf7ef;
        border: 1px solid #ccebd8;
    }

    .employee-profile-page .status-inactive {
        color: #6c757d;
        background: #f1f3f5;
        border: 1px solid #dee2e6;
    }

    .employee-profile-page .status-issued {
        color: #0d6efd;
        background: #eaf2ff;
        border: 1px solid #cfe0ff;
    }

    .employee-profile-page .status-returned {
        color: #6c757d;
        background: #f1f3f5;
        border: 1px solid #dee2e6;
    }

    /* =========================================================
       PROFILE DETAILS
    ========================================================= */

    .employee-profile-page .profile-details {
        margin-top: 22px;
        border-top: 1px solid #eef0f3;
    }

    .employee-profile-page .detail-item {
        padding: 13px 0;
        border-bottom: 1px solid #eef0f3;
    }

    .employee-profile-page .detail-item:last-child {
        border-bottom: 0;
    }

    .employee-profile-page .detail-label {
        margin-bottom: 4px;
        color: #8a94a6;
        font-size: 0.70rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .employee-profile-page .detail-value {
        color: var(--cpc-text);
        font-size: 0.84rem;
        font-weight: 600;
        word-break: break-word;
    }

    .employee-profile-page .detail-value a {
        color: var(--cpc-primary);
        text-decoration: none;
    }

    .employee-profile-page .detail-value a:hover {
        text-decoration: underline;
    }

    /* =========================================================
       STATISTICS
    ========================================================= */

    .employee-profile-page .stat-card {
        height: 100%;
        padding: 17px;
        background: #ffffff;
        border: 1px solid var(--cpc-border);
        border-radius: 13px;
        transition: 0.2s ease;
    }

    .employee-profile-page .stat-card:hover {
        border-color: #cbdcf5;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.07);
        transform: translateY(-1px);
    }

    .employee-profile-page .stat-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        border-radius: 11px;
        background: rgba(13, 110, 253, 0.09);
        color: var(--cpc-primary);
        font-size: 1.05rem;
    }

    .employee-profile-page .stat-number {
        margin-bottom: 4px;
        color: var(--cpc-dark);
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .employee-profile-page .stat-label {
        color: var(--cpc-muted);
        font-size: 0.74rem;
    }

    /* =========================================================
       QUICK ACTIONS
    ========================================================= */

    .employee-profile-page .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .employee-profile-page .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .employee-profile-page .table {
        margin-bottom: 0;
        min-width: 850px;
    }

    .employee-profile-page .table > :not(caption) > * > * {
        padding: 13px 14px;
        vertical-align: middle;
        border-bottom-color: #eef0f3;
    }

    .employee-profile-page .table thead th {
        background: #f8fafc;
        color: #6b7280;
        font-size: 0.69rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        white-space: nowrap;
    }

    .employee-profile-page .table tbody td {
        color: var(--cpc-text);
        font-size: 0.80rem;
    }

    .employee-profile-page .table tbody tr:last-child td {
        border-bottom: 0;
    }

    .employee-profile-page .table tbody tr:hover {
        background: #fafcff;
    }

    .employee-profile-page .asset-number {
        color: var(--cpc-primary);
        font-weight: 700;
    }

    .employee-profile-page .item-name {
        color: #273142;
        font-weight: 650;
    }

    .employee-profile-page .item-meta {
        display: block;
        margin-top: 3px;
        color: #8a94a6;
        font-size: 0.70rem;
    }

    .employee-profile-page .serial-number {
        color: #4b5563;
        font-family: monospace;
        font-size: 0.76rem;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .employee-profile-page .empty-state {
        padding: 42px 20px;
        text-align: center;
        color: var(--cpc-muted);
    }

    .employee-profile-page .empty-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 13px;
        border-radius: 14px;
        background: #f1f5f9;
        color: #94a3b8;
        font-size: 1.3rem;
    }

    .employee-profile-page .empty-title {
        margin-bottom: 4px;
        color: #4b5563;
        font-size: 0.90rem;
        font-weight: 700;
    }

    .employee-profile-page .empty-text {
        margin: 0;
        font-size: 0.76rem;
    }

    /* =========================================================
       BUTTONS
    ========================================================= */

    .employee-profile-page .btn {
        border-radius: 8px;
        padding: 8px 13px;
        font-size: 0.80rem;
        font-weight: 600;
    }

    .employee-profile-page .btn-light {
        background: #ffffff;
        border: 1px solid #d7dce2;
        color: #4b5563;
    }

    .employee-profile-page .btn-light:hover {
        background: #f3f4f6;
        border-color: #cbd1d8;
        color: #374151;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .employee-profile-page .alert {
        border-radius: 10px;
        font-size: 0.82rem;
        border: 0;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991.98px) {

        .employee-profile-page .header-actions {
            margin-top: 15px;
        }
    }

    @media (max-width: 767.98px) {

        .employee-profile-page .page-header {
            align-items: flex-start !important;
            flex-direction: column;
        }

        .employee-profile-page .header-actions {
            width: 100%;
        }

        .employee-profile-page .header-actions .btn {
            flex: 1;
        }

        .employee-profile-page .profile-content {
            padding-left: 18px;
            padding-right: 18px;
        }

        .employee-profile-page .cpc-card-body {
            padding: 17px;
        }

        .employee-profile-page .cpc-card-header {
            padding: 15px 17px;
        }
    }

    /* =========================================================
       PRINT
    ========================================================= */

    @media print {

        .employee-profile-page .header-actions,
        .employee-profile-page .quick-actions,
        .employee-profile-page .breadcrumb-area {
            display: none !important;
        }

        .employee-profile-page .cpc-card,
        .employee-profile-page .stat-card {
            box-shadow: none !important;
            break-inside: avoid;
        }

        .employee-profile-page .page-header {
            margin-bottom: 15px;
        }
    }
</style>


<div class="employee-profile-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="page-header d-flex justify-content-between align-items-start">

        <div>

            <div class="breadcrumb-area">

                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2 me-1"></i>
                    Dashboard
                </a>

                <i class="bi bi-chevron-right"></i>

                <a href="{{ route('admin.employees.index') }}">
                    Employees
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>Employee Profile</span>

            </div>

            <h1 class="page-title">
                Employee Profile
            </h1>

            <p class="page-subtitle">
                View employee information, assigned inventory and assignment history.
            </p>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('admin.employees.index') }}"
                class="btn btn-light"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Employee Directory
            </a>

            <a
                href="{{ route('admin.employees.edit', $employee) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>
                Edit Employee
            </a>

        </div>

    </div>


    {{-- =====================================================
         SUCCESS / ERROR MESSAGES
    ====================================================== --}}

    @if (session('success'))

        <div class="alert alert-success mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    @if (session('error'))

        <div class="alert alert-danger mb-4">

            <i class="bi bi-exclamation-circle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- =====================================================
         PROFILE + STATISTICS
    ====================================================== --}}

    <div class="row g-4 mb-4">

        {{-- =================================================
             EMPLOYEE PROFILE
        ================================================== --}}

        <div class="col-lg-5">

            <div class="cpc-card profile-card">

                <div class="profile-banner"></div>

                <div class="profile-content">

                    <div class="profile-avatar">

                        <i class="bi bi-person-fill"></i>

                    </div>


                    <div class="profile-name">
                        {{ $employee->name }}
                    </div>


                    <div class="profile-designation">

                        {{ $employee->designation ?: 'Designation not specified' }}

                    </div>


                    <div class="epf-badge">

                        <i class="bi bi-person-vcard"></i>

                        <span>EPF No.</span>

                        <strong>
                            {{ $employee->epf_no ?: 'Not provided' }}
                        </strong>

                    </div>


                    <div class="profile-status">

                        @if ($employee->is_active)

                            <span class="status-badge status-active">

                                <i class="bi bi-check-circle-fill"></i>

                                Active Employee

                            </span>

                        @else

                            <span class="status-badge status-inactive">

                                <i class="bi bi-dash-circle-fill"></i>

                                Inactive Employee

                            </span>

                        @endif

                    </div>


                    {{-- Employee Details --}}

                    <div class="profile-details">

                        {{-- Branch --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Branch
                            </div>

                            <div class="detail-value">

                                <i class="bi bi-building me-1 text-primary"></i>

                                {{ $employee->branch?->name ?? 'Not assigned' }}

                            </div>

                        </div>


                        {{-- Department --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Department
                            </div>

                            <div class="detail-value">

                                <i class="bi bi-diagram-3 me-1 text-primary"></i>

                                {{ $employee->department?->name ?? 'Not assigned' }}

                            </div>

                        </div>


                        {{-- Function Unit --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Function Unit
                            </div>

                            <div class="detail-value">

                                <i class="bi bi-diagram-2 me-1 text-primary"></i>

                                {{ $employee->functionUnit?->name ?? 'Not assigned' }}

                            </div>

                        </div>


                        {{-- Email --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Official Email
                            </div>

                            <div class="detail-value">

                                @if ($employee->email)

                                    <a href="mailto:{{ $employee->email }}">

                                        <i class="bi bi-envelope me-1"></i>

                                        {{ $employee->email }}

                                    </a>

                                @else

                                    <span class="text-muted">
                                        Not provided
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Phone --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Contact Number
                            </div>

                            <div class="detail-value">

                                @if ($employee->phone)

                                    <a href="tel:{{ $employee->phone }}">

                                        <i class="bi bi-telephone me-1"></i>

                                        {{ $employee->phone }}

                                    </a>

                                @else

                                    <span class="text-muted">
                                        Not provided
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             RIGHT SIDE
        ================================================== --}}

        <div class="col-lg-7">

            <div class="row g-3">

                {{-- Current Assets --}}

                <div class="col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="bi bi-pc-display"></i>
                        </div>

                        <div class="stat-number">

                            {{ $employee->itemAssignments->whereNull('returned_date')->count() }}

                        </div>

                        <div class="stat-label">
                            Currently Assigned Assets
                        </div>

                    </div>

                </div>


                {{-- Total Records --}}

                <div class="col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div class="stat-number">

                            {{ $employee->itemAssignments->count() }}

                        </div>

                        <div class="stat-label">
                            Total Assignment Records
                        </div>

                    </div>

                </div>


                {{-- Returned --}}

                <div class="col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="bi bi-arrow-return-left"></i>
                        </div>

                        <div class="stat-number">

                            {{ $employee->itemAssignments->whereNotNull('returned_date')->count() }}

                        </div>

                        <div class="stat-label">
                            Returned Assets
                        </div>

                    </div>

                </div>


                {{-- Employee Status --}}

                <div class="col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="bi bi-person-check"></i>
                        </div>

                        <div class="stat-number">

                            {{ $employee->is_active ? 'Active' : 'Inactive' }}

                        </div>

                        <div class="stat-label">
                            Current Employee Status
                        </div>

                    </div>

                </div>


                {{-- Quick Actions --}}

                <div class="col-12">

                    <div class="cpc-card">

                        <div class="cpc-card-header">

                            <h2 class="section-title">

                                <i class="bi bi-lightning-charge-fill text-primary me-2"></i>

                                Quick Actions

                            </h2>

                            <p class="section-subtitle">
                                Manage employee information and inventory assignments.
                            </p>

                        </div>


                        <div class="cpc-card-body">

                            <div class="quick-actions">

                                <a
                                    href="{{ route('admin.employees.edit', $employee) }}"
                                    class="btn btn-primary"
                                >
                                    <i class="bi bi-pencil-square me-1"></i>
                                    Edit Employee
                                </a>


                                <a
                                    href="{{ route('admin.employees.assign-item', $employee) }}"
                                    class="btn btn-light"
                                >
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    Assign Inventory
                                </a>


                                <button
                                    type="button"
                                    class="btn btn-light"
                                    onclick="window.print()"
                                >
                                    <i class="bi bi-printer me-1"></i>
                                    Print Profile
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         CURRENTLY ASSIGNED INVENTORY
    ====================================================== --}}

    <div class="cpc-card mb-4">

        <div class="cpc-card-header d-flex justify-content-between align-items-center">

            <div>

                <h2 class="section-title">

                    <i class="bi bi-box-seam-fill text-primary me-2"></i>

                    Currently Assigned Inventory

                </h2>

                <p class="section-subtitle">
                    Inventory assets currently assigned to this employee.
                </p>

            </div>


            <span class="status-badge status-issued">

                <i class="bi bi-box-seam"></i>

                {{ $employee->itemAssignments->whereNull('returned_date')->count() }}

                Active

            </span>

        </div>


        <div class="card-body p-0">

            @php
                $activeAssignments = $employee->itemAssignments
                    ->whereNull('returned_date');
            @endphp


            @if ($activeAssignments->count() > 0)

                <div class="table-wrapper">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>Asset No.</th>
                                <th>Item Type</th>
                                <th>Make / Model</th>
                                <th>Serial Number</th>
                                <th>Issued Date</th>
                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($activeAssignments as $assignment)

                                <tr>

                                    {{-- Asset Number --}}

                                    <td>

                                        <span class="asset-number">

                                            {{ $assignment->item?->asset_number ?? '—' }}

                                        </span>

                                    </td>


                                    {{-- Item Type --}}

                                    <td>

                                        <div class="item-name">

                                            {{ $assignment->item?->itemType?->name ?? 'Inventory Item' }}

                                        </div>

                                        @if ($assignment->item?->status)

                                            <span class="item-meta">

                                                {{ ucfirst(str_replace('_', ' ', $assignment->item->status)) }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Make / Model --}}

                                    <td>

                                        @if ($assignment->item)

                                            <div>

                                                {{ $assignment->item->make ?: '—' }}

                                            </div>

                                            @if ($assignment->item->model)

                                                <span class="item-meta">

                                                    {{ $assignment->item->model }}

                                                </span>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Serial Number --}}

                                    <td>

                                        @if ($assignment->item?->serial_number)

                                            <span class="serial-number">

                                                {{ $assignment->item->serial_number }}

                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Assigned Date --}}

                                    <td>

                                        @if ($assignment->assigned_date)

                                            {{ \Carbon\Carbon::parse($assignment->assigned_date)->format('d M Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span class="status-badge status-issued">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Issued

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="bi bi-box-seam"></i>

                    </div>

                    <div class="empty-title">

                        No Inventory Items Currently Assigned

                    </div>

                    <p class="empty-text">

                        This employee currently has no issued inventory assets.

                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         ASSIGNMENT HISTORY
    ====================================================== --}}

    <div class="cpc-card">

        <div class="cpc-card-header d-flex justify-content-between align-items-center">

            <div>

                <h2 class="section-title">

                    <i class="bi bi-clock-history text-primary me-2"></i>

                    Assignment History

                </h2>

                <p class="section-subtitle">

                    Complete inventory assignment history for this employee.

                </p>

            </div>


            <span class="status-badge status-returned">

                <i class="bi bi-list-check"></i>

                {{ $employee->itemAssignments->count() }}

                Records

            </span>

        </div>


        <div class="card-body p-0">

            @if ($employee->itemAssignments->count() > 0)

                <div class="table-wrapper">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>Asset No.</th>
                                <th>Item Type</th>
                                <th>Make / Model</th>
                                <th>Serial Number</th>
                                <th>Assigned Date</th>
                                <th>Returned Date</th>
                                <th>Status</th>
                                <th>Remarks</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($employee->itemAssignments as $assignment)

                                <tr>

                                    {{-- Asset Number --}}

                                    <td>

                                        <span class="asset-number">

                                            {{ $assignment->item?->asset_number ?? '—' }}

                                        </span>

                                    </td>


                                    {{-- Item Type --}}

                                    <td>

                                        <div class="item-name">

                                            {{ $assignment->item?->itemType?->name ?? 'Inventory Item' }}

                                        </div>

                                    </td>


                                    {{-- Make / Model --}}

                                    <td>

                                        @if ($assignment->item)

                                            <div>

                                                {{ $assignment->item->make ?: '—' }}

                                            </div>

                                            @if ($assignment->item->model)

                                                <span class="item-meta">

                                                    {{ $assignment->item->model }}

                                                </span>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Serial Number --}}

                                    <td>

                                        @if ($assignment->item?->serial_number)

                                            <span class="serial-number">

                                                {{ $assignment->item->serial_number }}

                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Assigned Date --}}

                                    <td>

                                        @if ($assignment->assigned_date)

                                            {{ \Carbon\Carbon::parse($assignment->assigned_date)->format('d M Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Returned Date --}}

                                    <td>

                                        @if ($assignment->returned_date)

                                            {{ \Carbon\Carbon::parse($assignment->returned_date)->format('d M Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Assignment Status --}}

                                    <td>

                                        @if (is_null($assignment->returned_date))

                                            <span class="status-badge status-issued">

                                                <i class="bi bi-check-circle-fill"></i>

                                                Issued

                                            </span>

                                        @else

                                            <span class="status-badge status-returned">

                                                <i class="bi bi-arrow-return-left"></i>

                                                Returned

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Remarks --}}

                                    <td>

                                        @if ($assignment->remarks)

                                            <span
                                                title="{{ $assignment->remarks }}"
                                            >
                                                {{ \Illuminate\Support\Str::limit($assignment->remarks, 45) }}
                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="bi bi-clock-history"></i>

                    </div>

                    <div class="empty-title">

                        No Assignment History

                    </div>

                    <p class="empty-text">

                        No inventory assignment records have been created
                        for this employee yet.

                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection