@extends('layouts.admin')

@section('title', 'Spare Parts | CPC Inventory Management System')

@section('content')

<style>
/* =========================================================
   CPC SPARE PARTS DASHBOARD
   Scoped styles - no backend/controller changes required
========================================================= */

.spare-parts-page {
    max-width: 1600px;
    margin: 0 auto;
    padding: 24px 0 40px;
}

/* =========================================================
   HERO HEADER
========================================================= */

.spare-parts-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #ffffff 0%, #f6f9ff 58%, #eaf2ff 100%);
    border: 1px solid #dfe7f3;
    border-radius: 20px;
    padding: 26px 28px;
    margin-bottom: 20px;
    box-shadow: 0 8px 28px rgba(30, 55, 90, .06);
}

.spare-parts-hero::before,
.spare-parts-hero::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}

.spare-parts-hero::before {
    width: 300px;
    height: 300px;
    right: -130px;
    top: -165px;
    background: rgba(13, 110, 253, .07);
}

.spare-parts-hero::after {
    width: 190px;
    height: 190px;
    right: 150px;
    bottom: -135px;
    background: rgba(13, 110, 253, .045);
}

.spare-parts-hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
}

.spare-parts-title-wrap {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    min-width: 0;
}

.spare-parts-hero-icon {
    width: 60px;
    height: 60px;
    min-width: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0d6efd, #3d8bfd);
    color: #fff;
    font-size: 25px;
    box-shadow: 0 10px 24px rgba(13, 110, 253, .20);
}

.spare-parts-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
}

.spare-parts-breadcrumb a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    text-decoration: none;
    transition: color .2s ease;
}

.spare-parts-breadcrumb a:hover {
    color: #0d6efd;
}

.spare-parts-context {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 9px;
    margin-bottom: 8px;
    border-radius: 999px;
    background: #eaf2ff;
    color: #0d6efd;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .45px;
}

.spare-parts-title {
    margin: 0;
    color: #172033;
    font-size: 28px;
    line-height: 1.2;
    font-weight: 750;
    letter-spacing: -.35px;
}

.spare-parts-subtitle {
    margin: 7px 0 0;
    max-width: 720px;
    color: #718096;
    font-size: 13px;
    line-height: 1.6;
}

.spare-parts-header-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
}

/* =========================================================
   BUTTONS
========================================================= */

.spare-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 15px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 650;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all .2s ease;
    white-space: nowrap;
}

.spare-btn-primary {
    background: #0d6efd;
    color: #fff;
    box-shadow: 0 5px 14px rgba(13, 110, 253, .16);
}

.spare-btn-primary:hover {
    background: #0b5ed7;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 7px 17px rgba(13, 110, 253, .21);
}

.spare-btn-secondary {
    background: #fff;
    color: #374151;
    border-color: #dbe3ec;
}

.spare-btn-secondary:hover {
    background: #f8fafc;
    color: #111827;
    border-color: #cbd5e1;
}

/* =========================================================
   STATISTICS
========================================================= */

.spare-stats-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}

.spare-stat-card {
    position: relative;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 15px;
    padding: 17px;
    box-shadow: 0 5px 18px rgba(15, 23, 42, .045);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.spare-stat-card::after {
    content: "";
    position: absolute;
    width: 75px;
    height: 75px;
    right: -28px;
    bottom: -32px;
    border-radius: 50%;
    background: rgba(13, 110, 253, .035);
}

.spare-stat-card:hover {
    transform: translateY(-2px);
    border-color: #d5e2f4;
    box-shadow: 0 9px 24px rgba(15, 23, 42, .07);
}

.spare-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.spare-stat-label {
    color: #64748b;
    font-size: 10px;
    line-height: 1.3;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .55px;
}

.spare-stat-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.spare-icon-blue {
    background: #e8f1ff;
    color: #0d6efd;
}

.spare-icon-green {
    background: #e8f7ef;
    color: #198754;
}

.spare-icon-orange {
    background: #fff3df;
    color: #f59e0b;
}

.spare-icon-red {
    background: #ffe8e8;
    color: #dc3545;
}

.spare-icon-purple {
    background: #f0e9ff;
    color: #6f42c1;
}

.spare-icon-cyan {
    background: #e5f8fb;
    color: #0aa2c0;
}

.spare-stat-value {
    margin-top: 12px;
    color: #172033;
    font-size: 24px;
    line-height: 1.1;
    font-weight: 750;
    letter-spacing: -.3px;
}

.spare-stat-subtitle {
    margin-top: 5px;
    color: #94a3b8;
    font-size: 11px;
}

/* =========================================================
   FILTER CARD
========================================================= */

.spare-filter-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    margin-bottom: 20px;
    box-shadow: 0 5px 18px rgba(15, 23, 42, .045);
    overflow: hidden;
}

