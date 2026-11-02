@extends('layouts.admin')

@section('title', 'Reports | CPC Inventory Management System')

@section('content')

<div class="container-fluid py-3">


{{-- ============================================================
    PAGE HEADER
============================================================ --}}
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                <i class="bi bi-bar-chart-line-fill fs-5"></i>
            </div>

            <h1 class="h3 fw-bold text-dark mb-0">
                Reports
            </h1>
        </div>

        <p class="text-muted mb-0">
            Generate and review reports across the CPC Inventory Management System.
        </p>
    </div>

</div>


{{-- ============================================================
    REPORT SUMMARY
============================================================ --}}
<div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3 me-3">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>

                <div>
                    <div class="small text-muted">
                        Inventory Reports
                    </div>
                    <div class="fw-bold fs-5">
                        Available
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-success bg-opacity-10 text-success p-3 me-3">
                    <i class="bi bi-person-check-fill fs-4"></i>
                </div>

                <div>
                    <div class="small text-muted">
                        Assignment Reports
                    </div>
                    <div class="fw-bold fs-5">
                        Available
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-3 me-3">
                    <i class="bi bi-tools fs-4"></i>
                </div>

                <div>
                    <div class="small text-muted">
                        Repair Reports
                    </div>
                    <div class="fw-bold fs-5">
                        Available
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-danger bg-opacity-10 text-danger p-3 me-3">
                    <i class="bi bi-recycle fs-4"></i>
                </div>

                <div>
                    <div class="small text-muted">
                        RDD Reports
                    </div>
                    <div class="fw-bold fs-5">
                        Available
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


{{-- ============================================================
    REPORT CATEGORIES
============================================================ --}}
<div class="mb-3">
    <h5 class="fw-bold text-dark mb-1">
        Report Categories
    </h5>

    <p class="text-muted small mb-0">
        Select a category to view and generate the relevant reports.
    </p>
</div>


