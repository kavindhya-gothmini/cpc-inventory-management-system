@extends('layouts.auth')

@section('title', 'Forgot Password - CPC Inventory')

@section('content')

{{-- =========================================================
FORGOT PASSWORD / OTP VERIFICATION
========================================================= --}}

@if (($step ?? 'details') === 'details')

    {{-- =====================================================
         STEP 1 — ENTER PHONE NUMBER
    ====================================================== --}}

    <div class="text-center mb-4">

        <div class="auth-page-icon">
            <i class="bi bi-key-fill"></i>
        </div>

        <h4 class="fw-bold mb-2">
            Forgot Your Password?
        </h4>

        <p class="text-muted small mb-0">
            Enter your registered phone number to receive a
            verification code.
        </p>

    </div>


    {{-- Security Notice --}}
    <div class="auth-info-box mb-4">

        <i class="bi bi-shield-check"></i>

        <div>
            <strong>Secure password recovery</strong>

            <div>
                A verification code will be sent to your
                registered CPC mobile number.
            </div>
        </div>

    </div>


    {{-- =====================================================
         PHONE NUMBER FORM
    ====================================================== --}}

    <form
        method="POST"
        action="{{ route('password.email') }}"
    >

        @csrf

        <div class="mb-4">

            <label
                for="phone"
                class="form-label"
            >
                Registered Phone Number
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-telephone"></i>
                </span>

                <input
                    id="phone"
                    type="tel"
                    class="form-control @error('phone') is-invalid @enderror"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="+94771234567"
                    inputmode="tel"
                    autocomplete="tel"
                    required
                    autofocus
                >

            </div>

            @error('phone')

                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>

            @enderror

            <div class="form-text mt-2">

                <i class="bi bi-info-circle me-1"></i>

                Use the phone number registered with your CPC account.

            </div>

        </div>


        {{-- Delivery Method --}}
        <input
            type="hidden"
            name="delivery_method"
            value="phone"
        >


        {{-- Send OTP --}}
        <div class="d-grid mb-3">

            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="bi bi-send me-2"></i>

                Send Verification Code

            </button>

        </div>


        {{-- Back to Login --}}
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


@else

    {{-- =====================================================
         STEP 2 — VERIFY OTP
    ====================================================== --}}

    <div class="text-center mb-4">

        <div class="auth-page-icon">

            <i class="bi bi-shield-lock-fill"></i>

        </div>

        <h4 class="fw-bold mb-2">
            Verify Your Phone
        </h4>

        <p class="text-muted small mb-0">
            Enter the 6-digit verification code sent to your
            registered mobile number.
        </p>

    </div>


    {{-- =====================================================
         OTP STATUS BOX
    ====================================================== --}}

    <div class="otp-status-box mb-4">

        <div class="otp-status-icon">

            <i class="bi bi-phone"></i>

        </div>

        <div class="flex-grow-1">

            <div class="otp-status-title">
                Verification code sent
            </div>

            <div class="otp-status-text">

                Your verification code is valid for
                <strong>1 minute</strong>.

            </div>

        </div>

    </div>


    {{-- =====================================================
         OTP FORM
    ====================================================== --}}

    <form
        method="POST"
        action="{{ route('password.verify') }}"
        id="otpVerificationForm"
    >

        @csrf

        {{-- OTP --}}
        <div class="mb-3">

            <label
                for="code"
                class="form-label"
            >
                Verification Code
            </label>

            <div class="input-group">

                <span class="input-group-text">

                    <i class="bi bi-shield-lock"></i>

                </span>

                <input
                    id="code"
                    type="text"
                    class="form-control otp-input @error('code') is-invalid @enderror"
                    name="code"
                    inputmode="numeric"
                    maxlength="6"
                    minlength="6"
                    pattern="[0-9]{6}"
                    autocomplete="one-time-code"
                    placeholder="Enter 6-digit code"
                    required
                    autofocus
                >

            </div>

            @error('code')

                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>

            @enderror

        </div>


        {{-- =================================================
             COUNTDOWN
        ================================================== --}}

        <div
            class="otp-countdown text-center mb-4"
            id="otpCountdownArea"
        >

            <div class="small text-muted">

                <i class="bi bi-clock me-1"></i>

                Code expires in

                <strong
                    id="otpCountdown"
                    class="text-primary"
                >
                    --:--
                </strong>

            </div>

        </div>


        {{-- Verify Button --}}
        <div class="d-grid mb-3">

            <button
                type="submit"
                class="btn btn-primary"
                id="verifyOtpButton"
            >

                <i class="bi bi-check-circle me-2"></i>

                Verify Code

            </button>

        </div>

    </form>


    {{-- =====================================================
         RESEND OTP
    ====================================================== --}}

    <div class="text-center mb-3">

        <div class="small text-muted mb-2">
            Didn't receive the code?
        </div>

        <form
            method="POST"
            action="{{ route('password.email') }}"
            id="resendOtpForm"
        >

            @csrf

            <input
                type="hidden"
                name="phone"
                value="{{ session('password_reset_phone', old('phone')) }}"
            >

            <input
                type="hidden"
                name="delivery_method"
                value="phone"
            >

            <button
                type="submit"
                class="btn btn-link p-0 small fw-semibold"
                id="resendOtpButton"
                disabled
            >

                <i class="bi bi-arrow-clockwise me-1"></i>

                Resend OTP

            </button>

        </form>

        <div
            id="resendCountdown"
            class="small text-muted mt-1"
        >

            Resend available in

            <strong id="resendTimer">
                --:--
            </strong>

        </div>

    </div>


    {{-- =====================================================
         USE DIFFERENT PHONE
    ====================================================== --}}

    <div class="text-center pt-2">

        <a
            href="{{ route('password.request') }}"
            class="small"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Use a Different Phone Number

        </a>

    </div>

