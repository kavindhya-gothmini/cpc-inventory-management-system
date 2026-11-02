@extends('layouts.admin')

@section('title', 'Employee Management | CPC Inventory Management System')

@section('content')

<style>
    /* =========================================================
       CPC INVENTORY MANAGEMENT SYSTEM
       EMPLOYEE MANAGEMENT
       Enterprise Dashboard UI
    ========================================================= */

    .employee-page {
        max-width: 1600px;
        margin: 0 auto;
        padding: 4px 0 36px;
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .employee-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        color: #8a94a4;
        font-size: 12px;
        font-weight: 500;
    }

    .employee-breadcrumb i {
        font-size: 10px;
        color: #aab2bf;
    }

    .employee-breadcrumb .current {
        color: #4b5563;
        font-weight: 600;
    }

    /* =========================================================
       HERO HEADER
    ========================================================= */

    .employee-hero {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(
                135deg,
                #ffffff 0%,
                #f8fbff 55%,
                #eef5ff 100%
            );
        border: 1px solid #dfe7f2;
        border-radius: 20px;
        padding: 26px 28px;
        margin-bottom: 22px;
        box-shadow: 0 6px 24px rgba(15, 23, 42, 0.055);
    }

    .employee-hero::before {
        content: "";
        position: absolute;
        width: 190px;
        height: 190px;
        right: -70px;
        top: -105px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.055);
    }

    .employee-hero::after {
        content: "";
        position: absolute;
        width: 130px;
        height: 130px;
        right: 120px;
        bottom: -95px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.035);
    }

    .employee-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .employee-header-left {
        display: flex;
        align-items: center;
        gap: 17px;
        min-width: 0;
    }

    .employee-header-icon {
        width: 62px;
        height: 62px;
        flex: 0 0 62px;
        border-radius: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0d6efd, #4d94ff);
        color: #ffffff;
        font-size: 26px;
        box-shadow: 0 8px 18px rgba(13, 110, 253, 0.22);
    }

    .employee-title-area {
        min-width: 0;
    }

    .employee-page-title {
        margin: 0;
        color: #172033;
        font-size: 25px;
        line-height: 1.25;
        font-weight: 750;
        letter-spacing: -0.45px;
    }

    .employee-page-subtitle {
        margin: 6px 0 9px;
        max-width: 760px;
        color: #687386;
        font-size: 13px;
        line-height: 1.6;
    }

    .employee-context-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 20px;
        background: #edf5ff;
        border: 1px solid #d8e8ff;
        color: #0d63d8;
        font-size: 11px;
        font-weight: 700;
    }

    .employee-context-badge i {
        font-size: 11px;
    }

    .employee-primary-btn {
        position: relative;
        z-index: 3;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 42px;
        padding: 10px 17px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 5px 14px rgba(13, 110, 253, 0.18);
    }

    /* =========================================================
       ROLE-BASED DISABLED UI
    ========================================================= */

    .employee-disabled-action {
        opacity: 0.48;
        cursor: not-allowed !important;
        filter: grayscale(12%);
        transform: none !important;
        box-shadow: none !important;
        user-select: none;
    }

    .employee-disabled-action:hover,
    .employee-disabled-action:focus,
    .employee-disabled-action:active {
        opacity: 0.48;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }

    .employee-readonly-notice {
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

    .employee-readonly-notice i {
        color: #0d6efd;
        font-size: 14px;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .employee-alert {
        border-radius: 11px;
        padding: 12px 15px;
        font-size: 13px;
        margin-bottom: 20px;
    }

    /* =========================================================
       KPI CARDS
    ========================================================= */

    .employee-stat-card {
        position: relative;
        height: 100%;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e4e9f0;
        border-radius: 15px;
        padding: 18px;
        box-shadow: 0 4px 17px rgba(15, 23, 42, 0.045);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
    }

    .employee-stat-card::after {
        content: "";
        position: absolute;
        width: 85px;
        height: 85px;
        right: -38px;
        bottom: -45px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.035);
    }

    .employee-stat-card:hover {
        transform: translateY(-3px);
        border-color: #d7e3f3;
        box-shadow: 0 9px 25px rgba(15, 23, 42, 0.075);
    }

    .employee-stat-icon {
        width: 43px;
        height: 43px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 13px;
        font-size: 18px;
    }

    .employee-stat-icon.total {
        background: #eaf2ff;
        color: #0d6efd;
    }

    .employee-stat-icon.assets {
        background: #fff6e7;
        color: #d88900;
    }

    .employee-stat-icon.organisation {
        background: #f1ecff;
        color: #6f42c1;
    }

    .employee-stat-icon.assignment {
        background: #eaf8f0;
        color: #198754;
    }

    .employee-stat-label {
        color: #697386;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .employee-stat-number {
        color: #172033;
        font-size: 25px;
        font-weight: 750;
        line-height: 1.2;
    }

    .employee-stat-description {
        margin-top: 5px;
        color: #98a1af;
        font-size: 11px;
    }

    /* =========================================================
       MAIN DIRECTORY CARD
    ========================================================= */

    .employee-table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 17px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(15, 23, 42, 0.05);
    }

    .employee-table-header {
        padding: 21px 23px;
        border-bottom: 1px solid #edf0f4;
        background: #ffffff;
    }

    .employee-directory-heading {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .employee-directory-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #edf4ff;
        color: #0d6efd;
        font-size: 15px;
    }

    .employee-table-title {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 750;
        letter-spacing: -0.15px;
    }

    .employee-table-description {
        margin: 5px 0 0 46px;
        color: #7d8796;
        font-size: 12px;
    }

    .employee-record-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-left: 8px;
        padding: 4px 8px;
        border-radius: 15px;
        background: #f4f6f9;
        color: #687386;
        font-size: 10px;
        font-weight: 700;
        vertical-align: middle;
    }

    /* =========================================================
       SEARCH
    ========================================================= */

    .employee-toolbar-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .employee-search-wrapper {
        position: relative;
        width: 330px;
    }

    .employee-search-wrapper > i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #8d97a6;
        pointer-events: none;
        font-size: 13px;
    }

    .employee-search {
        width: 100%;
        height: 40px;
        padding: 0 13px 0 38px;
        border: 1px solid #dce2ea;
        border-radius: 9px;
        outline: none;
        background: #ffffff;
        color: #273142;
        font-size: 12px;
        transition: all 0.2s ease;
    }

    .employee-search::placeholder {
        color: #a1a9b5;
    }

    .employee-search:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
    }

    .employee-reset {
        height: 40px;
        width: 40px;
        padding: 0;
        border: 1px solid #dce2ea;
        border-radius: 9px;
        background: #ffffff;
        color: #687386;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .employee-reset:hover {
        color: #0d6efd;
        background: #f5f9ff;
        border-color: #cfe0ff;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .employee-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .employee-table {
        width: 100%;
        min-width: 1120px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .employee-table thead th {
        background: #f8fafc;
        color: #667085;
        padding: 12px 14px;
        border-bottom: 1px solid #e7ebf0;
        font-size: 10.5px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.48px;
        white-space: nowrap;
    }

    .employee-table tbody td {
        padding: 14px;
        border-bottom: 1px solid #edf0f4;
        color: #374151;
        font-size: 12.5px;
        vertical-align: middle;
    }

    .employee-table tbody tr {
        transition: background 0.15s ease;
    }

    .employee-table tbody tr:hover {
        background: #f9fbff;
    }

    .employee-table tbody tr:last-child td {
        border-bottom: none;
    }

    .employee-row-number {
        width: 36px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #f4f6f9;
        color: #7c8796;
        font-size: 11px;
        font-weight: 650;
    }

    /* =========================================================
       EMPLOYEE IDENTITY
    ========================================================= */

    .employee-identity {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 190px;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eaf2ff, #dfeeff);
        color: #0d6efd;
        font-size: 16px;
    }

    .employee-name {
        max-width: 190px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #202939;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
    }

    .employee-epf {
        margin-top: 3px;
        color: #8a94a4;
        font-size: 10.5px;
    }

    .employee-epf strong {
        color: #687386;
    }

    /* =========================================================
       ORGANIZATIONAL INFORMATION
    ========================================================= */

    .employee-info {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #4b5563;
        font-size: 12px;
        white-space: nowrap;
    }

    .employee-info i {
        color: #8b95a5;
        font-size: 12px;
    }

    .employee-designation {
        max-width: 170px;
        color: #4b5563;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .employee-empty-value {
        color: #a1a9b5;
    }

    /* =========================================================
       CONTACT
    ========================================================= */

    .employee-contact {
        width: 185px;
        color: #596273;
        font-size: 11.5px;
    }

    .employee-contact div {
        max-width: 185px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 1.8;
    }

    .employee-contact i {
        color: #8b95a5;
    }

    /* =========================================================
       ASSET COUNT
    ========================================================= */

    .employee-asset-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-width: 88px;
        justify-content: center;
        padding: 6px 9px;
        border-radius: 8px;
        background: #f1f6ff;
        color: #0d6efd;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .employee-asset-count:hover {
        background: #e4efff;
        color: #0a58ca;
    }

    .employee-asset-count.empty {
        background: #f5f6f8;
        color: #7b8493;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .employee-actions {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: nowrap;
    }

    .employee-action {
        width: 32px;
        height: 32px;
        padding: 0;
        flex: 0 0 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        font-size: 12px;
    }

    .employee-action-assets {
        color: #0d6efd;
        border: 1px solid #cfe0ff;
        background: #f7faff;
    }

    .employee-action-assets:hover {
        color: #ffffff;
        background: #0d6efd;
        border-color: #0d6efd;
    }

    .employee-action-delete {
        color: #dc3545;
        border: 1px solid #f1b7bd;
        background: #fff8f8;
    }

    .employee-action-delete:hover {
        color: #ffffff;
        background: #dc3545;
        border-color: #dc3545;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .employee-pagination {
        padding: 17px 22px;
        border-top: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
    }

    .employee-pagination .pagination {
        margin: 0;
        gap: 4px;
    }

    .employee-pagination .page-link {
        border-radius: 7px !important;
        border: 1px solid #e0e5ec;
        color: #596273;
        font-size: 11px;
        min-width: 31px;
        text-align: center;
    }

    .employee-pagination .page-item.active .page-link {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .employee-empty {
        padding: 75px 25px;
        text-align: center;
    }

    .employee-empty-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 18px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: #98a2b3;
        font-size: 31px;
    }

    .employee-empty h5 {
        margin-bottom: 7px;
        color: #374151;
        font-weight: 750;
    }

    .employee-empty p {
        margin-bottom: 20px;
        color: #8a94a4;
        font-size: 13px;
    }

    /* =========================================================
       SEARCH EMPTY STATE
    ========================================================= */

    .employee-no-results {
        display: none;
        padding: 48px 20px;
        text-align: center;
        border-top: 1px solid #edf0f4;
    }

    .employee-no-results-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 12px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f5f8;
        color: #9aa4b2;
        font-size: 21px;
    }

    .employee-no-results h6 {
        margin-bottom: 5px;
        color: #4b5563;
        font-weight: 700;
    }

    .employee-no-results p {
        margin: 0;
        color: #929baa;
        font-size: 12px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 992px) {

        .employee-hero-content {
            align-items: flex-start;
        }

        .employee-search-wrapper {
            width: 280px;
        }

    }

    @media (max-width: 768px) {

        .employee-page {
            padding-bottom: 25px;
        }

        .employee-hero {
            padding: 20px;
            border-radius: 16px;
        }

        .employee-hero-content {
            flex-direction: column;
            align-items: stretch;
        }

        .employee-header-left {
            align-items: flex-start;
        }

        .employee-header-icon {
            width: 52px;
            height: 52px;
            flex-basis: 52px;
            border-radius: 14px;
            font-size: 22px;
        }

        .employee-page-title {
            font-size: 21px;
        }

        .employee-page-subtitle {
            font-size: 12px;
        }

        .employee-primary-btn {
            width: 100%;
        }

        .employee-table-header {
            padding: 18px;
        }

        .employee-toolbar-actions {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
            margin-top: 14px;
        }

        .employee-search-wrapper {
            width: 100%;
        }

        .employee-reset {
            width: 100%;
        }

        .employee-table-description {
            margin-left: 0;
        }

    }

    @media (max-width: 480px) {

        .employee-breadcrumb {
            margin-bottom: 10px;
        }

        .employee-hero {
            padding: 17px;
        }

        .employee-header-left {
            gap: 12px;
        }

        .employee-header-icon {
            width: 46px;
            height: 46px;
            flex-basis: 46px;
            font-size: 19px;
        }

        .employee-page-title {
            font-size: 19px;
        }

        .employee-context-badge {
            font-size: 10px;
        }

        .employee-stat-card {
            padding: 16px;
        }

        .employee-stat-number {
            font-size: 22px;
        }

    }
