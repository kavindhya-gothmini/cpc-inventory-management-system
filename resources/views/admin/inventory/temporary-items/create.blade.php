@extends('layouts.admin')

@section('title', 'Add Temporary Item | CPC Inventory Management System')

@section('page-heading', 'Add Temporary Item')

@section('content')

<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="page-icon">
                    <i class="bi bi-box-seam"></i>
                </span>

                <h4 class="fw-bold mb-0">
                    Add Temporary Item
                </h4>
            </div>

            <p class="text-muted mb-0 ms-lg-5">
                Register a temporary item for controlled inventory management and issue tracking.
            </p>
        </div>

        <a href="{{ route('admin.inventory.temporary-items.index') }}"
           class="btn btn-outline-secondary px-3">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Temporary Items

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">

            <div class="d-flex align-items-start">

                <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>

                <div>

                    <h6 class="fw-bold mb-1">
                        Please review the following errors
                    </h6>

                    <ul class="mb-0 ps-3">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.inventory.temporary-items.store') }}"
          id="temporaryItemForm">

        @csrf

        <div class="row g-4">

            {{-- Main Form --}}
            <div class="col-12 col-xl-8">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    {{-- Card Header --}}
                    <div class="card-header bg-white border-bottom py-3 px-4">

                        <div class="d-flex align-items-center">

                            <div class="section-icon me-3">
                                <i class="bi bi-info-circle"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Item Information
                                </h5>

                                <small class="text-muted">
                                    Enter the basic identification and classification details.
                                </small>
                            </div>

                        </div>

                    </div>


                    {{-- Card Body --}}
                    <div class="card-body p-4">

                        <div class="row g-4">

                            {{-- Item Code --}}
                            <div class="col-12 col-md-6">

                                <label for="item_code"
                                       class="form-label fw-semibold">

                                    Item Code
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-upc-scan text-muted"></i>
                                    </span>

                                    <input type="text"
                                           id="item_code"
                                           name="item_code"
                                           value="{{ old('item_code') }}"
                                           class="form-control border-start-0 ps-0 @error('item_code') is-invalid @enderror"
                                           placeholder="e.g. TEMP-001"
                                           maxlength="100"
                                           autocomplete="off"
                                           required>

                                </div>

                                <div class="form-text">
                                    Use a unique identification code for this temporary item.
                                </div>

                                @error('item_code')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Item Type --}}
                            <div class="col-12 col-md-6">

                                <label for="item_type"
                                       class="form-label fw-semibold">

                                    Item Type
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-tags text-muted"></i>
                                    </span>

                                    <input type="text"
                                           id="item_type"
                                           name="item_type"
                                           value="{{ old('item_type') }}"
                                           class="form-control border-start-0 ps-0 @error('item_type') is-invalid @enderror"
                                           placeholder="e.g. Laptop"
                                           maxlength="100"
                                           required>

                                </div>

                                <div class="form-text">
                                    Specify the category or type of temporary item.
                                </div>

                                @error('item_type')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Description --}}
                            <div class="col-12">

                                <label for="description"
                                       class="form-label fw-semibold">

                                    Description

                                </label>

                                <textarea id="description"
                                          name="description"
                                          rows="5"
                                          maxlength="1000"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Provide a brief description, specification, or identifying details of the temporary item...">{{ old('description') }}</textarea>

                                <div class="d-flex justify-content-between mt-1">

                                    <div class="form-text">
                                        Add useful information that helps identify the item.
                                    </div>

                                    <small class="text-muted">
                                        <span id="descriptionCount">0</span>/1000
                                    </small>

                                </div>

                                @error('description')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Status --}}
                            <div class="col-12 col-md-6">

                                <label for="status"
                                       class="form-label fw-semibold">

                                    Status
                                    <span class="text-danger">*</span>

                                </label>

                                <select id="status"
                                        name="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select status
                                    </option>

                                    <option value="available"
                                        {{ old('status', 'available') === 'available' ? 'selected' : '' }}>
                                        Available
                                    </option>

                                    <option value="issued"
                                        {{ old('status') === 'issued' ? 'selected' : '' }}>
                                        Issued
                                    </option>

                                    <option value="inactive"
                                        {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                                <div id="statusHelp"
                                     class="form-text">
                                    New temporary items are normally registered as available.
                                </div>

                                @error('status')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Remarks --}}
                            <div class="col-12 col-md-6">

                                <label for="remarks"
                                       class="form-label fw-semibold">

                                    Remarks

                                </label>

                                <textarea id="remarks"
                                          name="remarks"
                                          rows="3"
                                          maxlength="2000"
                                          class="form-control @error('remarks') is-invalid @enderror"
                                          placeholder="Enter any additional notes or remarks...">{{ old('remarks') }}</textarea>

                                <div class="d-flex justify-content-end mt-1">

                                    <small class="text-muted">
                                        <span id="remarksCount">0</span>/2000
                                    </small>

                                </div>

                                @error('remarks')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Side Information Panel --}}
            <div class="col-12 col-xl-4">

                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-header bg-white border-bottom py-3 px-4">

                        <div class="d-flex align-items-center">

                            <div class="section-icon me-3">
                                <i class="bi bi-shield-check"></i>
                            </div>

                            <div>
                                <h6 class="fw-bold mb-0">
                                    Registration Guide
                                </h6>

                                <small class="text-muted">
                                    Temporary item controls
                                </small>
                            </div>

                        </div>

                    </div>

                    <div class="card-body p-4">

                        <div class="guide-item">
                            <div class="guide-icon">
                                <i class="bi bi-upc"></i>
                            </div>

                            <div>
                                <h6 class="fw-semibold mb-1">
                                    Unique Item Code
                                </h6>

                                <p class="small text-muted mb-0">
                                    Each temporary item must have a unique identification code.
                                </p>
                            </div>
                        </div>


                        <div class="guide-item">
                            <div class="guide-icon">
                                <i class="bi bi-check2-circle"></i>
                            </div>

                            <div>
                                <h6 class="fw-semibold mb-1">
                                    Available Status
                                </h6>

                                <p class="small text-muted mb-0">
                                    Use <strong>Available</strong> when the item is ready to be issued.
                                </p>
                            </div>
                        </div>


                        <div class="guide-item">
                            <div class="guide-icon">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>

                            <div>
                                <h6 class="fw-semibold mb-1">
                                    Issue Tracking
                                </h6>

                                <p class="small text-muted mb-0">
                                    Issue records can be associated with this temporary item for tracking.
                                </p>
                            </div>
                        </div>


                        <div class="guide-item mb-0">
                            <div class="guide-icon">
                                <i class="bi bi-journal-text"></i>
                            </div>

                            <div>
                                <h6 class="fw-semibold mb-1">
                                    Remarks
                                </h6>

                                <p class="small text-muted mb-0">
                                    Use remarks for additional information that may be useful later.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>


                {{-- Status Preview --}}
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center justify-content-between mb-3">

                            <div>
                                <small class="text-muted d-block">
                                    Current Selection
                                </small>

                                <span class="fw-bold">
                                    Item Status
                                </span>
                            </div>

                            <span id="statusBadge"
                                  class="badge rounded-pill bg-success-subtle text-success px-3 py-2">

                                Available

                            </span>

                        </div>

                        <div class="progress"
                             style="height: 5px;">

                            <div id="statusProgress"
                                 class="progress-bar"
                                 role="progressbar"
                                 style="width: 100%;">

                            </div>

                        </div>

                        <p id="statusDescription"
                           class="small text-muted mt-3 mb-0">

                            This item is available for temporary issue.

                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- Form Actions --}}
        <div class="card border-0 shadow-sm rounded-4 mt-4">

            <div class="card-body p-3 p-md-4">

                <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3">

                    <div class="text-muted small">

                        <i class="bi bi-info-circle me-1"></i>

                        Fields marked with
                        <span class="text-danger">*</span>
                        are required.

                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">

                        <a href="{{ route('admin.inventory.temporary-items.index') }}"
                           class="btn btn-light border px-4">

                            <i class="bi bi-x-circle me-1"></i>
                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-primary px-4"
                                id="saveButton">

                            <i class="bi bi-check-circle me-1"></i>
                            Save Temporary Item

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- Page Styles --}}
@push('styles')
<style>

    .page-icon {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(13, 110, 253, 0.10);
        color: #0d6efd;
        font-size: 1.15rem;
    }

    .section-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
    }

    .card {
        transition: box-shadow 0.2s ease;
    }

    .form-label {
        color: #344054;
        margin-bottom: 0.55rem;
    }

    .form-control,
    .form-select,
    .input-group-text {
        min-height: 44px;
        border-color: #d9dee7;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.10);
    }

    textarea.form-control {
        min-height: auto;
        resize: vertical;
    }

    .input-group .form-control {
        border-left: 0;
    }

    .input-group:focus-within .input-group-text {
        border-color: #86b7fe;
        color: #0d6efd !important;
    }

    .guide-item {
        display: flex;
        gap: 14px;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid #edf0f4;
    }

    .guide-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #f5f7fa;
        color: #0d6efd;
    }

    .guide-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .badge.bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.10) !important;
    }

    .badge.bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.14) !important;
    }

    .badge.bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.12) !important;
    }

    @media (max-width: 575.98px) {

        .container-fluid {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .card-body {
            padding: 1.1rem !important;
        }

        .page-icon {
            width: 38px;
            height: 38px;
        }

        h4 {
            font-size: 1.2rem;
        }

        .btn {
            min-height: 44px;
        }

        .input-group .input-group-text {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

    }

</style>
@endpush


{{-- Page Scripts --}}
@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const description = document.getElementById('description');
        const remarks = document.getElementById('remarks');

        const descriptionCount = document.getElementById('descriptionCount');
        const remarksCount = document.getElementById('remarksCount');

        const status = document.getElementById('status');
        const statusBadge = document.getElementById('statusBadge');
        const statusProgress = document.getElementById('statusProgress');
        const statusDescription = document.getElementById('statusDescription');
        const statusHelp = document.getElementById('statusHelp');

        const saveButton = document.getElementById('saveButton');
        const form = document.getElementById('temporaryItemForm');


        function updateCharacterCount(field, counter) {

            if (!field || !counter) {
                return;
            }

            counter.textContent = field.value.length;

        }


        function updateStatusPreview() {

            if (!status) {
                return;
            }

            const value = status.value;

            statusBadge.className =
                'badge rounded-pill px-3 py-2';

            if (value === 'issued') {

                statusBadge.classList.add(
                    'bg-warning-subtle',
                    'text-warning-emphasis'
                );

                statusBadge.textContent = 'Issued';

                statusProgress.style.width = '60%';

                statusDescription.textContent =
                    'This item is currently marked as issued.';

                statusHelp.textContent =
                    'Use Issued when the temporary item is currently assigned or issued.';

            } else if (value === 'inactive') {

                statusBadge.classList.add(
                    'bg-secondary-subtle',
                    'text-secondary'
                );

                statusBadge.textContent = 'Inactive';

                statusProgress.style.width = '25%';

                statusDescription.textContent =
                    'This item is inactive and should not normally be issued.';

                statusHelp.textContent =
                    'Use Inactive when the temporary item is no longer available for normal use.';

            } else {

                statusBadge.classList.add(
                    'bg-success-subtle',
                    'text-success'
                );

                statusBadge.textContent = 'Available';

                statusProgress.style.width = '100%';

                statusDescription.textContent =
                    'This item is available for temporary issue.';

                statusHelp.textContent =
                    'New temporary items are normally registered as available.';

            }

        }


        if (description) {

            description.addEventListener('input', function () {
                updateCharacterCount(description, descriptionCount);
            });

            updateCharacterCount(description, descriptionCount);

        }


        if (remarks) {

            remarks.addEventListener('input', function () {
                updateCharacterCount(remarks, remarksCount);
            });

            updateCharacterCount(remarks, remarksCount);

        }


        if (status) {

            status.addEventListener('change', updateStatusPreview);

            updateStatusPreview();

        }


        if (form && saveButton) {

            form.addEventListener('submit', function () {

                saveButton.disabled = true;

                saveButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                    'Saving...';

            });

        }

    });

</script>
@endpush

@endsection

