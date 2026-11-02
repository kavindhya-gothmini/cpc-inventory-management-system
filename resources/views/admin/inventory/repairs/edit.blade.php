@extends('layouts.admin')

@section('title', 'Edit Repair | CPC Inventory Management System')

@section('content')

<style>
    .repair-edit-page {
        padding: 24px;
        background: #f5f7fb;
        min-height: calc(100vh - 72px);
    }

    .repair-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .repair-header-left h1 {
        margin: 0 0 6px;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .repair-header-left p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .repair-header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .repair-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .repair-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e5e7eb;
        background: #fafbfc;
    }

    .repair-card-header h2 {
        margin: 0 0 5px;
        font-size: 18px;
        font-weight: 700;
        color: #172033;
    }

    .repair-card-header p {
        margin: 0;
        color: #6b7280;
        font-size: 13px;
    }

    .repair-card-body {
        padding: 22px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .required {
        color: #dc2626;
    }

    .form-control,
    .form-select {
        width: 100%;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #111827;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .form-help {
        margin-top: 6px;
        font-size: 12px;
        color: #6b7280;
    }

    .error-message {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
    }

    /* =========================================================
       ITEM PREVIEW
    ========================================================= */

    .item-preview {
        margin-top: 12px;
        padding: 14px;
        border: 1px solid #dbeafe;
        background: #eff6ff;
        border-radius: 10px;
    }

    .item-preview-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 8px;
    }

    .item-preview-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px 18px;
    }

    .item-preview-row {
        font-size: 12px;
        color: #4b5563;
    }

    .item-preview-row strong {
        color: #1f2937;
    }

    /* =========================================================
       ACCESSORIES
    ========================================================= */

    .accessory-section-note {
        margin-bottom: 18px;
        padding: 12px 14px;
        border-radius: 9px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 13px;
        line-height: 1.5;
    }

    .accessory-rows {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .accessory-row {
        display: grid;
        grid-template-columns: minmax(0, 2fr) 120px 170px 46px;
        gap: 12px;
        align-items: end;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fafafa;
    }

    .accessory-row.returned {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .accessory-status {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-returned {
        color: #166534;
    }

    .status-pending {
        color: #92400e;
    }

    .returned-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-size: 11px;
        font-weight: 700;
    }

    .pending-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border-radius: 999px;
        background: #fef3c7;
        color: #92400e;
        font-size: 11px;
        font-weight: 700;
    }

    .returned-info {
        grid-column: 1 / -1;
        padding: 10px 12px;
        background: #ffffff;
        border: 1px solid #d1fae5;
        border-radius: 8px;
        font-size: 12px;
        color: #4b5563;
    }

    .returned-info strong {
        color: #1f2937;
    }

    .btn-add-accessory {
        margin-top: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 15px;
        border: 1px solid #2563eb;
        border-radius: 8px;
        background: #2563eb;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-add-accessory:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-remove-accessory {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fef2f2;
        color: #dc2626;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .btn-remove-accessory:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .btn-remove-accessory:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }

    .empty-accessories {
        padding: 18px;
        border: 1px dashed #d1d5db;
        border-radius: 10px;
        background: #f9fafb;
        color: #6b7280;
        text-align: center;
        font-size: 13px;
    }

    .accessory-error-box {
        margin-bottom: 15px;
        padding: 12px 14px;
        border-radius: 8px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: 13px;
    }

    /* =========================================================
       BUTTONS
    ========================================================= */

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 4px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 9px 17px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-secondary {
        background: #ffffff;
        color: #374151;
        border-color: #d1d5db;
    }

    .btn-secondary:hover {
        background: #f3f4f6;
    }

    .btn-danger {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }

    .btn-danger:hover {
        background: #b91c1c;
        border-color: #b91c1c;
    }

    .alert {
        margin-bottom: 20px;
        padding: 13px 16px;
        border-radius: 9px;
        font-size: 13px;
    }

    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .accessory-row {
            grid-template-columns: 1fr 1fr;
        }

        .accessory-row .form-group:first-of-type {
            grid-column: 1 / -1;
        }

        .btn-remove-accessory {
            width: 100%;
        }
    }

    @media (max-width: 600px) {
        .repair-edit-page {
            padding: 15px;
        }

        .repair-header-left h1 {
            font-size: 23px;
        }

        .repair-card-body,
        .repair-card-header {
            padding: 16px;
        }

        .item-preview-grid {
            grid-template-columns: 1fr;
        }

        .accessory-row {
            grid-template-columns: 1fr;
        }

        .accessory-row .form-group:first-of-type {
            grid-column: auto;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>


<div class="repair-edit-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="repair-header">

        <div class="repair-header-left">

            <h1>
                Edit Repair Record
            </h1>

            <p>
                Update repair information, accessories and repair status.
            </p>

        </div>

        <div class="repair-header-actions">

            <a
                href="{{ route('admin.inventory.repairs.show', $repair) }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-eye"></i>
                View Repair
            </a>

            <a
                href="{{ route('admin.inventory.repairs.index') }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Repairs
            </a>

        </div>

    </div>


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul style="margin: 8px 0 0 18px; padding: 0;">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.inventory.repairs.update', $repair) }}"
        id="repair-edit-form"
    >

        @csrf
        @method('PUT')


        {{-- =================================================
             REPAIR INFORMATION
        ================================================== --}}
        <div class="repair-card">

            <div class="repair-card-header">

                <h2>
                    <i class="bi bi-wrench-adjustable-circle"></i>
                    Repair Information
                </h2>

                <p>
                    Update the inventory item and basic repair information.
                </p>

            </div>


            <div class="repair-card-body">

                <div class="form-grid">

                    {{-- Item --}}
                    <div class="form-group full-width">

                        <label for="item_id">
                            Inventory Item <span class="required">*</span>
                        </label>

                        <select
                            name="item_id"
                            id="item_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Select Inventory Item --
                            </option>

                            @foreach ($items as $item)

                                <option
                                    value="{{ $item->id }}"
                                    data-asset="{{ $item->asset_number }}"
                                    data-serial="{{ $item->serial_number }}"
                                    data-type="{{ $item->itemType->name ?? 'N/A' }}"
                                    data-ip="{{ $item->ip_address ?? 'N/A' }}"
                                    data-workstation="{{ $item->workstation_id ?? 'N/A' }}"
                                    data-employee="{{ $item->currentEmployee->name ?? 'Not Assigned' }}"
                                    {{ old('item_id', $repair->item_id) == $item->id ? 'selected' : '' }}
                                >

                                    {{ $item->asset_number }}
                                    -
                                    {{ $item->itemType->name ?? 'Item' }}

                                    @if ($item->serial_number)
                                        | SN: {{ $item->serial_number }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        <div class="form-help">
                            Select the inventory item associated with this repair.
                        </div>

                        @error('item_id')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror


                        {{-- Selected Item Preview --}}
                        <div class="item-preview">

                            <div class="item-preview-title">

                                <i class="bi bi-info-circle"></i>

                                Selected Item Information

                            </div>

                            <div class="item-preview-grid">

                                <div class="item-preview-row">
                                    <strong>Asset No:</strong>
                                    <span id="preview-asset">-</span>
                                </div>

                                <div class="item-preview-row">
                                    <strong>Item Type:</strong>
                                    <span id="preview-type">-</span>
                                </div>

                                <div class="item-preview-row">
                                    <strong>Serial No:</strong>
                                    <span id="preview-serial">-</span>
                                </div>

                                <div class="item-preview-row">
                                    <strong>IP Address:</strong>
                                    <span id="preview-ip">-</span>
                                </div>

                                <div class="item-preview-row">
                                    <strong>Workstation ID:</strong>
                                    <span id="preview-workstation">-</span>
                                </div>

                                <div class="item-preview-row">
                                    <strong>Current Employee:</strong>
                                    <span id="preview-employee">-</span>
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Repair Reference --}}
                    <div class="form-group">

                        <label for="repair_reference">
                            Repair Reference <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="repair_reference"
                            id="repair_reference"
                            class="form-control"
                            value="{{ old('repair_reference', $repair->repair_reference) }}"
                            placeholder="e.g. REP-2026-001"
                            required
                        >

                        @error('repair_reference')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Date Sent --}}
                    <div class="form-group">

                        <label for="date_sent">
                            Date Sent <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="date_sent"
                            id="date_sent"
                            class="form-control"
                            value="{{ old('date_sent', optional($repair->date_sent)->format('Y-m-d')) }}"
                            required
                        >

                        @error('date_sent')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Employee --}}
                    <div class="form-group">

                        <label for="employee_id">
                            Employee
                        </label>

                        <select
                            name="employee_id"
                            id="employee_id"
                            class="form-select"
                        >

                            <option value="">
                                -- Select Employee --
                            </option>

                            @foreach ($employees as $employee)

                                <option
                                    value="{{ $employee->id }}"
                                    {{ old('employee_id', $repair->employee_id) == $employee->id ? 'selected' : '' }}
                                >

                                    {{ $employee->name }}

                                    @if ($employee->epf_no)
                                        - {{ $employee->epf_no }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        <div class="form-help">
                            Employee responsible for the item before repair.
                        </div>

                        @error('employee_id')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Carrier --}}
                    <div class="form-group">

                        <label for="carrier">
                            Carrier / Person Sending Item
                        </label>

                        <input
                            type="text"
                            name="carrier"
                            id="carrier"
                            class="form-control"
                            value="{{ old('carrier', $repair->carrier) }}"
                            placeholder="Enter carrier or person name"
                        >

                        @error('carrier')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             ACCESSORIES
        ================================================== --}}
        <div class="repair-card">

            <div class="repair-card-header">

                <h2>
                    <i class="bi bi-bag-check"></i>
                    Accessories Handed Over for Repair
                </h2>

                <p>
                    Update accessories sent together with the item.
                    Returned accessories are locked to preserve the return history.
                </p>

            </div>


            <div class="repair-card-body">

                <div class="accessory-section-note">

                    <strong>Examples:</strong>

                    Laptop Bag, Charger, Mouse, Keyboard, Power Cable,
                    HDMI Cable, Docking Station, Headset, Webcam, etc.

                    <br>

                    Accessories already returned cannot be edited or deleted
                    from this form.

                </div>


                @if (
                    $errors->has('accessories') ||
                    $errors->has('accessories.*.accessory_name') ||
                    $errors->has('accessories.*.quantity') ||
                    $errors->has('accessories.*.issued_date')
                )

                    <div class="accessory-error-box">

                        Please check the accessory details and make sure the
                        accessory name, quantity and date are valid.

                    </div>

                @endif


                <div id="accessory-rows" class="accessory-rows">

                    @foreach ($repair->accessories as $index => $accessory)

                        <div
                            class="accessory-row {{ $accessory->returned ? 'returned' : '' }}"
                            data-existing="true"
                            data-returned="{{ $accessory->returned ? '1' : '0' }}"
                        >

                            <input
                                type="hidden"
                                name="accessories[{{ $index }}][id]"
                                value="{{ $accessory->id }}"
                            >


                            {{-- Status --}}
                            <div class="accessory-status">

                                @if ($accessory->returned)

                                    <span class="returned-badge">
                                        <i class="bi bi-check-circle"></i>
                                        Returned
                                    </span>

                                    <span class="status-returned">
                                        This accessory has already been returned.
                                    </span>

                                @else

                                    <span class="pending-badge">
                                        <i class="bi bi-clock"></i>
                                        Awaiting Return
                                    </span>

                                    <span class="status-pending">
                                        This accessory is still expected to be returned.
                                    </span>

                                @endif

                            </div>


                            {{-- Accessory Name --}}
                            <div class="form-group">

                                <label>
                                    Accessory Name
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="accessories[{{ $index }}][accessory_name]"
                                    class="form-control"
                                    value="{{ old("accessories.$index.accessory_name", $accessory->accessory_name) }}"
                                    list="cpc-accessory-options"
                                    placeholder="e.g. Laptop Charger"
                                    {{ $accessory->returned ? 'readonly' : 'required' }}
                                >

                            </div>


                            {{-- Quantity --}}
                            <div class="form-group">

                                <label>
                                    Quantity
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="accessories[{{ $index }}][quantity]"
                                    class="form-control"
                                    value="{{ old("accessories.$index.quantity", $accessory->quantity) }}"
                                    min="1"
                                    max="999"
                                    {{ $accessory->returned ? 'readonly' : 'required' }}
                                >

                            </div>


                            {{-- Issued Date --}}
                            <div class="form-group">

                                <label>
                                    Date Handed Over
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="accessories[{{ $index }}][issued_date]"
                                    class="form-control"
                                    value="{{ old("accessories.$index.issued_date", optional($accessory->issued_date)->format('Y-m-d')) }}"
                                    {{ $accessory->returned ? 'readonly' : 'required' }}
                                >

                            </div>


                            {{-- Remove --}}
                            <button
                                type="button"
                                class="btn-remove-accessory"
                                title="Remove accessory"
                                {{ $accessory->returned ? 'disabled' : '' }}
                            >
                                <i class="bi bi-trash"></i>
                            </button>


                            {{-- Returned Information --}}
                            @if ($accessory->returned)

                                <div class="returned-info">

                                    <strong>
                                        <i class="bi bi-check2-circle"></i>
                                        Return Information:
                                    </strong>

                                    Returned on
                                    <strong>
                                        {{ optional($accessory->returned_date)->format('d M Y') ?? 'N/A' }}
                                    </strong>

                                    by
                                    <strong>
                                        {{ $accessory->returned_by ?? 'N/A' }}
                                    </strong>

                                    @if ($accessory->return_remarks)
                                        <br>
                                        <strong>Remarks:</strong>
                                        {{ $accessory->return_remarks }}
                                    @endif

                                </div>

                            @endif

                        </div>

                    @endforeach


                    {{-- Old input after validation error --}}
                    @if ($repair->accessories->isEmpty() && count(old('accessories', [])) > 0)

                        @foreach (old('accessories', []) as $index => $accessory)

                            <div class="accessory-row">

                                <input
                                    type="hidden"
                                    name="accessories[{{ $index }}][id]"
                                    value="{{ $accessory['id'] ?? '' }}"
                                >

                                <div class="form-group">

                                    <label>
                                        Accessory Name
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="accessories[{{ $index }}][accessory_name]"
                                        class="form-control"
                                        value="{{ $accessory['accessory_name'] ?? '' }}"
                                        list="cpc-accessory-options"
                                        required
                                    >

                                </div>

                                <div class="form-group">

                                    <label>
                                        Quantity
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="number"
                                        name="accessories[{{ $index }}][quantity]"
                                        class="form-control"
                                        value="{{ $accessory['quantity'] ?? 1 }}"
                                        min="1"
                                        max="999"
                                        required
                                    >

                                </div>

                                <div class="form-group">

                                    <label>
                                        Date Handed Over
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        name="accessories[{{ $index }}][issued_date]"
                                        class="form-control"
                                        value="{{ $accessory['issued_date'] ?? old('date_sent', now()->format('Y-m-d')) }}"
                                        required
                                    >

                                </div>

                                <button
                                    type="button"
                                    class="btn-remove-accessory"
                                    title="Remove accessory"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>

                        @endforeach

                    @endif

                </div>


                <div
                    id="no-accessories"
                    class="empty-accessories"
                    style="{{ $repair->accessories->count() > 0 ? 'display:none;' : '' }}"
                >

                    <i class="bi bi-info-circle"></i>

                    No accessories recorded.

                    Click <strong>Add Accessory</strong>
                    if an accessory was handed over with the item.

                </div>


                <button
                    type="button"
                    id="add-accessory"
                    class="btn-add-accessory"
                >

                    <i class="bi bi-plus-circle"></i>

                    Add Accessory

                </button>

            </div>

        </div>


        {{-- =================================================
             REPAIR DETAILS
        ================================================== --}}
        <div class="repair-card">

            <div class="repair-card-header">

                <h2>
                    <i class="bi bi-clipboard2-pulse"></i>
                    Repair Details
                </h2>

                <p>
                    Update the problem, technician, status and repair cost.
                </p>

            </div>


            <div class="repair-card-body">

                <div class="form-grid">

                    {{-- Problem --}}
                    <div class="form-group full-width">

                        <label for="problem_description">
                            Problem Description
                            <span class="required">*</span>
                        </label>

                        <textarea
                            name="problem_description"
                            id="problem_description"
                            class="form-control"
                            placeholder="Describe the problem reported with the item..."
                            required
                        >{{ old('problem_description', $repair->problem_description) }}</textarea>

                        @error('problem_description')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Technician --}}
                    <div class="form-group">

                        <label for="technician">
                            Technician
                        </label>

                        <input
                            type="text"
                            name="technician"
                            id="technician"
                            class="form-control"
                            value="{{ old('technician', $repair->technician) }}"
                            placeholder="Technician / service provider"
                        >

                        @error('technician')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="form-group">

                        <label for="status">
                            Repair Status
                            <span class="required">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="pending"
                                {{ old('status', $repair->status) === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="sent_for_repair"
                                {{ old('status', $repair->status) === 'sent_for_repair' ? 'selected' : '' }}
                            >
                                Sent for Repair
                            </option>

                            <option
                                value="under_repair"
                                {{ old('status', $repair->status) === 'under_repair' ? 'selected' : '' }}
                            >
                                Under Repair
                            </option>

                            <option
                                value="completed"
                                {{ old('status', $repair->status) === 'completed' ? 'selected' : '' }}
                            >
                                Completed
                            </option>

                            <option
                                value="returned"
                                {{ old('status', $repair->status) === 'returned' ? 'selected' : '' }}
                            >
                                Returned
                            </option>

                            <option
                                value="cancelled"
                                {{ old('status', $repair->status) === 'cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>

                        </select>

                        @error('status')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Date Returned --}}
                    <div class="form-group">

                        <label for="date_returned">
                            Date Returned
                        </label>

                        <input
                            type="date"
                            name="date_returned"
                            id="date_returned"
                            class="form-control"
                            value="{{ old('date_returned', optional($repair->date_returned)->format('Y-m-d')) }}"
                        >

                        <div class="form-help">
                            Date the repaired item was received back.
                        </div>

                        @error('date_returned')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Repair Cost --}}
                    <div class="form-group">

                        <label for="repair_cost">
                            Repair Cost
                        </label>

                        <input
                            type="number"
                            name="repair_cost"
                            id="repair_cost"
                            class="form-control"
                            value="{{ old('repair_cost', $repair->repair_cost) }}"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                        >

                        @error('repair_cost')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Remarks --}}
                    <div class="form-group full-width">

                        <label for="remarks">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            id="remarks"
                            class="form-control"
                            placeholder="Additional repair remarks..."
                        >{{ old('remarks', $repair->remarks) }}</textarea>

                        @error('remarks')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             ACTIONS
        ================================================== --}}
        <div class="form-actions">

            <a
                href="{{ route('admin.inventory.repairs.show', $repair) }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-x-circle"></i>
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-check-circle"></i>
                Update Repair Record
            </button>

        </div>

    </form>

