@extends('layouts.admin')

@section('title', 'Temporary Item Issue | CPC Inventory Management System')

@section('page-heading', 'Temporary Item Issue')

@section('content')

<style>
    /* =========================================================
       CPC TEMPORARY ITEM ISSUE
    ========================================================= */

    .temporary-page {
        min-height: calc(100vh - 72px);
        padding: 24px;
        background: #f5f7fb;
    }

    .temporary-header {
        background: linear-gradient(135deg, #0d6efd, #084298);
        border-radius: 16px;
        padding: 24px 26px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(13, 110, 253, 0.16);
    }

    .temporary-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .temporary-header p {
        margin: 6px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .action-bar {
        background: #fff;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
    }

    .search-form {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-form .search-box {
        flex: 1;
        min-width: 240px;
    }

    .form-control,
    .form-select {
        border-radius: 9px;
        min-height: 42px;
        border: 1px solid #d7dce5;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }

    .btn {
        border-radius: 9px;
        font-weight: 600;
        font-size: 14px;
        min-height: 42px;
    }

    .btn-primary {
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.18);
    }

    .btn-history {
        color: #495057;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .btn-history:hover {
        background: #e9ecef;
    }

    .table-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .table-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .table-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }

    .record-count {
        font-size: 13px;
        color: #6c757d;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .temporary-table {
        width: 100%;
        min-width: 1350px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .temporary-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        white-space: nowrap;
        padding: 14px 12px;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .temporary-table tbody td {
        padding: 14px 12px;
        font-size: 13px;
        color: #343a40;
        border-bottom: 1px solid #edf0f2;
        vertical-align: middle;
    }

    .temporary-table tbody tr:hover {
        background: #f8fbff;
    }

    .temporary-table tbody tr:last-child td {
        border-bottom: none;
    }

    .item-code {
        font-weight: 700;
        color: #0d6efd;
        white-space: nowrap;
    }

    .item-type {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 7px;
        background: #eef4ff;
        color: #0d6efd;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .employee-info {
        min-width: 170px;
    }

    .employee-name {
        display: block;
        font-weight: 600;
        color: #212529;
        line-height: 1.35;
    }

    .employee-epf {
        display: block;
        margin-top: 3px;
        color: #6c757d;
        font-size: 11px;
        font-weight: 500;
    }

    .location-info {
        min-width: 150px;
    }

    .location-name {
        display: block;
        font-weight: 600;
        color: #343a40;
    }

    .location-code {
        display: block;
        margin-top: 2px;
        color: #6c757d;
        font-size: 11px;
    }

    .event-text {
        max-width: 220px;
        line-height: 1.45;
    }

    .date-time {
        white-space: nowrap;
    }

    .date-time .date {
        display: block;
        font-weight: 600;
        color: #343a40;
    }

    .date-time .time {
        display: block;
        margin-top: 2px;
        font-size: 11px;
        color: #6c757d;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-received {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #842029;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-buttons .btn {
        min-height: 34px;
        padding: 5px 9px;
        font-size: 12px;
    }

    .empty-state {
        text-align: center;
        padding: 70px 20px;
        color: #6c757d;
    }

    .empty-state-icon {
        font-size: 42px;
        margin-bottom: 14px;
        opacity: 0.45;
    }

    .empty-state h5 {
        font-size: 17px;
        font-weight: 700;
        color: #495057;
        margin-bottom: 7px;
    }

    .empty-state p {
        margin-bottom: 18px;
        font-size: 14px;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid #edf0f2;
    }

    .alert {
        border-radius: 10px;
        font-size: 14px;
    }

    @media (max-width: 768px) {

        .temporary-page {
            padding: 15px;
        }

        .temporary-header {
            padding: 20px;
        }

        .temporary-header h2 {
            font-size: 20px;
        }

        .search-form {
            display: block;
        }

        .search-form .search-box {
            margin-bottom: 10px;
        }

        .search-form .btn {
            width: 100%;
            margin-bottom: 8px;
        }
    }
</style>

<div class="temporary-page">

@extends('layouts.admin')

@section('title', 'Temporary Item Issue | CPC Inventory Management System')

@section('page-heading', 'Temporary Item Issue')

@section('content')

<style>
    /* =========================================================
       CPC TEMPORARY ITEM ISSUE
    ========================================================= */

    .temporary-page {
        min-height: calc(100vh - 72px);
        padding: 24px;
        background: #f5f7fb;
    }

    .temporary-header {
        background: linear-gradient(135deg, #0d6efd, #084298);
        border-radius: 16px;
        padding: 24px 26px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(13, 110, 253, 0.16);
    }

    .temporary-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .temporary-header p {
        margin: 6px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .action-bar {
        background: #fff;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
    }

    .search-form {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-form .search-box {
        flex: 1;
        min-width: 240px;
    }

    .form-control,
    .form-select {
        border-radius: 9px;
        min-height: 42px;
        border: 1px solid #d7dce5;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }

    .btn {
        border-radius: 9px;
        font-weight: 600;
        font-size: 14px;
        min-height: 42px;
    }

    .btn-primary {
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.18);
    }

    .btn-history {
        color: #495057;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .btn-history:hover {
        background: #e9ecef;
    }

    .table-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .table-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .table-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }

    .record-count {
        font-size: 13px;
        color: #6c757d;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .temporary-table {
        width: 100%;
        min-width: 1350px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .temporary-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        white-space: nowrap;
        padding: 14px 12px;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .temporary-table tbody td {
        padding: 14px 12px;
        font-size: 13px;
        color: #343a40;
        border-bottom: 1px solid #edf0f2;
        vertical-align: middle;
    }

    .temporary-table tbody tr:hover {
        background: #f8fbff;
    }

    .temporary-table tbody tr:last-child td {
        border-bottom: none;
    }

    .item-code {
        font-weight: 700;
        color: #0d6efd;
        white-space: nowrap;
    }

    .item-type {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 7px;
        background: #eef4ff;
        color: #0d6efd;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .employee-info {
        min-width: 170px;
    }

    .employee-name {
        display: block;
        font-weight: 600;
        color: #212529;
        line-height: 1.35;
    }

    .employee-epf {
        display: block;
        margin-top: 3px;
        color: #6c757d;
        font-size: 11px;
        font-weight: 500;
    }

    .location-info {
        min-width: 150px;
    }

    .location-name {
        display: block;
        font-weight: 600;
        color: #343a40;
    }

    .location-code {
        display: block;
        margin-top: 2px;
        color: #6c757d;
        font-size: 11px;
    }

    .event-text {
        max-width: 220px;
        line-height: 1.45;
    }

    .date-time {
        white-space: nowrap;
    }

    .date-time .date {
        display: block;
        font-weight: 600;
        color: #343a40;
    }

    .date-time .time {
        display: block;
        margin-top: 2px;
        font-size: 11px;
        color: #6c757d;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-received {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #842029;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-buttons .btn {
        min-height: 34px;
        padding: 5px 9px;
        font-size: 12px;
    }

    .empty-state {
        text-align: center;
        padding: 70px 20px;
        color: #6c757d;
    }

    .empty-state-icon {
        font-size: 42px;
        margin-bottom: 14px;
        opacity: 0.45;
    }

    .empty-state h5 {
        font-size: 17px;
        font-weight: 700;
        color: #495057;
        margin-bottom: 7px;
    }

    .empty-state p {
        margin-bottom: 18px;
        font-size: 14px;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid #edf0f2;
    }

    .alert {
        border-radius: 10px;
        font-size: 14px;
    }

    @media (max-width: 768px) {

        .temporary-page {
            padding: 15px;
        }

        .temporary-header {
            padding: 20px;
        }

        .temporary-header h2 {
            font-size: 20px;
        }

        .search-form {
            display: block;
        }

        .search-form .search-box {
            margin-bottom: 10px;
        }

        .search-form .btn {
            width: 100%;
            margin-bottom: 8px;
        }
    }
</style>

<div class="temporary-page">


{{-- =====================================================
     PAGE HEADER
====================================================== --}}
<div class="temporary-header">

    <h2>
        <i class="bi bi-arrow-left-right me-2"></i>
        Temporary Item Issue
    </h2>

    <p>
        Manage temporary PC, projector and laptop issues,
        receiving and returns.
    </p>

</div>


{{-- =====================================================
     ALERT MESSAGES
====================================================== --}}
@if(session('success'))

    <div
        class="alert alert-success alert-dismissible fade show"
        role="alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>

@endif


@if(session('error'))

    <div
        class="alert alert-danger alert-dismissible fade show"
        role="alert">

        <i class="bi bi-exclamation-triangle-fill me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>

@endif


{{-- =====================================================
     SEARCH / FILTER / ACTION BAR
====================================================== --}}
<div class="action-bar">

    <form
        method="GET"
        action="{{ route('admin.temporary-item-issues.index') }}"
        class="search-form">

        <div class="search-box">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search item code, type, event, employee name or EPF number...">

        </div>


        <div>

            <select
                name="status"
                class="form-select">

                <option value="">
                    All Statuses
                </option>

                <option
                    value="pending"
                    {{ request('status') === 'pending' ? 'selected' : '' }}>

                    Pending

                </option>

                <option
                    value="received"
                    {{ request('status') === 'received' ? 'selected' : '' }}>

                    Received

                </option>

                <option
                    value="cancelled"
                    {{ request('status') === 'cancelled' ? 'selected' : '' }}>

                    Cancelled

                </option>

            </select>

        </div>


        <button
            type="submit"
            class="btn btn-primary">

            <i class="bi bi-search me-1"></i>
            Search

        </button>


        @if(
            request()->filled('search')
            || request()->filled('status')
        )

            <a
                href="{{ route('admin.temporary-item-issues.index') }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-x-circle me-1"></i>
                Clear

            </a>

        @endif


        <a
            href="{{ route(
                'admin.temporary-item-issues.history.index'
            ) }}"
            class="btn btn-history">

            <i class="bi bi-clock-history me-1"></i>
            History

        </a>


        <a
            href="{{ route(
                'admin.temporary-item-issues.create'
            ) }}"
            class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            New Temporary Issue

        </a>

    </form>

