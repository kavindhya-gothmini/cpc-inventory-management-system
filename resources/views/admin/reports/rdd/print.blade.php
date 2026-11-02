@extends('layouts.admin')

@section('title', 'RDD Report Print | CPC Inventory Management System')

@section('content')

@php
    $reportType = $reportType ?? request('report_type', 'summary');

    $rddTransactions = $rddTransactions ?? $transactions ?? collect();
    $locationRows = $locationRows ?? collect();

    $totalRdd = $totalRdd ?? 0;
    $activeRdd = $activeRdd ?? 0;
    $inactiveRdd = $inactiveRdd ?? 0;
    $pendingRdd = $pendingRdd ?? 0;
    $receivedRdd = $receivedRdd ?? 0;
    $completedRdd = $completedRdd ?? 0;
    $rejectedRdd = $rejectedRdd ?? 0;
    $beyondRepair = $beyondRepair ?? $beyondRepairRdd ?? 0;
    $recommendationRecorded = $recommendationRecorded ?? 0;

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

    $reportTitles = [
        'summary' => 'RDD Summary Report',
        'active' => 'Active RDD Report',
        'history' => 'RDD History Report',
        'location' => 'RDD Location Report',
    ];

    $reportTitle = $reportTitles[$reportType] ?? 'RDD Report';
@endphp

