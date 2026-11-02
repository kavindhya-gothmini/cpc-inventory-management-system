@extends('layouts.admin')

@section('title', 'Spare Part Details | CPC Inventory Management System')

@section('content')

<style>
/* =========================================================
   CPC SPARE PART DETAILS
========================================================= */

.spare-part-show-page {
    min-height: calc(100vh - 72px);
    background: #f4f7fb;
    padding: 28px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 24px;
}

.page-title h1 {
    margin: 0;
    color: #172033;
    font-size: 27px;
    font-weight: 700;
}

.page-title p {
    margin: 7px 0 0;
    color: #718096;
    font-size: 14px;
}

.header-actions {
    display: flex;
    gap: 9px;
    flex-wrap: wrap;
}

.btn-cpc {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 16px;
    border-radius: 9px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
}

.btn-primary-cpc {
    background: #0d6efd;
    color: #fff;
}

.btn-primary-cpc:hover {
    background: #0b5ed7;
    color: #fff;
}

.btn-secondary-cpc {
    background: #fff;
    color: #374151;
    border: 1px solid #dbe2ea;
}

.btn-secondary-cpc:hover {
    background: #f8fafc;
    color: #1f2937;
}

/* Hero */
.part-hero {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 15px;
    padding: 22px;
    margin-bottom: 22px;
    box-shadow: 0 4px 15px rgba(15, 23, 42, .04);
}

.hero-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.part-identity {
    display: flex;
    align-items: center;
    gap: 16px;
}

.part-icon {
    width: 62px;
    height: 62px;
    flex-shrink: 0;
    border-radius: 15px;
    background: #e8f1ff;
    color: #0d6efd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 27px;
}

.part-identity h2 {
    margin: 0;
    color: #172033;
    font-size: 21px;
    font-weight: 700;
}

.part-code {
    margin-top: 5px;
    color: #0d6efd;
    font-size: 13px;
    font-weight: 700;
}

.part-category {
    margin-top: 3px;
    color: #718096;
    font-size: 12px;
}

.hero-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.badge-active {
    background: #e8f7ef;
    color: #198754;
}

.badge-inactive {
    background: #eef1f4;
    color: #64748b;
}

.badge-good {
    background: #e8f7ef;
    color: #198754;
}

.badge-low {
    background: #fff3df;
    color: #b76e00;
}

.badge-out {
    background: #ffe8e8;
    color: #dc3545;
}

.badge-warranty {
    background: #e8f1ff;
    color: #0d6efd;
}

.badge-expiring {
    background: #fff3df;
    color: #b76e00;
}

.badge-expired {
    background: #ffe8e8;
    color: #dc3545;
}

.badge-none {
    background: #eef1f4;
    color: #64748b;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 22px;
}

.stat-card {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 13px;
    padding: 18px;
    box-shadow: 0 4px 15px rgba(15, 23, 42, .04);
}

.stat-label {
    color: #718096;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.stat-value {
    margin-top: 8px;
    color: #172033;
    font-size: 23px;
    font-weight: 700;
}

.stat-subtitle {
    margin-top: 4px;
    color: #94a3b8;
    font-size: 11px;
}

/* Main grid */
.content-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(300px, 1fr);
    gap: 22px;
    align-items: start;
}

.card {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 22px;
    box-shadow: 0 4px 15px rgba(15, 23, 42, .04);
}

.card:last-child {
    margin-bottom: 0;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 17px 20px;
    border-bottom: 1px solid #edf1f5;
}

.card-header h3 {
    margin: 0;
    color: #172033;
    font-size: 15px;
    font-weight: 700;
}

.card-header small {
    color: #94a3b8;
    font-size: 11px;
}

.card-body {
    padding: 20px;
}

/* Information rows */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0 22px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #edf1f5;
}

.info-label {
    color: #718096;
    font-size: 12px;
}

.info-value {
    color: #172033;
    font-size: 12px;
    font-weight: 600;
    text-align: right;
}

.description-box {
    padding: 14px;
    background: #f8fafc;
    border-radius: 9px;
    color: #475569;
    font-size: 12px;
    line-height: 1.7;
}

/* Stock display */
.stock-display {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px;
    background: #f8fbff;
    border: 1px solid #dcecff;
    border-radius: 10px;
    margin-bottom: 16px;
}

