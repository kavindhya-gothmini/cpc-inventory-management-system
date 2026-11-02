@extends('layouts.admin')

@section('title', 'Inventory Summary Report | CPC Inventory Management System')

@section('content')

<div class="container-fluid py-3">


{{-- ============================================================
    PAGE HEADER
============================================================ --}}
<div class="report-page-header mb-4">

    <div class="d-flex flex-column flex-lg-row
                justify-content-between
                align-items-lg-center
                gap-3">

        <div class="d-flex align-items-start gap-3">

            <a href="{{ route('admin.reports.index') }}"
               class="back-btn"
               title="Back to Reports"
               aria-label="Back to Reports">

                <i class="bi bi-arrow-left"></i>

            </a>

            <div class="page-title-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>

                <div class="d-flex align-items-center gap-2 flex-wrap">

                    <h1 class="page-title mb-0">
                        Inventory Summary Report
                    </h1>

                    <span class="report-type-badge">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i>
                        Inventory
                    </span>

                </div>

                <p class="page-description mb-0 mt-1">
                    Generate, review and export CPC inventory records
                    using flexible report filters.
                </p>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
    FILTER CARD
============================================================ --}}
<div class="card report-card border-0 shadow-sm mb-4">

    <div class="card-header report-card-header bg-white border-0">

        <div class="d-flex align-items-center gap-3">

            <div class="section-icon filter-section-icon">
                <i class="bi bi-funnel-fill"></i>
            </div>

            <div>

                <h5 class="section-title mb-1">
                    Report Filters
                </h5>

                <p class="section-description mb-0">
                    Select the criteria required for this inventory report.
                </p>

            </div>

        </div>

    </div>


    <div class="card-body px-4 pb-4">

        <form method="GET"
              action="{{ route('admin.reports.inventory.summary') }}">

            <div class="row g-3">

                {{-- Branch --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label for="branch_id"
                           class="form-label report-label">
                        Branch
                    </label>

                    <select name="branch_id"
                            id="branch_id"
                            class="form-select report-control">

                        <option value="">
                            All Branches
                        </option>

                        @foreach($branches as $branch)

                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>

                                {{ $branch->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Department --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label for="department_id"
                           class="form-label report-label">
                        Department
                    </label>

                    <select name="department_id"
                            id="department_id"
                            class="form-select report-control">

                        <option value="">
                            All Departments
                        </option>

                        @foreach($departments as $department)

                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>

                                {{ $department->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Item Type --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label for="item_type_id"
                           class="form-label report-label">
                        Item Type
                    </label>

                    <select name="item_type_id"
                            id="item_type_id"
                            class="form-select report-control">

                        <option value="">
                            All Item Types
                        </option>

                        @foreach($itemTypes as $itemType)

                            <option value="{{ $itemType->id }}"
                                {{ request('item_type_id') == $itemType->id ? 'selected' : '' }}>

                                {{ $itemType->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Status --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label for="status"
                           class="form-label report-label">
                        Status
                    </label>

                    <select name="status"
                            id="status"
                            class="form-select report-control">

                        <option value="">
                            All Statuses
                        </option>

                        <option value="available"
                            {{ request('status') === 'available' ? 'selected' : '' }}>
                            Available
                        </option>

                        <option value="in_use"
                            {{ request('status') === 'in_use' ? 'selected' : '' }}>
                            In Use
                        </option>

                        <option value="repair"
                            {{ request('status') === 'repair' ? 'selected' : '' }}>
                            Under Repair
                        </option>

                        <option value="rdd"
                            {{ request('status') === 'rdd' ? 'selected' : '' }}>
                            RDD
                        </option>

                    </select>

                </div>


                {{-- From Date --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label for="from_date"
                           class="form-label report-label">
                        From Date
                    </label>

                    <div class="input-icon-wrapper">

                        <i class="bi bi-calendar3"></i>

                        <input type="date"
                               name="from_date"
                               id="from_date"
                               class="form-control report-control input-with-icon"
                               value="{{ request('from_date') }}">

                    </div>

                </div>


                {{-- To Date --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label for="to_date"
                           class="form-label report-label">
                        To Date
                    </label>

                    <div class="input-icon-wrapper">

                        <i class="bi bi-calendar3"></i>

                        <input type="date"
                               name="to_date"
                               id="to_date"
                               class="form-control report-control input-with-icon"
                               value="{{ request('to_date') }}">

                    </div>

                </div>


                {{-- Filter Actions --}}
                <div class="col-12 col-xl-6">

                    <div class="filter-actions
                                d-flex flex-column flex-sm-row
                                align-items-stretch
                                align-items-sm-end
                                gap-2 h-100">

                        <button type="submit"
                                class="btn btn-primary generate-btn">

                            <i class="bi bi-bar-chart-line me-2"></i>
                            Generate Report

                        </button>

                        <a href="{{ route('admin.reports.inventory.summary') }}"
                           class="btn btn-light border reset-btn">

                            <i class="bi bi-arrow-counterclockwise me-2"></i>
                            Reset Filters

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- ============================================================
    REPORT SUMMARY
============================================================ --}}
<div class="d-flex align-items-center gap-2 mb-3">

    <div class="mini-section-icon">
        <i class="bi bi-speedometer2"></i>
    </div>

    <div>

        <h6 class="fw-bold mb-0">
            Inventory Overview
        </h6>

        <small class="text-muted">
            Current results based on the selected filters.
        </small>

    </div>

</div>


<div class="row g-3 mb-4">

    {{-- Total Assets --}}
    <div class="col-12 col-sm-6 col-xl-3">

        <div class="summary-card total-card h-100">

            <div class="summary-card-body">

                <div>

                    <span class="summary-label">
                        Total Assets
                    </span>

                    <div class="summary-value">
                        {{ number_format($totalAssets) }}
                    </div>

                    <span class="summary-meta">
                        Inventory records
                    </span>

                </div>

                <div class="summary-icon
                            summary-icon-primary">

                    <i class="bi bi-box-seam"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- In Use --}}
    <div class="col-12 col-sm-6 col-xl-3">

        <div class="summary-card h-100">

            <div class="summary-card-body">

                <div>

                    <span class="summary-label">
                        In Use
                    </span>

                    <div class="summary-value">
                        {{ number_format($inUse) }}
                    </div>

                    <span class="summary-meta">
                        Currently assigned
                    </span>

                </div>

                <div class="summary-icon
                            summary-icon-success">

                    <i class="bi bi-person-check"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- Available --}}
    <div class="col-12 col-sm-6 col-xl-3">

        <div class="summary-card h-100">

            <div class="summary-card-body">

                <div>

                    <span class="summary-label">
                        Available
                    </span>

                    <div class="summary-value">
                        {{ number_format($available) }}
                    </div>

                    <span class="summary-meta">
                        Ready for assignment
                    </span>

                </div>

                <div class="summary-icon
                            summary-icon-info">

                    <i class="bi bi-check-circle"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- Under Repair --}}
    <div class="col-12 col-sm-6 col-xl-3">

        <div class="summary-card h-100">

            <div class="summary-card-body">

                <div>

                    <span class="summary-label">
                        Under Repair
                    </span>

                    <div class="summary-value">
                        {{ number_format($underRepair) }}
                    </div>

                    <span class="summary-meta">
                        Currently under repair
                    </span>

                </div>

                <div class="summary-icon
                            summary-icon-warning">

                    <i class="bi bi-tools"></i>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
    RDD SUMMARY
============================================================ --}}
@if(isset($rdd))

    <div class="rdd-summary-card mb-4">

        <div class="d-flex align-items-center
                    justify-content-between
                    gap-3">

            <div class="d-flex align-items-center gap-3">

                <div class="rdd-icon">

                    <i class="bi bi-exclamation-octagon"></i>

                </div>

                <div>

                    <div class="fw-bold text-dark">
                        RDD Assets
                    </div>

                    <div class="small text-muted">
                        Assets currently marked for
                        Repair / Disposal / Decommissioning.
                    </div>

                </div>

            </div>

            <div class="rdd-value">
                {{ number_format($rdd) }}
            </div>

        </div>

    </div>

@endif


{{-- ============================================================
    RESULTS CARD
============================================================ --}}
<div class="card report-card results-card border-0 shadow-sm">

    {{-- Results Header --}}
    <div class="card-header bg-white border-0 p-4">

        <div class="d-flex flex-column flex-xl-row
                    justify-content-between
                    align-items-xl-center
                    gap-3">

            <div class="d-flex align-items-center gap-3">

                <div class="section-icon results-section-icon">

                    <i class="bi bi-table"></i>

                </div>

                <div>

                    <div class="d-flex align-items-center
                                gap-2 flex-wrap">

                        <h5 class="section-title mb-0">
                            Inventory Results
                        </h5>

                        <span class="record-count-badge">
                            {{ number_format($items->total()) }}
                        </span>

                    </div>

                    <small class="text-muted">
                        Inventory record{{ $items->total() == 1 ? '' : 's' }}
                        matching the current report criteria.
                    </small>

                </div>

            </div>


            {{-- ====================================================
                REPORT ACTIONS
            ===================================================== --}}
            <div class="report-actions-wrapper">

                <div class="report-actions-label">

                    <i class="bi bi-download me-1"></i>
                    Export Report

                </div>

                <div class="report-export-actions">

                    {{-- Print --}}
                    <a href="{{ route('admin.reports.inventory.print', request()->query()) }}"
                       target="_blank"
                       class="report-action-btn report-action-print"
                       title="Print inventory report">

                        <span class="report-action-icon">
                            <i class="bi bi-printer"></i>
                        </span>

                        <span>
                            <strong>Print</strong>
                            <small>Print report</small>
                        </span>

                    </a>


                    {{-- PDF --}}
                    <a href="{{ route('admin.reports.inventory.pdf', request()->query()) }}"
                       class="report-action-btn report-action-pdf"
                       title="Download inventory report as PDF">

                        <span class="report-action-icon">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </span>

                        <span>
                            <strong>PDF</strong>
                            <small>Download PDF</small>
                        </span>

                    </a>


                    {{-- Excel --}}
                    <a href="{{ route('admin.reports.inventory.excel', request()->query()) }}"
                       class="report-action-btn report-action-excel"
                       title="Download inventory report as Excel">

                        <span class="report-action-icon">
                            <i class="bi bi-file-earmark-excel"></i>
                        </span>

                        <span>
                            <strong>Excel</strong>
                            <small>Download XLSX</small>
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================
        ACTIVE FILTER SUMMARY
    ========================================================= --}}
    @if(
        request()->filled('branch_id') ||
        request()->filled('department_id') ||
        request()->filled('item_type_id') ||
        request()->filled('status') ||
        request()->filled('from_date') ||
        request()->filled('to_date')
    )

        <div class="px-4 pb-3">

            <div class="active-filter-box">

                <div class="d-flex align-items-center
                            justify-content-between
                            gap-2 mb-2">

                    <div class="d-flex align-items-center gap-2">

                        <div class="active-filter-icon">
                            <i class="bi bi-funnel"></i>
                        </div>

                        <span class="fw-bold small">
                            Active Filters
                        </span>

                    </div>

                    <span class="small text-muted">
                        Report scope
                    </span>

                </div>


                <div class="d-flex flex-wrap gap-2">

                    {{-- Branch --}}
                    @if(request('branch_id'))

                        @php
                            $selectedBranch = $branches->firstWhere(
                                'id',
                                request('branch_id')
                            );
                        @endphp

                        @if($selectedBranch)

                            <span class="filter-badge">
                                <i class="bi bi-building me-1"></i>
                                <span>Branch:</span>
                                {{ $selectedBranch->name }}
                            </span>

                        @endif

                    @endif


                    {{-- Department --}}
                    @if(request('department_id'))

                        @php
                            $selectedDepartment = $departments->firstWhere(
                                'id',
                                request('department_id')
                            );
                        @endphp

                        @if($selectedDepartment)

                            <span class="filter-badge">
                                <i class="bi bi-diagram-3 me-1"></i>
                                <span>Department:</span>
                                {{ $selectedDepartment->name }}
                            </span>

                        @endif

                    @endif


                    {{-- Item Type --}}
                    @if(request('item_type_id'))

                        @php
                            $selectedItemType = $itemTypes->firstWhere(
                                'id',
                                request('item_type_id')
                            );
                        @endphp

                        @if($selectedItemType)

                            <span class="filter-badge">
                                <i class="bi bi-box me-1"></i>
                                <span>Type:</span>
                                {{ $selectedItemType->name }}
                            </span>

                        @endif

                    @endif


                    {{-- Status --}}
                    @if(request('status'))

                        @php
                            $statusLabels = [
                                'available' => 'Available',
                                'in_use' => 'In Use',
                                'repair' => 'Under Repair',
                                'rdd' => 'RDD',
                            ];
                        @endphp

                        <span class="filter-badge">

                            <i class="bi bi-circle-half me-1"></i>

                            <span>Status:</span>
                            {{ $statusLabels[request('status')] ?? ucfirst(str_replace('_', ' ', request('status'))) }}

                        </span>

                    @endif


                    {{-- From Date --}}
                    @if(request('from_date'))

                        <span class="filter-badge">

                            <i class="bi bi-calendar-event me-1"></i>

                            <span>From:</span>
                            {{ \Carbon\Carbon::parse(request('from_date'))->format('d M Y') }}

                        </span>

                    @endif


                    {{-- To Date --}}
                    @if(request('to_date'))

                        <span class="filter-badge">

                            <i class="bi bi-calendar-event me-1"></i>

                            <span>To:</span>
                            {{ \Carbon\Carbon::parse(request('to_date'))->format('d M Y') }}

                        </span>

                    @endif

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================
        RESULTS TABLE
    ========================================================= --}}
    <div class="table-responsive inventory-table-wrapper">

        <table class="table table-hover align-middle mb-0 inventory-table">

            <thead>

                <tr>

                    <th>
                        Asset Number
                    </th>

                    <th>
                        Item Type
                    </th>

                    <th>
                        Make / Model
                    </th>

                    <th>
                        Serial Number
                    </th>

                    <th>
                        Branch
                    </th>

                    <th>
                        Department
                    </th>

                    <th>
                        Employee
                    </th>

                    <th>
                        Status
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($items as $item)

                    <tr>

                        {{-- Asset Number --}}
                        <td>

                            <span class="asset-number">
                                {{ $item->asset_number ?? '—' }}
                            </span>

                        </td>


                        {{-- Item Type --}}
                        <td>

                            <span class="item-type-name">
                                {{ optional($item->itemType)->name ?? '—' }}
                            </span>

                        </td>


                        {{-- Make / Model --}}
                        <td>

                            @if($item->make)

                                <div class="make-name">
                                    {{ $item->make }}
                                </div>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                            @if($item->model)

                                <div class="model-name">
                                    {{ $item->model }}
                                </div>

                            @endif

                        </td>


                        {{-- Serial Number --}}
                        <td>

                            @if($item->serial_number)

                                <span class="serial-number">
                                    {{ $item->serial_number }}
                                </span>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Branch --}}
                        <td>

                            <span class="table-primary-text">
                                {{ optional($item->branch)->name ?? '—' }}
                            </span>

                        </td>


                        {{-- Department --}}
                        <td>

                            <span class="table-primary-text">
                                {{ optional($item->department)->name ?? '—' }}
                            </span>

                        </td>


                        {{-- Employee --}}
                        <td>

                            @if($item->currentEmployee)

                                <div class="employee-cell">

                                    <div class="employee-avatar">
                                        <i class="bi bi-person"></i>
                                    </div>

                                    <span class="employee-name">
                                        {{ $item->currentEmployee->name }}
                                    </span>

                                </div>

                            @else

                                <span class="unassigned-label">
                                    <i class="bi bi-person-x me-1"></i>
                                    Unassigned
                                </span>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            @php

                                $statusConfig = match($item->status) {

                                    'available' => [
                                        'label' => 'Available',
                                        'class' => 'status-available',
                                        'icon' => 'bi-check-circle'
                                    ],

                                    'in_use' => [
                                        'label' => 'In Use',
                                        'class' => 'status-in-use',
                                        'icon' => 'bi-person-check'
                                    ],

                                    'repair' => [
                                        'label' => 'Under Repair',
                                        'class' => 'status-repair',
                                        'icon' => 'bi-tools'
                                    ],

                                    'rdd' => [
                                        'label' => 'RDD',
                                        'class' => 'status-rdd',
                                        'icon' => 'bi-exclamation-octagon'
                                    ],

                                    default => [
                                        'label' => ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $item->status ?? 'Unknown'
                                            )
                                        ),
                                        'class' => 'status-default',
                                        'icon' => 'bi-question-circle'
                                    ],

                                };

                            @endphp


                            <span class="status-badge {{ $statusConfig['class'] }}">

                                <i class="bi {{ $statusConfig['icon'] }}"></i>

                                {{ $statusConfig['label'] }}

                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8"
                            class="empty-state-cell">

                            <div class="empty-report-icon mb-3">

                                <i class="bi bi-inbox"></i>

                            </div>

                            <h6 class="fw-bold mb-1">
                                No Inventory Records Found
                            </h6>

                            <p class="text-muted small mb-3">
                                No inventory records match the selected
                                report criteria.
                            </p>

                            <a href="{{ route('admin.reports.inventory.summary') }}"
                               class="btn btn-light border btn-sm reset-empty-btn">

                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Clear Filters

                            </a>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- ========================================================
        PAGINATION
    ========================================================= --}}
    @if($items->hasPages())

        <div class="card-footer pagination-footer bg-white border-0 px-4 py-3">

            <div class="d-flex flex-column flex-md-row
                        justify-content-between
                        align-items-md-center
                        gap-3">

                <small class="text-muted">

                    Showing
                    <strong>{{ $items->firstItem() }}</strong>
                    to
                    <strong>{{ $items->lastItem() }}</strong>
                    of
                    <strong>{{ $items->total() }}</strong>
                    records

                </small>

                <div>
                    {{ $items->links() }}
                </div>

            </div>

        </div>

    @endif

</div>


</div>

{{-- ================================================================
PAGE STYLES
================================================================ --}}
@push('styles')

<style>

/* ============================================================
   PAGE HEADER
============================================================ */

.report-page-header {
    padding: 2px 0;
}

.back-btn {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    color: #495057;
    text-decoration: none;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.back-btn:hover {
    color: #0d6efd;
    border-color: rgba(13, 110, 253, 0.25);
    background: rgba(13, 110, 253, 0.04);
    transform: translateX(-2px);
}

.page-title-icon {
    width: 46px;
    height: 46px;
    border-radius: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(13, 110, 253, 0.10);
    color: #0d6efd;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.page-title {
    font-size: 1.55rem;
    font-weight: 750;
    color: #212529;
    letter-spacing: -0.02em;
}

.page-description {
    color: #6c757d;
    font-size: 0.88rem;
}

.report-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 20px;
    background: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    font-size: 0.70rem;
    font-weight: 700;
    letter-spacing: 0.02em;
}


/* ============================================================
   COMMON CARDS
============================================================ */

.report-card {
    border-radius: 14px;
    overflow: hidden;
}

.report-card-header {
    padding: 20px 24px 14px;
}

.section-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.filter-section-icon {
    background: rgba(13, 110, 253, 0.10);
    color: #0d6efd;
}

