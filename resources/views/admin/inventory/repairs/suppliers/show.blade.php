@extends('layouts.admin')

@section('title', 'Supplier Details | CPC Inventory Management System')

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
        box-shadow: 0 10px 30px rgba(13, 110, 253, .15);
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

    .breadcrumb-custom a:hover {
        color: #fff;
        text-decoration: underline;
    }

    .breadcrumb-custom span {
        margin: 0 8px;
        opacity: .7;
    }

    .summary-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 5px 18px rgba(0,0,0,.04);
    }

    .summary-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e7f1ff;
        color: #0d6efd;
        font-size: 21px;
        margin-bottom: 13px;
    }

    .summary-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .summary-value {
        font-size: 17px;
        font-weight: 700;
        color: #1f2937;
    }

    .content-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,.04);
        margin-bottom: 24px;
    }

    .content-card-header {
        padding: 20px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e9ecef;
    }

    .content-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #1f2937;
    }

    .content-card-header p {
        margin: 5px 0 0;
        font-size: 13px;
        color: #6b7280;
    }

    .content-card-body {
        padding: 24px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 13px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .info-row:last-child {
        border-bottom: 0;
    }

    .info-label {
        color: #6b7280;
        font-size: 14px;
    }

    .info-value {
        color: #1f2937;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-inactive {
        background: #f8d7da;
        color: #842029;
    }

    .document-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 17px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        margin-bottom: 12px;
        background: #fff;
        transition: .2s ease;
    }

    .document-item:last-child {
        margin-bottom: 0;
    }

    .document-item:hover {
        border-color: #b8d4ff;
        box-shadow: 0 5px 15px rgba(13,110,253,.08);
    }

    .document-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        border-radius: 12px;
        background: #e7f1ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
    }

    .document-info {
        flex: 1;
        min-width: 0;
    }

    .document-name {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
        word-break: break-word;
    }

    .document-meta {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.6;
        word-break: break-word;
    }

    .document-actions {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
    }

    .document-action {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #495057;
        transition: .2s ease;
    }

    .document-action:hover {
        background: #f8f9fa;
        color: #0d6efd;
        border-color: #b8d4ff;
    }

    .document-action.delete {
        cursor: pointer;
    }

    .document-action.delete:hover {
        color: #dc3545;
        border-color: #f1aeb5;
        background: #fff5f5;
    }

    .empty-documents {
        text-align: center;
        padding: 35px 20px;
        background: #f8fafc;
        border-radius: 14px;
        border: 1px dashed #cbd5e1;
    }

    .empty-documents i {
        font-size: 38px;
        color: #94a3b8;
    }

    .empty-documents h6 {
        margin-top: 12px;
        font-weight: 700;
        color: #475569;
    }

    .empty-documents p {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 0;
    }

    .notes-box {
        background: #f8fafc;
        border-left: 4px solid #0d6efd;
        border-radius: 10px;
        padding: 16px;
        color: #4b5563;
        white-space: pre-line;
    }

    .action-bar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-custom {
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .danger-card {
        border-color: #f1aeb5;
    }

    .danger-card .content-card-header {
        background: #fff5f5;
    }

    .document-delete-form {
        display: inline;
        margin: 0;
        padding: 0;
    }

    @media (max-width: 768px) {

        .page-header {
            padding: 22px;
        }

        .page-header h2 {
            font-size: 22px;
        }

        .content-card-body {
            padding: 18px;
        }

        .info-row {
            display: block;
        }

        .info-value {
            text-align: left;
            margin-top: 4px;
        }

        .document-item {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .document-actions {
            width: 100%;
            margin-left: 66px;
        }

        .action-bar {
            flex-direction: column;
        }

        .action-bar .btn,
        .action-bar form {
            width: 100%;
        }

        .action-bar form .btn {
            width: 100%;
        }
    }
</style>


<div class="container-fluid supplier-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="page-header">

        <h2>
            <i class="bi bi-building me-2"></i>
            Supplier Details
        </h2>

        <p>
            View supplier information and official documents.
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

            <strong>Details</strong>

        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-3">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm rounded-3">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Supplier Type --}}
        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon">
                    <i class="bi bi-tags"></i>
                </div>

                <div class="summary-label">
                    Supplier Type
                </div>

                <div class="summary-value">
                    {{ $supplier->supplier_type ?: 'Not specified' }}
                </div>

            </div>

        </div>


        {{-- City --}}
        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>

                <div class="summary-label">
                    City
                </div>

                <div class="summary-value">
                    {{ $supplier->city ?: 'Not specified' }}
                </div>

            </div>

        </div>


        {{-- Documents --}}
        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <div class="summary-label">
                    Documents
                </div>

                <div class="summary-value">
                    {{ $documentsCount ?? $supplier->documents->count() }}
                </div>

            </div>

        </div>


        {{-- Status --}}
        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div class="summary-label">
                    Current Status
                </div>

                <div class="summary-value">

                    @if($supplier->is_active)

                        <span class="status-badge status-active">
                            <i class="bi bi-check-circle-fill"></i>
                            Active
                        </span>

                    @else

                        <span class="status-badge status-inactive">
                            <i class="bi bi-x-circle-fill"></i>
                            Inactive
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="row g-4">

        {{-- =====================================================
             LEFT COLUMN
        ====================================================== --}}

        <div class="col-lg-7">


            {{-- =================================================
                 CONTACT INFORMATION
            ================================================== --}}

            <div class="content-card">

                <div class="content-card-header">

                    <h5>
                        <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                        Contact Information
                    </h5>

                    <p>
                        Supplier communication details.
                    </p>

                </div>

                <div class="content-card-body">

                    <div class="info-row">

                        <div class="info-label">
                            Contact Person
                        </div>

                        <div class="info-value">
                            {{ $supplier->contact_person ?: 'Not provided' }}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Primary Phone
                        </div>

                        <div class="info-value">
                            {{ $supplier->phone ?: 'Not provided' }}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Alternative Phone
                        </div>

                        <div class="info-value">
                            {{ $supplier->alternative_phone ?: 'Not provided' }}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Email
                        </div>

                        <div class="info-value">
                            {{ $supplier->email ?: 'Not provided' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SUPPLIER INFORMATION
            ================================================== --}}

            <div class="content-card">

                <div class="content-card-header">

                    <h5>
                        <i class="bi bi-building me-2 text-primary"></i>
                        Supplier Information
                    </h5>

                    <p>
                        Official supplier identification information.
                    </p>

                </div>

                <div class="content-card-body">

                    <div class="info-row">

                        <div class="info-label">
                            Supplier Code
                        </div>

                        <div class="info-value">
                            {{ $supplier->supplier_code }}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Supplier Name
                        </div>

                        <div class="info-value">
                            {{ $supplier->supplier_name }}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Supplier Type
                        </div>

                        <div class="info-value">
                            {{ $supplier->supplier_type ?: 'Not specified' }}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Registered Date
                        </div>

                        <div class="info-value">
                            {{ $supplier->created_at?->format('d M Y, h:i A') }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ADDRESS
            ================================================== --}}

            <div class="content-card">

                <div class="content-card-header">

                    <h5>
                        <i class="bi bi-geo-alt me-2 text-primary"></i>
                        Address
                    </h5>

                </div>

                <div class="content-card-body">

                    <div class="info-row">

                        <div class="info-label">
                            City
                        </div>

                        <div class="info-value">
                            {{ $supplier->city ?: 'Not provided' }}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Address
                        </div>

                        <div class="info-value">
                            {{ $supplier->address ?: 'Not provided' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 NOTES
            ================================================== --}}

            @if($supplier->notes)

                <div class="content-card">

                    <div class="content-card-header">

                        <h5>
                            <i class="bi bi-journal-text me-2 text-primary"></i>
                            Notes
                        </h5>

                    </div>

                    <div class="content-card-body">

                        <div class="notes-box">
                            {{ $supplier->notes }}
                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- =====================================================
             RIGHT COLUMN
        ====================================================== --}}

        <div class="col-lg-5">


            {{-- =================================================
                 OFFICIAL SUPPLIER DOCUMENTS
            ================================================== --}}

            <div class="content-card">

                <div class="content-card-header">

                    <h5>
                        <i class="bi bi-paperclip me-2 text-primary"></i>
                        Official Supplier Documents
                    </h5>

                    <p>
                        Documents attached to this supplier.
                    </p>

                </div>

                <div class="content-card-body">

                    @forelse($supplier->documents as $document)

                        <div class="document-item">

                            {{-- DOCUMENT ICON --}}
                            <div class="document-icon">

                                @php

                                    $extension = strtolower(
                                        pathinfo(
                                            $document->original_filename ?? '',
                                            PATHINFO_EXTENSION
                                        )
                                    );

                                @endphp


                                @if($extension === 'pdf')

                                    <i class="bi bi-file-earmark-pdf"></i>

                                @elseif(in_array(
                                    $extension,
                                    ['doc', 'docx']
                                ))

                                    <i class="bi bi-file-earmark-word"></i>

                                @elseif(in_array(
                                    $extension,
                                    ['jpg', 'jpeg', 'png']
                                ))

                                    <i class="bi bi-file-earmark-image"></i>

                                @else

                                    <i class="bi bi-file-earmark"></i>

                                @endif

                            </div>


                            {{-- DOCUMENT INFORMATION --}}
                            <div class="document-info">

                                <div class="document-name">

                                    {{ $document->document_name }}

                                </div>

                                <div class="document-meta">

                                    {{ $document->original_filename ?: 'No filename' }}

                                    @if($document->document_type)

                                        • {{ $document->document_type }}

                                    @endif

                                    @if($document->file_size)

                                        •
                                        {{ number_format(
                                            $document->file_size / 1024,
                                            1
                                        ) }}
                                        KB

                                    @endif

                                </div>

                            </div>


                            {{-- DOCUMENT ACTIONS --}}
                            <div class="document-actions">


                                {{-- VIEW --}}
                                <a
                                    href="{{ asset('storage/' . $document->file_path) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="document-action"
                                    title="View Document"
                                >

                                    <i class="bi bi-eye"></i>

                                </a>


                                {{-- DOWNLOAD --}}
                                <a
                                    href="{{ asset('storage/' . $document->file_path) }}"
                                    download="{{ $document->original_filename }}"
                                    class="document-action"
                                    title="Download Document"
                                >

                                    <i class="bi bi-download"></i>

                                </a>


                                {{-- =================================================
                                     DELETE ONLY THIS DOCUMENT
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
                                    class="document-delete-form"
                                    onsubmit="return confirm('Are you sure you want to delete this document only?');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="document-action delete"
                                        title="Delete Document"
                                    >

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="empty-documents">

                            <i class="bi bi-file-earmark-x"></i>

                            <h6>
                                No Documents Attached
                            </h6>

                            <p>
                                No official supplier documents have been uploaded yet.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                 SUPPLIER ACTIONS
            ================================================== --}}

            <div class="content-card">

                <div class="content-card-header">

                    <h5>
                        <i class="bi bi-gear me-2 text-primary"></i>
                        Supplier Actions
                    </h5>

                </div>

                <div class="content-card-body">

                    <div class="action-bar">


                        {{-- EDIT SUPPLIER --}}
                        <a
                            href="{{ route(
                                'admin.inventory.repairs.suppliers.edit',
                                $supplier
                            ) }}"
                            class="btn btn-primary btn-custom"
                        >

                            <i class="bi bi-pencil me-1"></i>

                            Edit Supplier

                        </a>


                        {{-- DEACTIVATE / ACTIVATE --}}
                        @if($supplier->is_active)

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.inventory.repairs.suppliers.deactivate',
                                    $supplier
                                ) }}"
                                style="display:inline;"
                            >

                                @csrf

                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="btn btn-warning btn-custom"
                                >

                                    <i class="bi bi-pause-circle me-1"></i>

                                    Deactivate

                                </button>

                            </form>

                        @else

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.inventory.repairs.suppliers.activate',
                                    $supplier
                                ) }}"
                                style="display:inline;"
                            >

                                @csrf

                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="btn btn-success btn-custom"
                                >

                                    <i class="bi bi-check-circle me-1"></i>

                                    Activate

                                </button>

                            </form>

                        @endif


                        {{-- BACK --}}
                        <a
                            href="{{ route(
                                'admin.inventory.repairs.suppliers.index'
                            ) }}"
                            class="btn btn-light border btn-custom"
                        >

                            <i class="bi bi-arrow-left me-1"></i>

                            Back

                        </a>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 DANGER ZONE
            ================================================== --}}

            <div class="content-card danger-card">

                <div class="content-card-header">

                    <h5 class="text-danger">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        Danger Zone

                    </h5>

                </div>

                <div class="content-card-body">

                    <p class="text-muted small">

                        Deleting this supplier will also remove its
                        attached supplier documents from the system.

                    </p>


                    {{-- DELETE SUPPLIER --}}
                    <form
                        method="POST"
                        action="{{ route(
                            'admin.inventory.repairs.suppliers.destroy',
                            $supplier
                        ) }}"
                        onsubmit="return confirm('Are you sure you want to permanently delete this supplier? This will also remove all attached supplier documents.');"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-outline-danger btn-custom"
                        >

                            <i class="bi bi-trash me-1"></i>

                            Delete Supplier

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection