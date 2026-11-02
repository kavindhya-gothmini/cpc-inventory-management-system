@extends('layouts.admin')

@section('title', 'Temporary Item Issue Details | CPC Inventory Management System')

@section('page-heading', 'Temporary Item Issue Details')

@section('content')

<style>
    /* =========================================================
       CPC TEMPORARY ITEM ISSUE - SHOW
    ========================================================= */

    .temporary-show-page {
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
        opacity: 0.9;
    }

    .header-reference {
        padding: 8px 13px;
        border: 1px solid rgba(255,255,255,.25);
        background: rgba(255,255,255,.12);
        border-radius: 8px;
        font-size: 12px;
        white-space: nowrap;
    }

    .alert {
        border-radius: 10px;
        font-size: 13px;
    }

    .details-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .details-card-header {
        padding: 18px 22px;
        background: #fafbfc;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }

    .details-card-header h5 {
        margin: 0;
        color: #212529;
        font-size: 16px;
        font-weight: 700;
    }

    .details-card-body {
        padding: 22px;
    }

    .detail-item {
        height: 100%;
        padding: 14px;
        background: #f8f9fa;
        border: 1px solid #edf0f2;
        border-radius: 10px;
    }

    .detail-label {
        display: block;
        color: #6c757d;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 6px;
    }

    .detail-value {
        display: block;
        color: #212529;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.45;
        word-break: break-word;
    }

    .detail-secondary {
        display: block;
        color: #6c757d;
        font-size: 11px;
        margin-top: 3px;
    }

    .employee-card {
        height: 100%;
        padding: 16px;
        background: #fff;
        border: 1px solid #e1e5eb;
        border-radius: 11px;
    }

    .employee-role {
        color: #6c757d;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 8px;
    }

    .employee-name {
        color: #212529;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .employee-epf {
        color: #0d6efd;
        font-size: 12px;
        font-weight: 600;
    }

    .employee-epf i {
        margin-right: 4px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-issued {
        background: #cfe2ff;
        color: #084298;
    }

    .status-received {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #842029;
    }

    .workflow-container {
        padding: 18px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 12px;
    }

    .workflow-title {
        font-size: 13px;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 12px;
    }

    .workflow-description {
        margin-bottom: 15px;
        color: #6c757d;
        font-size: 12px;
        line-height: 1.5;
    }

    .workflow-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .btn {
        min-height: 40px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 15px;
    }

    .btn-primary {
        box-shadow: 0 4px 10px rgba(13, 110, 253, .15);
    }

    .receive-form {
        margin-top: 16px;
        padding: 18px;
        background: #fff;
        border: 1px solid #badbcc;
        border-radius: 11px;
    }

    .receive-form h6 {
        margin-bottom: 4px;
        color: #0f5132;
        font-size: 14px;
        font-weight: 700;
    }

    .receive-form-description {
        margin-bottom: 16px;
        color: #6c757d;
        font-size: 11px;
    }

    .form-label {
        color: #343a40;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .required-star {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        min-height: 42px;
        border-radius: 8px;
        border: 1px solid #d7dce5;
        font-size: 13px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .2rem rgba(13,110,253,.12);
    }

    .timeline {
        position: relative;
        padding-left: 26px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 4px;
        bottom: 4px;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 22px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -25px;
        top: 2px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #0d6efd;
        border: 3px solid #fff;
        box-shadow: 0 0 0 1px #cfe2ff;
    }

    .timeline-action {
        color: #212529;
        font-size: 13px;
        font-weight: 700;
    }

    .timeline-description {
        color: #6c757d;
        font-size: 12px;
        margin-top: 3px;
        line-height: 1.45;
    }

    .timeline-meta {
        color: #adb5bd;
        font-size: 10px;
        margin-top: 5px;
    }

    .remarks-box {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        color: #495057;
        font-size: 13px;
        line-height: 1.6;
        white-space: pre-line;
    }

    .empty-history {
        padding: 25px;
        text-align: center;
        color: #6c757d;
        font-size: 13px;
    }

    .workflow-status-message {
        margin-top: 12px;
        padding: 12px 14px;
        border-radius: 9px;
        font-size: 12px;
        line-height: 1.5;
    }

    .workflow-status-received {
        background: #eaf7ef;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .workflow-status-cancelled {
        background: #fff0f1;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    @media (max-width: 768px) {

        .temporary-show-page {
            padding: 15px;
        }

        .page-header {
            padding: 20px;
        }

        .page-header-content {
            display: block;
        }

        .page-header h2 {
            font-size: 20px;
        }

        .header-reference {
            display: inline-block;
            margin-top: 12px;
        }

        .details-card-body {
            padding: 16px;
        }

        .workflow-actions {
            display: block;
        }

        .workflow-actions .btn {
            width: 100%;
            margin-bottom: 8px;
        }

        .workflow-actions .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>

<div class="temporary-show-page">


{{-- =====================================================
     PAGE HEADER
====================================================== --}}
<div class="page-header">

    <div class="page-header-content">

        <div>

            <h2>
                <i class="bi bi-file-earmark-text me-2"></i>
                Temporary Item Issue Details
            </h2>

            <p>
                View issue information, employee details and workflow history.
            </p>

        </div>

        <div class="header-reference">
            Issue #{{ $temporaryItemIssue->id }}
        </div>

    </div>

</div>


{{-- =====================================================
     ALERTS
====================================================== --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">

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

    <div class="alert alert-danger alert-dismissible fade show" role="alert">

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


@if($errors->any())

    <div class="alert alert-danger alert-dismissible fade show" role="alert">

        <strong>
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Please correct the following:
        </strong>

        <ul class="mb-0 mt-2 ps-4">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>

@endif


{{-- =====================================================
     BASIC ISSUE INFORMATION
====================================================== --}}
<div class="details-card">

    <div class="details-card-header">

        <h5>
            <i class="bi bi-info-circle me-2"></i>
            Issue Information
        </h5>


        @switch($temporaryItemIssue->status)

            @case('issued')

                <span class="status-badge status-issued">

                    <span class="status-dot"></span>

                    Issued

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

                    {{ ucfirst($temporaryItemIssue->status) }}

                </span>

        @endswitch

    </div>


    <div class="details-card-body">

        <div class="row g-3">

            {{-- Issue Date --}}
            <div class="col-lg-3 col-md-6">

                <div class="detail-item">

                    <span class="detail-label">
                        Issue Date & Time
                    </span>

                    <span class="detail-value">
                        {{ optional($temporaryItemIssue->issue_datetime)->format('d M Y, h:i A') ?? '—' }}
                    </span>

                </div>

            </div>


            {{-- Item Code --}}
            <div class="col-lg-3 col-md-6">

                <div class="detail-item">

                    <span class="detail-label">
                        Temporary Item Code
                    </span>

                    <span class="detail-value">
                        {{ $temporaryItemIssue->item_code }}
                    </span>

                </div>

            </div>


            {{-- Item Type --}}
            <div class="col-lg-3 col-md-6">

                <div class="detail-item">

                    <span class="detail-label">
                        Item Type
                    </span>

                    <span class="detail-value">
                        {{ $temporaryItemIssue->item_type }}
                    </span>

                </div>

            </div>


            {{-- Event --}}
            <div class="col-lg-3 col-md-6">

                <div class="detail-item">

                    <span class="detail-label">
                        Event
                    </span>

                    <span class="detail-value">
                        {{ $temporaryItemIssue->event }}
                    </span>

                </div>

            </div>


            {{-- Department --}}
            <div class="col-lg-6 col-md-6">

                <div class="detail-item">

                    <span class="detail-label">
                        Department
                    </span>

                    <span class="detail-value">

                        @if($temporaryItemIssue->department)

                            {{ $temporaryItemIssue->department->name }}

                            @if($temporaryItemIssue->department->code)

                                <span class="detail-secondary">
                                    Code: {{ $temporaryItemIssue->department->code }}
                                </span>

                            @endif

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </span>

                </div>

            </div>


            {{-- Function Unit --}}
            <div class="col-lg-6 col-md-6">

                <div class="detail-item">

                    <span class="detail-label">
                        Function Unit
                    </span>

                    <span class="detail-value">

                        @if($temporaryItemIssue->functionUnit)

                            {{ $temporaryItemIssue->functionUnit->name }}

                            @if($temporaryItemIssue->functionUnit->code)

                                <span class="detail-secondary">
                                    Code: {{ $temporaryItemIssue->functionUnit->code }}
                                </span>

                            @endif

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     EMPLOYEE INFORMATION
====================================================== --}}
<div class="details-card">

    <div class="details-card-header">

        <h5>
            <i class="bi bi-people me-2"></i>
            Employee Information
        </h5>

    </div>


    <div class="details-card-body">

        <div class="row g-3">

            {{-- Requesting Employee --}}
            <div class="col-lg-6 col-md-6">

                <div class="employee-card">

                    <div class="employee-role">
                        Requesting Employee
                    </div>

                    @if($temporaryItemIssue->requestingEmployee)

                        <div class="employee-name">
                            {{ $temporaryItemIssue->requestingEmployee->name }}
                        </div>

                        <div class="employee-epf">

                            <i class="bi bi-person-badge"></i>

                            EPF:
                            {{ $temporaryItemIssue->requestingEmployee->epf_no }}

                        </div>

                    @else

                        <span class="text-muted small">
                            Employee record unavailable
                        </span>

                    @endif

                </div>

            </div>


            {{-- Collecting Employee --}}
            <div class="col-lg-6 col-md-6">

                <div class="employee-card">

                    <div class="employee-role">
                        Collecting Employee
                    </div>

                    @if($temporaryItemIssue->collectingEmployee)

                        <div class="employee-name">
                            {{ $temporaryItemIssue->collectingEmployee->name }}
                        </div>

                        <div class="employee-epf">

                            <i class="bi bi-person-badge"></i>

                            EPF:
                            {{ $temporaryItemIssue->collectingEmployee->epf_no }}

                        </div>

                    @else

                        <span class="text-muted small">
                            Not available
                        </span>

                    @endif

                </div>

            </div>


            {{-- Returning Employee --}}
            <div class="col-lg-6 col-md-6">

                <div class="employee-card">

                    <div class="employee-role">
                        Returning Employee
                    </div>

                    @if($temporaryItemIssue->returningEmployee)

                        <div class="employee-name">
                            {{ $temporaryItemIssue->returningEmployee->name }}
                        </div>

                        <div class="employee-epf">

                            <i class="bi bi-person-badge"></i>

                            EPF:
                            {{ $temporaryItemIssue->returningEmployee->epf_no }}

                        </div>

                    @else

                        <span class="text-muted small">
                            Not returned yet
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     RECEIVED INFORMATION
====================================================== --}}
@if($temporaryItemIssue->received_datetime)

    <div class="details-card">

        <div class="details-card-header">

            <h5>
                <i class="bi bi-box-arrow-in-down me-2"></i>
                Received Information
            </h5>

        </div>


        <div class="details-card-body">

            <div class="row g-3">

                {{-- Received Date / Time --}}
                <div class="col-lg-6">

                    <div class="detail-item">

                        <span class="detail-label">
                            Received Date & Time
                        </span>

                        <span class="detail-value">
                            {{ $temporaryItemIssue->received_datetime->format('d M Y, h:i A') }}
                        </span>

                    </div>

                </div>


                {{-- Returning Employee --}}
                <div class="col-lg-6">

                    <div class="detail-item">

                        <span class="detail-label">
                            Returning Employee
                        </span>

                        <span class="detail-value">

                            @if($temporaryItemIssue->returningEmployee)

                                {{ $temporaryItemIssue->returningEmployee->name }}

                                <span class="detail-secondary">
                                    EPF:
                                    {{ $temporaryItemIssue->returningEmployee->epf_no }}
                                </span>

                            @else

                                Not available

                            @endif

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif


{{-- =====================================================
     WORKFLOW ACTIONS
====================================================== --}}
<div class="details-card">

    <div class="details-card-header">

        <h5>
            <i class="bi bi-arrow-repeat me-2"></i>
            Temporary Item Workflow
        </h5>

    </div>


    <div class="details-card-body">

        <div class="workflow-container">

            <div class="workflow-title">
                Available Actions
            </div>


            <div class="workflow-description">

                @if($temporaryItemIssue->status === 'issued')

                    This temporary item issue is currently
                    <strong>issued</strong>.
                    You can edit the record, record the returned item
                    using the Receive action, or cancel the issue.

                @elseif($temporaryItemIssue->status === 'received')

                    This temporary item issue has been completed and
                    the item has been received by the IT Function.

                @elseif($temporaryItemIssue->status === 'cancelled')

                    This temporary item issue has been cancelled and
                    no further workflow action is available.

                @else

                    Current status:
                    {{ ucfirst($temporaryItemIssue->status) }}

                @endif

            </div>


            <div class="workflow-actions">

                {{-- Edit --}}
                <a
                    href="{{ route(
                        'admin.temporary-item-issues.edit',
                        $temporaryItemIssue
                    ) }}"
                    class="btn btn-outline-secondary">

                    <i class="bi bi-pencil me-1"></i>
                    Edit

                </a>


                {{-- Receive --}}
                @if($temporaryItemIssue->status === 'issued')

                    <button
                        type="button"
                        class="btn btn-success"
                        onclick="document.getElementById('receive-item').scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        })">

                        <i class="bi bi-box-arrow-in-down me-1"></i>
                        Receive Item

                    </button>

                @endif


                {{-- History --}}
                <a
                    href="{{ route(
                        'admin.temporary-item-issues.history.show',
                        $temporaryItemIssue
                    ) }}"
                    class="btn btn-outline-dark">

                    <i class="bi bi-clock-history me-1"></i>
                    View History

                </a>


                {{-- Cancel --}}
                @if($temporaryItemIssue->status === 'issued')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.temporary-item-issues.cancel',
                            $temporaryItemIssue
                        ) }}"
                        class="d-inline">

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="btn btn-outline-danger"
                            onclick="return confirm(
                                'Are you sure you want to cancel this temporary item issue?'
                            )">

                            <i class="bi bi-x-circle me-1"></i>
                            Cancel Issue

                        </button>

                    </form>

                @endif


                {{-- Delete --}}
                <form
                    method="POST"
                    action="{{ route(
                        'admin.temporary-item-issues.destroy',
                        $temporaryItemIssue
                    ) }}"
                    class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                        onclick="return confirm(
                            'Are you sure you want to permanently delete this temporary item issue?'
                        )">

                        <i class="bi bi-trash me-1"></i>
                        Delete

                    </button>

                </form>

            </div>


            {{-- =================================================
                 RECEIVE FORM
            ================================================== --}}
            @if($temporaryItemIssue->status === 'issued')

                <div
                    class="receive-form"
                    id="receive-item">

                    <h6>
                        <i class="bi bi-box-arrow-in-down me-1"></i>
                        Record Item Return / Receipt
                    </h6>

                    <div class="receive-form-description">

                        Enter the date/time and employee who returned
                        the temporary item to the IT Function.

                    </div>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.temporary-item-issues.receive',
                            $temporaryItemIssue
                        ) }}">

                        @csrf
                        @method('PATCH')


                        <div class="row g-3 align-items-end">

                            {{-- Received Date & Time --}}
                            <div class="col-lg-5">

                                <label
                                    for="received_datetime"
                                    class="form-label">

                                    Received Date & Time
                                    <span class="required-star">*</span>

                                </label>

                                <input
                                    type="datetime-local"
                                    name="received_datetime"
                                    id="received_datetime"
                                    class="form-control @error('received_datetime') is-invalid @enderror"
                                    value="{{ old(
                                        'received_datetime',
                                        now()->format('Y-m-d\TH:i')
                                    ) }}"
                                    required>

                                @error('received_datetime')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Returning Employee --}}
                            <div class="col-lg-5">

                                <label
                                    for="returning_employee_id"
                                    class="form-label">

                                    Returning Employee
                                    <span class="required-star">*</span>

                                </label>

                                <select
                                    name="returning_employee_id"
                                    id="returning_employee_id"
                                    class="form-select @error('returning_employee_id') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        Select returning employee
                                    </option>


                                    @php

                                        $activeEmployees =
                                            \App\Models\Employee::where(
                                                'is_active',
                                                true
                                            )
                                            ->orderBy('name')
                                            ->get();

                                    @endphp


                                    @foreach($activeEmployees as $employee)

                                        <option
                                            value="{{ $employee->id }}"
                                            {{ (string) old(
                                                'returning_employee_id'
                                            ) === (string) $employee->id
                                                ? 'selected'
                                                : '' }}>

                                            {{ $employee->name }}
                                            — EPF:
                                            {{ $employee->epf_no }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('returning_employee_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Submit --}}
                            <div class="col-lg-2">

                                <button
                                    type="submit"
                                    class="btn btn-success w-100">

                                    <i class="bi bi-check-circle me-1"></i>
                                    Receive

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            @elseif($temporaryItemIssue->status === 'received')

                <div class="workflow-status-message workflow-status-received">

                    <i class="bi bi-check-circle-fill me-1"></i>

                    <strong>Completed:</strong>

                    This temporary item has already been received.
                    The returning employee and received date/time are
                    recorded above.

                </div>

            @elseif($temporaryItemIssue->status === 'cancelled')

                <div class="workflow-status-message workflow-status-cancelled">

                    <i class="bi bi-x-circle-fill me-1"></i>

                    <strong>Cancelled:</strong>

                    This temporary item issue has been cancelled.
                    No receiving action is available.

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =====================================================
     REMARKS
====================================================== --}}
@if($temporaryItemIssue->remarks)

    <div class="details-card">

        <div class="details-card-header">

            <h5>
                <i class="bi bi-chat-left-text me-2"></i>
                Remarks
            </h5>

        </div>


        <div class="details-card-body">

            <div class="remarks-box">
                {{ $temporaryItemIssue->remarks }}
            </div>

        </div>

    </div>

