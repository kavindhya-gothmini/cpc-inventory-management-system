@extends('layouts.admin')

@section('title', 'Repair Management | CPC Inventory Management System')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Role & Permission Control
    |--------------------------------------------------------------------------
    | Admin       -> Full management access
    | Super Admin -> Full management access
    | Employee    -> View + Search only
    */

    $currentUser = auth()->user();

    $isAdmin = $currentUser
        && $currentUser->role === 'admin';

    $isSuperAdmin = $currentUser
        && $currentUser->role === 'super_admin';

    $isEmployee = $currentUser
        && $currentUser->role === 'employee';

    $canManageRepairs = $isAdmin || $isSuperAdmin;
@endphp

<style>
    /* =========================================================
       CPC REPAIR MANAGEMENT
    ========================================================= */

    .repair-page {
        min-height: calc(100vh - 72px);
        padding: 24px;
        background:
            linear-gradient(
                180deg,
                #f8fafc 0%,
                #f5f7fb 100%
            );
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .repair-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .repair-header-left {
        min-width: 0;
    }

    .repair-breadcrumb {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 8px;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }

    .repair-breadcrumb i {
        color: #2563eb;
    }

    .repair-header h1 {
        margin: 0;
        color: #172033;
        font-size: 28px;
        line-height: 1.2;
        font-weight: 750;
        letter-spacing: -0.02em;
    }

    .repair-header p {
        margin: 7px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* =========================================================
       BUTTONS
    ========================================================= */

    .repair-page .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 9px 16px;
        border-radius: 9px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .repair-page .btn:hover {
        transform: translateY(-1px);
    }

    .repair-page .btn-primary {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 5px 12px rgba(37, 99, 235, 0.16);
    }

    .repair-page .btn-primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff;
    }

    .repair-page .btn-secondary {
        background: #ffffff;
        color: #374151;
        border-color: #d1d5db;
    }

    .repair-page .btn-secondary:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #1f2937;
    }

    .repair-page .btn-small {
        min-height: 34px;
        padding: 6px 10px;
        font-size: 12px;
        border-radius: 7px;
    }

    /* =========================================================
       EMPLOYEE READ-ONLY UI
    ========================================================= */

    .repair-disabled-action {
        opacity: 0.48;
        cursor: not-allowed !important;
        filter: grayscale(12%);
        transform: none !important;
        box-shadow: none !important;
        user-select: none;
    }

    .repair-disabled-action:hover,
    .repair-disabled-action:focus {
        opacity: 0.48;
        transform: none !important;
        box-shadow: none !important;
    }

    .repair-readonly-notice {
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

    .repair-readonly-notice i {
        color: #2563eb;
        font-size: 14px;
    }

    /* =========================================================
       STATISTICS
    ========================================================= */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
        min-width: 0;
        padding: 18px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.045);
    }

    .stat-card::after {
        content: "";
        position: absolute;
        right: -30px;
        bottom: -35px;
        width: 95px;
        height: 95px;
        border-radius: 50%;
        background: rgba(37, 99, 235, 0.045);
    }

    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .stat-icon {
        position: relative;
        z-index: 1;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        font-size: 17px;
    }

    .stat-total .stat-icon {
        background: #eff6ff;
        color: #2563eb;
    }

    .stat-pending .stat-icon {
        background: #fff7ed;
        color: #ea580c;
    }

    .stat-sent .stat-icon {
        background: #eef2ff;
        color: #4f46e5;
    }

    .stat-under .stat-icon {
        background: #fef2f2;
        color: #dc2626;
    }

    .stat-completed .stat-icon {
        background: #ecfdf5;
        color: #059669;
    }

    .stat-returned .stat-icon {
        background: #f0f9ff;
        color: #0284c7;
    }

    .stat-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .stat-value {
        position: relative;
        z-index: 1;
        margin-top: 11px;
        color: #172033;
        font-size: 27px;
        line-height: 1;
        font-weight: 750;
    }

    .stat-description {
        position: relative;
        z-index: 1;
        margin-top: 9px;
        color: #94a3b8;
        font-size: 11px;
    }

    .stat-total {
        border-top: 3px solid #2563eb;
    }

    .stat-pending {
        border-top: 3px solid #f59e0b;
    }

    .stat-sent {
        border-top: 3px solid #6366f1;
    }

    .stat-under {
        border-top: 3px solid #ef4444;
    }

    .stat-completed {
        border-top: 3px solid #10b981;
    }

    .stat-returned {
        border-top: 3px solid #0ea5e9;
    }

    /* =========================================================
       FILTER CARD
    ========================================================= */

    .filter-card {
        margin-bottom: 22px;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.04);
    }

    .filter-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 16px;
    }

    .filter-heading-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 14px;
    }

    .filter-title {
        margin: 0;
        color: #1f2937;
        font-size: 15px;
        font-weight: 700;
    }

    .filter-subtitle {
        margin: 2px 0 0;
        color: #94a3b8;
        font-size: 11px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns:
            minmax(220px, 2fr)
            minmax(150px, 1fr)
            minmax(145px, 1fr)
            minmax(145px, 1fr)
            auto;
        gap: 12px;
        align-items: end;
    }

    .form-group {
        min-width: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 6px;
        color: #374151;
        font-size: 12px;
        font-weight: 650;
    }

    .repair-page .form-control,
    .repair-page .form-select {
        width: 100%;
        min-height: 42px;
        padding: 9px 11px;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #111827;
        font-size: 13px;
        outline: none;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .repair-page .form-control:focus,
    .repair-page .form-select:focus {
        border-color: #2563eb;
        box-shadow:
            0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }

    .search-wrapper .form-control {
        padding-left: 36px;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
    }

    /* =========================================================
       TABLE CARD
    ========================================================= */

    .table-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.045);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 18px 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-header-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
    }

    .table-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 16px;
        font-weight: 700;
    }

    .table-header span {
        display: block;
        margin-top: 3px;
        color: #94a3b8;
        font-size: 11px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .repair-table {
        width: 100%;
        min-width: 1200px;
        border-collapse: collapse;
    }

    .repair-table th {
        padding: 13px 14px;
        background: #f8fafc;
        color: #64748b;
        border-bottom: 1px solid #e5e7eb;
        font-size: 10px;
        font-weight: 750;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 0.045em;
        white-space: nowrap;
    }

    .repair-table td {
        padding: 14px;
        color: #374151;
        border-bottom: 1px solid #f0f1f3;
        font-size: 13px;
        vertical-align: middle;
    }

    .repair-table tbody tr {
        transition: background 0.15s ease;
    }

    .repair-table tbody tr:hover {
        background: #fafcff;
    }

    .repair-table tbody tr:last-child td {
        border-bottom: 0;
    }

    /* =========================================================
       TABLE CONTENT
    ========================================================= */

    .reference-cell strong {
        color: #1f2937;
        font-size: 13px;
        font-weight: 700;
    }

    .asset-number {
        color: #1d4ed8;
        font-weight: 700;
    }

    .secondary-text {
        display: block;
        margin-top: 3px;
        color: #94a3b8;
        font-size: 11px;
    }

    .item-type {
        color: #64748b;
        font-size: 11px;
    }

    .employee-name {
        color: #374151;
        font-weight: 600;
    }

    .date-cell {
        white-space: nowrap;
        color: #475569;
    }

    .technician-cell {
        max-width: 150px;
    }

    .technician-name {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .spare-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 27px;
        padding: 0 8px;
        border-radius: 7px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
    }

    .cost-cell {
        color: #1f2937;
        font-weight: 650;
        white-space: nowrap;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-sent {
        background: #eef2ff;
        color: #4338ca;
    }

    .status-under {
        background: #fef2f2;
        color: #b91c1c;
    }

    .status-completed {
        background: #ecfdf5;
        color: #047857;
    }

    .status-returned {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .status-cancelled {
        background: #f3f4f6;
        color: #4b5563;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-button {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease,
            transform 0.2s ease;
    }

    .action-button:hover {
        transform: translateY(-1px);
    }

    .action-view {
        background: #eff6ff;
        border-color: #dbeafe;
        color: #1d4ed8;
    }

    .action-view:hover {
        background: #dbeafe;
        color: #1e40af;
    }

    .action-edit {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #475569;
    }

    .action-edit:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .action-delete {
        background: #fef2f2;
        border-color: #fee2e2;
        color: #dc2626;
    }

    .action-delete:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 70px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 62px;
        height: 62px;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 25px;
    }

    .empty-state h3 {
        margin: 0;
        color: #374151;
        font-size: 17px;
        font-weight: 700;
    }

    .empty-state p {
        max-width: 450px;
        margin: 7px auto 20px;
        color: #94a3b8;
        font-size: 13px;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .pagination-wrapper {
        padding: 18px 20px;
        border-top: 1px solid #e5e7eb;
    }

    .pagination-wrapper nav {
        margin: 0;
    }

    .pagination-wrapper .pagination {
        margin-bottom: 0;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1400px) {
        .stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .filter-grid {
            grid-template-columns:
                2fr
                1fr
                1fr
                1fr;
        }

        .filter-actions {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 992px) {
        .repair-page {
            padding: 20px;
        }

        .repair-header {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .filter-actions {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 768px) {
        .repair-page {
            padding: 16px;
        }

        .repair-header h1 {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .stat-card {
            padding: 15px;
        }

        .stat-value {
            font-size: 24px;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            grid-column: auto;
            width: 100%;
        }

        .filter-actions .btn {
            flex: 1;
        }

        .table-header {
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .header-actions {
            flex-direction: column;
        }

        .header-actions .btn {
            width: 100%;
        }

        .table-header {
            padding: 15px;
        }
    }
</style>

<div class="repair-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="repair-header">

        <div class="repair-header-left">

            <div class="repair-breadcrumb">
                <i class="bi bi-box-seam"></i>
                <span>Inventory</span>
                <i class="bi bi-chevron-right"></i>
                <span>Repair Management</span>
            </div>

            <h1>
                Repair Management
            </h1>

            <p>
                Manage inventory repairs, repair status,
                technicians, spare parts and repair history.
            </p>

            {{-- Employee Read-Only Notice --}}
            @if($isEmployee)

                <div class="repair-readonly-notice">

                    <i class="bi bi-info-circle"></i>

                    <span>
                        Employees have view and search access only.
                        Management actions are disabled.
                    </span>

                </div>

            @endif

        </div>


        <div class="header-actions">

            {{-- =================================================
                 SPARE PARTS
            ================================================== --}}

            @if($canManageRepairs)

                <a
                    href="{{ route('admin.inventory.repairs.spare-parts.index') }}"
                    class="btn btn-secondary"
                >
                    <i class="bi bi-boxes"></i>
                    Spare Parts
                </a>

            @else

                <button
                    type="button"
                    class="btn btn-secondary repair-disabled-action"
                    onclick="employeeReadOnlyMessage()"
                    title="Employees have view and search access only"
                    aria-disabled="true"
                >
                    <i class="bi bi-boxes"></i>
                    Spare Parts
                </button>

            @endif


            {{-- =================================================
                 NEW REPAIR
            ================================================== --}}

            @if($canManageRepairs)

                <a
                    href="{{ route('admin.inventory.repairs.create') }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-circle"></i>
                    New Repair
                </a>

            @else

                <button
                    type="button"
                    class="btn btn-primary repair-disabled-action"
                    onclick="employeeReadOnlyMessage()"
                    title="Employees have view and search access only"
                    aria-disabled="true"
                >
                    <i class="bi bi-plus-circle"></i>
                    New Repair
                </button>

            @endif

        </div>

    </div>


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    <div class="stats-grid">

        {{-- Total --}}
        <div class="stat-card stat-total">

            <div class="stat-top">

                <div class="stat-label">
                    Total Repairs
                </div>

                <div class="stat-icon">
                    <i class="bi bi-wrench-adjustable"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ number_format($totalRepairs) }}
            </div>

            <div class="stat-description">
                All repair records
            </div>

        </div>


        {{-- Pending --}}
        <div class="stat-card stat-pending">

            <div class="stat-top">

                <div class="stat-label">
                    Pending
                </div>

                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ number_format($pendingRepairs) }}
            </div>

            <div class="stat-description">
                Awaiting repair action
            </div>

        </div>


        {{-- Sent --}}
        <div class="stat-card stat-sent">

            <div class="stat-top">

                <div class="stat-label">
                    Sent for Repair
                </div>

                <div class="stat-icon">
                    <i class="bi bi-send"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ number_format($sentForRepair) }}
            </div>

            <div class="stat-description">
                Sent to repair provider
            </div>

        </div>


        {{-- Under Repair --}}
        <div class="stat-card stat-under">

            <div class="stat-top">

                <div class="stat-label">
                    Under Repair
                </div>

                <div class="stat-icon">
                    <i class="bi bi-tools"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ number_format($underRepair) }}
            </div>

            <div class="stat-description">
                Currently being repaired
            </div>

        </div>


        {{-- Completed --}}
        <div class="stat-card stat-completed">

            <div class="stat-top">

                <div class="stat-label">
                    Completed
                </div>

                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ number_format($completedRepairs) }}
            </div>

            <div class="stat-description">
                Repair work completed
            </div>

        </div>


        {{-- Returned --}}
        <div class="stat-card stat-returned">

            <div class="stat-top">

                <div class="stat-label">
                    Returned
                </div>

                <div class="stat-icon">
                    <i class="bi bi-arrow-return-left"></i>
                </div>

            </div>

            <div class="stat-value">
                {{ number_format($returnedRepairs) }}
            </div>

            <div class="stat-description">
                Items returned to inventory
            </div>

        </div>

    </div>


    {{-- =========================================================
         SEARCH & FILTER
    ========================================================== --}}

    <div class="filter-card">

        <div class="filter-heading">

            <div class="filter-heading-icon">
                <i class="bi bi-funnel"></i>
            </div>

            <div>

                <div class="filter-title">
                    Search & Filter Repairs
                </div>

                <div class="filter-subtitle">
                    Search by repair, asset, employee or technician
                </div>

            </div>

        </div>


        <form
            method="GET"
            action="{{ route('admin.inventory.repairs.index') }}"
        >

            <div class="filter-grid">

                {{-- Search --}}
                <div class="form-group">

                    <label
                        for="search"
                        class="form-label"
                    >
                        Search
                    </label>

                    <div class="search-wrapper">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Asset No, Serial No, EPF, Employee, Reference..."
                        >

                    </div>

                </div>


                {{-- Status --}}
                <div class="form-group">

                    <label
                        for="status"
                        class="form-label"
                    >
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

                        <option
                            value="pending"
                            {{ request('status') === 'pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="sent_for_repair"
                            {{ request('status') === 'sent_for_repair' ? 'selected' : '' }}
                        >
                            Sent for Repair
                        </option>

                        <option
                            value="under_repair"
                            {{ request('status') === 'under_repair' ? 'selected' : '' }}
                        >
                            Under Repair
                        </option>

                        <option
                            value="completed"
                            {{ request('status') === 'completed' ? 'selected' : '' }}
                        >
                            Completed
                        </option>

                        <option
                            value="returned"
                            {{ request('status') === 'returned' ? 'selected' : '' }}
                        >
                            Returned
                        </option>

                        <option
                            value="cancelled"
                            {{ request('status') === 'cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                    </select>

                </div>


                {{-- Date From --}}
                <div class="form-group">

                    <label
                        for="date_from"
                        class="form-label"
                    >
                        Date From
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
                <div class="form-group">

                    <label
                        for="date_to"
                        class="form-label"
                    >
                        Date To
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        id="date_to"
                        class="form-control"
                        value="{{ request('date_to') }}"
                    >

                </div>


                {{-- Filter Buttons --}}
                <div class="filter-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-search"></i>
                        Search
                    </button>

                    <a
                        href="{{ route('admin.inventory.repairs.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- =========================================================
         REPAIR TABLE
    ========================================================== --}}

    <div class="table-card">

        <div class="table-header">

            <div class="table-header-left">

                <div class="table-header-icon">
                    <i class="bi bi-list-check"></i>
                </div>

                <div>

                    <h2>
                        Repair Records
                    </h2>

                    <span>
                        {{ number_format($repairs->total()) }}
                        record(s) found
                    </span>

                </div>

            </div>

        </div>


        <div class="table-wrapper">

            @if ($repairs->count())

                <table class="repair-table">

                    <thead>

                        <tr>
                            <th>Repair Reference</th>
                            <th>Asset / Item</th>
                            <th>Employee</th>
                            <th>Date Sent</th>
                            <th>Technician</th>
                            <th>Spare Parts</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($repairs as $repair)

                            <tr>

                                {{-- =================================================
                                     REFERENCE
                                ================================================== --}}

                                <td class="reference-cell">

                                    <strong>
                                        {{ $repair->repair_reference }}
                                    </strong>

                                    <span class="secondary-text">
                                        Repair ID #{{ $repair->id }}
                                    </span>

                                </td>


                                {{-- =================================================
                                     ASSET / ITEM
                                ================================================== --}}

                                <td>

                                    @if ($repair->item)

                                        <span class="asset-number">
                                            {{ $repair->item->asset_number }}
                                        </span>

                                        @if ($repair->item->serial_number)

                                            <span class="secondary-text">
                                                S/N:
                                                {{ $repair->item->serial_number }}
                                            </span>

                                        @endif

                                        @if ($repair->item->itemType)

                                            <span class="secondary-text item-type">
                                                {{ $repair->item->itemType->name }}
                                            </span>

                                        @endif

                                    @else

                                        <span class="secondary-text">
                                            Item unavailable
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     EMPLOYEE
                                ================================================== --}}

                                <td>

                                    @if ($repair->employee)

                                        <span class="employee-name">
                                            {{ $repair->employee->name }}
                                        </span>

                                        @if ($repair->employee->epf_no)

                                            <span class="secondary-text">
                                                EPF:
                                                {{ $repair->employee->epf_no }}
                                            </span>

                                        @endif

                                    @else

                                        <span class="secondary-text">
                                            Not assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     DATE SENT
                                ================================================== --}}

                                <td class="date-cell">

                                    @if ($repair->date_sent)

                                        {{ $repair->date_sent->format('d M Y') }}

                                    @else

                                        <span class="secondary-text">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     TECHNICIAN
                                ================================================== --}}

                                <td class="technician-cell">

                                    @if ($repair->technician)

                                        <span
                                            class="technician-name"
                                            title="{{ $repair->technician }}"
                                        >
                                            {{ $repair->technician }}
                                        </span>

                                    @else

                                        <span class="secondary-text">
                                            Not assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     SPARE PART COUNT
                                ================================================== --}}

                                <td>

                                    <span
                                        class="spare-count"
                                        title="Spare parts used"
                                    >
                                        <i class="bi bi-box-seam me-1"></i>

                                        {{ $repair->spareParts->count() }}
                                    </span>

                                </td>


                                {{-- =================================================
                                     STATUS
                                ================================================== --}}

                                <td>

                                    @php

                                        $statusClass = match (
                                            $repair->status
                                        ) {

                                            'pending'
                                                => 'status-pending',

                                            'sent_for_repair'
                                                => 'status-sent',

                                            'under_repair'
                                                => 'status-under',

                                            'completed'
                                                => 'status-completed',

                                            'returned'
                                                => 'status-returned',

                                            'cancelled'
                                                => 'status-cancelled',

                                            default
                                                => 'status-cancelled',
                                        };


                                        $statusLabel = match (
                                            $repair->status
                                        ) {

                                            'pending'
                                                => 'Pending',

                                            'sent_for_repair'
                                                => 'Sent for Repair',

                                            'under_repair'
                                                => 'Under Repair',

                                            'completed'
                                                => 'Completed',

                                            'returned'
                                                => 'Returned',

                                            'cancelled'
                                                => 'Cancelled',

                                            default
                                                => ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $repair->status
                                                    )
                                                ),
                                        };

                                    @endphp


                                    <span
                                        class="status-badge {{ $statusClass }}"
                                    >

                                        <span class="status-dot"></span>

                                        {{ $statusLabel }}

                                    </span>

                                </td>


                                {{-- =================================================
                                     COST
                                ================================================== --}}

                                <td class="cost-cell">

                                    {{ number_format(
                                        (float) $repair->repair_cost,
                                        2
                                    ) }}

                                </td>


                                {{-- =================================================
                                     ACTIONS
                                ================================================== --}}

                                <td>

                                    <div class="action-buttons">

                                        {{-- View --}}
                                        <a
                                            href="{{ route(
                                                'admin.inventory.repairs.show',
                                                $repair
                                            ) }}"
                                            class="action-button action-view"
                                            title="View Repair"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>


                                        {{-- =================================================
                                             EDIT
                                             Admin + Super Admin
                                        ================================================== --}}

                                        @if($canManageRepairs)

                                            <a
                                                href="{{ route(
                                                    'admin.inventory.repairs.edit',
                                                    $repair
                                                ) }}"
                                                class="action-button action-edit"
                                                title="Edit Repair"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        @else

                                            <button
                                                type="button"
                                                class="action-button action-edit repair-disabled-action"
                                                onclick="employeeReadOnlyMessage()"
                                                title="Employees have view and search access only"
                                                aria-disabled="true"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                        @endif


                                        {{-- =================================================
                                             DELETE
                                             Admin + Super Admin
                                        ================================================== --}}

                                        @if($canManageRepairs)

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.inventory.repairs.destroy',
                                                    $repair
                                                ) }}"
                                                onsubmit="return confirm(
                                                    'Are you sure you want to delete this repair record?'
                                                );"
                                                style="display:inline;"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="action-button action-delete"
                                                    title="Delete Repair"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        @else

                                            <button
                                                type="button"
                                                class="action-button action-delete repair-disabled-action"
                                                onclick="employeeReadOnlyMessage()"
                                                title="Employees have view and search access only"
                                                aria-disabled="true"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}

                <div class="empty-state">

                    <div class="empty-state-icon">
                        <i class="bi bi-wrench-adjustable"></i>
                    </div>

                    <h3>
                        No Repair Records Found
                    </h3>

                    <p>
                        There are currently no repair records
                        matching your search or filter criteria.
                    </p>


                    {{-- =================================================
                         CREATE NEW REPAIR
                         Admin + Super Admin
                    ================================================== --}}

                    @if($canManageRepairs)

                        <a
                            href="{{ route('admin.inventory.repairs.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-plus-circle"></i>
                            Create New Repair
                        </a>

                    @else

                        <button
                            type="button"
                            class="btn btn-primary repair-disabled-action"
                            onclick="employeeReadOnlyMessage()"
                            title="Employees have view and search access only"
                            aria-disabled="true"
                        >
                            <i class="bi bi-plus-circle"></i>
                            Create New Repair
                        </button>

                    @endif

                </div>

            @endif

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}

        @if ($repairs->hasPages())

            <div class="pagination-wrapper">

                {{ $repairs->links() }}

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