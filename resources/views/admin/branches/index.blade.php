@extends('layouts.admin')

@section('title', 'Branch Management | CPC Inventory Management System')

@section('page-heading', 'Branch Management')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| Branch Page Role Access
|--------------------------------------------------------------------------
| SUPER ADMIN = FULL MANAGEMENT ACCESS
| ADMIN       = VIEW + SEARCH ONLY
| EMPLOYEE    = VIEW + SEARCH ONLY
|--------------------------------------------------------------------------
*/

$currentUser = auth()->user();

$isSuperAdmin = $currentUser
    && $currentUser->role === 'super_admin';

$isAdmin = $currentUser
    && $currentUser->role === 'admin';

$isEmployee = $currentUser
    && $currentUser->role === 'employee';

/*
 * Only Super Admin can manage Branch master data.
 *
 * Admin and Employee:
 * - View
 * - Search
 *
 * Super Admin:
 * - View
 * - Search
 * - Add
 * - Edit
 * - Deactivate
 * - Reactivate
 */
$canManageBranches = $isSuperAdmin;

$isReadOnlyUser = $isAdmin || $isEmployee;
@endphp

<style>

/* =========================================================
   BRANCH MANAGEMENT
========================================================= */

.branch-page {
    max-width: 1600px;
    margin: 0 auto;
    padding-bottom: 30px;
}


/* =========================================================
   BREADCRUMB
========================================================= */

.branch-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    color: #98a2b3;
    font-size: 11px;
}

.branch-breadcrumb .current {
    color: #344054;
    font-weight: 600;
}

.branch-breadcrumb i {
    font-size: 8px;
    color: #b5bdc9;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.branch-header {
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

.branch-header::after {
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

.branch-header-content {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: center;

    gap: 15px;
}

.branch-header-icon {
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

.branch-header-text h1 {
    margin: 0;

    color: #172033;

    font-size: 23px;
    font-weight: 700;
    letter-spacing: -0.2px;
}

.branch-header-text p {
    margin: 5px 0 0;

    color: #7d8798;

    font-size: 12px;
}

.branch-header-context {
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

.branch-header-action {
    position: relative;
    z-index: 3;
}

.branch-add-button {
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

.branch-disabled-action {
    opacity: 0.48;
    cursor: not-allowed !important;
    filter: grayscale(12%);
    transform: none !important;
    box-shadow: none !important;
    user-select: none;
}

.branch-disabled-action:hover,
.branch-disabled-action:focus,
.branch-disabled-action:active {
    opacity: 0.48;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

.branch-readonly-notice {
    display: flex;
    align-items: center;
    gap: 9px;

    margin-top: 12px;

    padding: 9px 12px;

    border: 1px solid #dbe7f8;
    border-radius: 9px;

    background: #f5f9ff;

    color: #55708f;

    font-size: 11px;
    line-height: 1.5;
}

.branch-readonly-notice i {
    color: #2f80ed;
    font-size: 14px;
}


/* =========================================================
   KPI SECTION
========================================================= */

.branch-kpi {
    margin-bottom: 20px;
}

.branch-stat-card {
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

.branch-stat-card:hover {
    transform: translateY(-2px);

    border-color: #d7e2ef;

    box-shadow:
        0 8px 24px rgba(31, 45, 61, 0.07);
}

.branch-stat-card::after {
    content: "";

    position: absolute;

    width: 95px;
    height: 95px;

    right: -40px;
    bottom: -45px;

    border-radius: 50%;

    background: rgba(47, 128, 237, 0.035);
}

.branch-stat-content {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 12px;
}

.branch-stat-label {
    margin-bottom: 7px;

    color: #8a94a6;

    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: 0.65px;
}

.branch-stat-number {
    color: #172033;

    font-size: 27px;
    font-weight: 700;

    line-height: 1;
}

.branch-stat-description {
    display: block;

    margin-top: 8px;

    color: #9aa3b2;

    font-size: 10px;
}

.branch-stat-icon {
    width: 42px;
    height: 42px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    font-size: 17px;
}

.stat-blue {
    background: #eaf3ff;
    color: #2f80ed;
}

.stat-green {
    background: #e9f7ef;
    color: #218653;
}

.stat-red {
    background: #ffebeb;
    color: #c0392b;
}

.stat-purple {
    background: #f1edff;
    color: #7357c7;
}


/* =========================================================
   MAIN CARD
========================================================= */

.branch-content-card {
    overflow: hidden;

    background: #ffffff;

    border: 1px solid #e2e7ee;
    border-radius: 15px;

    box-shadow:
        0 5px 22px rgba(31, 45, 61, 0.04);
}


/* =========================================================
   TABLE HEADER
========================================================= */

.branch-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 18px;

    padding: 18px 20px;

    border-bottom: 1px solid #edf0f4;
}

.branch-toolbar-title {
    display: flex;
    align-items: center;

    gap: 11px;
}

.branch-toolbar-title-icon {
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

.branch-toolbar-title h5 {
    margin: 0;

    color: #172033;

    font-size: 14px;
    font-weight: 700;
}

.branch-toolbar-title p {
    margin: 3px 0 0;

    color: #929bab;

    font-size: 10px;
}


/* =========================================================
   TOOLBAR ACTIONS
========================================================= */

.branch-toolbar-actions {
    display: flex;
    align-items: center;

    gap: 8px;
}

.branch-search {
    position: relative;

    width: 245px;
}

.branch-search input {
    width: 100%;
    height: 38px;

    padding:
        0 12px 0 36px;

    background: #f9fafc;

    border: 1px solid #dfe5ed;
    border-radius: 8px;

    color: #344054;

    font-size: 11px;

    outline: none;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        background 0.2s ease;
}

.branch-search input::placeholder {
    color: #a1a9b6;
}

.branch-search input:focus {
    background: #ffffff;

    border-color: #2f80ed;

    box-shadow:
        0 0 0 3px rgba(47, 128, 237, 0.08);
}

.branch-search i {
    position: absolute;

    left: 12px;
    top: 12px;

    color: #98a2b3;

    font-size: 12px;

    pointer-events: none;
}

.branch-filter {
    height: 38px;

    min-width: 125px;

    padding: 0 30px 0 10px;

    background: #f9fafc;

    border: 1px solid #dfe5ed;
    border-radius: 8px;

    color: #475467;

    font-size: 11px;

    outline: none;

    cursor: pointer;
}

.branch-filter:focus {
    border-color: #2f80ed;

    box-shadow:
        0 0 0 3px rgba(47, 128, 237, 0.07);
}

.branch-quick-add {
    width: 38px;
    height: 38px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    font-size: 13px;
}


/* =========================================================
   TABLE
========================================================= */

.branch-table-wrapper {
    width: 100%;

    overflow-x: auto;
}

.branch-table {
    width: 100%;
    min-width: 920px;

    border-collapse: collapse;
}

.branch-table thead th {
    padding: 11px 18px;

    background: #f8fafc;

    border-bottom: 1px solid #e4e9ef;

    color: #7b8597;

    font-size: 9px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: 0.6px;

    white-space: nowrap;
}

.branch-table tbody td {
    padding: 14px 18px;

    border-bottom: 1px solid #eef1f5;

    color: #344054;

    font-size: 11px;

    vertical-align: middle;
}

.branch-table tbody tr {
    transition:
        background 0.15s ease,
        box-shadow 0.15s ease;
}

.branch-table tbody tr:hover {
    background: #f9fbff;

    box-shadow:
        inset 3px 0 0 #2f80ed;
}

.branch-table tbody tr:last-child td {
    border-bottom: 0;
}


/* =========================================================
   NUMBER
========================================================= */

.branch-number {
    color: #98a2b3;

    font-size: 10px;
    font-weight: 600;
}


/* =========================================================
   BRANCH IDENTITY
========================================================= */

.branch-identity {
    display: flex;
    align-items: center;

    gap: 11px;
}

.branch-avatar {
    width: 38px;
    height: 38px;

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

    color: #2f80ed;

    font-size: 14px;
}

.branch-name {
    color: #1f2937;

    font-size: 12px;
    font-weight: 650;

    line-height: 1.35;
}

.branch-type {
    margin-top: 3px;

    color: #9aa3b2;

    font-size: 9px;
}


/* =========================================================
   CODE
========================================================= */

.branch-code {
    display: inline-flex;
    align-items: center;

    padding: 5px 8px;

    background: #f5f7fa;

    border: 1px solid #e5e9ef;
    border-radius: 5px;

    color: #475467;

    font-family: Consolas, Monaco, monospace;

    font-size: 10px;
    font-weight: 600;
}

.no-value {
    color: #a5adba;

    font-size: 10px;
}


/* =========================================================
   LOCATION
========================================================= */

.branch-location {
    display: flex;
    align-items: center;

    gap: 7px;

    color: #475467;

    font-size: 11px;
}

.branch-location i {
    color: #2f80ed;

    font-size: 12px;
}


/* =========================================================
   STATUS
========================================================= */

.branch-status {
    display: inline-flex;
    align-items: center;

    gap: 6px;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 9px;
    font-weight: 700;
}

.branch-status.active {
    background: #e9f7ef;
    color: #218653;
}

.branch-status.inactive {
    background: #ffebeb;
    color: #c0392b;
}

.status-dot {
    width: 6px;
    height: 6px;

    border-radius: 50%;

    background: currentColor;
}


/* =========================================================
   ACTIONS
========================================================= */

.branch-actions {
    display: flex;
    align-items: center;

    gap: 5px;
}

.branch-action {
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

.branch-action:hover {
    transform: translateY(-1px);
}

.branch-action-view {
    border: 1px solid #cfe0f8;
    color: #2f80ed;
}

.branch-action-view:hover {
    background: #eaf3ff;
}

.branch-action-edit {
    border: 1px solid #d9dee7;
    color: #667085;
}

.branch-action-edit:hover {
    background: #f4f6f9;
}

.branch-action-danger {
    border: 1px solid #f0caca;
    color: #c0392b;
}

.branch-action-danger:hover {
    background: #fff1f1;
}

.branch-action-success {
    border: 1px solid #c7e6d3;
    color: #218653;
}

.branch-action-success:hover {
    background: #eefaf3;
}


/* =========================================================
   SEARCH EMPTY
========================================================= */

.search-empty {
    display: none;

    text-align: center;

    padding: 55px 20px;

    color: #8b96a8;
}

.search-empty-icon {
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

.search-empty strong {
    display: block;

    margin-bottom: 5px;

    color: #475467;

    font-size: 13px;
}

.search-empty span {
    font-size: 10px;
}


/* =========================================================
   TABLE FOOTER
========================================================= */

.branch-table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 14px 18px;

    border-top: 1px solid #edf0f4;

    background: #ffffff;
}

.branch-record-count {
    color: #8b96a8;

    font-size: 10px;
}

.branch-record-count strong {
    color: #475467;
}


/* =========================================================
   PAGINATION
========================================================= */

.branch-table-footer nav {
    margin: 0;
}

.branch-table-footer .pagination {
    margin: 0;
    gap: 3px;
}

.branch-table-footer .page-link {
    min-width: 30px;
    height: 30px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 8px;

    border: 1px solid #e1e6ed;

    border-radius: 6px !important;

    color: #667085;

    background: #ffffff;

    font-size: 10px;
}

.branch-table-footer .page-item.active .page-link {
    background: #2f80ed;

    border-color: #2f80ed;

    color: #ffffff;
}

.branch-table-footer .page-link:hover {
    background: #f4f8ff;

    border-color: #cfe0f8;

    color: #2f80ed;
}


/* =========================================================
   EMPTY DATABASE STATE
========================================================= */

.branch-empty {
    text-align: center;

    padding: 75px 20px;
}

.branch-empty-icon {
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

.branch-empty h4 {
    margin: 0 0 7px;

    color: #344054;

    font-size: 16px;
    font-weight: 700;
}

.branch-empty p {
    max-width: 440px;

    margin: 0 auto 20px;

    color: #8b96a8;

    font-size: 11px;

    line-height: 1.7;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 992px) {

    .branch-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .branch-header-action {
        width: 100%;
    }

    .branch-add-button {
        width: 100%;
    }

    .branch-toolbar {
        align-items: flex-start;
        flex-direction: column;
    }

    .branch-toolbar-actions {
        width: 100%;
    }

    .branch-search {
        flex: 1;
        width: auto;
    }

}


@media (max-width: 768px) {

    .branch-page {
        padding-bottom: 20px;
    }

    .branch-header {
        padding: 20px;
    }

    .branch-header-content {
        align-items: flex-start;
    }

    .branch-header-icon {
        width: 48px;
        height: 48px;

        font-size: 20px;
    }

    .branch-header-text h1 {
        font-size: 20px;
    }

    .branch-header-text p {
        line-height: 1.5;
    }

    .branch-toolbar-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .branch-search {
        width: 100%;
    }

    .branch-filter {
        width: 100%;
    }

    .branch-quick-add {
        width: 100%;
    }

    .branch-table-footer {
        align-items: flex-start;
        flex-direction: column;
    }

}


@media (max-width: 480px) {

    .branch-breadcrumb {
        font-size: 10px;
    }

    .branch-header {
        padding: 17px;
    }

    .branch-header-content {
        gap: 11px;
    }

    .branch-header-text h1 {
        font-size: 18px;
    }

    .branch-header-text p {
        font-size: 10px;
    }

    .branch-stat-card {
        min-height: 115px;
        padding: 15px;
    }

    .branch-stat-number {
        font-size: 24px;
    }

    .branch-stat-icon {
        width: 38px;
        height: 38px;
    }

    .branch-toolbar {
        padding: 15px;
    }

}


/* =========================================================
   ACCESSIBILITY
========================================================= */

.branch-action:focus-visible,
.branch-add-button:focus-visible,
.branch-search input:focus-visible,
.branch-filter:focus-visible {
    outline: 2px solid rgba(47, 128, 237, 0.35);
    outline-offset: 2px;
}

</style>


<div class="branch-page">

    {{-- =====================================================
         BREADCRUMB
    ====================================================== --}}

    <div class="branch-breadcrumb">

        <span>
            Inventory
        </span>

        <i class="bi bi-chevron-right"></i>

        <span class="current">
            Branch Management
        </span>

    </div>


    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="branch-header">

        <div class="branch-header-content">

            <div class="branch-header-icon">
                <i class="bi bi-buildings-fill"></i>
            </div>

            <div class="branch-header-text">

                <h1>
                    Branch Management
                </h1>

                <p>
                    Manage CPC branch locations used for IT asset allocation and inventory tracking.
                </p>

                <div class="branch-header-context">

                    <i class="bi bi-box-seam-fill"></i>

                    Inventory Configuration

                </div>

                {{-- READ-ONLY NOTICE FOR ADMIN + EMPLOYEE --}}

                @if($isReadOnlyUser)

                    <div class="branch-readonly-notice">

                        <i class="bi bi-info-circle-fill"></i>

                        <span>
                            You have view and search access only.
                            Branch management actions are available to Super Admin only.
                        </span>

                    </div>

                @endif

            </div>

        </div>


        {{-- =================================================
             ADD BRANCH
             SUPER ADMIN = ENABLED
             ADMIN + EMPLOYEE = DISABLED
        ================================================== --}}

        <div class="branch-header-action">

            @if($canManageBranches)

                <a
                    href="{{ route('admin.branches.create') }}"
                    class="btn btn-primary branch-add-button"
                >

                    <i class="bi bi-plus-lg"></i>

                    Add New Branch

                </a>

            @else

                <button
                    type="button"
                    class="btn btn-primary branch-add-button branch-disabled-action"
                    title="Only Super Admin can add branches"
                    aria-label="Add New Branch - disabled"
                    onclick="branchReadOnlyMessage()"
                >

                    <i class="bi bi-plus-lg"></i>

                    Add New Branch

                </button>

            @endif

        </div>

    </div>


    {{-- =====================================================
         KPI CARDS
    ====================================================== --}}

    <div class="row g-3 branch-kpi">


        {{-- TOTAL --}}

        <div class="col-xl-3 col-md-6">

            <div class="branch-stat-card">

                <div class="branch-stat-content">

                    <div>

                        <div class="branch-stat-label">
                            Total Branches
                        </div>

                        <div class="branch-stat-number">
                            {{ $branches->total() }}
                        </div>

                        <span class="branch-stat-description">
                            Registered CPC locations
                        </span>

                    </div>

                    <div class="branch-stat-icon stat-blue">
                        <i class="bi bi-buildings-fill"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- ACTIVE --}}

        <div class="col-xl-3 col-md-6">

            <div class="branch-stat-card">

                <div class="branch-stat-content">

                    <div>

                        <div class="branch-stat-label">
                            Active Branches
                        </div>

                        <div class="branch-stat-number">
                            {{ $branches->where('is_active', true)->count() }}
                        </div>

                        <span class="branch-stat-description">
                            Currently operational
                        </span>

                    </div>

                    <div class="branch-stat-icon stat-green">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- INACTIVE --}}

        <div class="col-xl-3 col-md-6">

            <div class="branch-stat-card">

                <div class="branch-stat-content">

                    <div>

                        <div class="branch-stat-label">
                            Inactive Branches
                        </div>

                        <div class="branch-stat-number">
                            {{ $branches->where('is_active', false)->count() }}
                        </div>

                        <span class="branch-stat-description">
                            Currently deactivated
                        </span>

                    </div>

                    <div class="branch-stat-icon stat-red">
                        <i class="bi bi-archive-fill"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- SYSTEM SCOPE --}}

        <div class="col-xl-3 col-md-6">

            <div class="branch-stat-card">

                <div class="branch-stat-content">

                    <div>

                        <div class="branch-stat-label">
                            System Scope
                        </div>

                        <div class="branch-stat-number">
                            CPC
                        </div>

                        <span class="branch-stat-description">
                            IT inventory locations
                        </span>

                    </div>

                    <div class="branch-stat-icon stat-purple">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MAIN CONTENT CARD
    ====================================================== --}}

    <div class="branch-content-card">

        {{-- =================================================
             TOOLBAR
        ================================================== --}}

        <div class="branch-toolbar">

            <div class="branch-toolbar-title">

                <div class="branch-toolbar-title-icon">

                    <i class="bi bi-list-ul"></i>

                </div>

                <div>

                    <h5>
                        Branch Directory
                    </h5>

                    <p>
                        CPC locations available for inventory assignment
                    </p>

                </div>

            </div>


            <div class="branch-toolbar-actions">


                {{-- SEARCH --}}

                <div class="branch-search">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        id="branchSearch"
                        placeholder="Search branch, code or location..."
                        autocomplete="off"
                        aria-label="Search branches"
                    >

                </div>


                {{-- STATUS FILTER --}}

                <select
                    id="branchStatusFilter"
                    class="branch-filter"
                    aria-label="Filter branches by status"
                >

                    <option value="all">
                        All Status
                    </option>

                    <option value="active">
                        Active
                    </option>

                    <option value="inactive">
                        Inactive
                    </option>

                </select>


                {{-- QUICK ADD --}}

                @if(Route::has('admin.branches.create'))

                    @if($canManageBranches)

                        <a
                            href="{{ route('admin.branches.create') }}"
                            class="btn btn-primary branch-quick-add"
                            title="Add New Branch"
                            aria-label="Add New Branch"
                        >

                            <i class="bi bi-plus-lg"></i>

                        </a>

                    @else

                        <button
                            type="button"
                            class="btn btn-primary branch-quick-add branch-disabled-action"
                            title="Only Super Admin can add branches"
                            aria-label="Add New Branch - disabled"
                            onclick="branchReadOnlyMessage()"
                        >

                            <i class="bi bi-plus-lg"></i>

                        </button>

                    @endif

                @endif

            </div>

        </div>


        {{-- =================================================
             TABLE
        ================================================== --}}

        @if($branches->count() > 0)

            <div class="branch-table-wrapper">

                <table
                    class="branch-table"
                    id="branchesTable"
                >

                    <thead>

                        <tr>

                            <th style="width:55px;">
                                #
                            </th>

                            <th>
                                Branch
                            </th>

                            <th>
                                Branch Code
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="width:145px;">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($branches as $branch)

                            <tr
                                data-status="{{ $branch->is_active ? 'active' : 'inactive' }}"
                            >


                                {{-- NUMBER --}}

                                <td>

                                    <span class="branch-number">

                                        {{ $branches->firstItem() + $loop->index }}

                                    </span>

                                </td>


                                {{-- BRANCH --}}

                                <td>

                                    <div class="branch-identity">

                                        <div class="branch-avatar">

                                            <i class="bi bi-building-fill"></i>

                                        </div>

                                        <div>

                                            <div class="branch-name">

                                                {{ $branch->name }}

                                            </div>

                                            <div class="branch-type">

                                                CPC Branch Location

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- CODE --}}

                                <td>

                                    @if($branch->code)

                                        <span class="branch-code">

                                            {{ $branch->code }}

                                        </span>

                                    @else

                                        <span class="no-value">
                                            Not assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- LOCATION --}}

                                <td>

                                    @if($branch->location)

                                        <div class="branch-location">

                                            <i class="bi bi-geo-alt-fill"></i>

                                            <span>
                                                {{ $branch->location }}
                                            </span>

                                        </div>

                                    @else

                                        <span class="no-value">
                                            Location not specified
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($branch->is_active)

                                        <span
                                            class="branch-status active"
                                            data-status-label="active"
                                        >

                                            <span class="status-dot"></span>

                                            Active

                                        </span>

                                    @else

                                        <span
                                            class="branch-status inactive"
                                            data-status-label="inactive"
                                        >

                                            <span class="status-dot"></span>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     ACTIONS
                                ================================================== --}}

                                <td>

                                    <div class="branch-actions">


                                        {{-- VIEW
                                             ALL ROLES = ENABLED
                                        --}}

                                        @if(Route::has('admin.branches.show'))

                                            <a
                                                href="{{ route('admin.branches.show', $branch) }}"
                                                class="branch-action branch-action-view"
                                                title="View Branch"
                                                aria-label="View Branch"
                                            >

                                                <i class="bi bi-eye"></i>

                                            </a>

                                        @endif


                                        {{-- EDIT
                                             SUPER ADMIN = ENABLED
                                             ADMIN + EMPLOYEE = DISABLED
                                        --}}

                                        @if(Route::has('admin.branches.edit'))

                                            @if($canManageBranches)

                                                <a
                                                    href="{{ route('admin.branches.edit', $branch) }}"
                                                    class="branch-action branch-action-edit"
                                                    title="Edit Branch"
                                                    aria-label="Edit Branch"
                                                >

                                                    <i class="bi bi-pencil"></i>

                                                </a>

                                            @else

                                                <button
                                                    type="button"
                                                    class="branch-action branch-action-edit branch-disabled-action"
                                                    title="Only Super Admin can edit branches"
                                                    aria-label="Edit Branch - disabled"
                                                    onclick="branchReadOnlyMessage()"
                                                >

                                                    <i class="bi bi-pencil"></i>

                                                </button>

                                            @endif

                                        @endif


                                        {{-- DEACTIVATE
                                             SUPER ADMIN = ENABLED
                                             ADMIN + EMPLOYEE = DISABLED
                                        --}}

                                        @if($branch->is_active)

                                            @if($canManageBranches)

                                                <form
                                                    action="{{ route('admin.branches.destroy', $branch) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to deactivate this branch?');"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="branch-action branch-action-danger"
                                                        title="Deactivate Branch"
                                                        aria-label="Deactivate Branch"
                                                    >

                                                        <i class="bi bi-archive"></i>

                                                    </button>

                                                </form>

                                            @else

                                                <button
                                                    type="button"
                                                    class="branch-action branch-action-danger branch-disabled-action"
                                                    title="Only Super Admin can deactivate branches"
                                                    aria-label="Deactivate Branch - disabled"
                                                    onclick="branchReadOnlyMessage()"
                                                >

                                                    <i class="bi bi-archive"></i>

                                                </button>

                                            @endif


                                        @else


                                            {{-- REACTIVATE
                                                 SUPER ADMIN = ENABLED
                                                 ADMIN + EMPLOYEE = DISABLED
                                            --}}

                                            @if($canManageBranches)

                                                <form
                                                    action="{{ route('admin.branches.update', $branch) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to reactivate this branch?');"
                                                >

                                                    @csrf

                                                    @method('PUT')

                                                    <input
                                                        type="hidden"
                                                        name="name"
                                                        value="{{ $branch->name }}"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="code"
                                                        value="{{ $branch->code }}"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="location"
                                                        value="{{ $branch->location }}"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="description"
                                                        value="{{ $branch->description }}"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="is_active"
                                                        value="1"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="branch-action branch-action-success"
                                                        title="Reactivate Branch"
                                                        aria-label="Reactivate Branch"
                                                    >

                                                        <i class="bi bi-arrow-clockwise"></i>

                                                    </button>

                                                </form>

                                            @else

                                                <button
                                                    type="button"
                                                    class="branch-action branch-action-success branch-disabled-action"
                                                    title="Only Super Admin can reactivate branches"
                                                    aria-label="Reactivate Branch - disabled"
                                                    onclick="branchReadOnlyMessage()"
                                                >

                                                    <i class="bi bi-arrow-clockwise"></i>

                                                </button>

                                            @endif

                                        @endif


                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>


                {{-- SEARCH EMPTY --}}

                <div
                    id="searchEmpty"
                    class="search-empty"
                >

                    <div class="search-empty-icon">

                        <i class="bi bi-search"></i>

                    </div>

                    <strong>
                        No matching branches
                    </strong>

                    <span>
                        Try another branch name, code, location or status.
                    </span>

                </div>

            </div>


            {{-- =================================================
                 TABLE FOOTER
            ================================================== --}}

            <div class="branch-table-footer">

                <div class="branch-record-count">

                    Showing

                    <strong>
                        {{ $branches->firstItem() }}
                    </strong>

                    –

                    <strong>
                        {{ $branches->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $branches->total() }}
                    </strong>

                    branches

                </div>


                <div>

                    {{ $branches->links() }}

                </div>

            </div>


        @else


            {{-- =================================================
                 EMPTY DATABASE STATE
            ================================================== --}}

            <div class="branch-empty">

                <div class="branch-empty-icon">

                    <i class="bi bi-buildings"></i>

                </div>

                <h4>
                    No Branches Registered
                </h4>

                <p>
                    No CPC branch locations are currently registered.
                    Add a branch to begin assigning IT assets to locations.
                </p>

                @if(Route::has('admin.branches.create'))

                    @if($canManageBranches)

                        <a
                            href="{{ route('admin.branches.create') }}"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Register First Branch

                        </a>

                    @else

                        <button
                            type="button"
                            class="btn btn-primary branch-disabled-action"
                            title="Only Super Admin can add branches"
                            aria-label="Register First Branch - disabled"
                            onclick="branchReadOnlyMessage()"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Register First Branch

                        </button>

                    @endif

                @endif

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     SEARCH + FILTER
========================================================= --}}

@push('scripts')

<script>

function branchReadOnlyMessage()
{
    alert(
        'Branch management is available to Super Admin only. You have view and search access only.'
    );
}


document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('branchSearch');

    const statusFilter =
        document.getElementById('branchStatusFilter');

    const table =
        document.getElementById('branchesTable');

    const searchEmpty =
        document.getElementById('searchEmpty');


    if (!table) {
        return;
    }


    const rows =
        table.querySelectorAll('tbody tr');


    function filterBranches()
    {
        const searchValue =
            searchInput
                ? searchInput.value.toLowerCase().trim()
                : '';


        const statusValue =
            statusFilter
                ? statusFilter.value
                : 'all';


        let visibleRows = 0;


        rows.forEach(function (row) {

            const rowText =
                row.textContent.toLowerCase();


            const rowStatus =
                row.dataset.status;


            const matchesSearch =
                rowText.includes(searchValue);


            const matchesStatus =
                statusValue === 'all' ||
                rowStatus === statusValue;


            if (
                matchesSearch &&
                matchesStatus
            ) {

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


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            filterBranches
        );

    }


    if (statusFilter) {

        statusFilter.addEventListener(
            'change',
            filterBranches
        );

    }

});

</script>

@endpush

@endsection