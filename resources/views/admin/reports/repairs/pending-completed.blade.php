@extends('layouts.admin')

@section('title', 'Pending & Completed Repair Report | CPC Inventory Management System')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Status Configuration
    |--------------------------------------------------------------------------
    */

    $statusLabels = [
        'pending' => 'Pending',
        'sent_for_repair' => 'Sent for Repair',
        'under_repair' => 'Under Repair',
        'completed' => 'Completed',
        'returned' => 'Returned',
        'cancelled' => 'Cancelled',
    ];

    $statusClasses = [
        'pending' => 'warning',
        'sent_for_repair' => 'info',
        'under_repair' => 'primary',
        'completed' => 'success',
        'returned' => 'success',
        'cancelled' => 'danger',
    ];


    /*
    |--------------------------------------------------------------------------
    | Pagination-Safe Collection
    |--------------------------------------------------------------------------
    */

    $repairRows = method_exists($repairs, 'getCollection')
        ? $repairs->getCollection()
        : collect($repairs);

    $isPaginated = method_exists($repairs, 'total');


    /*
    |--------------------------------------------------------------------------
    | Report Statistics
    |--------------------------------------------------------------------------
    */

    $totalCount = $totalRepairs ?? (
        $isPaginated
            ? $repairs->total()
            : $repairRows->count()
    );

    /*
    | Prefer controller-provided full totals.
    | Otherwise calculate from the available collection.
    */

    $pendingCount = $pendingRepairs ?? $repairRows
        ->whereIn('status', [
            'pending',
            'sent_for_repair',
            'under_repair',
        ])
        ->count();

    $completedCount = $completedRepairs ?? $repairRows
        ->whereIn('status', [
            'completed',
            'returned',
        ])
        ->count();

    $totalRepairCost = $totalRepairCost
        ?? $repairRows->sum(
            fn ($repair) => (float) ($repair->repair_cost ?? 0)
        );


    /*
    |--------------------------------------------------------------------------
    | Current Page Statistics
    |--------------------------------------------------------------------------
    */

    $pageAverageCost = $repairRows->count() > 0
        ? $repairRows->avg(
            fn ($repair) => (float) ($repair->repair_cost ?? 0)
        )
        : 0;

    $pageTurnaroundRecords = $repairRows->filter(
        fn ($repair) => $repair->date_sent && $repair->date_returned
    );

    $averageTurnaround = $pageTurnaroundRecords->count() > 0
        ? round(
            $pageTurnaroundRecords->avg(function ($repair) {
                return \Carbon\Carbon::parse($repair->date_sent)
                    ->diffInDays(
                        \Carbon\Carbon::parse($repair->date_returned)
                    );
            }),
            1
        )
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    $formatDate = function ($date) {
        if (!$date) {
            return '—';
        }

        try {
            return \Carbon\Carbon::parse($date)->format('d M Y');
        } catch (\Throwable $e) {
            return '—';
        }
    };

    $formatStatus = function ($status) use ($statusLabels) {
        return $statusLabels[$status]
            ?? ucwords(str_replace('_', ' ', $status ?? 'Unknown'));
    };

    $getStatusClass = function ($status) use ($statusClasses) {
        return $statusClasses[$status] ?? 'secondary';
    };

    $getFilterName = function ($collection, $id, $fallback) {
        if (!$id) {
            return $fallback;
        }

        $record = $collection?->firstWhere('id', $id);

        return $record?->name ?? $fallback;
    };


    /*
    |--------------------------------------------------------------------------
    | Pagination Information
    |--------------------------------------------------------------------------
    */

    $firstItem = $isPaginated
        ? ($repairs->firstItem() ?? 0)
        : ($repairRows->count() > 0 ? 1 : 0);

    $lastItem = $isPaginated
        ? ($repairs->lastItem() ?? 0)
        : $repairRows->count();

    $totalItems = $isPaginated
        ? $repairs->total()
        : $repairRows->count();


    /*
    |--------------------------------------------------------------------------
    | Export / Print Query
    |--------------------------------------------------------------------------
    */

    $reportQuery = request()->query();


    /*
    |--------------------------------------------------------------------------
    | Back Navigation
    |--------------------------------------------------------------------------
    |
    | Remove report_type so the user returns to the main Repair Reports page.
    |
    */

    $backQuery = request()->except('report_type');


    /*
    |--------------------------------------------------------------------------
    | Completion Ratio
    |--------------------------------------------------------------------------
    */

    $completionRatio = $totalCount > 0
        ? ($completedCount / $totalCount) * 100
        : 0;
@endphp


