@extends('layouts.admin')

@section('title', 'Edit Supplier | CPC Inventory Management System')
@section('page-heading', 'Edit Supplier')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Edit Supplier
            </h4>

            <p class="text-muted mb-0">
                Update supplier information and manage official supplier documents.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <a
                href="{{ route('admin.inventory.repairs.suppliers.show', $supplier->id) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-eye me-1"></i>
                View Supplier
            </a>

            <a
                href="{{ route('admin.inventory.repairs.suppliers.index') }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Suppliers
            </a>

        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         MAIN SUPPLIER FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'admin.inventory.repairs.suppliers.update',
            $supplier->id
        ) }}"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        <div class="row g-4">


            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}

            <div class="col-lg-8">


                {{-- =================================================
                     SUPPLIER INFORMATION
                ================================================== --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex align-items-center">

                            <div class="section-icon me-3">
                                <i class="bi bi-building"></i>
                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Supplier Information
                                </h6>

                                <small class="text-muted">
                                    Basic supplier identification details
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body p-4">

                        <div class="row g-3">


                            {{-- Supplier Code --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Supplier Code
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="supplier_code"
                                    class="form-control @error('supplier_code') is-invalid @enderror"
                                    value="{{ old(
                                        'supplier_code',
                                        $supplier->supplier_code
                                    ) }}"
                                    maxlength="50"
                                    required
                                >

                                @error('supplier_code')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Supplier Name --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Supplier Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="supplier_name"
                                    class="form-control @error('supplier_name') is-invalid @enderror"
                                    value="{{ old(
                                        'supplier_name',
                                        $supplier->supplier_name
                                    ) }}"
                                    maxlength="150"
                                    required
                                >

                                @error('supplier_name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Supplier Type --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Supplier Type
                                </label>

                                @php

                                    $types = [
                                        'Manufacturer',
                                        'Distributor',
                                        'Dealer',
                                        'Retailer',
                                        'Service Provider',
                                        'Other',
                                    ];

                                    $currentSupplierType =
                                        old(
                                            'supplier_type',
                                            $supplier->supplier_type
                                        );

                                @endphp

                                <select
                                    name="supplier_type"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select Supplier Type
                                    </option>

                                    @foreach($types as $type)

                                        <option
                                            value="{{ $type }}"
                                            {{ $currentSupplierType === $type ? 'selected' : '' }}
                                        >
                                            {{ $type }}
                                        </option>

                                    @endforeach

                                    @if(
                                        $supplier->supplier_type &&
                                        !in_array(
                                            $supplier->supplier_type,
                                            $types
                                        )
                                    )

                                        <option
                                            value="{{ $supplier->supplier_type }}"
                                            selected
                                        >
                                            {{ $supplier->supplier_type }}
                                        </option>

                                    @endif

                                </select>

                            </div>


                            {{-- Contact Person --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Contact Person
                                </label>

                                <input
                                    type="text"
                                    name="contact_person"
                                    class="form-control"
                                    value="{{ old(
                                        'contact_person',
                                        $supplier->contact_person
                                    ) }}"
                                    maxlength="150"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     CONTACT INFORMATION
                ================================================== --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex align-items-center">

                            <div class="section-icon me-3">
                                <i class="bi bi-telephone"></i>
                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Contact Information
                                </h6>

                                <small class="text-muted">
                                    Supplier telephone and email details
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body p-4">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Primary Phone
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    value="{{ old(
                                        'phone',
                                        $supplier->phone
                                    ) }}"
                                    maxlength="30"
                                >

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Alternative Phone
                                </label>

                                <input
                                    type="text"
                                    name="alternative_phone"
                                    class="form-control"
                                    value="{{ old(
                                        'alternative_phone',
                                        $supplier->alternative_phone
                                    ) }}"
                                    maxlength="30"
                                >

                            </div>


                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old(
                                        'email',
                                        $supplier->email
                                    ) }}"
                                    maxlength="150"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ADDRESS
                ================================================== --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex align-items-center">

                            <div class="section-icon me-3">
                                <i class="bi bi-geo-alt"></i>
                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Address Information
                                </h6>

                                <small class="text-muted">
                                    Supplier location and address
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body p-4">

                        <div class="row g-3">

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Address
                                </label>

                                <textarea
                                    name="address"
                                    rows="3"
                                    class="form-control"
                                >{{ old(
                                    'address',
                                    $supplier->address
                                ) }}</textarea>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    City
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    class="form-control"
                                    value="{{ old(
                                        'city',
                                        $supplier->city
                                    ) }}"
                                    maxlength="100"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     OFFICIAL SUPPLIER DOCUMENTS
                ================================================== --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="d-flex align-items-center">

                                <div class="section-icon me-3">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Official Supplier Documents
                                    </h6>

                                    <small class="text-muted">
                                        Manage supplier documents
                                    </small>

                                </div>

                            </div>


                            <span class="badge bg-primary">

                                {{ $supplier->documents->count() }}

                                {{
                                    $supplier->documents->count() == 1
                                    ? 'Document'
                                    : 'Documents'
                                }}

                            </span>

                        </div>

                    </div>


                    <div class="card-body p-4">


                        {{-- =================================================
                             EXISTING DOCUMENTS
                        ================================================== --}}

                        @if($supplier->documents->count() > 0)

                            <div class="mb-4">

                                <h6 class="fw-bold mb-3">
                                    Existing Documents
                                </h6>


                                <div class="document-list">

                                    @foreach($supplier->documents as $document)

                                        @php

                                            $extension = strtolower(
                                                pathinfo(
                                                    $document->original_filename
                                                    ?? $document->file_path,
                                                    PATHINFO_EXTENSION
                                                )
                                            );

                                            $icon = match($extension) {

                                                'pdf'
                                                    => 'bi-file-earmark-pdf-fill',

                                                'doc',
                                                'docx'
                                                    => 'bi-file-earmark-word-fill',

                                                'jpg',
                                                'jpeg',
                                                'png'
                                                    => 'bi-file-earmark-image-fill',

                                                default
                                                    => 'bi-file-earmark-fill',
                                            };

                                            $documentUrl = asset(
                                                'storage/' .
                                                $document->file_path
                                            );

                                        @endphp


                                        <div class="document-item">


                                            {{-- DOCUMENT ICON --}}

                                            <div class="document-icon">

                                                <i class="bi {{ $icon }}"></i>

                                            </div>


                                            {{-- DOCUMENT DETAILS --}}

                                            <div class="document-details">

                                                <div class="document-name">

                                                    {{ $document->document_name }}

                                                </div>


                                                <div class="document-meta">

                                                    @if($document->document_type)

                                                        <span>
                                                            {{ $document->document_type }}
                                                        </span>

                                                    @endif


                                                    @if($document->original_filename)

                                                        <span>
                                                            {{ $document->original_filename }}
                                                        </span>

                                                    @endif


                                                    @if($document->file_size)

                                                        <span>
                                                            {{ number_format(
                                                                $document->file_size / 1024,
                                                                1
                                                            ) }}
                                                            KB
                                                        </span>

                                                    @endif

                                                </div>

                                            </div>


                                            {{-- =================================================
                                                 DOCUMENT ACTIONS
                                            ================================================== --}}

                                            <div class="document-actions">


                                                {{-- VIEW --}}

                                                <a
                                                    href="{{ $documentUrl }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="document-btn document-view"
                                                    title="View Document"
                                                >

                                                    <i class="bi bi-eye"></i>

                                                    <span>
                                                        View
                                                    </span>

                                                </a>


                                                {{-- DOWNLOAD --}}

                                                <a
                                                    href="{{ $documentUrl }}"
                                                    download
                                                    class="document-btn document-download"
                                                    title="Download Document"
                                                >

                                                    <i class="bi bi-download"></i>

                                                    <span>
                                                        Download
                                                    </span>

                                                </a>


                                                {{-- EDIT DOCUMENT --}}

                                                <button
                                                    type="button"
                                                    class="document-btn document-edit"
                                                    onclick="openDocumentEdit(
                                                        {{ $document->id }},
                                                        @js($document->document_name),
                                                        @js($document->document_type),
                                                        @js($document->description)
                                                    )"
                                                    title="Edit Document Information"
                                                >

                                                    <i class="bi bi-pencil"></i>

                                                    <span>
                                                        Edit
                                                    </span>

                                                </button>


                                                {{-- =================================================
                                                     DELETE DOCUMENT
                                                     IMPORTANT:
                                                     THIS FORM IS NOT THE SUPPLIER DELETE FORM.
                                                ================================================== --}}

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.inventory.repairs.suppliers.documents.destroy',
                                                        [
                                                            'supplier' => $supplier->id,
                                                            'document' => $document->id,
                                                        ]
                                                    ) }}"
                                                    onsubmit="return confirm(
                                                        'Are you sure you want to delete this document only? The supplier will NOT be deleted.'
                                                    );"
                                                    style="display:inline;"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="document-btn document-delete"
                                                        title="Delete Document Only"
                                                    >

                                                        <i class="bi bi-trash"></i>

                                                        <span>
                                                            Delete
                                                        </span>

                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @else

                            <div class="empty-documents mb-4">

                                <div class="empty-document-icon">

                                    <i class="bi bi-file-earmark-x"></i>

                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        No official documents attached
                                    </div>

                                    <div class="small text-muted">
                                        Upload the first official document below.
                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- =================================================
                             UPLOAD NEW DOCUMENT
                        ================================================== --}}

                        <div class="upload-box">

                            <div class="d-flex align-items-center mb-4">

                                <div class="upload-icon me-3">

                                    <i class="bi bi-cloud-arrow-up"></i>

                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Upload New Document
                                    </h6>

                                    <small class="text-muted">
                                        Add another official document to this supplier.
                                    </small>

                                </div>

                            </div>


                            <div class="row g-3">


                                {{-- FILE --}}

                                <div class="col-12">

                                    <label class="form-label fw-semibold">
                                        Select Document
                                    </label>

                                    <input
                                        type="file"
                                        name="document"
                                        id="supplierDocument"
                                        class="form-control @error('document') is-invalid @enderror"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                    >

                                    @error('document')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    <div class="form-text">

                                        PDF, DOC, DOCX, JPG, JPEG or PNG.
                                        Maximum 10 MB.

                                    </div>

                                </div>


                                {{-- DOCUMENT NAME --}}

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Document Name
                                    </label>

                                    <input
                                        type="text"
                                        name="document_name"
                                        class="form-control"
                                        value="{{ old('document_name') }}"
                                        maxlength="150"
                                        placeholder="Example: Business Registration Certificate"
                                    >

                                </div>


                                {{-- DOCUMENT TYPE --}}

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Document Type
                                    </label>

                                    <select
                                        name="document_type"
                                        class="form-select"
                                    >

                                        <option value="">
                                            Select Document Type
                                        </option>

                                        <option
                                            value="Business Registration"
                                            {{ old('document_type') === 'Business Registration' ? 'selected' : '' }}
                                        >
                                            Business Registration
                                        </option>

                                        <option
                                            value="Quotation"
                                            {{ old('document_type') === 'Quotation' ? 'selected' : '' }}
                                        >
                                            Quotation
                                        </option>

                                        <option
                                            value="Agreement"
                                            {{ old('document_type') === 'Agreement' ? 'selected' : '' }}
                                        >
                                            Agreement
                                        </option>

                                        <option
                                            value="Tax Document"
                                            {{ old('document_type') === 'Tax Document' ? 'selected' : '' }}
                                        >
                                            Tax Document
                                        </option>

                                        <option
                                            value="Certificate"
                                            {{ old('document_type') === 'Certificate' ? 'selected' : '' }}
                                        >
                                            Certificate
                                        </option>

                                        <option
                                            value="Other"
                                            {{ old('document_type') === 'Other' ? 'selected' : '' }}
                                        >
                                            Other
                                        </option>

                                    </select>

                                </div>


                                {{-- DESCRIPTION --}}

                                <div class="col-12">

                                    <label class="form-label fw-semibold">
                                        Document Description
                                    </label>

                                    <textarea
                                        name="document_description"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter any additional information about this document..."
                                    >{{ old('document_description') }}</textarea>

                                </div>

                            </div>


                            <div class="mt-4 pt-3 border-top">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="bi bi-cloud-upload me-1"></i>

                                    Upload Document & Save Supplier

                                </button>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     NOTES
                ================================================== --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex align-items-center">

                            <div class="section-icon me-3">
                                <i class="bi bi-journal-text"></i>
                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Additional Notes
                                </h6>

                                <small class="text-muted">
                                    Additional supplier information
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body p-4">

                        <textarea
                            name="notes"
                            rows="4"
                            class="form-control"
                            placeholder="Enter additional information..."
                        >{{ old(
                            'notes',
                            $supplier->notes
                        ) }}</textarea>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RIGHT COLUMN
            ================================================== --}}

            <div class="col-lg-4">


                {{-- =================================================
                     STATUS
                ================================================== --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h6 class="fw-bold mb-0">
                            Supplier Status
                        </h6>

                    </div>


                    <div class="card-body p-4">

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old(
                                    'is_active',
                                    $supplier->is_active
                                ) ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="is_active"
                            >
                                Active Supplier
                            </label>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DOCUMENT SUMMARY
                ================================================== --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="fw-bold mb-0">
                            Document Summary
                        </h6>

                    </div>


                    <div class="card-body p-4">

                        <div class="document-summary">

                            <div class="summary-number">

                                {{ $supplier->documents->count() }}

                            </div>

                            <div>

                                <div class="fw-semibold">
                                    Official Documents
                                </div>

                                <div class="small text-muted">
                                    Attached to supplier
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     SUPPLIER RECORD
                ================================================== --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="fw-bold mb-0">
                            Supplier Record
                        </h6>

                    </div>


                    <div class="card-body p-4">

                        <div class="record-row">

                            <span>
                                Supplier ID
                            </span>

                            <strong>
                                #{{ $supplier->id }}
                            </strong>

                        </div>


                        <div class="record-row">

                            <span>
                                Created
                            </span>

                            <strong>
                                {{ optional(
                                    $supplier->created_at
                                )->format('d M Y') }}
                            </strong>

                        </div>


                        <div class="record-row">

                            <span>
                                Updated
                            </span>

                            <strong>
                                {{ optional(
                                    $supplier->updated_at
                                )->format('d M Y') }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     SAVE
                ================================================== --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <button
                            type="submit"
                            class="btn btn-primary w-100 mb-2"
                        >

                            <i class="bi bi-check-circle me-1"></i>

                            Save Supplier Changes

                        </button>


                        <a
                            href="{{ route(
                                'admin.inventory.repairs.suppliers.show',
                                $supplier->id
                            ) }}"
                            class="btn btn-outline-secondary w-100"
                        >
                            Cancel
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =============================================================
     DOCUMENT EDIT MODAL
