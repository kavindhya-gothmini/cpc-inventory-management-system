
@extends('layouts.admin')

@section('title', 'Dashboard | CPC IT Inventory Management System')
@section('page-heading', 'Dashboard')

@section('content')

<style>
/* =========================================================
   CPC ENTERPRISE DASHBOARD
========================================================= */
.cpc-dashboard {
    width: 100%;
    max-width: 1700px;
    margin: 0 auto;
    padding: 4px 0 30px;
    color: #172033;
}

/* PAGE HEADER */
.cpc-dashboard-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 24px;
}

.cpc-dashboard-heading { min-width: 0; }

.cpc-dashboard-eyebrow {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 7px;
    color: #0d6efd;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .9px;
}

.cpc-dashboard-eyebrow i { font-size: 13px; }

.cpc-dashboard-title {
    margin: 0;
    color: #172033;
    font-size: 27px;
    line-height: 1.2;
    font-weight: 750;
    letter-spacing: -.4px;
}

.cpc-dashboard-subtitle {
    margin: 7px 0 0;
    color: #7b8494;
    font-size: 13px;
    line-height: 1.55;
}

.cpc-dashboard-date {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 13px;
    background: #e9f5fb;
    border: 1px solid #e5e9ef;
    border-radius: 8px;
    color: #667085;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 2px 7px rgba(15, 23, 42, .025);
}

/* HERO */
.cpc-dashboard-hero {
    position: relative;
    overflow: hidden;
    min-height: 155px;
    display: flex;
    align-items: center;
    padding: 28px 32px;
    margin-bottom: 22px;
    border-radius: 12px;
    background: linear-gradient(110deg, #73a2e8 0%, #1261c9 42%, #164d99 100%);
    box-shadow: 0 8px 25px rgba(13, 110, 253, .14);
}

.cpc-dashboard-hero::before,
.cpc-dashboard-hero::after {
    content: "";
    position: absolute;
    border: 1px solid rgba(255, 255, 255, .10);
    border-radius: 50%;
}

.cpc-dashboard-hero::before {
    width: 260px;
    height: 260px;
    right: -75px;
    top: -135px;
}

.cpc-dashboard-hero::after {
    width: 180px;
    height: 180px;
    right: 80px;
    bottom: -135px;
    border-color: rgba(255, 255, 255, .07);
}

.cpc-hero-content {
    position: relative;
    z-index: 2;
    max-width: 700px;
}

.cpc-hero-title {
    margin: 0;
    color: #fff;
    font-size: 22px;
    line-height: 1.35;
    font-weight: 700;
}

.cpc-hero-text {
    margin: 8px 0 0;
    color: rgba(255, 255, 255, .78);
    font-size: 12px;
    line-height: 1.65;
}

.cpc-hero-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-top: 15px;
    padding: 6px 10px;
    border-radius: 6px;
    background: rgba(255, 255, 255, .10);
    color: rgba(255, 255, 255, .88);
    font-size: 10px;
    font-weight: 600;
}

.cpc-hero-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #b7f7d0;
}

/* KPI CARDS */
.cpc-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}

.cpc-kpi-card {
    position: relative;
    min-width: 0;
    padding: 19px;
    background: #fff;
    border: 1px solid #e5e9ef;
    border-radius: 10px;
    box-shadow: 0 2px 7px rgba(15, 23, 42, .025);
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
}

.cpc-kpi-card:hover {
    transform: translateY(-2px);
    border-color: #d5e3f7;
    box-shadow: 0 8px 20px rgba(15, 23, 42, .07);
}

.cpc-kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.cpc-kpi-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #edf5ff;
    color: #0d6efd;
    font-size: 17px;
}

