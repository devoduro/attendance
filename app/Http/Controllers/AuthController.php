<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Update last login timestamp
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();
            
            // Redirect based on user role
            if ($user->isAdmin()) {
                return redirect()->intended(route('dashboard'));
            } elseif ($user->isTeacher()) {
                return redirect()->intended(route('dashboard'));
            } else {
                return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show the form for changing password.
     *
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * Change the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->password_changed_at = now();
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Show the form for requesting a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the password reset form.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'password_changed_at' => now(),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Show the two-factor authentication setup form.
     *
     * @return \Illuminate\View\View
     */
    public function showTwoFactorSetupForm()
    {
        return view('auth.two-factor-setup');
    }

    /**
     * Enable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enableTwoFactor(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $user->phone_number = $request->phone_number;
        $user->two_factor_enabled = true;
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Two-factor authentication enabled successfully.');
    }

    /**
     * Disable two-factor authentication for the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disableTwoFactor()
    {
        $user = Auth::user();
        $user->two_factor_enabled = false;
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Two-factor authentication disabled successfully.');
    }

    /**
     * Show the two-factor authentication challenge form.
     *
     * @return \Illuminate\View\View
     */
    public function showTwoFactorChallengeForm()
    {
        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the two-factor authentication code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyTwoFactorCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        // In a real application, you would verify the code against a service like Twilio Verify
        // For this example, we'll just check if the code is '123456' (for demonstration purposes)
        if ($request->code === '123456') {
            $request->session()->put('two_factor_authenticated', true);
            
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'code' => 'The provided code is invalid.',
        ]);
    }
}