============================================================= --}}

<div
    class="modal fade"
    id="documentEditModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">


            <div class="modal-header">

                <h5 class="modal-title fw-bold">

                    <i class="bi bi-pencil-square me-2 text-primary"></i>

                    Edit Document

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            {{-- =================================================
                 DOCUMENT UPDATE FORM
            ================================================== --}}

            <form
                method="POST"
                id="documentEditForm"
            >

                @csrf

                @method('PUT')


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Document Name
                        </label>

                        <input
                            type="text"
                            name="document_name"
                            id="editDocumentName"
                            class="form-control"
                            maxlength="150"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Document Type
                        </label>

                        <select
                            name="document_type"
                            id="editDocumentType"
                            class="form-select"
                        >

                            <option value="">
                                Select Document Type
                            </option>

                            <option value="Business Registration">
                                Business Registration
                            </option>

                            <option value="Quotation">
                                Quotation
                            </option>

                            <option value="Agreement">
                                Agreement
                            </option>

                            <option value="Tax Document">
                                Tax Document
                            </option>

                            <option value="Certificate">
                                Certificate
                            </option>

                            <option value="Other">
                                Other
                            </option>

                        </select>

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="editDocumentDescription"
                            class="form-control"
                            rows="4"
                        ></textarea>

                    </div>


                    <div class="alert alert-info small mb-0">

                        <i class="bi bi-info-circle me-1"></i>

                        This updates the document information only.
                        The uploaded file remains unchanged.

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-check-circle me-1"></i>

                        Save Document

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<style>

