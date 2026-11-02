@extends('layouts.admin')

@section('title', 'Temporary Item Management | CPC Inventory Management System')

@section('page-heading', 'Temporary Item Management')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        ALERT MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4"
             role="alert">

            <div class="d-flex align-items-start">

                <i class="bi bi-check-circle-fill fs-5 me-3"></i>

                <div class="flex-grow-1">
                    <div class="fw-semibold mb-1">
                        Operation Completed
                    </div>

                    <div>
                        {{ session('success') }}
                    </div>
                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>

        </div>
    @endif


    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4"
             role="alert">

            <div class="d-flex align-items-start">

                <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>

                <div class="flex-grow-1">

                    <div class="fw-semibold mb-2">
                        Please review the following errors
                    </div>

                    <ul class="mb-0 ps-3">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>

        </div>
    @endif


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="page-header-card mb-4">

        <div class="d-flex flex-column flex-lg-row
                    align-items-lg-center
                    justify-content-between
                    gap-3">

            <div>

                <div class="d-flex align-items-center gap-2 mb-2">

                    <span class="page-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>

                    <div>

                        <h4 class="fw-bold mb-0">
                            Temporary Item Management
                        </h4>

                        <div class="d-flex align-items-center gap-2 mt-1">

                            <span class="status-dot"></span>

                            <small class="text-muted">
                                Temporary Inventory Register
                            </small>

                        </div>

                    </div>

                </div>

                <p class="text-muted mb-0 ms-lg-5">
                    Register, monitor and manage temporary items used for
                    events, temporary assignments and operational requirements.
                </p>

            </div>


            <div class="d-flex flex-column flex-sm-row gap-2">

                <a href="{{ route('admin.inventory.temporary-items.create') }}"
                   class="btn btn-primary px-4">

                    <i class="bi bi-plus-circle me-1"></i>
                    Add Temporary Item

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <div class="stat-label">
                            Total Items
                        </div>

                        <div class="stat-value">
                            {{ \App\Models\TemporaryItem::count() }}
                        </div>

                        <div class="stat-description">
                            Registered temporary items
                        </div>

                    </div>

                    <div class="stat-icon stat-icon-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Available --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <div class="stat-label">
                            Available
                        </div>

                        <div class="stat-value text-success">
                            {{ \App\Models\TemporaryItem::where('status', 'available')->count() }}
                        </div>

                        <div class="stat-description">
                            Ready for temporary issue
                        </div>

                    </div>

                    <div class="stat-icon stat-icon-success">
                        <i class="bi bi-check-circle"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Issued --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <div class="stat-label">
                            Issued
                        </div>

                        <div class="stat-value text-warning">
                            {{ \App\Models\TemporaryItem::where('status', 'issued')->count() }}
                        </div>

                        <div class="stat-description">
                            Currently issued items
                        </div>

                    </div>

                    <div class="stat-icon stat-icon-warning">
                        <i class="bi bi-arrow-up-right-circle"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Inactive --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <div class="stat-label">
                            Inactive
                        </div>

                        <div class="stat-value text-secondary">
                            {{ \App\Models\TemporaryItem::where('status', 'inactive')->count() }}
                        </div>

                        <div class="stat-description">
                            No longer available
                        </div>

                    </div>

                    <div class="stat-icon stat-icon-secondary">
                        <i class="bi bi-slash-circle"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SEARCH / FILTER BAR
    ========================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-3 p-md-4">

            <form method="GET"
                  action="{{ route('admin.inventory.temporary-items.index') }}"
                  id="temporaryItemSearchForm">

                <div class="row g-2">

                    <div class="col-12 col-lg">

                        <label for="search"
                               class="form-label fw-semibold small mb-2">

                            Search Temporary Items

                        </label>

                        <div class="search-wrapper">

                            <i class="bi bi-search search-icon"></i>

                            <input type="text"
                                   id="search"
                                   name="search"
                                   value="{{ $search }}"
                                   class="form-control search-input"
                                   placeholder="Search by item code, item type or description..."
                                   autocomplete="off">

                            @if($search !== '')

                                <a href="{{ route('admin.inventory.temporary-items.index') }}"
                                   class="clear-search"
                                   title="Clear search">

                                    <i class="bi bi-x-circle-fill"></i>

                                </a>

                            @endif

                        </div>

                    </div>


                    <div class="col-12 col-lg-auto d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-primary search-button">

                            <i class="bi bi-search me-1"></i>
                            Search

                        </button>

                    </div>

                </div>


                @if($search !== '')

                    <div class="search-result-info mt-3">

                        <i class="bi bi-funnel me-1"></i>

                        Showing results for:

                        <strong>
                            "{{ $search }}"
                        </strong>

                        <span class="text-muted ms-1">
                            • {{ $items->total() }} result(s)
                        </span>

                    </div>

                @endif

            </form>

        </div>

    </div>


    {{-- =========================================================
        ITEMS TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        {{-- Table Header --}}
        <div class="card-header bg-white border-bottom py-3 px-3 px-md-4">

            <div class="d-flex flex-column flex-sm-row
                        align-items-sm-center
                        justify-content-between
                        gap-2">

                <div>

                    <div class="d-flex align-items-center gap-2">

                        <div class="section-icon">
                            <i class="bi bi-list-ul"></i>
                        </div>

                        <div>

                            <h5 class="fw-bold mb-0">
                                Registered Temporary Items
                            </h5>

                            <small class="text-muted">
                                Temporary items available for issue management
                            </small>

                        </div>

                    </div>

                </div>


                <div class="table-count">

                    <i class="bi bi-box me-1"></i>

                    {{ $items->total() }}

                    {{ \Illuminate\Support\Str::plural('item', $items->total()) }}

                </div>

            </div>

        </div>


        {{-- Table Body --}}
        <div class="card-body p-0">

            @if($items->count() > 0)

                <div class="table-responsive">

                    <table class="table temporary-items-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-3 ps-md-4 number-column">
                                    #
                                </th>

                                <th>
                                    Item
                                </th>

                                <th>
                                    Type
                                </th>

                                <th class="description-column">
                                    Description
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center">
                                    Issues
                                </th>

                                <th class="text-end pe-3 pe-md-4 action-column">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($items as $item)

                                <tr>

                                    {{-- Number --}}
                                    <td class="ps-3 ps-md-4">

                                        <span class="row-number">
                                            {{ $items->firstItem() + $loop->index }}
                                        </span>

                                    </td>


                                    {{-- Item Code --}}
                                    <td>

                                        <div class="item-identity">

                                            <div class="item-icon">
                                                <i class="bi bi-box-seam"></i>
                                            </div>

                                            <div class="item-code-wrapper">

                                                <span class="item-code">
                                                    {{ $item->item_code }}
                                                </span>

                                                <small class="text-muted d-block">
                                                    Temporary Item
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Item Type --}}
                                    <td>

                                        <span class="item-type">
                                            {{ $item->item_type }}
                                        </span>

                                    </td>


                                    {{-- Description --}}
                                    <td class="description-column">

                                        @if($item->description)

                                            <span class="description-text"
                                                  title="{{ $item->description }}">

                                                {{ \Illuminate\Support\Str::limit($item->description, 70) }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($item->status === 'available')

                                            <span class="status-badge status-badge-success">

                                                <span class="status-badge-dot"></span>

                                                Available

                                            </span>

                                        @elseif($item->status === 'issued')

                                            <span class="status-badge status-badge-warning">

                                                <span class="status-badge-dot"></span>

                                                Issued

                                            </span>

                                        @else

                                            <span class="status-badge status-badge-secondary">

                                                <span class="status-badge-dot"></span>

                                                Inactive

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Issues --}}
                                    <td class="text-center">

                                        <span class="issue-count-badge">

                                            <i class="bi bi-arrow-left-right me-1"></i>

                                            {{ $item->issues_count }}

                                        </span>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-3 pe-md-4">

                                        <div class="action-buttons">

                                            {{-- View --}}
                                            <a href="{{ route('admin.inventory.temporary-items.show', $item) }}"
                                               class="action-btn action-btn-view"
                                               title="View Temporary Item"
                                               aria-label="View Temporary Item">

                                                <i class="bi bi-eye"></i>

                                            </a>


                                            {{-- Edit --}}
                                            <a href="{{ route('admin.inventory.temporary-items.edit', $item) }}"
                                               class="action-btn action-btn-edit"
                                               title="Edit Temporary Item"
                                               aria-label="Edit Temporary Item">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>


                                            {{-- Delete / Deactivate --}}
                                            <form method="POST"
                                                  action="{{ route('admin.inventory.temporary-items.destroy', $item) }}"
                                                  class="d-inline delete-item-form">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="action-btn action-btn-delete"
                                                        title="Delete / Deactivate"
                                                        aria-label="Delete / Deactivate">

                                                    <i class="bi bi-trash3"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                    MOBILE ITEM CARDS
                ================================================== --}}
                <div class="mobile-items-list">

                    @foreach($items as $item)

                        <div class="mobile-item-card">

                            <div class="mobile-item-header">

                                <div class="item-identity">

                                    <div class="item-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </div>

                                    <div>

                                        <div class="item-code">
                                            {{ $item->item_code }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $item->item_type }}
                                        </small>

                                    </div>

                                </div>


                                @if($item->status === 'available')

                                    <span class="status-badge status-badge-success">
                                        <span class="status-badge-dot"></span>
                                        Available
                                    </span>

                                @elseif($item->status === 'issued')

                                    <span class="status-badge status-badge-warning">
                                        <span class="status-badge-dot"></span>
                                        Issued
                                    </span>

                                @else

                                    <span class="status-badge status-badge-secondary">
                                        <span class="status-badge-dot"></span>
                                        Inactive
                                    </span>

                                @endif

                            </div>


                            <div class="mobile-item-details">

                                <div>

                                    <span class="mobile-label">
                                        Description
                                    </span>

                                    <span class="mobile-value">

                                        {{ $item->description
                                            ? \Illuminate\Support\Str::limit($item->description, 100)
                                            : 'No description provided.' }}

                                    </span>

                                </div>


                                <div>

                                    <span class="mobile-label">
                                        Issue Records
                                    </span>

                                    <span class="mobile-value">

                                        <span class="issue-count-badge">
                                            <i class="bi bi-arrow-left-right me-1"></i>
                                            {{ $item->issues_count }}
                                        </span>

                                    </span>

                                </div>

                            </div>


                            <div class="mobile-item-actions">

                                <a href="{{ route('admin.inventory.temporary-items.show', $item) }}"
                                   class="btn btn-outline-primary">

                                    <i class="bi bi-eye me-1"></i>
                                    View

                                </a>

                                <a href="{{ route('admin.inventory.temporary-items.edit', $item) }}"
                                   class="btn btn-outline-secondary">

                                    <i class="bi bi-pencil me-1"></i>
                                    Edit

                                </a>

                                <form method="POST"
                                      action="{{ route('admin.inventory.temporary-items.destroy', $item) }}"
                                      class="delete-item-form flex-grow-1">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-outline-danger w-100">

                                        <i class="bi bi-trash3 me-1"></i>
                                        Delete

                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>


            @else

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}

                <div class="empty-state">

                    <div class="empty-state-icon">

                        @if($search !== '')

                            <i class="bi bi-search"></i>

                        @else

                            <i class="bi bi-box-seam"></i>

                        @endif

                    </div>


                    @if($search !== '')

                        <h5 class="fw-bold mb-2">
                            No Temporary Items Found
                        </h5>

                        <p class="text-muted mb-4">

                            No temporary items matched
                            <strong>"{{ $search }}"</strong>.

                            Try another search term.

                        </p>

                        <a href="{{ route('admin.inventory.temporary-items.index') }}"
                           class="btn btn-outline-primary px-4">

                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Clear Search

                        </a>

                    @else

                        <h5 class="fw-bold mb-2">
                            No Temporary Items Registered
                        </h5>

                        <p class="text-muted mb-4">

                            There are currently no temporary items in the register.
                            Start by adding the first temporary item.

                        </p>

                        <a href="{{ route('admin.inventory.temporary-items.create') }}"
                           class="btn btn-primary px-4">

                            <i class="bi bi-plus-circle me-1"></i>
                            Add Temporary Item

                        </a>

                    @endif

                </div>

            @endif

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}

        @if($items->hasPages())

            <div class="card-footer bg-white border-top py-3 px-3 px-md-4">

                <div class="d-flex flex-column flex-md-row
                            align-items-center
                            justify-content-between
                            gap-3">

                    <small class="text-muted">

                        Showing
                        <strong>{{ $items->firstItem() }}</strong>
                        to
                        <strong>{{ $items->lastItem() }}</strong>
                        of
                        <strong>{{ $items->total() }}</strong>
                        items

                    </small>

                    <div>
                        {{ $items->links() }}
                    </div>

                </div>

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
    PAGE STYLES