.results-section-icon {
    background: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
}

.section-title {
    color: #212529;
    font-size: 1rem;
    font-weight: 750;
}

.section-description {
    color: #6c757d;
    font-size: 0.78rem;
}


/* ============================================================
   FILTERS
============================================================ */

.report-label {
    color: #343a40;
    font-size: 0.80rem;
    font-weight: 700;
    margin-bottom: 7px;
}

.report-control {
    min-height: 44px;
    border-radius: 9px;
    border-color: #dee2e6;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.report-control:focus {
    border-color: rgba(13, 110, 253, 0.50);
    box-shadow: 0 0 0 0.20rem rgba(13, 110, 253, 0.10);
}

.input-icon-wrapper {
    position: relative;
}

.input-icon-wrapper > i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 0.88rem;
    z-index: 2;
    pointer-events: none;
}

.input-with-icon {
    padding-left: 36px;
}

.generate-btn,
.reset-btn {
    min-height: 44px;
    border-radius: 9px;
    font-size: 0.85rem;
    font-weight: 700;
}

.generate-btn {
    padding-left: 22px;
    padding-right: 22px;
}

.reset-btn {
    padding-left: 20px;
    padding-right: 20px;
}


/* ============================================================
   MINI SECTION HEADING
============================================================ */

.mini-section-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    font-size: 0.85rem;
}