<div class="container-fluid px-3 px-lg-4 py-4 report-page">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="report-hero mb-4">

        <div class="row align-items-center g-4">

            {{-- Header Information --}}
            <div class="col-12 col-xl-7">

                <div class="d-flex align-items-start gap-3">

                    <div class="report-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>

                    <div class="flex-grow-1">

                        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">

                            <span class="report-eyebrow">
                                REPAIR MANAGEMENT
                            </span>

                            <span class="report-status-dot"></span>

                            <span class="report-context">
                                Operational Report
                            </span>

                        </div>

                        <h1 class="report-title mb-2">
                            Pending & Completed Repairs
                        </h1>

                        <p class="report-description mb-0">
                            Monitor repair progress, completed repairs, return status,
                            repair expenditure and turnaround performance.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                 REPORT ACTION TOOLBAR
            ==================================================== --}}
            <div class="col-12 col-xl-5">

                <div class="report-toolbar">

                    <div class="toolbar-label">
                        <i class="bi bi-box-arrow-up-right me-1"></i>
                        Report Actions
                    </div>

                    <div class="d-flex flex-wrap justify-content-xl-end gap-2">

                        {{-- Back --}}
                        <a href="{{ route('admin.reports.repairs.index', $backQuery) }}"
                           class="btn btn-light report-action-btn report-back-btn">

                            <i class="bi bi-arrow-left me-2"></i>
                            Repair Reports

                        </a>


                        {{-- Print --}}
                        <button type="button"
                                class="btn btn-primary report-action-btn report-primary-action"
                                onclick="window.print()">

                            <i class="bi bi-printer me-2"></i>
                            Print

                        </button>


                        {{-- PDF --}}
                        <a href="{{ route('admin.reports.repairs.pdf', $reportQuery) }}"
                           class="btn btn-primary report-action-btn report-primary-action">

                            <i class="bi bi-file-earmark-pdf me-2"></i>
                            PDF

                        </a>


                        {{-- Excel --}}
                        <a href="{{ route('admin.reports.repairs.excel', $reportQuery) }}"
                           class="btn btn-primary report-action-btn report-primary-action">

                            <i class="bi bi-file-earmark-excel me-2"></i>
                            Excel

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         REPORT OVERVIEW
    ============================================================ --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card stat-primary h-100">

                <div class="stat-card-body">

                    <div class="stat-content">

                        <div class="stat-label">
                            TOTAL REPAIRS
                        </div>

                        <div class="stat-value">
                            {{ number_format($totalCount) }}
                        </div>

                        <div class="stat-description">
                            Matching current filters
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-tools"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card stat-warning h-100">

                <div class="stat-card-body">

                    <div class="stat-content">

                        <div class="stat-label">
                            PENDING / IN REPAIR
                        </div>

                        <div class="stat-value">
                            {{ number_format($pendingCount) }}
                        </div>

                        <div class="stat-description">
                            Awaiting completion
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card stat-success h-100">

                <div class="stat-card-body">

                    <div class="stat-content">

                        <div class="stat-label">
                            COMPLETED / RETURNED
                        </div>

                        <div class="stat-value">
                            {{ number_format($completedCount) }}
                        </div>

                        <div class="stat-description">
                            Successfully completed
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Cost --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card stat-dark h-100">

                <div class="stat-card-body">

                    <div class="stat-content">

                        <div class="stat-label">
                            TOTAL REPAIR COST
                        </div>

                        <div class="stat-value stat-money">
                            {{ number_format((float) $totalRepairCost, 2) }}
                        </div>

                        <div class="stat-description">
                            Across matching records
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         REPORT CRITERIA
    ============================================================ --}}
    <div class="card report-card mb-4">

        <div class="card-body p-0">

            <div class="report-section-header">

                <div class="d-flex align-items-center gap-3">

                    <div class="section-icon">
                        <i class="bi bi-funnel"></i>
                    </div>

                    <div>

                        <h5 class="section-title mb-0">
                            Report Criteria
                        </h5>

                        <div class="section-subtitle">
                            Filters and parameters applied to this report
                        </div>

                    </div>

                </div>

                <span class="criteria-badge">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Report Ready
                </span>

            </div>


            <div class="p-4">

                <div class="row g-3">

                    {{-- Report Type --}}
                    <div class="col-12 col-sm-6 col-lg-3">

                        <div class="criteria-item">

                            <div class="criteria-label">
                                Report Type
                            </div>

                            <div class="criteria-value">

                                <span class="criteria-highlight">
                                    <i class="bi bi-bar-chart-line me-2"></i>
                                    Pending & Completed
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-12 col-sm-6 col-lg-3">

                        <div class="criteria-item">

                            <div class="criteria-label">
                                Status
                            </div>

                            <div class="criteria-value">

                                @if($status)

                                    <span class="mini-status status-{{ $getStatusClass($status) }}">
                                        {{ $formatStatus($status) }}
                                    </span>

                                @else

                                    <span class="criteria-muted">
                                        All Relevant Statuses
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Employee --}}
                    <div class="col-12 col-sm-6 col-lg-3">

                        <div class="criteria-item">

                            <div class="criteria-label">
                                Employee
                            </div>

                            <div class="criteria-value">

                                <i class="bi bi-person me-2 text-primary"></i>

                                {{ $getFilterName(
                                    $employees,
                                    $employeeId,
                                    'All Employees'
                                ) }}

                            </div>

                        </div>

                    </div>


                    {{-- Branch --}}
                    <div class="col-12 col-sm-6 col-lg-3">

                        <div class="criteria-item">

                            <div class="criteria-label">
                                Branch
                            </div>

                            <div class="criteria-value">

                                <i class="bi bi-building me-2 text-primary"></i>

                                {{ $getFilterName(
                                    $branches,
                                    $branchId,
                                    'All Branches'
                                ) }}

                            </div>

                        </div>

                    </div>


                    {{-- Department --}}
                    <div class="col-12 col-sm-6 col-lg-3">

                        <div class="criteria-item">

                            <div class="criteria-label">
                                Department
                            </div>

                            <div class="criteria-value">

                                <i class="bi bi-diagram-3 me-2 text-primary"></i>

                                {{ $getFilterName(
                                    $departments,
                                    $departmentId,
                                    'All Departments'
                                ) }}

                            </div>

                        </div>

                    </div>


                    {{-- Item Type --}}
                    <div class="col-12 col-sm-6 col-lg-3">

                        <div class="criteria-item">

                            <div class="criteria-label">
                                Item Type
                            </div>

                            <div class="criteria-value">

                                <i class="bi bi-pc-display me-2 text-primary"></i>

                                {{ $getFilterName(
                                    $itemTypes,
                                    $itemTypeId,
                                    'All Item Types'
                                ) }}

                            </div>

                        </div>

                    </div>


                    {{-- From Date --}}
                    <div class="col-12 col-sm-6 col-lg-3">

                        <div class="criteria-item">

                            <div class="criteria-label">
                                From Date
                            </div>

                            <div class="criteria-value">

                                <i class="bi bi-calendar-event me-2 text-primary"></i>

                                {{ $fromDate
                                    ? \Carbon\Carbon::parse($fromDate)->format('d M Y')
                                    : 'All Dates' }}

                            </div>

                        </div>

                    </div>


                    {{-- To Date --}}
                    <div class="col-12 col-sm-6 col-lg-3">

                        <div class="criteria-item">

                            <div class="criteria-label">
                                To Date
                            </div>

                            <div class="criteria-value">

                                <i class="bi bi-calendar-check me-2 text-primary"></i>

                                {{ $toDate
                                    ? \Carbon\Carbon::parse($toDate)->format('d M Y')
                                    : 'All Dates' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         PERFORMANCE SUMMARY
    ============================================================ --}}
    <div class="row g-3 mb-4">

        {{-- Completion Ratio --}}
        <div class="col-12 col-md-4">

            <div class="mini-summary-card">

                <div class="mini-summary-icon summary-blue">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

                <div class="flex-grow-1">

                    <div class="mini-summary-label">
                        Completion Ratio
                    </div>

                    <div class="mini-summary-value">
                        {{ number_format($completionRatio, 1) }}%
                    </div>

                    <div class="mini-summary-progress">

                        <div class="mini-summary-progress-bar"
                             style="width: {{ min(100, max(0, $completionRatio)) }}%;">
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Average Turnaround --}}
        <div class="col-12 col-md-4">

            <div class="mini-summary-card">

                <div class="mini-summary-icon summary-info">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>

                    <div class="mini-summary-label">
                        Average Turnaround
                    </div>

                    <div class="mini-summary-value">

                        {{ number_format($averageTurnaround, 1) }}

                        <span>
                            days
                        </span>

                    </div>

                    <div class="mini-summary-note">
                        Based on returned repairs
                    </div>

                </div>

            </div>

        </div>


        {{-- Average Cost --}}
        <div class="col-12 col-md-4">

            <div class="mini-summary-card">

                <div class="mini-summary-icon summary-secondary">
                    <i class="bi bi-calculator"></i>
                </div>

                <div>

                    <div class="mini-summary-label">
                        Average Repair Cost
                    </div>

                    <div class="mini-summary-value">
                        {{ number_format((float) $pageAverageCost, 2) }}
                    </div>

                    <div class="mini-summary-note">
                        Current displayed records
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         REPAIR TABLE
    ============================================================ --}}
    <div class="card report-card overflow-hidden">

        {{-- Table Header --}}
        <div class="table-card-header">

            <div>

                <div class="d-flex align-items-center gap-2 mb-1">

                    <div class="table-title-icon">
                        <i class="bi bi-table"></i>
                    </div>

                    <h5 class="mb-0 fw-bold">
                        Repair Progress Details
                    </h5>

                </div>

                <div class="text-muted small ms-1">
                    Detailed pending and completed repair records
                </div>

            </div>


            <div class="table-record-info">

                <div class="record-count">

                    <span class="record-count-icon">
                        <i class="bi bi-collection"></i>
                    </span>

                    <strong>
                        {{ number_format($totalItems) }}
                    </strong>

                    records

                </div>

                @if($isPaginated && $repairs->hasPages())

                    <div class="record-page">

                        Page
                        <strong>{{ $repairs->currentPage() }}</strong>
                        of
                        <strong>{{ $repairs->lastPage() }}</strong>

                    </div>

                @endif

            </div>

        </div>


        {{-- Table --}}
        <div class="table-responsive">

            <table class="table repair-table align-middle mb-0">

                <thead>

                    <tr>

                        <th class="ps-4">
                            #
                        </th>

                        <th>
                            Repair Reference
                        </th>

                        <th>
                            Asset
                        </th>

                        <th>
                            Item Type
                        </th>

                        <th>
                            Employee
                        </th>

                        <th>
                            Branch
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Problem Description
                        </th>

                        <th>
                            Technician
                        </th>

                        <th>
                            Date Sent
                        </th>

                        <th>
                            Date Returned
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end">
                            Cost
                        </th>

                        <th class="text-end pe-4">
                            Turnaround
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($repairRows as $index => $repair)

                        @php

                            $turnaroundDays = null;

                            if (
                                $repair->date_sent &&
                                $repair->date_returned
                            ) {
                                try {

                                    $turnaroundDays =
                                        \Carbon\Carbon::parse(
                                            $repair->date_sent
                                        )->diffInDays(
                                            \Carbon\Carbon::parse(
                                                $repair->date_returned
                                            )
                                        );

                                } catch (\Throwable $e) {

                                    $turnaroundDays = null;

                                }
                            }

                            $rowNumber = $isPaginated
                                ? $firstItem + $index
                                : $index + 1;

                        @endphp


                        <tr>

                            {{-- Number --}}
                            <td class="ps-4">

                                <span class="row-number">
                                    {{ $rowNumber }}
                                </span>

                            </td>


                            {{-- Reference --}}
                            <td>

                                @if($repair->repair_reference)

                                    <div class="reference-value">
                                        {{ $repair->repair_reference }}
                                    </div>

                                    <div class="reference-label">
                                        Repair ID
                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Asset --}}
                            <td>

                                <div class="asset-value">
                                    {{ $repair->item?->asset_number ?? '—' }}
                                </div>

                                @if($repair->item?->serial_number)

                                    <div class="serial-value">

                                        <i class="bi bi-upc-scan me-1"></i>

                                        {{ $repair->item->serial_number }}

                                    </div>

                                @endif

                            </td>


                            {{-- Item Type --}}
                            <td>

                                <span class="item-type-text">
                                    {{ $repair->item?->itemType?->name ?? '—' }}
                                </span>

                            </td>


                            {{-- Employee --}}
                            <td>

                                @if($repair->employee)

                                    <div class="employee-cell">

                                        <div class="employee-avatar">

                                            {{ strtoupper(
                                                substr(
                                                    $repair->employee->name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>

                                        <div class="employee-name">

                                            {{ $repair->employee->name }}

                                        </div>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Branch --}}
                            <td>

                                <span class="location-value">
                                    {{ $repair->item?->branch?->name ?? '—' }}
                                </span>

                            </td>


                            {{-- Department --}}
                            <td>

                                <span class="location-value">
                                    {{ $repair->item?->department?->name ?? '—' }}
                                </span>

                            </td>


                            {{-- Problem --}}
                            <td class="problem-column">

                                @if($repair->problem_description)

                                    <div class="problem-wrapper">

                                        <span class="problem-indicator"></span>

                                        <div class="problem-text"
                                             title="{{ $repair->problem_description }}">

                                            {{ $repair->problem_description }}

                                        </div>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Technician --}}
                            <td>

                                @if($repair->technician)

                                    <span class="technician-value">

                                        <i class="bi bi-person-gear me-1"></i>

                                        {{ $repair->technician }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Date Sent --}}
                            <td class="text-nowrap">

                                @if($repair->date_sent)

                                    <div class="date-primary">
                                        {{ $formatDate($repair->date_sent) }}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Date Returned --}}
                            <td class="text-nowrap">

                                @if($repair->date_returned)

                                    <div class="date-primary">

                                        {{ $formatDate(
                                            $repair->date_returned
                                        ) }}

                                    </div>

                                @else

                                    <span class="date-pending">

                                        <i class="bi bi-clock me-1"></i>

                                        Not Returned

                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                <span class="status-badge status-{{ $getStatusClass($repair->status) }}">

                                    @if($repair->status === 'pending')

                                        <i class="bi bi-hourglass-split"></i>

                                    @elseif($repair->status === 'sent_for_repair')

                                        <i class="bi bi-send"></i>

                                    @elseif($repair->status === 'under_repair')

                                        <i class="bi bi-wrench-adjustable"></i>

                                    @elseif($repair->status === 'completed')

                                        <i class="bi bi-check-circle"></i>

                                    @elseif($repair->status === 'returned')

                                        <i class="bi bi-box-arrow-in-left"></i>

                                    @elseif($repair->status === 'cancelled')

                                        <i class="bi bi-x-circle"></i>

                                    @else

                                        <i class="bi bi-circle"></i>

                                    @endif

                                    {{ $formatStatus($repair->status) }}

                                </span>

                            </td>


                            {{-- Cost --}}
                            <td class="text-end text-nowrap">

                                <span class="cost-value">

                                    {{ number_format(
                                        (float) ($repair->repair_cost ?? 0),
                                        2
                                    ) }}

                                </span>

                            </td>


                            {{-- Turnaround --}}
                            <td class="text-end text-nowrap pe-4">

                                @if($turnaroundDays !== null)

                                    <span class="turnaround-value">
                                        {{ number_format($turnaroundDays) }}
                                    </span>

                                    <span class="turnaround-unit">
                                        days
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="14">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="bi bi-clipboard-x"></i>
                                    </div>

                                    <h5>
                                        No Repair Records Found
                                    </h5>

                                    <p>
                                        No pending or completed repair records
                                        match the selected report criteria.
                                    </p>

                                    <a href="{{ route('admin.reports.repairs.index') }}"
                                       class="btn btn-primary empty-action">

                                        <i class="bi bi-arrow-left me-2"></i>

                                        Return to Repair Reports

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ========================================================
             TABLE FOOTER / PAGINATION
        ========================================================= --}}
        @if(
            ($isPaginated && $repairs->hasPages())
            || $repairRows->count() > 0
        )

            <div class="table-footer">

                <div class="small text-muted">

                    @if($totalItems > 0)

                        <i class="bi bi-info-circle me-1"></i>

                        Showing

                        <strong>
                            {{ number_format($firstItem) }}
                        </strong>

                        –

                        <strong>
                            {{ number_format($lastItem) }}
                        </strong>

                        of

                        <strong>
                            {{ number_format($totalItems) }}
                        </strong>

                        repair records

                    @else

                        No records

                    @endif

                </div>


                @if($isPaginated && $repairs->hasPages())

                    <div class="pagination-wrapper">

                        {{ $repairs->withQueryString()->links() }}

                    </div>

                @endif

            </div>

        @endif

    </div>


    {{-- ============================================================
         REPORT FOOTER
    ============================================================ --}}
    <div class="report-footer mt-4">

        <div>

            <i class="bi bi-shield-check me-1"></i>

            CPC Inventory Management System

        </div>

        <div>

            <i class="bi bi-clock me-1"></i>

            Generated
            {{ now()->format('d M Y, h:i A') }}

        </div>

    </div>

