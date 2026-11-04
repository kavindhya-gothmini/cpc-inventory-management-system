@extends('layouts.admin')

@section('title', $location->Location_Name . ' | CPC Location Details')

@section('page-heading', 'CPC Location Details')

@section('content')

@php
    $isAvailable = (string) $location->Is_Available === '1';
@endphp

<style>
    .location-profile {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 30px;
    }

    .location-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
        color: #98a2b3;
        font-size: 11px;
    }

    .location-breadcrumb a {
        color: #667085;
        text-decoration: none;
    }

    .location-breadcrumb a:hover {
        color: #0d6efd;
    }

    .location-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 22px;
    }

    .location-title {
        margin: 0;
        color: #1f2937;
        font-size: 22px;
        font-weight: 700;
    }

    .location-subtitle {
        margin: 5px 0 0;
        color: #7d8798;
        font-size: 12px;
    }

    .location-card {
        overflow: hidden;
        margin-bottom: 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, .045);
    }

    .location-hero {
        position: relative;
        overflow: hidden;
        padding: 30px;
        background:
            radial-gradient(
                circle at 90% 15%,
                rgba(13, 110, 253, .15),
                transparent 30%
            ),
            linear-gradient(
                135deg,
                #eaf3ff,
                #f5f9ff 55%,
                #fff
            );
    }

    .location-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 17px;
        border-radius: 15px;
        background: #0d6efd;
        color: #fff;
        font-size: 25px;
    }

    .location-name {
        margin: 0 0 10px;
        color: #1f2937;
        font-size: 23px;
        font-weight: 700;
    }

    .location-id {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border: 1px solid #d8e5f7;
        border-radius: 7px;
        background: #fff;
        color: #475467;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
        font-weight: 600;
    }

    .location-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 16px;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .location-status.available {
        background: #e9f7ef;
        color: #218653;
    }

    .location-status.unavailable {
        background: #ffebeb;
        color: #c0392b;
    }

    .location-status .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .location-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .location-card-header h5 {
        margin: 0;
        color: #1f2937;
        font-size: 14px;
        font-weight: 700;
    }

    .location-card-body {
        padding: 22px;
    }

    .location-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .location-detail {
        padding: 17px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fafbfc;
    }

    .location-detail-label {
        margin-bottom: 8px;
        color: #8a94a6;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
    }

    .location-detail-value {
        color: #344054;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .location-source-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 14px 16px;
        border: 1px solid #dbe7f8;
        border-radius: 9px;
        background: #f5f9ff;
        color: #55708f;
        font-size: 11px;
        line-height: 1.6;
    }

    .location-source-note i {
        color: #2f80ed;
        font-size: 15px;
    }

    @media (max-width: 600px) {
        .location-detail-grid {
            grid-template-columns: 1fr;
        }

        .location-hero {
            padding: 22px;
        }

        .location-name {
            font-size: 19px;
        }
    }

    @media print {
        .location-header .btn,
        .location-breadcrumb {
            display: none !important;
        }

        .location-card {
            box-shadow: none;
        }
    }
</style>

<div class="location-profile">

    {{-- BREADCRUMB --}}

    <div class="location-breadcrumb">

        <a href="{{ route('admin.branches.index') }}">
            CPC Branch Directory
        </a>

        <i class="bi bi-chevron-right"></i>

        <span>
            Location Details
        </span>

    </div>


    {{-- PAGE HEADER --}}

    <div class="location-header">

        <div>
            <h1 class="location-title">
                CPC Location Profile
            </h1>

            <p class="location-subtitle">
                Official location information retrieved from the CPC master database.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.branches.index') }}"
                class="btn btn-light btn-sm"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Directory
            </a>

            <button
                type="button"
                class="btn btn-outline-secondary btn-sm"
                onclick="window.print()"
            >
                <i class="bi bi-printer me-1"></i>
                Print
            </button>

        </div>

    </div>


    {{-- LOCATION HERO --}}

    <div class="location-card">

        <div class="location-hero">

            <div class="location-icon">
                <i class="bi bi-building"></i>
            </div>

            <h2 class="location-name">
                {{ $location->Location_Name }}
            </h2>

            <div class="location-id">
                <i class="bi bi-hash"></i>
                CPC Location ID: {{ $location->Location_Id }}
            </div>

            <div>
                @if($isAvailable)

                    <span class="location-status available">
                        <span class="dot"></span>
                        Available in CPC
                    </span>

                @else

                    <span class="location-status unavailable">
                        <span class="dot"></span>
                        Unavailable in CPC
                    </span>

                @endif
            </div>

        </div>

    </div>


    {{-- LOCATION INFORMATION --}}

    <div class="location-card">

        <div class="location-card-header">
            <h5>
                <i class="bi bi-info-circle text-primary me-2"></i>
                CPC Location Information
            </h5>
        </div>

        <div class="location-card-body">

            <div class="location-detail-grid">

                <div class="location-detail">

                    <div class="location-detail-label">
                        Location ID
                    </div>

                    <div class="location-detail-value">
                        {{ $location->Location_Id }}
                    </div>

                </div>


                <div class="location-detail">

                    <div class="location-detail-label">
                        Location Name
                    </div>

                    <div class="location-detail-value">
                        {{ $location->Location_Name }}
                    </div>

                </div>


                <div class="location-detail">

                    <div class="location-detail-label">
                        Availability
                    </div>

                    <div class="location-detail-value">

                        @if($isAvailable)
                            <span class="text-success">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Available
                            </span>
                        @else
                            <span class="text-danger">
                                <i class="bi bi-x-circle-fill me-1"></i>
                                Unavailable
                            </span>
                        @endif

                    </div>

                </div>


                <div class="location-detail">

                    <div class="location-detail-label">
                        Data Source
                    </div>

                    <div class="location-detail-value">
                        CPC SQL Server
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- SOURCE INFORMATION --}}

    <div class="location-source-note">

        <i class="bi bi-database-check"></i>

        <div>
            <strong>Live CPC master data</strong><br>
            This profile is retrieved directly from
            <strong>CPC_HAPIS_JAN.TBL_HR_MAS_LOCATION</strong>.
            Location information is maintained in the CPC database.
            This page does not create or modify CPC location records.
        </div>

    </div>

</div>

@endsection