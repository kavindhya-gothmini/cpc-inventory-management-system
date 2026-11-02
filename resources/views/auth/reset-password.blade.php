@extends('layouts.auth')

@section('title', 'Reset Password - CPC Inventory')

@section('content')

{{-- =========================================================
     RESET PASSWORD INTRODUCTION
========================================================= --}}
<div class="text-center mb-4">

    <div
        class="mx-auto mb-3 d-flex align-items-center justify-content-center"
        style="
            width: 58px;
            height: 58px;
            border-radius: 15px;
            background: #eff6ff;
            color: #0d6efd;
            font-size: 26px;
        "
    >
        <i class="bi bi-shield-lock"></i>
    </div>

    <h4 class="fw-bold mb-2">
        Create New Password
    </h4>

    <p class="text-muted small mb-0">
        Create a new secure password for your
        CPC Inventory account.
    </p>

</div>


{{-- =========================================================
     SECURITY NOTICE
========================================================= --}}
<div
    class="mb-4 d-flex align-items-start gap-2"
    style="
        padding: 12px 14px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    "
>

    <i
        class="bi bi-shield-check mt-1"
        style="color: #22c55e;"
    ></i>

    <div>
        Choose a strong password that you have not used
        previously for this account.
    </div>

</div>


{{-- =========================================================
     RESET PASSWORD FORM
========================================================= --}}
<form
    method="POST"
    action="{{ route('password.update') }}"
>

    @csrf


    {{-- =====================================================
         NEW PASSWORD
    ====================================================== --}}
    <div class="mb-3">

        <label
            for="password"
            class="form-label"
        >
            New Password
        </label>

        <div class="input-group">

            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>

            <input
                id="password"
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                name="password"
                required
                autocomplete="new-password"
                autofocus
                placeholder="Create a new password"
            >

            <button
                type="button"
                class="btn btn-outline-secondary"
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

            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>

        @enderror

        {{-- Password Requirements --}}
        <div
            class="mt-2"
            style="
                padding: 10px 12px;
                border-radius: 8px;
                background: #f8fafc;
                border: 1px solid #edf0f4;
                color: #64748b;
                font-size: 11px;
                line-height: 1.7;
            "
        >

            <div class="fw-semibold mb-1" style="color: #475569;">
                Password requirements
            </div>

            <div>
                <i class="bi bi-check2 me-1"></i>
                Minimum 8 characters
            </div>

            <div>
                <i class="bi bi-check2 me-1"></i>
                Uppercase and lowercase letters
            </div>

            <div>
                <i class="bi bi-check2 me-1"></i>
                At least one number
            </div>

            <div>
                <i class="bi bi-check2 me-1"></i>
                At least one special character
            </div>

        </div>

    </div>


    {{-- =====================================================
         CONFIRM PASSWORD
    ====================================================== --}}
    <div class="mb-4">

        <label
            for="password_confirmation"
            class="form-label"
        >
            Confirm New Password
        </label>

        <div class="input-group">

            <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
            </span>

            <input
                id="password_confirmation"
                type="password"
                class="form-control"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Re-enter your new password"
            >

            <button
                type="button"
                class="btn btn-outline-secondary"
                id="toggleConfirmPassword"
                aria-label="Show confirmation password"
                title="Show confirmation password"
            >
                <i
                    class="bi bi-eye"
                    id="confirmPasswordIcon"
                ></i>
            </button>

        </div>

        {{-- Password Match Indicator --}}
        <div
            id="passwordMatchMessage"
            class="small mt-2"
            style="display: none;"
        ></div>

    </div>


    {{-- =====================================================
         RESET PASSWORD BUTTON
    ====================================================== --}}
    <div class="d-grid mb-3">

        <button
            type="submit"
            class="btn btn-primary"
        >
            <i class="bi bi-check-circle me-2"></i>
            Update Password
        </button>

    </div>


    {{-- =====================================================
         BACK TO LOGIN
    ====================================================== --}}
    <div class="text-center">

        <a
            href="{{ route('login') }}"
            class="small"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Login
        </a>

    </div>

</form>


{{-- =========================================================
     SECURITY INFORMATION
========================================================= --}}
<div
    class="mt-4 pt-3"
    style="
        border-top: 1px solid #edf0f4;
    "
>

    <div
        class="d-flex align-items-start gap-2"
        style="
            color: #94a3b8;
            font-size: 11px;
            line-height: 1.6;
        "
    >

        <i
            class="bi bi-shield-lock-fill mt-1"
            style="color: #22c55e;"
        ></i>

        <div>
            Your password is securely processed.
            Never share your password or verification code
            with another person.
        </div>

    </div>

</div>

@endsection


{{-- =========================================================
     PASSWORD VISIBILITY + MATCH SCRIPT
========================================================= --}}
@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | Password Toggle
        |--------------------------------------------------------------------------
        */

        function setupPasswordToggle(
            inputId,
            buttonId,
            iconId
        ) {

            const input =
                document.getElementById(inputId);

            const button =
                document.getElementById(buttonId);

            const icon =
                document.getElementById(iconId);

            if (!input || !button || !icon) {
                return;
            }

            button.addEventListener('click', function () {

                const isPassword =
                    input.getAttribute('type') === 'password';

                input.setAttribute(
                    'type',
                    isPassword ? 'text' : 'password'
                );

                icon.classList.toggle(
                    'bi-eye',
                    !isPassword
                );

                icon.classList.toggle(
                    'bi-eye-slash',
                    isPassword
                );

                button.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );

                button.setAttribute(
                    'title',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Initialize Password Toggles
        |--------------------------------------------------------------------------
        */

        setupPasswordToggle(
            'password',
            'togglePassword',
            'passwordIcon'
        );

        setupPasswordToggle(
            'password_confirmation',
            'toggleConfirmPassword',
            'confirmPasswordIcon'
        );


        /*
        |--------------------------------------------------------------------------
        | Password Match Indicator
        |--------------------------------------------------------------------------
        */

        const password =
            document.getElementById('password');

        const confirmation =
            document.getElementById('password_confirmation');

        const matchMessage =
            document.getElementById('passwordMatchMessage');


        function checkPasswordMatch() {

            if (!password.value || !confirmation.value) {

                matchMessage.style.display = 'none';

                return;
            }


            matchMessage.style.display = 'block';


            if (password.value === confirmation.value) {

                matchMessage.innerHTML =
                    '<i class="bi bi-check-circle-fill me-1"></i>' +
                    'Passwords match.';

                matchMessage.style.color = '#16a34a';

            } else {

                matchMessage.innerHTML =
                    '<i class="bi bi-exclamation-circle-fill me-1"></i>' +
                    'Passwords do not match.';

                matchMessage.style.color = '#dc2626';

            }

        }


        if (password && confirmation) {

            password.addEventListener(
                'input',
                checkPasswordMatch
            );

            confirmation.addEventListener(
                'input',
                checkPasswordMatch
            );

        }

    });
</script>

@endpush

