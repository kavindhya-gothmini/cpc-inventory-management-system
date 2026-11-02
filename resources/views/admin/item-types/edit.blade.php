@extends('layouts.admin')

@section('title', 'Edit Item Type | CPC IT Asset Management')

@section('content')

<style>
    /* =========================================================
       ITEM TYPE EDIT PAGE
    ========================================================= */

    .item-type-page {
        width: 100%;
        padding: 24px;
    }

    /* Page Header */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-header-left {
        min-width: 0;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 8px;
        color: #6b7280;
        font-size: 13px;
    }

    .breadcrumb a {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .page-title {
        margin: 0;
        color: #172033;
        font-size: 28px;
        line-height: 1.25;
        font-weight: 700;
    }

    .page-subtitle {
        margin: 7px 0 0;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
    }

    /* Back Button */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        border: 1px solid #dce2ea;
        border-radius: 8px;
        background: #ffffff;
        color: #374151;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .back-btn:hover {
        border-color: #0d6efd;
        color: #0d6efd;
        background: #f8fbff;
    }

    /* Current Information */
    .current-info {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 15px 18px;
        margin-bottom: 20px;
        border: 1px solid #dbeafe;
        border-left: 4px solid #0d6efd;
        border-radius: 10px;
        background: linear-gradient(
            135deg,
            #f8fbff 0%,
            #eef6ff 100%
        );
    }

    .current-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #0d6efd;
        color: #ffffff;
        font-size: 18px;
        font-weight: 700;
    }

    .current-content {
        min-width: 0;
    }

    .current-label {
        display: block;
        margin-bottom: 3px;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .current-name {
        color: #172033;
        font-size: 15px;
        font-weight: 700;
        word-break: break-word;
    }

    .current-id {
        color: #6b7280;
        font-size: 13px;
        margin-left: 5px;
    }

    /* Error Alert */
    .alert-danger {
        display: flex;
        gap: 12px;
        padding: 15px 17px;
        margin-bottom: 20px;
        border: 1px solid #f5c2c7;
        border-radius: 10px;
        background: #fff5f5;
        color: #842029;
    }

    .alert-icon {
        flex: 0 0 auto;
        font-size: 18px;
        font-weight: 700;
    }

    .alert-content {
        min-width: 0;
    }

    .alert-title {
        margin: 0 0 7px;
        font-size: 14px;
        font-weight: 700;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
        font-size: 13px;
        line-height: 1.7;
    }

    /* Main Layout */
    .edit-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 20px;
        align-items: start;
    }

    /* Card */
    .form-card,
    .info-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, 0.05);
    }

    .card-header {
        padding: 19px 22px;
        border-bottom: 1px solid #edf0f3;
    }

    .card-title {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 700;
    }

    .card-description {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.5;
    }

    .card-body {
        padding: 24px 22px;
    }

    /* Form */
    .form-group {
        margin-bottom: 22px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-size: 13px;
        font-weight: 700;
    }

    .required {
        color: #dc3545;
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        min-height: 44px;
        box-sizing: border-box;
        padding: 10px 13px;
        border: 1px solid #d5dbe3;
        border-radius: 8px;
        background: #ffffff;
        color: #1f2937;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.5;
        transition: border-color 0.2s ease,
                    box-shadow 0.2s ease,
                    background 0.2s ease;
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    .form-control:hover {
        border-color: #b8c1cc;
    }

    .form-control:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.10);
    }

    textarea.form-control {
        min-height: 130px;
        resize: vertical;
    }

    .form-help {
        margin-top: 6px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    .field-error {
        margin-top: 6px;
        color: #dc3545;
        font-size: 12px;
        font-weight: 500;
    }

    .has-error {
        border-color: #dc3545 !important;
    }

    .has-error:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.10);
    }

    /* Status */
    .status-box {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px;
        border: 1px solid #e3e7ec;
        border-radius: 9px;
        background: #f8fafc;
    }

    .status-checkbox {
        position: relative;
        width: 19px;
        height: 19px;
        flex: 0 0 19px;
        margin: 0;
        accent-color: #0d6efd;
        cursor: pointer;
    }

    .status-content {
        min-width: 0;
    }

    .status-label {
        display: block;
        margin: 0 0 3px;
        color: #1f2937;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .status-help {
        margin: 0;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding-top: 22px;
        margin-top: 22px;
        border-top: 1px solid #edf0f3;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 9px 17px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
        box-shadow: 0 2px 5px rgba(13, 110, 253, 0.18);
    }

    .btn-primary:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.22);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #ffffff;
        border-color: #d5dbe3;
        color: #4b5563;
    }

    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #9ca3af;
        color: #1f2937;
    }

    /* Information Card */
    .info-card {
        overflow: hidden;
    }

    .info-list {
        padding: 4px 0;
    }

    .info-row {
        padding: 14px 18px;
        border-bottom: 1px solid #edf0f3;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        display: block;
        margin-bottom: 4px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .info-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-active {
        background: #e8f7ee;
        color: #198754;
    }

    .status-inactive {
        background: #f1f3f5;
        color: #6c757d;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Responsive */
    @media (max-width: 1000px) {
        .edit-grid {
            grid-template-columns: 1fr;
        }

        .info-card {
            order: 2;
        }
    }

    @media (max-width: 768px) {
        .item-type-page {
            padding: 18px;
        }

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .back-btn {
            width: fit-content;
        }

        .page-title {
            font-size: 24px;
        }

        .card-body {
            padding: 20px 18px;
        }

        .card-header {
            padding: 17px 18px;
        }

        .current-info {
            align-items: flex-start;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .form-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .item-type-page {
            padding: 14px;
        }

        .page-title {
            font-size: 21px;
        }

        .page-subtitle {
            font-size: 13px;
        }

        .current-info {
            gap: 10px;
            padding: 13px;
        }

        .current-icon {
            width: 36px;
            height: 36px;
            flex-basis: 36px;
            font-size: 15px;
        }

        .card-body {
            padding: 18px 15px;
        }

        .card-header {
            padding: 16px 15px;
        }

        .status-box {
            align-items: flex-start;
        }
    }
</style>

<div class="item-type-page">

{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div class="page-header-left">

        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a>

            <span>/</span>

            <a href="{{ route('admin.item-types.index') }}">
                Item Types
            </a>

            <span>/</span>

            <span>Edit</span>
        </div>

        <h1 class="page-title">
            Edit Item Type
        </h1>

        <p class="page-subtitle">
            Update the configuration and information for this CPC IT asset type.
        </p>

    </div>

    <a
        href="{{ route('admin.item-types.show', $itemType) }}"
        class="back-btn"
    >
        ← View Item Type
    </a>

</div>


{{-- =====================================================
     CURRENT ITEM TYPE
====================================================== --}}

<div class="current-info">

    <div class="current-icon">
        IT
    </div>

    <div class="current-content">

        <span class="current-label">
            Currently Editing
        </span>

        <div class="current-name">

            {{ $itemType->name }}

            <span class="current-id">
                — ID #{{ $itemType->id }}
            </span>

        </div>

    </div>

</div>


{{-- =====================================================
     VALIDATION ERRORS
====================================================== --}}

@if($errors->any())

    <div class="alert-danger">

        <div class="alert-icon">
            !
        </div>

        <div class="alert-content">

            <p class="alert-title">
                Please correct the following errors:
            </p>

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    </div>

@endif


{{-- =====================================================
     MAIN CONTENT
====================================================== --}}

<div class="edit-grid">

    {{-- =================================================
         EDIT FORM
    ================================================== --}}

    <div class="form-card">

        <div class="card-header">

            <h2 class="card-title">
                Item Type Information
            </h2>

            <p class="card-description">
                Modify the details below and save your changes.
            </p>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.item-types.update', $itemType) }}"
            >

                @csrf
                @method('PUT')


                {{-- NAME --}}

                <div class="form-group">

                    <label
                        for="name"
                        class="form-label"
                    >
                        Item Type Name
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') has-error @enderror"
                        value="{{ old('name', $itemType->name) }}"
                        placeholder="Enter item type name"
                        autocomplete="off"
                        required
                    >

                    @error('name')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @else

                        <div class="form-help">
                            Enter the official name used to identify this asset type.
                        </div>

                    @enderror

                </div>


                {{-- CATEGORY --}}

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
                        class="form-control @error('category') has-error @enderror"
                        value="{{ old('category', $itemType->category) }}"
                        placeholder="Example: Computer Equipment"
                        autocomplete="off"
                    >

                    @error('category')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @else

                        <div class="form-help">
                            Group this item type under a suitable IT asset category.
                        </div>

                    @enderror

                </div>


                {{-- DESCRIPTION --}}

                <div class="form-group">

                    <label
                        for="description"
                        class="form-label"
                    >
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        class="form-control @error('description') has-error @enderror"
                        placeholder="Enter a description for this item type..."
                    >{{ old('description', $itemType->description) }}</textarea>

                    @error('description')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @else

                        <div class="form-help">
                            Add useful information about the asset type, if required.
                        </div>

                    @enderror

                </div>


                {{-- STATUS --}}

                <div class="form-group">

                    <label class="form-label">
                        Item Type Status
                    </label>

                    <div class="status-box">

                        <input
                            type="checkbox"
                            id="is_active"
                            name="is_active"
                            value="1"
                            class="status-checkbox"
                            {{ old('is_active', $itemType->is_active) ? 'checked' : '' }}
                        >

                        <div class="status-content">

                            <label
                                for="is_active"
                                class="status-label"
                            >
                                Active
                            </label>

                            <p class="status-help">
                                Active item types can be selected when registering
                                new CPC inventory assets.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ACTIONS --}}

                <div class="form-actions">

                    <a
                        href="{{ route('admin.item-types.show', $itemType) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        ✓ Update Item Type
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =================================================
         INFORMATION SIDEBAR
    ================================================== --}}

    <div class="info-card">

        <div class="card-header">

            <h2 class="card-title">
                Current Details
            </h2>

            <p class="card-description">
                Existing item type information.
            </p>

        </div>

        <div class="info-list">

            <div class="info-row">

                <span class="info-label">
                    Item Type ID
                </span>

                <span class="info-value">
                    #{{ $itemType->id }}
                </span>

            </div>

            <div class="info-row">

                <span class="info-label">
                    Current Name
                </span>

                <span class="info-value">
                    {{ $itemType->name }}
                </span>

            </div>

            <div class="info-row">

                <span class="info-label">
                    Category
                </span>

                <span class="info-value">
                    {{ $itemType->category ?: 'Not specified' }}
                </span>

            </div>

            <div class="info-row">

                <span class="info-label">
                    Status
                </span>

                <span class="info-value">

                    @if($itemType->is_active)

                        <span class="status-badge status-active">
                            <span class="status-dot"></span>
                            Active
                        </span>

                    @else

                        <span class="status-badge status-inactive">
                            <span class="status-dot"></span>
                            Inactive
                        </span>

                    @endif

                </span>

            </div>

            <div class="info-row">

                <span class="info-label">
                    Created
                </span>

                <span class="info-value">

                    {{ $itemType->created_at
                        ? $itemType->created_at->format('d M Y')
                        : '—'
                    }}

                </span>

            </div>

            <div class="info-row">

                <span class="info-label">
                    Last Updated
                </span>

                <span class="info-value">

                    {{ $itemType->updated_at
                        ? $itemType->updated_at->format('d M Y')
                        : '—'
                    }}

                </span>

            </div>

        </div>

    </div>

</div>


</div>

@endsection
