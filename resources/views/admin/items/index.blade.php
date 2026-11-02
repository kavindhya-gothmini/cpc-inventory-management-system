@extends('layouts.admin')

@section('title', 'Inventory Management | CPC Inventory Management System')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | ROLE & PERMISSION CONTROL
    |--------------------------------------------------------------------------
    |
    | Super Admin = Full inventory management
    | Admin       = Full inventory operational management
    | Employee    = View + Search only
    |
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
     * Inventory management permissions.
     */
    $canManageInventory = $isAdmin || $isSuperAdmin;

    /*
     * Employee is strictly read-only for inventory.
     */
    $isReadOnly = $isEmployee;

@endphp


<style>
    /* =========================================================
       CPC INVENTORY MANAGEMENT
       ENTERPRISE DASHBOARD UI
    ========================================================= */

    .inventory-page {
        max-width: 1600px;
        margin: 0 auto;
        padding: 4px 0 36px;
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .inventory-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 14px 2px;
        font-size: 11px;
        font-weight: 600;
        color: #8a94a6;
    }

    .inventory-breadcrumb i {
        font-size: 9px;
        color: #b3bcc9;
    }

    .inventory-breadcrumb .active {
        color: #0d6efd;
    }

    /* =========================================================
       HERO HEADER
    ========================================================= */

    .inventory-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 22px;
        padding: 28px;
        border: 1px solid #dfe7f3;
        border-radius: 20px;
        background:
            linear-gradient(
                135deg,
                #ffffff 0%,
                #f5f8ff 54%,
                #eaf2ff 100%
            );
        box-shadow: 0 8px 28px rgba(30, 55, 90, 0.07);
    }

    .inventory-hero::before {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        right: -80px;
        top: -125px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.055);
    }

    .inventory-hero::after {
        content: "";
        position: absolute;
        width: 170px;
        height: 170px;
        right: 110px;
        bottom: -115px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.035);
    }

    .inventory-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
    }

    .inventory-hero-main {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .inventory-hero-icon {
        width: 62px;
        height: 62px;
        min-width: 62px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: linear-gradient(
            135deg,
            #0d6efd,
            #3d8bfd
        );
        color: #ffffff;
        font-size: 25px;
        box-shadow: 0 10px 24px rgba(13, 110, 253, 0.20);
    }

    .inventory-context-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        margin-bottom: 8px;
        border: 1px solid rgba(13, 110, 253, 0.10);
        border-radius: 20px;
        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        font-size: 9.5px;
        font-weight: 750;
        letter-spacing: .55px;
        text-transform: uppercase;
    }

    .inventory-title {
        margin: 0;
        color: #172033;
        font-size: 27px;
        line-height: 1.2;
        font-weight: 750;
        letter-spacing: -.45px;
    }

    .inventory-description {
        max-width: 720px;
        margin: 7px 0 0;
        color: #718096;
        font-size: 13px;
        line-height: 1.6;
    }

    .inventory-hero-action {
        position: relative;
        z-index: 3;
        flex-shrink: 0;
    }

    /* =========================================================
       PRIMARY BUTTON
    ========================================================= */

    .btn-inventory-primary {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border: none;
        border-radius: 10px;
        background: #0d6efd;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        box-shadow: 0 7px 18px rgba(13, 110, 253, 0.18);
        transition: all .2s ease;
    }

    .btn-inventory-primary:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(13, 110, 253, 0.24);
    }

    /* =========================================================
       SECONDARY ASSIGN BUTTON
    ========================================================= */

    .btn-inventory-assign {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border: 1px solid #cfe0f7;
        border-radius: 10px;
        background: #f5f9ff;
        color: #1769c2 !important;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        transition: all .2s ease;
    }

    .btn-inventory-assign:hover {
        border-color: #0d6efd;
        background: #0d6efd;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.16);
    }

    /* =========================================================
       DISABLED ACTIONS
    ========================================================= */

    .inventory-disabled-action {
        opacity: 0.55;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
        user-select: none;
    }

    .inventory-disabled-action:hover {
        background: #0d6efd;
        transform: none !important;
        box-shadow: none !important;
    }

    .inventory-action-disabled {
        opacity: 0.48;
        cursor: not-allowed !important;
        filter: grayscale(15%);
    }

    .inventory-action-disabled:hover {
        transform: none !important;
    }

    /* =========================================================
       READ ONLY NOTICE
    ========================================================= */

    .inventory-readonly-notice {
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

    .inventory-readonly-notice i {
        color: #0d6efd;
        font-size: 14px;
    }

    /* =========================================================
       SUCCESS ALERT
    ========================================================= */

    .inventory-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        margin-bottom: 22px;
        border: 1px solid #ccebd9;
        border-radius: 11px;
        background: #ecfdf3;
        color: #18794e;
        font-size: 13px;
        box-shadow: 0 4px 15px rgba(30, 55, 90, 0.04);
    }

    .inventory-alert i {
        font-size: 17px;
    }

    /* =========================================================
       KPI CARDS
    ========================================================= */

    .inventory-stat-card {
        position: relative;
        height: 100%;
        min-height: 150px;
        overflow: hidden;
        padding: 21px;
        border: 1px solid #e3e8f0;
        border-radius: 17px;
        background: #ffffff;
        box-shadow: 0 5px 20px rgba(30, 55, 90, 0.05);
        transition: all .25s ease;
    }

    .inventory-stat-card:hover {
        transform: translateY(-3px);
        border-color: #d7e2f4;
        box-shadow: 0 12px 28px rgba(30, 55, 90, 0.09);
    }

    .inventory-stat-card::after {
        content: "";
        position: absolute;
        width: 125px;
        height: 125px;
        right: -50px;
        bottom: -65px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.045);
    }

    .inventory-stat-content {
        position: relative;
        z-index: 2;
    }

    .inventory-stat-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
    }

    .inventory-stat-label {
        margin-bottom: 8px;
        color: #7b8799;
        font-size: 10px;
        line-height: 1.4;
        font-weight: 750;
        letter-spacing: .75px;
        text-transform: uppercase;
    }

    .inventory-stat-number {
        color: #172033;
        font-size: 30px;
        line-height: 1.1;
        font-weight: 750;
        letter-spacing: -.5px;
    }

    .inventory-stat-description {
        margin-top: 8px;
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.5;
    }

    .inventory-stat-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: #edf5ff;
        color: #0d6efd;
        font-size: 20px;
    }

    .inventory-stat-card.available .inventory-stat-icon {
        background: #eaf8f0;
        color: #16834b;
    }

    .inventory-stat-card.inuse .inventory-stat-icon {
        background: #eef4ff;
        color: #4169e1;
    }

    .inventory-stat-card.attention .inventory-stat-icon {
        background: #fff5e6;
        color: #d98216;
    }

    /* =========================================================
       MAIN INVENTORY PANEL
    ========================================================= */

    .inventory-panel {
        overflow: hidden;
        border: 1px solid #e1e7ef;
        border-radius: 19px;
        background: #ffffff;
        box-shadow: 0 7px 24px rgba(30, 55, 90, 0.055);
    }

    .inventory-panel-header {
        padding: 22px 24px;
        border-bottom: 1px solid #edf0f5;
        background: #ffffff;
    }

    .inventory-panel-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .inventory-panel-title-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #edf5ff;
        color: #0d6efd;
        font-size: 17px;
    }

    .inventory-section-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
        color: #0d6efd;
        font-size: 9.5px;
        font-weight: 750;
        letter-spacing: .65px;
        text-transform: uppercase;
    }

    .inventory-panel-title {
        margin: 0;
        color: #202a3a;
        font-size: 17px;
        font-weight: 720;
    }

    .inventory-panel-description {
        margin: 4px 0 0;
        color: #8b95a5;
        font-size: 11px;
        line-height: 1.5;
    }

    .inventory-record-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        margin-left: 7px;
        border: 1px solid #e4e8ee;
        border-radius: 20px;
        background: #f4f6f8;
        color: #667085;
        font-size: 10px;
        font-weight: 700;
        vertical-align: middle;
    }

    /* =========================================================
       TOOLBAR
    ========================================================= */

    .inventory-toolbar {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .inventory-search {
        width: 280px;
        height: 41px;
        display: flex;
        align-items: center;
        overflow: hidden;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        background: #f8f9fb;
        transition: all .2s ease;
    }

    .inventory-search:focus-within {
        border-color: #86b7fe;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
    }

    .inventory-search-icon {
        padding-left: 13px;
        color: #8b96a7;
        font-size: 13px;
    }

    .inventory-search input {
        width: 100%;
        height: 100%;
        padding: 0 12px;
        border: none;
        outline: none;
        background: transparent;
        color: #273449;
        font-size: 12px;
    }

    .inventory-search input::placeholder {
        color: #9aa4b3;
    }

    .inventory-reset {
        height: 41px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0 12px;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        background: #ffffff;
        color: #667085;
        font-size: 11px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .inventory-reset:hover {
        border-color: #cbd3de;
        background: #f8fafc;
        color: #344054;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .inventory-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .inventory-table {
        width: 100%;
        min-width: 1200px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .inventory-table thead th {
        padding: 13px 15px;
        border-bottom: 1px solid #e5e9ef;
        background: #f8fafc;
        color: #748094;
        font-size: 9.5px;
        font-weight: 750;
        letter-spacing: .7px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .inventory-table tbody td {
        padding: 15px;
        border-bottom: 1px solid #eef1f5;
        color: #394457;
        font-size: 12px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .inventory-table tbody tr {
        transition: background .15s ease;
    }

    .inventory-table tbody tr:hover {
        background: #f9fbfe;
    }

    .inventory-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       TABLE CONTENT
    ========================================================= */

    .inventory-row-number {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e7ebf0;
        border-radius: 8px;
        background: #f5f7fa;
        color: #7b8799;
        font-size: 10px;
        font-weight: 700;
    }

    .asset-number {
        color: #1769c2;
        font-size: 12px;
        font-weight: 750;
    }

    .item-type-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .item-type-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: linear-gradient(
            135deg,
            #edf5ff,
            #e7f0ff
        );
        color: #3d73ad;
        font-size: 13px;
    }

    .item-type-name {
        color: #354052;
        font-size: 12px;
        font-weight: 650;
    }

    .inventory-value {
        color: #465264;
        font-weight: 500;
    }

    .inventory-muted {
        color: #a0a9b7;
    }

    .inventory-code {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border: 1px solid #e4e7ec;
        border-radius: 6px;
        background: #f7f8fa;
        color: #526071;
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", monospace;
        font-size: 10.5px;
    }

    /* =========================================================
       STATUS BADGES
    ========================================================= */

    .inventory-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .inventory-status i {
        font-size: 9px;
    }

    .status-available {
        background: #eaf8f1;
        color: #17885d;
        border: 1px solid #d7f0e2;
    }

    .status-inuse {
        background: #edf4ff;
        color: #3974cf;
        border: 1px solid #dbe8ff;
    }

    .status-repair {
        background: #fff5e5;
        color: #c97b12;
        border: 1px solid #f7e4c5;
    }

    .status-rdd {
        background: #f5edff;
        color: #7c45ad;
        border: 1px solid #e9dcf8;
    }

    .status-default {
        background: #f1f3f5;
        color: #687384;
        border: 1px solid #e3e6e9;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .inventory-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
    }

    .inventory-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 8px;
        font-size: 12px;
        transition: all .18s ease;
    }

    .inventory-action:hover {
        transform: translateY(-1px);
    }

    .inventory-action-view {
        border: 1px solid #cfe0f7;
        background: #f5f9ff;
        color: #2f80ed;
    }

    .inventory-action-view:hover {
        border-color: #2f80ed;
        background: #2f80ed;
        color: #ffffff;
    }

    .inventory-action-edit {
        border: 1px solid #dce1e8;
        background: #ffffff;
        color: #657184;
    }

    .inventory-action-edit:hover {
        border-color: #657184;
        background: #657184;
        color: #ffffff;
    }

    .inventory-action-assign {
        border: 1px solid #cfe8d9;
        background: #f1fbf5;
        color: #16834b;
    }

    .inventory-action-assign:hover {
        border-color: #16834b;
        background: #16834b;
        color: #ffffff;
    }

    .inventory-action-delete {
        border: 1px solid #f2d2d2;
        background: #fff8f8;
        color: #dc4b4b;
    }

    .inventory-action-delete:hover {
        border-color: #dc4b4b;
        background: #dc4b4b;
        color: #ffffff;
    }

    /* =========================================================
       SEARCH EMPTY
    ========================================================= */

    .inventory-search-empty {
        display: none;
        padding: 55px 25px;
        text-align: center;
        border-top: 1px solid #edf0f5;
    }

    .inventory-search-empty-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #edf5ff;
        color: #0d6efd;
        font-size: 27px;
    }

    .inventory-search-empty h5 {
        margin-bottom: 6px;
        color: #273142;
        font-size: 16px;
        font-weight: 700;
    }

    .inventory-search-empty p {
        max-width: 430px;
        margin: 0 auto;
        color: #8a94a6;
        font-size: 12px;
        line-height: 1.6;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .inventory-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
        padding: 17px 24px;
        border-top: 1px solid #edf0f5;
        background: #ffffff;
    }

    .inventory-pagination-info {
        color: #8a95a5;
        font-size: 11px;
    }

    .inventory-pagination-info strong {
        color: #566274;
        font-weight: 700;
    }

    /* =========================================================
       EMPTY DATABASE STATE
    ========================================================= */

    .inventory-empty {
        padding: 75px 25px;
        text-align: center;
    }

    .inventory-empty-icon {
        width: 84px;
        height: 84px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        color: #8995a7;
        font-size: 34px;
    }

    .inventory-empty h5 {
        margin-bottom: 7px;
        color: #273449;
        font-size: 18px;
        font-weight: 720;
    }

    .inventory-empty p {
        max-width: 470px;
        margin: 0 auto;
        color: #8b95a5;
        font-size: 12.5px;
        line-height: 1.65;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1199px) {

        .inventory-hero-content {
            align-items: flex-start;
        }

        .inventory-search {
            width: 240px;
        }

    }

    @media (max-width: 991px) {

        .inventory-page {
            padding-left: 4px;
            padding-right: 4px;
        }

        .inventory-hero {
            padding: 23px;
        }

        .inventory-hero-content {
            flex-direction: column;
        }

        .inventory-hero-action {
            width: 100%;
            display: flex;
            gap: 8px;
        }

        .inventory-hero-action .btn-inventory-primary,
        .inventory-hero-action .btn-inventory-assign {
            flex: 1;
        }

        .inventory-search {
            width: 230px;
        }

    }

    @media (max-width: 767px) {

        .inventory-hero {
            border-radius: 16px;
            padding: 20px;
        }

        .inventory-hero-main {
            gap: 12px;
        }

        .inventory-hero-icon {
            width: 53px;
            height: 53px;
            min-width: 53px;
            border-radius: 14px;
            font-size: 21px;
        }

        .inventory-title {
            font-size: 22px;
        }

        .inventory-description {
            font-size: 12px;
        }

        .inventory-panel-header {
            padding: 19px;
        }

        .inventory-toolbar {
            width: 100%;
        }

        .inventory-search {
            flex: 1;
            width: auto;
        }

        .inventory-pagination {
            flex-direction: column;
            align-items: stretch;
        }

        .inventory-pagination-info {
            text-align: center;
        }

        .inventory-pagination > div:last-child {
            display: flex;
            justify-content: center;
        }

    }

    @media (max-width: 576px) {

        .inventory-hero {
            padding: 17px;
            border-radius: 14px;
        }

        .inventory-context-pill {
            font-size: 8.5px;
        }

        .inventory-title {
            font-size: 20px;
        }

        .inventory-description {
            font-size: 11.5px;
        }

        .inventory-hero-action {
            flex-direction: column;
        }

        .inventory-hero-action .btn-inventory-primary,
        .inventory-hero-action .btn-inventory-assign {
            width: 100%;
        }

        .inventory-toolbar {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        .inventory-search {
            width: 100%;
        }

        .inventory-reset {
            width: 100%;
            justify-content: center;
        }

        .inventory-stat-card {
            min-height: 130px;
            padding: 18px;
        }

        .inventory-stat-number {
            font-size: 26px;
        }

        .inventory-stat-icon {
            width: 43px;
            height: 43px;
            min-width: 43px;
            font-size: 18px;
        }

        .inventory-panel-header {
            padding: 17px;
        }

        .inventory-pagination {
            padding: 16px;
        }

        .inventory-empty {
            padding: 55px 18px;
        }

    }
</style>


<div class="inventory-page">

    {{-- =====================================================
         BREADCRUMB
    ====================================================== --}}
    <div class="inventory-breadcrumb">

        <span>Inventory</span>

        <i class="bi bi-chevron-right"></i>

        <span class="active">
            Inventory Management
        </span>

    </div>


    {{-- =====================================================
         HERO HEADER
    ====================================================== --}}
    <div class="inventory-hero">

        <div class="inventory-hero-content">

            <div class="inventory-hero-main">

                <div class="inventory-hero-icon">
                    <i class="bi bi-box-seam-fill"></i>
                </div>

                <div>

                    <div class="inventory-context-pill">
                        <i class="bi bi-shield-check"></i>
                        CPC Inventory Administration
                    </div>

                    <h1 class="inventory-title">
                        Inventory Management
                    </h1>

                    <p class="inventory-description">
                        Manage CPC IT assets, equipment records, assignments,
                        operational status and inventory lifecycle information
                        from one centralized workspace.
                    </p>


                    {{-- =================================================
                         EMPLOYEE READ ONLY NOTICE
                    ================================================== --}}
                    @if ($isReadOnly)

                        <div class="inventory-readonly-notice">

                            <i class="bi bi-info-circle-fill"></i>

                            <span>
                                Employee accounts have view and search
                                access only. Inventory management and
                                assignment operations are disabled.
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 HERO ACTIONS
            ================================================== --}}
            @if (Route::has('admin.items.create'))

                <div class="inventory-hero-action">

                    @if ($canManageInventory)

                        <a
                            href="{{ route('admin.items.create') }}"
                            class="btn-inventory-primary"
                            title="Add Inventory Item"
                            aria-label="Add Inventory Item"
                        >

                            <i class="bi bi-plus-lg"></i>

                            Add Inventory Item

                        </a>

                    @else

                        <button
                            type="button"
                            class="btn-inventory-primary inventory-disabled-action"
                            onclick="inventoryPermissionMessage('add')"
                            title="Employee accounts cannot add inventory items."
                            aria-label="Add Inventory Item disabled"
                        >

                            <i class="bi bi-plus-lg"></i>

                            Add Inventory Item

                        </button>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         SUCCESS ALERT
    ====================================================== --}}
    @if(session('success'))

        <div class="inventory-alert">

            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

            <button
                type="button"
                class="btn-close ms-auto"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- =====================================================
         VALIDATION ERROR ALERT
    ====================================================== --}}
    @if ($errors->any())

        <div
            class="alert alert-danger d-flex align-items-start gap-2 mb-4"
            role="alert"
        >

            <i class="bi bi-exclamation-triangle-fill mt-1"></i>

            <div>

                <strong>
                    Please check the submitted information.
                </strong>

                <ul class="mb-0 mt-1 ps-3">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
         INVENTORY KPI CARDS
    ====================================================== --}}
    <div class="row g-3 mb-4">

        {{-- TOTAL INVENTORY --}}
        <div class="col-xl-3 col-md-6">

            <div class="inventory-stat-card">

                <div class="inventory-stat-content">

                    <div class="inventory-stat-top">

                        <div>

                            <div class="inventory-stat-label">
                                Total Inventory
                            </div>

                            <div class="inventory-stat-number">
                                {{ $items->total() }}
                            </div>

                        </div>

                        <div class="inventory-stat-icon">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>

                    </div>

                    <div class="inventory-stat-description">
                        Registered CPC IT assets
                    </div>

                </div>

            </div>

        </div>


        {{-- AVAILABLE --}}
        <div class="col-xl-3 col-md-6">

            <div class="inventory-stat-card available">

                <div class="inventory-stat-content">

                    <div class="inventory-stat-top">

                        <div>

                            <div class="inventory-stat-label">
                                Available
                            </div>

                            <div class="inventory-stat-number">
                                {{ $availableCount }}
                            </div>

                        </div>

                        <div class="inventory-stat-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>

                    </div>

                    <div class="inventory-stat-description">
                        Ready for assignment
                    </div>

                </div>

            </div>

        </div>


        {{-- IN USE --}}
        <div class="col-xl-3 col-md-6">

            <div class="inventory-stat-card inuse">

                <div class="inventory-stat-content">

                    <div class="inventory-stat-top">

                        <div>

                            <div class="inventory-stat-label">
                                Items In Use
                            </div>

                            <div class="inventory-stat-number">
                                {{ $inUseCount }}
                            </div>

                        </div>

                        <div class="inventory-stat-icon">
                            <i class="bi bi-person-check-fill"></i>
                        </div>

                    </div>

                    <div class="inventory-stat-description">
                        Currently assigned assets
                    </div>

                </div>

            </div>

        </div>


        {{-- REPAIR / RDD --}}
        <div class="col-xl-3 col-md-6">

            <div class="inventory-stat-card attention">

                <div class="inventory-stat-content">

                    <div class="inventory-stat-top">

                        <div>

                            <div class="inventory-stat-label">
                                Repair / RDD
                            </div>

                            <div class="inventory-stat-number">
                                {{ $attentionCount }}
                            </div>

                        </div>

                        <div class="inventory-stat-icon">
                            <i class="bi bi-tools"></i>
                        </div>

                    </div>

                    <div class="inventory-stat-description">
                        Assets requiring attention
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MAIN INVENTORY DIRECTORY
    ====================================================== --}}
    <div class="inventory-panel">


        {{-- =================================================
             PANEL HEADER
        ================================================== --}}
        <div class="inventory-panel-header">

            <div class="d-flex justify-content-between
                        align-items-center flex-wrap gap-3">

                <div class="inventory-panel-heading">

                    <div class="inventory-panel-title-icon">
                        <i class="bi bi-list-ul"></i>
                    </div>

                    <div>

                        <div class="inventory-section-label">

                            <i class="bi bi-database"></i>

                            CPC Asset Registry

                        </div>

                        <div>

                            <h5 class="inventory-panel-title
                                       d-inline-block">

                                Inventory Items

                            </h5>

                            <span class="inventory-record-badge">

                                <i class="bi bi-box-seam"></i>

                                {{ $items->total() }} Records

                            </span>

                        </div>

                        <p class="inventory-panel-description">
                            Centralized register of CPC IT equipment
                            and inventory assets.
                        </p>

                    </div>

                </div>


                {{-- SEARCH TOOLBAR --}}
                <div class="inventory-toolbar">

                    <div class="inventory-search">

                        <i class="bi bi-search inventory-search-icon"></i>

                        <input
                            type="text"
                            id="inventorySearch"
                            placeholder="Search asset, type, serial, IP..."
                            autocomplete="off"
                            maxlength="100"
                            aria-label="Search inventory"
                        >

                    </div>

                    <button
                        type="button"
                        id="inventoryReset"
                        class="inventory-reset"
                    >

                        <i class="bi bi-arrow-counterclockwise"></i>

                        Reset

                    </button>

                </div>

            </div>

        </div>


        {{-- =================================================
             INVENTORY TABLE
        ================================================== --}}
        @if ($items->count() > 0)

            <div class="inventory-table-wrapper">

                <table
                    class="inventory-table"
                    id="inventoryTable"
                >

                    <thead>

                        <tr>

                            <th style="width: 60px;">
                                #
                            </th>

                            <th>
                                Asset No.
                            </th>

                            <th>
                                Item Type
                            </th>

                            <th>
                                Make
                            </th>

                            <th>
                                Model
                            </th>

                            <th>
                                Serial No.
                            </th>

                            <th>
                                IP Address
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach ($items as $item)

                        @php

                            $status = $item->status;

                            $statusClass = match ($status) {

                                'available'
                                    => 'status-available',

                                'in_use'
                                    => 'status-inuse',

                                'repair',
                                'under_repair'
                                    => 'status-repair',

                                'rdd',
                                'sent_to_rdd'
                                    => 'status-rdd',

                                default
                                    => 'status-default',

                            };

                            $statusIcon = match ($status) {

                                'available'
                                    => 'bi-check-circle-fill',

                                'in_use'
                                    => 'bi-person-check-fill',

                                'repair',
                                'under_repair'
                                    => 'bi-tools',

                                'rdd',
                                'sent_to_rdd'
                                    => 'bi-arrow-repeat',

                                default
                                    => 'bi-info-circle-fill',

                            };

                            /*
                             * Only an available item should be
                             * offered for a new assignment.
                             */
                            $canAssignItem =
                                $canManageInventory
                                && $status === 'available';

                        @endphp


                        <tr
                            data-item-id="{{ $item->id }}"
                            data-item-status="{{ $status }}"
                        >

                            {{-- NUMBER --}}
                            <td>

                                <span class="inventory-row-number">

                                    {{ $items->firstItem() + $loop->index }}

                                </span>

                            </td>


                            {{-- ASSET NUMBER --}}
                            <td>

                                <strong class="asset-number">

                                    {{ $item->asset_number }}

                                </strong>

                            </td>


                            {{-- ITEM TYPE --}}
                            <td>

                                <div class="item-type-cell">

                                    <div class="item-type-icon">
                                        <i class="bi bi-box"></i>
                                    </div>

                                    <span class="item-type-name">

                                        {{ $item->itemType?->name ?? '—' }}

                                    </span>

                                </div>

                            </td>


                            {{-- MAKE --}}
                            <td>

                                @if ($item->make)

                                    <span class="inventory-value">
                                        {{ $item->make }}
                                    </span>

                                @else

                                    <span class="inventory-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- MODEL --}}
                            <td>

                                @if ($item->model)

                                    <span class="inventory-value">
                                        {{ $item->model }}
                                    </span>

                                @else

                                    <span class="inventory-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- SERIAL NUMBER --}}
                            <td>

                                @if ($item->serial_number)

                                    <span class="inventory-code">
                                        {{ $item->serial_number }}
                                    </span>

                                @else

                                    <span class="inventory-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- IP ADDRESS --}}
                            <td>

                                @if ($item->ip_address)

                                    <span class="inventory-code">
                                        {{ $item->ip_address }}
                                    </span>

                                @else

                                    <span class="inventory-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                <span
                                    class="inventory-status {{ $statusClass }}"
                                >

                                    <i
                                        class="bi {{ $statusIcon }}"
                                    ></i>

                                    {{ ucfirst(
                                        str_replace('_', ' ', $status)
                                    ) }}

                                </span>

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}
                            <td>

                                <div class="inventory-actions">


                                    {{-- =================================================
                                         VIEW
                                         ALL ROLES
                                    ================================================== --}}
                                    @if (Route::has('admin.items.show'))

                                        <a
                                            href="{{ route(
                                                'admin.items.show',
                                                $item->id
                                            ) }}"
                                            class="inventory-action
                                                   inventory-action-view"
                                            title="View Item"
                                            aria-label="View Item"
                                        >

                                            <i class="bi bi-eye"></i>

                                        </a>

                                    @endif


                                    {{-- =================================================
                                         ASSIGN ITEM
                                         ADMIN + SUPER ADMIN
                                    ================================================== --}}
                                    @if (Route::has('admin.employees.index'))

                                        @if ($canAssignItem)

                                            <a
                                                href="{{ route('admin.employees.index') }}"
                                                class="inventory-action
                                                       inventory-action-assign"
                                                onclick="return validateAssignmentOperation(
                                                    {{ $item->id }},
                                                    '{{ addslashes($item->asset_number) }}'
                                                )"
                                                title="Select Employee to Assign Item"
                                                aria-label="Select Employee to Assign Item"
                                            >

                                                <i class="bi bi-person-plus-fill"></i>

                                            </a>

                                        @elseif ($isReadOnly)

                                            <button
                                                type="button"
                                                class="inventory-action
                                                       inventory-action-assign
                                                       inventory-action-disabled"
                                                onclick="inventoryPermissionMessage('assign')"
                                                title="Employees cannot assign inventory items."
                                                aria-label="Assign Item disabled"
                                            >

                                                <i class="bi bi-person-plus-fill"></i>

                                            </button>

                                        @else

                                            <button
                                                type="button"
                                                class="inventory-action
                                                       inventory-action-assign
                                                       inventory-action-disabled"
                                                onclick="inventoryStatusMessage(
                                                    '{{ addslashes($item->asset_number) }}',
                                                    '{{ addslashes(str_replace('_', ' ', $status)) }}'
                                                )"
                                                title="Only available items can be assigned."
                                                aria-label="Assign Item unavailable"
                                            >

                                                <i class="bi bi-person-plus-fill"></i>

                                            </button>

                                        @endif

                                    @endif


                                    {{-- =================================================
                                         EDIT
                                         ADMIN + SUPER ADMIN
                                    ================================================== --}}
                                    @if (Route::has('admin.items.edit'))

                                        @if ($canManageInventory)

                                            <a
                                                href="{{ route(
                                                    'admin.items.edit',
                                                    $item->id
                                                ) }}"
                                                class="inventory-action
                                                       inventory-action-edit"
                                                title="Edit Item"
                                                aria-label="Edit Item"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </a>

                                        @else

                                            <button
                                                type="button"
                                                class="inventory-action
                                                       inventory-action-edit
                                                       inventory-action-disabled"
                                                onclick="inventoryPermissionMessage('edit')"
                                                title="Employees cannot edit inventory items."
                                                aria-label="Edit Item disabled"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </button>

                                        @endif

                                    @endif


                                    {{-- =================================================
                                         DELETE
                                         ADMIN + SUPER ADMIN
                                    ================================================== --}}
                                    @if (Route::has('admin.items.destroy'))

                                        @if ($canManageInventory)

                                            <form
                                                action="{{ route(
                                                    'admin.items.destroy',
                                                    $item->id
                                                ) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return validateDeleteOperation(
                                                    '{{ addslashes($item->asset_number) }}',
                                                    '{{ addslashes(str_replace('_', ' ', $status)) }}'
                                                );"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inventory-action
                                                           inventory-action-delete"
                                                    title="Delete Item"
                                                    aria-label="Delete Item"
                                                >

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        @else

                                            <button
                                                type="button"
                                                class="inventory-action
                                                       inventory-action-delete
                                                       inventory-action-disabled"
                                                onclick="inventoryPermissionMessage('delete')"
                                                title="Employees cannot delete inventory items."
                                                aria-label="Delete Item disabled"
                                            >

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        @endif

                                    @endif


                                </div>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 SEARCH NO RESULTS
            ================================================== --}}
            <div
                id="inventorySearchEmpty"
                class="inventory-search-empty"
            >

                <div class="inventory-search-empty-icon">

                    <i class="bi bi-search"></i>

                </div>

                <h5>
                    No Matching Inventory Items
                </h5>

                <p>
                    No inventory records match your search.
                    Try searching by asset number, item type,
                    make, model, serial number, IP address or status.
                </p>

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}
            <div class="inventory-pagination">

                <div class="inventory-pagination-info">

                    Showing

                    <strong>
                        {{ $items->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $items->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $items->total() }}
                    </strong>

                    inventory items

                </div>

                <div>

                    {{ $items->links() }}

                </div>

            </div>


        @else

            {{-- =================================================
                 EMPTY DATABASE STATE
            ================================================== --}}
            <div class="inventory-empty">

                <div class="inventory-empty-icon">

                    <i class="bi bi-box-seam"></i>

                </div>

                <h5>
                    No Inventory Items Found
                </h5>

                <p>

                    @if ($canManageInventory)

                        No IT assets have been registered in the CPC
                        inventory yet. Use the Add Inventory Item
                        button above to create the first record.

                    @else

                        No IT assets have been registered in the CPC
                        inventory yet.

                    @endif

                </p>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     INVENTORY OPERATION VALIDATION
