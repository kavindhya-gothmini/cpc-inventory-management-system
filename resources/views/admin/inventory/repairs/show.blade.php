@extends('layouts.admin')

@section('title', 'Repair Details | CPC Inventory Management System')

@section('content')

<style>
/* =========================================================
   CPC REPAIR DETAILS
========================================================= */

.repair-page {
    min-height: calc(100vh - 72px);
    background:
        radial-gradient(circle at top right, rgba(13, 110, 253, 0.08), transparent 30%),
        #f4f7fb;
    padding: 30px;
}

.repair-container {
    max-width: 1500px;
    margin: 0 auto;
}

/* =========================================================
   HEADER
========================================================= */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 25px;
}

.page-title {
    margin: 0;
    font-size: 28px;
    font-weight: 800;
    color: #172033;
}

.page-subtitle {
    margin: 7px 0 0;
    color: #6b7280;
    font-size: 14px;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-cpc {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 10px 16px;
    border-radius: 9px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    border: 1px solid transparent;
    cursor: pointer;
    transition: 0.2s ease;
}

.btn-primary-cpc {
    background: #0d6efd;
    color: #fff;
}

.btn-primary-cpc:hover {
    background: #0b5ed7;
    color: #fff;
}

.btn-secondary-cpc {
    background: #fff;
    color: #374151;
    border-color: #dbe1ea;
}

.btn-secondary-cpc:hover {
    background: #f8fafc;
    color: #111827;
}

.btn-danger-cpc {
    background: #fff;
    color: #dc3545;
    border-color: #f1b0b7;
}

.btn-danger-cpc:hover {
    background: #dc3545;
    color: #fff;
}

.btn-success-cpc {
    background: #198754;
    color: #fff;
}

.btn-success-cpc:hover {
    background: #157347;
    color: #fff;
}

/* =========================================================
   ALERTS
========================================================= */

.alert-cpc {
    border-radius: 10px;
    padding: 13px 16px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success-cpc {
    background: #ecfdf3;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert-danger-cpc {
    background: #fff1f2;
    color: #991b1b;
    border: 1px solid #fecdd3;
}

/* =========================================================
   CARDS
========================================================= */

.card-cpc {
    background: #fff;
    border: 1px solid #e4e9f0;
    border-radius: 14px;
    box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
    margin-bottom: 22px;
    overflow: hidden;
}

.card-header-cpc {
    padding: 18px 22px;
    border-bottom: 1px solid #edf0f5;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.card-title-cpc {
    margin: 0;
    color: #172033;
    font-size: 17px;
    font-weight: 800;
}

.card-subtitle-cpc {
    margin: 4px 0 0;
    color: #7b8494;
    font-size: 12px;
}

.card-body-cpc {
    padding: 22px;
}

/* =========================================================
   GRID
========================================================= */

.info-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

.info-item {
    background: #f8fafc;
    border: 1px solid #edf1f6;
    border-radius: 10px;
    padding: 14px;
}

.info-label {
    color: #7b8494;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 6px;
}

.info-value {
    color: #172033;
    font-size: 14px;
    font-weight: 700;
    word-break: break-word;
}

.info-value-muted {
    color: #6b7280;
    font-weight: 500;
}

/* =========================================================
   STATUS
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.status-pending {
    background: #fff7ed;
    color: #c2410c;
}

.status-sent_for_repair {
    background: #eff6ff;
    color: #1d4ed8;
}

.status-under_repair {
    background: #fef3c7;
    color: #92400e;
}

.status-completed {
    background: #ecfdf5;
    color: #047857;
}

.status-returned {
    background: #dcfce7;
    color: #166534;
}

.status-cancelled {
    background: #f3f4f6;
    color: #4b5563;
}

/* =========================================================
   ITEM HEADER
========================================================= */

.asset-highlight {
    display: flex;
    align-items: center;
    gap: 15px;
}

.asset-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf3ff;
    color: #0d6efd;
    font-size: 22px;
    flex-shrink: 0;
}

.asset-number {
    margin: 0;
    font-size: 19px;
    font-weight: 800;
    color: #172033;
}

.asset-meta {
    margin-top: 4px;
    color: #6b7280;
    font-size: 13px;
}

/* =========================================================
   SECTION ICON
========================================================= */

.section-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eaf3ff;
    color: #0d6efd;
    font-size: 18px;
    flex-shrink: 0;
}

.section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
}

/* =========================================================
   TEXT
========================================================= */

.text-box {
    background: #f8fafc;
    border: 1px solid #edf1f6;
    border-radius: 10px;
    padding: 16px;
    color: #374151;
    line-height: 1.65;
    font-size: 14px;
    white-space: pre-line;
    min-height: 55px;
}

.problem-box {
    background: #fffaf0;
    border: 1px solid #f5dfae;
    border-left: 5px solid #f59e0b;
    border-radius: 10px;
    padding: 18px;
    color: #374151;
    line-height: 1.75;
    font-size: 14px;
    white-space: pre-line;
    min-height: 65px;
}

.problem-empty {
    color: #9ca3af;
    font-style: italic;
}

/* =========================================================
   FORM
========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-size: 12px;
    font-weight: 700;
}

.form-control-cpc,
.form-select-cpc {
    width: 100%;
    min-height: 42px;
    border: 1px solid #d9e0e8;
    border-radius: 8px;
    padding: 9px 12px;
    background: #fff;
    color: #172033;
    font-size: 13px;
    outline: none;
}

.form-control-cpc:focus,
.form-select-cpc:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
}

.form-help {
    margin-top: 5px;
    font-size: 11px;
    color: #7b8494;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

/* =========================================================
   COLLECTION
========================================================= */

