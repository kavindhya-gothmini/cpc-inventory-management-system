@extends('layouts.admin')

@section('title', 'Add Supplier | CPC Inventory Management System')

@section('content')

<style>
    .supplier-page {
        padding-bottom: 40px;
    }

    .page-header {
        background: linear-gradient(135deg, #0d6efd, #084298);
        border-radius: 18px;
        padding: 28px 30px;
        color: #fff;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.15);
    }

    .page-header h2 {
        font-weight: 700;
        margin-bottom: 6px;
    }

    .page-header p {
        margin: 0;
        opacity: .9;
    }

    .breadcrumb-custom {
        margin-top: 15px;
        font-size: 14px;
    }

    .breadcrumb-custom a {
        color: rgba(255,255,255,.85);
        text-decoration: none;
    }

    .breadcrumb-custom span {
        margin: 0 8px;
        opacity: .7;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(0,0,0,.05);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e9ecef;
        background: #f8fafc;
    }

    .form-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #1f2937;
    }

    .form-card-header p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .form-card-body {
        padding: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        padding: 11px 13px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .2rem rgba(13,110,253,.12);
    }

    .required {
        color: #dc3545;
    }

    .document-upload {
        border: 2px dashed #b8c7d9;
        border-radius: 15px;
        padding: 28px;
        text-align: center;
        background: #f8fbff;
        transition: .2s ease;
    }

    .document-upload:hover {
        border-color: #0d6efd;
        background: #f1f7ff;
    }

    .document-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 12px;
        border-radius: 14px;
        background: #e7f1ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .document-upload h6 {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .document-upload p {
        color: #6b7280;
        font-size: 13px;
        margin-bottom: 15px;
    }

    .file-input-wrapper {
        max-width: 500px;
        margin: 0 auto;
        text-align: left;
    }

    .file-input-wrapper .form-control {
        background: #fff;
    }

    .file-help {
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
    }

    .action-bar {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-custom {
        border-radius: 10px;
        padding: 11px 20px;
        font-weight: 600;
    }

    .status-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 15px 18px;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 22px;
        }

        .form-card-body {
            padding: 18px;
        }

        .action-bar {
            flex-direction: column;
        }

        .action-bar .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid supplier-page">


{{-- PAGE HEADER --}}
<div class="page-header">
    <h2>
        <i class="bi bi-building-add me-2"></i>
        Add Supplier
    </h2>

    <p>
        Register a new supplier and attach official supplier documentation.
    </p>

    <div class="breadcrumb-custom">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>

        <span>/</span>

        <a href="{{ route('admin.items.index') }}">
            Inventory
        </a>

        <span>/</span>

        <a href="{{ route('admin.inventory.repairs.index') }}">
            Repair Management
        </a>

        <span>/</span>

        <a href="{{ route('admin.inventory.repairs.suppliers.index') }}">
            Suppliers
        </a>

        <span>/</span>

        <strong>Add Supplier</strong>
    </div>
</div>


{{-- VALIDATION ERRORS --}}
@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3">
        <div class="fw-bold mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Please correct the following errors:
        </div>

        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- MAIN FORM --}}
<form
    method="POST"
    action="{{ route('admin.inventory.repairs.suppliers.store') }}"
    enctype="multipart/form-data"
