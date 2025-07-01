<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{


    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): Response
    {
        // Get current admin user
        $admin = $request->user();

        // Get overview statistics
        $stats = [
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'active_students' => Student::active()->count(),
            // 'verified_teachers' => Teacher::verified()->count(), // removed, no such scope
            // If you want to show active teachers, uncomment below:
            // 'active_teachers' => Teacher::where('is_active', true)->count(),
            
            'total_courses' => Course::count(),
            'published_courses' => Course::published()->count(),
            'featured_courses' => Course::featured()->count(),
            
            'total_enrollments' => CourseEnrollment::count(),
            'active_enrollments' => CourseEnrollment::active()->count(),
            'completed_enrollments' => CourseEnrollment::completed()->count(),
            
            'total_payments' => Payment::count(),
            'completed_payments' => Payment::completed()->count(),
            'pending_payments' => Payment::pending()->count(),
            'total_revenue' => Payment::completed()->sum('amount'),
        ];

        // Recent enrollments
        $recentEnrollments = CourseEnrollment::with(['student.user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        // Recent payments  
        $recentPayments = Payment::with(['student.user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        // Popular courses
        $popularCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'admin' => $admin,
            'stats' => $stats,
            'recentEnrollments' => $recentEnrollments,
            'recentPayments' => $recentPayments,
            'popularCourses' => $popularCourses,
        ]);
    }

    /**
     * Display the admin settings page.
     */
    public function settings(Request $request): Response
    {
        return Inertia::render('Admin/Settings', [
            'user' => $request->user(),
        ]);
    }

         /**
     * Update admin settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update name and email
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
