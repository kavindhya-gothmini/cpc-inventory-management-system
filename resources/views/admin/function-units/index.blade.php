@extends('layouts.admin')

@section('title', 'Function Unit Management | CPC Inventory Management System')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | ROLE & PERMISSION CONTROL
    |--------------------------------------------------------------------------
    | Super Admin : Full management access
    | Admin       : View + Search only
    | Employee    : View + Search only
    |--------------------------------------------------------------------------
    */

    $currentUser = auth()->user();

    $isAdmin = $currentUser
        && $currentUser->role === 'admin';

    $isSuperAdmin = $currentUser
        && $currentUser->role === 'super_admin';

    $isEmployee = $currentUser
        && $currentUser->role === 'employee';

    /*
    |--------------------------------------------------------------------------
    | ONLY SUPER ADMIN CAN MANAGE FUNCTION UNITS
    |--------------------------------------------------------------------------
    */

    $canManageFunctionUnits = $isSuperAdmin;

    /*
    |--------------------------------------------------------------------------
    | READ-ONLY USERS
    |--------------------------------------------------------------------------
    | Admin and Employee users can view and search only.
    |--------------------------------------------------------------------------
    */

    $isReadOnly = !$isSuperAdmin;
@endphp

<style>
    /* =========================================================
       FUNCTION UNIT MANAGEMENT — ENTERPRISE UI
    ========================================================= */

    .function-unit-page {
        max-width: 1600px;
        margin: 0 auto;
        padding: 4px 0 36px;
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .fu-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 14px 2px;
        color: #8a94a6;
        font-size: 12px;
        font-weight: 600;
    }

    .fu-breadcrumb i {
        font-size: 10px;
        color: #b0b8c5;
    }

    .fu-breadcrumb .active {
        color: #0d6efd;
    }

    /* =========================================================
       HERO HEADER
    ========================================================= */

    .fu-hero {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(
                135deg,
                #ffffff 0%,
                #f5f8ff 55%,
                #eaf2ff 100%
            );
        border: 1px solid #dfe7f3;
        border-radius: 20px;
        padding: 28px;
        margin-bottom: 22px;
        box-shadow: 0 8px 28px rgba(30, 55, 90, 0.07);
    }

    .fu-hero::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.055);
        right: -70px;
        top: -110px;
    }

    .fu-hero::after {
        content: "";
        position: absolute;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.035);
        right: 100px;
        bottom: -105px;
    }

    .fu-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .fu-hero-main {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .fu-hero-icon {
        width: 62px;
        height: 62px;
        min-width: 62px;
        border-radius: 16px;
        background: linear-gradient(
            135deg,
            #0d6efd,
            #3d8bfd
        );
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        box-shadow: 0 10px 22px rgba(13, 110, 253, 0.20);
    }

    .fu-context-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 20px;
        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, 0.10);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .fu-page-title {
        font-size: 27px;
        line-height: 1.2;
        font-weight: 750;
        color: #172033;
        margin: 0;
        letter-spacing: -0.4px;
    }

    .fu-page-subtitle {
        color: #718096;
        font-size: 13px;
        line-height: 1.6;
        margin: 7px 0 0;
        max-width: 720px;
    }

    .fu-hero-action {
        position: relative;
        z-index: 3;
        flex-shrink: 0;
    }

    .fu-primary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 650;
        box-shadow: 0 7px 18px rgba(13, 110, 253, 0.17);
        transition: all 0.2s ease;
    }

    .fu-primary-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(13, 110, 253, 0.23);
    }

    /* =========================================================
       READ-ONLY UI
    ========================================================= */

    .fu-disabled-action {
        opacity: 0.48;
        cursor: not-allowed !important;
        filter: grayscale(12%);
        transform: none !important;
        box-shadow: none !important;
        user-select: none;
    }

    .fu-disabled-action:hover,
    .fu-disabled-action:focus {
        opacity: 0.48;
        transform: none !important;
        box-shadow: none !important;
    }

    .fu-readonly-notice {
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

    .fu-readonly-notice i {
        color: #2f80ed;
        font-size: 14px;
    }

    /* =========================================================
       ALERT
    ========================================================= */

    .fu-alert {
        border: 0;
        border-radius: 12px;
        padding: 13px 16px;
        box-shadow: 0 4px 15px rgba(30, 55, 90, 0.05);
        font-size: 13px;
        margin-bottom: 22px;
    }

    /* =========================================================
       KPI CARDS
    ========================================================= */

    .fu-stat-card {
        position: relative;
        overflow: hidden;
        height: 100%;
        min-height: 145px;
        background: #ffffff;
        border: 1px solid #e3e8f0;
        border-radius: 17px;
        padding: 21px;
        box-shadow: 0 5px 20px rgba(30, 55, 90, 0.05);
        transition: all 0.25s ease;
    }

    .fu-stat-card:hover {
        transform: translateY(-3px);
        border-color: #d7e2f4;
        box-shadow: 0 12px 28px rgba(30, 55, 90, 0.09);
    }

    .fu-stat-card::before {
        content: "";
        position: absolute;
        width: 115px;
        height: 115px;
        border-radius: 50%;
        right: -48px;
        bottom: -58px;
        background: rgba(13, 110, 253, 0.045);
    }

    .fu-stat-top {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
    }

    .fu-stat-label {
        font-size: 10px;
        line-height: 1.4;
        font-weight: 750;
        letter-spacing: 0.75px;
        color: #7b8799;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .fu-stat-number {
        font-size: 30px;
        line-height: 1.1;
        font-weight: 750;
        color: #172033;
        letter-spacing: -0.5px;
    }

    .fu-stat-description {
        margin-top: 7px;
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.5;
    }

    .fu-stat-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 13px;
        background: #edf5ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .fu-stat-card-active .fu-stat-icon {
        background: #eaf8f0;
        color: #16834b;
    }

    .fu-stat-card-inactive .fu-stat-icon {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* =========================================================
       DIRECTORY CARD
    ========================================================= */

    .fu-directory-card {
        background: #ffffff;
        border: 1px solid #e1e7ef;
        border-radius: 19px;
        overflow: hidden;
        box-shadow: 0 7px 24px rgba(30, 55, 90, 0.055);
    }

    .fu-directory-header {
        padding: 22px 24px;
        border-bottom: 1px solid #edf0f5;
        background: #ffffff;
    }

    .fu-section-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #0d6efd;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: 0.65px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .fu-section-label i {
        font-size: 12px;
    }

    .fu-directory-title {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .fu-table-title {
        font-size: 18px;
        font-weight: 720;
        color: #172033;
        margin: 0;
    }

    .fu-record-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border-radius: 20px;
        background: #f3f6fa;
        color: #667085;
        border: 1px solid #e4e8ee;
        font-size: 10px;
        font-weight: 700;
    }

    .fu-table-description {
        color: #8a94a6;
        font-size: 12px;
        margin-top: 5px;
        line-height: 1.5;
    }

    /* =========================================================
       TOOLBAR
    ========================================================= */

    .fu-toolbar {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .fu-search-box {
        position: relative;
        width: 270px;
    }

    .fu-search-box > i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        z-index: 2;
        font-size: 13px;
    }

    .fu-search-input {
        height: 41px;
        padding-left: 37px;
        padding-right: 12px;
        border-radius: 9px !important;
        border: 1px solid #dfe4ec;
        background: #f8f9fb;
        color: #374151;
        font-size: 12px;
        transition: all 0.2s ease;
    }

    .fu-search-input::placeholder {
        color: #a0a9b7;
    }

    .fu-search-input:focus {
        background: #ffffff;
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
    }

    .fu-status-filter {
        height: 41px;
        min-width: 135px;
        border-radius: 9px;
        border: 1px solid #dfe4ec;
        background: #f8f9fb;
        color: #465467;
        font-size: 12px;
        cursor: pointer;
    }

    .fu-status-filter:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
    }

    .fu-reset-btn {
        height: 41px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0 12px;
        border-radius: 9px;
        background: #ffffff;
        border: 1px solid #dfe4ec;
        color: #667085;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .fu-reset-btn:hover {
        background: #f8f9fb;
        border-color: #cfd6e0;
        color: #344054;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .fu-table-wrapper {
        overflow-x: auto;
    }

    .fu-table {
        width: 100%;
        min-width: 1000px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .fu-table thead th {
        background: #f8f9fb;
        color: #7b8799;
        font-size: 9.5px;
        font-weight: 750;
        letter-spacing: 0.75px;
        text-transform: uppercase;
        padding: 13px 18px;
        border-bottom: 1px solid #e7ebf1;
        white-space: nowrap;
    }

    .fu-table tbody td {
        padding: 15px 18px;
        border-bottom: 1px solid #f0f2f6;
        vertical-align: middle;
        color: #374151;
        font-size: 12.5px;
    }

    .fu-table tbody tr {
        transition: background 0.18s ease;
    }

    .fu-table tbody tr:hover {
        background: #f9fbff;
    }

    .fu-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       ROW NUMBER
    ========================================================= */

    .fu-row-number {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f5f7fa;
        color: #7b8799;
        border: 1px solid #e7ebf0;
        font-size: 10px;
        font-weight: 700;
    }

    /* =========================================================
       FUNCTION UNIT IDENTITY
    ========================================================= */

    .fu-unit-identity {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fu-unit-icon {
        width: 43px;
        height: 43px;
        min-width: 43px;
        border-radius: 11px;
        background: linear-gradient(
            135deg,
            #edf5ff,
            #e5efff
        );
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .fu-unit-name {
        font-size: 13px;
        line-height: 1.35;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .fu-unit-description {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #98a2b3;
        font-size: 10.5px;
    }

    /* =========================================================
       CODE
    ========================================================= */

    .fu-code {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 7px;
        background: #f7f8fa;
        border: 1px solid #e4e7ec;
        color: #465467;
        font-size: 10.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .fu-code i {
        color: #0d6efd;
        font-size: 11px;
    }

    /* =========================================================
       DEPARTMENT
    ========================================================= */

    .fu-department {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #465467;
        font-size: 12px;
        font-weight: 550;
    }

    .fu-department-icon {
        width: 29px;
        height: 29px;
        min-width: 29px;
        border-radius: 8px;
        background: #f3f6fa;
        color: #667085;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }

    .fu-not-assigned {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #98a2b3;
        font-size: 11px;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .fu-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .fu-status-active {
        background: #eaf8f0;
        color: #16834b;
        border: 1px solid #d6f0e1;
    }

    .fu-status-inactive {
        background: #f3f4f6;
        color: #667085;
        border: 1px solid #e5e7eb;
    }

    .fu-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .fu-action-group {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 5px;
    }

    .fu-action {
        width: 33px;
        height: 33px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .fu-action:hover {
        transform: translateY(-1px);
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .fu-pagination {
        padding: 17px 24px;
        border-top: 1px solid #edf0f5;
        background: #ffffff;
    }

    .fu-pagination-info {
        color: #8a94a6;
        font-size: 11px;
    }

    .fu-pagination-info strong {
        color: #374151;
        font-weight: 700;
    }

    .fu-pagination .pagination {
        margin-bottom: 0;
    }

    /* =========================================================
       SEARCH EMPTY STATE
    ========================================================= */

    .fu-search-empty {
        display: none;
        padding: 55px 25px;
        text-align: center;
        border-top: 1px solid #edf0f5;
    }

    .fu-search-empty-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #edf5ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .fu-search-empty-title {
        color: #273142;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .fu-search-empty-text {
        max-width: 430px;
        margin: 0 auto;
        color: #8a94a6;
        font-size: 12px;
        line-height: 1.6;
    }

    /* =========================================================
       DATABASE EMPTY STATE
    ========================================================= */

    .fu-empty {
        padding: 75px 25px;
        text-align: center;
    }

    .fu-empty-icon {
        width: 84px;
        height: 84px;
        margin: 0 auto 18px;
        border-radius: 50%;
        background: #edf5ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
    }

    .fu-empty-title {
        color: #273142;
        font-size: 18px;
        font-weight: 720;
        margin-bottom: 7px;
    }

    .fu-empty-text {
        max-width: 480px;
        margin: 0 auto 23px;
        color: #8a94a6;
        font-size: 12.5px;
        line-height: 1.65;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1199px) {

        .fu-hero-content {
            align-items: flex-start;
        }

        .fu-search-box {
            width: 230px;
        }

    }

    @media (max-width: 991px) {

        .function-unit-page {
            padding-left: 4px;
            padding-right: 4px;
        }

        .fu-hero {
            padding: 23px;
        }

        .fu-hero-content {
            flex-direction: column;
        }

        .fu-hero-action {
            width: 100%;
        }

        .fu-hero-action .fu-primary-btn {
            width: 100%;
        }

        .fu-directory-header {
            padding: 20px;
        }

        .fu-toolbar {
            width: 100%;
        }

        .fu-search-box {
            flex: 1;
            width: auto;
        }

    }

    @media (max-width: 767px) {

        .fu-breadcrumb {
            margin-bottom: 10px;
        }

        .fu-hero {
            border-radius: 16px;
            padding: 20px;
        }

        .fu-hero-main {
            gap: 12px;
        }

        .fu-hero-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 14px;
            font-size: 21px;
        }

        .fu-page-title {
            font-size: 22px;
        }

        .fu-page-subtitle {
            font-size: 12px;
        }

        .fu-toolbar {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .fu-search-box,
        .fu-status-filter,
        .fu-reset-btn {
            width: 100%;
        }

        .fu-stat-card {
            min-height: 135px;
        }

    }

    @media (max-width: 576px) {

        .function-unit-page {
            padding-bottom: 25px;
        }

        .fu-hero {
            padding: 17px;
            border-radius: 14px;
        }

        .fu-context-pill {
            font-size: 9px;
        }

        .fu-page-title {
            font-size: 20px;
        }

        .fu-page-subtitle {
            font-size: 11.5px;
        }

        .fu-stat-card {
            padding: 18px;
            min-height: 125px;
        }

        .fu-stat-number {
            font-size: 26px;
        }

        .fu-stat-icon {
            width: 43px;
            height: 43px;
            min-width: 43px;
            font-size: 18px;
        }

        .fu-directory-header {
            padding: 17px;
        }

        .fu-table-title {
            font-size: 16px;
        }

        .fu-table-description {
            font-size: 11px;
        }

        .fu-pagination {
            padding: 16px;
        }

        .fu-pagination > div {
            flex-direction: column;
            align-items: stretch !important;
        }

        .fu-pagination-info {
            text-align: center;
        }

        .fu-pagination .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }

        .fu-empty {
            padding: 55px 18px;
        }

    }
</style>

<div class="function-unit-page">

{{-- =====================================================
     BREADCRUMB
====================================================== --}}
<div class="fu-breadcrumb">

    <span>Inventory</span>

    <i class="bi bi-chevron-right"></i>

    <span class="active">
        Function Units
    </span>

</div>


{{-- =====================================================
     HERO HEADER
====================================================== --}}
<div class="fu-hero">

    <div class="fu-hero-content">

        <div class="fu-hero-main">

            <div class="fu-hero-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>

            <div>

                <div class="fu-context-pill">
                    <i class="bi bi-shield-check"></i>
                    Inventory Administration
                </div>

                <h1 class="fu-page-title">
                    Function Unit Management
                </h1>

                <p class="fu-page-subtitle">
                    Manage CPC organizational function units used for
                    employee structure, inventory assignment and
                    IT asset administration.
                </p>

            </div>

        </div>

        <div class="fu-hero-action">

            {{-- =================================================
                 HEADER ADD BUTTON
            ================================================== --}}
            @if($canManageFunctionUnits)

                <a
                    href="{{ route('admin.function-units.create') }}"
                    class="btn btn-primary fu-primary-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    Add Function Unit
                </a>

            @else

                <button
                    type="button"
                    class="btn btn-primary fu-primary-btn fu-disabled-action"
                    onclick="functionUnitReadOnlyMessage()"
                    title="Only Super Admin can manage Function Units."
                >
                    <i class="bi bi-plus-lg"></i>
                    Add Function Unit
                </button>

            @endif


            {{-- =================================================
                 READ-ONLY NOTICE
            ================================================== --}}
            @if($isReadOnly)

                <div class="fu-readonly-notice">

                    <i class="bi bi-info-circle-fill"></i>

                    <span>
                        Only Super Admin can add, edit, activate or
                        deactivate Function Units.
                        Admin and Employee users have view and search
                        access only.
                    </span>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =====================================================
     SUCCESS ALERT
====================================================== --}}
@if(session('success'))

    <div class="alert alert-success fu-alert
                d-flex align-items-center">

        <i class="bi bi-check-circle-fill me-2"></i>

        <span>
            {{ session('success') }}
        </span>

        <button
            type="button"
            class="btn-close ms-auto"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- =====================================================
     KPI / SUMMARY CARDS
====================================================== --}}
<div class="row g-3 mb-4">

    {{-- TOTAL --}}
    <div class="col-xl-4 col-md-6">

        <div class="fu-stat-card">

            <div class="fu-stat-top">

                <div>

                    <div class="fu-stat-label">
                        Total Function Units
                    </div>

                    <div class="fu-stat-number">
                        {{ $functionUnits->total() }}
                    </div>

                    <div class="fu-stat-description">
                        Registered organizational units
                    </div>

                </div>

                <div class="fu-stat-icon">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- ACTIVE --}}
    <div class="col-xl-4 col-md-6">

        <div class="fu-stat-card fu-stat-card-active">

            <div class="fu-stat-top">

                <div>

                    <div class="fu-stat-label">
                        Active Units
                    </div>

                    <div class="fu-stat-number">
                        {{ $functionUnits->where('is_active', true)->count() }}
                    </div>

                    <div class="fu-stat-description">
                        Available for inventory assignment
                    </div>

                </div>

                <div class="fu-stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- INACTIVE --}}
    <div class="col-xl-4 col-md-6">

        <div class="fu-stat-card fu-stat-card-inactive">

            <div class="fu-stat-top">

                <div>

                    <div class="fu-stat-label">
                        Inactive Units
                    </div>

                    <div class="fu-stat-number">
                        {{ $functionUnits->where('is_active', false)->count() }}
                    </div>

                    <div class="fu-stat-description">
                        Deactivated organizational units
                    </div>

                </div>

                <div class="fu-stat-icon">
                    <i class="bi bi-archive-fill"></i>
                </div>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     FUNCTION UNIT DIRECTORY
====================================================== --}}
<div class="fu-directory-card">


    {{-- =================================================
         DIRECTORY HEADER
    ================================================== --}}
    <div class="fu-directory-header">

        <div class="d-flex justify-content-between
                    align-items-center flex-wrap gap-3">

            <div>

                <div class="fu-section-label">
                    <i class="bi bi-database"></i>
                    CPC Organizational Registry
                </div>

                <div class="fu-directory-title">

                    <h5 class="fu-table-title">
                        Function Unit Directory
                    </h5>

                    <span class="fu-record-badge">
                        <i class="bi bi-diagram-3"></i>
                        {{ $functionUnits->total() }} Records
                    </span>

                </div>

                <div class="fu-table-description">
                    View and manage function units within the CPC
                    organizational structure.
                </div>

            </div>


            {{-- TOOLBAR --}}
            <div class="fu-toolbar">

                {{-- SEARCH --}}
                <div class="fu-search-box">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        id="functionUnitSearch"
                        class="form-control fu-search-input"
                        placeholder="Search function units..."
                        autocomplete="off"
                    >

                </div>


                {{-- STATUS --}}
                <select
                    id="functionUnitStatus"
                    class="form-select fu-status-filter"
                >

                    <option value="all">
                        All Status
                    </option>

                    <option value="active">
                        Active
                    </option>

                    <option value="inactive">
                        Inactive
                    </option>

                </select>


                {{-- RESET --}}
                <button
                    type="button"
                    id="resetFunctionUnitFilters"
                    class="btn fu-reset-btn"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </button>

            </div>

        </div>

    </div>


    {{-- =================================================
         TABLE
    ================================================== --}}
    @if($functionUnits->count())

        <div class="fu-table-wrapper">

            <table
                class="fu-table"
                id="functionUnitsTable"
            >

                <thead>

                    <tr>

                        <th style="width:65px;">
                            #
                        </th>

                        <th>
                            Function Unit
                        </th>

                        <th>
                            Unit Code
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                @foreach($functionUnits as $functionUnit)

                    <tr
                        data-status="{{ $functionUnit->is_active ? 'active' : 'inactive' }}"
                    >

                        {{-- NUMBER --}}
                        <td>

                            <span class="fu-row-number">
                                {{ $functionUnits->firstItem() + $loop->index }}
                            </span>

                        </td>


                        {{-- FUNCTION UNIT --}}
                        <td>

                            <div class="fu-unit-identity">

                                <div class="fu-unit-icon">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </div>

                                <div>

                                    <div class="fu-unit-name">
                                        {{ $functionUnit->name }}
                                    </div>

                                    @if($functionUnit->description)

                                        <div
                                            class="fu-unit-description"
                                            title="{{ $functionUnit->description }}"
                                        >
                                            {{ $functionUnit->description }}
                                        </div>

                                    @else

                                        <div class="fu-unit-description">
                                            CPC Function Unit
                                        </div>

                                    @endif

                                </div>

                            </div>

                        </td>


                        {{-- CODE --}}
                        <td>

                            @if($functionUnit->code)

                                <span class="fu-code">

                                    <i class="bi bi-upc-scan"></i>

                                    {{ $functionUnit->code }}

                                </span>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- DEPARTMENT --}}
                        <td>

                            @if($functionUnit->department)

                                <div class="fu-department">

                                    <div class="fu-department-icon">
                                        <i class="bi bi-building"></i>
                                    </div>

                                    <span>
                                        {{ $functionUnit->department->name }}
                                    </span>

                                </div>

                            @else

                                <span class="fu-not-assigned">
                                    <i class="bi bi-dash-circle"></i>
                                    Not assigned
                                </span>

                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if($functionUnit->is_active)

                                <span class="fu-status fu-status-active">

                                    <span class="fu-status-dot"></span>

                                    Active

                                </span>

                            @else

                                <span class="fu-status fu-status-inactive">

                                    <span class="fu-status-dot"></span>

                                    Inactive

                                </span>

                            @endif

                        </td>


                        {{-- ACTIONS --}}
                        <td>

                            <div class="fu-action-group">

                                {{-- VIEW --}}
                                <a
                                    href="{{ route('admin.function-units.show', $functionUnit) }}"
                                    class="btn btn-outline-primary fu-action"
                                    title="View Function Unit"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>


                                {{-- EDIT --}}
                                @if($canManageFunctionUnits)

                                    <a
                                        href="{{ route('admin.function-units.edit', $functionUnit) }}"
                                        class="btn btn-outline-secondary fu-action"
                                        title="Edit Function Unit"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                @else

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary fu-action fu-disabled-action"
                                        onclick="functionUnitReadOnlyMessage()"
                                        title="Only Super Admin can edit Function Units."
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                @endif


                                {{-- ACTIVE / INACTIVE ACTION --}}
                                @if($functionUnit->is_active)

                                    {{-- DEACTIVATE --}}
                                    @if($canManageFunctionUnits)

                                        <form
                                            action="{{ route('admin.function-units.destroy', $functionUnit) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to deactivate this Function Unit?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger fu-action"
                                                title="Deactivate Function Unit"
                                            >
                                                <i class="bi bi-archive"></i>
                                            </button>

                                        </form>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-outline-danger fu-action fu-disabled-action"
                                            onclick="functionUnitReadOnlyMessage()"
                                            title="Only Super Admin can deactivate Function Units."
                                        >
                                            <i class="bi bi-archive"></i>
                                        </button>

                                    @endif

                                @else

                                    {{-- REACTIVATE --}}
                                    @if($canManageFunctionUnits)

                                        <form
                                            action="{{ route('admin.function-units.update', $functionUnit) }}"
                                            method="POST"
                                            class="d-inline"
                                        >

                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="hidden"
                                                name="name"
                                                value="{{ $functionUnit->name }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="code"
                                                value="{{ $functionUnit->code }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="department_id"
                                                value="{{ $functionUnit->department_id }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="description"
                                                value="{{ $functionUnit->description }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="is_active"
                                                value="1"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-outline-success fu-action"
                                                title="Reactivate Function Unit"
                                            >
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>

                                        </form>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-outline-success fu-action fu-disabled-action"
                                            onclick="functionUnitReadOnlyMessage()"
                                            title="Only Super Admin can reactivate Function Units."
                                        >
                                            <i class="bi bi-arrow-clockwise"></i>
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
             SEARCH EMPTY STATE
        ================================================== --}}
        <div
            id="noSearchResults"
            class="fu-search-empty"
        >

            <div class="fu-search-empty-icon">
                <i class="bi bi-search"></i>
            </div>

            <div class="fu-search-empty-title">
                No Matching Function Units
            </div>

            <p class="fu-search-empty-text">
                No function units match the current search or
                status filter. Try changing your search criteria
                or reset the filters.
            </p>

        </div>


        {{-- =================================================
             PAGINATION
        ================================================== --}}
        <div class="fu-pagination">

            <div class="d-flex justify-content-between
                        align-items-center flex-wrap gap-3">

                <div class="fu-pagination-info">

                    Showing

                    <strong>
                        {{ $functionUnits->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $functionUnits->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $functionUnits->total() }}
                    </strong>

                    function units

                </div>

                <div>
                    {{ $functionUnits->links() }}
                </div>

            </div>

        </div>

    @else

        {{-- =================================================
             EMPTY DATABASE STATE
        ================================================== --}}
        <div class="fu-empty">

            <div class="fu-empty-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>

            <div class="fu-empty-title">
                No Function Units Found
            </div>

            <p class="fu-empty-text">
                No CPC function units have been registered yet.
                Create the first function unit to begin managing
                organizational assignments and inventory structure.
            </p>


            {{-- ONLY SUPER ADMIN --}}
            @if($canManageFunctionUnits)

                <a
                    href="{{ route('admin.function-units.create') }}"
                    class="btn btn-primary fu-primary-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    Add First Function Unit
                </a>

            @else

                <button
                    type="button"
                    class="btn btn-primary fu-primary-btn fu-disabled-action"
                    onclick="functionUnitReadOnlyMessage()"
                    title="Only Super Admin can manage Function Units."
                >
                    <i class="bi bi-plus-lg"></i>
                    Add First Function Unit
                </button>

            @endif

        </div>

    @endif

