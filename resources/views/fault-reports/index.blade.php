@extends('layouts.admin')

@section('title', 'My Fault Reports | CPC IT Inventory Management System')
@section('page-heading', 'My Fault Reports')

@section('content')

<style>
    .fault-page {
        max-width: 1600px;
        margin: 0 auto;
        padding: 8px 0 30px;
        color: #172033;
    }

    .fault-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .fault-eyebrow {
        margin-bottom: 7px;
        color: #0d6efd;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .9px;
        text-transform: uppercase;
    }

    .fault-title {
        margin: 0;
        color: #172033;
        font-size: 27px;
        font-weight: 750;
    }

    .fault-subtitle {
        margin: 8px 0 0;
        color: #7b8494;
        font-size: 13px;
    }

    .fault-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 17px;
        border: 1px solid #0d6efd;
        border-radius: 8px;
        background: #0d6efd;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s ease;
    }

    .fault-btn-primary:hover {
        background: #0b5ed7;
        color: #fff;
    }

    .fault-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .fault-summary-card {
        padding: 19px;
        border: 1px solid #e8edf4;
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    }

    .fault-summary-label {
        margin-bottom: 9px;
        color: #7b8494;
        font-size: 12px;
        font-weight: 600;
    }

    .fault-summary-number {
        color: #172033;
        font-size: 27px;
        font-weight: 750;
        line-height: 1.2;
    }

    .fault-summary-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        margin-bottom: 13px;
        border-radius: 9px;
        background: #eaf2ff;
        color: #0d6efd;
        font-size: 16px;
    }

    .fault-panel {
        overflow: hidden;
        border: 1px solid #e8edf4;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    }

    .fault-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf0f5;
    }

    .fault-panel-title {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 700;
    }

    .fault-panel-description {
        margin: 5px 0 0;
        color: #8791a1;
        font-size: 12px;
    }

    .fault-table-wrapper {
        overflow-x: auto;
    }

    .fault-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        white-space: nowrap;
    }

    .fault-table th {
        padding: 13px 18px;
        background: #f8fafc;
        color: #667085;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .5px;
        text-align: left;
        text-transform: uppercase;
        border-bottom: 1px solid #edf0f5;
    }

    .fault-table td {
        padding: 16px 18px;
        color: #475467;
        font-size: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f6;
    }

    .fault-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .fault-table tbody tr:hover {
        background: #fbfdff;
    }

    .fault-report-title {
        color: #172033;
        font-size: 13px;
        font-weight: 700;
    }

    .fault-report-id {
        margin-top: 5px;
        color: #98a2b3;
        font-size: 11px;
    }

    .fault-device {
        color: #344054;
        font-weight: 600;
    }

    .fault-muted {
        margin-top: 4px;
        color: #98a2b3;
        font-size: 11px;
    }

    .fault-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .fault-status-submitted {
        background: #eaf2ff;
        color: #175cd3;
    }

    .fault-status-under_review {
        background: #fff4db;
        color: #935f00;
    }

    .fault-status-in_progress {
        background: #e8f5ff;
        color: #026aa7;
    }

    .fault-status-waiting_for_user {
        background: #f4ebff;
        color: #6941c6;
    }

    .fault-status-resolved {
        background: #e7f8ee;
        color: #067647;
    }

    .fault-status-closed {
        background: #f0f1f3;
        color: #475467;
    }

    .fault-priority-low {
        background: #f0f1f3;
        color: #475467;
    }

    .fault-priority-medium {
        background: #eaf2ff;
        color: #175cd3;
    }

    .fault-priority-high {
        background: #fff0db;
        color: #b54708;
    }

    .fault-priority-critical {
        background: #fee4e2;
        color: #b42318;
    }

    .fault-view-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #0d6efd;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
    }

    .fault-view-link:hover {
        color: #084298;
        text-decoration: underline;
    }

    .fault-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .fault-empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 62px;
        height: 62px;
        margin-bottom: 17px;
        border-radius: 50%;
        background: #edf4ff;
        color: #0d6efd;
        font-size: 24px;
    }

    .fault-empty h3 {
        margin-bottom: 8px;
        color: #172033;
        font-size: 17px;
        font-weight: 700;
    }

    .fault-empty p {
        margin: 0 auto 20px;
        max-width: 400px;
        color: #7b8494;
        font-size: 13px;
        line-height: 1.7;
    }

    .fault-pagination {
        padding: 16px 20px;
        border-top: 1px solid #edf0f5;
    }

    .fault-alert {
        padding: 13px 16px;
        margin-bottom: 18px;
        border: 1px solid #b7ebc6;
        border-radius: 8px;
        background: #edfff2;
        color: #067647;
        font-size: 13px;
    }

    @media (max-width: 767px) {
        .fault-summary {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .fault-title {
            font-size: 23px;
        }

        .fault-header {
            align-items: flex-start;
        }

        .fault-btn-primary {
            width: 100%;
        }

        .fault-panel-header {
            padding: 16px;
        }
    }
</style>

<div class="fault-page">

    {{-- Page heading --}}
    <div class="fault-header">
        <div>
            <div class="fault-eyebrow">
                <i class="fas fa-headset me-1"></i>
                User Dashboard
            </div>

            <h1 class="fault-title">My Fault Reports</h1>

            <p class="fault-subtitle">
                Submit IT equipment issues and follow the progress of your reports.
            </p>
        </div>

        <a href="{{ route('fault-reports.create') }}" class="fault-btn-primary">
            <i class="fas fa-plus"></i>
            Report a Fault
        </a>
    </div>

    {{-- Success message --}}
    @if (session('success'))
        <div class="fault-alert" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Summary cards --}}
    <div class="fault-summary">
        <div class="fault-summary-card">
            <div class="fault-summary-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>

            <div class="fault-summary-label">Total Reports</div>

            <div class="fault-summary-number">
                {{ $reports->total() }}
            </div>
        </div>

        <div class="fault-summary-card">
            <div class="fault-summary-icon">
                <i class="fas fa-clock"></i>
            </div>

            <div class="fault-summary-label">Awaiting Resolution</div>

            <div class="fault-summary-number">
                {{ (clone $reports)->whereNotIn('status', ['resolved', 'closed'])->count() }}
            </div>
        </div>

        <div class="fault-summary-card">
            <div class="fault-summary-icon">
                <i class="fas fa-check-circle"></i>
            </div>

            <div class="fault-summary-label">Resolved or Closed</div>

            <div class="fault-summary-number">
                {{ (clone $reports)->whereIn('status', ['resolved', 'closed'])->count() }}
            </div>
        </div>
    </div>

    {{-- Reports table --}}
    <div class="fault-panel">

        <div class="fault-panel-header">
            <div>
                <h2 class="fault-panel-title">Your Submitted Reports</h2>

                <p class="fault-panel-description">
                    View the status and details of each report you submitted.
                </p>
            </div>

            <span class="text-muted small">
                {{ $reports->total() }} report(s)
            </span>
        </div>

        @if ($reports->count())
            <div class="fault-table-wrapper">
                <table class="fault-table">
                    <thead>
                        <tr>
                            <th>Report</th>
                            <th>Equipment</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td>
                                    <div class="fault-report-title">
                                        {{ $report->title }}
                                    </div>

                                    <div class="fault-report-id">
                                        Report #{{ $report->id }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fault-device">
                                        {{ $report->device_type }}
                                    </div>

                                    <div class="fault-muted">
                                        @if ($report->asset_number)
                                            Asset: {{ $report->asset_number }}
                                        @else
                                            Asset number not provided
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <span class="fault-badge fault-priority-{{ $report->priority }}">
                                        {{ ucfirst($report->priority) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="fault-badge fault-status-{{ $report->status }}">
                                        {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $report->created_at?->format('d M Y') ?? '—' }}

                                    <div class="fault-muted">
                                        {{ $report->created_at?->format('h:i A') }}
                                    </div>
                                </td>

                                <td>
                                    <a
                                        href="{{ route('fault-reports.show', $report) }}"
                                        class="fault-view-link"
                                    >
                                        View Details
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($reports->hasPages())
                <div class="fault-pagination">
                    {{ $reports->links() }}
                </div>
            @endif

        @else
            <div class="fault-empty">
                <div class="fault-empty-icon">
                    <i class="fas fa-laptop-medical"></i>
                </div>

                <h3>No Fault Reports Yet</h3>

                <p>
                    If your laptop, desktop PC, or other supported equipment has a problem,
                    submit a fault report here to get started.
                </p>

                <a href="{{ route('fault-reports.create') }}" class="fault-btn-primary">
                    <i class="fas fa-plus"></i>
                    Submit Your First Report
                </a>
            </div>
        @endif

    </div>

</div>

@endsection