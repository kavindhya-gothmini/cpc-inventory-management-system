
@extends('layouts.admin')

@section('title', 'Report a Fault | CPC IT Inventory Management System')
@section('page-heading', 'Report a Fault')

@section('content')

<style>
    .fault-form-page {
        max-width: 1150px;
        margin: 0 auto;
        padding: 8px 0 30px;
        color: #172033;
    }

    .fault-form-header {
        margin-bottom: 22px;
    }

    .fault-eyebrow {
        margin-bottom: 7px;
        color: #0d6efd;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .9px;
        text-transform: uppercase;
    }

    .fault-form-title {
        margin: 0;
        font-size: 27px;
        font-weight: 750;
        color: #172033;
    }

    .fault-form-subtitle {
        margin: 8px 0 0;
        color: #7b8494;
        font-size: 13px;
        line-height: 1.6;
    }

    .fault-form-card {
        overflow: hidden;
        border: 1px solid #e8edf4;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    }

    .fault-form-card-header {
        padding: 21px 24px;
        border-bottom: 1px solid #edf0f5;
        background: #fbfcfe;
    }

    .fault-form-card-header h2 {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 700;
    }

    .fault-form-card-header p {
        margin: 6px 0 0;
        color: #7b8494;
        font-size: 12px;
    }

    .fault-form-body {
        padding: 25px;
    }

    .fault-section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0 0 18px;
        color: #172033;
        font-size: 14px;
        font-weight: 700;
    }

    .fault-section-title i {
        color: #0d6efd;
    }

    .fault-form-section + .fault-form-section {
        margin-top: 29px;
        padding-top: 25px;
        border-top: 1px solid #edf0f5;
    }

    .fault-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 19px 22px;
    }

    .fault-field-full {
        grid-column: 1 / -1;
    }

    .fault-label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .fault-required {
        color: #dc3545;
    }

    .fault-input,
    .fault-select,
    .fault-textarea {
        display: block;
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #d8dee8;
        border-radius: 7px;
        background: #fff;
        color: #172033;
        font-family: inherit;
        font-size: 13px;
        transition: border-color .2s, box-shadow .2s;
    }

    .fault-input:focus,
    .fault-select:focus,
    .fault-textarea:focus {
        outline: none;
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .12);
    }

    .fault-textarea {
        min-height: 145px;
        resize: vertical;
        line-height: 1.6;
    }

    .fault-help {
        margin-top: 6px;
        color: #8791a1;
        font-size: 11px;
        line-height: 1.5;
    }

    .fault-error {
        margin-top: 6px;
        color: #b42318;
        font-size: 12px;
    }

    .fault-input.is-invalid,
    .fault-select.is-invalid,
    .fault-textarea.is-invalid {
        border-color: #dc3545;
    }

    .fault-priority-note {
        padding: 13px 15px;
        border: 1px solid #dbeafe;
        border-radius: 8px;
        background: #eff6ff;
        color: #344054;
        font-size: 12px;
        line-height: 1.7;
    }

    .fault-form-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid #edf0f5;
    }

    .fault-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 41px;
        padding: 10px 17px;
        border: 1px solid transparent;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s;
    }

    .fault-btn-primary {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .fault-btn-primary:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        color: #fff;
    }

    .fault-btn-secondary {
        background: #fff;
        border-color: #d8dee8;
        color: #475467;
    }

    .fault-btn-secondary:hover {
        background: #f8fafc;
        color: #172033;
    }

    .fault-alert {
        margin-bottom: 18px;
        padding: 14px 16px;
        border: 1px solid #f3b8bd;
        border-radius: 8px;
        background: #fff1f2;
        color: #b42318;
        font-size: 13px;
    }

    @media (max-width: 700px) {
        .fault-form-grid {
            grid-template-columns: 1fr;
        }

        .fault-field-full {
            grid-column: auto;
        }

        .fault-form-body {
            padding: 18px;
        }

        .fault-form-title {
            font-size: 23px;
        }

        .fault-form-actions {
            flex-direction: column-reverse;
        }

        .fault-btn {
            width: 100%;
        }
    }
</style>

