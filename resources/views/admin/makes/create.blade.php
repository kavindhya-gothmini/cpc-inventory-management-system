
@extends('layouts.admin')

@section('title', 'Add Make')

@section('page-heading', 'Add New Make')

@push('styles')
<style>

    /* =====================================================
       ADD MAKE PAGE
    ===================================================== */

    .make-page {
        max-width: 1180px;
        margin: 0 auto;
    }

    /* =====================================================
       PAGE HEADER
    ===================================================== */

    .make-page-header {
        margin-bottom: 28px;
    }

    .make-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;

        margin-bottom: 10px;

        font-size: 12px;
        color: #7b8494;
    }

    .make-breadcrumb a {
        color: #0d6efd;
        font-weight: 500;
    }

    .make-breadcrumb a:hover {
        text-decoration: underline;
    }

    .make-breadcrumb-separator {
        color: #b7bec9;
    }

    .make-page-title {
        margin: 0;

        font-size: 28px;
        font-weight: 700;

        letter-spacing: -0.3px;

        color: #172033;
    }

    .make-page-description {
        max-width: 720px;

        margin: 7px 0 0;

        font-size: 14px;
        line-height: 1.6;

        color: #7b8494;
    }

    .make-header-actions {
        display: flex;
        align-items: center;
    }

    /* =====================================================
       MAIN CARD
    ===================================================== */

    .make-form-card {
        overflow: hidden;

        background: #ffffff;

        border: 1px solid #e6eaf0;
        border-radius: 14px;

        box-shadow:
            0 4px 18px rgba(15, 23, 42, 0.05);
    }

    /* =====================================================
       CARD HEADER
    ===================================================== */

    .make-form-header {
        padding: 22px 28px;

        background:
            linear-gradient(
                180deg,
                #ffffff 0%,
                #fbfcfe 100%
            );

        border-bottom: 1px solid #e8ecf2;
    }

    .make-form-header-inner {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .make-icon {
        width: 46px;
        height: 46px;

        flex: 0 0 46px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: #eaf2ff;
        color: #0d6efd;

        font-size: 20px;

        border: 1px solid #dce9ff;
    }

    .make-form-title {
        margin: 0 0 3px;

        font-size: 16px;
        font-weight: 700;

        color: #1f2937;
    }

    .make-form-subtitle {
        margin: 0;

        font-size: 12px;

        color: #7b8494;
    }

    /* =====================================================
       FORM BODY
    ===================================================== */

    .make-form-body {
        padding: 30px 28px;
    }

    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .form-section-title {
        display: flex;
        align-items: center;
        gap: 8px;

        margin-bottom: 17px;

        font-size: 13px;
        font-weight: 700;

        color: #374151;
    }

    .form-section-title i {
        color: #0d6efd;
        font-size: 15px;
    }

    /* =====================================================
       FORM LABELS
    ===================================================== */

    .make-form-card .form-label {
        margin-bottom: 7px;

        font-size: 13px;
        font-weight: 600;

        color: #374151;
    }

    .required-mark {
        color: #dc3545;
        margin-left: 2px;
    }

    .optional-label {
        color: #9aa3b2;
        font-weight: 400;
    }

    /* =====================================================
       FORM CONTROLS
    ===================================================== */

    .make-form-card .form-control,
    .make-form-card .form-select {

        min-height: 44px;

        padding: 10px 13px;

        border: 1px solid #d9dee7;
        border-radius: 8px;

        font-size: 14px;

        color: #293241;

        background-color: #ffffff;

        box-shadow: none;

        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease,
            background-color 0.15s ease;
    }

    .make-form-card textarea.form-control {
        min-height: 120px;

        resize: vertical;
    }

    .make-form-card .form-control::placeholder {
        color: #a7afbd;
    }

    .make-form-card .form-control:focus,
    .make-form-card .form-select:focus {

        border-color: #86b7fe;

        box-shadow:
            0 0 0 3px rgba(13, 110, 253, 0.10);

        background-color: #ffffff;
    }

    .make-form-card .form-control:hover,
    .make-form-card .form-select:hover {
        border-color: #c6ccd6;
    }

    /* =====================================================
       FORM HELP TEXT
    ===================================================== */

    .make-form-card .form-text {

        margin-top: 7px;

        font-size: 11.5px;
        line-height: 1.5;

        color: #8a94a6;
    }

    /* =====================================================
       CHARACTER / INPUT SUPPORT
    ===================================================== */

    .field-meta {
        display: flex;
        justify-content: space-between;
        gap: 12px;

        margin-top: 7px;
    }

    .field-meta .form-text {
        margin-top: 0;
    }

    .character-count {
        flex-shrink: 0;

        font-size: 11px;
        color: #9aa3b2;
    }

    /* =====================================================
       STATUS FIELD
    ===================================================== */

    .status-help {
        display: flex;
        align-items: flex-start;
        gap: 7px;

        margin-top: 8px;

        font-size: 11.5px;
        line-height: 1.5;

        color: #8a94a6;
    }

    .status-help i {
        margin-top: 2px;

        color: #0d6efd;
    }

    /* =====================================================
       VALIDATION
    ===================================================== */

    .make-validation-alert {
        margin-bottom: 24px;

        border-radius: 10px;
        border: 1px solid #f1b0b7;

        font-size: 13px;
    }

    .make-validation-alert ul {
        line-height: 1.7;
    }

    /* =====================================================
       CARD FOOTER
    ===================================================== */

    .make-form-footer {

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        padding: 20px 28px;

        background: #fbfcfe;

        border-top: 1px solid #e8ecf2;
    }

    .footer-note {

        display: flex;
        align-items: center;
        gap: 7px;

        font-size: 11.5px;

        color: #8a94a6;
    }

    .footer-note i {
        color: #0d6efd;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        gap: 9px;

        flex-shrink: 0;
    }

    .form-actions .btn {

        min-height: 42px;

        padding:
            9px 17px;

        border-radius: 8px;

        font-size: 13px;
        font-weight: 600;
    }

    /* =====================================================
       BUTTONS
    ===================================================== */

    .btn-back {

        min-height: 40px;

        padding:
            8px 15px;

        border-radius: 8px;

        font-size: 13px;
        font-weight: 600;
    }

    .btn-save {

        min-width: 125px;
    }

    /* =====================================================
       RESPONSIVE — TABLET
    ===================================================== */

    @media (max-width: 991px) {

        .make-page {
            max-width: 100%;
        }

        .make-page-title {
            font-size: 25px;
        }

        .make-form-body {
            padding: 26px 22px;
        }

        .make-form-header {
            padding: 20px 22px;
        }

        .make-form-footer {
            padding: 18px 22px;
        }
    }

    /* =====================================================
       RESPONSIVE — MOBILE
    ===================================================== */

    @media (max-width: 767px) {

        .make-page {
            width: 100%;
        }

        .make-page-header {
            margin-bottom: 20px;
        }

        .make-page-header-row {
            display: block !important;
        }

        .make-page-title {
            font-size: 23px;
        }

        .make-page-description {
            font-size: 13px;
        }

        .make-header-actions {
            margin-top: 15px;
        }

        .btn-back {
            width: 100%;
        }

        .make-form-card {
            border-radius: 11px;
        }

        .make-form-header {
            padding: 18px;
        }

        .make-form-body {
            padding: 22px 18px;
        }

        .make-form-footer {
            display: block;

            padding: 18px;
        }

        .footer-note {
            margin-bottom: 15px;
        }

        .form-actions {
            width: 100%;
        }

        .form-actions .btn {
            flex: 1;
        }

        .make-icon {
            width: 42px;
            height: 42px;
            flex-basis: 42px;
        }

        .form-section {
            margin-bottom: 24px;
        }
    }

    /* =====================================================
       RESPONSIVE — SMALL MOBILE
    ===================================================== */

    @media (max-width: 420px) {

        .make-page-title {
            font-size: 21px;
        }

        .make-form-header-inner {
            align-items: flex-start;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn {
            width: 100%;
        }

        .field-meta {
            display: block;
        }

        .character-count {
            display: block;
            margin-top: 4px;
        }
    }

</style>
@endpush


@section('content')

<div class="make-page">


    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="make-page-header">

        <div
            class="make-page-header-row d-flex
                   justify-content-between
                   align-items-end
                   gap-3"
        >

            <div>

                {{-- Breadcrumb --}}

                <div class="make-breadcrumb">

                    <a href="{{ route('admin.makes.index') }}">
                        Make Management
                    </a>

                    <span class="make-breadcrumb-separator">
                        <i class="bi bi-chevron-right"></i>
                    </span>

                    <span>
                        Add Make
                    </span>

                </div>


                {{-- Page Title --}}

                <h1 class="make-page-title">
                    Add New Make
                </h1>

                <p class="make-page-description">
                    Register a manufacturer that can be used when
                    creating and managing inventory items.
                </p>

            </div>


            {{-- Back Button --}}

            <div class="make-header-actions">

                <a
                    href="{{ route('admin.makes.index') }}"
                    class="btn btn-outline-secondary btn-back"
                >

                    <i class="bi bi-arrow-left me-1"></i>

                    Back to Makes

                </a>

            </div>

        </div>

    </div>


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div
            class="alert alert-danger
                   alert-dismissible
                   fade show
                   make-validation-alert"
            role="alert"
        >

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- =====================================================
         FORM CARD
    ====================================================== --}}

    <div class="make-form-card">


        {{-- =================================================
             CARD HEADER
        ================================================== --}}

        <div class="make-form-header">

            <div class="make-form-header-inner">

                <div class="make-icon">

                    <i class="bi bi-building"></i>

                </div>

                <div>

                    <h2 class="make-form-title">
                        Make Information
                    </h2>

                    <p class="make-form-subtitle">
                        Enter the manufacturer details required
                        for inventory registration.
                    </p>

                </div>

            </div>

        </div>


        {{-- =================================================
             FORM
        ================================================== --}}

        <form
            action="{{ route('admin.makes.store') }}"
            method="POST"
        >

            @csrf


            <div class="make-form-body">


                {{-- =================================================
                     BASIC INFORMATION
                ================================================== --}}

                <div class="form-section">

                    <div class="form-section-title">

                        <i class="bi bi-info-circle"></i>

                        <span>
                            Basic Information
                        </span>

                    </div>


                    {{-- Make Name --}}

                    <div>

                        <label
                            for="name"
                            class="form-label"
                        >

                            Make Name

                            <span class="required-mark">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control
                                @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="e.g. HP"
                            maxlength="100"
                            autocomplete="organization"
                            required
                            autofocus
                        >

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="field-meta">

                            <div class="form-text">

                                Enter the manufacturer name,
                                such as HP, Dell, Lenovo, or Acer.

                            </div>

                            <span
                                class="character-count"
                                id="nameCounter"
                            >
                                0 / 100
                            </span>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ADDITIONAL INFORMATION
                ================================================== --}}

                <div class="form-section">

                    <div class="form-section-title">

                        <i class="bi bi-card-text"></i>

                        <span>
                            Additional Information
                        </span>

                    </div>


                    {{-- Description --}}

                    <div>

                        <label
                            for="description"
                            class="form-label"
                        >

                            Description

                            <span class="optional-label">
                                (Optional)
                            </span>

                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="5"
                            maxlength="500"
                            class="form-control
                                @error('description') is-invalid @enderror"
                            placeholder="Enter a short description about this manufacturer..."
                        >{{ old('description') }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="field-meta">

                            <div class="form-text">

                                Additional information about the
                                manufacturer can be recorded here.

                            </div>

                            <span
                                class="character-count"
                                id="descriptionCounter"
                            >
                                0 / 500
                            </span>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     STATUS
                ================================================== --}}

                <div class="form-section">

                    <div class="form-section-title">

                        <i class="bi bi-toggle-on"></i>

                        <span>
                            Availability
                        </span>

                    </div>


                    <label
                        for="is_active"
                        class="form-label"
                    >

                        Status

                        <span class="required-mark">
                            *
                        </span>

                    </label>

                    <select
                        name="is_active"
                        id="is_active"
                        class="form-select
                            @error('is_active') is-invalid @enderror"
                        required
                    >

                        <option
                            value="1"
                            {{ old('is_active', '1') == '1' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="0"
                            {{ old('is_active') === '0' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                    @error('is_active')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror


                    <div class="status-help">

                        <i class="bi bi-info-circle"></i>

                        <span>
                            Only active makes will be available for
                            selection when registering inventory items.
                        </span>

                    </div>

                </div>


            </div>


            {{-- =================================================
                 FORM FOOTER
            ================================================== --}}

            <div class="make-form-footer">


                <div class="footer-note">

                    <i class="bi bi-shield-check"></i>

                    <span>
                        This master record can be managed by Super Admin.
                    </span>

                </div>


                <div class="form-actions">

                    <a
                        href="{{ route('admin.makes.index') }}"
                        class="btn btn-outline-secondary"
                    >

                        <i class="bi bi-x-lg me-1"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary btn-save"
                    >

                        <i class="bi bi-check-lg me-1"></i>

                        Save Make

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const nameInput =
            document.getElementById('name');

        const nameCounter =
            document.getElementById('nameCounter');

        const descriptionInput =
            document.getElementById('description');

        const descriptionCounter =
            document.getElementById('descriptionCounter');


        /* =================================================
           NAME CHARACTER COUNTER
        ================================================== */

        function updateNameCounter() {

            if (!nameInput || !nameCounter) {
                return;
            }

            nameCounter.textContent =
                nameInput.value.length + ' / 100';

        }


        /* =================================================
           DESCRIPTION CHARACTER COUNTER
        ================================================== */

        function updateDescriptionCounter() {

            if (!descriptionInput || !descriptionCounter) {
                return;
            }

            descriptionCounter.textContent =
                descriptionInput.value.length + ' / 500';

        }


        /* =================================================
           EVENTS
        ================================================== */

        if (nameInput) {

            nameInput.addEventListener(
                'input',
                updateNameCounter
            );

        }

        if (descriptionInput) {

            descriptionInput.addEventListener(
                'input',
                updateDescriptionCounter
            );

        }


        /* =================================================
           INITIAL VALUES
        ================================================== */

        updateNameCounter();

        updateDescriptionCounter();

    });

</script>
@endpush

