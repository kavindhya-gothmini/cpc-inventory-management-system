<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'CPC Inventory Management System')</title>

    {{-- Bootstrap 5 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        /* =========================================================
           CPC AUTHENTICATION - CORPORATE WEB PORTAL
        ========================================================= */

        :root {
            --cpc-primary: #0d6efd;
            --cpc-primary-dark: #084298;
            --cpc-text: #1f2937;
            --cpc-muted: #6b7280;
            --cpc-border: #dfe3e8;
            --cpc-background: #eef3f8;
            --cpc-heading: #172033;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;

            font-family:
                Inter,
                "Segoe UI",
                Roboto,
                Helvetica,
                Arial,
                sans-serif;

            color: var(--cpc-text);

            background: var(--cpc-background);
        }

        /* =========================================================
           PAGE WRAPPER
        ========================================================= */

        .auth-wrapper {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 45px 30px;

            position: relative;

            background: #eef3f8;
        }

        /* =========================================================
           TOP CORPORATE LINE
        ========================================================= */

        .auth-wrapper::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            height: 5px;

            background: #0d6efd;
        }

        /* =========================================================
           MAIN AUTH CONTAINER
        ========================================================= */

        .auth-card {
            width: 100%;

            max-width: 760px;

            position: relative;

            z-index: 2;

            background: #ffffff;

            border: 1px solid #dfe4ea;

            border-radius: 12px;

            padding: 36px 46px 28px;

            box-shadow:
                0 18px 45px rgba(15, 23, 42, 0.08),
                0 3px 10px rgba(15, 23, 42, 0.04);

            animation: authCardFade 0.35s ease-out;
        }

        @keyframes authCardFade {

            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        /* =========================================================
           CPC BRANDING
        ========================================================= */

        .auth-brand {
            text-align: center;

            margin-bottom: 30px;

            padding-bottom: 24px;

            border-bottom: 1px solid #edf0f3;
        }

        .auth-logo {
            width: 82px;
            height: 82px;

            margin: 0 auto 13px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 8px;

            background: #ffffff;

            border: 1px solid #dfe3e8;

            border-radius: 10px;

            box-shadow:
                0 4px 12px rgba(15, 23, 42, 0.07);

            overflow: hidden;
        }

        .auth-logo img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: contain;
        }

        .auth-brand h1 {
            margin: 0;

            color: var(--cpc-heading);

            font-size: 25px;

            font-weight: 700;

            letter-spacing: -0.3px;
        }

        .auth-brand .subtitle {
            margin: 5px 0 0;

            color: var(--cpc-muted);

            font-size: 14px;

            font-weight: 400;
        }

        .auth-brand .system-label {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            margin-top: 11px;

            color: #475569;

            font-size: 11px;

            font-weight: 600;

            letter-spacing: 0.25px;
        }

        .auth-brand .system-label i {
            color: #16a34a;

            font-size: 13px;
        }

        /* =========================================================
           ALERTS
        ========================================================= */

        .auth-alert {
            border-radius: 7px;

            padding: 11px 14px;

            margin-bottom: 20px;

            border-width: 1px;

            font-size: 13px;
        }

        .auth-alert ul {
            padding-left: 19px;
        }

        .auth-alert li {
            margin-bottom: 3px;
        }

        .auth-alert li:last-child {
            margin-bottom: 0;
        }

        /* =========================================================
           FORM ELEMENTS
        ========================================================= */

        .auth-card .form-label {
            display: block;

            margin-bottom: 6px;

            color: #374151;

            font-size: 13px;

            font-weight: 600;
        }

        .auth-card .form-control,
        .auth-card .form-select {
            min-height: 46px;

            padding: 9px 12px;

            color: #111827;

            background-color: #ffffff;

            border: 1px solid var(--cpc-border);

            border-radius: 6px;

            font-size: 14px;

            transition:
                border-color 0.15s ease,
                box-shadow 0.15s ease;
        }

        .auth-card .form-control:hover,
        .auth-card .form-select:hover {
            border-color: #c5ccd5;
        }

        .auth-card .form-control:focus,
        .auth-card .form-select:focus {
            border-color: var(--cpc-primary);

            box-shadow:
                0 0 0 3px rgba(13, 110, 253, 0.10);

            outline: none;
        }

        .auth-card textarea.form-control {
            min-height: 100px;
        }

        /* =========================================================
           INPUT GROUPS
        ========================================================= */

        .auth-card .input-group-text {
            min-width: 44px;

            display: flex;

            align-items: center;
            justify-content: center;

            color: #64748b;

            background: #f8fafc;

            border-color: var(--cpc-border);
        }

        .auth-card .input-group .form-control {
            border-left: 0;
        }

        .auth-card .input-group:focus-within .input-group-text {
            color: var(--cpc-primary);

            background: #f1f6ff;

            border-color: var(--cpc-primary);
        }

        /* =========================================================
           PASSWORD TOGGLE
        ========================================================= */

        .auth-card .password-toggle {
            min-width: 44px;

            color: #64748b;

            background: #f8fafc;

            border-color: var(--cpc-border);

            transition:
                color 0.15s ease,
                background-color 0.15s ease;
        }

        .auth-card .password-toggle:hover {
            color: var(--cpc-primary);

            background: #f1f6ff;
        }

        .auth-card .password-toggle:focus {
            color: var(--cpc-primary);

            border-color: var(--cpc-primary);

            box-shadow:
                0 0 0 3px rgba(13, 110, 253, 0.10);
        }

        /* =========================================================
           CHECKBOX
        ========================================================= */

        .auth-card .form-check-input {
            width: 16px;
            height: 16px;

            margin-top: 0.15em;

            border-color: #cbd5e1;
        }

        .auth-card .form-check-input:checked {
            background-color: var(--cpc-primary);

            border-color: var(--cpc-primary);
        }

        .auth-card .form-check-label {
            color: #4b5563;

            font-size: 13px;
        }

        /* =========================================================
           PRIMARY BUTTON
        ========================================================= */

        .auth-card .btn-primary {
            min-height: 46px;

            padding: 10px 18px;

            border: 1px solid #0d6efd;

            border-radius: 6px;

            background: #0d6efd;

            color: #ffffff;

            font-size: 14px;

            font-weight: 600;

            box-shadow:
                0 4px 10px rgba(13, 110, 253, 0.15);

            transition:
                background-color 0.15s ease,
                border-color 0.15s ease,
                box-shadow 0.15s ease,
                transform 0.15s ease;
        }

        .auth-card .btn-primary:hover {
            background: #0b5ed7;

            border-color: #0a58ca;

            transform: translateY(-1px);

            box-shadow:
                0 6px 14px rgba(13, 110, 253, 0.20);
        }

        .auth-card .btn-primary:active {
            transform: translateY(0);
        }

        /* =========================================================
           LINKS
        ========================================================= */

        .auth-card a {
            color: var(--cpc-primary);

            font-weight: 600;

            text-decoration: none;
        }

        .auth-card a:hover {
            color: var(--cpc-primary-dark);

            text-decoration: underline;
        }

        /* =========================================================
           AUTH FOOTER
        ========================================================= */

        .auth-footer {
            margin-top: 28px;

            padding-top: 18px;

            border-top: 1px solid #edf0f3;

            text-align: center;

            color: #8a94a3;

            font-size: 11.5px;

            line-height: 1.7;
        }

        .auth-footer strong {
            color: #5f6977;

            font-weight: 600;
        }

        .auth-footer .footer-security {
            display: flex;

            align-items: center;
            justify-content: center;

            gap: 5px;

            margin-top: 3px;

            color: #8a94a3;

            font-size: 11px;
        }

        .auth-footer .footer-security i {
            color: #16a34a;
        }

        /* =========================================================
           FORM SECTION
        ========================================================= */

        .auth-form-section {
            width: 100%;
        }

        .auth-section-title {
            margin-bottom: 20px;

            color: #1f2937;

            font-size: 17px;

            font-weight: 650;
        }

        .auth-section-description {
            margin-top: -12px;

            margin-bottom: 22px;

            color: #6b7280;

            font-size: 13px;
        }

        /* =========================================================
           RESPONSIVE - TABLET
        ========================================================= */

        @media (max-width: 767.98px) {

            .auth-wrapper {
                padding: 30px 18px;
            }

            .auth-card {
                max-width: 100%;

                padding: 30px 25px 24px;
            }

            .auth-brand {
                margin-bottom: 25px;

                padding-bottom: 20px;
            }

            .auth-brand h1 {
                font-size: 22px;
            }

            .auth-logo {
                width: 76px;
                height: 76px;
            }
        }

        /* =========================================================
           RESPONSIVE - MOBILE
        ========================================================= */

        @media (max-width: 575.98px) {

            .auth-wrapper {
                padding: 24px 14px;
            }

            .auth-card {
                padding: 26px 20px 22px;

                border-radius: 9px;
            }

            .auth-brand h1 {
                font-size: 21px;
            }

            .auth-brand .subtitle {
                font-size: 13px;
            }
        }

        /* =========================================================
           ACCESSIBILITY
        ========================================================= */

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;

                animation-iteration-count: 1 !important;

                transition-duration: 0.01ms !important;
            }
        }
        .forgot-password-link {
    color: #0d6efd;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
}