</style>


<div class="employee-page">

    {{-- =====================================================
         BREADCRUMB
    ====================================================== --}}

    <div class="employee-breadcrumb">

        <span>Inventory</span>

        <i class="bi bi-chevron-right"></i>

        <span class="current">
            Employee Management
        </span>

    </div>


    {{-- =====================================================
         HERO HEADER
    ====================================================== --}}

    <div class="employee-hero">

        <div class="employee-hero-content">

            <div class="employee-header-left">

                <div class="employee-header-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div class="employee-title-area">

                    <h1 class="employee-page-title">
                        Employee Management
                    </h1>

                    <p class="employee-page-subtitle">
                        Manage CPC employee records, organizational information,
                        contact details and currently issued IT assets.
                    </p>

                    <span class="employee-context-badge">
                        <i class="bi bi-diagram-3-fill"></i>
                        Inventory &amp; Organizational Management
                    </span>

                    {{-- EMPLOYEE READ-ONLY NOTICE --}}

                    @if(auth()->user()->role === 'employee')

                        <div class="employee-readonly-notice">

                            <i class="bi bi-info-circle-fill"></i>

                            <span>
                                Employees have view and search access only.
                                Management actions are disabled.
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 REGISTER EMPLOYEE
                 ADMIN + SUPER ADMIN = ENABLED
                 EMPLOYEE = DISABLED
            ================================================== --}}

            @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))

                <a
                    href="{{ route('admin.employees.create') }}"
                    class="btn btn-primary employee-primary-btn"
                >

                    <i class="bi bi-person-plus-fill"></i>

                    Register Employee

                </a>

            @else

                <button
                    type="button"
                    class="btn btn-primary employee-primary-btn employee-disabled-action"
                    disabled
                    title="Employees have view and search access only"
                    aria-label="Register Employee disabled"
                >

                    <i class="bi bi-person-plus-fill"></i>

                    Register Employee

                </button>

            @endif

        </div>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if (session('success'))

        <div class="alert alert-success border-0 shadow-sm employee-alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =====================================================
         ERROR MESSAGE
    ====================================================== --}}

    @if (session('error'))

        <div class="alert alert-danger border-0 shadow-sm employee-alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- =====================================================
         KPI SUMMARY
    ====================================================== --}}

    <div class="row g-3 mb-4">

        {{-- TOTAL EMPLOYEES --}}

        <div class="col-xl-3 col-md-6">

            <div class="employee-stat-card">

                <div class="employee-stat-icon total">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div class="employee-stat-label">
                    Total Employees
                </div>

                <div class="employee-stat-number">
                    {{ $employees->total() }}
                </div>

                <div class="employee-stat-description">
                    Registered employee records
                </div>

            </div>

        </div>


        {{-- ASSIGNED ASSETS --}}

        <div class="col-xl-3 col-md-6">

            <div class="employee-stat-card">

                <div class="employee-stat-icon assets">
                    <i class="bi bi-pc-display"></i>
                </div>

                <div class="employee-stat-label">
                    Assigned Assets
                </div>

                <div class="employee-stat-number">

                    {{ $employees->getCollection()->sum(function ($employee) {

                        return $employee->itemAssignments
                            ->whereNull('returned_date')
                            ->count();

                    }) }}

                </div>

                <div class="employee-stat-description">
                    Currently issued assets
                </div>

            </div>

        </div>


        {{-- ORGANIZATIONAL RECORDS --}}

        <div class="col-xl-3 col-md-6">

            <div class="employee-stat-card">

                <div class="employee-stat-icon organisation">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>

                <div class="employee-stat-label">
                    Organizational Records
                </div>

                <div class="employee-stat-number">

                    {{ $employees->getCollection()->filter(function ($employee) {

                        return $employee->branch_id ||
                               $employee->department_id ||
                               $employee->function_unit_id;

                    })->count() }}

                </div>

                <div class="employee-stat-description">
                    Employees with organization data
                </div>

            </div>

        </div>


        {{-- EMPLOYEES WITH ASSETS --}}

        <div class="col-xl-3 col-md-6">

            <div class="employee-stat-card">

                <div class="employee-stat-icon assignment">
                    <i class="bi bi-person-workspace"></i>
                </div>

                <div class="employee-stat-label">
                    Employees with Assets
                </div>

                <div class="employee-stat-number">

                    {{ $employees->getCollection()->filter(function ($employee) {

                        return $employee->itemAssignments
                            ->whereNull('returned_date')
                            ->count() > 0;

                    })->count() }}

                </div>

                <div class="employee-stat-description">
                    Employees with current assignments
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         EMPLOYEE DIRECTORY
    ====================================================== --}}

    <div class="employee-table-card">

        {{-- TABLE HEADER --}}

        <div class="employee-table-header">

            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">

                <div>

                    <div class="employee-directory-heading">

                        <div class="employee-directory-icon">
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>

                        <h5 class="employee-table-title">

                            Employee Directory

                            <span class="employee-record-badge">

                                <i class="bi bi-database"></i>

                                {{ $employees->total() }} Records

                            </span>

                        </h5>

                    </div>

                    <p class="employee-table-description">

                        Search, review and manage CPC employee profiles and
                        inventory assignments.

                    </p>

                </div>


                {{-- SEARCH --}}

                <div class="employee-toolbar-actions">

                    <div class="employee-search-wrapper">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            id="employeeSearch"
                            class="employee-search"
                            placeholder="Search EPF, name, department..."
                            autocomplete="off"
                            aria-label="Search employees"
                        >

                    </div>


                    {{-- RESET --}}

                    <button
                        type="button"
                        id="employeeReset"
                        class="employee-reset"
                        title="Reset search"
                        aria-label="Reset search"
                    >

                        <i class="bi bi-arrow-counterclockwise"></i>

                    </button>

                </div>

            </div>

        </div>


        @if ($employees->count() > 0)

            {{-- =================================================
                 TABLE
            ================================================== --}}

            <div class="employee-table-wrapper">

                <table
                    class="employee-table"
                    id="employeesTable"
                >

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Employee</th>

                            <th>Designation</th>

                            <th>Branch</th>

                            <th>Department</th>

                            <th>Function Unit</th>

                            <th>Contact</th>

                            <th>Issued Assets</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($employees as $employee)

                            <tr>

                                {{-- NUMBER --}}

                                <td>

                                    <span class="employee-row-number">

                                        {{ $employees->firstItem() + $loop->index }}

                                    </span>

                                </td>


                                {{-- EMPLOYEE --}}

                                <td>

                                    <div class="employee-identity">

                                        <div class="employee-avatar">

                                            <i class="bi bi-person-fill"></i>

                                        </div>

                                        <div>

                                            <div
                                                class="employee-name"
                                                title="{{ $employee->name }}"
                                            >

                                                {{ $employee->name }}

                                            </div>

                                            <div class="employee-epf">

                                                <strong>EPF:</strong>

                                                {{ $employee->epf_no }}

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- DESIGNATION --}}

                                <td>

                                    @if ($employee->designation)

                                        <div
                                            class="employee-designation"
                                            title="{{ $employee->designation }}"
                                        >

                                            {{ $employee->designation }}

                                        </div>

                                    @else

                                        <span class="employee-empty-value">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- BRANCH --}}

                                <td>

                                    <div class="employee-info">

                                        <i class="bi bi-building"></i>

                                        <span>

                                            {{ $employee->branch->name ?? '—' }}

                                        </span>

                                    </div>

                                </td>


                                {{-- DEPARTMENT --}}

                                <td>

                                    <div class="employee-info">

                                        <i class="bi bi-diagram-3"></i>

                                        <span>

                                            {{ $employee->department->name ?? '—' }}

                                        </span>

                                    </div>

                                </td>


                                {{-- FUNCTION UNIT --}}

                                <td>

                                    <div class="employee-info">

                                        <i class="bi bi-grid-1x2"></i>

                                        <span>

                                            {{ $employee->functionUnit->name ?? '—' }}

                                        </span>

                                    </div>

                                </td>


                                {{-- CONTACT --}}

                                <td>

                                    <div class="employee-contact">

                                        @if ($employee->email)

                                            <div title="{{ $employee->email }}">

                                                <i class="bi bi-envelope me-1"></i>

                                                {{ $employee->email }}

                                            </div>

                                        @endif


                                        @if ($employee->phone)

                                            <div title="{{ $employee->phone }}">

                                                <i class="bi bi-telephone me-1"></i>

                                                {{ $employee->phone }}

                                            </div>

                                        @endif


                                        @if (!$employee->email && !$employee->phone)

                                            <span class="employee-empty-value">
                                                —
                                            </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- ISSUED ASSETS --}}

                                <td>

                                    @php

                                        $assetCount = $employee->itemAssignments
                                            ->whereNull('returned_date')
                                            ->count();

                                    @endphp


                                    @if ($assetCount > 0)

                                        <a
                                            href="{{ route('admin.employees.show', $employee->id) }}"
                                            class="employee-asset-count text-decoration-none"
                                            title="View assigned assets"
                                        >

                                            <i class="bi bi-box-seam-fill"></i>

                                            {{ $assetCount }}

                                            {{ $assetCount === 1 ? 'Asset' : 'Assets' }}

                                        </a>

                                    @else

                                        <span class="employee-asset-count empty">

                                            <i class="bi bi-box"></i>

                                            No Assets

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     ACTIONS
                                ================================================== --}}

                                <td>

                                    <div class="employee-actions">

                                        {{-- VIEW PROFILE --}}

                                        <a
                                            href="{{ route('admin.employees.show', $employee->id) }}"
                                            class="btn btn-outline-primary employee-action"
                                            title="View Employee Profile"
                                            aria-label="View Employee Profile"
                                        >

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        {{-- VIEW ASSETS --}}

                                        <a
                                            href="{{ route('admin.employees.show', $employee->id) }}"
                                            class="btn employee-action employee-action-assets"
                                            title="View Issued Assets"
                                            aria-label="View Issued Assets"
                                        >

                                            <i class="bi bi-box-seam"></i>

                                        </a>


                                        {{-- =================================================
                                             EDIT
                                             ADMIN + SUPER ADMIN = ENABLED
                                             EMPLOYEE = DISABLED
                                        ================================================== --}}

                                        @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))

                                            <a
                                                href="{{ route('admin.employees.edit', $employee->id) }}"
                                                class="btn btn-outline-secondary employee-action"
                                                title="Edit Employee"
                                                aria-label="Edit Employee"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </a>

                                        @else

                                            <button
                                                type="button"
                                                class="btn btn-outline-secondary employee-action employee-disabled-action"
                                                disabled
                                                title="Employees have view and search access only"
                                                aria-label="Edit Employee disabled"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </button>

                                        @endif


                                        {{-- =================================================
                                             DELETE
                                             ADMIN + SUPER ADMIN = ENABLED
                                             EMPLOYEE = DISABLED
                                        ================================================== --}}

                                        @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))

                                            <form
                                                action="{{ route('admin.employees.destroy', $employee->id) }}"
                                                method="POST"
                                                onsubmit="return confirmDeleteEmployee(@js($employee->name));"
                                                class="d-inline"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn employee-action employee-action-delete"
                                                    title="Delete Employee"
                                                    aria-label="Delete Employee"
                                                >

                                                    <i class="bi bi-trash3"></i>

                                                </button>

                                            </form>

                                        @else

                                            <button
                                                type="button"
                                                class="btn employee-action employee-action-delete employee-disabled-action"
                                                disabled
                                                title="Employees have view and search access only"
                                                aria-label="Delete Employee disabled"
                                            >

                                                <i class="bi bi-trash3"></i>

                                            </button>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 SEARCH EMPTY STATE
            ================================================== --}}

            <div
                id="employeeNoResults"
                class="employee-no-results"
            >

                <div class="employee-no-results-icon">

                    <i class="bi bi-search"></i>

                </div>

                <h6>
                    No matching employees found
                </h6>

                <p>
                    Try a different employee name, EPF number,
                    department or designation.
                </p>

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}

            <div class="employee-pagination">

                {{ $employees->links('pagination::bootstrap-5') }}

            </div>


        @else

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}

            <div class="employee-empty">

                <div class="employee-empty-icon">

                    <i class="bi bi-people"></i>

                </div>

                <h5>
                    No Employees Registered
                </h5>

                <p>
                    No employee records have been registered
                    in the system yet.
                </p>


                {{-- =================================================
                     REGISTER FIRST EMPLOYEE
                ================================================== --}}

                @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))

                    <a
                        href="{{ route('admin.employees.create') }}"
                        class="btn btn-primary employee-primary-btn"
                    >

                        <i class="bi bi-person-plus-fill"></i>

                        Register First Employee

                    </a>

                @else

                    <button
                        type="button"
                        class="btn btn-primary employee-primary-btn employee-disabled-action"
                        disabled
                        title="Employees have view and search access only"
                    >

                        <i class="bi bi-person-plus-fill"></i>

                        Register First Employee

                    </button>

                @endif

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     EMPLOYEE READ-ONLY MESSAGE
========================================================= --}}

