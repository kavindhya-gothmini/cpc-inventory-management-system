<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>CPC Assignment Report</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 8px;
            color: #212529;
        }

        .document-header {
            width: 100%;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 75px;
            vertical-align: middle;
        }

        .logo {
            width: 62px;
            height: auto;
        }

        .organization-cell {
            vertical-align: middle;
        }

        .organization-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .function-name {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .system-name {
            font-size: 8px;
            color: #666666;
        }

        .document-cell {
            width: 150px;
            text-align: right;
            vertical-align: middle;
        }

        .official-label {
            display: inline-block;
            border: 1px solid #0d6efd;
            color: #0d6efd;
            padding: 4px 7px;
            font-size: 7px;
            font-weight: bold;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .generated-date {
            font-size: 7px;
            color: #666666;
        }

        .report-title {
            text-align: center;
            margin: 10px 0 12px;
        }

        .report-title h1 {
            margin: 0 0 3px;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 0;
            font-size: 8px;
            color: #666666;
        }

        .section {
            margin-bottom: 10px;
        }

        .section-title {
            margin: 0 0 5px;
            padding: 5px 7px;
            background: #f1f3f5;
            border-left: 3px solid #0d6efd;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .criteria-table {
            width: 100%;
            border-collapse: collapse;
        }

        .criteria-table td {
            border: 1px solid #d9dee3;
            padding: 4px 5px;
            vertical-align: middle;
        }

        .criteria-label {
            width: 11%;
            font-weight: bold;
            background: #f8f9fa;
        }

        .criteria-value {
            width: 22%;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            width: 25%;
            border: 1px solid #d9dee3;
            padding: 7px;
            text-align: center;
        }

        .summary-number {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }

        .summary-label {
            font-size: 7px;
            color: #666666;
            font-weight: bold;
            text-transform: uppercase;
        }

        .records-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .records-table thead {
            display: table-header-group;
        }

        .records-table th {
            background: #212529;
            color: #ffffff;
            border: 1px solid #212529;
            padding: 4px 3px;
            font-size: 6.8px;
            font-weight: bold;
            text-align: left;
            vertical-align: middle;
        }

        .records-table td {
            border: 1px solid #d9dee3;
            padding: 4px 3px;
            font-size: 6.8px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .records-table tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .text-center {
            text-align: center;
        }

        .status-issued {
            font-weight: bold;
            color: #198754;
        }

        .status-returned {
            font-weight: bold;
            color: #6c757d;
        }

        .empty-state {
            border: 1px solid #d9dee3;
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 22px;
        }

        .signature-table td {
            width: 33.333%;
            padding: 5px 18px 0;
            vertical-align: bottom;
        }

        .signature-line {
            height: 30px;
            border-bottom: 1px solid #212529;
            margin-bottom: 5px;
        }

        .signature-title {
            font-size: 7px;
            font-weight: bold;
        }

        .signature-subtitle {
            font-size: 6.5px;
            color: #666666;
            margin-top: 2px;
        }

        .footer {
            width: 100%;
            border-top: 1px solid #d9dee3;
            margin-top: 15px;
            padding-top: 5px;
            text-align: center;
            color: #777777;
            font-size: 6.5px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

@php

    $reportType = $reportType ?? 'current';

    $assignmentCollection = collect($assignments ?? []);

    $unassignedCollection = collect($unassignedItems ?? []);

    $totalRecords = $assignmentCollection->count();

    $issuedRecords = $assignmentCollection
        ->filter(fn ($assignment) => is_null($assignment->returned_date))
        ->count();

    $returnedRecords = $assignmentCollection
        ->filter(fn ($assignment) => !is_null($assignment->returned_date))
        ->count();

    $unassignedCount = $unassignedCollection->count();

    $reportTypeLabel = match ($reportType) {
        'current' => 'Current Assignments',
        'history' => 'Assignment History',
        'employee' => 'Employee Assets',
        'unassigned' => 'Unassigned Assets',
        default => 'Assignment Report',
    };

@endphp


{{-- ============================================================
     CPC HEADER
============================================================= --}}

<div class="document-header">

    <table class="header-table">

        <tr>

            <td class="logo-cell">

               @if(file_exists(public_path('images/cpc-logo.png')))
    <img
        src="{{ public_path('images/cpc-logo.png') }}"
        class="logo"
        alt="CPC Logo"
    >
@endif
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


{{-- ============================================================
     TITLE
============================================================= --}}

<div class="report-title">

    <h1>
        Assignment Report
    </h1>

    <p>
        {{ $reportTypeLabel }}
    </p>

</div>


{{-- ============================================================
     REPORT CRITERIA
============================================================= --}}

<div class="section">

    <div class="section-title">
        Report Criteria
    </div>

    <table class="criteria-table">

        <tr>

            <td class="criteria-label">
                Report Type
            </td>

            <td class="criteria-value">
                {{ $reportTypeLabel }}
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


{{-- ============================================================
     SUMMARY
============================================================= --}}

<div class="section">

    <div class="section-title">
        Assignment Summary
    </div>

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


{{-- ============================================================
     ASSIGNMENT RECORDS
============================================================= --}}

@if($reportType !== 'unassigned')

    <div class="section">

        <div class="section-title">
            Assignment Records
        </div>

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

                                <span class="status-issued">
                                    Issued
                                </span>

                            @else

                                <span class="status-returned">
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

    {{-- ========================================================
         UNASSIGNED ASSETS
    ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Unassigned Assets
        </div>

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


{{-- ============================================================
     CERTIFICATION & SIGNATURE
============================================================= --}}

<div class="section">

    <div class="section-title">
        Document Certification & Authorization
    </div>

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


{{-- ============================================================
     FOOTER
============================================================= --}}

<div class="footer">

    CPC Inventory Management System
    |
    Information Technology Function
    |
    Ceylon Petroleum Corporation

    <br>

    This is a system-generated report. Please verify the information
    before official use.

</div>

</body>
</html>