========================================================= --}}
<script>

function inventoryPermissionMessage(operation) {

    const messages = {

        add:
            'Access denied. Employee accounts have view and search access only. They cannot add inventory items.',

        edit:
            'Access denied. Employee accounts have view and search access only. They cannot edit inventory items.',

        delete:
            'Access denied. Employee accounts have view and search access only. They cannot delete inventory items.',

        assign:
            'Access denied. Employee accounts have view and search access only. They cannot assign inventory items.'

    };

    alert(
        messages[operation]
        || 'You do not have permission to perform this operation.'
    );

    return false;
}


/*
|--------------------------------------------------------------------------
| ASSIGNMENT VALIDATION
|--------------------------------------------------------------------------
|
| The current assignment route requires:
|
| /employees/{employee}/assign-item
|
| Therefore this Inventory page first sends the user to
| Employee Management, where the employee must be selected.
|
| We validate the inventory item before allowing the operation.
|--------------------------------------------------------------------------
*/

function validateAssignmentOperation(itemId, assetNumber) {

    if (!itemId || itemId <= 0) {

        alert(
            'Invalid inventory item. The selected item could not be identified.'
        );

        return false;
    }

    if (!assetNumber || assetNumber.trim() === '') {

        alert(
            'This inventory item does not have a valid asset number.'
        );

        return false;
    }

    return true;
}