<div class="row g-4">

    {{-- ========================================================
        INVENTORY REPORTS
    ========================================================= --}}
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card report-category-card border-0 shadow-sm h-100">

            <div class="card-body p-4 d-flex flex-column">

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div class="report-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">
                        Inventory
                    </span>

                </div>

                <h5 class="fw-bold mb-2">
                    Inventory Reports
                </h5>

                <p class="text-muted small mb-4">
                    Review asset information, inventory status, item types,
                    locations and stock information.
                </p>

                <div class="small text-muted mb-4 report-features">

                    <div class="mb-2">
                        <i class="bi bi-check2 text-primary me-2"></i>
                        Inventory Summary
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-check2 text-primary me-2"></i>
                        Complete Inventory
                    </div>

                    <div>
                        <i class="bi bi-check2 text-primary me-2"></i>
                        Assets by Status & Location
                    </div>

                </div>

                <div class="mt-auto">
                    <a href="{{ route('admin.reports.inventory.summary') }}"
                       class="btn btn-primary w-100">
                        View Inventory Reports
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>


    {{-- ========================================================
        ASSIGNMENT REPORTS
    ========================================================= --}}
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card report-category-card border-0 shadow-sm h-100">

            <div class="card-body p-4 d-flex flex-column">

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div class="report-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-person-check-fill"></i>
                    </div>

                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                        Assignments
                    </span>

                </div>

                <h5 class="fw-bold mb-2">
                    Assignment Reports
                </h5>

                <p class="text-muted small mb-4">
                    Track assigned assets, employee asset records and
                    assignment and return history.
                </p>

                <div class="small text-muted mb-4 report-features">

                    <div class="mb-2">
                        <i class="bi bi-check2 text-success me-2"></i>
                        Employee Asset Report
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-check2 text-success me-2"></i>
                        Assignment History
                    </div>

                    <div>
                        <i class="bi bi-check2 text-success me-2"></i>
                        Unassigned Assets
                    </div>

                </div>

                <div class="mt-auto">
                    <a href="{{ route('admin.reports.assignments.index') }}"
                       class="btn btn-success w-100">
                        View Assignment Reports
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>


    {{-- ========================================================
        REPAIR REPORTS
    ========================================================= --}}
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card report-category-card border-0 shadow-sm h-100">

            <div class="card-body p-4 d-flex flex-column">

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div class="report-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-tools"></i>
                    </div>

                    <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning">
                        Repairs
                    </span>

                </div>

                <h5 class="fw-bold mb-2">
                    Repair Reports
                </h5>

                <p class="text-muted small mb-4">
                    Monitor repair activities, repair status, costs,
                    turnaround time and repair history.
                </p>

                <div class="small text-muted mb-4 report-features">

                    <div class="mb-2">
                        <i class="bi bi-check2 text-warning me-2"></i>
                        Repair Summary
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-check2 text-warning me-2"></i>
                        Pending & Completed Repairs
                    </div>

                    <div>
                        <i class="bi bi-check2 text-warning me-2"></i>
                        Repair Cost Report
                    </div>

                </div>

                <div class="mt-auto">
                    <a href="{{ route('admin.reports.repairs.index') }}"
                       class="btn btn-warning w-100 text-dark">
                        View Repair Reports
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>


    {{-- ========================================================
        RDD REPORTS
    ========================================================= --}}
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card report-category-card border-0 shadow-sm h-100">

            <div class="card-body p-4 d-flex flex-column">

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div class="report-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-recycle"></i>
                    </div>

                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger">
                        RDD
                    </span>

                </div>

                <h5 class="fw-bold mb-2">
                    RDD Reports
                </h5>

                <p class="text-muted small mb-4">
                    Review items recommended for disposal, RDD history,
                    locations and disposal-related information.
                </p>

                <div class="small text-muted mb-4 report-features">

                    <div class="mb-2">
                        <i class="bi bi-check2 text-danger me-2"></i>
                        RDD Summary
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-check2 text-danger me-2"></i>
                        RDD History
                    </div>

                    <div>
                        <i class="bi bi-check2 text-danger me-2"></i>
                        RDD by Location
                    </div>

                </div>

                {{-- IMPORTANT: Confirmed RDD report route --}}
                <div class="mt-auto">
                    <a href="{{ route('admin.reports.rdd.index') }}"
                       class="btn btn-danger w-100">
                        View RDD Reports
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>


    {{-- ========================================================
        TEMPORARY ITEM REPORTS
    ========================================================= --}}
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card report-category-card border-0 shadow-sm h-100">

            <div class="card-body p-4 d-flex flex-column">

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div class="report-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <span class="badge rounded-pill bg-info bg-opacity-10 text-info">
                        Temporary Items
                    </span>

                </div>

                <h5 class="fw-bold mb-2">
                    Temporary Item Reports
                </h5>

                <p class="text-muted small mb-4">
                    Track temporary item issues, returns, events,
                    employees and outstanding temporary items.
                </p>

                <div class="small text-muted mb-4 report-features">

                    <div class="mb-2">
                        <i class="bi bi-check2 text-info me-2"></i>
                        Issue Summary
                    </div>

                    <div class="mb-2">
                        <i class="bi bi-check2 text-info me-2"></i>
                        Current & Returned Items
                    </div>

                    <div>
                        <i class="bi bi-check2 text-info me-2"></i>
                        Overdue Items
                    </div>

                </div>
<div class="mt-auto">
    <a href="{{ route('admin.reports.temporary-items.index') }}"
       class="btn btn-info w-100 text-white">
        View Temporary Reports
        <i class="bi bi-arrow-right ms-2"></i>
    </a>
</div>

            </div>
        </div>
    </div>



</div>


{{-- ============================================================
    INFORMATION PANEL
============================================================ --}}
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body p-4">

        <div class="d-flex align-items-start gap-3">

            <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3 flex-shrink-0">
                <i class="bi bi-info-circle-fill fs-5"></i>
            </div>

            <div>
                <h6 class="fw-bold mb-1">
                    Report Generation
                </h6>

                <p class="text-muted small mb-0">
                    Reports can be generated using relevant filters and
                    exported for administrative review, stock verification,
                    auditing and operational analysis.
                </p>
            </div>

        </div>

    </div>
</div>


</div>

{{-- ================================================================
PAGE STYLES
================================================================ --}}
@push('styles')

<style>

    .report-category-card {
        border-radius: 14px;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .report-category-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }

    .report-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .report-category-card h5 {
        color: #212529;
    }

    .report-features {
        line-height: 1.5;
    }

    .report-features i {
        width: 18px;
        display: inline-block;
    }

    .report-category-card .btn {
        border-radius: 9px;
        font-weight: 600;
        padding: 0.65rem 1rem;
        transition:
            transform 0.15s ease,
            box-shadow 0.15s ease;
    }

    .report-category-card .btn:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 575.98px) {

        .report-category-card .card-body {
            padding: 1.25rem !important;
        }

        .report-category-card .btn {
            padding: 0.7rem 0.9rem;
        }

    }

</style>

@endpush

@endsection
