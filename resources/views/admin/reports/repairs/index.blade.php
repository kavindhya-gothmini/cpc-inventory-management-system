@extends('layouts.admin')

@section('title', 'Repair Reports | CPC Inventory Management System')

@section('content')

@php

    $selectedReportType =
        $reportType ?? request('report_type', 'summary');

    $selectedStatus =
        $status ?? request('status');

    $selectedEmployeeId =
        $employeeId ?? request('employee_id');

    $selectedBranchId =
        $branchId ?? request('branch_id');

    $selectedDepartmentId =
        $departmentId ?? request('department_id');

    $selectedFunctionUnitId =
        $functionUnitId ?? request('function_unit_id');

    $selectedItemTypeId =
        $itemTypeId ?? request('item_type_id');

    $selectedTechnician =
        $technician ?? request('technician');

    $selectedFromDate =
        $fromDate ?? request('from_date');

    $selectedToDate =
        $toDate ?? request('to_date');


    $reportTitles = [
        'summary' => 'Repair Summary',
        'pending' => 'Pending Repairs',
        'completed' => 'Completed Repairs',
        'cost' => 'Repair Cost Report',
    ];

    $reportTitle =
        $reportTitles[$selectedReportType]
        ?? 'Repair Summary';


    $hasFilters =
        $selectedReportType !== 'summary'
        || $selectedStatus
        || $selectedEmployeeId
        || $selectedBranchId
        || $selectedDepartmentId
        || $selectedFunctionUnitId
        || $selectedItemTypeId
        || $selectedTechnician
        || $selectedFromDate
        || $selectedToDate;

@endphp


