<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(Request $request): Response
    {
        // Set intended URL in session if provided
        if ($request->has('intended')) {
            $request->session()->put('url.intended', $request->query('intended'));
        }
        
        Inertia::encryptHistory();
        
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create student profile
        Student::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            // Add other fields as needed, or set defaults/nulls
        ]);

        // Assign student role
        UserRole::create([
            'user_id' => $user->id,
            'role_type' => 'student',
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Redirect to email verification notice after registration
        return redirect()->route('verification.notice');
    }
}
