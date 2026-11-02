@extends('layouts.admin')

@section('title', 'Temporary Item Reports | CPC Inventory Management System')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">

                <i class="bi bi-box-seam text-info fs-4"></i>

                <span class="text-muted small">
                    Reports / Inventory / Temporary Items
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Temporary Item Reports
            </h2>

            <p class="text-muted mb-0">
                Monitor temporary item issues, current items, returns and overdue items.
            </p>
        </div>


        <div class="d-flex flex-wrap gap-2">

            <a href="{{ route('admin.reports.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Reports Dashboard

            </a>

        </div>

    </div>


    {{-- =========================================================
         REPORT TYPE NAVIGATION
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- =====================================================
             ISSUE SUMMARY
        ====================================================== --}}
        <div class="col-xl-4 col-md-6">

            <a href="{{ route('admin.reports.temporary-items.index', [
                'report_type' => 'summary'
            ]) }}"
               class="text-decoration-none">

                <div class="report-type-card
                    {{ ($reportType ?? 'summary') === 'summary' ? 'active' : '' }}">

                    <div class="report-type-icon summary-icon">

                        <i class="bi bi-clipboard-data"></i>

                    </div>


                    <div class="flex-grow-1">

                        <h5>
                            Issue Summary
                        </h5>

                        <p>
                            View a complete summary of temporary item issue
                            transactions.
                        </p>

                        <span class="report-link">

                            View Summary

                            <i class="bi bi-arrow-right"></i>

                        </span>

                    </div>

                </div>

            </a>

        </div>


        {{-- =====================================================
             CURRENT & RETURNED
        ====================================================== --}}
        <div class="col-xl-4 col-md-6">

            <a href="{{ route('admin.reports.temporary-items.index', [
                'report_type' => 'current_returned'
            ]) }}"
               class="text-decoration-none">

                <div class="report-type-card
                    {{ ($reportType ?? '') === 'current_returned' ? 'active' : '' }}">

                    <div class="report-type-icon current-icon">

                        <i class="bi bi-arrow-left-right"></i>

                    </div>


                    <div class="flex-grow-1">

                        <h5>
                            Current & Returned Items
                        </h5>

                        <p>
                            Track currently issued temporary items and
                            completed returns.
                        </p>

                        <span class="report-link success-link">

                            View Report

                            <i class="bi bi-arrow-right"></i>

                        </span>

                    </div>

                </div>

            </a>

        </div>


        
    </div>


    {{-- =========================================================
         KPI CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- =====================================================
             TOTAL ISSUES
        ====================================================== --}}
        <div class="col-xl col-lg-4 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon kpi-primary">

                    <i class="bi bi-box-seam"></i>

                </div>


                <div>

                    <div class="kpi-label">
                        Total Issues
                    </div>

                    <div class="kpi-value">
                        {{ number_format($totalIssues ?? 0) }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             CURRENTLY ISSUED
        ====================================================== --}}
        <div class="col-xl col-lg-4 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon kpi-warning">

                    <i class="bi bi-clock-history"></i>

                </div>


                <div>

                    <div class="kpi-label">
                        Currently Issued
                    </div>

                    <div class="kpi-value">
                        {{ number_format($currentlyIssued ?? 0) }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RETURNED
        ====================================================== --}}
        <div class="col-xl col-lg-4 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon kpi-success">

                    <i class="bi bi-check-circle"></i>

                </div>


                <div>

                    <div class="kpi-label">
                        Returned
                    </div>

                    <div class="kpi-value">
                        {{ number_format($returnedItems ?? 0) }}
                    </div>

                </div>

            </div>

        </div>




        {{-- =====================================================
             EVENTS
        ====================================================== --}}
        <div class="col-xl col-lg-4 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon kpi-info">

                    <i class="bi bi-calendar-event"></i>

                </div>


                <div>

                    <div class="kpi-label">
                        Total Events
                    </div>

                    <div class="kpi-value">
                        {{ number_format($totalEvents ?? 0) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex align-items-center justify-content-between gap-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-funnel text-info me-2"></i>

                        Report Filters

                    </h5>

                    <small class="text-muted">
                        Refine the temporary item report
                    </small>

                </div>


                <a href="{{ route('admin.reports.temporary-items.index') }}"
                   class="btn btn-light btn-sm border">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                    Reset

                </a>

            </div>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.reports.temporary-items.index') }}">

                <input type="hidden"
                       name="report_type"
                       value="{{ $reportType ?? 'summary' }}">


                <div class="row g-3">

                    {{-- =================================================
                         SEARCH
                    ================================================== --}}
                    <div class="col-xl-4 col-lg-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">

                                <i class="bi bi-search"></i>

                            </span>


                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Item code, event, employee...">

                        </div>

                    </div>


                    {{-- =================================================
                         ITEM TYPE
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Item Type
                        </label>

                        <select name="item_type"
                                class="form-select">

                            <option value="">
                                All Types
                            </option>


                            @foreach(($temporaryItemTypes ?? ['PC', 'Projector', 'Laptop']) as $type)

                                <option value="{{ $type }}"
                                    {{ request('item_type') === $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         STATUS
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>


                            <option value="issued"
                                {{ request('status') === 'issued' ? 'selected' : '' }}>

                                Issued

                            </option>


                            <option value="received"
                                {{ request('status') === 'received' ? 'selected' : '' }}>

                                Received

                            </option>


                            <option value="cancelled"
                                {{ request('status') === 'cancelled' ? 'selected' : '' }}>

                                Cancelled

                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                         DEPARTMENT
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Department
                        </label>

                        <select name="department_id"
                                class="form-select">

                            <option value="">
                                All Departments
                            </option>


                            @foreach(($departments ?? []) as $department)

                                <option value="{{ $department->id }}"
                                    {{ (string) request('department_id') === (string) $department->id ? 'selected' : '' }}>

                                    {{ $department->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         REQUESTING EMPLOYEE
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Requesting Employee
                        </label>

                        <select name="requesting_employee_id"
                                class="form-select">

                            <option value="">
                                All Employees
                            </option>


                            @foreach(($employees ?? []) as $employee)

                                <option value="{{ $employee->id }}"
                                    {{ (string) request('requesting_employee_id') === (string) $employee->id ? 'selected' : '' }}>

                                    {{ $employee->name }}

                                    @if(!empty($employee->epf_no))

                                        — {{ $employee->epf_no }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         FROM DATE
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            From Date
                        </label>

                        <input type="date"
                               name="from_date"
                               value="{{ request('from_date') }}"
                               class="form-control">

                    </div>


                    {{-- =================================================
                         TO DATE
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            To Date
                        </label>

                        <input type="date"
                               name="to_date"
                               value="{{ request('to_date') }}"
                               class="form-control">

                    </div>


                    


                    {{-- =================================================
                         GENERATE
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-3 col-md-6 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-info text-white w-100">

                            <i class="bi bi-bar-chart-line me-1"></i>

                            Generate

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         REPORT RESULT
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        @switch($reportType ?? 'summary')

                            @case('current_returned')

                                Current & Returned Items

                                @break


                            @case('overdue')

                                Overdue Temporary Items

                                @break


                            @default

                                Temporary Item Issue Summary

                        @endswitch

                    </h5>


                    <small class="text-muted">
                        CPC Inventory Management System
                    </small>

                </div>


                {{-- =================================================
                     EXPORT BUTTONS
                ================================================== --}}
                <div class="d-flex flex-wrap gap-2">

                    {{-- PRINT --}}
                    <a href="{{ route(
                        'admin.reports.temporary-items.print',
                        request()->query()
                    ) }}"
                       target="_blank"
                       class="btn btn-outline-secondary btn-sm">

                        <i class="bi bi-printer me-1"></i>

                        Print

                    </a>


                    {{-- PDF --}}
                    <a href="{{ route(
                        'admin.reports.temporary-items.pdf',
                        request()->query()
                    ) }}"
                       class="btn btn-outline-danger btn-sm">

                        <i class="bi bi-file-earmark-pdf me-1"></i>

                        PDF

                    </a>


                    {{-- EXCEL --}}
                    <a href="{{ route(
                        'admin.reports.temporary-items.excel',
                        request()->query()
                    ) }}"
                       class="btn btn-outline-success btn-sm">

                        <i class="bi bi-file-earmark-excel me-1"></i>

                        Excel

                    </a>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            {{-- =====================================================
                 REPORT DATA
            ====================================================== --}}

            @if(isset($temporaryIssues) && $temporaryIssues->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Issue Date
                            </th>

                            <th>
                                Item Code
                            </th>

                            <th>
                                Item Type
                            </th>

                            <th>
                                Event
                            </th>

                            <th>
                                Department
                            </th>

                            <th>
                                Requesting Employee
                            </th>

                            <th>
                                Collecting Employee
                            </th>

                            <th>
                                Return Date
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($temporaryIssues as $index => $item)

                            @php

                                /*
                                |--------------------------------------------------------------------------
                                | STATUS
                                |--------------------------------------------------------------------------
                                */

                                $status = strtolower(
                                    $item->status ?? ''
                                );


                                $statusClass = match($status) {

                                    'issued' => 'warning',

                                    'received' => 'success',

                                    'cancelled' => 'secondary',

                                    default => 'dark',

                                };


                                /*
                                |--------------------------------------------------------------------------
                                | ISSUE DATE
                                |--------------------------------------------------------------------------
                                */

                                $issueDate = $item->issue_datetime
                                    ? \Carbon\Carbon::parse(
                                        $item->issue_datetime
                                    )
                                    : null;


                                /*
                                |--------------------------------------------------------------------------
                                | RETURN DATE
                                |--------------------------------------------------------------------------
                                */

                                $returnDate = $item->received_datetime
                                    ? \Carbon\Carbon::parse(
                                        $item->received_datetime
                                    )
                                    : null;

                            @endphp


                            <tr>

                                {{-- =================================================
                                     ROW NUMBER
                                ================================================== --}}
                                <td class="text-muted">

                                    @if(method_exists($temporaryIssues, 'firstItem'))

                                        {{
                                            ($temporaryIssues->firstItem() ?? 1)
                                            + $index
                                        }}

                                    @else

                                        {{ $index + 1 }}

                                    @endif

                                </td>


                                {{-- =================================================
                                     ISSUE DATE
                                ================================================== --}}
                                <td>

                                    @if($issueDate)

                                        <div class="fw-semibold">

                                            {{ $issueDate->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $issueDate->format('H:i') }}

                                        </small>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- =================================================
                                     ITEM CODE
                                ================================================== --}}
                                <td>

                                    <span class="fw-semibold">

                                        {{ $item->item_code ?? '—' }}

                                    </span>

                                </td>


                                {{-- =================================================
                                     ITEM TYPE
                                ================================================== --}}
                                <td>

                                    {{ $item->item_type ?? '—' }}

                                </td>


                                {{-- =================================================
                                     EVENT
                                ================================================== --}}
                                <td>

                                    <span class="fw-semibold">

                                        {{ $item->event ?? '—' }}

                                    </span>

                                </td>


                                {{-- =================================================
                                     DEPARTMENT
                                ================================================== --}}
                                <td>

                                    {{ $item->department?->name ?? '—' }}

                                </td>


                                {{-- =================================================
                                     REQUESTING EMPLOYEE
                                ================================================== --}}
                                <td>

                                    @if($item->requestingEmployee)

                                        <div class="fw-semibold">

                                            {{ $item->requestingEmployee->name }}

                                        </div>


                                        @if(!empty(
                                            $item->requestingEmployee->epf_no
                                        ))

                                            <small class="text-muted">

                                                {{
                                                    $item->requestingEmployee->epf_no
                                                }}

                                            </small>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- =================================================
                                     COLLECTING EMPLOYEE
                                ================================================== --}}
                                <td>

                                    @if($item->collectingEmployee)

                                        <div class="fw-semibold">

                                            {{ $item->collectingEmployee->name }}

                                        </div>


                                        @if(!empty(
                                            $item->collectingEmployee->epf_no
                                        ))

                                            <small class="text-muted">

                                                {{
                                                    $item->collectingEmployee->epf_no
                                                }}

                                            </small>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- =================================================
                                     RETURN DATE
                                ================================================== --}}
                                <td>

                                    @if($returnDate)

                                        {{ $returnDate->format('d M Y H:i') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- =================================================
                                     STATUS
                                ================================================== --}}
                                <td>

                                    <span class="badge rounded-pill text-bg-{{ $statusClass }}">

                                        {{
                                            $status !== ''
                                                ? ucfirst($status)
                                                : 'Unknown'
                                        }}

                                    </span>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                     PAGINATION
                ================================================== --}}
                @if(method_exists($temporaryIssues, 'links'))

                    <div class="p-3 border-top">

                        {{
                            $temporaryIssues
                                ->withQueryString()
                                ->links()
                        }}

                    </div>

                @endif

            @else

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}
                <div class="empty-state text-center">

                    <div class="empty-state-icon">

                        <i class="bi bi-box-seam"></i>

                    </div>


                    <h5 class="fw-bold mt-3">

                        No Temporary Item Records Found

                    </h5>


                    <p class="text-muted mb-0">

                        No records match the selected report criteria.

                    </p>

                </div>

            @endif

        </div>

    </div>