.spare-filter-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 17px 20px;
    border-bottom: 1px solid #edf1f5;
}

.spare-filter-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf2ff;
    color: #0d6efd;
    font-size: 15px;
}

.spare-filter-title {
    margin: 0;
    color: #172033;
    font-size: 14px;
    font-weight: 700;
}

.spare-filter-description {
    margin: 2px 0 0;
    color: #94a3b8;
    font-size: 11px;
}

.spare-filter-body {
    padding: 18px 20px;
}

.spare-filter-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
    gap: 12px;
    align-items: end;
}

.spare-form-group label {
    display: block;
    margin-bottom: 6px;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
}

.spare-form-control {
    width: 100%;
    min-height: 42px;
    padding: 9px 12px;
    border: 1px solid #d9e2ec;
    border-radius: 9px;
    background: #fff;
    color: #1f2937;
    font-size: 12px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.spare-form-control:hover {
    border-color: #cbd5e1;
}

.spare-form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, .09);
}

.spare-form-control::placeholder {
    color: #a0aec0;
}

.spare-filter-buttons {
    display: flex;
    align-items: center;
    gap: 7px;
}

.spare-filter-btn {
    min-height: 42px;
    padding: 9px 15px;
    border: 0;
    border-radius: 9px;
    background: #0d6efd;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s ease;
}

.spare-filter-btn:hover {
    background: #0b5ed7;
}

.spare-reset-btn {
    width: 42px;
    min-height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d9e2ec;
    border-radius: 9px;
    background: #fff;
    color: #64748b;
    text-decoration: none;
    transition: all .2s ease;
}

.spare-reset-btn:hover {
    background: #f8fafc;
    color: #0d6efd;
    border-color: #cbd5e1;
}

/* =========================================================
   TABLE CARD
========================================================= */

.spare-table-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 5px 18px rgba(15, 23, 42, .045);
}

.spare-table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 18px 20px;
    border-bottom: 1px solid #edf1f5;
}

.spare-table-heading {
    display: flex;
    align-items: center;
    gap: 12px;
}

.spare-table-heading-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    color: #475569;
    font-size: 17px;
}

.spare-table-title {
    margin: 0;
    color: #172033;
    font-size: 15px;
    font-weight: 750;
}

.spare-table-description {
    margin: 3px 0 0;
    color: #94a3b8;
    font-size: 11px;
}

.spare-record-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.spare-record-pill i {
    color: #0d6efd;
}

/* =========================================================
   TABLE
========================================================= */

.spare-table-scroll {
    width: 100%;
    overflow-x: auto;
    scrollbar-width: thin;
}

.spare-table-scroll::-webkit-scrollbar {
    height: 7px;
}

.spare-table-scroll::-webkit-scrollbar-track {
    background: #f8fafc;
}

.spare-table-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.spare-parts-table {
    width: 100%;
    min-width: 1280px;
    border-collapse: collapse;
}

.spare-parts-table thead th {
    padding: 12px 15px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    color: #64748b;
    font-size: 10px;
    font-weight: 750;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
}

