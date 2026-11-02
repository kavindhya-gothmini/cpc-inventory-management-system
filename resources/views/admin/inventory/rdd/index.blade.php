@extends('layouts.admin')

@section('title', 'RDD Management | CPC Inventory Management System')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | RDD Management Permissions
    |--------------------------------------------------------------------------
    | Admin       -> Full management access
    | Super Admin -> Full management access
    | Employee    -> View + Search only
    |--------------------------------------------------------------------------
    */

    $currentUser = auth()->user();

    $isAdmin = $currentUser
        && $currentUser->role === 'admin';

    $isSuperAdmin = $currentUser
        && $currentUser->role === 'super_admin';

    $isEmployee = $currentUser
        && $currentUser->role === 'employee';

    $canManageRdd = $isAdmin || $isSuperAdmin;
@endphp

<style>
    .rdd-page {
        padding: 24px;
        background: #f5f7fb;
        min-height: calc(100vh - 72px);
    }

    .rdd-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .rdd-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .rdd-header-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: linear-gradient(135deg, #0d6efd, #084298);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        box-shadow: 0 8px 20px rgba(13, 110, 253, .18);
    }

    .rdd-title {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #172033;
    }

    .rdd-subtitle {
        margin: 4px 0 0;
        color: #6c757d;
        font-size: 14px;
    }

    .btn-rdd-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 17px;
        border-radius: 9px;
        border: 0;
        background: #0d6efd;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 5px 14px rgba(13, 110, 253, .18);
        transition: .2s ease;
    }

    .btn-rdd-primary:hover {
        background: #0b5ed7;
        color: #fff;
        transform: translateY(-1px);
    }

    /* =========================================================
       EMPLOYEE READ-ONLY
    ========================================================= */

    .rdd-disabled-action {
        opacity: 0.48;
        cursor: not-allowed !important;
        filter: grayscale(12%);
        transform: none !important;
        box-shadow: none !important;
        user-select: none;
    }

    .rdd-disabled-action:hover,
    .rdd-disabled-action:focus {
        opacity: 0.48;
        transform: none !important;
        box-shadow: none !important;
    }

    .rdd-readonly-notice {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-top: 12px;
        padding: 9px 12px;
        border: 1px solid #dbe7f8;
        border-radius: 9px;
        background: #f5f9ff;
        color: #55708f;
        font-size: 11px;
        line-height: 1.5;
    }

    .rdd-readonly-notice i {
        color: #2563eb;
        font-size: 14px;
    }

    /* =========================================================
       KPI CARDS
    ========================================================= */

    .rdd-kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }

    .rdd-kpi-card {
        background: #fff;
        border: 1px solid #e8ebf1;
        border-radius: 13px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 3px 12px rgba(20, 30, 50, .04);
    }

    .rdd-kpi-icon {
        width: 45px;
        height: 45px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .rdd-kpi-icon.blue {
        background: #eaf2ff;
        color: #0d6efd;
    }

    .rdd-kpi-icon.orange {
        background: #fff4df;
        color: #e69500;
    }

    .rdd-kpi-icon.cyan {
        background: #e5f8fb;
        color: #0aa2b5;
    }

    .rdd-kpi-icon.green {
        background: #e8f8ee;
        color: #198754;
    }

    .rdd-kpi-icon.red {
        background: #fdebec;
        color: #dc3545;
    }

    .rdd-kpi-label {
        font-size: 12px;
        color: #7b8494;
        margin-bottom: 3px;
    }

    .rdd-kpi-value {
        font-size: 23px;
        font-weight: 700;
        color: #1d2738;
        line-height: 1;
    }

    /* =========================================================
       FILTER CARD
    ========================================================= */

    .rdd-filter-card {
        background: #fff;
        border: 1px solid #e7eaf0;
        border-radius: 13px;
        padding: 18px;
        margin-bottom: 20px;
        box-shadow: 0 3px 12px rgba(20, 30, 50, .035);
    }

    .rdd-filter-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #263246;
        margin-bottom: 14px;
    }

    .rdd-filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }

    .rdd-field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #657084;
        margin-bottom: 6px;
    }

    .rdd-field .form-control,
    .rdd-field .form-select {
        height: 41px;
        border: 1px solid #dfe4eb;
        border-radius: 8px;
        font-size: 13px;
        color: #263246;
        box-shadow: none;
    }

    .rdd-field .form-control:focus,
    .rdd-field .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .08);
    }

    .rdd-search-wrapper {
        position: relative;
    }

    .rdd-search-wrapper i {
        position: absolute;
        left: 12px;
        top: 12px;
        color: #8993a3;
        font-size: 15px;
        pointer-events: none;
    }

    .rdd-search-wrapper input {
        padding-left: 36px;
    }

    .rdd-filter-actions {
        display: flex;
        gap: 8px;
    }

    .btn-rdd-filter {
        height: 41px;
        padding: 0 15px;
        border: 0;
        border-radius: 8px;
        background: #0d6efd;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
    }

    .btn-rdd-filter:hover {
        background: #0b5ed7;
        color: #fff;
    }

    .btn-rdd-reset {
        height: 41px;
        padding: 0 13px;
        border: 1px solid #dfe4eb;
        border-radius: 8px;
        background: #fff;
        color: #596579;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn-rdd-reset:hover {
        background: #f7f8fa;
        color: #263246;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .rdd-table-card {
        background: #fff;
        border: 1px solid #e7eaf0;
        border-radius: 13px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(20, 30, 50, .035);
    }

    .rdd-table-header {
        padding: 17px 19px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .rdd-table-title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #263246;
    }

    .rdd-record-count {
        font-size: 12px;
        color: #7a8494;
    }

    .rdd-table-responsive {
        overflow-x: auto;
    }

    .rdd-table {
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
        margin: 0;
    }

    .rdd-table thead th {
        background: #f8f9fb;
        color: #687386;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
        padding: 13px 15px;
        border-bottom: 1px solid #e9edf2;
        white-space: nowrap;
    }

    .rdd-table tbody td {
        padding: 14px 15px;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
        color: #344054;
        font-size: 13px;
    }

    .rdd-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .rdd-table tbody tr:hover {
        background: #fafcff;
    }

    .rdd-reference {
        font-weight: 700;
        color: #0d6efd;
        white-space: nowrap;
    }

    .rdd-asset {
        font-weight: 700;
        color: #293548;
    }

    .rdd-serial {
        display: block;
        color: #8a94a4;
        font-size: 11px;
        margin-top: 2px;
    }

    .rdd-item-type {
        color: #344054;
        font-weight: 600;
    }

    .rdd-location {
        font-size: 12px;
        color: #596579;
        line-height: 1.45;
    }

    .rdd-employee {
        font-weight: 600;
        color: #344054;
    }

    .rdd-employee small {
        display: block;
        color: #8a94a4;
        font-size: 11px;
        margin-top: 2px;
    }

    .rdd-date {
        white-space: nowrap;
        color: #4e5b70;
        font-size: 12px;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .rdd-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .rdd-status::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-pending {
        background: #fff4df;
        color: #b76e00;
    }

    .status-approved {
        background: #eaf2ff;
        color: #0d6efd;
    }

    .status-dispatched {
        background: #edf0ff;
        color: #5964c8;
    }

    .status-received {
        background: #e5f8fb;
        color: #087f8f;
    }

    .status-inspection {
        background: #f1ecff;
        color: #7652c7;
    }

    .status-in-progress {
        background: #edf0ff;
        color: #4f5fb7;
    }

    .status-completed {
        background: #e8f8ee;
        color: #198754;
    }

    .status-returned {
        background: #e8f8ee;
        color: #198754;
    }

    .status-beyond-repair {
        background: #fdebec;
        color: #c12b3c;
    }

    .status-rejected {
        background: #fdebec;
        color: #dc3545;
    }

    .status-cancelled {
        background: #eef0f3;
        color: #687386;
    }

    /* =========================================================
       ACTIVE
    ========================================================= */

    .rdd-active {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 15px;
    }

    .rdd-active.yes {
        background: #e8f8ee;
        color: #198754;
    }

    .rdd-active.no {
        background: #eef0f3;
        color: #687386;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .rdd-actions {
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .rdd-action {
        width: 33px;
        height: 33px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e1e5eb;
        background: #fff;
        color: #596579;
        text-decoration: none;
        transition: .18s ease;
    }

    .rdd-action:hover {
        background: #f4f7fb;
        color: #0d6efd;
        border-color: #cbdaf2;
    }

    .rdd-action.edit:hover {
        color: #198754;
        border-color: #b8dfc8;
        background: #f0faf4;
    }

    .rdd-action.history:hover {
        color: #7652c7;
        border-color: #d8ccf5;
        background: #f8f5ff;
    }

    .rdd-action.delete:hover {
        color: #dc3545;
        border-color: #f1c2c7;
        background: #fff5f5;
    }

    /* =========================================================
       EMPTY
    ========================================================= */

    .rdd-empty {
        text-align: center;
        padding: 60px 20px;
    }

    .rdd-empty-icon {
        width: 62px;
        height: 62px;
        margin: 0 auto 15px;
        border-radius: 15px;
        background: #eef4ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .rdd-empty h5 {
        margin: 0 0 6px;
        color: #293548;
        font-weight: 700;
    }

    .rdd-empty p {
        margin: 0;
        color: #7a8494;
        font-size: 13px;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .rdd-pagination {
        padding: 16px 19px;
        border-top: 1px solid #edf0f4;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .rdd-pagination-info {
        font-size: 12px;
        color: #7a8494;
    }

    .rdd-pagination .pagination {
        margin: 0;
    }

    .rdd-pagination .page-link {
        border-color: #e1e5eb;
        color: #596579;
        font-size: 12px;
        min-width: 34px;
        text-align: center;
    }

    .rdd-pagination .page-item.active .page-link {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .rdd-alert {
        border: 0;
        border-radius: 9px;
        font-size: 13px;
        margin-bottom: 18px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1250px) {
        .rdd-kpi-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .rdd-filter-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .rdd-filter-actions {
            grid-column: span 3;
        }
    }

    @media (max-width: 800px) {
        .rdd-page {
            padding: 16px;
        }

        .rdd-kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .rdd-filter-grid {
            grid-template-columns: 1fr 1fr;
        }

        .rdd-filter-actions {
            grid-column: span 2;
        }

        .rdd-pagination {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 550px) {
        .rdd-kpi-grid {
            grid-template-columns: 1fr;
        }

        .rdd-filter-grid {
            grid-template-columns: 1fr;
        }

        .rdd-filter-actions {
            grid-column: span 1;
        }

        .rdd-header {
            align-items: flex-start;
        }

        .rdd-header-left {
            align-items: flex-start;
        }

        .rdd-title {
            font-size: 21px;
        }

        .btn-rdd-primary {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="rdd-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="rdd-header">

        <div class="rdd-header-left">

            <div class="rdd-header-icon">
                <i class="bi bi-send-check"></i>
            </div>

            <div>

                <h1 class="rdd-title">
                    RDD Management
                </h1>

                <p class="rdd-subtitle">
                    Manage equipment sent to RDD, track progress and maintain RDD history.
                </p>

                {{-- Employee read-only notice --}}
                @if($isEmployee)

                    <div class="rdd-readonly-notice">

                        <i class="bi bi-eye"></i>

                        <span>
                            Employees have view and search access only.
                            Management actions are disabled.
                        </span>

                    </div>

                @endif

            </div>

        </div>


        {{-- =================================================
             SEND ITEM TO RDD
        ================================================== --}}

        @if($canManageRdd)

            <a href="{{ route('admin.inventory.rdd.create') }}"
               class="btn-rdd-primary">

                <i class="bi bi-send-plus"></i>

                Send Item to RDD

            </a>

        @else

            <button type="button"
                    class="btn-rdd-primary rdd-disabled-action"
                    onclick="employeeReadOnlyMessage()"
                    aria-disabled="true"
                    title="Employees have view and search access only">

                <i class="bi bi-send-plus"></i>

                Send Item to RDD

            </button>

        @endif

    </div>


    {{-- =====================================================
         FLASH MESSAGES
    ====================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show rdd-alert"
             role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show rdd-alert"
             role="alert">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger rdd-alert">

            <div class="fw-semibold mb-1">

                <i class="bi bi-exclamation-circle me-2"></i>

                Please correct the following:

            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         KPI CARDS
    ====================================================== --}}

    <div class="rdd-kpi-grid">

        {{-- Total --}}
        <div class="rdd-kpi-card">

            <div class="rdd-kpi-icon blue">
                <i class="bi bi-archive"></i>
            </div>

            <div>

                <div class="rdd-kpi-label">
                    Total RDD Records
                </div>

                <div class="rdd-kpi-value">
                    {{ number_format($totalRdd) }}
                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="rdd-kpi-card">

            <div class="rdd-kpi-icon orange">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div>

                <div class="rdd-kpi-label">
                    Pending
                </div>

                <div class="rdd-kpi-value">
                    {{ number_format($pendingRdd) }}
                </div>

            </div>

        </div>


        {{-- Received --}}
        <div class="rdd-kpi-card">

            <div class="rdd-kpi-icon cyan">
                <i class="bi bi-box-arrow-in-down"></i>
            </div>

            <div>

                <div class="rdd-kpi-label">
                    Received / Processing
                </div>

                <div class="rdd-kpi-value">
                    {{ number_format($receivedRdd) }}
                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="rdd-kpi-card">

            <div class="rdd-kpi-icon green">
                <i class="bi bi-check2-circle"></i>
            </div>

            <div>

                <div class="rdd-kpi-label">
                    Completed
                </div>

                <div class="rdd-kpi-value">
                    {{ number_format($completedRdd) }}
                </div>

            </div>

        </div>


        {{-- Rejected --}}
        <div class="rdd-kpi-card">

            <div class="rdd-kpi-icon red">
                <i class="bi bi-x-circle"></i>
            </div>

            <div>

                <div class="rdd-kpi-label">
                    Rejected / Cancelled
                </div>

                <div class="rdd-kpi-value">
                    {{ number_format($rejectedRdd) }}
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FILTERS
    ====================================================== --}}

    <div class="rdd-filter-card">

        <div class="rdd-filter-title">

            <i class="bi bi-funnel"></i>

            Search & Filter RDD Records

        </div>


        <form method="GET"
              action="{{ route('admin.inventory.rdd.index') }}">

            <div class="rdd-filter-grid">

                {{-- Search --}}
                <div class="rdd-field">

                    <label for="search">
                        Search
                    </label>

                    <div class="rdd-search-wrapper">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Reference, Asset No., Serial No., EPF No., Employee..."
                        >

                    </div>

                </div>


                {{-- Status --}}
                <div class="rdd-field">

                    <label for="status">
                        Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-select"
                    >

                        <option value="">
                            All Statuses
                        </option>

                        <option value="pending"
                            {{ request('status') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="approved"
                            {{ request('status') === 'approved' ? 'selected' : '' }}>
                            Approved
                        </option>

                        <option value="dispatched"
                            {{ request('status') === 'dispatched' ? 'selected' : '' }}>
                            Dispatched
                        </option>

                        <option value="received"
                            {{ request('status') === 'received' ? 'selected' : '' }}>
                            Received
                        </option>

                        <option value="inspection"
                            {{ request('status') === 'inspection' ? 'selected' : '' }}>
                            Inspection
                        </option>

                        <option value="in_progress"
                            {{ request('status') === 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>

                        <option value="completed"
                            {{ request('status') === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>

                        <option value="returned"
                            {{ request('status') === 'returned' ? 'selected' : '' }}>
                            Returned
                        </option>

                        <option value="beyond_repair"
                            {{ request('status') === 'beyond_repair' ? 'selected' : '' }}>
                            Beyond Repair
                        </option>

                        <option value="rejected"
                            {{ request('status') === 'rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>

                        <option value="cancelled"
                            {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                            Cancelled
                        </option>

                    </select>

                </div>


                {{-- Date From --}}
                <div class="rdd-field">

                    <label for="date_from">
                        From Date
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        id="date_from"
                        class="form-control"
                        value="{{ request('date_from') }}"
                    >

                </div>


                {{-- Date To --}}
                <div class="rdd-field">

                    <label for="date_to">
                        To Date
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        id="date_to"
                        class="form-control"
                        value="{{ request('date_to') }}"
                    >

                </div>


                {{-- Active --}}
                <div class="rdd-field">

                    <label for="active">
                        Record State
                    </label>

                    <select
                        name="active"
                        id="active"
                        class="form-select"
                    >

                        <option value="">
                            All Records
                        </option>

                        <option value="1"
                            {{ request('active') === '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ request('active') === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- Buttons --}}
                <div class="rdd-filter-actions">

                    <button type="submit"
                            class="btn-rdd-filter">

                        <i class="bi bi-search"></i>

                        Search

                    </button>


                    <a href="{{ route('admin.inventory.rdd.index') }}"
                       class="btn-rdd-reset">

                        <i class="bi bi-arrow-counterclockwise me-1"></i>

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="rdd-table-card">

        <div class="rdd-table-header">

            <div>

                <h2 class="rdd-table-title">
                    RDD Transactions
                </h2>

                <div class="rdd-record-count">

                    Showing
                    {{ $rddTransactions->firstItem() ?? 0 }}
                    –
                    {{ $rddTransactions->lastItem() ?? 0 }}
                    of
                    {{ $rddTransactions->total() }}
                    records

                </div>

            </div>

        </div>


        @if($rddTransactions->count())

            <div class="rdd-table-responsive">

                <table class="rdd-table">

                    <thead>

                        <tr>

                            <th>RDD Reference</th>

                            <th>Equipment</th>

                            <th>Item Type</th>

                            <th>Employee</th>

                            <th>RDD Date</th>

                            <th>Status</th>

                            <th>Record State</th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($rddTransactions as $rdd)

                            @php
                                $statusClass = 'status-' . str_replace('_', '-', $rdd->status);
                            @endphp

                            <tr>

                                {{-- Reference --}}
                                <td>

                                    <div class="rdd-reference">
                                        {{ $rdd->reference_no }}
                                    </div>

                                    @if($rdd->decision)

                                        <small class="text-muted">

                                            Decision:
                                            {{ \Illuminate\Support\Str::headline($rdd->decision) }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Equipment --}}
                                <td>

                                    <div class="rdd-asset">
                                        {{ $rdd->item?->asset_number ?? 'N/A' }}
                                    </div>

                                    <span class="rdd-serial">

                                        Serial:
                                        {{ $rdd->item?->serial_number ?? 'N/A' }}

                                    </span>

                                </td>


                                {{-- Item Type --}}
                                <td>

                                    <div class="rdd-item-type">

                                        {{ $rdd->item?->itemType?->name ?? 'N/A' }}

                                    </div>

                                    @if($rdd->item?->make || $rdd->item?->model)

                                        <small class="text-muted">

                                            {{ trim(($rdd->item?->make ?? '') . ' ' . ($rdd->item?->model ?? '')) }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Employee --}}
                                <td>

                                    @if($rdd->employee)

                                        <div class="rdd-employee">

                                            {{ $rdd->employee->name }}

                                        </div>

                                        <small>

                                            EPF:
                                            {{ $rdd->employee->epf_no }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Not assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- Date --}}
                                <td>

                                    <div class="rdd-date">

                                        {{ $rdd->rdd_date?->format('d M Y') ?? 'N/A' }}

                                    </div>

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span class="rdd-status {{ $statusClass }}">

                                        {{ \Illuminate\Support\Str::headline($rdd->status) }}

                                    </span>

                                </td>


                                {{-- Active --}}
                                <td>

                                    @if($rdd->is_active)

                                        <span class="rdd-active yes">
                                            Active
                                        </span>

                                    @else

                                        <span class="rdd-active no">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="rdd-actions justify-content-end">

                                        {{-- View --}}
                                        <a
                                            href="{{ route('admin.inventory.rdd.show', $rdd) }}"
                                            class="rdd-action"
                                            title="View RDD"
                                        >

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        {{-- Edit --}}
                                        @if($canManageRdd)

                                            <a
                                                href="{{ route('admin.inventory.rdd.edit', $rdd) }}"
                                                class="rdd-action edit"
                                                title="Edit RDD"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </a>

                                        @else

                                            <button
                                                type="button"
                                                class="rdd-action edit rdd-disabled-action"
                                                onclick="employeeReadOnlyMessage()"
                                                aria-disabled="true"
                                                title="Edit disabled for employees"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </button>

                                        @endif


                                        {{-- History --}}
                                        <a
                                            href="{{ route('admin.inventory.rdd.history', $rdd) }}"
                                            class="rdd-action history"
                                            title="View History"
                                        >

                                            <i class="bi bi-clock-history"></i>

                                        </a>


                                        {{-- Cancel --}}
                                        @if(in_array($rdd->status, [
                                            'pending',
                                            'rejected'
                                        ], true))

                                            @if($canManageRdd)

                                                <form
                                                    action="{{ route('admin.inventory.rdd.destroy', $rdd) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to cancel this RDD transaction? The inventory item will be restored to its previous status.');"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="rdd-action delete"
                                                        title="Cancel RDD"
                                                    >

                                                        <i class="bi bi-x-lg"></i>

                                                    </button>

                                                </form>

                                            @else

                                                <button
                                                    type="button"
                                                    class="rdd-action delete rdd-disabled-action"
                                                    onclick="employeeReadOnlyMessage()"
                                                    aria-disabled="true"
                                                    title="Cancel disabled for employees"
                                                >

                                                    <i class="bi bi-x-lg"></i>

                                                </button>

                                            @endif

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}

            <div class="rdd-pagination">

                <div class="rdd-pagination-info">

                    Showing
                    {{ $rddTransactions->firstItem() ?? 0 }}
                    to
                    {{ $rddTransactions->lastItem() ?? 0 }}
                    of
                    {{ $rddTransactions->total() }}
                    RDD records

                </div>

                <div>

                    {{ $rddTransactions->links() }}

                </div>

            </div>

        @else

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}

            <div class="rdd-empty">

                <div class="rdd-empty-icon">

                    <i class="bi bi-inbox"></i>

                </div>

                <h5>
                    No RDD records found
                </h5>

                <p>
                    No RDD transactions match your current search or filter criteria.
                </p>


                @if(request()->hasAny([
                    'search',
                    'status',
                    'date_from',
                    'date_to',
                    'active'
                ]))

                    <a
                        href="{{ route('admin.inventory.rdd.index') }}"
                        class="btn-rdd-reset mt-3"
                    >

                        <i class="bi bi-arrow-counterclockwise me-1"></i>

                        Clear Filters

                    </a>

                @else

                    @if($canManageRdd)

                        <a
                            href="{{ route('admin.inventory.rdd.create') }}"
                            class="btn-rdd-primary mt-3"
                        >

                            <i class="bi bi-send-plus me-1"></i>

                            Send Item to RDD

                        </a>

                    @else

                        <button
                            type="button"
                            class="btn-rdd-primary rdd-disabled-action mt-3"
                            onclick="employeeReadOnlyMessage()"
                            aria-disabled="true"
                            title="Employees have view and search access only"
                        >

                            <i class="bi bi-send-plus me-1"></i>

                            Send Item to RDD

                        </button>

                    @endif

                @endif

            </div>

        @endif

    </div>

</div>


<script>
    function employeeReadOnlyMessage()
    {
        alert(
            'Employees have view and search access only.'
        );
    }
</script>

@endsection