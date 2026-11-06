
@extends('layouts.admin')

@section('title', 'Department Management | CPC Inventory Management System')

@section('page-heading', 'Department Management')

@section('content')

<style>
    .cpc-department-page {
        --cpc-blue: #0d6efd;
        --cpc-dark: #1f2937;
        --cpc-muted: #6b7280;
        --cpc-border: #e5e7eb;
        --cpc-soft: #f8fafc;
        color: var(--cpc-dark);
    }

    .department-breadcrumb {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 22px;
        color: var(--cpc-muted);
        font-size: 13px;
    }

    .department-breadcrumb i {
        font-size: 11px;
    }

    .department-breadcrumb .current {
        color: var(--cpc-dark);
        font-weight: 600;
    }

    .department-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 25px;
        margin-bottom: 22px;
        background: #fff;
        border: 1px solid var(--cpc-border);
        border-radius: 13px;
    }

    .department-header-content {
        display: flex;
        align-items: center;
        gap: 17px;
    }

    .department-header-icon {
        width: 57px;
        height: 57px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #eaf2ff;
        color: var(--cpc-blue);
        font-size: 25px;
    }

    .department-header-text h1 {
        margin: 0 0 6px;
        font-size: 23px;
        font-weight: 700;
    }

    .department-header-text p {
        margin: 0;
        color: var(--cpc-muted);
        font-size: 13px;
    }

    .department-source-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 11px;
        padding: 5px 10px;
        border-radius: 20px;
        background: #edf7f1;
        color: #18794e;
        font-size: 11px;
        font-weight: 600;
    }

    .department-source-label i {
        font-size: 12px;
    }

    .department-readonly-notice {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 14px 16px;
        margin-bottom: 20px;
        border: 1px solid #cfe2ff;
        border-radius: 9px;
        background: #f0f6ff;
        color: #24558b;
        font-size: 13px;
        line-height: 1.6;
    }

    .department-readonly-notice i {
        margin-top: 2px;
        font-size: 17px;
    }

    .department-kpi {
        margin-bottom: 22px;
    }

    .department-stat-card {
        height: 100%;
        padding: 19px;
        border: 1px solid var(--cpc-border);
        border-radius: 11px;
        background: #fff;
    }

    .department-stat-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .department-stat-label {
        margin-bottom: 8px;
        color: var(--cpc-muted);
        font-size: 12px;
        font-weight: 600;
    }

    .department-stat-number {
        font-size: 27px;
        font-weight: 750;
        line-height: 1.2;
    }

    .department-stat-description {
        display: block;
        margin-top: 6px;
        color: #9ca3af;
        font-size: 11px;
    }

    .department-stat-icon {
        width: 45px;
        height: 45px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #edf3ff;
        color: var(--cpc-blue);
        font-size: 20px;
    }

    .department-content-card {
        overflow: hidden;
        border: 1px solid var(--cpc-border);
        border-radius: 12px;
        background: #fff;
    }

    .department-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 20px;
        border-bottom: 1px solid var(--cpc-border);
    }

    .department-toolbar-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .department-toolbar-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #edf3ff;
        color: var(--cpc-blue);
        font-size: 18px;
    }

    .department-toolbar-title h5 {
        margin: 0 0 4px;
        font-size: 15px;
        font-weight: 700;
    }

    .department-toolbar-title p {
        margin: 0;
        color: var(--cpc-muted);
        font-size: 12px;
    }

    .department-toolbar-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .department-search {
        position: relative;
        width: 270px;
    }

    .department-search i {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .department-search input {
        width: 100%;
        height: 39px;
        padding: 8px 12px 8px 35px;
        border: 1px solid var(--cpc-border);
        border-radius: 7px;
        outline: none;
        font-size: 12px;
    }

    .department-search input:focus,
    .department-filter:focus {
        border-color: var(--cpc-blue);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .09);
    }

    .department-filter {
        height: 39px;
        min-width: 155px;
        padding: 7px 10px;
        border: 1px solid var(--cpc-border);
        border-radius: 7px;
        background: #fff;
        color: var(--cpc-dark);
        font-size: 12px;
        outline: none;
    }

    .department-table-wrapper {
        overflow-x: auto;
    }

    .department-table {
        width: 100%;
        min-width: 750px;
        border-collapse: collapse;
    }

    .department-table thead th {
        padding: 14px 17px;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .35px;
        white-space: nowrap;
    }

    .department-table tbody td {
        padding: 16px 17px;
        border-top: 1px solid #edf0f3;
        vertical-align: middle;
        font-size: 13px;
    }

    .department-table tbody tr:hover {
        background: #fafcff;
    }

    .department-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 48px;
        padding: 5px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    .department-identity {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .department-avatar {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #edf3ff;
        color: var(--cpc-blue);
        font-size: 16px;
    }

    .department-name {
        color: #1f2937;
        font-size: 13px;
        font-weight: 650;
    }

    .department-subtitle {
        margin-top: 4px;
        color: #9ca3af;
        font-size: 11px;
    }

    .department-location-code {
        display: inline-block;
        padding: 6px 9px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #fafafa;
        color: #475569;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
    }

    .department-location-type {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 10px;
        border-radius: 20px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .department-location-type i {
        font-size: 11px;
    }

    .department-no-value {
        color: #9ca3af;
        font-size: 12px;
        font-style: italic;
    }

    .department-table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 16px 20px;
        border-top: 1px solid var(--cpc-border);
    }

    .department-record-count {
        color: var(--cpc-muted);
        font-size: 12px;
    }

    .department-record-count strong {
        color: var(--cpc-dark);
    }

    .department-pagination nav {
        margin: 0;
    }

    .department-pagination .pagination {
        margin: 0;
    }

    .department-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .department-empty-icon {
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 17px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-size: 26px;
    }

    .department-empty h4 {
        margin-bottom: 8px;
        font-size: 17px;
        font-weight: 700;
    }

    .department-empty p {
        max-width: 480px;
        margin: 0 auto;
        color: var(--cpc-muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .department-reset-link {
        color: var(--cpc-blue);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .department-reset-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 767px) {
        .department-header,
        .department-toolbar,
        .department-table-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .department-toolbar-actions {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }

        .department-search,
        .department-filter {
            width: 100%;
        }

        .department-header-text h1 {
            font-size: 19px;
        }

        .department-header {
            padding: 18px;
        }

        .department-table-footer {
            padding: 15px;
        }
    }
</style>


<div class="cpc-department-page">

    {{-- BREADCRUMB --}}

    <div class="department-breadcrumb">
        <span>Inventory</span>
        <i class="bi bi-chevron-right"></i>
        <span class="current">Department Management</span>
    </div>


    {{-- PAGE HEADER --}}

    <div class="department-header">

        <div class="department-header-content">

            <div class="department-header-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>

            <div class="department-header-text">

                <h1>Department Management</h1>

                <p>
                    View the official CPC department directory and
                    organizational location information.
                </p>

                <div class="department-source-label">
                    <i class="bi bi-database-check"></i>
                    Live CPC SQL Server Master Data
                </div>

            </div>

        </div>

    </div>


    {{-- READ-ONLY NOTICE --}}

    <div class="department-readonly-notice">

        <i class="bi bi-info-circle-fill"></i>

        <div>
            <strong>CPC Master Department Directory</strong><br>
            Department information is retrieved directly from the CPC
            master database. This page is read-only; department records
            must be maintained in the authoritative CPC source system.
        </div>

    </div>


    {{-- STATISTICS --}}

    <div class="row g-3 department-kpi">

        <div class="col-xl-3 col-md-6">

            <div class="department-stat-card">

                <div class="department-stat-content">

                    <div>
                        <div class="department-stat-label">
                            Total Departments
                        </div>

                        <div class="department-stat-number">
                            {{ number_format($totalDepartments) }}
                        </div>

                        <span class="department-stat-description">
                            CPC master department records
                        </span>
                    </div>

                    <div class="department-stat-icon">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="department-stat-card">

                <div class="department-stat-content">

                    <div>
                        <div class="department-stat-label">
                            Head Office Departments
                        </div>

                        <div class="department-stat-number">
                            {{ number_format($headOfficeDepartments) }}
                        </div>

                        <span class="department-stat-description">
                            Location type: Head Office
                        </span>
                    </div>

                    <div class="department-stat-icon">
                        <i class="bi bi-building-fill"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="department-stat-card">

                <div class="department-stat-content">

                    <div>
                        <div class="department-stat-label">
                            Other Location Departments
                        </div>

                        <div class="department-stat-number">
                            {{ number_format($otherLocationDepartments) }}
                        </div>

                        <span class="department-stat-description">
                            Other CPC location types
                        </span>
                    </div>

                    <div class="department-stat-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="department-stat-card">

                <div class="department-stat-content">

                    <div>
                        <div class="department-stat-label">
                            Location Types
                        </div>

                        <div class="department-stat-number">
                            {{ number_format($locationTypes->count()) }}
                        </div>

                        <span class="department-stat-description">
                            Distinct CPC location classifications
                        </span>
                    </div>

                    <div class="department-stat-icon">
                        <i class="bi bi-collection-fill"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- DEPARTMENT DIRECTORY --}}

    <div class="department-content-card">

        {{-- TOOLBAR --}}

        <div class="department-toolbar">

            <div class="department-toolbar-title">

                <div class="department-toolbar-icon">
                    <i class="bi bi-list-ul"></i>
                </div>

                <div>
                    <h5>Department Directory</h5>

                    <p>
                        Official department records from the CPC database.
                    </p>
                </div>

            </div>


            {{-- SEARCH AND FILTER FORM --}}

            <form
                method="GET"
                action="{{ route('admin.departments.index') }}"
                class="department-toolbar-actions"
            >

                <div class="department-search">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search name, ID or location code..."
                        aria-label="Search CPC departments"
                    >

                </div>


                <select
                    name="location_type"
                    class="department-filter"
                    aria-label="Filter by location type"
                >

                    <option value="all">
                        All Location Types
                    </option>

                    @foreach($locationTypes as $type)

                        <option
                            value="{{ $type }}"
                            {{ $locationType === $type ? 'selected' : '' }}
                        >
                            {{ $type }}
                        </option>

                    @endforeach

                </select>


                <button
                    type="submit"
                    class="btn btn-primary"
                    style="height:39px; font-size:12px;"
                >
                    <i class="bi bi-search me-1"></i>
                    Search
                </button>


                @if($search !== '' || ($locationType !== '' && $locationType !== 'all'))

                    <a
                        href="{{ route('admin.departments.index') }}"
                        class="department-reset-link"
                    >
                        Reset
                    </a>

                @endif

            </form>

        </div>


        {{-- TABLE --}}

        @if($departments->count() > 0)

            <div class="department-table-wrapper">

                <table class="department-table">

                    <thead>
                        <tr>

                            <th style="width:90px;">
                                CPC ID
                            </th>

                            <th>
                                Department Name
                            </th>

                            <th style="width:190px;">
                                Location Code
                            </th>

                            <th style="width:190px;">
                                Location Type
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach($departments as $department)

                            <tr>

                                {{-- CPC ID --}}

                                <td>
                                    <span class="department-number">
                                        #{{ $department->Id }}
                                    </span>
                                </td>


                                {{-- DEPARTMENT NAME --}}

                                <td>

                                    <div class="department-identity">

                                        <div class="department-avatar">
                                            <i class="bi bi-building-fill"></i>
                                        </div>

                                        <div>

                                            <div class="department-name">
                                                {{ $department->Name ?: 'Unnamed Department' }}
                                            </div>

                                            <div class="department-subtitle">
                                                CPC Organizational Department
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- LOCATION CODE --}}

                                <td>

                                    @if($department->LocationCode)

                                        <span class="department-location-code">
                                            {{ $department->LocationCode }}
                                        </span>

                                    @else

                                        <span class="department-no-value">
                                            Not provided
                                        </span>

                                    @endif

                                </td>


                                {{-- LOCATION TYPE --}}

                                <td>

                                    @if($department->LocationType)

                                        <span class="department-location-type">

                                            <i class="bi bi-geo-alt-fill"></i>

                                            {{ $department->LocationType }}

                                        </span>

                                    @else

                                        <span class="department-no-value">
                                            Not specified
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- TABLE FOOTER --}}

            <div class="department-table-footer">

                <div class="department-record-count">

                    Showing

                    <strong>{{ $departments->firstItem() }}</strong>

                    to

                    <strong>{{ $departments->lastItem() }}</strong>

                    of

                    <strong>{{ number_format($departments->total()) }}</strong>

                    matching departments

                </div>


                <div class="department-pagination">

                    {{ $departments->onEachSide(1)->links('pagination::bootstrap-5') }}

                </div>

            </div>


        @else

            {{-- EMPTY STATE --}}

            <div class="department-empty">

                <div class="department-empty-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h4>No Departments Found</h4>

                <p>
                    No CPC department records match the current search
                    or location-type filter. Try changing your search
                    criteria or resetting the filters.
                </p>

                <div class="mt-3">

                    <a
                        href="{{ route('admin.departments.index') }}"
                        class="btn btn-outline-primary btn-sm"
                    >
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset Filters
                    </a>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection
