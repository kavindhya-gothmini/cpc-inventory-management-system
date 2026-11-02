
@extends('layouts.admin')

@section('title', 'Assign Inventory Item')

@section('content')
<div class="container-fluid py-4 assignment-page">

    {{-- Page header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="{{ route('admin.employees.show', $employee) }}"
               class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left"></i>
                Back to Employee Profile
            </a>

            <h2 class="fw-bold mb-1">
                <i class="bi bi-box-arrow-right text-primary me-2"></i>
                Assign Inventory Item
            </h2>

            <p class="text-muted mb-0">
                Assign an available inventory asset to
                <strong>{{ $employee->name }}</strong>.
            </p>
        </div>

        <a href="{{ route('admin.employees.show', $employee) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i>
            Cancel
        </a>
    </div>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Employee and assignment layout --}}
    <div class="row g-4">

        {{-- Employee summary --}}
        <div class="col-12 col-xl-4">
            <div class="card assignment-card h-100">
                <div class="card-header assignment-card-header">
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-person-badge text-primary me-2"></i>
                        Employee Details
                    </h5>
                    <p class="text-muted small mb-0">
                        Confirm the employee receiving the asset.
                    </p>
                </div>

                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="employee-avatar mx-auto">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <h5 class="fw-bold mt-3 mb-1">
                            {{ $employee->name }}
                        </h5>

                        <p class="text-muted mb-2">
                            {{ $employee->designation ?: 'Employee' }}
                        </p>

                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                            EPF: {{ $employee->epf_no ?: 'Not assigned' }}
                        </span>
                    </div>

                    <div class="employee-info-panel">
                        <div class="employee-info-row">
                            <span class="employee-info-label">
                                <i class="bi bi-building me-2"></i>Branch
                            </span>
                            <span class="employee-info-value">
                                {{ $employee->branch?->name ?? 'Not assigned' }}
                            </span>
                        </div>

                        <div class="employee-info-row">
                            <span class="employee-info-label">
                                <i class="bi bi-diagram-3 me-2"></i>Department
                            </span>
                            <span class="employee-info-value">
                                {{ $employee->department?->name ?? 'Not assigned' }}
                            </span>
                        </div>

                        <div class="employee-info-row">
                            <span class="employee-info-label">
                                <i class="bi bi-diagram-2 me-2"></i>Function Unit
                            </span>
                            <span class="employee-info-value">
                                {{ $employee->functionUnit?->name ?? 'Not assigned' }}
                            </span>
                        </div>
                    </div>

                    <div class="alert alert-primary bg-primary-subtle border-0 mt-4 mb-0">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-shield-check fs-5"></i>
                            <div class="small">
                                <strong>Branch restriction</strong>
                                <p class="mb-0 mt-1">
                                    Only inventory belonging to this employee's
                                    branch can be assigned.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Assignment form --}}
        <div class="col-12 col-xl-8">
            <div class="card assignment-card">

                <div class="card-header assignment-card-header">
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-clipboard-check text-primary me-2"></i>
                        Assignment Details
                    </h5>
                    <p class="text-muted small mb-0">
                        Filter available assets, select an item, and complete the assignment.
                    </p>
                </div>

                <div class="card-body p-3 p-md-4">

                    {{-- Inventory filters: GET request to the create page --}}
                    <section class="inventory-filter-panel mb-4"
                             aria-labelledby="inventoryFilterHeading">

                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                            <div>
                                <h6 id="inventoryFilterHeading" class="fw-bold mb-1">
                                    <i class="bi bi-funnel text-primary me-2"></i>
                                    Find Available Inventory
                                </h6>
                                <p class="text-muted small mb-0">
                                    Narrow down items by organizational location.
                                </p>
                            </div>

                            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>
                                Available only
                            </span>
                        </div>

                        <form method="GET"
                              action="{{ route('admin.employees.assign-item', $employee) }}">

                            <div class="row g-3">

                                {{-- Branch --}}
                                <div class="col-12 col-md-4">
                                    <label for="filter_branch" class="form-label fw-semibold">
                                        Branch
                                    </label>

                                    <select id="filter_branch"
                                            class="form-select"
                                            disabled>
                                        @if ($employee->branch)
                                            <option value="{{ $employee->branch_id }}" selected>
                                                {{ $employee->branch->name }}
                                            </option>
                                        @else
                                            <option selected>No branch assigned</option>
                                        @endif
                                    </select>

                                    <div class="form-text">
                                        Restricted to the employee's branch.
                                    </div>
                                </div>

                                {{-- Department --}}
                                <div class="col-12 col-md-4">
                                    <label for="filter_department" class="form-label fw-semibold">
                                        Department
                                    </label>

                                    <select name="department_id"
                                            id="filter_department"
                                            class="form-select">
                                        <option value="">All departments</option>

                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                @selected((string) request('department_id') === (string) $department->id)>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Function unit --}}
                                <div class="col-12 col-md-4">
                                    <label for="filter_function_unit"
                                           class="form-label fw-semibold">
                                        Function Unit
                                    </label>

                                    <select name="function_unit_id"
                                            id="filter_function_unit"
                                            class="form-select">
                                        <option value="">All function units</option>

                                        @foreach ($functionUnits as $functionUnit)
                                            <option value="{{ $functionUnit->id }}"
                                                @selected((string) request('function_unit_id') === (string) $functionUnit->id)>
                                                {{ $functionUnit->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="form-text">
                                        Optional.
                                    </div>
                                </div>

                                <div class="col-12 d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i>
                                        Filter Inventory
                                    </button>

                                    <a href="{{ route('admin.employees.assign-item', $employee) }}"
                                       class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                                        Clear Filters
                                    </a>
                                </div>
                            </div>
                        </form>
                    </section>

                    {{-- Assignment POST form --}}
                    <form method="POST"
                          action="{{ route('admin.employees.assign-item.store', $employee) }}"
                          id="assignmentForm">
                        @csrf

                        {{-- Select inventory item --}}
                        <div class="mb-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                <label for="item_id" class="form-label fw-semibold mb-0">
                                    Inventory Item <span class="text-danger">*</span>
                                </label>

                                <span class="small text-muted">
                                    {{ $items->count() }}
                                    {{ $items->count() === 1 ? 'item' : 'items' }} found
                                </span>
                            </div>

                            <select name="item_id"
                                    id="item_id"
                                    class="form-select form-select-lg @error('item_id') is-invalid @enderror"
                                    required
                                    {{ $items->isEmpty() ? 'disabled' : '' }}>

                                <option value="">
                                    Select an available inventory item
                                </option>

                                @foreach ($items as $item)
                                    @php
                                        $makeRelation = $item->relationLoaded('make')
                                            ? $item->getRelation('make')
                                            : null;

                                        $makeName = $makeRelation?->name
                                            ?? $item->make
                                            ?? '';

                                        $modelRelation = $item->relationLoaded('itemModel')
                                            ? $item->getRelation('itemModel')
                                            : null;

                                        $modelName = $modelRelation?->name
                                            ?? $item->model
                                            ?? '';

                                        $typeName = $item->itemType?->name ?? 'Inventory Item';
                                    @endphp

                                    <option value="{{ $item->id }}"
                                        @selected((string) old('item_id') === (string) $item->id)>

                                        {{ $item->asset_number ?: 'No asset number' }}
                                        — {{ $typeName }}

                                        @if ($makeName || $modelName)
                                            — {{ trim($makeName . ' ' . $modelName) }}
                                        @endif

                                        @if ($item->serial_number)
                                            — SN: {{ $item->serial_number }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($items->isEmpty())
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    No available inventory items match the selected filters.
                                    Try clearing the filters or registering available inventory
                                    for this branch.
                                </div>
                            @else
                                <div class="form-text">
                                    Only available items in the employee's branch are listed.
                                    Assigned, unavailable, and other-branch items are excluded.
                                </div>
                            @endif
                        </div>

                        {{-- Organizational assignment --}}
                        <div class="section-divider">
                            <h6 class="fw-bold mb-1">
                                <i class="bi bi-diagram-3 text-primary me-2"></i>
                                Organizational Assignment
                            </h6>
                            <p class="text-muted small mb-3">
                                These values are taken from the employee's profile.
                            </p>
                        </div>

                        <input type="hidden"
                               name="branch_id"
                               value="{{ $employee->branch_id }}">

                        <input type="hidden"
                               name="department_id"
                               value="{{ $employee->department_id }}">

                        <input type="hidden"
                               name="function_unit_id"
                               value="{{ $employee->function_unit_id }}">

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="organization-card h-100">
                                    <span class="organization-icon">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <div class="small text-muted mb-1">Branch</div>
                                    <div class="fw-semibold">
                                        {{ $employee->branch?->name ?? 'Not assigned' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="organization-card h-100">
                                    <span class="organization-icon">
                                        <i class="bi bi-diagram-3"></i>
                                    </span>
                                    <div class="small text-muted mb-1">Department</div>
                                    <div class="fw-semibold">
                                        {{ $employee->department?->name ?? 'Not assigned' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="organization-card h-100">
                                    <span class="organization-icon">
                                        <i class="bi bi-diagram-2"></i>
                                    </span>
                                    <div class="small text-muted mb-1">Function Unit</div>
                                    <div class="fw-semibold">
                                        {{ $employee->functionUnit?->name ?? 'Not assigned' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-light border rounded-3 small mb-4">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Organizational details are recorded from the employee's profile.
                            @if (!$employee->function_unit_id)
                                Function Unit is optional and is not currently assigned to this employee.
                            @endif
                        </div>

                        {{-- Assignment information --}}
                        <div class="section-divider">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-pc-display text-primary me-2"></i>
                                Assignment Information
                            </h6>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label for="workstation_id" class="form-label fw-semibold">
                                    Workstation ID
                                </label>

                                <input type="text"
                                       name="workstation_id"
                                       id="workstation_id"
                                       value="{{ old('workstation_id') }}"
                                       maxlength="255"
                                       class="form-control @error('workstation_id') is-invalid @enderror"
                                       placeholder="e.g. WKS-001">

                                @error('workstation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="assigned_date" class="form-label fw-semibold">
                                    Assigned Date <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="assigned_date"
                                       id="assigned_date"
                                       value="{{ old('assigned_date', now()->format('Y-m-d')) }}"
                                       class="form-control @error('assigned_date') is-invalid @enderror"
                                       required>

                                @error('assigned_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Remarks --}}
                        <div class="mb-4">
                            <label for="remarks" class="form-label fw-semibold">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      id="remarks"
                                      rows="4"
                                      maxlength="2000"
                                      class="form-control @error('remarks') is-invalid @enderror"
                                      placeholder="Enter any relevant assignment notes...">{{ old('remarks') }}</textarea>

                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirmation notice --}}
                        <div class="assignment-notice mb-4">
                            <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                            <div class="small">
                                <strong>Assignment confirmation</strong>
                                <p class="mb-0 mt-1">
                                    When submitted successfully, the inventory item will be
                                    assigned to <strong>{{ $employee->name }}</strong> and its
                                    status will change to <strong>In Use</strong>. The item
                                    can later be returned through the assignment history.
                                </p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="form-actions d-flex flex-wrap justify-content-end gap-2">
                            <a href="{{ route('admin.employees.show', $employee) }}"
                               class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i>
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary px-4"
                                    {{ $items->isEmpty() ? 'disabled' : '' }}>
                                <i class="bi bi-check2-circle me-1"></i>
                                Assign Inventory Item
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .assignment-page {
        --assignment-border: #e5eaf1;
        --assignment-muted-bg: #f7f9fc;
    }

    .assignment-page .assignment-card {
        border: 1px solid var(--assignment-border);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.045);
    }

    .assignment-page .assignment-card-header {
        padding: 1.35rem 1.5rem;
        background: #fff;
        border-bottom: 1px solid var(--assignment-border);
    }

    .assignment-page .employee-avatar {
        width: 78px;
        height: 78px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #eaf2ff;
        color: #0d6efd;
        font-size: 2.3rem;
    }

    .assignment-page .employee-info-panel {
        padding: 0.25rem 1rem;
        border: 1px solid var(--assignment-border);
        border-radius: 12px;
        background: var(--assignment-muted-bg);
    }

    .assignment-page .employee-info-row {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        padding: 0.9rem 0;
        border-bottom: 1px solid var(--assignment-border);
    }

    .assignment-page .employee-info-row:last-child {
        border-bottom: 0;
    }

    .assignment-page .employee-info-label {
        color: #64748b;
        font-size: 0.8rem;
    }

    .assignment-page .employee-info-value {
        color: #172033;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .assignment-page .inventory-filter-panel {
        padding: 1.25rem;
        border: 1px solid #dce7f8;
        border-radius: 14px;
        background: #f7faff;
    }

    .assignment-page .organization-card {
        padding: 1rem;
        border: 1px solid var(--assignment-border);
        border-radius: 12px;
        background: var(--assignment-muted-bg);
        overflow-wrap: anywhere;
    }

    .assignment-page .organization-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        margin-bottom: 0.75rem;
        border-radius: 10px;
        background: #eaf2ff;
        color: #0d6efd;
        font-size: 1.1rem;
    }

    .assignment-page .section-divider {
        padding-top: 1.25rem;
        margin-top: 0.5rem;
        border-top: 1px solid var(--assignment-border);
    }

    .assignment-page .assignment-notice {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        border: 1px solid #dce7f8;
        border-radius: 12px;
        background: #f7faff;
    }

    .assignment-page .form-control,
    .assignment-page .form-select {
        min-height: 44px;
        border-color: #d8dee8;
        border-radius: 9px;
    }

    .assignment-page textarea.form-control {
        min-height: 110px;
    }

    .assignment-page .form-control:focus,
    .assignment-page .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }

    .assignment-page .btn {
        min-height: 42px;
        border-radius: 9px;
        font-weight: 500;
    }

    @media (max-width: 575.98px) {
        .assignment-page {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }

        .assignment-page .assignment-card-header {
            padding: 1.1rem;
        }

        .assignment-page .inventory-filter-panel {
            padding: 1rem;
        }

        .assignment-page .form-actions {
            display: grid !important;
            grid-template-columns: 1fr;
        }

        .assignment-page .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endsection
