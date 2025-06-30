<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        // Create some sample enrollments
        $enrollments = [
            [
                'student_email' => 'abdullah@example.com',
                'course_slug' => 'fiqh-taharat-salah', // Free course
                'enrollment_type' => 'free',
                'payment_status' => 'completed',
                'progress_percentage' => 45.5,
                'is_completed' => false,
            ],
            [
                'student_email' => 'fatima@example.com',
                'course_slug' => 'quran-tilawat-basic', // Paid course
                'enrollment_type' => 'paid',
                'payment_status' => 'completed',
                'progress_percentage' => 78.0,
                'is_completed' => false,
            ],
            [
                'student_email' => 'ibrahim@example.com',
                'course_slug' => 'sahih-bukhari-selected', // Paid course
                'enrollment_type' => 'paid',
                'payment_status' => 'completed',
                'progress_percentage' => 100.0,
                'is_completed' => true,
                'completed_at' => now()->subDays(5),
            ],
        ];

        foreach ($enrollments as $enrollmentData) {
            $student = Student::whereHas('user', function($q) use ($enrollmentData) {
                $q->where('email', $enrollmentData['student_email']);
            })->first();

            $course = Course::where('slug', $enrollmentData['course_slug'])->first();

            if ($student && $course) {
                // Create payment record if it's a paid course
                $payment = null;
                if ($enrollmentData['enrollment_type'] === 'paid') {
                    $payment = Payment::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'amount' => $course->effective_price,
                        'currency' => $course->currency,
                        'payment_method' => 'bkash',
                        'transaction_id' => 'TXN' . rand(100000, 999999),
                        'gateway_payment_id' => 'BKASH' . rand(1000000000, 9999999999),
                        'status' => 'completed',
                        'payment_date' => now()->subDays(rand(1, 30)),
                        'gateway_response' => ['status' => 'success', 'message' => 'Payment completed successfully'],
                        'sender_number' => '01712345678',
                        'receiver_number' => '01950000000',
                        'reference_number' => 'REF' . rand(100000, 999999),
                    ]);
                }

                // Create enrollment
                CourseEnrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'enrolled_at' => now()->subDays(rand(1, 30)),
                    'enrollment_type' => $enrollmentData['enrollment_type'],
                    'payment_id' => $payment ? $payment->id : null,
                    'amount_paid' => $payment ? $payment->amount : 0,
                    'currency' => $course->currency,
                    'payment_status' => $enrollmentData['payment_status'],
                    'progress_percentage' => $enrollmentData['progress_percentage'],
                    'is_completed' => $enrollmentData['is_completed'],
                    'completed_at' => $enrollmentData['completed_at'] ?? null,
                    'is_active' => true,
                    'last_accessed_at' => now()->subHours(rand(1, 24)),
                ]);
            }
        }
    }
}
