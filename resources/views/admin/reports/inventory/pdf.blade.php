<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>CPC Inventory Summary Report</title>

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
            margin-bottom: 8px;
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
            width: 180px;
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
            margin-bottom: 12px;
        }

        /* =====================================================
           TITLE
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
            margin-bottom: 12px;
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
            width: 20%;
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
           INVENTORY TABLE
        ===================================================== */

        .inventory {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .inventory th {
            background: #0d6efd;
            color: #ffffff;
            border: 1px solid #0b5ed7;
            padding: 5px 3px;
            font-size: 7.5px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .inventory td {
            border: 1px solid #dee2e6;
            padding: 4px 3px;
            font-size: 7px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .inventory tr {
            page-break-inside: avoid;
        }

        .center {
            text-align: center;
        }

        .status {
            text-align: center;
            font-weight: bold;
        }

        .col-no {
            width: 4%;
        }

        .col-asset {
            width: 10%;
        }

        .col-type {
            width: 11%;
        }

        .col-make {
            width: 8%;
        }

        .col-model {
            width: 9%;
        }

        .col-serial {
            width: 11%;
        }

        .col-branch {
            width: 11%;
        }

        .col-department {
            width: 12%;
        }

        .col-employee {
            width: 10%;
        }

        .col-status {
            width: 7%;
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

        /* =====================================================
           PRINT CONTROLS
        ===================================================== */

        .print-controls {
            position: fixed;
            top: 15px;
            right: 15px;
        }

        .print-button,
        .close-button {
            border: none;
            padding: 8px 14px;
            margin-left: 5px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .print-button {
            background: #0d6efd;
            color: #ffffff;
        }

        .close-button {
            background: #6c757d;
            color: #ffffff;
        }

        @media print {

            .print-controls {
                display: none !important;
            }

            body {
                background: #ffffff;
            }
        }
    </style>
</head>

<body>

<div class="document">

    {{-- =====================================================
         PRINT CONTROLS
    ====================================================== --}}
{{--
    <div class="print-controls">

        <button
            type="button"
            class="print-button"
            onclick="window.print()"
        >
            Print
        </button>

        <button
            type="button"
            class="close-button"
            onclick="window.close()"
        >
            Close
        </button>

    </div>
--}}

    {{-- =====================================================
         CPC HEADER
    ====================================================== --}}

    <table class="header">
        <tr>

            <td class="logo-cell">

                @php
                    $logoPath = public_path('images/cpc-logo.png');
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
                    {{ now()->format('d M Y, h:i A') }}
                </div>

            </td>

        </tr>
    </table>

    <div class="header-line"></div>


    {{-- =====================================================
         REPORT TITLE
    ====================================================== --}}

    <div class="report-title">
        Inventory Summary Report
    </div>

    <div class="report-subtitle">
        Official inventory records generated through the CPC Inventory Management System
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
                Branch
            </td>

            <td class="criteria-value">

                @if(!empty(request('branch_id')))

                    {{ optional(
                        \App\Models\Branch::find(
                            request('branch_id')
                        )
                    )->name ?: '—' }}

                @else

                    All Branches

                @endif

            </td>


            <td class="criteria-label">
                Department
            </td>

            <td class="criteria-value">

                @if(!empty(request('department_id')))

                    {{ optional(
                        \App\Models\Department::find(
                            request('department_id')
                        )
                    )->name ?: '—' }}

                @else

                    All Departments

                @endif

            </td>

        </tr>

        <tr>

            <td class="criteria-label">
                Item Type
            </td>

            <td class="criteria-value">

                @if(!empty(request('item_type_id')))

                    {{ optional(
                        \App\Models\ItemType::find(
                            request('item_type_id')
                        )
                    )->name ?: '—' }}

                @else

                    All Item Types

                @endif

            </td>


            <td class="criteria-label">
                Status
            </td>

            <td class="criteria-value">

                @if(!empty(request('status')))

                    {{ ucwords(
                        str_replace(
                            '_',
                            ' ',
                            request('status')
                        )
                    ) }}

                @else

                    All Statuses

                @endif

            </td>

        </tr>

        <tr>

            <td class="criteria-label">
                From Date
            </td>

            <td class="criteria-value">

                @if(!empty($fromDate))

                    {{ date(
                        'd M Y',
                        strtotime($fromDate)
                    ) }}

                @else

                    All Dates

                @endif

            </td>


            <td class="criteria-label">
                To Date
            </td>

            <td class="criteria-value">

                @if(!empty($toDate))

                    {{ date(
                        'd M Y',
                        strtotime($toDate)
                    ) }}

                @else

                    All Dates

                @endif

            </td>

        </tr>

    </table>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="section-title">
        Inventory Summary
    </div>

    <table class="summary">

        <tr>

            <td>

                <div class="summary-label">
                    Total Assets
                </div>

                <div class="summary-value">
                    {{ number_format($totalAssets) }}
                </div>

            </td>

            <td>

                <div class="summary-label">
                    In Use
                </div>

                <div class="summary-value">
                    {{ number_format($inUse) }}
                </div>

            </td>

            <td>

                <div class="summary-label">
                    Available
                </div>

                <div class="summary-value">
                    {{ number_format($available) }}
                </div>

            </td>

            <td>

                <div class="summary-label">
                    Under Repair
                </div>

                <div class="summary-value">
                    {{ number_format($underRepair) }}
                </div>

            </td>

            <td>

                <div class="summary-label">
                    RDD
                </div>

                <div class="summary-value">
                    {{ number_format($rdd) }}
                </div>

            </td>

        </tr>

    </table>


    {{-- =====================================================
         INVENTORY RECORDS
    ====================================================== --}}

    <div class="section-title">
        Inventory Records
    </div>

    @if($items->count() > 0)

        <table class="inventory">

            <thead>

                <tr>

                    <th class="col-no">#</th>

                    <th class="col-asset">
                        Asset Number
                    </th>

                    <th class="col-type">
                        Item Type
                    </th>

                    <th class="col-make">
                        Make
                    </th>

                    <th class="col-model">
                        Model
                    </th>

                    <th class="col-serial">
                        Serial Number
                    </th>

                    <th class="col-branch">
                        Branch
                    </th>

                    <th class="col-department">
                        Department
                    </th>

                    <th class="col-employee">
                        Employee
                    </th>

                    <th class="col-status">
                        Status
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($items as $index => $item)

                    <tr>

                        <td class="center">
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $item->asset_number ?: '—' }}
                        </td>

                        <td>
                            {{ optional($item->itemType)->name ?: '—' }}
                        </td>

                        <td>
                            {{ $item->make ?: '—' }}
                        </td>

                        <td>
                            {{ $item->model ?: '—' }}
                        </td>

                        <td>
                            {{ $item->serial_number ?: '—' }}
                        </td>

                        <td>
                            {{ optional($item->branch)->name ?: '—' }}
                        </td>

                        <td>
                            {{ optional($item->department)->name ?: '—' }}
                        </td>

                        <td>
                            {{ optional($item->currentEmployee)->name ?: 'Unassigned' }}
                        </td>

                        <td class="status">
                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $item->status
                                )
                            ) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty">
            No inventory records were found for the selected report criteria.
        </div>

    @endif


    {{-- =====================================================
         SIGNATURE SECTION
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
            CPC Inventory Management System | Official Inventory Report
        </div>

        <div class="footer-note">
            This document was generated electronically by the CPC Inventory Management System.
        </div>

    </div>

</div>

</body>
</html>