.collection-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border-radius: 999px;
    background: #ecfdf5;
    color: #047857;
    font-size: 11px;
    font-weight: 800;
}

.collection-not-recorded {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border-radius: 999px;
    background: #fff7ed;
    color: #c2410c;
    font-size: 11px;
    font-weight: 800;
}

.collection-existing {
    border: 1px solid #dbe4f0;
    border-radius: 14px;
    background: #f8fbff;
    margin-bottom: 26px;
    overflow: hidden;
}

.collection-existing-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 18px 20px;
    border-bottom: 1px solid #dbe4f0;
    background: #ffffff;
}

.collection-existing-title {
    margin: 0 0 4px;
    font-size: 15px;
    font-weight: 800;
    color: #172033;
}

.collection-existing-subtitle {
    margin: 0;
    color: #7b8494;
    font-size: 12px;
}

.collection-record-id {
    background: #eaf3ff;
    color: #0d6efd;
    padding: 6px 10px;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.collection-details-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    padding: 20px;
}

.collection-detail {
    background: #fff;
    border: 1px solid #e3eaf2;
    border-radius: 10px;
    padding: 14px;
}

.collection-remarks {
    margin: 0 20px 20px;
}

/* =========================================================
   ACCESSORIES
========================================================= */

.accessory-add-box {
    background: #f8fbff;
    border: 1px solid #dce8f7;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 22px;
}

.accessory-add-title {
    margin: 0 0 5px;
    color: #172033;
    font-size: 15px;
    font-weight: 800;
}

.accessory-add-subtitle {
    margin: 0 0 18px;
    color: #7b8494;
    font-size: 12px;
}

.accessory-form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 12px;
    align-items: end;
}

.accessory-return-box {
    margin-top: 12px;
    padding: 15px;
    background: #fffaf0;
    border: 1px solid #f4dfae;
    border-radius: 10px;
}

.accessory-return-title {
    margin: 0 0 12px;
    color: #92400e;
    font-size: 12px;
    font-weight: 800;
}

.accessory-return-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr auto;
    gap: 12px;
    align-items: end;
}

.returned-accessory {
    background: #f4fff8;
}

.accessory-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.returned-badge,
.not-returned-badge {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
}

.returned-badge {
    background: #dcfce7;
    color: #166534;
}

.not-returned-badge {
    background: #fff7ed;
    color: #c2410c;
}

.accessory-name {
    font-weight: 700;
    color: #172033;
}

.quantity-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    padding: 5px 8px;
    border-radius: 7px;
    background: #eef4ff;
    color: #1d4ed8;
    font-weight: 800;
    font-size: 11px;
}

.inline-accessory-edit {
    display: none;
    background: #f8fafc;
}

.inline-accessory-edit td {
    padding: 16px;
}

.inline-accessory-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 12px;
    align-items: end;
}

/* =========================================================
   TABLE
========================================================= */

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.cpc-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px;
}

.cpc-table th {
    padding: 12px 14px;
    background: #f8fafc;
    color: #697386;
    border-bottom: 1px solid #e7ebf1;
    text-align: left;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
}

.cpc-table td {
    padding: 14px;
    border-bottom: 1px solid #edf0f4;
    color: #374151;
    font-size: 13px;
    vertical-align: middle;
}

.cpc-table tbody tr:hover {
    background: #fbfdff;
}

.action-group {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.btn-table {
    border: 1px solid #dbe1e8;
    background: #fff;
    border-radius: 7px;
    padding: 6px 9px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.btn-table-edit {
    color: #0d6efd;
}

.btn-table-delete {
    color: #dc3545;
}

/* =========================================================
   SPARE PARTS
========================================================= */

.spare-form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 18px;
}

.submit-row {
    display: flex;
    justify-content: flex-end;
}

.part-code {
    font-weight: 800;
    color: #0d6efd;
}

.part-name {
    font-weight: 700;
    color: #172033;
}

.price {
    font-weight: 700;
    color: #172033;
}

.inline-edit-form {
    display: none;
    background: #f8fafc;
}

.inline-edit-form td {
    padding: 16px;
}

.inline-edit-grid {
    display: grid;
    grid-template-columns: 1fr 2fr auto;
    gap: 12px;
    align-items: end;
}

/* =========================================================
   SUMMARY
========================================================= */

.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.summary-card {
    border-radius: 11px;
    padding: 18px;
    background: #f8fafc;
    border: 1px solid #edf1f6;
}

.summary-label {
    color: #7b8494;
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 800;
}

.summary-value {
    margin-top: 6px;
    color: #172033;
    font-size: 22px;
    font-weight: 800;
}

/* =========================================================
   EMPTY
========================================================= */

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #7b8494;
}

.empty-icon {
    font-size: 32px;
    margin-bottom: 10px;
}

.empty-title {
    color: #374151;
    font-size: 15px;
    font-weight: 700;
}

.empty-text {
    margin-top: 5px;
    font-size: 13px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .info-grid,
    .collection-details-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .accessory-form-grid,
    .accessory-return-grid {
        grid-template-columns: 1fr 1fr;
    }

    .summary-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {

    .repair-page {
        padding: 18px;
    }

    .page-header {
        flex-direction: column;
    }

    .header-actions {
        width: 100%;
    }

    .btn-cpc {
        flex: 1;
    }

    .info-grid,
    .collection-details-grid,
    .form-grid,
    .spare-form-grid,
    .accessory-form-grid,
    .accessory-return-grid,
    .inline-accessory-grid,
    .inline-edit-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: auto;
    }

    .collection-existing-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .accessory-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .accessory-actions form,
    .accessory-actions button {
        width: 100%;
    }

    .submit-row,
    .form-actions {
        justify-content: stretch;
    }

    .submit-row .btn-cpc,
    .form-actions .btn-cpc {
        width: 100%;
    }
}
</style>


<div class="repair-page">

<div class="repair-container">

{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>
        <h1 class="page-title">
            Repair Details
        </h1>

        <p class="page-subtitle">
            View repair information, repaired-item collection,
            accessories, inventory information and spare-part usage.
        </p>
    </div>

    <div class="header-actions">

        <a
            href="{{ route('admin.inventory.repairs.index') }}"
            class="btn-cpc btn-secondary-cpc"
        >
            ← Back to Repairs
        </a>

        <a
            href="{{ route('admin.inventory.repairs.edit', $repair) }}"
            class="btn-cpc btn-primary-cpc"
        >
            ✎ Edit Repair
        </a>

    </div>

</div>


{{-- =====================================================
     ALERTS
====================================================== --}}

@if(session('success'))

    <div class="alert-cpc alert-success-cpc">
        {{ session('success') }}
    </div>

@endif

@if(session('error'))

    <div class="alert-cpc alert-danger-cpc">
        {{ session('error') }}
    </div>

@endif

@if($errors->any())

    <div class="alert-cpc alert-danger-cpc">

        <strong>Please correct the following:</strong>

        <ul style="margin:8px 0 0 18px;">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- =====================================================
     INVENTORY ITEM
====================================================== --}}

