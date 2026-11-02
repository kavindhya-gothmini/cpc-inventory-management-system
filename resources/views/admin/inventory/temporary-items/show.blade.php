@extends('layouts.admin')

@section('title', 'Temporary Item Details | CPC Inventory Management System')

@section('page-heading', 'Temporary Item Details')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">

                <span class="page-icon">
                    <i class="bi bi-box-seam"></i>
                </span>

                <div>
                    <h4 class="fw-bold mb-0">
                        Temporary Item Details
                    </h4>

                    <div class="d-flex flex-wrap align-items-center gap-2 mt-1">

                        <span class="text-muted small">
                            Item Code:
                        </span>

                        <span class="badge bg-light text-dark border fw-semibold">
                            {{ $temporaryItem->item_code }}
                        </span>

                    </div>
                </div>

            </div>

            <p class="text-muted mb-0 ms-lg-5">
                View complete information, status, issue history and record details.
            </p>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2">

            <a href="{{ route('admin.inventory.temporary-items.edit', $temporaryItem) }}"
               class="btn btn-primary px-4">

                <i class="bi bi-pencil-square me-1"></i>
                Edit Item

            </a>

            <a href="{{ route('admin.inventory.temporary-items.index') }}"
               class="btn btn-outline-secondary px-4">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Items

            </a>

        </div>

    </div>


    {{-- =========================================================
        STATUS SUMMARY
    ========================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">

        <div class="status-banner
            @if($temporaryItem->status === 'available')
                status-available
            @elseif($temporaryItem->status === 'issued')
                status-issued
            @else
                status-inactive
            @endif">

            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="status-icon">

                        @if($temporaryItem->status === 'available')
                            <i class="bi bi-check-circle-fill"></i>
                        @elseif($temporaryItem->status === 'issued')
                            <i class="bi bi-arrow-up-right-circle-fill"></i>
                        @else
                            <i class="bi bi-slash-circle-fill"></i>
                        @endif

                    </div>

                    <div>

                        <div class="small text-muted mb-1">
                            Current Item Status
                        </div>

                        @if($temporaryItem->status === 'available')

                            <span class="badge bg-success-subtle text-success border border-success-subtle status-badge">
                                Available
                            </span>

                        @elseif($temporaryItem->status === 'issued')

                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle status-badge">
                                Issued
                            </span>

                        @else

                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle status-badge">
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>

                <div class="status-description">

                    @if($temporaryItem->status === 'available')

                        <i class="bi bi-check2-circle me-1"></i>
                        This item is currently available for temporary issue.

                    @elseif($temporaryItem->status === 'issued')

                        <i class="bi bi-info-circle me-1"></i>
                        This item is currently marked as issued.

                    @else

                        <i class="bi bi-exclamation-circle me-1"></i>
                        This item is currently inactive.

                    @endif

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- =====================================================
            MAIN ITEM INFORMATION
        ====================================================== --}}
        <div class="col-12 col-xl-8">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-bottom py-3 px-4">

                    <div class="d-flex align-items-center">

                        <div class="section-icon me-3">
                            <i class="bi bi-info-circle"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Item Information
                            </h5>

                            <small class="text-muted">
                                Registered information for this temporary item.
                            </small>

                        </div>

                    </div>

                </div>

                <div class="card-body p-4">

                    <div class="row g-4">

                        {{-- Item Code --}}
                        <div class="col-12 col-md-6">

                            <div class="detail-card h-100">

                                <div class="detail-icon">
                                    <i class="bi bi-upc-scan"></i>
                                </div>

                                <div class="detail-content">

                                    <div class="detail-label">
                                        Item Code
                                    </div>

                                    <div class="detail-value">
                                        {{ $temporaryItem->item_code }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Item Type --}}
                        <div class="col-12 col-md-6">

                            <div class="detail-card h-100">

                                <div class="detail-icon">
                                    <i class="bi bi-tags"></i>
                                </div>

                                <div class="detail-content">

                                    <div class="detail-label">
                                        Item Type
                                    </div>

                                    <div class="detail-value">
                                        {{ $temporaryItem->item_type }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <div class="detail-card detail-card-large">

                                <div class="detail-icon">
                                    <i class="bi bi-card-text"></i>
                                </div>

                                <div class="detail-content">

                                    <div class="detail-label">
                                        Description
                                    </div>

                                    <div class="detail-value detail-text">

                                        {{ $temporaryItem->description ?: 'No description provided.' }}

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Issue Records --}}
                        <div class="col-12 col-md-6">

                            <div class="detail-card h-100">

                                <div class="detail-icon">
                                    <i class="bi bi-arrow-left-right"></i>
                                </div>

                                <div class="detail-content">

                                    <div class="detail-label">
                                        Total Issue Records
                                    </div>

                                    <div class="d-flex align-items-center gap-2 mt-1">

                                        <span class="issue-count">
                                            {{ $issueCount }}
                                        </span>

                                        <span class="text-muted small">
                                            {{ \Illuminate\Support\Str::plural('record', $issueCount) }}
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Remarks --}}
                        <div class="col-12 col-md-6">

                            <div class="detail-card h-100">

                                <div class="detail-icon">
                                    <i class="bi bi-journal-text"></i>
                                </div>

                                <div class="detail-content">

                                    <div class="detail-label">
                                        Remarks
                                    </div>

                                    <div class="detail-value detail-text">

                                        {{ $temporaryItem->remarks ?: 'No remarks provided.' }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            RIGHT SIDEBAR
        ====================================================== --}}
        <div class="col-12 col-xl-4">

            {{-- Record Summary --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-header bg-white border-bottom py-3 px-4">

                    <div class="d-flex align-items-center">

                        <div class="section-icon me-3">
                            <i class="bi bi-clipboard-data"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">
                                Record Summary
                            </h6>

                            <small class="text-muted">
                                Current temporary item overview
                            </small>

                        </div>

                    </div>

                </div>

                <div class="card-body p-4">

                    <div class="summary-row">

                        <span class="summary-label">
                            Item Code
                        </span>

                        <span class="summary-value">
                            {{ $temporaryItem->item_code }}
                        </span>

                    </div>

                    <div class="summary-row">

                        <span class="summary-label">
                            Item Type
                        </span>

                        <span class="summary-value">
                            {{ $temporaryItem->item_type }}
                        </span>

                    </div>

                    <div class="summary-row">

                        <span class="summary-label">
                            Issue Records
                        </span>

                        <span class="summary-value">

                            <span class="badge bg-light text-dark border rounded-pill px-3">
                                {{ $issueCount }}
                            </span>

                        </span>

                    </div>

                    <div class="summary-row mb-0">

                        <span class="summary-label">
                            Status
                        </span>

                        <span class="summary-value">

                            @if($temporaryItem->status === 'available')

                                <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                    Available
                                </span>

                            @elseif($temporaryItem->status === 'issued')

                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">
                                    Issued
                                </span>

                            @else

                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">
                                    Inactive
                                </span>

                            @endif

                        </span>

                    </div>

                </div>

            </div>


            {{-- Audit Information --}}
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-bottom py-3 px-4">

                    <div class="d-flex align-items-center">

                        <div class="section-icon me-3">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">
                                Record Information
                            </h6>

                            <small class="text-muted">
                                Audit and timestamp details
                            </small>

                        </div>

                    </div>

                </div>

                <div class="card-body p-4">

                    {{-- Created By --}}
                    <div class="audit-item">

                        <div class="audit-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>

                        <div>

                            <div class="audit-label">
                                Created By
                            </div>

                            <div class="audit-value">
                                {{ $temporaryItem->creator?->name ?? 'System' }}
                            </div>

                            @if($temporaryItem->created_at)

                                <div class="audit-time">
                                    {{ $temporaryItem->created_at->format('d M Y, h:i A') }}
                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- Updated By --}}
                    <div class="audit-item mb-0">

                        <div class="audit-icon">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                        <div>

                            <div class="audit-label">
                                Last Updated By
                            </div>

                            <div class="audit-value">
                                {{ $temporaryItem->updater?->name ?? 'System' }}
                            </div>

                            @if($temporaryItem->updated_at)

                                <div class="audit-time">
                                    {{ $temporaryItem->updated_at->format('d M Y, h:i A') }}
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ACTIONS
    ========================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4">

        <div class="card-body p-3 p-md-4">

            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center justify-content-between gap-3">

                <div class="action-info">

                    <div class="d-flex align-items-center gap-2">

                        <span class="action-info-icon">
                            <i class="bi bi-shield-check"></i>
                        </span>

                        <div>

                            <div class="fw-semibold">
                                Temporary Item Management
                            </div>

                            <div class="small text-muted">
                                Update or remove this temporary item record.
                            </div>

                        </div>

                    </div>

                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">

                    <a href="{{ route('admin.inventory.temporary-items.edit', $temporaryItem) }}"
                       class="btn btn-light border px-4">

                        <i class="bi bi-pencil me-1"></i>
                        Edit Item

                    </a>


                    <form method="POST"
                          action="{{ route('admin.inventory.temporary-items.destroy', $temporaryItem) }}"
                          id="deleteTemporaryItemForm">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-outline-danger px-4 w-100">

                            <i class="bi bi-trash3 me-1"></i>
                            Delete / Deactivate

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
    PAGE STYLES
