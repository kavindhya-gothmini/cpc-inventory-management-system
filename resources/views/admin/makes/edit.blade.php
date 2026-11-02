
@extends('layouts.admin')

@section('title', 'Edit Make')

@push('styles')
<style>
    /* =========================================================
       EDIT MAKE PAGE
       ========================================================= */

    .make-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Breadcrumb */
    .make-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .make-breadcrumb a {
        color: #6c757d;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .make-breadcrumb a:hover {
        color: #0d6efd;
    }

    .make-breadcrumb .current {
        color: #212529;
        font-weight: 600;
    }

    .make-breadcrumb .separator {
        color: #adb5bd;
    }

    /* Header */
    .make-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 28px;
    }

    .make-title-wrapper {
        min-width: 0;
    }

    .make-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 7px;
        color: #212529;
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .make-title-icon {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 12px;
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        font-size: 1.2rem;
    }

    .make-subtitle {
        margin: 0;
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        min-height: 42px;
        padding: 9px 16px;
        border-radius: 9px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .back-button:hover {
        transform: translateY(-1px);
    }

    /* Validation */
    .validation-alert {
        border: 1px solid rgba(220, 53, 69, 0.2);
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 24px;
        background: #fff5f5;
    }

    .validation-alert-header {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #b02a37;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .validation-alert ul {
        padding-left: 28px;
        margin-bottom: 0;
        color: #842029;
    }

    .validation-alert li + li {
        margin-top: 4px;
    }

    /* Main card */
    .make-form-card,
    .management-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.045);
    }

    .make-card-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 19px 24px;
        border-bottom: 1px solid #edf0f2;
        background: #ffffff;
    }

    .card-header-icon {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 10px;
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        font-size: 1.05rem;
    }

    .make-card-header h2 {
        margin: 0;
        color: #212529;
        font-size: 1.05rem;
        font-weight: 700;
    }

    .make-card-header p {
        margin: 3px 0 0;
        color: #6c757d;
        font-size: 0.82rem;
    }

    .make-card-body {
        padding: 26px 24px;
    }

    /* Section */
    .form-section + .form-section {
        margin-top: 30px;
        padding-top: 28px;
        border-top: 1px solid #edf0f2;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .section-heading-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 8px;
        background: #f1f3f5;
        color: #495057;
        font-size: 0.9rem;
    }

    .section-heading h3 {
        margin: 0;
        color: #343a40;
        font-size: 0.94rem;
        font-weight: 700;
    }

    .section-heading p {
        margin: 2px 0 0;
        color: #868e96;
        font-size: 0.78rem;
    }

    /* Form fields */
    .field-group {
        margin-bottom: 22px;
    }

    .field-group:last-child {
        margin-bottom: 0;
    }

    .form-label-custom {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 8px;
        color: #343a40;
        font-size: 0.88rem;
        font-weight: 650;
    }

    .required-mark {
        color: #dc3545;
    }

    .form-control-custom,
    .form-select-custom {
        min-height: 45px;
        border: 1px solid #ced4da;
        border-radius: 9px;
        padding: 10px 13px;
        color: #212529;
        font-size: 0.9rem;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background-color 0.2s ease;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }

    textarea.form-control-custom {
        min-height: 125px;
        resize: vertical;
        line-height: 1.55;
    }

    .form-control-custom.is-invalid,
    .form-select-custom.is-invalid {
        border-color: #dc3545;
    }

    .form-control-custom.is-invalid:focus,
    .form-select-custom.is-invalid:focus {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
    }

    .invalid-feedback {
        font-size: 0.8rem;
    }

    .field-help {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-top: 7px;
        color: #868e96;
        font-size: 0.77rem;
        line-height: 1.45;
    }

    .character-counter {
        flex-shrink: 0;
        white-space: nowrap;
        color: #868e96;
    }

    /* Status */
    .status-wrapper {
        position: relative;
    }

    .status-select {
        font-weight: 600;
    }

    .status-description {
        margin-top: 9px;
        padding: 11px 13px;
        border-radius: 9px;
        background: #f8f9fa;
        color: #6c757d;
        font-size: 0.78rem;
        line-height: 1.5;
    }

    /* Associated Models */
    .association-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-top: 28px;
        padding: 17px;
        border: 1px solid #cfe2ff;
        border-radius: 11px;
        background: #f4f8ff;
    }

    .association-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 9px;
        background: #dbeafe;
        color: #0d6efd;
        font-size: 1rem;
    }

    .association-content {
        min-width: 0;
    }

    .association-title {
        margin-bottom: 4px;
        color: #1f2937;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .association-text {
        margin: 0;
        color: #5f6b7a;
        font-size: 0.82rem;
        line-height: 1.55;
    }

    .model-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 27px;
        height: 25px;
        margin: 0 3px;
        padding: 0 7px;
        border-radius: 7px;
        background: #0d6efd;
        color: #ffffff;
        font-size: 0.76rem;
        font-weight: 700;
    }

    .association-note {
        display: block;
        margin-top: 7px;
        color: #6c757d;
        font-size: 0.76rem;
        line-height: 1.5;
    }

    /* Form footer */
    .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-top: 30px;
        padding-top: 22px;
        border-top: 1px solid #edf0f2;
    }

    .form-footer-note {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6c757d;
        font-size: 0.77rem;
        line-height: 1.4;
    }

    .form-footer-note i {
        color: #0d6efd;
        font-size: 0.9rem;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-shrink: 0;
    }

    .action-button {
        min-height: 43px;
        padding: 9px 17px;
        border-radius: 9px;
        font-size: 0.86rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .action-button:hover {
        transform: translateY(-1px);
    }

    /* Management Card */
    .management-card {
        height: auto;
    }

    .management-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 19px 21px;
        border-bottom: 1px solid #edf0f2;
    }

    .management-header-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 9px;
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .management-header h2 {
        margin: 0;
        color: #212529;
        font-size: 0.98rem;
        font-weight: 700;
    }

    .management-body {
        padding: 21px;
    }

    .management-intro {
        margin: 0 0 20px;
        color: #6c757d;
        font-size: 0.84rem;
        line-height: 1.65;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 13px 0;
    }

    .info-item + .info-item {
        border-top: 1px solid #edf0f2;
    }

    .info-item-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 8px;
        background: #f8f9fa;
        color: #6c757d;
        font-size: 0.8rem;
    }

    .info-item-title {
        margin-bottom: 3px;
        color: #343a40;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .info-item-text {
        margin: 0;
        color: #6c757d;
        font-size: 0.78rem;
        line-height: 1.55;
    }

    .example-value {
        display: inline-block;
        margin-top: 4px;
        padding: 5px 8px;
        border-radius: 6px;
        background: #f8f9fa;
        color: #495057;
        font-size: 0.77rem;
        font-weight: 600;
    }

    /* Current status badge */
    .current-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 5px;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .current-status.active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .current-status.inactive {
        background: #f8d7da;
        color: #842029;
    }

    .current-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .make-page-header {
            margin-bottom: 22px;
        }

        .make-title {
            font-size: 1.5rem;
        }

        .make-form-card {
            height: auto;
        }

        .management-card {
            margin-top: 0;
        }
    }

    @media (max-width: 767.98px) {
        .make-page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
        }

        .back-button {
            width: 100%;
        }

        .make-title {
            font-size: 1.35rem;
        }

        .make-title-icon {
            width: 40px;
            height: 40px;
        }

        .make-card-header,
        .make-card-body {
            padding-left: 18px;
            padding-right: 18px;
        }

        .management-header,
        .management-body {
            padding-left: 18px;
            padding-right: 18px;
        }

        .form-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .form-footer-note {
            justify-content: center;
            text-align: center;
        }

        .form-actions {
            width: 100%;
        }

        .form-actions .action-button {
            flex: 1;
        }
    }

    @media (max-width: 575.98px) {
        .make-breadcrumb {
            font-size: 0.8rem;
        }

        .make-title {
            align-items: flex-start;
        }

        .make-subtitle {
            font-size: 0.86rem;
        }

        .make-card-header {
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .make-card-body {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .section-heading {
            margin-bottom: 16px;
        }

        .field-help {
            flex-direction: column;
            gap: 3px;
        }

        .association-box {
            padding: 14px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .action-button {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

<div class="container-fluid">
    <div class="make-page">

        {{-- =====================================================
             Breadcrumb
             ===================================================== --}}
        <div class="make-breadcrumb">

            <a href="{{ route('admin.makes.index') }}">
                <i class="bi bi-diagram-3 me-1"></i>
                Make Management
            </a>

            <span class="separator">
                <i class="bi bi-chevron-right"></i>
            </span>

            <span class="current">
                Edit Make
            </span>

        </div>


        {{-- =====================================================
             Page Header
             ===================================================== --}}
        <div class="make-page-header">

            <div class="make-title-wrapper">

                <h1 class="make-title">

                    <span class="make-title-icon">
                        <i class="bi bi-pencil-square"></i>
                    </span>

                    <span>Edit Make</span>

                </h1>

                <p class="make-subtitle">
                    Update the manufacturer information and availability
                    of this inventory make.
                </p>

            </div>


            <a href="{{ route('admin.makes.index') }}"
               class="btn btn-outline-secondary back-button">

                <i class="bi bi-arrow-left me-2"></i>
                Back to Makes

            </a>

        </div>


        {{-- =====================================================
             Validation Errors
             ===================================================== --}}
        @if ($errors->any())

            <div class="validation-alert">

                <div class="validation-alert-header">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                    <span>
                        Please correct the following errors
                    </span>

                </div>

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="row g-4">

            {{-- =================================================
                 Main Form
                 ================================================= --}}
            <div class="col-xl-8 col-lg-7">

                <div class="make-form-card">

                    {{-- Card Header --}}
                    <div class="make-card-header">

                        <span class="card-header-icon">
                            <i class="bi bi-pencil-square"></i>
                        </span>

                        <div>

                            <h2>
                                Make Information
                            </h2>

                            <p>
                                Update the master record details below.
                            </p>

                        </div>

                    </div>


                    {{-- Card Body --}}
                    <div class="make-card-body">

                        <form action="{{ route('admin.makes.update', $make) }}"
                              method="POST">

                            @csrf
                            @method('PUT')


                            {{-- =================================
                                 Basic Information
                                 ================================= --}}
                            <div class="form-section">

                                <div class="section-heading">

                                    <span class="section-heading-icon">
                                        <i class="bi bi-building"></i>
                                    </span>

                                    <div>

                                        <h3>
                                            Basic Information
                                        </h3>

                                        <p>
                                            Core manufacturer information.
                                        </p>

                                    </div>

                                </div>


                                {{-- Make Name --}}
                                <div class="field-group">

                                    <label for="name"
                                           class="form-label-custom">

                                        <span>
                                            Make Name
                                        </span>

                                        <span class="required-mark">
                                            *
                                        </span>

                                    </label>

                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $make->name) }}"
                                           class="form-control form-control-custom @error('name') is-invalid @enderror"
                                           placeholder="e.g. HP, Dell, Lenovo"
                                           maxlength="100"
                                           autocomplete="off"
                                           required>

                                    @error('name')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    <div class="field-help">

                                        <span>
                                            Enter the manufacturer or make name.
                                        </span>

                                        <span class="character-counter">
                                            <span id="nameCounter">
                                                {{ strlen(old('name', $make->name ?? '')) }}
                                            </span>
                                            / 100
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================
                                 Additional Information
                                 ================================= --}}
                            <div class="form-section">

                                <div class="section-heading">

                                    <span class="section-heading-icon">
                                        <i class="bi bi-card-text"></i>
                                    </span>

                                    <div>

                                        <h3>
                                            Additional Information
                                        </h3>

                                        <p>
                                            Optional information about this make.
                                        </p>

                                    </div>

                                </div>


                                {{-- Description --}}
                                <div class="field-group">

                                    <label for="description"
                                           class="form-label-custom">

                                        Description

                                    </label>

                                    <textarea id="description"
                                              name="description"
                                              rows="5"
                                              maxlength="500"
                                              class="form-control form-control-custom @error('description') is-invalid @enderror"
                                              placeholder="Enter an optional description for this manufacturer">{{ old('description', $make->description) }}</textarea>

                                    @error('description')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    <div class="field-help">

                                        <span>
                                            Optional. Provide additional information
                                            that may help identify the manufacturer.
                                        </span>

                                        <span class="character-counter">
                                            <span id="descriptionCounter">
                                                {{ strlen(old('description', $make->description ?? '')) }}
                                            </span>
                                            / 500
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================
                                 Availability
                                 ================================= --}}
                            <div class="form-section">

                                <div class="section-heading">

                                    <span class="section-heading-icon">
                                        <i class="bi bi-toggle-on"></i>
                                    </span>

                                    <div>

                                        <h3>
                                            Availability
                                        </h3>

                                        <p>
                                            Control whether this make can be used
                                            for new inventory registrations.
                                        </p>

                                    </div>

                                </div>


                                {{-- Status --}}
                                <div class="field-group">

                                    <label for="is_active"
                                           class="form-label-custom">

                                        <span>
                                            Status
                                        </span>

                                        <span class="required-mark">
                                            *
                                        </span>

                                    </label>

                                    <div class="status-wrapper">

                                        <select id="is_active"
                                                name="is_active"
                                                class="form-select form-select-custom status-select @error('is_active') is-invalid @enderror"
                                                required>

                                            <option value="1"
                                                {{ old('is_active', $make->is_active) == 1 ? 'selected' : '' }}>

                                                Active

                                            </option>

                                            <option value="0"
                                                {{ old('is_active', $make->is_active) == 0 ? 'selected' : '' }}>

                                                Inactive

                                            </option>

                                        </select>

                                    </div>

                                    @error('is_active')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    <div class="status-description">

                                        <i class="bi bi-info-circle me-1"></i>

                                        Active makes can be selected when registering
                                        new inventory items. Inactive makes are retained
                                        for existing records but are not intended for
                                        new registrations.

                                    </div>

                                </div>

                            </div>


                            {{-- =================================
                                 Associated Models
                                 ================================= --}}
                            @php
                                $modelCount = $make->itemModels()->count();
                            @endphp

                            <div class="association-box">

                                <div class="association-icon">

                                    <i class="bi bi-diagram-3"></i>

                                </div>

                                <div class="association-content">

                                    <div class="association-title">
                                        Associated Models
                                    </div>

                                    <p class="association-text">

                                        This make currently has

                                        <span class="model-count">
                                            {{ $modelCount }}
                                        </span>

                                        associated
                                        {{ $modelCount === 1 ? 'model' : 'models' }}.

                                    </p>

                                    @if ($modelCount > 0)

                                        <span class="association-note">

                                            <i class="bi bi-shield-check me-1"></i>

                                            Deactivating this make will prevent it from
                                            being selected for new inventory registrations.
                                            Existing models and inventory records will remain
                                            preserved.

                                        </span>

                                    @else

                                        <span class="association-note">

                                            <i class="bi bi-info-circle me-1"></i>

                                            No models are currently associated with this make.
                                            Models can be added from Model Management.

                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- =================================
                                 Form Footer
                                 ================================= --}}
                            <div class="form-footer">

                                <div class="form-footer-note">

                                    <i class="bi bi-shield-lock-fill"></i>

                                    <span>
                                        Make master data is managed by Super Admin.
                                    </span>

                                </div>


                                <div class="form-actions">

                                    <a href="{{ route('admin.makes.index') }}"
                                       class="btn btn-outline-secondary action-button">

                                        <i class="bi bi-x-lg me-1"></i>

                                        Cancel

                                    </a>


                                    <button type="submit"
                                            class="btn btn-primary action-button">

                                        <i class="bi bi-check-lg me-1"></i>

                                        Update Make

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 Side Information
                 ================================================= --}}
            <div class="col-xl-4 col-lg-5">

                <div class="management-card">

                    {{-- Management Header --}}
                    <div class="management-header">

                        <span class="management-header-icon">

                            <i class="bi bi-lightbulb"></i>

                        </span>

                        <h2>
                            Make Management
                        </h2>

                    </div>


                    {{-- Management Body --}}
                    <div class="management-body">

                        <p class="management-intro">

                            Makes are master records maintained by the
                            Super Admin. They are used when registering
                            and managing CPC inventory items.

                        </p>


                        {{-- Example --}}
                        <div class="info-item">

                            <span class="info-item-icon">
                                <i class="bi bi-diagram-3"></i>
                            </span>

                            <div>

                                <div class="info-item-title">
                                    Make & Model
                                </div>

                                <p class="info-item-text">
                                    Models are linked to their respective
                                    manufacturers.
                                </p>

                                <span class="example-value">
                                    HP → ProBook 450 G9
                                </span>

                            </div>

                        </div>


                        {{-- Active --}}
                        <div class="info-item">

                            <span class="info-item-icon">
                                <i class="bi bi-check-circle"></i>
                            </span>

                            <div>

                                <div class="info-item-title">
                                    Active Make
                                </div>

                                <p class="info-item-text">
                                    Available for selection when adding
                                    or registering new inventory items.
                                </p>

                                @if ($make->is_active)

                                    <span class="current-status active">
                                        <span class="current-status-dot"></span>
                                        Currently Active
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Inactive --}}
                        <div class="info-item">

                            <span class="info-item-icon">
                                <i class="bi bi-pause-circle"></i>
                            </span>

                            <div>

                                <div class="info-item-title">
                                    Inactive Make
                                </div>

                                <p class="info-item-text">
                                    Hidden from new inventory selections
                                    while existing records remain preserved.
                                </p>

                                @if (!$make->is_active)

                                    <span class="current-status inactive">
                                        <span class="current-status-dot"></span>
                                        Currently Inactive
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Associated Models --}}
                        <div class="info-item">

                            <span class="info-item-icon">
                                <i class="bi bi-layers"></i>
                            </span>

                            <div>

                                <div class="info-item-title">
                                    Associated Models
                                </div>

                                <p class="info-item-text">
                                    Models assigned to this make will remain
                                    linked when the make information is updated.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
         * ================================================
         * Character Counters
         * ================================================
         */

        const nameInput = document.getElementById('name');
        const nameCounter = document.getElementById('nameCounter');

        const descriptionInput = document.getElementById('description');
        const descriptionCounter = document.getElementById('descriptionCounter');


        function updateCounter(input, counter) {

            if (!input || !counter) {
                return;
            }

            counter.textContent = input.value.length;

        }


        if (nameInput && nameCounter) {

            updateCounter(nameInput, nameCounter);

            nameInput.addEventListener('input', function () {
                updateCounter(nameInput, nameCounter);
            });

        }


        if (descriptionInput && descriptionCounter) {

            updateCounter(descriptionInput, descriptionCounter);

            descriptionInput.addEventListener('input', function () {
                updateCounter(descriptionInput, descriptionCounter);
            });

        }


        /*
         * ================================================
         * Status Description
         * ================================================
         */

        const statusSelect = document.getElementById('is_active');

        if (statusSelect) {

            const statusDescription =
                document.querySelector('.status-description');

            function updateStatusDescription() {

                if (!statusDescription) {
                    return;
                }

                if (statusSelect.value === '1') {

                    statusDescription.innerHTML = `
                        <i class="bi bi-check-circle me-1"></i>
                        This make is <strong>Active</strong> and can be
                        selected when registering new inventory items.
                    `;

                } else {

                    statusDescription.innerHTML = `
                        <i class="bi bi-pause-circle me-1"></i>
                        This make is <strong>Inactive</strong> and will not
                        normally be available for new inventory registrations.
                        Existing records will remain preserved.
                    `;

                }

            }

            updateStatusDescription();

            statusSelect.addEventListener(
                'change',
                updateStatusDescription
            );

        }

    });
</script>
@endpush

