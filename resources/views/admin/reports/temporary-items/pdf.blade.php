@php
    /*
    |--------------------------------------------------------------------------
    | CPC Temporary Item Report - PDF
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
        'summary' =>
            'Temporary Item Issue Summary Report',

        'current_returned' =>
            'Current & Returned Temporary Items Report',

        'overdue' =>
            'Overdue Temporary Items Report',
    ];

    $reportTitle =
        $reportTitles[$reportType ?? 'summary']
        ?? 'Temporary Item Report';


    /*
    |--------------------------------------------------------------------------
    | Collection
    |--------------------------------------------------------------------------
    */

    $temporaryCollection =
        $temporaryIssues instanceof \Illuminate\Pagination\AbstractPaginator
            ? $temporaryIssues->getCollection()
            : collect($temporaryIssues ?? []);


    /*
    |--------------------------------------------------------------------------
    | Summary Calculations
    |--------------------------------------------------------------------------
    */

    $totalIssues =
        $temporaryCollection->count();


    $currentlyIssued =
        $temporaryCollection
            ->where('status', 'issued')
            ->count();


    $returnedItems =
        $temporaryCollection
            ->where('status', 'received')
            ->count();


    $cancelledItems =
        $temporaryCollection
            ->where('status', 'cancelled')
            ->count();


    $overdueThreshold =
        max(
            1,
            min(
                365,
                (int) ($overdueDays ?? 7)
            )
        );


    $overdueItems =
        $temporaryCollection
            ->filter(function ($issue) use ($overdueThreshold) {

                if ($issue->status !== 'issued') {
                    return false;
                }

                if (!$issue->issue_datetime) {
                    return false;
                }

                return
                    \Carbon\Carbon::parse(
                        $issue->issue_datetime
                    )->lessThanOrEqualTo(
                        now()->subDays($overdueThreshold)
                    );
            })
            ->count();


    $totalEvents =
        $temporaryCollection
            ->filter(function ($issue) {
                return !empty($issue->event);
            })
            ->pluck('event')
            ->unique()
            ->count();


    $uniqueEmployees =
        $temporaryCollection
            ->pluck('requesting_employee_id')
            ->filter()
            ->unique()
            ->count();


    /*
    |--------------------------------------------------------------------------
    | Filter Helpers
    |--------------------------------------------------------------------------
    */

    $statusLabels = [
        'issued' => 'Issued',
        'received' => 'Received',
        'cancelled' => 'Cancelled',
    ];


    $statusLabel =
        request('status')
            ? (
                $statusLabels[request('status')]
                ?? ucwords(
                    str_replace(
                        '_',
                        ' ',
                        request('status')
                    )
                )
            )
            : 'All';


    /*
    |--------------------------------------------------------------------------
    | Item Type
    |--------------------------------------------------------------------------
    */

    $itemTypeLabel =
        request('item_type')
            ?: 'All';


    /*
    |--------------------------------------------------------------------------
    | Item Code
    |--------------------------------------------------------------------------
    */

    $itemCodeLabel =
        request('item_code')
            ?: 'All';


    /*
    |--------------------------------------------------------------------------
    | Department
    |--------------------------------------------------------------------------
    */

    $departmentLabel = 'All';

    if (
        request('department_id') &&
        isset($departments)
    ) {

        $department =
            $departments->firstWhere(
                'id',
                request('department_id')
            );

        $departmentLabel =
            $department?->name
            ?? $department?->department_name
            ?? 'Selected Department';
    }


    /*
    |--------------------------------------------------------------------------
    | Function Unit
    |--------------------------------------------------------------------------
    */

    $functionUnitLabel = 'All';

    if (
        request('function_unit_id') &&
        isset($functionUnits)
    ) {

        $functionUnit =
            $functionUnits->firstWhere(
                'id',
                request('function_unit_id')
            );

        $functionUnitLabel =
            $functionUnit?->name
            ?? $functionUnit?->function_unit_name
            ?? 'Selected Function Unit';
    }


    /*
    |--------------------------------------------------------------------------
    | Requesting Employee
    |--------------------------------------------------------------------------
    */

    $employeeLabel = 'All';

    if (
        request('requesting_employee_id') &&
        isset($employees)
    ) {

        $employee =
            $employees->firstWhere(
                'id',
                request('requesting_employee_id')
            );

        $employeeLabel =
            $employee?->name
            ?? $employee?->full_name
            ?? 'Selected Employee';
    }


    /*
    |--------------------------------------------------------------------------
    | Event
    |--------------------------------------------------------------------------
    */

    $eventLabel =
        request('event')
            ?: 'All';


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    $searchLabel =
        request('search')
            ?: 'None';


    /*
    |--------------------------------------------------------------------------
    | Date Range
    |--------------------------------------------------------------------------
    */

    $fromDate =
        request('from_date')
            ?: 'Any';


    $toDate =
        request('to_date')
            ?: 'Any';


    /*
    |--------------------------------------------------------------------------
    | Generated Date
    |--------------------------------------------------------------------------
    */

    $generatedAt =
        now()->format('d M Y H:i');

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

            font-family:
                DejaVu Sans,
                Arial,
                sans-serif;

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

            padding:
                2px
                5px
                5px
                0;
        }


        .logo {

            width: 67px;

            height: auto;
        }


        .company-cell {

            width: 72%;

            text-align: center;

            padding:
                2px
                5px
                5px
                5px;
        }


        .company-name {

            margin: 0;

            font-size: 15px;

            font-weight: bold;

            letter-spacing: 0.4px;
        }


        .function-name {

            margin:
                2px
                0
                0;

            font-size: 9px;

            font-weight: bold;
        }


        .system-name {

            margin:
                2px
                0
                0;

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

            padding:
                4px
                6px;

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

            margin:
                5px
                0
                9px;
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

            padding:
                4px
                5px;

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

            margin:
                7px
                0
                4px;

            padding:
                4px
                6px;

            border-left:
                3px
                solid
                #0d6efd;

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

            border-spacing:
                4px
                0;

            margin:
                0
                -4px
                8px;
        }


        .summary-table td {

            width: 20%;

            border: 1px solid #d9dee3;

            padding:
                5px
                4px;

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

            padding:
                4px
                3px;

            border:
                1px
                solid
                #adb5bd;

            background: #eef2f6;

            color: #212529;

            font-size: 5.8px;

            font-weight: bold;

            text-align: center;

            vertical-align: middle;

            line-height: 1.2;
        }


        .records-table td {

            padding:
                3px
                3px;

            border:
                1px
                solid
                #dee2e6;

            font-size: 5.8px;

            vertical-align: top;

            word-wrap: break-word;

            overflow-wrap: break-word;
        }


        /*
        |--------------------------------------------------------------------------
        | Column Widths
        |--------------------------------------------------------------------------
        */

        .col-number {

            width: 3%;
        }


        .col-date {

            width: 8%;
        }


        .col-code {

            width: 7%;
        }


        .col-type {

            width: 7%;
        }


        .col-department {

            width: 9%;
        }


        .col-function {

            width: 9%;
        }


        .col-event {

            width: 12%;
        }


        .col-requesting {

            width: 12%;
        }


        .col-collecting {

            width: 11%;
        }


        .col-return {

            width: 8%;
        }


        .col-status {

            width: 7%;
        }


        .col-remarks {

            width: 7%;
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


        .muted {

            color: #6c757d;
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


        .status-issued {

            font-weight: bold;
        }


        .status-received {

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

            padding:
                14px
                8px !important;

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

            padding:
                0
                30px;

            vertical-align: bottom;
        }


        .signature-line {

            border-top:
                1px
                solid
                #495057;

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

            border-top:
                1px
                solid
                #dee2e6;

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


        {{-- LOGO --}}

        <td class="logo-cell">

            @if($logoBase64)

                <img
                    src="{{ $logoBase64 }}"
                    class="logo"
                    alt="CPC Logo"
                >

            @endif

        </td>


        {{-- COMPANY INFORMATION --}}

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


        {{-- OFFICIAL LABEL --}}

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
        {{ $generatedAt }}

    </div>

</div>



{{-- ============================================================
     REPORT CRITERIA
============================================================ --}}

<div class="section-title">

    Report Criteria

</div>


<table class="meta-table">

    {{-- ROW 1 --}}

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

            Item Type

        </td>


        <td class="meta-value">

            {{ $itemTypeLabel }}

        </td>


        <td class="meta-label">

            Item Code

        </td>


        <td class="meta-value">

            {{ $itemCodeLabel }}

        </td>

    </tr>


    {{-- ROW 2 --}}

    <tr>


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

            Requesting Employee

        </td>


        <td class="meta-value">

            {{ $employeeLabel }}

        </td>


        <td class="meta-label">

            Event

        </td>


        <td class="meta-value">

            {{ $eventLabel }}

        </td>

    </tr>


    {{-- ROW 3 --}}

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

            Overdue After

        </td>


        <td class="meta-value">

            {{ number_format($overdueThreshold) }}

            {{ $overdueThreshold == 1 ? 'Day' : 'Days' }}

        </td>


        <td class="meta-label">

            Records

        </td>


        <td class="meta-value">

            {{ number_format($totalIssues) }}

        </td>

    </tr>


    {{-- ROW 4 --}}

    <tr>


        <td class="meta-label">

            Search

        </td>


        <td class="meta-value">

            {{ $searchLabel }}

        </td>


        <td class="meta-label">

            Events

        </td>


        <td class="meta-value">

            {{ number_format($totalEvents) }}

        </td>


        <td class="meta-label">

            Employees

        </td>


        <td class="meta-value">

            {{ number_format($uniqueEmployees) }}

        </td>


        <td class="meta-label">

            Generated

        </td>


        <td class="meta-value">

            {{ $generatedAt }}

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


        {{-- TOTAL ISSUES --}}

        <td>

            <span class="summary-label">

                Total Issues

            </span>


            <span class="summary-value">

                {{ number_format($totalIssues) }}

            </span>


            <span class="summary-sub">

                Records

            </span>

        </td>


        {{-- CURRENTLY ISSUED --}}

        <td>

            <span class="summary-label">

                Currently Issued

            </span>


            <span class="summary-value">

                {{ number_format($currentlyIssued) }}

            </span>


            <span class="summary-sub">

                Outstanding Items

            </span>

        </td>


        {{-- RETURNED --}}

        <td>

            <span class="summary-label">

                Returned

            </span>


            <span class="summary-value">

                {{ number_format($returnedItems) }}

            </span>


            <span class="summary-sub">

                Received Items

            </span>

        </td>


        {{-- OVERDUE --}}

        <td>

            <span class="summary-label">

                Overdue

            </span>


            <span class="summary-value">

                {{ number_format($overdueItems) }}

            </span>


            <span class="summary-sub">

                Beyond {{ $overdueThreshold }} Days

            </span>

        </td>


        {{-- CANCELLED --}}

        <td>

            <span class="summary-label">

                Cancelled

            </span>


            <span class="summary-value">

                {{ number_format($cancelledItems) }}

            </span>


            <span class="summary-sub">

                Cancelled Records

            </span>

        </td>

    </tr>

</table>



{{-- ============================================================
     TEMPORARY ITEM RECORDS
============================================================ --}}

<div class="section-title">

    Temporary Item Records

</div>


<table class="records-table">

    <thead>

        <tr>


            <th class="col-number">

                #

            </th>


            <th class="col-date">

                Issue<br>Date & Time

            </th>


            <th class="col-code">

                Item<br>Code

            </th>


            <th class="col-type">

                Item<br>Type

            </th>


            <th class="col-department">

                Department

            </th>


            <th class="col-function">

                Function<br>Unit

            </th>


            <th class="col-event">

                Event

            </th>


            <th class="col-requesting">

                Requesting<br>Employee

            </th>


            <th class="col-collecting">

                Collecting<br>Employee

            </th>


            <th class="col-return">

                Return<br>Date & Time

            </th>


            <th class="col-status">

                Status

            </th>


            <th class="col-remarks">

                Remarks

            </th>

        </tr>

    </thead>


    <tbody>

        @forelse($temporaryCollection as $index => $issue)

            @php

                /*
                |--------------------------------------------------------------------------
                | Department
                |--------------------------------------------------------------------------
                */

                $departmentName =
                    optional(
                        $issue->department
                    )->name
                    ?? optional(
                        $issue->department
                    )->department_name
                    ?? '—';


                /*
                |--------------------------------------------------------------------------
                | Function Unit
                |--------------------------------------------------------------------------
                */

                $functionUnitName =
                    optional(
                        $issue->functionUnit
                    )->name
                    ?? optional(
                        $issue->functionUnit
                    )->function_unit_name
                    ?? '—';


                /*
                |--------------------------------------------------------------------------
                | Requesting Employee
                |--------------------------------------------------------------------------
                */

                $requestingEmployee =
                    $issue->requestingEmployee;


                $requestingEmployeeName =
                    optional(
                        $requestingEmployee
                    )->name
                    ?? optional(
                        $requestingEmployee
                    )->full_name
                    ?? '—';


                /*
                |--------------------------------------------------------------------------
                | Collecting Employee
                |--------------------------------------------------------------------------
                */

                $collectingEmployee =
                    $issue->collectingEmployee;


                $collectingEmployeeName =
                    optional(
                        $collectingEmployee
                    )->name
                    ?? optional(
                        $collectingEmployee
                    )->full_name
                    ?? '—';


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                $statusClass =
                    match ($issue->status) {

                        'issued' =>
                            'status-issued',

                        'received' =>
                            'status-received',

                        'cancelled' =>
                            'status-cancelled',

                        default =>
                            'status',
                    };


                $statusText =
                    $statusLabels[
                        $issue->status
                    ]
                    ?? ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $issue->status
                                ?? 'Unknown'
                        )
                    );

            @endphp


            <tr>


                {{-- # --}}

                <td class="center">

                    {{ $index + 1 }}

                </td>



                {{-- ISSUE DATE --}}

                <td class="center nowrap">

                    @if($issue->issue_datetime)

                        {{ \Carbon\Carbon::parse(
                            $issue->issue_datetime
                        )->format('d M Y') }}

                        <br>

                        <span class="muted">

                            {{ \Carbon\Carbon::parse(
                                $issue->issue_datetime
                            )->format('H:i') }}

                        </span>

                    @else

                        —

                    @endif

                </td>



                {{-- ITEM CODE --}}

                <td class="center">

                    {{ $issue->item_code ?? '—' }}

                </td>



                {{-- ITEM TYPE --}}

                <td>

                    {{ $issue->item_type ?? '—' }}

                </td>



                {{-- DEPARTMENT --}}

                <td>

                    {{ $departmentName }}

                </td>



                {{-- FUNCTION UNIT --}}

                <td>

                    {{ $functionUnitName }}

                </td>



                {{-- EVENT --}}

                <td>

                    {{ $issue->event ?? '—' }}

                </td>



                {{-- REQUESTING EMPLOYEE --}}

                <td>

                    {{ $requestingEmployeeName }}

                    @if(
                        optional(
                            $requestingEmployee
                        )->epf_no
                    )

                        <br>

                        <span class="muted">

                            EPF:
                            {{ $requestingEmployee->epf_no }}

                        </span>

                    @endif

                </td>



                {{-- COLLECTING EMPLOYEE --}}

                <td>

                    {{ $collectingEmployeeName }}

                    @if(
                        optional(
                            $collectingEmployee
                        )->epf_no
                    )

                        <br>

                        <span class="muted">

                            EPF:
                            {{ $collectingEmployee->epf_no }}

                        </span>

                    @endif

                </td>



                {{-- RETURN DATE --}}

                <td class="center nowrap">

                    @if($issue->received_datetime)

                        {{ \Carbon\Carbon::parse(
                            $issue->received_datetime
                        )->format('d M Y') }}

                        <br>

                        <span class="muted">

                            {{ \Carbon\Carbon::parse(
                                $issue->received_datetime
                            )->format('H:i') }}

                        </span>

                    @else

                        —

                    @endif

                </td>



                {{-- STATUS --}}

                <td class="center">

                    <span class="{{ $statusClass }}">

                        {{ $statusText }}

                    </span>

                </td>



                {{-- REMARKS --}}

                <td>

                    {{ $issue->remarks ?? '—' }}

                </td>


            </tr>


        @empty

            <tr>

                <td
                    colspan="12"
                    class="empty-state"
                >

                    No temporary item records were found
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

    Official Temporary Item Management Report

    &nbsp;|&nbsp;

    Generated {{ $generatedAt }}

</div>


</body>

</html>