</div>


{{-- =====================================================
     TABLE
====================================================== --}}
<div class="table-card">

    <div class="table-card-header">

        <h5>

            <i class="bi bi-list-ul me-2"></i>

            Temporary Item Issue Records

        </h5>


        <span class="record-count">

            {{ $temporaryIssues->total() }}

            {{ Str::plural(
                'record',
                $temporaryIssues->total()
            ) }}

        </span>

    </div>


    <div class="table-responsive">

        @if($temporaryIssues->count())

            <table class="temporary-table">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>
                            Issue Date & Time
                        </th>

                        <th>
                            Item Code
                        </th>

                        <th>
                            Item Type
                        </th>

                        <th>
                            Department / Function Unit
                        </th>

                        <th>
                            Event
                        </th>

                        <th>
                            Requesting Employee
                        </th>

                        <th>
                            Receiving Employee
                        </th>

                        <th>
                            Received Date & Time
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($temporaryIssues as $temporaryIssue)

                        <tr>

                            {{-- =================================================
                                 NUMBER
                            ================================================== --}}
                            <td>

                                {{ $temporaryIssues->firstItem() + $loop->index }}

                            </td>


                            {{-- =================================================
                                 ISSUE DATE / TIME
                            ================================================== --}}
                            <td>

                                <div class="date-time">

                                    <span class="date">

                                        {{ optional(
                                            $temporaryIssue->issue_datetime
                                        )->format('d M Y') }}

                                    </span>

                                    <span class="time">

                                        {{ optional(
                                            $temporaryIssue->issue_datetime
                                        )->format('h:i A') }}

                                    </span>

                                </div>

                            </td>


                            {{-- =================================================
                                 ITEM CODE
                            ================================================== --}}
                            <td>

                                <span class="item-code">

                                    {{ $temporaryIssue->item_code }}

                                </span>

                            </td>


                            {{-- =================================================
                                 ITEM TYPE
                            ================================================== --}}
                            <td>

                                <span class="item-type">

                                    {{ $temporaryIssue->item_type }}

                                </span>

                            </td>


                            {{-- =================================================
                                 DEPARTMENT / FUNCTION UNIT
                            ================================================== --}}
                            <td>

                                <div class="location-info">

                                    @if($temporaryIssue->department)

                                        <span class="location-name">

                                            {{ $temporaryIssue->department->name }}

                                        </span>


                                        @if($temporaryIssue->department->code)

                                            <span class="location-code">

                                                Department:
                                                {{ $temporaryIssue->department->code }}

                                            </span>

                                        @endif

                                    @endif


                                    @if($temporaryIssue->functionUnit)

                                        <span class="location-name mt-1">

                                            {{ $temporaryIssue->functionUnit->name }}

                                        </span>


                                        @if($temporaryIssue->functionUnit->code)

                                            <span class="location-code">

                                                Function Unit:
                                                {{ $temporaryIssue->functionUnit->code }}

                                            </span>

                                        @endif

                                    @endif


                                    @if(
                                        !$temporaryIssue->department
                                        && !$temporaryIssue->functionUnit
                                    )

                                        <span class="text-muted">

                                            Not specified

                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                 EVENT
                            ================================================== --}}
                            <td>

                                <div class="event-text">

                                    {{ $temporaryIssue->event }}

                                </div>

                            </td>


                            {{-- =================================================
                                 REQUESTING EMPLOYEE
                            ================================================== --}}
                            <td>

                                @if($temporaryIssue->requestingEmployee)

                                    <div class="employee-info">

                                        <span class="employee-name">

                                            {{ $temporaryIssue->requestingEmployee->name }}

                                        </span>

                                        <span class="employee-epf">

                                            EPF:
                                            {{ $temporaryIssue->requestingEmployee->epf_no }}

                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">

                                        Not available

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 RECEIVING EMPLOYEE
                            ================================================== --}}
                            <td>

                                @if($temporaryIssue->receivingEmployee)

                                    <div class="employee-info">

                                        <span class="employee-name">

                                            {{ $temporaryIssue->receivingEmployee->name }}

                                        </span>

                                        <span class="employee-epf">

                                            EPF:
                                            {{ $temporaryIssue->receivingEmployee->epf_no }}

                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">

                                        Not received yet

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 RECEIVED DATE / TIME
                            ================================================== --}}
                            <td>

                                @if($temporaryIssue->received_datetime)

                                    <div class="date-time">

                                        <span class="date">

                                            {{ $temporaryIssue->received_datetime->format(
                                                'd M Y'
                                            ) }}

                                        </span>

                                        <span class="time">

                                            {{ $temporaryIssue->received_datetime->format(
                                                'h:i A'
                                            ) }}

                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">

                                        Not received

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            <td>

                                @switch($temporaryIssue->status)

                                    @case('pending')

                                        <span class="status-badge status-pending">

                                            <span class="status-dot"></span>

                                            Pending

                                        </span>

                                        @break


                                    @case('received')

                                        <span class="status-badge status-received">

                                            <span class="status-dot"></span>

                                            Received

                                        </span>

                                        @break


                                    @case('cancelled')

                                        <span class="status-badge status-cancelled">

                                            <span class="status-dot"></span>

                                            Cancelled

                                        </span>

                                        @break


                                    @default

                                        <span class="status-badge">

                                            {{ ucfirst(
                                                $temporaryIssue->status
                                            ) }}

                                        </span>

                                @endswitch

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- View --}}
                                    <a
                                        href="{{ route(
                                            'admin.temporary-item-issues.show',
                                            $temporaryIssue
                                        ) }}"
                                        class="btn btn-outline-primary"
                                        title="View">

                                        <i class="bi bi-eye"></i>

                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route(
                                            'admin.temporary-item-issues.edit',
                                            $temporaryIssue
                                        ) }}"
                                        class="btn btn-outline-secondary"
                                        title="Edit">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- History --}}
                                    <a
                                        href="{{ route(
                                            'admin.temporary-item-issues.history.show',
                                            $temporaryIssue
                                        ) }}"
                                        class="btn btn-outline-dark"
                                        title="History">

                                        <i class="bi bi-clock-history"></i>

                                    </a>


                                    {{-- =================================================
                                         RECEIVE
                                    ================================================== --}}
                                    @if(
                                        $temporaryIssue->status === 'pending'
                                    )

                                        <a
                                            href="{{ route(
                                                'admin.temporary-item-issues.show',
                                                $temporaryIssue
                                            ) }}#receive-item"
                                            class="btn btn-outline-success"
                                            title="Receive">

                                            <i class="bi bi-box-arrow-in-down"></i>

                                        </a>

                                    @endif


                                    {{-- =================================================
                                         CANCEL
                                    ================================================== --}}
                                    @if(
                                        $temporaryIssue->status === 'pending'
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.temporary-item-issues.cancel',
                                                $temporaryIssue
                                            ) }}"
                                            class="d-inline">

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger"
                                                title="Cancel"
                                                onclick="return confirm(
                                                    'Are you sure you want to cancel this temporary item issue?'
                                                )">

                                                <i class="bi bi-x-circle"></i>

                                            </button>

                                        </form>

                                    @endif


                                    {{-- =================================================
                                         DELETE
                                    ================================================== --}}
                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.temporary-item-issues.destroy',
                                            $temporaryIssue
                                        ) }}"
                                        class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger"
                                            title="Delete"
                                            onclick="return confirm(
                                                'Are you sure you want to permanently delete this temporary item issue?'
                                            )">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}
            <div class="empty-state">

                <div class="empty-state-icon">

                    <i class="bi bi-inbox"></i>

                </div>

                <h5>

                    No Temporary Item Issues Found

                </h5>


                @if(
                    request()->filled('search')
                    || request()->filled('status')
                )

                    <p>

                        No records match your current search or filter.

                    </p>


                    <a
                        href="{{ route(
                            'admin.temporary-item-issues.index'
                        ) }}"
                        class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-counterclockwise me-1"></i>

                        Clear Filters

                    </a>

                @else

                    <p>

                        No temporary item issue records have been
                        created yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.temporary-item-issues.create'
                        ) }}"
                        class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Create First Issue

                    </a>

                @endif

            </div>

        @endif

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}
    @if($temporaryIssues->hasPages())

        <div class="pagination-wrapper">

            {{ $temporaryIssues->links() }}

        </div>

    @endif

