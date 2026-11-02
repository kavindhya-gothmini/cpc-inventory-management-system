@extends('layouts.admin')

@section('title', 'Temporary Item Issue History | CPC Inventory Management System')

@section('page-heading', 'Temporary Item Issue History')

@section('content')

<style>
    /* =========================================================
       CPC TEMPORARY ITEM ISSUE HISTORY SHOW
    ========================================================= */

    .history-page {
        min-height: calc(100vh - 72px);
        padding: 24px;
        background:
            linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
    }

    .history-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .history-title {
        margin: 0;
        color: #123b6d;
        font-size: 28px;
        font-weight: 700;
    }

    .history-subtitle {
        margin: 7px 0 0;
        color: #6c7a89;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-cpc {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 9px 16px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-primary-cpc {
        color: #fff;
        background: linear-gradient(135deg, #0d6efd, #084298);
        box-shadow: 0 5px 14px rgba(13, 110, 253, 0.18);
    }

    .btn-primary-cpc:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(13, 110, 253, 0.25);
    }

    .btn-light-cpc {
        color: #23476d;
        background: #fff;
        border-color: #d9e4f0;
    }

    .btn-light-cpc:hover {
        color: #0d6efd;
        background: #f5f9ff;
        border-color: #bcd2eb;
    }

    .history-card {
        background: #fff;
        border: 1px solid #e1eaf3;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(30, 70, 110, 0.07);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .card-heading {
        padding: 18px 22px;
        border-bottom: 1px solid #e8eef5;
        background: linear-gradient(180deg, #ffffff, #f9fbfe);
    }

    .card-heading h5 {
        margin: 0;
        color: #173f6f;
        font-size: 17px;
        font-weight: 700;
    }

    .card-heading p {
        margin: 5px 0 0;
        color: #7b8794;
        font-size: 13px;
    }

    .issue-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        padding: 22px;
    }

    .summary-item {
        padding: 16px;
        background: #f8fbff;
        border: 1px solid #e2ebf5;
        border-radius: 11px;
    }

    .summary-label {
        display: block;
        margin-bottom: 6px;
        color: #7b8794;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .summary-value {
        color: #183b63;
        font-size: 15px;
        font-weight: 700;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .status-pending {
        color: #8a5a00;
        background: #fff4d6;
    }

    .status-received {
        color: #166534;
        background: #dcfce7;
    }

    .status-cancelled {
        color: #991b1b;
        background: #fee2e2;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .history-table th {
        padding: 14px 16px;
        background: #f6f9fc;
        border-bottom: 1px solid #dfe8f1;
        color: #4e6175;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }

    .history-table td {
        padding: 16px;
        border-bottom: 1px solid #edf1f5;
        color: #405366;
        font-size: 14px;
        vertical-align: top;
    }

    .history-table tbody tr:hover {
        background: #f9fcff;
    }

    .history-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .action-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 7px;
        background: #eaf3ff;
        color: #0d5ec7;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .description {
        max-width: 360px;
        line-height: 1.55;
    }

    .user-name {
        color: #183b63;
        font-weight: 700;
    }

    .user-meta {
        margin-top: 3px;
        color: #7b8794;
        font-size: 12px;
    }

    .date-main {
        color: #334e68;
        font-weight: 600;
    }

    .date-sub {
        margin-top: 3px;
        color: #8a97a5;
        font-size: 12px;
    }

    .empty-history {
        padding: 55px 20px;
        text-align: center;
        color: #7b8794;
    }

    .empty-history-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #edf5ff;
        color: #0d6efd;
        font-size: 25px;
    }

    .empty-history h5 {
        margin-bottom: 6px;
        color: #34495e;
        font-weight: 700;
    }

    .empty-history p {
        margin: 0;
        font-size: 14px;
    }

    .pagination-wrapper {
        padding: 18px 22px;
        border-top: 1px solid #e8eef5;
        background: #fbfdff;
    }

    .remarks-box {
        padding: 18px 22px;
        color: #526577;
        font-size: 14px;
        line-height: 1.7;
    }

    @media (max-width: 1100px) {
        .issue-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .history-page {
            padding: 15px;
        }

        .history-title {
            font-size: 23px;
        }

        .issue-summary {
            grid-template-columns: 1fr;
            padding: 16px;
        }

        .card-heading {
            padding: 16px;
        }

        .remarks-box {
            padding: 16px;
        }
    }
</style>

<div class="history-page">


<div class="history-container">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="history-header">

        <div>

            <h1 class="history-title">
                Temporary Item Issue History
            </h1>

            <p class="history-subtitle">
                Complete activity history for temporary item
                <strong>{{ $temporaryItemIssue->item_code }}</strong>
            </p>

        </div>

        <div class="header-actions">

            <a
                href="{{ route(
                    'admin.temporary-item-issues.show',
                    $temporaryItemIssue
                ) }}"
                class="btn-cpc btn-light-cpc">

                <i class="bi bi-arrow-left"></i>
                Back to Issue

            </a>

            <a
                href="{{ route(
                    'admin.temporary-item-issues.history.index'
                ) }}"
                class="btn-cpc btn-primary-cpc">

                <i class="bi bi-clock-history"></i>
                All History

            </a>

        </div>

    </div>


    {{-- =====================================================
         ISSUE SUMMARY
    ====================================================== --}}
    <div class="history-card">

        <div class="card-heading">

            <h5>
                Temporary Item Issue Details
            </h5>

            <p>
                Reference information for this temporary item issue.
            </p>

        </div>


        <div class="issue-summary">

            {{-- Item Code --}}
            <div class="summary-item">

                <span class="summary-label">
                    Item Code
                </span>

                <div class="summary-value">
                    {{ $temporaryItemIssue->item_code }}
                </div>

            </div>


            {{-- Item Type --}}
            <div class="summary-item">

                <span class="summary-label">
                    Item Type
                </span>

                <div class="summary-value">
                    {{ $temporaryItemIssue->item_type }}
                </div>

            </div>


            {{-- Event --}}
            <div class="summary-item">

                <span class="summary-label">
                    Event
                </span>

                <div class="summary-value">
                    {{ $temporaryItemIssue->event }}
                </div>

            </div>


            {{-- Current Status --}}
            <div class="summary-item">

                <span class="summary-label">
                    Current Status
                </span>

                <div class="summary-value">

                    @php
                        $statusClass = match ($temporaryItemIssue->status) {
                            'pending' => 'status-pending',
                            'received' => 'status-received',
                            'cancelled' => 'status-cancelled',
                            default => 'status-pending',
                        };
                    @endphp

                    <span class="status-badge {{ $statusClass }}">
                        {{ str_replace(
                            '_',
                            ' ',
                            $temporaryItemIssue->status
                        ) }}
                    </span>

                </div>

            </div>


            {{-- Issue Date & Time --}}
            <div class="summary-item">

                <span class="summary-label">
                    Issue Date & Time
                </span>

                <div class="summary-value">
                    {{ $temporaryItemIssue->issue_datetime?->format('d M Y, h:i A') ?? '—' }}
                </div>

            </div>


            {{-- Department --}}
            <div class="summary-item">

                <span class="summary-label">
                    Department
                </span>

                <div class="summary-value">
                    {{ $temporaryItemIssue->department?->name ?? '—' }}
                </div>

            </div>


            {{-- Function Unit --}}
            <div class="summary-item">

                <span class="summary-label">
                    Function Unit
                </span>

                <div class="summary-value">
                    {{ $temporaryItemIssue->functionUnit?->name ?? '—' }}
                </div>

            </div>


            {{-- Received Date & Time --}}
            <div class="summary-item">

                <span class="summary-label">
                    Received Date & Time
                </span>

                <div class="summary-value">

                    {{ $temporaryItemIssue->received_datetime?->format(
                        'd M Y, h:i A'
                    ) ?? 'Not received' }}

                </div>

            </div>

        </div>


        {{-- Remarks --}}
        @if($temporaryItemIssue->remarks)

            <div class="remarks-box">

                <strong>Remarks:</strong>

                {{ $temporaryItemIssue->remarks }}

            </div>

        @endif

    </div>


    {{-- =====================================================
         ACTIVITY HISTORY
    ====================================================== --}}
    <div class="history-card">

        <div class="card-heading">

            <h5>
                Activity History
            </h5>

            <p>

                {{ $histories->total() }}

                {{ \Illuminate\Support\Str::plural(
                    'record',
                    $histories->total()
                ) }}

                recorded for this temporary item issue.

            </p>

        </div>


        @if($histories->count())

            <div class="table-wrapper">

                <table class="history-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>
                                Action
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Changed By
                            </th>

                            <th>
                                Changed At
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($histories as $history)

                            <tr>

                                {{-- Number --}}
                                <td>

                                    <strong>
                                        {{ $histories->firstItem() + $loop->index }}
                                    </strong>

                                </td>


                                {{-- Action --}}
                                <td>

                                    <span class="action-badge">

                                        {{ str_replace(
                                            '_',
                                            ' ',
                                            $history->action
                                        ) }}

                                    </span>

                                </td>


                                {{-- Description --}}
                                <td>

                                    <div class="description">

                                        {{ $history->description
                                            ?? 'No description provided.' }}

                                    </div>

                                </td>


                                {{-- Changed By --}}
                                <td>

                                    <div class="user-name">

                                        {{
                                            $history->changed_by_name
                                            ?? $history->changedBy?->name
                                            ?? 'System'
                                        }}

                                    </div>


                                    @php
                                        $epf = $history->changed_by_epf
                                            ?? $history->changedBy?->epf_number;
                                    @endphp


                                    @if($epf)

                                        <div class="user-meta">

                                            EPF:
                                            {{ $epf }}

                                        </div>

                                    @endif


                                    @if($history->changed_by_role)

                                        <div class="user-meta">

                                            Role:
                                            {{ str_replace(
                                                '_',
                                                ' ',
                                                $history->changed_by_role
                                            ) }}

                                        </div>

                                    @elseif($history->changedBy?->role)

                                        <div class="user-meta">

                                            Role:
                                            {{ str_replace(
                                                '_',
                                                ' ',
                                                $history->changedBy->role
                                            ) }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Changed At --}}
                                <td>

                                    <div class="date-main">

                                        {{ $history->changed_at?->format(
                                            'd M Y'
                                        ) ?? '—' }}

                                    </div>

                                    <div class="date-sub">

                                        {{ $history->changed_at?->format(
                                            'h:i A'
                                        ) ?? '—' }}

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($histories->hasPages())

                <div class="pagination-wrapper">

                    {{ $histories->links() }}

                </div>

            @endif

        @else

            {{-- Empty History --}}
            <div class="empty-history">

                <div class="empty-history-icon">

                    <i class="bi bi-clock-history"></i>

                </div>

                <h5>
                    No History Available
                </h5>

                <p>
                    No activity history has been recorded for this
                    temporary item issue yet.
                </p>

            </div>

        @endif

    </div>

</div>


</div>

@endsection