</div>


{{-- =================================================================
     PAGE STYLES
================================================================= --}}
<style>

    /* ================================================================
       GENERAL
    ================================================================ */

    .report-page {
        max-width: 100%;
    }

    .report-card {
        border: 1px solid #e8edf2;
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 5px 22px rgba(20, 35, 55, 0.045);
    }


    /* ================================================================
       HERO
    ================================================================ */

    .report-hero {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        padding: 28px 30px;
        background:
            linear-gradient(
                135deg,
                #f7fbff 0%,
                #ffffff 52%,
                #f3f7fd 100%
            );
        border: 1px solid #dfe9f5;
        box-shadow: 0 7px 26px rgba(13, 110, 253, 0.055);
    }

    .report-hero::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        right: -110px;
        top: -145px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.045);
        pointer-events: none;
    }

    .report-hero::after {
        content: "";
        position: absolute;
        width: 130px;
        height: 130px;
        right: 140px;
        bottom: -90px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.025);
        pointer-events: none;
    }

    .report-icon {
        position: relative;
        z-index: 1;
        width: 58px;
        height: 58px;
        min-width: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #0d6efd;
        color: #ffffff;
        font-size: 25px;
        box-shadow: 0 9px 22px rgba(13, 110, 253, 0.22);
    }

    .report-eyebrow {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 7px;
        background: #eaf2ff;
        color: #0d6efd;
        font-size: 0.66rem;
        font-weight: 800;
        letter-spacing: 0.08em;
    }

    .report-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #20c997;
        box-shadow: 0 0 0 4px rgba(32, 201, 151, 0.10);
    }

    .report-context {
        color: #8a939d;
        font-size: 0.73rem;
        font-weight: 600;
    }

    .report-title {
        position: relative;
        z-index: 1;
        color: #172033;
        font-size: clamp(1.55rem, 2.3vw, 2rem);
        font-weight: 750;
        letter-spacing: -0.025em;
    }

    .report-description {
        position: relative;
        z-index: 1;
        max-width: 720px;
        color: #6c757d;
        font-size: 0.91rem;
        line-height: 1.65;
    }


    /* ================================================================
       REPORT TOOLBAR
    ================================================================ */

    .report-toolbar {
        position: relative;
        z-index: 2;
    }

    .toolbar-label {
        margin-bottom: 8px;
        color: #8a939d;
        font-size: 0.66rem;
        font-weight: 800;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        text-align: right;
    }

    .report-action-btn {
        min-height: 42px;
        border-radius: 10px;
        padding: 9px 15px;
        font-size: 0.79rem;
        font-weight: 700;
        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease,
            background-color 0.18s ease;
    }

    .report-action-btn:hover {
        transform: translateY(-2px);
    }

    /*
    |--------------------------------------------------------------------------
    | Primary Report Actions
    |--------------------------------------------------------------------------
    */

    .report-primary-action {
        border-color: #0d6efd;
        background: #0d6efd;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.14);
    }

    .report-primary-action:hover,
    .report-primary-action:focus {
        border-color: #0b5ed7;
        background: #0b5ed7;
        color: #ffffff;
        box-shadow: 0 7px 17px rgba(13, 110, 253, 0.22);
    }

    .report-primary-action:active {
        background: #0a58ca;
        color: #ffffff;
    }

    /*
    |--------------------------------------------------------------------------
    | Back Button
    |--------------------------------------------------------------------------
    */

    .report-back-btn {
        border: 1px solid #dfe4e9;
        background: #ffffff;
        color: #495057;
        box-shadow: 0 3px 9px rgba(0, 0, 0, 0.035);
    }

    .report-back-btn:hover {
        border-color: #cbd3dc;
        background: #f8f9fa;
        color: #212529;
    }


    /* ================================================================
       STAT CARDS
    ================================================================ */

    .stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 17px;
        background: #ffffff;
        border: 1px solid #e8edf2;
        box-shadow: 0 5px 18px rgba(20, 35, 55, 0.04);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 27px rgba(20, 35, 55, 0.075);
    }

    .stat-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    .stat-primary::before {
        background: #0d6efd;
    }

    .stat-warning::before {
        background: #f0ad00;
    }

    .stat-success::before {
        background: #198754;
    }

    .stat-dark::before {
        background: #343a40;
    }

    .stat-card-body {
        min-height: 150px;
        padding: 23px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .stat-content {
        min-width: 0;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.06em;
    }

    .stat-value {
        margin-top: 6px;
        color: #18202f;
        font-size: 1.85rem;
        line-height: 1.15;
        font-weight: 750;
    }

    .stat-money {
        font-size: 1.5rem;
    }

    .stat-description {
        margin-top: 7px;
        color: #8a939d;
        font-size: 0.74rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        font-size: 20px;
    }

    .stat-primary .stat-icon {
        color: #0d6efd;
        background: #eaf2ff;
    }

    .stat-warning .stat-icon {
        color: #b47d00;
        background: #fff6d9;
    }

    .stat-success .stat-icon {
        color: #198754;
        background: #e9f7ef;
    }

    .stat-dark .stat-icon {
        color: #343a40;
        background: #f0f1f3;
    }


    /* ================================================================
       SECTION HEADER
    ================================================================ */

    .report-section-header {
        min-height: 78px;
        padding: 17px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        border-bottom: 1px solid #edf0f3;
    }

    .section-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #edf4ff;
        color: #0d6efd;
    }

    .section-title {
        color: #202733;
        font-size: 0.98rem;
        font-weight: 700;
    }

    .section-subtitle {
        margin-top: 2px;
        color: #8a939d;
        font-size: 0.74rem;
    }

    .criteria-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 11px;
        border-radius: 8px;
        background: #eaf7ef;
        color: #198754;
        font-size: 0.71rem;
        font-weight: 700;
        white-space: nowrap;
    }


    /* ================================================================
       CRITERIA
    ================================================================ */

    .criteria-item {
        height: 100%;
        padding: 14px 15px;
        border: 1px solid #edf0f3;
        border-radius: 11px;
        background: #fafbfc;
        transition:
            border-color 0.15s ease,
            background-color 0.15s ease;
    }

    .criteria-item:hover {
        border-color: #dce5ef;
        background: #f8fbff;
    }

    .criteria-label {
        margin-bottom: 6px;
        color: #89919b;
        font-size: 0.67rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .criteria-value {
        min-height: 22px;
        color: #273142;
        font-size: 0.82rem;
        font-weight: 600;
        word-break: break-word;
    }

    .criteria-highlight {
        display: inline-flex;
        align-items: center;
        color: #0d6efd;
    }

    .criteria-muted {
        color: #6c757d;
    }


    /* ================================================================
       MINI SUMMARY
    ================================================================ */

    .mini-summary-card {
        min-height: 112px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid #e8edf2;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(20, 35, 55, 0.035);
    }

    .mini-summary-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 18px;
    }

    .summary-blue {
        background: #eaf2ff;
        color: #0d6efd;
    }

    .summary-info {
        background: #e5f6fb;
        color: #087990;
    }

    .summary-secondary {
        background: #eef0f2;
        color: #626b74;
    }

    .mini-summary-label {
        color: #8a939d;
        font-size: 0.69rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.035em;
    }

    .mini-summary-value {
        margin-top: 4px;
        color: #202733;
        font-size: 1.18rem;
        font-weight: 750;
    }

    .mini-summary-value span {
        color: #8a939d;
        font-size: 0.72rem;
        font-weight: 500;
    }

    .mini-summary-note {
        margin-top: 3px;
        color: #9aa2aa;
        font-size: 0.67rem;
    }

    .mini-summary-progress {
        width: 100%;
        height: 4px;
        margin-top: 8px;
        overflow: hidden;
        border-radius: 99px;
        background: #e9edf2;
    }

    .mini-summary-progress-bar {
        height: 100%;
        border-radius: inherit;
        background: #0d6efd;
        transition: width 0.4s ease;
    }


    /* ================================================================
       TABLE HEADER
    ================================================================ */

    .table-card-header {
        min-height: 82px;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .table-title-icon {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #edf4ff;
        color: #0d6efd;
        font-size: 14px;
    }

    .table-record-info {
        text-align: right;
    }

    .record-count {
        color: #495057;
        font-size: 0.77rem;
    }

    .record-count-icon {
        margin-right: 4px;
        color: #0d6efd;
    }

    .record-page {
        margin-top: 3px;
        color: #8a939d;
        font-size: 0.69rem;
    }


    /* ================================================================
       TABLE
    ================================================================ */

    .repair-table {
        min-width: 1650px;
    }

    .repair-table thead th {
        padding-top: 13px;
        padding-bottom: 13px;
        background: #f7f9fb;
        border-bottom: 1px solid #e4e8ec;
        color: #68727d;
        font-size: 0.66rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.035em;
        white-space: nowrap;
    }

    .repair-table tbody td {
        padding-top: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f0f2f4;
        color: #384250;
        font-size: 0.79rem;
        vertical-align: middle;
    }

    .repair-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .repair-table tbody tr:hover {
        background: #f8fbff;
    }

    .repair-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .row-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 27px;
        height: 27px;
        border-radius: 8px;
        background: #f1f4f7;
        color: #68727d;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .reference-value {
        color: #0d6efd;
        font-weight: 750;
        white-space: nowrap;
    }

    .reference-label {
        margin-top: 2px;
        color: #9aa2aa;
        font-size: 0.61rem;
    }

    .asset-value {
        color: #212936;
        font-weight: 700;
        white-space: nowrap;
    }

    .serial-value {
        margin-top: 3px;
        color: #929aa3;
        font-size: 0.65rem;
        white-space: nowrap;
    }

    .item-type-text {
        color: #4d5864;
        white-space: nowrap;
    }

    .location-value {
        color: #4d5864;
        white-space: nowrap;
    }

    .employee-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 150px;
    }

    .employee-avatar {
        width: 28px;
        height: 28px;
        min-width: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #eaf2ff;
        color: #0d6efd;
        font-size: 0.67rem;
        font-weight: 800;
    }

    .employee-name {
        color: #3d4753;
        font-weight: 600;
        white-space: nowrap;
    }

    .problem-column {
        width: 250px;
        max-width: 250px;
    }

    .problem-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .problem-indicator {
        width: 4px;
        height: 25px;
        min-width: 4px;
        border-radius: 99px;
        background: #dee5ed;
    }

    .problem-text {
        max-width: 235px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #4d5864;
    }

    .technician-value {
        color: #4d5864;
        white-space: nowrap;
    }

    .date-primary {
        color: #4d5864;
        font-weight: 600;
    }

    .date-pending {
        color: #b47d00;
        font-size: 0.72rem;
        font-weight: 650;
        white-space: nowrap;
    }


    /* ================================================================
       STATUS BADGES
    ================================================================ */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 8px;
        font-size: 0.66rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .status-warning {
        background: #fff4cf;
        color: #966c00;
    }

    .status-info {
        background: #e5f6fb;
        color: #087990;
    }

    .status-primary {
        background: #eaf2ff;
        color: #0d5dcc;
    }

    .status-success {
        background: #e7f6ed;
        color: #167247;
    }

    .status-danger {
        background: #fdebec;
        color: #b02a37;
    }

    .status-secondary {
        background: #eef0f2;
        color: #626b74;
    }

    .mini-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.69rem;
        font-weight: 700;
    }


    /* ================================================================
       COST / TURNAROUND
    ================================================================ */

    .cost-value {
        color: #202733;
        font-weight: 750;
    }

    .turnaround-value {
        color: #202733;
        font-weight: 750;
    }

    .turnaround-unit {
        color: #929aa3;
        font-size: 0.66rem;
    }


    /* ================================================================
       EMPTY STATE
    ================================================================ */

    .empty-state {
        padding: 65px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background: #f1f4f7;
        color: #8a939d;
        font-size: 28px;
    }

    .empty-state h5 {
        color: #343a40;
        font-weight: 700;
    }

    .empty-state p {
        max-width: 480px;
        margin: 0 auto 20px;
        color: #8a939d;
        font-size: 0.84rem;
        line-height: 1.6;
    }

    .empty-action {
        border-radius: 9px;
        font-weight: 650;
    }


    /* ================================================================
       TABLE FOOTER
    ================================================================ */

    .table-footer {
        min-height: 68px;
        padding: 13px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        border-top: 1px solid #edf0f3;
        background: #fcfdfe;
    }

    .pagination-wrapper .pagination {
        margin-bottom: 0;
    }

    .pagination-wrapper .page-link {
        margin: 0 2px;
        border-radius: 8px;
        color: #495057;
        border-color: #e1e5e9;
    }

    .pagination-wrapper .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
    }


    /* ================================================================
       FOOTER
    ================================================================ */

    .report-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        color: #929aa3;
        font-size: 0.7rem;
    }


    /* ================================================================
       RESPONSIVE
    ================================================================ */

    @media (max-width: 1199.98px) {

        .toolbar-label {
            text-align: left;
        }

        .report-toolbar .d-flex {
            justify-content: flex-start !important;
        }

    }


    @media (max-width: 991.98px) {

        .report-hero {
            padding: 22px;
        }

        .report-section-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .criteria-badge {
            align-self: flex-start;
        }

    }


    @media (max-width: 767.98px) {

        .report-page {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .report-hero {
            border-radius: 15px;
            padding: 18px;
        }

        .report-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            font-size: 20px;
        }

        .report-title {
            font-size: 1.4rem;
        }

        .report-description {
            font-size: 0.82rem;
        }

        .report-action-btn {
            flex: 1 1 auto;
            justify-content: center;
            min-width: 105px;
        }

        .report-back-btn {
            flex-basis: 100%;
        }

        .table-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .table-record-info {
            text-align: left;
        }

        .table-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .report-footer {
            align-items: flex-start;
            flex-direction: column;
        }

    }


    @media (max-width: 575.98px) {

        .report-action-btn {
            width: 100%;
            flex-basis: 100%;
        }

        .report-toolbar .d-flex {
            width: 100%;
        }

        .criteria-item {
            padding: 12px 13px;
        }

        .stat-card-body {
            min-height: 135px;
            padding: 19px;
        }

        .stat-value {
            font-size: 1.55rem;
        }

        .stat-money {
            font-size: 1.3rem;
        }

    }


    /* ================================================================
       PRINT
    ================================================================ */

    @media print {

        @page {
            size: landscape;
            margin: 10mm;
        }

        body {
            background: #ffffff !important;
        }

        .report-page {
            padding: 0 !important;
            width: 100% !important;
        }

        .report-hero {
            padding: 15px !important;
            box-shadow: none !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Hide interactive controls
        |--------------------------------------------------------------------------
        */

        .report-toolbar,
        .report-action-btn,
        .pagination-wrapper,
        .table-footer,
        nav,
        .sidebar,
        .navbar,
        .criteria-badge,
        .report-status-dot {
            display: none !important;
        }

        .report-card,
        .stat-card,
        .mini-summary-card {
            box-shadow: none !important;
        }

        .stat-card {
            border: 1px solid #dee2e6 !important;
        }

        .repair-table {
            min-width: 0 !important;
            width: 100% !important;
            font-size: 7px !important;
        }

        .repair-table th,
        .repair-table td {
            padding: 4px !important;
        }

        .problem-column,
        .problem-text {
            max-width: none !important;
            width: auto !important;
            white-space: normal !important;
        }

        .report-footer {
            margin-top: 15px !important;
        }

    }

</style>

@endsection