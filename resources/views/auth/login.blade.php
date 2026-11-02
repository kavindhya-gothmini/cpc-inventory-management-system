@extends('layouts.auth')

@section('title', 'Login - CPC Inventory')

@section('content')

{{-- =========================================================
LOGIN HEADER
========================================================= --}}

<div class="login-header">

    <div class="login-icon">
        <i class="bi bi-person-lock"></i>
    </div>

    <div>
        <h2>Welcome Back</h2>

        <p>
            Sign in to access the CPC Inventory Management System.
        </p>
    </div>

</div>


{{-- =========================================================
LOGIN FORM
========================================================= --}}

<form method="POST" action="{{ route('login') }}">

    @csrf


    {{-- =====================================================
         EMAIL
    ====================================================== --}}

    <div class="login-field">

        <label for="email">
            Email Address
        </label>

        <div class="login-input-group">

            <span class="login-input-icon">
                <i class="bi bi-envelope"></i>
            </span>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="@error('email') is-invalid @enderror"
                required
                autofocus
                autocomplete="email"
                placeholder="Enter your email address"
            >

        </div>

        @error('email')

            <div class="login-error">

                <i class="bi bi-exclamation-circle"></i>

                {{ $message }}

            </div>

        @enderror

    </div>


    {{-- =====================================================
         PASSWORD
    ====================================================== --}}

    <div class="login-field">

        <div class="password-label-row">

            <label for="password">
                Password
            </label>

            @if (Route::has('password.request'))

                <a
                    href="{{ route('password.request') }}"
                    class="forgot-password"
                >
                    Forgot password?
                </a>

            @endif

        </div>

        <div class="login-input-group">

            <span class="login-input-icon">
                <i class="bi bi-lock"></i>
            </span>

            <input
                id="password"
                type="password"
                name="password"
                class="@error('password') is-invalid @enderror"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
            >

            <button
                type="button"
                class="password-toggle"
                id="togglePassword"
                aria-label="Show password"
                title="Show password"
            >

                <i
                    class="bi bi-eye"
                    id="passwordIcon"
                ></i>

            </button>

        </div>

        @error('password')

            <div class="login-error">

                <i class="bi bi-exclamation-circle"></i>

                {{ $message }}

            </div>

        @enderror

    </div>


    {{-- =====================================================
         REMEMBER ME / SECURITY
    ====================================================== --}}

    <div class="login-options">

        <label class="remember-option">

            <input
                type="checkbox"
                name="remember"
                id="remember"
                {{ old('remember') ? 'checked' : '' }}
            >

            <span>
                Remember me
            </span>

        </label>

        <span class="secure-login">

            <i class="bi bi-shield-check"></i>

            Secure login

        </span>

    </div>


    {{-- =====================================================
         LOGIN BUTTON
    ====================================================== --}}

    <button
        type="submit"
        class="login-button"
    >

        <i class="bi bi-box-arrow-in-right"></i>

        <span>
            Sign In
        </span>

    </button>

</form>


{{-- =========================================================
SECURITY NOTICE
========================================================= --}}

<div class="login-security">

    <i class="bi bi-shield-lock-fill"></i>

    <span>
        Your account information is protected.
        Authorized CPC personnel only.
    </span>

</div>

@endsection


{{-- =========================================================
LOGIN PAGE STYLES
========================================================= --}}

@push('styles')