/* =============================================================
   GENERAL
============================================================= */

.card {
    border-radius: 12px;
}

.card-header {
    border-bottom: 1px solid #edf0f3;
    border-radius: 12px 12px 0 0 !important;
}

.form-label {
    color: #374151;
    font-size: 13px;
}

.form-control,
.form-select {
    min-height: 44px;
    border-color: #dfe3e8;
    border-radius: 8px;
}

textarea.form-control {
    min-height: auto;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .10);
}


/* =============================================================
   SECTION ICON
============================================================= */

.section-icon {
    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #eaf2ff;
    color: #0d6efd;

    font-size: 19px;
}


/* =============================================================
   DOCUMENT LIST
============================================================= */

.document-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}


.document-item {
    display: flex;
    align-items: center;
    gap: 14px;

    padding: 15px;

    border: 1px solid #e3e7ec;
    border-radius: 10px;

    background: #fafbfc;

    transition: .2s ease;
}

.document-item:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0,0,0,.05);
}


/* =============================================================
   DOCUMENT ICON
============================================================= */

.document-icon {
    width: 46px;
    height: 46px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #eaf2ff;
    color: #0d6efd;

    font-size: 21px;
}


/* =============================================================
   DOCUMENT DETAILS
============================================================= */

.document-details {
    flex: 1;
    min-width: 0;
}

