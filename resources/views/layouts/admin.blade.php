
<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<meta
    name="csrf-token"
    content="{{ csrf_token() }}"
>

<title>
    @yield(
        'title',
        'CPC Inventory Management System'
    )
</title>


{{-- =====================================================
     BOOTSTRAP
====================================================== --}}

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>


{{-- =====================================================
     BOOTSTRAP ICONS
====================================================== --}}

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
>


<style>

    /* =====================================================
       GLOBAL
    ===================================================== */

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        min-height: 100%;

        font-family:
            "Segoe UI",
            Arial,
            sans-serif;

        background: #f4f7fb;
        color: #1f2937;
    }

    a {
        text-decoration: none;
    }


    /* =====================================================
       ADMIN WRAPPER
    ===================================================== */

    .admin-wrapper {
        min-height: 100vh;

        display: flex;

        background: #f4f7fb;
    }


    /* =====================================================
       SIDEBAR
    ===================================================== */

    .admin-sidebar {

        width: 260px;
        min-width: 260px;
        min-height: 100vh;

        background:
            linear-gradient(
                180deg,
                #0b1f3a 0%,
                #102d50 55%,
                #0b1f3a 100%
            );

        color: #ffffff;

        position: fixed;

        left: 0;
        top: 0;
        bottom: 0;

        z-index: 1000;

        overflow-y: auto;

        box-shadow:
            4px 0 20px rgba(0, 0, 0, 0.08);
    }


    /* =====================================================
       SIDEBAR BRAND
    ===================================================== */

    .sidebar-brand {

        min-height: 76px;

        display: flex;
        align-items: center;

        padding: 16px 20px;

        border-bottom:
            1px solid rgba(255, 255, 255, 0.10);
    }

    .sidebar-brand-icon {

        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background:
            rgba(255, 255, 255, 0.12);

        margin-right: 12px;

        font-size: 21px;
    }

    .sidebar-brand-text {
        line-height: 1.15;
    }

    .sidebar-brand-title {

        font-size: 16px;
        font-weight: 700;

        letter-spacing: 0.2px;
    }

    .sidebar-brand-subtitle {

        font-size: 11px;

        color:
            rgba(255, 255, 255, 0.65);

        margin-top: 3px;
    }


    /* =====================================================
       SIDEBAR MENU
    ===================================================== */

    .sidebar-menu {

        padding:
            18px 12px 30px;
    }

    .sidebar-section-title {

        padding:
            10px 12px 7px;

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 1px;

        color:
            rgba(255, 255, 255, 0.42);
    }


    /* =====================================================
       SIDEBAR LINKS
    ===================================================== */

    .sidebar-link {

        width: 100%;

        display: flex;
        align-items: center;

        min-height: 44px;

        padding:
            10px 13px;

        margin-bottom: 4px;

        border-radius: 8px;

        color:
            rgba(255, 255, 255, 0.78);

        font-size: 14px;

        font-weight: 500;

        transition:
            background 0.15s ease,
            color 0.15s ease,
            opacity 0.15s ease;
    }

    .sidebar-link i {

        width: 25px;

        margin-right: 8px;

        font-size: 17px;

        text-align: center;
    }

    .sidebar-link:hover {

        color: #ffffff;

        background:
            rgba(255, 255, 255, 0.09);
    }

    .sidebar-link.active {

        color: #ffffff;

        background:
            linear-gradient(
                90deg,
                rgba(13, 110, 253, 0.95),
                rgba(13, 110, 253, 0.70)
            );

        box-shadow:
            0 5px 15px
            rgba(13, 110, 253, 0.18);
    }


    /* =====================================================
       ROLE-RESTRICTED SIDEBAR ITEM
    ===================================================== */

    .sidebar-link.role-disabled {

        opacity: 0.42;

        cursor: not-allowed;

        pointer-events: none;

        user-select: none;

        background: transparent;

        color:
            rgba(255, 255, 255, 0.50);
    }

    .sidebar-link.role-disabled:hover {

        background: transparent;

        color:
            rgba(255, 255, 255, 0.50);
    }

    .sidebar-link.role-disabled i {

        color:
            rgba(255, 255, 255, 0.45);
    }


    /* =====================================================
       PAGE ACTION BUTTON SUPPORT
    ===================================================== */

    .role-action-disabled {

        opacity: 0.45 !important;

        cursor: not-allowed !important;

        pointer-events: none !important;

        user-select: none !important;
    }

    button.role-action-disabled,
    a.role-action-disabled {

        filter: grayscale(15%);
    }


    /* =====================================================
       INVENTORY SECTION
    ===================================================== */

    .inventory-section {

        margin-top: 4px;
    }


    /* =====================================================
       INVENTORY HEADER
    ===================================================== */

    .inventory-header {

        width: 100%;

        display: flex;
        align-items: center;
        justify-content: space-between;

        min-height: 44px;

        padding:
            10px 13px;

        margin-bottom: 4px;

        border: 0;

        border-radius: 8px;

        background:
            transparent;

        color:
            rgba(255, 255, 255, 0.82);

        font-size: 14px;
        font-weight: 600;

        cursor: pointer;

        transition:
            background 0.15s ease,
            color 0.15s ease,
            opacity 0.15s ease;
    }

    .inventory-header:hover {

        color: #ffffff;

        background:
            rgba(255, 255, 255, 0.09);
    }

    .inventory-header-left {

        display: flex;
        align-items: center;
    }

    .inventory-header-left i {

        width: 25px;

        margin-right: 8px;

        font-size: 17px;

        text-align: center;
    }

    .inventory-chevron {

        font-size: 12px;

        transition:
            transform 0.2s ease;
    }

    .inventory-header[aria-expanded="true"]
    .inventory-chevron {

        transform: rotate(180deg);
    }


    /* =====================================================
       INVENTORY SUBMENU
    ===================================================== */

    .inventory-submenu {

        margin:
            2px 0 8px 16px;

        padding-left: 10px;

        border-left:
            1px solid
            rgba(255, 255, 255, 0.14);
    }

    .inventory-submenu .sidebar-link {

        min-height: 38px;

        padding:
            8px 10px;

        font-size: 13px;

        margin-bottom: 3px;
    }

    .inventory-submenu .sidebar-link i {

        font-size: 14px;
    }


    /* =====================================================
       SUBMENU GROUP TITLE
    ===================================================== */

    .inventory-group-title {

        padding:
            10px 10px 5px;

        color:
            rgba(255, 255, 255, 0.42);

        font-size: 10px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.8px;
    }


    /* =====================================================
       MASTER DATA LINKS
    ===================================================== */

    .master-data-link {

        position: relative;
    }

    .master-data-badge {

        margin-left: auto;

        font-size: 9px;

        font-weight: 600;

        padding: 3px 6px;

        border-radius: 20px;

        color:
            rgba(255, 255, 255, 0.70);

        background:
            rgba(255, 255, 255, 0.08);
    }

    .master-data-link.active
    .master-data-badge {

        color: #ffffff;

        background:
            rgba(255, 255, 255, 0.15);
    }


    /* =====================================================
       TEMPORARY ITEM DETAILS
    ===================================================== */

    .temporary-item-link {

        position: relative;
    }

    .temporary-item-link .temporary-item-badge {

        margin-left: auto;

        font-size: 9px;

        font-weight: 600;

        padding: 3px 6px;

        border-radius: 20px;

        color:
            rgba(255, 255, 255, 0.70);

        background:
            rgba(255, 255, 255, 0.08);
    }

    .temporary-item-link.active
    .temporary-item-badge {

        color: #ffffff;

        background:
            rgba(255, 255, 255, 0.15);
    }


    /* =====================================================
       MAIN AREA
    ===================================================== */

    .admin-main {

        width:
            calc(100% - 260px);

        margin-left: 260px;

        min-height: 100vh;

        display: flex;

        flex-direction: column;
    }


    /* =====================================================
       TOP NAVBAR
    ===================================================== */

    .admin-navbar {

        height: 72px;

        background: #ffffff;

        border-bottom:
            1px solid #e5e7eb;

        display: flex;

        align-items: center;

        justify-content: space-between;

        padding:
            0 28px;

        position: sticky;

        top: 0;

        z-index: 900;

        box-shadow:
            0 2px 8px
            rgba(0, 0, 0, 0.03);
    }

    .navbar-page-title {

        font-size: 19px;

        font-weight: 700;

        color: #172033;
    }

    .navbar-page-subtitle {

        font-size: 12px;

        color: #7b8494;

        margin-top: 2px;
    }

    .navbar-right {

        display: flex;

        align-items: center;

        gap: 14px;
    }

    .navbar-user {

        display: flex;

        align-items: center;

        gap: 10px;
    }


    /* =====================================================
       NAVBAR DEFAULT USER ICON
    ===================================================== */

    .navbar-user-icon {

        width: 38px;
        height: 38px;

        flex-shrink: 0;

        border-radius: 50%;

        display: flex;

        align-items: center;
        justify-content: center;

        background: #eaf2ff;

        color: #0d6efd;

        font-size: 17px;

        border:
            1px solid #dce9ff;
    }


    /* =====================================================
       NAVBAR PROFILE PICTURE
    ===================================================== */

    .navbar-user-avatar {

        width: 38px;
        height: 38px;

        min-width: 38px;
        min-height: 38px;

        flex-shrink: 0;

        border-radius: 50%;

        object-fit: cover;

        display: block;

        background: #f8fafc;

        border:
            2px solid #e5e7eb;

        box-shadow:
            0 2px 6px
            rgba(0, 0, 0, 0.08);
    }


    /* =====================================================
       NAVBAR USER INFORMATION
    ===================================================== */

    .navbar-user-name {

        font-size: 13px;

        font-weight: 600;

        color: #293241;

        line-height: 1.2;
    }

    .navbar-user-role {

        font-size: 11px;

        color: #8a94a6;

        margin-top: 2px;

        line-height: 1.2;
    }


    /* =====================================================
       PAGE CONTENT
    ===================================================== */

    .admin-content {

        flex: 1;

        padding: 28px;
    }


    /* =====================================================
       FOOTER
    ===================================================== */

    .admin-footer {

        padding:
            15px 28px;

        background: #ffffff;

        border-top:
            1px solid #e5e7eb;

        color: #8a94a6;

        font-size: 12px;
    }


    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 991px) {

        .admin-sidebar {

            width: 220px;

            min-width: 220px;
        }

        .admin-main {

            width:
                calc(100% - 220px);

            margin-left: 220px;
        }

        .admin-content {

            padding: 20px;
        }
    }


    @media (max-width: 767px) {

        .admin-sidebar {

            position: relative;

            width: 100%;

            min-width: 100%;

            min-height: auto;
        }

        .admin-wrapper {

            display: block;
        }

        .admin-main {

            width: 100%;

            margin-left: 0;
        }

        .admin-navbar {

            height: auto;

            min-height: 65px;

            padding:
                12px 18px;
        }

        .navbar-user-name,
        .navbar-user-role {

            display: none;
        }

        .admin-content {

            padding: 16px;
        }
    }