</div>


<style>

/* ============================================================
   REPORT TYPE CARDS
============================================================ */

.report-type-card {

    height: 100%;

    display: flex;

    align-items: flex-start;

    gap: 18px;

    padding: 22px;

    background: #ffffff;

    border: 1px solid #e7eaee;

    border-radius: 14px;

    box-shadow: 0 2px 8px rgba(0, 0, 0, .035);

    transition:
        transform .2s ease,
        box-shadow .2s ease,
        border-color .2s ease;

}


.report-type-card:hover {

    transform: translateY(-3px);

    box-shadow: 0 8px 24px rgba(0, 0, 0, .08);

}


.report-type-card.active {

    border-color: rgba(13, 110, 253, .35);

    box-shadow: 0 5px 18px rgba(13, 110, 253, .10);

}


.report-type-card h5 {

    margin: 0 0 7px;

    font-size: 16px;

    font-weight: 700;

    color: #212529;

}


.report-type-card p {

    margin: 0 0 10px;

    color: #6c757d;

    font-size: 13px;

    line-height: 1.55;

}


.report-type-icon {

    width: 52px;

    height: 52px;

    min-width: 52px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    font-size: 21px;

}


.summary-icon {

    background: rgba(13, 110, 253, .10);

    color: #0d6efd;

}


