@extends('layouts.admin')

@section('title', 'Assignment Reports | CPC Inventory Management System')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Current Report Context
    |--------------------------------------------------------------------------
    */

    $selectedReportType = $reportType ?? request('report_type', 'current');
    $selectedStatus = $status ?? request('status');
    $selectedEmployee = $employeeId ?? request('employee_id');
    $selectedBranch = $branchId ?? request('branch_id');
    $selectedDepartment = $departmentId ?? request('department_id');
    $selectedFunctionUnit = $functionUnitId ?? request('function_unit_id');
    $selectedItemType = $itemTypeId ?? request('item_type_id');
    $selectedFromDate = $fromDate ?? request('from_date');
    $selectedToDate = $toDate ?? request('to_date');

    $hasFilters =
        $selectedReportType !== 'current' ||
        filled($selectedStatus) ||
        filled($selectedEmployee) ||
        filled($selectedBranch) ||
        filled($selectedDepartment) ||
        filled($selectedFunctionUnit) ||
        filled($selectedItemType) ||
        filled($selectedFromDate) ||
        filled($selectedToDate);

    /*
    |--------------------------------------------------------------------------
    | Report Labels
    |--------------------------------------------------------------------------
    */

    $reportTypeLabels = [
        'current' => 'Current Assignments',
        'history' => 'Assignment History',
        'employee' => 'Employee Assets',
        'unassigned' => 'Unassigned Assets',
    ];

    $currentReportLabel =
        $reportTypeLabels[$selectedReportType]
        ?? 'Current Assignments';

    /*
    |--------------------------------------------------------------------------
    | Display Counts
    |--------------------------------------------------------------------------
    */

    $displayCurrentAssignments =
        $selectedReportType === 'unassigned'
            ? 0
            : ($filteredCurrentAssignments ?? $currentAssignments ?? 0);

    $displayReturnedAssignments =
        $selectedReportType === 'unassigned'
            ? 0
            : ($filteredReturnedAssignments ?? $returnedAssignments ?? 0);

    $displayTotalAssignments =
        $selectedReportType === 'unassigned'
            ? ($unassignedItems->total() ?? $unassignedAssets ?? 0)
            : ($filteredTotalAssignments ?? $totalAssignments ?? 0);

    $displayUnassignedAssets =
        $selectedReportType === 'unassigned'
            ? ($unassignedItems->total() ?? $unassignedAssets ?? 0)
            : ($unassignedAssets ?? 0);
@endphp


