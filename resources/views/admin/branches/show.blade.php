@extends('layouts.admin')

@section('title', $branch->name . ' | Branch Profile | CPC Inventory Management System')

@section('content')

<style>
    /* =========================================================
       CPC BRANCH PROFILE
    ========================================================= */

    .branch-profile-page {
        --cpc-primary: #0d6efd;
        --cpc-primary-dark: #0b5ed7;
        --cpc-dark: #1f2937;
        --cpc-text: #374151;
        --cpc-muted: #6b7280;
        --cpc-border: #e5e7eb;
        --cpc-light: #f8fafc;
        --cpc-success: #198754;
        --cpc-danger: #dc3545;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .branch-profile-page .page-header {
        margin-bottom: 24px;
    }

    .branch-profile-page .breadcrumb-area {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 8px;
        color: var(--cpc-muted);
        font-size: 0.76rem;
    }

    .branch-profile-page .breadcrumb-area a {
        color: var(--cpc-muted);
        text-decoration: none;
    }

    .branch-profile-page .breadcrumb-area a:hover {
        color: var(--cpc-primary);
    }

    .branch-profile-page .page-title {
        margin: 0;
        color: var(--cpc-dark);
        font-size: 1.45rem;
        font-weight: 700;
    }

    .branch-profile-page .page-subtitle {
        margin: 5px 0 0;
        color: var(--cpc-muted);
        font-size: 0.88rem;
    }

    .branch-profile-page .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* =========================================================
       BUTTONS
    ========================================================= */

    .branch-profile-page .btn {
        border-radius: 8px;
        padding: 8px 13px;
        font-size: 0.80rem;
        font-weight: 600;
    }

    .branch-profile-page .btn-light {
        background: #ffffff;
        border: 1px solid #d7dce2;
        color: #4b5563;
    }

    .branch-profile-page .btn-light:hover {
        background: #f3f4f6;
        border-color: #cbd1d8;
        color: #374151;
    }

    /* =========================================================
       COMMON CARD
    ========================================================= */

    .branch-profile-page .cpc-card {
        background: #ffffff;
        border: 1px solid var(--cpc-border);
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.045);
        overflow: hidden;
    }

    .branch-profile-page .cpc-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid var(--cpc-border);
        background: #ffffff;
    }

    .branch-profile-page .cpc-card-body {
        padding: 20px;
    }

    .branch-profile-page .section-title {
        margin: 0;
        color: var(--cpc-dark);
        font-size: 0.98rem;
        font-weight: 700;
    }

    .branch-profile-page .section-subtitle {
        margin: 4px 0 0;
        color: var(--cpc-muted);
        font-size: 0.76rem;
    }

    /* =========================================================
       BRANCH HERO
    ========================================================= */

    .branch-profile-page .branch-hero {
        position: relative;
        overflow: hidden;
        min-height: 220px;
        background:
            radial-gradient(
                circle at 90% 15%,
                rgba(13, 110, 253, 0.18),
                transparent 30%
            ),
            linear-gradient(
                135deg,
                #eaf3ff 0%,
                #f5f9ff 55%,
                #ffffff 100%
            );
    }

    .branch-profile-page .branch-hero::before {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: -65px;
        top: -75px;
        border: 1px solid rgba(13, 110, 253, 0.10);
        border-radius: 50%;
    }

    .branch-profile-page .branch-hero::after {
        content: "";
        position: absolute;
        width: 110px;
        height: 110px;
        right: 80px;
        bottom: -65px;
        border: 1px solid rgba(13, 110, 253, 0.08);
        border-radius: 50%;
    }

    .branch-profile-page .hero-content {
        position: relative;
        z-index: 2;
        padding: 28px;
    }

    .branch-profile-page .branch-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        border-radius: 15px;
        background: #ffffff;
        border: 1px solid #dce5f0;
        box-shadow: 0 7px 18px rgba(15, 23, 42, 0.07);
        color: var(--cpc-primary);
        font-size: 1.45rem;
    }

    .branch-profile-page .branch-name {
        margin: 0;
        color: var(--cpc-dark);
        font-size: 1.55rem;
        font-weight: 750;
    }

    .branch-profile-page .branch-location {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 7px;
        color: var(--cpc-muted);
        font-size: 0.84rem;
    }

    .branch-profile-page .branch-code {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 14px;
        padding: 6px 10px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.75);
        border: 1px solid #dce5f0;
        color: #4b5563;
        font-family: monospace;
        font-size: 0.76rem;
    }

    .branch-profile-page .hero-status {
        margin-top: 14px;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .branch-profile-page .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 0.70rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .branch-profile-page .status-active {
        color: #157347;
        background: #eaf7ef;
        border: 1px solid #ccebd8;
    }

    .branch-profile-page .status-inactive {
        color: #842029;
        background: #fbeaec;
        border: 1px solid #f2c8ce;
    }

    .branch-profile-page .status-available {
        color: #157347;
        background: #eaf7ef;
        border: 1px solid #ccebd8;
    }

    .branch-profile-page .status-use {
        color: #0d6efd;
        background: #eaf2ff;
        border: 1px solid #cfe0ff;
    }

    .branch-profile-page .status-repair {
        color: #996c00;
        background: #fff7df;
        border: 1px solid #f4e3ad;
    }

    .branch-profile-page .status-rdd {
        color: #842029;
        background: #fbeaec;
        border: 1px solid #f2c8ce;
    }

    /* =========================================================
       SUMMARY STATISTICS
    ========================================================= */

    .branch-profile-page .stat-card {
        height: 100%;
        padding: 18px;
        background: #ffffff;
        border: 1px solid var(--cpc-border);
        border-radius: 13px;
        transition: 0.2s ease;
    }

    .branch-profile-page .stat-card:hover {
        border-color: #cbdcf5;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.07);
        transform: translateY(-1px);
    }

    .branch-profile-page .stat-icon {
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

    .branch-profile-page .stat-number {
        color: var(--cpc-dark);
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .branch-profile-page .stat-label {
        margin-top: 4px;
        color: var(--cpc-muted);
        font-size: 0.74rem;
    }

    /* =========================================================
       DETAILS
    ========================================================= */

    .branch-profile-page .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px;
    }

    .branch-profile-page .detail-box {
        padding: 14px;
        background: #f8fafc;
        border: 1px solid #edf0f4;
        border-radius: 10px;
    }

    .branch-profile-page .detail-label {
        margin-bottom: 5px;
        color: #8a94a6;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .branch-profile-page .detail-value {
        color: var(--cpc-text);
        font-size: 0.84rem;
        font-weight: 600;
        word-break: break-word;
    }

    .branch-profile-page .description-box {
        margin-top: 14px;
        padding: 15px;
        background: #f8fafc;
        border: 1px solid #edf0f4;
        border-radius: 10px;
    }

    .branch-profile-page .description-text {
        color: var(--cpc-text);
        font-size: 0.84rem;
        line-height: 1.6;
        white-space: pre-line;
    }

    /* =========================================================
       TABLES
    ========================================================= */

    .branch-profile-page .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .branch-profile-page .table {
        min-width: 700px;
        margin-bottom: 0;
    }

    .branch-profile-page .table > :not(caption) > * > * {
        padding: 13px 14px;
        vertical-align: middle;
        border-bottom-color: #eef0f3;
    }

    .branch-profile-page .table thead th {
        background: #f8fafc;
        color: #6b7280;
        font-size: 0.69rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        white-space: nowrap;
    }

    .branch-profile-page .table tbody td {
        color: var(--cpc-text);
        font-size: 0.80rem;
    }

    .branch-profile-page .table tbody tr:last-child td {
        border-bottom: 0;
    }

    .branch-profile-page .table tbody tr:hover {
        background: #fafcff;
    }

    .branch-profile-page .primary-text {
        color: var(--cpc-primary);
        font-weight: 700;
    }

    .branch-profile-page .secondary-text {
        display: block;
        margin-top: 3px;
        color: #8a94a6;
        font-size: 0.70rem;
    }

    .branch-profile-page .asset-number {
        color: var(--cpc-primary);
        font-family: monospace;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .branch-profile-page .serial-number {
        color: #4b5563;
        font-family: monospace;
        font-size: 0.74rem;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .branch-profile-page .empty-state {
        padding: 42px 20px;
        text-align: center;
        color: var(--cpc-muted);
    }

    .branch-profile-page .empty-icon {
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

    .branch-profile-page .empty-title {
        margin-bottom: 4px;
        color: #4b5563;
        font-size: 0.90rem;
        font-weight: 700;
    }

    .branch-profile-page .empty-text {
        margin: 0;
        font-size: 0.76rem;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .branch-profile-page .alert {
        border: 0;
        border-radius: 10px;
        font-size: 0.82rem;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991.98px) {

        .branch-profile-page .header-actions {
            margin-top: 15px;
        }
    }

    @media (max-width: 767.98px) {

        .branch-profile-page .page-header {
            align-items: flex-start !important;
            flex-direction: column;
        }

        .branch-profile-page .header-actions {
            width: 100%;
        }

        .branch-profile-page .header-actions .btn {
            flex: 1;
        }

        .branch-profile-page .details-grid {
            grid-template-columns: 1fr;
        }

        .branch-profile-page .hero-content {
            padding: 22px;
        }

        .branch-profile-page .branch-name {
            font-size: 1.3rem;
        }

        .branch-profile-page .cpc-card-body {
            padding: 17px;
        }

        .branch-profile-page .cpc-card-header {
            padding: 15px 17px;
        }
    }

    /* =========================================================
       PRINT
    ========================================================= */

    @media print {

        .branch-profile-page .header-actions,
        .branch-profile-page .breadcrumb-area {
            display: none !important;
        }

        .branch-profile-page .cpc-card,
        .branch-profile-page .stat-card {
            box-shadow: none !important;
            break-inside: avoid;
        }
    }
</style>


<div class="branch-profile-page">

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

                <a href="{{ route('admin.branches.index') }}">
                    Branches
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>Branch Profile</span>

            </div>

            <h1 class="page-title">
                Branch Profile
            </h1>

            <p class="page-subtitle">
                Branch information, departments, employees and inventory assets.
            </p>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('admin.branches.index') }}"
                class="btn btn-light"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Branch Directory
            </a>

            <a
                href="{{ route('admin.branches.edit', $branch) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil-square me-1"></i>
                Edit Branch
            </a>

            <button
                type="button"
                class="btn btn-light"
                onclick="window.print()"
            >
                <i class="bi bi-printer me-1"></i>
                Print
            </button>

        </div>

    </div>


    {{-- =====================================================
         SUCCESS / ERROR
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
         BRANCH HERO + SUMMARY
    ====================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Branch Hero --}}

        <div class="col-lg-5">

            <div class="cpc-card branch-hero h-100">

                <div class="hero-content">

                    <div class="branch-icon">

                        <i class="bi bi-building"></i>

                    </div>


                    <h2 class="branch-name">
                        {{ $branch->name }}
                    </h2>


                    <div class="branch-location">

                        <i class="bi bi-geo-alt-fill text-primary"></i>

                        {{ $branch->location ?: 'Location not specified' }}

                    </div>


                    <div class="branch-code">

                        <i class="bi bi-hash"></i>

                        Branch Code:
                        {{ $branch->code ?: 'Not assigned' }}

                    </div>


                    <div class="hero-status">

                        @if ($branch->is_active)

                            <span class="status-badge status-active">

                                <i class="bi bi-check-circle-fill"></i>

                                Active Branch

                            </span>

                        @else

                            <span class="status-badge status-inactive">

                                <i class="bi bi-dash-circle-fill"></i>

                                Inactive Branch

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Summary Statistics --}}

        <div class="col-lg-7">

            <div class="row g-3 h-100">

                {{-- Departments --}}

                <div class="col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">

                            <i class="bi bi-diagram-3"></i>

                        </div>

                        <div class="stat-number">

                            {{ $branch->departments->count() }}

                        </div>

                        <div class="stat-label">
                            Departments
                        </div>

                    </div>

                </div>


                {{-- Employees --}}

                <div class="col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">

                            <i class="bi bi-people"></i>

                        </div>

                        <div class="stat-number">

                            {{ $branch->employees->count() }}

                        </div>

                        <div class="stat-label">
                            Employees
                        </div>

                    </div>

                </div>


                {{-- Inventory --}}

                <div class="col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">

                            <i class="bi bi-box-seam"></i>

                        </div>

                        <div class="stat-number">

                            {{ $branch->items->count() }}

                        </div>

                        <div class="stat-label">
                            Inventory Assets
                        </div>

                    </div>

                </div>


                {{-- Active Employees --}}

                <div class="col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">

                            <i class="bi bi-person-check"></i>

                        </div>

                        <div class="stat-number">

                            {{ $branch->employees->where('is_active', true)->count() }}

                        </div>

                        <div class="stat-label">
                            Active Employees
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         BRANCH INFORMATION
    ====================================================== --}}

    <div class="cpc-card mb-4">

        <div class="cpc-card-header">

            <h2 class="section-title">

                <i class="bi bi-info-circle text-primary me-2"></i>

                Branch Information

            </h2>

            <p class="section-subtitle">
                General information and administrative details for this branch.
            </p>

        </div>


        <div class="cpc-card-body">

            <div class="details-grid">

                {{-- Branch ID --}}

                <div class="detail-box">

                    <div class="detail-label">
                        Branch ID
                    </div>

                    <div class="detail-value">
                        #{{ $branch->id }}
                    </div>

                </div>


                {{-- Branch Name --}}

                <div class="detail-box">

                    <div class="detail-label">
                        Branch Name
                    </div>

                    <div class="detail-value">
                        {{ $branch->name }}
                    </div>

                </div>


                {{-- Branch Code --}}

                <div class="detail-box">

                    <div class="detail-label">
                        Branch Code
                    </div>

                    <div class="detail-value">
                        {{ $branch->code ?: 'Not assigned' }}
                    </div>

                </div>


                {{-- Location --}}

                <div class="detail-box">

                    <div class="detail-label">
                        Location
                    </div>

                    <div class="detail-value">
                        {{ $branch->location ?: 'Not specified' }}
                    </div>

                </div>


                {{-- Status --}}

                <div class="detail-box">

                    <div class="detail-label">
                        Status
                    </div>

                    <div class="detail-value">

                        @if ($branch->is_active)

                            <span class="status-badge status-active">

                                <i class="bi bi-check-circle-fill"></i>

                                Active

                            </span>

                        @else

                            <span class="status-badge status-inactive">

                                <i class="bi bi-dash-circle-fill"></i>

                                Inactive

                            </span>

                        @endif

                    </div>

                </div>


                {{-- Created --}}

                <div class="detail-box">

                    <div class="detail-label">
                        Created
                    </div>

                    <div class="detail-value">

                        {{ $branch->created_at?->format('d M Y, h:i A') ?? '—' }}

                    </div>

                </div>


                {{-- Last Updated --}}

                <div class="detail-box">

                    <div class="detail-label">
                        Last Updated
                    </div>

                    <div class="detail-value">

                        {{ $branch->updated_at?->format('d M Y, h:i A') ?? '—' }}

                    </div>

                </div>

            </div>


            {{-- Description --}}

            @if ($branch->description)

                <div class="description-box">

                    <div class="detail-label">
                        Description
                    </div>

                    <div class="description-text">

                        {{ $branch->description }}

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         DEPARTMENTS
    ====================================================== --}}

    <div class="cpc-card mb-4">

        <div class="cpc-card-header d-flex justify-content-between align-items-center">

            <div>

                <h2 class="section-title">

                    <i class="bi bi-diagram-3 text-primary me-2"></i>

                    Departments

                </h2>

                <p class="section-subtitle">
                    Departments registered under this branch.
                </p>

            </div>


            <span class="status-badge status-use">

                <i class="bi bi-diagram-3"></i>

                {{ $branch->departments->count() }}

                Departments

            </span>

        </div>


        <div class="card-body p-0">

            @if ($branch->departments->count() > 0)

                <div class="table-wrapper">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>ID</th>
                                <th>Department</th>
                                <th>Code</th>
                                <th>Employees</th>
                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($branch->departments as $department)

                                <tr>

                                    <td>
                                        #{{ $department->id }}
                                    </td>


                                    <td>

                                        <span class="primary-text">

                                            {{ $department->name }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $department->code ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $department->employees?->count() ?? 0 }}

                                    </td>


                                    <td>

                                        @if ($department->is_active)

                                            <span class="status-badge status-active">

                                                <i class="bi bi-check-circle"></i>

                                                Active

                                            </span>

                                        @else

                                            <span class="status-badge status-inactive">

                                                <i class="bi bi-dash-circle"></i>

                                                Inactive

                                            </span>

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

                        <i class="bi bi-diagram-3"></i>

                    </div>

                    <div class="empty-title">

                        No Departments Registered

                    </div>

                    <p class="empty-text">

                        No departments have been registered under this branch yet.

                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         EMPLOYEES
    ====================================================== --}}

    <div class="cpc-card mb-4">

        <div class="cpc-card-header d-flex justify-content-between align-items-center">

            <div>

                <h2 class="section-title">

                    <i class="bi bi-people text-primary me-2"></i>

                    Employees

                </h2>

                <p class="section-subtitle">
                    Employees currently registered under this branch.
                </p>

            </div>


            <span class="status-badge status-use">

                <i class="bi bi-people"></i>

                {{ $branch->employees->count() }}

                Employees

            </span>

        </div>


        <div class="card-body p-0">

            @if ($branch->employees->count() > 0)

                <div class="table-wrapper">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>EPF No.</th>
                                <th>Employee</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($branch->employees as $employee)

                                <tr>

                                    <td>

                                        <span class="asset-number">

                                            {{ $employee->epf_no ?: '—' }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="primary-text">

                                            {{ $employee->name }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $employee->designation ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $employee->department?->name ?? '—' }}

                                    </td>


                                    <td>

                                        @if ($employee->is_active)

                                            <span class="status-badge status-active">

                                                <i class="bi bi-check-circle"></i>

                                                Active

                                            </span>

                                        @else

                                            <span class="status-badge status-inactive">

                                                <i class="bi bi-dash-circle"></i>

                                                Inactive

                                            </span>

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

                        <i class="bi bi-people"></i>

                    </div>

                    <div class="empty-title">

                        No Employees Registered

                    </div>

                    <p class="empty-text">

                        No employees have been registered under this branch yet.

                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         INVENTORY
    ====================================================== --}}

    <div class="cpc-card">

        <div class="cpc-card-header d-flex justify-content-between align-items-center">

            <div>

                <h2 class="section-title">

                    <i class="bi bi-box-seam text-primary me-2"></i>

                    Inventory Assets

                </h2>

                <p class="section-subtitle">
                    IT inventory items currently linked to this branch.
                </p>

            </div>


            <span class="status-badge status-use">

                <i class="bi bi-box-seam"></i>

                {{ $branch->items->count() }}

                Assets

            </span>

        </div>


        <div class="card-body p-0">

            @if ($branch->items->count() > 0)

                <div class="table-wrapper">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>Asset No.</th>
                                <th>Item Type</th>
                                <th>Make / Model</th>
                                <th>Serial Number</th>
                                <th>Department</th>
                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($branch->items as $item)

                                <tr>

                                    {{-- Asset Number --}}

                                    <td>

                                        <span class="asset-number">

                                            {{ $item->asset_number ?: '—' }}

                                        </span>

                                    </td>


                                    {{-- Item Type --}}

                                    <td>

                                        <span class="primary-text">

                                            {{ $item->itemType?->name ?? 'Unknown Item Type' }}

                                        </span>

                                    </td>


                                    {{-- Make / Model --}}

                                    <td>

                                        @if ($item->make)

                                            {{ $item->make }}

                                        @else

                                            —

                                        @endif


                                        @if ($item->model)

                                            <span class="secondary-text">

                                                {{ $item->model }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Serial Number --}}

                                    <td>

                                        @if ($item->serial_number)

                                            <span class="serial-number">

                                                {{ $item->serial_number }}

                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Department --}}

                                    <td>

                                        {{ $item->department?->name ?? '—' }}

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @php

                                            $itemStatus = strtolower(
                                                str_replace('_', ' ', $item->status ?? '')
                                            );

                                        @endphp


                                        @if ($itemStatus === 'available')

                                            <span class="status-badge status-available">

                                                <i class="bi bi-check-circle"></i>

                                                Available

                                            </span>

                                        @elseif ($itemStatus === 'in use')

                                            <span class="status-badge status-use">

                                                <i class="bi bi-person-check"></i>

                                                In Use

                                            </span>

                                        @elseif ($itemStatus === 'under repair')

                                            <span class="status-badge status-repair">

                                                <i class="bi bi-tools"></i>

                                                Under Repair

                                            </span>

                                        @elseif ($itemStatus === 'sent to rdd')

                                            <span class="status-badge status-rdd">

                                                <i class="bi bi-send"></i>

                                                Sent to RDD

                                            </span>

                                        @else

                                            <span class="status-badge status-inactive">

                                                {{ ucfirst($itemStatus ?: 'Unknown') }}

                                            </span>

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

                        <i class="bi bi-box-seam"></i>

                    </div>

                    <div class="empty-title">

                        No Inventory Assets

                    </div>

                    <p class="empty-text">

                        No inventory items are currently linked to this branch.

                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection