@extends('layouts.admin')

@section('title', 'RDD History | CPC Inventory Management System')

@section('page-heading', 'RDD History')

@section('content')

<style>
    /* =========================================================
       RDD HISTORY PAGE
    ========================================================= */

    .rdd-history-page {
        max-width: 1600px;
        margin: 0 auto;
        padding: 4px 0 30px;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .rdd-history-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 26px;
    }

    .rdd-history-header-left {
        min-width: 0;
    }

    .rdd-history-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 9px;
        font-size: 13px;
        color: #7b8794;
    }

    .rdd-history-breadcrumb a {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 600;
    }

    .rdd-history-breadcrumb a:hover {
        text-decoration: underline;
    }

    .rdd-history-title {
        margin: 0;
        font-size: 28px;
        font-weight: 750;
        color: #172033;
        letter-spacing: -0.5px;
    }

    .rdd-history-subtitle {
        margin: 7px 0 0;
        color: #6b7785;
        font-size: 14px;
    }

    .rdd-history-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .rdd-history-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 650;
        transition: all .18s ease;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .rdd-history-btn-primary {
        background: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, .15);
    }

    .rdd-history-btn-primary:hover {
        background: #0b5ed7;
        color: #fff;
        transform: translateY(-1px);
    }

    .rdd-history-btn-secondary {
        background: #fff;
        color: #344054;
        border-color: #dfe5ec;
    }

    .rdd-history-btn-secondary:hover {
        background: #f8fafc;
        color: #0d6efd;
        border-color: #cbd5e1;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .rdd-history-alert {
        border-radius: 10px;
        padding: 13px 16px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        font-size: 14px;
    }

    .rdd-history-alert-success {
        background: #ecfdf3;
        border-color: #b7ebcc;
        color: #146c43;
    }

    .rdd-history-alert-error {
        background: #fff5f5;
        border-color: #f1b8b8;
        color: #b42318;
    }

    /* =========================================================
       SUMMARY CARD
    ========================================================= */

    .rdd-history-summary {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    .rdd-history-summary-card {
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 3px 14px rgba(16, 24, 40, .04);
    }

    .rdd-history-summary-label {
        color: #7b8794;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .65px;
        margin-bottom: 7px;
    }

    .rdd-history-summary-value {
        color: #172033;
        font-size: 16px;
        font-weight: 700;
        word-break: break-word;
    }

    .rdd-history-summary-value.large {
        font-size: 20px;
    }

    .rdd-history-summary-meta {
        margin-top: 5px;
        color: #8a96a3;
        font-size: 12px;
    }

    /* =========================================================
       STATUS BADGES
    ========================================================= */

    .rdd-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .25px;
        white-space: nowrap;
    }

    .rdd-status-pending {
        background: #fff7e6;
        color: #9a6700;
    }

    .rdd-status-approved {
        background: #eef4ff;
        color: #2457a6;
    }

    .rdd-status-dispatched {
        background: #f0ebff;
        color: #6546a3;
    }

    .rdd-status-received {
        background: #e9f7ff;
        color: #0969a8;
    }

    .rdd-status-inspection {
        background: #fff4e5;
        color: #a15c00;
    }

    .rdd-status-in-progress {
        background: #edf7ed;
        color: #237a3b;
    }

    .rdd-status-completed {
        background: #e9f8ef;
        color: #18794e;
    }

    .rdd-status-returned {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .rdd-status-beyond-repair {
        background: #fff0f0;
        color: #b42318;
    }

    .rdd-status-rejected,
    .rdd-status-cancelled {
        background: #f1f3f5;
        color: #667085;
    }

    .rdd-status-default {
        background: #f1f5f9;
        color: #475467;
    }

    /* =========================================================
       MAIN LAYOUT
    ========================================================= */

    .rdd-history-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 350px;
        gap: 20px;
        align-items: start;
    }

    .rdd-history-card {
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 13px;
        box-shadow: 0 3px 14px rgba(16, 24, 40, .04);
        overflow: hidden;
    }

    .rdd-history-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f4;
    }

    .rdd-history-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 750;
    }

    .rdd-history-card-title i {
        color: #0d6efd;
        font-size: 18px;
    }

    .rdd-history-card-subtitle {
        margin: 3px 0 0 28px;
        color: #8994a3;
        font-size: 12px;
    }

    .rdd-history-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 26px;
        padding: 0 8px;
        border-radius: 7px;
        background: #eef4ff;
        color: #0d6efd;
        font-size: 12px;
        font-weight: 750;
    }

    /* =========================================================
       TIMELINE
    ========================================================= */

    .rdd-history-timeline {
        position: relative;
        padding: 24px 25px 28px 35px;
    }

    .rdd-history-timeline::before {
        content: "";
        position: absolute;
        top: 35px;
        bottom: 35px;
        left: 50px;
        width: 2px;
        background: #e6ebf2;
    }

    .rdd-history-entry {
        position: relative;
        display: grid;
        grid-template-columns: 42px minmax(0, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .rdd-history-entry:last-child {
        margin-bottom: 0;
    }

    .rdd-history-marker {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        margin-top: 2px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #0d6efd;
        color: #0d6efd;
        box-shadow: 0 0 0 5px #fff;
    }

    .rdd-history-marker i {
        font-size: 13px;
    }

    .rdd-history-entry-card {
        border: 1px solid #e7ebf0;
        border-radius: 11px;
        padding: 16px 17px;
        background: #fbfcfe;
        transition: all .18s ease;
    }

    .rdd-history-entry-card:hover {
        border-color: #d4dce7;
        box-shadow: 0 4px 14px rgba(16, 24, 40, .05);
        background: #fff;
    }

    .rdd-history-entry-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 12px;
    }

    .rdd-history-transition {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .rdd-history-arrow {
        color: #98a2b3;
        font-size: 13px;
    }

    .rdd-history-entry-date {
        flex-shrink: 0;
        color: #7b8794;
        font-size: 12px;
        font-weight: 600;
        text-align: right;
    }

    .rdd-history-entry-date strong {
        display: block;
        color: #344054;
        font-size: 12px;
    }

    .rdd-history-entry-body {
        display: grid;
        gap: 12px;
    }

    .rdd-history-field-label {
        margin-bottom: 4px;
        color: #8994a3;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .rdd-history-field-value {
        color: #344054;
        font-size: 13px;
        line-height: 1.55;
        white-space: pre-line;
    }

    .rdd-history-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 18px;
        padding-top: 11px;
        border-top: 1px solid #edf0f4;
        color: #7b8794;
        font-size: 11px;
    }

    .rdd-history-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .rdd-history-meta i {
        color: #98a2b3;
    }

    /* =========================================================
       SIDEBAR
    ========================================================= */

    .rdd-history-sidebar {
        display: grid;
        gap: 20px;
    }

    .rdd-sidebar-section {
        padding: 18px 20px;
    }

    .rdd-sidebar-label {
        margin-bottom: 8px;
        color: #8994a3;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .65px;
    }

    .rdd-sidebar-value {
        color: #172033;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.45;
    }

    .rdd-sidebar-meta {
        margin-top: 4px;
        color: #8994a3;
        font-size: 12px;
        line-height: 1.5;
    }

    .rdd-info-list {
        display: grid;
    }

    .rdd-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        padding: 12px 20px;
        border-bottom: 1px solid #edf0f4;
    }

    .rdd-info-row:last-child {
        border-bottom: 0;
    }

    .rdd-info-label {
        color: #7b8794;
        font-size: 12px;
    }

    .rdd-info-value {
        color: #344054;
        font-size: 12px;
        font-weight: 650;
        text-align: right;
        word-break: break-word;
    }

    .rdd-quick-actions {
        padding: 16px 20px 20px;
        display: grid;
        gap: 9px;
    }

    .rdd-quick-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 0 13px;
        border-radius: 8px;
        border: 1px solid #dfe5ec;
        background: #fff;
        color: #344054;
        text-decoration: none;
        font-size: 12px;
        font-weight: 650;
        transition: all .18s ease;
    }

    .rdd-quick-action:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0d6efd;
    }

    .rdd-quick-action.primary {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .rdd-quick-action.primary:hover {
        background: #0b5ed7;
        color: #fff;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .rdd-history-empty {
        padding: 55px 25px;
        text-align: center;
    }

    .rdd-history-empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        margin-bottom: 14px;
        border-radius: 14px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 24px;
    }

    .rdd-history-empty h3 {
        margin: 0 0 7px;
        color: #344054;
        font-size: 16px;
        font-weight: 700;
    }

    .rdd-history-empty p {
        margin: 0;
        color: #8994a3;
        font-size: 13px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {
        .rdd-history-layout {
            grid-template-columns: minmax(0, 1fr) 300px;
        }

        .rdd-history-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 900px) {
        .rdd-history-header {
            flex-direction: column;
        }

        .rdd-history-header-actions {
            width: 100%;
        }

        .rdd-history-header-actions .rdd-history-btn {
            flex: 1;
        }

        .rdd-history-layout {
            grid-template-columns: 1fr;
        }

        .rdd-history-sidebar {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .rdd-history-page {
            padding-bottom: 20px;
        }

        .rdd-history-title {
            font-size: 23px;
        }

        .rdd-history-summary {
            grid-template-columns: 1fr;
        }

        .rdd-history-sidebar {
            grid-template-columns: 1fr;
        }

        .rdd-history-card-header {
            align-items: flex-start;
        }

        .rdd-history-timeline {
            padding-left: 18px;
            padding-right: 18px;
        }

        .rdd-history-timeline::before {
            left: 33px;
        }

        .rdd-history-entry {
            grid-template-columns: 30px minmax(0, 1fr);
            gap: 12px;
        }

        .rdd-history-marker {
            width: 28px;
            height: 28px;
        }

        .rdd-history-entry-top {
            flex-direction: column;
        }

        .rdd-history-entry-date {
            text-align: left;
        }
    }
</style>

@php
/*
|--------------------------------------------------------------------------
| Safe Display Helpers
|--------------------------------------------------------------------------
*/


$status = $rdd->status ?? 'pending';

$statusLabel = \Illuminate\Support\Str::headline($status);

$statusClass = match ($status) {
    'pending' => 'rdd-status-pending',
    'approved' => 'rdd-status-approved',
    'dispatched' => 'rdd-status-dispatched',
    'received' => 'rdd-status-received',
    'inspection' => 'rdd-status-inspection',
    'in_progress' => 'rdd-status-in-progress',
    'completed' => 'rdd-status-completed',
    'returned' => 'rdd-status-returned',
    'beyond_repair' => 'rdd-status-beyond-repair',
    'rejected' => 'rdd-status-rejected',
    'cancelled' => 'rdd-status-cancelled',
    default => 'rdd-status-default',
};

$item = $rdd->item;
$employee = $rdd->employee;

$historyCount = $rdd->histories?->count() ?? 0;

@endphp

<div class="rdd-history-page">


{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="rdd-history-header">

    <div class="rdd-history-header-left">

        <div class="rdd-history-breadcrumb">
            <a href="{{ route('admin.inventory.rdd.index') }}">
                RDD Management
            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('admin.inventory.rdd.show', $rdd) }}">
                {{ $rdd->reference_no }}
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>History</span>
        </div>

        <h1 class="rdd-history-title">
            RDD Transaction History
        </h1>

        <p class="rdd-history-subtitle">
            Complete audit trail and status history for
            <strong>{{ $rdd->reference_no }}</strong>.
        </p>

    </div>

    <div class="rdd-history-header-actions">

        <a
            href="{{ route('admin.inventory.rdd.show', $rdd) }}"
            class="rdd-history-btn rdd-history-btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Back to RDD Record
        </a>

        <a
            href="{{ route('admin.inventory.rdd.index') }}"
            class="rdd-history-btn rdd-history-btn-primary"
        >
            <i class="bi bi-list-ul"></i>
            RDD Management
        </a>

    </div>

</div>


{{-- =====================================================
     FLASH MESSAGES
====================================================== --}}

@if(session('success'))
    <div class="rdd-history-alert rdd-history-alert-success">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="rdd-history-alert rdd-history-alert-error">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
    </div>
@endif


{{-- =====================================================
     SUMMARY
====================================================== --}}

<div class="rdd-history-summary">

    <div class="rdd-history-summary-card">
        <div class="rdd-history-summary-label">
            RDD Reference
        </div>

        <div class="rdd-history-summary-value large">
            {{ $rdd->reference_no }}
        </div>

        <div class="rdd-history-summary-meta">
            Created
            {{ $rdd->created_at?->format('d M Y, h:i A') ?? '—' }}
        </div>
    </div>

    <div class="rdd-history-summary-card">
        <div class="rdd-history-summary-label">
            Current Status
        </div>

        <div class="rdd-history-summary-value">
            <span class="rdd-status {{ $statusClass }}">
                <i class="bi bi-circle-fill" style="font-size: 6px;"></i>
                {{ $statusLabel }}
            </span>
        </div>

        <div class="rdd-history-summary-meta">
            Current workflow stage
        </div>
    </div>

    <div class="rdd-history-summary-card">
        <div class="rdd-history-summary-label">
            Asset Number
        </div>

        <div class="rdd-history-summary-value">
            {{ $item?->asset_number ?? '—' }}
        </div>

        <div class="rdd-history-summary-meta">
            {{ $item?->serial_number ? 'Serial: '.$item->serial_number : 'Serial number unavailable' }}
        </div>
    </div>

    <div class="rdd-history-summary-card">
        <div class="rdd-history-summary-label">
            History Events
        </div>

        <div class="rdd-history-summary-value large">
            {{ $historyCount }}
        </div>

        <div class="rdd-history-summary-meta">
            Recorded audit events
        </div>
    </div>

</div>


{{-- =====================================================
     MAIN CONTENT
====================================================== --}}

<div class="rdd-history-layout">

    {{-- =================================================
         TIMELINE
    ================================================== --}}

    <div class="rdd-history-card">

        <div class="rdd-history-card-header">

            <div>
                <h2 class="rdd-history-card-title">
                    <i class="bi bi-clock-history"></i>
                    Status Timeline
                </h2>

                <p class="rdd-history-card-subtitle">
                    Every recorded change for this RDD transaction.
                </p>
            </div>

            <span class="rdd-history-count">
                {{ $historyCount }}
            </span>

        </div>


        @if($rdd->histories && $rdd->histories->count())

            <div class="rdd-history-timeline">

                @foreach($rdd->histories as $history)

                    @php
                        $newStatus = $history->new_status ?? 'unknown';
                        $oldStatus = $history->old_status;

                        $newStatusClass = match ($newStatus) {
                            'pending' => 'rdd-status-pending',
                            'approved' => 'rdd-status-approved',
                            'dispatched' => 'rdd-status-dispatched',
                            'received' => 'rdd-status-received',
                            'inspection' => 'rdd-status-inspection',
                            'in_progress' => 'rdd-status-in-progress',
                            'completed' => 'rdd-status-completed',
                            'returned' => 'rdd-status-returned',
                            'beyond_repair' => 'rdd-status-beyond-repair',
                            'rejected' => 'rdd-status-rejected',
                            'cancelled' => 'rdd-status-cancelled',
                            default => 'rdd-status-default',
                        };

                        $oldStatusClass = match ($oldStatus) {
                            'pending' => 'rdd-status-pending',
                            'approved' => 'rdd-status-approved',
                            'dispatched' => 'rdd-status-dispatched',
                            'received' => 'rdd-status-received',
                            'inspection' => 'rdd-status-inspection',
                            'in_progress' => 'rdd-status-in-progress',
                            'completed' => 'rdd-status-completed',
                            'returned' => 'rdd-status-returned',
                            'beyond_repair' => 'rdd-status-beyond-repair',
                            'rejected' => 'rdd-status-rejected',
                            'cancelled' => 'rdd-status-cancelled',
                            default => 'rdd-status-default',
                        };
                    @endphp

                    <div class="rdd-history-entry">

                        <div class="rdd-history-marker">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>

                        <div class="rdd-history-entry-card">

                            <div class="rdd-history-entry-top">

                                <div class="rdd-history-transition">

                                    @if($oldStatus)

                                        <span class="rdd-status {{ $oldStatusClass }}">
                                            {{ \Illuminate\Support\Str::headline($oldStatus) }}
                                        </span>

                                        <i class="bi bi-arrow-right rdd-history-arrow"></i>

                                    @endif

                                    <span class="rdd-status {{ $newStatusClass }}">
                                        {{ \Illuminate\Support\Str::headline($newStatus) }}
                                    </span>

                                </div>

                                <div class="rdd-history-entry-date">

                                    <strong>
                                        {{ $history->created_at?->format('d M Y') ?? '—' }}
                                    </strong>

                                    {{ $history->created_at?->format('h:i A') ?? '—' }}

                                </div>

                            </div>


                            <div class="rdd-history-entry-body">

                                @if($history->remarks)

                                    <div>
                                        <div class="rdd-history-field-label">
                                            Remarks
                                        </div>

                                        <div class="rdd-history-field-value">
                                            {{ $history->remarks }}
                                        </div>
                                    </div>

                                @endif


                                @if($history->recommendation)

                                    <div>
                                        <div class="rdd-history-field-label">
                                            Recommendation
                                        </div>

                                        <div class="rdd-history-field-value">
                                            {{ $history->recommendation }}
                                        </div>
                                    </div>

                                @endif

                            </div>


                            <div class="rdd-history-meta">

                                <span>
                                    <i class="bi bi-calendar3"></i>
                                    {{ $history->created_at?->format('d M Y, h:i A') ?? '—' }}
                                </span>

                                @if($history->changed_by)
                                    <span>
                                        <i class="bi bi-person"></i>
                                        User ID: {{ $history->changed_by }}
                                    </span>
                                @else
                                    <span>
                                        <i class="bi bi-person"></i>
                                        System
                                    </span>
                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="rdd-history-empty">

                <div class="rdd-history-empty-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <h3>No History Available</h3>

                <p>
                    No status or workflow history has been recorded
                    for this RDD transaction yet.
                </p>

            </div>

        @endif

    </div>


    {{-- =================================================
         SIDEBAR
    ================================================== --}}

    <div class="rdd-history-sidebar">


        {{-- =============================================
             RDD INFORMATION
        ============================================== --}}

        <div class="rdd-history-card">

            <div class="rdd-history-card-header">
                <h2 class="rdd-history-card-title">
                    <i class="bi bi-file-earmark-text"></i>
                    RDD Information
                </h2>
            </div>

            <div class="rdd-info-list">

                <div class="rdd-info-row">
                    <span class="rdd-info-label">
                        Reference
                    </span>

                    <span class="rdd-info-value">
                        {{ $rdd->reference_no }}
                    </span>
                </div>

                <div class="rdd-info-row">
                    <span class="rdd-info-label">
                        RDD Date
                    </span>

                    <span class="rdd-info-value">
                        {{ $rdd->rdd_date?->format('d M Y') ?? '—' }}
                    </span>
                </div>

                <div class="rdd-info-row">
                    <span class="rdd-info-label">
                        Status
                    </span>

                    <span class="rdd-info-value">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="rdd-info-row">
                    <span class="rdd-info-label">
                        Active
                    </span>

                    <span class="rdd-info-value">
                        {{ $rdd->is_active ? 'Yes' : 'No' }}
                    </span>
                </div>

                @if($rdd->received_date)

                    <div class="rdd-info-row">
                        <span class="rdd-info-label">
                            Received
                        </span>

                        <span class="rdd-info-value">
                            {{ $rdd->received_date->format('d M Y') }}
                        </span>
                    </div>

                @endif

                @if($rdd->completed_date)

                    <div class="rdd-info-row">
                        <span class="rdd-info-label">
                            Completed
                        </span>

                        <span class="rdd-info-value">
                            {{ $rdd->completed_date->format('d M Y') }}
                        </span>
                    </div>

                @endif

            </div>

        </div>


        {{-- =============================================
             EQUIPMENT
        ============================================== --}}

        <div class="rdd-history-card">

            <div class="rdd-history-card-header">
                <h2 class="rdd-history-card-title">
                    <i class="bi bi-pc-display"></i>
                    Equipment
                </h2>
            </div>

            <div class="rdd-sidebar-section">

                <div class="rdd-sidebar-label">
                    Asset Number
                </div>

                <div class="rdd-sidebar-value">
                    {{ $item?->asset_number ?? '—' }}
                </div>

                <div class="rdd-sidebar-meta">
                    Serial Number:
                    {{ $item?->serial_number ?? '—' }}
                </div>

                @if($item?->itemType)

                    <div class="rdd-sidebar-meta">
                        Type:
                        {{ $item->itemType->name }}
                    </div>

                @endif

                @if($item?->make || $item?->model)

                    <div class="rdd-sidebar-meta">
                        {{ trim(($item?->make ?? '') . ' ' . ($item?->model ?? '')) }}
                    </div>

                @endif

            </div>

        </div>


        {{-- =============================================
             EMPLOYEE
        ============================================== --}}

        <div class="rdd-history-card">

            <div class="rdd-history-card-header">
                <h2 class="rdd-history-card-title">
                    <i class="bi bi-person-badge"></i>
                    Employee
                </h2>
            </div>

            <div class="rdd-sidebar-section">

                @if($employee)

                    <div class="rdd-sidebar-value">
                        {{ $employee->name }}
                    </div>

                    @if($employee->epf_no)
                        <div class="rdd-sidebar-meta">
                            EPF No: {{ $employee->epf_no }}
                        </div>
                    @endif

                    @if($employee->designation)
                        <div class="rdd-sidebar-meta">
                            {{ $employee->designation }}
                        </div>
                    @endif

                @else

                    <div class="rdd-sidebar-value">
                        No employee assigned
                    </div>

                @endif

            </div>

        </div>


        {{-- =============================================
             QUICK ACTIONS
        ============================================== --}}

        <div class="rdd-history-card">

            <div class="rdd-history-card-header">
                <h2 class="rdd-history-card-title">
                    <i class="bi bi-lightning-charge"></i>
                    Quick Actions
                </h2>
            </div>

            <div class="rdd-quick-actions">

                <a
                    href="{{ route('admin.inventory.rdd.show', $rdd) }}"
                    class="rdd-quick-action primary"
                >
                    <i class="bi bi-file-earmark-text"></i>
                    View RDD Record
                </a>

                <a
                    href="{{ route('admin.inventory.rdd.edit', $rdd) }}"
                    class="rdd-quick-action"
                >
                    <i class="bi bi-pencil-square"></i>
                    Edit RDD Record
                </a>

                <a
                    href="{{ route('admin.inventory.rdd.index') }}"
                    class="rdd-quick-action"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to RDD Management
                </a>

            </div>

        </div>

    </div>

</div>


</div>

@endsection
