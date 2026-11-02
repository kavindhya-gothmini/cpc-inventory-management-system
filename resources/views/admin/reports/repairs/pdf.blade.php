@php
    /*
    |--------------------------------------------------------------------------
    | CPC Repair Report - PDF
    |--------------------------------------------------------------------------
    */

    $logoPath = public_path('images/cpc-logo.png');

    $logoBase64 = null;

    if (file_exists($logoPath)) {
        $logoBase64 =
            'data:image/png;base64,' .
            base64_encode(
                file_get_contents($logoPath)
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Report Titles
    |--------------------------------------------------------------------------
    */

    $reportTitles = [
        'summary' => 'Repair Summary Report',
        'pending' => 'Pending Repairs Report',
        'completed' => 'Completed Repairs Report',
        'pending_completed' => 'Pending & Completed Repairs Report',
        'cost' => 'Repair Cost Analysis Report',
    ];

    $reportTitle =
        $reportTitles[$reportType ?? 'summary']
        ?? 'Repair Report';

    /*
    |--------------------------------------------------------------------------
    | Summary Calculations
    |--------------------------------------------------------------------------
    */

    $totalRepairs = $repairs->count();

    $pending =
        $repairs
            ->whereIn(
                'status',
                [
                    'pending',
                    'sent_for_repair',
                    'under_repair',
                ]
            )
            ->count();

    $completed =
        $repairs
            ->whereIn(
                'status',
                [
                    'completed',
                    'returned',
                ]
            )
            ->count();

    $cancelled =
        $repairs
            ->where(
                'status',
                'cancelled'
            )
            ->count();

    $totalCost =
        $repairs->sum(
            fn ($repair) =>
                (float) (
                    $repair->repair_cost
                    ?? 0
                )
        );

    $averageCost =
        $totalRepairs > 0
            ? $totalCost / $totalRepairs
            : 0;

    $completedForTurnaround =
        $repairs->filter(
            fn ($repair) =>
                $repair->date_sent &&
                $repair->date_returned
        );

    $averageTurnaroundDays =
        $completedForTurnaround->isEmpty()
            ? 0
            : round(
                $completedForTurnaround->avg(
                    fn ($repair) =>
                        $repair->date_sent
                            ->diffInDays(
                                $repair->date_returned
                            )
                ),
                1
            );

    /*
    |--------------------------------------------------------------------------
    | Filter Helpers
    |--------------------------------------------------------------------------
    */

    $statusLabel = request('status')
        ? ucwords(
            str_replace(
                '_',
                ' ',
                request('status')
            )
        )
        : 'All';

    $employeeLabel = 'All';

    if (
        request('employee_id') &&
        isset($employees)
    ) {
        $employee = $employees->firstWhere(
            'id',
            request('employee_id')
        );

        $employeeLabel =
            $employee?->name
            ?? 'Selected Employee';
    }

    $branchLabel = 'All';

    if (
        request('branch_id') &&
        isset($branches)
    ) {
        $branch = $branches->firstWhere(
            'id',
            request('branch_id')
        );

        $branchLabel =
            $branch?->name
            ?? 'Selected Branch';
    }

    $departmentLabel = 'All';

    if (
        request('department_id') &&
        isset($departments)
    ) {
        $department = $departments->firstWhere(
            'id',
            request('department_id')
        );

        $departmentLabel =
            $department?->name
            ?? 'Selected Department';
    }

    $itemTypeLabel = 'All';

    if (
        request('item_type_id') &&
        isset($itemTypes)
    ) {
        $itemType = $itemTypes->firstWhere(
            'id',
            request('item_type_id')
        );

        $itemTypeLabel =
            $itemType?->name
            ?? 'Selected Item Type';
    }

    $functionUnitLabel =
        request('function_unit_id')
            ? 'Selected Function Unit'
            : 'All';

    $technicianLabel =
        request('technician')
            ?: 'All';

    $fromDate =
        request('from_date')
            ?: 'Any';

    $toDate =
        request('to_date')
            ?: 'Any';

@endphp


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $reportTitle }}
    </title>

    <style>

        /*
        |--------------------------------------------------------------------------
        | Page
        |--------------------------------------------------------------------------
        */

        @page {
            size: A4 landscape;
            margin: 10mm 9mm 12mm 9mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 7px;
            line-height: 1.35;
            color: #212529;
            background: #ffffff;
        }

        /*
        |--------------------------------------------------------------------------
        | Corporate Header
        |--------------------------------------------------------------------------
        */

        .corporate-header {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #0d6efd;
            margin-bottom: 9px;
        }

        .corporate-header td {
            vertical-align: middle;
        }

        .logo-cell {
            width: 13%;
            padding: 2px 5px 5px 0;
        }

        .logo {
            width: 67px;
            height: auto;
        }

        .company-cell {
            width: 72%;
            text-align: center;
            padding: 2px 5px 5px 5px;
        }

        .company-name {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.4px;
        }

        .function-name {
            margin: 2px 0 0;
            font-size: 9px;
            font-weight: bold;
        }

        .system-name {
            margin: 2px 0 0;
            font-size: 7.5px;
        }

        .official-cell {
            width: 15%;
            text-align: right;
            vertical-align: top !important;
            padding-top: 4px;
        }

        .official-badge {
            display: inline-block;
            border: 1px solid #adb5bd;
            padding: 4px 6px;
            font-size: 6px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        /*
        |--------------------------------------------------------------------------
        | Report Heading
        |--------------------------------------------------------------------------
        */

        .report-heading {
            text-align: center;
            margin: 5px 0 9px;
        }

        .report-heading h1 {
            margin: 0;
            font-size: 13px;
            font-weight: bold;
        }

        .report-heading .subtitle {
            margin-top: 3px;
            font-size: 7px;
            color: #6c757d;
        }

        /*
        |--------------------------------------------------------------------------
        | Report Meta
        |--------------------------------------------------------------------------
        */

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
        }

        .meta-table td {
            border: 1px solid #d9dee3;
            padding: 4px 5px;
            vertical-align: middle;
        }

        .meta-label {
            width: 9%;
            font-size: 6.5px;
            font-weight: bold;
            color: #495057;
            white-space: nowrap;
        }

        .meta-value {
            width: 16%;
            font-size: 7px;
        }

        /*
        |--------------------------------------------------------------------------
        | Section Title
        |--------------------------------------------------------------------------
        */

        .section-title {
            margin: 7px 0 4px;
            padding: 4px 6px;
            border-left: 3px solid #0d6efd;
            background: #f4f7fb;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px 0;
            margin: 0 -4px 8px;
        }

        .summary-table td {
            width: 16.66%;
            border: 1px solid #d9dee3;
            padding: 5px 4px;
            text-align: center;
            vertical-align: middle;
        }

        .summary-label {
            display: block;
            font-size: 6px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
        }

        .summary-value {
            display: block;
            margin-top: 2px;
            font-size: 10px;
            font-weight: bold;
        }

        .summary-sub {
            display: block;
            margin-top: 1px;
            font-size: 5.5px;
            color: #6c757d;
        }

        /*
        |--------------------------------------------------------------------------
        | Records Table
        |--------------------------------------------------------------------------
        */

        .records-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .records-table thead {
            display: table-header-group;
        }

        .records-table tr {
            page-break-inside: avoid;
        }

        .records-table th {
            padding: 4px 3px;
            border: 1px solid #adb5bd;
            background: #eef2f6;
            color: #212529;
            font-size: 6px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            line-height: 1.2;
        }

        .records-table td {
            padding: 3px 3px;
            border: 1px solid #dee2e6;
            font-size: 6px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /*
        |--------------------------------------------------------------------------
        | Column Widths
        |--------------------------------------------------------------------------
        */

        .col-reference {
            width: 7%;
        }

        .col-asset {
            width: 7%;
        }

        .col-type {
            width: 8%;
        }

        .col-employee {
            width: 9%;
        }

        .col-branch {
            width: 7%;
        }

        .col-department {
            width: 8%;
        }

        .col-function {
            width: 8%;
        }

        .col-problem {
            width: 14%;
        }

        .col-technician {
            width: 8%;
        }

        .col-carrier {
            width: 6%;
        }

        .col-date {
            width: 7%;
        }

        .col-status {
            width: 7%;
        }

        .col-cost {
            width: 6%;
        }

        /*
        |--------------------------------------------------------------------------
        | Table Alignment
        |--------------------------------------------------------------------------
        */

        .center {
            text-align: center !important;
        }

        .right {
            text-align: right !important;
        }

        .nowrap {
            white-space: nowrap;
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        .status {
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }

        .status-pending,
        .status-sent_for_repair,
        .status-under_repair {
            font-weight: bold;
        }

        .status-completed,
        .status-returned {
            font-weight: bold;
        }

        .status-cancelled {
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */

        .empty-state {
            text-align: center;
            padding: 14px 8px !important;
            font-size: 7px !important;
            font-style: italic;
            color: #6c757d;
        }

        /*
        |--------------------------------------------------------------------------
        | Signature Section
        |--------------------------------------------------------------------------
        */

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 22px;
            page-break-inside: avoid;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            padding: 0 30px;
            vertical-align: bottom;
        }

        .signature-line {
            border-top: 1px solid #495057;
            padding-top: 4px;
            margin-top: 22px;
            font-size: 6.5px;
            font-weight: bold;
        }

        .signature-role {
            margin-top: 2px;
            font-size: 5.5px;
            color: #6c757d;
        }

        /*
        |--------------------------------------------------------------------------
        | Footer
        |--------------------------------------------------------------------------
        */

        .footer {
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 5.5px;
            color: #6c757d;
        }

        /*
        |--------------------------------------------------------------------------
        | Page Break Helpers
        |--------------------------------------------------------------------------
        */

        .avoid-break {
            page-break-inside: avoid;
        }

    </style>

</head>


<body>


{{-- ============================================================
     CORPORATE HEADER
============================================================ --}}

<table class="corporate-header">

    <tr>

        <td class="logo-cell">

            @if($logoBase64)

                <img
                    src="{{ $logoBase64 }}"
                    class="logo"
                    alt="CPC Logo"
                >

            @endif

        </td>


        <td class="company-cell">

            <div class="company-name">
                CEYLON PETROLEUM CORPORATION
            </div>

            <div class="function-name">
                INFORMATION TECHNOLOGY FUNCTION
            </div>

            <div class="system-name">
                CPC INVENTORY MANAGEMENT SYSTEM
            </div>

        </td>


        <td class="official-cell">

            <span class="official-badge">
                OFFICIAL DOCUMENT
            </span>

        </td>

    </tr>

</table>


{{-- ============================================================
     REPORT TITLE
============================================================ --}}

<div class="report-heading">

    <h1>
        {{ $reportTitle }}
    </h1>

    <div class="subtitle">

        Generated on
        {{ now()->format('d M Y H:i') }}

    </div>

</div>


{{-- ============================================================
     REPORT CRITERIA
============================================================ --}}

<div class="section-title">
    Report Criteria
</div>


<table class="meta-table">

    <tr>

        <td class="meta-label">
            Report
        </td>

        <td class="meta-value">
            {{ $reportTitle }}
        </td>

        <td class="meta-label">
            Status
        </td>

        <td class="meta-value">
            {{ $statusLabel }}
        </td>

        <td class="meta-label">
            Employee
        </td>

        <td class="meta-value">
            {{ $employeeLabel }}
        </td>

        <td class="meta-label">
            Technician
        </td>

        <td class="meta-value">
            {{ $technicianLabel }}
        </td>

    </tr>


    <tr>

        <td class="meta-label">
            Branch
        </td>

        <td class="meta-value">
            {{ $branchLabel }}
        </td>

        <td class="meta-label">
            Department
        </td>

        <td class="meta-value">
            {{ $departmentLabel }}
        </td>

        <td class="meta-label">
            Function Unit
        </td>

        <td class="meta-value">
            {{ $functionUnitLabel }}
        </td>

        <td class="meta-label">
            Item Type
        </td>

        <td class="meta-value">
            {{ $itemTypeLabel }}
        </td>

    </tr>


    <tr>

        <td class="meta-label">
            From Date
        </td>

        <td class="meta-value">
            {{ $fromDate }}
        </td>

        <td class="meta-label">
            To Date
        </td>

        <td class="meta-value">
            {{ $toDate }}
        </td>

        <td class="meta-label">
            Records
        </td>

        <td class="meta-value">
            {{ number_format($totalRepairs) }}
        </td>

        <td class="meta-label">
            Generated
        </td>

        <td class="meta-value">
            {{ now()->format('d M Y H:i') }}
        </td>

    </tr>

</table>


{{-- ============================================================
     SUMMARY
============================================================ --}}

<div class="section-title">
    Report Summary
</div>


<table class="summary-table">

    <tr>

        <td>

            <span class="summary-label">
                Total Repairs
            </span>

            <span class="summary-value">
                {{ number_format($totalRepairs) }}
            </span>

            <span class="summary-sub">
                Records
            </span>

        </td>


        <td>

            <span class="summary-label">
                Pending
            </span>

            <span class="summary-value">
                {{ number_format($pending) }}
            </span>

            <span class="summary-sub">
                Active Repairs
            </span>

        </td>


        <td>

            <span class="summary-label">
                Completed
            </span>

            <span class="summary-value">
                {{ number_format($completed) }}
            </span>

            <span class="summary-sub">
                Completed / Returned
            </span>

        </td>


        <td>

            <span class="summary-label">
                Cancelled
            </span>

            <span class="summary-value">
                {{ number_format($cancelled) }}
            </span>

            <span class="summary-sub">
                Cancelled Records
            </span>

        </td>


        <td>

            <span class="summary-label">
                Total Repair Cost
            </span>

            <span class="summary-value">
                {{ number_format($totalCost, 2) }}
            </span>

            <span class="summary-sub">
                Total
            </span>

        </td>


        <td>

            <span class="summary-label">
                Avg. Turnaround
            </span>

            <span class="summary-value">
                {{ number_format($averageTurnaroundDays, 1) }}
            </span>

            <span class="summary-sub">
                Days
            </span>

        </td>

    </tr>

</table>


{{-- ============================================================
     REPAIR RECORDS
============================================================ --}}

<div class="section-title">
    Repair Records
</div>


<table class="records-table">

    <thead>

        <tr>

            <th class="col-reference">
                Repair<br>Reference
            </th>

            <th class="col-asset">
                Asset<br>Number
            </th>

            <th class="col-type">
                Item<br>Type
            </th>

            <th class="col-employee">
                Employee
            </th>

            <th class="col-branch">
                Branch
            </th>

            <th class="col-department">
                Department
            </th>

            <th class="col-function">
                Function<br>Unit
            </th>

            <th class="col-problem">
                Problem Description
            </th>

            <th class="col-technician">
                Technician
            </th>

            <th class="col-carrier">
                Carrier
            </th>

            <th class="col-date">
                Date<br>Sent
            </th>

            <th class="col-date">
                Date<br>Returned
            </th>

            <th class="col-status">
                Status
            </th>

            <th class="col-cost">
                Cost
            </th>

        </tr>

    </thead>


    <tbody>

        @forelse($repairs as $repair)

            @php

                $turnaroundDays = null;

                if (
                    $repair->date_sent &&
                    $repair->date_returned
                ) {
                    $turnaroundDays =
                        $repair->date_sent->diffInDays(
                            $repair->date_returned
                        );
                }

                $statusClass =
                    'status-' .
                    ($repair->status ?? 'unknown');

                $statusText =
                    $repair->status
                        ? ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $repair->status
                            )
                        )
                        : '—';

            @endphp


            <tr>

                <td class="center">
                    {{ $repair->repair_reference ?: '—' }}
                </td>


                <td class="center">
                    {{ optional($repair->item)->asset_number ?? '—' }}
                </td>


                <td>
                    {{ optional(
                        optional($repair->item)->itemType
                    )->name ?? '—' }}
                </td>


                <td>
                    {{ optional($repair->employee)->name ?? '—' }}
                </td>


                <td>
                    {{ optional(
                        optional($repair->item)->branch
                    )->name ?? '—' }}
                </td>


                <td>
                    {{ optional(
                        optional($repair->item)->department
                    )->name ?? '—' }}
                </td>


                <td>
                    {{ optional(
                        optional($repair->item)->functionUnit
                    )->name ?? '—' }}
                </td>


                <td>
                    {{ $repair->problem_description ?: '—' }}
                </td>


                <td>
                    {{ $repair->technician ?: '—' }}
                </td>


                <td>
                    {{ $repair->carrier ?: '—' }}
                </td>


                <td class="center nowrap">

                    {{ $repair->date_sent
                        ? $repair->date_sent->format('d M Y')
                        : '—' }}

                </td>


                <td class="center nowrap">

                    {{ $repair->date_returned
                        ? $repair->date_returned->format('d M Y')
                        : '—' }}

                </td>


                <td class="center">

                    <span class="{{ $statusClass }}">
                        {{ $statusText }}
                    </span>

                </td>


                <td class="right nowrap">

                    {{ number_format(
                        (float) (
                            $repair->repair_cost
                            ?? 0
                        ),
                        2
                    ) }}

                </td>

            </tr>


        @empty

            <tr>

                <td
                    colspan="14"
                    class="empty-state"
                >
                    No repair records were found
                    for the selected report criteria.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>


{{-- ============================================================
     SIGNATURES
============================================================ --}}

<table class="signature-table">

    <tr>

        <td>

            <div class="signature-line">
                Prepared By
            </div>

            <div class="signature-role">
                Name / Signature / Date
            </div>

        </td>


        <td>

            <div class="signature-line">
                Checked By
            </div>

            <div class="signature-role">
                Name / Signature / Date
            </div>

        </td>


        <td>

            <div class="signature-line">
                Authorized By
            </div>

            <div class="signature-role">
                Name / Signature / Date
            </div>

        </td>

    </tr>

</table>


{{-- ============================================================
     FOOTER
============================================================ --}}

<div class="footer">

    CPC Inventory Management System
    &nbsp;|&nbsp;
    Official Repair Management Report
    &nbsp;|&nbsp;
    Generated {{ now()->format('d M Y H:i') }}

</div>


</body>

</html>