.stock-number {
    color: #172033;
    font-size: 30px;
    font-weight: 700;
}

.stock-label {
    margin-top: 2px;
    color: #718096;
    font-size: 11px;
}

.stock-progress {
    width: 180px;
}

.progress-label {
    display: flex;
    justify-content: space-between;
    color: #718096;
    font-size: 10px;
    margin-bottom: 6px;
}

.progress-track {
    height: 7px;
    overflow: hidden;
    border-radius: 99px;
    background: #e5eaf0;
}

.progress-bar {
    height: 100%;
    border-radius: 99px;
    background: #0d6efd;
}

/* Warranty */
.warranty-box {
    padding: 15px;
    border-radius: 10px;
    background: #f8fafc;
}

.warranty-status {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 12px;
}

.warranty-date {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e9eef3;
    font-size: 12px;
}

.warranty-date:last-child {
    border-bottom: none;
}

.warranty-date span:first-child {
    color: #718096;
}

.warranty-date span:last-child {
    color: #172033;
    font-weight: 600;
}

/* Tables */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.data-table {
    width: 100%;
    min-width: 700px;
    border-collapse: collapse;
}

.data-table th {
    padding: 12px 15px;
    background: #f8fafc;
    border-bottom: 1px solid #e5eaf0;
    color: #64748b;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    text-align: left;
    white-space: nowrap;
}

.data-table td {
    padding: 13px 15px;
    border-bottom: 1px solid #edf1f5;
    color: #475569;
    font-size: 12px;
    vertical-align: middle;
}

.data-table tbody tr:hover {
    background: #fafcff;
}

.movement-in {
    color: #198754;
    font-weight: 700;
}

.movement-out {
    color: #dc3545;
    font-weight: 700;
}

.movement-adjustment {
    color: #b76e00;
    font-weight: 700;
}

/* Empty */
.empty-small {
    padding: 35px 20px;
    text-align: center;
    color: #94a3b8;
    font-size: 12px;
}

.empty-small i {
    display: block;
    margin-bottom: 8px;
    font-size: 25px;
}

/* Document */
.document-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #edf1f5;
}

.document-row:last-child {
    border-bottom: none;
}

.document-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.document-icon {
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    border-radius: 8px;
    background: #ffe8e8;
    color: #dc3545;
    display: flex;
    align-items: center;
    justify-content: center;
}

.document-name {
    color: #172033;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.document-meta {
    margin-top: 3px;
    color: #94a3b8;
    font-size: 10px;
}

.document-link {
    color: #0d6efd;
    font-size: 12px;
    text-decoration: none;
    white-space: nowrap;
}

/* Alert */
.alert-box {
    padding: 13px 15px;
    border-radius: 9px;
    margin-bottom: 18px;
    font-size: 12px;
}

.alert-warning {
    background: #fff8e8;
    border: 1px solid #ffe5a8;
    color: #8a6200;
}

