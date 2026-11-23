@extends('layouts.admin')

@section('title', 'Fault Reports | CPC Inventory Management System')
@section('page-heading', 'Fault Reports')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Employee Fault Reports</h2>
            <p class="text-muted mb-0">Review, assign, and track submitted IT issues.</p>
        </div>
        <span class="badge bg-primary-subtle text-primary px-3 py-2">
            {{ $reports->total() }} report(s)
        </span>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Report</th>
                        <th>Submitted By</th>
                        <th>Equipment</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            <td>
                                <strong>{{ $report->title }}</strong>
                                <div class="text-muted small">#{{ $report->id }}</div>
                            </td>
                            <td>{{ $report->user->name ?? 'Unknown' }}</td>
                            <td>
                                {{ $report->device_type }}
                                @if ($report->asset_number)
                                    <div class="text-muted small">{{ $report->asset_number }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge text-uppercase bg-{{ $report->priority === 'critical' ? 'danger' : ($report->priority === 'high' ? 'warning' : 'secondary') }}">
                                    {{ $report->priority }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                </span>
                            </td>
                            <td>{{ $report->assignedEmployee->name ?? $report->assignedUser->name ?? 'Unassigned' }}</td>
                            <td>{{ $report->created_at?->format('d M Y h:i A') }}</td>
                            <td class="text-end">
                                <a href="{{ route('fault-reports.show', $report) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                No fault reports have been submitted.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reports->hasPages())
            <div class="card-footer bg-white">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
