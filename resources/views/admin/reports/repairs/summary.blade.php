@extends('layouts.admin')

@section('title', 'Repair Reports | CPC Inventory Management System')

@section('content')

@php
$currentUser = auth()->user();


$statusLabels = [
    'pending' => 'Pending',
    'sent_for_repair' => 'Sent for Repair',
    'under_repair' => 'Under Repair',
    'completed' => 'Completed',
    'returned' => 'Returned',
    'cancelled' => 'Cancelled',
];

$statusClasses = [
    'pending' => 'status-pending',
    'sent_for_repair' => 'status-sent',
    'under_repair' => 'status-under-repair',
    'completed' => 'status-completed',
    'returned' => 'status-returned',
    'cancelled' => 'status-cancelled',
];

$totalPages = method_exists($repairs, 'lastPage') ? $repairs->lastPage() : 1;
$currentPage = method_exists($repairs, 'currentPage') ? $repairs->currentPage() : 1;


@endphp

<div class="repair-report-page">


{{-- =========================================================
     PAGE HEADER
========================================================== --}}
<div class="page-header">
    <div class="header-content">
        <div class="header-icon">
            <i class="bi bi-tools"></i>
        </div>

        <div>
            <h1>Repair Reports</h1>
            <p>
                Monitor repair activity, costs, turnaround time and
                current repair status.
            </p>
        </div>
    </div>

    <div class="header-actions">
        <a href="{{ route('admin.reports.repairs.print', request()->query()) }}"
           target="_blank"
           class="btn btn-outline-primary">
            <i class="bi bi-printer me-1"></i>
            Print
        </a>

        <a href="{{ route('admin.reports.repairs.pdf', request()->query()) }}"
           target="_blank"
           class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i>
            PDF
        </a>

        <a href="{{ route('admin.reports.repairs.excel', request()->query()) }}"
           class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i>
            Excel
        </a>
    </div>
</div>


{{-- =========================================================
     REPORT NAVIGATION
========================================================== --}}
<div class="report-navigation">
    <a href="{{ route('admin.reports.repairs.index', array_merge(request()->query(), ['report_type' => 'summary'])) }}"
       class="report-nav-item active">
        <i class="bi bi-bar-chart-line"></i>
        <span>
            <strong>Summary</strong>
            <small>Repair overview</small>
        </span>
    </a>

    <a href="{{ route('admin.reports.repairs.index', array_merge(request()->query(), ['report_type' => 'pending_completed'])) }}"
       class="report-nav-item">
        <i class="bi bi-list-check"></i>
        <span>
            <strong>Pending & Completed</strong>
            <small>Status comparison</small>
        </span>
    </a>

    <a href="{{ route('admin.reports.repairs.index', array_merge(request()->query(), ['report_type' => 'cost'])) }}"
       class="report-nav-item">
        <i class="bi bi-cash-stack"></i>
        <span>
            <strong>Repair Cost</strong>
            <small>Cost analysis</small>
        </span>
    </a>
</div>


