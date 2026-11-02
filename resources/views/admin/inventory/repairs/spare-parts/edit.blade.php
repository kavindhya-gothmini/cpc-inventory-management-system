@extends('layouts.admin')

@section('title', 'Edit Spare Part | CPC Inventory Management System')

@section('content')

<style>
.spare-part-edit-page {
    min-height: calc(100vh - 72px);
    background: #f4f7fb;
    padding: 28px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 24px;
}

.page-title h1 {
    margin: 0;
    color: #172033;
    font-size: 27px;
    font-weight: 700;
}

.page-title p {
    margin: 7px 0 0;
    color: #718096;
    font-size: 14px;
}

.header-actions,
.form-actions {
    display: flex;
    gap: 10px;
}

.btn-cpc {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 16px;
    border-radius: 9px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
}

.btn-primary-cpc {
    background: #0d6efd;
    color: #fff;
}

.btn-primary-cpc:hover {
    background: #0b5ed7;
    color: #fff;
}

.btn-secondary-cpc {
    background: #fff;
    color: #374151;
    border: 1px solid #dbe2ea;
}

.btn-secondary-cpc:hover {
    background: #f8fafc;
    color: #1f2937;
}

.form-layout {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
    gap: 22px;
    align-items: start;
}

.form-card,
.info-card {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(15, 23, 42, .04);
    overflow: hidden;
}

.card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #edf1f5;
}

.card-header h2 {
    margin: 0;
    color: #172033;
    font-size: 16px;
    font-weight: 700;
}

.card-header p {
    margin: 5px 0 0;
    color: #718096;
    font-size: 12px;
}

.card-body {
    padding: 22px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
}

.required {
    color: #dc3545;
}

.form-control-cpc {
    width: 100%;
    min-height: 43px;
    padding: 9px 12px;
    border: 1px solid #d9e1ea;
    border-radius: 8px;
    background: #fff;
    color: #1f2937;
    font-size: 13px;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}

textarea.form-control-cpc {
    min-height: 105px;
    resize: vertical;
}

.form-control-cpc:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, .1);
}

.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    display: block;
    margin-top: 5px;
    color: #dc3545;
    font-size: 11px;
}

.field-help {
    margin-top: 5px;
    color: #94a3b8;
    font-size: 11px;
}

.stock-highlight {
    background: #f8fbff;
    border: 1px solid #dcecff;
    border-radius: 10px;
    padding: 16px;
}

.stock-highlight-title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    color: #0d6efd;
    font-size: 13px;
    font-weight: 700;
}

.current-stock {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 7px;
    padding: 5px 9px;
    border-radius: 7px;
    background: #e8f1ff;
    color: #0d6efd;
    font-size: 11px;
    font-weight: 700;
}

.info-section {
    padding: 20px;
    border-bottom: 1px solid #edf1f5;
}

.info-section:last-child {
    border-bottom: none;
}

.info-title {
    margin-bottom: 13px;
    color: #172033;
    font-size: 14px;
    font-weight: 700;
}

.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-list li {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-bottom: 12px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}

.info-list li:last-child {
    margin-bottom: 0;
}

.info-list i {
    color: #0d6efd;
    margin-top: 2px;
}

.part-summary {
    padding: 14px;
    background: #f8fafc;
    border-radius: 9px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 7px 0;
    font-size: 12px;
}

.summary-row:not(:last-child) {
    border-bottom: 1px solid #e9eef3;
}

.summary-label {
    color: #718096;
}

.summary-value {
    color: #172033;
    font-weight: 600;
    text-align: right;
}

.warning-box {
    padding: 13px;
    border-radius: 9px;
    background: #fff8e8;
    border: 1px solid #ffe5a8;
    color: #8a6200;
    font-size: 12px;
    line-height: 1.5;
}

.form-actions {
    justify-content: flex-end;
    padding: 18px 22px;
    border-top: 1px solid #edf1f5;
    background: #fafcff;
}

