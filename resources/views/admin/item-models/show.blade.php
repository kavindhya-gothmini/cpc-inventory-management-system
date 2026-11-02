
@extends('layouts.admin')

@section('title', 'Item Model Details')

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

    .details-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .details-card-header {
        padding: 22px 25px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .details-card-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #212529;
    }

    .details-card-header p {
        margin: 5px 0 0;
        color: #6c757d;
        font-size: 13px;
    }

    .details-card-body {
        padding: 28px 25px;
    }

    .detail-item {
        padding: 17px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:last-child {
        border-bottom: 0;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 6px;
    }

    .detail-value {
        font-size: 15px;
        font-weight: 500;
        color: #212529;
        word-break: break-word;
    }

    .model-name {
        font-size: 22px;
        font-weight: 700;
        color: #212529;
    }

    .make-name {
        color: #0d6efd;
        font-weight: 600;
    }

    .description-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px;
        min-height: 100px;
        color: #495057;
        font-size: 14px;
        line-height: 1.6;
        white-space: pre-line;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-inactive {
        background: #f8d7da;
        color: #842029;
    }

    .relationship-box {
        background: #f0f7ff;
        border: 1px solid #cfe2ff;
        border-radius: 9px;
        padding: 18px;
    }

    .relationship-title {
        font-size: 13px;
        font-weight: 600;
        color: #084298;
        margin-bottom: 10px;
    }

    .relationship-path {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .relationship-item {
        background: #fff;
        border: 1px solid #cfe2ff;
        border-radius: 7px;
        padding: 8px 13px;
        font-size: 14px;
        font-weight: 600;
        color: #212529;
    }

    .relationship-arrow {
        color: #0d6efd;
        font-size: 16px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn {
        border-radius: 7px;
        font-size: 14px;
        font-weight: 500;
        padding: 9px 17px;
    }

    .metadata {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }

    .empty-description {
        color: #adb5bd;
        font-style: italic;
    }

    @media (max-width: 767.98px) {
        .page-title {
            font-size: 24px;
        }

        .details-card-body {
            padding: 20px 17px;
        }

        .details-card-header {
            padding: 18px 17px;
        }

        .action-buttons {
            width: 100%;
        }

        .action-buttons .btn {
            flex: 1;
        }

        .model-name {
            font-size: 20px;
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
            Details
        </span>

    </div>

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">

        <div>
            <h1 class="page-title">
                Item Model Details
            </h1>

            <p class="page-subtitle">
                View the complete information for this inventory model.
            </p>
        </div>

        <div class="action-buttons">

            <a
                href="{{ route('admin.item-models.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

            @if(auth()->user()->role === 'super_admin')

                <a
                    href="{{ route('admin.item-models.edit', $itemModel) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Edit Model
                </a>

            @endif

        </div>

    </div>

    {{-- Main Details Card --}}
    <div class="details-card">

        <div class="details-card-header">

            <div>

                <h5>
                    <i class="bi bi-box-seam me-2"></i>
                    Model Information
                </h5>

                <p>
                    Master data used for CPC inventory item registration.
                </p>

            </div>

            <div>

                @if($itemModel->is_active)

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

        <div class="details-card-body">

            {{-- Model / Make --}}
            <div class="row">

                <div class="col-md-6">

                    <div class="detail-item">

                        <div class="detail-label">
                            Model Name
                        </div>

                        <div class="model-name">
                            {{ $itemModel->name }}
                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="detail-item">

                        <div class="detail-label">
                            Make
                        </div>

                        <div class="detail-value make-name">

                            <i class="bi bi-building me-1"></i>

                            {{ $itemModel->make->name ?? '—' }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- Relationship --}}
            <div class="detail-item">

                <div class="detail-label">
                    Make / Model Relationship
                </div>

                <div class="relationship-box">

                    <div class="relationship-title">
                        <i class="bi bi-diagram-3 me-1"></i>
                        Associated Inventory Master Data
                    </div>

                    <div class="relationship-path">

                        <div class="relationship-item">

                            <i class="bi bi-building me-1"></i>

                            {{ $itemModel->make->name ?? 'Unknown Make' }}

                        </div>

                        <div class="relationship-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>

                        <div class="relationship-item">

                            <i class="bi bi-box-seam me-1"></i>

                            {{ $itemModel->name }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- Description --}}
            <div class="detail-item">

                <div class="detail-label">
                    Description
                </div>

                <div class="description-box">

                    @if($itemModel->description)

                        {{ $itemModel->description }}

                    @else

                        <span class="empty-description">
                            No description has been added for this model.
                        </span>

                    @endif

                </div>

            </div>

            {{-- Status --}}
            <div class="detail-item">

                <div class="detail-label">
                    Status
                </div>

                <div class="detail-value">

                    @if($itemModel->is_active)

                        <span class="status-badge status-active">
                            <i class="bi bi-check-circle-fill"></i>
                            Active Model
                        </span>

                    @else

                        <span class="status-badge status-inactive">
                            <i class="bi bi-x-circle-fill"></i>
                            Inactive Model
                        </span>

                    @endif

                </div>

            </div>

            {{-- Record Metadata --}}
            <div class="detail-item">

                <div class="detail-label">
                    Record Information
                </div>

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="detail-value">
                            {{ $itemModel->id }}
                        </div>

                        <div class="metadata">
                            Model ID
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="detail-value">

                            {{ $itemModel->created_at?->format('d M Y, h:i A') ?? '—' }}

                        </div>

                        <div class="metadata">
                            Created At
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="detail-value">

                            {{ $itemModel->updated_at?->format('d M Y, h:i A') ?? '—' }}

                        </div>

                        <div class="metadata">
                            Last Updated
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Bottom Actions --}}
    @if(auth()->user()->role === 'super_admin')

        <div class="d-flex justify-content-end gap-2 flex-wrap">

            <a
                href="{{ route('admin.item-models.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-list me-1"></i>
                All Models
            </a>

            <a
                href="{{ route('admin.item-models.edit', $itemModel) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit Model
            </a>

        </div>

    @endif

</div>

@endsection


