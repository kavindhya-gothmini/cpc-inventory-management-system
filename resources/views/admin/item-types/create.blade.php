@extends('layouts.admin')

@section('title', 'Add Item Type | CPC Inventory Management System')

@section('content')

<style>
    /* =========================================================
       CPC INVENTORY - ADD ITEM TYPE
       Professional Responsive Form UI
    ========================================================= */

    .item-type-create-page {
        width: 100%;
        max-width: 100%;
        padding-bottom: 2rem;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .it-create-header {
        background: linear-gradient(135deg, #ffffff 0%, #f6f9ff 100%);
        border: 1px solid #e7ebf3;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 18px rgba(30, 55, 90, 0.05);
    }

    .it-create-header-main {
        min-width: 0;
    }

    .it-create-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 14px;
        background: rgba(13, 110, 253, 0.10);
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
    }

    .it-create-title {
        font-size: 25px;
        font-weight: 700;
        color: #172033;
        margin: 0;
        line-height: 1.25;
    }

    .it-create-subtitle {
        color: #788397;
        font-size: 14px;
        margin: 5px 0 0;
        line-height: 1.5;
    }

    /* =========================================================
       BACK BUTTON
    ========================================================= */

    .it-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 10px;
        padding: 9px 15px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .it-back-btn:hover {
        transform: translateY(-1px);
    }

    /* =========================================================
       FORM CARD
    ========================================================= */

    .it-form-card {
        background: #ffffff;
        border: 1px solid #e7ebf3;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(30, 55, 90, 0.055);
    }

    .it-form-card-header {
        padding: 22px 26px;
        border-bottom: 1px solid #edf0f5;
        background: #ffffff;
    }

    .it-form-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 17px;
        font-weight: 700;
        color: #172033;
        margin: 0;
    }

    .it-form-card-title-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #edf5ff;
        color: #0d6efd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .it-form-card-description {
        margin: 5px 0 0 44px;
        color: #8a94a6;
        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================================================
       FORM BODY
    ========================================================= */

    .it-form-body {
        padding: 28px 26px;
    }

    .it-form-section {
        margin-bottom: 28px;
    }

    .it-form-section:last-child {
        margin-bottom: 0;
    }

    .it-section-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
        padding-bottom: 11px;
        border-bottom: 1px solid #edf0f5;
    }

    .it-section-heading i {
        color: #0d6efd;
        font-size: 15px;
    }

    .it-section-heading span {
        color: #374151;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* =========================================================
       FORM GROUP
    ========================================================= */

    .it-form-group {
        margin-bottom: 20px;
    }

    .it-form-group:last-child {
        margin-bottom: 0;
    }

    .it-form-label {
        display: block;
        margin-bottom: 7px;
        color: #374151;
        font-size: 13px;
        font-weight: 650;
    }

    .it-required {
        color: #dc3545;
        margin-left: 2px;
    }

    .it-form-control {
        width: 100%;
        min-height: 43px;
        padding: 10px 13px;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        background: #f8f9fb;
        color: #273142;
        font-size: 13px;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .it-form-control::placeholder {
        color: #a0a8b5;
    }

    .it-form-control:hover {
        border-color: #cbd2dc;
        background: #ffffff;
    }

    .it-form-control:focus {
        outline: none;
        border-color: #86b7fe;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
    }

    .it-form-control.is-invalid {
        border-color: #dc3545;
    }

    .it-form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.08);
    }

    textarea.it-form-control {
        min-height: 125px;
        resize: vertical;
        line-height: 1.55;
    }

    .it-help-text {
        margin-top: 6px;
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.5;
    }

    .it-error {
        margin-top: 6px;
        color: #dc3545;
        font-size: 11px;
        font-weight: 500;
    }

    /* =========================================================
       STATUS TOGGLE
    ========================================================= */

    .it-status-box {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 15px 16px;
        border: 1px solid #e2e7ef;
        border-radius: 11px;
        background: #f8f9fb;
        transition: all 0.2s ease;
    }

    .it-status-box:hover {
        border-color: #cbd5e1;
        background: #ffffff;
    }

    .it-status-checkbox {
        width: 18px !important;
        height: 18px;
        min-width: 18px;
        margin: 0;
        cursor: pointer;
        accent-color: #0d6efd;
    }

    .it-status-content {
        min-width: 0;
    }

    .it-status-label {
        display: block;
        color: #273142;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        margin-bottom: 2px;
    }

    .it-status-description {
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.45;
    }

    /* =========================================================
       ERROR ALERT
    ========================================================= */

    .it-error-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 15px 17px;
        margin-bottom: 24px;
        border: 1px solid #f1b0b7;
        border-radius: 12px;
        background: #fff5f5;
        color: #842029;
    }

    .it-error-alert > i {
        margin-top: 2px;
        font-size: 16px;
        flex-shrink: 0;
    }

    .it-error-alert-content {
        min-width: 0;
    }

    .it-error-alert-title {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .it-error-alert ul {
        margin: 0;
        padding-left: 18px;
        font-size: 12px;
        line-height: 1.6;
    }

    /* =========================================================
       FORM FOOTER
    ========================================================= */

    .it-form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 20px 26px;
        border-top: 1px solid #edf0f5;
        background: #fafbfc;
    }

    .it-required-note {
        color: #8a94a6;
        font-size: 11px;
    }

    .it-required-note span {
        color: #dc3545;
    }

    .it-form-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .it-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 17px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .it-btn-primary {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }

    .it-btn-primary:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(13, 110, 253, 0.22);
    }

    .it-btn-secondary {
        background: #ffffff;
        border-color: #dfe4ec;
        color: #596579;
    }

    .it-btn-secondary:hover {
        background: #f6f8fb;
        border-color: #cbd2dc;
        color: #374151;
        transform: translateY(-1px);
    }

    /* =========================================================
       RESPONSIVE - TABLET
    ========================================================= */

    @media (max-width: 991px) {

        .it-create-header {
            padding: 22px;
        }

        .it-create-title {
            font-size: 23px;
        }

        .it-form-card-header {
            padding: 20px 22px;
        }

        .it-form-body {
            padding: 24px 22px;
        }

        .it-form-footer {
            padding: 18px 22px;
        }
    }

    /* =========================================================
       RESPONSIVE - MOBILE
    ========================================================= */

    @media (max-width: 767px) {

        .item-type-create-page {
            padding-bottom: 1rem;
        }

        .it-create-header {
            padding: 18px;
            border-radius: 14px;
            margin-bottom: 18px;
        }

        .it-create-header > div {
            align-items: flex-start !important;
        }

        .it-create-header-main {
            width: 100%;
        }

        .it-create-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 11px;
            font-size: 19px;
        }

        .it-create-title {
            font-size: 20px;
        }

        .it-create-subtitle {
            font-size: 12px;
            margin-top: 4px;
        }

        .it-create-header .it-back-btn {
            width: 100%;
            margin-top: 2px;
        }

        .it-form-card {
            border-radius: 14px;
        }

        .it-form-card-header {
            padding: 18px;
        }

        .it-form-card-title {
            font-size: 16px;
        }

        .it-form-card-description {
            margin-left: 0;
            margin-top: 7px;
        }

        .it-form-body {
            padding: 20px 18px;
        }

        .it-form-section {
            margin-bottom: 24px;
        }

        .it-section-heading {
            margin-bottom: 15px;
        }

        .it-form-group {
            margin-bottom: 18px;
        }

        .it-form-footer {
            flex-direction: column;
            align-items: stretch;
            padding: 17px 18px;
        }

        .it-required-note {
            text-align: left;
        }

        .it-form-actions {
            width: 100%;
        }

        .it-form-actions .it-btn {
            flex: 1;
        }
    }

    /* =========================================================
       RESPONSIVE - SMALL MOBILE
    ========================================================= */

    @media (max-width: 575px) {

        .it-create-header {
            padding: 16px;
        }

        .it-create-title {
            font-size: 18px;
        }

        .it-create-subtitle {
            font-size: 11px;
        }

        .it-form-card-header {
            padding: 16px;
        }

        .it-form-body {
            padding: 18px 16px;
        }

        .it-form-control {
            min-height: 42px;
            font-size: 13px;
        }

        textarea.it-form-control {
            min-height: 110px;
        }

        .it-form-footer {
            padding: 15px 16px;
        }

        .it-form-actions {
            flex-direction: column-reverse;
        }

        .it-form-actions .it-btn {
            width: 100%;
            flex: none;
        }

        .it-error-alert {
            padding: 13px 14px;
        }
    }