.document-name {
    font-weight: 600;
    color: #293241;
    font-size: 14px;

    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.document-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;

    margin-top: 5px;

    font-size: 11px;
    color: #7b8494;
}

.document-meta span {
    padding-right: 8px;
    border-right: 1px solid #dfe3e8;
}

.document-meta span:last-child {
    border-right: 0;
}


/* =============================================================
   DOCUMENT ACTIONS
============================================================= */

.document-actions {
    display: flex;
    align-items: center;
    gap: 6px;

    flex-shrink: 0;
}

.document-actions form {
    margin: 0;
}


.document-btn {
    min-height: 34px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 5px;

    padding: 6px 9px;

    border: 1px solid #dfe3e8;
    border-radius: 7px;

    background: #ffffff;

    font-size: 12px;
    font-weight: 500;

    text-decoration: none;

    cursor: pointer;

    transition: .2s ease;
}


.document-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0,0,0,.08);
}


.document-view {
    color: #0d6efd;
}

.document-view:hover {
    background: #eef4ff;
}


.document-download {
    color: #198754;
}

.document-download:hover {
    background: #edf8f2;
}


.document-edit {
    color: #6f42c1;
}

.document-edit:hover {
    background: #f4efff;
}


.document-delete {
    color: #dc3545;
}

.document-delete:hover {
    background: #fff1f2;
}


