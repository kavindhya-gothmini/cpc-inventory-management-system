@extends('layouts.admin')

@section('title', 'Department Management | CPC Inventory Management System')

@section('page-heading', 'Department Management')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Department Page Role Access
    |--------------------------------------------------------------------------
    | SUPER ADMIN:
    |   - View
    |   - Search
    |   - Add
    |   - Edit
    |   - Deactivate
    |
    | ADMIN:
    |   - View
    |   - Search only
    |
    | EMPLOYEE:
    |   - View
    |   - Search only
    |--------------------------------------------------------------------------
    */

    $currentUser = auth()->user();

    $isAdmin = $currentUser
        && $currentUser->role === 'admin';

    $isSuperAdmin = $currentUser
        && $currentUser->role === 'super_admin';

    $isEmployee = $currentUser
        && $currentUser->role === 'employee';

    /*
     * Only Super Admin can manage department master data.
     */
    $canManageDepartments = $isSuperAdmin;
@endphp

<style>

/* =========================================================
   CPC DEPARTMENT MANAGEMENT
   Professional Enterprise UI
========================================================= */

.department-page {
    max-width: 1600px;
    margin: 0 auto;
    padding-bottom: 30px;
    color: #344054;
}


/* =========================================================
   BREADCRUMB
========================================================= */

.department-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    color: #98a2b3;
    font-size: 11px;
}

.department-breadcrumb .current {
    color: #344054;
    font-weight: 600;
}

.department-breadcrumb i {
    color: #b5bdc9;
    font-size: 8px;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.department-header {
    position: relative;
    overflow: hidden;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 25px;
    padding: 24px 27px;
    margin-bottom: 20px;

    background:
        linear-gradient(
            135deg,
            #ffffff 0%,
            #f8fbff 65%,
            #eef6ff 100%
        );

    border: 1px solid #e2e8f0;
    border-radius: 16px;

    box-shadow:
        0 5px 22px rgba(31, 45, 61, 0.045);
}

.department-header::after {
    content: "";

    position: absolute;

    width: 190px;
    height: 190px;

    right: -75px;
    top: -95px;

    border-radius: 50%;

    background: rgba(47, 128, 237, 0.055);

    pointer-events: none;
}

.department-header-content {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: center;

    gap: 15px;
}

.department-header-icon {
    width: 55px;
    height: 55px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    background:
        linear-gradient(
            135deg,
            #2f80ed,
            #4c96ee
        );

    color: #ffffff;

    font-size: 23px;

    box-shadow:
        0 7px 18px rgba(47, 128, 237, 0.20);
}

.department-header-text h1 {
    margin: 0;

    color: #172033;

    font-size: 23px;
    font-weight: 700;

    letter-spacing: -0.2px;
}

.department-header-text p {
    margin: 5px 0 0;

    color: #7d8798;

    font-size: 12px;
}

.department-header-context {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    margin-top: 8px;

    padding: 5px 9px;

    border-radius: 6px;

    background: #edf5ff;

    color: #2f70c9;

    font-size: 10px;
    font-weight: 600;
}

.department-header-action {
    position: relative;
    z-index: 3;
}

.department-add-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    min-height: 39px;

    padding: 0 15px;

    border-radius: 8px;

    font-size: 12px;
    font-weight: 600;

    box-shadow:
        0 5px 13px rgba(47, 128, 237, 0.15);
}


/* =========================================================
   READ-ONLY ACTIONS
========================================================= */

.department-disabled-action {
    opacity: 0.48;
    cursor: not-allowed !important;
    filter: grayscale(12%);
    transform: none !important;
    box-shadow: none !important;
    user-select: none;
}

.department-disabled-action:hover,
.department-disabled-action:focus {
    opacity: 0.48;
    transform: none !important;
    box-shadow: none !important;
}

.department-readonly-notice {
    display: flex;
    align-items: center;
    gap: 9px;

    margin-top: 12px;
    margin-bottom: 20px;

    padding: 9px 12px;

    border: 1px solid #dbe7f8;
    border-radius: 9px;

    background: #f5f9ff;

    color: #55708f;

    font-size: 11px;
    line-height: 1.5;
}

