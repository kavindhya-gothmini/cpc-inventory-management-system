
@extends('layouts.admin')

@section('content')

<style>
    .inventory-form-page {
        max-width: 1350px;
        margin: 0 auto;
    }

    .inventory-form-header {
        position: relative;
        overflow: hidden;
        color: #fff;
        background: linear-gradient(135deg, #0d6efd, #084298);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(13, 110, 253, .15);
    }

    .inventory-form-header::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -70px;
        top: -120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        margin-bottom: 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .13);
        border: 1px solid rgba(255, 255, 255, .18);
        font-size: 11px;
        font-weight: 750;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .inventory-form-header h1 {
        margin: 0 0 7px;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 750;
    }

    .inventory-form-header p {
        margin: 0;
        color: rgba(255, 255, 255, .9);
        font-size: 14px;
        line-height: 1.6;
    }

    .inventory-form-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 18px;
        box-shadow: 0 7px 25px rgba(16, 24, 40, .05);
    }

    .form-card-body {
        padding: 28px;
    }

    .registration-alert {
        display: flex;
        gap: 13px;
        padding: 16px;
        margin-bottom: 24px;
        background: #f0f7ff;
        border: 1px solid #cfe2ff;
        border-radius: 13px;
        color: #174a7c;
    }

    .registration-alert-icon {
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #dbeafe;
        color: #0d6efd;
    }

    .registration-alert-title {
        margin-bottom: 3px;
        font-size: 13px;
        font-weight: 750;
    }

    .registration-alert-text {
        margin: 0;
        font-size: 13px;
        line-height: 1.6;
    }

    .validation-alert {
        padding: 16px 18px;
        margin-bottom: 24px;
        background: #fff5f5;
        border: 1px solid #f5c2c7;
        border-radius: 13px;
        color: #842029;
    }

    .validation-alert-title {
        display: flex;
        gap: 8px;
        align-items: center;
        font-size: 14px;
        font-weight: 750;
    }

    .validation-alert ul {
        margin: 9px 0 0;
        padding-left: 21px;
        font-size: 13px;
        line-height: 1.7;
    }

    .form-section {
        margin-bottom: 32px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 13px;
        margin-bottom: 20px;
        border-bottom: 1px solid #e9edf2;
    }

    .section-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 32px;
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: #eaf2ff;
        color: #0d6efd;
        font-size: 13px;
        font-weight: 800;
    }

    .section-heading-title {
        margin: 0;
        color: #172b4d;
        font-size: 15px;
        font-weight: 750;
    }

    .section-heading-description {
        margin: 3px 0 0;
        color: #98a2b3;
        font-size: 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .form-field {
        min-width: 0;
    }

    .form-field.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 750;
    }

    .required-mark {
        color: #dc3545;
        font-weight: 800;
    }

    .form-control,
    .form-select {
        width: 100%;
        min-height: 44px;
        border: 1px solid #d0d5dd;
        border-radius: 10px;
        color: #172b4d;
        background-color: #fff;
        font-size: 13px;
        transition: border-color .2s, box-shadow .2s;
    }

    .form-control {
        padding: 10px 13px;
    }

    .form-select {
        padding: 10px 36px 10px 13px;
    }

    .form-control::placeholder {
        color: #98a2b3;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .1);
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
    }

    .form-select:disabled {
        background-color: #f3f5f7;
        color: #98a2b3;
        cursor: not-allowed;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
        line-height: 1.6;
    }

    .field-help {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-top: 6px;
        color: #667085;
        font-size: 11px;
        line-height: 1.5;
    }

    .empty-options,
    .selection-info {
        margin-top: 7px;
        padding: 9px 11px;
        border-radius: 8px;
        font-size: 11px;
        line-height: 1.5;
    }

    .empty-options {
        color: #856404;
        background: #fff8e6;
        border: 1px solid #ffe69c;
    }

    .selection-info {
        color: #174a7c;
        background: #f0f7ff;
        border: 1px solid #cfe2ff;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding-top: 22px;
        margin-top: 24px;
        border-top: 1px solid #e9edf2;
    }

    .action-note {
        color: #667085;
        font-size: 11px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        min-height: 43px;
        padding: 10px 17px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
    }

    @media (max-width: 991.98px) {
        .form-card-body {
            padding: 24px;
        }
    }

    @media (max-width: 767.98px) {
        .container-fluid {
            padding-right: 12px !important;
            padding-left: 12px !important;
        }

        .inventory-form-header {
            padding: 21px;
            border-radius: 16px;
        }

        .inventory-form-card {
            border-radius: 15px;
        }

        .form-card-body {
            padding: 19px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 17px;
        }

        .form-field.full-width {
            grid-column: auto;
        }

        .form-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .action-btn {
            width: 100%;
        }
    }

    @media (max-width: 430px) {
        .action-buttons {
            grid-template-columns: 1fr;
        }

        .section-heading-description {
            display: none;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="inventory-form-page">

        <div class="inventory-form-header">
            <div class="header-content">
                <div class="header-eyebrow">
                    <i class="bi bi-box-seam"></i>
                    Inventory Management
                </div>

                <h1>Register Inventory Item</h1>

                <p>
                    Add a new IT asset to the CPC Inventory Management System.
                    Complete the identification and technical information available at registration.
                </p>
            </div>
        </div>

        <div class="inventory-form-card">
            <div class="form-card-body">

                @if ($errors->any())
                    <div class="validation-alert">
                        <div class="validation-alert-title">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Please review the following information.
                        </div>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($itemTypes->isEmpty())
                    <div class="empty-options mb-3">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        No item types are available. A Super Admin must create an Item Type first.
                    </div>
                @endif

                @if ($makes->isEmpty())
                    <div class="empty-options mb-3">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        No active makes are available. A Super Admin must create a Make before
                        a model can be selected.
                    </div>
                @endif

                <div class="registration-alert">
                    <div class="registration-alert-icon">
                        <i class="bi bi-info-lg"></i>
                    </div>

                    <div>
                        <div class="registration-alert-title">
                            Registration Information
                        </div>

                        <p class="registration-alert-text">
                            Item Type and Asset Number are required.
                            Select the Make before selecting its Model.
                            Record serial number and network details when available.
                            Newly registered assets start with Available status.
                        </p>
                    </div>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.items.store') }}"
                    id="inventoryItemForm"
                >
                    @csrf

                    {{-- SECTION 1: BASIC ITEM INFORMATION --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-number">01</div>
                            <div>
                                <h2 class="section-heading-title">Basic Item Information</h2>
                                <p class="section-heading-description">
                                    Core identification details for the inventory asset.
                                </p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-field">
                                <label for="item_type_id" class="form-label">
                                    Item Type <span class="required-mark">*</span>
                                </label>

                                <select
                                    id="item_type_id"
                                    name="item_type_id"
                                    class="form-select @error('item_type_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="">Select item type</option>

                                    @foreach ($itemTypes as $itemType)
                                        <option
                                            value="{{ $itemType->id }}"
                                            @selected((string) old('item_type_id') === (string) $itemType->id)
                                        >
                                            {{ $itemType->name }}
                                            @if (!empty($itemType->category))
                                                — {{ $itemType->category }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                @error('item_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="asset_number" class="form-label">
                                    Asset Number <span class="required-mark">*</span>
                                </label>

                                <input
                                    id="asset_number"
                                    type="text"
                                    name="asset_number"
                                    class="form-control @error('asset_number') is-invalid @enderror"
                                    value="{{ old('asset_number') }}"
                                    placeholder="Example: CPC-IT-0001"
                                    maxlength="100"
                                    required
                                >

                                @error('asset_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="make_id" class="form-label">Make</label>

                                <select
                                    id="make_id"
                                    name="make_id"
                                    class="form-select @error('make_id') is-invalid @enderror"
                                >
                                    <option value="">Select make</option>

                                    @foreach ($makes as $make)
                                        <option
                                            value="{{ $make->id }}"
                                            @selected((string) old('make_id') === (string) $make->id)
                                        >
                                            {{ $make->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('make_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="field-help">
                                    <i class="bi bi-info-circle"></i>
                                    Select a registered Make to display its associated Models.
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="model_id" class="form-label">Model</label>

                                <select
                                    id="model_id"
                                    name="model_id"
                                    class="form-select @error('model_id') is-invalid @enderror"
                                    disabled
                                >
                                    <option value="">Select make first</option>

                                    @foreach ($itemModels as $itemModel)
                                        <option
                                            value="{{ $itemModel->id }}"
                                            data-make-id="{{ $itemModel->make_id }}"
                                        >
                                            {{ $itemModel->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('model_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div id="modelHelp" class="field-help">
                                    <i class="bi bi-info-circle"></i>
                                    Select a Make first to choose its Model.
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="serial_number" class="form-label">Serial Number</label>

                                <input
                                    id="serial_number"
                                    type="text"
                                    name="serial_number"
                                    class="form-control @error('serial_number') is-invalid @enderror"
                                    value="{{ old('serial_number') }}"
                                    placeholder="Enter serial number"
                                    maxlength="150"
                                >

                                @error('serial_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="status" class="form-label">Initial Inventory Status</label>

                                <select id="status" name="status" class="form-select" disabled>
                                    <option value="available" selected>Available</option>
                                </select>

                                {{-- Ensure the form submits the initial status. --}}
                                <input type="hidden" name="status" value="available">

                                <div class="field-help">
                                    <i class="bi bi-info-circle"></i>
                                    New assets are registered as Available. Assignment and repair workflows
                                    update the status when necessary.
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 2: LOCATION AND NETWORK --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-number">02</div>
                            <div>
                                <h2 class="section-heading-title">Location &amp; Network</h2>
                                <p class="section-heading-description">
                                    Organisational and network details for the asset.
                                </p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-field">
                                <label for="branch_id" class="form-label">Branch</label>

                                <select
                                    id="branch_id"
                                    name="branch_id"
                                    class="form-select @error('branch_id') is-invalid @enderror"
                                >
                                    <option value="">Select branch</option>

                                    @isset($branches)
                                        @foreach ($branches as $branch)
                                            <option
                                                value="{{ $branch->id }}"
                                                @selected((string) old('branch_id') === (string) $branch->id)
                                            >
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>

                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="department_id" class="form-label">Department</label>

                                <select
                                    id="department_id"
                                    name="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror"
                                >
                                    <option value="">Select department</option>

                                    @isset($departments)
                                        @foreach ($departments as $department)
                                            <option
                                                value="{{ $department->id }}"
                                                data-branch-id="{{ $department->branch_id }}"
                                                @selected((string) old('department_id') === (string) $department->id)
                                            >
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>

                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="function_unit_id" class="form-label">Function Unit</label>

                                <select
                                    id="function_unit_id"
                                    name="function_unit_id"
                                    class="form-select @error('function_unit_id') is-invalid @enderror"
                                >
                                    <option value="">Select function unit</option>

                                    @isset($functionUnits)
                                        @foreach ($functionUnits as $functionUnit)
                                            <option
                                                value="{{ $functionUnit->id }}"
                                                data-department-id="{{ $functionUnit->department_id }}"
                                                @selected((string) old('function_unit_id') === (string) $functionUnit->id)
                                            >
                                                {{ $functionUnit->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>

                                @error('function_unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="field-help">
                                    <i class="bi bi-info-circle"></i>
                                    Optional. Select a Department first.
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="ip_address" class="form-label">IP Address</label>

                                <input
                                    id="ip_address"
                                    type="text"
                                    name="ip_address"
                                    class="form-control @error('ip_address') is-invalid @enderror"
                                    value="{{ old('ip_address') }}"
                                    placeholder="Example: 192.168.1.100"
                                    maxlength="45"
                                >

                                @error('ip_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="field-help">
                                    <i class="bi bi-info-circle"></i>
                                    Enter the IPv4 or IPv6 address when available.
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="workstation_id" class="form-label">Workstation ID</label>

                                <input
                                    id="workstation_id"
                                    type="text"
                                    name="workstation_id"
                                    class="form-control @error('workstation_id') is-invalid @enderror"
                                    value="{{ old('workstation_id') }}"
                                    placeholder="Example: CPC-WS-001"
                                    maxlength="100"
                                >

                                @error('workstation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="field-help">
                                    <i class="bi bi-info-circle"></i>
                                    Can be completed when the device is assigned.
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 3: DEVICE SPECIFICATIONS --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-number">03</div>
                            <div>
                                <h2 class="section-heading-title">Device Specifications</h2>
                                <p class="section-heading-description">
                                    Primary hardware and operating system information.
                                </p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-field">
                                <label for="ram" class="form-label">RAM</label>

                                <input
                                    id="ram"
                                    type="text"
                                    name="ram"
                                    class="form-control @error('ram') is-invalid @enderror"
                                    value="{{ old('ram') }}"
                                    placeholder="Example: 8 GB"
                                    maxlength="100"
                                >

                                @error('ram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="storage" class="form-label">Storage</label>

                                <input
                                    id="storage"
                                    type="text"
                                    name="storage"
                                    class="form-control @error('storage') is-invalid @enderror"
                                    value="{{ old('storage') }}"
                                    placeholder="Example: 512 GB SSD"
                                    maxlength="150"
                                >

                                @error('storage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="processor" class="form-label">Processor</label>

                                <input
                                    id="processor"
                                    type="text"
                                    name="processor"
                                    class="form-control @error('processor') is-invalid @enderror"
                                    value="{{ old('processor') }}"
                                    placeholder="Example: Intel Core i5"
                                    maxlength="150"
                                >

                                @error('processor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="graphics" class="form-label">Graphics</label>

                                <input
                                    id="graphics"
                                    type="text"
                                    name="graphics"
                                    class="form-control @error('graphics') is-invalid @enderror"
                                    value="{{ old('graphics') }}"
                                    placeholder="Example: Intel UHD Graphics"
                                    maxlength="150"
                                >

                                @error('graphics')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field full-width">
                                <label for="operating_system" class="form-label">Operating System</label>

                                <input
                                    id="operating_system"
                                    type="text"
                                    name="operating_system"
                                    class="form-control @error('operating_system') is-invalid @enderror"
                                    value="{{ old('operating_system') }}"
                                    placeholder="Example: Windows 11 Pro"
                                    maxlength="150"
                                >

                                @error('operating_system')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 4: ADDITIONAL INFORMATION --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-number">04</div>
                            <div>
                                <h2 class="section-heading-title">Additional Information</h2>
                                <p class="section-heading-description">
                                    Descriptive information and internal notes.
                                </p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-field full-width">
                                <label for="description" class="form-label">Description</label>

                                <textarea
                                    id="description"
                                    name="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Enter a brief description of the inventory item..."
                                >{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-field full-width">
                                <label for="notes" class="form-label">Notes</label>

                                <textarea
                                    id="notes"
                                    name="notes"
                                    class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Enter additional inventory notes, observations, or remarks..."
                                >{{ old('notes') }}</textarea>

                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- FORM ACTIONS --}}
                    <div class="form-actions">
                        <div class="action-note">
                            <i class="bi bi-shield-check me-1"></i>
                            Fields marked with <span class="required-mark">*</span> are required.
                        </div>

                        <div class="action-buttons">
                            <a
                                href="{{ route('admin.items.index') }}"
                                class="btn btn-light border action-btn"
                                id="cancelButton"
                            >
                                <i class="bi bi-x-lg me-1"></i>
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary action-btn"
                                id="submitButton"
                            >
                                <i class="bi bi-check2-circle me-1"></i>
                                Register Inventory Item
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const makeSelect = document.getElementById('make_id');
    const modelSelect = document.getElementById('model_id');
    const modelHelp = document.getElementById('modelHelp');

    const branchSelect = document.getElementById('branch_id');
    const departmentSelect = document.getElementById('department_id');
    const functionUnitSelect = document.getElementById('function_unit_id');

    const form = document.getElementById('inventoryItemForm');
    const submitButton = document.getElementById('submitButton');

    /*
     * MAKE -> MODEL FILTERING
     */
    if (makeSelect && modelSelect) {
        const allModels = Array.from(
            modelSelect.querySelectorAll('option[data-make-id]')
        ).map(function (option) {
            return {
                id: option.value,
                name: option.textContent.trim(),
                makeId: option.dataset.makeId
            };
        });

        function updateModels(makeId, selectedModelId = '') {
            modelSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = makeId
                ? 'Select model'
                : 'Select make first';

            modelSelect.appendChild(placeholder);

            if (!makeId) {
                modelSelect.disabled = true;

                if (modelHelp) {
                    modelHelp.innerHTML =
                        '<i class="bi bi-info-circle"></i>' +
                        '<span>Select a Make first to choose its Model.</span>';
                }

                return;
            }

            const models = allModels.filter(function (model) {
                return String(model.makeId) === String(makeId);
            });

            if (models.length === 0) {
                modelSelect.disabled = true;

                if (modelHelp) {
                    modelHelp.innerHTML =
                        '<i class="bi bi-exclamation-circle"></i>' +
                        '<span>No Models are registered for this Make.</span>';
                }

                return;
            }

            models.forEach(function (model) {
                const option = document.createElement('option');
                option.value = model.id;
                option.textContent = model.name;

                if (String(selectedModelId) === String(model.id)) {
                    option.selected = true;
                }

                modelSelect.appendChild(option);
            });

            modelSelect.disabled = false;

            if (modelHelp) {
                modelHelp.innerHTML =
                    '<i class="bi bi-funnel"></i>' +
                    '<span>Showing ' + models.length +
                    (models.length === 1 ? ' Model' : ' Models') +
                    ' for the selected Make.</span>';
            }
        }

        makeSelect.addEventListener('change', function () {
            updateModels(this.value, '');
        });

        updateModels(
            makeSelect.value,
            @json(old('model_id', ''))
        );
    }

    /*
     * BRANCH -> DEPARTMENT -> FUNCTION UNIT FILTERING
     */
    if (branchSelect && departmentSelect && functionUnitSelect) {
        const allDepartments = Array.from(
            departmentSelect.querySelectorAll('option[data-branch-id]')
        ).map(function (option) {
            return {
                id: option.value,
                name: option.textContent.trim(),
                branchId: option.dataset.branchId
            };
        });

        const allFunctionUnits = Array.from(
            functionUnitSelect.querySelectorAll('option[data-department-id]')
        ).map(function (option) {
            return {
                id: option.value,
                name: option.textContent.trim(),
                departmentId: option.dataset.departmentId
            };
        });

        function updateFunctionUnits(departmentId, selectedFunctionUnitId = '') {
            functionUnitSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = departmentId
                ? 'Select function unit'
                : 'Select department first';

            functionUnitSelect.appendChild(placeholder);

            if (!departmentId) {
                functionUnitSelect.disabled = true;
                return;
            }

            const units = allFunctionUnits.filter(function (unit) {
                return String(unit.departmentId) === String(departmentId);
            });

            units.forEach(function (unit) {
                const option = document.createElement('option');
                option.value = unit.id;
                option.textContent = unit.name;

                if (String(selectedFunctionUnitId) === String(unit.id)) {
                    option.selected = true;
                }

                functionUnitSelect.appendChild(option);
            });

            functionUnitSelect.disabled = units.length === 0;
        }

        function updateDepartments(branchId, selectedDepartmentId = '', selectedFunctionUnitId = '') {
            departmentSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = branchId
                ? 'Select department'
                : 'Select branch first';

            departmentSelect.appendChild(placeholder);

            if (!branchId) {
                departmentSelect.disabled = true;
                updateFunctionUnits('');
                return;
            }

            const departments = allDepartments.filter(function (department) {
                return String(department.branchId) === String(branchId);
            });

            departments.forEach(function (department) {
                const option = document.createElement('option');
                option.value = department.id;
                option.textContent = department.name;

                if (String(selectedDepartmentId) === String(department.id)) {
                    option.selected = true;
                }

                departmentSelect.appendChild(option);
            });

            departmentSelect.disabled = departments.length === 0;

            updateFunctionUnits(
                departmentSelect.value,
                selectedFunctionUnitId
            );
        }

        branchSelect.addEventListener('change', function () {
            updateDepartments(this.value, '', '');
        });

        departmentSelect.addEventListener('change', function () {
            updateFunctionUnits(this.value, '');
        });

        updateDepartments(
            branchSelect.value,
            @json(old('department_id', '')),
            @json(old('function_unit_id', ''))
        );
    }

    /*
     * PREVENT DOUBLE SUBMISSION
     */
    if (form && submitButton) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }

            if (form.dataset.submitting === 'true') {
                event.preventDefault();
                return;
            }

            form.dataset.submitting = 'true';
            submitButton.disabled = true;
            submitButton.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2" ' +
                'aria-hidden="true"></span>Registering...';
        });
    }
});
</script>

@endsection