/* ============================================================
   SUMMARY CARDS
============================================================ */

.summary-card {
    background: #ffffff;
    border: 1px solid #edf0f2;
    border-radius: 14px;
    box-shadow: 0 0.15rem 0.75rem rgba(0, 0, 0, 0.045);
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        border-color 0.2s ease;
}

.summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.55rem 1.4rem rgba(0, 0, 0, 0.08);
    border-color: #e2e6ea;
}

.summary-card-body {
    min-height: 135px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.summary-label {
    display: block;
    color: #6c757d;
    font-size: 0.76rem;
    font-weight: 700;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.summary-value {
    color: #212529;
    font-size: 1.75rem;
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.summary-meta {
    display: block;
    color: #8a9198;
    font-size: 0.70rem;
    margin-top: 5px;
}

.summary-icon {
    width: 50px;
    height: 50px;
    border-radius: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.summary-icon-primary {
    background: rgba(13, 110, 253, 0.10);
    color: #0d6efd;
}

.summary-icon-success {
    background: rgba(25, 135, 84, 0.10);
    color: #198754;
}

.summary-icon-info {
    background: rgba(13, 202, 240, 0.10);
    color: #0dcaf0;
}

.summary-icon-warning {
    background: rgba(255, 193, 7, 0.13);
    color: #d39e00;
}


/* ============================================================
   RDD SUMMARY
============================================================ */

.rdd-summary-card {
    background: #ffffff;
    border: 1px solid rgba(220, 53, 69, 0.15);
    border-left: 4px solid #dc3545;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 0.15rem 0.75rem rgba(0, 0, 0, 0.035);
}

.rdd-icon {
    width: 44px;
    height: 44px;
    border-radius: 11px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(220, 53, 69, 0.10);
    color: #dc3545;
    flex-shrink: 0;
}

.rdd-value {
    color: #dc3545;
    font-size: 1.45rem;
    font-weight: 800;
}


/* ============================================================
   RESULTS HEADER
============================================================ */

.results-card {
    overflow: hidden;
}

.record-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    min-height: 22px;
    padding: 3px 8px;
    border-radius: 20px;
    background: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    font-size: 0.68rem;
    font-weight: 800;
}


/* ============================================================
   EXPORT ACTIONS
============================================================ */

.report-actions-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 7px;
    flex-shrink: 0;
}

.report-actions-label {
    color: #6c757d;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.045em;
}

.report-export-actions {
    display: flex;
    align-items: stretch;
    gap: 8px;
}

.report-action-btn {
    min-width: 112px;
    min-height: 52px;
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 7px 11px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    background: #ffffff;
    color: #343a40;
    text-decoration: none;
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        border-color 0.2s ease,
        background-color 0.2s ease;
}

.report-action-btn:hover {
    transform: translateY(-2px);
    color: #212529;
    box-shadow: 0 0.35rem 0.9rem rgba(0, 0, 0, 0.07);
}

.report-action-btn strong {
    display: block;
    font-size: 0.78rem;
    line-height: 1.15;
}

.report-action-btn small {
    display: block;
    margin-top: 2px;
    color: #8a9198;
    font-size: 0.62rem;
    white-space: nowrap;
}

.report-action-icon {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.report-action-print:hover {
    border-color: rgba(108, 117, 125, 0.35);
}

.report-action-print .report-action-icon {
    background: rgba(108, 117, 125, 0.10);
    color: #6c757d;
}

.report-action-pdf:hover {
    border-color: rgba(220, 53, 69, 0.30);
}

.report-action-pdf .report-action-icon {
    background: rgba(220, 53, 69, 0.10);
    color: #dc3545;
}

.report-action-excel:hover {
    border-color: rgba(25, 135, 84, 0.30);
}

.report-action-excel .report-action-icon {
    background: rgba(25, 135, 84, 0.10);
    color: #198754;
}


/* ============================================================
   ACTIVE FILTERS
============================================================ */

.active-filter-box {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 11px;
    padding: 13px 15px;
}

.active-filter-icon {
    width: 25px;
    height: 25px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: rgba(13, 110, 253, 0.09);
    color: #0d6efd;
    font-size: 0.72rem;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    background: #ffffff;
    border: 1px solid #dee2e6;
    color: #495057;
    border-radius: 20px;
    padding: 6px 10px;
    font-size: 0.72rem;
    font-weight: 600;
}

.filter-badge span {
    color: #6c757d;
    font-weight: 700;
}


/* ============================================================
   TABLE
============================================================ */

.inventory-table-wrapper {
    border-top: 1px solid #f0f2f4;
}

.inventory-table {
    min-width: 1050px;
}

.inventory-table thead th {
    padding: 13px 16px;
    background: #f8f9fa;
    color: #6c757d;
    border-bottom: 1px solid #e9ecef;
    font-size: 0.70rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.035em;
    white-space: nowrap;
}

.inventory-table tbody td {
    padding: 14px 16px;
    border-color: #f1f3f5;
    color: #495057;
    font-size: 0.82rem;
    vertical-align: middle;
}

.inventory-table tbody tr {
    transition: background-color 0.15s ease;
}

.inventory-table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.018);
}

.inventory-table tbody tr:last-child td {
    border-bottom: 0;
}

.asset-number {
    display: inline-block;
    color: #212529;
    font-weight: 750;
    white-space: nowrap;
}

.item-type-name,
.table-primary-text {
    color: #343a40;
    font-weight: 600;
}

.make-name {
    color: #343a40;
    font-weight: 650;
}

.model-name {
    margin-top: 2px;
    color: #8a9198;
    font-size: 0.72rem;
}

.serial-number {
    color: #495057;
    font-family: "SFMono-Regular", Consolas, "Liberation Mono", monospace;
    font-size: 0.76rem;
    white-space: nowrap;
}


/* ============================================================
   EMPLOYEE
============================================================ */

.employee-cell {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 145px;
}

.employee-avatar {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    font-size: 0.76rem;
    flex-shrink: 0;
}

.employee-name {
    color: #343a40;
    font-weight: 600;
    white-space: nowrap;
}

.unassigned-label {
    display: inline-flex;
    align-items: center;
    color: #8a9198;
    font-size: 0.76rem;
    font-weight: 650;
    white-space: nowrap;
}


/* ============================================================
   STATUS
============================================================ */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 0.68rem;
    font-weight: 750;
    white-space: nowrap;
}

