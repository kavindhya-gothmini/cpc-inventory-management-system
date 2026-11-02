
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Dashboard | CPC Inventory Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --navy: #10243a;
            --blue: #1769aa;
            --background: #f3f6fa;
            --muted: #6b7b8e;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--background);
            color: var(--navy);
            font-family: 'DM Sans', sans-serif;
        }

        .topbar {
            background: var(--navy);
            color: white;
            padding: 18px 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 13px;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: .2px;
        }

        .brand-logo {
            width: 43px;
            height: 43px;
            object-fit: contain;
            background: white;
            border-radius: 10px;
            padding: 4px;
        }

        .topbar-subtitle {
            color: #c5d4e5;
            font-size: 12px;
            font-weight: 400;
            margin-top: 3px;
        }

        .user-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 30px;
            padding: 9px 14px;
            font-size: 13px;
        }

        .page-wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 40px 22px;
        }

        .welcome-panel {
            background: linear-gradient(120deg, #10243a, #174c78);
            border-radius: 22px;
            color: white;
            padding: 34px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .welcome-panel::after {
            content: "";
            position: absolute;
            width: 230px;
            height: 230px;
            border: 35px solid rgba(255,255,255,.06);
            border-radius: 50%;
            right: -45px;
            top: -95px;
            pointer-events: none;
        }

        .eyebrow {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            font-weight: 700;
            color: #a9d8ff;
            margin-bottom: 12px;
        }

        .welcome-panel h1 {
            font-size: clamp(25px, 4vw, 35px);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .welcome-panel p {
            color: #d4e4f3;
            max-width: 650px;
            margin-bottom: 0;
            line-height: 1.7;
        }

        .section-heading {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .section-description {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 22px;
        }

        .dashboard-card {
            height: 100%;
            background: white;
            border: 1px solid #e4eaf1;
            border-radius: 18px;
            padding: 25px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 35px rgba(16,36,58,.09);
        }

        .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            border-radius: 15px;
            background: #e8f2fc;
            color: var(--blue);
            font-size: 25px;
            margin-bottom: 20px;
        }

        .icon-box.employee {
            background: #e7f7ef;
            color: #168653;
        }

        .card-label {
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 9px;
        }

        .dashboard-card h3 {
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .dashboard-card p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
            min-height: 44px;
        }

        .dashboard-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
            margin-top: 20px;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .dashboard-link.primary {
            background: var(--navy);
            color: white;
        }

        .dashboard-link.primary:hover {
            background: var(--blue);
            color: white;
        }

        .dashboard-link.secondary {
            background: #eaf7f0;
            color: #167448;
        }

        .dashboard-link.secondary:hover {
            background: #d5f0e1;
            color: #125d3b;
        }

        .location-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #e8eef5;
            color: #344b64;
            border-radius: 20px;
            padding: 7px 12px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .footer {
            text-align: center;
            color: #8190a1;
            font-size: 12px;
            padding: 25px 15px;
        }

        .logout-btn {
            background: transparent;
            border: 1px solid rgba(255,255,255,.35);
            color: white;
            border-radius: 9px;
            padding: 9px 13px;
            font-size: 12px;
            font-weight: 700;
        }

        .logout-btn:hover {
            background: white;
            color: var(--navy);
        }

        @media (max-width: 767px) {
            .page-wrap { padding: 25px 15px; }
            .welcome-panel { padding: 25px 21px; }
            .topbar .container { align-items: flex-start !important; }
            .user-pill { display: none; }
            .dashboard-card { padding: 21px; }
            .dashboard-card p { min-height: 0; }
        }
    </style>
</head>
<body>

@php
    $user = auth()->user();
    $role = strtolower(trim((string) ($user->role ?? 'employee')));

    // This follows the application's current role names.
    // Update this permission rule if you later add a separate IT role.
    $canAccessItDashboard = in_array($role, ['admin', 'super_admin'], true);

    $branch = $user->branch ?? $user->employee?->branch;
    $branchName = $branch->name ?? 'Branch not assigned';
    $branchCode = (string) ($branch->code ?? '');

    $locationName = 'Organization';
    if ($branchCode === '2000' ||
        str_contains(strtolower($branchName), 'head office')) {
        $locationName = 'Head Office';
    } elseif (str_contains(strtolower($branchName), 'refinery')) {
        $locationName = 'Refinery';
    } elseif ($branchName !== 'Branch not assigned') {
        $locationName = $branchName;
    }
@endphp

<header class="topbar">
    <div class="container d-flex justify-content-between align-items-center gap-3">
        <div class="brand">
            <img src="{{ asset('images/cpc-logo.png') }}"
                 class="brand-logo"
                 alt="CPC logo"
                 onerror="this.style.display='none'">

            <div>
                CPC INVENTORY
                <div class="topbar-subtitle">Inventory & IT Support Portal</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="user-pill">
                <i class="bi bi-person-circle"></i>
                {{ $user->name ?? $user->epf_number ?? 'User' }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>

<main class="page-wrap">
    <section class="welcome-panel">
        <div class="eyebrow">Ceylon Petroleum Corporation</div>
        <h1>Welcome, {{ $user->name ?? 'User' }}</h1>
        <p>
            Select the dashboard you need to access. Your available options
            depend on your account permissions and assigned location.
        </p>
    </section>

    <div class="location-badge">
        <i class="bi bi-geo-alt-fill"></i>
        {{ $locationName }}
        @if($branchCode !== '')
            <span>·</span> Branch {{ $branchCode }}
        @endif
    </div>

    <section class="mb-4">
        <h2 class="section-heading">Choose Your Dashboard</h2>
        <p class="section-description">
            Access the tools relevant to your responsibilities.
        </p>

        <div class="row g-4">

            @if($canAccessItDashboard)
                <div class="col-12 col-md-6">
                    <article class="dashboard-card">
                        <div class="icon-box">
                            <i class="bi bi-pc-display-horizontal"></i>
                        </div>

                        <div class="card-label">{{ $locationName }}</div>
                        <h3>IT Function Dashboard</h3>
                        <p>
                            Manage inventory, review equipment information,
                            and access the existing IT Function dashboard.
                            Data must be restricted to your authorized branch.
                        </p>

                        <a href="{{ route('dashboard') }}"
                           class="dashboard-link primary">
                            <span>Open IT Dashboard</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </article>
                </div>
            @endif

            <div class="col-12 col-md-6">
                <article class="dashboard-card">
                    <div class="icon-box employee">
                        <i class="bi bi-person-workspace"></i>
                    </div>

                    <div class="card-label">Employee Services</div>
                    <h3>User Dashboard</h3>
                    <p>
                        View your personal dashboard, report equipment
                        faults, track submitted reports, and check updates
                        from IT support.
                    </p>

                    <a href="{{ route('employee.dashboard') }}"
                       class="dashboard-link secondary">
                        <span>Open User Dashboard</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </article>
            </div>

        </div>
    </section>

    <footer class="footer">
        Ceylon Petroleum Corporation · Inventory Management System
        <br>
        Authorized access only
    </footer>
</main>

</body>
</html>
