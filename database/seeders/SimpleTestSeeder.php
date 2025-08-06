<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use App\Models\CourseEnrollment;
use App\Models\Certificate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SimpleTestSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::first();
        $student = Student::first();

        if (!$course || !$student) {
            echo "No course or student found. Run BasicSeeder first.\n";
            return;
        }

        // Create enrollment for test student
        $enrollment = CourseEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()->subWeeks(2),
            'enrollment_type' => 'free',
            'payment_status' => 'completed',
            'progress_percentage' => 100.00,
            'lessons_completed' => 5,
            'is_completed' => true,
            'is_active' => true,
        ]);

        // Create a test certificate
        Certificate::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'certificate_number' => 'IQRA-TEST001',
            'verification_code' => 'TEST12345678',
            'student_name' => $student->full_name,
            'course_title' => $course->title,
            'course_description' => Str::limit($course->full_description, 200),
            'instructors' => ['উস্তাদ আবু বকর সিদ্দিক'],
            'final_score' => 85,
            'completion_date' => now()->subDays(3),
            'issue_date' => now()->subDays(3),
            'is_revoked' => false,
        ]);

        // Create a few sample certificates with known verification codes
        Certificate::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'certificate_number' => 'IQRA-DEMO001',
            'verification_code' => 'DEMO12345678',
            'student_name' => $student->full_name,
            'course_title' => $course->title,
            'course_description' => Str::limit($course->full_description, 200),
            'instructors' => ['উস্তাদ আবু বকর সিদ্দিক', 'হাফেজ মুহাম্মদ ইব্রাহিম'],
            'final_score' => 92,
            'completion_date' => now()->subDays(10),
            'issue_date' => now()->subDays(10),
            'is_revoked' => false,
        ]);

        echo "Simple test seeding completed!\n";
        echo "- Student enrollment created\n";
        echo "- Test certificates created\n";
        echo "- Test certificate verification codes:\n";
        echo "  - TEST12345678\n";
        echo "  - DEMO12345678\n";
        echo "\nStudent login: student@iqra.academy / password\n";
        echo "Admin login: admin@iqra.academy / password\n";
    }
}