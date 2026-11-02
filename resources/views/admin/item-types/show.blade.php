@extends('layouts.admin')

@section('title', $itemType->name . ' | Item Type | CPC Inventory Management System')

@section('content')

<style>
/* =========================================================
   CPC ITEM TYPE PROFILE
========================================================= */

.item-type-page {
    max-width: 1450px;
    margin: 0 auto;
    padding-bottom: 30px;
}

/* =========================================================
   BREADCRUMB
========================================================= */

.item-type-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    color: #667085;
    font-size: 13px;
}

.item-type-breadcrumb a {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 600;
}

.item-type-breadcrumb a:hover {
    text-decoration: underline;
}

.item-type-breadcrumb i {
    color: #98a2b3;
    font-size: 10px;
}

/* =========================================================
   HERO
========================================================= */

.item-type-hero {
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    padding: 30px;
    border-radius: 22px;
    color: #ffffff;
    background: linear-gradient(
        135deg,
        #0d6efd 0%,
        #084298 100%
    );
    box-shadow: 0 14px 35px rgba(13, 110, 253, .18);
}

.item-type-hero::before {
    content: "";
    position: absolute;
    width: 320px;
    height: 320px;
    right: -120px;
    top: -175px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.item-type-hero::after {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    right: 180px;
    bottom: -130px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}

.item-type-hero-content {
    position: relative;
    z-index: 2;
}

.item-type-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.13);
    border: 1px solid rgba(255,255,255,.18);
    font-size: 11px;
    font-weight: 750;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.item-type-title {
    margin: 14px 0 5px;
    font-size: clamp(1.7rem, 3vw, 2.3rem);
    font-weight: 800;
    letter-spacing: -.7px;
}

.item-type-subtitle {
    margin: 0;
    max-width: 700px;
    color: rgba(255,255,255,.85);
    font-size: 14px;
    line-height: 1.6;
}

.hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    margin-top: 18px;
}

.hero-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 13px;
    border-radius: 10px;
    background: rgba(255,255,255,.11);
    border: 1px solid rgba(255,255,255,.17);
    font-size: 12px;
    font-weight: 650;
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.hero-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 9px 15px;
    border-radius: 10px;
    border: 0;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
}

.hero-action-light {
    background: #ffffff;
    color: #172b4d;
}

.hero-action-light:hover {
    background: #f2f4f7;
    color: #172b4d;
}

.hero-action-warning {
    background: #ffc107;
    color: #212529;
}

.hero-action-warning:hover {
    background: #ffca2c;
    color: #212529;
}

/* =========================================================
   SUMMARY CARDS
========================================================= */

.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.summary-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 17px;
    background: #ffffff;
    border: 1px solid #e7ebf0;
    border-radius: 16px;
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
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.summary-value {
    margin-top: 3px;
    color: #172b4d;
    font-size: 15px;
    font-weight: 750;
}

/* =========================================================
   CARDS
========================================================= */

.item-type-card {
    height: 100%;
    overflow: hidden;
    background: #ffffff;
    border: 1px solid #e6eaf0;
    border-radius: 17px;
    box-shadow: 0 5px 18px rgba(16,24,40,.045);
}

.card-header-custom {
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 61px;
    padding: 15px 20px;
    background: #fbfcfe;
    border-bottom: 1px solid #edf0f4;
}

.card-header-custom i {
    color: #0d6efd;
    font-size: 18px;
}

.card-header-custom h5 {
    margin: 0;
    color: #172b4d;
    font-size: 14px;
    font-weight: 750;
}

.card-body-custom {
    padding: 20px;
}

/* =========================================================
   DETAILS
========================================================= */

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0 30px;
}

.detail-item {
    padding: 14px 0;
    border-bottom: 1px solid #f0f2f5;
}

.detail-label {
    margin-bottom: 5px;
    color: #667085;
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .45px;
}

.detail-value {
    color: #172b4d;
    font-size: 14px;
    font-weight: 650;
    line-height: 1.55;
    word-break: break-word;
}

.detail-muted {
    color: #98a2b3;
    font-weight: 500;
}

/* =========================================================
   STATUS
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.status-active {
    background: #e8f7ee;
    color: #198754;
}

.status-inactive {
    background: #f8d7da;
    color: #842029;
}

/* =========================================================
   DESCRIPTION
========================================================= */

.description-box {
    padding: 16px;
    border-radius: 13px;
    background: #f8fafc;
    border: 1px solid #e7ebf0;
    color: #475467;
    font-size: 13px;
    line-height: 1.75;
    white-space: pre-line;
}

/* =========================================================
   INVENTORY TABLE
========================================================= */

.inventory-table {
    min-width: 850px;
    margin: 0;
}

