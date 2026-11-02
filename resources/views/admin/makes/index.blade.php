
@extends('layouts.admin')

@section('title', 'Make Management')
@section('page-heading', 'Make Management')

@push('styles')
<style>
    /* =========================================================
       MAKE MANAGEMENT
       Professional CPC Admin UI
       ========================================================= */

    .make-management-page {
        width: 100%;
        max-width: 100%;
    }

    /* ---------------------------------------------------------
       Breadcrumb
       --------------------------------------------------------- */
    .make-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #6b7280;
    }

    .make-breadcrumb a {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .make-breadcrumb a:hover {
        color: #0d6efd;
    }

    .make-breadcrumb .separator {
        display: inline-flex;
        align-items: center;
        color: #cbd5e1;
        font-size: 11px;
    }

    .make-breadcrumb .current {
        color: #1e293b;
        font-weight: 600;
    }

    /* ---------------------------------------------------------
       Page Header
       --------------------------------------------------------- */
    .make-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .make-page-header-content {
        min-width: 0;
    }

    .make-page-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 7px;
        color: #172033;
        font-size: clamp(24px, 3vw, 31px);
        line-height: 1.2;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .make-page-title-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eaf3ff;
        color: #0d6efd;
        font-size: 19px;
    }

    .make-page-description {
        margin: 0;
        max-width: 720px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.65;
    }

    .make-header-actions {
        flex-shrink: 0;
    }

    .make-add-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 17px;
        border: 1px solid #0d6efd;
        border-radius: 8px;
        background: #0d6efd;
        color: #ffffff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(13, 110, 253, 0.16);
        transition:
            background-color 0.2s ease,
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            transform 0.2s ease;
    }

    .make-add-button:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        color: #ffffff;
        box-shadow: 0 5px 12px rgba(13, 110, 253, 0.20);
        transform: translateY(-1px);
    }

    .make-add-button:focus {
        color: #ffffff;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.18);
    }

    /* ---------------------------------------------------------
       Alerts
       --------------------------------------------------------- */
    .make-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 22px;
        padding: 13px 15px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-size: 14px;
        line-height: 1.5;
    }

    .make-alert-icon {
        flex: 0 0 auto;
        margin-top: 1px;
        font-size: 17px;
    }

    .make-alert-content {
        flex: 1;
        min-width: 0;
    }

    .make-alert-success {
        border-color: #b7e4c7;
        background: #f1fbf4;
        color: #176b35;
    }

    .make-alert-danger {
        border-color: #f3c2c7;
        background: #fff5f5;
        color: #9b1c31;
    }

    .make-alert-warning {
        border-color: #ffe08a;
        background: #fffaf0;
        color: #795700;
    }

    .make-alert-close {
        margin-left: auto;
        padding: 0;
        border: 0;
        background: transparent;
        color: inherit;
        opacity: 0.65;
        font-size: 16px;
        cursor: pointer;
    }

    .make-alert-close:hover {
        opacity: 1;
    }

    .make-validation-list {
        margin: 5px 0 0;
        padding-left: 20px;
    }

    /* ---------------------------------------------------------
       Statistics
       --------------------------------------------------------- */
    .make-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 26px;
    }

    .make-stat-card {
        position: relative;
        min-width: 0;
        padding: 19px 20px;
        border: 1px solid #e5eaf0;
        border-radius: 11px;
        background: #ffffff;
        box-shadow: 0 2px 7px rgba(15, 23, 42, 0.045);
        overflow: hidden;
    }

    .make-stat-card::after {
        content: "";
        position: absolute;
        right: 0;
        top: 0;
        width: 4px;
        height: 100%;
        background: #0d6efd;
    }

    .make-stat-card.stat-active::after {
        background: #198754;
    }

    .make-stat-card.stat-inactive::after {
        background: #6c757d;
    }

    .make-stat-card.stat-models::after {
        background: #6f42c1;
    }

    .make-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .make-stat-label {
        margin: 0;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .make-stat-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #edf5ff;
        color: #0d6efd;
        font-size: 17px;
    }

    .stat-active .make-stat-icon {
        background: #edf9f1;
        color: #198754;
    }

    .stat-inactive .make-stat-icon {
        background: #f1f3f5;
        color: #6c757d;
    }

    .stat-models .make-stat-icon {
        background: #f4efff;
        color: #6f42c1;
    }

    .make-stat-value {
        margin-top: 10px;
        color: #172033;
        font-size: 26px;
        line-height: 1;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .make-stat-description {
        margin: 7px 0 0;
        color: #94a3b8;
        font-size: 12px;
    }

    /* ---------------------------------------------------------
       Main Card
       --------------------------------------------------------- */
    .make-table-card {
        border: 1px solid #e3e8ef;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .make-table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 19px 21px;
        border-bottom: 1px solid #e9edf2;
        background: #ffffff;
    }

    .make-table-title-wrapper {
        min-width: 0;
    }

    .make-table-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 700;
    }

    .make-table-title i {
        color: #0d6efd;
        font-size: 17px;
    }

    .make-table-subtitle {
        margin: 5px 0 0;
        color: #94a3b8;
        font-size: 12px;
    }

    .make-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 25px;
        padding: 2px 9px;
        border: 1px solid #dbeafe;
        border-radius: 999px;
        background: #eff6ff;
        color: #0d6efd;
        font-size: 12px;
        font-weight: 700;
    }

    /* ---------------------------------------------------------
       Table
       --------------------------------------------------------- */
    .make-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .make-table {
        width: 100%;
        min-width: 780px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .make-table thead th {
        padding: 13px 18px;
        border-bottom: 1px solid #e3e8ef;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.045em;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .make-table tbody td {
        padding: 15px 18px;
        border-bottom: 1px solid #edf0f3;
        color: #334155;
        font-size: 13px;
        vertical-align: middle;
    }

    .make-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .make-table tbody tr {
        transition: background-color 0.16s ease;
    }

    .make-table tbody tr:hover {
        background: #f9fbfd;
    }

    .make-id {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 600;
    }

    /* ---------------------------------------------------------
       Make Identity
       --------------------------------------------------------- */
    .make-identity {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 190px;
    }

    .make-avatar {
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d7e7fb;
        border-radius: 9px;
        background: #eef6ff;
        color: #0d6efd;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .make-name {
        margin: 0;
        color: #1e293b;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.35;
    }

    .make-created {
        margin-top: 3px;
        color: #94a3b8;
        font-size: 11px;
    }

    /* ---------------------------------------------------------
       Description
       --------------------------------------------------------- */
    .make-description {
        max-width: 280px;
        margin: 0;
        color: #64748b;
        line-height: 1.5;
    }

    .make-description-empty {
        color: #cbd5e1;
        font-style: italic;
    }

    /* ---------------------------------------------------------
       Models
       --------------------------------------------------------- */
    .make-model-count {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #475569;
        font-weight: 600;
        white-space: nowrap;
    }

    .make-model-count i {
        color: #6f42c1;
        font-size: 14px;
    }

    /* ---------------------------------------------------------
       Status
       --------------------------------------------------------- */
    .make-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }

    .make-status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .make-status-active {
        background: #eaf8ef;
        color: #198754;
    }

    .make-status-inactive {
        background: #f1f3f5;
        color: #6c757d;
    }

    /* ---------------------------------------------------------
       Actions
       --------------------------------------------------------- */
    .make-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 7px;
        white-space: nowrap;
    }

    .make-action-button {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dfe5ec;
        border-radius: 7px;
        background: #ffffff;
        text-decoration: none;
        font-size: 14px;
        transition:
            background-color 0.18s ease,
            border-color 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease;
    }

    .make-action-edit {
        color: #0d6efd;
    }

    .make-action-edit:hover {
        border-color: #b9d7ff;
        background: #eef6ff;
        color: #0b5ed7;
        transform: translateY(-1px);
    }

    .make-action-delete {
        color: #dc3545;
    }

    .make-action-delete:hover {
        border-color: #f2b8be;
        background: #fff1f2;
        color: #bb2d3b;
        transform: translateY(-1px);
    }

    .make-action-button:focus {
        outline: none;
        box-shadow: 0 0 0 0.18rem rgba(13, 110, 253, 0.14);
    }

    /* ---------------------------------------------------------
       Empty State
       --------------------------------------------------------- */
    .make-empty-state {
        padding: 58px 24px;
        text-align: center;
    }

    .make-empty-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dce8f7;
        border-radius: 14px;
        background: #f2f7fd;
        color: #0d6efd;
        font-size: 25px;
    }

    .make-empty-title {
        margin: 0 0 7px;
        color: #1e293b;
        font-size: 17px;
        font-weight: 700;
    }

    .make-empty-description {
        max-width: 430px;
        margin: 0 auto 20px;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .make-empty-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 14px;
        border: 1px solid #0d6efd;
        border-radius: 7px;
        background: #0d6efd;
        color: #ffffff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .make-empty-button:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        color: #ffffff;
    }

    /* ---------------------------------------------------------
       Table Footer / Pagination
       --------------------------------------------------------- */
    .make-table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 15px 20px;
        border-top: 1px solid #e9edf2;
        background: #ffffff;
    }

    .make-pagination-info {
        color: #64748b;
        font-size: 12px;
    }

    .make-pagination-info strong {
        color: #334155;
        font-weight: 700;
    }

    .make-pagination-wrapper {
        flex-shrink: 0;
    }

    .make-pagination-wrapper .pagination {
        margin: 0;
    }

    .make-pagination-wrapper .page-link {
        min-width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-color: #dfe5ec;
        color: #475569;
        font-size: 12px;
        box-shadow: none;
    }

    .make-pagination-wrapper .page-item.active .page-link {
        border-color: #0d6efd;
        background: #0d6efd;
        color: #ffffff;
    }

    .make-pagination-wrapper .page-link:hover {
        border-color: #b9d7ff;
        background: #eef6ff;
        color: #0d6efd;
    }

    /* ---------------------------------------------------------
       Delete Modal
       --------------------------------------------------------- */
    .make-delete-modal .modal-content {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
        overflow: hidden;
    }

    .make-delete-modal .modal-header {
        padding: 19px 21px;
        border-bottom: 1px solid #edf0f3;
    }

    .make-delete-modal .modal-title {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #1e293b;
        font-size: 16px;
        font-weight: 700;
    }

    .make-delete-modal .modal-title i {
        color: #dc3545;
    }

    .make-delete-modal .modal-body {
        padding: 22px 21px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .make-delete-name-box {
        margin-top: 13px;
        padding: 11px 13px;
        border: 1px solid #e9edf2;
        border-radius: 8px;
        background: #f8fafc;
        color: #1e293b;
        font-weight: 700;
    }

    .make-delete-warning {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-top: 14px;
        color: #856404;
        font-size: 12px;
    }

    .make-delete-warning i {
        margin-top: 2px;
    }

    .make-delete-modal .modal-footer {
        padding: 14px 21px;
        border-top: 1px solid #edf0f3;
    }

    .make-modal-cancel,
    .make-modal-delete {
        min-height: 38px;
        padding: 8px 14px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
    }

    .make-modal-cancel {
        border: 1px solid #dfe5ec;
        background: #ffffff;
        color: #475569;
    }

    .make-modal-cancel:hover {
        background: #f8fafc;
        color: #334155;
    }

    .make-modal-delete {
        border: 1px solid #dc3545;
        background: #dc3545;
        color: #ffffff;
    }

    .make-modal-delete:hover {
        border-color: #bb2d3b;
        background: #bb2d3b;
        color: #ffffff;
    }

    /* ---------------------------------------------------------
       Responsive - Tablet
       --------------------------------------------------------- */
    @media (max-width: 1100px) {
        .make-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .make-page-header {
            align-items: flex-start;
        }
    }

    /* ---------------------------------------------------------
       Responsive - Mobile
       --------------------------------------------------------- */
    @media (max-width: 767.98px) {
        .make-breadcrumb {
            margin-bottom: 16px;
        }

        .make-page-header {
            flex-direction: column;
            gap: 17px;
            margin-bottom: 22px;
        }

        .make-page-header-content {
            width: 100%;
        }

        .make-page-title {
            font-size: 23px;
        }

        .make-page-title-icon {
            width: 38px;
            height: 38px;
            flex-basis: 38px;
            font-size: 17px;
        }

        .make-header-actions {
            width: 100%;
        }

        .make-add-button {
            width: 100%;
        }

        .make-stats-grid {
            grid-template-columns: 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .make-stat-card {
            padding: 16px;
        }

        .make-table-card-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 17px;
            gap: 10px;
        }

        .make-table-wrapper {
            border-bottom: 0;
        }

        .make-table-footer {
            align-items: flex-start;
            flex-direction: column;
            padding: 14px 16px;
        }

        .make-pagination-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .make-pagination-wrapper .pagination {
            width: max-content;
        }

        .make-empty-state {
            padding: 46px 18px;
        }
    }

    /* ---------------------------------------------------------
       Responsive - Small Mobile
       --------------------------------------------------------- */
    @media (max-width: 420px) {
        .make-page-title {
            font-size: 21px;
        }

        .make-page-description {
            font-size: 13px;
        }

        .make-stat-value {
            font-size: 24px;
        }

        .make-table-card {
            border-radius: 9px;
        }

        .make-delete-modal .modal-footer {
            flex-direction: column-reverse;
        }

        .make-delete-modal .modal-footer > * {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="make-management-page">

    {{-- =====================================================
         BREADCRUMB
         ===================================================== --}}
    <div class="make-breadcrumb">

        <a href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>

        <span class="separator">
            <i class="bi bi-chevron-right"></i>
        </span>

        <span class="current">
            Inventory
        </span>

        <span class="separator">
            <i class="bi bi-chevron-right"></i>
        </span>

        <span class="current">
            Make Management
        </span>

    </div>


    {{-- =====================================================
         PAGE HEADER
         ===================================================== --}}
    <div class="make-page-header">

        <div class="make-page-header-content">

            <h1 class="make-page-title">
                <span class="make-page-title-icon">
                    <i class="bi bi-tags"></i>
                </span>

                <span>Make Management</span>
            </h1>

            <p class="make-page-description">
                Manage the hardware manufacturers used throughout the CPC
                Inventory Management System. Makes are maintained as master
                records and can be associated with multiple device models.
            </p>

        </div>

        <div class="make-header-actions">
            <a href="{{ route('admin.makes.create') }}"
               class="make-add-button">
                <i class="bi bi-plus-lg"></i>
                <span>Add New Make</span>
            </a>
        </div>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
         ===================================================== --}}
    @if(session('success'))
        <div class="make-alert make-alert-success" role="alert">

            <div class="make-alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div class="make-alert-content">
                {{ session('success') }}
            </div>

            <button type="button"
                    class="make-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>

        </div>
    @endif


    {{-- =====================================================
         ERROR MESSAGE
         ===================================================== --}}
    @if(session('error'))
        <div class="make-alert make-alert-danger" role="alert">

            <div class="make-alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="make-alert-content">
                {{ session('error') }}
            </div>

            <button type="button"
                    class="make-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>

        </div>
    @endif


    {{-- =====================================================
         VALIDATION ERRORS
         ===================================================== --}}
    @if($errors->any())
        <div class="make-alert make-alert-danger" role="alert">

            <div class="make-alert-icon">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>

            <div class="make-alert-content">

                <strong>
                    Please correct the following errors:
                </strong>

                <ul class="make-validation-list mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

            <button type="button"
                    class="make-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>

        </div>
    @endif


    {{-- =====================================================
         STATISTICS
         ===================================================== --}}
    <div class="make-stats-grid">

        {{-- Total Makes --}}
        <div class="make-stat-card">

            <div class="make-stat-top">

                <div>
                    <p class="make-stat-label">
                        Total Makes
                    </p>

                    <div class="make-stat-value">
                        {{ number_format($totalMakes) }}
                    </div>

                    <p class="make-stat-description">
                        Registered manufacturers
                    </p>
                </div>

                <span class="make-stat-icon">
                    <i class="bi bi-tags"></i>
                </span>

            </div>

        </div>


        {{-- Active Makes --}}
        <div class="make-stat-card stat-active">

            <div class="make-stat-top">

                <div>
                    <p class="make-stat-label">
                        Active Makes
                    </p>

                    <div class="make-stat-value">
                        {{ number_format($activeMakes) }}
                    </div>

                    <p class="make-stat-description">
                        Available for selection
                    </p>
                </div>

                <span class="make-stat-icon">
                    <i class="bi bi-check-circle"></i>
                </span>

            </div>

        </div>


        {{-- Inactive Makes --}}
        <div class="make-stat-card stat-inactive">

            <div class="make-stat-top">

                <div>
                    <p class="make-stat-label">
                        Inactive Makes
                    </p>

                    <div class="make-stat-value">
                        {{ number_format($inactiveMakes) }}
                    </div>

                    <p class="make-stat-description">
                        Currently unavailable
                    </p>
                </div>

                <span class="make-stat-icon">
                    <i class="bi bi-pause-circle"></i>
                </span>

            </div>

        </div>


        {{-- Total Models --}}
        <div class="make-stat-card stat-models">

            <div class="make-stat-top">

                <div>
                    <p class="make-stat-label">
                        Total Models
                    </p>

                    <div class="make-stat-value">
                        {{ number_format($totalModels) }}
                    </div>

                    <p class="make-stat-description">
                        Associated device models
                    </p>
                </div>

                <span class="make-stat-icon">
                    <i class="bi bi-diagram-3"></i>
                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MAIN MAKE TABLE
         ===================================================== --}}
    <div class="make-table-card">

        {{-- Card Header --}}
        <div class="make-table-card-header">

            <div class="make-table-title-wrapper">

                <h2 class="make-table-title">
                    <i class="bi bi-list-ul"></i>
                    <span>Inventory Makes</span>

                    <span class="make-count-badge">
                        {{ number_format($totalMakes) }}
                    </span>
                </h2>

                <p class="make-table-subtitle">
                    Manufacturer master records used by the inventory system.
                </p>

            </div>

        </div>


        {{-- =================================================
             TABLE
             ================================================= --}}
        @if($makes->count())

            <div class="make-table-wrapper">

                <table class="make-table">

                    <thead>
                        <tr>

                            <th style="width: 70px;">
                                ID
                            </th>

                            <th>
                                Make
                            </th>

                            <th>
                                Description
                            </th>

                            <th style="width: 125px;">
                                Models
                            </th>

                            <th style="width: 125px;">
                                Status
                            </th>

                            <th style="width: 120px; text-align: right;">
                                Actions
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @foreach($makes as $make)

                            @php
                                $makeName = trim((string) $make->name);

                                $makeInitials = collect(
                                    preg_split('/\s+/', $makeName)
                                )
                                ->filter()
                                ->take(2)
                                ->map(function ($word) {
                                    return strtoupper(substr($word, 0, 1));
                                })
                                ->implode('');

                                if ($makeInitials === '') {
                                    $makeInitials = '?';
                                }
                            @endphp

                            <tr>

                                {{-- ID --}}
                                <td>
                                    <span class="make-id">
                                        #{{ $make->id }}
                                    </span>
                                </td>


                                {{-- Make --}}
                                <td>

                                    <div class="make-identity">

                                        <div class="make-avatar"
                                             aria-hidden="true">
                                            {{ $makeInitials }}
                                        </div>

                                        <div>

                                            <p class="make-name">
                                                {{ $make->name }}
                                            </p>

                                            <div class="make-created">
                                                Added
                                                {{ $make->created_at?->format('d M Y') ?? '—' }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Description --}}
                                <td>

                                    @if(filled($make->description))

                                        <p class="make-description">
                                            {{ $make->description }}
                                        </p>

                                    @else

                                        <p class="make-description make-description-empty">
                                            No description provided
                                        </p>

                                    @endif

                                </td>


                                {{-- Models --}}
                                <td>

                                    <span class="make-model-count">

                                        <i class="bi bi-diagram-3"></i>

                                        <span>
                                            {{ number_format($make->item_models_count ?? 0) }}

                                            @if(($make->item_models_count ?? 0) === 1)
                                                model
                                            @else
                                                models
                                            @endif
                                        </span>

                                    </span>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($make->is_active)

                                        <span class="make-status-badge make-status-active">
                                            Active
                                        </span>

                                    @else

                                        <span class="make-status-badge make-status-inactive">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="make-actions">

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.makes.edit', $make) }}"
                                           class="make-action-button make-action-edit"
                                           title="Edit Make"
                                           aria-label="Edit {{ $make->name }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>


                                        {{-- Delete --}}
                                        <button type="button"
                                                class="make-action-button make-action-delete"
                                                title="Delete Make"
                                                aria-label="Delete {{ $make->name }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteMakeModal"
                                                data-make-id="{{ $make->id }}"
                                                data-make-name="{{ $make->name }}"
                                                data-model-count="{{ $make->item_models_count ?? 0 }}">

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 TABLE FOOTER
                 ================================================= --}}
            <div class="make-table-footer">

                <div class="make-pagination-info">

                    Showing
                    <strong>{{ $makes->firstItem() }}</strong>
                    to
                    <strong>{{ $makes->lastItem() }}</strong>
                    of
                    <strong>{{ $makes->total() }}</strong>
                    makes

                </div>


                @if($makes->hasPages())

                    <div class="make-pagination-wrapper">
                        {{ $makes->links() }}
                    </div>

                @endif

            </div>

        @else

            {{-- =================================================
                 EMPTY STATE
                 ================================================= --}}
            <div class="make-empty-state">

                <div class="make-empty-icon">
                    <i class="bi bi-tags"></i>
                </div>

                <h3 class="make-empty-title">
                    No Makes Found
                </h3>

                <p class="make-empty-description">
                    No hardware manufacturers have been registered yet.
                    Add the first Make to begin building your inventory
                    manufacturer and model master data.
                </p>

                <a href="{{ route('admin.makes.create') }}"
                   class="make-empty-button">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add First Make</span>
                </a>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     DELETE MAKE MODAL
     ========================================================= --}}
