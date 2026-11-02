@extends('layouts.admin')

@section('title', 'Edit Temporary Item | CPC Inventory Management System')

@section('page-heading', 'Edit Temporary Item')

@section('content')

<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">

                <span class="page-icon">
                    <i class="bi bi-pencil-square"></i>
                </span>

                <div>
                    <h4 class="fw-bold mb-0">
                        Edit Temporary Item
                    </h4>

                    <div class="d-flex align-items-center gap-2 mt-1">

                        <span class="text-muted small">
                            Item Code:
                        </span>

                        <span class="badge bg-light text-dark border fw-semibold">
                            {{ $temporaryItem->item_code }}
                        </span>

                    </div>
                </div>

            </div>

            <p class="text-muted mb-0 ms-lg-5">
                Review and update the registered temporary item information.
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
          action="{{ route('admin.inventory.temporary-items.update', $temporaryItem) }}"
          id="temporaryItemForm">

        @csrf
        @method('PUT')


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
                                    Update the identification, classification and status details.
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
                                           value="{{ old('item_code', $temporaryItem->item_code) }}"
                                           class="form-control border-start-0 ps-0 @error('item_code') is-invalid @enderror"
                                           maxlength="100"
                                           autocomplete="off"
                                           required>

                                </div>

                                <div class="form-text">
                                    The item code must remain unique within temporary items.
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
                                           value="{{ old('item_type', $temporaryItem->item_type) }}"
                                           class="form-control border-start-0 ps-0 @error('item_type') is-invalid @enderror"
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
                                          placeholder="Enter a brief description or identifying details...">{{ old('description', $temporaryItem->description) }}</textarea>

                                <div class="d-flex justify-content-between mt-1">

                                    <div class="form-text">
                                        Keep the description useful for identification and future reference.
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

                                    <option value="available"
                                        {{ old('status', $temporaryItem->status) === 'available' ? 'selected' : '' }}>
                                        Available
                                    </option>

                                    <option value="issued"
                                        {{ old('status', $temporaryItem->status) === 'issued' ? 'selected' : '' }}>
                                        Issued
                                    </option>

                                    <option value="inactive"
                                        {{ old('status', $temporaryItem->status) === 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                                <div id="statusHelp"
                                     class="form-text">
                                    Status determines whether the item is available for temporary issue.
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
                                          placeholder="Enter any additional notes or remarks...">{{ old('remarks', $temporaryItem->remarks) }}</textarea>

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


            {{-- Right Information Panel --}}
            <div class="col-12 col-xl-4">


                {{-- Current Record --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-header bg-white border-bottom py-3 px-4">

                        <div class="d-flex align-items-center">

                            <div class="section-icon me-3">
                                <i class="bi bi-clipboard-data"></i>
                            </div>

                            <div>

                                <h6 class="fw-bold mb-0">
                                    Record Information
                                </h6>

                                <small class="text-muted">
                                    Current item details
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body p-4">

                        <div class="record-row">

                            <span class="record-label">
                                Item Code
                            </span>

                            <span class="record-value">
                                {{ $temporaryItem->item_code }}
                            </span>

                        </div>


                        <div class="record-row">

                            <span class="record-label">
                                Item Type
                            </span>

                            <span class="record-value">
                                {{ $temporaryItem->item_type }}
                            </span>

                        </div>


                        <div class="record-row">

                            <span class="record-label">
                                Issue Records
                            </span>

                            <span class="record-value">

                                <span class="badge bg-light text-dark border">

                                    {{ $temporaryItem->issues()->count() }}

                                    {{ \Illuminate\Support\Str::plural('record', $temporaryItem->issues()->count()) }}

                                </span>

                            </span>

                        </div>


                        <div class="record-row mb-0">

                            <span class="record-label">
                                Current Status
                            </span>

                            <span class="record-value">

                                @if($temporaryItem->status === 'available')

                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                        Available
                                    </span>

                                @elseif($temporaryItem->status === 'issued')

                                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">
                                        Issued
                                    </span>

                                @else

                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">
                                        Inactive
                                    </span>

                                @endif

                            </span>

                        </div>

                    </div>

                </div>


                {{-- Status Preview --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center justify-content-between mb-3">

                            <div>

                                <small class="text-muted d-block">
                                    Updated Selection
                                </small>

                                <span class="fw-bold">
                                    Item Status
                                </span>

                            </div>

                            <span id="statusBadge"
                                  class="badge rounded-pill px-3 py-2">

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


                {{-- Update Notice --}}
                <div class="alert alert-primary border-0 rounded-4 mb-0">

                    <div class="d-flex align-items-start">

                        <i class="bi bi-info-circle-fill me-3 fs-5"></i>

                        <div>

                            <h6 class="fw-bold mb-1">
                                Update Notice
                            </h6>

                            <p class="small mb-0">
                                Changes made here will update the temporary item record and its current status.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Form Actions --}}
        <div class="card border-0 shadow-sm rounded-4 mt-4">

            <div class="card-body p-3 p-md-4">

                <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3">

                    <div class="text-muted small">

                        <i class="bi bi-clock-history me-1"></i>

                        Last updated:
                        <strong>
                            {{ $temporaryItem->updated_at?->format('d M Y, h:i A') ?? '—' }}
                        </strong>

                    </div>


                    <div class="d-flex flex-column flex-sm-row gap-2">

                        <a href="{{ route('admin.inventory.temporary-items.show', $temporaryItem) }}"
                           class="btn btn-light border px-4">

                            <i class="bi bi-eye me-1"></i>
                            View Details

                        </a>

                        <a href="{{ route('admin.inventory.temporary-items.index') }}"
                           class="btn btn-light border px-4">

                            <i class="bi bi-x-circle me-1"></i>
                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-primary px-4"
                                id="updateButton">

                            <i class="bi bi-save me-1"></i>
                            Update Temporary Item

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
        flex-shrink: 0;
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

    .record-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 13px 0;
        border-bottom: 1px solid #edf0f4;
    }

    .record-label {
        color: #667085;
        font-size: 0.875rem;
    }

    .record-value {
        color: #344054;
        font-size: 0.875rem;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
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

    .progress {
        background-color: #edf0f4;
        border-radius: 10px;
    }

    .progress-bar {
        transition: width 0.25s ease;
    }

    .card {
        transition: box-shadow 0.2s ease;
    }

    @media (max-width: 575.98px) {

        .container-fluid {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .card-body {
            padding: 1.1rem !important;
        }

        .card-header {
            padding-left: 1.1rem !important;
            padding-right: 1.1rem !important;
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

        .record-row {
            align-items: flex-start;
        }

        .record-value {
            max-width: 55%;
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

        const form = document.getElementById('temporaryItemForm');
        const updateButton = document.getElementById('updateButton');


        function updateCharacterCount(field, counter) {

            if (!field || !counter) {
                return;
            }

            counter.textContent = field.value.length;

        }


        function updateStatusPreview() {

            if (!status || !statusBadge) {
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
                    'Use Inactive when the item should no longer be available for normal issue.';

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
                    'Use Available when the temporary item is ready to be issued.';

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


        if (form && updateButton) {

            form.addEventListener('submit', function () {

                updateButton.disabled = true;

                updateButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                    'Updating...';

            });

        }

    });

</script>
@endpush

@endsection

