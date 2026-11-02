
@extends('layouts.admin')

@section('title', 'Fault Report Details | CPC IT Inventory Management System')
@section('page-heading', 'Fault Report Details')

@section('content')

<style>
    .fault-detail-page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 8px 0 30px;
        color: #172033;
    }

    .fault-detail-header {
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
        line-height: 1.6;
    }

    .fault-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 15px;
        border: 1px solid #d8dee8;
        border-radius: 7px;
        background: #fff;
        color: #475467;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s;
    }

    .fault-btn:hover {
        background: #f8fafc;
        color: #172033;
    }

    .fault-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.8fr) minmax(260px, 1fr);
        align-items: start;
        gap: 20px;
    }

    .fault-card {
        overflow: hidden;
        margin-bottom: 20px;
        border: 1px solid #e8edf4;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    }

    .fault-card:last-child {
        margin-bottom: 0;
    }

    .fault-card-header {
        padding: 19px 22px;
        border-bottom: 1px solid #edf0f5;
        background: #fbfcfe;
    }

    .fault-card-header h2 {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 700;
    }

    .fault-card-header p {
        margin: 6px 0 0;
        color: #8791a1;
        font-size: 12px;
    }

    .fault-card-body {
        padding: 22px;
    }

    .fault-report-heading {
        margin-bottom: 17px;
        color: #172033;
        font-size: 20px;
        font-weight: 750;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .fault-report-number {
        color: #98a2b3;
        font-size: 12px;
    }

    .fault-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 15px;
    }

    .fault-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
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

    .fault-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 20px;
    }

    .fault-info-label {
        margin-bottom: 6px;
        color: #8791a1;
        font-size: 11px;
        font-weight: 600;
    }

    .fault-info-value {
        color: #344054;
        font-size: 13px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .fault-description {
        margin-top: 23px;
        padding-top: 21px;
        border-top: 1px solid #edf0f5;
    }

    .fault-description h3 {
        margin: 0 0 11px;
        color: #172033;
        font-size: 13px;
        font-weight: 700;
    }

    .fault-description-text {
        margin: 0;
        color: #475467;
        font-size: 13px;
        line-height: 1.8;
        overflow-wrap: anywhere;
        white-space: pre-wrap;
    }

    .fault-response {
        padding: 16px;
        border: 1px solid #dbeafe;
        border-radius: 9px;
        background: #f5f9ff;
    }

    .fault-response + .fault-response {
        margin-top: 15px;
    }

    .fault-response h3 {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 10px;
        color: #175cd3;
        font-size: 13px;
        font-weight: 700;
    }

    .fault-response p {
        margin: 0;
        color: #344054;
        font-size: 13px;
        line-height: 1.8;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .fault-empty-response {
        padding: 18px;
        border: 1px dashed #d8dee8;
        border-radius: 9px;
        color: #8791a1;
        font-size: 12px;
        line-height: 1.7;
        text-align: center;
    }

    .fault-empty-response i {
        display: block;
        margin-bottom: 10px;
        color: #98a2b3;
        font-size: 22px;
    }

    .fault-side-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .fault-side-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid #f0f2f6;
    }

    .fault-side-item:first-child {
        padding-top: 0;
    }

    .fault-side-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .fault-side-icon {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #edf4ff;
        color: #0d6efd;
        font-size: 13px;
    }

    .fault-side-label {
        margin-bottom: 5px;
        color: #8791a1;
        font-size: 11px;
    }

    .fault-side-value {
        color: #344054;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .fault-timeline {
        position: relative;
        padding-left: 22px;
    }

    .fault-timeline::before {
        position: absolute;
        top: 7px;
        bottom: 8px;
        left: 6px;
        width: 2px;
        background: #e4e7ec;
        content: "";
    }

    .fault-timeline-item {
        position: relative;
        padding-bottom: 22px;
    }

    .fault-timeline-item:last-child {
        padding-bottom: 0;
    }

    .fault-timeline-dot {
        position: absolute;
        top: 4px;
        left: -22px;
        width: 14px;
        height: 14px;
        border: 3px solid #fff;
        border-radius: 50%;
        background: #0d6efd;
        box-shadow: 0 0 0 1px #b8d2ff;
    }

    .fault-timeline-item h3 {
        margin: 0 0 5px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .fault-timeline-item p {
        margin: 0;
        color: #8791a1;
        font-size: 11px;
        line-height: 1.6;
    }

    @media (max-width: 850px) {
        .fault-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 550px) {
        .fault-title {
            font-size: 23px;
        }

        .fault-info-grid {
            grid-template-columns: 1fr;
            gap: 17px;
        }

        .fault-card-body {
            padding: 17px;
        }

        .fault-card-header {
            padding: 17px;
        }

        .fault-detail-header .fault-btn {
            width: 100%;
        }
    }
</style>

@php
    $statusLabel = ucwords(str_replace('_', ' ', $report->status));
    $priorityLabel = ucfirst($report->priority);

    $statusDescription = match ($report->status) {
        'submitted' => 'Your report has been submitted and is awaiting IT review.',
        'under_review' => 'The IT team is reviewing the reported issue.',
        'in_progress' => 'The IT team is working on your reported issue.',
        'waiting_for_user' => 'The IT team may need additional information from you.',
        'resolved' => 'The issue has been marked as resolved.',
        'closed' => 'This fault report has been closed.',
        default => 'Check this page for updates to your report.',
    };
@endphp

<div class="fault-detail-page">

    <div class="fault-detail-header">
        <div>
            <div class="fault-eyebrow">
                <i class="fas fa-headset me-1"></i>
                User Dashboard
            </div>

            <h1 class="fault-title">Fault Report Details</h1>

            <p class="fault-subtitle">
                Review your submitted issue and any updates from the IT team.
            </p>
        </div>

        <a href="{{ route('fault-reports.index') }}" class="fault-btn">
            <i class="fas fa-arrow-left"></i>
            Back to My Reports
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="fault-layout">

        {{-- Main report information --}}
        <div>

            <div class="fault-card">
                <div class="fault-card-body">

                    <div class="fault-report-number">
                        Report #{{ $report->id }}
                    </div>

                    <h2 class="fault-report-heading">
                        {{ $report->title }}
                    </h2>

                    <div class="fault-badges">
                        <span class="fault-badge fault-status-{{ $report->status }}">
                            {{ $statusLabel }}
                        </span>

                        <span class="fault-badge fault-priority-{{ $report->priority }}">
                            {{ $priorityLabel }} Priority
                        </span>
                    </div>

                    <div class="fault-description">
                        <h3>
                            <i class="fas fa-info-circle me-2"></i>
                            Current Status
                        </h3>

                        <p class="fault-description-text">{{ $statusDescription }}</p>
                    </div>

                </div>
            </div>

            {{-- Equipment information --}}
            <div class="fault-card">
                <div class="fault-card-header">
                    <h2>
                        <i class="fas fa-laptop me-2 text-primary"></i>
                        Equipment Information
                    </h2>

                    <p>Equipment details supplied with your report.</p>
                </div>

                <div class="fault-card-body">
                    <div class="fault-info-grid">

                        <div>
                            <div class="fault-info-label">Equipment Type</div>
                            <div class="fault-info-value">
                                {{ $report->device_type }}
                            </div>
                        </div>

                        <div>
                            <div class="fault-info-label">Asset Number</div>
                            <div class="fault-info-value">
                                {{ $report->asset_number ?: 'Not provided' }}
                            </div>
                        </div>

                        <div>
                            <div class="fault-info-label">Make / Manufacturer</div>
                            <div class="fault-info-value">
                                {{ $report->device_make ?: 'Not provided' }}
                            </div>
                        </div>

                        <div>
                            <div class="fault-info-label">Model</div>
                            <div class="fault-info-value">
                                {{ $report->device_model ?: 'Not provided' }}
                            </div>
                        </div>

                    </div>

                    <div class="fault-description">
                        <h3>
                            <i class="fas fa-align-left me-2 text-primary"></i>
                            Fault Description
                        </h3>

                        <p class="fault-description-text">{{ $report->description }}</p>
                    </div>
                </div>
            </div>

            {{-- IT response and resolution --}}
            <div class="fault-card">
                <div class="fault-card-header">
                    <h2>
                        <i class="fas fa-comment-dots me-2 text-primary"></i>
                        IT Updates and Resolution
                    </h2>

                    <p>Responses and resolution information recorded by IT staff.</p>
                </div>

                <div class="fault-card-body">

                    @if ($report->it_response)
                        <div class="fault-response">
                            <h3>
                                <i class="fas fa-reply"></i>
                                IT Response
                            </h3>

                            <p>{{ $report->it_response }}</p>
                        </div>
                    @endif

                    @if ($report->resolution_notes)
                        <div class="fault-response">
                            <h3>
                                <i class="fas fa-check-circle"></i>
                                Resolution Notes
                            </h3>

                            <p>{{ $report->resolution_notes }}</p>
                        </div>
                    @endif

                    @if (!$report->it_response && !$report->resolution_notes)
                        <div class="fault-empty-response">
                            <i class="fas fa-comment-slash"></i>

                            <strong>No IT update yet</strong>

                            <div>
                                Your report details are available above. Any response or
                                resolution notes will appear here when recorded by the IT team.
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div>

            <div class="fault-card">
                <div class="fault-card-header">
                    <h2>Report Summary</h2>
                    <p>Key information about this report.</p>
                </div>

                <div class="fault-card-body">
                    <ul class="fault-side-list">

                        <li class="fault-side-item">
                            <div class="fault-side-icon">
                                <i class="fas fa-calendar-plus"></i>
                            </div>

                            <div>
                                <div class="fault-side-label">Date Submitted</div>

                                <div class="fault-side-value">
                                    {{ $report->created_at?->format('d M Y, h:i A') ?? '—' }}
                                </div>
                            </div>
                        </li>

                        <li class="fault-side-item">
                            <div class="fault-side-icon">
                                <i class="fas fa-sync-alt"></i>
                            </div>

                            <div>
                                <div class="fault-side-label">Last Updated</div>

                                <div class="fault-side-value">
                                    {{ $report->updated_at?->format('d M Y, h:i A') ?? '—' }}
                                </div>
                            </div>
                        </li>

                        <li class="fault-side-item">
                            <div class="fault-side-icon">
                                <i class="fas fa-building"></i>
                            </div>

                            <div>
                                <div class="fault-side-label">Assigned Location</div>

                                <div class="fault-side-value">
                                    {{ $report->branch?->name ?? 'Not available' }}
                                </div>
                            </div>
                        </li>

                        <li class="fault-side-item">
                            <div class="fault-side-icon">
                                <i class="fas fa-user-cog"></i>
                            </div>

                            <div>
                                <div class="fault-side-label">Assigned IT Staff</div>

                                <div class="fault-side-value">
                                    {{ $report->assignedUser?->name ?? 'Not yet assigned' }}
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="fault-card">
                <div class="fault-card-header">
                    <h2>Report Progress</h2>
                    <p>Current stage of your request.</p>
                </div>

                <div class="fault-card-body">
                    <div class="fault-timeline">

                        <div class="fault-timeline-item">
                            <span class="fault-timeline-dot"></span>

                            <h3>Report Submitted</h3>

                            <p>
                                {{ $report->created_at?->format('d M Y, h:i A') ?? 'Submission date unavailable' }}
                            </p>
                        </div>

                        <div class="fault-timeline-item">
                            <span class="fault-timeline-dot"></span>

                            <h3>IT Review and Handling</h3>

                            <p>
                                @if (in_array($report->status, ['under_review', 'in_progress', 'waiting_for_user', 'resolved', 'closed']))
                                    Your report has progressed beyond initial submission.
                                @else
                                    Awaiting review by the IT team.
                                @endif
                            </p>
                        </div>

                        <div class="fault-timeline-item">
                            <span class="fault-timeline-dot"></span>

                            <h3>Resolution</h3>

                            <p>
                                @if ($report->resolved_at)
                                    {{ $report->resolved_at->format('d M Y, h:i A') }}
                                @elseif (in_array($report->status, ['resolved', 'closed']))
                                    Marked {{ $report->status }}; no resolution timestamp is recorded.
                                @else
                                    Not yet marked as resolved.
                                @endif
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