============================================================= --}}
@push('styles')

<style>

    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .page-header-card {

        background: #ffffff;

        border: 1px solid #edf0f4;

        border-radius: 16px;

        padding: 22px 24px;

        box-shadow: 0 2px 10px rgba(16, 24, 40, 0.04);

    }


    .page-icon {

        width: 44px;
        height: 44px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 12px;

        background: rgba(13, 110, 253, 0.10);

        color: #0d6efd;

        font-size: 1.2rem;

    }


    .status-dot {

        width: 7px;
        height: 7px;

        display: inline-block;

        border-radius: 50%;

        background: #198754;

    }


    /* =========================================================
       STATISTICS
    ========================================================== */

    .stat-card {

        position: relative;

        background: #ffffff;

        border: 1px solid #edf0f4;

        border-radius: 14px;

        padding: 20px;

        box-shadow: 0 2px 10px rgba(16, 24, 40, 0.04);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;

    }


    .stat-card:hover {

        transform: translateY(-2px);

        box-shadow: 0 6px 18px rgba(16, 24, 40, 0.07);

    }


    .stat-label {

        color: #667085;

        font-size: 0.82rem;

        font-weight: 500;

        margin-bottom: 5px;

    }


    .stat-value {

        color: #344054;

        font-size: 1.75rem;

        font-weight: 700;

        line-height: 1.2;

    }


    .stat-description {

        color: #98a2b3;

        font-size: 0.75rem;

        margin-top: 4px;

    }


    .stat-icon {

        width: 46px;
        height: 46px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        font-size: 1.15rem;

    }


    .stat-icon-primary {

        background: rgba(13, 110, 253, 0.10);
        color: #0d6efd;

    }


    .stat-icon-success {

        background: rgba(25, 135, 84, 0.10);
        color: #198754;

    }


    .stat-icon-warning {

        background: rgba(255, 193, 7, 0.13);
        color: #b58100;

    }


    .stat-icon-secondary {

        background: rgba(108, 117, 125, 0.10);
        color: #6c757d;

    }


    /* =========================================================
       SECTION ICON
    ========================================================== */

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


    .table-count {

        color: #667085;

        background: #f8f9fb;

        border: 1px solid #e7ebf0;

        border-radius: 20px;

        padding: 6px 12px;

        font-size: 0.78rem;

        font-weight: 600;

        white-space: nowrap;

    }


    /* =========================================================
       SEARCH
    ========================================================== */

    .search-wrapper {

        position: relative;

    }


    .search-icon {

        position: absolute;

        left: 14px;
        top: 50%;

        transform: translateY(-50%);

        color: #98a2b3;

        z-index: 2;

    }


    .search-input {

        min-height: 44px;

        padding-left: 40px;

        padding-right: 42px;

        border-color: #d9dee7;

        border-radius: 10px;

    }


    .search-input:focus {

        border-color: #86b7fe;

        box-shadow:
            0 0 0 0.2rem rgba(13, 110, 253, 0.10);

    }


    .clear-search {

        position: absolute;

        right: 13px;
        top: 50%;

        transform: translateY(-50%);

        color: #98a2b3;

        text-decoration: none;

        z-index: 2;

    }


    .clear-search:hover {

        color: #dc3545;

    }


    .search-button {

        min-height: 44px;

        min-width: 110px;

        border-radius: 10px;

    }


    .search-result-info {

        color: #667085;

        font-size: 0.82rem;

    }


    /* =========================================================
       TABLE
    ========================================================== */

    .temporary-items-table {

        min-width: 950px;

    }


    .temporary-items-table thead th {

        background: #f8f9fb;

        color: #667085;

        border-bottom: 1px solid #e7ebf0;

        padding: 13px 12px;

        font-size: 0.73rem;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.035em;

        white-space: nowrap;

    }


    .temporary-items-table tbody td {

        padding: 15px 12px;

        border-bottom: 1px solid #edf0f4;

        color: #344054;

        font-size: 0.875rem;

    }


    .temporary-items-table tbody tr:last-child td {

        border-bottom: 0;

    }


    .temporary-items-table tbody tr {

        transition: background-color 0.15s ease;

    }


    .temporary-items-table tbody tr:hover {

        background-color: #fafbfc;

    }


    .number-column {

        width: 55px;

    }


    .action-column {

        width: 135px;

    }


    .description-column {

        min-width: 220px;

        max-width: 320px;

    }


    .row-number {

        color: #98a2b3;

        font-size: 0.8rem;

        font-weight: 500;

    }


    /* =========================================================
       ITEM IDENTITY
    ========================================================== */

    .item-identity {

        display: flex;

        align-items: center;

        gap: 11px;

        min-width: 180px;

    }


    .item-icon {

        width: 36px;
        height: 36px;

        min-width: 36px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: rgba(13, 110, 253, 0.08);

        color: #0d6efd;

    }


    .item-code {

        color: #344054;

        font-weight: 650;

        font-size: 0.88rem;

        word-break: break-word;

    }


    .item-code-wrapper {

        min-width: 0;

    }


    .item-type {

        color: #475467;

        font-weight: 500;

    }


    .description-text {

        display: block;

        color: #667085;

        line-height: 1.5;

        max-width: 320px;

    }


    /* =========================================================
       STATUS BADGES
    ========================================================== */

    .status-badge {

        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 6px 10px;

        border-radius: 20px;

        font-size: 0.75rem;

        font-weight: 600;

        white-space: nowrap;

    }


    .status-badge-dot {

        width: 6px;
        height: 6px;

        border-radius: 50%;

        display: inline-block;

    }


    .status-badge-success {

        background: rgba(25, 135, 84, 0.10);

        color: #198754;

        border: 1px solid rgba(25, 135, 84, 0.15);

    }


    .status-badge-success .status-badge-dot {

        background: #198754;

    }


    .status-badge-warning {

        background: rgba(255, 193, 7, 0.14);

        color: #956f00;

        border: 1px solid rgba(255, 193, 7, 0.20);

    }


    .status-badge-warning .status-badge-dot {

        background: #d39e00;

    }


    .status-badge-secondary {

        background: rgba(108, 117, 125, 0.10);

        color: #6c757d;

        border: 1px solid rgba(108, 117, 125, 0.15);

    }


    .status-badge-secondary .status-badge-dot {

        background: #6c757d;

    }


    /* =========================================================
       ISSUE COUNT
    ========================================================== */

    .issue-count-badge {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 34px;

        min-height: 28px;

        padding: 4px 9px;

        border-radius: 8px;

        background: #f8f9fb;

        border: 1px solid #e7ebf0;

        color: #475467;

        font-size: 0.78rem;

        font-weight: 600;

    }


    /* =========================================================
       ACTION BUTTONS
    ========================================================== */

    .action-buttons {

        display: inline-flex;

        align-items: center;

        gap: 5px;

    }


    .action-btn {

        width: 34px;
        height: 34px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: #ffffff;

        border: 1px solid #d9dee7;

        text-decoration: none;

        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;

    }


    .action-btn-view {

        color: #0d6efd;

    }


    .action-btn-view:hover {

        background: rgba(13, 110, 253, 0.08);

        border-color: rgba(13, 110, 253, 0.25);

        color: #0a58ca;

    }


    .action-btn-edit {

        color: #475467;

    }


    .action-btn-edit:hover {

        background: #f5f7fa;

        border-color: #cbd2dc;

        color: #344054;

    }


    .action-btn-delete {

        color: #dc3545;

    }


    .action-btn-delete:hover {

        background: rgba(220, 53, 69, 0.07);

        border-color: rgba(220, 53, 69, 0.25);

        color: #bb2d3b;

    }


    /* =========================================================
       MOBILE LIST
    ========================================================== */

    .mobile-items-list {

        display: none;

    }


    .mobile-item-card {

        padding: 18px;

        border-bottom: 1px solid #edf0f4;

    }


    .mobile-item-card:last-child {

        border-bottom: 0;

    }


    .mobile-item-header {

        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 12px;

        margin-bottom: 17px;

    }


    .mobile-item-details {

        display: grid;

        gap: 13px;

        margin-bottom: 17px;

    }


    .mobile-label {

        display: block;

        color: #98a2b3;

        font-size: 0.72rem;

        font-weight: 600;

        text-transform: uppercase;

        letter-spacing: 0.03em;

        margin-bottom: 3px;

    }


    .mobile-value {

        display: block;

        color: #475467;

        font-size: 0.84rem;

        line-height: 1.5;

    }


    .mobile-item-actions {

        display: flex;

        gap: 7px;

    }


    .mobile-item-actions .btn {

        min-height: 40px;

        font-size: 0.8rem;

    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {

        text-align: center;

        padding: 70px 20px;

    }


    .empty-state-icon {

        width: 70px;
        height: 70px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        border-radius: 18px;

        background: #f5f7fa;

        color: #98a2b3;

        font-size: 1.8rem;

        margin-bottom: 20px;

    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 767.98px) {

        .page-header-card {

            padding: 18px;

        }

        .page-header-card .btn {

            width: 100%;

        }

        .search-button {

            width: 100%;

        }

        .temporary-items-table {

            display: none;

        }

        .mobile-items-list {

            display: block;

        }

    }


    @media (max-width: 575.98px) {

        .container-fluid {

            padding-left: 12px !important;
            padding-right: 12px !important;

        }

        .page-icon {

            width: 40px;
            height: 40px;

        }

        h4 {

            font-size: 1.18rem;

        }

        .stat-card {

            padding: 17px;

        }

        .stat-value {

            font-size: 1.55rem;

        }

        .stat-icon {

            width: 42px;
            height: 42px;

        }

        .card-body {

            padding: 1rem !important;

        }

        .card-header {

            padding-left: 1rem !important;
            padding-right: 1rem !important;

        }

        .mobile-item-card {

            padding: 16px;

        }

        .mobile-item-actions {

            flex-wrap: wrap;

        }

        .mobile-item-actions > a {

            flex: 1;

        }

        .mobile-item-actions form {

            flex: 1;

            min-width: 100%;

        }

        .mobile-item-actions form button {

            width: 100%;

        }

        .empty-state {

            padding: 55px 18px;

        }

    }

</style>

@endpush


{{-- =============================================================
    PAGE SCRIPTS
============================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const deleteForms =
        document.querySelectorAll('.delete-item-form');


    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const confirmed = window.confirm(
                'Are you sure you want to delete this temporary item?\n\n' +
                'If this item has existing issue history, it will be deactivated instead of permanently deleted.'
            );


            if (!confirmed) {

                event.preventDefault();

                return;

            }


            const button =
                form.querySelector('button[type="submit"]');


            if (button) {

                button.disabled = true;

                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1" ' +
                    'role="status" aria-hidden="true"></span>' +
                    'Processing...';

            }

        });

    });


    /*
     * Prevent accidental repeated search submissions.
     */

    const searchForm =
        document.getElementById('temporaryItemSearchForm');


    if (searchForm) {

        searchForm.addEventListener('submit', function () {

            const button =
                searchForm.querySelector('button[type="submit"]');


            if (button) {

                button.disabled = true;

                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1" ' +
                    'role="status" aria-hidden="true"></span>' +
                    'Searching...';

            }

        });

    }

});

</script>

@endpush

@endsection

