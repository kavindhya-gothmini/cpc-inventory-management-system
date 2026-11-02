@extends('layouts.admin')

@section('title', 'Add Spare Part | CPC Inventory Management System')

@section('content')

<style>
/* =========================================================
   CPC CREATE SPARE PART
========================================================= */

.spare-part-create-page {
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

.header-actions {
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

/* Main grid */
.form-layout {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
    gap: 22px;
    align-items: start;
}

.form-card {
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

/* Form */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.form-group {
    margin-bottom: 2px;
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

.form-control-cpc.is-invalid {
    border-color: #dc3545;
}

.field-help {
    margin-top: 5px;
    color: #94a3b8;
    font-size: 11px;
}

.invalid-feedback {
    display: block;
    margin-top: 5px;
    color: #dc3545;
    font-size: 11px;
}

/* Stock card */
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
    color: #0d6efd;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 14px;
}

/* Sidebar */
.info-card {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(15, 23, 42, .04);
    overflow: hidden;
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
    margin: 0;
    padding: 0;
    list-style: none;
}

.info-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
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

.warning-box {
    padding: 13px;
    border-radius: 9px;
    background: #fff8e8;
    border: 1px solid #ffe5a8;
    color: #8a6200;
    font-size: 12px;
    line-height: 1.5;
}

.status-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 13px;
    background: #f8fafc;
    border-radius: 9px;
    margin-bottom: 9px;
}

.status-preview:last-child {
    margin-bottom: 0;
}

.status-name {
    color: #475569;
    font-size: 12px;
    font-weight: 600;
}

.status-badge {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
}

.badge-active {
    background: #e8f7ef;
    color: #198754;
}

.badge-inactive {
    background: #eef1f4;
    color: #64748b;
}

/* Bottom actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 18px 22px;
    border-top: 1px solid #edf1f5;
    background: #fafcff;
}

/* Responsive */
@media (max-width: 1000px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {
    .spare-part-create-page {
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


<div class="spare-part-create-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="page-header">

        <div class="page-title">

            <h1>
                <i class="bi bi-plus-square"></i>
                Add Spare Part
            </h1>

            <p>
                Register a new spare part for CPC IT repair and
                inventory management.
            </p>

        </div>

        <div class="header-actions">

            <a
                href="{{ route('admin.inventory.repairs.spare-parts.index') }}"
                class="btn-cpc btn-secondary-cpc"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Spare Parts
            </a>

        </div>

    </div>


    {{-- =====================================================
         VALIDATION ERRORS
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

            <strong>
                Please correct the following errors:
            </strong>

            <ul style="margin:8px 0 0 18px;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         FORM LAYOUT
    ====================================================== --}}
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
                    Enter the basic identification and supplier
                    information for this spare part.
                </p>

            </div>


            <form
                method="POST"
                action="{{ route('admin.inventory.repairs.spare-parts.store') }}"
            >

                @csrf


                <div class="card-body">

                    <div class="form-grid">

                        {{-- =================================
                             PART CODE
                        ================================== --}}
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
                                value="{{ old('part_code') }}"
                                placeholder="e.g. RAM-DDR4-001"
                                required
                            >

                            <div class="field-help">
                                Unique code used to identify the spare part.
                            </div>

                            @error('part_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================
                             PART NAME
                        ================================== --}}
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
                                value="{{ old('part_name') }}"
                                placeholder="e.g. 8GB DDR4 RAM"
                                required
                            >

                            @error('part_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================
                             CATEGORY
                        ================================== --}}
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
                                value="{{ old('category') }}"
                                placeholder="e.g. Computer Components"
                            >

                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================
                             SUPPLIER
                        ================================== --}}
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
                                value="{{ old('supplier_name') }}"
                                placeholder="Supplier / Vendor name"
                            >

                            @error('supplier_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================
                             SUPPLIER REFERENCE
                        ================================== --}}
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
                                value="{{ old('supplier_reference') }}"
                                placeholder="Invoice / PO / supplier reference"
                            >

                            @error('supplier_reference')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================
                             LOCATION
                        ================================== --}}
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
                                value="{{ old('location') }}"
                                placeholder="e.g. CPC Head Office IT Store"
                            >

                            @error('location')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================
                             DESCRIPTION
                        ================================== --}}
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
                                placeholder="Enter spare part specifications, compatibility information or other details..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================
                             STOCK SECTION
                        ================================== --}}
                        <div class="form-group full-width">

                            <div class="stock-highlight">

                                <div class="stock-highlight-title">

                                    <i class="bi bi-boxes"></i>

                                    Initial Stock Information

                                </div>


                                <div class="form-grid">

                                    {{-- Quantity --}}
                                    <div class="form-group">

                                        <label
                                            for="quantity"
                                            class="form-label"
                                        >
                                            Initial Quantity
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="number"
                                            id="quantity"
                                            name="quantity"
                                            class="form-control-cpc @error('quantity') is-invalid @enderror"
                                            value="{{ old('quantity', 0) }}"
                                            min="0"
                                            step="1"
                                            required
                                        >

                                        <div class="field-help">
                                            Number of units currently received.
                                        </div>

                                        @error('quantity')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- Minimum Stock --}}
                                    <div class="form-group">

                                        <label
                                            for="minimum_stock"
                                            class="form-label"
                                        >
                                            Minimum Stock Level
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="number"
                                            id="minimum_stock"
                                            name="minimum_stock"
                                            class="form-control-cpc @error('minimum_stock') is-invalid @enderror"
                                            value="{{ old('minimum_stock', 0) }}"
                                            min="0"
                                            step="1"
                                            required
                                        >

                                        <div class="field-help">
                                            Alert threshold for low stock.
                                        </div>

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
                                            value="{{ old('unit_price', '0.00') }}"
                                            min="0"
                                            step="0.01"
                                            placeholder="0.00"
                                        >

                                        <div class="field-help">
                                            Cost per individual unit.
                                        </div>

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
                                                @selected(old('status', 'active') === 'active')
                                            >
                                                Active
                                            </option>

                                            <option
                                                value="inactive"
                                                @selected(old('status') === 'inactive')
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


                        {{-- =================================
                             WARRANTY
                        ================================== --}}
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
                                value="{{ old('warranty_start') }}"
                            >

                            @error('warranty_start')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


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
                                value="{{ old('warranty_end') }}"
                            >

                            @error('warranty_end')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =============================================
                     ACTIONS
                ============================================== --}}
                <div class="form-actions">

                    <a
                        href="{{ route('admin.inventory.repairs.spare-parts.index') }}"
                        class="btn-cpc btn-secondary-cpc"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn-cpc btn-primary-cpc"
                    >
                        <i class="bi bi-check-lg"></i>
                        Save Spare Part
                    </button>

                </div>

            </form>

        </div>


        {{-- =================================================
             INFORMATION SIDEBAR
        ================================================== --}}
        <div class="info-card">

            {{-- Information --}}
            <div class="info-section">

                <div class="info-title">
                    <i class="bi bi-info-circle"></i>
                    Spare Part Management
                </div>

                <ul class="info-list">

                    <li>
                        <i class="bi bi-check-circle"></i>
                        <span>
                            Each spare part receives a unique part code.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle"></i>
                        <span>
                            Initial stock is automatically recorded as a
                            stock-in movement.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle"></i>
                        <span>
                            Stock is automatically deducted when a part
                            is used in a repair.
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-check-circle"></i>
                        <span>
                            The system prevents repair usage when
                            available stock is insufficient.
                        </span>
                    </li>

                </ul>

            </div>


            {{-- Stock --}}
            <div class="info-section">

                <div class="info-title">
                    <i class="bi bi-boxes"></i>
                    Stock Monitoring
                </div>

                <div class="status-preview">

                    <span class="status-name">
                        Normal Stock
                    </span>

                    <span class="status-badge badge-active">
                        Available
                    </span>

                </div>

                <div class="status-preview">

                    <span class="status-name">
                        Low Stock
                    </span>

                    <span class="status-badge"
                          style="background:#fff3df;color:#b76e00;">
                        Warning
                    </span>

                </div>

                <div class="status-preview">

                    <span class="status-name">
                        Zero Stock
                    </span>

                    <span class="status-badge"
                          style="background:#ffe8e8;color:#dc3545;">
                        Out
                    </span>

                </div>

            </div>


            {{-- Warranty --}}
            <div class="info-section">

                <div class="info-title">
                    <i class="bi bi-shield-check"></i>
                    Warranty
                </div>

                <div class="warning-box">

                    Warranty dates are optional. When entered,
                    the system automatically identifies active,
                    expiring and expired warranties.

                </div>

            </div>

        </div>

    </div>

</div>

@endsection