</div>


</div>

@endsection

{{-- =====================================================
     PAGE HEADER
====================================================== --}}
<div class="temporary-header">

    <h2>
        <i class="bi bi-arrow-left-right me-2"></i>
        Temporary Item Issue
    </h2>

    <p>
        Manage temporary PC, projector and laptop issues,
        receiving and returns.
    </p>

</div>


{{-- =====================================================
     ALERT MESSAGES
====================================================== --}}
@if(session('success'))

    <div
        class="alert alert-success alert-dismissible fade show"
        role="alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>

@endif


@if(session('error'))

    <div
        class="alert alert-danger alert-dismissible fade show"
        role="alert">

        <i class="bi bi-exclamation-triangle-fill me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>

@endif


{{-- =====================================================
     SEARCH / FILTER / ACTION BAR
====================================================== --}}
<div class="action-bar">

    <form
        method="GET"
        action="{{ route('admin.temporary-item-issues.index') }}"
        class="search-form">

        <div class="search-box">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search item code, type, event, employee name or EPF number...">

        </div>


        <div>

            <select
                name="status"
                class="form-select">

                <option value="">
                    All Statuses
                </option>

                <option
                    value="pending"
                    {{ request('status') === 'pending' ? 'selected' : '' }}>

                    Pending

                </option>

                <option
                    value="received"
                    {{ request('status') === 'received' ? 'selected' : '' }}>

                    Received

                </option>

                <option
                    value="cancelled"
                    {{ request('status') === 'cancelled' ? 'selected' : '' }}>

                    Cancelled

                </option>

            </select>

        </div>


        <button
            type="submit"
            class="btn btn-primary">

            <i class="bi bi-search me-1"></i>
            Search

        </button>


        @if(
            request()->filled('search')
            || request()->filled('status')
        )

            <a
                href="{{ route('admin.temporary-item-issues.index') }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-x-circle me-1"></i>
                Clear

            </a>

        @endif


        <a
            href="{{ route(
                'admin.temporary-item-issues.history.index'
            ) }}"
            class="btn btn-history">

            <i class="bi bi-clock-history me-1"></i>
            History

        </a>


        <a
            href="{{ route(
                'admin.temporary-item-issues.create'
            ) }}"
            class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            New Temporary Issue

        </a>

    </form>