<div class="card-cpc">

    <div class="card-header-cpc">

        <div class="asset-highlight">

            <div class="asset-icon">
                🖥
            </div>

            <div>

                <h2 class="asset-number">
                    {{ $repair->item?->asset_number ?? 'N/A' }}
                </h2>

                <div class="asset-meta">

                    {{ $repair->item?->itemType?->name ?? 'Inventory Item' }}

                    @if($repair->item?->make)
                        · {{ $repair->item->make }}
                    @endif

                    @if($repair->item?->model)
                        {{ $repair->item->model }}
                    @endif

                </div>

            </div>

        </div>

        <span class="status-badge status-{{ $repair->status }}">
            {{ ucwords(str_replace('_', ' ', $repair->status)) }}
        </span>

    </div>

    <div class="card-body-cpc">

        <div class="info-grid">

            <div class="info-item">
                <div class="info-label">Serial Number</div>
                <div class="info-value">
                    {{ $repair->item?->serial_number ?? '—' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">IP Address</div>
                <div class="info-value">
                    {{ $repair->item?->ip_address ?? '—' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Workstation ID</div>
                <div class="info-value">
                    {{ $repair->item?->workstation_id ?? '—' }}
                </div>
            </div>

            <div class="info-item">

                <div class="info-label">
                    Current Employee
                </div>

                <div class="info-value">

                    @if($repair->item?->currentEmployee)

                        {{ $repair->item->currentEmployee->name }}

                        @if($repair->item->currentEmployee->epf_no)

                            <span class="info-value-muted">
                                ({{ $repair->item->currentEmployee->epf_no }})
                            </span>

                        @endif

                    @else
                        —
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     REPAIR INFORMATION
====================================================== --}}

<div class="card-cpc">

    <div class="card-header-cpc">

        <div>

            <h2 class="card-title-cpc">
                Repair Information
            </h2>

            <p class="card-subtitle-cpc">
                Main repair record and tracking information.
            </p>

        </div>

    </div>

    <div class="card-body-cpc">

        <div class="info-grid">

            <div class="info-item">
                <div class="info-label">Repair Reference</div>
                <div class="info-value">
                    {{ $repair->repair_reference }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Date Sent</div>
                <div class="info-value">
                    {{ $repair->date_sent?->format('Y-m-d') ?? '—' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Date Returned</div>
                <div class="info-value">
                    {{ $repair->date_returned?->format('Y-m-d') ?? '—' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Responsible Employee</div>
                <div class="info-value">
                    {{ $repair->employee?->name ?? '—' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Carrier</div>
                <div class="info-value">
                    {{ $repair->carrier ?? '—' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Technician</div>
                <div class="info-value">
                    {{ $repair->technician ?? '—' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Repair Cost</div>
                <div class="info-value">
                    Rs. {{ number_format((float) $repair->repair_cost, 2) }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Created</div>
                <div class="info-value">
                    {{ $repair->created_at?->format('Y-m-d H:i') ?? '—' }}
                </div>
            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     PROBLEM DESCRIPTION
====================================================== --}}

<div class="card-cpc">

    <div class="card-header-cpc">

        <div class="section-heading">

            <div class="section-icon">
                ⚠
            </div>

            <div>

                <h2 class="card-title-cpc">
                    Problem Description
                </h2>

                <p class="card-subtitle-cpc">
                    Problem or fault reported for this inventory item.
                </p>

            </div>

        </div>

    </div>

    <div class="card-body-cpc">

        @if(
            $repair->problem_description !== null &&
            trim((string) $repair->problem_description) !== ''
        )

            <div class="problem-box">
                {{ $repair->problem_description }}
            </div>

        @else

            <div class="problem-box problem-empty">
                No problem description was provided for this repair.
            </div>

        @endif

    </div>

</div>


{{-- =====================================================
     REPAIRED ITEM COLLECTION
====================================================== --}}

<div class="card-cpc">

    <div class="card-header-cpc">

        <div class="section-heading">

            <div class="section-icon">
                🤝
            </div>

            <div>

                <h2 class="card-title-cpc">
                    Repaired Item Collection
                </h2>

                <p class="card-subtitle-cpc">
                    Record the person who collects the repaired inventory item.
                </p>

            </div>

        </div>

        @if($repair->collection)

            <span class="collection-status">
                ✓ Collection Recorded
            </span>

        @else

            <span class="collection-not-recorded">
                ● Not Collected
            </span>

        @endif

    </div>

    <div class="card-body-cpc">

        @if($repair->collection)

            <div class="collection-existing">

                <div class="collection-existing-header">

                    <div>

                        <h4 class="collection-existing-title">
                            Current Collection Record
                        </h4>

                        <p class="collection-existing-subtitle">
                            Person and date recorded when the repaired item
                            was collected.
                        </p>

                    </div>

                    <span class="collection-record-id">
                        Record #{{ $repair->collection->id }}
                    </span>

                </div>

                <div class="collection-details-grid">

                    <div class="collection-detail">

                        <div class="info-label">
                            Collector Name
                        </div>

                        <div class="info-value">
                            {{ $repair->collection->collector_name }}
                        </div>

                    </div>

                    <div class="collection-detail">

                        <div class="info-label">
                            EPF Number
                        </div>

                        <div class="info-value">
                            {{ $repair->collection->collector_epf_no ?? '—' }}
                        </div>

                    </div>

                    <div class="collection-detail">

                        <div class="info-label">
                            Contact Number
                        </div>

                        <div class="info-value">
                            {{ $repair->collection->collector_contact ?? '—' }}
                        </div>

                    </div>

                    <div class="collection-detail">

                        <div class="info-label">
                            Collection Date
                        </div>

                        <div class="info-value">
                            {{ $repair->collection->issued_date?->format('Y-m-d') ?? '—' }}
                        </div>

                    </div>

                </div>

                @if($repair->collection->remarks)

                    <div class="collection-remarks">

                        <div class="info-label">
                            Collection Remarks
                        </div>

                        <div class="text-box">
                            {{ $repair->collection->remarks }}
                        </div>

                    </div>

                @endif

            </div>

        @endif


        {{-- COLLECTION FORM --}}

        <div class="accessory-add-box">

            <h4 class="accessory-add-title">

                @if($repair->collection)
                    ✎ Update Collection Details
                @else
                    ＋ Record Repaired Item Collection
                @endif

            </h4>

            <p class="accessory-add-subtitle">
                Enter the details of the person collecting the repaired item.
            </p>

            <form
                method="POST"
                action="{{ route(
                    'admin.inventory.repairs.collection.store',
                    $repair
                ) }}"
            >

                @csrf

                <div class="form-grid">

                    <div class="form-group">

                        <label class="form-label" for="collector_name">
                            Collector Name
                            <span style="color:#dc3545;">*</span>
                        </label>

                        <input
                            type="text"
                            id="collector_name"
                            name="collector_name"
                            class="form-control-cpc"
                            value="{{ old(
                                'collector_name',
                                $repair->collection->collector_name ?? ''
                            ) }}"
                            maxlength="150"
                            required
                        >

                        @error('collector_name')
                            <div class="form-help" style="color:#dc3545;">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label class="form-label" for="collector_epf_no">
                            Collector EPF Number
                        </label>

                        <input
                            type="text"
                            id="collector_epf_no"
                            name="collector_epf_no"
                            class="form-control-cpc"
                            value="{{ old(
                                'collector_epf_no',
                                $repair->collection->collector_epf_no ?? ''
                            ) }}"
                            maxlength="50"
                        >

                        @error('collector_epf_no')
                            <div class="form-help" style="color:#dc3545;">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label class="form-label" for="collector_contact">
                            Contact Number
                        </label>

                        <input
                            type="text"
                            id="collector_contact"
                            name="collector_contact"
                            class="form-control-cpc"
                            value="{{ old(
                                'collector_contact',
                                $repair->collection->collector_contact ?? ''
                            ) }}"
                            maxlength="50"
                        >

                        @error('collector_contact')
                            <div class="form-help" style="color:#dc3545;">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label class="form-label" for="issued_date">
                            Collection Date
                            <span style="color:#dc3545;">*</span>
                        </label>

                        <input
                            type="date"
                            id="issued_date"
                            name="issued_date"
                            class="form-control-cpc"
                            value="{{ old(
                                'issued_date',
                                $repair->collection?->issued_date?->format('Y-m-d')
                                    ?? $repair->date_returned?->format('Y-m-d')
                                    ?? now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                        <div class="form-help">
                            This is stored in the existing
                            <strong>issued_date</strong> database column,
                            but is displayed here as the repaired-item
                            collection date.
                        </div>

                        @error('issued_date')
                            <div class="form-help" style="color:#dc3545;">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="form-group full-width">

                        <label class="form-label" for="collection_remarks">
                            Collection Remarks
                        </label>

                        <textarea
                            id="collection_remarks"
                            name="remarks"
                            class="form-control-cpc"
                            rows="4"
                            placeholder="Enter any remarks about the repaired item collection..."
                        >{{ old(
                            'remarks',
                            $repair->collection->remarks ?? ''
                        ) }}</textarea>

                        @error('remarks')
                            <div class="form-help" style="color:#dc3545;">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-cpc btn-primary-cpc"
                    >
                        @if($repair->collection)
                            ✎ Update Collection Details
                        @else
                            ✓ Save Collection Details
                        @endif
                    </button>

                </div>

            </form>

        </div>


        @if($repair->collection)

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                gap:20px;
                flex-wrap:wrap;
                margin-top:18px;
                padding:16px 18px;
                border:1px solid #f1d1d1;
                border-radius:11px;
                background:#fff8f8;
            ">

                <div>

                    <div style="
                        color:#842029;
                        font-size:13px;
                        font-weight:800;
                    ">
                        Remove Collection Record
                    </div>

                    <div style="
                        margin-top:3px;
                        color:#7b8494;
                        font-size:11px;
                    ">
                        This removes only the collection record.
                    </div>

                </div>

                <form
                    method="POST"
                    action="{{ route(
                        'admin.inventory.repairs.collection.destroy',
                        $repair
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to remove this collection record?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn-cpc btn-danger-cpc"
                    >
                        🗑 Remove Collection
                    </button>

                </form>

            </div>

        @endif

    </div>