/*
|--------------------------------------------------------------------------
| STATUS VALIDATION
|--------------------------------------------------------------------------
*/

function inventoryStatusMessage(assetNumber, status) {

    const readableStatus =
        status
            .replace(/\b\w/g, function (character) {
                return character.toUpperCase();
            });

    alert(
        'The inventory item "' +
        assetNumber +
        '" cannot be assigned because its current status is "' +
        readableStatus +
        '".\n\n' +
        'Only items with Available status can be assigned to an employee.'
    );

    return false;
}


/*
|--------------------------------------------------------------------------
| DELETE VALIDATION
|--------------------------------------------------------------------------
*/

function validateDeleteOperation(assetNumber, status) {

    if (!assetNumber || assetNumber.trim() === '') {

        alert(
            'Invalid inventory item. The asset number is missing.'
        );

        return false;
    }


    /*
     * Extra warning for items that are currently in use.
     *
     * The backend remains the final authorization/security layer.
     */
    if (status === 'in use') {

        const confirmInUse =
            confirm(
                'This inventory item is currently assigned and marked as In Use.\n\n' +
                'Asset No.: ' +
                assetNumber +
                '\n\n' +
                'Deleting it may affect assignment history.\n\n' +
                'Do you want to continue?'
            );

        if (!confirmInUse) {
            return false;
        }

    } else {

        const confirmed =
            confirm(
                'Are you sure you want to delete this inventory item?\n\n' +
                'Asset No.: ' +
                assetNumber +
                '\n\n' +
                'This operation cannot be undone.'
            );

        if (!confirmed) {
            return false;
        }

    }

    return true;
}