/* =============================================================
   UPLOAD BOX
============================================================= */

.upload-box {
    padding: 22px;

    border: 1px dashed #a9bfdc;
    border-radius: 12px;

    background: #f8fbff;
}


.upload-icon {
    width: 46px;
    height: 46px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #eaf2ff;
    color: #0d6efd;

    font-size: 21px;
}


/* =============================================================
   EMPTY DOCUMENT
============================================================= */

.empty-documents {
    display: flex;
    align-items: center;
    gap: 14px;

    padding: 18px;

    border: 1px solid #e5e7eb;
    border-radius: 10px;

    background: #fafbfc;
}


.empty-document-icon {
    width: 44px;
    height: 44px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #f1f3f5;
    color: #7b8494;

    font-size: 20px;
}


/* =============================================================
   DOCUMENT SUMMARY
============================================================= */

.document-summary {
    display: flex;
    align-items: center;
    gap: 14px;
}


.summary-number {
    width: 50px;
    height: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #eaf2ff;
    color: #0d6efd;

    font-size: 20px;
    font-weight: 700;
}


/* =============================================================
   RECORD
============================================================= */

.record-row {
    display: flex;
    justify-content: space-between;

    padding: 11px 0;

    border-bottom: 1px solid #edf0f3;

    font-size: 13px;
}

.record-row:last-child {
    border-bottom: 0;
}

.record-row span {
    color: #7b8494;
}

.record-row strong {
    color: #293241;
}


/* =============================================================
   RESPONSIVE
============================================================= */

@media(max-width: 991.98px) {

    .document-item {
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .document-details {
        flex: 1;
    }

    .document-actions {
        width: 100%;
        padding-left: 60px;
    }

}


@media(max-width: 575.98px) {

    .document-actions {
        padding-left: 0;
        flex-wrap: wrap;
    }

    .document-btn span {
        display: inline;
    }

    .document-name {
        white-space: normal;
    }

}

</style>


<script>

/*
|--------------------------------------------------------------------------
| Open Document Edit Modal
|--------------------------------------------------------------------------
*/

function openDocumentEdit(
    id,
    name,
    type,
    description
) {

    /*
    |--------------------------------------------------------------------------
    | Fill modal fields
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'editDocumentName'
    ).value = name || '';

    document.getElementById(
        'editDocumentType'
    ).value = type || '';

    document.getElementById(
        'editDocumentDescription'
    ).value = description || '';


    /*
    |--------------------------------------------------------------------------
    | Build the correct update URL
    |--------------------------------------------------------------------------
    |
    | This URL updates ONLY the selected supplier document.
    |
    */

    const supplierId =
        {{ $supplier->id }};

    const updateUrl =
        "{{ url('admin/inventory/repairs/suppliers') }}"
        + "/"
        + supplierId
        + "/documents/"
        + id;


    document.getElementById(
        'documentEditForm'
    ).action = updateUrl;


    /*
    |--------------------------------------------------------------------------
    | Open Bootstrap Modal
    |--------------------------------------------------------------------------
    */

    const modalElement =
        document.getElementById(
            'documentEditModal'
        );

    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );

    modal.show();
}

</script>

@endsection