<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Certificate;

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
            // Show certificate for the specific completed course using consistent data
            $certificateData = $completedEnrollment->getCertificateData();
            $certificate = (object) [
                'certificate_number' => $certificateData['certificate_number'],
                'verification_code' => $certificateData['verification_code'],
                'student_name' => $certificateData['student_name'],
                'course_title' => $certificateData['course_title'],
                'course_description' => $certificateData['course_description'] ?? 'Successfully completed all course requirements with dedication and excellence in Islamic education.',
                'instructors' => [$certificateData['instructor']],
                'completion_date' => $certificateData['completion_date'],
                'issue_date' => $certificateData['issue_date'],
                'expiry_date' => null,
                'is_verified' => true,
                'is_revoked' => false,
            ];
        }

        return view('certificates.template', compact('certificate'));
    }

    /**
     * Show certificate verification page.
     */
    public function verify()
    {
        return Inertia::render('Frontend/CertificateVerification');
    }

    /**
     * Verify certificate by number or verification code.
     */
    public function verifySubmit(Request $request)
    {
        $validated = $request->validate([
            'certificate_code' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $trimmed = strtoupper(trim($value));
                    $isExactPattern = preg_match('/^IOA-\d{4}-\d{4}$/', $trimmed) === 1
                        || preg_match('/^CERT[A-Z0-9]{8}$/', $trimmed) === 1;
                    if (!$isExactPattern) {
                        $fail('Please enter a valid certificate number (IOA-YYYY-####) or verification code (CERTXXXXXXXX).');
                    }
                },
            ],
        ]);

        $code = strtoupper(trim($validated['certificate_code']));

        $certificate = \App\Models\Certificate::where('status', 'issued')
            ->with(['student.user', 'course'])
            ->where(function ($q) use ($code) {
                $q->where('certificate_number', $code)
                  ->orWhere('verification_code', $code);
            })
            ->first();

        if (!$certificate) {
            return Inertia::render('Frontend/CertificateVerification', [
                'errors' => [
                    'certificate_code' => 'Certificate not found. Please check the certificate number or verification code and try again.'
                ],
                'form' => [
                    'certificate_code' => $request->certificate_code,
                ],
                'certificate' => null,
            ]);
        }

        if (!$certificate->is_valid) {
            $status = $certificate->display_status;
            $message = $status === 'Revoked'
                ? 'This certificate has been revoked and is no longer valid.'
                : 'This certificate has expired and is no longer valid.';
            return Inertia::render('Frontend/CertificateVerification', [
                'errors' => [
                    'certificate_code' => $message
                ],
                'form' => [
                    'certificate_code' => $request->certificate_code,
                ],
                'certificate' => null,
            ]);
        }

        $certificateData = [
            'certificate_number' => $certificate->certificate_number,
            'verification_code' => $certificate->verification_code,
            'student_name' => $certificate->student_name,
            'course_title' => $certificate->course_title,
            'course_description' => $certificate->course_description,
            'completion_date' => $certificate->completed_at ? $certificate->completed_at->format('F j, Y') : null,
            'issue_date' => $certificate->issued_at ? $certificate->issued_at->format('F j, Y') : null,
            'instructor' => $certificate->instructor_name,
            'is_valid' => $certificate->is_valid,
            'status' => $certificate->display_status,
        ];

        return Inertia::render('Frontend/CertificateVerification', [
            'certificate' => $certificateData,
            'form' => [
                'certificate_code' => $request->certificate_code,
            ],
            'errors' => [],
        ]);
    }
}