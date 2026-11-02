
@extends('layouts.admin')

@section('title', 'Edit RDD Transaction | CPC Inventory Management System')

@section('content')

<style>
    .rdd-edit-page {
        padding: 24px;
        background: #f5f7fb;
        min-height: calc(100vh - 72px);
    }

    .rdd-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .rdd-edit-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .rdd-edit-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: linear-gradient(135deg, #0d6efd, #084298);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 8px 20px rgba(13, 110, 253, .18);
    }

    .rdd-edit-title {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #172033;
    }

    .rdd-edit-subtitle {
        margin: 4px 0 0;
        color: #6c757d;
        font-size: 14px;
    }

    .rdd-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 41px;
        padding: 0 15px;
        border: 1px solid #dfe4eb;
        border-radius: 8px;
        background: #fff;
        color: #596579;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: .2s ease;
    }

    .rdd-back-btn:hover {
        background: #f7f8fa;
        color: #0d6efd;
        border-color: #cbdaf2;
    }

    .rdd-edit-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(300px, .9fr);
        gap: 20px;
        align-items: start;
    }

    .rdd-form-card {
        background: #fff;
        border: 1px solid #e7eaf0;
        border-radius: 13px;
        box-shadow: 0 3px 12px rgba(20, 30, 50, .035);
        overflow: hidden;
    }

    .rdd-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rdd-card-header-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #eaf2ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .rdd-card-header h2 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #263246;
    }

    .rdd-card-header p {
        margin: 2px 0 0;
        font-size: 11px;
        color: #8993a3;
    }

    .rdd-card-body {
        padding: 21px;
    }

    .rdd-form-group {
        margin-bottom: 18px;
    }

    .rdd-form-group:last-child {
        margin-bottom: 0;
    }

    .rdd-label {
        display: block;
        margin-bottom: 7px;
        font-size: 12px;
        font-weight: 700;
        color: #465267;
    }

    .rdd-required {
        color: #dc3545;
    }

    .rdd-form-control,
    .rdd-form-select {
        width: 100%;
        min-height: 42px;
        border: 1px solid #dfe4eb;
        border-radius: 8px;
        padding: 9px 12px;
        color: #263246;
        background: #fff;
        font-size: 13px;
        outline: none;
        transition: .2s ease;
        box-shadow: none;
    }

    .rdd-form-control:focus,
    .rdd-form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .08);
    }

    textarea.rdd-form-control {
        min-height: 105px;
        resize: vertical;
    }

    .rdd-form-help {
        display: block;
        margin-top: 5px;
        color: #8993a3;
        font-size: 11px;
        line-height: 1.45;
    }

    .rdd-form-error {
        margin-top: 5px;
        color: #dc3545;
        font-size: 11px;
    }

    .rdd-two-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .rdd-three-columns {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
    }

    .rdd-section-divider {
        height: 1px;
        background: #edf0f4;
        margin: 22px 0;
    }

    /* =========================================================
       INFORMATION NOTICE
    ========================================================= */

    .rdd-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 14px;
        background: #f5f9ff;
        border: 1px solid #dceaff;
        border-radius: 9px;
        margin-bottom: 20px;
    }

    .rdd-notice-icon {
        color: #0d6efd;
        font-size: 16px;
        margin-top: 1px;
    }

    .rdd-notice-content {
        color: #536174;
        font-size: 12px;
        line-height: 1.55;
    }

    .rdd-notice-content strong {
        color: #263246;
    }

    /* =========================================================
       REFERENCE DISPLAY
    ========================================================= */

    .rdd-reference-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 16px;
        background: #f8faff;
        border: 1px solid #dce7f7;
        border-radius: 9px;
        margin-bottom: 20px;
    }

    .rdd-reference-left {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .rdd-reference-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: #eaf2ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .rdd-reference-label {
        display: block;
        font-size: 10px;
        color: #8993a3;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .rdd-reference-value {
        display: block;
        font-size: 13px;
        color: #263246;
        font-weight: 700;
    }

    .rdd-reference-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 20px;
        background: #fff4d8;
        color: #946200;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .rdd-reference-badge.approved {
        background: #e8f7ee;
        color: #198754;
    }

    /* =========================================================
       ITEM INFORMATION
    ========================================================= */

    .rdd-item-box {
        border: 1px solid #e1e7ef;
        border-radius: 10px;
        background: #fbfcfe;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .rdd-item-box-header {
        padding: 13px 15px;
        border-bottom: 1px solid #e7ebf1;
        display: flex;
        align-items: center;
        gap: 9px;
        background: #f8fafc;
    }

    .rdd-item-box-header-icon {
        width: 31px;
        height: 31px;
        border-radius: 8px;
        background: #eaf2ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .rdd-item-box-header strong {
        font-size: 13px;
        color: #263246;
    }

    .rdd-item-box-body {
        padding: 15px;
    }

    .rdd-item-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .rdd-item-field {
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 7px;
        padding: 9px 10px;
    }

    .rdd-item-field-label {
        display: block;
        color: #8993a3;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 3px;
        font-weight: 700;
    }

    .rdd-item-field-value {
        color: #344054;
        font-size: 12px;
        font-weight: 600;
        word-break: break-word;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .rdd-status-help {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        margin-top: 8px;
        padding: 10px 11px;
        background: #f8f9fb;
        border-radius: 8px;
        color: #6c757d;
        font-size: 11px;
        line-height: 1.5;
    }

    .rdd-status-help i {
        color: #0d6efd;
        margin-top: 1px;
    }

    /* =========================================================
       SIDE CARD
    ========================================================= */

    .rdd-side-card {
        position: sticky;
        top: 20px;
    }

    .rdd-side-section {
        padding: 20px;
    }

    .rdd-side-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 18px;
        font-size: 14px;
        font-weight: 700;
        color: #263246;
    }

    .rdd-side-title i {
        color: #0d6efd;
    }

    .rdd-detail-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 10px 0;
        border-bottom: 1px solid #edf0f4;
    }

    .rdd-detail-row:last-child {
        border-bottom: 0;
    }

    .rdd-detail-label {
        color: #8993a3;
        font-size: 11px;
    }

    .rdd-detail-value {
        color: #344054;
        font-size: 11px;
        font-weight: 700;
        text-align: right;
        word-break: break-word;
    }

    .rdd-status-card {
        margin-top: 18px;
        padding: 14px;
        border-radius: 9px;
        background: #fff9ed;
        border: 1px solid #f5e4bc;
    }

    .rdd-status-card.approved {
        background: #f0faf4;
        border-color: #cfead9;
    }

    .rdd-status-card-title {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #946200;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .rdd-status-card.approved .rdd-status-card-title {
        color: #198754;
    }

    .rdd-status-card p {
        margin: 0;
        color: #806b43;
        font-size: 11px;
        line-height: 1.5;
    }

    .rdd-status-card.approved p {
        color: #4d745d;
    }

    /* =========================================================
       BUTTONS
    ========================================================= */

    .rdd-form-footer {
        padding: 17px 21px;
        border-top: 1px solid #edf0f4;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 9px;
        background: #fbfcfe;
    }

    .rdd-btn-cancel {
        height: 41px;
        padding: 0 16px;
        border: 1px solid #dfe4eb;
        border-radius: 8px;
        background: #fff;
        color: #596579;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
    }

    .rdd-btn-cancel:hover {
        background: #f7f8fa;
        color: #344054;
    }

    .rdd-btn-submit {
        height: 41px;
        padding: 0 18px;
        border: 0;
        border-radius: 8px;
        background: #0d6efd;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 5px 14px rgba(13, 110, 253, .17);
        cursor: pointer;
        transition: .2s ease;
    }

    .rdd-btn-submit:hover {
        background: #0b5ed7;
        transform: translateY(-1px);
    }

    .rdd-btn-submit:disabled {
        opacity: .65;
        cursor: not-allowed;
        transform: none;
    }

    /* =========================================================
       ERROR ALERT
    ========================================================= */

    .rdd-error-alert {
        border: 0;
        border-radius: 9px;
        font-size: 13px;
        margin-bottom: 20px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1050px) {
        .rdd-edit-layout {
            grid-template-columns: 1fr;
        }

        .rdd-side-card {
            position: static;
        }
    }

    @media (max-width: 800px) {
        .rdd-item-grid,
        .rdd-three-columns {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 700px) {
        .rdd-edit-page {
            padding: 16px;
        }

        .rdd-two-columns {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .rdd-item-grid,
        .rdd-three-columns {
            grid-template-columns: 1fr;
        }

        .rdd-edit-title {
            font-size: 21px;
        }

        .rdd-reference-box {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 500px) {
        .rdd-form-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .rdd-btn-cancel,
        .rdd-btn-submit {
            width: 100%;
        }
    }
</style>

<div class="rdd-edit-page">

    {{-- =========================================================
         HEADER
    ========================================================= --}}

    <div class="rdd-edit-header">

        <div class="rdd-edit-header-left">

            <div class="rdd-edit-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h1 class="rdd-edit-title">
                    Edit RDD Transaction
                </h1>

                <p class="rdd-edit-subtitle">
                    Update the information recorded for this RDD transaction.
                </p>
            </div>

        </div>

        <a
            href="{{ route('admin.inventory.rdd.index') }}"
            class="rdd-back-btn"
        >
            <i class="bi bi-arrow-left"></i>
            Back to RDD
        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger rdd-error-alert">

            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="rdd-edit-layout">

        {{-- =====================================================
             MAIN FORM
        ====================================================== --}}

        <div class="rdd-form-card">

            <div class="rdd-card-header">

                <div class="rdd-card-header-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <div>
                    <h2>RDD Transaction Details</h2>

                    <p>
                        Review and update the existing RDD transaction information.
                    </p>
                </div>

            </div>


            <form
                action="{{ route('admin.inventory.rdd.update', $rdd) }}"
                method="POST"
                id="rddEditForm"
            >

                @csrf

                @method('PUT')


                <div class="rdd-card-body">

                    {{-- =================================================
                         INFORMATION NOTICE
                    ================================================== --}}

                    <div class="rdd-notice">

                        <div class="rdd-notice-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>

                        <div class="rdd-notice-content">

                            <strong>Important:</strong>

                            The inventory item linked to this RDD transaction
                            cannot be changed here. Only the RDD transaction
                            information can be updated.

                        </div>

                    </div>


                    {{-- =================================================
                         REFERENCE NUMBER
                    ================================================== --}}

                    <div class="rdd-reference-box">

                        <div class="rdd-reference-left">

                            <div class="rdd-reference-icon">
                                <i class="bi bi-hash"></i>
                            </div>

                            <div>

                                <span class="rdd-reference-label">
                                    RDD Reference Number
                                </span>

                                <span class="rdd-reference-value">
                                    {{ $rdd->reference_no ?? 'N/A' }}
                                </span>

                            </div>

                        </div>


                        @php
                            $currentStatus = strtolower((string) ($rdd->status ?? 'pending'));
                        @endphp

                        <span
                            class="rdd-reference-badge {{ $currentStatus === 'approved' ? 'approved' : '' }}"
                        >

                            <i class="bi bi-circle-fill"></i>

                            {{ ucfirst($currentStatus) }}

                        </span>

                    </div>


                    {{-- =================================================
                         INVENTORY ITEM
                    ================================================== --}}

                    <div class="rdd-item-box">

                        <div class="rdd-item-box-header">

                            <div class="rdd-item-box-header-icon">
                                <i class="bi bi-pc-display"></i>
                            </div>

                            <strong>
                                Linked Inventory Item
                            </strong>

                        </div>


                        <div class="rdd-item-box-body">

                            <div class="rdd-item-grid">

                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        Asset Number
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->asset_number ?? 'N/A' }}
                                    </span>

                                </div>


                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        Serial Number
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->serial_number ?? 'N/A' }}
                                    </span>

                                </div>


                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        Item Type
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->itemType?->name ?? 'N/A' }}
                                    </span>

                                </div>


                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        Make
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->make ?? 'N/A' }}
                                    </span>

                                </div>


                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        Model
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->model ?? 'N/A' }}
                                    </span>

                                </div>


                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        IP Address
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->ip_address ?? 'N/A' }}
                                    </span>

                                </div>


                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        Workstation ID
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->workstation_id ?? 'N/A' }}
                                    </span>

                                </div>


                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        Branch
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->branch?->name ?? 'N/A' }}
                                    </span>

                                </div>


                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        Department
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->department?->name ?? 'N/A' }}
                                    </span>

                                </div>


                                <div class="rdd-item-field">

                                    <span class="rdd-item-field-label">
                                        Current Employee
                                    </span>

                                    <span class="rdd-item-field-value">
                                        {{ $rdd->item?->currentEmployee?->name ?? 'Not assigned' }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="rdd-section-divider"></div>


                    {{-- =================================================
                         DATE + EMPLOYEE
                    ================================================== --}}

                    <div class="rdd-two-columns">

                        {{-- RDD DATE --}}

                        <div class="rdd-form-group">

                            <label
                                for="rdd_date"
                                class="rdd-label"
                            >
                                RDD Date
                                <span class="rdd-required">*</span>
                            </label>

                            <input
                                type="date"
                                name="rdd_date"
                                id="rdd_date"
                                class="rdd-form-control @error('rdd_date') is-invalid @enderror"
                                value="{{ old('rdd_date', optional($rdd->rdd_date)->format('Y-m-d')) }}"
                                required
                            >

                            <small class="rdd-form-help">
                                Date on which the equipment was sent to RDD.
                            </small>

                            @error('rdd_date')
                                <div class="rdd-form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- EMPLOYEE --}}

                        <div class="rdd-form-group">

                            <label
                                for="employee_id"
                                class="rdd-label"
                            >
                                Employee
                            </label>

                            <select
                                name="employee_id"
                                id="employee_id"
                                class="rdd-form-select @error('employee_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Use item's current employee
                                </option>

                                @foreach($employees as $employee)

                                    <option
                                        value="{{ $employee->id }}"
                                        {{ old('employee_id', $rdd->employee_id) == $employee->id ? 'selected' : '' }}
                                    >
                                        {{ $employee->epf_no }}
                                        —
                                        {{ $employee->name }}
                                    </option>

                                @endforeach

                            </select>

                            <small class="rdd-form-help">
                                Leave blank to use the item's current employee.
                            </small>

                            @error('employee_id')
                                <div class="rdd-form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- =================================================
                         STATUS
                    ================================================== --}}

                    <div class="rdd-form-group">

                        <label
                            for="status"
                            class="rdd-label"
                        >
                            RDD Status
                            <span class="rdd-required">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="rdd-form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option
                                value="pending"
                                {{ old('status', $rdd->status) === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="approved"
                                {{ old('status', $rdd->status) === 'approved' ? 'selected' : '' }}
                            >
                                Approved
                            </option>

                        </select>

                        <div class="rdd-status-help">

                            <i class="bi bi-info-circle"></i>

                            <span>
                                Use <strong>Pending</strong> while the RDD transaction
                                is awaiting a decision. Use <strong>Approved</strong>
                                when the RDD request has been formally approved.
                            </span>

                        </div>

                        @error('status')
                            <div class="rdd-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         REASON
                    ================================================== --}}

                    <div class="rdd-form-group">

                        <label
                            for="reason"
                            class="rdd-label"
                        >
                            Reason for Sending to RDD
                            <span class="rdd-required">*</span>
                        </label>

                        <textarea
                            name="reason"
                            id="reason"
                            class="rdd-form-control @error('reason') is-invalid @enderror"
                            rows="4"
                            minlength="5"
                            required
                            placeholder="Enter the valid reason for sending this equipment to RDD..."
                        >{{ old('reason', $rdd->reason) }}</textarea>

                        <small class="rdd-form-help">
                            A valid reason is mandatory. Minimum 5 characters.
                        </small>

                        @error('reason')
                            <div class="rdd-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         RECOMMENDATION
                    ================================================== --}}

                    <div class="rdd-form-group">

                        <label
                            for="recommendation"
                            class="rdd-label"
                        >
                            Recommendation
                        </label>

                        <textarea
                            name="recommendation"
                            id="recommendation"
                            class="rdd-form-control @error('recommendation') is-invalid @enderror"
                            rows="4"
                            placeholder="Enter any recommendation for RDD assessment..."
                        >{{ old('recommendation', $rdd->recommendation) }}</textarea>

                        <small class="rdd-form-help">
                            Optional recommendation such as repair, replacement,
                            inspection, or other action.
                        </small>

                        @error('recommendation')
                            <div class="rdd-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         REMARKS
                    ================================================== --}}

                    <div class="rdd-form-group">

                        <label
                            for="remarks"
                            class="rdd-label"
                        >
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            id="remarks"
                            class="rdd-form-control @error('remarks') is-invalid @enderror"
                            rows="3"
                            placeholder="Enter any additional remarks..."
                        >{{ old('remarks', $rdd->remarks) }}</textarea>

                        <small class="rdd-form-help">
                            Optional additional information related to the RDD transaction.
                        </small>

                        @error('remarks')
                            <div class="rdd-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- =====================================================
                     FORM FOOTER
                ====================================================== --}}

                <div class="rdd-form-footer">

                    <a
                        href="{{ route('admin.inventory.rdd.index') }}"
                        class="rdd-btn-cancel"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rdd-btn-submit"
                        id="updateRddButton"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Update RDD Transaction
                    </button>

                </div>

            </form>

        </div>


        {{-- =====================================================
             SIDE INFORMATION CARD
        ====================================================== --}}

        <div class="rdd-form-card rdd-side-card">

            <div class="rdd-side-section">

                <div class="rdd-side-title">

                    <i class="bi bi-info-circle"></i>

                    Transaction Information

                </div>


                <div class="rdd-detail-row">

                    <span class="rdd-detail-label">
                        Reference No.
                    </span>

                    <span class="rdd-detail-value">
                        {{ $rdd->reference_no ?? 'N/A' }}
                    </span>

                </div>


                <div class="rdd-detail-row">

                    <span class="rdd-detail-label">
                        Asset Number
                    </span>

                    <span class="rdd-detail-value">
                        {{ $rdd->item?->asset_number ?? 'N/A' }}
                    </span>

                </div>


                <div class="rdd-detail-row">

                    <span class="rdd-detail-label">
                        RDD Date
                    </span>

                    <span class="rdd-detail-value">
                        {{ $rdd->rdd_date ? $rdd->rdd_date->format('d M Y') : 'N/A' }}
                    </span>

                </div>


                <div class="rdd-detail-row">

                    <span class="rdd-detail-label">
                        Created
                    </span>

                    <span class="rdd-detail-value">
                        {{ $rdd->created_at ? $rdd->created_at->format('d M Y, H:i') : 'N/A' }}
                    </span>

                </div>


                <div class="rdd-detail-row">

                    <span class="rdd-detail-label">
                        Last Updated
                    </span>

                    <span class="rdd-detail-value">
                        {{ $rdd->updated_at ? $rdd->updated_at->format('d M Y, H:i') : 'N/A' }}
                    </span>

                </div>


                {{-- CURRENT STATUS --}}

                @if($currentStatus === 'approved')

                    <div class="rdd-status-card approved">

                        <div class="rdd-status-card-title">

                            <i class="bi bi-check-circle-fill"></i>

                            Approved

                        </div>

                        <p>
                            This RDD transaction is currently marked as
                            <strong>Approved</strong>.
                        </p>

                    </div>

                @else

                    <div class="rdd-status-card">

                        <div class="rdd-status-card-title">

                            <i class="bi bi-clock-fill"></i>

                            Pending

                        </div>

                        <p>
                            This RDD transaction is currently awaiting
                            approval.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('rddEditForm');
    const submitButton = document.getElementById('updateRddButton');

    if (form && submitButton) {

        form.addEventListener('submit', function () {

            submitButton.disabled = true;

            submitButton.innerHTML =
                '<i class="bi bi-hourglass-split"></i> Updating...';

        });

    }

});
</script>

@endsection

