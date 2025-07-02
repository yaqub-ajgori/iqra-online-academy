<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    /**
     * Display the admin login view.
     */
    public function showLoginForm(): Response
    {
        return Inertia::render('Admin/Auth/Login', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $email = $request->validated()['email'];
        $user = \App\Models\User::where('email', $email)->first();
        // Check if user exists and is admin
        if ($user && $user->isAdmin()) {
            // Check for lockout
            if ($user->isLocked()) {
                $unlockTime = $user->locked_until ? $user->locked_until->format('H:i:s') : 'later';
                throw ValidationException::withMessages([
                    'email' => __('Your account is locked due to too many failed login attempts. Please try again at :time.', ['time' => $unlockTime])
                ]);
            }
        }
        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            // Increment failed login attempts for admin
            if ($user && $user->isAdmin()) {
                $failKey = 'admin_login_fails:' . $user->id;
                $fails = Cache::increment($failKey);
                if ($fails == 1) {
                    Cache::put($failKey, 1, now()->addMinutes(16));
                }
                if ($fails >= 5) {
                    $user->is_locked = true;
                    $user->locked_until = Carbon::now()->addMinutes(15);
                    $user->save();
                    Cache::forget($failKey);
                }
            }
            throw $e;
        }
        // Check if the authenticated user is an admin
        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => trans('auth.admin_access_denied'),
            ]);
        }
        // On successful login, clear fail counter and lockout
        if ($user && $user->isAdmin()) {
            $failKey = 'admin_login_fails:' . $user->id;
            Cache::forget($failKey);
            if ($user->is_locked) {
                $user->is_locked = false;
                $user->locked_until = null;
                $user->save();
            }
        }
        session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Destroy an authenticated admin session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
