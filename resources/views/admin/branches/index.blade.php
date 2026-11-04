
@extends('layouts.admin')

@section('title', 'CPC Branch Directory | CPC Inventory Management System')

@section('page-heading', 'CPC Branch Directory')

@section('content')

<style>
    .cpc-directory {
        --cpc-primary: #155eef;
        --cpc-primary-dark: #1249bd;
        --cpc-text: #172b4d;
        --cpc-muted: #667085;
        --cpc-border: #e5eaf1;
        --cpc-surface: #ffffff;

        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 4px 0 32px;
        color: var(--cpc-text);
    }

    .cpc-directory *,
    .cpc-directory *::before,
    .cpc-directory *::after {
        box-sizing: border-box;
    }

    /* PAGE HEADER */

    .cpc-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 25px 27px;
        margin-bottom: 22px;
        background: linear-gradient(115deg, #ffffff 0%, #f2f7ff 100%);
        border: 1px solid #e3eaf5;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(16, 24, 40, .025);
    }

    .cpc-page-heading {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .cpc-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 54px;
        width: 54px;
        height: 54px;
        color: #ffffff;
        font-size: 23px;
        background: linear-gradient(145deg, #2674ed, #1553c7);
        border-radius: 12px;
        box-shadow: 0 5px 12px rgba(21, 94, 239, .16);
    }

    .cpc-heading-text {
        min-width: 0;
    }

    .cpc-heading-text h1 {
        margin: 0;
        color: #172b4d;
        font-size: 21px;
        font-weight: 750;
        letter-spacing: -.45px;
        line-height: 1.35;
    }

    .cpc-heading-text p {
        margin: 5px 0 0;
        color: #667085;
        font-size: 12px;
        line-height: 1.6;
    }

    .cpc-source-indicator {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 10px;
        padding: 6px 10px;
        color: #176b45;
        background: #eaf7f0;
        border: 1px solid #d5efdf;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .15px;
    }

    .cpc-source-indicator i {
        font-size: 12px;
    }

    .cpc-header-reference {
        flex-shrink: 0;
        color: #8792a5;
        font-size: 10px;
        text-align: right;
    }

    .cpc-header-reference strong {
        display: block;
        margin-bottom: 4px;
        color: #475467;
        font-size: 11px;
        font-weight: 700;
    }

    /* STATISTICS */

    .cpc-stat-card {
        position: relative;
        height: 100%;
        min-height: 128px;
        padding: 19px 20px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid var(--cpc-border);
        border-radius: 12px;
        box-shadow: 0 2px 7px rgba(16, 24, 40, .025);
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .cpc-stat-card:hover {
        border-color: #cbd9ee;
        box-shadow: 0 5px 15px rgba(16, 24, 40, .055);
    }

    .cpc-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .cpc-stat-label {
        color: #667085;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: .65px;
        text-transform: uppercase;
    }

    .cpc-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 37px;
        height: 37px;
        border-radius: 9px;
        font-size: 16px;
    }

    .cpc-stat-icon.total {
        color: #155eef;
        background: #edf4ff;
    }

    .cpc-stat-icon.available {
        color: #168653;
        background: #eaf7f0;
    }

    .cpc-stat-icon.unavailable {
        color: #d04444;
        background: #fff0ef;
    }

    .cpc-stat-number {
        margin-top: 12px;
        color: #172b4d;
        font-size: 29px;
        font-weight: 750;
        line-height: 1.1;
        letter-spacing: -.7px;
        font-variant-numeric: tabular-nums;
    }

    .cpc-stat-description {
        display: block;
        margin-top: 7px;
        color: #98a2b3;
        font-size: 10px;
    }

    /* DIRECTORY CARD */

    .cpc-directory-card {
        overflow: hidden;
        background: var(--cpc-surface);
        border: 1px solid var(--cpc-border);
        border-radius: 13px;
        box-shadow: 0 2px 9px rgba(16, 24, 40, .03);
    }

    .cpc-directory-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 19px 21px;
        border-bottom: 1px solid #edf0f4;
    }

    .cpc-directory-title {
        min-width: 0;
    }

    .cpc-directory-title h5 {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0;
        color: #253858;
        font-size: 14px;
        font-weight: 750;
    }

    .cpc-directory-title h5 i {
        color: var(--cpc-primary);
        font-size: 15px;
    }

    .cpc-directory-title p {
        margin: 5px 0 0;
        color: #98a2b3;
        font-size: 10px;
    }

    /* SEARCH AND FILTER */

    .cpc-directory-filters {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
    }

    .cpc-search-wrapper {
        position: relative;
    }

    .cpc-search-wrapper i {
        position: absolute;
        top: 50%;
        left: 12px;
        z-index: 1;
        color: #98a2b3;
        font-size: 13px;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .cpc-search-input,
    .cpc-status-select {
        height: 39px;
        color: #344054;
        background: #ffffff;
        border: 1px solid #dce3ed;
        border-radius: 7px;
        outline: none;
        font-family: inherit;
        font-size: 11px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .cpc-search-input {
        width: 245px;
        padding: 0 12px 0 35px;
    }

    .cpc-status-select {
        min-width: 140px;
        padding: 0 30px 0 11px;
        cursor: pointer;
    }

    .cpc-search-input:focus,
    .cpc-status-select:focus {
        border-color: #84adff;
        box-shadow: 0 0 0 3px rgba(21, 94, 239, .09);
    }

    .cpc-filter-button,
    .cpc-clear-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 39px;
        padding: 0 14px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 650;
        text-decoration: none;
        transition: all .2s ease;
        white-space: nowrap;
    }

    .cpc-filter-button {
        color: #ffffff;
        background: #155eef;
        border: 1px solid #155eef;
    }

    .cpc-filter-button:hover {
        color: #ffffff;
        background: #1249bd;
        border-color: #1249bd;
    }

    .cpc-clear-button {
        color: #475467;
        background: #ffffff;
        border: 1px solid #dce3ed;
    }

    .cpc-clear-button:hover {
        color: #155eef;
        background: #f5f8ff;
        border-color: #b8cef5;
    }

    /* TABLE */

    .cpc-table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .cpc-location-table {
        width: 100%;
        min-width: 690px;
        border-collapse: collapse;
    }

    .cpc-location-table thead th {
        padding: 13px 20px;
        color: #7b8798;
        background: #f8fafc;
        border-bottom: 1px solid #e9edf3;
        font-size: 9px;
        font-weight: 750;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .65px;
        white-space: nowrap;
    }

    .cpc-location-table tbody td {
        padding: 14px 20px;
        color: #475467;
        border-bottom: 1px solid #f0f2f5;
        font-size: 11px;
        vertical-align: middle;
    }

    .cpc-location-table tbody tr {
        transition: background .15s ease;
    }

    .cpc-location-table tbody tr:hover {
        background: #f8faff;
    }

    .cpc-location-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .cpc-row-number {
        color: #98a2b3 !important;
        font-size: 10px !important;
        font-variant-numeric: tabular-nums;
    }

    .cpc-location-identity {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 220px;
    }

    .cpc-location-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 39px;
        width: 39px;
        height: 39px;
        color: #2165d5;
        background: #edf4ff;
        border: 1px solid #e0ebff;
        border-radius: 9px;
        font-size: 15px;
    }

    .cpc-location-name {
        color: #253858;
        font-size: 11px;
        font-weight: 750;
        line-height: 1.5;
    }

    .cpc-location-subtitle {
        margin-top: 3px;
        color: #98a2b3;
        font-size: 9px;
    }

    .cpc-location-code {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        color: #475467;
        background: #f7f8fa;
        border: 1px solid #e7eaf0;
        border-radius: 5px;
        font-family: Consolas, Monaco, monospace;
        font-size: 10px;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    /* STATUS */

    .cpc-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 10px;
        border-radius: 30px;
        font-size: 9px;
        font-weight: 750;
        white-space: nowrap;
    }

    .cpc-status-dot {
        width: 6px;
        height: 6px;
        flex: 0 0 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .cpc-status.is-available {
        color: #168653;
        background: #eaf7f0;
        border: 1px solid #d7efdf;
    }

    .cpc-status.is-unavailable {
        color: #c33d3d;
        background: #fff0ef;
        border: 1px solid #f8d9d7;
    }

    /* ACTION */

    .cpc-view-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-width: 75px;
        height: 32px;
        padding: 0 10px;
        color: #155eef;
        background: #ffffff;
        border: 1px solid #d7e3f8;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
        transition: all .2s ease;
    }

    .cpc-view-button:hover {
        color: #ffffff;
        background: #155eef;
        border-color: #155eef;
    }

    .cpc-view-button i {
        font-size: 12px;
    }

    /* FOOTER AND PAGINATION */

    .cpc-table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        padding: 15px 20px;
        background: #ffffff;
        border-top: 1px solid #edf0f4;
    }

    .cpc-record-count {
        color: #8792a5;
        font-size: 10px;
    }

    .cpc-record-count strong {
        color: #475467;
        font-weight: 750;
    }

    .cpc-pagination {
        margin: 0;
    }

    .cpc-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    .cpc-pagination .pagination {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .cpc-pagination .page-item {
        margin: 0;
    }

    .cpc-pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 11px;
        color: #475467;
        background: #ffffff;
        border: 1px solid #e5eaf1;
        border-radius: 6px !important;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: none;
        transition: all .2s ease;
    }

    .cpc-pagination .page-item.active .page-link {
        color: #ffffff;
        background: #155eef;
        border-color: #155eef;
    }

    .cpc-pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        color: #155eef;
        background: #f3f7ff;
        border-color: #c8d9f5;
    }

    .cpc-pagination .page-item.disabled .page-link {
        color: #aab3c0;
        background: #f9fafb;
        border-color: #edf0f4;
        cursor: not-allowed;
    }

    .cpc-pagination .page-link:focus {
        box-shadow: 0 0 0 3px rgba(21, 94, 239, .10);
    }

    /* EMPTY STATE */

    .cpc-empty-state {
        padding: 65px 20px;
        text-align: center;
    }

    .cpc-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 68px;
        height: 68px;
        margin: 0 auto 17px;
        color: #3977d7;
        background: #edf4ff;
        border: 1px solid #e0ebff;
        border-radius: 18px;
        font-size: 27px;
    }

    .cpc-empty-state h4 {
        margin: 0 0 8px;
        color: #253858;
        font-size: 15px;
        font-weight: 750;
    }

    .cpc-empty-state p {
        max-width: 390px;
        margin: 0 auto 17px;
        color: #8792a5;
        font-size: 11px;
        line-height: 1.7;
    }

    /* SOURCE FOOTNOTE */

    .cpc-data-note {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        margin-top: 15px;
        padding: 12px 14px;
        color: #667085;
        background: #f8fafc;
        border: 1px solid #e9edf3;
        border-radius: 8px;
        font-size: 10px;
        line-height: 1.65;
    }

    .cpc-data-note i {
        margin-top: 1px;
        color: #3977d7;
        font-size: 13px;
    }

    /* RESPONSIVE */

    @media (max-width: 992px) {
        .cpc-directory-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .cpc-directory-filters {
            width: 100%;
        }

        .cpc-search-wrapper {
            flex: 1 1 220px;
        }

        .cpc-search-input {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .cpc-page-header {
            align-items: flex-start;
            padding: 19px;
        }

        .cpc-header-reference {
            display: none;
        }

        .cpc-heading-icon {
            flex-basis: 46px;
            width: 46px;
            height: 46px;
            font-size: 19px;
        }

        .cpc-heading-text h1 {
            font-size: 18px;
        }

        .cpc-stat-card {
            min-height: 115px;
            padding: 16px;
        }

        .cpc-stat-number {
            font-size: 25px;
        }

        .cpc-directory-toolbar {
            padding: 17px;
        }

        .cpc-directory-filters {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .cpc-search-wrapper {
            grid-column: 1 / -1;
        }

        .cpc-search-input {
            width: 100%;
        }

        .cpc-status-select {
            width: 100%;
        }

        .cpc-filter-button,
        .cpc-clear-button {
            width: 100%;
        }

        .cpc-table-footer {
            align-items: flex-start;
            flex-direction: column;
            padding: 14px 16px;
        }

        .cpc-pagination {
            width: 100%;
            overflow-x: auto;
        }

        .cpc-pagination nav {
            justify-content: flex-start;
        }
    }

    @media (max-width: 480px) {
        .cpc-page-header {
            padding: 16px;
        }

        .cpc-heading-text h1 {
            font-size: 16px;
        }

        .cpc-heading-text p {
            font-size: 10px;
        }

        .cpc-source-indicator {
            font-size: 9px;
        }

        .cpc-directory-filters {
            grid-template-columns: 1fr;
        }

        .cpc-search-wrapper {
            grid-column: auto;
        }

        .cpc-stat-card {
            min-height: 108px;
        }
    }
</style>

<div class="cpc-directory">

    {{-- PAGE HEADER --}}

    <section class="cpc-page-header">

        <div class="cpc-page-heading">

            <div class="cpc-heading-icon">
                <i class="bi bi-buildings-fill"></i>
            </div>

            <div class="cpc-heading-text">

                <h1>CPC Branch Directory</h1>

                <p>
                    Official CPC locations and availability information.
                </p>

                <span class="cpc-source-indicator">
                    <i class="bi bi-database-check"></i>
                    Live CPC SQL Server Data
                </span>

            </div>

        </div>

        <div class="cpc-header-reference">
            <strong>MASTER DATA DIRECTORY</strong>
            CPC Inventory Management System
        </div>

    </section>


    {{-- SUMMARY CARDS --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-4 col-md-4 col-12">

            <div class="cpc-stat-card">

                <div class="cpc-stat-top">

                    <span class="cpc-stat-label">
                        Total CPC Locations
                    </span>

                    <span class="cpc-stat-icon total">
                        <i class="bi bi-buildings"></i>
                    </span>

                </div>

                <div class="cpc-stat-number">
                    {{ number_format($totalBranches) }}
                </div>

                <span class="cpc-stat-description">
                    Registered in the CPC master database
                </span>

            </div>

        </div>


        <div class="col-xl-4 col-md-4 col-12">

            <div class="cpc-stat-card">

                <div class="cpc-stat-top">

                    <span class="cpc-stat-label">
                        Available Locations
                    </span>

                    <span class="cpc-stat-icon available">
                        <i class="bi bi-check-circle"></i>
                    </span>

                </div>

                <div class="cpc-stat-number">
                    {{ number_format($activeBranches) }}
                </div>

                <span class="cpc-stat-description">
                    Currently marked as available by CPC
                </span>

            </div>

        </div>


        <div class="col-xl-4 col-md-4 col-12">

            <div class="cpc-stat-card">

                <div class="cpc-stat-top">

                    <span class="cpc-stat-label">
                        Unavailable Locations
                    </span>

                    <span class="cpc-stat-icon unavailable">
                        <i class="bi bi-exclamation-circle"></i>
                    </span>

                </div>

                <div class="cpc-stat-number">
                    {{ number_format($inactiveBranches) }}
                </div>

                <span class="cpc-stat-description">
                    Not currently marked as available
                </span>

            </div>

        </div>

    </div>


    {{-- LOCATION DIRECTORY --}}

    <section class="cpc-directory-card">

        {{-- TOOLBAR --}}

        <div class="cpc-directory-toolbar">

            <div class="cpc-directory-title">

                <h5>
                    <i class="bi bi-list-ul"></i>
                    CPC Location Directory
                </h5>

                <p>
                    Search, filter and view official CPC location records.
                </p>

            </div>


            <form
                method="GET"
                action="{{ route('admin.branches.index') }}"
                class="cpc-directory-filters"
            >

                <div class="cpc-search-wrapper">

                    <i class="bi bi-search"></i>

                    <input
                        type="search"
                        name="search"
                        class="cpc-search-input"
                        placeholder="Search location name or ID..."
                        value="{{ $search }}"
                        aria-label="Search CPC locations"
                    >

                </div>


                <select
                    name="status"
                    class="cpc-status-select"
                    aria-label="Filter locations by availability"
                >

                    <option value="all" @selected($status === 'all')>
                        All Availability
                    </option>

                    <option value="active" @selected($status === 'active')>
                        Available
                    </option>

                    <option value="inactive" @selected($status === 'inactive')>
                        Unavailable
                    </option>

                </select>


                <button type="submit" class="cpc-filter-button">
                    <i class="bi bi-search"></i>
                    Apply
                </button>


                @if($search !== '' || $status !== 'all')

                    <a
                        href="{{ route('admin.branches.index') }}"
                        class="cpc-clear-button"
                    >
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset
                    </a>

                @endif

            </form>

        </div>


        {{-- TABLE --}}

        @if($branches->count() > 0)

            <div class="cpc-table-responsive">

                <table class="cpc-location-table">

                    <thead>

                        <tr>
                            <th style="width: 65px;">No.</th>
                            <th>CPC Location</th>
                            <th style="width: 145px;">Location ID</th>
                            <th style="width: 165px;">Availability</th>
                            <th style="width: 120px;">Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($branches as $branch)

                            @php
                                $isAvailable = (string) $branch->Is_Available === '1';
                            @endphp

                            <tr>

                                <td class="cpc-row-number">
                                    {{ $branches->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    <div class="cpc-location-identity">

                                        <div class="cpc-location-icon">
                                            <i class="bi bi-building"></i>
                                        </div>

                                        <div>

                                            <div class="cpc-location-name">
                                                {{ $branch->Location_Name }}
                                            </div>

                                            <div class="cpc-location-subtitle">
                                                CPC Master Location
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <span class="cpc-location-code">
                                        {{ $branch->Location_Id }}
                                    </span>

                                </td>


                                <td>

                                    @if($isAvailable)

                                        <span class="cpc-status is-available">
                                            <span class="cpc-status-dot"></span>
                                            Available
                                        </span>

                                    @else

                                        <span class="cpc-status is-unavailable">
                                            <span class="cpc-status-dot"></span>
                                            Unavailable
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <a
                                        href="{{ route('admin.branches.show', $branch->Location_Id) }}"
                                        class="cpc-view-button"
                                        title="View location details"
                                        aria-label="View details for {{ $branch->Location_Name }}"
                                    >
                                        <i class="bi bi-eye"></i>
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            <div class="cpc-table-footer">

                <div class="cpc-record-count">

                    Showing

                    <strong>{{ $branches->firstItem() }}</strong>

                    to

                    <strong>{{ $branches->lastItem() }}</strong>

                    of

                    <strong>{{ number_format($branches->total()) }}</strong>

                    locations

                </div>


                <div class="cpc-pagination">

                    {{ $branches->onEachSide(1)->links('vendor.pagination.cpc-bootstrap') }}

                </div>

            </div>


        @else

            {{-- EMPTY STATE --}}

            <div class="cpc-empty-state">

                <div class="cpc-empty-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h4>No CPC Locations Found</h4>

                <p>
                    No location records match your search criteria.
                    Try another location name or ID, or reset the filters.
                </p>

                @if($search !== '' || $status !== 'all')

                    <a
                        href="{{ route('admin.branches.index') }}"
                        class="cpc-clear-button"
                    >
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset Filters
                    </a>

                @endif

            </div>

        @endif

    </section>


    {{-- DATA SOURCE INFORMATION --}}

    <div class="cpc-data-note">

        <i class="bi bi-info-circle-fill"></i>

        <div>
            <strong>Data source:</strong>
            Location information is retrieved from the CPC master database
            (SQL Server). This directory is read-only. Location records are
            maintained by the source system.
        </div>

    </div>

</div>

@endsection