>

    @csrf


    {{-- SUPPLIER INFORMATION --}}
    <div class="form-card">

        <div class="form-card-header">
            <h5>
                <i class="bi bi-building me-2 text-primary"></i>
                Supplier Information
            </h5>

            <p>
                Enter the basic identification and classification details of the supplier.
            </p>
        </div>

        <div class="form-card-body">

            <div class="row g-4">

                {{-- SUPPLIER CODE --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Supplier Code
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="supplier_code"
                        class="form-control"
                        value="{{ old('supplier_code') }}"
                        placeholder="Example: SUP-001"
                        required
                    >

                    <div class="form-text">
                        Enter a unique supplier identification code.
                    </div>
                </div>


                {{-- SUPPLIER NAME --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Supplier Name
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="supplier_name"
                        class="form-control"
                        value="{{ old('supplier_name') }}"
                        placeholder="Enter supplier/company name"
                        required
                    >
                </div>


                {{-- CONTACT PERSON --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Contact Person
                    </label>

                    <input
                        type="text"
                        name="contact_person"
                        class="form-control"
                        value="{{ old('contact_person') }}"
                        placeholder="Enter contact person's name"
                    >
                </div>


                {{-- SUPPLIER TYPE --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Supplier Type
                    </label>

                    <select
                        name="supplier_type"
                        class="form-select"
                    >
                        <option value="">
                            Select Supplier Type
                        </option>

                        <option value="Distributor"
                            {{ old('supplier_type') == 'Distributor' ? 'selected' : '' }}>
                            Distributor
                        </option>

                        <option value="Manufacturer"
                            {{ old('supplier_type') == 'Manufacturer' ? 'selected' : '' }}>
                            Manufacturer
                        </option>

                        <option value="Service Provider"
                            {{ old('supplier_type') == 'Service Provider' ? 'selected' : '' }}>
                            Service Provider
                        </option>

                        <option value="Dealer"
                            {{ old('supplier_type') == 'Dealer' ? 'selected' : '' }}>
                            Dealer
                        </option>

                        <option value="Other"
                            {{ old('supplier_type') == 'Other' ? 'selected' : '' }}>
                            Other
                        </option>
                    </select>
                </div>

            </div>

        </div>
    </div>


    {{-- CONTACT INFORMATION --}}
    <div class="form-card">

        <div class="form-card-header">
            <h5>
                <i class="bi bi-telephone me-2 text-primary"></i>
                Contact Information
            </h5>

            <p>
                Enter the supplier's telephone and email contact details.
            </p>
        </div>

        <div class="form-card-body">

            <div class="row g-4">

                {{-- PHONE --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Primary Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone') }}"
                        placeholder="Example: 0112345678"
                    >
                </div>


                {{-- ALTERNATIVE PHONE --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Alternative Phone
                    </label>

                    <input
                        type="text"
                        name="alternative_phone"
                        class="form-control"
                        value="{{ old('alternative_phone') }}"
                        placeholder="Example: 0771234567"
                    >
                </div>


                {{-- EMAIL --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="supplier@example.com"
                    >
                </div>


                {{-- CITY --}}
                <div class="col-md-6">
                    <label class="form-label">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        class="form-control"
                        value="{{ old('city') }}"
                        placeholder="Example: Colombo"
                    >
                </div>


                {{-- ADDRESS --}}
                <div class="col-12">
                    <label class="form-label">
                        Address
                    </label>

                    <textarea
                        name="address"
                        class="form-control"
                        rows="3"
                        placeholder="Enter complete supplier address"
                    >{{ old('address') }}</textarea>
                </div>

            </div>

        </div>
    </div>


    {{-- OFFICIAL DOCUMENT ATTACHMENT --}}
    <div class="form-card">

        <div class="form-card-header">
            <h5>
                <i class="bi bi-paperclip me-2 text-primary"></i>
                Official Supplier Documents
            </h5>

            <p>
                Attach official documents related to this supplier.
            </p>
        </div>

        <div class="form-card-body">

            <div class="document-upload">

                <div class="document-icon">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                </div>

                <h6>
                    Attach Official Document
                </h6>

                <p>
                    Select a document directly from your computer or device.
                </p>

                <div class="file-input-wrapper">

                    <label class="form-label">
                        Select Document
                    </label>

                    <input
                        type="file"
                        name="document"
                        id="supplierDocument"
                        class="form-control"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                    >

                    <div class="file-help">
                        <i class="bi bi-info-circle me-1"></i>

                        Supported formats:
                        PDF, DOC, DOCX, JPG, JPEG, PNG.
                        Maximum size: 10 MB.
                    </div>

                </div>

            </div>


            {{-- DOCUMENT DETAILS --}}
            <div class="row g-4 mt-3">

                <div class="col-md-6">

                    <label class="form-label">
                        Document Name
                    </label>

                    <input
                        type="text"
                        name="document_name"
                        class="form-control"
                        value="{{ old('document_name') }}"
                        placeholder="Example: Business Registration Certificate"
                    >

                    <div class="form-text">
                        Give the attached document a meaningful name.
                    </div>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Document Type
                    </label>

                    <select
                        name="document_type"
                        class="form-select"
                    >
                        <option value="">
                            Select Document Type
                        </option>

                        <option value="Business Registration"
                            {{ old('document_type') == 'Business Registration' ? 'selected' : '' }}>
                            Business Registration
                        </option>

                        <option value="Quotation"
                            {{ old('document_type') == 'Quotation' ? 'selected' : '' }}>
                            Quotation
                        </option>

                        <option value="Agreement"
                            {{ old('document_type') == 'Agreement' ? 'selected' : '' }}>
                            Agreement
                        </option>

                        <option value="Tax Document"
                            {{ old('document_type') == 'Tax Document' ? 'selected' : '' }}>
                            Tax Document
                        </option>

                        <option value="Certificate"
                            {{ old('document_type') == 'Certificate' ? 'selected' : '' }}>
                            Certificate
                        </option>

                        <option value="Other"
                            {{ old('document_type') == 'Other' ? 'selected' : '' }}>
                            Other
                        </option>
                    </select>

                </div>


                <div class="col-12">

                    <label class="form-label">
                        Document Description
                    </label>

                    <textarea
                        name="document_description"
                        class="form-control"
                        rows="3"
                        placeholder="Enter any additional information about this document"
                    >{{ old('document_description') }}</textarea>

                </div>

            </div>

        </div>
    </div>


    {{-- NOTES & STATUS --}}
    <div class="form-card">

        <div class="form-card-header">
            <h5>
                <i class="bi bi-journal-text me-2 text-primary"></i>
                Additional Information
            </h5>

            <p>
                Add notes and set the initial supplier status.
            </p>
        </div>

        <div class="form-card-body">

            <div class="row g-4">

                {{-- NOTES --}}
                <div class="col-md-8">

                    <label class="form-label">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        class="form-control"
                        rows="4"
                        placeholder="Enter additional supplier notes..."
                    >{{ old('notes') }}</textarea>

                </div>


                {{-- STATUS --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Supplier Status
                    </label>

                    <div class="status-box">

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_active"
                                value="1"
                                id="isActive"
                                {{ old('is_active', true) ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="isActive"
                            >
                                Active Supplier
                            </label>

                        </div>

                        <div class="small text-muted mt-2">
                            Active suppliers can be selected for future repair and spare-part operations.
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>


    {{-- ACTIONS --}}
    <div class="action-bar">

        <a
            href="{{ route('admin.inventory.repairs.suppliers.index') }}"
            class="btn btn-light border btn-custom"
        >
            <i class="bi bi-x-circle me-1"></i>
            Cancel
        </a>

        <button
            type="submit"
            class="btn btn-primary btn-custom"
        >
            <i class="bi bi-check-circle me-1"></i>
            Save Supplier
        </button>

    </div>

</form>


</div>

@endsection