.current-icon {

    background: rgba(25, 135, 84, .10);

    color: #198754;

}


.overdue-icon {

    background: rgba(220, 53, 69, .10);

    color: #dc3545;

}


.report-link {

    color: #0d6efd;

    font-size: 13px;

    font-weight: 700;

}


.success-link {

    color: #198754;

}


.danger-link {

    color: #dc3545;

}


.report-link i {

    margin-left: 4px;

    transition: margin-left .2s ease;

}


.report-type-card:hover .report-link i {

    margin-left: 8px;

}


/* ============================================================
   KPI CARDS
============================================================ */

.kpi-card {

    min-height: 94px;

    height: 100%;

    display: flex;

    align-items: center;

    gap: 14px;

    padding: 17px;

    background: #ffffff;

    border: 1px solid #e7eaee;

    border-radius: 12px;

    box-shadow: 0 2px 7px rgba(0, 0, 0, .035);

}


.kpi-icon {

    width: 48px;

    height: 48px;

    min-width: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 11px;

    font-size: 20px;

}


.kpi-primary {

    background: rgba(13, 110, 253, .10);

    color: #0d6efd;

}


.kpi-warning {

    background: rgba(255, 193, 7, .14);

    color: #997404;

}


.kpi-success {

    background: rgba(25, 135, 84, .10);

    color: #198754;

}


