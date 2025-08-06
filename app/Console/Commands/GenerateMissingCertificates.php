<?php

namespace App\Console\Commands;

use App\Models\CourseEnrollment;
use App\Services\CertificateService;
use Illuminate\Console\Command;

class GenerateMissingCertificates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificates:generate-missing 
                            {--course= : Generate certificates for specific course ID}
                            {--student= : Generate certificates for specific student ID}
                            {--dry-run : Show what would be generated without actually generating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate missing certificates for completed courses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $certificateService = app(CertificateService::class);
        
        $this->info('🔍 Finding completed courses without certificates...');
        
        // Build query for completed enrollments
        $query = CourseEnrollment::where('is_completed', true)
            ->where('progress_percentage', 100)
            ->with(['student.user', 'course']);
        
        // Apply filters if provided
        if ($this->option('course')) {
            $query->where('course_id', $this->option('course'));
        }
        
        if ($this->option('student')) {
            $query->where('student_id', $this->option('student'));
        }
        
        $completedEnrollments = $query->get();
        
        if ($completedEnrollments->isEmpty()) {
            $this->warn('No completed enrollments found.');
            return;
        }
        
        $eligible = [];
        $alreadyHasCert = [];
        
        // Check eligibility for each enrollment
        foreach ($completedEnrollments as $enrollment) {
            if ($certificateService->isEligibleForCertificate($enrollment->student, $enrollment->course)) {
                $eligible[] = $enrollment;
            } else {
                $alreadyHasCert[] = $enrollment;
            }
        }
        
        // Show summary
        $this->table(['Status', 'Count'], [
            ['Eligible for certificate', count($eligible)],
            ['Already has certificate', count($alreadyHasCert)],
            ['Total completed', $completedEnrollments->count()],
        ]);
        
        if (empty($eligible)) {
            $this->info('✅ All completed courses already have certificates!');
            return;
        }
        
        // Show eligible enrollments
        if ($this->option('verbose')) {
            $this->info('📋 Eligible for certificate generation:');
            foreach ($eligible as $enrollment) {
                $this->line("- {$enrollment->student->full_name} → {$enrollment->course->title}");
            }
        }
        
        if ($this->option('dry-run')) {
            $this->info('🔍 Dry run complete. Use without --dry-run to generate certificates.');
            return;
        }
        
        // Confirm before generating
        if (!$this->confirm('Generate certificates for ' . count($eligible) . ' completed courses?')) {
            $this->info('Certificate generation cancelled.');
            return;
        }
        
        // Generate certificates
        $generated = 0;
        $errors = 0;
        
        $bar = $this->output->createProgressBar(count($eligible));
        $bar->start();
        
        foreach ($eligible as $enrollment) {
            try {
                $certificate = $certificateService->generateCertificate($enrollment->student, $enrollment->course);
                $this->newLine();
                $this->info("✅ Generated: {$certificate->certificate_number} for {$enrollment->student->full_name}");
                $generated++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Failed for {$enrollment->student->full_name}: {$e->getMessage()}");
                $errors++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        // Final summary
        $this->info("🎯 Certificate generation complete!");
        $this->table(['Result', 'Count'], [
            ['✅ Successfully generated', $generated],
            ['❌ Failed', $errors],
            ['📊 Total processed', count($eligible)],
        ]);
    }
}