.cpc-kpi-more { color: #aab2be; font-size: 12px; }

.cpc-kpi-label {
    margin-top: 16px;
    color: #7b8494;
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .65px;
}

.cpc-kpi-number {
    margin-top: 4px;
    color: #172033;
    font-size: 26px;
    line-height: 1.2;
    font-weight: 750;
}

.cpc-kpi-description {
    margin-top: 5px;
    color: #98a1af;
    font-size: 10.5px;
}

.cpc-kpi-line {
    height: 3px;
    margin-top: 15px;
    overflow: hidden;
    border-radius: 10px;
    background: #edf1f5;
}

.cpc-kpi-line span {
    display: block;
    height: 100%;
    border-radius: inherit;
    background: #0d6efd;
}

/* MAIN GRID AND PANELS */
.cpc-main-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.65fr) minmax(320px, 1fr);
    gap: 18px;
}

.cpc-panel {
    min-width: 0;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5e9ef;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, .025);
}

.cpc-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 19px;
    border-bottom: 1px solid #edf0f4;
}

.cpc-panel-heading {
    display: flex;
    align-items: center;
    gap: 11px;
}

.cpc-panel-icon {
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #edf5ff;
    color: #0d6efd;
    font-size: 14px;
}

.cpc-panel-title {
    margin: 0;
    color: #172033;
    font-size: 14px;
    font-weight: 700;
}

.cpc-panel-subtitle {
    margin-top: 3px;
    color: #98a1af;
    font-size: 10.5px;
}

.cpc-panel-body { padding: 17px 19px; }

/* INVENTORY MODULE LINKS */
.cpc-module-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.cpc-module {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    padding: 13px;
    border: 1px solid #e9edf2;
    border-radius: 8px;
    background: #fff;
    text-decoration: none;
    transition: background .18s ease, border-color .18s ease, transform .18s ease;
}

.cpc-module:hover {
    background: #f8fbff;
    border-color: #cfe0f8;
    transform: translateY(-1px);
}

.cpc-module-icon {
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f0f6ff;
    color: #0d6efd;
    font-size: 15px;
}

.cpc-module-content { min-width: 0; flex: 1; }

.cpc-module-title {
    color: #293241;
    font-size: 11.5px;
    font-weight: 700;
    line-height: 1.35;
}

.cpc-module:hover .cpc-module-title { color: #0d6efd; }

.cpc-module-text {
    margin-top: 3px;
    overflow: hidden;
    color: #98a1af;
    font-size: 9.5px;
    line-height: 1.45;
    text-overflow: ellipsis;
}

.cpc-module-arrow { color: #b7c0cb; font-size: 10px; }

/* INVENTORY STATUS */
.cpc-status-section {
    margin-top: 18px;
    padding-top: 17px;
    border-top: 1px solid #edf0f4;
}

.cpc-status-title {
    margin-bottom: 13px;
    color: #475467;
    font-size: 11px;
    font-weight: 700;
}

.cpc-status-row { margin-bottom: 13px; }
.cpc-status-row:last-child { margin-bottom: 0; }

.cpc-status-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
    color: #667085;
    font-size: 10px;
    font-weight: 600;
}

.cpc-status-value { color: #293241; }

.cpc-progress {
    width: 100%;
    height: 5px;
    overflow: hidden;
    border-radius: 10px;
    background: #edf1f5;
}

.cpc-progress span {
    display: block;
    height: 100%;
    border-radius: inherit;
    background: #0d6efd;
}

/* SYSTEM OVERVIEW */
.cpc-system-list { width: 100%; }

.cpc-system-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #edf0f4;
}

.cpc-system-row:first-child { padding-top: 0; }
.cpc-system-row:last-child { padding-bottom: 0; border-bottom: 0; }

.cpc-system-name {
    color: #667085;
    font-size: 11px;
}

.cpc-system-value {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #198754;
    font-size: 10px;
    font-weight: 700;
}

.cpc-system-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #198754;
}

/* QUICK ACTIONS */
.cpc-quick-actions {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 9px;
    margin-top: 18px;
}

.cpc-quick-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 37px;
    padding: 8px 10px;
    border: 1px solid #e2e8f0;
    border-radius: 7px;
    background: #fff;
    color: #536174;
    text-decoration: none;
    font-size: 10px;
    font-weight: 600;
    transition: .18s ease;
}

.cpc-quick-action:hover {
    color: #0d6efd;
    background: #f7faff;
    border-color: #cfe0f8;
}

