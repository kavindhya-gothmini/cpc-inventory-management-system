
@extends('layouts.admin')

@section('title', 'Add Item Model')

@section('content')

<style>
    .item-model-create-page {
        padding: 24px;
    }

    .page-header {
        margin-bottom: 24px;
    }

    .breadcrumb-area {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #7b8794;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .breadcrumb-area i {
        font-size: 11px;
    }

    .page-heading-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .page-description {
        margin: 7px 0 0;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 14px;
        border: 1px solid #d9dee7;
        border-radius: 7px;
        background: #fff;
        color: #495057;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .back-button:hover {
        background: #f8f9fa;
        color: #212529;
        border-color: #c5cad2;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }

    .form-card-header h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #1f2937;
    }

    .form-card-header p {
        margin: 5px 0 0;
        color: #8a94a6;
        font-size: 12px;
    }

    .form-card-body {
        padding: 26px 24px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 20px;
    }

    .section-number {
        width: 27px;
        height: 27px;
        border-radius: 7px;
        background: #eef4ff;
        color: #0d6efd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }

    .section-heading h6 {
        margin: 0;
        color: #1f2937;
        font-size: 14px;
        font-weight: 700;
    }

    .form-label {
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .required-mark {
        color: #dc3545;
        margin-left: 2px;
    }

    .form-control,
    .form-select {
        min-height: 42px;
        border-color: #d9dee7;
        border-radius: 7px;
        font-size: 13px;
        color: #374151;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.10);
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .form-help {
        margin-top: 6px;
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.5;
    }

    .invalid-feedback {
        font-size: 11px;
    }

    .form-check-card {
        display: flex;
        align-items: center;
        gap: 11px;
        min-height: 42px;
        padding: 10px 13px;
        border: 1px solid #d9dee7;
        border-radius: 7px;
        background: #fff;
    }

    .form-check-card .form-check-input {
        margin: 0;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .form-check-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .form-check-title {
        color: #374151;
        font-size: 13px;
        font-weight: 600;
    }

    .form-check-description {
        color: #8a94a6;
        font-size: 11px;
    }

    .form-actions {
        padding: 18px 24px;
        border-top: 1px solid #e5e7eb;
        background: #fafbfc;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    .btn-cancel {
        padding: 9px 16px;
        border: 1px solid #d9dee7;
        border-radius: 7px;
        background: #fff;
        color: #495057;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: #f8f9fa;
        color: #212529;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 17px;
        border: 1px solid #0d6efd;
        border-radius: 7px;
        background: #0d6efd;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-save:hover {
        background: #0b5ed7;
        border-color: #0a58ca;
    }

    .alert-message {
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 13px;
    }

    .info-box {
        display: flex;
        gap: 11px;
        padding: 13px 15px;
        margin-bottom: 24px;
        border: 1px solid #dbeafe;
        border-radius: 8px;
        background: #f8fbff;
    }

    .info-box i {
        color: #0d6efd;
        font-size: 17px;
        margin-top: 1px;
    }

    .info-box-text {
        color: #526071;
        font-size: 12px;
        line-height: 1.6;
    }

    .info-box-text strong {
        color: #374151;
    }

    @media (max-width: 767px) {
        .item-model-create-page {
            padding: 16px;
        }

        .page-heading-row {
            flex-direction: column;
        }

        .back-button {
            width: 100%;
            justify-content: center;
        }

        .page-title {
            font-size: 24px;
        }

        .form-card-body {
            padding: 20px 16px;
        }

        .form-card-header {
            padding: 18px 16px;
        }

        .form-actions {
            padding: 16px;
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-save {
            width: 100%;
            justify-content: center;
            text-align: center;
        }
    }
</style>


<div class="item-model-create-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="page-header">

        <div class="breadcrumb-area">

            <span>Home</span>

            <i class="bi bi-chevron-right"></i>

            <span>Inventory</span>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('admin.item-models.index') }}"
               class="text-decoration-none text-muted">
                Item Models
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>Add Model</span>

        </div>


        <div class="page-heading-row">

            <div>

                <h1 class="page-title">
                    Add Item Model
                </h1>

                <p class="page-description">
                    Add a specific equipment model and associate it with its manufacturer.
                </p>

            </div>


            <div>

                <a href="{{ route('admin.item-models.index') }}"
                   class="back-button">

                    <i class="bi bi-arrow-left"></i>

                    Back to Item Models

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show alert-message"
             role="alert">

            <div class="fw-semibold mb-1">
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-3">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         INFORMATION BOX
    ========================================================== --}}

    <div class="info-box">

        <i class="bi bi-info-circle-fill"></i>

        <div class="info-box-text">

            <strong>Model and Make relationship:</strong>

            Select the manufacturer first, such as HP, Dell, Lenovo,
            or another registered make. Only the selected make will be
            associated with this model.

        </div>

    </div>


    {{-- =========================================================
         FORM
    ========================================================== --}}

    <div class="form-card">

        <div class="form-card-header">

            <h5>
                Model Information
            </h5>

            <p>
                Enter the details required to register the equipment model.
            </p>

        </div>


        <form action="{{ route('admin.item-models.store') }}"
              method="POST">

            @csrf


            <div class="form-card-body">

                {{-- =================================================
                     BASIC MODEL INFORMATION
                ================================================== --}}

                <div class="section-heading">

                    <span class="section-number">
                        01
                    </span>

                    <h6>
                        Basic Model Information
                    </h6>

                </div>


                <div class="row g-4">

                    {{-- MAKE --}}
                    <div class="col-md-6">

                        <label for="make_id"
                               class="form-label">

                            Make

                            <span class="required-mark">
                                *
                            </span>

                        </label>


                        <select name="make_id"
                                id="make_id"
                                class="form-select @error('make_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Make
                            </option>


                            @foreach($makes as $make)

                                <option value="{{ $make->id }}"
                                    {{ old('make_id') == $make->id ? 'selected' : '' }}>

                                    {{ $make->name }}

                                </option>

                            @endforeach

                        </select>


                        @error('make_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="form-help">

                            Select the manufacturer associated with this model.

                        </div>

                    </div>


                    {{-- MODEL NAME --}}
                    <div class="col-md-6">

                        <label for="name"
                               class="form-label">

                            Model Name

                            <span class="required-mark">
                                *
                            </span>

                        </label>


                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="e.g. ProBook 450 G10"
                               maxlength="150"
                               required>


                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="form-help">

                            Enter the exact equipment model name or number.

                        </div>

                    </div>


                    {{-- DESCRIPTION --}}
                    <div class="col-12">

                        <label for="description"
                               class="form-label">

                            Description

                        </label>


                        <textarea name="description"
                                  id="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Enter any additional information about this model..."
                                  maxlength="1000">{{ old('description') }}</textarea>


                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="form-help">

                            Optional. Use this field for specifications,
                            notes, or other useful model information.

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     STATUS
                ================================================== --}}

                <div class="section-heading mt-5">

                    <span class="section-number">
                        02
                    </span>

                    <h6>
                        Model Status
                    </h6>

                </div>


                <div class="row">

                    <div class="col-md-6">

                        <div class="form-check-card">

                            <input type="hidden"
                                   name="is_active"
                                   value="0">


                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   class="form-check-input"
                                   {{ old('is_active', 1) ? 'checked' : '' }}>


                            <div class="form-check-content">

                                <label for="is_active"
                                       class="form-check-title">

                                    Active Model

                                </label>

                                <span class="form-check-description">

                                    Active models can be selected when registering inventory items.

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 FORM ACTIONS
            ====================================================== --}}

            <div class="form-actions">

                <a href="{{ route('admin.item-models.index') }}"
                   class="btn-cancel">

                    Cancel

                </a>


                <button type="submit"
                        class="btn-save">

                    <i class="bi bi-check-lg"></i>

                    Save Model

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