============================================================= --}}
@push('styles')

<style>

    /* ---------------------------------------------------------
       PAGE HEADER
    --------------------------------------------------------- */

    .page-icon {

        width: 42px;
        height: 42px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 12px;

        background: rgba(13, 110, 253, 0.10);
        color: #0d6efd;

        font-size: 1.15rem;

    }


    /* ---------------------------------------------------------
       SECTION ICON
    --------------------------------------------------------- */

    .section-icon {

        width: 38px;
        height: 38px;
        min-width: 38px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;

    }


    /* ---------------------------------------------------------
       STATUS BANNER
    --------------------------------------------------------- */

    .status-banner {

        padding: 20px 24px;

        border-left: 4px solid transparent;

    }

    .status-available {

        background: rgba(25, 135, 84, 0.045);
        border-left-color: #198754;

    }

    .status-issued {

        background: rgba(255, 193, 7, 0.06);
        border-left-color: #ffc107;

    }

    .status-inactive {

        background: rgba(108, 117, 125, 0.045);
        border-left-color: #6c757d;

    }

    .status-icon {

        width: 44px;
        height: 44px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 12px;

        background: #ffffff;

        box-shadow: 0 2px 8px rgba(16, 24, 40, 0.06);

        font-size: 1.2rem;

    }

    .status-available .status-icon {
        color: #198754;
    }

    .status-issued .status-icon {
        color: #b58100;
    }

    .status-inactive .status-icon {
        color: #6c757d;
    }

    .status-badge {

        font-size: 0.8rem;

        padding: 7px 13px;

    }

    .status-description {

        color: #667085;

        font-size: 0.875rem;

    }


    /* ---------------------------------------------------------
       ITEM DETAIL CARDS
    --------------------------------------------------------- */

    .detail-card {

        display: flex;
        align-items: flex-start;

        gap: 14px;

        padding: 18px;

        background: #ffffff;

        border: 1px solid #e7ebf0;

        border-radius: 14px;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            transform 0.2s ease;

    }

    .detail-card:hover {

        border-color: #d5dce5;

        box-shadow: 0 4px 14px rgba(16, 24, 40, 0.05);

        transform: translateY(-1px);

    }

    .detail-card-large {

        min-height: 120px;

    }

    .detail-icon {

        width: 38px;
        height: 38px;
        min-width: 38px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #f5f8fc;

        color: #0d6efd;

        font-size: 1rem;

    }

    .detail-content {

        min-width: 0;

        flex: 1;

    }

    .detail-label {

        color: #667085;

        font-size: 0.78rem;

        font-weight: 500;

        text-transform: uppercase;

        letter-spacing: 0.035em;

        margin-bottom: 5px;

    }

    .detail-value {

        color: #344054;

        font-size: 0.95rem;

        font-weight: 600;

        word-break: break-word;

    }

    .detail-text {

        line-height: 1.65;

        font-weight: 400;

        white-space: pre-line;

    }


    /* ---------------------------------------------------------
       ISSUE COUNT
    --------------------------------------------------------- */

    .issue-count {

        color: #0d6efd;

        font-size: 1.65rem;

        font-weight: 700;

        line-height: 1;

    }


    /* ---------------------------------------------------------
       SUMMARY
    --------------------------------------------------------- */

    .summary-row {

        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 15px;

        padding: 13px 0;

        border-bottom: 1px solid #edf0f4;

    }

    .summary-label {

        color: #667085;

        font-size: 0.875rem;

    }

    .summary-value {

        color: #344054;

        font-size: 0.875rem;

        font-weight: 600;

        text-align: right;

        max-width: 60%;

        word-break: break-word;

    }


    /* ---------------------------------------------------------
       AUDIT INFORMATION
    --------------------------------------------------------- */

    .audit-item {

        display: flex;

        align-items: flex-start;

        gap: 13px;

        padding-bottom: 18px;

        margin-bottom: 18px;

        border-bottom: 1px solid #edf0f4;

    }

    .audit-icon {

        width: 36px;
        height: 36px;
        min-width: 36px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #f5f7fa;

        color: #0d6efd;

    }

    .audit-label {

        color: #667085;

        font-size: 0.78rem;

        margin-bottom: 2px;

    }

    .audit-value {

        color: #344054;

        font-size: 0.9rem;

        font-weight: 600;

        word-break: break-word;

    }

    .audit-time {

        color: #98a2b3;

        font-size: 0.76rem;

        margin-top: 3px;

    }


    /* ---------------------------------------------------------
       ACTION AREA
    --------------------------------------------------------- */

    .action-info-icon {

        width: 38px;
        height: 38px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: rgba(13, 110, 253, 0.08);

        color: #0d6efd;

        flex-shrink: 0;

    }


    /* ---------------------------------------------------------
       GENERAL
    --------------------------------------------------------- */

    .card {

        transition: box-shadow 0.2s ease;

    }

    .badge.bg-success-subtle {

        background-color: rgba(25, 135, 84, 0.10) !important;

    }

    .badge.bg-warning-subtle {

        background-color: rgba(255, 193, 7, 0.14) !important;

    }

    .badge.bg-secondary-subtle {

        background-color: rgba(108, 117, 125, 0.12) !important;

    }


    /* ---------------------------------------------------------
       RESPONSIVE
    --------------------------------------------------------- */

    @media (max-width: 991.98px) {

        .status-description {

            padding-left: 0;

        }

    }


    @media (max-width: 575.98px) {

        .container-fluid {

            padding-left: 12px !important;
            padding-right: 12px !important;

        }

        .page-icon {

            width: 38px;
            height: 38px;

        }

        h4 {

            font-size: 1.2rem;

        }

        .card-body {

            padding: 1.1rem !important;

        }

        .card-header {

            padding-left: 1.1rem !important;
            padding-right: 1.1rem !important;

        }

        .status-banner {

            padding: 16px;

        }

        .status-icon {

            width: 40px;
            height: 40px;

        }

        .detail-card {

            padding: 15px;

        }

        .summary-row {

            gap: 10px;

        }

        .summary-value {

            max-width: 58%;

        }

        .btn {

            min-height: 44px;

        }

    }

</style>

@endpush


{{-- =============================================================
    PAGE SCRIPTS
============================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const deleteForm =
        document.getElementById('deleteTemporaryItemForm');

    if (!deleteForm) {
        return;
    }

    deleteForm.addEventListener('submit', function (event) {

        const confirmed = window.confirm(
            'Are you sure you want to delete this temporary item?\n\n' +
            'If this item has existing issue history, it will be deactivated instead of permanently deleted.'
        );

        if (!confirmed) {
            event.preventDefault();
            return;
        }

        const button = deleteForm.querySelector(
            'button[type="submit"]'
        );

        if (button) {

            button.disabled = true;

            button.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2" ' +
                'role="status" aria-hidden="true"></span>' +
                'Processing...';

        }

    });

});

</script>

@endpush

@endsection