</style>

@stack('styles')

</head>


<body>

@php

/*
|-------------------------------------------------------------------------- 
| CURRENT USER
|-------------------------------------------------------------------------- 
|
| Navigation visibility is NOT the primary security mechanism.
| Controllers and middleware must enforce permissions.
|
*/

$currentUser = auth()->user();

$isSuperAdmin = $currentUser
    && $currentUser->role === 'super_admin';

$isAdmin = $currentUser
    && $currentUser->role === 'admin';

$isEmployee = $currentUser
    && $currentUser->role === 'employee';

@endphp


<div class="admin-wrapper">


{{-- =====================================================
     SIDEBAR
====================================================== --}}

<aside class="admin-sidebar">


    {{-- =================================================
         BRAND
    ================================================== --}}

    <div class="sidebar-brand">

        <div class="sidebar-brand-icon">

            <i class="bi bi-box-seam"></i>

        </div>

        <div class="sidebar-brand-text">

            <div class="sidebar-brand-title">
                Inventory
            </div>

            <div class="sidebar-brand-subtitle">
                Management System
            </div>

        </div>

    </div>


    <nav class="sidebar-menu">


        {{-- =================================================
             MAIN
        ================================================== --}}

        <div class="sidebar-section-title">
            Main
        </div>


        {{-- =================================================
             DASHBOARD
             ALL ROLES
        ================================================== --}}

        @if(Route::has('dashboard'))

            <a
                href="{{ route('dashboard') }}"
                class="sidebar-link
                    {{
                        request()->routeIs('dashboard')
                            ? 'active'
                            : ''
                    }}"
            >

                <i class="bi bi-speedometer2"></i>

                <span>
                    Dashboard
                </span>

            </a>

        @endif


        {{-- =================================================
             ORGANIZATION
        ================================================== --}}

        <div class="sidebar-section-title">
            Organization
        </div>


        {{-- =================================================
             EMPLOYEES
             ALL ROLES
        ================================================== --}}

        @if(Route::has('admin.employees.index'))

            <a
                href="{{ route('admin.employees.index') }}"
                class="sidebar-link
                    {{
                        request()->routeIs('admin.employees.*')
                            ? 'active'
                            : ''
                    }}"
            >

                <i class="bi bi-people"></i>

                <span>
                    Employees
                </span>

            </a>

        @endif


        {{-- =================================================
             USER ACCOUNT MANAGEMENT
             SUPER ADMIN ONLY
        ================================================== --}}

        @if(
            auth()->check() &&
            Route::has('admin.users.index')
        )

            @if($isSuperAdmin)

                <a
                    href="{{ route('admin.users.index') }}"
                    class="sidebar-link
                        {{
                            request()->routeIs('admin.users.*')
                                ? 'active'
                                : ''
                        }}"
                >

                    <i class="bi bi-person-lock"></i>

                    <span>
                        User Account Management
                    </span>

                </a>

            @else

                <div
                    class="sidebar-link role-disabled"
                    aria-disabled="true"
                    title="Only Super Admin can manage user accounts"
                >

                    <i class="bi bi-person-lock"></i>

                    <span>
                        User Account Management
                    </span>

                </div>

            @endif

        @endif


        {{-- =================================================
             BRANCHES
             ALL ROLES CAN VIEW / SEARCH
        ================================================== --}}

        @if(Route::has('admin.branches.index'))

            <a
                href="{{ route('admin.branches.index') }}"
                class="sidebar-link
                    {{
                        request()->routeIs('admin.branches.*')
                            ? 'active'
                            : ''
                    }}"
            >

                <i class="bi bi-building"></i>

                <span>
                    Branches
                </span>

            </a>

        @endif


        {{-- =================================================
             DEPARTMENTS
             ALL ROLES CAN VIEW / SEARCH
        ================================================== --}}

        @if(Route::has('admin.departments.index'))

            <a
                href="{{ route('admin.departments.index') }}"
                class="sidebar-link
                    {{
                        request()->routeIs('admin.departments.*')
                            ? 'active'
                            : ''
                    }}"
            >

                <i class="bi bi-diagram-3"></i>

                <span>
                    Departments
                </span>

            </a>

        @endif


        {{-- =================================================
             FUNCTION UNITS
             ALL ROLES CAN VIEW / SEARCH
        ================================================== --}}

        @if(Route::has('admin.function-units.index'))

            <a
                href="{{ route('admin.function-units.index') }}"
                class="sidebar-link
                    {{
                        request()->routeIs(
                            'admin.function-units.*'
                        )
                            ? 'active'
                            : ''
                    }}"
            >

                <i class="bi bi-diagram-2"></i>

                <span>
                    Function Units
                </span>

            </a>

        @endif


        {{-- =================================================
             INVENTORY
             ALL ROLES
        ================================================== --}}

        <div class="sidebar-section-title">
            Inventory
        </div>


        <div class="inventory-section">


            {{-- =================================================
                 INVENTORY HEADER
                 ALL ROLES
            ================================================== --}}

            <button
                class="inventory-header"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#inventoryMenu"

                aria-expanded="{{
                    request()->routeIs('admin.items.*')
                    || request()->routeIs('admin.item-types.*')
                    || request()->routeIs('admin.makes.*')
                    || request()->routeIs('admin.item-models.*')
                    || request()->routeIs('admin.inventory.*')
                        ? 'true'
                        : 'false'
                }}"

                aria-controls="inventoryMenu"
            >

                <span class="inventory-header-left">

                    <i class="bi bi-box-seam"></i>

                    <span>
                        Inventory
                    </span>

                </span>

                <i
                    class="bi bi-chevron-down inventory-chevron"
                ></i>

            </button>


            {{-- =================================================
                 INVENTORY MENU
            ================================================== --}}

            <div
                class="collapse
                    {{
                        request()->routeIs('admin.items.*')
                        || request()->routeIs('admin.item-types.*')
                        || request()->routeIs('admin.makes.*')
                        || request()->routeIs('admin.item-models.*')
                        || request()->routeIs('admin.inventory.*')
                            ? 'show'
                            : ''
                    }}"

                id="inventoryMenu"
            >

                <div class="inventory-submenu">


                    {{-- =================================================
                         INVENTORY REGISTER
                    ================================================== --}}

                    <div class="inventory-group-title">
                        Inventory Register
                    </div>


                    {{-- =================================================
                         INVENTORY ITEMS
                    ================================================== --}}

                    @if(Route::has('admin.items.index'))

                        <a
                            href="{{ route('admin.items.index') }}"
                            class="sidebar-link
                                {{
                                    request()->routeIs(
                                        'admin.items.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}"
                        >

                            <i class="bi bi-pc-display"></i>

                            <span>
                                Inventory Items
                            </span>

                        </a>

                    @endif


                    {{-- =================================================
                         ITEM TYPES
                    ================================================== --}}

                    @if(Route::has('admin.item-types.index'))

                        <a
                            href="{{ route('admin.item-types.index') }}"
                            class="sidebar-link
                                {{
                                    request()->routeIs(
                                        'admin.item-types.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}"
                        >

                            <i class="bi bi-tags"></i>

                            <span>
                                Item Types
                            </span>

                        </a>

                    @endif


                    {{-- =================================================
                         MAKES
                         SUPER ADMIN ONLY
                    ================================================== --}}

                    @if(
                        $isSuperAdmin &&
                        Route::has('admin.makes.index')
                    )

                        <a
                            href="{{ route('admin.makes.index') }}"
                            class="sidebar-link master-data-link
                                {{
                                    request()->routeIs(
                                        'admin.makes.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}"
                        >

                            <i class="bi bi-building"></i>

                            <span>
                                Makes
                            </span>

                            <span class="master-data-badge">
                                Admin
                            </span>

                        </a>

                    @endif


                    {{-- =================================================
                         MODELS
                         SUPER ADMIN ONLY
                    ================================================== --}}

                    @if(
                        $isSuperAdmin &&
                        Route::has('admin.item-models.index')
                    )

                        <a
                            href="{{ route('admin.item-models.index') }}"
                            class="sidebar-link master-data-link
                                {{
                                    request()->routeIs(
                                        'admin.item-models.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}"
                        >

                            <i class="bi bi-cpu"></i>

                            <span>
                                Models
                            </span>

                            <span class="master-data-badge">
                                Admin
                            </span>

                        </a>

                    @endif


                    {{-- =================================================
                         TEMPORARY ITEMS
                         ALL ROLES
                    ================================================== --}}

                    @if(
                        Route::has(
                            'admin.inventory.temporary-items.index'
                        )
                    )

                        <a
                            href="{{
                                route(
                                    'admin.inventory.temporary-items.index'
                                )
                            }}"
                            class="sidebar-link temporary-item-link
                                {{
                                    request()->routeIs(
                                        'admin.inventory.temporary-items.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}"
                        >

                            <i class="bi bi-box-seam"></i>

                            <span>
                                Temporary Items
                            </span>

                        </a>

                    @endif


                    {{-- =================================================
                         REPAIR MANAGEMENT
                    ================================================== --}}

                    <div class="inventory-group-title">
                        Repair Management
                    </div>


                    {{-- =================================================
                         REPAIRS
                    ================================================== --}}

                    @if(
                        Route::has(
                            'admin.inventory.repairs.index'
                        )
                    )

                        <a
                            href="{{
                                route(
                                    'admin.inventory.repairs.index'
                                )
                            }}"
                            class="sidebar-link
                                {{
                                    request()->routeIs(
                                        'admin.inventory.repairs.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}"
                        >

                            <i class="bi bi-tools"></i>

                            <span>
                                Repairs
                            </span>

                        </a>

                    @endif


                    {{-- =================================================
                         SPARE PARTS
                    ================================================== --}}

                    @if(
                        Route::has(
                            'admin.inventory.repairs.spare-parts.index'
                        )
                    )

                        <a
                            href="{{
                                route(
                                    'admin.inventory.repairs.spare-parts.index'
                                )
                            }}"
                            class="sidebar-link
                                {{
                                    request()->routeIs(
                                        'admin.inventory.repairs.spare-parts.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}"
                        >

                            <i class="bi bi-boxes"></i>

                            <span>
                                Spare Parts
                            </span>

                        </a>

                    @endif


                    {{-- =================================================
                         SUPPLIERS
                    ================================================== --}}

                    @if(
                        Route::has(
                            'admin.inventory.repairs.suppliers.index'
                        )
                    )

                        <a
                            href="{{
                                route(
                                    'admin.inventory.repairs.suppliers.index'
                                )
                            }}"
                            class="sidebar-link
                                {{
                                    request()->routeIs(
                                        'admin.inventory.repairs.suppliers.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}"
                        >

                            <i class="bi bi-truck"></i>

                            <span>
                                Suppliers
                            </span>

                        </a>

                    @endif


                    {{-- =================================================
                         RDD MANAGEMENT
                    ================================================== --}}

                    <div class="inventory-group-title">
                        RDD Management
                    </div>


                    @if(
                        Route::has(
                            'admin.inventory.rdd.index'
                        )
                    )

                        <a
                            href="{{
                                route(
                                    'admin.inventory.rdd.index'
                                )
                            }}"
                            class="sidebar-link
                                {{
                                    request()->routeIs(
                                        'admin.inventory.rdd.*'
                                    )
                                        ? 'active'
                                        : ''
                                }}"
                        >

                            <i class="bi bi-box-arrow-up-right"></i>

                            <span>
                                RDD Management
                            </span>

                        </a>

                    @endif


                </div>

            </div>

        </div>


        {{-- =====================================================
             TEMPORARY ITEM ISSUE
             STANDALONE MODULE
        ====================================================== --}}

        @if(Route::has('admin.temporary-item-issues.index'))

            <div class="sidebar-section-title">
                Temporary Operations
            </div>

            <a
                href="{{ route('admin.temporary-item-issues.index') }}"
                class="sidebar-link
                    {{
                        request()->routeIs(
                            'admin.temporary-item-issues.*'
                        )
                            ? 'active'
                            : ''
                    }}"
            >

                <i class="bi bi-arrow-left-right"></i>

                <span>
                    Temporary Item Issue
                </span>

            </a>

        @endif


        {{-- =================================================
             SYSTEM
        ================================================== --}}

        <div class="sidebar-section-title">
            System
        </div>


        {{-- =================================================
             REPORTS
             ALL AUTHENTICATED ROLES
        ================================================== --}}

        @if(
            auth()->check() &&
            Route::has('admin.reports.index')
        )

            <a
                href="{{ route('admin.reports.index') }}"
                class="sidebar-link
                    {{
                        request()->routeIs(
                            'admin.reports.*'
                        )
                            ? 'active'
                            : ''
                    }}"
            >

                <i class="bi bi-bar-chart"></i>

                <span>
                    Reports
                </span>

            </a>

        @endif


        {{-- =================================================
             SETTINGS
             SUPER ADMIN ONLY
        ================================================== --}}

        @if(Route::has('admin.settings.index'))

            @if($isSuperAdmin)

                <a
                    href="{{ route('admin.settings.index') }}"
                    class="sidebar-link
                        {{
                            request()->routeIs(
                                'admin.settings.*'
                            )
                                ? 'active'
                                : ''
                        }}"
                >

                    <i class="bi bi-gear"></i>

                    <span>
                        Settings
                    </span>

                </a>

            @else

                <div
                    class="sidebar-link role-disabled"
                    aria-disabled="true"
                    title="Only Super Admin can access Settings"
                >

                    <i class="bi bi-gear"></i>

                    <span>
                        Settings
                    </span>

                </div>

            @endif

        @endif


    </nav>

</aside>


{{-- =====================================================
     MAIN AREA
====================================================== --}}

<main class="admin-main">


    {{-- =================================================
         TOP NAVBAR
    ================================================== --}}

    <header class="admin-navbar">

        <div>

            <div class="navbar-page-title">

                @yield(
                    'page-heading',
                    'CPC Inventory Management System'
                )

            </div>

            <div class="navbar-page-subtitle">

                Ceylon Petroleum Corporation — IT Function

            </div>

        </div>


        {{-- =================================================
             LOGGED-IN USER + LOGOUT
        ================================================== --}}

        <div class="navbar-right">

            <div class="navbar-user">


                {{-- =================================================
                     PROFILE PICTURE / DEFAULT AVATAR
                ================================================== --}}

                @if(
                    Auth::check() &&
                    !empty(Auth::user()->profile_picture)
                )

                    <img
                        src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                        alt="{{ Auth::user()->name }}"
                        class="navbar-user-avatar"
                    >

                @else

                    <div class="navbar-user-icon">

                        <i class="bi bi-person"></i>

                    </div>

                @endif


                {{-- =================================================
                     USER INFORMATION
                ================================================== --}}

                <div>

                    <div class="navbar-user-name">

                        {{ Auth::user()->name ?? 'Administrator' }}

                    </div>

                    <div class="navbar-user-role">

                        @if($isSuperAdmin)

                            Super Admin

                        @elseif($isAdmin)

                            Standard Admin

                        @elseif($isEmployee)

                            Employee

                        @else

                            Administrator

                        @endif

                    </div>

                </div>

            </div>


            {{-- =================================================
                 LOGOUT
            ================================================== --}}

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="m-0"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-danger btn-sm"
                    title="Logout"
                >

                    <i class="bi bi-box-arrow-right me-1"></i>

                    Logout

                </button>

            </form>

        </div>

    </header>


    {{-- =================================================
         PAGE CONTENT
    ================================================== --}}

    <section class="admin-content">


        {{-- =================================================
             SUCCESS MESSAGE
        ================================================== --}}

        @if(session('success'))

            <div
                class="alert alert-success
                    alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- =================================================
             ERROR MESSAGE
        ================================================== --}}

        @if(session('error'))

            <div
                class="alert alert-danger
                    alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- =================================================
             VALIDATION ERRORS
        ================================================== --}}

        @if($errors->any())

            <div
                class="alert alert-danger
                    alert-dismissible fade show"
                role="alert"
            >

                <div class="fw-semibold mb-1">

                    <i class="bi bi-exclamation-triangle me-2"></i>

                    Please correct the following errors:

                </div>

                <ul class="mb-0 ps-4">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @yield('content')

    </section>


    {{-- =================================================
         FOOTER
    ================================================== --}}

    <footer class="admin-footer">

        <div
            class="d-flex
                justify-content-between
                flex-wrap
                gap-2"
        >

            <span>

                © {{ date('Y') }}
                Ceylon Petroleum Corporation

            </span>

            <span>

                CPC IT Inventory Management System

            </span>

        </div>

    </footer>

</main>


</div>


{{-- =====================================================
     BOOTSTRAP JS
====================================================== --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

@stack('scripts')

</body>

</html>