@endif


{{-- =========================================================
PAGE-SPECIFIC STYLES
========================================================= --}}

@push('styles')

<style>

    /* =========================================================
       AUTH PAGE ICON
    ========================================================= */

    .auth-page-icon {

        width: 54px;
        height: 54px;

        margin: 0 auto 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #eff6ff;

        color: #0d6efd;

        font-size: 23px;

    }


    /* =========================================================
       INFORMATION BOX
    ========================================================= */

    .auth-info-box {

        display: flex;
        align-items: flex-start;
        gap: 10px;

        padding: 12px 14px;

        border: 1px solid #dbe5f0;

        border-radius: 7px;

        background: #f8fafc;

        color: #64748b;

        font-size: 12.5px;

        line-height: 1.5;

    }

    .auth-info-box > i {

        margin-top: 2px;

        color: #0d6efd;

        font-size: 16px;

    }

    .auth-info-box strong {

        display: block;

        margin-bottom: 2px;

        color: #374151;

        font-size: 13px;

    }


    /* =========================================================
       OTP STATUS
    ========================================================= */

    .otp-status-box {

        display: flex;
        align-items: center;
        gap: 11px;

        padding: 12px 14px;

        border: 1px solid #dbe5f0;

        border-radius: 7px;

        background: #f8fafc;

    }

    .otp-status-icon {

        width: 38px;
        height: 38px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #eff6ff;

        color: #0d6efd;

        font-size: 17px;

    }

    .otp-status-title {

        color: #374151;

        font-size: 13px;

        font-weight: 600;

    }

    .otp-status-text {

        margin-top: 2px;

        color: #6b7280;

        font-size: 11.5px;

    }


    /* =========================================================
       OTP INPUT
    ========================================================= */

    .otp-input {

        letter-spacing: 4px;

        font-size: 16px !important;

        font-weight: 600;

    }


    /* =========================================================
       COUNTDOWN
    ========================================================= */

    .otp-countdown {

        color: #64748b;

        font-size: 12px;

    }

    .otp-countdown.expired {

        color: #dc2626;

    }

    .otp-countdown.expired strong {

        color: #dc2626 !important;

    }


    /* =========================================================
       RESEND BUTTON
    ========================================================= */

    #resendOtpButton {

        color: #0d6efd;

        text-decoration: none;

        transition: all 0.2s ease;

    }

    #resendOtpButton:hover {

        color: #084298;

        text-decoration: underline;

    }

    #resendOtpButton:disabled {

        color: #9ca3af;

        cursor: not-allowed;

        text-decoration: none;

    }

    #resendCountdown {

        font-size: 11px;

    }


    /* =========================================================
       VERIFY BUTTON
    ========================================================= */

    #verifyOtpButton {

        transition: all 0.2s ease;

    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 575.98px) {

        .otp-status-box {

            padding: 11px 12px;

        }

        .otp-status-text {

            font-size: 11px;

        }

    }

