<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use App\Models\CourseEnrollment;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::published()->get();

        // Create enrollments for each student
        foreach ($students as $student) {
            // Each student enrolls in 2-5 random courses
            $coursesToEnroll = $courses->random(rand(2, 5));
            
            foreach ($coursesToEnroll as $course) {
                // Check if already enrolled
                if ($student->enrollments()->where('course_id', $course->id)->exists()) {
                    continue;
                }

                $enrolledAt = fake()->dateTimeBetween('-3 months', 'now');
                $progressPercentage = fake()->numberBetween(10, 100);
                $isCompleted = $progressPercentage >= 100;
                
                $enrollment = CourseEnrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'enrolled_at' => $enrolledAt,
                    'enrollment_type' => $course->is_free ? 'free' : 'paid',
                    'progress_percentage' => $progressPercentage,
                    'lessons_completed' => fake()->numberBetween(1, 10),
                    'is_completed' => $isCompleted,
                    'payment_status' => $course->is_free ? 'completed' : 'completed',
                    'is_active' => true,
                ]);

                // Create payment if course is not free
                if (!$course->is_free) {
                    Payment::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'amount' => $course->discount_price ?? $course->price,
                        'currency' => $course->currency,
                        'payment_method' => fake()->randomElement(['bkash', 'nagad', 'rocket', 'bank_transfer']),
                        'status' => 'completed',
                        'transaction_id' => 'TXN' . fake()->unique()->numerify('########'),
                        'sender_number' => fake()->phoneNumber(),
                    ]);
                }
            }
        }

        // Ensure our test student has some specific enrollments
        $testStudent = Student::whereHas('user', function($q) {
            $q->where('email', 'student@iqra.academy');
        })->first();

        if ($testStudent) {
            $freeCourses = Course::where('is_free', true)->take(2)->get();
            $paidCourses = Course::where('is_free', false)->take(3)->get();
            
            foreach ($freeCourses as $course) {
                if (!$testStudent->enrollments()->where('course_id', $course->id)->exists()) {
                    CourseEnrollment::create([
                        'student_id' => $testStudent->id,
                        'course_id' => $course->id,
                        'enrolled_at' => now()->subWeeks(2),
                        'enrollment_type' => 'free',
                        'progress_percentage' => 100,
                        'lessons_completed' => 10,
                        'is_completed' => true,
                        'payment_status' => 'completed',
                        'is_active' => true,
                    ]);
                }
            }

            foreach ($paidCourses as $course) {
                if (!$testStudent->enrollments()->where('course_id', $course->id)->exists()) {
                    $enrollment = CourseEnrollment::create([
                        'student_id' => $testStudent->id,
                        'course_id' => $course->id,
                        'enrolled_at' => now()->subWeeks(4),
                        'enrollment_type' => 'paid',
                        'progress_percentage' => fake()->numberBetween(30, 80),
                        'lessons_completed' => fake()->numberBetween(3, 8),
                        'is_completed' => false,
                        'payment_status' => 'completed',
                        'is_active' => true,
                    ]);

                    Payment::create([
                        'student_id' => $testStudent->id,
                        'course_id' => $course->id,
                        'amount' => $course->discount_price ?? $course->price,
                        'currency' => $course->currency,
                        'payment_method' => 'bkash',
                        'status' => 'completed',
                        'transaction_id' => 'TXN' . fake()->unique()->numerify('########'),
                        'sender_number' => '01700000000',
                    ]);
                }
            }
        }
    }
}