<script>

function employeeReadOnlyMessage()
{
    alert(
        'Employees have view and search access only.'
    );
}


/* =========================================================
   SEARCH
========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('employeeSearch');

    const resetButton =
        document.getElementById('employeeReset');

    const table =
        document.getElementById('employeesTable');

    const noResults =
        document.getElementById('employeeNoResults');


    if (!searchInput || !table) {
        return;
    }


    const rows =
        table.querySelectorAll('tbody tr');


    function filterEmployees()
    {
        const searchValue =
            searchInput.value
                .toLowerCase()
                .trim();


        let visibleRows = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent
                    .toLowerCase();


            const matchesSearch =
                searchValue === '' ||
                rowText.includes(searchValue);


            if (matchesSearch) {

                row.style.display = '';

                visibleRows++;

            } else {

                row.style.display = 'none';

            }

        });


        if (noResults) {

            noResults.style.display =
                visibleRows === 0
                    ? 'block'
                    : 'none';

        }

    }


    searchInput.addEventListener(
        'input',
        filterEmployees
    );


    if (resetButton) {

        resetButton.addEventListener(
            'click',
            function () {

                searchInput.value = '';

                filterEmployees();

                searchInput.focus();

            }
        );

    }

});


/* =========================================================
   DELETE CONFIRMATION
========================================================= */

function confirmDeleteEmployee(employeeName)
{
    return confirm(
        'Delete Employee\n\n' +
        'Are you sure you want to permanently delete "' +
        employeeName +
        '"?\n\n' +
        'This action cannot be undone.'
    );
}

</script>

@endsection