<div class="container-fluid py-3 assignment-report-page">

    {{-- ================================================================
         PAGE HEADER
    ================================================================= --}}

    <div class="report-page-header mb-4">

        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">

            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('admin.reports.index') }}"
                   class="btn btn-light border shadow-sm report-back-btn"
                   title="Back to Reports">

                    <i class="bi bi-arrow-left"></i>

                </a>


                <div class="report-header-icon">
                    <i class="bi bi-arrow-left-right"></i>
                </div>


                <div>

                    <div class="text-uppercase small fw-semibold text-muted mb-1">
                        CPC Inventory Management System
                    </div>

                    <h1 class="report-page-title mb-1">
                        Assignment Reports
                    </h1>

                    <p class="text-muted mb-0">
                        Monitor asset assignments, employee assets and assignment history.
                    </p>

                </div>

            </div>


            <div class="report-header-meta">

                <span class="report-status-pill">

                    <i class="bi bi-file-earmark-bar-graph me-1"></i>

                    {{ $currentReportLabel }}

                </span>

            </div>

        </div>

    </div>


    {{-- ================================================================
         SUMMARY CARDS
    ================================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Current Assignments --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="summary-card summary-current">

                <div class="summary-card-content">

                    <div>

                        <div class="summary-label">
                            Current Assignments
                        </div>

                        <div class="summary-value">
                            {{ number_format($displayCurrentAssignments) }}
                        </div>

                        <div class="summary-description">
                            Assets currently issued
                        </div>

                    </div>

                    <div class="summary-icon">
                        <i class="bi bi-person-check"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Returned Assignments --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="summary-card summary-returned">

                <div class="summary-card-content">

                    <div>

                        <div class="summary-label">
                            Returned Assignments
                        </div>

                        <div class="summary-value">
                            {{ number_format($displayReturnedAssignments) }}
                        </div>

                        <div class="summary-description">
                            Completed assignment records
                        </div>

                    </div>

                    <div class="summary-icon">
                        <i class="bi bi-arrow-return-left"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Total Assignments --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="summary-card summary-total">

                <div class="summary-card-content">

                    <div>

                        <div class="summary-label">
                            Assignment Records
                        </div>

                        <div class="summary-value">
                            {{ number_format($displayTotalAssignments) }}
                        </div>

                        <div class="summary-description">
                            Assignment transaction records
                        </div>

                    </div>

                    <div class="summary-icon">
                        <i class="bi bi-clipboard-data"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Unassigned Assets --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="summary-card summary-unassigned">

                <div class="summary-card-content">

                    <div>

                        <div class="summary-label">
                            Unassigned Assets
                        </div>

                        <div class="summary-value">
                            {{ number_format($displayUnassignedAssets) }}
                        </div>

                        <div class="summary-description">
                            Assets without active assignment
                        </div>

                    </div>

                    <div class="summary-icon">
                        <i class="bi bi-person-x"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         FILTER CARD
    ================================================================= --}}

    <div class="card border-0 shadow-sm report-card mb-4">

        <div class="card-header report-card-header">

            <div class="d-flex align-items-center gap-2">

                <div class="section-icon">
                    <i class="bi bi-funnel"></i>
                </div>

                <div>

                    <h5 class="mb-0 fw-semibold">
                        Report Filters
                    </h5>

                    <small class="text-muted">
                        Select the criteria required for your assignment report.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-4">

            <form method="GET"
                  action="{{ route('admin.reports.assignments.index') }}"
                  id="assignmentReportForm">

                <div class="row g-3">

                    {{-- Report Type --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <label for="report_type"
                               class="form-label report-label">

                            Report Type

                        </label>

                        <select name="report_type"
                                id="report_type"
                                class="form-select report-input">

                            <option value="current"
                                {{ $selectedReportType === 'current' ? 'selected' : '' }}>
                                Current Assignments
                            </option>

                            <option value="history"
                                {{ $selectedReportType === 'history' ? 'selected' : '' }}>
                                Assignment History
                            </option>

                            <option value="employee"
                                {{ $selectedReportType === 'employee' ? 'selected' : '' }}>
                                Employee Assets
                            </option>

                            <option value="unassigned"
                                {{ $selectedReportType === 'unassigned' ? 'selected' : '' }}>
                                Unassigned Assets
                            </option>

                        </select>

                    </div>


                    {{-- Employee --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <label for="employee_id"
                               class="form-label report-label">

                            Employee

                        </label>

                        <select name="employee_id"
                                id="employee_id"
                                class="form-select report-input">

                            <option value="">
                                All Employees
                            </option>

                            @foreach($employees as $employee)

                                <option value="{{ $employee->id }}"
                                    {{ (string) $selectedEmployee === (string) $employee->id ? 'selected' : '' }}>

                                    {{ $employee->name }}

                                    @if(!empty($employee->epf_number))
                                        — {{ $employee->epf_number }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Assignment Status --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <label for="status"
                               class="form-label report-label">

                            Assignment Status

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select report-input">

                            <option value="">
                                All Statuses
                            </option>

                            <option value="issued"
                                {{ $selectedStatus === 'issued' ? 'selected' : '' }}>
                                Issued / Current
                            </option>

                            <option value="returned"
                                {{ $selectedStatus === 'returned' ? 'selected' : '' }}>
                                Returned
                            </option>

                        </select>

                    </div>


                    {{-- Branch --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <label for="branch_id"
                               class="form-label report-label">

                            Branch

                        </label>

                        <select name="branch_id"
                                id="branch_id"
                                class="form-select report-input">

                            <option value="">
                                All Branches
                            </option>

                            @foreach($branches as $branch)

                                <option value="{{ $branch->id }}"
                                    {{ (string) $selectedBranch === (string) $branch->id ? 'selected' : '' }}>

                                    {{ $branch->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Department --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <label for="department_id"
                               class="form-label report-label">

                            Department

                        </label>

                        <select name="department_id"
                                id="department_id"
                                class="form-select report-input">

                            <option value="">
                                All Departments
                            </option>

                            @foreach($departments as $department)

                                <option value="{{ $department->id }}"
                                    {{ (string) $selectedDepartment === (string) $department->id ? 'selected' : '' }}>

                                    {{ $department->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Function Unit --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <label for="function_unit_id"
                               class="form-label report-label">

                            Function Unit

                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <select name="function_unit_id"
                                id="function_unit_id"
                                class="form-select report-input">

                            <option value="">
                                All Function Units
                            </option>

                            @foreach($functionUnits as $functionUnit)

                                <option value="{{ $functionUnit->id }}"
                                    {{ (string) $selectedFunctionUnit === (string) $functionUnit->id ? 'selected' : '' }}>

                                    {{ $functionUnit->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Item Type --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <label for="item_type_id"
                               class="form-label report-label">

                            Item Type

                        </label>

                        <select name="item_type_id"
                                id="item_type_id"
                                class="form-select report-input">

                            <option value="">
                                All Item Types
                            </option>

                            @foreach($itemTypes as $itemType)

                                <option value="{{ $itemType->id }}"
                                    {{ (string) $selectedItemType === (string) $itemType->id ? 'selected' : '' }}>

                                    {{ $itemType->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From Date --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <label for="from_date"
                               class="form-label report-label">

                            From Date

                        </label>

                        <input type="date"
                               name="from_date"
                               id="from_date"
                               value="{{ $selectedFromDate }}"
                               class="form-control report-input">

                    </div>


                    {{-- To Date --}}
                    <div class="col-12 col-md-6 col-lg-4">

                        <label for="to_date"
                               class="form-label report-label">

                            To Date

                        </label>

                        <input type="date"
                               name="to_date"
                               id="to_date"
                               value="{{ $selectedToDate }}"
                               class="form-control report-input">

                    </div>

                </div>


                {{-- Filter Actions --}}
                <div class="filter-actions mt-4 pt-3">

                    <div class="d-flex flex-wrap gap-2">

                        <button type="submit"
                                class="btn btn-primary report-generate-btn">

                            <i class="bi bi-bar-chart-line me-1"></i>
                            Generate Report

                        </button>


                        <a href="{{ route('admin.reports.assignments.index') }}"
                           class="btn btn-outline-secondary report-reset-btn">

                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset

                        </a>

                    </div>


                    @if($hasFilters)

                        <div class="active-filter-indicator">

                            <i class="bi bi-check-circle-fill me-1"></i>

                            Filters applied

                        </div>

                    @endif

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================
         ACTIVE FILTER SUMMARY
    ================================================================= --}}

    @if($hasFilters)

        <div class="alert filter-summary-alert border-0 shadow-sm mb-4">

            <div class="d-flex align-items-start gap-3">

                <div class="filter-summary-icon">
                    <i class="bi bi-funnel-fill"></i>
                </div>


                <div class="flex-grow-1">

                    <div class="fw-semibold mb-2">
                        Active Report Criteria
                    </div>


                    <div class="d-flex flex-wrap gap-2">

                        <span class="filter-chip">

                            <strong>Report:</strong>

                            {{ $currentReportLabel }}

                        </span>


                        @if($selectedStatus)

                            <span class="filter-chip">

                                <strong>Status:</strong>

                                {{ $selectedStatus === 'issued'
                                    ? 'Issued / Current'
                                    : 'Returned' }}

                            </span>

                        @endif


                        @if($selectedEmployee)

                            @php
                                $selectedEmployeeModel =
                                    $employees->firstWhere('id', $selectedEmployee);
                            @endphp

                            @if($selectedEmployeeModel)

                                <span class="filter-chip">

                                    <strong>Employee:</strong>

                                    {{ $selectedEmployeeModel->name }}

                                </span>

                            @endif

                        @endif


                        @if($selectedBranch)

                            @php
                                $selectedBranchModel =
                                    $branches->firstWhere('id', $selectedBranch);
                            @endphp

                            @if($selectedBranchModel)

                                <span class="filter-chip">

                                    <strong>Branch:</strong>

                                    {{ $selectedBranchModel->name }}

                                </span>

                            @endif

                        @endif


                        @if($selectedDepartment)

                            @php
                                $selectedDepartmentModel =
                                    $departments->firstWhere('id', $selectedDepartment);
                            @endphp

                            @if($selectedDepartmentModel)

                                <span class="filter-chip">

                                    <strong>Department:</strong>

                                    {{ $selectedDepartmentModel->name }}

                                </span>

                            @endif

                        @endif


                        @if($selectedFunctionUnit)

                            @php
                                $selectedFunctionUnitModel =
                                    $functionUnits->firstWhere('id', $selectedFunctionUnit);
                            @endphp

                            @if($selectedFunctionUnitModel)

                                <span class="filter-chip">

                                    <strong>Function Unit:</strong>

                                    {{ $selectedFunctionUnitModel->name }}

                                </span>

                            @endif

                        @endif


                        @if($selectedItemType)

                            @php
                                $selectedItemTypeModel =
                                    $itemTypes->firstWhere('id', $selectedItemType);
                            @endphp

                            @if($selectedItemTypeModel)

                                <span class="filter-chip">

                                    <strong>Item Type:</strong>

                                    {{ $selectedItemTypeModel->name }}

                                </span>

                            @endif

                        @endif


                        @if($selectedFromDate)

                            <span class="filter-chip">

                                <strong>From:</strong>

                                {{ \Carbon\Carbon::parse($selectedFromDate)->format('d M Y') }}

                            </span>

                        @endif


                        @if($selectedToDate)

                            <span class="filter-chip">

                                <strong>To:</strong>

                                {{ \Carbon\Carbon::parse($selectedToDate)->format('d M Y') }}

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ================================================================
         REPORT RESULTS
    ================================================================= --}}

    <div class="card border-0 shadow-sm report-card">

        <div class="card-header report-card-header">

            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">

                {{-- Results Title --}}
                <div class="d-flex align-items-center gap-2">

                    <div class="section-icon">
                        <i class="bi bi-table"></i>
                    </div>

                    <div>

                        <h5 class="mb-0 fw-semibold">
                            {{ $currentReportLabel }}
                        </h5>

                        <small class="text-muted">
                            Detailed assignment records
                        </small>

                    </div>

                </div>


                {{-- ====================================================
                     RECORD COUNT + EXPORT ACTIONS
                ===================================================== --}}

                <div class="report-results-actions">

                    <div class="record-count-badge">

                        <i class="bi bi-database me-1"></i>

                        @if($selectedReportType === 'unassigned')

                            {{ number_format($unassignedItems->total()) }}
                            assets

                        @else

                            {{ number_format($assignments->total()) }}
                            records

                        @endif

                    </div>


                    <div class="report-export-actions">

                        {{-- Print --}}
                        <a href="{{ route('admin.reports.assignments.print', request()->query()) }}"
                           target="_blank"
                           class="btn btn-outline-secondary btn-sm export-btn"
                           title="Print assignment report">

                            <i class="bi bi-printer me-1"></i>

                            <span>
                                Print
                            </span>

                        </a>


                        {{-- PDF --}}
                        <a href="{{ route('admin.reports.assignments.pdf', request()->query()) }}"
                           class="btn btn-outline-danger btn-sm export-btn"
                           title="Download assignment report as PDF">

                            <i class="bi bi-file-earmark-pdf me-1"></i>

                            <span>
                                PDF
                            </span>

                        </a>


                        {{-- Excel --}}
                        <a href="{{ route('admin.reports.assignments.excel', request()->query()) }}"
                           class="btn btn-outline-success btn-sm export-btn"
                           title="Download assignment report as Excel">

                            <i class="bi bi-file-earmark-excel me-1"></i>

                            <span>
                                Excel
                            </span>

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <div class="card-body p-0">


            {{-- ========================================================
                 ASSIGNMENT TABLE
            ========================================================= --}}

            @if($selectedReportType !== 'unassigned')

                @if($assignments->count() > 0)

                    <div class="table-responsive assignment-table-wrapper">

                        <table class="table table-hover align-middle mb-0 assignment-table">

                            <thead>

                                <tr>

                                    <th class="text-center">
                                        #
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
                                        Function Unit
                                    </th>

                                    <th>
                                        Workstation
                                    </th>

                                    <th>
                                        Assigned Date
                                    </th>

                                    <th>
                                        Returned Date
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($assignments as $assignment)

                                    @php

                                        $item = $assignment->item;
                                        $employee = $assignment->employee;
                                        $branch = $assignment->branch;
                                        $department = $assignment->department;
                                        $functionUnit = $assignment->functionUnit;

                                        $isIssued =
                                            is_null($assignment->returned_date);

                                    @endphp


                                    <tr>

                                        {{-- Number --}}
                                        <td class="text-center text-muted fw-semibold">

                                            {{ $assignments->firstItem() + $loop->index }}

                                        </td>


                                        {{-- Asset --}}
                                        <td>

                                            <div class="asset-cell">

                                                <div class="asset-icon">
                                                    <i class="bi bi-pc-display"></i>
                                                </div>

                                                <div>

                                                    <div class="fw-semibold text-dark">

                                                        {{ $item?->asset_number ?? 'N/A' }}

                                                    </div>


                                                    @if($item?->serial_number)

                                                        <div class="small text-muted">

                                                            SN:
                                                            {{ $item->serial_number }}

                                                        </div>

                                                    @endif

                                                </div>

                                            </div>

                                        </td>


                                        {{-- Item Type --}}
                                        <td>

                                            <span class="item-type-text">

                                                {{ $item?->itemType?->name ?? 'N/A' }}

                                            </span>


                                            @if($item?->make || $item?->model)

                                                <div class="small text-muted mt-1">

                                                    {{ $item?->make ?? '' }}

                                                    @if($item?->make && $item?->model)
                                                        /
                                                    @endif

                                                    {{ $item?->model ?? '' }}

                                                </div>

                                            @endif

                                        </td>


                                        {{-- Employee --}}
                                        <td>

                                            @if($employee)

                                                <div class="employee-cell">

                                                    <div class="employee-avatar">

                                                        <i class="bi bi-person"></i>

                                                    </div>

                                                    <div>

                                                        <div class="fw-semibold text-dark">

                                                            {{ $employee->name }}

                                                        </div>


                                                        @if(!empty($employee->epf_number))

                                                            <div class="small text-muted">

                                                                EPF:
                                                                {{ $employee->epf_number }}

                                                            </div>

                                                        @endif

                                                    </div>

                                                </div>

                                            @else

                                                <span class="text-muted">
                                                    Not Assigned
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Branch --}}
                                        <td>

                                            {{ $branch?->name ?? 'N/A' }}

                                        </td>


                                        {{-- Department --}}
                                        <td>

                                            {{ $department?->name ?? 'N/A' }}

                                        </td>


                                        {{-- Function Unit --}}
                                        <td>

                                            @if($functionUnit)

                                                {{ $functionUnit->name }}

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Workstation --}}
                                        <td>

                                            @if($assignment->workstation_id)

                                                <span class="workstation-badge">

                                                    {{ $assignment->workstation_id }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Assigned Date --}}
                                        <td>

                                            @if($assignment->assigned_date)

                                                <span class="date-value">

                                                    {{ $assignment->assigned_date->format('d M Y') }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Returned Date --}}
                                        <td>

                                            @if($assignment->returned_date)

                                                <span class="date-value">

                                                    {{ $assignment->returned_date->format('d M Y') }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if($isIssued)

                                                <span class="status-badge status-issued">

                                                    <i class="bi bi-check-circle-fill me-1"></i>

                                                    Issued

                                                </span>

                                            @else

                                                <span class="status-badge status-returned">

                                                    <i class="bi bi-arrow-return-left me-1"></i>

                                                    Returned

                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    <div class="pagination-section">

                        <div class="pagination-info">

                            Showing

                            <strong>
                                {{ $assignments->firstItem() }}
                            </strong>

                            to

                            <strong>
                                {{ $assignments->lastItem() }}
                            </strong>

                            of

                            <strong>
                                {{ number_format($assignments->total()) }}
                            </strong>

                            assignment records

                        </div>


                        <div>

                            {{ $assignments->links() }}

                        </div>

                    </div>

                @else

                    <div class="empty-state">

                        <div class="empty-state-icon">

                            <i class="bi bi-clipboard-x"></i>

                        </div>


                        <h5 class="fw-semibold mt-3">

                            No Assignment Records Found

                        </h5>


                        <p class="text-muted mb-3">

                            No assignment records match the selected report criteria.

                        </p>


                        <a href="{{ route('admin.reports.assignments.index') }}"
                           class="btn btn-outline-primary btn-sm">

                            <i class="bi bi-arrow-counterclockwise me-1"></i>

                            Clear Filters

                        </a>

                    </div>

                @endif


            {{-- ========================================================
                 UNASSIGNED ASSET TABLE
            ========================================================= --}}

            @else

                @if($unassignedItems->count() > 0)

                    <div class="table-responsive assignment-table-wrapper">

                        <table class="table table-hover align-middle mb-0 assignment-table">

                            <thead>

                                <tr>

                                    <th class="text-center">
                                        #
                                    </th>

                                    <th>
                                        Asset
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
                                        Current Employee
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($unassignedItems as $item)

                                    <tr>

                                        {{-- Number --}}
                                        <td class="text-center text-muted fw-semibold">

                                            {{ $unassignedItems->firstItem() + $loop->index }}

                                        </td>


                                        {{-- Asset --}}
                                        <td>

                                            <div class="asset-cell">

                                                <div class="asset-icon">

                                                    <i class="bi bi-pc-display"></i>

                                                </div>


                                                <div>

                                                    <div class="fw-semibold text-dark">

                                                        {{ $item->asset_number ?? 'N/A' }}

                                                    </div>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- Item Type --}}
                                        <td>

                                            {{ $item->itemType?->name ?? 'N/A' }}

                                        </td>


                                        {{-- Make / Model --}}
                                        <td>

                                            @if($item->make || $item->model)

                                                {{ $item->make ?? '' }}

                                                @if($item->make && $item->model)
                                                    /
                                                @endif

                                                {{ $item->model ?? '' }}

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Serial Number --}}
                                        <td>

                                            @if($item->serial_number)

                                                {{ $item->serial_number }}

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Branch --}}
                                        <td>

                                            {{ $item->branch?->name ?? 'N/A' }}

                                        </td>


                                        {{-- Department --}}
                                        <td>

                                            {{ $item->department?->name ?? 'N/A' }}

                                        </td>


                                        {{-- Current Employee --}}
                                        <td>

                                            @if($item->currentEmployee)

                                                {{ $item->currentEmployee->name }}

                                            @else

                                                <span class="text-muted">
                                                    Not Assigned
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @php
                                                $itemStatus = $item->status;
                                            @endphp


                                            @switch($itemStatus)

                                                @case('available')

                                                    <span class="status-badge status-available">
                                                        Available
                                                    </span>

                                                    @break


                                                @case('in_use')

                                                    <span class="status-badge status-issued">
                                                        In Use
                                                    </span>

                                                    @break


                                                @case('repair')

                                                    <span class="status-badge status-repair">
                                                        Repair
                                                    </span>

                                                    @break


                                                @case('rdd')

                                                    <span class="status-badge status-rdd">
                                                        RDD
                                                    </span>

                                                    @break


                                                @default

                                                    <span class="status-badge status-neutral">

                                                        {{ ucfirst(
                                                            str_replace(
                                                                '_',
                                                                ' ',
                                                                $itemStatus ?? 'Unknown'
                                                            )
                                                        ) }}

                                                    </span>

                                            @endswitch

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Unassigned Pagination --}}
                    <div class="pagination-section">

                        <div class="pagination-info">

                            Showing

                            <strong>
                                {{ $unassignedItems->firstItem() }}
                            </strong>

                            to

                            <strong>
                                {{ $unassignedItems->lastItem() }}
                            </strong>

                            of

                            <strong>
                                {{ number_format($unassignedItems->total()) }}
                            </strong>

                            unassigned assets

                        </div>


                        <div>

                            {{ $unassignedItems->links() }}

                        </div>

                    </div>

                @else

                    <div class="empty-state">

                        <div class="empty-state-icon empty-success">

                            <i class="bi bi-check-circle"></i>

                        </div>


                        <h5 class="fw-semibold mt-3">

                            No Unassigned Assets Found

                        </h5>


                        <p class="text-muted mb-0">

                            All assets matching the selected criteria currently have an active assignment.

                        </p>

                    </div>

                @endif

            @endif

        </div>

    </div>

