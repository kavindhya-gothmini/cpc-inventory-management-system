@extends('layouts.admin')

@section('title', 'Send Item to RDD | CPC Inventory Management System')

@section('content')

<style>
    .rdd-create-page {
        padding: 24px;
        background: #f5f7fb;
        min-height: calc(100vh - 72px);
    }

    .rdd-create-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .rdd-create-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .rdd-create-icon {
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

    .rdd-create-title {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #172033;
    }

    .rdd-create-subtitle {
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

    .rdd-form-layout {
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

    .rdd-section-divider {
        height: 1px;
        background: #edf0f4;
        margin: 22px 0;
    }

    /* Item selector */

    .rdd-item-select-wrapper {
        position: relative;
    }

    .rdd-item-select {
        min-height: 46px;
        padding-left: 42px;
        font-weight: 500;
    }

    .rdd-item-select-icon {
        position: absolute;
        left: 13px;
        top: 14px;
        color: #0d6efd;
        font-size: 16px;
        pointer-events: none;
        z-index: 2;
    }

    .rdd-selected-preview {
        display: none;
        margin-top: 12px;
        border: 1px solid #dbe7f8;
        border-radius: 10px;
        background: #f7faff;
        padding: 15px;
    }

    .rdd-selected-preview.show {
        display: block;
    }

    .rdd-preview-header {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 12px;
    }

    .rdd-preview-header-icon {
        width: 31px;
        height: 31px;
        border-radius: 8px;
        background: #e3efff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .rdd-preview-header strong {
        font-size: 13px;
        color: #263246;
    }

    .rdd-preview-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .rdd-preview-field {
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 7px;
        padding: 9px 10px;
    }

    .rdd-preview-label {
        display: block;
        color: #8993a3;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 3px;
        font-weight: 700;
    }

    .rdd-preview-value {
        color: #344054;
        font-size: 12px;
        font-weight: 600;
        word-break: break-word;
    }

    /* Information notice */

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

    /* Side card */

    .rdd-side-card {
        position: sticky;
        top: 20px;
    }

    .rdd-workflow {
        padding: 20px;
    }

    .rdd-workflow-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 19px;
        font-size: 14px;
        font-weight: 700;
        color: #263246;
    }

    .rdd-workflow-step {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        position: relative;
        padding-bottom: 19px;
    }

    .rdd-workflow-step:last-child {
        padding-bottom: 0;
    }

    .rdd-workflow-step:not(:last-child)::after {
        content: "";
        position: absolute;
        left: 14px;
        top: 29px;
        width: 1px;
        height: calc(100% - 10px);
        background: #dce3ec;
    }

    .rdd-step-number {
        width: 29px;
        height: 29px;
        border-radius: 50%;
        background: #eaf2ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .rdd-step-content strong {
        display: block;
        color: #344054;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .rdd-step-content span {
        color: #8993a3;
        font-size: 11px;
        line-height: 1.4;
    }

    .rdd-status-preview {
        margin-top: 20px;
        padding: 14px;
        border-radius: 9px;
        background: #fff9ed;
        border: 1px solid #f5e4bc;
    }

    .rdd-status-preview-title {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #946200;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .rdd-status-preview p {
        margin: 0;
        color: #806b43;
        font-size: 11px;
        line-height: 1.5;
    }

    /* Bottom buttons */

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

    /* Error alert */

    .rdd-error-alert {
        border: 0;
        border-radius: 9px;
        font-size: 13px;
        margin-bottom: 20px;
    }

    /* Responsive */

    @media (max-width: 1050px) {
        .rdd-form-layout {
            grid-template-columns: 1fr;
        }

        .rdd-side-card {
            position: static;
        }
    }

    @media (max-width: 700px) {
        .rdd-create-page {
            padding: 16px;
        }

        .rdd-two-columns {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .rdd-preview-grid {
            grid-template-columns: 1fr 1fr;
        }

        .rdd-create-title {
            font-size: 21px;
        }
    }

    @media (max-width: 500px) {
        .rdd-preview-grid {
            grid-template-columns: 1fr;
        }

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

<div class="rdd-create-page">

    {{-- HEADER --}}
    <div class="rdd-create-header">

        <div class="rdd-create-header-left">

            <div class="rdd-create-icon">
                <i class="bi bi-send-plus"></i>
            </div>

            <div>
                <h1 class="rdd-create-title">
                    Send Item to RDD
                </h1>

                <p class="rdd-create-subtitle">
                    Create an RDD transaction for an inventory item.
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


    {{-- VALIDATION ERRORS --}}
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


    <div class="rdd-form-layout">

        {{-- ========================================================= --}}
        {{-- MAIN FORM --}}
        {{-- ========================================================= --}}

        <div class="rdd-form-card">

            <div class="rdd-card-header">

                <div class="rdd-card-header-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <div>
                    <h2>RDD Transaction Details</h2>

                    <p>
                        Provide the required information before sending the equipment.
                    </p>
                </div>

            </div>


            <form
                action="{{ route('admin.inventory.rdd.store') }}"
                method="POST"
                id="rddCreateForm"
            >

                @csrf


                <div class="rdd-card-body">

                    {{-- INFORMATION NOTICE --}}

                    <div class="rdd-notice">

                        <div class="rdd-notice-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>

                        <div class="rdd-notice-content">

                            <strong>Important:</strong>

                            Sending an item to RDD will change its inventory
                            status to <strong>Sent to RDD</strong>. The original
                            inventory record will remain available for historical
                            tracking.

                        </div>

                    </div>


                    {{-- EQUIPMENT SECTION --}}

                    <div class="rdd-form-group">

                        <label
                            for="item_id"
                            class="rdd-label"
                        >
                            Inventory Item
                            <span class="rdd-required">*</span>
                        </label>

                        <div class="rdd-item-select-wrapper">

                            <i class="bi bi-pc-display rdd-item-select-icon"></i>

                            <select
                                name="item_id"
                                id="item_id"
                                class="rdd-form-select rdd-item-select @error('item_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select inventory item...
                                </option>

                                @foreach($items as $item)

                                    <option
                                        value="{{ $item->id }}"
                                        data-asset="{{ $item->asset_number ?? 'N/A' }}"
                                        data-serial="{{ $item->serial_number ?? 'N/A' }}"
                                        data-type="{{ $item->itemType?->name ?? 'N/A' }}"
                                        data-make="{{ $item->make ?? 'N/A' }}"
                                        data-model="{{ $item->model ?? 'N/A' }}"
                                        data-ip="{{ $item->ip_address ?? 'N/A' }}"
                                        data-workstation="{{ $item->workstation_id ?? 'N/A' }}"
                                        data-branch="{{ $item->branch?->name ?? 'N/A' }}"
                                        data-department="{{ $item->department?->name ?? 'N/A' }}"
                                        data-employee="{{ $item->currentEmployee?->name ?? 'Not assigned' }}"
                                        {{ old('item_id') == $item->id ? 'selected' : '' }}
                                    >
                                        {{ $item->asset_number ?? 'N/A' }}
                                        —
                                        {{ $item->itemType?->name ?? 'Unknown Type' }}
                                        @if($item->serial_number)
                                            — SN: {{ $item->serial_number }}
                                        @endif
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <small class="rdd-form-help">
                            Only inventory items that are not currently marked as
                            Sent to RDD and do not have an active RDD transaction
                            are available.
                        </small>

                        @error('item_id')
                            <div class="rdd-form-error">
                                {{ $message }}
                            </div>
                        @enderror


                        {{-- SELECTED ITEM PREVIEW --}}

                        <div
                            id="selectedItemPreview"
                            class="rdd-selected-preview"
                        >

                            <div class="rdd-preview-header">

                                <div class="rdd-preview-header-icon">
                                    <i class="bi bi-info-lg"></i>
                                </div>

                                <strong>
                                    Selected Equipment
                                </strong>

                            </div>


                            <div class="rdd-preview-grid">

                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        Asset Number
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewAsset"
                                    >
                                        —
                                    </span>

                                </div>


                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        Serial Number
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewSerial"
                                    >
                                        —
                                    </span>

                                </div>


                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        Item Type
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewType"
                                    >
                                        —
                                    </span>

                                </div>


                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        Make
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewMake"
                                    >
                                        —
                                    </span>

                                </div>


                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        Model
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewModel"
                                    >
                                        —
                                    </span>

                                </div>


                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        IP Address
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewIp"
                                    >
                                        —
                                    </span>

                                </div>


                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        Workstation ID
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewWorkstation"
                                    >
                                        —
                                    </span>

                                </div>


                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        Branch
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewBranch"
                                    >
                                        —
                                    </span>

                                </div>


                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        Department
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewDepartment"
                                    >
                                        —
                                    </span>

                                </div>


                                <div class="rdd-preview-field">

                                    <span class="rdd-preview-label">
                                        Current Employee
                                    </span>

                                    <span
                                        class="rdd-preview-value"
                                        id="previewEmployee"
                                    >
                                        —
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="rdd-section-divider"></div>


                    {{-- DATE + EMPLOYEE --}}

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
                                value="{{ old('rdd_date', now()->format('Y-m-d')) }}"
                                required
                            >

                            <small class="rdd-form-help">
                                Date on which the equipment is sent to RDD.
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
                                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                                    >
                                        {{ $employee->epf_no }}
                                        —
                                        {{ $employee->name }}
                                    </option>

                                @endforeach

                            </select>

                            <small class="rdd-form-help">
                                Leave blank to automatically use the item's
                                current employee.
                            </small>

                            @error('employee_id')
                                <div class="rdd-form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- REASON --}}

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
                        >{{ old('reason') }}</textarea>

                        <small class="rdd-form-help">
                            A valid reason is mandatory. Minimum 5 characters.
                        </small>

                        @error('reason')
                            <div class="rdd-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- RECOMMENDATION --}}

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
                        >{{ old('recommendation') }}</textarea>

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


                    {{-- REMARKS --}}

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
                        >{{ old('remarks') }}</textarea>

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


                {{-- FORM FOOTER --}}

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
                        id="submitRddButton"
                    >
                        <i class="bi bi-send-check"></i>
                        Send Item to RDD
                    </button>

                </div>

            </form>

        </div>


        {{-- ========================================================= --}}
        {{-- SIDE WORKFLOW --}}
        {{-- ========================================================= --}}

        <div class="rdd-form-card rdd-side-card">

            <div class="rdd-workflow">

                <div class="rdd-workflow-title">

                    <i class="bi bi-diagram-3"></i>

                    RDD Workflow

                </div>


                {{-- STEP 1 --}}

                <div class="rdd-workflow-step">

                    <div class="rdd-step-number">
                        1
                    </div>

                    <div class="rdd-step-content">

                        <strong>
                            Select Inventory Item
                        </strong>

                        <span>
                            Choose an available inventory item that does not
                            already have an active RDD transaction.
                        </span>

                    </div>

                </div>


                {{-- STEP 2 --}}

                <div class="rdd-workflow-step">

                    <div class="rdd-step-number">
                        2
                    </div>

                    <div class="rdd-step-content">

                        <strong>
                            Record RDD Information
                        </strong>

                        <span>
                            Enter the RDD date, employee, reason,
                            recommendation and remarks.
                        </span>

                    </div>

                </div>


                {{-- STEP 3 --}}

                <div class="rdd-workflow-step">

                    <div class="rdd-step-number">
                        3
                    </div>

                    <div class="rdd-step-content">

                        <strong>
                            Send to RDD
                        </strong>

                        <span>
                            The system creates an RDD reference and
                            records the transaction.
                        </span>

                    </div>

                </div>


                {{-- STEP 4 --}}

                <div class="rdd-workflow-step">

                    <div class="rdd-step-number">
                        4
                    </div>

                    <div class="rdd-step-content">

                        <strong>
                            Inventory Status Updated
                        </strong>

                        <span>
                            The equipment is marked as
                            <strong>Sent to RDD</strong> in Inventory.
                        </span>

                    </div>

                </div>


                {{-- STEP 5 --}}

                <div class="rdd-workflow-step">

                    <div class="rdd-step-number">
                        5
                    </div>

                    <div class="rdd-step-content">

                        <strong>
                            Track RDD Progress
                        </strong>

                        <span>
                            Follow status changes and complete RDD history
                            from the transaction details.
                        </span>

                    </div>

                </div>


                {{-- STATUS NOTICE --}}

                <div class="rdd-status-preview">

                    <div class="rdd-status-preview-title">

                        <i class="bi bi-shield-check"></i>

                        Initial RDD Status

                    </div>

                    <p>
                        Every newly created RDD transaction starts with
                        <strong>Pending</strong> status.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const itemSelect = document.getElementById('item_id');
    const preview = document.getElementById('selectedItemPreview');

    const previewFields = {
        asset: document.getElementById('previewAsset'),
        serial: document.getElementById('previewSerial'),
        type: document.getElementById('previewType'),
        make: document.getElementById('previewMake'),
        model: document.getElementById('previewModel'),
        ip: document.getElementById('previewIp'),
        workstation: document.getElementById('previewWorkstation'),
        branch: document.getElementById('previewBranch'),
        department: document.getElementById('previewDepartment'),
        employee: document.getElementById('previewEmployee')
    };

    function updateItemPreview() {

        const selectedOption =
            itemSelect.options[itemSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {

            preview.classList.remove('show');

            Object.values(previewFields).forEach(function (field) {
                field.textContent = '—';
            });

            return;
        }

        previewFields.asset.textContent =
            selectedOption.dataset.asset || 'N/A';

        previewFields.serial.textContent =
            selectedOption.dataset.serial || 'N/A';

        previewFields.type.textContent =
            selectedOption.dataset.type || 'N/A';

        previewFields.make.textContent =
            selectedOption.dataset.make || 'N/A';

        previewFields.model.textContent =
            selectedOption.dataset.model || 'N/A';

        previewFields.ip.textContent =
            selectedOption.dataset.ip || 'N/A';

        previewFields.workstation.textContent =
            selectedOption.dataset.workstation || 'N/A';

        previewFields.branch.textContent =
            selectedOption.dataset.branch || 'N/A';

        previewFields.department.textContent =
            selectedOption.dataset.department || 'N/A';

        previewFields.employee.textContent =
            selectedOption.dataset.employee || 'Not assigned';

        preview.classList.add('show');
    }

    itemSelect.addEventListener(
        'change',
        updateItemPreview
    );

    /*
     * Restore selected item preview after validation
     * redirects back to this page.
     */
    updateItemPreview();


    /*
     * Prevent accidental double submission.
     */
    const form = document.getElementById('rddCreateForm');
    const submitButton = document.getElementById('submitRddButton');

    form.addEventListener('submit', function () {

        submitButton.disabled = true;

        submitButton.innerHTML =
            '<i class="bi bi-hourglass-split"></i> Sending to RDD...';

    });

});
</script>

@endsection