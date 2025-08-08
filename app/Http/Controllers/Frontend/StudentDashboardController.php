<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Models\LessonProgress;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class StudentDashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index(Request $request): Response
    {
        // Get the authenticated student
        $student = $request->user()->student;
        
        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        // Get student's enrollments with course and progress data
        $enrollments = CourseEnrollment::with([
            'course.category',
            'course.instructor',
            'payment'
        ])
        ->where('student_id', $student->id)
        ->latest('enrolled_at')
        ->get();

        return Inertia::render('Frontend/StudentDashboard', [
            'student' => [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'email' => $student->user->email,
                'phone' => $student->phone,
                'profile_picture' => $student->profile_picture,
                'date_of_birth' => $student->date_of_birth,
                'address' => $student->address,
                'city' => $student->city,
                'district' => $student->district,
                'country' => $student->country,
                'is_active' => $student->is_active,
                'is_verified' => $student->is_verified,
            ],
            'enrollments' => $enrollments->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'course' => [
                        'id' => $enrollment->course->id,
                        'title' => $enrollment->course->title,
                        'slug' => $enrollment->course->slug,
                        'thumbnail_image' => $enrollment->course->thumbnail_image,
                        'image' => $enrollment->course->thumbnail_image ? Storage::url($enrollment->course->thumbnail_image) : null,
                        'category' => $enrollment->course->category?->name,
                        'instructor' => $enrollment->course->instructor?->full_name,
                    ],
                    'enrolled_at' => $enrollment->enrolled_at,
                    'enrollment_type' => $enrollment->enrollment_type,
                    'payment_status' => $enrollment->payment_status,
                    'progress_percentage' => $enrollment->progress_percentage,
                    'lessons_completed' => $enrollment->lessons_completed,
                    'is_completed' => $enrollment->is_completed,
                    'is_active' => $enrollment->is_active,
                ];
            }),
            // Defer heavy computations
            'stats' => Inertia::defer(fn() => $this->getStudentStats($student, $enrollments)),
            'recentActivities' => Inertia::defer(fn() => $this->getRecentActivities($student, $enrollments)),
            'certificates' => Inertia::defer(fn() => $this->getCertificates($enrollments)),
        ]);
    }

    /**
     * Update student profile.
     */
    public function updateProfile(Request $request)
    {
        $student = $request->user()->student;
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $student->update($request->only([
            'full_name',
            'phone',
            'date_of_birth',
            'address',
            'city',
            'district',
            'country',
        ]));

        // Also update the user's name to keep it in sync
        if ($request->has('full_name')) {
            $request->user()->update([
                'name' => $request->full_name
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change student password.
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Get student statistics with caching
     */
    private function getStudentStats($student, $enrollments): array
    {
        return Cache::remember("student_stats_{$student->id}", 600, function() use ($student, $enrollments) {
            $enrollmentIds = $enrollments->pluck('id');
            
            return [
                'total_enrollments' => $enrollments->count(),
                'active_enrollments' => $enrollments->where('is_active', true)->count(),
                'completed_courses' => $enrollments->where('is_completed', true)->count(),
                'total_lessons_completed' => LessonProgress::whereIn('enrollment_id', $enrollmentIds)
                    ->where('is_completed', true)
                    ->count(),
                'total_study_hours' => $this->calculateStudyHours($enrollmentIds),
                'average_progress' => $this->calculateAverageProgress($enrollments),
            ];
        });
    }

    /**
     * Get recent activities with caching
     */
    private function getRecentActivities($student, $enrollments): array
    {
        return Cache::remember("student_activities_{$student->id}", 300, function() use ($enrollments) {
            $enrollmentIds = $enrollments->pluck('id');
            
            return LessonProgress::with(['lesson.module.course', 'enrollment'])
                ->whereIn('enrollment_id', $enrollmentIds)
                ->where('is_completed', true)
                ->latest('completed_at')
                ->take(10)
                ->get()
                ->map(function ($progress) {
                    return [
                        'id' => $progress->id,
                        'title' => $progress->lesson->title,
                        'course' => $progress->lesson->module->course->title,
                        'date' => $progress->completed_at ? $progress->completed_at->diffForHumans() : 'In Progress',
                        'type' => 'lesson_completed'
                    ];
                })->toArray();
        });
    }

    /**
     * Get certificates data - based on completed courses
     */
    private function getCertificates($enrollments): array
    {
        // Return certificates for completed courses
        return $enrollments
            ->where('is_completed', true)
            ->where('payment_status', 'completed')
            ->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'certificate_number' => 'IOA-' . now()->format('Y') . '-' . str_pad($enrollment->id, 3, '0', STR_PAD_LEFT),
                    'course' => $enrollment->course->title,
                    'date' => $enrollment->updated_at->format('d M Y'),
                    'course_slug' => $enrollment->course->slug,
                    'verification_code' => 'CERT' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'download_url' => route('certificates.preview'), // Points to preview route
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Calculate total study hours for a student.
     */
    private function calculateStudyHours($enrollmentIds): int
    {
        // This is a simplified calculation
        // In a real application, you'd track actual study time
        $completedLessons = LessonProgress::whereIn('enrollment_id', $enrollmentIds)
            ->where('is_completed', true)
            ->count();
        
        // Assume average 30 minutes per lesson
        return round($completedLessons * 0.5);
    }

    /**
     * Calculate average progress across all enrollments.
     */
    private function calculateAverageProgress($enrollments): int
    {
        if ($enrollments->isEmpty()) {
            return 0;
        }

        return round($enrollments->avg('progress_percentage'));
    }
} 