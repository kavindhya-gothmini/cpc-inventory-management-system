@extends('layouts.admin')

@section('title', 'Repair Cost Analysis | CPC Inventory Management System')

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
    | Pagination / Collection Compatibility
    |--------------------------------------------------------------------------
    |
    | The controller may return a paginator. Work with its collection
    | for calculations while keeping pagination functionality intact.
    |
    */

    $isPaginator = $repairs instanceof \Illuminate\Pagination\LengthAwarePaginator;

    $repairRows = $isPaginator
        ? $repairs->getCollection()
        : collect($repairs);


    /*
    |--------------------------------------------------------------------------
    | Report Statistics
    |--------------------------------------------------------------------------
    */

    $totalCount = $totalRepairs
        ?? ($isPaginator ? $repairs->total() : $repairRows->count());

    $totalRepairCost = $totalRepairCost
        ?? $repairRows->sum(
            fn ($repair) => (float) ($repair->repair_cost ?? 0)
        );

    $averageRepairCost = $repairRows->count() > 0
        ? $repairRows->avg(
            fn ($repair) => (float) ($repair->repair_cost ?? 0)
        )
        : 0;

    $highestRepairCost = $repairRows->count() > 0
        ? $repairRows->max(
            fn ($repair) => (float) ($repair->repair_cost ?? 0)
        )
        : 0;

    $lowestRepairCost = $repairRows->count() > 0
        ? $repairRows
            ->filter(fn ($repair) => $repair->repair_cost !== null)
            ->min(fn ($repair) => (float) $repair->repair_cost)
        : 0;

    $completedCount = $repairRows
        ->whereIn('status', ['completed', 'returned'])
        ->count();

    $pendingCount = $repairRows
        ->whereIn('status', [
            'pending',
            'sent_for_repair',
            'under_repair',
        ])
        ->count();


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

    $firstItem = $isPaginator
        ? ($repairs->firstItem() ?? 0)
        : ($repairRows->count() > 0 ? 1 : 0);

    $lastItem = $isPaginator
        ? ($repairs->lastItem() ?? 0)
        : $repairRows->count();

    $totalItems = $isPaginator
        ? $repairs->total()
        : $repairRows->count();

    /*
    |--------------------------------------------------------------------------
    | Back Link
    |--------------------------------------------------------------------------
    |
    | Remove report_type so the button returns to the main Repair Reports
    | page instead of reopening the Cost report.
    |
    */

    $backQuery = request()->except('report_type');

@endphp