{{-- =========================================================
     FILTER CARD
========================================================== --}}
<div class="card report-card filter-card">

    <div class="card-header-custom">
        <div class="section-heading">
            <div class="section-icon">
                <i class="bi bi-funnel"></i>
            </div>

            <div>
                <h2>Report Filters</h2>
                <p>Filter repair records using the available criteria.</p>
            </div>
        </div>

        @if(request()->hasAny([
            'status',
            'employee_id',
            'branch_id',
            'department_id',
            'item_type_id',
            'from_date',
            'to_date'
        ]))
            <span class="filter-active-badge">
                <i class="bi bi-check-circle me-1"></i>
                Filters Applied
            </span>
        @endif
    </div>

    <div class="card-body">

        <form method="GET"
              action="{{ route('admin.reports.repairs.index') }}"
              id="repairReportFilterForm">

            <input type="hidden" name="report_type" value="summary">

            <div class="row g-3">

                {{-- Status --}}
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <label for="status" class="form-label">
                        Repair Status
                    </label>

                    <select name="status"
                            id="status"
                            class="form-select">
                        <option value="">All Statuses</option>

                        @foreach($statusLabels as $value => $label)
                            <option value="{{ $value }}"
                                {{ ($status ?? '') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- Employee --}}
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <label for="employee_id" class="form-label">
                        Employee
                    </label>

                    <select name="employee_id"
                            id="employee_id"
                            class="form-select">
                        <option value="">All Employees</option>

                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ (string)($employeeId ?? '') === (string)$employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                                @if(!empty($employee->epf_number))
                                    — {{ $employee->epf_number }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- Branch --}}
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <label for="branch_id" class="form-label">
                        Branch
                    </label>

                    <select name="branch_id"
                            id="branch_id"
                            class="form-select">
                        <option value="">All Branches</option>

                        @isset($branches)
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ (string)($branchId ?? '') === (string)$branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>


                {{-- Department --}}
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <label for="department_id" class="form-label">
                        Department
                    </label>

                    <select name="department_id"
                            id="department_id"
                            class="form-select">
                        <option value="">All Departments</option>

                        @isset($departments)
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ (string)($departmentId ?? '') === (string)$department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>


                {{-- Item Type --}}
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <label for="item_type_id" class="form-label">
                        Item Type
                    </label>

                    <select name="item_type_id"
                            id="item_type_id"
                            class="form-select">
                        <option value="">All Item Types</option>

                        @foreach($itemTypes as $itemType)
                            <option value="{{ $itemType->id }}"
                                {{ (string)($itemTypeId ?? '') === (string)$itemType->id ? 'selected' : '' }}>
                                {{ $itemType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- From Date --}}
                <div class="col-xl-1 col-lg-3 col-md-6">
                    <label for="from_date" class="form-label">
                        From
                    </label>

                    <input type="date"
                           name="from_date"
                           id="from_date"
                           value="{{ $fromDate ?? '' }}"
                           class="form-control">
                </div>


                {{-- To Date --}}
                <div class="col-xl-1 col-lg-3 col-md-6">
                    <label for="to_date" class="form-label">
                        To
                    </label>

                    <input type="date"
                           name="to_date"
                           id="to_date"
                           value="{{ $toDate ?? '' }}"
                           class="form-control">
                </div>


                {{-- Buttons --}}
                <div class="col-xl-12">
                    <div class="filter-actions">

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.reports.repairs.index') }}"
                           class="btn btn-light border">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>

                    </div>
                </div>

            </div>
        </form>

    </div>
</div>


{{-- =========================================================
     SUMMARY CARDS
========================================================== --}}
<div class="summary-grid">

    {{-- Total Repairs --}}
    <div class="summary-card">
        <div class="summary-icon total">
            <i class="bi bi-tools"></i>
        </div>

        <div class="summary-content">
            <span>Total Repairs</span>
            <strong>{{ number_format($totalRepairs ?? 0) }}</strong>
            <small>Filtered repair records</small>
        </div>
    </div>


    {{-- Pending --}}
    <div class="summary-card">
        <div class="summary-icon pending">
            <i class="bi bi-clock-history"></i>
        </div>

        <div class="summary-content">
            <span>Pending / In Progress</span>
            <strong>{{ number_format($pendingRepairs ?? 0) }}</strong>
            <small>Pending, sent or under repair</small>
        </div>
    </div>


    {{-- Completed --}}
    <div class="summary-card">
        <div class="summary-icon completed">
            <i class="bi bi-check2-circle"></i>
        </div>

        <div class="summary-content">
            <span>Completed / Returned</span>
            <strong>{{ number_format($completedRepairs ?? 0) }}</strong>
            <small>Successfully completed repairs</small>
        </div>
    </div>


    {{-- Cancelled --}}
    <div class="summary-card">
        <div class="summary-icon cancelled">
            <i class="bi bi-x-circle"></i>
        </div>

        <div class="summary-content">
            <span>Cancelled</span>
            <strong>{{ number_format($cancelledRepairs ?? 0) }}</strong>
            <small>Cancelled repair records</small>
        </div>
    </div>


    {{-- Total Cost --}}
    <div class="summary-card">
        <div class="summary-icon cost">
            <i class="bi bi-currency-dollar"></i>
        </div>

        <div class="summary-content">
            <span>Total Repair Cost</span>
            <strong>
                {{ number_format((float)($totalRepairCost ?? 0), 2) }}
            </strong>
            <small>Recorded repair expenditure</small>
        </div>
    </div>


    {{-- Average Cost --}}
    <div class="summary-card">
        <div class="summary-icon average">
            <i class="bi bi-calculator"></i>
        </div>

        <div class="summary-content">
            <span>Average Repair Cost</span>
            <strong>
                {{ number_format((float)($averageRepairCost ?? 0), 2) }}
            </strong>
            <small>Average cost per repair</small>
        </div>
    </div>


    {{-- Turnaround --}}
    <div class="summary-card">
        <div class="summary-icon turnaround">
            <i class="bi bi-stopwatch"></i>
        </div>

        <div class="summary-content">
            <span>Average Turnaround</span>
            <strong>
                {{ number_format((float)($averageTurnaroundDays ?? 0), 1) }}
                <small class="unit">days</small>
            </strong>
            <small>Completed / returned repairs</small>
        </div>
    </div>

