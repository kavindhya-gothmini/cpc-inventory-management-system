@extends('layouts.admin')

@section('title', 'RDD Details | CPC Inventory Management System')

@section('page-heading', 'RDD Details')

@section('content')

<style>
    /* =========================================================
       RDD SHOW PAGE
    ========================================================= */

    .rdd-show-page {
        max-width: 1600px;
        margin: 0 auto;
        padding: 4px 0 30px;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .rdd-show-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 26px;
    }

    .rdd-show-header-left {
        min-width: 0;
    }

    .rdd-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 9px;
        font-size: 13px;
        color: #7b8794;
    }

    .rdd-breadcrumb a {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 600;
    }

    .rdd-breadcrumb a:hover {
        text-decoration: underline;
    }

    .rdd-page-title {
        margin: 0;
        color: #172033;
        font-size: 28px;
        font-weight: 750;
        letter-spacing: -.5px;
    }

    .rdd-page-subtitle {
        margin: 7px 0 0;
        color: #6b7785;
        font-size: 14px;
    }

    .rdd-header-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
        flex-shrink: 0;
    }

    .rdd-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 15px;
        border-radius: 9px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 13px;
        font-weight: 650;
        transition: all .18s ease;
        white-space: nowrap;
    }

    .rdd-btn-primary {
        background: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, .15);
    }

    .rdd-btn-primary:hover {
        background: #0b5ed7;
        color: #fff;
        transform: translateY(-1px);
    }

    .rdd-btn-secondary {
        background: #fff;
        color: #344054;
        border-color: #dfe5ec;
    }

    .rdd-btn-secondary:hover {
        background: #f8fafc;
        color: #0d6efd;
        border-color: #cbd5e1;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .rdd-alert {
        padding: 13px 16px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 14px;
        border: 1px solid transparent;
    }

    .rdd-alert-success {
        background: #ecfdf3;
        border-color: #b7ebcc;
        color: #146c43;
    }

    .rdd-alert-error {
        background: #fff5f5;
        border-color: #f1b8b8;
        color: #b42318;
    }

    /* =========================================================
       LAYOUT
    ========================================================= */

    .rdd-show-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 350px;
        gap: 20px;
        align-items: start;
    }

    .rdd-main-column,
    .rdd-sidebar {
        display: grid;
        gap: 20px;
    }

    .rdd-card {
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 13px;
        box-shadow: 0 3px 14px rgba(16, 24, 40, .04);
        overflow: hidden;
    }

    .rdd-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f4;
    }

    .rdd-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 750;
    }

    .rdd-card-title i {
        color: #0d6efd;
        font-size: 18px;
    }

    .rdd-card-body {
        padding: 20px;
    }

    /* =========================================================
       REFERENCE / STATUS HERO
    ========================================================= */

    .rdd-reference-card {
        padding: 22px;
        background: linear-gradient(135deg, #f8fbff 0%, #ffffff 65%);
    }

    .rdd-reference-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .rdd-reference-label {
        margin-bottom: 5px;
        color: #8994a3;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .rdd-reference-number {
        color: #172033;
        font-size: 25px;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .rdd-reference-date {
        margin-top: 5px;
        color: #7b8794;
        font-size: 12px;
    }

    /* =========================================================
       STATUS BADGES
    ========================================================= */

    .rdd-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
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
       INFORMATION GRID
    ========================================================= */

    .rdd-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 25px;
    }

    .rdd-info-item {
        min-width: 0;
    }

    .rdd-info-label {
        margin-bottom: 5px;
        color: #8994a3;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .6px;
    }

    .rdd-info-value {
        color: #344054;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.5;
        word-break: break-word;
    }

    .rdd-info-value.muted {
        color: #98a2b3;
        font-weight: 500;
    }

    .rdd-full-width {
        grid-column: 1 / -1;
    }

    .rdd-text-box {
        padding: 13px 14px;
        border: 1px solid #e7ebf0;
        border-radius: 9px;
        background: #fbfcfe;
        color: #344054;
        font-size: 13px;
        line-height: 1.6;
        white-space: pre-line;
    }

    /* =========================================================
       SIDEBAR
    ========================================================= */

    .rdd-sidebar-section {
        padding: 18px 20px;
    }

    .rdd-sidebar-label {
        margin-bottom: 7px;
        color: #8994a3;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .6px;
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

    /* =========================================================
       STATUS UPDATE FORM
    ========================================================= */

    .rdd-status-update-card {
        border-color: #dce8f8;
    }

    .rdd-status-update-header {
        background: #f8fbff;
    }

    .rdd-status-current {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
        padding: 13px 14px;
        border: 1px solid #e2eaf4;
        border-radius: 9px;
        background: #fff;
    }

    .rdd-status-current-label {
        color: #8994a3;
        font-size: 11px;
        font-weight: 700;
    }

    .rdd-form-group {
        margin-bottom: 16px;
    }

    .rdd-form-group:last-child {
        margin-bottom: 0;
    }

    .rdd-form-label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .rdd-required {
        color: #dc3545;
    }

    .rdd-form-control {
        width: 100%;
        min-height: 43px;
        padding: 9px 12px;
        border: 1px solid #d7dee8;
        border-radius: 8px;
        background: #fff;
        color: #344054;
        font-size: 13px;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .rdd-form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .10);
    }

    textarea.rdd-form-control {
        min-height: 95px;
        resize: vertical;
    }

    .rdd-form-help {
        margin-top: 5px;
        color: #8994a3;
        font-size: 11px;
        line-height: 1.45;
    }

    .rdd-status-submit {
        width: 100%;
        min-height: 43px;
        border: 0;
        border-radius: 8px;
        background: #0d6efd;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all .18s ease;
    }

    .rdd-status-submit:hover {
        background: #0b5ed7;
        transform: translateY(-1px);
    }

    .rdd-status-submit:disabled {
        opacity: .65;
        cursor: not-allowed;
        transform: none;
    }

    /* =========================================================
       STATUS WARNING
    ========================================================= */

    .rdd-completed-notice {
        padding: 13px 14px;
        border-radius: 9px;
        background: #f8f9fa;
        border: 1px solid #e2e5e9;
        color: #667085;
        font-size: 12px;
        line-height: 1.55;
    }

    .rdd-completed-notice i {
        margin-right: 5px;
    }

    /* =========================================================
       QUICK ACTIONS
    ========================================================= */

    .rdd-quick-actions {
        display: grid;
        gap: 9px;
        padding: 16px 20px 20px;
    }

    .rdd-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 41px;
        padding: 0 13px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 650;
        border: 1px solid #dfe5ec;
        background: #fff;
        color: #344054;
        transition: all .18s ease;
    }

    .rdd-action:hover {
        background: #f8fafc;
        color: #0d6efd;
        border-color: #cbd5e1;
    }

    .rdd-action-primary {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .rdd-action-primary:hover {
        background: #0b5ed7;
        color: #fff;
    }

    /* =========================================================
       HISTORY PREVIEW
    ========================================================= */

    .rdd-history-list {
        padding: 5px 20px 20px;
    }

    .rdd-history-item {
        position: relative;
        display: grid;
        grid-template-columns: 30px minmax(0, 1fr);
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid #edf0f4;
    }

    .rdd-history-item:last-child {
        border-bottom: 0;
    }

    .rdd-history-dot {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #eef4ff;
        color: #0d6efd;
        font-size: 12px;
    }

    .rdd-history-item-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .rdd-history-transition {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    .rdd-history-date {
        flex-shrink: 0;
        color: #8994a3;
        font-size: 11px;
        text-align: right;
    }

    .rdd-history-remark {
        margin-top: 7px;
        color: #667085;
        font-size: 12px;
        line-height: 1.5;
    }

    .rdd-history-view-all {
        padding: 0 20px 20px;
    }

    .rdd-history-view-all a {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        border-radius: 8px;
        border: 1px solid #dfe5ec;
        color: #0d6efd;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .rdd-history-view-all a:hover {
        background: #f8fbff;
        border-color: #b9d2f5;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {
        .rdd-show-layout {
            grid-template-columns: 1fr;
        }

        .rdd-sidebar {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .rdd-show-header {
            flex-direction: column;
        }

        .rdd-header-actions {
            width: 100%;
        }

        .rdd-header-actions .rdd-btn {
            flex: 1;
        }

        .rdd-sidebar {
            grid-template-columns: 1fr;
        }

        .rdd-info-grid {
            grid-template-columns: 1fr;
        }

        .rdd-full-width {
            grid-column: auto;
        }

        .rdd-reference-top {
            flex-direction: column;
        }

        .rdd-history-item-top {
            flex-direction: column;
        }

        .rdd-history-date {
            text-align: left;
        }
    }

    @media (max-width: 520px) {
        .rdd-page-title {
            font-size: 23px;
        }

        .rdd-header-actions {
            flex-direction: column;
        }

        .rdd-header-actions .rdd-btn {
            width: 100%;
        }

        .rdd-card-body,
        .rdd-reference-card {
            padding: 16px;
        }

        .rdd-card-header {
            padding: 16px;
        }
    }
</style>

@php
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

$history = $rdd->histories ?? collect();

$workflowClosed = in_array($status, [
    'completed',
    'returned',
    'beyond_repair',
    'rejected',
    'cancelled',
], true);


@endphp

<div class="rdd-show-page">

{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="rdd-show-header">

    <div class="rdd-show-header-left">

        <div class="rdd-breadcrumb">

            <a href="{{ route('admin.inventory.rdd.index') }}">
                RDD Management
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>
                {{ $rdd->reference_no }}
            </span>

        </div>

        <h1 class="rdd-page-title">
            RDD Transaction Details
        </h1>

        <p class="rdd-page-subtitle">
            Review equipment information, RDD processing details,
            workflow status and history.
        </p>

    </div>

    <div class="rdd-header-actions">

        <a
            href="{{ route('admin.inventory.rdd.index') }}"
            class="rdd-btn rdd-btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Back to RDD
        </a>

        <a
            href="{{ route('admin.inventory.rdd.edit', $rdd) }}"
            class="rdd-btn rdd-btn-primary"
        >
            <i class="bi bi-pencil-square"></i>
            Edit RDD
        </a>

    </div>

</div>


{{-- =====================================================
     FLASH MESSAGES
====================================================== --}}

@if(session('success'))
    <div class="rdd-alert rdd-alert-success">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="rdd-alert rdd-alert-error">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
    </div>
@endif


{{-- =====================================================
     MAIN LAYOUT
====================================================== --}}

<div class="rdd-show-layout">

    {{-- =================================================
         MAIN COLUMN
    ================================================== --}}

    <div class="rdd-main-column">

        {{-- =============================================
             REFERENCE / STATUS
        ============================================== --}}

        <div class="rdd-card">

            <div class="rdd-reference-card">

                <div class="rdd-reference-top">

                    <div>

                        <div class="rdd-reference-label">
                            RDD Reference Number
                        </div>

                        <div class="rdd-reference-number">
                            {{ $rdd->reference_no }}
                        </div>

                        <div class="rdd-reference-date">
                            Created
                            {{ $rdd->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>

                    </div>

                    <div>
                        <span class="rdd-status {{ $statusClass }}">
                            <i class="bi bi-circle-fill" style="font-size: 6px;"></i>
                            {{ $statusLabel }}
                        </span>
                    </div>

                </div>

            </div>

        </div>


        {{-- =============================================
             STATUS UPDATE
        ============================================== --}}

        <div class="rdd-card rdd-status-update-card">

            <div class="rdd-card-header rdd-status-update-header">

                <h2 class="rdd-card-title">
                    <i class="bi bi-arrow-repeat"></i>
                    Update RDD Status
                </h2>

            </div>

            <div class="rdd-card-body">

                @if($workflowClosed)

                    <div class="rdd-completed-notice">
                        <i class="bi bi-lock-fill"></i>

                        This RDD transaction is currently
                        <strong>{{ $statusLabel }}</strong>
                        and is no longer active in the normal workflow.
                    </div>

                @else

                    <form
                        method="POST"
                        action="{{ route('admin.inventory.rdd.status', $rdd) }}"
                        id="rddStatusForm"
                    >

                        @csrf
                        @method('PATCH')

                        <div class="rdd-status-current">

                            <span class="rdd-status-current-label">
                                Current Status
                            </span>

                            <span class="rdd-status {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                        </div>


                        <div class="rdd-form-group">

                            <label
                                for="status"
                                class="rdd-form-label"
                            >
                                New Status
                                <span class="rdd-required">*</span>
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="rdd-form-control"
                                required
                            >

                                <option value="">
                                    Select new status
                                </option>

                                @foreach([
                                    'pending' => 'Pending',
                                    'approved' => 'Approved',
                                    'dispatched' => 'Dispatched',
                                    'received' => 'Received',
                                    'inspection' => 'Inspection',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                    'returned' => 'Returned',
                                    'beyond_repair' => 'Beyond Repair',
                                    'rejected' => 'Rejected',
                                    'cancelled' => 'Cancelled',
                                ] as $value => $label)

                                    <option
                                        value="{{ $value }}"
                                        {{ $status === $value ? 'disabled' : '' }}
                                    >
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>

                            <div class="rdd-form-help">
                                Select the next applicable stage in the RDD workflow.
                            </div>

                        </div>


                        <div class="rdd-form-group">

                            <label
                                for="remarks"
                                class="rdd-form-label"
                            >
                                Status Remarks
                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                class="rdd-form-control"
                                placeholder="Enter any remarks related to this status change..."
                            >{{ old('remarks') }}</textarea>

                        </div>


                        <div class="rdd-form-group">

                            <label
                                for="recommendation"
                                class="rdd-form-label"
                            >
                                Recommendation
                            </label>

                            <textarea
                                name="recommendation"
                                id="recommendation"
                                class="rdd-form-control"
                                placeholder="Enter recommendation if applicable..."
                            >{{ old('recommendation', $rdd->recommendation) }}</textarea>

                        </div>


                        <button
                            type="submit"
                            class="rdd-status-submit"
                            id="rddStatusSubmit"
                        >
                            <i class="bi bi-check2-circle me-1"></i>
                            Update RDD Status
                        </button>

                    </form>

                @endif

            </div>

        </div>


        {{-- =============================================
             RDD TRANSACTION INFORMATION
        ============================================== --}}

        <div class="rdd-card">

            <div class="rdd-card-header">

                <h2 class="rdd-card-title">
                    <i class="bi bi-file-earmark-text"></i>
                    RDD Transaction Information
                </h2>

            </div>

            <div class="rdd-card-body">

                <div class="rdd-info-grid">

                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            RDD Date
                        </div>

                        <div class="rdd-info-value">
                            {{ $rdd->rdd_date?->format('d M Y') ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Current Status
                        </div>

                        <div class="rdd-info-value">
                            {{ $statusLabel }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Received Date
                        </div>

                        <div class="rdd-info-value {{ !$rdd->received_date ? 'muted' : '' }}">
                            {{ $rdd->received_date?->format('d M Y') ?? 'Not received yet' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Completed Date
                        </div>

                        <div class="rdd-info-value {{ !$rdd->completed_date ? 'muted' : '' }}">
                            {{ $rdd->completed_date?->format('d M Y') ?? 'Not completed yet' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Decision
                        </div>

                        <div class="rdd-info-value {{ !$rdd->decision ? 'muted' : '' }}">
                            {{ $rdd->decision ? \Illuminate\Support\Str::headline($rdd->decision) : 'Not decided' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Active Transaction
                        </div>

                        <div class="rdd-info-value">
                            {{ $rdd->is_active ? 'Yes' : 'No' }}
                        </div>

                    </div>


                    <div class="rdd-info-item rdd-full-width">

                        <div class="rdd-info-label">
                            Reason for Sending to RDD
                        </div>

                        <div class="rdd-text-box">
                            {{ $rdd->reason ?: 'No reason recorded.' }}
                        </div>

                    </div>


                    <div class="rdd-info-item rdd-full-width">

                        <div class="rdd-info-label">
                            Recommendation
                        </div>

                        <div class="rdd-text-box">
                            {{ $rdd->recommendation ?: 'No recommendation recorded.' }}
                        </div>

                    </div>


                    @if($rdd->remarks)

                        <div class="rdd-info-item rdd-full-width">

                            <div class="rdd-info-label">
                                Remarks
                            </div>

                            <div class="rdd-text-box">
                                {{ $rdd->remarks }}
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =============================================
             EQUIPMENT INFORMATION
        ============================================== --}}

        <div class="rdd-card">

            <div class="rdd-card-header">

                <h2 class="rdd-card-title">
                    <i class="bi bi-pc-display"></i>
                    Equipment Information
                </h2>

            </div>

            <div class="rdd-card-body">

                <div class="rdd-info-grid">

                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Asset Number
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->asset_number ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Serial Number
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->serial_number ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Item Type
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->itemType?->name ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Make
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->make ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Model
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->model ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            IP Address
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->ip_address ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Workstation ID
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->workstation_id ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Inventory Status
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->status ? \Illuminate\Support\Str::headline($item->status) : '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Branch
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->branch?->name ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Department
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->department?->name ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Function Unit
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->functionUnit?->name ?? '—' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Current Employee
                        </div>

                        <div class="rdd-info-value">
                            {{ $item?->currentEmployee?->name ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =============================================
             ASSESSMENT / OUTCOME
        ============================================== --}}

        <div class="rdd-card">

            <div class="rdd-card-header">

                <h2 class="rdd-card-title">
                    <i class="bi bi-clipboard2-check"></i>
                    Assessment & Outcome
                </h2>

            </div>

            <div class="rdd-card-body">

                <div class="rdd-info-grid">

                    <div class="rdd-info-item rdd-full-width">

                        <div class="rdd-info-label">
                            Work Performed
                        </div>

                        <div class="rdd-text-box">
                            {{ $rdd->work_performed ?: 'No work performed has been recorded yet.' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Final Condition
                        </div>

                        <div class="rdd-info-value {{ !$rdd->final_condition ? 'muted' : '' }}">
                            {{ $rdd->final_condition ?: 'Not recorded' }}
                        </div>

                    </div>


                    <div class="rdd-info-item">

                        <div class="rdd-info-label">
                            Decision
                        </div>

                        <div class="rdd-info-value {{ !$rdd->decision ? 'muted' : '' }}">
                            {{ $rdd->decision ? \Illuminate\Support\Str::headline($rdd->decision) : 'Not recorded' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =============================================
             HISTORY PREVIEW
        ============================================== --}}

        <div class="rdd-card">

            <div class="rdd-card-header">

                <h2 class="rdd-card-title">
                    <i class="bi bi-clock-history"></i>
                    Recent History
                </h2>

                <span style="font-size: 11px; color: #8994a3;">
                    {{ $history->count() }} event{{ $history->count() === 1 ? '' : 's' }}
                </span>

            </div>


            @if($history->count())

                <div class="rdd-history-list">

                    @foreach($history->take(5) as $historyItem)

                        @php
                            $historyOldStatus = $historyItem->old_status;
                            $historyNewStatus = $historyItem->new_status ?? 'unknown';

                            $historyOldClass = match ($historyOldStatus) {
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

                            $historyNewClass = match ($historyNewStatus) {
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

                        <div class="rdd-history-item">

                            <div class="rdd-history-dot">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>

                            <div>

                                <div class="rdd-history-item-top">

                                    <div class="rdd-history-transition">

                                        @if($historyOldStatus)

                                            <span class="rdd-status {{ $historyOldClass }}">
                                                {{ \Illuminate\Support\Str::headline($historyOldStatus) }}
                                            </span>

                                            <i class="bi bi-arrow-right" style="color:#98a2b3;"></i>

                                        @endif

                                        <span class="rdd-status {{ $historyNewClass }}">
                                            {{ \Illuminate\Support\Str::headline($historyNewStatus) }}
                                        </span>

                                    </div>

                                    <div class="rdd-history-date">
                                        {{ $historyItem->created_at?->format('d M Y, h:i A') ?? '—' }}
                                    </div>

                                </div>

                                @if($historyItem->remarks)

                                    <div class="rdd-history-remark">
                                        {{ $historyItem->remarks }}
                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>


                <div class="rdd-history-view-all">

                    <a href="{{ route('admin.inventory.rdd.history', $rdd) }}">
                        <i class="bi bi-clock-history"></i>
                        View Complete History
                    </a>

                </div>

            @else

                <div style="padding: 35px 20px; text-align:center; color:#8994a3; font-size:13px;">
                    <i
                        class="bi bi-clock-history"
                        style="font-size:25px; display:block; margin-bottom:8px;"
                    ></i>

                    No history has been recorded yet.
                </div>

            @endif

        </div>

    </div>


    {{-- =================================================
         SIDEBAR
    ================================================== --}}

    <div class="rdd-sidebar">

        {{-- =============================================
             CURRENT STATUS
        ============================================== --}}

        <div class="rdd-card">

            <div class="rdd-card-header">

                <h2 class="rdd-card-title">
                    <i class="bi bi-activity"></i>
                    Current Status
                </h2>

            </div>

            <div class="rdd-sidebar-section">

                <span class="rdd-status {{ $statusClass }}">
                    <i class="bi bi-circle-fill" style="font-size: 6px;"></i>
                    {{ $statusLabel }}
                </span>

                <div class="rdd-sidebar-meta" style="margin-top: 12px;">
                    RDD workflow status
                </div>

                @if($rdd->received_date)

                    <div class="rdd-sidebar-meta">
                        Received:
                        {{ $rdd->received_date->format('d M Y') }}
                    </div>

                @endif

                @if($rdd->completed_date)

                    <div class="rdd-sidebar-meta">
                        Completed:
                        {{ $rdd->completed_date->format('d M Y') }}
                    </div>

                @endif

            </div>

        </div>


        {{-- =============================================
             EMPLOYEE
        ============================================== --}}

        <div class="rdd-card">

            <div class="rdd-card-header">

                <h2 class="rdd-card-title">
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
             EQUIPMENT SUMMARY
        ============================================== --}}

        <div class="rdd-card">

            <div class="rdd-card-header">

                <h2 class="rdd-card-title">
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
                    Serial:
                    {{ $item?->serial_number ?? '—' }}
                </div>

                @if($item?->itemType)

                    <div class="rdd-sidebar-meta">
                        Type:
                        {{ $item->itemType->name }}
                    </div>

                @endif

            </div>

        </div>


        {{-- =============================================
             QUICK ACTIONS
        ============================================== --}}

        <div class="rdd-card">

            <div class="rdd-card-header">

                <h2 class="rdd-card-title">
                    <i class="bi bi-lightning-charge"></i>
                    Quick Actions
                </h2>

            </div>

            <div class="rdd-quick-actions">

                <a
                    href="{{ route('admin.inventory.rdd.edit', $rdd) }}"
                    class="rdd-action rdd-action-primary"
                >
                    <i class="bi bi-pencil-square"></i>
                    Edit RDD Record
                </a>

                <a
                    href="{{ route('admin.inventory.rdd.history', $rdd) }}"
                    class="rdd-action"
                >
                    <i class="bi bi-clock-history"></i>
                    View Complete History
                </a>

                <a
                    href="{{ route('admin.inventory.rdd.index') }}"
                    class="rdd-action"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to RDD Management
                </a>

            </div>

        </div>

    </div>

</div>


</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('rddStatusForm');
        const submitButton = document.getElementById('rddStatusSubmit');

        if (form && submitButton) {

            form.addEventListener('submit', function () {

                const status = document.getElementById('status');

                if (!status || !status.value) {
                    return;
                }

                submitButton.disabled = true;

                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Updating Status...';

            });

        }

    });
</script>

@endsection