</div>


{{-- =====================================================
     ACCESSORIES
====================================================== --}}

<div class="card-cpc">

    <div class="card-header-cpc">

        <div class="section-heading">

            <div class="section-icon">
                🎒
            </div>

            <div>

                <h2 class="card-title-cpc">
                    Accessories Handed Over for Repair
                </h2>

                <p class="card-subtitle-cpc">
                    Record accessories sent with the item and confirm
                    their return after the repair is completed.
                </p>

            </div>

        </div>

    </div>

    <div class="card-body-cpc">


        {{-- ADD ACCESSORY --}}

        <div class="accessory-add-box">

            <h4 class="accessory-add-title">
                ＋ Add Accessory
            </h4>

            <p class="accessory-add-subtitle">
                Add items such as laptop bags, chargers, mice, keyboards
                or other accessories handed over with this repair.
            </p>

            <form
                method="POST"
                action="{{ route(
                    'admin.inventory.repairs.accessories.store',
                    $repair
                ) }}"
            >

                @csrf

                <div class="accessory-form-grid">

                    <div>

                        <label class="form-label">
                            Accessory Name
                        </label>

                        <input
                            type="text"
                            name="accessory_name"
                            class="form-control-cpc"
                            list="accessory-options"
                            placeholder="e.g. Laptop Bag"
                            maxlength="150"
                            required
                        >

                    </div>

                    <div>

                        <label class="form-label">
                            Quantity
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            class="form-control-cpc"
                            min="1"
                            max="999"
                            value="1"
                            required
                        >

                    </div>

                    <div>

                        <label class="form-label">
                            Issued Date
                        </label>

                        <input
                            type="date"
                            name="issued_date"
                            class="form-control-cpc"
                            value="{{ old(
                                'issued_date',
                                $repair->date_sent?->format('Y-m-d')
                                    ?? now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>

                    <div>

                        <button
                            type="submit"
                            class="btn-cpc btn-primary-cpc"
                        >
                            + Add
                        </button>

                    </div>

                </div>

            </form>

        </div>


        <datalist id="accessory-options">

            <option value="Laptop Bag">
            <option value="Laptop Charger">
            <option value="Desktop Power Cable">
            <option value="Mouse">
            <option value="Keyboard">
            <option value="Monitor">
            <option value="HDMI Cable">
            <option value="DisplayPort Cable">
            <option value="VGA Cable">
            <option value="Docking Station">
            <option value="Headset">
            <option value="Webcam">
            <option value="Laptop Carrying Case">
            <option value="Power Adapter">
            <option value="Other">

        </datalist>


        {{-- ACCESSORY TABLE --}}

        @if($repair->accessories->count())

            <div class="table-responsive">

                <table class="cpc-table">

                    <thead>

                        <tr>
                            <th>Accessory</th>
                            <th>Qty</th>
                            <th>Issued Date</th>
                            <th>Return Status</th>
                            <th>Returned Date</th>
                            <th>Returned By</th>
                            <th>Return Remarks</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    @foreach($repair->accessories as $accessory)

                        <tr
                            class="{{ $accessory->returned ? 'returned-accessory' : '' }}"
                        >

                            <td>

                                <span class="accessory-name">
                                    {{ $accessory->accessory_name }}
                                </span>

                            </td>

                            <td>

                                <span class="quantity-badge">
                                    {{ $accessory->quantity }}
                                </span>

                            </td>

                            <td>
                                {{ $accessory->issued_date?->format('Y-m-d') ?? '—' }}
                            </td>

                            <td>

                                @if($accessory->returned)

                                    <span class="returned-badge">
                                        ✓ Returned
                                    </span>

                                @else

                                    <span class="not-returned-badge">
                                        ● Not Returned
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $accessory->returned_date?->format('Y-m-d') ?? '—' }}
                            </td>

                            <td>
                                {{ $accessory->returned_by ?? '—' }}
                            </td>

                            <td>
                                {{ $accessory->return_remarks ?? '—' }}
                            </td>

                            <td>

                                @if(!$accessory->returned)

                                    <div class="accessory-actions">

                                        <button
                                            type="button"
                                            class="btn-table btn-table-edit"
                                            onclick="toggleAccessoryEdit('{{ $accessory->id }}')"
                                        >
                                            Edit
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.inventory.repairs.accessories.destroy',
                                                [
                                                    'repair' => $repair->id,
                                                    'accessory' => $accessory->id
                                                ]
                                            ) }}"
                                            onsubmit="return confirm(
                                                'Remove this accessory from the repair record?'
                                            );"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn-table btn-table-delete"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                @else

                                    <span style="
                                        color:#6b7280;
                                        font-size:11px;
                                        font-weight:700;
                                    ">
                                        Return completed
                                    </span>

                                @endif

                            </td>

                        </tr>


                        {{-- EDIT PENDING ACCESSORY --}}

                        @if(!$accessory->returned)

                            <tr
                                id="accessory-edit-{{ $accessory->id }}"
                                class="inline-accessory-edit"
                            >

                                <td colspan="8">

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.inventory.repairs.accessories.update',
                                            [
                                                'repair' => $repair->id,
                                                'accessory' => $accessory->id
                                            ]
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PUT')

                                        <div class="inline-accessory-grid">

                                            <div>

                                                <label class="form-label">
                                                    Accessory Name
                                                </label>

                                                <input
                                                    type="text"
                                                    name="accessory_name"
                                                    class="form-control-cpc"
                                                    list="accessory-options"
                                                    value="{{ $accessory->accessory_name }}"
                                                    maxlength="150"
                                                    required
                                                >

                                            </div>

                                            <div>

                                                <label class="form-label">
                                                    Quantity
                                                </label>

                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    class="form-control-cpc"
                                                    min="1"
                                                    max="999"
                                                    value="{{ $accessory->quantity }}"
                                                    required
                                                >

                                            </div>

                                            <div>

                                                <label class="form-label">
                                                    Issued Date
                                                </label>

                                                <input
                                                    type="date"
                                                    name="issued_date"
                                                    class="form-control-cpc"
                                                    value="{{ $accessory->issued_date?->format('Y-m-d') }}"
                                                    required
                                                >

                                            </div>

                                            <div>

                                                <button
                                                    type="submit"
                                                    class="btn-cpc btn-primary-cpc"
                                                >
                                                    Save
                                                </button>

                                            </div>

                                        </div>

                                    </form>

                                </td>

                            </tr>


                            {{-- RETURN ACCESSORY --}}

                            <tr>

                                <td colspan="8">

                                    <div class="accessory-return-box">

                                        <div class="accessory-return-title">
                                            ↩ Record Accessory Return
                                        </div>

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.inventory.repairs.accessories.return',
                                                [
                                                    'repair' => $repair->id,
                                                    'accessory' => $accessory->id
                                                ]
                                            ) }}"
                                        >

                                            @csrf
                                            @method('PUT')

                                            <div class="accessory-return-grid">

                                                <div>

                                                    <label class="form-label">
                                                        Returned Date
                                                    </label>

                                                    <input
                                                        type="date"
                                                        name="returned_date"
                                                        class="form-control-cpc"
                                                        value="{{ now()->format('Y-m-d') }}"
                                                        required
                                                    >

                                                </div>

                                                <div>

                                                    <label class="form-label">
                                                        Returned By
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="returned_by"
                                                        class="form-control-cpc"
                                                        placeholder="Person confirming return"
                                                        maxlength="150"
                                                        required
                                                    >

                                                </div>

                                                <div>

                                                    <label class="form-label">
                                                        Return Remarks
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="return_remarks"
                                                        class="form-control-cpc"
                                                        placeholder="Optional return remarks"
                                                        maxlength="1000"
                                                    >

                                                </div>

                                                <div>

                                                    <button
                                                        type="submit"
                                                        class="btn-cpc btn-success-cpc"
                                                        onclick="return confirm(
                                                            'Confirm that this accessory has been returned?'
                                                        );"
                                                    >
                                                        ✓ Mark Returned
                                                    </button>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endif

                    @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    🎒
                </div>

                <div class="empty-title">
                    No Accessories Recorded
                </div>

                <div class="empty-text">
                    Add any laptop bags, chargers, mice, keyboards
                    or other accessories handed over with the repair.
                </div>

            </div>

        @endif

    </div>