<style>
    @page {
        size: A4 landscape;
        margin: 12mm;
    }

    .rdd-print-page {
        background: #fff;
        color: #1f2937;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
    }

    .print-toolbar {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-bottom: 18px;
    }

    .print-toolbar button,
    .print-toolbar a {
        border: 0;
        border-radius: 6px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-print {
        background: #0d6efd;
        color: #fff;
    }

    .btn-back {
        background: #6c757d;
        color: #fff;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #0d6efd;
        padding-bottom: 12px;
        margin-bottom: 16px;
    }

    .company-section {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .company-logo {
        width: 62px;
        height: 62px;
        object-fit: contain;
    }

    .company-name {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #123b63;
    }

    .company-subtitle {
        margin: 3px 0 0;
        color: #6b7280;
        font-size: 11px;
    }

    .report-heading {
        text-align: right;
    }

    .report-heading h2 {
        margin: 0;
        font-size: 18px;
        color: #123b63;
    }

    .report-heading p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 10px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 7px;
        margin-bottom: 18px;
    }

    .summary-card {
        border: 1px solid #dce3ea;
        border-radius: 6px;
        padding: 8px;
        background: #f8fafc;
    }

    .summary-label {
        font-size: 8px;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .3px;
    }

    .summary-value {
        margin-top: 3px;
        font-size: 16px;
        font-weight: 700;
        color: #123b63;
    }

    .section-title {
        margin: 16px 0 8px;
        padding-bottom: 5px;
        border-bottom: 1px solid #d9e1e8;
        color: #123b63;
        font-size: 13px;
        font-weight: 700;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 14px;
    }

    th {
        background: #123b63;
        color: #fff;
        padding: 6px 5px;
        border: 1px solid #123b63;
        font-size: 8.5px;
        text-align: left;
        white-space: nowrap;
    }

    td {
        padding: 5px;
        border: 1px solid #d9dee5;
        vertical-align: top;
        font-size: 8.5px;
    }

    tbody tr:nth-child(even) {
        background: #f8fafc;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .status {
        display: inline-block;
        padding: 3px 6px;
        border-radius: 10px;
        font-size: 7.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #f1f5f9;
        color: #475569;
    }

    .status-pending {
        background: #fff7ed;
        color: #9a3412;
    }

    .status-completed {
        background: #dcfce7;
        color: #166534;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-beyond {
        background: #fef3c7;
        color: #92400e;
    }

    .location-summary {
        page-break-inside: avoid;
    }

    .report-footer {
        margin-top: 20px;
        padding-top: 8px;
        border-top: 1px solid #d9dee5;
        display: flex;
        justify-content: space-between;
        color: #6b7280;
        font-size: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 20px;
        color: #6b7280;
        border: 1px solid #d9dee5;
    }

    @media print {
        .print-toolbar,
        nav,
        aside,
        .sidebar,
        .navbar {
            display: none !important;
        }

        body {
            background: #fff !important;
        }

        .rdd-print-page {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>

<div class="rdd-print-page">

    {{-- Print Controls --}}
    <div class="print-toolbar">
        <a
            href="{{ route('admin.reports.rdd.index', request()->query()) }}"
            class="btn-back"
        >
            Back to Report
        </a>

        <button
            type="button"
            class="btn-print"
            onclick="window.print()"
        >
            Print Report
        </button>
    </div>

    {{-- Report Header --}}
    <div class="report-header">

        <div class="company-section">

            @if(file_exists(public_path('images/cpc-logo.png')))
                <img
                    src="{{ asset('images/cpc-logo.png') }}"
                    alt="CPC Logo"
                    class="company-logo"
                >
            @endif

            <div>
                <h1 class="company-name">
                    Ceylon Petroleum Corporation
                </h1>

                <p class="company-subtitle">
                    IT Function — Inventory Management System
                </p>
            </div>

        </div>

        <div class="report-heading">

            <h2>
                {{ $reportTitle }}
            </h2>

            <p>
                Generated:
                {{ now()->format('d M Y, h:i A') }}
            </p>

            <p>
                Report Type:
                {{ ucfirst(str_replace('_', ' ', $reportType)) }}
            </p>

        </div>

    </div>


    {{-- KPI Summary --}}
    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-label">Total RDD</div>
            <div class="summary-value">{{ number_format($totalRdd) }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Active</div>
            <div class="summary-value">{{ number_format($activeRdd) }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Inactive</div>
            <div class="summary-value">{{ number_format($inactiveRdd) }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Pending</div>
            <div class="summary-value">{{ number_format($pendingRdd) }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Received</div>
            <div class="summary-value">{{ number_format($receivedRdd) }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Completed</div>
            <div class="summary-value">{{ number_format($completedRdd) }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Rejected</div>
            <div class="summary-value">{{ number_format($rejectedRdd) }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Beyond Repair</div>
            <div class="summary-value">{{ number_format($beyondRepair) }}</div>
        </div>

    </div>


    {{-- Location Report --}}
    @if($reportType === 'location')

        <div class="section-title">
            RDD Location Summary
        </div>

        @if($locationRows->count())

            <table class="location-summary">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Branch / Location</th>
                        <th>Branch ID</th>
                        <th>Total</th>
                        <th>Active</th>
                        <th>Pending</th>
                        <th>Received</th>
                        <th>Completed</th>
                        <th>Beyond Repair</th>
                        <th>Rejected</th>
                        <th>Departments</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($locationRows as $index => $location)

                        <tr>

                            <td class="text-center">
                                {{ $index + 1 }}
                            </td>

                            <td>
                                <strong>
                                    {{ $location['branch_name'] ?? 'Unknown Location' }}
                                </strong>
                            </td>

                            <td>
                                {{ $location['branch_id'] ?? '—' }}
                            </td>

                            <td class="text-center">
                                {{ number_format($location['total'] ?? 0) }}
                            </td>

                            <td class="text-center">
                                {{ number_format($location['active'] ?? 0) }}
                            </td>

                            <td class="text-center">
                                {{ number_format($location['pending'] ?? 0) }}
                            </td>

                            <td class="text-center">
                                {{ number_format($location['received'] ?? 0) }}
                            </td>

                            <td class="text-center">
                                {{ number_format($location['completed'] ?? 0) }}
                            </td>

                            <td class="text-center">
                                {{ number_format($location['beyond_repair'] ?? 0) }}
                            </td>

                            <td class="text-center">
                                {{ number_format($location['rejected'] ?? 0) }}
                            </td>

                            <td>
                                {{ $location['departments'] ?? '—' }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-state">
                No RDD location records were found for the selected filters.
            </div>

        @endif

    @else

        {{-- Detailed RDD Report --}}
        <div class="section-title">
            RDD Transaction Details
        </div>

        @if($rddTransactions->count())

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>RDD Reference</th>
                        <th>Asset Number</th>
                        <th>Item Type</th>
                        <th>Serial Number</th>
                        <th>Branch</th>
                        <th>Department</th>
                        <th>Employee</th>
                        <th>EPF No.</th>
                        <th>RDD Date</th>
                        <th>Status</th>
                        <th>Record State</th>
                        <th>Recommendation</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($rddTransactions as $index => $rdd)

                        @php
                            $item = $rdd->item;
                            $branch = $item?->branch;
                            $department = $item?->department;
                            $employee = $rdd->employee ?? $item?->currentEmployee;
                            $rddStatus = $rdd->status ?? 'pending';

                            $statusClass = match ($rddStatus) {
                                'completed', 'returned' => 'status-completed',
                                'rejected', 'cancelled' => 'status-rejected',
                                'beyond_repair' => 'status-beyond',
                                'pending', 'approved', 'dispatched',
                                'received', 'inspection', 'in_progress' => 'status-pending',
                                default => 'status-inactive',
                            };
                        @endphp

                        <tr>

                            <td class="text-center">
                                {{ $index + 1 }}
                            </td>

                            <td>
                                <strong>
                                    {{ $rdd->reference_no ?? 'RDD-' . $rdd->id }}
                                </strong>
                            </td>

                            <td>
                                {{ $item?->asset_number ?? '—' }}
                            </td>

                            <td>
                                {{ $item?->itemType?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $item?->serial_number ?? '—' }}
                            </td>

                            <td>
                                {{ $branch?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $department?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $employee?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $employee?->epf_no ?? '—' }}
                            </td>

                            <td>
                                {{ $rdd->rdd_date
                                    ? \Carbon\Carbon::parse($rdd->rdd_date)->format('d M Y')
                                    : '—'
                                }}
                            </td>

                            <td>
                                <span class="status {{ $statusClass }}">
                                    {{ $statusLabels[$rddStatus] ?? ucfirst(str_replace('_', ' ', $rddStatus)) }}
                                </span>
                            </td>

                            <td>

                                @if($rdd->is_active)
                                    <span class="status status-active">
                                        Active
                                    </span>
                                @else
                                    <span class="status status-inactive">
                                        Inactive
                                    </span>
                                @endif

                            </td>

                            <td>
                                {{ $rdd->recommendation ?? '—' }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-state">
                No RDD records were found for the selected filters.
            </div>

        @endif

    @endif


    {{-- Footer --}}
    <div class="report-footer">

        <span>
            CPC Inventory Management System
        </span>

        <span>
            Confidential — Internal Use Only
        </span>

        <span>
            Page generated {{ now()->format('d M Y') }}
        </span>

    </div>

</div>

@endsection