</style>

<div class="item-type-create-page">


{{-- =========================================================
     PAGE HEADER
========================================================== --}}
<div class="it-create-header">

    <div class="d-flex justify-content-between align-items-center
                flex-wrap gap-3">

        <div class="d-flex align-items-center gap-3 it-create-header-main">

            <div class="it-create-icon">
                <i class="bi bi-tags-fill"></i>
            </div>

            <div class="min-w-0">

                <h1 class="it-create-title">
                    Add Item Type
                </h1>

                <p class="it-create-subtitle">
                    Create a new IT asset type for the CPC Inventory
                    Management System.
                </p>

            </div>

        </div>

        <a
            href="{{ route('admin.item-types.index') }}"
            class="btn btn-outline-secondary it-back-btn"
        >

            <i class="bi bi-arrow-left"></i>

            Back to Item Types

        </a>

    </div>

</div>


{{-- =========================================================
     VALIDATION ERRORS
========================================================== --}}
@if($errors->any())

    <div class="it-error-alert">

        <i class="bi bi-exclamation-triangle-fill"></i>

        <div class="it-error-alert-content">

            <div class="it-error-alert-title">
                Please correct the following errors:
            </div>

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    </div>

@endif


{{-- =========================================================
     FORM CARD
========================================================== --}}
<div class="it-form-card">

    {{-- FORM HEADER --}}
    <div class="it-form-card-header">

        <h5 class="it-form-card-title">

            <span class="it-form-card-title-icon">
                <i class="bi bi-plus-lg"></i>
            </span>

            Item Type Information

        </h5>

        <p class="it-form-card-description">
            Enter the classification details that will be used
            when registering CPC IT inventory assets.
        </p>

    </div>


    {{-- FORM BODY --}}
    <form
        method="POST"
        action="{{ route('admin.item-types.store') }}"
    >

        @csrf

        <div class="it-form-body">

            {{-- =================================================
                 BASIC INFORMATION
            ================================================== --}}
            <div class="it-form-section">

                <div class="it-section-heading">

                    <i class="bi bi-info-circle-fill"></i>

                    <span>
                        Basic Information
                    </span>

                </div>


                <div class="row g-3">

                    {{-- NAME --}}
                    <div class="col-12">

                        <div class="it-form-group">

                            <label
                                for="name"
                                class="it-form-label"
                            >

                                Item Type Name

                                <span class="it-required">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                class="it-form-control @error('name') is-invalid @enderror"
                                placeholder="Example: CPU"
                                maxlength="255"
                                required
                                autofocus
                            >

                            <div class="it-help-text">
                                Enter the main inventory asset type name,
                                such as CPU, Monitor, Printer, Router, or Laptop.
                            </div>

                            @error('name')

                                <div class="it-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- CATEGORY --}}
                    <div class="col-md-6">

                        <div class="it-form-group">

                            <label
                                for="category"
                                class="it-form-label"
                            >

                                Category

                            </label>

                            <input
                                type="text"
                                id="category"
                                name="category"
                                value="{{ old('category') }}"
                                class="it-form-control @error('category') is-invalid @enderror"
                                placeholder="Example: Computer Equipment"
                                maxlength="255"
                            >

                            <div class="it-help-text">
                                Optional grouping for better inventory
                                classification.
                            </div>

                            @error('category')

                                <div class="it-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- DESCRIPTION --}}
                    <div class="col-md-6">

                        <div class="it-form-group">

                            <label
                                for="description"
                                class="it-form-label"
                            >

                                Description

                            </label>

                            <textarea
                                id="description"
                                name="description"
                                class="it-form-control @error('description') is-invalid @enderror"
                                placeholder="Enter a brief description of this item type..."
                                maxlength="1000"
                            >{{ old('description') }}</textarea>

                            <div class="it-help-text">
                                Provide additional information about
                                the asset classification.
                            </div>

                            @error('description')

                                <div class="it-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 STATUS
            ================================================== --}}
            <div class="it-form-section">

                <div class="it-section-heading">

                    <i class="bi bi-toggle-on"></i>

                    <span>
                        Item Type Status
                    </span>

                </div>


                <div class="it-status-box">

                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        class="it-status-checkbox"
                        {{ old('is_active', true) ? 'checked' : '' }}
                    >

                    <div class="it-status-content">

                        <label
                            for="is_active"
                            class="it-status-label"
                        >
                            Active
                        </label>

                        <div class="it-status-description">
                            Active item types can be selected when
                            registering new CPC inventory items.
                        </div>

                    </div>

                </div>

                @error('is_active')

                    <div class="it-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- =====================================================
             FORM FOOTER
        ====================================================== --}}
        <div class="it-form-footer">

            <div class="it-required-note">

                <span>*</span>
                Required field

            </div>


            <div class="it-form-actions">

                <a
                    href="{{ route('admin.item-types.index') }}"
                    class="it-btn it-btn-secondary"
                >

                    <i class="bi bi-x-lg"></i>

                    Cancel

                </a>


                <button
                    type="submit"
                    class="it-btn it-btn-primary"
                >

                    <i class="bi bi-check-lg"></i>

                    Save Item Type

                </button>

            </div>

        </div>

    </form>

</div>


</div>

@endsection
