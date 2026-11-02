@extends('layouts.admin')

@section('title', $functionUnit->name . ' | Function Unit')

@section('content')

@php
    $currentUser = auth()->user();
    $isSuperAdmin = $currentUser && $currentUser->role === 'super_admin';
@endphp

<style>
/* =========================================================
   FUNCTION UNIT DASHBOARD
========================================================= */

.function-unit-page {
    min-height: calc(100vh - 72px);
    background:
        radial-gradient(circle at top right, rgba(13, 110, 253, 0.08), transparent 30%),
        #f5f7fb;
    padding: 28px;
}

/* =========================================================
   PAGE HEADER
========================================================= */

.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 25px;
}

.page-header-left {
    min-width: 0;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    font-size: 13px;
    color: #6b7280;
}

.breadcrumb a {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 600;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.page-title {
    margin: 0;
    font-size: 30px;
    font-weight: 750;
    letter-spacing: -0.5px;
    color: #172033;
}

.page-subtitle {
    margin: 7px 0 0;
    color: #6b7280;
    font-size: 14px;
}

.page-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* =========================================================
   BUTTONS
========================================================= */

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 16px;
    border-radius: 9px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 650;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-primary {
    background: #0d6efd;
    color: #ffffff;
    box-shadow: 0 5px 14px rgba(13, 110, 253, 0.20);
}

.btn-primary:hover {
    background: #0b5ed7;
    color: #ffffff;
    transform: translateY(-1px);
}

.btn-light {
    background: #ffffff;
    color: #374151;
    border-color: #dfe3e8;
}

.btn-light:hover {
    background: #f8fafc;
    color: #172033;
}

/* =========================================================
   READ ONLY NOTICE
========================================================= */

.readonly-notice {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 15px 17px;
    margin-bottom: 22px;
    border: 1px solid #fde68a;
    border-left: 4px solid #f59e0b;
    border-radius: 10px;
    background: #fffbeb;
    color: #78350f;
}

.readonly-icon {
    width: 30px;
    height: 30px;
    flex: 0 0 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f59e0b;
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
}

.readonly-content {
    min-width: 0;
}

.readonly-title {
    margin: 0 0 4px;
    color: #78350f;
    font-size: 13px;
    font-weight: 700;
}

.readonly-text {
    margin: 0;
    color: #92400e;
    font-size: 12px;
    line-height: 1.55;
}

/* =========================================================
   HERO CARD
========================================================= */

.unit-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(
        135deg,
        #0d6efd 0%,
        #1456b8 55%,
        #163b7a 100%
    );
    border-radius: 16px;
    padding: 28px;
    color: #ffffff;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
    margin-bottom: 22px;
}

.unit-hero::after {
    content: "";
    position: absolute;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    right: -70px;
    top: -90px;
    background: rgba(255, 255, 255, 0.08);
}

.unit-hero-content {
    position: relative;
    z-index: 2;
}

.unit-icon {
    width: 54px;
    height: 54px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.14);
    font-size: 24px;
    margin-bottom: 16px;
}

.unit-hero h2 {
    margin: 0 0 7px;
    font-size: 25px;
    font-weight: 750;
}

.unit-hero p {
    margin: 0;
    color: rgba(255, 255, 255, 0.82);
    font-size: 14px;
}

.hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}

.hero-tag {
    display: inline-flex;
    align-items: center;
    padding: 7px 12px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
    font-size: 12px;
    font-weight: 650;
}

/* =========================================================
   SUMMARY CARDS
========================================================= */

.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 22px;
}

.summary-card {
    background: #ffffff;
    border: 1px solid #e7ebf0;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 5px 18px rgba(15, 23, 42, 0.045);
}

.summary-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.summary-label {
    color: #6b7280;
    font-size: 13px;
    font-weight: 650;
}

.summary-icon {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #eef5ff;
    color: #0d6efd;
    font-size: 17px;
}

.summary-value {
    margin-top: 14px;
    font-size: 27px;
    line-height: 1;
    font-weight: 750;
    color: #172033;
}

.summary-description {
    margin-top: 8px;
    color: #8a94a6;
    font-size: 12px;
}

/* =========================================================
   MAIN GRID
========================================================= */

.content-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(300px, 0.8fr);
    gap: 22px;
}

.card {
    background: #ffffff;
    border: 1px solid #e7ebf0;
    border-radius: 14px;
    box-shadow: 0 5px 18px rgba(15, 23, 42, 0.045);
    overflow: hidden;
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 19px 21px;
    border-bottom: 1px solid #edf0f3;
}

.card-title {
    margin: 0;
    color: #172033;
    font-size: 16px;
    font-weight: 720;
}

.card-subtitle {
    margin: 4px 0 0;
    color: #8a94a6;
    font-size: 12px;
}

.card-body {
    padding: 21px;
}

