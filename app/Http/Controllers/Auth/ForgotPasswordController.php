<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * OTP validity period.
     *
     * 1 minute = 60 seconds.
     */
    private const OTP_EXPIRY_MINUTES = 1;

    /**
     * Password reset validity period after OTP verification.
     *
     * This is separate from the OTP expiry.
     */
    private const VERIFIED_RESET_MINUTES = 10;

    /**
     * Show the forgot password page.
     */
    public function showLinkRequestForm(
        Request $request
    ): View|RedirectResponse {

        $codeId = $request->session()->get(
            'password_reset_code_id'
        );

        /*
         * No active OTP session.
         */
        if (! $codeId) {

            return view('auth.forgot-password', [
                'step' => 'details',
            ]);
        }

        /*
         * Get the current OTP.
         */
        $resetCode = PasswordResetCode::find($codeId);

        /*
         * If the OTP no longer exists, start again.
         */
        if (! $resetCode) {

            $request->session()->forget([
                'password_reset_code_id',
                'password_reset_verified_id',
                'password_reset_delivery_method',
                'password_reset_phone',
            ]);

            return view('auth.forgot-password', [
                'step' => 'details',
            ]);
        }

        /*
         * If the OTP has already been verified,
         * send the user to the password reset page.
         */
        if ($resetCode->verified_at) {

            $verifiedId = $request->session()->get(
                'password_reset_verified_id'
            );

            if ($verifiedId === $resetCode->id) {

                return redirect()
                    ->route('password.reset.phone');

            }

            /*
             * Invalid verification session.
             */
            $request->session()->forget([
                'password_reset_code_id',
                'password_reset_verified_id',
                'password_reset_delivery_method',
                'password_reset_phone',
            ]);

            return view('auth.forgot-password', [
                'step' => 'details',
            ]);
        }

        /*
         * IMPORTANT:
         *
         * Even if the OTP has expired, keep the user
         * on the OTP page so the Resend OTP button remains
         * available.
         */
        return view('auth.forgot-password', [
            'step' => 'code',

            /*
             * Send the actual backend expiration timestamp
             * to JavaScript.
             */
            'otpExpiresAt' =>
                $resetCode->expires_at->timestamp * 1000,
        ]);
    }


    /**
     * Send password reset OTP to the registered phone number.
     *
     * This method is also used when the user clicks
     * "Resend OTP".
     */
    public function sendResetCode(
        Request $request
    ): RedirectResponse {

        $data = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^\+[1-9][0-9]{7,14}$/',
            ],
        ]);

        /*
         * Password reset is phone OTP only.
         */
        $deliveryMethod = 'phone';

        /*
         * Find registered user.
         */
        $user = $this->findUserByPhone(
            $data['phone']
        );

        if (! $user) {

            return back()
                ->withErrors([
                    'phone' =>
                        'We could not find an account with that registered phone number.',
                ])
                ->withInput();
        }

        /*
         * ---------------------------------------------------------
         * INVALIDATE PREVIOUS OTP
         * ---------------------------------------------------------
         *
         * Any previous OTP belonging to this reset session
         * becomes unusable immediately.
         */
        $previousCodeId =
            $request->session()->get(
                'password_reset_code_id'
            );

        if ($previousCodeId) {

            PasswordResetCode::whereKey(
                $previousCodeId
            )->delete();
        }

        /*
         * Also remove any previously verified reset ID.
         */
        $request->session()->forget(
            'password_reset_verified_id'
        );

        /*
         * Generate a secure 6-digit OTP.
         */
        $code = (string) random_int(
            100000,
            999999
        );

        /*
         * Create a completely new OTP.
         *
         * IMPORTANT:
         * This OTP expires exactly 1 minute after creation.
         */
        $resetCode = PasswordResetCode::create([
            'email' =>
                $user->email,

            'phone' =>
                $user->phone,

            'delivery_method' =>
                $deliveryMethod,

            'code_hash' =>
                Hash::make($code),

            'expires_at' =>
                now()->addMinutes(
                    self::OTP_EXPIRY_MINUTES
                ),

            'attempts' =>
                0,

            'verified_at' =>
                null,
        ]);

        /*
         * Store the new OTP ID.
         */
        $request->session()->put(
            'password_reset_code_id',
            $resetCode->id
        );

        /*
         * Store delivery method.
         */
        $request->session()->put(
            'password_reset_delivery_method',
            $deliveryMethod
        );

        /*
         * Store phone number.
         *
         * This allows Resend OTP to continue using
         * the registered phone number.
         */
        $request->session()->put(
            'password_reset_phone',
            $user->phone
        );

        /*
         * Send OTP.
         */
        $this->sendCode(
            $user,
            $code
        );

        return redirect()
            ->route('password.request')
            ->with(
                'status',
                'A new verification code was sent to your registered phone number. It expires in 1 minute.'
            );
    }


    /**
     * Verify the password reset OTP.
     */
    public function verifyCode(
        Request $request
    ): RedirectResponse {

        $data = $request->validate([
            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        /*
         * Get the currently active OTP.
         */
        $resetCode = PasswordResetCode::find(
            $request->session()->get(
                'password_reset_code_id'
            )
        );

        /*
         * ---------------------------------------------------------
         * BACKEND OTP VALIDATION
         * ---------------------------------------------------------
         *
         * JavaScript is NOT trusted here.
         *
         * The database expiration time is authoritative.
         */
        if (! $resetCode) {

            return back()
                ->withErrors([
                    'code' =>
                        'This OTP is invalid or expired. Please request a new code.',
                ]);
        }

        /*
         * Already verified.
         */
        if ($resetCode->verified_at) {

            return back()
                ->withErrors([
                    'code' =>
                        'This OTP has already been used. Please request a new code.',
                ]);
        }

        /*
         * OTP expired.
         */
        if ($resetCode->expires_at->isPast()) {

            return back()
                ->withErrors([
                    'code' =>
                        'This OTP has expired. Please request a new code.',
                ]);
        }

        /*
         * Maximum attempts reached.
         */
        if ($resetCode->attempts >= 5) {

            return back()
                ->withErrors([
                    'code' =>
                        'Too many incorrect attempts. Please request a new OTP.',
                ]);
        }

        /*
         * Check OTP value.
         */
        if (! Hash::check(
            $data['code'],
            $resetCode->code_hash
        )) {

            $resetCode->increment(
                'attempts'
            );

            return back()
                ->withErrors([
                    'code' =>
                        'The OTP you entered is incorrect.',
                ]);
        }

        /*
         * ---------------------------------------------------------
         * OTP VERIFIED
         * ---------------------------------------------------------
         */
        $resetCode->update([
            'verified_at' => now(),
        ]);

        /*
         * Store verified reset ID.
         */
        $request->session()->put(
            'password_reset_verified_id',
            $resetCode->id
        );

        /*
         * OTP verification successful.
         */
        return redirect()
            ->route('password.reset.phone');
    }


    /**
     * Show the new password form.
     */
    public function showResetForm(
        Request $request
    ): View|RedirectResponse {

        $verifiedId =
            $request->session()->get(
                'password_reset_verified_id'
            );

        if (! $verifiedId) {

            return redirect()
                ->route('password.request');
        }

        /*
         * Make sure the verified OTP still exists
         * and has actually been verified.
         */
        $resetCode = PasswordResetCode::whereKey(
            $verifiedId
        )
            ->whereNotNull('verified_at')
            ->first();

        if (! $resetCode) {

            $request->session()->forget([
                'password_reset_code_id',
                'password_reset_verified_id',
                'password_reset_delivery_method',
                'password_reset_phone',
            ]);

            return redirect()
                ->route('password.request');
        }

        /*
         * After OTP verification, allow 10 minutes
         * to complete the password reset.
         */
        if (
            $resetCode->verified_at
                ->addMinutes(
                    self::VERIFIED_RESET_MINUTES
                )
                ->isPast()
        ) {

            $request->session()->forget([
                'password_reset_code_id',
                'password_reset_verified_id',
                'password_reset_delivery_method',
                'password_reset_phone',
            ]);

            return redirect()
                ->route('password.request')
                ->withErrors([
                    'code' =>
                        'Your password reset session expired. Please request a new OTP.',
                ]);
        }

        return view(
            'auth.reset-password',
            [
                'phoneReset' =>
                    true,

                'deliveryMethod' =>
                    'phone',
            ]
        );
    }


    /**
     * Reset the user's password.
     */
    public function resetPassword(
        Request $request
    ): RedirectResponse {

        $data = $request->validate([
            'password' => [
                'required',
                'confirmed',

                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        /*
         * Get the OTP that was successfully verified.
         */
        $resetCode = PasswordResetCode::whereKey(
            $request->session()->get(
                'password_reset_verified_id'
            )
        )
            ->whereNotNull('verified_at')
            ->first();

        /*
         * ---------------------------------------------------------
         * VERIFIED RESET SESSION EXPIRY
         * ---------------------------------------------------------
         *
         * IMPORTANT:
         *
         * The OTP itself expires after 1 minute.
         *
         * Once the OTP has been successfully verified,
         * the user gets 10 minutes to enter the new password.
         */
        if (
            ! $resetCode ||
            $resetCode->verified_at
                ->addMinutes(
                    self::VERIFIED_RESET_MINUTES
                )
                ->isPast()
        ) {

            $request->session()->forget([
                'password_reset_code_id',
                'password_reset_verified_id',
                'password_reset_delivery_method',
                'password_reset_phone',
            ]);

            return redirect()
                ->route('password.request')
                ->withErrors([
                    'code' =>
                        'Your password reset session expired. Please request a new OTP.',
                ]);
        }

        /*
         * Find the user connected to this reset request.
         */
        $user = User::where(
            'email',
            $resetCode->email
        )
            ->where(
                'phone',
                $resetCode->phone
            )
            ->first();

        if (! $user) {

            return redirect()
                ->route('password.request')
                ->withErrors([
                    'phone' =>
                        'The account could not be found.',
                ]);
        }

        /*
         * User.php automatically hashes the password.
         */
        $user->forceFill([
            'password' =>
                $data['password'],

            'remember_token' =>
                Str::random(60),
        ])->save();

        /*
         * OTP has served its purpose.
         */
        $resetCode->delete();

        /*
         * Clear all password-reset session data.
         */
        $request->session()->forget([
            'password_reset_code_id',
            'password_reset_verified_id',
            'password_reset_delivery_method',
            'password_reset_phone',
        ]);

        /*
         * Password successfully changed.
         */
        return redirect()
            ->route('login')
            ->with(
                'status',
                'Your password has been reset successfully. You can now sign in.'
            );
    }


    /**
     * Find a user using the registered phone number.
     */
    private function findUserByPhone(
        string $phone
    ): ?User {

        return User::where(
            'phone',
            $phone
        )->first();
    }


    /**
     * Send the OTP to the user's registered phone number.
     */
    private function sendCode(
        User $user,
        string $code
    ): void {

        $message =
            "CPC Inventory password reset OTP: {$code}. "
            . "This code expires in 1 minute.";

        $webhook = config(
            'services.sms.webhook_url'
        );

        /*
         * Production SMS service.
         */
        if ($webhook) {

            try {

                Http::post(
                    $webhook,
                    [
                        'to' =>
                            $user->phone,

                        'message' =>
                            $message,
                    ]
                )->throw();

                Log::info(
                    'Password reset OTP sent successfully.',
                    [
                        'user_id' =>
                            $user->id,
                    ]
                );

                return;

            } catch (\Throwable $exception) {

                Log::error(
                    'Password reset SMS delivery failed.',
                    [
                        'user_id' =>
                            $user->id,

                        'error' =>
                            $exception->getMessage(),
                    ]
                );
            }
        }

        /*
         * Local development fallback.
         *
         * Check storage/logs/laravel.log
         * during local testing.
         */
        Log::info(
            'Password reset OTP generated for local development.',
            [
                'user_id' =>
                    $user->id,

                'phone' =>
                    $user->phone,

                'code' =>
                    $code,
            ]
        );
    }
}