@extends('layouts.admin')

@section('title', 'Spare Part Suppliers | CPC Inventory Management System')

@section('content')

<style>
    /* =========================================================
       CPC SPARE PART SUPPLIERS
       Enterprise Inventory Dashboard UI
    ========================================================= */

    .supplier-page {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 4px 0 36px;
    }

    /* =========================================================
       HERO HEADER
    ========================================================= */

    .supplier-hero {
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

    .supplier-hero::before {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        right: -90px;
        top: -120px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.055);
        pointer-events: none;
    }

    .supplier-hero::after {
        content: "";
        position: absolute;
        width: 170px;
        height: 170px;
        right: 110px;
        bottom: -110px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.035);
        pointer-events: none;
    }

    .supplier-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
    }

    .supplier-hero-left {
        min-width: 0;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .supplier-hero-icon {
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

    .supplier-hero-context {
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

    .supplier-hero-title {
        margin: 0;
        color: #172033;
        font-size: 26px;
        font-weight: 750;
        line-height: 1.2;
        letter-spacing: -0.3px;
    }

    .supplier-hero-subtitle {
        margin: 6px 0 0;
        max-width: 680px;
        color: #788397;
        font-size: 13px;
        line-height: 1.6;
    }

    .supplier-hero-right {
        flex-shrink: 0;
    }

    .supplier-primary-btn {
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

    .supplier-primary-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(13, 110, 253, 0.23);
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .supplier-breadcrumb {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 12px;
        color: #8a94a6;
        font-size: 11px;
    }

    .supplier-breadcrumb a {
        color: #667085;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .supplier-breadcrumb a:hover {
        color: #0d6efd;
    }

    .supplier-breadcrumb i {
        font-size: 9px;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .supplier-alert {
        border-radius: 11px !important;
        padding: 13px 16px;
        margin-bottom: 22px;
        font-size: 13px;
    }

    /* =========================================================
       KPI CARDS
    ========================================================= */

    .supplier-stat-card {
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

    .supplier-stat-card:hover {
        transform: translateY(-3px);
        border-color: #d5dfed;
        box-shadow: 0 11px 28px rgba(30, 55, 90, 0.09);
    }

    .supplier-stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #0d6efd, #65a5ff);
    }

    .supplier-stat-card::after {
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

    .supplier-stat-content {
        position: relative;
        z-index: 2;
    }

    .supplier-stat-label {
        margin-bottom: 7px;
        color: #7b8799;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: 0.85px;
        text-transform: uppercase;
    }

    .supplier-stat-number {
        color: #172033;
        font-size: 30px;
        font-weight: 750;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    .supplier-stat-description {
        margin-top: 7px;
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.45;
    }

    .supplier-stat-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .supplier-stat-icon.primary {
        background: #edf5ff;
        color: #0d6efd;
    }

    .supplier-stat-icon.success {
        background: #ebf8f1;
        color: #16834b;
    }

    .supplier-stat-icon.secondary {
        background: #f1f3f5;
        color: #6c757d;
    }

    .supplier-stat-icon.info {
        background: #eaf9fc;
        color: #0aa2c0;
    }

    /* =========================================================
       FILTER CARD
    ========================================================= */

    .supplier-filter-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e1e7ef;
        border-radius: 17px;
        box-shadow: 0 6px 22px rgba(30, 55, 90, 0.05);
        margin-bottom: 22px;
    }

    .supplier-filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 18px 22px;
        border-bottom: 1px solid #e9edf3;
        background: #fbfcfe;
    }

    .supplier-filter-heading {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .supplier-filter-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 10px;
        background: #edf5ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .supplier-filter-title {
        margin: 0;
        color: #172033;
        font-size: 14px;
        font-weight: 700;
    }

    .supplier-filter-description {
        margin-top: 3px;
        color: #8a94a6;
        font-size: 11px;
    }

    .supplier-filter-body {
        padding: 20px 22px;
    }

    .supplier-filter-label {
        display: block;
        margin-bottom: 7px;
        color: #475467;
        font-size: 11px;
        font-weight: 650;
    }

    .supplier-search-box {
        position: relative;
    }

    .supplier-search-box > i {
        position: absolute;
        z-index: 2;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        pointer-events: none;
    }

    .supplier-filter-body .form-control,
    .supplier-filter-body .form-select {
        height: 42px;
        border: 1px solid #dce2ea;
        border-radius: 9px;
        background: #f8f9fb;
        color: #344054;
        font-size: 12px;
        transition: all 0.2s ease;
    }

    .supplier-search-box .form-control {
        padding-left: 38px;
    }

    .supplier-filter-body .form-control:focus,
    .supplier-filter-body .form-select:focus {
        background: #ffffff;
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
    }

    .supplier-filter-actions {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .supplier-search-btn {
        height: 42px;
        padding: 0 16px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
    }

    .supplier-reset-btn {
        width: 42px;
        height: 42px;
        padding: 0;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* =========================================================
       SUPPLIER REGISTER
    ========================================================= */

    .supplier-register {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e1e7ef;
        border-radius: 18px;
        box-shadow: 0 7px 25px rgba(30, 55, 90, 0.055);
    }

    .supplier-register-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        padding: 21px 24px;
        border-bottom: 1px solid #e9edf3;
        background: #ffffff;
    }

    .supplier-register-label {
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

    .supplier-register-title-row {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .supplier-register-title {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 700;
    }

    .supplier-record-count {
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

    .supplier-register-description {
        margin-top: 5px;
        color: #8a94a6;
        font-size: 11px;
    }

    .supplier-register-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border: 1px solid #e4e9ef;
        border-radius: 20px;
        background: #f8fafc;
        color: #667085;
        font-size: 10px;
        font-weight: 650;
        white-space: nowrap;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .supplier-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .supplier-table {
        width: 100%;
        min-width: 1080px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .supplier-table thead th {
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

    .supplier-table tbody td {
        padding: 15px 18px;
        border-bottom: 1px solid #eef1f5;
        color: #374151;
        font-size: 12.5px;
        vertical-align: middle;
    }

    .supplier-table tbody tr {
        transition: background 0.18s ease;
    }

    .supplier-table tbody tr:hover {
        background: #f9fbff;
    }

    .supplier-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       SUPPLIER IDENTITY
    ========================================================= */

    .supplier-identity {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 210px;
    }

    .supplier-avatar {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 11px;
        background: #edf5ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .supplier-name {
        margin-bottom: 3px;
        color: #1f2937;
        font-size: 12.5px;
        font-weight: 700;
        line-height: 1.35;
    }

    .supplier-code {
        color: #98a2b3;
        font-size: 10px;
    }

    /* =========================================================
       CONTACT
    ========================================================= */

    .supplier-contact-name {
        color: #344054;
        font-size: 12px;
        font-weight: 600;
    }

    .supplier-email {
        margin-top: 4px;
        color: #8a94a6;
        font-size: 10.5px;
        white-space: nowrap;
    }

    .supplier-email i {
        color: #98a2b3;
    }

    /* =========================================================
       PHONE / LOCATION
    ========================================================= */

    .supplier-phone,
    .supplier-location {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #475467;
        font-size: 11.5px;
        white-space: nowrap;
    }

    .supplier-phone i,
    .supplier-location i {
        color: #98a2b3;
    }

    /* =========================================================
       TYPE
    ========================================================= */

    .supplier-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border: 1px solid #e2e7ee;
        border-radius: 8px;
        background: #f7f9fc;
        color: #465467;
        font-size: 10.5px;
        font-weight: 650;
        white-space: nowrap;
    }

    .supplier-type i {
        color: #0d6efd;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .supplier-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .supplier-status.active {
        background: #eaf8f0;
        color: #16834b;
    }

    .supplier-status.inactive {
        background: #f1f3f5;
        color: #6c757d;
    }

    .supplier-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .supplier-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
        white-space: nowrap;
    }

    .supplier-action {
        width: 33px;
        height: 33px;
        min-width: 33px;
        padding: 0;
        border: 1px solid #dfe5ec;
        border-radius: 8px;
        background: #ffffff;
        color: #667085;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.18s ease;
    }

    .supplier-action:hover {
        transform: translateY(-1px);
    }

    .supplier-action.view:hover {
        background: #edf5ff;
        border-color: #b9d3ff;
        color: #0d6efd;
    }

    .supplier-action.edit:hover {
        background: #f4edff;
        border-color: #d9c7f3;
        color: #6f42c1;
    }

    .supplier-action.warning:hover {
        background: #fff7e8;
        border-color: #ffe0a3;
        color: #b7791f;
    }

    .supplier-action.success:hover {
        background: #eaf8f0;
        border-color: #b7e4c7;
        color: #16834b;
    }

    .supplier-action.more:hover,
    .supplier-action.more[aria-expanded="true"] {
        background: #edf5ff;
        border-color: #b9d3ff;
        color: #0d6efd;
    }

    .supplier-actions .dropdown-menu {
        min-width: 190px;
        padding: 7px;
        margin-top: 7px;
        border: 1px solid #e5e9ef !important;
        border-radius: 10px;
    }

    .supplier-actions .dropdown-item {
        padding: 9px 10px;
        border-radius: 7px;
        color: #475467;
        font-size: 11.5px;
    }

    .supplier-actions .dropdown-item:hover {
        background: #f7f9fc;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .supplier-empty {
        padding: 70px 25px;
        text-align: center;
    }

    .supplier-empty-icon {
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

    .supplier-empty-title {
        margin-bottom: 7px;
        color: #273142;
        font-size: 17px;
        font-weight: 700;
    }

    .supplier-empty-text {
        max-width: 480px;
        margin: 0 auto 22px;
        color: #8a94a6;
        font-size: 12.5px;
        line-height: 1.65;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .supplier-pagination {
        padding: 17px 24px;
        border-top: 1px solid #e9edf3;
    }

    .supplier-pagination .pagination {
        margin-bottom: 0;
        justify-content: flex-end;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1199px) {

        .supplier-table {
            min-width: 1000px;
        }

    }

    @media (max-width: 991px) {

        .supplier-hero {
            padding: 23px;
        }

        .supplier-hero-title {
            font-size: 23px;
        }

        .supplier-filter-body {
            padding: 18px;
        }

        .supplier-register-header {
            padding: 19px 20px;
        }

    }

    @media (max-width: 767px) {

        .supplier-page {
            padding-bottom: 20px;
        }

        .supplier-hero {
            padding: 19px;
            border-radius: 15px;
            margin-bottom: 18px;
        }

        .supplier-hero-content {
            flex-direction: column;
            align-items: stretch;
            gap: 17px;
        }

        .supplier-hero-left {
            width: 100%;
            gap: 12px;
        }

        .supplier-hero-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 12px;
            font-size: 20px;
        }

        .supplier-hero-context {
            font-size: 9px;
        }

        .supplier-hero-title {
            font-size: 20px;
        }

        .supplier-hero-subtitle {
            font-size: 11.5px;
        }

        .supplier-hero-right {
            width: 100%;
        }

        .supplier-primary-btn {
            width: 100%;
        }

        .supplier-stat-card {
            min-height: 120px;
            padding: 17px;
            border-radius: 14px;
        }

        .supplier-stat-number {
            font-size: 25px;
        }

        .supplier-stat-icon {
            width: 41px;
            height: 41px;
            min-width: 41px;
            border-radius: 11px;
            font-size: 17px;
        }

        .supplier-filter-header {
            padding: 16px;
        }

        .supplier-filter-body {
            padding: 16px;
        }

        .supplier-filter-actions {
            width: 100%;
        }

        .supplier-search-btn {
            flex: 1;
        }

        .supplier-register {
            border-radius: 14px;
        }

        .supplier-register-header {
            padding: 17px;
            flex-direction: column;
            align-items: flex-start;
        }

        .supplier-register-status {
            align-self: flex-start;
        }

        .supplier-table {
            min-width: 980px;
        }

        .supplier-pagination {
            padding: 15px;
        }

    }

    @media (max-width: 575px) {

        .supplier-hero {
            padding: 16px;
        }

        .supplier-hero-icon {
            width: 43px;
            height: 43px;
            min-width: 43px;
            font-size: 18px;
        }

        .supplier-hero-title {
            font-size: 18px;
        }

        .supplier-hero-subtitle {
            font-size: 10.5px;
        }

        .supplier-stat-card {
            padding: 15px;
        }

        .supplier-stat-label {
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        .supplier-stat-number {
            font-size: 23px;
        }

        .supplier-stat-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            font-size: 15px;
        }

        .supplier-alert {
            font-size: 12px;
        }

    }
</style>


<div class="supplier-page">

    {{-- =========================================================
         HERO HEADER
    ========================================================== --}}
    <div class="supplier-hero">

        <div class="supplier-hero-content">

            <div class="supplier-hero-left">

                <div class="supplier-hero-icon">
                    <i class="bi bi-truck"></i>
                </div>

                <div class="min-w-0">

                    <div class="supplier-hero-context">

                        <i class="bi bi-box-seam"></i>

                        Inventory & Repair Administration

                    </div>

                    <h1 class="supplier-hero-title">
                        Spare Part Suppliers
                    </h1>

                    <p class="supplier-hero-subtitle">
                        Manage supplier records, contacts and supplier
                        information used for CPC spare parts and repair
                        operations.
                    </p>

                </div>

            </div>

            <div class="supplier-hero-right">

                <a href="{{ route('admin.inventory.repairs.suppliers.create') }}"
                   class="btn btn-primary supplier-primary-btn">

                    <i class="bi bi-plus-lg"></i>

                    Add Supplier

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         BREADCRUMB
    ========================================================== --}}
    <div class="supplier-breadcrumb">

        <a href="{{ route('admin.inventory.repairs.spare-parts.index') }}">

            <i class="bi bi-box-seam me-1"></i>

            Spare Parts

        </a>

        <i class="bi bi-chevron-right"></i>

        <span>
            Suppliers
        </span>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm
                    d-flex align-items-center supplier-alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            <span>
                {{ session('success') }}
            </span>

            <button type="button"
                    class="btn-close ms-auto"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm
                    d-flex align-items-center supplier-alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <span>
                {{ session('error') }}
            </span>

            <button type="button"
                    class="btn-close ms-auto"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         KPI CARDS
    ========================================================== --}}
    <div class="row g-3 g-lg-4 mb-4">

        {{-- TOTAL --}}
        <div class="col-xl-3 col-md-6 col-12">

            <div class="supplier-stat-card">

                <div class="supplier-stat-content">

                    <div class="d-flex justify-content-between
                                align-items-start">

                        <div>

                            <div class="supplier-stat-label">
                                Total Suppliers
                            </div>

                            <div class="supplier-stat-number">
                                {{ \App\Models\SparePartSupplier::count() }}
                            </div>

                            <div class="supplier-stat-description">
                                Registered supplier records
                            </div>

                        </div>

                        <div class="supplier-stat-icon primary">

                            <i class="bi bi-truck"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ACTIVE --}}
        <div class="col-xl-3 col-md-6 col-12">

            <div class="supplier-stat-card">

                <div class="supplier-stat-content">

                    <div class="d-flex justify-content-between
                                align-items-start">

                        <div>

                            <div class="supplier-stat-label">
                                Active Suppliers
                            </div>

                            <div class="supplier-stat-number">
                                {{ \App\Models\SparePartSupplier::where('is_active', true)->count() }}
                            </div>

                            <div class="supplier-stat-description">
                                Currently available suppliers
                            </div>

                        </div>

                        <div class="supplier-stat-icon success">

                            <i class="bi bi-check-circle-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- INACTIVE --}}
        <div class="col-xl-3 col-md-6 col-12">

            <div class="supplier-stat-card">

                <div class="supplier-stat-content">

                    <div class="d-flex justify-content-between
                                align-items-start">

                        <div>

                            <div class="supplier-stat-label">
                                Inactive Suppliers
                            </div>

                            <div class="supplier-stat-number">
                                {{ \App\Models\SparePartSupplier::where('is_active', false)->count() }}
                            </div>

                            <div class="supplier-stat-description">
                                Deactivated supplier records
                            </div>

                        </div>

                        <div class="supplier-stat-icon secondary">

                            <i class="bi bi-pause-circle-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- DOCUMENTS --}}
        <div class="col-xl-3 col-md-6 col-12">

            <div class="supplier-stat-card">

                <div class="supplier-stat-content">

                    <div class="d-flex justify-content-between
                                align-items-start">

                        <div>

                            <div class="supplier-stat-label">
                                Supplier Documents
                            </div>

                            <div class="supplier-stat-number">
                                {{ \App\Models\SparePartDocument::count() }}
                            </div>

                            <div class="supplier-stat-description">
                                Supporting supplier documents
                            </div>

                        </div>

                        <div class="supplier-stat-icon info">

                            <i class="bi bi-file-earmark-text-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SEARCH & FILTERS
    ========================================================== --}}
    <div class="supplier-filter-card">

        <div class="supplier-filter-header">

            <div class="supplier-filter-heading">

                <div class="supplier-filter-icon">

                    <i class="bi bi-funnel-fill"></i>

                </div>

                <div>

                    <h6 class="supplier-filter-title">
                        Search & Filters
                    </h6>

                    <div class="supplier-filter-description">
                        Find and filter supplier records
                    </div>

                </div>

            </div>

        </div>


        <div class="supplier-filter-body">

            <form method="GET"
                  action="{{ route('admin.inventory.repairs.suppliers.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- SEARCH --}}
                    <div class="col-lg-5">

                        <label class="supplier-filter-label">
                            Search Supplier
                        </label>

                        <div class="supplier-search-box">

                            <i class="bi bi-search"></i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Code, name, contact, phone, email..."
                            >

                        </div>

                    </div>


                    {{-- TYPE --}}
                    <div class="col-lg-3">

                        <label class="supplier-filter-label">
                            Supplier Type
                        </label>

                        <select name="supplier_type"
                                class="form-select">

                            <option value="">
                                All Types
                            </option>

                            @foreach($supplierTypes as $type)

                                <option value="{{ $type }}"
                                    @selected(request('supplier_type') === $type)>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-lg-2">

                        <label class="supplier-filter-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                @selected(request('status') === 'active')>

                                Active

                            </option>

                            <option value="inactive"
                                @selected(request('status') === 'inactive')>

                                Inactive

                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-lg-2">

                        <div class="supplier-filter-actions">

                            <button
                                type="submit"
                                class="btn btn-primary supplier-search-btn"
                            >

                                <i class="bi bi-search me-1"></i>

                                Search

                            </button>

                            <a
                                href="{{ route('admin.inventory.repairs.suppliers.index') }}"
                                class="btn btn-light border supplier-reset-btn"
                                title="Clear filters"
                            >

                                <i class="bi bi-arrow-counterclockwise"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         SUPPLIER REGISTER
    ========================================================== --}}
    <div class="supplier-register">

        {{-- REGISTER HEADER --}}
        <div class="supplier-register-header">

            <div>

                <div class="supplier-register-label">

                    <i class="bi bi-database"></i>

                    Supplier Administration

                </div>

                <div class="supplier-register-title-row">

                    <h5 class="supplier-register-title">
                        Supplier Register
                    </h5>

                    <span class="supplier-record-count">

                        <i class="bi bi-collection"></i>

                        {{ $suppliers->total() }} Records

                    </span>

                </div>

                <div class="supplier-register-description">

                    Registered suppliers available for CPC spare
                    part procurement and repair operations.

                </div>

            </div>


            <div class="supplier-register-status">

                <span class="status-dot"></span>

                Supplier Records

            </div>

        </div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}
        <div class="supplier-table-wrapper">

            @if($suppliers->count())

                <table class="table supplier-table align-middle">

                    <thead>

                        <tr>

                            <th class="ps-4">
                                Supplier
                            </th>

                            <th>
                                Contact
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Supplier Type
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end pe-4">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach($suppliers as $supplier)

                        <tr>

                            {{-- SUPPLIER --}}
                            <td class="ps-4">

                                <div class="supplier-identity">

                                    <div class="supplier-avatar">

                                        <i class="bi bi-building"></i>

                                    </div>

                                    <div>

                                        <div class="supplier-name">

                                            {{ $supplier->supplier_name }}

                                        </div>

                                        <div class="supplier-code">

                                            {{ $supplier->supplier_code }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- CONTACT --}}
                            <td>

                                @if($supplier->contact_person)

                                    <div class="supplier-contact-name">

                                        {{ $supplier->contact_person }}

                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif


                                @if($supplier->email)

                                    <div class="supplier-email">

                                        <i class="bi bi-envelope me-1"></i>

                                        {{ $supplier->email }}

                                    </div>

                                @endif

                            </td>


                            {{-- PHONE --}}
                            <td>

                                @if($supplier->phone)

                                    <span class="supplier-phone">

                                        <i class="bi bi-telephone"></i>

                                        {{ $supplier->phone }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- TYPE --}}
                            <td>

                                @if($supplier->supplier_type)

                                    <span class="supplier-type">

                                        <i class="bi bi-tag-fill"></i>

                                        {{ $supplier->supplier_type }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- LOCATION --}}
                            <td>

                                @if($supplier->city)

                                    <span class="supplier-location">

                                        <i class="bi bi-geo-alt-fill"></i>

                                        {{ $supplier->city }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if($supplier->is_active)

                                    <span class="supplier-status active">

                                        <span class="supplier-status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="supplier-status inactive">

                                        <span class="supplier-status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}
                            <td class="text-end pe-4">

                                <div class="supplier-actions">

                                    {{-- VIEW --}}
                                    <a
                                        href="{{ route('admin.inventory.repairs.suppliers.show', $supplier) }}"
                                        class="supplier-action view"
                                        title="View Supplier"
                                        data-bs-toggle="tooltip"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('admin.inventory.repairs.suppliers.edit', $supplier) }}"
                                        class="supplier-action edit"
                                        title="Edit Supplier"
                                        data-bs-toggle="tooltip"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                    </a>


                                    {{-- ACTIVATE / DEACTIVATE --}}
                                    @if($supplier->is_active)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.inventory.repairs.suppliers.deactivate', $supplier) }}"
                                            class="d-inline"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="supplier-action warning"
                                                title="Deactivate Supplier"
                                                data-bs-toggle="tooltip"
                                            >

                                                <i class="bi bi-pause-circle"></i>

                                            </button>

                                        </form>

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('admin.inventory.repairs.suppliers.activate', $supplier) }}"
                                            class="d-inline"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="supplier-action success"
                                                title="Activate Supplier"
                                                data-bs-toggle="tooltip"
                                            >

                                                <i class="bi bi-check-circle"></i>

                                            </button>

                                        </form>

                                    @endif


                                    {{-- MORE --}}
                                    <div class="dropdown d-inline-block">

                                        <button
                                            type="button"
                                            class="supplier-action more"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            title="More Actions"
                                        >

                                            <i class="bi bi-three-dots-vertical"></i>

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route('admin.inventory.repairs.suppliers.show', $supplier) }}"
                                                >

                                                    <i class="bi bi-eye me-2"></i>

                                                    View Details

                                                </a>

                                            </li>


                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route('admin.inventory.repairs.suppliers.edit', $supplier) }}"
                                                >

                                                    <i class="bi bi-pencil me-2"></i>

                                                    Edit Supplier

                                                </a>

                                            </li>


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.inventory.repairs.suppliers.destroy', $supplier) }}"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this supplier?');"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-danger"
                                                    >

                                                        <i class="bi bi-trash3 me-2"></i>

                                                        Delete Supplier

                                                    </button>

                                                </form>

                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            @else

                {{-- EMPTY STATE --}}
                <div class="supplier-empty">

                    <div class="supplier-empty-icon">

                        <i class="bi bi-truck"></i>

                    </div>

                    <div class="supplier-empty-title">

                        No Suppliers Found

                    </div>

                    <p class="supplier-empty-text">

                        No supplier records match your current
                        search or filter criteria.

                    </p>

                    <a
                        href="{{ route('admin.inventory.repairs.suppliers.create') }}"
                        class="btn btn-primary supplier-primary-btn"
                    >

                        <i class="bi bi-plus-lg"></i>

                        Add First Supplier

                    </a>

                </div>

            @endif

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if($suppliers->hasPages())

            <div class="supplier-pagination">

                {{ $suppliers->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     BOOTSTRAP TOOLTIPS
========================================================= --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tooltipTriggerList =
        document.querySelectorAll('[data-bs-toggle="tooltip"]');

    tooltipTriggerList.forEach(function (tooltipTriggerEl) {

        if (typeof bootstrap !== 'undefined' &&
            bootstrap.Tooltip) {

            new bootstrap.Tooltip(tooltipTriggerEl);

        }

    });

});
</script>

@endpush

@endsection