/* =========================================================
   DETAILS
========================================================= */

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}

.detail-item {
    border: 1px solid #edf0f3;
    background: #fafbfd;
    border-radius: 11px;
    padding: 15px;
}

.detail-item.full {
    grid-column: 1 / -1;
}

.detail-label {
    margin-bottom: 7px;
    color: #8a94a6;
    font-size: 11px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: #263247;
    font-size: 14px;
    font-weight: 650;
    line-height: 1.5;
    word-break: break-word;
}

/* =========================================================
   STATUS
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

.status-active {
    background: #e8f7ee;
    color: #16834d;
}

.status-inactive {
    background: #fdebec;
    color: #c0392b;
}

/* =========================================================
   RELATIONSHIP CARDS
========================================================= */

.relationship-card {
    margin-bottom: 22px;
}

.relationship-card:last-child {
    margin-bottom: 0;
}

.relationship-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 27px;
    padding: 0 9px;
    border-radius: 15px;
    background: #eef5ff;
    color: #0d6efd;
    font-size: 12px;
    font-weight: 750;
}

.relationship-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.relationship-icon {
    flex-shrink: 0;
    width: 46px;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #f1f5f9;
    color: #475569;
    font-size: 19px;
}

.relationship-text {
    min-width: 0;
}

.relationship-text strong {
    display: block;
    color: #273449;
    font-size: 14px;
    margin-bottom: 4px;
}

.relationship-text span {
    display: block;
    color: #8a94a6;
    font-size: 12px;
    line-height: 1.5;
}

.empty-state {
    padding: 25px 15px;
    text-align: center;
    border: 1px dashed #dce2e8;
    border-radius: 11px;
    background: #fafbfd;
}

.empty-icon {
    font-size: 25px;
    margin-bottom: 9px;
    color: #a0a9b8;
}

.empty-state strong {
    display: block;
    color: #475569;
    font-size: 13px;
    margin-bottom: 4px;
}

.empty-state span {
    color: #9aa3b1;
    font-size: 12px;
}

/* =========================================================
   QUICK ACTIONS
========================================================= */

.quick-actions {
    display: grid;
    gap: 10px;
}

.quick-action {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 14px;
    border: 1px solid #e7ebf0;
    border-radius: 10px;
    color: #334155;
    text-decoration: none;
    background: #ffffff;
    transition: all 0.2s ease;
}

.quick-action:hover {
    border-color: #bdd4f8;
    background: #f8fbff;
    transform: translateX(2px);
}

.quick-action-left {
    display: flex;
    align-items: center;
    gap: 11px;
}

.quick-action-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #eef5ff;
    color: #0d6efd;
}

.quick-action-text {
    font-size: 13px;
    font-weight: 650;
}

.quick-arrow {
    color: #9aa3b1;
}

/* =========================================================
   DISABLED QUICK ACTION
========================================================= */

.quick-action-disabled {
    cursor: default;
    opacity: 0.58;
    background: #f8fafc;
}

.quick-action-disabled:hover {
    border-color: #e7ebf0;
    background: #f8fafc;
    transform: none;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1000px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 800px) {
    .summary-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
    }

    .page-actions {
        width: 100%;
    }
}