.status-available {
    background: rgba(25, 135, 84, 0.10);
    color: #198754;
}

.status-in-use {
    background: rgba(13, 110, 253, 0.10);
    color: #0d6efd;
}

.status-repair {
    background: rgba(255, 193, 7, 0.14);
    color: #b58100;
}

.status-rdd {
    background: rgba(220, 53, 69, 0.10);
    color: #dc3545;
}

.status-default {
    background: rgba(108, 117, 125, 0.10);
    color: #6c757d;
}


/* ============================================================
   EMPTY STATE
============================================================ */

.empty-state-cell {
    padding: 70px 20px !important;
    text-align: center;
}

.empty-report-icon {
    width: 64px;
    height: 64px;
    margin-left: auto;
    margin-right: auto;
    border-radius: 17px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    font-size: 1.55rem;
}

.reset-empty-btn {
    border-radius: 8px;
    font-weight: 650;
}


/* ============================================================
   PAGINATION
============================================================ */

.pagination-footer {
    border-top: 1px solid #f0f2f4 !important;
}

.pagination {
    margin-bottom: 0;
}


/* ============================================================
   GENERAL
============================================================ */

.btn {
    border-radius: 9px;
    font-weight: 650;
}

a,
button {
    -webkit-tap-highlight-color: transparent;
}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 1199.98px) {

    .report-actions-wrapper {
        align-items: flex-start;
        width: 100%;
    }

    .report-export-actions {
        width: 100%;
    }

    .report-action-btn {
        flex: 1 1 0;
    }

}


