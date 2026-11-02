
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Employee Dashboard | CPC IT Support</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --navy: #10243a;
            --navy-light: #183650;
            --blue: #2563eb;
            --page: #f4f7fb;
            --muted: #718096;
            --border: #e7edf4;
            --green: #16845b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page);
            color: #243449;
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #93c5fd;
            outline-offset: 3px;
        }

        .topbar {
            background: var(--navy);
            color: #fff;
            padding: 15px 0;
            box-shadow: 0 3px 15px rgba(16, 36, 58, .12);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            letter-spacing: .2px;
            min-width: 0;
        }

        .brand:hover {
            color: #fff;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 43px;
            height: 43px;
            background: #fff;
            color: var(--navy);
            border-radius: 12px;
            font-size: 22px;
        }

        .brand small {
            display: block;
            color: #c4d4e5;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1.2px;
            margin-top: 2px;
        }

        .topbar .btn {
            border-radius: 10px;
            font-weight: 600;
        }

        .page-wrap {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 34px 22px 50px;
        }

        .welcome-panel {
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            padding: 32px;
            color: #fff;
            background: linear-gradient(120deg, #10243a, #1c4569);
            box-shadow: 0 12px 32px rgba(16, 36, 58, .12);
        }

        .welcome-panel::after {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            right: -70px;
            top: -100px;
            border-radius: 50%;
            border: 35px solid rgba(255, 255, 255, .06);
            pointer-events: none;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
            min-width: 0;
        }

        .eyebrow {
            color: #a9c8e6;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.7px;
            text-transform: uppercase;
        }

        .welcome-panel h1 {
            font-size: clamp(25px, 4vw, 36px);
            font-weight: 800;
            letter-spacing: -.8px;
            margin: 10px 0;
            overflow-wrap: anywhere;
        }

        .welcome-panel p {
            color: #d5e2ef;
            margin-bottom: 0;
            max-width: 620px;
            line-height: 1.7;
        }

        .welcome-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 76px;
            height: 76px;
            border-radius: 20px;
            background: rgba(255, 255, 255, .12);
            color: #fff;
            font-size: 35px;
            margin-left: 20px;
        }

        .section-heading {
            font-size: 19px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 5px;
        }

        .section-description {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 0;
            line-height: 1.6;
        }

        .stat-card,
        .content-card {
            height: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 17px;
            box-shadow: 0 5px 18px rgba(24, 48, 77, .035);
        }

        .stat-card {
            padding: 21px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 9px 24px rgba(24, 48, 77, .07);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.5;
        }

        .stat-number {
            color: var(--navy);
            font-size: 32px;
            line-height: 1.2;
            font-weight: 800;
            margin-top: 16px;
            overflow-wrap: anywhere;
        }

        .stat-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 43px;
            height: 43px;
            border-radius: 13px;
            font-size: 20px;
        }

        .icon-blue {
            background: #eaf1ff;
            color: #2860c9;
        }

        .icon-orange {
            background: #fff3e5;
            color: #c97716;
        }

        .icon-green {
            background: #e7f7ef;
            color: #16845b;
        }

        .content-card {
            padding: 23px;
        }

        .primary-action {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            padding: 13px 18px;
            border: 0;
            border-radius: 11px;
            background: var(--blue);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            text-align: center;
            transition: background .2s ease, transform .2s ease;
        }

        .primary-action:hover {
            color: #fff;
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .secondary-action {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            padding: 11px 15px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--navy);
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: background .2s ease;
        }

        .secondary-action:hover {
            color: var(--navy);
            background: #f7faff;
        }

        .info-strip {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px;
            border-radius: 12px;
            background: #f1f6ff;
            color: #375779;
            font-size: 13px;
            line-height: 1.6;
        }

        .info-strip i {
            flex-shrink: 0;
            color: var(--blue);
            font-size: 19px;
        }

        .table-responsive {
            margin-top: 18px;
            border-radius: 10px;
        }

        .table {
            margin-bottom: 0;
            min-width: 590px;
        }

        .table thead th {
            padding: 13px 12px;
            background: #f7f9fc;
            color: #68798d;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table tbody td {
            padding: 15px 12px;
            vertical-align: middle;
            border-color: #eef2f7;
            font-size: 13px;
        }

        .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .report-title {
            color: var(--navy);
            font-weight: 700;
            text-decoration: none;
            overflow-wrap: anywhere;
        }

        .report-title:hover {
            color: var(--blue);
        }

        .report-id {
            display: block;
            color: #8996a8;
            font-size: 11px;
            margin-top: 4px;
        }

        .status-badge,
        .priority-badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-submitted {
            background: #eaf1ff;
            color: #285bc0;
        }

        .status-under_review {
            background: #fff3d9;
            color: #95620b;
        }

        .status-in_progress {
            background: #e9f4ff;
            color: #1768a5;
        }

        .status-waiting_for_user {
            background: #f3eaff;
            color: #7744a4;
        }

        .status-resolved {
            background: #e5f7ed;
            color: #18784e;
        }

        .status-closed {
            background: #edf0f4;
            color: #5f6b7a;
        }

        .priority-low {
            background: #edf7ef;
            color: #397849;
        }

        .priority-medium {
            background: #fff4db;
            color: #8d6509;
        }

        .priority-high {
            background: #ffeadf;
            color: #b44a17;
        }

        .priority-critical {
            background: #ffe5e7;
            color: #b42332;
        }

        .empty-state {
            padding: 35px 15px;
            text-align: center;
        }

        .empty-state i {
            display: block;
            font-size: 38px;
            color: #a9b9cd;
            margin-bottom: 13px;
        }

        .empty-state h3 {
            font-size: 17px;
            font-weight: 800;
            color: var(--navy);
        }

        .empty-state p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .profile-detail {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid #edf1f6;
        }

        .profile-detail:last-child {
            border-bottom: 0;
        }

        .profile-detail i {
            width: 20px;
            flex-shrink: 0;
            color: #6b8aaa;
            font-size: 17px;
        }

        .profile-detail small {
            display: block;
            color: var(--muted);
            font-size: 11px;
            margin-bottom: 3px;
        }

        .profile-detail strong {
            display: block;
            color: var(--navy);
            font-size: 13px;
            overflow-wrap: anywhere;
            line-height: 1.5;
        }

        .footer {
            padding: 25px 0 0;
            text-align: center;
            color: #8795a6;
            font-size: 12px;
            line-height: 1.8;
        }

        .pagination {
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .alert {
            border-radius: 12px;
        }

        @media (max-width: 991.98px) {
            .page-wrap {
                padding: 28px 18px 40px;
            }

            .stat-card {
                padding: 18px;
            }

            .stat-number {
                font-size: 29px;
            }

            .content-card {
                padding: 20px;
            }
        }

        @media (max-width: 767.98px) {
            .page-wrap {
                padding: 22px 14px 35px;
            }

            .welcome-panel {
                padding: 24px 20px;
                border-radius: 17px;
            }

            .welcome-icon {
                display: none;
            }

            .content-card {
                padding: 17px;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-number {
                font-size: 27px;
                margin-top: 12px;
            }

            .stat-label {
                font-size: 12px;
            }

            .stat-icon {
                width: 37px;
                height: 37px;
                border-radius: 11px;
                font-size: 17px;
            }

            .topbar {
                padding: 12px 0;
            }

            .topbar .container {
                gap: 12px;
            }

            .brand-mark {
                width: 38px;
                height: 38px;
                font-size: 20px;
            }

            .brand {
                gap: 9px;
                font-size: 13px;
            }

            .brand small {
                font-size: 9px;
                letter-spacing: .9px;
            }

            .section-heading {
                font-size: 17px;
            }
        }

        @media (max-width: 380px) {
            .page-wrap {
                padding-right: 10px;
                padding-left: 10px;
            }

            .welcome-panel {
                padding: 21px 16px;
            }

            .stat-card {
                padding: 13px;
            }

            .stat-label {
                font-size: 11px;
            }

            .stat-icon {
                width: 32px;
                height: 32px;
                font-size: 15px;
            }

            .stat-number {
                font-size: 24px;
            }

            .content-card {
                padding: 14px;
            }

            .primary-action,
            .secondary-action {
                padding: 11px 13px;
                font-size: 13px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="brand" href="{{ route('employee.dashboard') }}">
            <span class="brand-mark">
                <i class="bi bi-pc-display-horizontal"></i>
            </span>
            <span>
                CPC IT SUPPORT
                <small>EMPLOYEE PORTAL</small>
            </span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm px-3 py-2">
                <i class="bi bi-box-arrow-right me-1"></i>
                <span class="d-none d-sm-inline">Sign out</span>
                <span class="d-sm-none">Exit</span>
            </button>
        </form>
    </div>
</header>

<main class="page-wrap">

    {{-- Success notification --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- General error notification --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Welcome panel --}}
    <section class="welcome-panel mb-4">
        <div class="d-flex align-items-center justify-content-between position-relative">
            <div class="welcome-content">
                <div class="eyebrow">Ceylon Petroleum Corporation</div>

                <h1>Welcome, {{ $user->name }}!</h1>

                <p>
                    Your employee IT support centre. Report computer problems,
                    follow your requests, and view updates from the IT team.
                </p>

                <div class="mt-4 d-flex flex-wrap gap-2">
                    <a href="{{ route('fault-reports.create') }}"
                       class="primary-action">
                        <i class="bi bi-plus-circle"></i>
                        Report a Fault
                    </a>

                    <a href="{{ route('fault-reports.index') }}"
                       class="secondary-action">
                        <i class="bi bi-list-check"></i>
                        My Reports
                    </a>
                </div>
            </div>

            <div class="welcome-icon" aria-hidden="true">
                <i class="bi bi-headset"></i>
            </div>
        </div>
    </section>

    {{-- Fault report summary --}}
    <section class="mb-4" aria-label="Fault report summary">
        <div class="row g-3">

            {{-- Total reports --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Total Reports</span>

                        <span class="stat-icon icon-blue">
                            <i class="bi bi-file-earmark-text"></i>
                        </span>
                    </div>

                    <div class="stat-number">
                        {{ $totalReports }}
                    </div>
                </div>
            </div>

            {{-- Submitted and under review --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Submitted / Review</span>

                        <span class="stat-icon icon-orange">
                            <i class="bi bi-hourglass-split"></i>
                        </span>
                    </div>

                    <div class="stat-number">
                        {{ $submittedReports }}
                    </div>
                </div>
            </div>

            {{-- In progress and waiting for user --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">In Progress</span>

                        <span class="stat-icon icon-blue">
                            <i class="bi bi-tools"></i>
                        </span>
                    </div>

                    <div class="stat-number">
                        {{ $inProgressReports }}
                    </div>
                </div>
            </div>

            {{-- Resolved and closed --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-label">Resolved / Closed</span>

                        <span class="stat-icon icon-green">
                            <i class="bi bi-check2-circle"></i>
                        </span>
                    </div>

                    <div class="stat-number">
                        {{ $resolvedReports }}
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div class="row g-4">

        {{-- Recent fault reports --}}
        <div class="col-12 col-lg-8">
            <section class="content-card">

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h2 class="section-heading">My Fault Reports</h2>

                        <p class="section-description">
                            Track the progress of issues you have reported.
                        </p>
                    </div>

                    <a href="{{ route('fault-reports.index') }}"
                       class="secondary-action">
                        View All
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                @if($faultReports->count())

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Report</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Priority</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($faultReports as $report)
                                    <tr>
                                        <td>
                                            <a class="report-title"
                                               href="{{ route('fault-reports.show', $report) }}">
                                                {{ \Illuminate\Support\Str::limit($report->title, 35) }}
                                            </a>

                                            <span class="report-id">
                                                FR-{{ str_pad((string) $report->id, 5, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="status-badge status-{{ $report->status }}">
                                                {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="priority-badge priority-{{ $report->priority }}">
                                                {{ ucfirst($report->priority) }}
                                            </span>
                                        </td>

                                        <td class="text-nowrap">
                                            {{ $report->created_at?->format('d M Y') ?? '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                        <small class="text-secondary">
                            Showing {{ $faultReports->firstItem() ?? 0 }}
                            – {{ $faultReports->lastItem() ?? 0 }}
                            of {{ $faultReports->total() }} reports
                        </small>
                    </div>

                    {{ $faultReports->links() }}

                @else

                    <div class="empty-state">
                        <i class="bi bi-clipboard2-check" aria-hidden="true"></i>

                        <h3>No fault reports yet</h3>

                        <p>
                            If your work PC or laptop has a problem, submit a report
                            to notify the IT support team.
                        </p>

                        <a href="{{ route('fault-reports.create') }}"
                           class="primary-action">
                            <i class="bi bi-plus-circle"></i>
                            Submit Your First Report
                        </a>
                    </div>

                @endif

            </section>
        </div>

        {{-- Employee details and help --}}
        <div class="col-12 col-lg-4">

            {{-- Employee profile --}}
            <section class="content-card mb-4">
                <h2 class="section-heading">My Employee Details</h2>

                <p class="section-description mb-3">
                    Your account information
                </p>

                <div class="profile-detail">
                    <i class="bi bi-person" aria-hidden="true"></i>

                    <div>
                        <small>Employee Name</small>
                        <strong>{{ $user->name ?: 'Not available' }}</strong>
                    </div>
                </div>

                <div class="profile-detail">
                    <i class="bi bi-person-badge" aria-hidden="true"></i>

                    <div>
                        <small>EPF Number</small>

                        <strong>
                            {{ $user->epf_number ?: ($employee?->epf_no ?: 'Not available') }}
                        </strong>
                    </div>
                </div>

                <div class="profile-detail">
                    <i class="bi bi-building" aria-hidden="true"></i>

                    <div>
                        <small>Branch / Location</small>

                        <strong>
                            {{ $branch?->name ?? 'Not assigned' }}
                        </strong>
                    </div>
                </div>

                <div class="profile-detail">
                    <i class="bi bi-briefcase" aria-hidden="true"></i>

                    <div>
                        <small>Designation</small>

                        <strong>
                            {{ $employee?->designation ?: 'Not available' }}
                        </strong>
                    </div>
                </div>

                <div class="profile-detail">
                    <i class="bi bi-envelope" aria-hidden="true"></i>

                    <div>
                        <small>Email</small>

                        <strong>
                            {{ $user->email ?: 'Not available' }}
                        </strong>
                    </div>
                </div>
            </section>

            {{-- IT support instructions --}}
            <section class="content-card">
                <h2 class="section-heading">How to Get IT Help</h2>

                <p class="section-description mb-3">
                    Follow these steps when a device has a problem.
                </p>

                <div class="info-strip mb-3">
                    <i class="bi bi-1-circle-fill" aria-hidden="true"></i>

                    <div>
                        <strong>Identify your device</strong>
                        <br>
                        Include the asset number, if available.
                    </div>
                </div>

                <div class="info-strip mb-3">
                    <i class="bi bi-2-circle-fill" aria-hidden="true"></i>

                    <div>
                        <strong>Describe the problem</strong>
                        <br>
                        Explain the error message and when it occurs.
                    </div>
                </div>

                <div class="info-strip">
                    <i class="bi bi-3-circle-fill" aria-hidden="true"></i>

                    <div>
                        <strong>Track your report</strong>
                        <br>
                        Check its status and any response from IT.
                    </div>
                </div>

                <a href="{{ route('fault-reports.create') }}"
                   class="primary-action w-100 mt-3">
                    <i class="bi bi-headset"></i>
                    Contact IT Support
                </a>
            </section>

        </div>
    </div>

    <footer class="footer">
        Ceylon Petroleum Corporation &middot; Employee IT Support Portal
        <br>
        <span>
            Use this portal to report work-device issues to your IT team.
        </span>
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
