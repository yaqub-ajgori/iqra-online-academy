<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class CertificateController extends Controller
{
    /**
     * Show certificate preview for logged-in student.
     */
    public function preview($courseSlug = null)
    {
        // Check if user is authenticated and is a student
        $user = auth()->user();
        if (!$user || !$user->student) {
            // Show sample certificate for non-authenticated users
            $certificate = (object) [
                'certificate_number' => 'IOA-' . now()->format('Y') . '-SAMPLE-001',
                'verification_code' => 'SAMPLE' . strtoupper(\Illuminate\Support\Str::random(6)),
                'student_name' => 'Sample Student Name',
                'course_title' => 'Sample Course Title',
                'course_description' => 'This is a sample certificate for demonstration purposes.',
                'instructors' => ['Sample Instructor'],
                'completion_date' => now()->subDays(7),
                'issue_date' => now(),
                'expiry_date' => null,
                'is_verified' => true,
                'is_revoked' => false,
            ];
            return view('certificates.template', compact('certificate'));
        }

        $student = $user->student;
        
        // If course slug is provided, find that specific completed enrollment
        if ($courseSlug) {
            $completedEnrollment = \App\Models\CourseEnrollment::where('student_id', $student->id)
                ->where('is_completed', true)
                ->where('payment_status', 'completed')
                ->whereHas('course', function($query) use ($courseSlug) {
                    $query->where('slug', $courseSlug);
                })
                ->with('course')
                ->first();
                
            if (!$completedEnrollment) {
                abort(404, 'Certificate not found. You must complete the course to view its certificate.');
            }
        } else {
            // Get the student's first completed course enrollment
            $completedEnrollment = \App\Models\CourseEnrollment::where('student_id', $student->id)
                ->where('is_completed', true)
                ->where('payment_status', 'completed')
                ->with('course')
                ->first();
        }

        if (!$completedEnrollment) {
            // If no completed courses, show a preview with student's name
            $certificate = (object) [
                'certificate_number' => 'IOA-' . now()->format('Y') . '-PREVIEW-001',
                'verification_code' => 'PREV' . strtoupper(\Illuminate\Support\Str::random(8)),
                'student_name' => $student->full_name,
                'course_title' => 'Complete a course to earn your certificate',
                'course_description' => 'This preview shows how your certificate will look once you complete a course.',
                'instructors' => ['Your Course Instructor'],
                'completion_date' => now(),
                'issue_date' => now(),
                'expiry_date' => null,
                'is_verified' => true,
                'is_revoked' => false,
            ];
        } else {
            // Show certificate for the specific completed course
            $course = $completedEnrollment->course;
            $certificate = (object) [
                'certificate_number' => 'IOA-' . now()->format('Y') . '-' . str_pad($completedEnrollment->id, 4, '0', STR_PAD_LEFT),
                'verification_code' => 'CERT' . strtoupper(\Illuminate\Support\Str::random(8)),
                'student_name' => $student->full_name,
                'course_title' => $course->title,
                'course_description' => $course->description ?? 'Successfully completed all course requirements with dedication and excellence in Islamic education.',
                'instructors' => [$course->instructor ?? 'Islamic Studies Instructor'],
                'completion_date' => $completedEnrollment->updated_at ?? now(),
                'issue_date' => now(),
                'expiry_date' => null,
                'is_verified' => true,
                'is_revoked' => false,
            ];
        }

        return view('certificates.template', compact('certificate'));
    }
}