<div class="modal fade make-delete-modal"
     id="deleteMakeModal"
     tabindex="-1"
     aria-labelledby="deleteMakeModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title"
                    id="deleteMakeModalLabel">

                    <i class="bi bi-trash3"></i>

                    <span>Delete Make</span>

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>


            <div class="modal-body">

                <p class="mb-0">
                    Are you sure you want to permanently delete this Make?
                    This action cannot be undone.
                </p>

                <div class="make-delete-name-box"
                     id="deleteMakeName">
                    —
                </div>

                <div class="make-delete-warning"
                     id="deleteMakeWarning">

                    <i class="bi bi-info-circle-fill"></i>

                    <span>
                        If models are associated with this Make, the system
                        will prevent deletion.
                    </span>

                </div>

            </div>


            <div class="modal-footer">

                <button type="button"
                        class="btn make-modal-cancel"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <form method="POST"
                      id="deleteMakeForm"
                      action="">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn make-modal-delete">
                        <i class="bi bi-trash3 me-1"></i>
                        Delete Make
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const deleteMakeModal = document.getElementById('deleteMakeModal');
        const deleteMakeForm = document.getElementById('deleteMakeForm');
        const deleteMakeName = document.getElementById('deleteMakeName');
        const deleteMakeWarning = document.getElementById('deleteMakeWarning');

        if (!deleteMakeModal) {
            return;
        }

        deleteMakeModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const makeId = button.getAttribute('data-make-id');
            const makeName = button.getAttribute('data-make-name');
            const modelCount = parseInt(
                button.getAttribute('data-model-count') || '0',
                10
            );

            if (deleteMakeName) {
                deleteMakeName.textContent = makeName || 'Selected Make';
            }

            if (deleteMakeForm && makeId) {
                deleteMakeForm.action =
                    "{{ url('/admin/makes') }}/" + makeId;
            }

            if (deleteMakeWarning) {

                if (modelCount > 0) {

                    deleteMakeWarning.innerHTML =
                        '<i class="bi bi-exclamation-triangle-fill"></i>' +
                        '<span>' +
                        'This Make currently has ' +
                        '<strong>' +
                        modelCount +
                        '</strong> associated ' +
                        (modelCount === 1 ? 'model' : 'models') +
                        '. Deletion will be prevented until those models are removed.' +
                        '</span>';

                } else {

                    deleteMakeWarning.innerHTML =
                        '<i class="bi bi-info-circle-fill"></i>' +
                        '<span>' +
                        'This Make has no associated models and can be deleted.' +
                        '</span>';

                }

            }

        });

    });
</script>
@endpush

@endsection