<div class="container-fluid px-3 px-lg-4 py-4 repair-cost-page">

    {{-- ================================================================
         HERO HEADER
    ================================================================= --}}

    <div class="cost-hero mb-4">

        <div class="row align-items-center g-4">

            <div class="col-12 col-xl-7">

                <div class="d-flex align-items-start gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                    <div>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">

                            <span class="hero-eyebrow">
                                REPAIR MANAGEMENT
                            </span>

                            <span class="report-type-pill">
                                <i class="bi bi-graph-up me-1"></i>
                                Cost Analysis
                            </span>

                        </div>

                        <h1 class="hero-title mb-2">
                            Repair Cost Analysis
                        </h1>

                        <p class="hero-description mb-0">
                            Analyse repair expenditure, individual repair costs,
                            completion status and cost distribution across
                            filtered repair records.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 ACTION BUTTONS
            ========================================================= --}}

            <div class="col-12 col-xl-5">

                <div class="d-flex flex-wrap justify-content-xl-end gap-2">

                    {{-- Back --}}
                    <a href="{{ route('admin.reports.repairs.index', $backQuery) }}"
                       class="btn btn-light cost-action-btn">

                        <i class="bi bi-arrow-left me-2"></i>
                        Repair Reports

                    </a>


                    {{-- Print --}}
                    <button type="button"
                            class="btn btn-primary cost-action-btn"
                            onclick="window.print()">

                        <i class="bi bi-printer me-2"></i>
                        Print

                    </button>


                    {{-- PDF --}}
                    <a href="{{ route('admin.reports.repairs.pdf', request()->query()) }}"
                       class="btn btn-primary cost-action-btn">

                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        PDF

                    </a>


                    {{-- Excel --}}
                    <a href="{{ route('admin.reports.repairs.excel', request()->query()) }}"
                       class="btn btn-primary cost-action-btn">

                        <i class="bi bi-file-earmark-excel me-2"></i>
                        Excel

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         KPI CARDS
    ================================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total Repairs --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="kpi-card kpi-blue h-100">

                <div class="kpi-content">

                    <div>

                        <div class="kpi-label">
                            TOTAL REPAIRS
                        </div>

                        <div class="kpi-value">
                            {{ number_format($totalCount) }}
                        </div>

                        <div class="kpi-description">
                            Matching report criteria
                        </div>

                    </div>

                    <div class="kpi-icon">
                        <i class="bi bi-tools"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Total Cost --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="kpi-card kpi-dark h-100">

                <div class="kpi-content">

                    <div>

                        <div class="kpi-label">
                            TOTAL REPAIR COST
                        </div>

                        <div class="kpi-value money-value">
                            {{ number_format((float) $totalRepairCost, 2) }}
                        </div>

                        <div class="kpi-description">
                            Total recorded expenditure
                        </div>

                    </div>

                    <div class="kpi-icon">
                        <i class="bi bi-currency-exchange"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Average --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="kpi-card kpi-info h-100">

                <div class="kpi-content">

                    <div>

                        <div class="kpi-label">
                            AVERAGE REPAIR COST
                        </div>

                        <div class="kpi-value money-value">
                            {{ number_format((float) $averageRepairCost, 2) }}
                        </div>

                        <div class="kpi-description">
                            Average of displayed records
                        </div>

                    </div>

                    <div class="kpi-icon">
                        <i class="bi bi-calculator"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Highest --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="kpi-card kpi-warning h-100">

                <div class="kpi-content">

                    <div>

                        <div class="kpi-label">
                            HIGHEST REPAIR COST
                        </div>

                        <div class="kpi-value money-value">
                            {{ number_format((float) $highestRepairCost, 2) }}
                        </div>

                        <div class="kpi-description">
                            Highest displayed repair
                        </div>

                    </div>

                    <div class="kpi-icon">
                        <i class="bi bi-arrow-up-circle"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         REPORT CRITERIA
    ================================================================= --}}

    <div class="card report-card mb-4">

        <div class="report-card-header">

            <div class="d-flex align-items-center gap-3">

                <div class="section-icon">
                    <i class="bi bi-funnel"></i>
                </div>

                <div>

                    <h5 class="section-title mb-0">
                        Report Criteria
                    </h5>

                    <div class="section-subtitle">
                        Filters applied to the cost analysis
                    </div>

                </div>

            </div>

            <span class="criteria-status">
                <i class="bi bi-check-circle me-1"></i>
                Filtered Report
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
                            <i class="bi bi-bar-chart-line text-primary me-2"></i>
                            Repair Cost Analysis
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

                                All Repair Statuses

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

                            <i class="bi bi-person text-primary me-2"></i>

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

                            <i class="bi bi-building text-primary me-2"></i>

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

                            <i class="bi bi-diagram-3 text-primary me-2"></i>

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

                            <i class="bi bi-pc-display text-primary me-2"></i>

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

                            <i class="bi bi-calendar-event text-primary me-2"></i>

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

                            <i class="bi bi-calendar-check text-primary me-2"></i>

                            {{ $toDate
                                ? \Carbon\Carbon::parse($toDate)->format('d M Y')
                                : 'All Dates' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         COST SUMMARY
    ================================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-12 col-lg-8">

            <div class="card report-card h-100">

                <div class="report-card-header">

                    <div>

                        <h5 class="section-title mb-1">
                            Cost Overview
                        </h5>

                        <div class="section-subtitle">
                            Summary of repair expenditure
                        </div>

                    </div>

                    <div class="overview-icon">
                        <i class="bi bi-pie-chart"></i>
                    </div>

                </div>


                <div class="p-4">

                    <div class="overview-grid">

                        <div class="overview-item">

                            <span class="overview-label">
                                Total Expenditure
                            </span>

                            <strong class="overview-value">
                                {{ number_format((float) $totalRepairCost, 2) }}
                            </strong>

                        </div>


                        <div class="overview-item">

                            <span class="overview-label">
                                Average Cost
                            </span>

                            <strong class="overview-value">
                                {{ number_format((float) $averageRepairCost, 2) }}
                            </strong>

                        </div>


                        <div class="overview-item">

                            <span class="overview-label">
                                Lowest Cost
                            </span>

                            <strong class="overview-value">
                                {{ number_format((float) $lowestRepairCost, 2) }}
                            </strong>

                        </div>


                        <div class="overview-item">

                            <span class="overview-label">
                                Highest Cost
                            </span>

                            <strong class="overview-value">
                                {{ number_format((float) $highestRepairCost, 2) }}
                            </strong>

                        </div>

                    </div>


                    <div class="cost-progress-area mt-4">

                        <div class="d-flex justify-content-between mb-2">

                            <span class="progress-label">
                                Completed / Returned Repairs
                            </span>

                            <strong class="progress-value">

                                @if($totalCount > 0)
                                    {{ number_format(($completedCount / $totalCount) * 100, 1) }}%
                                @else
                                    0.0%
                                @endif

                            </strong>

                        </div>

                        <div class="progress cost-progress">

                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: {{ $totalCount > 0 ? min(100, ($completedCount / $totalCount) * 100) : 0 }}%;">
                            </div>

                        </div>

                        <div class="small text-muted mt-2">

                            {{ number_format($completedCount) }}
                            completed or returned
                            /
                            {{ number_format($pendingCount) }}
                            pending or in repair

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Cost Snapshot --}}
        <div class="col-12 col-lg-4">

            <div class="card snapshot-card h-100">

                <div class="snapshot-header">

                    <div>

                        <div class="snapshot-eyebrow">
                            COST SNAPSHOT
                        </div>

                        <h5 class="snapshot-title">
                            Expenditure Summary
                        </h5>

                    </div>

                    <div class="snapshot-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>

                </div>


                <div class="snapshot-body">

                    <div class="snapshot-row">

                        <span>
                            Repair Records
                        </span>

                        <strong>
                            {{ number_format($totalCount) }}
                        </strong>

                    </div>


                    <div class="snapshot-row">

                        <span>
                            Total Cost
                        </span>

                        <strong>
                            {{ number_format((float) $totalRepairCost, 2) }}
                        </strong>

                    </div>


                    <div class="snapshot-row">

                        <span>
                            Average Cost
                        </span>

                        <strong>
                            {{ number_format((float) $averageRepairCost, 2) }}
                        </strong>

                    </div>


                    <div class="snapshot-row">

                        <span>
                            Completed
                        </span>

                        <strong class="text-success">
                            {{ number_format($completedCount) }}
                        </strong>

                    </div>


                    <div class="snapshot-row">

                        <span>
                            Pending / In Repair
                        </span>

                        <strong class="text-warning">
                            {{ number_format($pendingCount) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         REPAIR COST DETAILS
    ================================================================= --}}

    <div class="card report-card overflow-hidden">

        <div class="table-card-header">

            <div>

                <div class="d-flex align-items-center gap-2 mb-1">

                    <div class="table-icon">
                        <i class="bi bi-table"></i>
                    </div>

                    <h5 class="table-title mb-0">
                        Repair Cost Details
                    </h5>

                </div>

                <div class="table-subtitle">
                    Detailed repair expenditure records
                </div>

            </div>


            <div class="table-meta">

                <strong>
                    {{ number_format($totalItems) }}
                </strong>

                records

                @if($isPaginator)

                    <div class="table-page">
                        Page {{ $repairs->currentPage() }}
                        of {{ $repairs->lastPage() }}
                    </div>

                @endif

            </div>

        </div>


        {{-- ============================================================
             TABLE
        ============================================================= --}}

        <div class="table-responsive">

            <table class="table cost-table align-middle mb-0">

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
                            Problem
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

                        <th class="text-end pe-4">
                            Repair Cost
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($repairRows as $index => $repair)

                        <tr>

                            {{-- Number --}}
                            <td class="ps-4">

                                <span class="row-number">

                                    {{ $firstItem + $index }}

                                </span>

                            </td>


                            {{-- Reference --}}
                            <td>

                                <span class="reference-value">
                                    {{ $repair->repair_reference ?: '—' }}
                                </span>

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

                                <span class="item-type">
                                    {{ $repair->item?->itemType?->name ?? '—' }}
                                </span>

                            </td>


                            {{-- Employee --}}
                            <td>

                                @if($repair->employee)

                                    <div class="employee-cell">

                                        <div class="employee-avatar">

                                            {{ strtoupper(
                                                substr($repair->employee->name, 0, 1)
                                            ) }}

                                        </div>

                                        <span class="employee-name">

                                            {{ $repair->employee->name }}

                                        </span>

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


                            {{-- Problem --}}
                            <td class="problem-cell">

                                @if($repair->problem_description)

                                    <span title="{{ $repair->problem_description }}">

                                        {{ $repair->problem_description }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Date Sent --}}
                            <td class="text-nowrap">

                                <span class="date-value">

                                    {{ $formatDate($repair->date_sent) }}

                                </span>

                            </td>


                            {{-- Date Returned --}}
                            <td class="text-nowrap">

                                @if($repair->date_returned)

                                    <span class="date-value">

                                        {{ $formatDate($repair->date_returned) }}

                                    </span>

                                @else

                                    <span class="not-returned">
                                        Not Returned
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                <span class="status-badge status-{{ $getStatusClass($repair->status) }}">

                                    @switch($repair->status)

                                        @case('pending')
                                            <i class="bi bi-hourglass-split"></i>
                                            @break

                                        @case('sent_for_repair')
                                            <i class="bi bi-send"></i>
                                            @break

                                        @case('under_repair')
                                            <i class="bi bi-wrench-adjustable"></i>
                                            @break

                                        @case('completed')
                                            <i class="bi bi-check-circle"></i>
                                            @break

                                        @case('returned')
                                            <i class="bi bi-box-arrow-in-left"></i>
                                            @break

                                        @default
                                            <i class="bi bi-circle"></i>

                                    @endswitch

                                    {{ $formatStatus($repair->status) }}

                                </span>

                            </td>


                            {{-- Cost --}}
                            <td class="text-end pe-4">

                                <span class="cost-value">

                                    {{ number_format(
                                        (float) ($repair->repair_cost ?? 0),
                                        2
                                    ) }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="11">

                                <div class="empty-state">

                                    <div class="empty-icon">

                                        <i class="bi bi-cash-stack"></i>

                                    </div>

                                    <h5>
                                        No Repair Cost Records Found
                                    </h5>

                                    <p>
                                        No repair records match the selected
                                        cost analysis criteria.
                                    </p>

                                    <a href="{{ route('admin.reports.repairs.index') }}"
                                       class="btn btn-primary">

                                        <i class="bi bi-arrow-left me-2"></i>

                                        Return to Repair Reports

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>


                @if($repairRows->count() > 0)

                    <tfoot>

                        <tr>

                            <td colspan="10" class="text-end">

                                <strong>
                                    Displayed Total
                                </strong>

                            </td>

                            <td class="text-end pe-4">

                                <strong class="footer-total">

                                    {{ number_format(
                                        (float) $repairRows->sum(
                                            fn ($repair) =>
                                                (float) ($repair->repair_cost ?? 0)
                                        ),
                                        2
                                    ) }}

                                </strong>

                            </td>

                        </tr>

                    </tfoot>

                @endif

            </table>

        </div>


        {{-- ============================================================
             PAGINATION
        ============================================================= --}}

        @if($isPaginator && ($repairs->hasPages() || $repairRows->count() > 0))

            <div class="table-footer">

                <div class="small text-muted">

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

                </div>


                @if($repairs->hasPages())

                    <div class="pagination-wrapper">

                        {{ $repairs->withQueryString()->links() }}

                    </div>

                @endif

            </div>

        @endif

    </div>


    {{-- ================================================================
         FOOTER
    ================================================================= --}}

    <div class="report-footer mt-4">

        <div>

            <i class="bi bi-shield-check me-1"></i>

            CPC Inventory Management System

        </div>

        <div>

            Repair Cost Analysis ·
            Generated {{ now()->format('d M Y, h:i A') }}

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

    .repair-cost-page {
        max-width: 100%;
    }

    .report-card,
    .snapshot-card {
        border: 1px solid #e8edf2;
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 5px 22px rgba(0, 0, 0, 0.045);
    }


    /* ================================================================
       HERO
    ================================================================ */

    .cost-hero {
        position: relative;
        overflow: hidden;
        padding: 28px 30px;
        border: 1px solid #dce8f6;
        border-radius: 20px;
        background:
            linear-gradient(
                135deg,
                #f7fbff 0%,
                #ffffff 55%,
                #f2f7ff 100%
            );
        box-shadow: 0 7px 26px rgba(13, 110, 253, 0.055);
    }

    .cost-hero::after {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        right: -100px;
        top: -130px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.045);
        pointer-events: none;
    }

    .hero-icon {
        width: 58px;
        height: 58px;
        min-width: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #0d6efd;
        color: #ffffff;
        font-size: 24px;
        box-shadow: 0 8px 22px rgba(13, 110, 253, 0.23);
    }

    .hero-eyebrow {
        padding: 5px 9px;
        border-radius: 6px;
        background: #eaf2ff;
        color: #0d6efd;
        font-size: 0.67rem;
        font-weight: 800;
        letter-spacing: 0.08em;
    }

    .report-type-pill {
        padding: 5px 9px;
        border-radius: 6px;
        background: #eef1f5;
        color: #59636e;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .hero-title {
        color: #172033;
        font-size: clamp(1.55rem, 2.4vw, 2rem);
        font-weight: 750;
        letter-spacing: -0.025em;
    }

    .hero-description {
        max-width: 760px;
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.65;
    }

    .cost-action-btn {
        padding: 9px 14px;
        border-radius: 10px;
        font-weight: 650;
        transition: all 0.2s ease;
    }

    .cost-action-btn:hover {
        transform: translateY(-1px);
    }


    /* ================================================================
       KPI CARDS
    ================================================================ */

    .kpi-card {
        position: relative;
        overflow: hidden;
        border: 1px solid #e8edf2;
        border-radius: 17px;
        background: #ffffff;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 9px 25px rgba(0, 0, 0, 0.07);
    }

    .kpi-card::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 4px;
    }

    .kpi-blue::before {
        background: #0d6efd;
    }

    .kpi-dark::before {
        background: #343a40;
    }

    .kpi-info::before {
        background: #0dcaf0;
    }

    .kpi-warning::before {
        background: #f0ad00;
    }

    .kpi-content {
        min-height: 148px;
        padding: 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .kpi-label {
        color: #78828d;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.055em;
    }

    .kpi-value {
        margin-top: 6px;
        color: #1d2633;
        font-size: 1.75rem;
        line-height: 1.15;
        font-weight: 750;
    }

    .money-value {
        font-size: 1.45rem;
    }

    .kpi-description {
        margin-top: 7px;
        color: #929aa3;
        font-size: 0.72rem;
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        font-size: 20px;
    }

    .kpi-blue .kpi-icon {
        color: #0d6efd;
        background: #eaf2ff;
    }

    .kpi-dark .kpi-icon {
        color: #343a40;
        background: #f0f1f3;
    }

    .kpi-info .kpi-icon {
        color: #087990;
        background: #e5f8fc;
    }

    .kpi-warning .kpi-icon {
        color: #a87500;
        background: #fff5d8;
    }


    /* ================================================================
       CARD HEADERS
    ================================================================ */

    .report-card-header,
    .table-card-header {
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

    .criteria-status {
        padding: 6px 10px;
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
        padding: 13px 15px;
        border: 1px solid #edf0f3;
        border-radius: 11px;
        background: #fafbfc;
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


    /* ================================================================
       STATUS
    ================================================================ */

    .mini-status,
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 7px;
        font-weight: 700;
        white-space: nowrap;
    }

    .mini-status {
        padding: 4px 8px;
        font-size: 0.69rem;
    }

    .status-badge {
        padding: 6px 9px;
        font-size: 0.66rem;
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


    /* ================================================================
       COST OVERVIEW
    ================================================================ */

    .overview-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #edf4ff;
        color: #0d6efd;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .overview-item {
        padding: 15px;
        border: 1px solid #edf0f3;
        border-radius: 11px;
        background: #fafbfc;
    }

    .overview-label {
        display: block;
        margin-bottom: 5px;
        color: #89919b;
        font-size: 0.69rem;
        font-weight: 700;
    }

    .overview-value {
        color: #202733;
        font-size: 1.05rem;
    }

    .progress-label {
        color: #606b76;
        font-size: 0.77rem;
        font-weight: 600;
    }

    .progress-value {
        color: #198754;
        font-size: 0.77rem;
    }

    .cost-progress {
        height: 8px;
        overflow: hidden;
        border-radius: 20px;
        background: #edf0f3;
    }

    .cost-progress .progress-bar {
        background: #198754;
        border-radius: 20px;
    }


    /* ================================================================
       SNAPSHOT
    ================================================================ */

    .snapshot-card {
        overflow: hidden;
    }

    .snapshot-header {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        gap: 15px;
        border-bottom: 1px solid #edf0f3;
        background: #fafbfc;
    }

    .snapshot-eyebrow {
        color: #0d6efd;
        font-size: 0.66rem;
        font-weight: 800;
        letter-spacing: 0.08em;
    }

    .snapshot-title {
        margin: 4px 0 0;
        color: #202733;
        font-size: 0.98rem;
        font-weight: 700;
    }

    .snapshot-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #eaf2ff;
        color: #0d6efd;
    }

    .snapshot-body {
        padding: 8px 20px 16px;
    }

    .snapshot-row {
        min-height: 42px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        border-bottom: 1px solid #f0f2f4;
        color: #6c757d;
        font-size: 0.77rem;
    }

    .snapshot-row:last-child {
        border-bottom: 0;
    }

    .snapshot-row strong {
        color: #273142;
        font-size: 0.78rem;
    }


    /* ================================================================
       TABLE
    ================================================================ */

    .table-icon {
        color: #0d6efd;
        font-size: 17px;
    }

    .table-title {
        color: #202733;
        font-size: 0.98rem;
        font-weight: 700;
    }

    .table-subtitle {
        margin-left: 27px;
        color: #8a939d;
        font-size: 0.73rem;
    }

    .table-meta {
        color: #68727d;
        text-align: right;
        font-size: 0.76rem;
    }

    .table-meta strong {
        color: #202733;
    }

    .table-page {
        margin-top: 3px;
        color: #929aa3;
        font-size: 0.68rem;
    }

    .cost-table {
        min-width: 1450px;
    }

    .cost-table thead th {
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

    .cost-table tbody td {
        padding-top: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f0f2f4;
        color: #384250;
        font-size: 0.78rem;
    }

    .cost-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .cost-table tbody tr:hover {
        background: #f8fbff;
    }

    .cost-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .cost-table tfoot td {
        padding-top: 15px;
        padding-bottom: 15px;
        background: #fafbfc;
        border-top: 1px solid #e4e8ec;
        color: #495057;
        font-size: 0.78rem;
    }

    .row-number {
        width: 27px;
        height: 27px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f4f7;
        color: #68727d;
        font-size: 0.69rem;
        font-weight: 700;
    }

    .reference-value {
        color: #0d6efd;
        font-weight: 700;
        white-space: nowrap;
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

    .item-type {
        color: #4d5864;
        white-space: nowrap;
    }

    .employee-cell {
        min-width: 155px;
        display: flex;
        align-items: center;
        gap: 8px;
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
        font-size: 0.66rem;
        font-weight: 800;
    }

    .employee-name {
        color: #3d4753;
        font-weight: 600;
        white-space: nowrap;
    }

    .location-value {
        color: #4d5864;
        white-space: nowrap;
    }

    .problem-cell {
        width: 240px;
        max-width: 240px;
    }

    .problem-cell span {
        display: block;
        max-width: 240px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #4d5864;
    }

    .date-value {
        color: #4d5864;
        font-weight: 600;
    }

    .not-returned {
        color: #b47d00;
        font-size: 0.72rem;
        font-weight: 650;
        white-space: nowrap;
    }

    .cost-value {
        color: #202733;
        font-weight: 750;
        white-space: nowrap;
    }

    .footer-total {
        color: #0d6efd;
        font-size: 0.88rem;
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
    }


    /* ================================================================
       FOOTER / PAGINATION
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
        border-color: #e1e5e9;
        color: #495057;
    }

    .pagination-wrapper .page-item.active .page-link {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
    }

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

    @media (max-width: 991.98px) {

        .cost-hero {
            padding: 22px;
        }

        .report-card-header,
        .table-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .criteria-status {
            align-self: flex-start;
        }

        .table-meta {
            text-align: left;
        }

    }


    @media (max-width: 767.98px) {

        .repair-cost-page {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .cost-hero {
            padding: 18px;
            border-radius: 15px;
        }

        .hero-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            font-size: 20px;
        }

        .hero-title {
            font-size: 1.4rem;
        }

        .cost-action-btn {
            flex: 1 1 auto;
            justify-content: center;
        }

        .overview-grid {
            grid-template-columns: 1fr;
        }

        .table-footer,
        .report-footer {
            align-items: flex-start;
            flex-direction: column;
        }

    }


    /* ================================================================
       PRINT
    ================================================================ */

    @media print {

        body {
            background: #ffffff !important;
        }

        .repair-cost-page {
            width: 100% !important;
            padding: 0 !important;
        }

        .cost-hero {
            padding: 15px !important;
            box-shadow: none !important;
        }

        .cost-action-btn,
        .pagination-wrapper,
        .table-footer,
        nav,
        .sidebar,
        .navbar,
        .criteria-status {
            display: none !important;
        }

        .report-card,
        .snapshot-card,
        .kpi-card {
            box-shadow: none !important;
        }

        .kpi-card {
            border: 1px solid #dee2e6 !important;
        }

        .cost-table {
            min-width: 0 !important;
            width: 100% !important;
            font-size: 7px !important;
        }

        .cost-table th,
        .cost-table td {
            padding: 4px !important;
        }

        .problem-cell,
        .problem-cell span {
            width: auto !important;
            max-width: none !important;
            white-space: normal !important;
        }

        .report-footer {
            margin-top: 15px !important;
        }

    }

</style>

@endsection