</div>


@push('styles')
<style>

    /* ================================================================
       PAGE
    ================================================================ */

    .assignment-report-page {
        max-width: 100%;
    }


    .report-page-header {
        padding: 0.25rem 0;
    }


    .report-back-btn {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s ease;
    }


    .report-back-btn:hover {
        transform: translateX(-2px);
    }


    .report-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(
            135deg,
            #0d6efd,
            #084298
        );
        color: #fff;
        font-size: 1.25rem;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.18);
    }


    .report-page-title {
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: #1f2937;
    }


    .report-header-meta {
        display: flex;
        align-items: center;
    }


    .report-status-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.55rem 0.85rem;
        border-radius: 999px;
        background: #f1f5f9;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }


    /* ================================================================
       SUMMARY CARDS
    ================================================================ */

    .summary-card {
        height: 100%;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, 0.055);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
        overflow: hidden;
        position: relative;
    }


    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.09);
    }


    .summary-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #0d6efd;
    }


    .summary-current::before {
        background: #198754;
    }


    .summary-returned::before {
        background: #6c757d;
    }


    .summary-total::before {
        background: #0d6efd;
    }


    .summary-unassigned::before {
        background: #fd7e14;
    }


    .summary-card-content {
        min-height: 145px;
        padding: 1.2rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }


    .summary-label {
        font-size: 0.79rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.045em;
        margin-bottom: 0.35rem;
    }


    .summary-value {
        color: #111827;
        font-size: 1.85rem;
        font-weight: 750;
        line-height: 1.15;
    }


    .summary-description {
        color: #94a3b8;
        font-size: 0.78rem;
        margin-top: 0.35rem;
    }


    .summary-icon {
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #f1f5f9;
        color: #0d6efd;
        font-size: 1.25rem;
    }


    .summary-current .summary-icon {
        color: #198754;
        background: #eaf7ef;
    }


    .summary-returned .summary-icon {
        color: #6c757d;
        background: #f1f3f5;
    }


    .summary-unassigned .summary-icon {
        color: #fd7e14;
        background: #fff4e6;
    }


    /* ================================================================
       CARDS
    ================================================================ */

    .report-card {
        border-radius: 14px;
        overflow: hidden;
    }


    .report-card-header {
        background: #fff;
        border-bottom: 1px solid #edf0f3;
        padding: 1rem 1.25rem;
    }


    .section-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eef5ff;
        color: #0d6efd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }


    /* ================================================================
       FILTERS
    ================================================================ */

    .report-label {
        color: #374151;
        font-size: 0.83rem;
        font-weight: 650;
        margin-bottom: 0.45rem;
    }


    .report-input {
        min-height: 43px;
        border-color: #dfe3e8;
        border-radius: 9px;
        font-size: 0.88rem;
        box-shadow: none;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }


    .report-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
    }


    .filter-actions {
        border-top: 1px solid #edf0f3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }


    .report-generate-btn {
        min-height: 42px;
        padding: 0.55rem 1rem;
        border-radius: 9px;
        font-weight: 600;
    }


    .report-reset-btn {
        min-height: 42px;
        padding: 0.55rem 1rem;
        border-radius: 9px;
        font-weight: 600;
    }


    .active-filter-indicator {
        color: #198754;
        font-size: 0.82rem;
        font-weight: 600;
    }


    /* ================================================================
       FILTER SUMMARY
    ================================================================ */

    .filter-summary-alert {
        background: #f8fbff;
        border-left: 4px solid #0d6efd !important;
        padding: 1rem 1.1rem;
        color: #334155;
    }


    .filter-summary-icon {
        width: 36px;
        height: 36px;
        flex: 0 0 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #e8f1ff;
        color: #0d6efd;
    }


    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: #fff;
        border: 1px solid #dce6f3;
        border-radius: 999px;
        padding: 0.35rem 0.65rem;
        color: #475569;
        font-size: 0.76rem;
    }


    /* ================================================================
       RESULTS HEADER / EXPORTS
    ================================================================ */

    .report-results-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 0.5rem;
    }


    .record-count-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.7rem;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 650;
        white-space: nowrap;
    }


    .report-export-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.4rem;
    }


    .export-btn {
        min-height: 34px;
        padding: 0.4rem 0.7rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 650;
        white-space: nowrap;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .export-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(15, 23, 42, 0.08);
    }


    /* ================================================================
       TABLE
    ================================================================ */

    .assignment-table-wrapper {
        width: 100%;
    }


    .assignment-table {
        min-width: 1250px;
        font-size: 0.84rem;
    }


    .assignment-table thead th {
        background: #f8fafc;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.85rem 0.75rem;
        white-space: nowrap;
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.035em;
        font-weight: 700;
    }


    .assignment-table tbody td {
        padding: 0.85rem 0.75rem;
        border-bottom-color: #edf0f3;
        color: #475569;
        white-space: nowrap;
    }


    .assignment-table tbody tr:last-child td {
        border-bottom: 0;
    }


    .assignment-table tbody tr {
        transition: background-color 0.15s ease;
    }


    .assignment-table tbody tr:hover {
        background: #f8fbff;
    }


    /* ================================================================
       ASSET / EMPLOYEE CELLS
    ================================================================ */

    .asset-cell,
    .employee-cell {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }


    .asset-icon,
    .employee-avatar {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }


    .asset-icon {
        background: #eef5ff;
        color: #0d6efd;
    }


    .employee-avatar {
        background: #f1f5f9;
        color: #475569;
    }


    .item-type-text {
        font-weight: 600;
        color: #334155;
    }


    .workstation-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.76rem;
        color: #475569;
    }


    .date-value {
        color: #475569;
        font-weight: 500;
    }


    /* ================================================================
       STATUS BADGES
    ================================================================ */

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.36rem 0.6rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }


    .status-issued {
        background: #eaf7ef;
        color: #157347;
    }


    .status-returned {
        background: #f1f3f5;
        color: #495057;
    }


    .status-available {
        background: #eaf7ef;
        color: #157347;
    }


    .status-repair {
        background: #fff4e6;
        color: #b45309;
    }


    .status-rdd {
        background: #fce8f3;
        color: #a61e4d;
    }


    .status-neutral {
        background: #f1f5f9;
        color: #64748b;
    }


    /* ================================================================
       PAGINATION
    ================================================================ */

    .pagination-section {
        padding: 1rem 1.25rem;
        border-top: 1px solid #edf0f3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }


    .pagination-info {
        color: #64748b;
        font-size: 0.8rem;
    }


    .pagination-section .pagination {
        margin-bottom: 0;
    }


    /* ================================================================
       EMPTY STATE
    ================================================================ */

    .empty-state {
        padding: 4rem 1.5rem;
        text-align: center;
    }


    .empty-state-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 1.7rem;
    }


    .empty-success {
        background: #eaf7ef;
        color: #198754;
    }


    /* ================================================================
       RESPONSIVE
    ================================================================ */

    @media (max-width: 1199.98px) {

        .report-results-actions {
            justify-content: flex-start;
        }

    }


    @media (max-width: 767.98px) {

        .assignment-report-page {
            padding-left: 0.35rem;
            padding-right: 0.35rem;
        }


        .report-page-title {
            font-size: 1.35rem;
        }


        .report-header-icon {
            width: 42px;
            height: 42px;
        }


        .summary-card-content {
            min-height: 125px;
        }


        .summary-value {
            font-size: 1.55rem;
        }


        .report-card-header {
            padding: 0.9rem 1rem;
        }


        .report-card .card-body {
            padding: 1rem !important;
        }


        .filter-actions {
            align-items: stretch;
        }


        .filter-actions > div:first-child {
            width: 100%;
        }


        .report-generate-btn,
        .report-reset-btn {
            flex: 1;
        }


        .report-results-actions {
            width: 100%;
            justify-content: flex-start;
        }


        .report-export-actions {
            width: 100%;
        }


        .export-btn {
            flex: 1;
        }


        .pagination-section {
            align-items: flex-start;
            flex-direction: column;
        }

    }


    @media (max-width: 420px) {

        .report-export-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            width: 100%;
        }


        .export-btn {
            width: 100%;
            padding-left: 0.4rem;
            padding-right: 0.4rem;
        }


        .export-btn span {
            display: none;
        }


        .export-btn i {
            margin-right: 0 !important;
        }

    }

</style>
@endpush

@endsection