</div>


{{-- =========================================================
     REPORT TABLE
========================================================== --}}
<div class="card report-card">

    <div class="card-header-custom">

        <div class="section-heading">
            <div class="section-icon">
                <i class="bi bi-table"></i>
            </div>

            <div>
                <h2>Repair Records</h2>
                <p>
                    Detailed repair information based on the selected filters.
                </p>
            </div>
        </div>

        <div class="record-count">
            <i class="bi bi-database me-1"></i>
            {{ number_format($repairs->total()) }} records
        </div>

    </div>


    <div class="card-body table-card-body">

        @if($repairs->count())

            <div class="table-responsive">

                <table class="table repair-table align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Repair Reference</th>
                            <th>Asset</th>
                            <th>Item Type</th>
                            <th>Employee</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Date Sent</th>
                            <th>Date Returned</th>
                            <th>Technician</th>
                            <th>Status</th>
                            <th class="text-end">Cost</th>
                            <th class="text-center">Turnaround</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($repairs as $index => $repair)

                            @php
                                $item = $repair->item;

                                $turnaroundDays = null;

                                if ($repair->date_sent && $repair->date_returned) {
                                    $turnaroundDays =
                                        $repair->date_sent->diffInDays($repair->date_returned);
                                }
                            @endphp

                            <tr>

                                <td class="text-muted fw-semibold">
                                    {{ $repairs->firstItem() + $index }}
                                </td>


                                {{-- Repair Reference --}}
                                <td>
                                    <div class="reference-cell">
                                        <span class="reference-code">
                                            {{ $repair->repair_reference ?: '—' }}
                                        </span>

                                        @if($repair->problem_description)
                                            <small title="{{ $repair->problem_description }}">
                                                {{ \Illuminate\Support\Str::limit($repair->problem_description, 35) }}
                                            </small>
                                        @endif
                                    </div>
                                </td>


                                {{-- Asset --}}
                                <td>
                                    @if($item)
                                        <div class="asset-cell">
                                            <strong>
                                                {{ $item->asset_number ?: 'N/A' }}
                                            </strong>

                                            @if($item->serial_number)
                                                <small>
                                                    SN: {{ $item->serial_number }}
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>


                                {{-- Item Type --}}
                                <td>
                                    {{ $item?->itemType?->name ?? 'N/A' }}
                                </td>


                                {{-- Employee --}}
                                <td>
                                    <div class="person-cell">
                                        <strong>
                                            {{ $repair->employee?->name ?? 'N/A' }}
                                        </strong>

                                        @if($repair->employee?->epf_number)
                                            <small>
                                                {{ $repair->employee->epf_number }}
                                            </small>
                                        @endif
                                    </div>
                                </td>


                                {{-- Branch --}}
                                <td>
                                    {{ $item?->branch?->name ?? 'N/A' }}
                                </td>


                                {{-- Department --}}
                                <td>
                                    {{ $item?->department?->name ?? 'N/A' }}
                                </td>


                                {{-- Date Sent --}}
                                <td>
                                    @if($repair->date_sent)
                                        <span class="date-value">
                                            {{ $repair->date_sent->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>


                                {{-- Date Returned --}}
                                <td>
                                    @if($repair->date_returned)
                                        <span class="date-value">
                                            {{ $repair->date_returned->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>


                                {{-- Technician --}}
                                <td>
                                    {{ $repair->technician ?: '—' }}
                                </td>


                                {{-- Status --}}
                                <td>
                                    @php
                                        $statusValue = $repair->status;
                                        $statusLabel = $statusLabels[$statusValue] ?? ucwords(str_replace('_', ' ', $statusValue));
                                        $statusClass = $statusClasses[$statusValue] ?? 'status-default';
                                    @endphp

                                    <span class="status-badge {{ $statusClass }}">
                                        <span class="status-dot"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>


                                {{-- Cost --}}
                                <td class="text-end">
                                    <span class="cost-value">
                                        {{ number_format((float)($repair->repair_cost ?? 0), 2) }}
                                    </span>
                                </td>


                                {{-- Turnaround --}}
                                <td class="text-center">
                                    @if($turnaroundDays !== null)
                                        <span class="turnaround-badge">
                                            {{ $turnaroundDays }} day{{ $turnaroundDays == 1 ? '' : 's' }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =====================================================
                 PAGINATION
            ====================================================== --}}
            @if($repairs->hasPages())

                <div class="pagination-wrapper">

                    <div class="pagination-info">
                        Showing
                        <strong>{{ $repairs->firstItem() }}</strong>
                        to
                        <strong>{{ $repairs->lastItem() }}</strong>
                        of
                        <strong>{{ $repairs->total() }}</strong>
                        repair records
                    </div>

                    <div>
                        {{ $repairs->withQueryString()->links() }}
                    </div>

                </div>

            @endif

        @else

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}
            <div class="empty-state">

                <div class="empty-icon">
                    <i class="bi bi-tools"></i>
                </div>

                <h3>No Repair Records Found</h3>

                <p>
                    No repair records match the selected report filters.
                    Try changing the filters or resetting the report.
                </p>

                <a href="{{ route('admin.reports.repairs.index') }}"
                   class="btn btn-primary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Reset Filters
                </a>

            </div>

        @endif

    </div>

</div>

</div>

{{-- =============================================================
PAGE STYLES
============================================================= --}}

<style>

    .repair-report-page {
        width: 100%;
        padding-bottom: 2rem;
    }

    /* ---------------------------------------------------------
       HEADER
    --------------------------------------------------------- */

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(13, 110, 253, 0.10);
        color: #0d6efd;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .page-header h1 {
        margin: 0;
        font-size: 1.55rem;
        font-weight: 700;
        color: #212529;
    }

    .page-header p {
        margin: .25rem 0 0;
        color: #6c757d;
        font-size: .88rem;
    }

    .header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .header-actions .btn {
        white-space: nowrap;
    }


    /* ---------------------------------------------------------
       NAVIGATION
    --------------------------------------------------------- */

    .report-navigation {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .75rem;
        margin-bottom: 1.25rem;
    }

    .report-nav-item {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: .9rem 1rem;
        border: 1px solid #e4e8ec;
        border-radius: 12px;
        background: #fff;
        color: #495057;
        text-decoration: none;
        transition: all .2s ease;
    }

    .report-nav-item:hover {
        border-color: #0d6efd;
        color: #0d6efd;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, .05);
    }

    .report-nav-item.active {
        border-color: #0d6efd;
        background: rgba(13, 110, 253, .045);
        color: #0d6efd;
    }

    .report-nav-item > i {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(13, 110, 253, .10);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .report-nav-item strong,
    .report-nav-item small {
        display: block;
    }

    .report-nav-item strong {
        font-size: .88rem;
        font-weight: 700;
    }

    .report-nav-item small {
        margin-top: .15rem;
        color: #7a828a;
        font-size: .74rem;
    }


    /* ---------------------------------------------------------
       CARDS
    --------------------------------------------------------- */

    .report-card {
        border: 1px solid #e6e9ed;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .card-header-custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.15rem;
        border-bottom: 1px solid #edf0f2;
        background: #fff;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .section-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(13, 110, 253, .09);
        color: #0d6efd;
    }

    .section-heading h2 {
        margin: 0;
        font-size: .98rem;
        font-weight: 700;
        color: #212529;
    }

    .section-heading p {
        margin: .15rem 0 0;
        font-size: .76rem;
        color: #7a828a;
    }

    .card-body {
        padding: 1.15rem;
    }


    /* ---------------------------------------------------------
       FILTERS
    --------------------------------------------------------- */

    .filter-active-badge {
        display: inline-flex;
        align-items: center;
        padding: .4rem .7rem;
        border-radius: 20px;
        background: rgba(25, 135, 84, .09);
        color: #198754;
        font-size: .73rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .form-label {
        margin-bottom: .4rem;
        color: #495057;
        font-size: .77rem;
        font-weight: 600;
    }

    .form-select,
    .form-control {
        min-height: 39px;
        border-color: #dfe3e7;
        border-radius: 8px;
        font-size: .8rem;
        box-shadow: none;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .18rem rgba(13, 110, 253, .08);
    }

    .filter-actions {
        display: flex;
        gap: .5rem;
        padding-top: .35rem;
    }

    .filter-actions .btn {
        min-height: 39px;
        border-radius: 8px;
        font-size: .8rem;
        font-weight: 600;
    }


    /* ---------------------------------------------------------
       SUMMARY
    --------------------------------------------------------- */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .9rem;
        margin-bottom: 1.25rem;
    }

    .summary-card {
        min-width: 0;
        display: flex;
        align-items: center;
        gap: .8rem;
        padding: 1rem;
        background: #fff;
        border: 1px solid #e6e9ed;
        border-radius: 13px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
    }

    .summary-icon {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .summary-icon.total {
        background: rgba(13, 110, 253, .10);
        color: #0d6efd;
    }

    .summary-icon.pending {
        background: rgba(255, 193, 7, .13);
        color: #b58100;
    }

    .summary-icon.completed {
        background: rgba(25, 135, 84, .10);
        color: #198754;
    }

    .summary-icon.cancelled {
        background: rgba(220, 53, 69, .10);
        color: #dc3545;
    }

    .summary-icon.cost {
        background: rgba(111, 66, 193, .10);
        color: #6f42c1;
    }

    .summary-icon.average {
        background: rgba(13, 202, 240, .11);
        color: #087990;
    }

    .summary-icon.turnaround {
        background: rgba(108, 117, 125, .10);
        color: #495057;
    }

    .summary-content {
        min-width: 0;
    }

    .summary-content > span {
        display: block;
        color: #6c757d;
        font-size: .72rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .summary-content > strong {
        display: block;
        margin-top: .12rem;
        color: #212529;
        font-size: 1.2rem;
        line-height: 1.25;
        font-weight: 750;
        white-space: nowrap;
    }

    .summary-content > small {
        display: block;
        margin-top: .12rem;
        color: #9aa1a8;
        font-size: .66rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .summary-content .unit {
        font-size: .7rem;
        font-weight: 600;
        color: #6c757d;
    }


    /* ---------------------------------------------------------
       TABLE
    --------------------------------------------------------- */

    .table-card-body {
        padding: 0;
    }

    .record-count {
        color: #6c757d;
        font-size: .76rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .repair-table {
        min-width: 1500px;
        margin: 0;
    }

    .repair-table thead th {
        padding: .8rem .75rem;
        border-bottom: 1px solid #dfe3e7;
        background: #f8f9fa;
        color: #5f6871;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .025em;
        white-space: nowrap;
    }

    .repair-table tbody td {
        padding: .8rem .75rem;
        border-bottom: 1px solid #edf0f2;
        color: #3d454d;
        font-size: .76rem;
        white-space: nowrap;
    }

    .repair-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .repair-table tbody tr:hover {
        background: #fafbfd;
    }

    .reference-cell,
    .asset-cell,
    .person-cell {
        display: flex;
        flex-direction: column;
        gap: .12rem;
    }

    .reference-code {
        color: #0d6efd;
        font-weight: 700;
    }

    .reference-cell small,
    .asset-cell small,
    .person-cell small {
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #8a929a;
        font-size: .66rem;
    }

    .asset-cell strong,
    .person-cell strong {
        font-weight: 650;
        color: #343a40;
    }

    .date-value {
        color: #495057;
        font-size: .74rem;
    }

    .cost-value {
        color: #495057;
        font-weight: 700;
    }


    /* ---------------------------------------------------------
       STATUS
    --------------------------------------------------------- */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .34rem .55rem;
        border-radius: 20px;
        font-size: .66rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-pending {
        color: #996c00;
        background: rgba(255, 193, 7, .13);
    }

    .status-sent {
        color: #0b7285;
        background: rgba(13, 202, 240, .12);
    }

    .status-under-repair {
        color: #664d03;
        background: rgba(255, 193, 7, .18);
    }

    .status-completed {
        color: #146c43;
        background: rgba(25, 135, 84, .11);
    }

    .status-returned {
        color: #0f5132;
        background: rgba(25, 135, 84, .14);
    }

    .status-cancelled {
        color: #b02a37;
        background: rgba(220, 53, 69, .10);
    }

    .status-default {
        color: #495057;
        background: #f1f3f5;
    }

    .turnaround-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 55px;
        padding: .3rem .45rem;
        border-radius: 6px;
        background: #f1f3f5;
        color: #495057;
        font-size: .67rem;
        font-weight: 650;
    }


    /* ---------------------------------------------------------
       PAGINATION
    --------------------------------------------------------- */

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: .9rem 1.15rem;
        border-top: 1px solid #edf0f2;
    }

    .pagination-info {
        color: #7a828a;
        font-size: .74rem;
    }

    .pagination-info strong {
        color: #495057;
    }

    .pagination-wrapper .pagination {
        margin: 0;
    }

    .pagination-wrapper .page-link {
        border-radius: 7px;
        margin: 0 .12rem;
        font-size: .74rem;
    }


    /* ---------------------------------------------------------
       EMPTY STATE
    --------------------------------------------------------- */

    .empty-state {
        padding: 4rem 1.5rem;
        text-align: center;
    }

    .empty-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: #f1f3f5;
        color: #8a929a;
        font-size: 1.5rem;
    }

    .empty-state h3 {
        margin: 0;
        color: #343a40;
        font-size: 1rem;
        font-weight: 700;
    }

    .empty-state p {
        max-width: 500px;
        margin: .5rem auto 1.2rem;
        color: #7a828a;
        font-size: .78rem;
        line-height: 1.6;
    }


    /* ---------------------------------------------------------
       RESPONSIVE
    --------------------------------------------------------- */

    @media (max-width: 1400px) {

        .summary-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

    }


    @media (max-width: 1100px) {

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .report-navigation {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 768px) {

        .page-header h1 {
            font-size: 1.3rem;
        }

        .header-content {
            align-items: flex-start;
        }

        .header-icon {
            width: 45px;
            height: 45px;
        }

        .header-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }

        .header-actions .btn {
            width: 100%;
        }

        .summary-grid {
            grid-template-columns: 1fr 1fr;
            gap: .65rem;
        }

        .summary-card {
            padding: .8rem;
        }

        .summary-icon {
            width: 38px;
            height: 38px;
        }

        .summary-content > strong {
            font-size: 1rem;
        }

        .card-header-custom {
            align-items: flex-start;
            flex-direction: column;
        }

        .record-count {
            align-self: flex-start;
        }

        .pagination-wrapper {
            align-items: flex-start;
            flex-direction: column;
        }

    }


    @media (max-width: 520px) {

        .header-actions {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            width: 100%;
            flex-direction: column;
        }

        .filter-actions .btn {
            width: 100%;
        }

        .section-heading p {
            display: none;
        }

    }

</style>

@endsection