.inventory-table thead th {
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

.inventory-table tbody td {
    padding: 14px 16px;
    color: #344054;
    border-bottom: 1px solid #eef1f4;
    font-size: 12px;
    vertical-align: middle;
}

.inventory-table tbody tr:last-child td {
    border-bottom: 0;
}

.inventory-table tbody tr:hover {
    background: #fafcff;
}

.asset-number {
    color: #0d6efd;
    font-weight: 750;
}

.asset-number a {
    color: inherit;
    text-decoration: none;
}

.asset-number a:hover {
    text-decoration: underline;
}

.secondary-text {
    margin-top: 2px;
    color: #98a2b3;
    font-size: 10px;
}

/* =========================================================
   INVENTORY STATUS
========================================================= */

.asset-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.asset-status-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
}

.asset-available {
    background: #e8f7ee;
    color: #198754;
}

.asset-in-use {
    background: #e8f1ff;
    color: #0d6efd;
}

.asset-repair {
    background: #fff3cd;
    color: #856404;
}

.asset-rdd {
    background: #f8d7da;
    color: #842029;
}

.asset-default {
    background: #eef1f4;
    color: #667085;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    padding: 45px 20px;
    text-align: center;
    color: #98a2b3;
}

.empty-state i {
    display: block;
    margin-bottom: 10px;
    font-size: 34px;
    opacity: .5;
}

.empty-state-title {
    color: #475467;
    font-size: 14px;
    font-weight: 650;
}