@media (max-width: 1000px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {
    .spare-part-edit-page {
        padding: 18px;
    }

    .page-header {
        flex-direction: column;
    }

    .header-actions {
        width: 100%;
    }

    .header-actions .btn-cpc {
        flex: 1;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: auto;
    }
}
</style>


<div class="spare-part-edit-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="page-header">

        <div class="page-title">

            <h1>
                <i class="bi bi-pencil-square"></i>
                Edit Spare Part
            </h1>

            <p>
                Update spare part information, stock level,
                warranty and status.
            </p>

        </div>

        <div class="header-actions">

            <a
                href="{{ route('admin.inventory.repairs.spare-parts.show', $sparePart) }}"
                class="btn-cpc btn-secondary-cpc"
            >
                <i class="bi bi-eye"></i>
                View Details
            </a>

            <a
                href="{{ route('admin.inventory.repairs.spare-parts.index') }}"
                class="btn-cpc btn-secondary-cpc"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =====================================================
         ERRORS
    ====================================================== --}}
    @if($errors->any())

        <div style="
            background:#fff0f0;
            border:1px solid #ffc9c9;
            color:#842029;
            padding:15px 18px;
            border-radius:10px;
            margin-bottom:20px;
        ">

            <strong>Please correct the following errors:</strong>

            <ul style="margin:8px 0 0 18px;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="form-layout">

        {{-- =================================================
             MAIN FORM
        ================================================== --}}
        <div class="form-card">

            <div class="card-header">

                <h2>
                    <i class="bi bi-box-seam"></i>
                    Spare Part Information
                </h2>

                <p>
                    Modify the registered information for this
                    spare part.
                </p>

            </div>


            <form
                method="POST"
                action="{{ route('admin.inventory.repairs.spare-parts.update', $sparePart) }}"
            >

                @csrf
                @method('PUT')


                <div class="card-body">

                    <div class="form-grid">

                        {{-- Part Code --}}
                        <div class="form-group">

                            <label
                                for="part_code"
                                class="form-label"
                            >
                                Part Code
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="part_code"
                                name="part_code"
                                class="form-control-cpc @error('part_code') is-invalid @enderror"
                                value="{{ old('part_code', $sparePart->part_code) }}"
                                required
                            >

                            @error('part_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Part Name --}}
                        <div class="form-group">

                            <label
                                for="part_name"
                                class="form-label"
                            >
                                Part Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="part_name"
                                name="part_name"
                                class="form-control-cpc @error('part_name') is-invalid @enderror"
                                value="{{ old('part_name', $sparePart->part_name) }}"
                                required
                            >

                            @error('part_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Category --}}
                        <div class="form-group">

                            <label
                                for="category"
                                class="form-label"
                            >
                                Category
                            </label>

                            <input
                                type="text"
                                id="category"
                                name="category"
                                class="form-control-cpc @error('category') is-invalid @enderror"
                                value="{{ old('category', $sparePart->category) }}"
                                placeholder="e.g. Computer Components"
                            >

                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Supplier --}}
                        <div class="form-group">

                            <label
                                for="supplier_name"
                                class="form-label"
                            >
                                Supplier
                            </label>

                            <input
                                type="text"
                                id="supplier_name"
                                name="supplier_name"
                                class="form-control-cpc @error('supplier_name') is-invalid @enderror"
                                value="{{ old('supplier_name', $sparePart->supplier_name) }}"
                                placeholder="Supplier / Vendor name"
                            >

                            @error('supplier_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Supplier Reference --}}
                        <div class="form-group">

                            <label
                                for="supplier_reference"
                                class="form-label"
                            >
                                Supplier Reference
                            </label>

                            <input
                                type="text"
                                id="supplier_reference"
                                name="supplier_reference"
                                class="form-control-cpc @error('supplier_reference') is-invalid @enderror"
                                value="{{ old('supplier_reference', $sparePart->supplier_reference) }}"
                                placeholder="Invoice / PO / reference"
                            >

                            @error('supplier_reference')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Location --}}
                        <div class="form-group">

                            <label
                                for="location"
                                class="form-label"
                            >
                                Storage Location
                            </label>

                            <input
                                type="text"
                                id="location"
                                name="location"
                                class="form-control-cpc @error('location') is-invalid @enderror"
                                value="{{ old('location', $sparePart->location) }}"
                                placeholder="CPC IT Store / Location"
                            >

                            @error('location')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="form-group full-width">

                            <label
                                for="description"
                                class="form-label"
                            >
                                Description
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                class="form-control-cpc @error('description') is-invalid @enderror"
                                placeholder="Specifications, compatibility or other information..."
                            >{{ old('description', $sparePart->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================================
                             STOCK
                        ================================================== --}}
                        <div class="form-group full-width">

                            <div class="stock-highlight">

                                <div class="stock-highlight-title">

                                    <i class="bi bi-boxes"></i>

                                    Stock & Pricing

                                </div>


                                <div class="form-grid">

                                    {{-- Quantity --}}
                                    <div class="form-group">

                                        <label
                                            for="quantity"
                                            class="form-label"
                                        >
                                            Current Quantity
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="number"
                                            id="quantity"
                                            name="quantity"
                                            class="form-control-cpc @error('quantity') is-invalid @enderror"
                                            value="{{ old('quantity', $sparePart->quantity) }}"
                                            min="0"
                                            step="1"
                                            required
                                        >

                                        <div class="current-stock">

                                            <i class="bi bi-database"></i>

                                            Existing:
                                            {{ number_format($sparePart->quantity) }}

                                            units

                                        </div>

                                        <div class="field-help">
                                            Changing this quantity creates
                                            a stock adjustment history record.
                                        </div>

                                        @error('quantity')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- Minimum --}}
                                    <div class="form-group">

                                        <label
                                            for="minimum_stock"
                                            class="form-label"
                                        >
                                            Minimum Stock
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="number"
                                            id="minimum_stock"
                                            name="minimum_stock"
                                            class="form-control-cpc @error('minimum_stock') is-invalid @enderror"
                                            value="{{ old('minimum_stock', $sparePart->minimum_stock) }}"
                                            min="0"
                                            step="1"
                                            required
                                        >

                                        @error('minimum_stock')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- Unit Price --}}
                                    <div class="form-group">

                                        <label
                                            for="unit_price"
                                            class="form-label"
                                        >
                                            Unit Price
                                        </label>

                                        <input
                                            type="number"
                                            id="unit_price"
                                            name="unit_price"
                                            class="form-control-cpc @error('unit_price') is-invalid @enderror"
                                            value="{{ old('unit_price', $sparePart->unit_price) }}"
                                            min="0"
                                            step="0.01"
                                        >

                                        @error('unit_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- Status --}}
                                    <div class="form-group">

                                        <label
                                            for="status"
                                            class="form-label"
                                        >
                                            Status
                                            <span class="required">*</span>
                                        </label>

                                        <select
                                            id="status"
                                            name="status"
                                            class="form-control-cpc @error('status') is-invalid @enderror"
                                            required
                                        >

                                            <option
                                                value="active"
                                                @selected(old('status', $sparePart->status) === 'active')
                                            >
                                                Active
                                            </option>

                                            <option
                                                value="inactive"
                                                @selected(old('status', $sparePart->status) === 'inactive')
                                            >
                                                Inactive
                                            </option>

                                        </select>

                                        @error('status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Warranty Start --}}
                        <div class="form-group">

                            <label
                                for="warranty_start"
                                class="form-label"
                            >
                                Warranty Start
                            </label>

                            <input
                                type="date"
                                id="warranty_start"
                                name="warranty_start"
                                class="form-control-cpc @error('warranty_start') is-invalid @enderror"
                                value="{{ old(
                                    'warranty_start',
                                    optional($sparePart->warranty_start)->format('Y-m-d')
                                ) }}"
                            >

                            @error('warranty_start')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Warranty End --}}
                        <div class="form-group">

                            <label
                                for="warranty_end"
                                class="form-label"
                            >
                                Warranty End
                            </label>

                            <input
                                type="date"
                                id="warranty_end"
                                name="warranty_end"
                                class="form-control-cpc @error('warranty_end') is-invalid @enderror"
                                value="{{ old(
                                    'warranty_end',
                                    optional($sparePart->warranty_end)->format('Y-m-d')
                                ) }}"
                            >

                            @error('warranty_end')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ACTIONS
                ================================================== --}}
                <div class="form-actions">

                    <a
                        href="{{ route('admin.inventory.repairs.spare-parts.show', $sparePart) }}"
                        class="btn-cpc btn-secondary-cpc"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn-cpc btn-primary-cpc"
                    >
                        <i class="bi bi-check-lg"></i>
                        Update Spare Part
                    </button>

                </div>

            </form>

        </div>


        {{-- =================================================
             SIDEBAR
        ================================================== --}}
        <div class="info-card">

            {{-- Current Part --}}
            <div class="info-section">

                <div class="info-title">
                    <i class="bi bi-info-circle"></i>
                    Current Part
                </div>

                <div class="part-summary">

                    <div class="summary-row">

                        <span class="summary-label">
                            Part Code
                        </span>

                        <span class="summary-value">
                            {{ $sparePart->part_code }}
                        </span>

                    </div>

                    <div class="summary-row">

                        <span class="summary-label">
                            Part Name
                        </span>

                        <span class="summary-value">
                            {{ $sparePart->part_name }}
                        </span>

                    </div>

                    <div class="summary-row">

                        <span class="summary-label">
                            Category
                        </span>

                        <span class="summary-value">
                            {{ $sparePart->category ?: '—' }}
                        </span>

                    </div>

                    <div class="summary-row">

                        <span class="summary-label">
                            Current Stock
                        </span>

                        <span class="summary-value">
                            {{ number_format($sparePart->quantity) }}
                        </span>

                    </div>

                    <div class="summary-row">

                        <span class="summary-label">
                            Minimum Stock
                        </span>

                        <span class="summary-value">
                            {{ number_format($sparePart->minimum_stock) }}
                        </span>

                    </div>

                    <div class="summary-row">

                        <span class="summary-label">
                            Status
                        </span>

                        <span class="summary-value">
                            {{ ucfirst($sparePart->status) }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- Stock History --}}
            <div class="info-section">

                <div class="info-title">
                    <i class="bi bi-clock-history"></i>
                    Stock History
                </div>

                <ul class="info-list">

                    <li>
                        <i class="bi bi-check-circle"></i>

                        <span>
                            Stock usage from repairs is tracked
                            automatically.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle"></i>

                        <span>
                            Increasing or decreasing the current
                            quantity creates an adjustment record.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle"></i>

                        <span>
                            Previous stock quantities remain
                            available in the audit history.
                        </span>
                    </li>

                </ul>

            </div>


            {{-- Warning --}}
            <div class="info-section">

                <div class="warning-box">

                    <strong>
                        <i class="bi bi-exclamation-triangle"></i>
                        Important
                    </strong>

                    <br>

                    Do not manually reduce stock to record a repair
                    part usage. Repair usage will be handled through
                    the Repair Management module so the system can
                    maintain the complete stock history.

                </div>

            </div>

        </div>

    </div>

</div>

@endsection