.spare-parts-table tbody td {
    padding: 14px 15px;
    border-bottom: 1px solid #edf1f5;
    color: #475569;
    font-size: 12px;
    vertical-align: middle;
}

.spare-parts-table tbody tr {
    transition: background .15s ease;
}

.spare-parts-table tbody tr:hover {
    background: #f9fbff;
}

.spare-parts-table tbody tr:last-child td {
    border-bottom: 0;
}

/* =========================================================
   PART IDENTITY
========================================================= */

.spare-part-identity {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 175px;
}

.spare-part-icon {
    width: 35px;
    height: 35px;
    min-width: 35px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf2ff;
    color: #0d6efd;
    font-size: 15px;
}

.spare-part-code {
    color: #0d6efd;
    font-size: 12px;
    font-weight: 750;
}

.spare-part-name {
    color: #1f2937;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.4;
}

.spare-part-category {
    margin-top: 2px;
    color: #94a3b8;
    font-size: 10px;
}

.spare-category-text {
    color: #64748b;
    font-size: 11px;
}

.spare-supplier {
    max-width: 175px;
    color: #475569;
    font-size: 11px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* =========================================================
   STOCK
========================================================= */

.spare-stock-cell {
    min-width: 110px;
}

.spare-stock-number {
    display: block;
    margin-bottom: 5px;
    color: #172033;
    font-size: 13px;
    font-weight: 750;
}

.spare-stock-low {
    color: #b76e00;
}

.spare-stock-out {
    color: #dc3545;
}

/* =========================================================
   BADGES
========================================================= */

.spare-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.spare-status-badge::before {
    content: "";
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
}

.spare-badge-active {
    background: #e8f7ef;
    color: #198754;
}

.spare-badge-inactive {
    background: #eef1f4;
    color: #64748b;
}

.spare-badge-good {
    background: #e8f7ef;
    color: #198754;
}

.spare-badge-low {
    background: #fff3df;
    color: #b76e00;
}

.spare-badge-out {
    background: #ffe8e8;
    color: #dc3545;
}

.spare-badge-warranty {
    background: #e8f1ff;
    color: #0d6efd;
}

.spare-badge-expiring {
    background: #fff3df;
    color: #b76e00;
}

.spare-badge-expired {
    background: #ffe8e8;
    color: #dc3545;
}

.spare-badge-none {
    background: #eef1f4;
    color: #64748b;
}

/* =========================================================
   PRICE / LOCATION
========================================================= */

.spare-price {
    color: #172033;
    font-weight: 700;
    white-space: nowrap;
}

.spare-location {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #64748b;
    white-space: nowrap;
}

.spare-location i {
    color: #94a3b8;
}

/* =========================================================
   ACTIONS
========================================================= */

.spare-actions {
    display: flex;
    align-items: center;
    gap: 5px;
}

.spare-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e0e6ed;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
    font-size: 12px;
    transition: all .18s ease;
}

.spare-action-view {
    color: #0d6efd;
}

.spare-action-view:hover {
    background: #e8f1ff;
    border-color: #cfe0ff;
}

.spare-action-edit {
    color: #f59e0b;
}

.spare-action-edit:hover {
    background: #fff3df;
    border-color: #f8dfb4;
}

.spare-action-delete {
    color: #dc3545;
}

.spare-action-delete:hover {
    background: #ffe8e8;
    border-color: #f5caca;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.spare-empty-state {
    padding: 75px 20px;
    text-align: center;
}

.spare-empty-icon {
    width: 68px;
    height: 68px;
    margin: 0 auto 16px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf2ff;
    color: #0d6efd;
    font-size: 27px;
}

.spare-empty-state h3 {
    margin: 0 0 7px;
    color: #172033;
    font-size: 18px;
    font-weight: 750;
}

.spare-empty-state p {
    max-width: 510px;
    margin: 0 auto 20px;
    color: #718096;
    font-size: 12px;
    line-height: 1.6;
}

