{{-- resources/views/admin/reports/repairs/print.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $reportTypeLabel ?? 'Repair Report' }} |
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

            font-family: Arial, Helvetica, sans-serif;
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
            width: 16.66%;
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

            font-size: 7.8px;
            font-weight: 800;

            text-align: left;
            vertical-align: middle;

            line-height: 1.2;
        }

        .report-table td {
            padding: 5px 4px;

            border: 1px solid #dfe4ea;

            color: #212529;

            font-size: 7.8px;
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

        .description-cell {
            line-height: 1.35;
        }

        .date-cell {
            white-space: nowrap;
        }

        /* =========================================================
           STATUS BADGES
        ========================================================= */

        .status-badge {
            display: inline-block;

            min-width: 52px;

            padding: 3px 5px;

            border: 1px solid transparent;
            border-radius: 4px;

            font-size: 6.9px;
            font-weight: 800;

            text-align: center;
            line-height: 1.15;

            white-space: nowrap;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border-color: #ffe69c;
        }

        .status-sent {
            background: #cfe2ff;
            color: #084298;
            border-color: #9ec5fe;
        }

        .status-under-repair {
            background: #e2e3e5;
            color: #41464b;
            border-color: #c4c8cb;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
            border-color: #a3cfbb;
        }

        .status-returned {
            background: #cff4fc;
            color: #055160;
            border-color: #9eeaf9;
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
            base64_encode(file_get_contents($logoPath));
    }


    /*
    |--------------------------------------------------------------------------
    | REPORT TYPE
    |--------------------------------------------------------------------------
    */

    $reportTypeLabels = [
        'summary' => 'Repair Summary',
        'pending' => 'Pending Repairs',
        'completed' => 'Completed Repairs',
        'pending_completed' => 'Pending & Completed Repairs',
        'cost' => 'Repair Cost Report',
    ];

    $reportTypeLabel =
        $reportTypeLabels[$reportType ?? 'summary']
        ?? 'Repair Report';


    /*
    |--------------------------------------------------------------------------
    | COLLECTION
    |--------------------------------------------------------------------------
    */

    $repairCollection =
        $repairs instanceof \Illuminate\Pagination\AbstractPaginator
            ? $repairs->getCollection()
            : collect($repairs ?? []);


    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

    $totalRepairs =
        $repairCollection->count();

    $pendingRepairs =
        $repairCollection
            ->where('status', 'pending')
            ->count();

    $sentForRepair =
        $repairCollection
            ->where('status', 'sent_for_repair')
            ->count();

    $underRepair =
        $repairCollection
            ->where('status', 'under_repair')
            ->count();

    $completedRepairs =
        $repairCollection
            ->whereIn('status', ['completed', 'returned'])
            ->count();

    $cancelledRepairs =
        $repairCollection
            ->where('status', 'cancelled')
            ->count();

    $totalRepairCost =
        $repairCollection->sum(function ($repair) {
            return (float) ($repair->repair_cost ?? 0);
        });


    /*
    |--------------------------------------------------------------------------
    | TURNAROUND
    |--------------------------------------------------------------------------
    */

    $turnaroundValues =
        $repairCollection
            ->filter(function ($repair) {
                return
                    $repair->date_sent &&
                    $repair->date_returned;
            })
            ->map(function ($repair) {
                return $repair->date_sent
                    ->diffInDays($repair->date_returned);
            });

    $averageTurnaround =
        $turnaroundValues->count() > 0
            ? round($turnaroundValues->avg(), 1)
            : 0;


    /*
    |--------------------------------------------------------------------------
    | STATUS LABELS
    |--------------------------------------------------------------------------
    */

    $statusLabels = [
        'pending' => 'Pending',
        'sent_for_repair' => 'Sent for Repair',
        'under_repair' => 'Under Repair',
        'completed' => 'Completed',
        'returned' => 'Returned',
        'cancelled' => 'Cancelled',
    ];

    $selectedStatus =
        $statusLabels[$status ?? '']
        ?? 'All Statuses';


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE
    |--------------------------------------------------------------------------
    */

    $selectedEmployee = 'All Employees';

    if (!empty($employeeId)) {

        $employee =
            $employees->firstWhere('id', $employeeId);

        if ($employee) {

            $selectedEmployee =
                $employee->name
                ?? $employee->full_name
                ?? ('Employee #' . $employee->id);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | BRANCH
    |--------------------------------------------------------------------------
    */

    $selectedBranch = 'All Branches';

    if (!empty($branchId)) {

        $branch =
            $branches->firstWhere('id', $branchId);

        if ($branch) {

            $selectedBranch =
                $branch->name
                ?? $branch->branch_name
                ?? ('Branch #' . $branch->id);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT
    |--------------------------------------------------------------------------
    */

    $selectedDepartment = 'All Departments';

    if (!empty($departmentId)) {

        $department =
            $departments->firstWhere('id', $departmentId);

        if ($department) {

            $selectedDepartment =
                $department->name
                ?? $department->department_name
                ?? ('Department #' . $department->id);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ITEM TYPE
    |--------------------------------------------------------------------------
    */

    $selectedItemType = 'All Item Types';

    if (!empty($itemTypeId)) {

        $itemType =
            $itemTypes->firstWhere('id', $itemTypeId);

        if ($itemType) {

            $selectedItemType =
                $itemType->name
                ?? $itemType->item_type
                ?? $itemType->type_name
                ?? ('Item Type #' . $itemType->id);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTION UNIT
    |--------------------------------------------------------------------------
    */

    $selectedFunctionUnit =
        'All Function Units';

    if (!empty($functionUnitId) && isset($functionUnits)) {

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
    | TECHNICIAN
    |--------------------------------------------------------------------------
    */

    $selectedTechnician =
        !empty($technician)
            ? $technician
            : 'All Technicians';


    /*
    |--------------------------------------------------------------------------
    | DATE RANGE
    |--------------------------------------------------------------------------
    */

    $fromDateLabel =
        !empty($fromDate)
            ? \Carbon\Carbon::parse($fromDate)->format('d M Y')
            : 'All Dates';

    $toDateLabel =
        !empty($toDate)
            ? \Carbon\Carbon::parse($toDate)->format('d M Y')
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
        aria-label="Print repair report"
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

                    <td class="header-logo-cell">

                        @if($logoBase64)

                            <img
                                src="{{ $logoBase64 }}"
                                alt="Ceylon Petroleum Corporation"
                                class="header-logo"
                            >

                        @endif

                    </td>


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
                            Official repair management and asset service report
                        </div>

                    </td>


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

                    <tr>

                        <td class="criteria-label">
                            Status
                        </td>

                        <td class="criteria-value">
                            {{ $selectedStatus }}
                        </td>

                        <td class="criteria-label">
                            Employee
                        </td>

                        <td class="criteria-value">
                            {{ $selectedEmployee }}
                        </td>

                        <td class="criteria-label">
                            Item Type
                        </td>

                        <td class="criteria-value">
                            {{ $selectedItemType }}
                        </td>

                    </tr>

                    <tr>

                        <td class="criteria-label">
                            Branch
                        </td>

                        <td class="criteria-value">
                            {{ $selectedBranch }}
                        </td>

                        <td class="criteria-label">
                            Department
                        </td>

                        <td class="criteria-value">
                            {{ $selectedDepartment }}
                        </td>

                        <td class="criteria-label">
                            Date Range
                        </td>

                        <td class="criteria-value">
                            {{ $fromDateLabel }}
                            —
                            {{ $toDateLabel }}
                        </td>

                    </tr>

                    @if(
                        (!empty($functionUnitId) && isset($functionUnits))
                        || !empty($technician)
                    )

                        <tr>

                            <td class="criteria-label">
                                Function Unit
                            </td>

                            <td class="criteria-value">
                                {{ $selectedFunctionUnit }}
                            </td>

                            <td class="criteria-label">
                                Technician
                            </td>

                            <td class="criteria-value">
                                {{ $selectedTechnician }}
                            </td>

                            <td class="criteria-label">
                                Records
                            </td>

                            <td class="criteria-value">
                                {{ number_format($totalRepairs) }}
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
                    Repair Summary
                </div>

                <div class="section-subtitle">
                    Key repair activity and cost indicators
                </div>

            </div>


            <table class="summary-grid">

                <tr>

                    <td>

                        <span class="summary-label">
                            Total Repairs
                        </span>

                        <span class="summary-value">
                            {{ number_format($totalRepairs) }}
                        </span>

                        <span class="summary-note">
                            Records in report
                        </span>

                    </td>


                    <td>

                        <span class="summary-label">
                            Pending
                        </span>

                        <span class="summary-value">
                            {{ number_format($pendingRepairs) }}
                        </span>

                        <span class="summary-note">
                            Awaiting action
                        </span>

                    </td>


                    <td>

                        <span class="summary-label">
                            Sent / Under Repair
                        </span>

                        <span class="summary-value">
                            {{ number_format($sentForRepair + $underRepair) }}
                        </span>

                        <span class="summary-note">
                            Active repair process
                        </span>

                    </td>


                    <td>

                        <span class="summary-label">
                            Completed / Returned
                        </span>

                        <span class="summary-value">
                            {{ number_format($completedRepairs) }}
                        </span>

                        <span class="summary-note">
                            Completed service
                        </span>

                    </td>


                    <td>

                        <span class="summary-label">
                            Total Repair Cost
                        </span>

                        <span class="summary-value">
                            Rs.
                            {{ number_format($totalRepairCost, 2) }}
                        </span>

                        <span class="summary-note">
                            Recorded repair cost
                        </span>

                    </td>


                    <td>

                        <span class="summary-label">
                            Avg. Turnaround
                        </span>

                        <span class="summary-value">
                            {{ number_format($averageTurnaround, 1) }}
                            Days
                        </span>

                        <span class="summary-note">
                            Completed repairs
                        </span>

                    </td>

                </tr>

            </table>

        </div>


        {{-- =================================================
             REPAIR DETAILS
        ================================================== --}}

        <div class="section">

            <div class="section-header">

                <div class="section-title">
                    Repair Details
                </div>

                <div class="section-subtitle">
                    Detailed repair records matching the selected criteria
                </div>

            </div>


            <div class="table-wrapper">

                <table class="report-table">

                    <thead>

                        <tr>

                            <th style="width: 3%;">
                                #
                            </th>

                            <th style="width: 7%;">
                                Repair Ref.
                            </th>

                            <th style="width: 7%;">
                                Asset No.
                            </th>

                            <th style="width: 7%;">
                                Item Type
                            </th>

                            <th style="width: 8%;">
                                Employee
                            </th>

                            <th style="width: 7%;">
                                Branch
                            </th>

                            <th style="width: 8%;">
                                Department
                            </th>

                            <th style="width: 15%;">
                                Problem Description
                            </th>

                            <th style="width: 7%;">
                                Technician
                            </th>

                            <th style="width: 6%;">
                                Date Sent
                            </th>

                            <th style="width: 6%;">
                                Date Returned
                            </th>

                            <th style="width: 7%;">
                                Status
                            </th>

                            <th style="width: 7%;">
                                Cost
                            </th>

                            <th style="width: 5%;">
                                TAT
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($repairCollection as $index => $repair)

                            @php

                                /*
                                |--------------------------------------------------------------------------
                                | Status
                                |--------------------------------------------------------------------------
                                */

                                $statusClass = match ($repair->status) {

                                    'pending' =>
                                        'status-pending',

                                    'sent_for_repair' =>
                                        'status-sent',

                                    'under_repair' =>
                                        'status-under-repair',

                                    'completed' =>
                                        'status-completed',

                                    'returned' =>
                                        'status-returned',

                                    'cancelled' =>
                                        'status-cancelled',

                                    default =>
                                        'status-default',
                                };


                                $statusText =
                                    $statusLabels[$repair->status]
                                    ?? ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $repair->status ?? 'Unknown'
                                        )
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | Turnaround
                                |--------------------------------------------------------------------------
                                */

                                $turnaround = null;

                                if (
                                    $repair->date_sent &&
                                    $repair->date_returned
                                ) {

                                    $turnaround =
                                        $repair->date_sent
                                            ->diffInDays(
                                                $repair->date_returned
                                            );
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Employee
                                |--------------------------------------------------------------------------
                                */

                                $employeeName =
                                    optional($repair->employee)->name
                                    ?? optional($repair->employee)->full_name
                                    ?? '—';


                                /*
                                |--------------------------------------------------------------------------
                                | Item
                                |--------------------------------------------------------------------------
                                */

                                $item =
                                    $repair->item;


                                /*
                                |--------------------------------------------------------------------------
                                | Branch
                                |--------------------------------------------------------------------------
                                */

                                $branchName =
                                    optional($item?->branch)->name
                                    ?? optional($item?->branch)->branch_name
                                    ?? '—';


                                /*
                                |--------------------------------------------------------------------------
                                | Department
                                |--------------------------------------------------------------------------
                                */

                                $departmentName =
                                    optional($item?->department)->name
                                    ?? optional($item?->department)->department_name
                                    ?? '—';


                                /*
                                |--------------------------------------------------------------------------
                                | Item Type
                                |--------------------------------------------------------------------------
                                */

                                $itemTypeName =
                                    optional($item?->itemType)->name
                                    ?? optional($item?->itemType)->item_type
                                    ?? optional($item?->itemType)->type_name
                                    ?? '—';

                            @endphp


                            <tr>

                                {{-- # --}}

                                <td class="col-center col-bold">
                                    {{ $index + 1 }}
                                </td>


                                {{-- Repair Reference --}}

                                <td class="col-bold">
                                    {{ $repair->repair_reference ?? '—' }}
                                </td>


                                {{-- Asset Number --}}

                                <td class="col-bold">
                                    {{ $item?->asset_number ?? '—' }}
                                </td>


                                {{-- Item Type --}}

                                <td>
                                    {{ $itemTypeName }}
                                </td>


                                {{-- Employee --}}

                                <td>
                                    {{ $employeeName }}
                                </td>


                                {{-- Branch --}}

                                <td>
                                    {{ $branchName }}
                                </td>


                                {{-- Department --}}

                                <td>
                                    {{ $departmentName }}
                                </td>


                                {{-- Problem Description --}}

                                <td class="description-cell">

                                    {{ $repair->problem_description ?? '—' }}

                                </td>


                                {{-- Technician --}}

                                <td>
                                    {{ $repair->technician ?? '—' }}
                                </td>


                                {{-- Date Sent --}}

                                <td class="date-cell">

                                    @if($repair->date_sent)

                                        {{ $repair->date_sent->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Date Returned --}}

                                <td class="date-cell">

                                    @if($repair->date_returned)

                                        {{ $repair->date_returned->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Status --}}

                                <td class="col-center">

                                    <span
                                        class="status-badge {{ $statusClass }}"
                                    >
                                        {{ $statusText }}
                                    </span>

                                </td>


                                {{-- Cost --}}

                                <td class="col-right col-bold">

                                    Rs.
                                    {{ number_format(
                                        (float) ($repair->repair_cost ?? 0),
                                        2
                                    ) }}

                                </td>


                                {{-- Turnaround --}}

                                <td class="col-center">

                                    @if($turnaround !== null)

                                        {{ $turnaround }}
                                        {{ $turnaround == 1 ? 'day' : 'days' }}

                                    @else

                                        —

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="14"
                                    class="empty-state"
                                >

                                    <span class="empty-title">
                                        No Repair Records Found
                                    </span>

                                    <span class="empty-message">
                                        No repair records match the selected
                                        report criteria.
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