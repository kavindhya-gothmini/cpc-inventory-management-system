@extends('layouts.admin')

@section('title', 'Edit Temporary Item Issue | CPC Inventory Management System')

@section('page-heading', 'Edit Temporary Item Issue')

@section('content')

<style>
    /* =========================================================
       CPC TEMPORARY ITEM ISSUE - EDIT
    ========================================================= */

    .temporary-edit-page {
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

    .header-id {
        padding: 8px 12px;
        border: 1px solid rgba(255,255,255,.25);
        background: rgba(255,255,255,.12);
        border-radius: 8px;
        font-size: 12px;
        white-space: nowrap;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .form-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #edf0f2;
        background: #fafbfc;
    }

    .form-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }

    .form-card-header p {
        margin: 4px 0 0;
        color: #6c757d;
        font-size: 12px;
    }

    .form-card-body {
        padding: 24px 22px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 7px;
    }

    .required-star {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border: 1px solid #d7dce5;
        border-radius: 9px;
        font-size: 14px;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }

    .form-text {
        font-size: 11px;
        color: #6c757d;
        margin-top: 5px;
    }

    .workflow-note {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 14px 16px;
        background: #eef6ff;
        border: 1px solid #cfe2ff;
        border-radius: 10px;
        margin-bottom: 22px;
    }

    .workflow-note-icon {
        color: #0d6efd;
        font-size: 19px;
        flex-shrink: 0;
    }

    .workflow-note strong {
        display: block;
        color: #084298;
        font-size: 13px;
        margin-bottom: 3px;
    }

    .workflow-note span {
        color: #495057;
        font-size: 12px;
        line-height: 1.5;
    }

    .section-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        margin-right: 8px;
    }

    .received-section {
        background: #f0fff7;
        border: 1px solid #badbcc;
        border-radius: 10px;
        padding: 16px;
    }

    .received-section-title {
        color: #0f5132;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .received-section-text {
        color: #49665a;
        font-size: 11px;
        margin-bottom: 14px;
    }

    .status-display {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
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

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 18px 22px;
        border-top: 1px solid #edf0f2;
        background: #fafbfc;
    }

    .btn {
        min-height: 42px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
        padding: 8px 18px;
    }

    .btn-primary {
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.18);
    }

    .alert {
        border-radius: 10px;
        font-size: 13px;
    }

    .invalid-feedback {
        font-size: 11px;
    }

    @media (max-width: 768px) {

        .temporary-edit-page {
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

        .header-id {
            display: inline-block;
            margin-top: 12px;
        }

        .form-card-body {
            padding: 18px 16px;
        }

        .form-actions {
            display: block;
        }

        .form-actions .btn {
            width: 100%;
            margin-bottom: 8px;
        }

        .form-actions .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>

<div class="temporary-edit-page">


{{-- =====================================================
     PAGE HEADER
====================================================== --}}
<div class="page-header">

    <div class="page-header-content">

        <div>

            <h2>
                <i class="bi bi-pencil-square me-2"></i>
                Edit Temporary Item Issue
            </h2>

            <p>
                Update the temporary item issue information.
            </p>

        </div>

        <div class="header-id">
            Issue #{{ $temporaryItemIssue->id }}
        </div>

    </div>

</div>


{{-- =====================================================
     VALIDATION ERRORS
====================================================== --}}
@if($errors->any())

    <div class="alert alert-danger alert-dismissible fade show" role="alert">

        <div class="fw-bold mb-1">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Please correct the following errors:
        </div>

        <ul class="mb-0 ps-4">

            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
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
     WORKFLOW INFORMATION
====================================================== --}}
<div class="workflow-note">

    <div class="workflow-note-icon">
        <i class="bi bi-info-circle-fill"></i>
    </div>

    <div>

        <strong>Temporary Item Workflow</strong>

        <span>
            Editing this page changes the original issue information.
            The requesting employee and collecting employee identify
            the people involved when the item is issued. When the item
            is returned to the IT Function, use the
            <strong>Receive</strong> workflow action to record the
            returning employee and returned date/time. An issued issue
            can also be cancelled before it is returned.
        </span>

    </div>

