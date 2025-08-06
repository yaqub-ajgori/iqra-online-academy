<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Student;
use App\Services\CertificateService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CertificateManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificate:manage 
                            {action : Action to perform: generate, list, regenerate, verify}
                            {--student= : Student ID or email}
                            {--course= : Course ID or slug}
                            {--certificate= : Certificate number or ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage certificates (generate, list, regenerate, verify)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        match ($action) {
            'generate' => $this->generateCertificate(),
            'list' => $this->listCertificates(),
            'regenerate' => $this->regeneratePDF(),
            'verify' => $this->verifyCertificate(),
            default => $this->error("Unknown action: {$action}. Use: generate, list, regenerate, or verify")
        };
    }

    private function generateCertificate()
    {
        $studentOption = $this->option('student');
        $courseOption = $this->option('course');

        if (!$studentOption || !$courseOption) {
            $this->error('Both --student and --course options are required for generation.');
            return;
        }

        // Find student
        $student = is_numeric($studentOption) 
            ? Student::find($studentOption)
            : Student::whereHas('user', fn($q) => $q->where('email', $studentOption))->first();

        if (!$student) {
            $this->error('Student not found.');
            return;
        }

        // Find course
        $course = is_numeric($courseOption) 
            ? Course::find($courseOption)
            : Course::where('slug', $courseOption)->first();

        if (!$course) {
            $this->error('Course not found.');
            return;
        }

        // Check eligibility
        $certificateService = app(CertificateService::class);
        if (!$certificateService->isEligibleForCertificate($student, $course)) {
            $this->warn('Student is not eligible for a certificate for this course.');
            $this->line('Reasons:');
            $this->line('- Course may not be completed');
            $this->line('- Progress may be less than 100%');
            $this->line('- Certificate may already exist');
            return;
        }

        try {
            $certificate = $certificateService->generateCertificate($student, $course);
            $this->info("✅ Certificate generated successfully!");
            $this->table(['Field', 'Value'], [
                ['Certificate Number', $certificate->certificate_number],
                ['Student', $certificate->student_name],
                ['Course', $certificate->course_title],
                ['Issue Date', $certificate->issue_date->format('Y-m-d')],
                ['Verification Code', $certificate->verification_code],
                ['Download URL', $certificate->download_url],
            ]);
        } catch (\Exception $e) {
            $this->error("Failed to generate certificate: {$e->getMessage()}");
        }
    }

    private function listCertificates()
    {
        $query = Certificate::with(['student.user', 'course']);

        if ($this->option('student')) {
            $studentOption = $this->option('student');
            if (is_numeric($studentOption)) {
                $query->where('student_id', $studentOption);
            } else {
                $query->whereHas('student.user', fn($q) => $q->where('email', $studentOption));
            }
        }

        if ($this->option('course')) {
            $courseOption = $this->option('course');
            if (is_numeric($courseOption)) {
                $query->where('course_id', $courseOption);
            } else {
                $query->whereHas('course', fn($q) => $q->where('slug', $courseOption));
            }
        }

        $certificates = $query->orderBy('created_at', 'desc')->get();

        if ($certificates->isEmpty()) {
            $this->info('No certificates found.');
            return;
        }

        $tableData = $certificates->map(function ($cert) {
            return [
                $cert->certificate_number,
                $cert->student_name,
                Str::limit($cert->course_title, 30),
                $cert->issue_date->format('Y-m-d'),
                $cert->is_valid ? '✅' : '❌',
                $cert->certificate_path ? '✅' : '❌',
            ];
        })->toArray();

        $this->table([
            'Certificate #', 'Student', 'Course', 'Issued', 'Valid', 'PDF'
        ], $tableData);

        $this->info("Total: {$certificates->count()} certificates");
    }

    private function regeneratePDF()
    {
        $certificateOption = $this->option('certificate');

        if (!$certificateOption) {
            $this->error('--certificate option is required for regeneration.');
            return;
        }

        // Find certificate
        $certificate = is_numeric($certificateOption) 
            ? Certificate::find($certificateOption)
            : Certificate::where('certificate_number', $certificateOption)->first();

        if (!$certificate) {
            $this->error('Certificate not found.');
            return;
        }

        try {
            $certificateService = app(CertificateService::class);
            $certificateService->regenerateCertificatePDF($certificate);
            $this->info("✅ PDF regenerated successfully!");
            $this->line("Download URL: {$certificate->download_url}");
        } catch (\Exception $e) {
            $this->error("Failed to regenerate PDF: {$e->getMessage()}");
        }
    }

    private function verifyCertificate()
    {
        $certificateOption = $this->option('certificate');

        if (!$certificateOption) {
            $this->error('--certificate option is required for verification.');
            return;
        }

        try {
            $certificateService = app(CertificateService::class);
            $certificate = $certificateService->verifyCertificate($certificateOption);

            if ($certificate) {
                $this->info("✅ Certificate is VALID!");
                $this->table(['Field', 'Value'], [
                    ['Certificate Number', $certificate->certificate_number],
                    ['Student', $certificate->student_name],
                    ['Course', $certificate->course_title],
                    ['Issue Date', $certificate->issue_date->format('Y-m-d')],
                    ['Status', ucfirst($certificate->status)],
                ]);
            } else {
                $this->error("❌ Certificate is INVALID or NOT FOUND!");
            }
        } catch (\Exception $e) {
            $this->error("Verification failed: {$e->getMessage()}");
        }
    }
}