<div class="container-fluid py-3">

    {{-- ==========================================================
         PAGE HEADER
    =========================================================== --}}

    <div class="report-page-header mb-4">

        <div class="d-flex flex-column flex-lg-row
                    justify-content-between
                    align-items-lg-center gap-3">

            <div>

                <div class="d-flex align-items-center gap-2 mb-2">

                    <span class="report-header-icon">
                        <i class="bi bi-tools"></i>
                    </span>

                    <span class="text-uppercase
                                 small fw-semibold
                                 text-primary">
                        Reports
                    </span>

                </div>

                <h1 class="report-title mb-1">
                    Repair Reports
                </h1>

                <p class="report-subtitle mb-0">
                    Monitor repair activities, repair status,
                    costs, turnaround time and repair history.
                </p>

            </div>


            <div class="d-flex flex-wrap gap-2">

                <a
                    href="{{ route('admin.reports.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Reports Dashboard
                </a>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         FILTER CARD
    =========================================================== --}}

    <div class="card report-card border-0 shadow-sm mb-4">

        <div class="card-header report-card-header">

            <div class="d-flex
                        justify-content-between
                        align-items-center">

                <div>

                    <h5 class="mb-1 fw-semibold">
                        <i class="bi bi-funnel me-2 text-primary"></i>
                        Repair Report Filters
                    </h5>

                    <small class="text-muted">
                        Select the report type and criteria
                        to generate the required repair report.
                    </small>

                </div>

                @if($hasFilters)

                    <span class="filter-active-badge">
                        <i class="bi bi-check-circle me-1"></i>
                        Filters Applied
                    </span>

                @endif

            </div>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.repairs.index') }}"
            >

                <div class="row g-3">

                    {{-- Report Type --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            Report Type
                        </label>

                        <select
                            name="report_type"
                            class="form-select"
                        >

                            <option
                                value="summary"
                                @selected($selectedReportType === 'summary')
                            >
                                Repair Summary
                            </option>

                            <option
                                value="pending"
                                @selected($selectedReportType === 'pending')
                            >
                                Pending Repairs
                            </option>

                            <option
                                value="completed"
                                @selected($selectedReportType === 'completed')
                            >
                                Completed Repairs
                            </option>

                            <option
                                value="cost"
                                @selected($selectedReportType === 'cost')
                            >
                                Repair Cost Report
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            Repair Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="pending"
                                @selected($selectedStatus === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="sent_for_repair"
                                @selected($selectedStatus === 'sent_for_repair')
                            >
                                Sent for Repair
                            </option>

                            <option
                                value="under_repair"
                                @selected($selectedStatus === 'under_repair')
                            >
                                Under Repair
                            </option>

                            <option
                                value="completed"
                                @selected($selectedStatus === 'completed')
                            >
                                Completed
                            </option>

                            <option
                                value="returned"
                                @selected($selectedStatus === 'returned')
                            >
                                Returned
                            </option>

                            <option
                                value="cancelled"
                                @selected($selectedStatus === 'cancelled')
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Employee --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            Employee
                        </label>

                        <select
                            name="employee_id"
                            class="form-select"
                        >

                            <option value="">
                                All Employees
                            </option>

                            @foreach($employees as $employee)

                                <option
                                    value="{{ $employee->id }}"
                                    @selected(
                                        (string) $selectedEmployeeId ===
                                        (string) $employee->id
                                    )
                                >
                                    {{ $employee->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Branch --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            Branch
                        </label>

                        <select
                            name="branch_id"
                            class="form-select"
                        >

                            <option value="">
                                All Branches
                            </option>

                            @foreach($branches as $branch)

                                <option
                                    value="{{ $branch->id }}"
                                    @selected(
                                        (string) $selectedBranchId ===
                                        (string) $branch->id
                                    )
                                >
                                    {{ $branch->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Department --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            Department
                        </label>

                        <select
                            name="department_id"
                            class="form-select"
                        >

                            <option value="">
                                All Departments
                            </option>

                            @foreach($departments as $department)

                                <option
                                    value="{{ $department->id }}"
                                    @selected(
                                        (string) $selectedDepartmentId ===
                                        (string) $department->id
                                    )
                                >
                                    {{ $department->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Function Unit --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            Function Unit
                            <span class="text-muted">
                                (Optional)
                            </span>
                        </label>

                        <select
                            name="function_unit_id"
                            class="form-select"
                        >

                            <option value="">
                                All Function Units
                            </option>

                            @foreach($functionUnits as $functionUnit)

                                <option
                                    value="{{ $functionUnit->id }}"
                                    @selected(
                                        (string) $selectedFunctionUnitId ===
                                        (string) $functionUnit->id
                                    )
                                >
                                    {{ $functionUnit->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Item Type --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            Item Type
                        </label>

                        <select
                            name="item_type_id"
                            class="form-select"
                        >

                            <option value="">
                                All Item Types
                            </option>

                            @foreach($itemTypes as $itemType)

                                <option
                                    value="{{ $itemType->id }}"
                                    @selected(
                                        (string) $selectedItemTypeId ===
                                        (string) $itemType->id
                                    )
                                >
                                    {{ $itemType->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Technician --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            Technician
                        </label>

                        <input
                            type="text"
                            name="technician"
                            value="{{ $selectedTechnician }}"
                            class="form-control"
                            placeholder="Search technician..."
                        >

                    </div>


                    {{-- From Date --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            value="{{ $selectedFromDate }}"
                            class="form-control"
                        >

                    </div>


                    {{-- To Date --}}

                    <div class="col-12 col-md-6 col-xl-3">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            value="{{ $selectedToDate }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Actions --}}

                    <div class="col-12">

                        <div class="d-flex
                                    flex-wrap
                                    gap-2
                                    pt-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-bar-chart-line me-1"></i>
                                Generate Report
                            </button>

                            <a
                                href="{{ route('admin.reports.repairs.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ==========================================================
         SUMMARY CARDS
    =========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total Repairs --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="summary-card">

                <div class="summary-icon summary-primary">
                    <i class="bi bi-tools"></i>
                </div>

                <div>

                    <div class="summary-label">
                        Total Repairs
                    </div>

                    <div class="summary-value">
                        {{ number_format($filteredTotalRepairs) }}
                    </div>

                    <div class="summary-meta">
                        Overall:
                        {{ number_format($totalRepairs) }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Pending --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="summary-card">

                <div class="summary-icon summary-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>

                    <div class="summary-label">
                        Pending Repairs
                    </div>

                    <div class="summary-value">
                        {{ number_format($filteredPendingRepairs) }}
                    </div>

                    <div class="summary-meta">
                        Overall:
                        {{ number_format($pendingRepairs) }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="summary-card">

                <div class="summary-icon summary-success">
                    <i class="bi bi-check2-circle"></i>
                </div>

                <div>

                    <div class="summary-label">
                        Completed
                    </div>

                    <div class="summary-value">
                        {{ number_format($filteredCompletedRepairs) }}
                    </div>

                    <div class="summary-meta">
                        Overall:
                        {{ number_format($completedRepairs) }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Repair Cost --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="summary-card">

                <div class="summary-icon summary-danger">
                    <i class="bi bi-currency-dollar"></i>
                </div>

                <div>

                    <div class="summary-label">
                        Repair Cost
                    </div>

                    <div class="summary-value">
                        {{ number_format($totalRepairCost, 2) }}
                    </div>

                    <div class="summary-meta">
                        Avg:
                        {{ number_format($averageRepairCost ?? 0, 2) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         PERFORMANCE SUMMARY
    =========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-12 col-md-4">

            <div class="metric-card">

                <div class="metric-icon">
                    <i class="bi bi-x-circle"></i>
                </div>

                <div>

                    <span>
                        Cancelled Repairs
                    </span>

                    <strong>
                        {{ number_format($cancelledRepairs) }}
                    </strong>

                </div>

            </div>

        </div>


        <div class="col-12 col-md-4">

            <div class="metric-card">

                <div class="metric-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>

                    <span>
                        Avg. Turnaround
                    </span>

                    <strong>
                        {{ number_format($averageTurnaroundDays, 1) }}
                        days
                    </strong>

                </div>

            </div>

        </div>


        <div class="col-12 col-md-4">

            <div class="metric-card">

                <div class="metric-icon">
                    <i class="bi bi-clipboard-data"></i>
                </div>

                <div>

                    <span>
                        Report
                    </span>

                    <strong>
                        {{ $reportTitle }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         ACTIVE FILTER SUMMARY
    =========================================================== --}}

    @if($hasFilters)

        <div class="active-filter-card mb-4">

            <div class="active-filter-title">

                <i class="bi bi-funnel-fill me-2"></i>

                Active Report Criteria

            </div>

            <div class="filter-chips">

                @if($selectedReportType !== 'summary')

                    <span class="filter-chip">
                        Report:
                        {{ $reportTitle }}
                    </span>

                @endif


                @if($selectedStatus)

                    <span class="filter-chip">
                        Status:
                        {{ ucwords(str_replace('_', ' ', $selectedStatus)) }}
                    </span>

                @endif


                @if($selectedEmployeeId)

                    @php
                        $selectedEmployee =
                            $employees->firstWhere(
                                'id',
                                $selectedEmployeeId
                            );
                    @endphp

                    @if($selectedEmployee)

                        <span class="filter-chip">
                            Employee:
                            {{ $selectedEmployee->name }}
                        </span>

                    @endif

                @endif


                @if($selectedBranchId)

                    @php
                        $selectedBranch =
                            $branches->firstWhere(
                                'id',
                                $selectedBranchId
                            );
                    @endphp

                    @if($selectedBranch)

                        <span class="filter-chip">
                            Branch:
                            {{ $selectedBranch->name }}
                        </span>

                    @endif

                @endif


                @if($selectedDepartmentId)

                    @php
                        $selectedDepartment =
                            $departments->firstWhere(
                                'id',
                                $selectedDepartmentId
                            );
                    @endphp

                    @if($selectedDepartment)

                        <span class="filter-chip">
                            Department:
                            {{ $selectedDepartment->name }}
                        </span>

                    @endif

                @endif


                @if($selectedFunctionUnitId)

                    @php
                        $selectedFunctionUnit =
                            $functionUnits->firstWhere(
                                'id',
                                $selectedFunctionUnitId
                            );
                    @endphp

                    @if($selectedFunctionUnit)

                        <span class="filter-chip">
                            Function Unit:
                            {{ $selectedFunctionUnit->name }}
                        </span>

                    @endif

                @endif


                @if($selectedItemTypeId)

                    @php
                        $selectedItemType =
                            $itemTypes->firstWhere(
                                'id',
                                $selectedItemTypeId
                            );
                    @endphp

                    @if($selectedItemType)

                        <span class="filter-chip">
                            Item Type:
                            {{ $selectedItemType->name }}
                        </span>

                    @endif

                @endif


                @if($selectedTechnician)

                    <span class="filter-chip">
                        Technician:
                        {{ $selectedTechnician }}
                    </span>

                @endif


                @if($selectedFromDate)

                    <span class="filter-chip">
                        From:
                        {{ \Carbon\Carbon::parse($selectedFromDate)->format('d M Y') }}
                    </span>

                @endif


                @if($selectedToDate)

                    <span class="filter-chip">
                        To:
                        {{ \Carbon\Carbon::parse($selectedToDate)->format('d M Y') }}
                    </span>

                @endif

            </div>

        </div>

    @endif


    {{-- ==========================================================
         RESULTS
    =========================================================== --}}

    <div class="card report-card border-0 shadow-sm">

        <div class="card-header report-card-header">

            <div class="d-flex
                        flex-column
                        flex-lg-row
                        justify-content-between
                        align-items-lg-center
                        gap-3">

                <div>

                    <h5 class="mb-1 fw-semibold">

                        <i class="bi bi-table me-2 text-primary"></i>

                        {{ $reportTitle }}

                    </h5>

                    <small class="text-muted">

                        Showing
                        <strong>
                            {{ number_format($repairs->total()) }}
                        </strong>
                        repair records

                    </small>

                </div>


                {{-- Export Buttons --}}

                <div class="d-flex flex-wrap gap-2">

                    <a
                        href="{{ route('admin.reports.repairs.print', request()->query()) }}"
                        target="_blank"
                        class="btn btn-outline-secondary btn-sm"
                    >
                        <i class="bi bi-printer me-1"></i>
                        Print
                    </a>

                    <a
                        href="{{ route('admin.reports.repairs.pdf', request()->query()) }}"
                        class="btn btn-outline-danger btn-sm"
                    >
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        PDF
                    </a>

                    <a
                        href="{{ route('admin.reports.repairs.excel', request()->query()) }}"
                        class="btn btn-outline-success btn-sm"
                    >
                        <i class="bi bi-file-earmark-excel me-1"></i>
                        Excel
                    </a>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover
                              align-middle
                              mb-0
                              repair-report-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Asset</th>

                            <th>Item Type</th>

                            <th>Employee</th>

                            <th>Branch</th>

                            <th>Problem</th>

                            <th>Technician</th>

                            <th>Date Sent</th>

                            <th>Date Returned</th>

                            <th>Status</th>

                            <th class="text-end">
                                Cost
                            </th>

                            <th>
                                Turnaround
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($repairs as $index => $repair)

                            @php

                                $turnaroundDays = null;

                                if (
                                    $repair->date_sent &&
                                    $repair->date_returned
                                ) {

                                    $turnaroundDays =
                                        $repair->date_sent
                                            ->diffInDays(
                                                $repair->date_returned
                                            );
                                }

                                $statusClass = match(
                                    $repair->status
                                ) {

                                    'pending' =>
                                        'status-warning',

                                    'sent_for_repair' =>
                                        'status-info',

                                    'under_repair' =>
                                        'status-primary',

                                    'completed' =>
                                        'status-success',

                                    'returned' =>
                                        'status-success',

                                    'cancelled' =>
                                        'status-danger',

                                    default =>
                                        'status-secondary',
                                };

                            @endphp

                            <tr>

                                <td class="text-muted">
                                    {{ $repairs->firstItem() + $index }}
                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ optional($repair->item)->asset_number
                                            ?? '—' }}

                                    </div>

                                    @if($repair->repair_reference)

                                        <small class="text-muted">

                                            Ref:
                                            {{ $repair->repair_reference }}

                                        </small>

                                    @endif

                                </td>


                                <td>

                                    {{ optional(
                                        optional($repair->item)->itemType
                                    )->name ?? '—' }}

                                </td>


                                <td>

                                    {{ optional($repair->employee)->name
                                        ?? '—' }}

                                </td>


                                <td>

                                    {{ optional(
                                        optional($repair->item)->branch
                                    )->name ?? '—' }}

                                </td>


                                <td>

                                    <span
                                        class="problem-text"
                                        title="{{ $repair->problem_description }}"
                                    >
                                        {{ \Illuminate\Support\Str::limit(
                                            $repair->problem_description,
                                            35
                                        ) }}
                                    </span>

                                </td>


                                <td>

                                    {{ $repair->technician ?: '—' }}

                                </td>


                                <td>

                                    {{ $repair->date_sent
                                        ? $repair->date_sent->format('d M Y')
                                        : '—' }}

                                </td>


                                <td>

                                    {{ $repair->date_returned
                                        ? $repair->date_returned->format('d M Y')
                                        : '—' }}

                                </td>


                                <td>

                                    <span
                                        class="status-badge {{ $statusClass }}"
                                    >
                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $repair->status
                                            )
                                        ) }}
                                    </span>

                                </td>


                                <td class="text-end fw-semibold">

                                    {{ number_format(
                                        (float) ($repair->repair_cost ?? 0),
                                        2
                                    ) }}

                                </td>


                                <td>

                                    @if($turnaroundDays !== null)

                                        <span class="turnaround-badge">

                                            {{ $turnaroundDays }}
                                            {{ $turnaroundDays == 1 ? 'day' : 'days' }}

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

                                <td
                                    colspan="12"
                                    class="text-center py-5"
                                >

                                    <div class="empty-state">

                                        <i class="bi bi-tools"></i>

                                        <h6>
                                            No repair records found
                                        </h6>

                                        <p>
                                            No repairs match the
                                            selected report criteria.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($repairs->hasPages())

            <div class="card-footer bg-white border-0">

                <div class="d-flex
                            flex-column
                            flex-md-row
                            justify-content-between
                            align-items-md-center
                            gap-2">

                    <small class="text-muted">

                        Showing
                        {{ number_format($repairs->firstItem()) }}
                        –
                        {{ number_format($repairs->lastItem()) }}
                        of
                        {{ number_format($repairs->total()) }}
                        records

                    </small>

                    <div>
                        {{ $repairs->links() }}
                    </div>

                </div>

            </div>

        @endif

    </div>

</div>


@push('styles')

<style>

    .report-page-header {
        padding: .25rem 0;
    }

    .report-header-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(13, 110, 253, .1);
        color: #0d6efd;
        font-size: 1.15rem;
    }

    .report-title {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -.02em;
    }

    .report-subtitle {
        color: #6c757d;
        font-size: .95rem;
    }

    .report-card {
        border-radius: 14px;
        overflow: hidden;
    }

    .report-card-header {
        background: #fff;
        border-bottom: 1px solid #edf0f3;
        padding: 1rem 1.15rem;
    }

    .form-label {
        font-size: .82rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: .4rem;
    }

    .form-select,
    .form-control {
        min-height: 42px;
        border-color: #dfe3e8;
        border-radius: 9px;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .08);
    }

    .filter-active-badge {
        display: inline-flex;
        align-items: center;
        padding: .35rem .65rem;
        border-radius: 999px;
        background: rgba(25, 135, 84, .1);
        color: #198754;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .summary-card {
        height: 100%;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.15rem;
        background: #fff;
        border: 1px solid #edf0f3;
        border-radius: 14px;
        box-shadow: 0 .125rem .35rem rgba(0,0,0,.04);
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
    }

    .summary-primary {
        background: rgba(13,110,253,.1);
        color: #0d6efd;
    }

    .summary-warning {
        background: rgba(255,193,7,.14);
        color: #997404;
    }

    .summary-success {
        background: rgba(25,135,84,.1);
        color: #198754;
    }

    .summary-danger {
        background: rgba(220,53,69,.1);
        color: #dc3545;
    }

    .summary-label {
        color: #6c757d;
        font-size: .78rem;
        font-weight: 600;
        margin-bottom: .15rem;
    }

    .summary-value {
        color: #212529;
        font-size: 1.35rem;
        line-height: 1.2;
        font-weight: 700;
    }

    .summary-meta {
        color: #8a9198;
        font-size: .72rem;
        margin-top: .2rem;
    }

    .metric-card {
        height: 100%;
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: 1rem;
        border: 1px solid #edf0f3;
        border-radius: 12px;
        background: #fff;
    }

    .metric-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f4f6f8;
        color: #495057;
    }

    .metric-card span {
        display: block;
        color: #6c757d;
        font-size: .75rem;
    }

    .metric-card strong {
        display: block;
        margin-top: .15rem;
        color: #212529;
        font-size: .95rem;
    }

    .active-filter-card {
        padding: .9rem 1rem;
        border: 1px solid #dce8f8;
        border-radius: 12px;
        background: #f8fbff;
    }

    .active-filter-title {
        font-size: .8rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: .65rem;
    }

    .filter-chips {
        display: flex;
        flex-wrap: wrap;
        gap: .45rem;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        padding: .35rem .65rem;
        border-radius: 999px;
        background: #fff;
        border: 1px solid #dce8f8;
        color: #495057;
        font-size: .75rem;
    }

    .repair-report-table {
        min-width: 1250px;
    }

    .repair-report-table thead th {
        padding: .85rem .8rem;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        color: #495057;
        font-size: .73rem;
        font-weight: 700;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .repair-report-table tbody td {
        padding: .8rem;
        border-color: #f0f2f4;
        font-size: .82rem;
        white-space: nowrap;
    }

    .problem-text {
        display: inline-block;
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: .3rem .55rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-warning {
        background: rgba(255,193,7,.14);
        color: #997404;
    }

    .status-info {
        background: rgba(13,202,240,.12);
        color: #087990;
    }

    .status-primary {
        background: rgba(13,110,253,.1);
        color: #0d6efd;
    }

    .status-success {
        background: rgba(25,135,84,.1);
        color: #198754;
    }

    .status-danger {
        background: rgba(220,53,69,.1);
        color: #dc3545;
    }

    .status-secondary {
        background: #f1f3f5;
        color: #6c757d;
    }

    .turnaround-badge {
        display: inline-flex;
        padding: .3rem .5rem;
        border-radius: 7px;
        background: #f5f7fa;
        color: #495057;
        font-size: .72rem;
        font-weight: 600;
    }

    .empty-state {
        color: #6c757d;
    }

    .empty-state i {
        display: block;
        font-size: 2.25rem;
        margin-bottom: .65rem;
        color: #adb5bd;
    }

    .empty-state h6 {
        color: #495057;
        margin-bottom: .3rem;
    }

    .empty-state p {
        margin-bottom: 0;
        font-size: .82rem;
    }

    @media (max-width: 767.98px) {

        .report-title {
            font-size: 1.45rem;
        }

        .report-card-header {
            padding: .9rem;
        }

        .summary-card {
            padding: 1rem;
        }

    }

</style>

@endpush

@endsection