/* Responsive */
@media (max-width: 1100px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {
    .spare-part-show-page {
        padding: 18px;
    }

    .page-header,
    .hero-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
    }

    .header-actions .btn-cpc {
        flex: 1;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .stock-display {
        flex-direction: column;
        align-items: flex-start;
    }

    .stock-progress {
        width: 100%;
    }
}
</style>


<div class="spare-part-show-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="page-header">

        <div class="page-title">

            <h1>
                <i class="bi bi-box-seam"></i>
                Spare Part Details
            </h1>

            <p>
                Complete spare part information and stock history.
            </p>

        </div>

        <div class="header-actions">

            <a
                href="{{ route('admin.inventory.repairs.spare-parts.index') }}"
                class="btn-cpc btn-secondary-cpc"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <a
                href="{{ route('admin.inventory.repairs.spare-parts.edit', $sparePart) }}"
                class="btn-cpc btn-primary-cpc"
            >
                <i class="bi bi-pencil"></i>
                Edit Spare Part
            </a>

        </div>

    </div>


    {{-- =====================================================
         HERO
    ====================================================== --}}
    <div class="part-hero">

        <div class="hero-content">

            <div class="part-identity">

                <div class="part-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>

                    <h2>
                        {{ $sparePart->part_name }}
                    </h2>

                    <div class="part-code">
                        {{ $sparePart->part_code }}
                    </div>

                    <div class="part-category">
                        {{ $sparePart->category ?: 'Uncategorized' }}
                    </div>

                </div>

            </div>


            <div class="hero-badges">

                @if($sparePart->status === 'active')

                    <span class="status-badge badge-active">
                        <i class="bi bi-check-circle"></i>
                        &nbsp;Active
                    </span>

                @else

                    <span class="status-badge badge-inactive">
                        <i class="bi bi-pause-circle"></i>
                        &nbsp;Inactive
                    </span>

                @endif


                @if($sparePart->quantity <= 0)

                    <span class="status-badge badge-out">
                        Out of Stock
                    </span>

                @elseif($sparePart->quantity <= $sparePart->minimum_stock)

                    <span class="status-badge badge-low">
                        Low Stock
                    </span>

                @else

                    <span class="status-badge badge-good">
                        Stock Available
                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- =====================================================
         SUMMARY STATISTICS
    ====================================================== --}}
    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-label">
                Current Stock
            </div>

            <div class="stat-value">
                {{ number_format($sparePart->quantity) }}
            </div>

            <div class="stat-subtitle">
                Units available
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-label">
                Minimum Stock
            </div>

            <div class="stat-value">
                {{ number_format($sparePart->minimum_stock) }}
            </div>

            <div class="stat-subtitle">
                Reorder threshold
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-label">
                Unit Price
            </div>

            <div class="stat-value">
                {{ number_format((float) $sparePart->unit_price, 2) }}
            </div>

            <div class="stat-subtitle">
                Per unit
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-label">
                Stock Value
            </div>

            <div class="stat-value">
                {{ number_format(
                    $sparePart->quantity * (float) $sparePart->unit_price,
                    2
                ) }}
            </div>

            <div class="stat-subtitle">
                Current inventory value
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

            {{-- Basic Information --}}
            <div class="card">

                <div class="card-header">

                    <h3>
                        <i class="bi bi-info-circle"></i>
                        Spare Part Information
                    </h3>

                </div>

                <div class="card-body">

                    <div class="info-grid">

                        <div class="info-row">

                            <span class="info-label">
                                Part Code
                            </span>

                            <span class="info-value">
                                {{ $sparePart->part_code }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Part Name
                            </span>

                            <span class="info-value">
                                {{ $sparePart->part_name }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Category
                            </span>

                            <span class="info-value">
                                {{ $sparePart->category ?: '—' }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Supplier
                            </span>

                            <span class="info-value">
                                {{ $sparePart->supplier_name ?: '—' }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Supplier Reference
                            </span>

                            <span class="info-value">
                                {{ $sparePart->supplier_reference ?: '—' }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Storage Location
                            </span>

                            <span class="info-value">
                                {{ $sparePart->location ?: '—' }}
                            </span>

                        </div>

                    </div>


                    @if($sparePart->description)

                        <div style="margin-top:18px;">

                            <div style="
                                color:#718096;
                                font-size:12px;
                                margin-bottom:7px;
                                font-weight:600;
                            ">
                                Description
                            </div>

                            <div class="description-box">

                                {{ $sparePart->description }}

                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Stock Information --}}
            <div class="card">

                <div class="card-header">

                    <h3>
                        <i class="bi bi-boxes"></i>
                        Stock Information
                    </h3>

                </div>

                <div class="card-body">

                    <div class="stock-display">

                        <div>

                            <div class="stock-number">
                                {{ number_format($sparePart->quantity) }}
                            </div>

                            <div class="stock-label">
                                Current available units
                            </div>

                        </div>


                        <div class="stock-progress">

                            <div class="progress-label">

                                <span>
                                    Stock Level
                                </span>

                                <span>
                                    Minimum:
                                    {{ number_format($sparePart->minimum_stock) }}
                                </span>

                            </div>

                            @php
                                $minimum = max((int) $sparePart->minimum_stock, 1);
                                $stockPercentage = min(
                                    100,
                                    ($sparePart->quantity / $minimum) * 100
                                );
                            @endphp

                            <div class="progress-track">

                                <div
                                    class="progress-bar"
                                    style="width: {{ $stockPercentage }}%;"
                                ></div>

                            </div>

                        </div>

                    </div>


                    <div class="info-grid">

                        <div class="info-row">

                            <span class="info-label">
                                Minimum Stock
                            </span>

                            <span class="info-value">
                                {{ number_format($sparePart->minimum_stock) }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Unit Price
                            </span>

                            <span class="info-value">
                                {{ number_format((float) $sparePart->unit_price, 2) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Stock Movement History --}}
            <div class="card">

                <div class="card-header">

                    <h3>
                        <i class="bi bi-clock-history"></i>
                        Stock Movement History
                    </h3>

                    <small>
                        {{ $sparePart->stockMovements->count() }}
                        movement(s)
                    </small>

                </div>

                <div class="card-body" style="padding:0;">

                    @if($sparePart->stockMovements->count())

                        <div class="table-responsive">

                            <table class="data-table">

                                <thead>

                                    <tr>

                                        <th>Date</th>

                                        <th>Movement</th>

                                        <th>Quantity</th>

                                        <th>Before</th>

                                        <th>After</th>

                                        <th>Reference</th>

                                        <th>Remarks</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($sparePart->stockMovements as $movement)

                                        <tr>

                                            <td>
                                                {{ $movement->created_at?->format('Y-m-d H:i') }}
                                            </td>


                                            <td>

                                                @if($movement->movement_type === 'stock_in')

                                                    <span class="movement-in">
                                                        Stock In
                                                    </span>

                                                @elseif($movement->movement_type === 'used_for_repair')

                                                    <span class="movement-out">
                                                        Used for Repair
                                                    </span>

                                                @elseif($movement->movement_type === 'return')

                                                    <span class="movement-in">
                                                        Return
                                                    </span>

                                                @else

                                                    <span class="movement-adjustment">
                                                        Adjustment
                                                    </span>

                                                @endif

                                            </td>


                                            <td>
                                                {{ number_format($movement->quantity) }}
                                            </td>


                                            <td>
                                                {{ number_format($movement->quantity_before) }}
                                            </td>


                                            <td>
                                                {{ number_format($movement->quantity_after) }}
                                            </td>


                                            <td>
                                                {{ $movement->reference ?: '—' }}
                                            </td>


                                            <td>
                                                {{ $movement->remarks ?: '—' }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-small">

                            <i class="bi bi-clock-history"></i>

                            No stock movements recorded yet.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Repair Usage --}}
            <div class="card">

                <div class="card-header">

                    <h3>
                        <i class="bi bi-wrench-adjustable"></i>
                        Repair Usage
                    </h3>

                    <small>
                        {{ $sparePart->repairSpareParts->count() }}
                        usage record(s)
                    </small>

                </div>

                <div class="card-body" style="padding:0;">

                    @if($sparePart->repairSpareParts->count())

                        <div class="table-responsive">

                            <table class="data-table">

                                <thead>

                                    <tr>

                                        <th>Repair Reference</th>

                                        <th>Asset</th>

                                        <th>Quantity Used</th>

                                        <th>Unit Price</th>

                                        <th>Total</th>

                                        <th>Date</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($sparePart->repairSpareParts as $usage)

                                        <tr>

                                            <td>

                                                @if($usage->repair)

                                                    <a
                                                        href="{{ route(
                                                            'admin.inventory.repairs.show',
                                                            $usage->repair
                                                        ) }}"
                                                        style="
                                                            color:#0d6efd;
                                                            font-weight:700;
                                                            text-decoration:none;
                                                        "
                                                    >
                                                        {{ $usage->repair->repair_reference }}
                                                    </a>

                                                @else

                                                    —

                                                @endif

                                            </td>


                                            <td>

                                                @if($usage->repair?->item)

                                                    {{ $usage->repair->item->asset_number }}

                                                @else

                                                    —

                                                @endif

                                            </td>


                                            <td>
                                                {{ number_format($usage->quantity_used) }}
                                            </td>


                                            <td>
                                                {{ number_format((float) $usage->unit_price, 2) }}
                                            </td>


                                            <td>
                                                {{ number_format((float) $usage->total_price, 2) }}
                                            </td>


                                            <td>

                                                {{ $usage->created_at?->format('Y-m-d') }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-small">

                            <i class="bi bi-wrench"></i>

                            This spare part has not been used in a repair yet.

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =================================================
             RIGHT COLUMN
        ================================================== --}}
        <div>

            {{-- Warranty --}}
            <div class="card">

                <div class="card-header">

                    <h3>
                        <i class="bi bi-shield-check"></i>
                        Warranty
                    </h3>

                </div>

                <div class="card-body">

                    <div class="warranty-box">

                        <div class="warranty-status">

                            <strong style="font-size:13px;">
                                Warranty Status
                            </strong>

                            @if($sparePart->warranty_status === 'Active')

                                <span class="status-badge badge-warranty">
                                    Active
                                </span>

                            @elseif($sparePart->warranty_status === 'Expiring Soon')

                                <span class="status-badge badge-expiring">
                                    Expiring Soon
                                </span>

                            @elseif($sparePart->warranty_status === 'Expired')

                                <span class="status-badge badge-expired">
                                    Expired
                                </span>

                            @else

                                <span class="status-badge badge-none">
                                    No Warranty
                                </span>

                            @endif

                        </div>


                        <div class="warranty-date">

                            <span>
                                Start Date
                            </span>

                            <span>
                                {{ $sparePart->warranty_start?->format('Y-m-d') ?? '—' }}
                            </span>

                        </div>


                        <div class="warranty-date">

                            <span>
                                End Date
                            </span>

                            <span>
                                {{ $sparePart->warranty_end?->format('Y-m-d') ?? '—' }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Supplier --}}
            <div class="card">

                <div class="card-header">

                    <h3>
                        <i class="bi bi-building"></i>
                        Supplier Information
                    </h3>

                </div>

                <div class="card-body">

                    <div class="info-row">

                        <span class="info-label">
                            Supplier
                        </span>

                        <span class="info-value">
                            {{ $sparePart->supplier_name ?: '—' }}
                        </span>

                    </div>


                    <div class="info-row">

                        <span class="info-label">
                            Reference
                        </span>

                        <span class="info-value">
                            {{ $sparePart->supplier_reference ?: '—' }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- Documents --}}
            <div class="card">

                <div class="card-header">

                    <h3>
                        <i class="bi bi-file-earmark-text"></i>
                        Documents
                    </h3>

                    <small>
                        {{ $sparePart->documents->count() }}
                        document(s)
                    </small>

                </div>

                <div class="card-body">

                    @if($sparePart->documents->count())

                        @foreach($sparePart->documents as $document)

                            <div class="document-row">

                                <div class="document-info">

                                    <div class="document-icon">
                                        <i class="bi bi-file-earmark"></i>
                                    </div>

                                    <div style="min-width:0;">

                                        <div
                                            class="document-name"
                                            title="{{ $document->original_filename }}"
                                        >
                                            {{ $document->document_name }}
                                        </div>

                                        <div class="document-meta">

                                            {{ strtoupper($document->document_type) }}

                                            @if($document->file_size)
                                                ·
                                                {{ number_format($document->file_size / 1024, 1) }}
                                                KB
                                            @endif

                                        </div>

                                    </div>

                                </div>


                                @if($document->file_path)

                                    <a
                                        href="{{ asset('storage/' . $document->file_path) }}"
                                        target="_blank"
                                        class="document-link"
                                    >
                                        <i class="bi bi-box-arrow-up-right"></i>
                                        Open
                                    </a>

                                @endif

                            </div>

                        @endforeach

                    @else

                        <div class="empty-small">

                            <i class="bi bi-file-earmark"></i>

                            No documents uploaded yet.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Record Information --}}
            <div class="card">

                <div class="card-header">

                    <h3>
                        <i class="bi bi-database"></i>
                        Record Information
                    </h3>

                </div>

                <div class="card-body">

                    <div class="info-row">

                        <span class="info-label">
                            Record ID
                        </span>

                        <span class="info-value">
                            #{{ $sparePart->id }}
                        </span>

                    </div>


                    <div class="info-row">

                        <span class="info-label">
                            Created
                        </span>

                        <span class="info-value">
                            {{ $sparePart->created_at?->format('Y-m-d H:i') }}
                        </span>

                    </div>


                    <div class="info-row">

                        <span class="info-label">
                            Last Updated
                        </span>

                        <span class="info-value">
                            {{ $sparePart->updated_at?->format('Y-m-d H:i') }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection