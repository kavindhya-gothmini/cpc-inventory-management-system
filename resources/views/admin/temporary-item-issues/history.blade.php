@extends('layouts.admin')

@section('title', 'Temporary Item Issue History | CPC Inventory Management System')

@section('page-heading', 'Temporary Item Issue History')

@section('content')

<style>
    /* =========================================================
       CPC TEMPORARY ITEM ISSUE HISTORY
    ========================================================= */

    .history-page {
        min-height: calc(100vh - 72px);
        padding: 24px;
        background: #f5f7fb;
    }

    .page-header {
        background: linear-gradient(135deg, #0d6efd, #084298);
        border-radius: 16px;
        padding: 24px 26px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(13, 110, 253, 0.16);
    }

    .page-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .page-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .page-header p {
        margin: 6px 0 0;
        font-size: 14px;
        opacity: .9;
    }

    .header-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.14);
        font-size: 24px;
    }

    .alert {
        border-radius: 10px;
        font-size: 13px;
    }

    .filter-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 20px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
    }

    .filter-label {
        display: block;
        color: #495057;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 6px;
    }

    .form-control {
        min-height: 42px;
        border-radius: 8px;
        border: 1px solid #d7dce5;
        font-size: 13px;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .2rem rgba(13,110,253,.12);
    }

    .btn {
        min-height: 42px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 15px;
    }

    .history-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .05);
    }

    .history-card-header {
        padding: 18px 22px;
        background: #fafbfc;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .history-card-header h5 {
        margin: 0;
        color: #212529;
        font-size: 16px;
        font-weight: 700;
    }

    .record-count {
        color: #6c757d;
        font-size: 12px;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        min-width: 1050px;
        margin: 0;
        border-collapse: collapse;
    }

    .history-table thead th {
        background: #f8f9fa;
        color: #495057;
        border-bottom: 1px solid #dee2e6;
        padding: 13px 14px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
        white-space: nowrap;
    }

    .history-table tbody td {
        padding: 14px;
        border-bottom: 1px solid #f0f1f3;
        color: #343a40;
        font-size: 12px;
        vertical-align: middle;
    }

    .history-table tbody tr:hover {
        background: #fafcff;
    }

    .history-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .issue-id {
        color: #0d6efd;
        font-weight: 700;
        white-space: nowrap;
    }

    .item-code {
        color: #212529;
        font-weight: 700;
        white-space: nowrap;
    }

    .item-type {
        color: #6c757d;
        font-size: 11px;
        margin-top: 2px;
    }

    .event-text {
        max-width: 180px;
        white-space: normal;
        line-height: 1.4;
    }

    .action-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .action-created {
        background: #d1e7dd;
        color: #0f5132;
    }

    .action-updated {
        background: #cfe2ff;
        color: #084298;
    }

    .action-received {
        background: #d1e7dd;
        color: #0f5132;
    }

    .action-cancelled {
        background: #f8d7da;
        color: #842029;
    }

    .action-default {
        background: #e9ecef;
        color: #495057;
    }

    .description {
        max-width: 300px;
        color: #495057;
        line-height: 1.45;
    }

    .changed-by {
        min-width: 160px;
    }

    .changed-by-name {
        color: #212529;
        font-weight: 700;
        font-size: 12px;
    }

    .changed-by-meta {
        color: #6c757d;
        font-size: 10px;
        margin-top: 3px;
    }

    .changed-at {
        min-width: 145px;
        color: #6c757d;
        font-size: 11px;
        line-height: 1.5;
    }

    .view-btn {
        min-height: 34px;
        padding: 6px 11px;
        font-size: 11px;
    }

    .empty-state {
        padding: 55px 20px;
        text-align: center;
        color: #6c757d;
    }

    .empty-state-icon {
        width: 62px;
        height: 62px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 25px;
    }

    .empty-state h5 {
        color: #495057;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .empty-state p {
        font-size: 12px;
        margin: 0;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid #edf0f2;
    }

    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        font-size: 12px;
    }

    @media (max-width: 768px) {

        .history-page {
            padding: 15px;
        }

        .page-header {
            padding: 20px;
        }

        .page-header-content {
            align-items: flex-start;
        }

        .page-header h2 {
            font-size: 20px;
        }

        .header-icon {
            width: 44px;
            height: 44px;
            font-size: 20px;
        }
    }
</style>

<div class="history-page">


{{-- =====================================================
     PAGE HEADER
====================================================== --}}
<div class="page-header">

    <div class="page-header-content">

        <div>

            <h2>
                <i class="bi bi-clock-history me-2"></i>
                Temporary Item Issue History
            </h2>

            <p>
                Audit trail of changes made to temporary item issue records.
            </p>

        </div>

        <div class="header-icon">
            <i class="bi bi-clock-history"></i>
        </div>

    </div>

</div>


{{-- =====================================================
     SEARCH / FILTER
====================================================== --}}
<div class="filter-card">

    <form
        method="GET"
        action="{{ route('admin.temporary-item-issues.history.index') }}">

        <div class="row g-3 align-items-end">

            <div class="col-lg-10 col-md-9">

                <label
                    for="search"
                    class="filter-label">

                    Search History

                </label>

                <input
                    type="text"
                    name="search"
                    id="search"
                    class="form-control"
                    value="{{ request('search') }}"
                    placeholder="Search by item code, type, event, action, description, employee name or EPF...">

            </div>

            <div class="col-lg-2 col-md-3">

                <button
                    type="submit"
                    class="btn btn-primary w-100">

                    <i class="bi bi-search me-1"></i>
                    Search

                </button>

            </div>

        </div>

    </form>

