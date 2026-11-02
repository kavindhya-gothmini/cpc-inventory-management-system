@extends('layouts.admin')

@section('title', 'Inventory Item Types | CPC Inventory Management System')

@section('content')

@php
    $currentUser = auth()->user();

    $isAdmin = $currentUser
        && $currentUser->role === 'admin';

    $isSuperAdmin = $currentUser
        && $currentUser->role === 'super_admin';

    $isEmployee = $currentUser
        && $currentUser->role === 'employee';

    /*
     * Admin and Super Admin have full management access.
     * Employees have view + search access only.
     */
    $canManageItemTypes = $isAdmin || $isSuperAdmin;
@endphp

<style>
    /* =========================================================
       CPC INVENTORY ITEM TYPES
       Enterprise Dashboard UI
    ========================================================= */

    .item-type-page {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 4px 0 36px;
    }

    /* =========================================================
       HERO / PAGE HEADER
    ========================================================= */

    .it-hero {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(
                135deg,
                #ffffff 0%,
                #f7faff 52%,
                #edf5ff 100%
            );
        border: 1px solid #dfe7f3;
        border-radius: 20px;
        padding: 28px 30px;
        margin-bottom: 22px;
        box-shadow: 0 8px 28px rgba(30, 55, 90, 0.06);
    }

    .it-hero::before {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        border-radius: 50%;
        right: -80px;
        top: -110px;
        background: rgba(13, 110, 253, 0.055);
        pointer-events: none;
    }

    .it-hero::after {
        content: "";
        position: absolute;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        right: 120px;
        bottom: -100px;
        background: rgba(13, 110, 253, 0.035);
        pointer-events: none;
    }

    .it-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
    }

    .it-hero-left {
        min-width: 0;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .it-hero-icon {
        width: 62px;
        height: 62px;
        min-width: 62px;
        border-radius: 16px;
        background: linear-gradient(135deg, #0d6efd, #3d8bfd);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        box-shadow: 0 10px 25px rgba(13, 110, 253, 0.20);
    }

    .it-hero-context {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        margin-bottom: 7px;
        border-radius: 7px;
        background: rgba(13, 110, 253, 0.07);
        border: 1px solid rgba(13, 110, 253, 0.10);
        color: #0d6efd;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.55px;
        text-transform: uppercase;
    }

    .it-hero-title {
        margin: 0;
        color: #172033;
        font-size: 26px;
        font-weight: 750;
        line-height: 1.2;
        letter-spacing: -0.3px;
    }

    .it-hero-subtitle {
        margin: 6px 0 0;
        max-width: 650px;
        color: #788397;
        font-size: 13px;
        line-height: 1.6;
    }

    .it-hero-right {
        flex-shrink: 0;
    }

    .it-primary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 43px;
        padding: 10px 18px;
        border: 0;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 650;
        box-shadow: 0 7px 18px rgba(13, 110, 253, 0.17);
        transition: all 0.22s ease;
        white-space: nowrap;
    }

    .it-primary-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(13, 110, 253, 0.23);
    }

    /* =========================================================
       READ-ONLY EMPLOYEE ACTIONS
    ========================================================= */

    .it-disabled-action {
        opacity: 0.48;
        cursor: not-allowed !important;
        filter: grayscale(12%);
        transform: none !important;
        box-shadow: none !important;
        user-select: none;
    }

    .it-disabled-action:hover {
        transform: none !important;
        box-shadow: none !important;
    }

    .it-readonly-notice {
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

    .it-readonly-notice i {
        color: #0d6efd;
        font-size: 14px;
    }

    /* =========================================================
       SUCCESS ALERT
    ========================================================= */

    .it-alert {
        border-radius: 11px !important;
        padding: 13px 16px;
        margin-bottom: 22px;
        font-size: 13px;
    }

    /* =========================================================
       STATISTICS
    ========================================================= */

    .it-stat-card {
        position: relative;
        overflow: hidden;
        height: 100%;
        min-height: 145px;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #e3e8f0;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(30, 55, 90, 0.05);
        transition: all 0.24s ease;
    }

    .it-stat-card:hover {
        transform: translateY(-3px);
        border-color: #d5dfed;
        box-shadow: 0 11px 28px rgba(30, 55, 90, 0.09);
    }

    .it-stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(
            90deg,
            #0d6efd,
            #65a5ff
        );
        opacity: 0.9;
    }

    .it-stat-card::after {
        content: "";
        position: absolute;
        width: 125px;
        height: 125px;
        right: -55px;
        bottom: -65px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.035);
        pointer-events: none;
    }

    .it-stat-content {
        position: relative;
        z-index: 2;
    }

    .it-stat-label {
        margin-bottom: 7px;
        color: #7b8799;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: 0.85px;
        text-transform: uppercase;
    }

    .it-stat-number {
        color: #172033;
        font-size: 30px;
        font-weight: 750;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    .it-stat-description {
        margin-top: 7px;
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.45;
    }

    .it-stat-icon {
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
        box-shadow: inset 0 0 0 1px rgba(13, 110, 253, 0.035);
    }

    .it-stat-icon.success {
        background: #ebf8f1;
        color: #16834b;
    }

    .it-stat-icon.warning {
        background: #fff7e8;
        color: #b7791f;
    }

    /* =========================================================
       MAIN TABLE CARD
    ========================================================= */

    .it-table-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e1e7ef;
        border-radius: 18px;
        box-shadow: 0 7px 25px rgba(30, 55, 90, 0.055);
    }

    /* =========================================================
       TABLE HEADER
    ========================================================= */

    .it-table-header {
        padding: 22px 24px;
        border-bottom: 1px solid #e9edf3;
        background: #ffffff;
    }

    .it-table-heading {
        min-width: 0;
    }

    .it-section-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
        color: #0d6efd;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: 0.75px;
        text-transform: uppercase;
    }

    .it-table-title-row {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .it-table-title {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 700;
    }

    .it-record-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border: 1px solid #dfe5ed;
        border-radius: 20px;
        background: #f7f9fc;
        color: #657184;
        font-size: 10px;
        font-weight: 650;
    }

    .it-table-description {
        margin-top: 5px;
        color: #8a94a6;
        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================================================
       TABLE TOOLS
    ========================================================= */

    .it-table-tools {
        flex-shrink: 0;
    }

    .it-search-box {
        position: relative;
        width: 245px;
    }

    .it-search-box > i {
        position: absolute;
        z-index: 2;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        pointer-events: none;
    }

    .it-search-input {
        width: 100%;
        height: 40px;
        padding: 0 36px 0 38px;
        border: 1px solid #dce2ea;
        border-radius: 9px !important;
        background: #f8f9fb;
        color: #344054;
        font-size: 12px;
        transition: all 0.2s ease;
    }

    .it-search-input::placeholder {
        color: #a0a8b5;
    }

    .it-search-input:focus {
        background: #ffffff;
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
    }

    .it-search-clear {
        position: absolute;
        top: 50%;
        right: 7px;
        width: 25px;
        height: 25px;
        transform: translateY(-50%);
        border: 0;
        border-radius: 6px;
        background: transparent;
        color: #98a2b3;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .it-search-clear:hover {
        background: #edf2f8;
        color: #475467;
    }

    .it-status-filter {
        width: 135px;
        min-width: 135px;
        height: 40px;
        border: 1px solid #dce2ea;
        border-radius: 9px;
        background-color: #f8f9fb;
        color: #475467;
        font-size: 12px;
        cursor: pointer;
    }

    .it-status-filter:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
    }

    .it-reset-btn {
        height: 40px;
        padding: 0 11px;
        border: 1px solid #dce2ea;
        border-radius: 9px;
        background: #ffffff;
        color: #667085;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .it-reset-btn:hover {
        background: #f7f9fc;
        border-color: #cbd5e1;
        color: #344054;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .it-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .it-table {
        width: 100%;
        min-width: 950px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .it-table thead th {
        padding: 13px 18px;
        background: #f8f9fb;
        border-bottom: 1px solid #e5e9ef;
        color: #7b8799;
        font-size: 9.5px;
        font-weight: 750;
        letter-spacing: 0.75px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .it-table tbody td {
        padding: 15px 18px;
        border-bottom: 1px solid #eef1f5;
        color: #374151;
        font-size: 12.5px;
        vertical-align: middle;
    }

    .it-table tbody tr {
        transition: background 0.18s ease;
    }

    .it-table tbody tr:hover {
        background: #f9fbff;
    }

    .it-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       NUMBER
    ========================================================= */

    .it-row-number {
        color: #98a2b3;
        font-size: 11px;
        font-weight: 600;
    }

    /* =========================================================
       ITEM TYPE
    ========================================================= */

    .it-item-wrapper {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 200px;
    }

    .it-item-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 11px;
        background: #edf5ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .it-item-name {
        margin-bottom: 2px;
        color: #1f2937;
        font-size: 12.5px;
        font-weight: 700;
        line-height: 1.35;
    }

    .it-item-subtitle {
        color: #98a2b3;
        font-size: 10px;
    }

    /* =========================================================
       CATEGORY
    ========================================================= */

    .it-category {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border: 1px solid #e2e7ee;
        border-radius: 8px;
        background: #f7f9fc;
        color: #465467;
        font-size: 10.5px;
        font-weight: 650;
        white-space: nowrap;
    }

    .it-category i {
        color: #0d6efd;
        font-size: 11px;
    }

    /* =========================================================
       DESCRIPTION
    ========================================================= */

    .it-description {
        max-width: 290px;
        color: #667085;
        font-size: 11.5px;
        line-height: 1.5;
    }

    .it-no-data {
        color: #a0a8b5;
        font-size: 11px;
        font-style: italic;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .it-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .it-status-active {
        background: #eaf8f0;
        color: #16834b;
    }

    .it-status-inactive {
        background: #f1f3f5;
        color: #6c757d;
    }

    .it-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* =========================================================
       STICKY ACTIONS
    ========================================================= */

    .it-actions-column {
        position: sticky;
        right: 0;
        z-index: 4;
        background: #ffffff !important;
        box-shadow: -6px 0 14px rgba(30, 55, 90, 0.04);
    }

    .it-table tbody tr:hover .it-actions-column {
        background: #f9fbff !important;
    }

    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .it-action-group {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .it-action {
        width: 33px;
        height: 33px;
        min-width: 33px;
        padding: 0 !important;
        border-radius: 8px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: all 0.18s ease;
    }

    .it-action:hover {
        transform: translateY(-1px);
    }

    /* =========================================================
       SCROLL HINT
    ========================================================= */

    .it-scroll-hint {
        display: none;
        padding: 8px 18px;
        border-bottom: 1px solid #edf0f5;
        background: #f8f9fb;
        color: #8a94a6;
        font-size: 10.5px;
    }

    /* =========================================================
       NO RESULTS / EMPTY
    ========================================================= */

    .it-empty {
        padding: 68px 25px;
        text-align: center;
    }

    .it-empty-icon {
        width: 78px;
        height: 78px;
        margin: 0 auto 17px;
        border-radius: 50%;
        background: #edf5ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 31px;
    }

    .it-empty-title {
        margin-bottom: 7px;
        color: #273142;
        font-size: 17px;
        font-weight: 700;
    }

    .it-empty-text {
        max-width: 480px;
        margin: 0 auto 22px;
        color: #8a94a6;
        font-size: 12.5px;
        line-height: 1.65;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .it-pagination {
        padding: 17px 24px;
        border-top: 1px solid #e9edf3;
        background: #ffffff;
    }

    .it-pagination-info {
        color: #8a94a6;
        font-size: 11.5px;
    }

    .it-pagination-info strong {
        color: #374151;
        font-weight: 650;
    }

    .it-pagination .pagination {
        margin-bottom: 0;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1199px) {

        .it-search-box {
            width: 220px;
        }

        .it-table {
            min-width: 900px;
        }
    }

    @media (max-width: 991px) {

        .it-hero {
            padding: 23px;
        }

        .it-hero-content {
            align-items: flex-start;
        }

        .it-hero-title {
            font-size: 23px;
        }

        .it-table-header {
            padding: 20px;
        }

        .it-table-tools {
            width: 100%;
        }

        .it-search-box {
            flex: 1 1 220px;
        }

        .it-status-filter {
            flex: 0 0 135px;
        }

        .it-reset-btn {
            flex: 0 0 auto;
        }

        .it-scroll-hint {
            display: block;
        }
    }

    @media (max-width: 767px) {

        .item-type-page {
            padding-bottom: 20px;
        }

        .it-hero {
            padding: 19px;
            border-radius: 15px;
            margin-bottom: 18px;
        }

        .it-hero-content {
            flex-direction: column;
            gap: 17px;
        }

        .it-hero-left {
            width: 100%;
        }

        .it-hero-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 12px;
            font-size: 20px;
        }

        .it-hero-context {
            font-size: 9px;
            padding: 4px 7px;
        }

        .it-hero-title {
            font-size: 20px;
        }

        .it-hero-subtitle {
            font-size: 11.5px;
        }

        .it-hero-right {
            width: 100%;
        }

        .it-primary-btn {
            width: 100%;
        }

        .it-stat-card {
            min-height: 120px;
            padding: 17px;
            border-radius: 14px;
        }

        .it-stat-number {
            font-size: 25px;
        }

        .it-stat-description {
            font-size: 10.5px;
        }

        .it-stat-icon {
            width: 41px;
            height: 41px;
            min-width: 41px;
            border-radius: 11px;
            font-size: 17px;
        }

        .it-table-card {
            border-radius: 14px;
        }

        .it-table-header {
            padding: 17px;
        }

        .it-table-title {
            font-size: 16px;
        }

        .it-table-description {
            font-size: 11px;
        }

        .it-table-tools {
            display: flex;
            flex-direction: column;
            align-items: stretch !important;
            gap: 8px !important;
        }

        .it-search-box,
        .it-status-filter,
        .it-reset-btn {
            width: 100%;
            min-width: 100%;
            flex: none;
        }

        .it-search-input,
        .it-status-filter,
        .it-reset-btn {
            height: 42px;
        }

        .it-pagination {
            padding: 15px;
        }

        .it-pagination-info {
            width: 100%;
            text-align: center;
        }

        .it-pagination > div {
            justify-content: center !important;
        }

        .it-pagination .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }

        .it-empty {
            padding: 52px 20px;
        }

        .it-empty-icon {
            width: 70px;
            height: 70px;
            font-size: 28px;
        }
    }

    @media (max-width: 575px) {

        .it-hero {
            padding: 16px;
        }

        .it-hero-left {
            gap: 11px;
        }

        .it-hero-icon {
            width: 43px;
            height: 43px;
            min-width: 43px;
            font-size: 18px;
        }

        .it-hero-title {
            font-size: 18px;
        }

        .it-hero-subtitle {
            font-size: 10.5px;
        }

        .it-stat-card {
            padding: 15px;
        }

        .it-stat-label {
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        .it-stat-number {
            font-size: 23px;
        }

        .it-stat-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            font-size: 15px;
        }

        .it-alert {
            font-size: 12px;
        }

        .it-pagination-info {
            font-size: 10.5px;
        }
    }
</style>

<div class="item-type-page">

{{-- =========================================================
     PROFESSIONAL HERO HEADER
========================================================== --}}
<div class="it-hero">

    <div class="it-hero-content">

        <div class="it-hero-left">

            <div class="it-hero-icon">
                <i class="bi bi-tags-fill"></i>
            </div>

            <div class="min-w-0">

                <div class="it-hero-context">
                    <i class="bi bi-box-seam"></i>
                    CPC Inventory Administration
                </div>

                <h1 class="it-hero-title">
                    Inventory Item Types
                </h1>

                <p class="it-hero-subtitle">
                    Manage standardized item classifications and categories
                    used throughout the CPC IT inventory system.
                </p>

                @if($isEmployee)

                    <div class="it-readonly-notice">

                        <i class="bi bi-info-circle-fill"></i>

                        <span>
                            Employees have view and search access only.
                            Management actions are disabled.
                        </span>

                    </div>

                @endif

            </div>

        </div>

        <div class="it-hero-right">

            {{-- =================================================
                 ADD ITEM TYPE
                 ADMIN + SUPER ADMIN
            ================================================== --}}
            @if($canManageItemTypes)

                <a
                    href="{{ route('admin.item-types.create') }}"
                    class="btn btn-primary it-primary-btn"
                >

                    <i class="bi bi-plus-lg"></i>

                    Add Item Type

                </a>

            @else

                <button
                    type="button"
                    class="btn btn-primary it-primary-btn it-disabled-action"
                    onclick="employeeReadOnlyMessage()"
                    title="Employees have view and search access only."
                    aria-label="Add Item Type disabled for employees"
                    aria-disabled="true"
                >

                    <i class="bi bi-plus-lg"></i>

                    Add Item Type

                </button>

            @endif

        </div>

    </div>

</div>


{{-- =========================================================
     SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))

    <div class="alert alert-success border-0 shadow-sm
                d-flex align-items-center it-alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        <span>
            {{ session('success') }}
        </span>

        <button
            type="button"
            class="btn-close ms-auto"
            data-bs-dismiss="alert"
        >
        </button>

    </div>

@endif


{{-- =========================================================
     KPI CARDS
========================================================== --}}
<div class="row g-3 g-lg-4 mb-4">

    {{-- TOTAL --}}
    <div class="col-xl-4 col-md-6 col-12">

        <div class="it-stat-card">

            <div class="it-stat-content">

                <div class="d-flex justify-content-between
                            align-items-start">

                    <div>

                        <div class="it-stat-label">
                            Total Item Types
                        </div>

                        <div class="it-stat-number">
                            {{ $itemTypes->total() }}
                        </div>

                        <div class="it-stat-description">
                            Registered inventory classifications
                        </div>

                    </div>

                    <div class="it-stat-icon">

                        <i class="bi bi-tags-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ACTIVE --}}
    <div class="col-xl-4 col-md-6 col-12">

        <div class="it-stat-card">

            <div class="it-stat-content">

                <div class="d-flex justify-content-between
                            align-items-start">

                    <div>

                        <div class="it-stat-label">
                            Active Types
                        </div>

                        <div class="it-stat-number">
                            {{ $itemTypes->where('is_active', true)->count() }}
                        </div>

                        <div class="it-stat-description">
                            Available for inventory registration
                        </div>

                    </div>

                    <div class="it-stat-icon success">

                        <i class="bi bi-check-circle-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- INACTIVE --}}
    <div class="col-xl-4 col-md-6 col-12">

        <div class="it-stat-card">

            <div class="it-stat-content">

                <div class="d-flex justify-content-between
                            align-items-start">

                    <div>

                        <div class="it-stat-label">
                            Inactive Types
                        </div>

                        <div class="it-stat-number">
                            {{ $itemTypes->where('is_active', false)->count() }}
                        </div>

                        <div class="it-stat-description">
                            Deactivated inventory classifications
                        </div>

                    </div>

                    <div class="it-stat-icon warning">

                        <i class="bi bi-archive-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     MAIN ITEM TYPE REGISTER
========================================================== --}}
<div class="it-table-card">

    {{-- TABLE HEADER --}}
    <div class="it-table-header">

        <div class="d-flex justify-content-between
                    align-items-center flex-wrap gap-3">

            <div class="it-table-heading">

                <div class="it-section-label">

                    <i class="bi bi-database"></i>

                    Inventory Configuration

                </div>

                <div class="it-table-title-row">

                    <h5 class="it-table-title">
                        Item Type Register
                    </h5>

                    <span class="it-record-count">

                        <i class="bi bi-collection"></i>

                        {{ $itemTypes->total() }} Records

                    </span>

                </div>

                <div class="it-table-description">

                    Standard classifications available when
                    registering CPC IT assets.

                </div>

            </div>


            {{-- SEARCH / FILTER TOOLS --}}
            <div class="d-flex align-items-center
                        gap-2 flex-wrap it-table-tools">

                {{-- SEARCH --}}
                <div class="it-search-box">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        id="itemTypeSearch"
                        class="form-control it-search-input"
                        placeholder="Search item types..."
                        autocomplete="off"
                    >

                    <button
                        type="button"
                        id="itemTypeSearchClear"
                        class="it-search-clear"
                        aria-label="Clear search"
                        title="Clear search"
                    >

                        <i class="bi bi-x-lg"></i>

                    </button>

                </div>


                {{-- STATUS --}}
                <select
                    id="itemTypeStatus"
                    class="form-select it-status-filter"
                    aria-label="Filter item types by status"
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
                    id="itemTypeReset"
                    class="it-reset-btn"
                    title="Reset filters"
                >

                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                    Reset

                </button>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MOBILE SCROLL HINT
    ====================================================== --}}
    @if($itemTypes->count())

        <div class="it-scroll-hint">

            <i class="bi bi-arrow-left-right me-1"></i>

            Swipe horizontally to view all item type details.

        </div>

    @endif


    {{-- =====================================================
         TABLE
    ====================================================== --}}
    @if($itemTypes->count())

        <div class="it-table-wrapper">

            <table
                class="it-table"
                id="itemTypesTable"
            >

                <thead>

                    <tr>

                        <th style="width:65px;">
                            #
                        </th>

                        <th>
                            Item Type
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Description
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end it-actions-column">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                @foreach($itemTypes as $itemType)

                    <tr
                        data-status="{{ $itemType->is_active ? 'active' : 'inactive' }}"
                    >

                        {{-- NUMBER --}}
                        <td>

                            <span class="it-row-number">

                                {{ $itemTypes->firstItem() + $loop->index }}

                            </span>

                        </td>


                        {{-- ITEM TYPE --}}
                        <td>

                            <div class="it-item-wrapper">

                                <div class="it-item-icon">

                                    <i class="bi bi-box-seam-fill"></i>

                                </div>

                                <div>

                                    <div class="it-item-name">

                                        {{ $itemType->name }}

                                    </div>

                                    <div class="it-item-subtitle">

                                        Inventory classification

                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- CATEGORY --}}
                        <td>

                            @if($itemType->category)

                                <span class="it-category">

                                    <i class="bi bi-folder2"></i>

                                    {{ $itemType->category }}

                                </span>

                            @else

                                <span class="it-no-data">
                                    Not categorized
                                </span>

                            @endif

                        </td>


                        {{-- DESCRIPTION --}}
                        <td>

                            @if($itemType->description)

                                <span
                                    class="it-description d-block text-truncate"
                                    title="{{ $itemType->description }}"
                                >

                                    {{ \Illuminate\Support\Str::limit($itemType->description, 65) }}

                                </span>

                            @else

                                <span class="it-no-data">
                                    No description
                                </span>

                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if($itemType->is_active)

                                <span class="it-status it-status-active">

                                    <span class="it-status-dot"></span>

                                    Active

                                </span>

                            @else

                                <span class="it-status it-status-inactive">

                                    <span class="it-status-dot"></span>

                                    Inactive

                                </span>

                            @endif

                        </td>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}
                        <td class="it-actions-column">

                            <div class="it-action-group">

                                {{-- VIEW
                                     ALL ROLES
                                --}}
                                <a
                                    href="{{ route('admin.item-types.show', $itemType) }}"
                                    class="btn btn-outline-primary it-action"
                                    title="View Item Type"
                                    aria-label="View Item Type"
                                >

                                    <i class="bi bi-eye"></i>

                                </a>


                                {{-- =================================================
                                     EDIT
                                     ADMIN + SUPER ADMIN
                                ================================================== --}}
                                @if($canManageItemTypes)

                                    <a
                                        href="{{ route('admin.item-types.edit', $itemType) }}"
                                        class="btn btn-outline-secondary it-action"
                                        title="Edit Item Type"
                                        aria-label="Edit Item Type"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                @else

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary it-action it-disabled-action"
                                        onclick="employeeReadOnlyMessage()"
                                        title="Employees have view and search access only."
                                        aria-label="Edit Item Type disabled for employees"
                                        aria-disabled="true"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </button>

                                @endif


                                {{-- =================================================
                                     ACTIVE / INACTIVE ACTION
                                ================================================== --}}
                                @if($itemType->is_active)

                                    {{-- DEACTIVATE --}}
                                    @if($canManageItemTypes)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.item-types.destroy', $itemType) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to deactivate this item type?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger it-action"
                                                title="Deactivate Item Type"
                                                aria-label="Deactivate Item Type"
                                            >

                                                <i class="bi bi-archive"></i>

                                            </button>

                                        </form>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-outline-danger it-action it-disabled-action"
                                            onclick="employeeReadOnlyMessage()"
                                            title="Employees have view and search access only."
                                            aria-label="Deactivate Item Type disabled for employees"
                                            aria-disabled="true"
                                        >

                                            <i class="bi bi-archive"></i>

                                        </button>

                                    @endif

                                @else

                                    {{-- REACTIVATE --}}
                                    @if($canManageItemTypes)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.item-types.update', $itemType) }}"
                                            class="d-inline"
                                        >

                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="hidden"
                                                name="name"
                                                value="{{ $itemType->name }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="category"
                                                value="{{ $itemType->category }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="description"
                                                value="{{ $itemType->description }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="is_active"
                                                value="1"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-outline-success it-action"
                                                title="Reactivate Item Type"
                                                aria-label="Reactivate Item Type"
                                            >

                                                <i class="bi bi-arrow-clockwise"></i>

                                            </button>

                                        </form>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-outline-success it-action it-disabled-action"
                                            onclick="employeeReadOnlyMessage()"
                                            title="Employees have view and search access only."
                                            aria-label="Reactivate Item Type disabled for employees"
                                            aria-disabled="true"
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
             NO SEARCH RESULTS
        ================================================== --}}
        <div
            id="noItemTypeResults"
            class="it-empty d-none"
        >

            <div class="it-empty-icon">

                <i class="bi bi-search"></i>

            </div>

            <div class="it-empty-title">
                No Matching Item Types
            </div>

            <p class="it-empty-text">

                No inventory item types match your current
                search or status filter.

            </p>

            <button
                type="button"
                id="noResultsReset"
                class="btn btn-outline-primary btn-sm"
            >

                <i class="bi bi-arrow-counterclockwise me-1"></i>

                Clear Filters

            </button>

        </div>


        {{-- =================================================
             PAGINATION
        ================================================== --}}
        <div class="it-pagination">

            <div class="d-flex justify-content-between
                        align-items-center flex-wrap gap-3">

                <div class="it-pagination-info">

                    Showing

                    <strong>
                        {{ $itemTypes->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $itemTypes->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $itemTypes->total() }}
                    </strong>

                    item types

                </div>

                <div>

                    {{ $itemTypes->links() }}

                </div>

            </div>

        </div>

    @else

        {{-- =================================================
             EMPTY DATABASE STATE
        ================================================== --}}
        <div class="it-empty">

            <div class="it-empty-icon">

                <i class="bi bi-tags-fill"></i>

            </div>

            <div class="it-empty-title">

                No Inventory Item Types Found

            </div>

            <p class="it-empty-text">

                No inventory item types have been registered yet.
                Create the first item type to begin organizing
                CPC inventory assets.

            </p>

            {{-- =================================================
                 ADD FIRST ITEM TYPE
                 ADMIN + SUPER ADMIN
            ================================================== --}}
            @if($canManageItemTypes)

                <a
                    href="{{ route('admin.item-types.create') }}"
                    class="btn btn-primary it-primary-btn"
                >

                    <i class="bi bi-plus-lg"></i>

                    Add First Item Type

                </a>

            @else

                <button
                    type="button"
                    class="btn btn-primary it-primary-btn it-disabled-action"
                    onclick="employeeReadOnlyMessage()"
                    title="Employees have view and search access only."
                    aria-label="Add First Item Type disabled for employees"
                    aria-disabled="true"
                >

                    <i class="bi bi-plus-lg"></i>

                    Add First Item Type

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
function employeeReadOnlyMessage() {
    alert(
        'Employees have view and search access only.'
    );
}
</script>