.department-readonly-notice i {
    color: #2f80ed;
    font-size: 14px;
}


/* =========================================================
   KPI CARDS
========================================================= */

.department-kpi {
    margin-bottom: 20px;
}

.department-stat-card {
    position: relative;
    overflow: hidden;

    height: 100%;
    min-height: 124px;

    padding: 18px;

    background: #ffffff;

    border: 1px solid #e4e9f0;
    border-radius: 13px;

    box-shadow:
        0 4px 18px rgba(31, 45, 61, 0.035);

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        border-color 0.2s ease;
}

.department-stat-card:hover {
    transform: translateY(-2px);

    border-color: #d7e2ef;

    box-shadow:
        0 8px 24px rgba(31, 45, 61, 0.07);
}

.department-stat-card::after {
    content: "";

    position: absolute;

    width: 95px;
    height: 95px;

    right: -40px;
    bottom: -45px;

    border-radius: 50%;

    background: rgba(47, 128, 237, 0.035);
}

.department-stat-content {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 12px;
}

.department-stat-label {
    margin-bottom: 7px;

    color: #8a94a6;

    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: 0.65px;
}

.department-stat-number {
    color: #172033;

    font-size: 27px;
    font-weight: 700;

    line-height: 1;
}

.department-stat-description {
    display: block;

    margin-top: 8px;

    color: #9aa3b2;

    font-size: 10px;
}

.department-stat-icon {
    width: 42px;
    height: 42px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #eaf3ff;
    color: #2f80ed;

    font-size: 17px;
}


/* =========================================================
   MAIN MANAGEMENT CARD
========================================================= */

.department-content-card {
    overflow: hidden;

    background: #ffffff;

    border: 1px solid #e2e7ee;
    border-radius: 15px;

    box-shadow:
        0 5px 22px rgba(31, 45, 61, 0.04);
}


/* =========================================================
   TOOLBAR
========================================================= */

.department-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 18px;

    padding: 18px 20px;

    border-bottom: 1px solid #edf0f4;
}

.department-toolbar-title {
    display: flex;
    align-items: center;

    gap: 11px;
}

.department-toolbar-icon {
    width: 34px;
    height: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: #edf5ff;
    color: #2f80ed;

    font-size: 14px;
}

.department-toolbar-title h5 {
    margin: 0;

    color: #172033;

    font-size: 14px;
    font-weight: 700;
}

.department-toolbar-title p {
    margin: 3px 0 0;

    color: #929bab;

    font-size: 10px;
}

.department-toolbar-actions {
    display: flex;
    align-items: center;

    gap: 8px;
}


/* =========================================================
   SEARCH
========================================================= */

.department-search {
    position: relative;

    width: 245px;
}

.department-search i {
    position: absolute;

    left: 12px;
    top: 50%;

    transform: translateY(-50%);

    color: #98a2b3;

    font-size: 12px;

    pointer-events: none;
}