@endif


{{-- =====================================================
     RECENT HISTORY
====================================================== --}}
<div class="details-card">

    <div class="details-card-header">

        <h5>
            <i class="bi bi-clock-history me-2"></i>
            Recent History
        </h5>

        <a
            href="{{ route(
                'admin.temporary-item-issues.history.show',
                $temporaryItemIssue
            ) }}"
            class="btn btn-sm btn-outline-dark">

            View Full History

        </a>

    </div>


    <div class="details-card-body">

        @if($temporaryItemIssue->histories->count())

            <div class="timeline">

                @foreach(
                    $temporaryItemIssue->histories->take(5)
                    as $history
                )

                    <div class="timeline-item">

                        <div class="timeline-dot"></div>

                        <div class="timeline-action">

                            {{ ucwords(str_replace(
                                '_',
                                ' ',
                                $history->action
                            )) }}

                        </div>


                        @if($history->description)

                            <div class="timeline-description">
                                {{ $history->description }}
                            </div>

                        @endif


                        <div class="timeline-meta">

                            {{ optional(
                                $history->changed_at
                            )->format(
                                'd M Y, h:i A'
                            ) }}

                            @if($history->changed_by_name)

                                ·
                                {{ $history->changed_by_name }}

                                @if($history->changed_by_epf)

                                    (EPF:
                                    {{ $history->changed_by_epf }})

                                @endif

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-history">
                No history has been recorded yet.
            </div>

        @endif

    </div>

</div>


{{-- =====================================================
     FOOTER ACTIONS
====================================================== --}}
<div class="d-flex flex-wrap gap-2 mb-4">

    <a
        href="{{ route(
            'admin.temporary-item-issues.index'
        ) }}"
        class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left me-1"></i>
        Back to Temporary Item Issues

    </a>


    <a
        href="{{ route(
            'admin.temporary-item-issues.edit',
            $temporaryItemIssue
        ) }}"
        class="btn btn-primary">

        <i class="bi bi-pencil me-1"></i>
        Edit Issue

    </a>

</div>


</div>

@endsection