</div>


{{-- =====================================================
     TABLE
====================================================== --}}
<div class="table-card">

    <div class="table-card-header">

        <h5>

            <i class="bi bi-list-ul me-2"></i>

            Temporary Item Issue Records

        </h5>


        <span class="record-count">

            {{ $temporaryIssues->total() }}

            {{ Str::plural(
                'record',
                $temporaryIssues->total()
            ) }}

        </span>

    </div>


    <div class="table-responsive">

        @if($temporaryIssues->count())

            <table class="temporary-table">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>
                            Issue Date & Time
                        </th>

                        <th>
                            Item Code
                        </th>

                        <th>
                            Item Type
                        </th>

                        <th>
                            Department / Function Unit
                        </th>

                        <th>
                            Event
                        </th>

                        <th>
                            Requesting Employee
                        </th>

                        <th>
                            Receiving Employee
                        </th>

                        <th>
                            Received Date & Time
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($temporaryIssues as $temporaryIssue)

                        <tr>

                            {{-- =================================================
                                 NUMBER
                            ================================================== --}}
                            <td>

                                {{ $temporaryIssues->firstItem() + $loop->index }}

                            </td>


                            {{-- =================================================
                                 ISSUE DATE / TIME
                            ================================================== --}}
                            <td>

                                <div class="date-time">

                                    <span class="date">

                                        {{ optional(
                                            $temporaryIssue->issue_datetime
                                        )->format('d M Y') }}

                                    </span>

                                    <span class="time">

                                        {{ optional(
                                            $temporaryIssue->issue_datetime
                                        )->format('h:i A') }}

                                    </span>

                                </div>

                            </td>


                            {{-- =================================================
                                 ITEM CODE
                            ================================================== --}}
                            <td>

                                <span class="item-code">

                                    {{ $temporaryIssue->item_code }}

                                </span>

                            </td>


                            {{-- =================================================
                                 ITEM TYPE
                            ================================================== --}}
                            <td>

                                <span class="item-type">

                                    {{ $temporaryIssue->item_type }}

                                </span>

                            </td>


                            {{-- =================================================
                                 DEPARTMENT / FUNCTION UNIT
                            ================================================== --}}
                            <td>

                                <div class="location-info">

                                    @if($temporaryIssue->department)

                                        <span class="location-name">

                                            {{ $temporaryIssue->department->name }}

                                        </span>


                                        @if($temporaryIssue->department->code)

                                            <span class="location-code">

                                                Department:
                                                {{ $temporaryIssue->department->code }}

                                            </span>

                                        @endif

                                    @endif


                                    @if($temporaryIssue->functionUnit)

                                        <span class="location-name mt-1">

                                            {{ $temporaryIssue->functionUnit->name }}

                                        </span>


                                        @if($temporaryIssue->functionUnit->code)

                                            <span class="location-code">

                                                Function Unit:
                                                {{ $temporaryIssue->functionUnit->code }}

                                            </span>

                                        @endif

                                    @endif


                                    @if(
                                        !$temporaryIssue->department
                                        && !$temporaryIssue->functionUnit
                                    )

                                        <span class="text-muted">

                                            Not specified

                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                 EVENT
                            ================================================== --}}
                            <td>

                                <div class="event-text">

                                    {{ $temporaryIssue->event }}

                                </div>

                            </td>


                            {{-- =================================================
                                 REQUESTING EMPLOYEE
                            ================================================== --}}
                            <td>

                                @if($temporaryIssue->requestingEmployee)

                                    <div class="employee-info">

                                        <span class="employee-name">

                                            {{ $temporaryIssue->requestingEmployee->name }}

                                        </span>

                                        <span class="employee-epf">

                                            EPF:
                                            {{ $temporaryIssue->requestingEmployee->epf_no }}

                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">

                                        Not available

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 RECEIVING EMPLOYEE
                            ================================================== --}}
                            <td>

                                @if($temporaryIssue->receivingEmployee)

                                    <div class="employee-info">

                                        <span class="employee-name">

                                            {{ $temporaryIssue->receivingEmployee->name }}

                                        </span>

                                        <span class="employee-epf">

                                            EPF:
                                            {{ $temporaryIssue->receivingEmployee->epf_no }}

                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">

                                        Not received yet

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 RECEIVED DATE / TIME
                            ================================================== --}}
                            <td>

                                @if($temporaryIssue->received_datetime)

                                    <div class="date-time">

                                        <span class="date">

                                            {{ $temporaryIssue->received_datetime->format(
                                                'd M Y'
                                            ) }}

                                        </span>

                                        <span class="time">

                                            {{ $temporaryIssue->received_datetime->format(
                                                'h:i A'
                                            ) }}

                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">

                                        Not received

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            <td>

                                @switch($temporaryIssue->status)

                                    @case('pending')

                                        <span class="status-badge status-pending">

                                            <span class="status-dot"></span>

                                            Pending

                                        </span>

                                        @break


                                    @case('received')

                                        <span class="status-badge status-received">

                                            <span class="status-dot"></span>

                                            Received

                                        </span>

                                        @break


                                    @case('cancelled')

                                        <span class="status-badge status-cancelled">

                                            <span class="status-dot"></span>

                                            Cancelled

                                        </span>

                                        @break


                                    @default

                                        <span class="status-badge">

                                            {{ ucfirst(
                                                $temporaryIssue->status
                                            ) }}

                                        </span>

                                @endswitch

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- View --}}
                                    <a
                                        href="{{ route(
                                            'admin.temporary-item-issues.show',
                                            $temporaryIssue
                                        ) }}"
                                        class="btn btn-outline-primary"
                                        title="View">

                                        <i class="bi bi-eye"></i>

                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route(
                                            'admin.temporary-item-issues.edit',
                                            $temporaryIssue
                                        ) }}"
                                        class="btn btn-outline-secondary"
                                        title="Edit">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- History --}}
                                    <a
                                        href="{{ route(
                                            'admin.temporary-item-issues.history.show',
                                            $temporaryIssue
                                        ) }}"
                                        class="btn btn-outline-dark"
                                        title="History">

                                        <i class="bi bi-clock-history"></i>

                                    </a>


                                    {{-- =================================================
                                         RECEIVE
                                    ================================================== --}}
                                    @if(
                                        $temporaryIssue->status === 'pending'
                                    )

                                        <a
                                            href="{{ route(
                                                'admin.temporary-item-issues.show',
                                                $temporaryIssue
                                            ) }}#receive-item"
                                            class="btn btn-outline-success"
                                            title="Receive">

                                            <i class="bi bi-box-arrow-in-down"></i>

                                        </a>

                                    @endif


                                    {{-- =================================================
                                         CANCEL
                                    ================================================== --}}
                                    @if(
                                        $temporaryIssue->status === 'pending'
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.temporary-item-issues.cancel',
                                                $temporaryIssue
                                            ) }}"
                                            class="d-inline">

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger"
                                                title="Cancel"
                                                onclick="return confirm(
                                                    'Are you sure you want to cancel this temporary item issue?'
                                                )">

                                                <i class="bi bi-x-circle"></i>

                                            </button>

                                        </form>

                                    @endif


                                    {{-- =================================================
                                         DELETE
                                    ================================================== --}}
                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.temporary-item-issues.destroy',
                                            $temporaryIssue
                                        ) }}"
                                        class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger"
                                            title="Delete"
                                            onclick="return confirm(
                                                'Are you sure you want to permanently delete this temporary item issue?'
                                            )">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}
            <div class="empty-state">

                <div class="empty-state-icon">

                    <i class="bi bi-inbox"></i>

                </div>

                <h5>

                    No Temporary Item Issues Found

                </h5>


                @if(
                    request()->filled('search')
                    || request()->filled('status')
                )

                    <p>

                        No records match your current search or filter.

                    </p>


                    <a
                        href="{{ route(
                            'admin.temporary-item-issues.index'
                        ) }}"
                        class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-counterclockwise me-1"></i>

                        Clear Filters

                    </a>

                @else

                    <p>

                        No temporary item issue records have been
                        created yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.temporary-item-issues.create'
                        ) }}"
                        class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Create First Issue

                    </a>

                @endif

            </div>

        @endif

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}
    @if($temporaryIssues->hasPages())

        <div class="pagination-wrapper">

            {{ $temporaryIssues->links() }}

        </div>

    @endif

</div>


</div>

@endsection