/* INFORMATION */
.cpc-information {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 18px;
    padding: 11px;
    border: 1px solid #dceaff;
    border-radius: 7px;
    background: #f7faff;
    color: #667085;
    font-size: 10px;
    line-height: 1.5;
}

.cpc-information i { color: #0d6efd; flex: 0 0 auto; }

/* RESPONSIVE */
@media (max-width: 1150px) {
    .cpc-kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .cpc-main-grid { grid-template-columns: 1fr; }
}

@media (max-width: 700px) {
    .cpc-dashboard-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 12px;
    }

    .cpc-dashboard-date { width: 100%; justify-content: center; }
    .cpc-dashboard-title { font-size: 23px; }
    .cpc-dashboard-hero { padding: 23px; min-height: 145px; }
    .cpc-hero-title { font-size: 19px; }
    .cpc-kpi-grid { grid-template-columns: 1fr; gap: 10px; }
    .cpc-module-grid { grid-template-columns: 1fr; }
    .cpc-quick-actions { grid-template-columns: 1fr; }
}

@media (max-width: 420px) {
    .cpc-dashboard { padding-bottom: 20px; }
    .cpc-dashboard-title { font-size: 21px; }
    .cpc-dashboard-subtitle { font-size: 11px; }
    .cpc-dashboard-hero { padding: 20px; }
    .cpc-hero-title { font-size: 17px; }
    .cpc-hero-text { font-size: 10.5px; }
    .cpc-panel-header,
    .cpc-panel-body {
        padding-left: 14px;
        padding-right: 14px;
    }
}

.cpc-module:focus-visible,
.cpc-quick-action:focus-visible {
    outline: 3px solid rgba(13, 110, 253, .18);
    outline-offset: 2px;
}
</style>