{{-- =========================================================
     SEARCH + STATUS FILTER
========================================================= --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('itemTypeSearch');

    const searchClear =
        document.getElementById('itemTypeSearchClear');

    const statusFilter =
        document.getElementById('itemTypeStatus');

    const resetButton =
        document.getElementById('itemTypeReset');

    const noResultsReset =
        document.getElementById('noResultsReset');

    const table =
        document.getElementById('itemTypesTable');

    const noResults =
        document.getElementById('noItemTypeResults');


    if (!searchInput || !statusFilter || !table) {
        return;
    }


    function filterItemTypes() {

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
                row.dataset.status;


            const matchesSearch =
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

            noResults.classList.toggle(
                'd-none',
                visibleRows > 0
            );

        }


        if (searchClear) {

            searchClear.style.display =
                searchValue.length > 0
                    ? 'flex'
                    : 'none';

        }

    }


    function resetFilters() {

        searchInput.value = '';

        statusFilter.value = 'all';

        filterItemTypes();

        searchInput.focus();

    }


    searchInput.addEventListener(
        'input',
        filterItemTypes
    );


    statusFilter.addEventListener(
        'change',
        filterItemTypes
    );


    if (searchClear) {

        searchClear.addEventListener(
            'click',
            function () {

                searchInput.value = '';

                filterItemTypes();

                searchInput.focus();

            }
        );

    }


    if (resetButton) {

        resetButton.addEventListener(
            'click',
            resetFilters
        );

    }


    if (noResultsReset) {

        noResultsReset.addEventListener(
            'click',
            resetFilters
        );

    }

});
</script>

@endpush

@endsection