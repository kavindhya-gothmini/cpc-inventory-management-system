@extends('layouts.admin')

@section('title', 'RDD Reports | CPC Inventory Management System')

@section('content')

@php
    $reportType = request('report_type', 'summary');

    $status = request('status');
    $employeeId = request('employee_id');
    $branchId = request('branch_id');
    $departmentId = request('department_id');
    $itemTypeId = request('item_type_id');
    $active = request('active', '');
    $fromDate = request('from_date');
    $toDate = request('to_date');
    $search = request('search');

    $totalRdd = $totalRdd ?? 0;
    $activeRdd = $activeRdd ?? 0;
    $inactiveRdd = $inactiveRdd ?? 0;
    $pendingRdd = $pendingRdd ?? 0;
    $receivedRdd = $receivedRdd ?? 0;
    $completedRdd = $completedRdd ?? 0;
    $rejectedRdd = $rejectedRdd ?? 0;
    $beyondRepair = $beyondRepair ?? 0;
    $recommendationRecorded = $recommendationRecorded ?? 0;

    $rddTransactions = $rddTransactions ?? collect();
    $locationRows = $locationRows ?? collect();

    $statusLabels = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'dispatched' => 'Dispatched',
        'received' => 'Received',
        'inspection' => 'Inspection',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'returned' => 'Returned',
        'beyond_repair' => 'Beyond Repair',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
    ];

    $statusClasses = [
        'pending' => 'status-pending',
        'approved' => 'status-approved',
        'dispatched' => 'status-dispatched',
        'received' => 'status-received',
        'inspection' => 'status-inspection',
        'in_progress' => 'status-progress',
        'completed' => 'status-completed',
        'returned' => 'status-returned',
        'beyond_repair' => 'status-beyond-repair',
        'rejected' => 'status-rejected',
        'cancelled' => 'status-cancelled',
    ];

    $queryParams = array_filter([
        'report_type' => $reportType,
        'search' => $search,
        'status' => $status,
        'employee_id' => $employeeId,
        'branch_id' => $branchId,
        'department_id' => $departmentId,
        'item_type_id' => $itemTypeId,
        'active' => $active,
        'from_date' => $fromDate,
        'to_date' => $toDate,
    ], fn ($value) => $value !== null && $value !== '');

    $printUrl = route('admin.reports.rdd.print', $queryParams);
    $pdfUrl = route('admin.reports.rdd.pdf', $queryParams);
    $excelUrl = route('admin.reports.rdd.excel', $queryParams);
@endphp