/* =========================================================
   PAGINATION
========================================================= */

.spare-pagination {
    padding: 17px 20px;
    border-top: 1px solid #edf1f5;
}

/* =========================================================
   MOBILE SCROLL NOTE
========================================================= */

.spare-scroll-note {
    display: none;
    align-items: center;
    gap: 6px;
    padding: 8px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #edf1f5;
    color: #94a3b8;
    font-size: 10px;
}

.spare-scroll-note i {
    color: #0d6efd;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1450px) {

    .spare-stats-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .spare-filter-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .spare-filter-grid .spare-form-group:first-child {
        grid-column: span 3;
    }

    .spare-filter-buttons {
        grid-column: span 3;
    }
}

@media (max-width: 1000px) {

    .spare-parts-hero-content {
        align-items: flex-start;
        flex-direction: column;
    }

    .spare-parts-header-actions {
        width: 100%;
    }

    .spare-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .spare-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .spare-filter-grid .spare-form-group:first-child,
    .spare-filter-buttons {
        grid-column: span 2;
    }
}

@media (max-width: 700px) {

    .spare-parts-page {
        padding: 16px 0 30px;
    }

    .spare-parts-hero {
        border-radius: 16px;
        padding: 20px;
    }

    .spare-parts-title-wrap {
        gap: 12px;
    }

    .spare-parts-hero-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        border-radius: 13px;
        font-size: 21px;
    }

    .spare-parts-title {
        font-size: 23px;
    }

    .spare-parts-subtitle {
        font-size: 12px;
    }

    .spare-parts-header-actions {
        flex-direction: column;
    }

    .spare-btn {
        width: 100%;
    }

    .spare-stats-grid {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .spare-stat-card {
        padding: 14px;
    }

    .spare-stat-value {
        font-size: 21px;
    }

    .spare-filter-grid {
        grid-template-columns: 1fr;
    }

    .spare-filter-grid .spare-form-group:first-child,
    .spare-filter-buttons {
        grid-column: auto;
    }

    .spare-filter-buttons {
        width: 100%;
    }

    .spare-filter-btn {
        flex: 1;
    }

    .spare-table-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 16px;
    }

    .spare-record-pill {
        align-self: flex-start;
    }

    .spare-scroll-note {
        display: flex;
    }

    .spare-pagination {
        padding: 14px 16px;
        overflow-x: auto;
    }
}

@media (max-width: 480px) {

    .spare-stats-grid {
        grid-template-columns: 1fr;
    }

    .spare-parts-title {
        font-size: 21px;
    }

    .spare-parts-breadcrumb {
        font-size: 10px;
    }

    .spare-filter-header,
    .spare-filter-body {
        padding-left: 15px;
        padding-right: 15px;
    }
}
</style>


