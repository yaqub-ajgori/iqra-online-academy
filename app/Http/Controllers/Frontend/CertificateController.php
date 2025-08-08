<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CertificateController extends Controller
{
    public function __construct(
        private CertificateService $certificateService
    ) {}

    /**
     * Show certificate verification page.
     */
    public function verify()
    {
        return Inertia::render('Frontend/CertificateVerification');
    }

    /**
     * Verify certificate by verification code.
     */
    public function checkVerification(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:12',
        ]);

        $certificate = $this->certificateService->verifyCertificate($request->verification_code);

        if (!$certificate) {
            return back()->withErrors([
                'verification_code' => 'Invalid or expired verification code.'
            ]);
        }

        // Store verification code in session for later use
        session(['verified_certificate_code' => $request->verification_code]);

        return Inertia::render('Frontend/CertificateVerification', [
            'certificate' => [
                'certificate_number' => $certificate->certificate_number,
                'student_name' => $certificate->student_name,
                'course_title' => $certificate->course_title,
                'completion_date' => $certificate->completion_date->format('d M Y'),
                'issue_date' => $certificate->issue_date->format('d M Y'),
                'final_score' => $certificate->final_score,
                'instructors' => is_array($certificate->instructors) ? $certificate->instructors : json_decode($certificate->instructors ?? '[]', true),
                'verification_code' => $certificate->verification_code,
                'is_valid' => true,
            ]
        ]);
    }

    /**
     * Download certificate PDF.
     */
    public function download(Certificate $certificate)
    {
        // Check if user can access this certificate
        $user = auth()->user();
        if (!$user || $certificate->student_id !== $user->student->id) {
            abort(403, 'You are not authorized to download this certificate.');
        }

        // Check if certificate file exists
        if (!$certificate->certificate_path || !Storage::disk('public')->exists($certificate->certificate_path)) {
            // Regenerate certificate if missing
            $certificate = $this->certificateService->regenerateCertificatePDF($certificate);
        }

        $filePath = Storage::disk('public')->path($certificate->certificate_path);
        $fileName = "Certificate_{$certificate->certificate_number}.pdf";

        return response()->download($filePath, $fileName);
    }

    /**
     * Show student's certificates.
     */
    public function myCertificates()
    {
        $student = auth()->user()->student;
        
        $certificates = Certificate::where('student_id', $student->id)
            ->where('is_revoked', false)
            ->with('course')
            ->orderBy('issue_date', 'desc')
            ->get()
            ->map(function ($certificate) {
                return [
                    'id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number,
                    'course_title' => $certificate->course_title,
                    'completion_date' => $certificate->completion_date->format('d M Y'),
                    'issue_date' => $certificate->issue_date->format('d M Y'),
                        'verification_code' => $certificate->verification_code,
                    'download_url' => route('certificates.download', $certificate),
                ];
            });

        return Inertia::render('Frontend/MyCertificates', [
            'certificates' => $certificates
        ]);
    }

    /**
     * Generate certificate for completed course.
     */
    public function generate(Request $request)
    {
        $student = auth()->user()->student;
        
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = \App\Models\Course::findOrFail($request->course_id);

        // Check if student is eligible
        if (!$this->certificateService->isEligibleForCertificate($student, $course)) {
            return back()->withErrors([
                'message' => 'You are not eligible for a certificate for this course yet.'
            ]);
        }

        // Get final score from enrollment
        $enrollment = $student->courseEnrollments()
            ->where('course_id', $course->id)
            ->first();

        $certificate = $this->certificateService->generateCertificate($student, $course);

        return redirect()->route('certificates.download', $certificate)
            ->with('success', 'Certificate generated successfully!');
    }

    /**
     * Show certificate details (public view via verification code).
     */
    public function show(string $verificationCode)
    {
        $certificate = $this->certificateService->verifyCertificate($verificationCode);

        if (!$certificate) {
            abort(404, 'Certificate not found or expired.');
        }

        return Inertia::render('Frontend/CertificateView', [
            'certificate' => [
                'certificate_number' => $certificate->certificate_number,
                'student_name' => $certificate->student_name,
                'course_title' => $certificate->course_title,
                'course_description' => $certificate->course_description,
                'completion_date' => $certificate->completion_date->format('d M Y'),
                'issue_date' => $certificate->issue_date->format('d M Y'),
                'final_score' => $certificate->final_score,
                'instructors' => $certificate->instructors,
                'verification_code' => $certificate->verification_code,
            ]
        ]);
    }

    /**
     * Download certificate PDF by verification code (public access).
     */
    public function downloadPublic(string $verificationCode)
    {
        $certificate = $this->certificateService->verifyCertificate($verificationCode);

        if (!$certificate) {
            abort(404, 'Certificate not found or expired.');
        }

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.template', compact('certificate'))
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultFont', 'DejaVu Sans');
        
        $fileName = "Certificate_{$certificate->certificate_number}.pdf";
        
        return $pdf->download($fileName);
    }

    /**
     * Show certificate preview for development/testing.
     */
    public function preview()
    {
        // Create a sample certificate object for preview (without saving to database)
        $certificate = new Certificate([
            'certificate_number' => 'IOA-' . now()->format('Y') . '-PREVIEW-001',
            'verification_code' => 'PREV' . strtoupper(\Illuminate\Support\Str::random(8)),
            'student_name' => 'Muhammad Abdullah Ahmed',
            'course_title' => 'Comprehensive Quranic Studies & Tajweed Fundamentals',
            'course_description' => 'This comprehensive course covers the fundamental principles of Quranic recitation, Tajweed rules, Arabic pronunciation, and Islamic studies. Students learn proper articulation points (Makhraj), characteristics of letters (Sifaat), and various Tajweed regulations essential for correct Quranic recitation.',
            'instructors' => ['Ahmadullah AL Jami', 'Ustadha Sarah Khan', 'Sheikh Abdullah Al-Mansouri'],
            'completion_date' => now()->subDays(7),
            'issue_date' => now(),
            'expiry_date' => null,
            'is_verified' => true,
            'is_revoked' => false,
        ]);

        return view('certificates.template', compact('certificate'));
    }

    /**
     * Download preview certificate as PDF.
     */
    public function previewDownload()
    {
        $pdf = $this->certificateService->createPreviewCertificate();
        
        return $pdf->download('certificate-preview.pdf');
    }
}