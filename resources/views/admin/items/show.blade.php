@extends('layouts.admin')

@section('title', 'Asset Details | CPC Inventory Management System')

@section('content')

<style>
/* =========================================================
   CPC ASSET PROFILE
========================================================= */

.asset-page {
    max-width: 1500px;
    margin: 0 auto;
    padding-bottom: 30px;
}

/* =========================================================
   BREADCRUMB
========================================================= */

.asset-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    color: #667085;
    font-size: 13px;
}

.asset-breadcrumb a {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 600;
}

.asset-breadcrumb a:hover {
    text-decoration: underline;
}

.asset-breadcrumb i {
    font-size: 11px;
    color: #98a2b3;
}

/* =========================================================
   HERO
========================================================= */

.asset-hero {
    position: relative;
    overflow: hidden;
    background:
        linear-gradient(135deg, #0d6efd 0%, #084298 100%);
    border-radius: 22px;
    padding: 30px;
    color: #ffffff;
    box-shadow: 0 14px 35px rgba(13, 110, 253, .18);
    margin-bottom: 24px;
}

.asset-hero::before {
    content: "";
    position: absolute;
    width: 330px;
    height: 330px;
    right: -110px;
    top: -180px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.asset-hero::after {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    right: 160px;
    bottom: -130px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}

.asset-hero-content {
    position: relative;
    z-index: 2;
}

.asset-type-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.13);
    border: 1px solid rgba(255,255,255,.18);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.asset-hero-title {
    margin: 14px 0 5px;
    font-size: clamp(1.7rem, 3vw, 2.35rem);
    font-weight: 800;
    letter-spacing: -.7px;
}

.asset-hero-subtitle {
    margin: 0;
    color: rgba(255,255,255,.84);
    font-size: 14px;
}

.asset-identity {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
}

.asset-identity-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 13px;
    border-radius: 10px;
    background: rgba(255,255,255,.11);
    border: 1px solid rgba(255,255,255,.17);
    font-size: 12px;
    font-weight: 650;
}

.asset-identity-item i {
    opacity: .85;
}

/* =========================================================
   HERO ACTIONS
========================================================= */

.asset-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.asset-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 9px 15px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    border: 0;
    text-decoration: none;
}

.asset-action-light {
    background: #ffffff;
    color: #172b4d;
}

.asset-action-light:hover {
    background: #f2f4f7;
    color: #172b4d;
}

.asset-action-warning {
    background: #ffc107;
    color: #212529;
}

.asset-action-warning:hover {
    background: #ffca2c;
    color: #212529;
}

/* =========================================================
   SUMMARY CARDS
========================================================= */

.asset-summary {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.summary-card {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #ffffff;
    border: 1px solid #e7ebf0;
    border-radius: 16px;
    padding: 17px;
    box-shadow: 0 5px 18px rgba(16,24,40,.045);
}

.summary-icon {
    flex: 0 0 44px;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #edf5ff;
    color: #0d6efd;
    font-size: 19px;
}

.summary-label {
    color: #667085;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .35px;
}

.summary-value {
    margin-top: 3px;
    color: #172b4d;
    font-size: 14px;
    font-weight: 750;
}

/* =========================================================
   STATUS
========================================================= */

.asset-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 750;
    white-space: nowrap;
}

.asset-status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

.status-available {
    background: #e8f7ee;
    color: #198754;
}

.status-in-use {
    background: #e8f1ff;
    color: #0d6efd;
}

.status-under-repair {
    background: #fff3cd;
    color: #856404;
}

.status-rdd {
    background: #f8d7da;
    color: #842029;
}

.status-default {
    background: #eef1f4;
    color: #667085;
}

/* =========================================================
   CARD
========================================================= */

.asset-card {
    height: 100%;
    background: #ffffff;
    border: 1px solid #e6eaf0;
    border-radius: 17px;
    box-shadow: 0 5px 18px rgba(16,24,40,.045);
    overflow: hidden;
}

.asset-card-header {
    min-height: 61px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 20px;
    background: #fbfcfe;
    border-bottom: 1px solid #edf0f4;
}

.asset-card-header i {
    color: #0d6efd;
    font-size: 18px;
}

.asset-card-header h5 {
    margin: 0;
    color: #172b4d;
    font-size: 14px;
    font-weight: 750;
}

.asset-card-body {
    padding: 20px;
}

/* =========================================================
   INFORMATION GRID
========================================================= */

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0 28px;
}