</div>


{{-- =====================================================
     REMARKS
====================================================== --}}

@if($repair->remarks)

    <div class="card-cpc">

        <div class="card-header-cpc">

            <h2 class="card-title-cpc">
                Remarks
            </h2>

        </div>

        <div class="card-body-cpc">

            <div class="text-box">
                {{ $repair->remarks }}
            </div>

        </div>

    </div>

@endif


{{-- =====================================================
     ADD SPARE PART
====================================================== --}}

<div class="card-cpc">

    <div class="card-header-cpc">

        <div>

            <h2 class="card-title-cpc">
                Add Spare Part to Repair
            </h2>

            <p class="card-subtitle-cpc">
                Select a spare part used during this repair.
                Stock will be deducted automatically.
            </p>

        </div>

    </div>

    <div class="card-body-cpc">

        <form
            method="POST"
            action="{{ route(
                'admin.inventory.repairs.repair-spare-parts.store',
                $repair
            ) }}"
        >

            @csrf

            <div class="spare-form-grid">

                <div class="form-group">

                    <label
                        for="spare_part_id"
                        class="form-label"
                    >
                        Spare Part
                    </label>

                    <select
                        name="spare_part_id"
                        id="spare_part_id"
                        class="form-select-cpc"
                        required
                    >

                        <option value="">
                            Select Spare Part
                        </option>

                        @foreach($activeSpareParts as $sparePart)

                            <option
                                value="{{ $sparePart->id }}"
                                data-stock="{{ $sparePart->quantity }}"
                                data-price="{{ $sparePart->unit_price }}"
                            >
                                {{ $sparePart->part_code }}
                                —
                                {{ $sparePart->part_name }}
                                —
                                Stock: {{ $sparePart->quantity }}
                            </option>

                        @endforeach

                    </select>

                    <div class="form-help">
                        Only active spare parts are available.
                    </div>

                </div>


                <div class="form-group">

                    <label
                        for="quantity_used"
                        class="form-label"
                    >
                        Quantity Used
                    </label>

                    <input
                        type="number"
                        name="quantity_used"
                        id="quantity_used"
                        class="form-control-cpc"
                        min="1"
                        value="1"
                        required
                    >

                    <div
                        class="form-help"
                        id="stock-help"
                    >
                        Select a spare part to view available stock.
                    </div>

                </div>

            </div>


            <div class="form-group">

                <label
                    for="spare_part_remarks"
                    class="form-label"
                >
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    id="spare_part_remarks"
                    class="form-control-cpc"
                    placeholder="Optional remarks about this spare-part usage..."
                ></textarea>

            </div>


            <div class="submit-row">

                <button
                    type="submit"
                    class="btn-cpc btn-primary-cpc"
                >
                    + Add Spare Part
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =====================================================
     SPARE PARTS USED
