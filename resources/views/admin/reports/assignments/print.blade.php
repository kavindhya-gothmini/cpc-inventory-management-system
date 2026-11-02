
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Assignment Report | CPC Inventory Management System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            padding: 30px;
            background: #f4f6f9;
            color: #212529;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .report-container {
            max-width: 1400px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        /* =========================================================
           CPC DOCUMENT HEADER
        ========================================================== */

        .document-header {
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 18px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 110px;
            vertical-align: middle;
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Use asset() for browser Print reports.
        | public_path() is intended for server-side filesystem access,
        | especially DomPDF, not normal browser image loading.
        |--------------------------------------------------------------------------
        */

        .logo {
            display: block;
            width: 82px;
            height: auto;
            max-height: 82px;
            object-fit: contain;
        }

        .organization-cell {
            vertical-align: middle;
            text-align: left;
        }

        .organization-name {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.3px;
            margin-bottom: 4px;
        }

        .function-name {
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 4px;
        }

        .system-name {
            font-size: 12px;
            color: #6c757d;
        }

        .document-cell {
            width: 180px;
            text-align: right;
            vertical-align: middle;
        }

        .official-label {
            display: inline-block;
            border: 1px solid #0d6efd;
            color: #0d6efd;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.8px;
            margin-bottom: 7px;
        }

        .generated-date {
            color: #6c757d;
            font-size: 10px;
        }

        /* =========================================================
           REPORT TITLE
        ========================================================== */

        .report-title {
            text-align: center;
            margin: 22px 0 20px;
        }

        .report-title h1 {
            margin: 0 0 5px;
            font-size: 21px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 0;
            color: #6c757d;
            font-size: 11px;
        }

        /* =========================================================
           SECTIONS
        ========================================================== */

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            margin: 0 0 8px;
            padding: 8px 10px;
            background: #f1f3f5;
            border-left: 4px solid #0d6efd;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* =========================================================
           CRITERIA
        ========================================================== */

        .criteria-table {
            width: 100%;
            border-collapse: collapse;
        }

        .criteria-table td {
            border: 1px solid #dee2e6;
            padding: 7px 9px;
        }

        .criteria-label {
            width: 14%;
            font-weight: 700;
            background: #f8f9fa;
        }

        .criteria-value {
            width: 19%;
        }

        /* =========================================================
           SUMMARY
        ========================================================== */

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            width: 25%;
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: center;
        }

        .summary-number {
            display: block;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .summary-label {
            display: block;
            color: #6c757d;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* =========================================================
           RECORDS TABLE
        ========================================================== */

        .records-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .records-table th {
            background: #212529;
            color: #ffffff;
            border: 1px solid #212529;
            padding: 7px 5px;
            font-size: 9px;
            text-align: left;
            vertical-align: middle;
        }

        .records-table td {
            border: 1px solid #dee2e6;
            padding: 6px 5px;
            font-size: 9px;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .records-table tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .text-center {
            text-align: center !important;
        }

        /* =========================================================
           STATUS
        ========================================================== */

        .status {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-issued {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-returned {
            background: #e2e3e5;
            color: #41464b;
        }

        /* =========================================================
           EMPTY STATE
        ========================================================== */

        .empty-state {
            text-align: center;
            padding: 25px;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }

        /* =========================================================
           SIGNATURE SECTION
        ========================================================== */

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 35px;
        }

        .signature-table td {
            width: 33.333%;
            padding: 10px 25px 0;
            vertical-align: bottom;
        }

        .signature-line {
            border-bottom: 1px solid #212529;
            height: 45px;
            margin-bottom: 7px;
        }

        .signature-title {
            font-weight: 700;
            font-size: 10px;
        }

        .signature-subtitle {
            color: #6c757d;
            font-size: 9px;
            margin-top: 3px;
        }

        /* =========================================================
           FOOTER
        ========================================================== */

        .document-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 9px;
        }

        /* =========================================================
           PRINT ACTIONS
        ========================================================== */

        .print-actions {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 8px;
            z-index: 9999;
        }

        .print-btn {
            border: none;
            border-radius: 5px;
            padding: 9px 14px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-print {
            background: #0d6efd;
            color: #ffffff;
        }

        .btn-close {
            background: #6c757d;
            color: #ffffff;
        }

        .print-btn:hover {
            opacity: 0.9;
        }

        /* =========================================================
           RESPONSIVE SCREEN VIEW
        ========================================================== */

        @media screen and (max-width: 768px) {

            body {
                padding: 15px;
            }

            .report-container {
                padding: 18px;
            }

            .header-table,
            .header-table tbody,
            .header-table tr,
            .header-table td {
                display: block;
                width: 100%;
                text-align: center;
            }

            .logo-cell {
                margin-bottom: 10px;
            }

            .logo {
                margin: 0 auto;
            }

            .organization-cell {
                margin-bottom: 12px;
            }

            .document-cell {
                text-align: center;
            }

            .criteria-table {
                display: block;
                overflow-x: auto;
            }

            .records-table {
                min-width: 1000px;
            }

            .signature-table,
            .signature-table tbody,
            .signature-table tr,
            .signature-table td {
                display: block;
                width: 100%;
            }

            .signature-table td {
                padding: 15px 10px;
            }

            .print-actions {
                position: static;
                justify-content: flex-end;
                margin-bottom: 15px;
            }
        }

        /* =========================================================
           PRINT STYLES
        ========================================================== */

        @media print {

            @page {
                size: A4 landscape;
                margin: 12mm;
            }

            html,
            body {
                width: 100%;
                margin: 0;
                padding: 0;
                background: #ffffff;
            }

            body {
                font-size: 10px;
            }

            .report-container {
                max-width: none;
                width: 100%;
                margin: 0;
                padding: 0;
                background: #ffffff;
                box-shadow: none;
            }

            /*
            |--------------------------------------------------------------------------
            | Keep CPC logo visible in browser print output.
            |--------------------------------------------------------------------------
            */

            .logo {
                display: block !important;
                width: 82px !important;
                height: auto !important;
                max-height: 82px !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .print-actions {
                display: none !important;
            }

            .document-header {
                margin-bottom: 12px;
                page-break-inside: avoid;
            }

            .header-table {
                width: 100%;
            }

            .report-title {
                margin: 12px 0;
                page-break-inside: avoid;
            }

            .section {
                margin-bottom: 10px;
            }

            .records-table {
                page-break-inside: auto;
            }

            .records-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .records-table thead {
                display: table-header-group;
            }

            .records-table tfoot {
                display: table-footer-group;
            }

            .signature-table {
                page-break-inside: avoid;
            }

            .document-footer {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

<div class="print-actions">

    <button type="button"
            class="print-btn btn-print"
            onclick="window.print()">
        Print Report
    </button>

    <button type="button"
            class="print-btn btn-close"
            onclick="window.close()">
        Close
    </button>

</div>

<div class="report-container">

    {{-- =========================================================
         CPC DOCUMENT HEADER
    ========================================================== --}}

    <div class="document-header">

        <table class="header-table">

            <tr>

                <td class="logo-cell">

                    {{-- IMPORTANT: asset() is used for browser printing --}}
                    <img
                        src="{{ asset('images/cpc-logo.png') }}"
                        alt="Ceylon Petroleum Corporation Logo"
                        class="logo"
                    >

                </td>

                <td class="organization-cell">

                    <div class="organization-name">
                        CEYLON PETROLEUM CORPORATION
                    </div>

                    <div class="function-name">
                        INFORMATION TECHNOLOGY FUNCTION
                    </div>

                    <div class="system-name">
                        CPC INVENTORY MANAGEMENT SYSTEM
                    </div>

                </td>

                <td class="document-cell">

                    <div class="official-label">
                        OFFICIAL DOCUMENT
                    </div>

                    <div class="generated-date">
                        Generated:
                        {{ now()->format('Y-m-d H:i:s') }}
                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- =========================================================
         REPORT TITLE
    ========================================================== --}}

    <div class="report-title">

        <h1>
            Assignment Report
        </h1>

        <p>
            Asset Assignment, Employee Allocation and Assignment History
        </p>

    </div>


    {{-- =========================================================
         REPORT CRITERIA
    ========================================================== --}}

    <div class="section">

        <h2 class="section-title">
            Report Criteria
        </h2>

        <table class="criteria-table">

            <tr>

                <td class="criteria-label">
                    Report Type
                </td>

                <td class="criteria-value">

                    @switch($reportType ?? 'current')

                        @case('current')
                            Current Assignments
                            @break

                        @case('history')
                            Assignment History
                            @break

                        @case('employee')
                            Employee Assets
                            @break

                        @case('unassigned')
                            Unassigned Assets
                            @break

                        @default
                            All Assignments

                    @endswitch

                </td>

                <td class="criteria-label">
                    Employee
                </td>

                <td class="criteria-value">

                    @if(request('employee_id'))

                        {{ optional(\App\Models\Employee::find(request('employee_id')))->name ?? 'Selected Employee' }}

                    @else

                        All Employees

                    @endif

                </td>

                <td class="criteria-label">
                    Status
                </td>

                <td class="criteria-value">

                    @switch(request('status'))

                        @case('issued')
                            Issued
                            @break

                        @case('returned')
                            Returned
                            @break

                        @default
                            All Statuses

                    @endswitch

                </td>

            </tr>

            <tr>

                <td class="criteria-label">
                    Branch
                </td>

                <td class="criteria-value">

                    @if(request('branch_id'))

                        {{ optional(\App\Models\Branch::find(request('branch_id')))->name ?? 'Selected Branch' }}

                    @else

                        All Branches

                    @endif

                </td>

                <td class="criteria-label">
                    Department
                </td>

                <td class="criteria-value">

                    @if(request('department_id'))

                        {{ optional(\App\Models\Department::find(request('department_id')))->name ?? 'Selected Department' }}

                    @else

                        All Departments

                    @endif

                </td>

                <td class="criteria-label">
                    Function Unit
                </td>

                <td class="criteria-value">

                    @if(request('function_unit_id'))

                        {{ optional(\App\Models\FunctionUnit::find(request('function_unit_id')))->name ?? 'Selected Function Unit' }}

                    @else

                        All Function Units

                    @endif

                </td>

            </tr>

            <tr>

                <td class="criteria-label">
                    Item Type
                </td>

                <td class="criteria-value">

                    @if(request('item_type_id'))

                        {{ optional(\App\Models\ItemType::find(request('item_type_id')))->name ?? 'Selected Item Type' }}

                    @else

                        All Item Types

                    @endif

                </td>

                <td class="criteria-label">
                    From Date
                </td>

                <td class="criteria-value">
                    {{ request('from_date') ?: 'All Dates' }}
                </td>

                <td class="criteria-label">
                    To Date
                </td>

                <td class="criteria-value">
                    {{ request('to_date') ?: 'All Dates' }}
                </td>

            </tr>

        </table>

    </div>


    {{-- =========================================================
         SUMMARY
    ========================================================== --}}

    <div class="section">

        <h2 class="section-title">
            Assignment Summary
        </h2>

        @php

            $assignmentCollection = collect($assignments ?? []);

            $totalRecords = $assignmentCollection->count();

            $issuedRecords = $assignmentCollection
                ->filter(fn ($assignment) => is_null($assignment->returned_date))
                ->count();

            $returnedRecords = $assignmentCollection
                ->filter(fn ($assignment) => !is_null($assignment->returned_date))
                ->count();

            $unassignedCount = collect($unassignedItems ?? [])->count();

        @endphp

        <table class="summary-table">

            <tr>

                <td>

                    <span class="summary-number">
                        {{ number_format($totalRecords) }}
                    </span>

                    <span class="summary-label">
                        Assignment Records
                    </span>

                </td>

                <td>

                    <span class="summary-number">
                        {{ number_format($issuedRecords) }}
                    </span>

                    <span class="summary-label">
                        Currently Issued
                    </span>

                </td>

                <td>

                    <span class="summary-number">
                        {{ number_format($returnedRecords) }}
                    </span>

                    <span class="summary-label">
                        Returned
                    </span>

                </td>

                <td>

                    <span class="summary-number">
                        {{ number_format($unassignedCount) }}
                    </span>

                    <span class="summary-label">
                        Unassigned Assets
                    </span>

                </td>

            </tr>

        </table>

    </div>


    {{-- =========================================================
         ASSIGNMENT RECORDS
    ========================================================== --}}

    @if(($reportType ?? 'current') !== 'unassigned')

        <div class="section">

            <h2 class="section-title">
                Assignment Records
            </h2>

            @if($assignmentCollection->count())

                <table class="records-table">

                    <thead>

                    <tr>

                        <th style="width: 3%;" class="text-center">
                            #
                        </th>

                        <th style="width: 9%;">
                            Asset Number
                        </th>

                        <th style="width: 9%;">
                            Item Type
                        </th>

                        <th style="width: 12%;">
                            Employee
                        </th>

                        <th style="width: 10%;">
                            Branch
                        </th>

                        <th style="width: 11%;">
                            Department
                        </th>

                        <th style="width: 10%;">
                            Function Unit
                        </th>

                        <th style="width: 8%;">
                            Workstation
                        </th>

                        <th style="width: 8%;">
                            Assigned Date
                        </th>

                        <th style="width: 8%;">
                            Returned Date
                        </th>

                        <th style="width: 7%;">
                            Status
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($assignmentCollection as $index => $assignment)

                        @php
                            $isIssued = is_null($assignment->returned_date);
                        @endphp

                        <tr>

                            <td class="text-center">
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ optional($assignment->item)->asset_number ?? '—' }}
                            </td>

                            <td>
                                {{ optional(optional($assignment->item)->itemType)->name ?? '—' }}
                            </td>

                            <td>
                                {{ optional($assignment->employee)->name ?? '—' }}
                            </td>

                            <td>
                                {{ optional($assignment->branch)->name ?? '—' }}
                            </td>

                            <td>
                                {{ optional($assignment->department)->name ?? '—' }}
                            </td>

                            <td>
                                {{ optional($assignment->functionUnit)->name ?? '—' }}
                            </td>

                            <td>
                                {{ $assignment->workstation_id ?? '—' }}
                            </td>

                            <td>
                                {{ optional($assignment->assigned_date)->format('Y-m-d') ?? '—' }}
                            </td>

                            <td>
                                {{ optional($assignment->returned_date)->format('Y-m-d') ?? '—' }}
                            </td>

                            <td>

                                @if($isIssued)

                                    <span class="status status-issued">
                                        Issued
                                    </span>

                                @else

                                    <span class="status status-returned">
                                        Returned
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">
                    No assignment records were found for the selected criteria.
                </div>

            @endif

        </div>

    @else

        {{-- =====================================================
             UNASSIGNED ASSETS
        ====================================================== --}}

        <div class="section">

            <h2 class="section-title">
                Unassigned Assets
            </h2>

            @php
                $unassignedCollection = collect($unassignedItems ?? []);
            @endphp

            @if($unassignedCollection->count())

                <table class="records-table">

                    <thead>

                    <tr>

                        <th style="width: 4%;" class="text-center">
                            #
                        </th>

                        <th style="width: 12%;">
                            Asset Number
                        </th>

                        <th style="width: 12%;">
                            Item Type
                        </th>

                        <th style="width: 14%;">
                            Make / Model
                        </th>

                        <th style="width: 12%;">
                            Serial Number
                        </th>

                        <th style="width: 14%;">
                            Branch
                        </th>

                        <th style="width: 14%;">
                            Department
                        </th>

                        <th style="width: 10%;">
                            Current Employee
                        </th>

                        <th style="width: 8%;">
                            Status
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($unassignedCollection as $index => $item)

                        <tr>

                            <td class="text-center">
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ $item->asset_number ?? '—' }}
                            </td>

                            <td>
                                {{ optional($item->itemType)->name ?? '—' }}
                            </td>

                            <td>
                                {{ trim(($item->make ?? '') . ' ' . ($item->model ?? '')) ?: '—' }}
                            </td>

                            <td>
                                {{ $item->serial_number ?? '—' }}
                            </td>

                            <td>
                                {{ optional($item->branch)->name ?? '—' }}
                            </td>

                            <td>
                                {{ optional($item->department)->name ?? '—' }}
                            </td>

                            <td>
                                {{ optional($item->currentEmployee)->name ?? 'Unassigned' }}
                            </td>

                            <td>
                                {{ ucwords(str_replace('_', ' ', $item->status ?? '—')) }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">
                    No unassigned assets were found for the selected criteria.
                </div>

            @endif

        </div>

    @endif


    {{-- =========================================================
         CERTIFICATION / SIGNATURE
    ========================================================== --}}

    <div class="section">

        <h2 class="section-title">
            Document Certification & Authorization
        </h2>

        <table class="signature-table">

            <tr>

                <td>

                    <div class="signature-line"></div>

                    <div class="signature-title">
                        Prepared By
                    </div>

                    <div class="signature-subtitle">
                        Name / Signature / Date
                    </div>

                </td>

                <td>

                    <div class="signature-line"></div>

                    <div class="signature-title">
                        Checked By
                    </div>

                    <div class="signature-subtitle">
                        Name / Signature / Date
                    </div>

                </td>

                <td>

                    <div class="signature-line"></div>

                    <div class="signature-title">
                        Authorized By
                    </div>

                    <div class="signature-subtitle">
                        Name / Signature / Date
                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <div class="document-footer">

        CPC Inventory Management System
        &nbsp;|&nbsp;
        Information Technology Function
        &nbsp;|&nbsp;
        Ceylon Petroleum Corporation

        <br>

        This is a system-generated report. Please verify the information
        before official use.

    </div>

</div>

</body>
</html>