<div class="cpc-dashboard">

    {{-- PAGE HEADER --}}
    <div class="cpc-dashboard-header">
        <div class="cpc-dashboard-heading">
            <div class="cpc-dashboard-eyebrow">
                <i class="bi bi-grid-1x2-fill"></i>
                {{ strtoupper($branchName ?? 'IT FUNCTION') }}
            </div>

            <h1 class="cpc-dashboard-title">Dashboard</h1>

            <p class="cpc-dashboard-subtitle">
                Centralized overview of IT assets, assignments,
                employees and inventory operations.
            </p>
        </div>

        <div class="cpc-dashboard-date">
            <i class="bi bi-calendar3"></i>
            {{ now()->format('l, d F Y') }}
        </div>
    </div>

    {{-- HERO --}}
    <section class="cpc-dashboard-hero">
        <div class="cpc-hero-content">
            <h2 class="cpc-hero-title">Inventory Management System</h2>

            <p class="cpc-hero-text">
                Manage CPC IT equipment through a centralized inventory
                environment covering asset registration, assignments,
                repairs and RDD activities.
            </p>

            <div class="cpc-hero-status">
                <span class="cpc-hero-status-dot"></span>
                System operational
            </div>
        </div>
    </section>

    {{-- KPI CARDS --}}
    <section class="cpc-kpi-grid">

        <div class="cpc-kpi-card">
            <div class="cpc-kpi-top">
                <div class="cpc-kpi-icon"><i class="bi bi-pc-display"></i></div>
                <span class="cpc-kpi-more"><i class="bi bi-box-seam"></i></span>
            </div>
            <div class="cpc-kpi-label">Total Inventory</div>
            <div class="cpc-kpi-number">{{ number_format($totalItems) }}</div>
            <div class="cpc-kpi-description">Registered IT assets</div>
            <div class="cpc-kpi-line"><span style="width:100%;"></span></div>
        </div>

        <div class="cpc-kpi-card">
            <div class="cpc-kpi-top">
                <div class="cpc-kpi-icon"><i class="bi bi-check-circle"></i></div>
                <span class="cpc-kpi-more"><i class="bi bi-check2-circle"></i></span>
            </div>
            <div class="cpc-kpi-label">Available Assets</div>
            <div class="cpc-kpi-number">{{ number_format($availableItems) }}</div>
            <div class="cpc-kpi-description">Assets currently available</div>
            <div class="cpc-kpi-line">
                <span style="width:{{ $availablePercentage }}%;"></span>
            </div>
        </div>

        <div class="cpc-kpi-card">
            <div class="cpc-kpi-top">
                <div class="cpc-kpi-icon"><i class="bi bi-person-check"></i></div>
                <span class="cpc-kpi-more"><i class="bi bi-arrow-left-right"></i></span>
            </div>
            <div class="cpc-kpi-label">Assignments</div>
            <div class="cpc-kpi-number">{{ number_format($assignedItems) }}</div>
            <div class="cpc-kpi-description">Recorded asset assignments</div>
            <div class="cpc-kpi-line">
                <span style="width:{{ $assignedPercentage }}%;"></span>
            </div>
        </div>

        <div class="cpc-kpi-card">
            <div class="cpc-kpi-top">
                <div class="cpc-kpi-icon"><i class="bi bi-people"></i></div>
                <span class="cpc-kpi-more"><i class="bi bi-person"></i></span>
            </div>
            <div class="cpc-kpi-label">Employees</div>
            <div class="cpc-kpi-number">{{ number_format($employees) }}</div>
            <div class="cpc-kpi-description">Employees registered</div>
            <div class="cpc-kpi-line"><span style="width:100%;"></span></div>
        </div>

    </section>

    {{-- MAIN CONTENT --}}
    <div class="cpc-main-grid">

        {{-- INVENTORY MANAGEMENT --}}
        <section class="cpc-panel">
            <div class="cpc-panel-header">
                <div class="cpc-panel-heading">
                    <div class="cpc-panel-icon"><i class="bi bi-boxes"></i></div>
                    <div>
                        <h2 class="cpc-panel-title">Inventory Management</h2>
                        <div class="cpc-panel-subtitle">
                            Assets, assignments, repairs and RDD operations
                        </div>
                    </div>
                </div>
            </div>

            <div class="cpc-panel-body">
                <div class="cpc-module-grid">

                    @if(Route::has('admin.items.index'))
                        <a href="{{ route('admin.items.index') }}" class="cpc-module">
                            <div class="cpc-module-icon"><i class="bi bi-pc-display"></i></div>
                            <div class="cpc-module-content">
                                <div class="cpc-module-title">Inventory Items</div>
                                <div class="cpc-module-text">Register and manage CPC IT assets</div>
                            </div>
                            <div class="cpc-module-arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    @endif

                    @if(Route::has('admin.item-types.index'))
                        <a href="{{ route('admin.item-types.index') }}" class="cpc-module">
                            <div class="cpc-module-icon"><i class="bi bi-tags"></i></div>
                            <div class="cpc-module-content">
                                <div class="cpc-module-title">Item Types</div>
                                <div class="cpc-module-text">Manage asset classifications</div>
                            </div>
                            <div class="cpc-module-arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    @endif

                    @if(Route::has('admin.item-assignments.index'))
                        <a href="{{ route('admin.item-assignments.index') }}" class="cpc-module">
                            <div class="cpc-module-icon"><i class="bi bi-person-check"></i></div>
                            <div class="cpc-module-content">
                                <div class="cpc-module-title">Item Assignments</div>
                                <div class="cpc-module-text">Manage employee and department assignments</div>
                            </div>
                            <div class="cpc-module-arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    @endif

                    @if(Route::has('admin.inventory.repairs.index'))
                        <a href="{{ route('admin.inventory.repairs.index') }}" class="cpc-module">
                            <div class="cpc-module-icon"><i class="bi bi-tools"></i></div>
                            <div class="cpc-module-content">
                                <div class="cpc-module-title">Repair Management</div>
                                <div class="cpc-module-text">Track equipment repair activities</div>
                            </div>
                            <div class="cpc-module-arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    @endif

                    @if(Route::has('admin.inventory.rdd.index'))
                        <a href="{{ route('admin.inventory.rdd.index') }}" class="cpc-module">
                            <div class="cpc-module-icon"><i class="bi bi-box-arrow-up-right"></i></div>
                            <div class="cpc-module-content">
                                <div class="cpc-module-title">RDD Management</div>
                                <div class="cpc-module-text">Manage assets sent to RDD</div>
                            </div>
                            <div class="cpc-module-arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    @endif

                </div>

                {{-- INVENTORY STATUS --}}
                <div class="cpc-status-section">
                    <div class="cpc-status-title">INVENTORY OVERVIEW</div>

                    <div class="cpc-status-row">
                        <div class="cpc-status-label">
                            <span>Available Assets</span>
                            <span class="cpc-status-value">{{ number_format($availableItems) }}</span>
                        </div>
                        <div class="cpc-progress">
                            <span style="width:{{ $availablePercentage }}%;"></span>
                        </div>
                    </div>

                    <div class="cpc-status-row">
                        <div class="cpc-status-label">
                            <span>Assigned Assets</span>
                            <span class="cpc-status-value">{{ number_format($assignedItems) }}</span>
                        </div>
                        <div class="cpc-progress">
                            <span style="width:{{ $assignedPercentage }}%;"></span>
                        </div>
                    </div>
                </div>

                {{-- QUICK ACTIONS --}}
                <div class="cpc-quick-actions">
                    @if(Route::has('admin.items.create'))
                        <a href="{{ route('admin.items.create') }}" class="cpc-quick-action">
                            <i class="bi bi-plus-lg"></i>
                            Add Inventory Item
                        </a>
                    @endif

                    @if(Route::has('admin.inventory.repairs.create'))
                        <a href="{{ route('admin.inventory.repairs.create') }}" class="cpc-quick-action">
                            <i class="bi bi-tools"></i>
                            New Repair
                        </a>
                    @endif
                </div>
            </div>
        </section>

        {{-- SYSTEM OVERVIEW --}}
        <section class="cpc-panel">
            <div class="cpc-panel-header">
                <div class="cpc-panel-heading">
                    <div class="cpc-panel-icon"><i class="bi bi-activity"></i></div>
                    <div>
                        <h2 class="cpc-panel-title">System Overview</h2>
                        <div class="cpc-panel-subtitle">Current application environment</div>
                    </div>
                </div>
            </div>

            <div class="cpc-panel-body">
                <div class="cpc-system-list">

                    <div class="cpc-system-row">
                        <span class="cpc-system-name">Inventory Database</span>
                        <span class="cpc-system-value">
                            <span class="cpc-system-dot"></span>Connected
                        </span>
                    </div>

                    <div class="cpc-system-row">
                        <span class="cpc-system-name">Application</span>
                        <span class="cpc-system-value">
                            <span class="cpc-system-dot"></span>Operational
                        </span>
                    </div>

                    <div class="cpc-system-row">
                        <span class="cpc-system-name">Inventory Module</span>
                        <span class="cpc-system-value">
                            <span class="cpc-system-dot"></span>Active
                        </span>
                    </div>

                    <div class="cpc-system-row">
                        <span class="cpc-system-name">Employee Module</span>
                        <span class="cpc-system-value">
                            <span class="cpc-system-dot"></span>Active
                        </span>
                    </div>

                    <div class="cpc-system-row">
                        <span class="cpc-system-name">Environment</span>
                        <span class="cpc-system-value">
                            {{ strtoupper(app()->environment()) }}
                        </span>
                    </div>

                    <div class="cpc-system-row">
                        <span class="cpc-system-name">Item Types</span>
                        <span class="cpc-system-value">
                            <span class="cpc-system-dot"></span>
                            {{ number_format($itemTypes) }} Registered
                        </span>
                    </div>

                    <div class="cpc-system-row">
                        <span class="cpc-system-name">Repair Records</span>
                        <span class="cpc-system-value">
                            <span class="cpc-system-dot"></span>
                            {{ number_format($repairItems) }} Recorded
                        </span>
                    </div>

                </div>

                <div class="cpc-information">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        Repair Management and RDD Management are maintained
                        within the main Inventory Management module.
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

@endsection
