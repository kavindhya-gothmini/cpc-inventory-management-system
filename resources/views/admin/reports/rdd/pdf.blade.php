<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        CPC RDD Report
    </title>

    <style>

        @page {
            size: A4 landscape;
            margin: 12mm 10mm 12mm 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #212529;
            background: #ffffff;
        }

        .document {
            width: 100%;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .header td {
            border: none;
        }

        .logo-cell {
            width: 90px;
            vertical-align: middle;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        .header-main {
            vertical-align: middle;
        }

        .organization {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .function {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .system {
            font-size: 9px;
            color: #495057;
        }

        .header-meta {
            width: 190px;
            text-align: right;
            vertical-align: middle;
        }

        .official {
            font-size: 8px;
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .generated {
            font-size: 8px;
            color: #495057;
        }

        .header-line {
            border-bottom: 2px solid #0d6efd;
            margin-bottom: 11px;
        }


        /* =====================================================
           REPORT TITLE
        ===================================================== */

        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .report-subtitle {
            text-align: center;
            color: #6c757d;
            font-size: 8px;
            margin-bottom: 11px;
        }


        /* =====================================================
           SECTION
        ===================================================== */

        .section-title {
            background: #e9ecef;
            border: 1px solid #d9dee3;
            padding: 5px 7px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }


        /* =====================================================
           CRITERIA
        ===================================================== */

        .criteria {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .criteria td {
            border: 1px solid #dee2e6;
            padding: 5px 6px;
            vertical-align: middle;
        }

        .criteria-label {
            width: 14%;
            background: #f8f9fa;
            font-weight: bold;
        }

        .criteria-value {
            width: 36%;
        }


        /* =====================================================
           SUMMARY
        ===================================================== */

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .summary td {
            width: 16.66%;
            border: 1px solid #d9dee3;
            text-align: center;
            padding: 6px;
        }

        .summary-label {
            font-size: 7px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .summary-value {
            font-size: 13px;
            font-weight: bold;
        }


        /* =====================================================
           MAIN TABLE
        ===================================================== */

        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .report-table th {
            background: #0d6efd;
            color: #ffffff;
            border: 1px solid #0b5ed7;
            padding: 5px 3px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .report-table td {
            border: 1px solid #dee2e6;
            padding: 4px 3px;
            font-size: 6.9px;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .report-table tr {
            page-break-inside: avoid;
        }

        .report-table tbody tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .center {
            text-align: center;
        }


        /* =====================================================
           SUMMARY TABLE COLUMNS
        ===================================================== */

        .col-reference {
            width: 8%;
        }

        .col-asset {
            width: 9%;
        }

        .col-serial {
            width: 10%;
        }

        .col-type {
            width: 10%;
        }

        .col-branch {
            width: 10%;
        }

        .col-department {
            width: 11%;
        }

        .col-employee {
            width: 10%;
        }

        .col-date {
            width: 8%;
        }

        .col-status {
            width: 8%;
        }

        .col-reason {
            width: 16%;
        }


        /* =====================================================
           HISTORY TABLE
        ===================================================== */

        .history-reference {
            width: 9%;
        }

        .history-asset {
            width: 8%;
        }

        .history-type {
            width: 10%;
        }

        .history-branch {
            width: 10%;
        }

        .history-department {
            width: 10%;
        }

        .history-date {
            width: 8%;
        }

        .history-status {
            width: 10%;
        }

        .history-events {
            width: 7%;
        }

        .history-change {
            width: 11%;
        }

        .history-completed {
            width: 9%;
        }

        .history-state {
            width: 8%;
        }


        /* =====================================================
           LOCATION TABLE
        ===================================================== */

        .location-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .location-table th {
            background: #0d6efd;
            color: #ffffff;
            border: 1px solid #0b5ed7;
            padding: 5px 3px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .location-table td {
            border: 1px solid #dee2e6;
            padding: 5px 4px;
            font-size: 7px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .location-table tbody tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .location-branch {
            width: 22%;
        }

        .location-total {
            width: 9%;
        }

        .location-active {
            width: 9%;
        }

        .location-pending {
            width: 10%;
        }

        .location-received {
            width: 10%;
        }

        .location-completed {
            width: 10%;
        }

        .location-beyond {
            width: 10%;
        }

        .location-rejected {
            width: 10%;
        }

        .location-department {
            width: 10%;
        }


        /* =====================================================
           STATUS
        ===================================================== */

        .status {
            text-align: center;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-pending {
            color: #856404;
        }

        .status-approved {
            color: #2456a6;
        }

        .status-dispatched {
            color: #5b3fa3;
        }

        .status-received {
            color: #12618f;
        }

        .status-completed {
            color: #176b35;
        }

        .status-beyond-repair {
            color: #a12626;
        }

        .status-cancelled {
            color: #555555;
        }


        /* =====================================================
           RECORD STATE
        ===================================================== */

        .active-state {
            color: #176b35;
            font-weight: bold;
        }

        .inactive-state {
            color: #6c757d;
            font-weight: bold;
        }


        /* =====================================================
           EMPTY
        ===================================================== */

        .empty {
            border: 1px solid #dee2e6;
            padding: 18px;
            text-align: center;
            color: #6c757d;
        }


        /* =====================================================
           SIGNATURE
        ===================================================== */

        .signature-section {
            margin-top: 18px;
            page-break-inside: avoid;
        }

        .signature-title {
            background: #e9ecef;
            border: 1px solid #d9dee3;
            padding: 5px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .signatures {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .signature-column {
            width: 33.33%;
            padding: 0 5px;
            vertical-align: top;
        }

        .signature-box {
            border: 1px solid #d9dee3;
            min-height: 105px;
            padding: 8px;
        }

        .signature-role {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 22px;
        }

        .line {
            border-bottom: 1px solid #343a40;
            height: 18px;
            margin-bottom: 4px;
        }

        .field {
            font-size: 7px;
            color: #495057;
            margin-bottom: 3px;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 7px;
            color: #6c757d;
        }

        .footer-main {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .footer-note {
            font-size: 6.5px;
        }


    </style>

</head>


<body>

<div class="document">


    @php

        /*
        |--------------------------------------------------------------------------
        | REPORT TYPE
        |--------------------------------------------------------------------------
        */

        $reportType = $reportType
            ?? request('report_type', 'summary');


        $reportTitle = match ($reportType) {

            'history' =>
                'RDD History Report',

            'active' =>
                'Active RDD Report',

            'location' =>
                'RDD by Location Report',

            default =>
                'RDD Summary Report',
        };


        /*
        |--------------------------------------------------------------------------
        | TRANSACTIONS
        |--------------------------------------------------------------------------
        */

        $transactions = $rddTransactions
            ?? $transactions
            ?? $rdds
            ?? $records
            ?? collect();

        if (is_array($transactions)) {
            $transactions = collect($transactions);
        }


        /*
        |--------------------------------------------------------------------------
        | KPI VALUES
        |--------------------------------------------------------------------------
        */

        $totalRdd = $totalRdd
            ?? $transactions->count();

        $activeRdd = $activeRdd
            ?? $transactions
                ->where('is_active', true)
                ->count();

        $inactiveRdd = $inactiveRdd
            ?? $transactions
                ->where('is_active', false)
                ->count();

        $pendingRdd = $pendingRdd
            ?? $transactions
                ->whereIn('status', [
                    'pending',
                    'approved',
                    'dispatched',
                ])
                ->count();

        $receivedRdd = $receivedRdd
            ?? $transactions
                ->whereIn('status', [
                    'received',
                    'inspection',
                    'in_progress',
                ])
                ->count();

        $completedRdd = $completedRdd
            ?? $transactions
                ->whereIn('status', [
                    'completed',
                    'returned',
                ])
                ->count();

        $beyondRepairRdd = $beyondRepairRdd
            ?? $beyondRepair
            ?? $transactions
                ->where(
                    'status',
                    'beyond_repair'
                )
                ->count();


        $rejectedRdd = $rejectedRdd
            ?? $transactions
                ->whereIn('status', [
                    'rejected',
                    'cancelled',
                ])
                ->count();


        /*
        |--------------------------------------------------------------------------
        | LOCATION DATA
        |--------------------------------------------------------------------------
        */

        $locationRows = $locationRows
            ?? $locations
            ?? collect();

        if (is_array($locationRows)) {
            $locationRows = collect($locationRows);
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS LABEL
        |--------------------------------------------------------------------------
        */

        $statusText = function ($status) {

            if (!$status) {
                return '—';
            }

            return match ($status) {

                'pending' =>
                    'Pending',

                'approved' =>
                    'Approved',

                'dispatched' =>
                    'Dispatched',

                'received' =>
                    'Received',

                'inspection' =>
                    'Inspection',

                'in_progress' =>
                    'In Progress',

                'completed' =>
                    'Completed',

                'returned' =>
                    'Returned',

                'beyond_repair' =>
                    'Beyond Repair',

                'rejected' =>
                    'Rejected',

                'cancelled' =>
                    'Cancelled',

                default =>
                    ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $status
                        )
                    ),
            };
        };


        /*
        |--------------------------------------------------------------------------
        | STATUS CLASS
        |--------------------------------------------------------------------------
        */

        $statusClass = function ($status) {

            return match ($status) {

                'pending' =>
                    'status-pending',

                'approved' =>
                    'status-approved',

                'dispatched' =>
                    'status-dispatched',

                'received',
                'inspection',
                'in_progress' =>
                    'status-received',

                'completed',
                'returned' =>
                    'status-completed',

                'beyond_repair' =>
                    'status-beyond-repair',

                'rejected',
                'cancelled' =>
                    'status-cancelled',

                default =>
                    'status-cancelled',
            };
        };


        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        $formatDate = function ($date) {

            if (!$date) {
                return '—';
            }

            try {

                return \Carbon\Carbon::parse($date)
                    ->format('d M Y');

            } catch (\Throwable $e) {

                return '—';
            }
        };


        /*
        |--------------------------------------------------------------------------
        | FILTER VALUES
        |--------------------------------------------------------------------------
        */

        $selectedStatus =
            $status
            ?? request('status');

        $selectedActive =
            $active
            ?? request('active');

        $selectedBranchName =
            $selectedBranchName
            ?? null;

        $selectedDepartmentName =
            $selectedDepartmentName
            ?? null;

        $selectedEmployeeName =
            $selectedEmployeeName
            ?? null;

        $selectedItemTypeName =
            $selectedItemTypeName
            ?? null;

        $fromDate =
            $fromDate
            ?? request('from_date');

        $toDate =
            $toDate
            ?? request('to_date');


        /*
        |--------------------------------------------------------------------------
        | GENERATED TIME
        |--------------------------------------------------------------------------
        */

        $generatedAt = now()->format(
            'd M Y, h:i A'
        );

    @endphp



    {{-- =====================================================
         CPC HEADER
    ====================================================== --}}

    <table class="header">

        <tr>

            <td class="logo-cell">

                @php
                    $logoPath =
                        public_path(
                            'images/cpc-logo.png'
                        );
                @endphp

                @if(file_exists($logoPath))

                    <img
                        src="{{ $logoPath }}"
                        class="logo"
                        alt="CPC Logo"
                    >

                @endif

            </td>


            <td class="header-main">

                <div class="organization">
                    CEYLON PETROLEUM CORPORATION
                </div>

                <div class="function">
                    INFORMATION TECHNOLOGY FUNCTION
                </div>

                <div class="system">
                    CPC INVENTORY MANAGEMENT SYSTEM
                </div>

            </td>


            <td class="header-meta">

                <div class="official">
                    OFFICIAL DOCUMENT
                </div>

                <div class="generated">
                    Generated:
                    {{ $generatedAt }}
                </div>

            </td>

        </tr>

    </table>


    <div class="header-line"></div>



    {{-- =====================================================
         REPORT TITLE
    ====================================================== --}}

    <div class="report-title">
        {{ $reportTitle }}
    </div>

    <div class="report-subtitle">
        Official RDD records generated through the CPC Inventory Management System
    </div>



    {{-- =====================================================
         REPORT CRITERIA
    ====================================================== --}}

    <div class="section-title">
        Report Criteria
    </div>


    <table class="criteria">

        <tr>

            <td class="criteria-label">
                Report Type
            </td>

            <td class="criteria-value">
                {{ $reportTitle }}
            </td>


            <td class="criteria-label">
                Status
            </td>

            <td class="criteria-value">

                @if(!empty($selectedStatus))

                    {{ $statusText($selectedStatus) }}

                @else

                    All Statuses

                @endif

            </td>

        </tr>


        <tr>

            <td class="criteria-label">
                Branch
            </td>

            <td class="criteria-value">

                {{ $selectedBranchName
                    ?? 'All Branches' }}

            </td>


            <td class="criteria-label">
                Department
            </td>

            <td class="criteria-value">

                {{ $selectedDepartmentName
                    ?? 'All Departments' }}

            </td>

        </tr>


        <tr>

            <td class="criteria-label">
                Employee
            </td>

            <td class="criteria-value">

                {{ $selectedEmployeeName
                    ?? 'All Employees' }}

            </td>


            <td class="criteria-label">
                Item Type
            </td>

            <td class="criteria-value">

                {{ $selectedItemTypeName
                    ?? 'All Item Types' }}

            </td>

        </tr>


        <tr>

            <td class="criteria-label">
                Record State
            </td>

            <td class="criteria-value">

                @if($selectedActive !== null
                    && $selectedActive !== '')

                    @if(in_array(
                        strtolower(
                            (string) $selectedActive
                        ),
                        [
                            '1',
                            'true',
                            'active'
                        ],
                        true
                    ))

                        Active

                    @elseif(in_array(
                        strtolower(
                            (string) $selectedActive
                        ),
                        [
                            '0',
                            'false',
                            'inactive'
                        ],
                        true
                    ))

                        Inactive

                    @else

                        All Records

                    @endif

                @else

                    All Records

                @endif

            </td>


            <td class="criteria-label">
                Date Range
            </td>

            <td class="criteria-value">

                @if($fromDate || $toDate)

                    {{ $fromDate
                        ? $formatDate($fromDate)
                        : 'Start' }}

                    &nbsp; — &nbsp;

                    {{ $toDate
                        ? $formatDate($toDate)
                        : 'End' }}

                @else

                    All Dates

                @endif

            </td>

        </tr>

    </table>



    {{-- =====================================================
         RDD SUMMARY
    ====================================================== --}}

    <div class="section-title">
        RDD Summary
    </div>


    <table class="summary">

        <tr>

            <td>

                <div class="summary-label">
                    Total RDD
                </div>

                <div class="summary-value">
                    {{ number_format($totalRdd) }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Active
                </div>

                <div class="summary-value">
                    {{ number_format($activeRdd) }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Pending / Processing
                </div>

                <div class="summary-value">
                    {{ number_format($pendingRdd) }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Received / Inspection
                </div>

                <div class="summary-value">
                    {{ number_format($receivedRdd) }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Completed / Returned
                </div>

                <div class="summary-value">
                    {{ number_format($completedRdd) }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Beyond Repair
                </div>

                <div class="summary-value">
                    {{ number_format($beyondRepairRdd) }}
                </div>

            </td>

        </tr>

    </table>



    {{-- =====================================================
         SUMMARY / ACTIVE REPORT
    ====================================================== --}}

    @if(
        $reportType === 'summary'
        || $reportType === 'active'
    )

        <div class="section-title">
            RDD Records
        </div>


        @if($transactions->count() > 0)

            <table class="report-table">

                <colgroup>

                    <col class="col-reference">
                    <col class="col-asset">
                    <col class="col-serial">
                    <col class="col-type">
                    <col class="col-branch">
                    <col class="col-department">
                    <col class="col-employee">
                    <col class="col-date">
                    <col class="col-status">
                    <col class="col-reason">

                </colgroup>


                <thead>

                    <tr>

                        <th>
                            Reference
                        </th>

                        <th>
                            Asset Number
                        </th>

                        <th>
                            Serial Number
                        </th>

                        <th>
                            Item Type
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
                            RDD Date
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Reason / Recommendation
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($transactions as $rdd)

                        @php

                            $item =
                                $rdd->item;

                            $employee =
                                $rdd->employee
                                ?? $item?->currentEmployee;

                        @endphp


                        <tr>

                            <td>
                                {{ $rdd->reference_no
                                    ?? 'RDD-' . $rdd->id }}
                            </td>

                            <td>
                                {{ $item?->asset_number
                                    ?? '—' }}
                            </td>

                            <td>
                                {{ $item?->serial_number
                                    ?? '—' }}
                            </td>

                            <td>
                                {{ $item?->itemType?->name
                                    ?? '—' }}
                            </td>

                            <td>
                                {{ $item?->branch?->name
                                    ?? '—' }}
                            </td>

                            <td>
                                {{ $item?->department?->name
                                    ?? '—' }}
                            </td>

                            <td>
                                {{ $employee?->name
                                    ?? '—' }}
                            </td>

                            <td class="center">

                                {{ $formatDate(
                                    $rdd->rdd_date
                                ) }}

                            </td>

                            <td class="status">

                                <span class="{{ $statusClass(
                                    $rdd->status
                                ) }}">

                                    {{ $statusText(
                                        $rdd->status
                                    ) }}

                                </span>

                            </td>

                            <td>

                                <strong>
                                    Reason:
                                </strong>

                                {{ $rdd->reason ?? '—' }}

                                @if(
                                    !empty(
                                        $rdd->recommendation
                                    )
                                )

                                    <br>

                                    <strong>
                                        Recommendation:
                                    </strong>

                                    {{ $rdd->recommendation }}

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">
                No RDD records were found for the selected report criteria.
            </div>

        @endif

    @endif



    {{-- =====================================================
         HISTORY REPORT
    ====================================================== --}}

    @if($reportType === 'history')

        <div class="section-title">
            RDD Status History
        </div>


        @if($transactions->count() > 0)

            <table class="report-table">

                <colgroup>

                    <col class="history-reference">
                    <col class="history-asset">
                    <col class="history-type">
                    <col class="history-branch">
                    <col class="history-department">
                    <col class="history-date">
                    <col class="history-status">
                    <col class="history-events">
                    <col class="history-change">
                    <col class="history-completed">
                    <col class="history-state">

                </colgroup>


                <thead>

                    <tr>

                        <th>
                            Reference
                        </th>

                        <th>
                            Asset Number
                        </th>

                        <th>
                            Item Type
                        </th>

                        <th>
                            Branch
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            RDD Date
                        </th>

                        <th>
                            Current Status
                        </th>

                        <th>
                            Events
                        </th>

                        <th>
                            Last Status Change
                        </th>

                        <th>
                            Completed
                        </th>

                        <th>
                            Record State
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($transactions as $rdd)

                        @php

                            $item =
                                $rdd->item;

                            $histories =
                                $rdd->histories
                                ?? collect();

                            if (is_array($histories)) {
                                $histories =
                                    collect($histories);
                            }

                            $latestHistory =
                                $histories
                                    ->sortByDesc(
                                        'created_at'
                                    )
                                    ->first();

                        @endphp


                        <tr>

                            <td>
                                {{ $rdd->reference_no
                                    ?? 'RDD-' . $rdd->id }}
                            </td>

                            <td>
                                {{ $item?->asset_number
                                    ?? '—' }}
                            </td>

                            <td>
                                {{ $item?->itemType?->name
                                    ?? '—' }}
                            </td>

                            <td>
                                {{ $item?->branch?->name
                                    ?? '—' }}
                            </td>

                            <td>
                                {{ $item?->department?->name
                                    ?? '—' }}
                            </td>

                            <td class="center">
                                {{ $formatDate(
                                    $rdd->rdd_date
                                ) }}
                            </td>

                            <td class="status">

                                <span class="{{ $statusClass(
                                    $rdd->status
                                ) }}">

                                    {{ $statusText(
                                        $rdd->status
                                    ) }}

                                </span>

                            </td>

                            <td class="center">
                                {{ $histories->count() }}
                            </td>

                            <td class="center">

                                @if(
                                    $latestHistory?->created_at
                                )

                                    {{ \Carbon\Carbon::parse(
                                        $latestHistory->created_at
                                    )->format(
                                        'd M Y H:i'
                                    ) }}

                                @else

                                    —

                                @endif

                            </td>

                            <td class="center">

                                {{ $formatDate(
                                    $rdd->completed_date
                                    ?? null
                                ) }}

                            </td>

                            <td class="center">

                                @if($rdd->is_active)

                                    <span class="active-state">
                                        Active
                                    </span>

                                @else

                                    <span class="inactive-state">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">
                No RDD history records were found.
            </div>

        @endif

    @endif



    {{-- =====================================================
         LOCATION REPORT
    ====================================================== --}}

    @if($reportType === 'location')

        <div class="section-title">
            RDD Distribution by Location
        </div>


        @if($locationRows->count() > 0)

            <table class="location-table">

                <colgroup>

                    <col class="location-branch">
                    <col class="location-total">
                    <col class="location-active">
                    <col class="location-pending">
                    <col class="location-received">
                    <col class="location-completed">
                    <col class="location-beyond">
                    <col class="location-rejected">
                    <col class="location-department">

                </colgroup>


                <thead>

                    <tr>

                        <th>
                            Branch / Location
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Active
                        </th>

                        <th>
                            Pending
                        </th>

                        <th>
                            Received
                        </th>

                        <th>
                            Completed
                        </th>

                        <th>
                            Beyond Repair
                        </th>

                        <th>
                            Rejected / Cancelled
                        </th>

                        <th>
                            Departments
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($locationRows as $location)

                        @php

                            $departments =
                                $location['departments']
                                ?? [];

                            if (
                                $departments
                                instanceof \Illuminate\Support\Collection
                            ) {
                                $departments =
                                    $departments->toArray();
                            }

                        @endphp


                        <tr>

                            <td>

                                <strong>

                                    {{ $location['branch_name']
                                        ?? $location['name']
                                        ?? 'Location Not Assigned' }}

                                </strong>

                                @if(
                                    !empty(
                                        $location['branch_id']
                                    )
                                )

                                    <br>

                                    <span class="muted">
                                        Branch ID:
                                        {{ $location['branch_id'] }}
                                    </span>

                                @endif

                            </td>


                            <td class="center">
                                {{ number_format(
                                    $location['total']
                                    ?? 0
                                ) }}
                            </td>


                            <td class="center">
                                {{ number_format(
                                    $location['active']
                                    ?? 0
                                ) }}
                            </td>


                            <td class="center">
                                {{ number_format(
                                    $location['pending']
                                    ?? 0
                                ) }}
                            </td>


                            <td class="center">
                                {{ number_format(
                                    $location['received']
                                    ?? 0
                                ) }}
                            </td>


                            <td class="center">
                                {{ number_format(
                                    $location['completed']
                                    ?? 0
                                ) }}
                            </td>


                            <td class="center">
                                {{ number_format(
                                    $location['beyond_repair']
                                    ?? 0
                                ) }}
                            </td>


                            <td class="center">
                                {{ number_format(
                                    $location['rejected']
                                    ?? 0
                                ) }}
                            </td>


                            <td>

                                @if(is_array($departments))

                                    @forelse(
                                        $departments
                                        as $department => $count
                                    )

                                        @if(is_numeric($count))

                                            {{ $department }}:
                                            {{ number_format(
                                                $count
                                            ) }}

                                        @else

                                            {{ $count }}

                                        @endif

                                        @if(!$loop->last)
                                            <br>
                                        @endif

                                    @empty

                                        —

                                    @endforelse

                                @else

                                    {{ $departments ?: '—' }}

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">
                No location-based RDD data was found.
            </div>

        @endif

    @endif



    {{-- =====================================================
         VERIFICATION
    ====================================================== --}}

    <div class="signature-section">

        <div class="signature-title">
            Document Certification &amp; Authorization
        </div>


        <table class="signatures">

            <tr>


                {{-- Prepared By --}}
                <td class="signature-column">

                    <div class="signature-box">

                        <div class="signature-role">
                            Prepared By
                        </div>

                        <div class="line"></div>

                        <div class="field">
                            Signature
                        </div>

                        <div class="line"></div>

                        <div class="field">
                            Name / Designation
                        </div>

                        <div class="line"></div>

                        <div class="field">
                            Date
                        </div>

                    </div>

                </td>


                {{-- Checked By --}}
                <td class="signature-column">

                    <div class="signature-box">

                        <div class="signature-role">
                            Checked By
                        </div>

                        <div class="line"></div>

                        <div class="field">
                            Signature
                        </div>

                        <div class="line"></div>

                        <div class="field">
                            Name / Designation
                        </div>

                        <div class="line"></div>

                        <div class="field">
                            Date
                        </div>

                    </div>

                </td>


                {{-- Authorized By --}}
                <td class="signature-column">

                    <div class="signature-box">

                        <div class="signature-role">
                            Authorized By
                        </div>

                        <div class="line"></div>

                        <div class="field">
                            Signature
                        </div>

                        <div class="line"></div>

                        <div class="field">
                            Name / Designation
                        </div>

                        <div class="line"></div>

                        <div class="field">
                            Date
                        </div>

                    </div>

                </td>

            </tr>

        </table>

    </div>



    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="footer">

        <div class="footer-main">
            CPC Inventory Management System
            | Official RDD Report
        </div>

        <div class="footer-note">
            This document was generated electronically by the
            CPC Inventory Management System.
        </div>

    </div>


</div>

</body>

</html>