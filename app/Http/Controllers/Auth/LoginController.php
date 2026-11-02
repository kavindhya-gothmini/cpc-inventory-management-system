<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Employees go directly to the User Dashboard.
            if ($user->role === 'employee') {
                return redirect()->route('employee.dashboard');
            }

            return redirect()->route('main.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Validate login credentials before authentication.
     *
     * Locked accounts are prevented from logging in.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        $user = User::where(
            $this->username(),
            $request->input($this->username())
        )->first();

        if ($user && $user->isLocked()) {
            throw ValidationException::withMessages([
                $this->username() => [
                    'This account is locked. Please contact a Super Admin to unlock your account.',
                ],
            ]);
        }
    }

    /**
     * Handle a successful authentication.
     */
    protected function authenticated(Request $request, $user)
    {
        // Reset failed login attempts after successful login.
        if ($user->failed_login_attempts > 0) {
            $user->update([
                'failed_login_attempts' => 0,
            ]);
        }

        // Employees go directly to the User Dashboard.
        if ($user->role === 'employee') {
            return redirect()->route('employee.dashboard');
        }

        // Preserve the existing redirect for other account roles.
        return redirect()->intended(
            route('main.dashboard')
        );
    }

    /**
     * Handle a failed authentication attempt.
     *
     * Lock the account after five unsuccessful attempts.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where(
            $this->username(),
            $request->input($this->username())
        )->first();

        if ($user && ! $user->isLocked()) {
            $attempts = (int) $user->failed_login_attempts + 1;

            if ($attempts >= 5) {
                $user->update([
                    'failed_login_attempts' => $attempts,
                    'is_locked' => true,
                    'locked_at' => now(),
                ]);

                throw ValidationException::withMessages([
                    $this->username() => [
                        'Your account has been locked after 5 unsuccessful login attempts. Please contact a Super Admin to unlock your account.',
                    ],
                ]);
            }

            $user->update([
                'failed_login_attempts' => $attempts,
            ]);
        }

        throw ValidationException::withMessages([
            $this->username() => [
                trans('auth.failed'),
            ],
        ]);
    }

    /**
     * Default redirect destination after login.
     */
    protected $redirectTo = '/main-dashboard';
}