<style>
    .rdd-report-page {
        --cpc-blue: #0d6efd;
        --cpc-dark: #172033;
        --cpc-muted: #6c757d;
        --cpc-border: #e9edf3;
        --cpc-light: #f7f9fc;
    }

    .rdd-report-page .page-header {
        background: linear-gradient(135deg, #ffffff 0%, #f7faff 100%);
        border: 1px solid var(--cpc-border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .rdd-report-page .page-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--cpc-dark);
        margin-bottom: 5px;
    }

    .rdd-report-page .page-subtitle {
        color: var(--cpc-muted);
        font-size: .9rem;
        margin-bottom: 0;
    }

    .rdd-report-page .header-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .1);
        color: var(--cpc-blue);
        font-size: 1.25rem;
        margin-right: 13px;
    }

    .report-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .report-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px;
        background: #fff;
        border: 1px solid var(--cpc-border);
        border-radius: 15px;
        color: var(--cpc-dark);
        text-decoration: none;
        transition: all .2s ease;
        overflow: hidden;
    }

    .report-card::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 3px;
        background: var(--cpc-blue);
        opacity: 0;
        transition: opacity .2s ease;
    }

    .report-card:hover {
        transform: translateY(-2px);
        border-color: rgba(13, 110, 253, .25);
        box-shadow: 0 8px 24px rgba(24, 39, 75, .08);
        color: var(--cpc-dark);
    }

    .report-card.active {
        border-color: rgba(13, 110, 253, .35);
        background: #f8fbff;
        box-shadow: 0 7px 22px rgba(13, 110, 253, .08);
    }

    .report-card.active::after {
        opacity: 1;
    }

    .report-card-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .report-card.summary .report-card-icon {
        background: rgba(13, 110, 253, .1);
        color: #0d6efd;
    }

    .report-card.history .report-card-icon {
        background: rgba(111, 66, 193, .1);
        color: #6f42c1;
    }

    .report-card.location .report-card-icon {
        background: rgba(25, 135, 84, .1);
        color: #198754;
    }

    .report-card-title {
        font-size: .96rem;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .report-card-description {
        font-size: .76rem;
        color: var(--cpc-muted);
        line-height: 1.4;
    }

    .filter-card,
    .content-card {
        background: #fff;
        border: 1px solid var(--cpc-border);
        border-radius: 16px;
        overflow: hidden;
    }

    .card-header-modern {
        padding: 17px 20px;
        border-bottom: 1px solid var(--cpc-border);
        background: #fff;
    }

    .card-header-modern h6 {
        font-weight: 700;
        color: var(--cpc-dark);
        margin: 0;
    }

    .filter-body {
        padding: 20px;
        background: var(--cpc-light);
    }

    .form-label-modern {
        font-size: .76rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 6px;
    }

    .form-control-modern,
    .form-select-modern {
        min-height: 40px;
        border: 1px solid #dfe4ea;
        border-radius: 9px;
        font-size: .84rem;
        background: #fff;
    }

    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .1);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        align-items: end;
        height: 100%;
    }

    .btn-modern {
        min-height: 40px;
        border-radius: 9px;
        font-size: .82rem;
        font-weight: 600;
        padding: 8px 14px;
    }

    .action-toolbar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-toolbar .btn {
        border-radius: 9px;
        font-size: .8rem;
        font-weight: 600;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .kpi-card {
        background: #fff;
        border: 1px solid var(--cpc-border);
        border-radius: 14px;
        padding: 17px;
        position: relative;
        overflow: hidden;
    }

    .kpi-card::after {
        content: "";
        position: absolute;
        width: 4px;
        top: 0;
        bottom: 0;
        left: 0;
        background: var(--kpi-color, #0d6efd);
    }

    .kpi-label {
        color: var(--cpc-muted);
        font-size: .75rem;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .kpi-value {
        color: var(--cpc-dark);
        font-size: 1.45rem;
        line-height: 1;
        font-weight: 750;
    }

    .kpi-icon {
        position: absolute;
        right: 16px;
        top: 16px;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .08);
        color: var(--kpi-color, #0d6efd);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        min-width: 1050px;
        margin: 0;
    }

    .modern-table thead th {
        background: #f8f9fb;
        border-bottom: 1px solid var(--cpc-border);
        color: #667085;
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .035em;
        white-space: nowrap;
        padding: 13px 14px;
    }

    .modern-table tbody td {
        padding: 13px 14px;
        border-bottom: 1px solid #eef1f5;
        color: #344054;
        font-size: .82rem;
        vertical-align: middle;
    }

    .modern-table tbody tr:hover {
        background: #fbfcfe;
    }

    .reference-number {
        font-weight: 700;
        color: #0d6efd;
    }

    .asset-number {
        font-weight: 650;
        color: #212529;
    }

    .sub-text {
        color: #8a94a6;
        font-size: .72rem;
        margin-top: 2px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: .69rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-pending {
        color: #996c00;
        background: #fff4cc;
    }

    .status-approved {
        color: #0a58ca;
        background: #e7f1ff;
    }

    .status-dispatched {
        color: #6f42c1;
        background: #f0e8ff;
    }

    .status-received {
        color: #087990;
        background: #e4f8fc;
    }

    .status-inspection {
        color: #495057;
        background: #edf0f2;
    }

    .status-progress {
        color: #0d6efd;
        background: #e8f1ff;
    }

    .status-completed,
    .status-returned {
        color: #146c43;
        background: #dff6e9;
    }

    .status-beyond-repair {
        color: #b02a37;
        background: #fde8eb;
    }

    .status-rejected,
    .status-cancelled {
        color: #842029;
        background: #f8d7da;
    }

    .active-badge,
    .inactive-badge {
        font-size: .68rem;
        font-weight: 700;
        border-radius: 999px;
        padding: 4px 8px;
    }

    .active-badge {
        background: #e8f7ef;
        color: #198754;
    }

    .inactive-badge {
        background: #eef0f2;
        color: #6c757d;
    }

    .location-card {
        border: 1px solid var(--cpc-border);
        border-radius: 14px;
        padding: 18px;
        height: 100%;
        background: #fff;
    }

    .location-name {
        font-weight: 700;
        color: var(--cpc-dark);
        font-size: .95rem;
    }

    .location-meta {
        color: #8a94a6;
        font-size: .73rem;
    }

    .location-total {
        font-size: 1.7rem;
        font-weight: 750;
        color: var(--cpc-dark);
    }

    .location-stat {
        border-top: 1px solid #eef1f5;
        padding-top: 10px;
        margin-top: 12px;
    }

    .location-stat span {
        font-size: .72rem;
        color: #667085;
    }

    .location-stat strong {
        font-size: .82rem;
        color: #344054;
    }

    .empty-state {
        padding: 55px 20px;
        text-align: center;
        color: #98a2b3;
    }

    .empty-state i {
        font-size: 2.2rem;
        margin-bottom: 12px;
    }

    .empty-state h6 {
        color: #475467;
        font-weight: 700;
    }

    @media (max-width: 1100px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .report-selector {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .rdd-report-page .page-header {
            padding: 18px;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .filter-body {
            padding: 15px;
        }

        .action-toolbar {
            width: 100%;
        }

        .action-toolbar .btn {
            flex: 1;
        }

        .filter-actions {
            margin-top: 8px;
        }
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .rdd-report-page .page-header,
        .filter-card,
        .content-card,
        .kpi-card,
        .location-card {
            box-shadow: none !important;
        }

        body {
            background: #fff !important;
        }
    }
</style>

<div class="container-fluid py-3 rdd-report-page">

    {{-- Page Header --}}
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">

        <div class="d-flex align-items-center">
            <div class="header-icon">
                <i class="bi bi-recycle"></i>
            </div>

            <div>
                <h1 class="page-title">RDD Reports</h1>
                <p class="page-subtitle">
                    Review recommended-for-disposal items, RDD status history and location-based information.
                </p>
            </div>
        </div>

        <div class="action-toolbar no-print">
            <a href="{{ $printUrl }}"
               target="_blank"
               class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer me-1"></i>
                Print
            </a>

            <a href="{{ $pdfUrl }}"
               target="_blank"
               class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                PDF
            </a>

            <a href="{{ $excelUrl }}"
               class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>
                Excel
            </a>
        </div>

    </div>

    {{-- Report Selector --}}
    <div class="report-selector no-print">

        <a href="{{ route('admin.reports.rdd.index', ['report_type' => 'summary']) }}"
           class="report-card summary {{ $reportType === 'summary' ? 'active' : '' }}">

            <div class="report-card-icon">
                <i class="bi bi-bar-chart-line"></i>
            </div>

            <div>
                <div class="report-card-title">RDD Summary</div>
                <div class="report-card-description">
                    Overall RDD volume, status distribution and disposal-related statistics.
                </div>
            </div>

        </a>

        <a href="{{ route('admin.reports.rdd.index', ['report_type' => 'history']) }}"
           class="report-card history {{ $reportType === 'history' ? 'active' : '' }}">

            <div class="report-card-icon">
                <i class="bi bi-clock-history"></i>
            </div>

            <div>
                <div class="report-card-title">RDD History</div>
                <div class="report-card-description">
                    Track RDD transactions and their status progression over time.
                </div>
            </div>

        </a>

        <a href="{{ route('admin.reports.rdd.index', ['report_type' => 'location']) }}"
           class="report-card location {{ $reportType === 'location' ? 'active' : '' }}">

            <div class="report-card-icon">
                <i class="bi bi-geo-alt"></i>
            </div>

            <div>
                <div class="report-card-title">RDD by Location</div>
                <div class="report-card-description">
                    Compare RDD activity across branches and operational locations.
                </div>
            </div>

        </a>

    </div>

    {{-- Filters --}}
    <div class="filter-card mb-4 no-print">

        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-funnel text-primary"></i>
                <h6>Report Filters</h6>
            </div>

            <a href="{{ route('admin.reports.rdd.index', ['report_type' => $reportType]) }}"
               class="btn btn-sm btn-light border">
                <i class="bi bi-arrow-counterclockwise me-1"></i>
                Reset
            </a>
        </div>

        <div class="filter-body">

            <form method="GET"
                  action="{{ route('admin.reports.rdd.index') }}">

                <input type="hidden"
                       name="report_type"
                       value="{{ $reportType }}">

                <div class="row g-3">

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <label class="form-label-modern">Search</label>
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               class="form-control form-control-modern"
                               placeholder="Reference, asset, serial, employee...">
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label-modern">Status</label>

                        <select name="status"
                                class="form-select form-select-modern">

                            <option value="">All Statuses</option>

                            @foreach($statusLabels as $value => $label)
                                <option value="{{ $value }}"
                                    {{ $status === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label-modern">Employee</label>

                        <select name="employee_id"
                                class="form-select form-select-modern">

                            <option value="">All Employees</option>

                            @foreach(($employees ?? []) as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ (string) $employeeId === (string) $employee->id ? 'selected' : '' }}>
                                    {{ $employee->epf_number ?? $employee->name }}
                                    @if(!empty($employee->name))
                                        — {{ $employee->name }}
                                    @endif
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label-modern">Branch / Location</label>

                        <select name="branch_id"
                                class="form-select form-select-modern">

                            <option value="">All Locations</option>

                            @foreach(($branches ?? []) as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ (string) $branchId === (string) $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label-modern">Department</label>

                        <select name="department_id"
                                class="form-select form-select-modern">

                            <option value="">All Departments</option>

                            @foreach(($departments ?? []) as $department)
                                <option value="{{ $department->id }}"
                                    {{ (string) $departmentId === (string) $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label-modern">Item Type</label>

                        <select name="item_type_id"
                                class="form-select form-select-modern">

                            <option value="">All Item Types</option>

                            @foreach(($itemTypes ?? []) as $itemType)
                                <option value="{{ $itemType->id }}"
                                    {{ (string) $itemTypeId === (string) $itemType->id ? 'selected' : '' }}>
                                    {{ $itemType->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label-modern">Record State</label>

                        <select name="active"
                                class="form-select form-select-modern">

                            <option value="">All Records</option>
                            <option value="1" {{ $active === '1' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0" {{ $active === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label-modern">From Date</label>

                        <input type="date"
                               name="from_date"
                               value="{{ $fromDate }}"
                               class="form-control form-control-modern">
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label-modern">To Date</label>

                        <input type="date"
                               name="to_date"
                               value="{{ $toDate }}"
                               class="form-control form-control-modern">
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="filter-actions">

                            <button type="submit"
                                    class="btn btn-primary btn-modern">
                                <i class="bi bi-search me-1"></i>
                                Apply Filters
                            </button>

                            <a href="{{ route('admin.reports.rdd.index', ['report_type' => $reportType]) }}"
                               class="btn btn-light border btn-modern">
                                Clear
                            </a>

                        </div>
                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- KPI Summary --}}
    @if($reportType === 'summary' || $reportType === 'location')

        <div class="kpi-grid">

            <div class="kpi-card" style="--kpi-color:#0d6efd;">
                <div class="kpi-label">Total RDD Records</div>
                <div class="kpi-value">{{ number_format($totalRdd) }}</div>
                <div class="kpi-icon">
                    <i class="bi bi-recycle"></i>
                </div>
            </div>

            <div class="kpi-card" style="--kpi-color:#198754;">
                <div class="kpi-label">Active RDD</div>
                <div class="kpi-value">{{ number_format($activeRdd) }}</div>
                <div class="kpi-icon">
                    <i class="bi bi-activity"></i>
                </div>
            </div>

            <div class="kpi-card" style="--kpi-color:#ffc107;">
                <div class="kpi-label">Pending / Processing</div>
                <div class="kpi-value">{{ number_format($pendingRdd) }}</div>
                <div class="kpi-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>

            <div class="kpi-card" style="--kpi-color:#6f42c1;">
                <div class="kpi-label">Completed / Returned</div>
                <div class="kpi-value">{{ number_format($completedRdd) }}</div>
                <div class="kpi-icon">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>

            <div class="kpi-card" style="--kpi-color:#0dcaf0;">
                <div class="kpi-label">Received / Inspection</div>
                <div class="kpi-value">{{ number_format($receivedRdd) }}</div>
                <div class="kpi-icon">
                    <i class="bi bi-box-arrow-in-down"></i>
                </div>
            </div>

            <div class="kpi-card" style="--kpi-color:#dc3545;">
                <div class="kpi-label">Rejected / Cancelled</div>
                <div class="kpi-value">{{ number_format($rejectedRdd) }}</div>
                <div class="kpi-icon">
                    <i class="bi bi-x-circle"></i>
                </div>
            </div>

            <div class="kpi-card" style="--kpi-color:#b02a37;">
                <div class="kpi-label">Beyond Repair</div>
                <div class="kpi-value">{{ number_format($beyondRepair) }}</div>
                <div class="kpi-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>

            <div class="kpi-card" style="--kpi-color:#20c997;">
                <div class="kpi-label">Recommendations Recorded</div>
                <div class="kpi-value">{{ number_format($recommendationRecorded) }}</div>
                <div class="kpi-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>
            </div>

        </div>

    @endif

    {{-- RDD Summary --}}
    @if($reportType === 'summary')

        <div class="content-card">

            <div class="card-header-modern d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>
                    <h6>
                        <i class="bi bi-bar-chart-line text-primary me-2"></i>
                        RDD Summary
                    </h6>

                    <small class="text-muted">
                        Recommended-for-disposal transaction overview
                    </small>
                </div>

                <span class="badge bg-light text-dark border">
                    {{ $rddTransactions instanceof \Illuminate\Pagination\AbstractPaginator
                        ? $rddTransactions->total()
                        : $rddTransactions->count() }}
                    Records
                </span>

            </div>

            <div class="table-responsive">

                <table class="table modern-table align-middle">

                    <thead>
                        <tr>
                            <th>RDD Reference</th>
                            <th>Item</th>
                            <th>Location</th>
                            <th>Employee</th>
                            <th>RDD Date</th>
                            <th>Status</th>
                            <th>Record State</th>
                            <th>Recommendation</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($rddTransactions as $rdd)

                            @php
                                $item = $rdd->item;
                                $branch = $item?->branch;
                                $department = $item?->department;
                                $employee = $rdd->employee ?? $item?->currentEmployee;
                                $rddStatus = $rdd->status ?? 'pending';
                            @endphp

                            <tr>

                                <td>
                                    <div class="reference-number">
                                        {{ $rdd->reference_no ?? 'RDD-' . $rdd->id }}
                                    </div>

                                    @if($rdd->reason)
                                        <div class="sub-text">
                                            {{ \Illuminate\Support\Str::limit($rdd->reason, 45) }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="asset-number">
                                        {{ $item?->asset_number ?? '—' }}
                                    </div>

                                    <div class="sub-text">
                                        {{ $item?->itemType?->name ?? 'Item Type N/A' }}
                                    </div>

                                    @if($item?->serial_number)
                                        <div class="sub-text">
                                            SN: {{ $item->serial_number }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div>{{ $branch?->name ?? '—' }}</div>

                                    @if($department?->name)
                                        <div class="sub-text">
                                            {{ $department->name }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if($employee)
                                        <div>{{ $employee->name }}</div>
                                        @if($employee->epf_number)
                                            <div class="sub-text">
                                                EPF: {{ $employee->epf_number }}
                                            </div>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    {{ $rdd->rdd_date?->format('d M Y') ?? '—' }}
                                </td>

                                <td>
                                    <span class="status-badge {{ $statusClasses[$rddStatus] ?? 'status-inspection' }}">
                                        {{ $statusLabels[$rddStatus] ?? \Illuminate\Support\Str::headline($rddStatus) }}
                                    </span>
                                </td>

                                <td>
                                    @if($rdd->is_active)
                                        <span class="active-badge">Active</span>
                                    @else
                                        <span class="inactive-badge">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    @if($rdd->recommendation)
                                        <span class="text-dark">
                                            {{ \Illuminate\Support\Str::limit($rdd->recommendation, 50) }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not recorded</span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox d-block"></i>
                                        <h6>No RDD records found</h6>
                                        <p class="mb-0">
                                            Try adjusting the selected report filters.
                                        </p>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($rddTransactions instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)

                <div class="p-3 border-top">
                    {{ $rddTransactions->withQueryString()->links() }}
                </div>

            @endif

        </div>

    @endif

    {{-- RDD History --}}
    @if($reportType === 'history')

        <div class="content-card">

            <div class="card-header-modern d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>
                    <h6>
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        RDD History
                    </h6>

                    <small class="text-muted">
                        RDD transactions and recorded status progression
                    </small>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table modern-table align-middle">

                    <thead>
                        <tr>
                            <th>RDD Reference</th>
                            <th>Item</th>
                            <th>Location</th>
                            <th>Current Status</th>
                            <th>History</th>
                            <th>RDD Date</th>
                            <th>Completed Date</th>
                            <th>Record State</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($rddTransactions as $rdd)

                            @php
                                $item = $rdd->item;
                                $branch = $item?->branch;
                                $department = $item?->department;
                                $rddStatus = $rdd->status ?? 'pending';
                                $histories = $rdd->histories ?? collect();
                            @endphp

                            <tr>

                                <td>
                                    <div class="reference-number">
                                        {{ $rdd->reference_no ?? 'RDD-' . $rdd->id }}
                                    </div>

                                    <div class="sub-text">
                                        {{ $rdd->reason
                                            ? \Illuminate\Support\Str::limit($rdd->reason, 40)
                                            : 'No reason recorded' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="asset-number">
                                        {{ $item?->asset_number ?? '—' }}
                                    </div>

                                    <div class="sub-text">
                                        {{ $item?->itemType?->name ?? 'Item Type N/A' }}
                                    </div>
                                </td>

                                <td>
                                    <div>{{ $branch?->name ?? '—' }}</div>

                                    @if($department?->name)
                                        <div class="sub-text">
                                            {{ $department->name }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="status-badge {{ $statusClasses[$rddStatus] ?? 'status-inspection' }}">
                                        {{ $statusLabels[$rddStatus] ?? \Illuminate\Support\Str::headline($rddStatus) }}
                                    </span>
                                </td>

                                <td style="min-width:260px;">

                                    @if($histories->count())

                                        @foreach($histories as $history)

                                            <div class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">

                                                <div class="d-flex align-items-center gap-2">

                                                    <span class="small fw-semibold">
                                                        {{ $statusLabels[$history->new_status] ?? \Illuminate\Support\Str::headline($history->new_status ?? 'Updated') }}
                                                    </span>

                                                    <span class="text-muted small">
                                                        {{ $history->created_at?->format('d M Y H:i') }}
                                                    </span>

                                                </div>

                                                @if($history->old_status)
                                                    <div class="sub-text">
                                                        {{ $statusLabels[$history->old_status] ?? \Illuminate\Support\Str::headline($history->old_status) }}
                                                        →
                                                        {{ $statusLabels[$history->new_status] ?? \Illuminate\Support\Str::headline($history->new_status) }}
                                                    </div>
                                                @endif

                                                @if($history->remarks)
                                                    <div class="sub-text">
                                                        {{ \Illuminate\Support\Str::limit($history->remarks, 60) }}
                                                    </div>
                                                @endif

                                            </div>

                                        @endforeach

                                    @else

                                        <span class="text-muted">
                                            No history recorded
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $rdd->rdd_date?->format('d M Y') ?? '—' }}
                                </td>

                                <td>
                                    {{ $rdd->completed_date?->format('d M Y') ?? '—' }}
                                </td>

                                <td>
                                    @if($rdd->is_active)
                                        <span class="active-badge">Active</span>
                                    @else
                                        <span class="inactive-badge">Inactive</span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="bi bi-clock-history d-block"></i>
                                        <h6>No RDD history found</h6>
                                        <p class="mb-0">
                                            No transactions match the current filters.
                                        </p>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($rddTransactions instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)

                <div class="p-3 border-top">
                    {{ $rddTransactions->withQueryString()->links() }}
                </div>

            @endif

        </div>

    @endif

    {{-- RDD By Location --}}
    @if($reportType === 'location')

        <div class="content-card">

            <div class="card-header-modern d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>
                    <h6>
                        <i class="bi bi-geo-alt text-success me-2"></i>
                        RDD by Location
                    </h6>

                    <small class="text-muted">
                        RDD activity grouped by branch / operational location
                    </small>
                </div>

                <span class="badge bg-light text-dark border">
                    {{ $locationRows->count() }} Locations
                </span>

            </div>

            <div class="p-3">

                <div class="row g-3">

                    @forelse($locationRows as $location)

                        <div class="col-xl-4 col-lg-6">

                            <div class="location-card">

                                <div class="d-flex justify-content-between align-items-start gap-3">

                                    <div>
                                        <div class="location-name">
                                            <i class="bi bi-geo-alt-fill text-success me-1"></i>
                                            {{ $location['branch_name'] ?? 'Unknown Location' }}
                                        </div>

                                        @if(!empty($location['branch_id']))
                                            <div class="location-meta">
                                                Branch ID: {{ $location['branch_id'] }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-end">
                                        <div class="location-total">
                                            {{ number_format($location['total'] ?? 0) }}
                                        </div>
                                        <div class="location-meta">
                                            Total RDD
                                        </div>
                                    </div>

                                </div>

                                <div class="row g-2 location-stat">

                                    <div class="col-6">
                                        <span class="d-block">Active</span>
                                        <strong>
                                            {{ number_format($location['active'] ?? 0) }}
                                        </strong>
                                    </div>

                                    <div class="col-6">
                                        <span class="d-block">Pending</span>
                                        <strong>
                                            {{ number_format($location['pending'] ?? 0) }}
                                        </strong>
                                    </div>

                                    <div class="col-6 mt-3">
                                        <span class="d-block">Received</span>
                                        <strong>
                                            {{ number_format($location['received'] ?? 0) }}
                                        </strong>
                                    </div>

                                    <div class="col-6 mt-3">
                                        <span class="d-block">Completed</span>
                                        <strong>
                                            {{ number_format($location['completed'] ?? 0) }}
                                        </strong>
                                    </div>

                                    <div class="col-6 mt-3">
                                        <span class="d-block">Beyond Repair</span>
                                        <strong>
                                            {{ number_format($location['beyond_repair'] ?? 0) }}
                                        </strong>
                                    </div>

                                    <div class="col-6 mt-3">
                                        <span class="d-block">Rejected</span>
                                        <strong>
                                            {{ number_format($location['rejected'] ?? 0) }}
                                        </strong>
                                    </div>

                                </div>

                                @if(!empty($location['departments']))

                                    <div class="location-stat">

                                        <span class="d-block mb-2">
                                            Department Distribution
                                        </span>

                                        @foreach($location['departments'] as $departmentName => $count)

                                            <div class="d-flex justify-content-between align-items-center mb-1">

                                                <span class="small text-muted">
                                                    {{ $departmentName }}
                                                </span>

                                                <strong>
                                                    {{ $count }}
                                                </strong>

                                            </div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="col-12">

                            <div class="empty-state">
                                <i class="bi bi-geo-alt d-block"></i>

                                <h6>No location data found</h6>

                                <p class="mb-0">
                                    No RDD records match the selected filters.
                                </p>
                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    @endif

</div>

@endsection