</div>

</div>


{{-- =========================================================
     SEARCH + STATUS FILTER + READ-ONLY MESSAGE
========================================================= --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('functionUnitSearch');

    const statusFilter =
        document.getElementById('functionUnitStatus');

    const resetButton =
        document.getElementById('resetFunctionUnitFilters');

    const table =
        document.getElementById('functionUnitsTable');

    const noResults =
        document.getElementById('noSearchResults');


    if (!searchInput || !statusFilter || !table) {
        return;
    }


    function filterFunctionUnits() {

        const searchValue =
            searchInput.value
                .trim()
                .toLowerCase();

        const selectedStatus =
            statusFilter.value;

        const rows =
            table.querySelectorAll('tbody tr');

        let visibleRows = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent.toLowerCase();

            const rowStatus =
                row.dataset.status || '';


            const matchesSearch =
                searchValue === '' ||
                rowText.includes(searchValue);


            const matchesStatus =
                selectedStatus === 'all' ||
                rowStatus === selectedStatus;


            if (matchesSearch && matchesStatus) {

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
        filterFunctionUnits
    );


    statusFilter.addEventListener(
        'change',
        filterFunctionUnits
    );


    if (resetButton) {

        resetButton.addEventListener(
            'click',
            function () {

                searchInput.value = '';
                statusFilter.value = 'all';

                filterFunctionUnits();

                searchInput.focus();

            }
        );

    }

});


/*
|--------------------------------------------------------------------------
| FUNCTION UNIT READ-ONLY MESSAGE
|--------------------------------------------------------------------------
*/

function functionUnitReadOnlyMessage()
{
    alert(
        'Only Super Admin can add, edit, deactivate, or reactivate Function Units. Admin and Employee accounts have view and search access only.'
    );
}
</script>

@endpush

@endsection