@media (max-width: 650px) {
    .function-unit-page {
        padding: 18px;
    }

    .page-title {
        font-size: 24px;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .detail-item.full {
        grid-column: auto;
    }

    .unit-hero {
        padding: 22px;
    }

    .card-header,
    .card-body {
        padding: 17px;
    }

    .readonly-notice {
        align-items: flex-start;
    }
}
</style>

<div class="function-unit-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="page-header">

        <div class="page-header-left">

            <div class="breadcrumb">

                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>

                <span>/</span>

                <a href="{{ route('admin.function-units.index') }}">
                    Function Units
                </a>

                <span>/</span>

                <span>
                    {{ $functionUnit->name }}
                </span>

            </div>

            <h1 class="page-title">
                Function Unit
            </h1>

            <p class="page-subtitle">
                View function unit information and associated
                employees and IT assets.
            </p>

        </div>

        <div class="page-actions">

            <a
                href="{{ route('admin.function-units.index') }}"
                class="btn btn-light"
            >
                ← Back
            </a>

            {{-- Only Super Admin can edit --}}
            @if($isSuperAdmin)

                <a
                    href="{{ route('admin.function-units.edit', $functionUnit->id) }}"
                    class="btn btn-primary"
                >
                    ✎ Edit Function Unit
                </a>

            @endif

        </div>

    </div>


    {{-- =====================================================
         READ ONLY NOTICE
    ====================================================== --}}

    @if(!$isSuperAdmin)

        <div class="readonly-notice">

            <div class="readonly-icon">
                !
            </div>

            <div class="readonly-content">

                <p class="readonly-title">
                    View-Only Access
                </p>

                <p class="readonly-text">
                    Only Super Admin can add, edit, activate, or deactivate
                    Function Units. Admin and Employee users have view and
                    search access only.
                </p>

            </div>

        </div>

    @endif


    {{-- =====================================================
         HERO
    ====================================================== --}}

    <div class="unit-hero">

        <div class="unit-hero-content">

            <div class="unit-icon">
                🏢
            </div>

            <h2>
                {{ $functionUnit->name }}
            </h2>

            <p>
                CPC IT Asset Management System
                · Function Unit Profile
            </p>

            <div class="hero-meta">

                @if ($functionUnit->code)

                    <span class="hero-tag">
                        Code: {{ $functionUnit->code }}
                    </span>

                @endif

                @if ($functionUnit->is_active)

                    <span class="hero-tag">
                        ● Active
                    </span>

                @else

                    <span class="hero-tag">
                        ● Inactive
                    </span>

                @endif

                @if ($functionUnit->department)

                    <span class="hero-tag">
                        Department:
                        {{ $functionUnit->department->name }}
                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-top">

                <span class="summary-label">
                    Employees
                </span>

                <div class="summary-icon">
                    👥
                </div>

            </div>

            <div class="summary-value">
                {{ $functionUnit->employees->count() }}
            </div>

            <div class="summary-description">
                Employees associated with this function unit
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <span class="summary-label">
                    IT Assets
                </span>

                <div class="summary-icon">
                    💻
                </div>

            </div>

            <div class="summary-value">
                {{ $functionUnit->items->count() }}
            </div>

            <div class="summary-description">
                Inventory items associated with this function unit
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <span class="summary-label">
                    Current Status
                </span>

                <div class="summary-icon">
                    ●
                </div>

            </div>

            <div class="summary-value" style="font-size:20px;">

                @if ($functionUnit->is_active)
                    Active
                @else
                    Inactive
                @endif

            </div>

            <div class="summary-description">
                Function unit operational status
            </div>

        </div>

    </div>


    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}

    <div class="content-grid">

        {{-- =================================================
             LEFT COLUMN
        ================================================== --}}

        <div>

            {{-- FUNCTION UNIT INFORMATION --}}

            <div class="card relationship-card">

                <div class="card-header">

                    <div>

                        <h2 class="card-title">
                            Function Unit Information
                        </h2>

                        <p class="card-subtitle">
                            Official function unit details
                        </p>

                    </div>

                    @if ($functionUnit->is_active)

                        <span class="status-badge status-active">
                            <span class="status-dot"></span>
                            Active
                        </span>

                    @else

                        <span class="status-badge status-inactive">
                            <span class="status-dot"></span>
                            Inactive
                        </span>

                    @endif

                </div>

                <div class="card-body">

                    <div class="details-grid">

                        <div class="detail-item">

                            <div class="detail-label">
                                Function Unit Name
                            </div>

                            <div class="detail-value">
                                {{ $functionUnit->name }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Function Unit Code
                            </div>

                            <div class="detail-value">
                                {{ $functionUnit->code ?: 'Not Assigned' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Department
                            </div>

                            <div class="detail-value">

                                @if ($functionUnit->department)

                                    {{ $functionUnit->department->name }}

                                    @if ($functionUnit->department->code)
                                        ({{ $functionUnit->department->code }})
                                    @endif

                                @else

                                    Not Assigned

                                @endif

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Status
                            </div>

                            <div class="detail-value">

                                @if ($functionUnit->is_active)

                                    <span class="status-badge status-active">
                                        <span class="status-dot"></span>
                                        Active
                                    </span>

                                @else

                                    <span class="status-badge status-inactive">
                                        <span class="status-dot"></span>
                                        Inactive
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="detail-item full">

                            <div class="detail-label">
                                Description
                            </div>

                            <div class="detail-value">
                                {{ $functionUnit->description ?: 'No description provided.' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- EMPLOYEES --}}

            <div class="card relationship-card">

                <div class="card-header">

                    <div>

                        <h2 class="card-title">
                            Employees
                        </h2>

                        <p class="card-subtitle">
                            Employees associated with this function unit
                        </p>

                    </div>

                    <span class="relationship-count">
                        {{ $functionUnit->employees->count() }}
                    </span>

                </div>

                <div class="card-body">

                    @if ($functionUnit->employees->count() > 0)

                        <div class="relationship-content">

                            <div class="relationship-icon">
                                👥
                            </div>

                            <div class="relationship-text">

                                <strong>
                                    {{ $functionUnit->employees->count() }}
                                    employee(s)
                                </strong>

                                <span>
                                    Employees are currently associated with
                                    this Function Unit.
                                </span>

                            </div>

                        </div>

                    @else

                        <div class="empty-state">

                            <div class="empty-icon">
                                👥
                            </div>

                            <strong>
                                No Employees Assigned
                            </strong>

                            <span>
                                No employees are currently associated with
                                this Function Unit.
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            {{-- IT ASSETS --}}

            <div class="card relationship-card">

                <div class="card-header">

                    <div>

                        <h2 class="card-title">
                            IT Assets
                        </h2>

                        <p class="card-subtitle">
                            Inventory items associated with this function unit
                        </p>

                    </div>

                    <span class="relationship-count">
                        {{ $functionUnit->items->count() }}
                    </span>

                </div>

                <div class="card-body">

                    @if ($functionUnit->items->count() > 0)

                        <div class="relationship-content">

                            <div class="relationship-icon">
                                💻
                            </div>

                            <div class="relationship-text">

                                <strong>
                                    {{ $functionUnit->items->count() }}
                                    IT asset(s)
                                </strong>

                                <span>
                                    Inventory assets are currently associated
                                    with this Function Unit.
                                </span>

                            </div>

                        </div>

                    @else

                        <div class="empty-state">

                            <div class="empty-icon">
                                💻
                            </div>

                            <strong>
                                No IT Assets Assigned
                            </strong>

                            <span>
                                No inventory assets are currently associated
                                with this Function Unit.
                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =================================================
             RIGHT COLUMN
        ================================================== --}}

        <div>

            {{-- QUICK ACTIONS --}}

            <div class="card relationship-card">

                <div class="card-header">

                    <div>

                        <h2 class="card-title">
                            Quick Actions
                        </h2>

                        <p class="card-subtitle">
                            Available actions
                        </p>

                    </div>

                </div>

                <div class="card-body">

                    <div class="quick-actions">

                        {{-- Edit only for Super Admin --}}
                        @if($isSuperAdmin)

                            <a
                                href="{{ route('admin.function-units.edit', $functionUnit->id) }}"
                                class="quick-action"
                            >

                                <div class="quick-action-left">

                                    <div class="quick-action-icon">
                                        ✎
                                    </div>

                                    <span class="quick-action-text">
                                        Edit Function Unit
                                    </span>

                                </div>

                                <span class="quick-arrow">
                                    →
                                </span>

                            </a>

                        @else

                            <div class="quick-action quick-action-disabled">

                                <div class="quick-action-left">

                                    <div class="quick-action-icon">
                                        🔒
                                    </div>

                                    <span class="quick-action-text">
                                        Edit Function Unit
                                    </span>

                                </div>

                                <span class="quick-arrow">
                                    🔒
                                </span>

                            </div>

                        @endif


                        <a
                            href="{{ route('admin.function-units.index') }}"
                            class="quick-action"
                        >

                            <div class="quick-action-left">

                                <div class="quick-action-icon">
                                    ☷
                                </div>

                                <span class="quick-action-text">
                                    All Function Units
                                </span>

                            </div>

                            <span class="quick-arrow">
                                →
                            </span>

                        </a>


                        <a
                            href="{{ route('admin.employees.index') }}"
                            class="quick-action"
                        >

                            <div class="quick-action-left">

                                <div class="quick-action-icon">
                                    👥
                                </div>

                                <span class="quick-action-text">
                                    Employee Management
                                </span>

                            </div>

                            <span class="quick-arrow">
                                →
                            </span>

                        </a>


                        <a
                            href="{{ route('admin.items.index') }}"
                            class="quick-action"
                        >

                            <div class="quick-action-left">

                                <div class="quick-action-icon">
                                    💻
                                </div>

                                <span class="quick-action-text">
                                    Inventory Management
                                </span>

                            </div>

                            <span class="quick-arrow">
                                →
                            </span>

                        </a>

                    </div>

                </div>

            </div>


            {{-- SYSTEM INFORMATION --}}

            <div class="card">

                <div class="card-header">

                    <div>

                        <h2 class="card-title">
                            System Information
                        </h2>

                        <p class="card-subtitle">
                            Function unit record
                        </p>

                    </div>

                </div>

                <div class="card-body">

                    <div class="details-grid">

                        <div class="detail-item">

                            <div class="detail-label">
                                Record ID
                            </div>

                            <div class="detail-value">
                                #{{ $functionUnit->id }}
                            </div>

                        </div>

                        <div class="detail-item">

                            <div class="detail-label">
                                Created
                            </div>

                            <div class="detail-value">

                                {{ $functionUnit->created_at
                                    ? $functionUnit->created_at->format('d M Y')
                                    : '—'
                                }}

                            </div>

                        </div>

                        <div class="detail-item full">

                            <div class="detail-label">
                                Last Updated
                            </div>

                            <div class="detail-value">

                                {{ $functionUnit->updated_at
                                    ? $functionUnit->updated_at->format('d M Y, h:i A')
                                    : '—'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection