<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CourseEnrollment;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        // Get completed enrollments that should have certificates
        $completedEnrollments = CourseEnrollment::where('is_completed', true)
            ->with(['student.user', 'course.instructor', 'course.instructors'])
            ->get();

        foreach ($completedEnrollments as $enrollment) {
            // Skip if certificate already exists
            if (Certificate::where('student_id', $enrollment->student_id)
                ->where('course_id', $enrollment->course_id)
                ->exists()) {
                continue;
            }

            // Get all instructors for this course
            $instructors = collect();
            
            // Add primary instructor
            if ($enrollment->course->instructor) {
                $instructors->push($enrollment->course->instructor->full_name);
            }
            
            // Add additional instructors
            foreach ($enrollment->course->instructors as $instructor) {
                if (!$instructors->contains($instructor->full_name)) {
                    $instructors->push($instructor->full_name);
                }
            }

            Certificate::create([
                'student_id' => $enrollment->student_id,
                'course_id' => $enrollment->course_id,
                'certificate_number' => 'IQRA-' . strtoupper(Str::random(8)),
                'verification_code' => strtoupper(Str::random(12)),
                'student_name' => $enrollment->student->user->name,
                'course_title' => $enrollment->course->title,
                'course_description' => Str::limit($enrollment->course->full_description, 200),
                'instructors' => $instructors->toArray(),
                'final_score' => rand(70, 95),
                'completion_date' => now()->subDays(rand(1, 30)),
                'issue_date' => now()->subDays(rand(1, 30)),
                'expiry_date' => null, // Certificates don't expire
                'certificate_path' => null, // Will be generated when needed
                'is_revoked' => false,
            ]);
        }

        // Create specific certificates for test student
        $testStudent = Student::whereHas('user', function($q) {
            $q->where('email', 'student@iqra.academy');
        })->first();

        if ($testStudent) {
            $testEnrollments = $testStudent->enrollments()
                ->where('is_completed', true)
                ->with('course.instructor')
                ->get();

            foreach ($testEnrollments as $enrollment) {
                if (!Certificate::where('student_id', $testStudent->id)
                    ->where('course_id', $enrollment->course_id)
                    ->exists()) {
                    
                    Certificate::create([
                        'student_id' => $testStudent->id,
                        'course_id' => $enrollment->course_id,
                        'certificate_number' => 'IQRA-TEST' . str_pad($enrollment->course_id, 3, '0', STR_PAD_LEFT),
                        'verification_code' => 'TEST' . strtoupper(Str::random(8)),
                        'student_name' => $testStudent->user->name,
                        'course_title' => $enrollment->course->title,
                        'course_description' => Str::limit($enrollment->course->full_description, 200),
                        'instructors' => [$enrollment->course->instructor->user->name ?? 'Unknown Instructor'],
                        'final_score' => $enrollment->final_score,
                        'completion_date' => $enrollment->completed_at,
                        'issue_date' => $enrollment->completed_at,
                        'certificate_path' => null,
                        'is_revoked' => false,
                    ]);
                }
            }
        }

        // Create a few sample certificates with specific verification codes for testing
        $sampleVerificationCodes = ['SAMPLE123456', 'TEST98765432', 'DEMO11111111'];
        
        foreach ($sampleVerificationCodes as $index => $code) {
            $student = Student::inRandomOrder()->first();
            $course = Course::inRandomOrder()->first();
            
            if ($student && $course) {
                Certificate::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'certificate_number' => 'IQRA-SAMPLE' . ($index + 1),
                    'verification_code' => $code,
                    'student_name' => $student->user->name,
                    'course_title' => $course->title,
                    'course_description' => Str::limit($course->full_description, 200),
                    'instructors' => [$course->instructor->user->name ?? 'Sample Instructor'],
                    'final_score' => fake()->randomFloat(1, 75, 95),
                    'completion_date' => fake()->dateTimeBetween('-2 months', 'now'),
                    'issue_date' => fake()->dateTimeBetween('-2 months', 'now'),
                    'certificate_path' => null,
                    'is_revoked' => false,
                ]);
            }
        }
    }
}