</div>


{{-- =========================================================
     ACCESSORY OPTIONS
========================================================= --}}
<datalist id="cpc-accessory-options">

    <option value="Laptop Bag">
    <option value="Laptop Charger">
    <option value="Desktop Power Cable">
    <option value="Mouse">
    <option value="Keyboard">
    <option value="Monitor">
    <option value="HDMI Cable">
    <option value="DisplayPort Cable">
    <option value="VGA Cable">
    <option value="Docking Station">
    <option value="Headset">
    <option value="Webcam">
    <option value="Laptop Carrying Case">
    <option value="Power Adapter">
    <option value="Other">

</datalist>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       ITEM PREVIEW
    ====================================================== */

    const itemSelect =
        document.getElementById('item_id');

    const previewAsset =
        document.getElementById('preview-asset');

    const previewType =
        document.getElementById('preview-type');

    const previewSerial =
        document.getElementById('preview-serial');

    const previewIp =
        document.getElementById('preview-ip');

    const previewWorkstation =
        document.getElementById('preview-workstation');

    const previewEmployee =
        document.getElementById('preview-employee');


    function updateItemPreview() {

        const selectedOption =
            itemSelect.options[itemSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {

            previewAsset.textContent = '-';
            previewType.textContent = '-';
            previewSerial.textContent = '-';
            previewIp.textContent = '-';
            previewWorkstation.textContent = '-';
            previewEmployee.textContent = '-';

            return;
        }

        previewAsset.textContent =
            selectedOption.dataset.asset || 'N/A';

        previewType.textContent =
            selectedOption.dataset.type || 'N/A';

        previewSerial.textContent =
            selectedOption.dataset.serial || 'N/A';

        previewIp.textContent =
            selectedOption.dataset.ip || 'N/A';

        previewWorkstation.textContent =
            selectedOption.dataset.workstation || 'N/A';

        previewEmployee.textContent =
            selectedOption.dataset.employee || 'Not Assigned';
    }


    itemSelect.addEventListener(
        'change',
        updateItemPreview
    );

    updateItemPreview();


    /* =====================================================
       ACCESSORY MANAGEMENT
    ====================================================== */

    const accessoryRows =
        document.getElementById('accessory-rows');

    const noAccessories =
        document.getElementById('no-accessories');

    const addAccessoryButton =
        document.getElementById('add-accessory');

    const defaultAccessoryDate =
        @json(old('date_sent', optional($repair->date_sent)->format('Y-m-d') ?? now()->format('Y-m-d')));


    let accessoryIndex =
        {{ max(
            $repair->accessories->count(),
            count(old('accessories', []))
        ) }};


    function updateEmptyState() {

        const rows =
            accessoryRows.querySelectorAll('.accessory-row');

        if (rows.length === 0) {

            noAccessories.style.display = 'block';

        } else {

            noAccessories.style.display = 'none';

        }
    }


    function attachRemoveButton(button) {

        button.addEventListener('click', function () {

            if (button.disabled) {
                return;
            }

            const row =
                button.closest('.accessory-row');

            if (!row) {
                return;
            }

            /*
             * Existing database accessories must be removed through
             * the accessory DELETE route, not simply removed from
             * the edit form.
             *
             * For newly added rows, we can remove them directly.
             */

            const existing =
                row.dataset.existing === 'true';

            if (existing) {

                const accessoryId =
                    row.querySelector('input[name*="[id]"]')?.value;

                if (!accessoryId) {
                    row.remove();
                    updateEmptyState();
                    return;
                }

                const confirmed =
                    window.confirm(
                        'Are you sure you want to remove this accessory from the repair record?'
                    );

                if (!confirmed) {
                    return;
                }

                /*
                 * Create a small DELETE form dynamically.
                 */
                const form =
                    document.createElement('form');

                form.method = 'POST';

                form.action =
                    "{{ route('admin.inventory.repairs.accessories.destroy', [
                        'repair' => $repair->id,
                        'accessory' => '__ACCESSORY_ID__'
                    ]) }}"
                    .replace(
                        '__ACCESSORY_ID__',
                        accessoryId
                    );

                const csrf =
                    document.createElement('input');

                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = "{{ csrf_token() }}";

                const method =
                    document.createElement('input');

                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';

                form.appendChild(csrf);
                form.appendChild(method);

                document.body.appendChild(form);

                form.submit();

                return;
            }


            row.remove();

            updateEmptyState();

        });

    }


    /*
     * Existing accessory buttons
     */
    document
        .querySelectorAll('.btn-remove-accessory')
        .forEach(function (button) {

            attachRemoveButton(button);

        });


    /* =====================================================
       ADD NEW ACCESSORY
    ====================================================== */

    function addAccessoryRow() {

        const index =
            accessoryIndex++;

        const row =
            document.createElement('div');

        row.className =
            'accessory-row';

        row.dataset.existing =
            'false';

        row.dataset.returned =
            '0';


        row.innerHTML = `

            <input
                type="hidden"
                name="accessories[${index}][id]"
                value=""
            >

            <div class="accessory-status">

                <span class="pending-badge">
                    <i class="bi bi-clock"></i>
                    Awaiting Return
                </span>

                <span class="status-pending">
                    New accessory will be recorded with this repair.
                </span>

            </div>


            <div class="form-group">

                <label>
                    Accessory Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    name="accessories[${index}][accessory_name]"
                    class="form-control"
                    list="cpc-accessory-options"
                    placeholder="e.g. Laptop Charger"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Quantity
                    <span class="required">*</span>
                </label>

                <input
                    type="number"
                    name="accessories[${index}][quantity]"
                    class="form-control"
                    value="1"
                    min="1"
                    max="999"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Date Handed Over
                    <span class="required">*</span>
                </label>

                <input
                    type="date"
                    name="accessories[${index}][issued_date]"
                    class="form-control"
                    value="${defaultAccessoryDate}"
                    required
                >

            </div>


            <button
                type="button"
                class="btn-remove-accessory"
                title="Remove accessory"
            >
                <i class="bi bi-trash"></i>
            </button>

        `;


        accessoryRows.appendChild(row);


        const removeButton =
            row.querySelector('.btn-remove-accessory');


        attachRemoveButton(
            removeButton
        );


        updateEmptyState();
    }


    addAccessoryButton.addEventListener(
        'click',
        addAccessoryRow
    );


    updateEmptyState();

});
</script>

@endsection