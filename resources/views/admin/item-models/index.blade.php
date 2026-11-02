
@extends('layouts.admin')

@section('title', 'Item Models')

@section('content')

<style>
    .item-models-page {
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
        letter-spacing: -0.3px;
    }

    .page-description {
        margin: 7px 0 0;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
    }

    .page-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .btn-add-model {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 7px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        color: #fff;
        background: #0d6efd;
        border: 1px solid #0d6efd;
        transition: all 0.2s ease;
    }

    .btn-add-model:hover {
        color: #fff;
        background: #0b5ed7;
        border-color: #0a58ca;
    }

    .stats-row {
        margin-bottom: 24px;
    }

    .stat-card {
        height: 100%;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .stat-label {
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .stat-number {
        color: #1f2937;
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }

    .stat-description {
        color: #9ca3af;
        font-size: 12px;
        margin-top: 7px;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef4ff;
        color: #0d6efd;
        font-size: 21px;
    }

    .stat-icon.success {
        background: #ecfdf3;
        color: #198754;
    }

    .stat-icon.secondary {
        background: #f3f4f6;
        color: #6b7280;
    }

    .models-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .models-card-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .models-card-title {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #1f2937;
    }

    .models-card-subtitle {
        margin: 4px 0 0;
        font-size: 12px;
        color: #8a94a6;
    }

    .search-area {
        min-width: 280px;
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        z-index: 2;
    }

    .search-input {
        width: 100%;
        height: 40px;
        padding: 8px 13px 8px 38px;
        border: 1px solid #d9dee7;
        border-radius: 7px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .search-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.10);
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .models-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .models-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        padding: 13px 16px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .models-table tbody td {
        padding: 15px 16px;
        border-bottom: 1px solid #edf0f4;
        color: #374151;
        font-size: 13px;
        vertical-align: middle;
    }

    .models-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .models-table tbody tr:hover {
        background: #fafcff;
    }

    .model-number {
        color: #8a94a6;
        font-weight: 600;
        width: 55px;
    }

    .model-name {
        color: #1f2937;
        font-weight: 700;
    }

    .make-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .description-text {
        max-width: 280px;
        color: #6b7280;
        line-height: 1.5;
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

    .status-badge.active {
        color: #146c43;
        background: #d1e7dd;
    }

    .status-badge.inactive {
        color: #6c757d;
        background: #e9ecef;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        background: #fff;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .action-btn.view {
        color: #0d6efd;
    }

    .action-btn.view:hover {
        color: #fff;
        background: #0d6efd;
        border-color: #0d6efd;
    }

    .action-btn.edit {
        color: #6f42c1;
    }

    .action-btn.edit:hover {
        color: #fff;
        background: #6f42c1;
        border-color: #6f42c1;
    }

    .action-btn.delete {
        color: #dc3545;
    }

    .action-btn.delete:hover {
        color: #fff;
        background: #dc3545;
        border-color: #dc3545;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 18px;
        border-radius: 50%;
        background: #f3f6fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #8b96a8;
        font-size: 32px;
    }

    .empty-state h5 {
        margin: 0 0 8px;
        color: #1f2937;
        font-size: 17px;
        font-weight: 700;
    }

    .empty-state p {
        margin: 0 auto 20px;
        max-width: 460px;
        color: #7b8794;
        font-size: 13px;
        line-height: 1.6;
    }

    .table-footer {
        padding: 15px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .record-count {
        color: #6b7280;
        font-size: 12px;
    }

    .record-count strong {
        color: #374151;
    }

    .pagination-wrapper nav {
        margin: 0;
    }

    .pagination-wrapper .pagination {
        margin: 0;
    }

    .pagination-wrapper .page-link {
        font-size: 12px;
        padding: 6px 10px;
    }

    .alert-message {
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 13px;
    }

    @media (max-width: 767px) {
        .item-models-page {
            padding: 16px;
        }

        .page-heading-row {
            flex-direction: column;
        }

        .page-actions {
            width: 100%;
        }

        .btn-add-model {
            width: 100%;
            justify-content: center;
        }

        .search-area {
            width: 100%;
            min-width: 0;
        }

        .models-card-header {
            align-items: stretch;
        }

        .table-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .page-title {
            font-size: 24px;
        }
    }
</style>


<div class="item-models-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="page-header">

        <div class="breadcrumb-area">
            <span>Home</span>
            <i class="bi bi-chevron-right"></i>
            <span>Inventory</span>
            <i class="bi bi-chevron-right"></i>
            <span>Item Models</span>
        </div>


        <div class="page-heading-row">

            <div>

                <h1 class="page-title">
                    Item Models
                </h1>

                <p class="page-description">
                    Manage the IT equipment models associated with each registered make.
                    Models are used when registering CPC inventory assets.
                </p>

            </div>


            @if(auth()->user()->role === 'super_admin')

                <div class="page-actions">

                    <a href="{{ route('admin.item-models.create') }}"
                       class="btn-add-model">

                        <i class="bi bi-plus-lg"></i>

                        Add Model

                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         SUCCESS / ERROR MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show alert-message"
             role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show alert-message"
             role="alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    <div class="row g-3 stats-row">

        {{-- TOTAL --}}
        <div class="col-xl-4 col-md-6">

            <div class="stat-card">

                <div>

                    <div class="stat-label">
                        Total Models
                    </div>

                    <div class="stat-number">
                        {{ $itemModels->total() }}
                    </div>

                    <div class="stat-description">
                        Registered equipment models
                    </div>

                </div>

                <div class="stat-icon">

                    <i class="bi bi-pc-display-horizontal"></i>

                </div>

            </div>

        </div>


        {{-- ACTIVE --}}
        <div class="col-xl-4 col-md-6">

            <div class="stat-card">

                <div>

                    <div class="stat-label">
                        Active Models
                    </div>

                    <div class="stat-number">
                        {{ $itemModels->where('is_active', true)->count() }}
                    </div>

                    <div class="stat-description">
                        Currently available for inventory
                    </div>

                </div>

                <div class="stat-icon success">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

            </div>

        </div>


        {{-- INACTIVE --}}
        <div class="col-xl-4 col-md-6">

            <div class="stat-card">

                <div>

                    <div class="stat-label">
                        Inactive Models
                    </div>

                    <div class="stat-number">
                        {{ $itemModels->where('is_active', false)->count() }}
                    </div>

                    <div class="stat-description">
                        Deactivated model records
                    </div>

                </div>

                <div class="stat-icon secondary">

                    <i class="bi bi-archive-fill"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MODELS TABLE
    ========================================================== --}}

    <div class="models-card">

        <div class="models-card-header">

            <div>

                <h5 class="models-card-title">
                    Registered Item Models
                </h5>

                <p class="models-card-subtitle">
                    Models are linked to their corresponding equipment make.
                </p>

            </div>


            <div class="search-area">

                <div class="search-wrapper">

                    <i class="bi bi-search"></i>

                    <input type="text"
                           id="modelSearch"
                           class="search-input"
                           placeholder="Search models, makes...">

                </div>

            </div>

        </div>


        @if($itemModels->count())

            <div class="table-container">

                <table class="models-table"
                       id="itemModelsTable">

                    <thead>

                        <tr>

                            <th style="width: 60px;">
                                #
                            </th>

                            <th>
                                Model
                            </th>

                            <th>
                                Make
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="width: 150px;">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach($itemModels as $index => $itemModel)

                        <tr>

                            {{-- NUMBER --}}
                            <td class="model-number">

                                {{ $itemModels->firstItem() + $index }}

                            </td>


                            {{-- MODEL --}}
                            <td>

                                <div class="model-name">

                                    {{ $itemModel->name }}

                                </div>

                            </td>


                            {{-- MAKE --}}
                            <td>

                                <span class="make-badge">

                                    <i class="bi bi-tag-fill me-1"></i>

                                    {{ $itemModel->make->name ?? '—' }}

                                </span>

                            </td>


                            {{-- DESCRIPTION --}}
                            <td>

                                <div class="description-text">

                                    @if($itemModel->description)

                                        {{ $itemModel->description }}

                                    @else

                                        <span class="text-muted">
                                            No description provided
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if($itemModel->is_active)

                                    <span class="status-badge active">

                                        <span class="status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="status-badge inactive">

                                        <span class="status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- VIEW --}}
                                    <a href="{{ route('admin.item-models.show', $itemModel) }}"
                                       class="action-btn view"
                                       title="View Model">

                                        <i class="bi bi-eye"></i>

                                    </a>


                                    {{-- EDIT --}}
                                    @if(auth()->user()->role === 'super_admin')

                                        <a href="{{ route('admin.item-models.edit', $itemModel) }}"
                                           class="action-btn edit"
                                           title="Edit Model">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>


                                        {{-- DELETE --}}
                                        <form action="{{ route('admin.item-models.destroy', $itemModel) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this item model?');">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="action-btn delete"
                                                    title="Delete Model">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 TABLE FOOTER / PAGINATION
            ================================================== --}}

            <div class="table-footer">

                <div class="record-count">

                    Showing

                    <strong>
                        {{ $itemModels->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $itemModels->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $itemModels->total() }}
                    </strong>

                    item models

                </div>


                <div class="pagination-wrapper">

                    {{ $itemModels->links() }}

                </div>

            </div>


        @else

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}

            <div class="empty-state">

                <div class="empty-icon">

                    <i class="bi bi-pc-display-horizontal"></i>

                </div>


                <h5>
                    No Item Models Found
                </h5>


                <p>
                    No equipment models have been registered yet.
                    Add a model and associate it with an existing make
                    such as HP, Dell, Lenovo, or another supported manufacturer.
                </p>


                @if(auth()->user()->role === 'super_admin')

                    <a href="{{ route('admin.item-models.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Model

                    </a>

                @endif

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
     SEARCH SCRIPT
============================================================== --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('modelSearch');
    const table = document.getElementById('itemModelsTable');

    if (!searchInput || !table) {
        return;
    }

    searchInput.addEventListener('input', function () {

        const searchValue = this.value
            .toLowerCase()
            .trim();

        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(function (row) {

            const rowText = row.textContent
                .toLowerCase();

            row.style.display =
                rowText.includes(searchValue)
                    ? ''
                    : 'none';

        });

    });

});
</script>

@endpush

@endsection