====================================================== --}}

@php
    $repairSpareParts = $repair->repairSpareParts;
@endphp

<div class="card-cpc">

    <div class="card-header-cpc">

        <div>

            <h2 class="card-title-cpc">
                Spare Parts Used
            </h2>

            <p class="card-subtitle-cpc">
                Parts consumed during this repair.
            </p>

        </div>

    </div>

    <div class="card-body-cpc">

        @if($repairSpareParts->count())

            <div class="table-responsive">

                <table class="cpc-table">

                    <thead>

                        <tr>
                            <th>Part Code</th>
                            <th>Part Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($repairSpareParts as $usage)

                            <tr>

                                <td>
                                    <span class="part-code">
                                        {{ $usage->sparePart?->part_code ?? '—' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="part-name">
                                        {{ $usage->sparePart?->part_name ?? '—' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $usage->quantity_used }}
                                </td>

                                <td>
                                    <span class="price">
                                        Rs.
                                        {{ number_format(
                                            (float) $usage->unit_price,
                                            2
                                        ) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="price">
                                        Rs.
                                        {{ number_format(
                                            (float) $usage->total_price,
                                            2
                                        ) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $usage->remarks ?: '—' }}
                                </td>

                                <td>

                                    <div class="action-group">

                                        <button
                                            type="button"
                                            class="btn-table btn-table-edit"
                                            onclick="toggleEditForm('{{ $usage->id }}')"
                                        >
                                            Edit
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.inventory.repairs.repair-spare-parts.destroy',
                                                [
                                                    'repair' => $repair->id,
                                                    'repairSparePart' => $usage->id
                                                ]
                                            ) }}"
                                            onsubmit="return confirm(
                                                'Remove this spare part from the repair and return the quantity to stock?'
                                            );"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn-table btn-table-delete"
                                            >
                                                Remove
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            <tr
                                id="edit-row-{{ $usage->id }}"
                                class="inline-edit-form"
                            >

                                <td colspan="7">

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.inventory.repairs.repair-spare-parts.update',
                                            [
                                                'repair' => $repair->id,
                                                'repairSparePart' => $usage->id
                                            ]
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PUT')

                                        <div class="inline-edit-grid">

                                            <div>

                                                <label class="form-label">
                                                    Quantity Used
                                                </label>

                                                <input
                                                    type="number"
                                                    name="quantity_used"
                                                    class="form-control-cpc"
                                                    min="1"
                                                    value="{{ $usage->quantity_used }}"
                                                    required
                                                >

                                            </div>

                                            <div>

                                                <label class="form-label">
                                                    Remarks
                                                </label>

                                                <input
                                                    type="text"
                                                    name="remarks"
                                                    class="form-control-cpc"
                                                    value="{{ $usage->remarks }}"
                                                >

                                            </div>

                                            <div>

                                                <button
                                                    type="submit"
                                                    class="btn-cpc btn-primary-cpc"
                                                >
                                                    Save
                                                </button>

                                            </div>

                                        </div>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    🔧
                </div>

                <div class="empty-title">
                    No Spare Parts Used
                </div>

                <div class="empty-text">
                    No spare parts have been recorded for this repair yet.
                </div>

            </div>

        @endif

    </div>

</div>


{{-- =====================================================
     COST SUMMARY
====================================================== --}}

@php

    $sparePartsTotal = $repairSpareParts->sum(
        fn ($usage) => (float) $usage->total_price
    );

    $repairCost = (float) $repair->repair_cost;

    $grandTotal = $repairCost + $sparePartsTotal;