.forgot-password-link:hover {
    color: #084298;
    text-decoration: underline;
}
    </style>

    @stack('styles')
</head>

<body>

    <div class="auth-wrapper">

        <main class="auth-card">

            {{-- =================================================
                 CPC BRANDING
            ================================================== --}}
            <div class="auth-brand">

                <div class="auth-logo">

                    <img
                        src="{{ asset('images/cpc-logo.png') }}"
                        alt="Ceylon Petroleum Corporation Logo"
                    >

                </div>

                <h1>
                    CEYLON   PETROLEYUM   CORPORATION
                </h1>

                <p class="subtitle">
                    Inventory Management System
                </p>

                <div class="system-label">

                    <i class="bi bi-shield-check"></i>

                    Secure Internal System

                </div>

            </div>


            {{-- =================================================
                 SUCCESS MESSAGE
            ================================================== --}}
            @if (session('status'))

                <div
                    class="alert alert-success auth-alert"
                    role="alert"
                >

                    <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-check-circle-fill mt-1"></i>

                        <div>
                            {{ session('status') }}
                        </div>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 VALIDATION ERRORS
            ================================================== --}}
            @if ($errors->any())

                <div
                    class="alert alert-danger auth-alert"
                    role="alert"
                >

                    <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-exclamation-triangle-fill mt-1"></i>

                        <div class="flex-grow-1">

                            <strong>
                                Please check the following:
                            </strong>

                            <ul class="mb-0 mt-1">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 PAGE CONTENT
            ================================================== --}}
            <div class="auth-form-section">

                @yield('content')

            </div>


            {{-- =================================================
                 FOOTER
            ================================================== --}}
            <div class="auth-footer">

                <div>
                    © {{ date('Y') }}
                    <strong>Ceylon Petroleum Corporation</strong>
                </div>

                <div>
                    CPC Inventory Management System
                </div>

                <div class="footer-security">

                    <i class="bi bi-shield-lock-fill"></i>

                    Authorized personnel only

                </div>

            </div>

        </main>

    </div>


    {{-- Bootstrap JavaScript --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')

</body>
</html>