<div class="spare-parts-page">

    {{-- =====================================================
         HERO HEADER
    ====================================================== --}}
    <div class="spare-parts-hero">

        <div class="spare-parts-hero-content">

            <div class="spare-parts-title-wrap">

                <div class="spare-parts-hero-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>

                    <div class="spare-parts-breadcrumb">

                        <a href="{{ route('admin.inventory.repairs.index') }}">
                            <i class="bi bi-tools"></i>
                            Repair Management
                        </a>

                        <i class="bi bi-chevron-right"></i>

                        <span>Spare Parts</span>

                    </div>

                    <div class="spare-parts-context">
                        <i class="bi bi-shield-check"></i>
                        CPC Inventory Administration
                    </div>

                    <h1 class="spare-parts-title">
                        Spare Parts
                    </h1>

                    <p class="spare-parts-subtitle">
                        Manage repair spare parts, stock levels, suppliers,
                        warranties and inventory usage from one central register.
                    </p>

                </div>

            </div>


            <div class="spare-parts-header-actions">

                <a href="{{ route('admin.inventory.repairs.index') }}"
                   class="spare-btn spare-btn-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Repair Management

                </a>

                <a href="{{ route('admin.inventory.repairs.spare-parts.create') }}"
                   class="spare-btn spare-btn-primary">

                    <i class="bi bi-plus-lg"></i>
                    Add Spare Part

                </a>

            </div>

        </div>

    </div>


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}
    <div class="spare-stats-grid">

        {{-- Total --}}
        <div class="spare-stat-card">

            <div class="spare-stat-top">

                <span class="spare-stat-label">
                    Total Parts
                </span>

                <div class="spare-stat-icon spare-icon-blue">
                    <i class="bi bi-boxes"></i>
                </div>

            </div>

            <div class="spare-stat-value">
                {{ number_format($totalParts ?? 0) }}
            </div>

            <div class="spare-stat-subtitle">
                Registered spare parts
            </div>

        </div>


        {{-- Active --}}
        <div class="spare-stat-card">

            <div class="spare-stat-top">

                <span class="spare-stat-label">
                    Active
                </span>

                <div class="spare-stat-icon spare-icon-green">
                    <i class="bi bi-check-circle"></i>
                </div>

            </div>

            <div class="spare-stat-value">
                {{ number_format($activeParts ?? 0) }}
            </div>

            <div class="spare-stat-subtitle">
                Currently active
            </div>

        </div>


        {{-- Low Stock --}}
        <div class="spare-stat-card">

            <div class="spare-stat-top">

                <span class="spare-stat-label">
                    Low Stock
                </span>

                <div class="spare-stat-icon spare-icon-orange">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

            </div>

            <div class="spare-stat-value">
                {{ number_format($lowStockParts ?? 0) }}
            </div>

            <div class="spare-stat-subtitle">
                At or below minimum
            </div>

        </div>


        {{-- Out of Stock --}}
        <div class="spare-stat-card">

            <div class="spare-stat-top">

                <span class="spare-stat-label">
                    Out of Stock
                </span>

                <div class="spare-stat-icon spare-icon-red">
                    <i class="bi bi-x-circle"></i>
                </div>

            </div>

            <div class="spare-stat-value">
                {{ number_format($outOfStockParts ?? 0) }}
            </div>

            <div class="spare-stat-subtitle">
                Zero quantity
            </div>

        </div>


        {{-- Total Stock --}}
        <div class="spare-stat-card">

            <div class="spare-stat-top">

                <span class="spare-stat-label">
                    Total Stock
                </span>

                <div class="spare-stat-icon spare-icon-purple">
                    <i class="bi bi-stack"></i>
                </div>

            </div>

            <div class="spare-stat-value">
                {{ number_format($totalStock ?? 0) }}
            </div>

            <div class="spare-stat-subtitle">
                Units currently available
            </div>

        </div>


        {{-- Stock Value --}}
        <div class="spare-stat-card">

            <div class="spare-stat-top">

                <span class="spare-stat-label">
                    Stock Value
                </span>

                <div class="spare-stat-icon spare-icon-cyan">
                    <i class="bi bi-cash-stack"></i>
                </div>

            </div>

            <div class="spare-stat-value">
                {{ number_format($totalStockValue ?? 0, 2) }}
            </div>

            <div class="spare-stat-subtitle">
                Current inventory value
            </div>

        </div>

    </div>


    {{-- =====================================================
         FILTERS
    ====================================================== --}}
    <div class="spare-filter-card">

        <div class="spare-filter-header">

            <div class="spare-filter-icon">
                <i class="bi bi-funnel"></i>
            </div>

            <div>

                <h2 class="spare-filter-title">
                    Search & Filter Spare Parts
                </h2>

                <p class="spare-filter-description">
                    Quickly locate parts using inventory, stock and warranty criteria.
                </p>

            </div>

        </div>


        <div class="spare-filter-body">

            <form method="GET"
                  action="{{ route('admin.inventory.repairs.spare-parts.index') }}">

                <div class="spare-filter-grid">

                    {{-- Search --}}
                    <div class="spare-form-group">

                        <label for="search">
                            Search
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="spare-form-control"
                            value="{{ request('search') }}"
                            placeholder="Part code, name, supplier..."
                        >

                    </div>


                    {{-- Category --}}
                    <div class="spare-form-group">

                        <label for="category">
                            Category
                        </label>

                        <select
                            name="category"
                            id="category"
                            class="spare-form-control"
                        >

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories ?? [] as $category)

                                <option
                                    value="{{ $category }}"
                                    @selected(request('category') == $category)
                                >
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="spare-form-group">

                        <label for="status">
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="spare-form-control"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="active"
                                @selected(request('status') === 'active')
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                @selected(request('status') === 'inactive')
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Stock --}}
                    <div class="spare-form-group">

                        <label for="stock">
                            Stock
                        </label>

                        <select
                            name="stock"
                            id="stock"
                            class="spare-form-control"
                        >

                            <option value="">
                                All Stock
                            </option>

                            <option
                                value="low"
                                @selected(request('stock') === 'low')
                            >
                                Low Stock
                            </option>

                            <option
                                value="out"
                                @selected(request('stock') === 'out')
                            >
                                Out of Stock
                            </option>

                            <option
                                value="available"
                                @selected(request('stock') === 'available')
                            >
                                Available
                            </option>

                        </select>

                    </div>


                    {{-- Warranty --}}
                    <div class="spare-form-group">

                        <label for="warranty">
                            Warranty
                        </label>

                        <select
                            name="warranty"
                            id="warranty"
                            class="spare-form-control"
                        >

                            <option value="">
                                All Warranty
                            </option>

                            <option
                                value="active"
                                @selected(request('warranty') === 'active')
                            >
                                Active
                            </option>

                            <option
                                value="expiring"
                                @selected(request('warranty') === 'expiring')
                            >
                                Expiring Soon
                            </option>

                            <option
                                value="expired"
                                @selected(request('warranty') === 'expired')
                            >
                                Expired
                            </option>

                            <option
                                value="none"
                                @selected(request('warranty') === 'none')
                            >
                                No Warranty
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="spare-filter-buttons">

                        <button
                            type="submit"
                            class="spare-filter-btn"
                        >
                            <i class="bi bi-search"></i>
                            Filter
                        </button>

                        <a
                            href="{{ route('admin.inventory.repairs.spare-parts.index') }}"
                            class="spare-reset-btn"
                            title="Reset filters"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         SPARE PARTS REGISTER
    ====================================================== --}}
    <div class="spare-table-card">

        <div class="spare-table-header">

            <div class="spare-table-heading">

                <div class="spare-table-heading-icon">
                    <i class="bi bi-clipboard-data"></i>
                </div>

                <div>

                    <h2 class="spare-table-title">
                        Spare Parts Inventory
                    </h2>

                    <p class="spare-table-description">
                        Registered repair parts and current stock information
                    </p>

                </div>

            </div>


            <div class="spare-record-pill">

                <i class="bi bi-database"></i>

                {{ $spareParts->total() ?? 0 }}
                record(s)

            </div>

        </div>


        @if($spareParts->count())

            <div class="spare-scroll-note">
                <i class="bi bi-arrow-left-right"></i>
                Scroll horizontally to view all inventory columns.
            </div>


            <div class="spare-table-scroll">

                <table class="spare-parts-table">

                    <thead>

                        <tr>

                            <th>Part</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Stock</th>
                            <th>Minimum</th>
                            <th>Unit Price</th>
                            <th>Warranty</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($spareParts as $part)

                            <tr>

                                {{-- Part --}}
                                <td>

                                    <div class="spare-part-identity">

                                        <div class="spare-part-icon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>

                                        <div>

                                            <div class="spare-part-code">
                                                {{ $part->part_code }}
                                            </div>

                                            <div class="spare-part-name">
                                                {{ $part->part_name }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Category --}}
                                <td>

                                    <div class="spare-category-text">
                                        {{ $part->category ?: '—' }}
                                    </div>

                                </td>


                                {{-- Supplier --}}
                                <td>

                                    <div
                                        class="spare-supplier"
                                        title="{{ $part->supplier_name }}"
                                    >
                                        {{ $part->supplier_name ?: '—' }}
                                    </div>

                                </td>


                                {{-- Stock --}}
                                <td>

                                    <div class="spare-stock-cell">

                                        @if($part->quantity <= 0)

                                            <span class="spare-stock-number spare-stock-out">
                                                {{ number_format($part->quantity) }}
                                            </span>

                                            <span class="spare-status-badge spare-badge-out">
                                                Out of Stock
                                            </span>

                                        @elseif($part->quantity <= $part->minimum_stock)

                                            <span class="spare-stock-number spare-stock-low">
                                                {{ number_format($part->quantity) }}
                                            </span>

                                            <span class="spare-status-badge spare-badge-low">
                                                Low
                                            </span>

                                        @else

                                            <span class="spare-stock-number">
                                                {{ number_format($part->quantity) }}
                                            </span>

                                            <span class="spare-status-badge spare-badge-good">
                                                Good
                                            </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- Minimum --}}
                                <td>

                                    <span class="quantity-value">
                                        {{ number_format($part->minimum_stock) }}
                                    </span>

                                </td>


                                {{-- Unit Price --}}
                                <td>

                                    <span class="spare-price">
                                        {{ number_format((float) $part->unit_price, 2) }}
                                    </span>

                                </td>


                                {{-- Warranty --}}
                                <td>

                                    @php
                                        $warrantyStatus = $part->warranty_status;
                                    @endphp

                                    @if($warrantyStatus === 'Active')

                                        <span class="spare-status-badge spare-badge-warranty">
                                            Active
                                        </span>

                                    @elseif($warrantyStatus === 'Expiring Soon')

                                        <span class="spare-status-badge spare-badge-expiring">
                                            Expiring Soon
                                        </span>

                                    @elseif($warrantyStatus === 'Expired')

                                        <span class="spare-status-badge spare-badge-expired">
                                            Expired
                                        </span>

                                    @else

                                        <span class="spare-status-badge spare-badge-none">
                                            No Warranty
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($part->status === 'active')

                                        <span class="spare-status-badge spare-badge-active">
                                            Active
                                        </span>

                                    @else

                                        <span class="spare-status-badge spare-badge-inactive">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Location --}}
                                <td>

                                    <span class="spare-location">

                                        <i class="bi bi-geo-alt"></i>

                                        {{ $part->location ?: '—' }}

                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="spare-actions">

                                        <a
                                            href="{{ route('admin.inventory.repairs.spare-parts.show', $part) }}"
                                            class="spare-action-btn spare-action-view"
                                            title="View Spare Part"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>


                                        <a
                                            href="{{ route('admin.inventory.repairs.spare-parts.edit', $part) }}"
                                            class="spare-action-btn spare-action-edit"
                                            title="Edit Spare Part"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route('admin.inventory.repairs.spare-parts.destroy', $part) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this spare part?');"
                                            style="display:inline;"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="spare-action-btn spare-action-delete"
                                                title="Delete Spare Part"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($spareParts->hasPages())

                <div class="spare-pagination">

                    {{ $spareParts->withQueryString()->links() }}

                </div>

            @endif


        @else

            {{-- Empty State --}}
            <div class="spare-empty-state">

                <div class="spare-empty-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <h3>
                    No Spare Parts Found
                </h3>

                <p>
                    There are currently no spare parts matching your
                    search or filter criteria.
                </p>

                <a
                    href="{{ route('admin.inventory.repairs.spare-parts.create') }}"
                    class="spare-btn spare-btn-primary"
                >
                    <i class="bi bi-plus-lg"></i>
                    Add First Spare Part
                </a>

            </div>

        @endif

    </div>

</div>

@endsection