.empty-state-text {
    margin-top: 4px;
    font-size: 12px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 767px) {

    .item-type-page {
        padding-left: 4px;
        padding-right: 4px;
    }

    .item-type-hero {
        padding: 22px;
        border-radius: 17px;
    }

    .item-type-title {
        font-size: 1.55rem;
    }

    .hero-actions {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }

    .hero-action {
        width: 100%;
    }

    .summary-grid {
        grid-template-columns: 1fr;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .card-body-custom {
        padding: 17px;
    }

    .card-header-custom {
        padding: 14px 17px;
    }
}

@media (max-width: 430px) {

    .hero-actions {
        grid-template-columns: 1fr;
    }

    .hero-meta {
        flex-direction: column;
        align-items: stretch;
    }

    .hero-meta-item {
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

    .item-type-page {
        max-width: none;
    }

    .item-type-hero {
        color: #000000 !important;
        background: #ffffff !important;
        border: 1px solid #d0d5dd;
        box-shadow: none;
    }

    .item-type-subtitle {
        color: #475467 !important;
    }

    .item-type-card,
    .summary-card {
        box-shadow: none;
        break-inside: avoid;
    }

    .inventory-table {
        min-width: 0;
    }
}
</style>


<div class="container-fluid py-4">

<div class="item-type-page">


    {{-- =====================================================
         BREADCRUMB
    ====================================================== --}}

    <div class="item-type-breadcrumb no-print">

        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>

        <i class="bi bi-chevron-right"></i>

        <a href="{{ route('admin.item-types.index') }}">
            Item Types
        </a>

        <i class="bi bi-chevron-right"></i>

        <span>
            Details
        </span>

    </div>


    {{-- =====================================================
         HERO
    ====================================================== --}}

    <div class="item-type-hero">

        <div class="item-type-hero-content">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">

                <div>

                    <div class="item-type-eyebrow">

                        <i class="bi bi-box-seam"></i>

                        Inventory Item Type

                    </div>


                    <h1 class="item-type-title">

                        {{ $itemType->name }}

                    </h1>


                    <p class="item-type-subtitle">

                        Item classification and associated CPC inventory assets.

                    </p>


                    <div class="hero-meta">

                        <div class="hero-meta-item">

                            <i class="bi bi-tag"></i>

                            {{ $itemType->category ?: 'Uncategorized' }}

                        </div>


                        <div class="hero-meta-item">

                            <i class="bi bi-boxes"></i>

                            {{ $itemType->items->count() }}
                            {{ $itemType->items->count() === 1 ? 'Asset' : 'Assets' }}

                        </div>


                        <div class="hero-meta-item">

                            <i class="bi bi-calendar3"></i>

                            Created
                            {{ $itemType->created_at?->format('d M Y') ?? '—' }}

                        </div>

                    </div>

                </div>


                <div class="hero-actions no-print">

                    <a href="{{ route('admin.item-types.index') }}"
                       class="hero-action hero-action-light">

                        <i class="bi bi-arrow-left"></i>

                        Item Types

                    </a>


                    <a href="{{ route('admin.item-types.edit', $itemType) }}"
                       class="hero-action hero-action-warning">

                        <i class="bi bi-pencil-square"></i>

                        Edit

                    </a>


                    <button type="button"
                            onclick="window.print()"
                            class="hero-action hero-action-light">

                        <i class="bi bi-printer"></i>

                        Print

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-icon">

                <i class="bi bi-boxes"></i>

            </div>

            <div>

                <div class="summary-label">
                    Total Assets
                </div>

                <div class="summary-value">

                    {{ $itemType->items->count() }}

                </div>

            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon">

                <i class="bi bi-check-circle"></i>

            </div>

            <div>

                <div class="summary-label">
                    Available
                </div>

                <div class="summary-value">

                    {{ $itemType->items->where('status', 'available')->count() }}

                </div>

            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon">

                <i class="bi bi-person-check"></i>

            </div>

            <div>

                <div class="summary-label">
                    In Use
                </div>

                <div class="summary-value">

                    {{ $itemType->items->where('status', 'in_use')->count() }}

                </div>

            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon">

                <i class="bi bi-tools"></i>

            </div>

            <div>

                <div class="summary-label">
                    Under Repair
                </div>

                <div class="summary-value">

                    {{ $itemType->items->where('status', 'under_repair')->count() }}

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ITEM TYPE INFORMATION
    ====================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-7">

            <div class="item-type-card">

                <div class="card-header-custom">

                    <i class="bi bi-info-circle"></i>

                    <h5>Item Type Information</h5>

                </div>


                <div class="card-body-custom">

                    <div class="details-grid">

                        <div class="detail-item">

                            <div class="detail-label">
                                Item Type Name
                            </div>

                            <div class="detail-value">

                                {{ $itemType->name }}

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Category
                            </div>

                            <div class="detail-value {{ !$itemType->category ? 'detail-muted' : '' }}">

                                {{ $itemType->category ?: 'Not categorized' }}

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Status
                            </div>

                            <div class="detail-value">

                                @if($itemType->is_active)

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


                        <div class="detail-item">

                            <div class="detail-label">
                                Inventory Assets
                            </div>

                            <div class="detail-value">

                                {{ $itemType->items->count() }}

                                {{ $itemType->items->count() === 1 ? 'asset' : 'assets' }}

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Created
                            </div>

                            <div class="detail-value">

                                {{ $itemType->created_at?->format('d M Y, h:i A') ?? '—' }}

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Last Updated
                            </div>

                            <div class="detail-value">

                                {{ $itemType->updated_at?->format('d M Y, h:i A') ?? '—' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             DESCRIPTION
        ================================================== --}}

        <div class="col-lg-5">

            <div class="item-type-card">

                <div class="card-header-custom">

                    <i class="bi bi-card-text"></i>

                    <h5>Description</h5>

                </div>


                <div class="card-body-custom">

                    @if($itemType->description)

                        <div class="description-box">

                            {{ $itemType->description }}

                        </div>

                    @else

                        <div class="empty-state">

                            <i class="bi bi-card-text"></i>

                            <div class="empty-state-title">

                                No description available

                            </div>

                            <div class="empty-state-text">

                                A description has not been recorded for this item type.

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ASSOCIATED INVENTORY
    ====================================================== --}}

    <div class="item-type-card">

        <div class="card-header-custom">

            <i class="bi bi-boxes"></i>

            <h5>
                Associated Inventory Assets
            </h5>

            <span class="badge bg-primary ms-auto">

                {{ $itemType->items->count() }}

            </span>

        </div>


        <div class="p-0">

            @if($itemType->items->count())

                <div class="table-responsive">

                    <table class="table inventory-table table-hover">

                        <thead>

                            <tr>

                                <th>
                                    Asset Number
                                </th>

                                <th>
                                    Make
                                </th>

                                <th>
                                    Model
                                </th>

                                <th>
                                    Serial Number
                                </th>

                                <th>
                                    Location
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($itemType->items as $item)

                                @php

                                    $assetStatusClass = match($item->status) {

                                        'available' =>
                                            'asset-available',

                                        'in_use' =>
                                            'asset-in-use',

                                        'under_repair' =>
                                            'asset-repair',

                                        'sent_to_rdd' =>
                                            'asset-rdd',

                                        default =>
                                            'asset-default',

                                    };

                                    $assetStatusLabel =
                                        ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $item->status ?? 'Unknown'
                                            )
                                        );

                                @endphp


                                <tr>

                                    <td>

                                        <div class="asset-number">

                                            @if(Route::has('admin.items.show'))

                                                <a href="{{ route('admin.items.show', $item) }}">

                                                    {{ $item->asset_number }}

                                                </a>

                                            @else

                                                {{ $item->asset_number }}

                                            @endif

                                        </div>

                                        @if($item->workstation_id)

                                            <div class="secondary-text">

                                                Workstation:
                                                {{ $item->workstation_id }}

                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $item->make ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $item->model ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $item->serial_number ?: '—' }}

                                    </td>


                                    <td>

                                        <div class="table-primary-text">

                                            {{ $item->branch?->name ?? '—' }}

                                        </div>

                                        @if($item->department)

                                            <div class="secondary-text">

                                                {{ $item->department->name }}

                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="asset-status {{ $assetStatusClass }}">

                                            <span class="asset-status-dot"></span>

                                            {{ $assetStatusLabel }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">

                    <i class="bi bi-box-seam"></i>

                    <div class="empty-state-title">

                        No inventory assets registered

                    </div>

                    <div class="empty-state-text">

                        There are currently no inventory items associated with this item type.

                    </div>

                </div>

            @endif

        </div>

    </div>


</div>

</div>

@endsection