@media (max-width: 767.98px) {

    .container-fluid {
        padding-left: 12px;
        padding-right: 12px;
    }

    .page-title {
        font-size: 1.30rem;
    }

    .page-description {
        font-size: 0.80rem;
    }

    .report-card-header {
        padding: 17px 16px 12px;
    }

    .report-card .card-body {
        padding-left: 16px !important;
        padding-right: 16px !important;
    }

    .summary-card-body {
        min-height: 120px;
        padding: 17px;
    }

    .summary-value {
        font-size: 1.55rem;
    }

    .summary-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
    }

    .rdd-summary-card {
        padding: 15px;
    }

    .report-actions-wrapper {
        align-items: stretch;
    }

    .report-export-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        width: 100%;
    }

    .report-action-btn {
        min-width: 0;
        width: 100%;
        justify-content: center;
        padding: 8px;
    }

    .report-action-btn small {
        display: none;
    }

    .report-action-icon {
        width: 31px;
        height: 31px;
    }

    .active-filter-box {
        padding: 12px;
    }

}


@media (max-width: 575.98px) {

    .page-title-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        font-size: 1rem;
    }

    .back-btn {
        width: 36px;
        height: 36px;
    }

    .report-type-badge {
        display: none;
    }

    .section-icon {
        width: 38px;
        height: 38px;
    }

    .section-title {
        font-size: 0.92rem;
    }

    .section-description {
        font-size: 0.72rem;
    }

    .filter-actions .btn {
        width: 100%;
    }

    .report-export-actions {
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
    }

    .report-action-btn {
        min-height: 48px;
        gap: 6px;
        padding: 6px;
    }

    .report-action-btn strong {
        font-size: 0.70rem;
    }

    .report-action-icon {
        width: 28px;
        height: 28px;
        font-size: 0.82rem;
    }

    .rdd-summary-card .d-flex {
        align-items: flex-start !important;
    }

}


</style>

@endpush

@endsection