</div>


{{-- =====================================================
     ALERTS
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
            data-bs-dismiss="alert">
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
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- =====================================================
     HISTORY TABLE
====================================================== --}}
<div class="history-card">

    <div class="history-card-header">

        <h5>
            <i class="bi bi-list-check me-2"></i>
            Change History
        </h5>

        <span class="record-count">

            {{ $histories->total() }}

            {{ \Illuminate\Support\Str::plural(
                'record',
                $histories->total()
            ) }}

        </span>

    </div>


    @if($histories->count())

        <div class="table-responsive">

            <table class="history-table">

                <thead>

                    <tr>

                        <th>Issue</th>

                        <th>Temporary Item</th>

                        <th>Event</th>

                        <th>Action</th>

                        <th>Description</th>

                        <th>Changed By</th>

                        <th>Changed At</th>

                        <th>View</th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($histories as $history)

                        @php

                            /*
                             * Valid history actions:
                             *
                             * created
                             * updated
                             * received
                             * cancelled
                             */
                            $actionClass = match($history->action) {

                                'created'
                                    => 'action-created',

                                'updated'
                                    => 'action-updated',

                                'received'
                                    => 'action-received',

                                'cancelled'
                                    => 'action-cancelled',

                                default
                                    => 'action-default',

                            };

                        @endphp

                        <tr>

                            {{-- =================================================
                                 ISSUE ID
                            ================================================== --}}
                            <td>

                                @if($history->temporaryItemIssue)

                                    <span class="issue-id">
                                        #{{ $history->temporary_item_issue_id }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        Deleted
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 TEMPORARY ITEM
                            ================================================== --}}
                            <td>

                                @if($history->temporaryItemIssue)

                                    <div class="item-code">
                                        {{ $history->temporaryItemIssue->item_code }}
                                    </div>

                                    <div class="item-type">
                                        {{ $history->temporaryItemIssue->item_type }}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        Record unavailable
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 EVENT
                            ================================================== --}}
                            <td>

                                @if($history->temporaryItemIssue)

                                    <div class="event-text">
                                        {{ $history->temporaryItemIssue->event }}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACTION
                            ================================================== --}}
                            <td>

                                <span class="action-badge {{ $actionClass }}">

                                    {{ str_replace(
                                        '_',
                                        ' ',
                                        $history->action
                                    ) }}

                                </span>

                            </td>


                            {{-- =================================================
                                 DESCRIPTION
                            ================================================== --}}
                            <td>

                                <div class="description">

                                    {{ $history->description ?: '—' }}

                                </div>

                            </td>


                            {{-- =================================================
                                 CHANGED BY
                            ================================================== --}}
                            <td>

                                <div class="changed-by">

                                    @if($history->changed_by_name)

                                        <div class="changed-by-name">
                                            {{ $history->changed_by_name }}
                                        </div>

                                    @else

                                        <div class="changed-by-name text-muted">
                                            System / Unknown
                                        </div>

                                    @endif


                                    <div class="changed-by-meta">

                                        @if($history->changed_by_epf)

                                            EPF:
                                            {{ $history->changed_by_epf }}

                                        @endif


                                        @if(
                                            $history->changed_by_epf
                                            && $history->changed_by_role
                                        )

                                            ·

                                        @endif


                                        @if($history->changed_by_role)

                                            {{ ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $history->changed_by_role
                                                )
                                            ) }}

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 CHANGED AT
                            ================================================== --}}
                            <td>

                                <div class="changed-at">

                                    {{ optional(
                                        $history->changed_at
                                    )->format(
                                        'd M Y, h:i A'
                                    ) }}

                                </div>

                            </td>


                            {{-- =================================================
                                 VIEW HISTORY
                            ================================================== --}}
                            <td>

                                @if($history->temporaryItemIssue)

                                    <a
                                        href="{{ route(
                                            'admin.temporary-item-issues.history.show',
                                            $history->temporaryItemIssue
                                        ) }}"
                                        class="btn btn-outline-dark view-btn">

                                        <i class="bi bi-eye me-1"></i>
                                        History

                                    </a>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- =================================================
             PAGINATION
        ================================================== --}}
        @if($histories->hasPages())

            <div class="pagination-wrapper">

                {{ $histories->links() }}

            </div>

        @endif


    @else

        {{-- =================================================
             EMPTY STATE
        ================================================== --}}
        <div class="empty-state">

            <div class="empty-state-icon">

                <i class="bi bi-clock-history"></i>

            </div>

            <h5>
                No History Records Found
            </h5>

            <p>
                There are no temporary item issue history records
                matching your search.
            </p>

        </div>

    @endif

</div>


{{-- =====================================================
     FOOTER
====================================================== --}}
<div class="mt-4">

    <a
        href="{{ route('admin.temporary-item-issues.index') }}"
        class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left me-1"></i>
        Back to Temporary Item Issues

    </a>

</div>


</div>

@endsection
