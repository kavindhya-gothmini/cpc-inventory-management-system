@extends('layouts.admin')
@section('title', 'Function Unit Directory | CPC Inventory Management System')

@section('page-heading', 'Function Unit Directory')

@section('content')

<style>
    .fu-page {
        color: #1f2937;
    }

    .fu-hero {
        background: linear-gradient(120deg, #10243a, #174b72);
        color: #fff;
        padding: 26px;
        border-radius: 16px;
        margin-bottom: 24px;
    }

    .fu-hero h2 {
        font-size: 23px;
        font-weight: 700;
        margin: 12px 0 8px;
    }

    .fu-hero p {
        color: #dbeafe;
        margin: 0;
        max-width: 720px;
    }

    .fu-source {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        border-radius: 30px;
        background: rgba(255,255,255,.13);
        font-size: 12px;
    }

    .fu-stat {
        height: 100%;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
    }

    .fu-stat-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .fu-stat-number {
        font-size: 29px;
        font-weight: 750;
        margin-top: 8px;
        color: #172b4d;
    }

    .fu-stat-note {
        color: #94a3b8;
        font-size: 12px;
        margin-top: 5px;
    }

    .fu-directory {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 15px;
        overflow: hidden;
    }

    .fu-directory-header {
        padding: 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .fu-directory-header h5 {
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .fu-muted {
        color: #64748b;
        font-size: 13px;
    }

    /* FILTER AREA */

    .fu-filter-panel {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
        margin-top: 20px;
    }

    .fu-filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 7px;
    }

    .fu-filter-panel .form-control,
    .fu-filter-panel .form-select {
        min-height: 40px;
        font-size: 13px;
        border-color: #dbe2ea;
        border-radius: 8px;
    }

    .fu-filter-panel .form-control:focus,
    .fu-filter-panel .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.10);
    }

    .fu-filter-actions {
        display: flex;
        align-items: end;
        gap: 8px;
    }

    .fu-filter-actions .btn {
        min-height: 40px;
        font-size: 13px;
        white-space: nowrap;
    }

    .fu-table-wrapper {
        overflow-x: auto;
    }

    .fu-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 950px;
    }

    .fu-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding: 14px;
        text-align: left;
        white-space: nowrap;
    }

    .fu-table td {
        padding: 14px;
        border-top: 1px solid #edf0f4;
        vertical-align: middle;
        font-size: 13px;
    }

    .fu-table tbody tr:hover {
        background: #f8fbff;
    }

    .fu-name {
        font-weight: 650;
        color: #1e293b;
    }

    .fu-id {
        font-family: monospace;
        font-size: 12px;
        background: #f1f5f9;
        border-radius: 6px;
        padding: 5px 8px;
        display: inline-block;
    }

    .fu-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 30px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .fu-status-active {
        background: #dcfce7;
        color: #166534;
    }

    .fu-status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .fu-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .fu-pagination {
        padding: 16px 22px;
        border-top: 1px solid #e5e7eb;
    }

    /* COMPACT PAGINATION ARROWS */

    .fu-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    .fu-pagination .pagination {
        margin: 0;
        gap: 4px;
        align-items: center;
    }

    .fu-pagination .page-link {
        min-width: 30px;
        height: 30px;
        padding: 5px 9px;
        font-size: 12px;
        line-height: 18px;
        text-align: center;
        border-radius: 6px !important;
        border: 1px solid #e2e8f0;
        color: #334155;
        background: #fff;
        box-shadow: none;
    }

    .fu-pagination .page-link svg {
        width: 12px !important;
        height: 12px !important;
        max-width: 12px;
        max-height: 12px;
        vertical-align: middle;
    }

    .fu-pagination .page-link:hover {
        background: #eff6ff;
        color: #2563eb;
    }

    .fu-pagination .active .page-link {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    .fu-pagination .disabled .page-link {
        color: #94a3b8;
        background: #f8fafc;
    }

    .fu-empty {
        padding: 55px 20px;
        text-align: center;
        color: #64748b;
    }

    .fu-empty i {
        font-size: 38px;
        color: #94a3b8;
    }

    .fu-readonly {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        padding: 13px 15px;
        border-radius: 10px;
        font-size: 13px;
    }

    @media(max-width: 767px) {
        .fu-hero {
            padding: 20px;
        }

        .fu-hero h2 {
            font-size: 20px;
        }

        .fu-filter-actions {
            width: 100%;
        }

        .fu-filter-actions .btn {
            flex: 1;
        }

        .fu-pagination > div {
            flex-direction: column;
            align-items: flex-start !important;
        }
    }
</style>

<div class="fu-page">

    {{-- HEADER --}}
    <div class="fu-hero">

        <div class="fu-source">
            <i class="bi bi-database-check"></i>
            CPC HR Master Database
        </div>

        <h2>
            <i class="bi bi-diagram-3-fill me-2"></i>
            Function & Sub-function Directory
        </h2>

        <p>
            View the official CPC organizational Function and
            Sub-function hierarchy retrieved from the CPC HR
            SQL Server master database.
        </p>

    </div>

    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="fu-stat">
                <div class="fu-stat-label">Total Functions</div>
                <div class="fu-stat-number">
                    {{ number_format($functionsCount) }}
                </div>
                <div class="fu-stat-note">
                    {{ number_format($activeFunctionsCount) }} available
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="fu-stat">
                <div class="fu-stat-label">Total Sub-functions</div>
                <div class="fu-stat-number">
                    {{ number_format($subFunctionsCount) }}
                </div>
                <div class="fu-stat-note">
                    CPC HR master records
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="fu-stat">
                <div class="fu-stat-label">Active Sub-functions</div>
                <div class="fu-stat-number">
                    {{ number_format($activeSubFunctionsCount) }}
                </div>
                <div class="fu-stat-note">
                    Available in CPC HR
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="fu-stat">
                <div class="fu-stat-label">Units</div>
                <div class="fu-stat-number">
                    {{ number_format($unitsCount) }}
                </div>
                <div class="fu-stat-note">
                    Records currently in CPC Unit master
                </div>
            </div>
        </div>

    </div>

    {{-- READ ONLY NOTICE --}}
    <div class="fu-readonly mb-4">
        <i class="bi bi-info-circle-fill me-2"></i>
        This directory displays read-only CPC HR master data.
        Changes to Functions and Sub-functions must be made through
        the authorized CPC master-data system.
    </div>

    {{-- DIRECTORY --}}
    <div class="fu-directory">

        <div class="fu-directory-header">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

                <div>
                    <h5>
                        <i class="bi bi-list-nested me-2"></i>
                        CPC Organizational Registry
                    </h5>

                    <div class="fu-muted">
                        Function and Sub-function master records
                    </div>
                </div>

                <span class="badge bg-primary">
                    {{ number_format($subFunctions->total()) }} Records
                </span>

            </div>

            {{-- SEARCH AND FILTERS --}}
            <form method="GET"
                  action="{{ route('admin.function-units.index') }}"
                  class="fu-filter-panel">

                <div class="row g-3">

                    {{-- GENERAL SEARCH --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="fu-filter-label">
                            Search Records
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ $search ?? request('search') }}"
                            placeholder="Search name, ID, location, cost centre..."
                        >

                    </div>

                    {{-- FUNCTION FILTER --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="fu-filter-label">
                            Function
                        </label>

                        <select name="function_id" class="form-select">

                            <option value="">All Functions</option>

                            @foreach($functionOptions ?? [] as $function)
                                <option value="{{ $function->function_id }}"
                                    {{ (string) request('function_id') === (string) $function->function_id ? 'selected' : '' }}>
                                    {{ $function->function_name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- LOCATION FILTER --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="fu-filter-label">
                            Location ID
                        </label>

                        <input
                            type="text"
                            name="location_id"
                            class="form-control"
                            value="{{ request('location_id') }}"
                            placeholder="Location ID"
                        >

                    </div>

                    {{-- STATUS FILTER --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="fu-filter-label">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option value="all"
                                {{ ($status ?? request('status', 'all')) === 'all' ? 'selected' : '' }}>
                                All Status
                            </option>

                            <option value="active"
                                {{ ($status ?? request('status')) === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ ($status ?? request('status')) === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                    {{-- SUB-FUNCTION SEARCH --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="fu-filter-label">
                            Sub-function
                        </label>

                        <input
                            type="text"
                            name="sub_function"
                            class="form-control"
                            value="{{ request('sub_function') }}"
                            placeholder="Sub-function name or ID"
                        >

                    </div>

                    {{-- COST CENTRE FILTER --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="fu-filter-label">
                            Cost Centre
                        </label>

                        <input
                            type="text"
                            name="cost_centre"
                            class="form-control"
                            value="{{ request('cost_centre') }}"
                            placeholder="Cost centre"
                        >

                    </div>

                    {{-- IBM CODE FILTER --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="fu-filter-label">
                            IBM Code
                        </label>

                        <input
                            type="text"
                            name="ibm_code"
                            class="form-control"
                            value="{{ request('ibm_code') }}"
                            placeholder="IBM code"
                        >

                    </div>

                    {{-- BUTTONS --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="fu-filter-actions">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.function-units.index') }}"
                               class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

        @if($subFunctions->count())

            <div class="fu-table-wrapper">

                <table class="fu-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Function</th>
                            <th>Function ID</th>
                            <th>Sub-function</th>
                            <th>Sub-function ID</th>
                            <th>Location ID</th>
                            <th>Cost Centre</th>
                            <th>IBM Code</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($subFunctions as $record)

                            <tr>

                                <td>
                                    {{ $subFunctions->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="fu-name">
                                        {{ $record->function_name }}
                                    </div>
                                </td>

                                <td>
                                    <span class="fu-id">
                                        {{ $record->function_id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="fu-name">
                                        {{ $record->sub_function_name }}
                                    </div>
                                </td>

                                <td>
                                    <span class="fu-id">
                                        {{ $record->sub_function_id }}
                                    </span>
                                </td>

                                <td>
                                    {{ $record->location_id ?: '—' }}
                                </td>

                                <td>
                                    {{ $record->cost_centre ?: '—' }}
                                </td>

                                <td>
                                    {{ $record->sub_function_ibm_code ?: '—' }}
                                </td>

                                <td>

                                    @if((string) $record->sub_function_is_available === '1')

                                        <span class="fu-status fu-status-active">
                                            <span class="fu-dot"></span>
                                            Active
                                        </span>

                                    @else

                                        <span class="fu-status fu-status-inactive">
                                            <span class="fu-dot"></span>
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="fu-pagination">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div class="fu-muted">
                        Showing
                        <strong>{{ $subFunctions->firstItem() }}</strong>
                        to
                        <strong>{{ $subFunctions->lastItem() }}</strong>
                        of
                        <strong>{{ $subFunctions->total() }}</strong>
                        sub-functions
                    </div>

                    <div>
                        {{ $subFunctions->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>

                </div>

            </div>

        @else

            <div class="fu-empty">

                <i class="bi bi-search"></i>

                <h5 class="mt-3">
                    No Matching CPC Records
                </h5>

                <p class="mb-0">
                    No Function or Sub-function records match
                    your current search and filter selections.
                </p>

                <a href="{{ route('admin.function-units.index') }}"
                   class="btn btn-outline-primary btn-sm mt-3">
                    Clear All Filters
                </a>

            </div>

        @endif

    </div>

</div>

@endsection