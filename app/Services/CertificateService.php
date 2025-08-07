<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Student;
use App\Models\Course;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService
{
    /**
     * Generate certificate for a student who completed a course.
     */
    public function generateCertificate(Student $student, Course $course): Certificate
    {
        // Get all instructors (primary + additional)
        $instructors = collect();
        
        // Add primary instructor
        if ($course->instructor) {
            $instructors->push($course->instructor->full_name);
        }
        
        // Add additional instructors
        $additionalInstructors = $course->instructors()->wherePivot('is_active', true)->get();
        foreach ($additionalInstructors as $instructor) {
            if (!$instructors->contains($instructor->full_name)) {
                $instructors->push($instructor->full_name);
            }
        }

        // Create certificate record
        $certificate = Certificate::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'student_name' => $student->full_name,
            'course_title' => $course->title,
            'course_description' => $course->full_description,
            'instructors' => $instructors->toArray(),
            'completion_date' => now(),
        ]);

        // Generate PDF
        $pdf = $this->createPDF($certificate);
        
        // Save PDF file
        $filename = "certificate_{$certificate->certificate_number}.pdf";
        $path = "certificates/{$filename}";
        Storage::disk('public')->put($path, $pdf->output());
        
        // Update certificate with file path
        $certificate->update(['certificate_path' => $path]);
        
        return $certificate;
    }

    /**
     * Create optimized PDF from certificate data using dompdf.
     */
    private function createPDF(Certificate $certificate)
    {
        // Create PDF with optimized settings for certificate generation
        $pdf = Pdf::loadView('certificates.template', compact('certificate'));
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');
        
        // Apply dompdf-specific options for better rendering
        $pdf->setOptions([
            // Security settings
            'isPhpEnabled' => false,
            'isRemoteEnabled' => false,
            
            // Font settings
            'defaultFont' => 'DejaVu Sans',
            'fontSubsetting' => true,
            'isFontSubsettingEnabled' => true,
            'fontHeightRatio' => 1.1,
            
            // Quality settings
            'dpi' => 300,
            'defaultMediaType' => 'print',
            
            // Paper settings
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'landscape',
            
            // Parser settings
            'isHtml5ParserEnabled' => true,
            'enable_html5_parser' => true,
            
            // Additional optimization
            'debugKeepTemp' => false,
            'debugCss' => false,
            'debugLayout' => false,
            'debugLayoutLines' => false,
            'debugLayoutBlocks' => false,
            'debugLayoutInline' => false,
            'debugLayoutPaddingBox' => false,
            
            // Rendering optimization
            'enable_font_subsetting' => true,
            'pdf_backend' => 'CPDF',
            'convert_entities' => true,
        ]);
        
        return $pdf;
    }

    /**
     * Regenerate certificate PDF.
     */
    public function regenerateCertificatePDF(Certificate $certificate): Certificate
    {
        // Generate new PDF
        $pdf = $this->createPDF($certificate);
        
        // Delete old file if exists
        if ($certificate->certificate_path) {
            Storage::disk('public')->delete($certificate->certificate_path);
        }
        
        // Save new PDF file
        $filename = "certificate_{$certificate->certificate_number}.pdf";
        $path = "certificates/{$filename}";
        Storage::disk('public')->put($path, $pdf->output());
        
        // Update certificate with file path
        $certificate->update(['certificate_path' => $path]);
        
        return $certificate;
    }

    /**
     * Verify certificate by verification code.
     */
    public function verifyCertificate(string $verificationCode): ?Certificate
    {
        return Certificate::where('verification_code', $verificationCode)
            ->valid()
            ->notExpired()
            ->first();
    }

    /**
     * Check if student is eligible for certificate.
     */
    public function isEligibleForCertificate(Student $student, Course $course): bool
    {
        $enrollment = $student->courseEnrollments()
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return false;
        }

        // Check if course is completed
        if (!$enrollment->is_completed) {
            return false;
        }

        // Check progress percentage (should be 100%)
        if ($enrollment->progress_percentage < 100) {
            return false;
        }

        // Check if certificate already exists
        $existingCertificate = Certificate::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('is_revoked', false)
            ->exists();

        if ($existingCertificate) {
            return false; // Already has certificate
        }

        return true;
    }

    /**
     * Auto-generate certificates for eligible students.
     */
    public function autoGenerateCertificates(Course $course): int
    {
        $generated = 0;
        
        // Get all students who completed the course
        $completedEnrollments = $course->enrollments()
            ->where('is_completed', true)
            ->where('progress_percentage', 100)
            ->with('student')
            ->get();

        foreach ($completedEnrollments as $enrollment) {
            $student = $enrollment->student;
            
            if ($this->isEligibleForCertificate($student, $course)) {
                $this->generateCertificate($student, $course);
                $generated++;
            }
        }

        return $generated;
    }

    /**
     * Create a preview certificate for testing purposes.
     */
    public function createPreviewCertificate()
    {
        // Create a mock certificate object for preview (without saving to database)
        $certificate = new Certificate([
            'certificate_number' => 'IOA-' . now()->format('Y') . '-PREVIEW-001',
            'verification_code' => 'PREV' . strtoupper(Str::random(8)),
            'student_name' => 'Muhammad Abdullah Ahmed',
            'course_title' => 'Comprehensive Quranic Studies & Tajweed Fundamentals',
            'course_description' => 'This comprehensive course covers the fundamental principles of Quranic recitation, Tajweed rules, Arabic pronunciation, and Islamic studies. Students learn proper articulation points (Makhraj), characteristics of letters (Sifaat), and various Tajweed regulations essential for correct Quranic recitation.',
            'instructors' => ['Dr. Muhammad Ibrahim Al-Hafiz', 'Ustadha Sarah Khan', 'Sheikh Abdullah Al-Mansouri'],
            'completion_date' => now()->subDays(7),
            'issue_date' => now(),
            'expiry_date' => null,
            'is_verified' => true,
            'is_revoked' => false,
        ]);

        return $this->createPDF($certificate);
    }
}