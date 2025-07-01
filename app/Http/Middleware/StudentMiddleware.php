<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Redirect to login page or show 403 error
            if ($request->expectsJson()) {
                abort(401, 'Unauthenticated.');
            }
            
            return redirect()->route('login')->with('error', 'Please log in to access the student dashboard.');
        }

        $user = Auth::user();
        
        // Debug: Log user info
        Log::info('StudentMiddleware: User authenticated', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'has_student_role' => $user->isStudent() ? 'yes' : 'no',
            'has_student_profile' => $user->student ? 'yes' : 'no'
        ]);

        // Check if user has student role (primary check)
        if (!$user->isStudent()) {
            abort(403, 'Access denied. Student role not found. User ID: ' . $user->id);
        }

        // Optional: Also ensure student profile exists (for data integrity)
        if (!$user->student) {
            Log::warning('User has student role but no student profile', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            
            // You can either:
            // 1. Create the missing student profile automatically
            // 2. Or block access until profile is created
            // For now, we'll block access
            abort(403, 'Access denied. Student profile not found. Please contact support.');
        }

        return $next($request);
    }
} 