.kpi-danger {

    background: rgba(220, 53, 69, .10);

    color: #dc3545;

}


.kpi-info {

    background: rgba(13, 202, 240, .12);

    color: #087990;

}


.kpi-label {

    color: #6c757d;

    font-size: 12px;

    font-weight: 600;

    margin-bottom: 2px;

}


.kpi-value {

    color: #212529;

    font-size: 22px;

    line-height: 1.2;

    font-weight: 700;

}


/* ============================================================
   TABLE
============================================================ */

.table {

    margin-bottom: 0;

}


.table thead th {

    white-space: nowrap;

    color: #495057;

    font-size: 12px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .02em;

    vertical-align: middle;

}


.table tbody td {

    font-size: 13px;

    white-space: nowrap;

    vertical-align: middle;

}


.table tbody tr {

    transition: background-color .15s ease;

}


.table tbody tr:hover {

    background-color: rgba(13, 110, 253, .025);

}


/* ============================================================
   EMPTY STATE
============================================================ */

.empty-state {

    padding: 70px 20px;

}


.empty-state-icon {

    width: 70px;

    height: 70px;

    margin: 0 auto;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #f1f3f5;

    color: #6c757d;

    font-size: 29px;

}


/* ============================================================
   FORM CONTROLS
============================================================ */

.form-label {

    font-size: 13px;

    margin-bottom: 6px;

}


.form-control,
.form-select {

    min-height: 40px;

    border-color: #dee2e6;

    font-size: 13px;

}


.form-control:focus,
.form-select:focus {

    border-color: rgba(13, 202, 240, .55);

    box-shadow: 0 0 0 .2rem rgba(13, 202, 240, .10);

}


.input-group-text {

    border-color: #dee2e6;

    color: #6c757d;

}


/* ============================================================
   BUTTONS
============================================================ */

.btn {

    font-size: 13px;

    font-weight: 600;

}


.btn-info {

    border-color: #0dcaf0;

}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 1200px) {

    .report-type-card {

        padding: 20px;

    }

}


@media (max-width: 768px) {

    .container-fluid {

        padding-left: 12px;

        padding-right: 12px;

    }


    .report-type-card {

        padding: 18px;

        gap: 14px;

    }


    .report-type-icon {

        width: 46px;

        height: 46px;

        min-width: 46px;

        font-size: 19px;

    }


    .kpi-card {

        min-height: 82px;

        padding: 14px;

    }


    .kpi-icon {

        width: 43px;

        height: 43px;

        min-width: 43px;

        font-size: 18px;

    }


    .kpi-value {

        font-size: 20px;

    }


    .table {

        min-width: 1050px;

    }

}


@media (max-width: 576px) {

    .report-type-card {

        padding: 16px;

    }


    .report-type-card h5 {

        font-size: 15px;

    }


    .report-type-card p {

        font-size: 12px;

    }


    .d-flex.flex-wrap.gap-2 {

        width: 100%;

    }

}

</style>

@endsection