</style>

@endpush


{{-- =========================================================
OTP COUNTDOWN + RESEND SCRIPT
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | OTP ELEMENTS
    |--------------------------------------------------------------------------
    */

    const otpCountdown =
        document.getElementById('otpCountdown');

    const otpCountdownArea =
        document.getElementById('otpCountdownArea');

    const resendOtpButton =
        document.getElementById('resendOtpButton');

    const resendTimer =
        document.getElementById('resendTimer');

    const resendCountdown =
        document.getElementById('resendCountdown');

    const otpInput =
        document.getElementById('code');


    /*
    |--------------------------------------------------------------------------
    | ACTUAL OTP EXPIRATION TIME
    |--------------------------------------------------------------------------
    |
    | This value comes from:
    |
    | $resetCode->expires_at->timestamp * 1000
    |
    | in ForgotPasswordController.
    |
    | This prevents the timer from resetting when the page is
    | refreshed.
    |
    */

    const otpExpiresAt =
        @json($otpExpiresAt ?? null);


    /*
    |--------------------------------------------------------------------------
    | Only run on OTP verification step
    |--------------------------------------------------------------------------
    */

    if (
        !otpCountdown ||
        !resendOtpButton ||
        !resendTimer ||
        !otpExpiresAt
    ) {

        return;

    }


    let countdownInterval = null;


    /*
    |--------------------------------------------------------------------------
    | Format countdown
    |--------------------------------------------------------------------------
    */

    function formatTime(seconds) {

        const minutes =
            Math.floor(seconds / 60);

        const secondsPart =
            seconds % 60;

        return (
            String(minutes).padStart(2, '0') +
            ':' +
            String(secondsPart).padStart(2, '0')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | OTP EXPIRED
    |--------------------------------------------------------------------------
    */

    function expireOtp() {

        if (countdownInterval) {

            clearInterval(
                countdownInterval
            );

        }


        /*
        | Show expired state.
        */

        otpCountdown.textContent =
            'Expired';

        otpCountdownArea.classList.add(
            'expired'
        );


        /*
        | Enable Resend OTP.
        */

        resendOtpButton.disabled =
            false;

        resendOtpButton.innerHTML =
            '<i class="bi bi-arrow-clockwise me-1"></i>' +
            'Resend OTP';


        resendCountdown.innerHTML =
            '<span class="text-danger">' +
            'The previous code has expired.' +
            '</span>';


        /*
        | Clear the old OTP input.
        */

        if (otpInput) {

            otpInput.value = '';

            otpInput.focus();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE COUNTDOWN
    |--------------------------------------------------------------------------
    */

    function updateCountdown() {

        const now =
            Date.now();


        const difference =
            otpExpiresAt - now;


        const remainingSeconds =
            Math.ceil(
                difference / 1000
            );


        /*
        | OTP has expired.
        */

        if (
            remainingSeconds <= 0
        ) {

            expireOtp();

            return;

        }


        /*
        | Display remaining time.
        */

        const formatted =
            formatTime(
                remainingSeconds
            );


        otpCountdown.textContent =
            formatted;

        resendTimer.textContent =
            formatted;

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL COUNTDOWN UPDATE
    |--------------------------------------------------------------------------
    */

    updateCountdown();


    /*
    |--------------------------------------------------------------------------
    | UPDATE EVERY SECOND
    |--------------------------------------------------------------------------
    */

    countdownInterval =
        setInterval(
            updateCountdown,
            1000
        );


    /*
    |--------------------------------------------------------------------------
    | RESEND OTP FORM
    |--------------------------------------------------------------------------
    */

    const resendForm =
        document.getElementById(
            'resendOtpForm'
        );


    if (resendForm) {

        resendForm.addEventListener(
            'submit',
            function () {

                /*
                | Prevent multiple clicks.
                */

                resendOtpButton.disabled =
                    true;


                /*
                | Show loading state.
                */

                resendOtpButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span>' +
                    'Sending...';

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | OTP INPUT — NUMBERS ONLY
    |--------------------------------------------------------------------------
    */

    if (otpInput) {

        otpInput.addEventListener(
            'input',
            function () {

                this.value =
                    this.value
                        .replace(/\D/g, '')
                        .slice(0, 6);

            }
        );

    }

});

</script>

@endpush

@endsection