</div>


{{-- =====================================================
     MAIN FORM
====================================================== --}}
<form
    method="POST"
    action="{{ route(
        'admin.temporary-item-issues.update',
        $temporaryItemIssue
    ) }}">

    @csrf
    @method('PUT')


    {{-- =================================================
         ISSUE INFORMATION
    ================================================== --}}
    <div class="form-card">

        <div class="form-card-header">

            <h5>
                <span class="section-number">1</span>
                Issue Information
            </h5>

            <p>
                Update the basic details of the temporary item issue.
            </p>

        </div>


        <div class="form-card-body">

            <div class="row g-4">

                {{-- Issue Date & Time --}}
                <div class="col-lg-6">

                    <label
                        for="issue_datetime"
                        class="form-label">

                        Issue Date & Time
                        <span class="required-star">*</span>

                    </label>

                    <input
                        type="datetime-local"
                        name="issue_datetime"
                        id="issue_datetime"
                        class="form-control @error('issue_datetime') is-invalid @enderror"
                        value="{{ old(
                            'issue_datetime',
                            optional($temporaryItemIssue->issue_datetime)->format('Y-m-d\TH:i')
                        ) }}"
                        required>

                    @error('issue_datetime')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Item Code --}}
                <div class="col-lg-6">

                    <label
                        for="item_code"
                        class="form-label">

                        Temporary Item Code
                        <span class="required-star">*</span>

                    </label>

                    <input
                        type="text"
                        name="item_code"
                        id="item_code"
                        class="form-control @error('item_code') is-invalid @enderror"
                        value="{{ old(
                            'item_code',
                            $temporaryItemIssue->item_code
                        ) }}"
                        maxlength="255"
                        required>

                    <div class="form-text">
                        Temporary item codes are independent of the
                        permanent inventory item records.
                    </div>

                    @error('item_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Item Type --}}
                <div class="col-lg-6">

                    <label
                        for="item_type"
                        class="form-label">

                        Item Type
                        <span class="required-star">*</span>

                    </label>

                    <select
                        name="item_type"
                        id="item_type"
                        class="form-select @error('item_type') is-invalid @enderror"
                        required>

                        <option value="">
                            Select item type
                        </option>

                        @foreach($itemTypes as $itemType)

                            <option
                                value="{{ $itemType }}"
                                {{ old(
                                    'item_type',
                                    $temporaryItemIssue->item_type
                                ) === $itemType ? 'selected' : '' }}>

                                {{ $itemType }}

                            </option>

                        @endforeach

                    </select>

                    @error('item_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Event --}}
                <div class="col-lg-6">

                    <label
                        for="event"
                        class="form-label">

                        Event
                        <span class="required-star">*</span>

                    </label>

                    <input
                        type="text"
                        name="event"
                        id="event"
                        class="form-control @error('event') is-invalid @enderror"
                        value="{{ old(
                            'event',
                            $temporaryItemIssue->event
                        ) }}"
                        maxlength="255"
                        required>

                    @error('event')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>

    </div>


    {{-- =================================================
         LOCATION INFORMATION
    ================================================== --}}
    <div class="form-card">

        <div class="form-card-header">

            <h5>
                <span class="section-number">2</span>
                Department / Function Unit
            </h5>

            <p>
                Update the organizational area associated with this issue.
            </p>

        </div>


        <div class="form-card-body">

            <div class="row g-4">

                {{-- Department --}}
                <div class="col-lg-6">

                    <label
                        for="department_id"
                        class="form-label">

                        Department

                    </label>

                    <select
                        name="department_id"
                        id="department_id"
                        class="form-select @error('department_id') is-invalid @enderror">

                        <option value="">
                            Select department
                        </option>

                        @foreach($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                {{ (string) old(
                                    'department_id',
                                    $temporaryItemIssue->department_id
                                ) === (string) $department->id
                                    ? 'selected'
                                    : '' }}>

                                {{ $department->name }}

                                @if($department->code)
                                    ({{ $department->code }})
                                @endif

                            </option>

                        @endforeach

                    </select>

                    @error('department_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Function Unit --}}
                <div class="col-lg-6">

                    <label
                        for="function_unit_id"
                        class="form-label">

                        Function Unit

                    </label>

                    <select
                        name="function_unit_id"
                        id="function_unit_id"
                        class="form-select @error('function_unit_id') is-invalid @enderror">

                        <option value="">
                            Select function unit
                        </option>

                        @foreach($functionUnits as $functionUnit)

                            <option
                                value="{{ $functionUnit->id }}"
                                {{ (string) old(
                                    'function_unit_id',
                                    $temporaryItemIssue->function_unit_id
                                ) === (string) $functionUnit->id
                                    ? 'selected'
                                    : '' }}>

                                {{ $functionUnit->name }}

                                @if($functionUnit->code)
                                    ({{ $functionUnit->code }})
                                @endif

                            </option>

                        @endforeach

                    </select>

                    @error('function_unit_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Select the applicable department or function unit.
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =================================================
         EMPLOYEE INFORMATION
    ================================================== --}}
    <div class="form-card">

        <div class="form-card-header">

            <h5>
                <span class="section-number">3</span>
                Employee Information
            </h5>

            <p>
                Record the employee who requested the item and the
                employee who collected it from the IT Function.
            </p>

        </div>


        <div class="form-card-body">

            <div class="row g-4">

                {{-- Requesting Employee --}}
                <div class="col-lg-6">

                    <label
                        for="requesting_employee_id"
                        class="form-label">

                        Requesting Employee
                        <span class="required-star">*</span>

                    </label>

                    <select
                        name="requesting_employee_id"
                        id="requesting_employee_id"
                        class="form-select @error('requesting_employee_id') is-invalid @enderror"
                        required>

                        <option value="">
                            Select requesting employee
                        </option>

                        @foreach($employees as $employee)

                            <option
                                value="{{ $employee->id }}"
                                {{ (string) old(
                                    'requesting_employee_id',
                                    $temporaryItemIssue->requesting_employee_id
                                ) === (string) $employee->id
                                    ? 'selected'
                                    : '' }}>

                                {{ $employee->name }}
                                — EPF: {{ $employee->epf_no }}

                            </option>

                        @endforeach

                    </select>

                    <div class="form-text">
                        Employee who requested the temporary item.
                    </div>

                    @error('requesting_employee_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Collecting Employee --}}
                <div class="col-lg-6">

                    <label
                        for="collecting_employee_id"
                        class="form-label">

                        Collecting Employee
                        <span class="required-star">*</span>

                    </label>

                    <select
                        name="collecting_employee_id"
                        id="collecting_employee_id"
                        class="form-select @error('collecting_employee_id') is-invalid @enderror"
                        required>

                        <option value="">
                            Select collecting employee
                        </option>

                        @foreach($employees as $employee)

                            <option
                                value="{{ $employee->id }}"
                                {{ (string) old(
                                    'collecting_employee_id',
                                    $temporaryItemIssue->collecting_employee_id
                                ) === (string) $employee->id
                                    ? 'selected'
                                    : '' }}>

                                {{ $employee->name }}
                                — EPF: {{ $employee->epf_no }}

                            </option>

                        @endforeach

                    </select>

                    <div class="form-text">
                        Employee who physically collected the temporary
                        item from the IT Function.
                    </div>

                    @error('collecting_employee_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>

    </div>


    {{-- =================================================
         RETURN INFORMATION
    ================================================== --}}
    <div class="form-card">

        <div class="form-card-header">

            <h5>
                <span class="section-number">4</span>
                Return Information
            </h5>

            <p>
                Returning details are recorded when the item is received
                back by the IT Function.
            </p>

        </div>


        <div class="form-card-body">

            @if($temporaryItemIssue->status === 'received')

                <div class="received-section">

                    <div class="received-section-title">

                        <i class="bi bi-check-circle-fill me-1"></i>
                        Item Received

                    </div>

                    <div class="received-section-text">
                        This temporary item has already been returned
                        to and received by the IT Function.
                    </div>

                    <div class="row g-4">

                        {{-- Returning Employee --}}
                        <div class="col-lg-6">

                            <label class="form-label">
                                Returning Employee
                            </label>

                            <div class="form-control bg-light">

                                @if($temporaryItemIssue->returningEmployee)

                                    {{ $temporaryItemIssue->returningEmployee->name }}
                                    — EPF:
                                    {{ $temporaryItemIssue->returningEmployee->epf_no }}

                                @else

                                    <span class="text-muted">
                                        Not available
                                    </span>

                                @endif

                            </div>

                            <div class="form-text">
                                Employee who physically returned the item
                                to the IT Function.
                            </div>

                        </div>


                        {{-- Received Date & Time --}}
                        <div class="col-lg-6">

                            <label class="form-label">
                                Received Date & Time
                            </label>

                            <div class="form-control bg-light">

                                @if($temporaryItemIssue->received_datetime)

                                    {{ $temporaryItemIssue->received_datetime->format('d M Y, h:i A') }}

                                @else

                                    <span class="text-muted">
                                        Not available
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            @else

                <div class="text-muted small">

                    <i class="bi bi-info-circle me-1"></i>

                    The returning employee and received date/time will be
                    recorded when the item is returned using the
                    <strong>Receive</strong> action.

                </div>

            @endif

        </div>

    </div>


    {{-- =================================================
         STATUS
    ================================================== --}}
    <div class="form-card">

        <div class="form-card-header">

            <h5>
                <span class="section-number">5</span>
                Current Status
            </h5>

            <p>
                The status is controlled by the temporary item workflow.
            </p>

        </div>


        <div class="form-card-body">

            @switch($temporaryItemIssue->status)

                @case('issued')

                    <span class="status-display status-issued">
                        <span class="status-dot"></span>
                        Issued
                    </span>

                    @break

                @case('received')

                    <span class="status-display status-received">
                        <span class="status-dot"></span>
                        Received
                    </span>

                    @break

                @case('cancelled')

                    <span class="status-display status-cancelled">
                        <span class="status-dot"></span>
                        Cancelled
                    </span>

                    @break

                @default

                    <span class="status-display">
                        {{ ucfirst($temporaryItemIssue->status) }}
                    </span>

            @endswitch

            <div class="form-text">
                Status cannot be changed directly from this form.
                Use the appropriate workflow action.
            </div>

        </div>

    </div>


    {{-- =================================================
         REMARKS
    ================================================== --}}
    <div class="form-card">

        <div class="form-card-header">

            <h5>
                <span class="section-number">6</span>
                Additional Information
            </h5>

            <p>
                Update any relevant notes about this temporary issue.
            </p>

        </div>


        <div class="form-card-body">

            <label
                for="remarks"
                class="form-label">

                Remarks

            </label>

            <textarea
                name="remarks"
                id="remarks"
                class="form-control @error('remarks') is-invalid @enderror"
                maxlength="5000"
                placeholder="Enter any additional information or remarks...">{{ old(
                    'remarks',
                    $temporaryItemIssue->remarks
                ) }}</textarea>

            @error('remarks')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Optional. Maximum 5,000 characters.
            </div>

        </div>

    </div>


    {{-- =================================================
         FORM ACTIONS
    ================================================== --}}
    <div class="form-card">

        <div class="form-actions">

            <a
                href="{{ route(
                    'admin.temporary-item-issues.show',
                    $temporaryItemIssue
                ) }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-primary">

                <i class="bi bi-check-circle me-1"></i>
                Save Changes

            </button>

        </div>

    </div>

</form>


</div>

@endsection