<div class="fault-form-page">

    <div class="fault-form-header">
        <div class="fault-eyebrow">
            <i class="fas fa-headset me-1"></i>
            User Dashboard
        </div>

        <h1 class="fault-form-title">Report an IT Fault</h1>

        <p class="fault-form-subtitle">
            Provide the equipment details and describe the problem so the IT team
            can review your request.
        </p>
    </div>

    @if ($errors->any())
        <div class="fault-alert" role="alert">
            <strong><i class="fas fa-exclamation-circle me-2"></i>Please check the form.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('fault-reports.store') }}"
        novalidate
    >
        @csrf

        <div class="fault-form-card">

            <div class="fault-form-card-header">
                <h2>Fault Report Details</h2>
                <p>Fields marked with <span class="fault-required">*</span> are required.</p>
            </div>

            <div class="fault-form-body">

                {{-- Equipment details --}}
                <section class="fault-form-section">
                    <h3 class="fault-section-title">
                        <i class="fas fa-laptop"></i>
                        Equipment Information
                    </h3>

                    <div class="fault-form-grid">

                        <div>
                            <label for="device_type" class="fault-label">
                                Equipment Type <span class="fault-required">*</span>
                            </label>

                            <select
                                id="device_type"
                                name="device_type"
                                class="fault-select @error('device_type') is-invalid @enderror"
                                required
                            >
                                <option value="">Select equipment type</option>
                                <option value="Laptop" @selected(old('device_type') === 'Laptop')>Laptop</option>
                                <option value="Desktop PC" @selected(old('device_type') === 'Desktop PC')>Desktop PC</option>
                                <option value="All-in-One PC" @selected(old('device_type') === 'All-in-One PC')>All-in-One PC</option>
                            </select>

                            @error('device_type')
                                <div class="fault-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="asset_number" class="fault-label">
                                Asset Number
                            </label>

                            <input
                                type="text"
                                id="asset_number"
                                name="asset_number"
                                value="{{ old('asset_number') }}"
                                class="fault-input @error('asset_number') is-invalid @enderror"
                                maxlength="100"
                                placeholder="Enter CPC asset number"
                            >

                            <div class="fault-help">
                                Enter the asset number shown on the CPC asset label, if available.
                            </div>

                            @error('asset_number')
                                <div class="fault-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="device_make" class="fault-label">
                                Make / Manufacturer
                            </label>

                            <input
                                type="text"
                                id="device_make"
                                name="device_make"
                                value="{{ old('device_make') }}"
                                class="fault-input @error('device_make') is-invalid @enderror"
                                maxlength="100"
                                placeholder="e.g. HP, Dell, Lenovo"
                            >

                            @error('device_make')
                                <div class="fault-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="device_model" class="fault-label">
                                Model
                            </label>

                            <input
                                type="text"
                                id="device_model"
                                name="device_model"
                                value="{{ old('device_model') }}"
                                class="fault-input @error('device_model') is-invalid @enderror"
                                maxlength="100"
                                placeholder="Enter equipment model"
                            >

                            @error('device_model')
                                <div class="fault-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </section>

                {{-- Fault details --}}
                <section class="fault-form-section">
                    <h3 class="fault-section-title">
                        <i class="fas fa-tools"></i>
                        Problem Description
                    </h3>

                    <div class="fault-form-grid">

                        <div class="fault-field-full">
                            <label for="title" class="fault-label">
                                Fault Title <span class="fault-required">*</span>
                            </label>

                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                class="fault-input @error('title') is-invalid @enderror"
                                maxlength="150"
                                required
                                placeholder="Briefly describe the problem"
                            >

                            @error('title')
                                <div class="fault-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="fault-field-full">
                            <label for="description" class="fault-label">
                                Detailed Description <span class="fault-required">*</span>
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                class="fault-textarea @error('description') is-invalid @enderror"
                                maxlength="10000"
                                required
                                placeholder="Describe the symptoms, any error messages, when the issue started, and what you have already tried."
                            >{{ old('description') }}</textarea>

                            <div class="fault-help">
                                Do not include passwords or other confidential information.
                            </div>

                            @error('description')
                                <div class="fault-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="priority" class="fault-label">
                                Priority <span class="fault-required">*</span>
                            </label>

                            <select
                                id="priority"
                                name="priority"
                                class="fault-select @error('priority') is-invalid @enderror"
                                required
                            >
                                <option value="low" @selected(old('priority', 'medium') === 'low')>Low</option>
                                <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                                <option value="high" @selected(old('priority', 'medium') === 'high')>High</option>
                                <option value="critical" @selected(old('priority', 'medium') === 'critical')>Critical</option>
                            </select>

                            @error('priority')
                                <div class="fault-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="fault-label">What does priority mean?</label>

                            <div class="fault-priority-note">
                                <strong>Low:</strong> Minor issue.<br>
                                <strong>Medium:</strong> Normal equipment problem.<br>
                                <strong>High:</strong> Work significantly affected.<br>
                                <strong>Critical:</strong> Urgent disruption requiring immediate attention.
                            </div>
                        </div>

                    </div>
                </section>

                <div class="fault-form-actions">
                    <a
                        href="{{ route('fault-reports.index') }}"
                        class="fault-btn fault-btn-secondary"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Cancel
                    </a>

                    <button type="submit" class="fault-btn fault-btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Submit Fault Report
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection
