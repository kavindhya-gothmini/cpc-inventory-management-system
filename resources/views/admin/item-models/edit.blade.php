
@extends('layouts.admin')

@section('title', 'Edit Item Model')

@section('content')

<style>
    .page-header {
        margin-bottom: 28px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 6px;
    }

    .page-subtitle {
        color: #6c757d;
        margin-bottom: 0;
        font-size: 14px;
    }

    .breadcrumb-custom {
        font-size: 13px;
        margin-bottom: 18px;
    }

    .breadcrumb-custom a {
        text-decoration: none;
        color: #0d6efd;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .form-card-header {
        padding: 22px 25px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .form-card-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #212529;
    }

    .form-card-header p {
        margin: 5px 0 0;
        color: #6c757d;
        font-size: 13px;
    }

    .form-card-body {
        padding: 28px 25px;
    }

    .form-label {
        font-weight: 600;
        font-size: 14px;
        color: #343a40;
        margin-bottom: 8px;
    }

    .required {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border-color: #dee2e6;
        border-radius: 7px;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .form-text {
        font-size: 12px;
        color: #6c757d;
        margin-top: 6px;
    }

    .info-box {
        background: #f0f7ff;
        border: 1px solid #cfe2ff;
        border-radius: 8px;
        padding: 14px 16px;
        margin-bottom: 25px;
        font-size: 13px;
        color: #084298;
    }

    .info-box i {
        margin-right: 7px;
    }

    .status-box {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px 17px;
        background: #fafafa;
    }

    .status-box .form-check {
        margin: 0;
    }

    .status-box .form-check-label {
        font-size: 14px;
        font-weight: 600;
        color: #343a40;
    }

    .status-description {
        margin-top: 5px;
        margin-left: 24px;
        font-size: 12px;
        color: #6c757d;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 25px;
        margin-top: 25px;
        border-top: 1px solid #e9ecef;
    }

    .btn {
        border-radius: 7px;
        font-size: 14px;
        font-weight: 500;
        padding: 9px 18px;
    }

    .record-info {
        margin-top: 18px;
        padding: 14px 16px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        font-size: 12px;
        color: #6c757d;
    }

    .record-info strong {
        color: #495057;
    }

    @media (max-width: 767.98px) {
        .page-title {
            font-size: 24px;
        }

        .form-card-body {
            padding: 20px 17px;
        }

        .form-card-header {
            padding: 18px 17px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">

    {{-- Breadcrumb --}}
    <div class="breadcrumb-custom">
        <a href="{{ route('admin.dashboard') }}">
            Home
        </a>

        <span class="text-muted mx-1">/</span>

        <a href="{{ route('admin.item-models.index') }}">
            Item Models
        </a>

        <span class="text-muted mx-1">/</span>

        <span class="text-muted">
            Edit Model
        </span>
    </div>

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">

        <div>
            <h1 class="page-title">
                Edit Item Model
            </h1>

            <p class="page-subtitle">
                Update the make, model name, description, or active status.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.item-models.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Models
            </a>
        </div>

    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Form Card --}}
    <div class="form-card">

        <div class="form-card-header">
            <h5>
                <i class="bi bi-pencil-square me-2"></i>
                Model Information
            </h5>

            <p>
                Modify the information associated with this inventory model.
            </p>
        </div>

        <div class="form-card-body">

            {{-- Relationship Information --}}
            <div class="info-box">
                <i class="bi bi-info-circle"></i>

                <strong>Make → Model relationship:</strong>
                Each model must belong to a specific Make. When users select a
                Make while registering an inventory item, only models associated
                with that Make will be available.
            </div>

            <form action="{{ route('admin.item-models.update', $itemModel) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Make --}}
                    <div class="col-md-6">

                        <label for="make_id" class="form-label">
                            Make
                            <span class="required">*</span>
                        </label>

                        <select
                            name="make_id"
                            id="make_id"
                            class="form-select @error('make_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Make
                            </option>

                            @foreach ($makes as $make)

                                <option
                                    value="{{ $make->id }}"
                                    {{ old('make_id', $itemModel->make_id) == $make->id ? 'selected' : '' }}
                                >
                                    {{ $make->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('make_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Select the manufacturer associated with this model.
                        </div>

                    </div>

                    {{-- Model Name --}}
                    <div class="col-md-6">

                        <label for="name" class="form-label">
                            Model Name
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $itemModel->name) }}"
                            maxlength="150"
                            placeholder="e.g. ProBook 450 G10"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Enter the exact model name used for the inventory item.
                        </div>

                    </div>

                    {{-- Description --}}
                    <div class="col-12">

                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            class="form-control @error('description') is-invalid @enderror"
                            maxlength="1000"
                            placeholder="Enter an optional description for this model..."
                        >{{ old('description', $itemModel->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Optional. Maximum 1000 characters.
                        </div>

                    </div>

                    {{-- Status --}}
                    <div class="col-12">

                        <label class="form-label">
                            Model Status
                        </label>

                        <div class="status-box">

                            {{-- Ensures 0 is submitted when unchecked --}}
                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    id="is_active"
                                    value="1"
                                    class="form-check-input @error('is_active') is-invalid @enderror"
                                    {{ old('is_active', $itemModel->is_active) == 1 ? 'checked' : '' }}
                                >

                                <label
                                    for="is_active"
                                    class="form-check-label"
                                >
                                    Active Model
                                </label>

                            </div>

                            <div class="status-description">
                                Active models can be selected when registering
                                or assigning inventory items. Disable this
                                option if the model should no longer be used.
                            </div>

                        </div>

                        @error('is_active')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                {{-- Existing Record Information --}}
                <div class="record-info">

                    <div class="row g-2">

                        <div class="col-md-4">
                            <strong>Model ID:</strong>
                            {{ $itemModel->id }}
                        </div>

                        <div class="col-md-4">
                            <strong>Created:</strong>
                            {{ $itemModel->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>

                        <div class="col-md-4">
                            <strong>Last Updated:</strong>
                            {{ $itemModel->updated_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>

                    </div>

                </div>

                {{-- Form Actions --}}
                <div class="form-actions">

                    <a
                        href="{{ route('admin.item-models.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-x-lg me-1"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Update Model
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