.department-search input {
    width: 100%;
    height: 38px;

    padding: 0 12px 0 36px;

    background: #f9fafc;

    border: 1px solid #dfe5ed;
    border-radius: 8px;

    color: #344054;

    font-size: 11px;

    outline: none;

    transition:
        background 0.2s ease,
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.department-search input::placeholder {
    color: #a1a9b6;
}

.department-search input:focus {
    background: #ffffff;

    border-color: #2f80ed;

    box-shadow:
        0 0 0 3px rgba(47, 128, 237, 0.08);
}


/* =========================================================
   TABLE
========================================================= */

.department-table-wrapper {
    width: 100%;

    overflow-x: auto;
}

.department-table {
    width: 100%;

    min-width: 820px;

    border-collapse: collapse;
}

.department-table thead th {
    padding: 12px 18px;

    background: #f8fafc;

    border-bottom: 1px solid #e4e9ef;

    color: #7b8597;

    font-size: 9px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: 0.6px;

    white-space: nowrap;
}

.department-table tbody td {
    padding: 14px 18px;

    background: #ffffff;

    border-bottom: 1px solid #eef1f5;

    color: #475467;

    font-size: 11px;

    vertical-align: middle;
}

.department-table tbody tr {
    transition:
        background 0.15s ease,
        box-shadow 0.15s ease;
}

.department-table tbody tr:hover td {
    background: #f9fbff;
}

.department-table tbody tr:hover {
    box-shadow:
        inset 3px 0 0 #2f80ed;
}

.department-table tbody tr:last-child td {
    border-bottom: 0;
}


/* =========================================================
   RECORD NUMBER
========================================================= */

.department-number {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    min-width: 38px;
    height: 24px;

    padding: 0 7px;

    border-radius: 6px;

    background: #f5f7fa;

    border: 1px solid #e6eaf0;

    color: #8994a5;

    font-size: 9px;
    font-weight: 600;
}


/* =========================================================
   DEPARTMENT IDENTITY
========================================================= */

.department-identity {
    display: flex;
    align-items: center;

    gap: 11px;
}

.department-avatar {
    width: 39px;
    height: 39px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background:
        linear-gradient(
            135deg,
            #edf5ff,
            #e4f0ff
        );

    border: 1px solid #dfebf8;

    color: #2f80ed;

    font-size: 14px;
}

.department-name {
    color: #273142;

    font-size: 12px;
    font-weight: 700;

    line-height: 1.35;
}

.department-type {
    margin-top: 3px;

    color: #9aa3b2;

    font-size: 9px;
}


/* =========================================================
   DESCRIPTION
========================================================= */

.department-description {
    max-width: 430px;

    display: block;

    overflow: hidden;

    color: #667085;

    line-height: 1.45;

    text-overflow: ellipsis;

    white-space: nowrap;
}

.department-no-description {
    color: #a8b0bc;

    font-style: italic;

    font-size: 10px;
}


/* =========================================================
   ORGANIZATIONAL STATUS
========================================================= */

.department-scope {
    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 5px 9px;

    border-radius: 20px;

    background: #f1f6fc;

    border: 1px solid #dfeaf5;

    color: #50739a;

    font-size: 9px;
    font-weight: 650;

    white-space: nowrap;
}

.department-scope-dot {
    width: 6px;
    height: 6px;

    border-radius: 50%;

    background: #5b8fc9;
}


/* =========================================================
   ACTIONS
========================================================= */

.department-actions {
    display: flex;

    align-items: center;
    justify-content: flex-end;

    gap: 5px;
}

.department-action {
    width: 30px;
    height: 30px;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    border-radius: 7px;

    background: #ffffff;

    font-size: 11px;

    transition:
        transform 0.15s ease,
        background 0.15s ease,
        border-color 0.15s ease;
}

.department-action:hover {
    transform: translateY(-1px);
}

.department-action-view {
    border: 1px solid #cfe0f8;

    color: #2f80ed;
}

.department-action-view:hover {
    background: #eaf3ff;
}

.department-action-edit {
    border: 1px solid #d7e3ed;

    color: #557287;
}

.department-action-edit:hover {
    background: #f1f7fa;

    border-color: #c7dbe6;

    color: #1683a7;
}

.department-action-delete {
    border: 1px solid #f0caca;

    color: #c0392b;
}

.department-action-delete:hover {
    background: #fff1f1;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.department-empty {
    padding: 75px 20px;

    text-align: center;
}

.department-empty-icon {
    width: 82px;
    height: 82px;

    margin: 0 auto 18px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 22px;

    background: #edf5ff;

    color: #2f80ed;

    font-size: 32px;

    box-shadow:
        0 9px 22px rgba(47, 128, 237, 0.07);
}

.department-empty h4 {
    margin: 0 0 7px;

    color: #344054;

    font-size: 16px;
    font-weight: 700;
}

.department-empty p {
    max-width: 440px;

    margin: 0 auto 20px;

    color: #8b96a8;

    font-size: 11px;

    line-height: 1.7;
}


/* =========================================================
   SEARCH EMPTY
========================================================= */

.department-search-empty {
    display: none;

    padding: 50px 20px;

    text-align: center;

    color: #8b96a8;
}

.department-search-empty-icon {
    width: 58px;
    height: 58px;

    margin: 0 auto 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background: #f4f6f9;

    color: #98a2b3;

    font-size: 22px;
}

.department-search-empty strong {
    display: block;

    margin-bottom: 5px;

    color: #475467;

    font-size: 13px;
}

.department-search-empty span {
    font-size: 10px;
}


/* =========================================================
   TABLE FOOTER
========================================================= */

.department-table-footer {
    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 14px 18px;

    border-top: 1px solid #edf0f4;

    background: #fcfdfe;
}

.department-record-count {
    color: #8b96a8;

    font-size: 10px;
}

.department-record-count strong {
    color: #475467;
}


/* =========================================================
   PAGINATION
========================================================= */

.department-pagination .pagination {
    margin: 0;

    display: flex;

    gap: 3px;
}

.department-pagination .page-link {
    min-width: 30px;
    height: 30px;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    padding: 0 8px;

    border: 1px solid #dfe5ec;

    border-radius: 7px !important;

    background: #ffffff;

    color: #667085;

    font-size: 10px;

    box-shadow: none;
}

.department-pagination .page-link:hover {
    background: #f3f7fc;

    border-color: #cfe0f8;

    color: #2f80ed;
}

.department-pagination .page-item.active .page-link {
    background: #2f80ed;

    border-color: #2f80ed;

    color: #ffffff;

    box-shadow:
        0 3px 8px rgba(47, 128, 237, 0.16);
}

.department-pagination .page-item.disabled .page-link {
    background: #f7f9fb;

    border-color: #e8ebef;

    color: #b5bdc8;
}

.department-pagination svg {
    width: 13px;
    height: 13px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .department-stat-card {
        min-height: 115px;
    }

}


@media (max-width: 850px) {

    .department-header {
        align-items: flex-start;

        flex-direction: column;
    }

    .department-header-action {
        width: 100%;
    }

    .department-add-button {
        width: 100%;
    }

    .department-toolbar {
        align-items: flex-start;

        flex-direction: column;
    }

    .department-toolbar-actions {
        width: 100%;
    }

    .department-search {
        flex: 1;

        width: auto;
    }

}


@media (max-width: 768px) {

    .department-header {
        padding: 20px;
    }

    .department-header-icon {
        width: 48px;
        height: 48px;

        font-size: 20px;
    }

    .department-header-text h1 {
        font-size: 20px;
    }

    .department-header-text p {
        line-height: 1.5;
    }

}


@media (max-width: 576px) {

    .department-page {
        padding-bottom: 20px;
    }

    .department-toolbar-actions {
        align-items: stretch;

        flex-direction: column;
    }

    .department-search {
        width: 100%;
    }

    .department-table-footer {
        align-items: flex-start;

        flex-direction: column;
    }

    .department-pagination {
        width: 100%;

        overflow-x: auto;
    }

}


@media (max-width: 450px) {

    .department-header {
        padding: 17px;
    }

    .department-header-content {
        gap: 11px;
    }

    .department-header-text h1 {
        font-size: 18px;
    }

    .department-header-text p {
        font-size: 10px;
    }

    .department-stat-card {
        min-height: 110px;

        padding: 15px;
    }

    .department-stat-number {
        font-size: 24px;
    }

    .department-toolbar {
        padding: 15px;
    }

    .department-table thead th,
    .department-table tbody td {
        padding-left: 13px;
        padding-right: 13px;
    }

}

</style>


<div class="department-page">


{{-- =====================================================
     BREADCRUMB
====================================================== --}}

<div class="department-breadcrumb">

    <span>
        Inventory
    </span>

    <i class="bi bi-chevron-right"></i>

    <span class="current">
        Department Management
    </span>

</div>


{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="department-header">

    <div class="department-header-content">

        <div class="department-header-icon">

            <i class="bi bi-diagram-3-fill"></i>

        </div>


        <div class="department-header-text">

            <h1>
                Department Management
            </h1>

            <p>
                Manage CPC departments used for organizational
                structure and IT inventory assignment.
            </p>


            <div class="department-header-context">

                <i class="bi bi-box-seam-fill"></i>

                Inventory Configuration

            </div>

        </div>

    </div>


    <div class="department-header-action">

        @if($canManageDepartments)

            <a
                href="{{ route('admin.departments.create') }}"
                class="btn btn-primary department-add-button"
            >

                <i class="bi bi-plus-lg"></i>

                Add New Department

            </a>

        @else

            <button
                type="button"
                class="btn btn-primary department-add-button department-disabled-action"
                onclick="departmentReadOnlyMessage()"
                title="Only Super Admin can manage departments"
                aria-disabled="true"
            >

                <i class="bi bi-plus-lg"></i>

                Add New Department

            </button>

        @endif

    </div>

</div>


{{-- =====================================================
     READ-ONLY NOTICE
====================================================== --}}

@if(!$canManageDepartments)

    <div class="department-readonly-notice">

        <i class="bi bi-info-circle-fill"></i>

        <span>
            Only Super Admin can add, edit, or deactivate departments.
            Admin and Employee users have view and search access only.
        </span>

    </div>

@endif


{{-- =====================================================
     SUCCESS ALERT
====================================================== --}}

@if(session('success'))

    <div
        class="alert alert-success alert-dismissible fade show"
        role="alert"
    >

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>

    </div>

@endif


{{-- =====================================================
     KPI CARDS
====================================================== --}}

<div class="row g-3 department-kpi">


    {{-- TOTAL DEPARTMENTS --}}

    <div class="col-xl-3 col-md-6">

        <div class="department-stat-card">

            <div class="department-stat-content">

                <div>

                    <div class="department-stat-label">
                        Total Departments
                    </div>

                    <div class="department-stat-number">
                        {{ $departments->total() }}
                    </div>

                    <span class="department-stat-description">
                        Registered CPC departments
                    </span>

                </div>


                <div class="department-stat-icon">

                    <i class="bi bi-diagram-3-fill"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- DISPLAYED --}}

    <div class="col-xl-3 col-md-6">

        <div class="department-stat-card">

            <div class="department-stat-content">

                <div>

                    <div class="department-stat-label">
                        Displayed Records
                    </div>

                    <div class="department-stat-number">
                        {{ $departments->count() }}
                    </div>

                    <span class="department-stat-description">
                        Records on current page
                    </span>

                </div>


                <div class="department-stat-icon">

                    <i class="bi bi-list-ul"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- PAGE CAPACITY --}}

    <div class="col-xl-3 col-md-6">

        <div class="department-stat-card">

            <div class="department-stat-content">

                <div>

                    <div class="department-stat-label">
                        Page Capacity
                    </div>

                    <div class="department-stat-number">
                        {{ $departments->perPage() }}
                    </div>

                    <span class="department-stat-description">
                        Records per page
                    </span>

                </div>


                <div class="department-stat-icon">

                    <i class="bi bi-grid-3x3-gap-fill"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- CURRENT PAGE --}}

    <div class="col-xl-3 col-md-6">

        <div class="department-stat-card">

            <div class="department-stat-content">

                <div>

                    <div class="department-stat-label">
                        Current Page
                    </div>

                    <div class="department-stat-number">
                        {{ $departments->currentPage() }}
                    </div>

                    <span class="department-stat-description">
                        Active directory page
                    </span>

                </div>


                <div class="department-stat-icon">

                    <i class="bi bi-file-earmark-text-fill"></i>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     DEPARTMENT DIRECTORY
====================================================== --}}

<div class="department-content-card">


    {{-- =================================================
         TOOLBAR
    ================================================== --}}

    <div class="department-toolbar">


        <div class="department-toolbar-title">

            <div class="department-toolbar-icon">

                <i class="bi bi-collection-fill"></i>

            </div>


            <div>

                <h5>
                    Department Directory
                </h5>

                <p>
                    View, search and manage registered CPC departments.
                </p>

            </div>

        </div>


        <div class="department-toolbar-actions">


            {{-- SEARCH --}}

            <div class="department-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="departmentSearch"
                    placeholder="Search department or description..."
                    autocomplete="off"
                    aria-label="Search departments"
                >

            </div>


            {{-- ADD BUTTON --}}

            @if($canManageDepartments)

                <a
                    href="{{ route('admin.departments.create') }}"
                    class="btn btn-primary"
                    style="
                        height: 38px;
                        width: 38px;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 8px;
                    "
                    title="Add Department"
                    aria-label="Add Department"
                >

                    <i class="bi bi-plus-lg"></i>

                </a>

            @else

                <button
                    type="button"
                    class="btn btn-primary department-disabled-action"
                    style="
                        height: 38px;
                        width: 38px;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 8px;
                    "
                    onclick="departmentReadOnlyMessage()"
                    title="Only Super Admin can manage departments"
                    aria-label="Add Department"
                    aria-disabled="true"
                >

                    <i class="bi bi-plus-lg"></i>

                </button>

            @endif

        </div>

    </div>


    {{-- =================================================
         TABLE
    ================================================== --}}

    @if($departments->count() > 0)

        <div class="department-table-wrapper">

            <table
                class="department-table"
                id="departmentsTable"
            >

                <thead>

                    <tr>

                        <th style="width: 70px;">
                            #
                        </th>

                        <th style="width: 300px;">
                            Department
                        </th>

                        <th>
                            Description
                        </th>

                        <th style="width: 130px;">
                            Scope
                        </th>

                        <th
                            style="
                                width: 130px;
                                text-align: right;
                            "
                        >
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody id="departmentTableBody">


                    @foreach($departments as $department)

                        <tr>


                            {{-- NUMBER --}}

                            <td>

                                <span class="department-number">

                                    #{{ $department->id }}

                                </span>

                            </td>


                            {{-- DEPARTMENT --}}

                            <td>

                                <div class="department-identity">


                                    <div class="department-avatar">

                                        <i class="bi bi-building-fill"></i>

                                    </div>


                                    <div>

                                        <div class="department-name">

                                            {{ $department->name }}

                                        </div>


                                        <div class="department-type">

                                            CPC Organizational Department

                                        </div>

                                    </div>


                                </div>

                            </td>


                            {{-- DESCRIPTION --}}

                            <td>

                                @if($department->description)

                                    <span
                                        class="department-description"
                                        title="{{ $department->description }}"
                                    >

                                        {{ $department->description }}

                                    </span>

                                @else

                                    <span class="department-no-description">

                                        No description provided

                                    </span>

                                @endif

                            </td>


                            {{-- SCOPE --}}

                            <td>

                                <span class="department-scope">

                                    <span class="department-scope-dot"></span>

                                    CPC Department

                                </span>

                            </td>


                            {{-- ACTIONS --}}

                            <td>

                                <div class="department-actions">


                                    {{-- VIEW --}}

                                    @if(Route::has('admin.departments.show'))

                                        <a
                                            href="{{ route(
                                                'admin.departments.show',
                                                $department
                                            ) }}"
                                            class="department-action department-action-view"
                                            title="View Department"
                                            aria-label="View Department"
                                        >

                                            <i class="bi bi-eye"></i>

                                        </a>

                                    @endif


                                    {{-- EDIT --}}

                                    @if(Route::has('admin.departments.edit'))

                                        @if($canManageDepartments)

                                            <a
                                                href="{{ route(
                                                    'admin.departments.edit',
                                                    $department
                                                ) }}"
                                                class="department-action department-action-edit"
                                                title="Edit Department"
                                                aria-label="Edit Department"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </a>

                                        @else

                                            <button
                                                type="button"
                                                class="department-action department-action-edit department-disabled-action"
                                                title="Only Super Admin can edit departments"
                                                aria-label="Edit Department"
                                                aria-disabled="true"
                                                onclick="departmentReadOnlyMessage()"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </button>

                                        @endif

                                    @endif


                                    {{-- DEACTIVATE --}}

                                    @if(Route::has('admin.departments.destroy'))

                                        @if($canManageDepartments)

                                            <form
                                                action="{{ route(
                                                    'admin.departments.destroy',
                                                    $department
                                                ) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="
                                                    return confirm(
                                                        'Are you sure you want to deactivate this department?'
                                                    );
                                                "
                                            >

                                                @csrf

                                                @method('DELETE')


                                                <button
                                                    type="submit"
                                                    class="department-action department-action-delete"
                                                    title="Deactivate Department"
                                                    aria-label="Deactivate Department"
                                                >

                                                    <i class="bi bi-trash3"></i>

                                                </button>

                                            </form>

                                        @else

                                            <button
                                                type="button"
                                                class="department-action department-action-delete department-disabled-action"
                                                title="Only Super Admin can deactivate departments"
                                                aria-label="Deactivate Department"
                                                aria-disabled="true"
                                                onclick="departmentReadOnlyMessage()"
                                            >

                                                <i class="bi bi-trash3"></i>

                                            </button>

                                        @endif

                                    @endif


                                </div>

                            </td>


                        </tr>

                    @endforeach


                </tbody>

            </table>


            {{-- =================================================
                 SEARCH EMPTY
            ================================================== --}}

            <div
                id="departmentSearchEmpty"
                class="department-search-empty"
            >

                <div class="department-search-empty-icon">

                    <i class="bi bi-search"></i>

                </div>


                <strong>
                    No matching departments
                </strong>


                <span>
                    Try another department name or description.
                </span>

            </div>


        </div>


        {{-- =================================================
             TABLE FOOTER
        ================================================== --}}

        <div class="department-table-footer">


            <div class="department-record-count">

                Showing

                <strong>
                    {{ $departments->firstItem() }}
                </strong>

                –

                <strong>
                    {{ $departments->lastItem() }}
                </strong>

                of

                <strong>
                    {{ $departments->total() }}
                </strong>

                departments

            </div>


            <div class="department-pagination">

                {{
                    $departments
                        ->onEachSide(1)
                        ->links('pagination::bootstrap-5')
                }}

            </div>


        </div>


    @else


        {{-- =================================================
             EMPTY STATE
        ================================================== --}}

        <div class="department-empty">


            <div class="department-empty-icon">

                <i class="bi bi-diagram-3"></i>

            </div>


            <h4>
                No Departments Registered
            </h4>


            <p>
                There are currently no CPC department records
                available. Add a department to begin organizing
                inventory and organizational assignments.
            </p>


            @if($canManageDepartments)

                <a
                    href="{{ route('admin.departments.create') }}"
                    class="btn btn-primary"
                >

                    <i class="bi bi-plus-lg me-1"></i>

                    Register First Department

                </a>

            @else

                <button
                    type="button"
                    class="btn btn-primary department-disabled-action"
                    onclick="departmentReadOnlyMessage()"
                    title="Only Super Admin can manage departments"
                    aria-disabled="true"
                >

                    <i class="bi bi-plus-lg me-1"></i>

                    Register First Department

                </button>

            @endif


        </div>

    @endif


</div>


</div>


{{-- =========================================================
CLIENT-SIDE SEARCH + READ-ONLY MESSAGE
========================================================= --}}

@push('scripts')

<script>

function departmentReadOnlyMessage()
{
    alert(
        'Only Super Admin can add, edit, or deactivate departments. Admin and Employee users have view and search access only.'
    );
}


document.addEventListener('DOMContentLoaded', function () {


    const searchInput =
        document.getElementById('departmentSearch');


    const table =
        document.getElementById('departmentsTable');


    const searchEmpty =
        document.getElementById('departmentSearchEmpty');


    if (!searchInput || !table) {
        return;
    }


    const rows =
        table.querySelectorAll('tbody tr');


    function searchDepartments() {


        const searchValue =
            searchInput.value
                .toLowerCase()
                .trim();


        let visibleRows = 0;


        rows.forEach(function (row) {


            const rowText =
                row.textContent.toLowerCase();


            if (rowText.includes(searchValue)) {

                row.style.display = '';

                visibleRows++;

            } else {

                row.style.display = 'none';

            }

        });


        if (searchEmpty) {

            searchEmpty.style.display =
                visibleRows === 0
                    ? 'block'
                    : 'none';

        }

    }


    searchInput.addEventListener(
        'input',
        searchDepartments
    );


});

</script>

@endpush

@endsection