</script>


{{-- =========================================================
     SEARCH SCRIPT
========================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('inventorySearch');

    const resetButton =
        document.getElementById('inventoryReset');

    const table =
        document.getElementById('inventoryTable');

    const emptyState =
        document.getElementById('inventorySearchEmpty');


    if (!searchInput || !table) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize search input
    |--------------------------------------------------------------------------
    */

    function normalizeSearchValue(value) {

        return value
            .trim()
            .replace(/\s+/g, ' ')
            .toLowerCase();

    }


    /*
    |--------------------------------------------------------------------------
    | Filter inventory
    |--------------------------------------------------------------------------
    */

    function filterInventory() {

        const searchValue =
            normalizeSearchValue(searchInput.value);

        const rows =
            table.querySelectorAll('tbody tr');

        let visibleRows = 0;


        rows.forEach(function (row) {

            const rowText =
                normalizeSearchValue(row.textContent);


            /*
             * Empty search means display all records.
             */
            const matches =
                searchValue === '' ||
                rowText.includes(searchValue);


            if (matches) {

                row.style.display = '';

                visibleRows++;

            } else {

                row.style.display = 'none';

            }

        });


        if (emptyState) {

            emptyState.style.display =
                visibleRows === 0
                    ? 'block'
                    : 'none';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Search validation
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener(
        'input',
        function () {

            /*
             * Prevent excessive input length.
             */
            if (searchInput.value.length > 100) {

                searchInput.value =
                    searchInput.value.substring(0, 100);

            }

            filterInventory();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Prevent invalid leading whitespace
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener(
        'blur',
        function () {

            searchInput.value =
                searchInput.value.trim();

            filterInventory();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */

    if (resetButton) {

        resetButton.addEventListener(
            'click',
            function () {

                searchInput.value = '';

                filterInventory();

                searchInput.focus();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Enter key
    |--------------------------------------------------------------------------
    |
    | Prevent accidental form submission if this page is ever
    | rendered inside another form.
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                filterInventory();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial filtering
    |--------------------------------------------------------------------------
    */

    filterInventory();

});

</script>

@endpush

@endsection