@endphp

<div class="card-cpc">

    <div class="card-header-cpc">

        <div>

            <h2 class="card-title-cpc">
                Cost Summary
            </h2>

            <p class="card-subtitle-cpc">
                Repair and spare-part costs.
            </p>

        </div>

    </div>

    <div class="card-body-cpc">

        <div class="summary-grid">

            <div class="summary-card">

                <div class="summary-label">
                    Repair Cost
                </div>

                <div class="summary-value">
                    Rs. {{ number_format($repairCost, 2) }}
                </div>

            </div>

            <div class="summary-card">

                <div class="summary-label">
                    Spare Parts
                </div>

                <div class="summary-value">
                    Rs. {{ number_format($sparePartsTotal, 2) }}
                </div>

            </div>

            <div class="summary-card">

                <div class="summary-label">
                    Total Repair Cost
                </div>

                <div class="summary-value">
                    Rs. {{ number_format($grandTotal, 2) }}
                </div>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     INVENTORY LOCATION
====================================================== --}}

<div class="card-cpc">

    <div class="card-header-cpc">

        <h2 class="card-title-cpc">
            Inventory Location
        </h2>

    </div>

    <div class="card-body-cpc">

        <div class="info-grid">

            <div class="info-item">

                <div class="info-label">
                    Branch
                </div>

                <div class="info-value">
                    {{ $repair->item?->branch?->name ?? '—' }}
                </div>

            </div>

            <div class="info-item">

                <div class="info-label">
                    Department
                </div>

                <div class="info-value">
                    {{ $repair->item?->department?->name ?? '—' }}
                </div>

            </div>

            <div class="info-item">

                <div class="info-label">
                    Function Unit
                </div>

                <div class="info-value">
                    {{ $repair->item?->functionUnit?->name ?? '—' }}
                </div>

            </div>

            <div class="info-item">

                <div class="info-label">
                    Inventory Status
                </div>

                <div class="info-value">

                    {{ ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $repair->item?->status ?? '—'
                        )
                    ) }}

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     DELETE REPAIR
====================================================== --}}

<div class="card-cpc">

    <div class="card-body-cpc">

        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:15px;
            flex-wrap:wrap;
        ">

            <div>

                <div style="
                    font-size:14px;
                    font-weight:800;
                    color:#172033;
                ">
                    Delete Repair Record
                </div>

                <div style="
                    margin-top:4px;
                    color:#7b8494;
                    font-size:12px;
                ">
                    Deleting the repair record will also remove its
                    collection, accessory and spare-part records.
                </div>

            </div>

            <form
                method="POST"
                action="{{ route(
                    'admin.inventory.repairs.destroy',
                    $repair
                ) }}"
                onsubmit="return confirm(
                    'Are you sure you want to delete this repair record?'
                );"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn-cpc btn-danger-cpc"
                >
                    Delete Repair
                </button>

            </form>

        </div>

    </div>

</div>

</div>

</div>


<script>

/* =========================================================
   ACCESSORY INLINE EDIT
========================================================= */

function toggleAccessoryEdit(id)
{
    const row = document.getElementById(
        'accessory-edit-' + id
    );

    if (!row) {
        return;
    }

    row.style.display =
        row.style.display === 'table-row'
            ? 'none'
            : 'table-row';
}


/* =========================================================
   SPARE PART INLINE EDIT
========================================================= */

function toggleEditForm(id)
{
    const row = document.getElementById(
        'edit-row-' + id
    );

    if (!row) {
        return;
    }

    row.style.display =
        row.style.display === 'table-row'
            ? 'none'
            : 'table-row';
}


/* =========================================================
   SPARE PART STOCK
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const sparePartSelect =
            document.getElementById('spare_part_id');

        const quantityInput =
            document.getElementById('quantity_used');

        const stockHelp =
            document.getElementById('stock-help');

        if (
            !sparePartSelect ||
            !quantityInput ||
            !stockHelp
        ) {
            return;
        }

        sparePartSelect.addEventListener(
            'change',
            function () {

                const selectedOption =
                    this.options[this.selectedIndex];

                if (
                    !selectedOption ||
                    !selectedOption.value
                ) {

                    stockHelp.textContent =
                        'Select a spare part to view available stock.';

                    quantityInput.removeAttribute('max');

                    quantityInput.disabled = false;

                    quantityInput.value = 1;

                    return;
                }

                const stock =
                    parseInt(
                        selectedOption.dataset.stock || '0',
                        10
                    );

                const price =
                    parseFloat(
                        selectedOption.dataset.price || '0'
                    );

                quantityInput.max = stock;

                if (stock <= 0) {

                    stockHelp.textContent =
                        'OUT OF STOCK — this spare part cannot be used.';

                    quantityInput.value = 0;

                    quantityInput.disabled = true;

                } else {

                    quantityInput.disabled = false;

                    let currentQuantity =
                        parseInt(
                            quantityInput.value || '1',
                            10
                        );

                    if (currentQuantity < 1) {
                        currentQuantity = 1;
                    }

                    if (currentQuantity > stock) {
                        currentQuantity = stock;
                    }

                    quantityInput.value =
                        currentQuantity;

                    stockHelp.textContent =
                        'Available stock: ' +
                        stock +
                        ' | Unit price: Rs. ' +
                        price.toFixed(2);
                }

            }
        );

    }
);

</script>

@endsection