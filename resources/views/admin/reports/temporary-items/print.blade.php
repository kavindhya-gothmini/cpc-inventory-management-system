{{-- resources/views/admin/reports/temporary-items/print.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $reportTypeLabel ?? 'Temporary Item Report' }} |
        CPC Inventory Management System
    </title>


    <style>

        /* =========================================================
           PAGE CONFIGURATION
        ========================================================= */

        @page {
            size: A4 landscape;
            margin: 11mm 10mm 13mm 10mm;
        }


        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;
        }


        body {

            background: #eef2f6;

            color: #212529;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            font-size: 10px;

            line-height: 1.35;
        }


        /* =========================================================
           SCREEN / REPORT WRAPPER
        ========================================================= */

        .page-shell {

            width: 100%;

            max-width: 1400px;

            margin: 0 auto;

            padding: 24px;
        }


        .report-paper {

            width: 100%;

            background: #ffffff;

            padding: 20px 22px 18px;

            box-shadow:
                0 5px 25px rgba(0, 0, 0, 0.08);
        }


        /* =========================================================
           PRINT TOOLBAR
        ========================================================= */

        .print-toolbar {

            position: fixed;

            top: 18px;

            right: 18px;

            z-index: 9999;
        }


        .print-button {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            border: 0;

            border-radius: 7px;

            background: #0d6efd;

            color: #ffffff;

            padding: 10px 16px;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            font-size: 12px;

            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 4px 12px rgba(13, 110, 253, 0.25);

            transition:
                background-color 0.2s ease,
                transform 0.2s ease;
        }


        .print-button:hover {

            background: #0b5ed7;

            transform: translateY(-1px);
        }


        .print-button:active {

            transform: translateY(0);
        }


        /* =========================================================
           CORPORATE HEADER
        ========================================================= */

        .corporate-header {

            width: 100%;

            border-bottom: 3px solid #0d6efd;

            padding-bottom: 12px;

            margin-bottom: 14px;
        }


        .header-table {

            width: 100%;

            border-collapse: collapse;
        }


        .header-logo-cell {

            width: 90px;

            vertical-align: middle;
        }


        .header-logo {

            display: block;

            max-width: 76px;

            max-height: 65px;

            object-fit: contain;
        }


        .header-main {

            vertical-align: middle;

            padding-left: 8px;
        }


        .organization-name {

            margin: 0;

            color: #0d3b66;

            font-size: 19px;

            font-weight: 800;

            line-height: 1.15;

            letter-spacing: 0.15px;
        }


        .organization-subtitle {

            margin-top: 3px;

            color: #6c757d;

            font-size: 10px;

            font-weight: 600;
        }


        .report-heading {

            margin-top: 7px;

            color: #212529;

            font-size: 16px;

            font-weight: 800;

            line-height: 1.2;
        }


        .report-description {

            margin-top: 2px;

            color: #6c757d;

            font-size: 9px;
        }


        .header-meta-cell {

            width: 190px;

            vertical-align: middle;

            text-align: right;
        }


        .meta-label {

            color: #6c757d;

            font-size: 8px;

            font-weight: 600;

            text-transform: uppercase;

            letter-spacing: 0.35px;
        }


        .meta-value {

            color: #212529;

            font-size: 9px;

            font-weight: 700;
        }


        .official-label {

            display: inline-block;

            margin-top: 5px;

            padding: 3px 7px;

            border: 1px solid #0d6efd;

            border-radius: 4px;

            color: #0d6efd;

            font-size: 7px;

            font-weight: 800;

            letter-spacing: 0.6px;
        }


        /* =========================================================
           SECTION HEADINGS
        ========================================================= */

        .section {

            margin-top: 12px;
        }


        .section-header {

            width: 100%;

            margin-bottom: 6px;
        }


        .section-title {

            margin: 0;

            color: #0d3b66;

            font-size: 11px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 0.45px;
        }


        .section-subtitle {

            margin-top: 2px;

            color: #6c757d;

            font-size: 8px;
        }


        /* =========================================================
           FILTER / CRITERIA CARD
        ========================================================= */

        .criteria-card {

            width: 100%;

            border: 1px solid #dfe4ea;

            border-left: 4px solid #0d6efd;

            background: #f8fafc;

            padding: 8px 10px;
        }


        .criteria-table {

            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }


        .criteria-table td {

            padding: 4px 6px;

            vertical-align: top;
        }


        .criteria-label {

            width: 9%;

            color: #6c757d;

            font-size: 8px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 0.25px;
        }


        .criteria-value {

            width: 24.33%;

            color: #212529;

            font-size: 8.5px;

            font-weight: 700;

            word-break: break-word;
        }


        /* =========================================================
           SUMMARY CARDS
        ========================================================= */

        .summary-grid {

            width: 100%;

            border-collapse: separate;

            border-spacing: 5px 0;

            margin-left: -5px;

            margin-right: -5px;
        }


        .summary-grid td {

            width: 20%;

            padding: 8px 9px;

            border: 1px solid #dfe4ea;

            background: #ffffff;

            vertical-align: middle;
        }


        .summary-label {

            display: block;

            margin-bottom: 3px;

            color: #6c757d;

            font-size: 7.5px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 0.4px;
        }


        .summary-value {

            display: block;

            color: #0d3b66;

            font-size: 13px;

            font-weight: 800;

            line-height: 1.15;
        }


        .summary-note {

            display: block;

            margin-top: 2px;

            color: #868e96;

            font-size: 7px;
        }


        /* =========================================================
           REPORT TABLE
        ========================================================= */

        .table-wrapper {

            width: 100%;

            overflow: visible;
        }


        .report-table {

            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }


        .report-table thead {

            display: table-header-group;
        }


        .report-table tfoot {

            display: table-footer-group;
        }


        .report-table tr {

            page-break-inside: avoid;
        }


        .report-table th {

            padding: 6px 4px;

            background: #0d3b66;

            color: #ffffff;

            border: 1px solid #0d3b66;

            font-size: 7.6px;

            font-weight: 800;

            text-align: left;

            vertical-align: middle;

            line-height: 1.2;
        }


        .report-table td {

            padding: 5px 4px;

            border: 1px solid #dfe4ea;

            color: #212529;

            font-size: 7.6px;

            font-weight: 500;

            line-height: 1.3;

            vertical-align: middle;

            word-break: break-word;

            overflow-wrap: anywhere;
        }


        .report-table tbody tr:nth-child(even) {

            background: #f8fafc;
        }


        .report-table tbody tr:hover {

            background: #f1f5f9;
        }


        /* =========================================================
           COLUMN ALIGNMENT
        ========================================================= */

        .col-center {

            text-align: center !important;
        }


        .col-right {

            text-align: right !important;
        }


        .col-bold {

            font-weight: 800 !important;
        }


        .col-muted {

            color: #6c757d !important;
        }


        .date-cell {

            white-space: nowrap;
        }


        .description-cell {

            line-height: 1.35;
        }


        /* =========================================================
           STATUS BADGES
        ========================================================= */

        .status-badge {

            display: inline-block;

            min-width: 48px;

            padding: 3px 5px;

            border: 1px solid transparent;

            border-radius: 4px;

            font-size: 6.8px;

            font-weight: 800;

            text-align: center;

            line-height: 1.15;

            white-space: nowrap;
        }


        .status-issued {

            background: #cfe2ff;

            color: #084298;

            border-color: #9ec5fe;
        }


        .status-received {

            background: #d1e7dd;

            color: #0f5132;

            border-color: #a3cfbb;
        }


        .status-cancelled {

            background: #f8d7da;

            color: #842029;

            border-color: #f1aeb5;
        }


        .status-default {

            background: #e9ecef;

            color: #495057;

            border-color: #ced4da;
        }


        /* =========================================================
           EMPTY STATE
        ========================================================= */

        .empty-state {

            padding: 22px 12px !important;

            text-align: center;

            background: #ffffff !important;
        }


        .empty-title {

            display: block;

            margin-bottom: 3px;

            color: #343a40;

            font-size: 9px;

            font-weight: 800;
        }


        .empty-message {

            color: #6c757d;

            font-size: 8px;
        }


        /* =========================================================
           SIGNATURE SECTION
        ========================================================= */

        .signature-section {

            margin-top: 22px;

            page-break-inside: avoid;
        }


        .signature-table {

            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }


        .signature-table td {

            width: 33.33%;

            padding: 0 22px;

            text-align: center;

            vertical-align: bottom;
        }


        .signature-line {

            width: 72%;

            margin: 25px auto 5px;

            border-top: 1px solid #343a40;
        }


        .signature-label {

            color: #495057;

            font-size: 8px;

            font-weight: 700;
        }


        .signature-role {

            margin-top: 2px;

            color: #868e96;

            font-size: 7px;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .report-footer {

            margin-top: 15px;

            padding-top: 7px;

            border-top: 1px solid #dfe4ea;

            text-align: center;
        }


        .footer-primary {

            color: #495057;

            font-size: 7.5px;

            font-weight: 700;
        }


        .footer-secondary {

            margin-top: 2px;

            color: #868e96;

            font-size: 7px;
        }


        /* =========================================================
           RESPONSIVE SCREEN VIEW
        ========================================================= */

        @media screen and (max-width: 900px) {

            .page-shell {

                padding: 10px;
            }


            .report-paper {

                padding: 15px;
            }


            .print-toolbar {

                position: sticky;

                top: 10px;

                right: auto;

                margin-bottom: 10px;

                text-align: right;
            }


            .header-meta-cell {

                width: 150px;
            }


            .organization-name {

                font-size: 16px;
            }


            .report-heading {

                font-size: 14px;
            }


            .criteria-table,
            .criteria-table tbody,
            .criteria-table tr,
            .criteria-table td {

                display: block;

                width: 100%;
            }


            .criteria-table tr {

                margin-bottom: 4px;
            }


            .criteria-label,
            .criteria-value {

                display: inline-block !important;

                width: auto !important;
            }


            .criteria-label {

                margin-right: 4px;
            }


            .summary-grid {

                display: block;

                width: 100%;

                margin: 0;
            }


            .summary-grid tbody,
            .summary-grid tr,
            .summary-grid td {

                display: block;

                width: 100%;
            }


            .summary-grid td {

                margin-bottom: 5px;
            }


            .table-wrapper {

                overflow-x: auto;

                -webkit-overflow-scrolling: touch;
            }


            .report-table {

                min-width: 1250px;
            }
        }


        /* =========================================================
           PRINT MODE
        ========================================================= */

        @media print {

            html,
            body {

                background: #ffffff !important;
            }


            .page-shell {

                max-width: none;

                padding: 0;

                margin: 0;
            }


            .report-paper {

                padding: 0;

                margin: 0;

                box-shadow: none;
            }


            .print-toolbar {

                display: none !important;
            }


            .corporate-header {

                margin-top: 0;
            }


            .report-table tbody tr:hover {

                background: inherit;
            }


            .report-table th {

                -webkit-print-color-adjust: exact;

                print-color-adjust: exact;
            }


            .status-badge,
            .summary-grid td,
            .criteria-card {

                -webkit-print-color-adjust: exact;

                print-color-adjust: exact;
            }


            .signature-section {

                page-break-inside: avoid;
            }


            .report-footer {

                page-break-inside: avoid;
            }
        }

    </style>

</head>


<body>


@php

    /*
    |--------------------------------------------------------------------------
    | CPC LOGO
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
    | REPORT TYPE
    |--------------------------------------------------------------------------
    */

    $reportTypeLabels = [

        'summary' =>
            'Temporary Item Issue Summary',

        'current_returned' =>
            'Current & Returned Temporary Items',

        'overdue' =>
            'Overdue Temporary Items',
    ];


    $reportTypeLabel =
        $reportTypeLabels[$reportType ?? 'summary']
        ?? 'Temporary Item Report';


    /*
    |--------------------------------------------------------------------------
    | COLLECTION
    |--------------------------------------------------------------------------
    */

    $temporaryCollection =
        $temporaryIssues instanceof \Illuminate\Pagination\AbstractPaginator
            ? $temporaryIssues->getCollection()
            : collect($temporaryIssues ?? []);


    /*
    |--------------------------------------------------------------------------
    | SUMMARY
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
    | STATUS
    |--------------------------------------------------------------------------
    */

    $statusLabels = [

        'issued' =>
            'Issued',

        'received' =>
            'Received',

        'cancelled' =>
            'Cancelled',
    ];


    $selectedStatus =
        $statusLabels[$status ?? '']
        ?? 'All Statuses';


    /*
    |--------------------------------------------------------------------------
    | ITEM TYPE
    |--------------------------------------------------------------------------
    */

    $selectedItemType =
        !empty($itemType)
            ? $itemType
            : 'All Item Types';


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT
    |--------------------------------------------------------------------------
    */

    $selectedDepartment =
        'All Departments';


    if (
        !empty($departmentId)
        && isset($departments)
    ) {

        $department =
            $departments->firstWhere(
                'id',
                $departmentId
            );


        if ($department) {

            $selectedDepartment =
                $department->name
                ?? $department->department_name
                ?? ('Department #' . $department->id);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTION UNIT
    |--------------------------------------------------------------------------
    */

    $selectedFunctionUnit =
        'All Function Units';


    if (
        !empty($functionUnitId)
        && isset($functionUnits)
    ) {

        $functionUnit =
            $functionUnits->firstWhere(
                'id',
                $functionUnitId
            );


        if ($functionUnit) {

            $selectedFunctionUnit =
                $functionUnit->name
                ?? $functionUnit->function_unit_name
                ?? ('Function Unit #' . $functionUnit->id);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REQUESTING EMPLOYEE
    |--------------------------------------------------------------------------
    */

    $selectedEmployee =
        'All Requesting Employees';


    if (
        !empty($requestingEmployeeId)
        && isset($employees)
    ) {

        $employee =
            $employees->firstWhere(
                'id',
                $requestingEmployeeId
            );


        if ($employee) {

            $selectedEmployee =
                $employee->name
                ?? $employee->full_name
                ?? ('Employee #' . $employee->id);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EVENT
    |--------------------------------------------------------------------------
    */

    $selectedEvent =
        !empty($event)
            ? $event
            : 'All Events';


    /*
    |--------------------------------------------------------------------------
    | ITEM CODE
    |--------------------------------------------------------------------------
    */

    $selectedItemCode =
        !empty($itemCode)
            ? $itemCode
            : 'All Item Codes';


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    $selectedSearch =
        !empty($search)
            ? $search
            : 'No Search Filter';


    /*
    |--------------------------------------------------------------------------
    | DATE RANGE
    |--------------------------------------------------------------------------
    */

    $fromDateLabel =
        !empty($fromDate)
            ? \Carbon\Carbon::parse(
                $fromDate
            )->format('d M Y')
            : 'All Dates';


    $toDateLabel =
        !empty($toDate)
            ? \Carbon\Carbon::parse(
                $toDate
            )->format('d M Y')
            : 'All Dates';


    /*
    |--------------------------------------------------------------------------
    | GENERATED TIME
    |--------------------------------------------------------------------------
    */

    $generatedAt =
        now()->format('d M Y, h:i A');

@endphp


{{-- =========================================================
     PRINT CONTROL
========================================================= --}}

<div class="print-toolbar">

    <button
        type="button"
        class="print-button"
        onclick="window.print()"
        aria-label="Print temporary item report"
    >

        🖨 Print Report

    </button>

</div>



{{-- =========================================================
     REPORT PAGE
========================================================= --}}

<div class="page-shell">

    <div class="report-paper">


        {{-- =================================================
             CORPORATE HEADER
        ================================================== --}}

        <div class="corporate-header">

            <table class="header-table">

                <tr>


                    {{-- LOGO --}}

                    <td class="header-logo-cell">

                        @if($logoBase64)

                            <img
                                src="{{ $logoBase64 }}"
                                alt="Ceylon Petroleum Corporation"
                                class="header-logo"
                            >

                        @endif

                    </td>



                    {{-- MAIN HEADER --}}

                    <td class="header-main">

                        <div class="organization-name">

                            Ceylon Petroleum Corporation

                        </div>


                        <div class="organization-subtitle">

                            IT Function — Inventory Management System

                        </div>


                        <div class="report-heading">

                            {{ $reportTypeLabel }}

                        </div>


                        <div class="report-description">

                            Official temporary item issue and return management report

                        </div>

                    </td>



                    {{-- HEADER META --}}

                    <td class="header-meta-cell">

                        <div class="meta-label">

                            Report Generated

                        </div>


                        <div class="meta-value">

                            {{ $generatedAt }}

                        </div>


                        <div style="margin-top: 6px;">

                            <div class="meta-label">

                                Report Type

                            </div>


                            <div class="meta-value">

                                {{ $reportTypeLabel }}

                            </div>

                        </div>


                        <div class="official-label">

                            OFFICIAL DOCUMENT

                        </div>

                    </td>


                </tr>

            </table>

        </div>



        {{-- =================================================
             REPORT CRITERIA
        ================================================== --}}

        <div class="section">

            <div class="section-header">

                <div class="section-title">

                    Report Criteria

                </div>


                <div class="section-subtitle">

                    Filters and parameters applied to this report

                </div>

            </div>



            <div class="criteria-card">

                <table class="criteria-table">


                    {{-- ROW 1 --}}

                    <tr>

                        <td class="criteria-label">

                            Status

                        </td>

                        <td class="criteria-value">

                            {{ $selectedStatus }}

                        </td>


                        <td class="criteria-label">

                            Item Type

                        </td>

                        <td class="criteria-value">

                            {{ $selectedItemType }}

                        </td>


                        <td class="criteria-label">

                            Item Code

                        </td>

                        <td class="criteria-value">

                            {{ $selectedItemCode }}

                        </td>

                    </tr>



                    {{-- ROW 2 --}}

                    <tr>

                        <td class="criteria-label">

                            Department

                        </td>

                        <td class="criteria-value">

                            {{ $selectedDepartment }}

                        </td>


                        <td class="criteria-label">

                            Function Unit

                        </td>

                        <td class="criteria-value">

                            {{ $selectedFunctionUnit }}

                        </td>


                        <td class="criteria-label">

                            Requesting Employee

                        </td>

                        <td class="criteria-value">

                            {{ $selectedEmployee }}

                        </td>

                    </tr>



                    {{-- ROW 3 --}}

                    <tr>

                        <td class="criteria-label">

                            Event

                        </td>

                        <td class="criteria-value">

                            {{ $selectedEvent }}

                        </td>


                        <td class="criteria-label">

                            Date Range

                        </td>

                        <td class="criteria-value">

                            {{ $fromDateLabel }}

                            —

                            {{ $toDateLabel }}

                        </td>


                        <td class="criteria-label">

                            Overdue After

                        </td>

                        <td class="criteria-value">

                            {{ number_format($overdueThreshold) }}

                            {{ $overdueThreshold == 1 ? 'Day' : 'Days' }}

                        </td>

                    </tr>



                    {{-- ROW 4 --}}

                    @if(!empty($search))

                        <tr>

                            <td class="criteria-label">

                                Search

                            </td>

                            <td
                                class="criteria-value"
                                colspan="3"
                            >

                                {{ $selectedSearch }}

                            </td>


                            <td class="criteria-label">

                                Records

                            </td>

                            <td class="criteria-value">

                                {{ number_format($totalIssues) }}

                            </td>

                        </tr>

                    @else

                        <tr>

                            <td class="criteria-label">

                                Records

                            </td>

                            <td class="criteria-value">

                                {{ number_format($totalIssues) }}

                            </td>


                            <td class="criteria-label">

                                Events

                            </td>

                            <td class="criteria-value">

                                {{ number_format($totalEvents) }}

                            </td>


                            <td class="criteria-label">

                                Requesting Employees

                            </td>

                            <td class="criteria-value">

                                {{ number_format($uniqueEmployees) }}

                            </td>

                        </tr>

                    @endif


                </table>

            </div>

        </div>



        {{-- =================================================
             SUMMARY
        ================================================== --}}

        <div class="section">

            <div class="section-header">

                <div class="section-title">

                    Temporary Item Summary

                </div>


                <div class="section-subtitle">

                    Key temporary item issue, return and outstanding indicators

                </div>

            </div>



            <table class="summary-grid">

                <tr>


                    {{-- TOTAL --}}

                    <td>

                        <span class="summary-label">

                            Total Issues

                        </span>


                        <span class="summary-value">

                            {{ number_format($totalIssues) }}

                        </span>


                        <span class="summary-note">

                            Records in report

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


                        <span class="summary-note">

                            Items currently outstanding

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


                        <span class="summary-note">

                            Successfully received items

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


                        <span class="summary-note">

                            Issued beyond {{ $overdueThreshold }} days

                        </span>

                    </td>



                    {{-- EVENTS --}}

                    <td>

                        <span class="summary-label">

                            Total Events

                        </span>


                        <span class="summary-value">

                            {{ number_format($totalEvents) }}

                        </span>


                        <span class="summary-note">

                            Unique events represented

                        </span>

                    </td>

                </tr>

            </table>

        </div>



        {{-- =================================================
             TEMPORARY ITEM DETAILS
        ================================================== --}}

        <div class="section">

            <div class="section-header">

                <div class="section-title">

                    Temporary Item Details

                </div>


                <div class="section-subtitle">

                    Detailed temporary item issue and return records matching the selected criteria

                </div>

            </div>



            <div class="table-wrapper">

                <table class="report-table">

                    <thead>

                        <tr>


                            <th style="width: 3%;">

                                #

                            </th>


                            <th style="width: 8%;">

                                Issue Date

                            </th>


                            <th style="width: 7%;">

                                Item Code

                            </th>


                            <th style="width: 7%;">

                                Item Type

                            </th>


                            <th style="width: 11%;">

                                Department

                            </th>


                            <th style="width: 10%;">

                                Function Unit

                            </th>


                            <th style="width: 13%;">

                                Event

                            </th>


                            <th style="width: 12%;">

                                Requesting Employee

                            </th>


                            <th style="width: 11%;">

                                Collecting Employee

                            </th>


                            <th style="width: 8%;">

                                Return Date

                            </th>


                            <th style="width: 7%;">

                                Status

                            </th>


                            <th style="width: 10%;">

                                Remarks

                            </th>

                        </tr>

                    </thead>



                    <tbody>

                        @forelse($temporaryCollection as $index => $issue)

                            @php


                                /*
                                |--------------------------------------------------------------------------
                                | STATUS CLASS
                                |--------------------------------------------------------------------------
                                */

                                $statusClass = match (
                                    $issue->status
                                ) {

                                    'issued' =>
                                        'status-issued',

                                    'received' =>
                                        'status-received',

                                    'cancelled' =>
                                        'status-cancelled',

                                    default =>
                                        'status-default',
                                };


                                /*
                                |--------------------------------------------------------------------------
                                | STATUS TEXT
                                |--------------------------------------------------------------------------
                                */

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


                                /*
                                |--------------------------------------------------------------------------
                                | DEPARTMENT
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
                                | FUNCTION UNIT
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
                                | REQUESTING EMPLOYEE
                                |--------------------------------------------------------------------------
                                */

                                $requestingEmployeeName =
                                    optional(
                                        $issue->requestingEmployee
                                    )->name
                                    ?? optional(
                                        $issue->requestingEmployee
                                    )->full_name
                                    ?? '—';


                                /*
                                |--------------------------------------------------------------------------
                                | COLLECTING EMPLOYEE
                                |--------------------------------------------------------------------------
                                */

                                $collectingEmployeeName =
                                    optional(
                                        $issue->collectingEmployee
                                    )->name
                                    ?? optional(
                                        $issue->collectingEmployee
                                    )->full_name
                                    ?? '—';


                            @endphp



                            <tr>


                                {{-- # --}}

                                <td class="col-center col-bold">

                                    {{ $index + 1 }}

                                </td>



                                {{-- ISSUE DATE --}}

                                <td class="date-cell">

                                    @if($issue->issue_datetime)

                                        {{ \Carbon\Carbon::parse(
                                            $issue->issue_datetime
                                        )->format('d M Y') }}

                                        <br>

                                        <span class="col-muted">

                                            {{ \Carbon\Carbon::parse(
                                                $issue->issue_datetime
                                            )->format('h:i A') }}

                                        </span>

                                    @else

                                        —

                                    @endif

                                </td>



                                {{-- ITEM CODE --}}

                                <td class="col-bold">

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

                                <td class="description-cell">

                                    {{ $issue->event ?? '—' }}

                                </td>



                                {{-- REQUESTING EMPLOYEE --}}

                                <td>

                                    {{ $requestingEmployeeName }}

                                    @if(
                                        optional(
                                            $issue->requestingEmployee
                                        )->epf_no
                                    )

                                        <br>

                                        <span class="col-muted">

                                            EPF:
                                            {{ $issue->requestingEmployee->epf_no }}

                                        </span>

                                    @endif

                                </td>



                                {{-- COLLECTING EMPLOYEE --}}

                                <td>

                                    {{ $collectingEmployeeName }}

                                    @if(
                                        optional(
                                            $issue->collectingEmployee
                                        )->epf_no
                                    )

                                        <br>

                                        <span class="col-muted">

                                            EPF:
                                            {{ $issue->collectingEmployee->epf_no }}

                                        </span>

                                    @endif

                                </td>



                                {{-- RETURN DATE --}}

                                <td class="date-cell">

                                    @if($issue->received_datetime)

                                        {{ \Carbon\Carbon::parse(
                                            $issue->received_datetime
                                        )->format('d M Y') }}

                                        <br>

                                        <span class="col-muted">

                                            {{ \Carbon\Carbon::parse(
                                                $issue->received_datetime
                                            )->format('h:i A') }}

                                        </span>

                                    @else

                                        —

                                    @endif

                                </td>



                                {{-- STATUS --}}

                                <td class="col-center">

                                    <span
                                        class="status-badge {{ $statusClass }}"
                                    >

                                        {{ $statusText }}

                                    </span>

                                </td>



                                {{-- REMARKS --}}

                                <td class="description-cell">

                                    {{ $issue->remarks ?? '—' }}

                                </td>


                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="12"
                                    class="empty-state"
                                >

                                    <span class="empty-title">

                                        No Temporary Item Records Found

                                    </span>


                                    <span class="empty-message">

                                        No temporary item records match
                                        the selected report criteria.

                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>



        {{-- =================================================
             SIGNATURES
        ================================================== --}}

        <div class="signature-section">

            <table class="signature-table">

                <tr>


                    <td>

                        <div class="signature-line"></div>

                        <div class="signature-label">

                            Prepared By

                        </div>

                        <div class="signature-role">

                            Name / Signature / Date

                        </div>

                    </td>



                    <td>

                        <div class="signature-line"></div>

                        <div class="signature-label">

                            Checked By

                        </div>

                        <div class="signature-role">

                            Name / Signature / Date

                        </div>

                    </td>



                    <td>

                        <div class="signature-line"></div>

                        <div class="signature-label">

                            Authorized By

                        </div>

                        <div class="signature-role">

                            Name / Signature / Date

                        </div>

                    </td>


                </tr>

            </table>

        </div>



        {{-- =================================================
             FOOTER
        ================================================== --}}

        <div class="report-footer">

            <div class="footer-primary">

                Ceylon Petroleum Corporation —

                IT Function Inventory Management System

            </div>


            <div class="footer-secondary">

                {{ $reportTypeLabel }}

                |

                Generated on {{ $generatedAt }}

                |

                This report is system-generated.

            </div>

        </div>


    </div>

</div>



<script>

    /*
    |--------------------------------------------------------------------------
    | PRINT BEHAVIOUR
    |--------------------------------------------------------------------------
    |
    | Automatic printing intentionally remains disabled.
    | The user can click "Print Report" when required.
    |
    */

    // window.addEventListener('load', function () {
    //     window.print();
    // });

</script>


</body>

</html>