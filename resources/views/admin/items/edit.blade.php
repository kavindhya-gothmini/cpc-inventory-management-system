
@extends('layouts.admin')

@section('title', 'Edit Inventory Item | CPC Inventory Management System')

@section('content')

<style>
    .inventory-edit-page {
        max-width: 1280px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    .inventory-edit-header {
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 16px;
        padding: 24px 26px;
        margin-bottom: 22px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .breadcrumb-line {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 18px;
        font-size: 13px;
        color: #7b8798;
    }

    .breadcrumb-line a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    .breadcrumb-line a:hover {
        text-decoration: underline;
    }

    .breadcrumb-line .separator {
        color: #b0b8c5;
    }

    .header-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .header-content {
        min-width: 0;
    }

    .header-kicker {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #2563eb;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        margin-bottom: 7px;
    }

    .header-content h1 {
        margin: 0 0 7px;
        color: #172033;
        font-size: 28px;
        line-height: 1.2;
        font-weight: 750;
        letter-spacing: -.35px;
    }

    .header-content p {
        margin: 0;
        color: #748093;
        font-size: 14px;
        line-height: 1.6;
        max-width: 720px;
    }

    .header-back {
        flex: 0 0 auto;
    }

    .header-back .btn {
        min-height: 42px;
        padding: 9px 16px;
        border-radius: 9px;
        font-weight: 600;
        white-space: nowrap;
    }

    .validation-alert {
        border: 1px solid #f3c4c8;
        border-left: 4px solid #dc3545;
        border-radius: 11px;
        background: #fff7f7;
        color: #6f2930;
        padding: 15px 17px;
        margin-bottom: 20px;
    }

    .validation-alert-title {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .validation-alert ul {
        margin: 0;
        padding-left: 22px;
        font-size: 13px;
        line-height: 1.7;
    }

    .item-reference-card {
        background: #f8fafc;
        border: 1px solid #e5e9ef;
        border-radius: 14px;
        padding: 17px 20px;
        margin-bottom: 20px;
    }

    .reference-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .reference-item {
        min-width: 0;
    }

    .reference-label {
        display: block;
        color: #8993a3;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .55px;
        margin-bottom: 5px;
    }

    .reference-value {
        display: block;
        color: #1d2939;
        font-size: 14px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .reference-value.mono {
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", monospace;
        letter-spacing: .2px;
    }

    .reference-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        background: #eef4ff;
        color: #2563eb;
    }

    .inventory-form-card {
        background: #fff;
        border: 1px solid #e5e9ef;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(15, 23, 42, .045);
    }

    .form-card-top {
        padding: 19px 24px;
        border-bottom: 1px solid #edf0f4;
        background: #fbfcfd;
    }

    .form-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #1d2939;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .form-card-title-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #edf4ff;
        color: #2563eb;
        flex: 0 0 32px;
    }

    .form-card-subtitle {
        margin: 5px 0 0 42px;
        color: #7b8798;
        font-size: 12px;
    }

    .form-card-body {
        padding: 28px 26px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section:last-of-type {
        margin-bottom: 0;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .65px;
        padding-bottom: 11px;
        margin-bottom: 18px;
        border-bottom: 1px solid #e9edf2;
    }

    .form-label {
        display: block;
        color: #344054;
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 7px;
    }

    .required {
        color: #dc3545;
        margin-left: 2px;
    }

    .form-control,
    .form-select {
        width: 100%;
        min-height: 44px;
        border: 1px solid #d8dee8;
        border-radius: 9px;
        background: #fff;
        color: #1d2939;
        font-size: 13px;
        padding: 9px 12px;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: #c3cad5;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
        outline: none;
    }

    .form-control::placeholder {
        color: #a0a8b5;
    }

    .form-select:disabled {
        background: #f8fafc;
        color: #98a2b3;
        cursor: not-allowed;
    }

    textarea.form-control {
        min-height: 115px;
        resize: vertical;
        line-height: 1.55;
    }

    .field-hint {
        display: block;
        color: #8a94a3;
        font-size: 11px;
        line-height: 1.5;
        margin-top: 5px;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 11px;
        line-height: 1.45;
        margin-top: 5px;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, .10) !important;
    }

    .make-model-help {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        color: #8a94a3;
        font-size: 11px;
        line-height: 1.5;
        margin-top: 5px;
    }

    .make-model-help i {
        margin-top: 2px;
        color: #64748b;
    }

    .make-model-info,
    .empty-model-message {
        display: none;
        margin-top: 7px;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: 11px;
        line-height: 1.5;
    }

    .make-model-info {
        background: #f0f7ff;
        border: 1px solid #cfe2ff;
        color: #174a7c;
    }

    .empty-model-message {
        background: #fff8e6;
        border: 1px solid #ffe69c;
        color: #856404;
    }

    .make-model-info.visible,
    .empty-model-message.visible {
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    .make-model-info i,
    .empty-model-message i {
        margin-top: 2px;
    }

    .status-field-wrapper {
        position: relative;
    }

    .status-field-wrapper .form-select {
        padding-left: 38px;
    }

    .status-field-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        color: #64748b;
        pointer-events: none;
        font-size: 13px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        border-top: 1px solid #e9edf2;
        margin-top: 30px;
        padding-top: 22px;
    }

    .form-actions .btn {
        min-height: 43px;
        border-radius: 9px;
        padding: 9px 17px;
        font-size: 13px;
        font-weight: 650;
    }

    .btn-primary {
        background: #2563eb;
        border-color: #2563eb;
    }

    .btn-primary:hover,
    .btn-primary:focus {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-outline-secondary {
        color: #475467;
        border-color: #d5dae2;
        background: #fff;
    }

    .btn-outline-secondary:hover {
        color: #344054;
        background: #f8fafc;
        border-color: #c4cad3;
    }

    @media (max-width: 991.98px) {
        .inventory-edit-page {
            padding-bottom: 28px;
        }

        .header-main {
            align-items: flex-start;
        }

        .header-content h1 {
            font-size: 25px;
        }

        .reference-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .form-card-body {
            padding: 24px 21px;
        }
    }

    @media (max-width: 767.98px) {
        .container-fluid {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .inventory-edit-header {
            border-radius: 13px;
            padding: 19px 17px;
            margin-bottom: 16px;
        }

        .breadcrumb-line {
            margin-bottom: 14px;
            font-size: 12px;
        }

        .header-main {
            display: block;
        }

        .header-content h1 {
            font-size: 22px;
        }

        .header-content p {
            font-size: 13px;
        }

        .header-back {
            margin-top: 16px;
        }

        .header-back .btn {
            width: 100%;
            justify-content: center;
        }

        .reference-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .item-reference-card {
            padding: 15px;
            border-radius: 12px;
        }

        .reference-item {
            padding-bottom: 11px;
            border-bottom: 1px solid #e9edf2;
        }

        .reference-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .inventory-form-card {
            border-radius: 13px;
        }

        .form-card-top {
            padding: 16px;
        }

        .form-card-body {
            padding: 20px 16px;
        }

        .form-card-subtitle {
            margin-left: 42px;
            line-height: 1.5;
        }

        .form-section {
            margin-bottom: 26px;
        }

        .section-heading {
            margin-bottom: 16px;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .inventory-edit-header {
            padding: 16px 14px;
        }

        .header-content h1 {
            font-size: 20px;
        }

        .header-content p {
            font-size: 12px;
        }

        .form-card-body {
            padding: 18px 13px;
        }

        .form-card-top {
            padding: 15px 13px;
        }

        .form-card-title {
            font-size: 14px;
        }

        .form-card-subtitle {
            font-size: 11px;
        }

        .form-control,
        .form-select {
            min-height: 43px;
            font-size: 13px;
        }

        textarea.form-control {
            min-height: 105px;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="inventory-edit-page">

        <div class="inventory-edit-header">
            <div class="breadcrumb-line">
                <a href="{{ route('admin.items.index') }}">
                    <i class="bi bi-box-seam me-1"></i>
                    Inventory
                </a>
                <span class="separator">/</span>
                <span>Edit Item</span>
            </div>

            <div class="header-main">
                <div class="header-content">
                    <div class="header-kicker">
                        <i class="bi bi-pencil-square"></i>
                        CPC IT Asset Register
                    </div>

                    <h1>Edit Inventory Item</h1>

                    <p>
                        Update the registered asset information, network details,
                        location and current inventory status.
                    </p>
                </div>

                <div class="header-back">
                    <a href="{{ route('admin.items.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Inventory
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="validation-alert">
                <div class="validation-alert-title">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Please review the highlighted information.
                </div>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="item-reference-card">
            <div class="reference-grid">
                <div class="reference-item">
                    <span class="reference-label">Database ID</span>
                    <span class="reference-value mono">#{{ $item->id }}</span>
                </div>

                <div class="reference-item">
                    <span class="reference-label">Asset Number</span>
                    <span class="reference-value mono">
                        {{ $item->asset_number ?? 'Not assigned' }}
                    </span>
                </div>

                <div class="reference-item">
                    <span class="reference-label">Current Status</span>
                    <span class="reference-status">
                        @php
                            $statusIcon = match ($item->status) {
                                'available' => 'bi-check-circle-fill',
                                'in_use' => 'bi-person-check-fill',
                                'under_repair' => 'bi-tools',
                                'sent_to_rdd' => 'bi-arrow-repeat',
                                'scrapped' => 'bi-trash3',
                                'transferred' => 'bi-arrow-left-right',
                                default => 'bi-info-circle-fill',
                            };
                        @endphp

                        <i class="bi {{ $statusIcon }}"></i>
                        {{ ucfirst(str_replace('_', ' ', $item->status ?? 'Unknown')) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="inventory-form-card">
            <div class="form-card-top">
                <h2 class="form-card-title">
                    <span class="form-card-title-icon">
                        <i class="bi bi-pencil-square"></i>
                    </span>
                    Inventory Item Information
                </h2>

                <p class="form-card-subtitle">
                    Maintain accurate information for this registered CPC asset.
                </p>
            </div>

            <div class="form-card-body">
                <form method="POST"
                      action="{{ route('admin.items.update', $item) }}"
                      id="inventoryEditForm">

                    @csrf
                    @method('PUT')

                    {{-- BASIC INFORMATION --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <i class="bi bi-info-circle"></i>
                            Basic Information
                        </div>

                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label for="item_type_id" class="form-label">
                                    Asset Type <span class="required">*</span>
                                </label>

                                <select name="item_type_id" id="item_type_id"
                                        class="form-select @error('item_type_id') is-invalid @enderror"
                                        required>
                                    <option value="">Select Asset Type</option>

                                    @foreach ($itemTypes as $itemType)
                                        <option value="{{ $itemType->id }}"
                                            {{ old('item_type_id', $item->item_type_id) == $itemType->id ? 'selected' : '' }}>
                                            {{ $itemType->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('item_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="asset_number" class="form-label">
                                    Asset Number <span class="required">*</span>
                                </label>

                                <input type="text" name="asset_number" id="asset_number"
                                       class="form-control @error('asset_number') is-invalid @enderror"
                                       value="{{ old('asset_number', $item->asset_number) }}"
                                       placeholder="Enter official asset number"
                                       maxlength="100" required>

                                @error('asset_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- MAKE --}}
                            <div class="col-12 col-md-6">
                                <label for="make_id" class="form-label">Make</label>

                                <select name="make_id" id="make_id"
                                        class="form-select @error('make_id') is-invalid @enderror">
                                    <option value="">Select Make</option>

                                    @foreach ($makes as $make)
                                        <option value="{{ $make->id }}"
                                            {{ old('make_id', $item->make_id) == $make->id ? 'selected' : '' }}>
                                            {{ $make->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('make_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="make-model-help">
                                    <i class="bi bi-info-circle"></i>
                                    <span>
                                        Select a registered Make. The Model list will
                                        automatically show only Models belonging to that Make.
                                    </span>
                                </div>
                            </div>

                            {{-- MODEL --}}
                            <div class="col-12 col-md-6">
                                <label for="model_id" class="form-label">Model</label>

                                <select name="model_id" id="model_id"
                                        class="form-select @error('model_id') is-invalid @enderror">
                                    <option value="">Select Make First</option>

                                    @foreach ($itemModels as $itemModel)
                                        <option value="{{ $itemModel->id }}"
                                                data-make-id="{{ $itemModel->make_id }}">
                                            {{ $itemModel->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('model_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div id="modelHelp" class="make-model-help">
                                    <i class="bi bi-info-circle"></i>
                                    <span>Select a Make first to choose an associated Model.</span>
                                </div>

                                <div id="modelInfo" class="make-model-info">
                                    <i class="bi bi-funnel"></i>
                                    <span id="modelInfoText"></span>
                                </div>

                                <div id="emptyModelMessage" class="empty-model-message">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span>
                                        No active Models are registered for the selected Make.
                                        A Super Admin must add a Model for this Make first.
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="serial_number" class="form-label">Serial Number</label>

                                <input type="text" name="serial_number" id="serial_number"
                                       class="form-control @error('serial_number') is-invalid @enderror"
                                       value="{{ old('serial_number', $item->serial_number) }}"
                                       placeholder="Enter manufacturer serial number"
                                       maxlength="150">

                                @error('serial_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- UPDATED INVENTORY STATUS --}}
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label">
                                    Inventory Status <span class="required">*</span>
                                </label>

                                <div class="status-field-wrapper">
                                    <i class="bi bi-circle-fill status-field-icon"></i>

                                    <select name="status" id="status"
                                            class="form-select @error('status') is-invalid @enderror"
                                            required>
                                        <option value="available"
                                            {{ old('status', $item->status) === 'available' ? 'selected' : '' }}>
                                            Available
                                        </option>

                                        <option value="in_use"
                                            {{ old('status', $item->status) === 'in_use' ? 'selected' : '' }}>
                                            In Use
                                        </option>

                                        <option value="under_repair"
                                            {{ old('status', $item->status) === 'under_repair' ? 'selected' : '' }}>
                                            Under Repair
                                        </option>

                                        <option value="sent_to_rdd"
                                            {{ old('status', $item->status) === 'sent_to_rdd' ? 'selected' : '' }}>
                                            Sent to RDD
                                        </option>

                                        <option value="scrapped"
                                            {{ old('status', $item->status) === 'scrapped' ? 'selected' : '' }}>
                                            Scrapped
                                        </option>

                                        <option value="transferred"
                                            {{ old('status', $item->status) === 'transferred' ? 'selected' : '' }}>
                                            Transferred
                                        </option>
                                    </select>
                                </div>

                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <span class="field-hint">
                                    Select the current operational state of this asset.
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- NETWORK & WORKSTATION --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <i class="bi bi-pc-display"></i>
                            Network &amp; Workstation Information
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="ip_address" class="form-label">IP Address</label>

                                <input type="text" name="ip_address" id="ip_address"
                                       class="form-control @error('ip_address') is-invalid @enderror"
                                       value="{{ old('ip_address', $item->ip_address) }}"
                                       placeholder="e.g. 192.168.10.20" maxlength="45">

                                @error('ip_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="workstation_id" class="form-label">Workstation ID</label>

                                <input type="text" name="workstation_id" id="workstation_id"
                                       class="form-control @error('workstation_id') is-invalid @enderror"
                                       value="{{ old('workstation_id', $item->workstation_id) }}"
                                       placeholder="Enter workstation identifier"
                                       maxlength="100">

                                @error('workstation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- LOCATION & ASSIGNMENT --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <i class="bi bi-geo-alt"></i>
                            Location &amp; Assignment
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="branch_id" class="form-label">Branch</label>

                                <select name="branch_id" id="branch_id"
                                        class="form-select @error('branch_id') is-invalid @enderror">
                                    <option value="">Select Branch</option>

                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $item->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="department_id" class="form-label">Department</label>

                                <select name="department_id" id="department_id"
                                        class="form-select @error('department_id') is-invalid @enderror">
                                    <option value="">Select Department</option>

                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                                data-branch-id="{{ $department->branch_id }}">
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="function_unit_id" class="form-label">Function Unit</label>

                                <select name="function_unit_id" id="function_unit_id"
                                        class="form-select @error('function_unit_id') is-invalid @enderror">
                                    <option value="">Select Function Unit</option>

                                    @foreach ($functionUnits as $functionUnit)
                                        <option value="{{ $functionUnit->id }}"
                                                data-department-id="{{ $functionUnit->department_id }}">
                                            {{ $functionUnit->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('function_unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ADDITIONAL INFORMATION --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <i class="bi bi-card-text"></i>
                            Additional Information
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="description" class="form-label">Description</label>

                                <textarea name="description" id="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Enter a brief description of the inventory item">{{ old('description', $item->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="notes" class="form-label">Notes</label>

                                <textarea name="notes" id="notes"
                                          class="form-control @error('notes') is-invalid @enderror"
                                          placeholder="Enter any additional operational or administrative notes">{{ old('notes', $item->notes) }}</textarea>

                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- FORM ACTIONS --}}
                    <div class="form-actions">
                        <a href="{{ route('admin.items.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>
                            Update Inventory Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       MAKE → MODEL FILTERING
    ========================================================= */

    const makeSelect = document.getElementById('make_id');
    const modelSelect = document.getElementById('model_id');
    const modelHelp = document.getElementById('modelHelp');
    const modelInfo = document.getElementById('modelInfo');
    const modelInfoText = document.getElementById('modelInfoText');
    const emptyModelMessage = document.getElementById('emptyModelMessage');

    if (makeSelect && modelSelect) {
        const allModelOptions = Array.from(
            modelSelect.querySelectorAll('option[data-make-id]')
        ).map(function (option) {
            return {
                value: option.value,
                text: option.textContent.trim(),
                makeId: option.getAttribute('data-make-id')
            };
        });

        function resetModelMessages() {
            modelInfo?.classList.remove('visible');
            emptyModelMessage?.classList.remove('visible');
        }

        function updateModels(selectedMakeId, selectedModelId = '') {
            resetModelMessages();
            modelSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = selectedMakeId
                ? 'Select Model'
                : 'Select Make First';

            modelSelect.appendChild(defaultOption);

            if (!selectedMakeId) {
                modelSelect.value = '';
                modelSelect.disabled = true;

                if (modelHelp) {
                    modelHelp.innerHTML = `
                        <i class="bi bi-info-circle"></i>
                        <span>Select a Make first to choose an associated Model.</span>
                    `;
                }

                return;
            }

            const filteredModels = allModelOptions.filter(function (model) {
                return String(model.makeId) === String(selectedMakeId);
            });

            if (filteredModels.length === 0) {
                modelSelect.value = '';
                modelSelect.disabled = true;

                if (modelHelp) {
                    modelHelp.innerHTML = `
                        <i class="bi bi-exclamation-circle"></i>
                        <span>No Models are available for this Make.</span>
                    `;
                }

                emptyModelMessage?.classList.add('visible');
                return;
            }

            filteredModels.forEach(function (model) {
                const option = document.createElement('option');
                option.value = model.value;
                option.textContent = model.text;

                if (
                    selectedModelId &&
                    String(selectedModelId) === String(model.value)
                ) {
                    option.selected = true;
                }

                modelSelect.appendChild(option);
            });

            modelSelect.disabled = false;

            if (modelInfo && modelInfoText) {
                modelInfoText.textContent =
                    `${filteredModels.length} ${filteredModels.length === 1 ? 'Model' : 'Models'} available for the selected Make.`;

                modelInfo.classList.add('visible');
            }

            if (modelHelp) {
                modelHelp.innerHTML = `
                    <i class="bi bi-funnel"></i>
                    <span>Models are filtered according to the selected Make.</span>
                `;
            }
        }

        makeSelect.addEventListener('change', function () {
            updateModels(this.value, '');
        });

        const initialMakeId = @json(old('make_id', $item->make_id));
        const initialModelId = @json(old('model_id', $item->model_id));

        if (initialMakeId) {
            makeSelect.value = String(initialMakeId);
        }

        updateModels(makeSelect.value, initialModelId);
    }

    /* =========================================================
       BRANCH → DEPARTMENT → FUNCTION UNIT FILTERING
    ========================================================= */

    const branchSelect = document.getElementById('branch_id');
    const departmentSelect = document.getElementById('department_id');
    const functionUnitSelect = document.getElementById('function_unit_id');

    if (branchSelect && departmentSelect && functionUnitSelect) {
        const departmentOptions = Array.from(
            departmentSelect.querySelectorAll('option[data-branch-id]')
        ).map(function (option) {
            return {
                value: option.value,
                text: option.textContent.trim(),
                branchId: option.getAttribute('data-branch-id')
            };
        });

        const functionUnitOptions = Array.from(
            functionUnitSelect.querySelectorAll('option[data-department-id]')
        ).map(function (option) {
            return {
                value: option.value,
                text: option.textContent.trim(),
                departmentId: option.getAttribute('data-department-id')
            };
        });

        function updateFunctionUnits(
            selectedDepartmentId,
            selectedFunctionUnitId = ''
        ) {
            functionUnitSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = selectedDepartmentId
                ? 'Select Function Unit'
                : 'Select Department First';

            functionUnitSelect.appendChild(defaultOption);

            if (!selectedDepartmentId) {
                functionUnitSelect.disabled = true;
                return;
            }

            const filteredFunctionUnits = functionUnitOptions.filter(
                function (functionUnit) {
                    return String(functionUnit.departmentId) ===
                        String(selectedDepartmentId);
                }
            );

            filteredFunctionUnits.forEach(function (functionUnit) {
                const option = document.createElement('option');
                option.value = functionUnit.value;
                option.textContent = functionUnit.text;

                if (
                    selectedFunctionUnitId &&
                    String(selectedFunctionUnitId) === String(functionUnit.value)
                ) {
                    option.selected = true;
                }

                functionUnitSelect.appendChild(option);
            });

            functionUnitSelect.disabled = filteredFunctionUnits.length === 0;
        }

        function updateDepartments(
            selectedBranchId,
            selectedDepartmentId = '',
            selectedFunctionUnitId = ''
        ) {
            departmentSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = selectedBranchId
                ? 'Select Department'
                : 'Select Branch First';

            departmentSelect.appendChild(defaultOption);

            if (!selectedBranchId) {
                departmentSelect.disabled = true;
                updateFunctionUnits('');
                return;
            }

            const filteredDepartments = departmentOptions.filter(
                function (department) {
                    return String(department.branchId) ===
                        String(selectedBranchId);
                }
            );

            filteredDepartments.forEach(function (department) {
                const option = document.createElement('option');
                option.value = department.value;
                option.textContent = department.text;

                if (
                    selectedDepartmentId &&
                    String(selectedDepartmentId) === String(department.value)
                ) {
                    option.selected = true;
                }

                departmentSelect.appendChild(option);
            });

            departmentSelect.disabled = filteredDepartments.length === 0;

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

        const initialBranchId = @json(old('branch_id', $item->branch_id));
        const initialDepartmentId = @json(old('department_id', $item->department_id));
        const initialFunctionUnitId = @json(
            old('function_unit_id', $item->function_unit_id)
        );

        if (initialBranchId) {
            branchSelect.value = String(initialBranchId);
        }

        updateDepartments(
            branchSelect.value,
            initialDepartmentId,
            initialFunctionUnitId
        );
    }
});
</script>

@endsection