.info-item {
    padding: 14px 0;
    border-bottom: 1px solid #f0f2f5;
}

.info-grid .info-item:nth-last-child(-n+2) {
    border-bottom: 0;
}

.info-label {
    margin-bottom: 5px;
    color: #667085;
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.info-value {
    color: #172b4d;
    font-size: 14px;
    font-weight: 650;
    line-height: 1.5;
    word-break: break-word;
}

.info-muted {
    color: #98a2b3;
    font-weight: 500;
}

/* =========================================================
   ASSIGNMENT
========================================================= */

.assignment-box {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 15px;
    margin-bottom: 8px;
    border: 1px solid #dce8f8;
    border-radius: 13px;
    background: linear-gradient(135deg,#f7fbff,#f2f7fd);
}

.assignment-avatar {
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #0d6efd;
    color: #ffffff;
    font-size: 17px;
    font-weight: 750;
}

.assignment-label {
    color: #667085;
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.assignment-name {
    margin-top: 2px;
    color: #172b4d;
    font-size: 14px;
    font-weight: 750;
}

.assignment-meta {
    margin-top: 2px;
    color: #667085;
    font-size: 11px;
}

/* =========================================================
   SPECIFICATIONS
========================================================= */

.spec-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.spec-item {
    padding: 14px;
    border: 1px solid #e7ebf0;
    border-radius: 12px;
    background: #f8fafc;
}

.spec-item.full {
    grid-column: span 2;
}

.spec-label {
    margin-bottom: 6px;
    color: #667085;
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.spec-value {
    color: #172b4d;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.5;
    word-break: break-word;
}

/* =========================================================
   DESCRIPTION / NOTES
========================================================= */

.text-content {
    color: #475467;
    font-size: 13px;
    line-height: 1.75;
    white-space: pre-line;
    word-break: break-word;
}

/* =========================================================
   TABLE
========================================================= */

.asset-table {
    min-width: 850px;
    margin: 0;
}

.asset-table thead th {
    padding: 13px 16px;
    background: #f8fafc;
    color: #667085;
    border-bottom: 1px solid #e4e7ec;
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}

.asset-table tbody td {
    padding: 14px 16px;
    color: #344054;
    border-bottom: 1px solid #eef1f4;
    font-size: 12px;
    vertical-align: middle;
}

.asset-table tbody tr:last-child td {
    border-bottom: 0;
}

.asset-table tbody tr:hover {
    background: #fafcff;
}

.table-primary-text {
    color: #172b4d;
    font-weight: 700;
}

.table-secondary-text {
    margin-top: 2px;
    color: #98a2b3;
    font-size: 10px;
}

/* =========================================================
   HISTORY TIMELINE
========================================================= */

.history {
    position: relative;
    padding-left: 13px;
}

.history-item {
    position: relative;
    padding: 0 0 25px 31px;
    border-left: 2px solid #e4e9f0;
}

.history-item:last-child {
    padding-bottom: 0;
    border-left-color: transparent;
}

.history-dot {
    position: absolute;
    left: -7px;
    top: 2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #0d6efd;
    border: 2px solid #ffffff;
    box-shadow: 0 0 0 2px #0d6efd;
}

.history-title {
    color: #172b4d;
    font-size: 13px;
    font-weight: 750;
}

.history-date {
    margin-top: 3px;
    color: #98a2b3;
    font-size: 10px;
}

.history-description {
    margin-top: 7px;
    color: #667085;
    font-size: 12px;
    line-height: 1.6;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    padding: 28px 15px;
    text-align: center;
    color: #98a2b3;
}

.empty-state i {
    display: block;
    margin-bottom: 8px;
    font-size: 28px;
    opacity: .5;
}

.empty-state-text {
    font-size: 12px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .asset-summary {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 767px) {

    .asset-page {
        padding-left: 4px;
        padding-right: 4px;
    }

    .asset-hero {
        padding: 22px;
        border-radius: 17px;
    }

    .asset-hero-title {
        font-size: 1.55rem;
    }

    .asset-actions {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }

    .asset-action {
        width: 100%;
    }

    .asset-summary {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .info-grid .info-item {
        border-bottom: 1px solid #f0f2f5;
    }

    .info-grid .info-item:last-child {
        border-bottom: 0;
    }

    .spec-grid {
        grid-template-columns: 1fr;
    }

    .spec-item.full {
        grid-column: span 1;
    }

    .asset-card-body {
        padding: 17px;
    }

    .asset-card-header {
        padding: 14px 17px;
    }
}

@media (max-width: 430px) {

    .asset-actions {
        grid-template-columns: 1fr;
    }

    .asset-identity {
        flex-direction: column;
        align-items: stretch;
    }

    .asset-identity-item {
        justify-content: center;
    }
}

/* =========================================================
   PRINT
========================================================= */

@media print {

    body {
        background: #ffffff !important;
    }

    .no-print {
        display: none !important;
    }

    .asset-page {
        max-width: none;
    }

    .asset-hero {
        color: #000000 !important;
        background: #ffffff !important;
        border: 1px solid #d0d5dd;
        box-shadow: none;
    }

    .asset-hero-subtitle {
        color: #475467 !important;
    }

    .asset-card,
    .summary-card {
        box-shadow: none;
        break-inside: avoid;
    }

    .asset-table {
        min-width: 0;
    }
}
</style>


<div class="container-fluid py-4">

<div class="asset-page">

    {{-- =====================================================
         BREADCRUMB
    ====================================================== --}}

    <div class="asset-breadcrumb no-print">

        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>

        <i class="bi bi-chevron-right"></i>

        <a href="{{ route('admin.items.index') }}">
            Inventory
        </a>

        <i class="bi bi-chevron-right"></i>

        <span>Asset Details</span>

    </div>


    {{-- =====================================================
         HERO
    ====================================================== --}}

    <div class="asset-hero">

        <div class="asset-hero-content">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">

                <div>

                    <div class="asset-type-label">

                        <i class="bi bi-box-seam"></i>

                        CPC Inventory Asset

                    </div>

                    <h1 class="asset-hero-title">

                        {{ $item->asset_number }}

                    </h1>

                    <p class="asset-hero-subtitle">

                        {{ $item->itemType?->name ?? 'Inventory Asset' }}

                        @if($item->make || $item->model)

                            <span class="mx-1">•</span>

                            {{ trim(($item->make ?? '') . ' ' . ($item->model ?? '')) }}

                        @endif

                    </p>


                    <div class="asset-identity">

                        <div class="asset-identity-item">

                            <i class="bi bi-upc-scan"></i>

                            Asset No:
                            {{ $item->asset_number }}

                        </div>

                        @if($item->serial_number)

                            <div class="asset-identity-item">

                                <i class="bi bi-upc"></i>

                                Serial:
                                {{ $item->serial_number }}

                            </div>

                        @endif

                    </div>

                </div>


                <div class="asset-actions no-print">

                    <a href="{{ route('admin.items.index') }}"
                       class="asset-action asset-action-light">

                        <i class="bi bi-arrow-left"></i>

                        Inventory

                    </a>

                    <a href="{{ route('admin.items.edit', $item) }}"
                       class="asset-action asset-action-warning">

                        <i class="bi bi-pencil-square"></i>

                        Edit Asset

                    </a>

                    <button type="button"
                            onclick="window.print()"
                            class="asset-action asset-action-light">

                        <i class="bi bi-printer"></i>

                        Print

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         QUICK SUMMARY
    ====================================================== --}}

    @php

        $statusClass = match($item->status) {

            'available' =>
                'status-available',

            'in_use' =>
                'status-in-use',

            'under_repair' =>
                'status-under-repair',

            'sent_to_rdd' =>
                'status-rdd',

            default =>
                'status-default',

        };

        $statusLabel =
            ucfirst(
                str_replace('_', ' ', $item->status ?? 'Unknown')
            );

    @endphp


    <div class="asset-summary">

        <div class="summary-card">

            <div class="summary-icon">

                <i class="bi bi-activity"></i>

            </div>

            <div>

                <div class="summary-label">
                    Current Status
                </div>

                <div class="summary-value">

                    <span class="asset-status {{ $statusClass }}">

                        <span class="asset-status-dot"></span>

                        {{ $statusLabel }}

                    </span>

                </div>

            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon">

                <i class="bi bi-building"></i>

            </div>

            <div>

                <div class="summary-label">
                    Branch
                </div>

                <div class="summary-value">

                    {{ $item->branch?->name ?? 'Not assigned' }}

                </div>

            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon">

                <i class="bi bi-diagram-3"></i>

            </div>

            <div>

                <div class="summary-label">
                    Department
                </div>

                <div class="summary-value">

                    {{ $item->department?->name ?? 'Not assigned' }}

                </div>

            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon">

                <i class="bi bi-person-badge"></i>

            </div>

            <div>

                <div class="summary-label">
                    Current Employee
                </div>

                <div class="summary-value">

                    {{ $item->currentEmployee?->name ?? 'Not assigned' }}

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ASSET INFORMATION + LOCATION
    ====================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-7">

            <div class="asset-card">

                <div class="asset-card-header">

                    <i class="bi bi-info-circle"></i>

                    <h5>Asset Information</h5>

                </div>


                <div class="asset-card-body">

                    <div class="info-grid">

                        <div class="info-item">

                            <div class="info-label">
                                Asset Number
                            </div>

                            <div class="info-value">
                                {{ $item->asset_number }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Asset Type
                            </div>

                            <div class="info-value">
                                {{ $item->itemType?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Make
                            </div>

                            <div class="info-value {{ !$item->make ? 'info-muted' : '' }}">
                                {{ $item->make ?: 'Not recorded' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Model
                            </div>

                            <div class="info-value {{ !$item->model ? 'info-muted' : '' }}">
                                {{ $item->model ?: 'Not recorded' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Serial Number
                            </div>

                            <div class="info-value {{ !$item->serial_number ? 'info-muted' : '' }}">
                                {{ $item->serial_number ?: 'Not recorded' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Status
                            </div>

                            <div class="info-value">

                                <span class="asset-status {{ $statusClass }}">

                                    <span class="asset-status-dot"></span>

                                    {{ $statusLabel }}

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-5">

            <div class="asset-card">

                <div class="asset-card-header">

                    <i class="bi bi-geo-alt"></i>

                    <h5>Location & Assignment</h5>

                </div>


                <div class="asset-card-body">

                    @if($item->currentEmployee)

                        <div class="assignment-box">

                            <div class="assignment-avatar">

                                <i class="bi bi-person"></i>

                            </div>

                            <div>

                                <div class="assignment-label">
                                    Currently Assigned To
                                </div>

                                <div class="assignment-name">

                                    {{ $item->currentEmployee->name }}

                                </div>

                                @if($item->currentEmployee->epf_no)

                                    <div class="assignment-meta">

                                        EPF No:
                                        {{ $item->currentEmployee->epf_no }}

                                    </div>

                                @endif

                            </div>

                        </div>

                    @else

                        <div class="info-item">

                            <div class="info-label">
                                Current Employee
                            </div>

                            <div class="info-value info-muted">
                                Not currently assigned
                            </div>

                        </div>

                    @endif


                    <div class="info-item">

                        <div class="info-label">
                            Branch
                        </div>

                        <div class="info-value">

                            {{ $item->branch?->name ?? 'Not assigned' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Department
                        </div>

                        <div class="info-value">

                            {{ $item->department?->name ?? 'Not assigned' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Function Unit
                        </div>

                        <div class="info-value">

                            {{ $item->functionUnit?->name ?? 'Not assigned' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         NETWORK + TECHNICAL SPECIFICATIONS
    ====================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-5">

            <div class="asset-card">

                <div class="asset-card-header">

                    <i class="bi bi-pc-display"></i>

                    <h5>Network Information</h5>

                </div>


                <div class="asset-card-body">

                    <div class="info-item">

                        <div class="info-label">
                            IP Address
                        </div>

                        <div class="info-value {{ !$item->ip_address ? 'info-muted' : '' }}">

                            {{ $item->ip_address ?: 'Not recorded' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Workstation ID
                        </div>

                        <div class="info-value {{ !$item->workstation_id ? 'info-muted' : '' }}">

                            {{ $item->workstation_id ?: 'Not recorded' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-7">

            <div class="asset-card">

                <div class="asset-card-header">

                    <i class="bi bi-cpu"></i>

                    <h5>Technical Specifications</h5>

                </div>


                <div class="asset-card-body">

                    @if($item->specification)

                        <div class="spec-grid">

                            <div class="spec-item">

                                <div class="spec-label">
                                    RAM
                                </div>

                                <div class="spec-value">
                                    {{ $item->specification->ram ?: '—' }}
                                </div>

                            </div>


                            <div class="spec-item">

                                <div class="spec-label">
                                    Storage
                                </div>

                                <div class="spec-value">
                                    {{ $item->specification->storage ?: '—' }}
                                </div>

                            </div>


                            <div class="spec-item">

                                <div class="spec-label">
                                    Processor
                                </div>

                                <div class="spec-value">
                                    {{ $item->specification->processor ?: '—' }}
                                </div>

                            </div>


                            <div class="spec-item">

                                <div class="spec-label">
                                    Graphics
                                </div>

                                <div class="spec-value">
                                    {{ $item->specification->graphics ?: '—' }}
                                </div>

                            </div>


                            <div class="spec-item full">

                                <div class="spec-label">
                                    Operating System
                                </div>

                                <div class="spec-value">
                                    {{ $item->specification->operating_system ?: '—' }}
                                </div>

                            </div>

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="bi bi-cpu"></i>

                            <div class="empty-state-text">
                                No technical specifications have been recorded.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         DESCRIPTION + NOTES
    ====================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="asset-card">

                <div class="asset-card-header">

                    <i class="bi bi-card-text"></i>

                    <h5>Description</h5>

                </div>


                <div class="asset-card-body">

                    @if($item->description)

                        <div class="text-content">

                            {{ $item->description }}

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="bi bi-card-text"></i>

                            <div class="empty-state-text">
                                No description has been recorded.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="asset-card">

                <div class="asset-card-header">

                    <i class="bi bi-journal-text"></i>

                    <h5>Notes</h5>

                </div>


                <div class="asset-card-body">

                    @if($item->notes)

                        <div class="text-content">

                            {{ $item->notes }}

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="bi bi-journal-text"></i>

                            <div class="empty-state-text">
                                No additional notes have been recorded.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ASSIGNMENT HISTORY
    ====================================================== --}}

    <div class="asset-card mb-4">

        <div class="asset-card-header">

            <i class="bi bi-person-workspace"></i>

            <h5>Assignment History</h5>

        </div>


        <div class="p-0">

            @if($item->assignments->count())

                <div class="table-responsive">

                    <table class="table asset-table table-hover">

                        <thead>

                            <tr>

                                <th>Employee</th>

                                <th>Branch</th>

                                <th>Department</th>

                                <th>Workstation</th>

                                <th>Assigned</th>

                                <th>Returned</th>

                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($item->assignments->sortByDesc('assigned_date') as $assignment)

                                <tr>

                                    <td>

                                        @if($assignment->employee)

                                            <div class="table-primary-text">

                                                {{ $assignment->employee->name }}

                                            </div>

                                            @if($assignment->employee->epf_no)

                                                <div class="table-secondary-text">

                                                    EPF:
                                                    {{ $assignment->employee->epf_no }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $assignment->branch?->name ?? '—' }}

                                    </td>


                                    <td>

                                        {{ $assignment->department?->name ?? '—' }}

                                    </td>


                                    <td>

                                        {{ $assignment->workstation_id ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $assignment->assigned_date?->format('d M Y') ?? '—' }}

                                    </td>


                                    <td>

                                        {{ $assignment->returned_date?->format('d M Y') ?? '—' }}

                                    </td>


                                    <td>

                                        @if($assignment->returned_date)

                                            <span class="badge bg-secondary">
                                                Returned
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                Issued
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

                    <i class="bi bi-person-x"></i>

                    <div class="empty-state-text">
                        No assignment history is available for this asset.
                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         INVENTORY HISTORY
    ====================================================== --}}

    <div class="asset-card">

        <div class="asset-card-header">

            <i class="bi bi-clock-history"></i>

            <h5>Inventory History</h5>

        </div>


        <div class="asset-card-body">

            @if($item->histories->count())

                <div class="history">

                    @foreach($item->histories->sortByDesc('created_at') as $history)

                        <div class="history-item">

                            <span class="history-dot"></span>


                            <div class="history-title">

                                {{ ucfirst(str_replace('_', ' ', $history->event_type)) }}

                            </div>


                            <div class="history-date">

                                {{ $history->created_at?->format('d M Y, h:i A') ?? '—' }}

                            </div>


                            @if($history->old_status || $history->new_status)

                                <div class="history-description">

                                    @if($history->old_status)

                                        <strong>
                                            {{ ucfirst(str_replace('_', ' ', $history->old_status)) }}
                                        </strong>

                                    @endif


                                    @if($history->old_status && $history->new_status)

                                        <span class="mx-1">
                                            →
                                        </span>

                                    @endif


                                    @if($history->new_status)

                                        <strong>
                                            {{ ucfirst(str_replace('_', ' ', $history->new_status)) }}
                                        </strong>

                                    @endif

                                </div>

                            @endif


                            @if($history->remarks)

                                <div class="history-description">

                                    {{ $history->remarks }}

                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <i class="bi bi-clock-history"></i>

                    <div class="empty-state-text">
                        No inventory history is available for this asset.
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

</div>

@endsection