<style>

    /* =========================================================
       LOGIN HEADER
    ========================================================= */

    .login-header {
        display: flex;
        align-items: center;
        gap: 14px;

        margin-bottom: 24px;
        padding-bottom: 20px;

        border-bottom: 1px solid #edf0f3;
    }

    .login-icon {
        width: 48px;
        height: 48px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #eff6ff;
        color: #0d6efd;

        font-size: 21px;
    }

    .login-header h2 {
        margin: 0;

        color: #172033;

        font-size: 21px;
        font-weight: 700;

        letter-spacing: -0.2px;
    }

    .login-header p {
        margin: 4px 0 0;

        color: #6b7280;

        font-size: 12.5px;
        line-height: 1.5;
    }


    /* =========================================================
       FORM FIELDS
    ========================================================= */

    .login-field {
        margin-bottom: 17px;
    }

    .login-field label {
        display: block;

        margin-bottom: 6px;

        color: #374151;

        font-size: 13px;
        font-weight: 600;
    }

    .password-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 6px;
    }

    .password-label-row label {
        margin-bottom: 0;
    }

    .forgot-password {
        color: #0d6efd !important;

        font-size: 12px;
        font-weight: 600;

        text-decoration: none !important;
    }

    .forgot-password:hover {
        color: #084298 !important;

        text-decoration: underline !important;
    }


    /* =========================================================
       INPUT GROUP
    ========================================================= */

    .login-input-group {
        display: flex;

        width: 100%;

        min-height: 45px;

        border: 1px solid #dfe3e8;

        border-radius: 6px;

        overflow: hidden;

        background: #ffffff;

        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease;
    }

    .login-input-group:focus-within {
        border-color: #0d6efd;

        box-shadow:
            0 0 0 3px rgba(13, 110, 253, 0.09);
    }

    .login-input-icon {
        width: 44px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #f8fafc;

        border-right: 1px solid #e5e7eb;

        color: #64748b;

        font-size: 15px;
    }

    .login-input-group:focus-within .login-input-icon {
        color: #0d6efd;

        background: #f1f6ff;
    }

    .login-input-group input {
        width: 100%;

        min-width: 0;

        border: 0;
        outline: 0;

        padding: 9px 12px;

        color: #111827;

        background: #ffffff;

        font-size: 13.5px;
    }

    .login-input-group input::placeholder {
        color: #9ca3af;
    }

    .login-input-group input:focus {
        outline: none;
    }


    /* =========================================================
       PASSWORD TOGGLE
    ========================================================= */

    .password-toggle {
        width: 44px;

        flex-shrink: 0;

        border: 0;

        border-left: 1px solid #e5e7eb;

        background: #f8fafc;

        color: #64748b;

        cursor: pointer;

        transition:
            color 0.15s ease,
            background-color 0.15s ease;
    }

    .password-toggle:hover {
        color: #0d6efd;

        background: #f1f6ff;
    }

    .password-toggle:focus {
        outline: none;

        color: #0d6efd;

        background: #f1f6ff;
    }


    /* =========================================================
       VALIDATION ERROR
    ========================================================= */

    .login-error {
        display: flex;
        align-items: center;
        gap: 5px;

        margin-top: 5px;

        color: #dc3545;

        font-size: 11.5px;
    }


    /* =========================================================
       REMEMBER / SECURITY
    ========================================================= */

    .login-options {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin: 2px 0 18px;
    }

    .remember-option {
        display: flex;
        align-items: center;
        gap: 7px;

        margin: 0;

        color: #4b5563;

        font-size: 12.5px;

        cursor: pointer;
    }

    .remember-option input {
        width: 15px;
        height: 15px;

        margin: 0;

        accent-color: #0d6efd;

        cursor: pointer;
    }

    .secure-login {
        display: flex;
        align-items: center;
        gap: 5px;

        color: #64748b;

        font-size: 11.5px;
    }

    .secure-login i {
        color: #16a34a;
    }


    /* =========================================================
       SIGN IN BUTTON
    ========================================================= */

    .login-button {
        width: 100%;

        min-height: 45px;

        display: flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        border: 1px solid #0d6efd;

        border-radius: 6px;

        background: #0d6efd;

        color: #ffffff;

        font-size: 13.5px;
        font-weight: 600;

        cursor: pointer;

        box-shadow:
            0 4px 10px rgba(13, 110, 253, 0.14);

        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            transform 0.15s ease,
            box-shadow 0.15s ease;
    }

    .login-button:hover {
        background: #0b5ed7;

        border-color: #0a58ca;

        transform: translateY(-1px);

        box-shadow:
            0 6px 14px rgba(13, 110, 253, 0.19);
    }

    .login-button:active {
        transform: translateY(0);
    }


    /* =========================================================
       SECURITY NOTICE
    ========================================================= */

    .login-security {
        display: flex;
        align-items: flex-start;
        gap: 8px;

        margin-top: 20px;
        padding-top: 15px;

        border-top: 1px solid #edf0f3;

        color: #8a94a3;

        font-size: 10.5px;

        line-height: 1.5;
    }

    .login-security i {
        flex-shrink: 0;

        margin-top: 1px;

        color: #16a34a;
    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 575.98px) {

        .login-header {
            gap: 11px;

            margin-bottom: 20px;
            padding-bottom: 17px;
        }

        .login-icon {
            width: 43px;
            height: 43px;

            font-size: 19px;
        }

        .login-header h2 {
            font-size: 19px;
        }

        .login-header p {
            font-size: 11.5px;
        }

        .login-field {
            margin-bottom: 15px;
        }

        .login-options {
            margin-bottom: 16px;
        }

        .secure-login {
            display: none;
        }

        .login-security {
            font-size: 10px;
        }

    }

</style>

@endpush


{{-- =========================================================
PASSWORD VISIBILITY SCRIPT
========================================================= --}}

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const password =
            document.getElementById('password');

        const togglePassword =
            document.getElementById('togglePassword');

        const passwordIcon =
            document.getElementById('passwordIcon');


        if (
            !password ||
            !togglePassword ||
            !passwordIcon
        ) {
            return;
        }


        togglePassword.addEventListener('click', function () {

            const isPassword =
                password.type === 'password';


            password.type =
                isPassword ? 'text' : 'password';


            passwordIcon.classList.toggle(
                'bi-eye',
                !isPassword
            );

            passwordIcon.classList.toggle(
                'bi-eye-slash',
                isPassword
            );


            togglePassword.setAttribute(
                'aria-label',
                isPassword
                    ? 'Hide password'
                    : 'Show password'
            );


            togglePassword.setAttribute(
                'title',
                isPassword
                    ? 'Hide password'
                    : 'Show password'
            );

        });

    });
</script>

@endpush