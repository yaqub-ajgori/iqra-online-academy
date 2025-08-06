<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\CourseEnrollment;
use App\Models\Student;
use App\Models\Certificate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuickTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        $student = Student::first();

        foreach ($courses as $course) {
            // Create course modules and lessons
            $module = CourseModule::create([
                'course_id' => $course->id,
                'title' => 'অধ্যায় ১: ভূমিকা',
                'sort_order' => 1,
            ]);

            // Create lessons
            CourseLesson::create([
                'course_id' => $course->id,
                'module_id' => $module->id,
                'title' => 'পাঠ ১: প্রাথমিক পরিচিতি',
                'content' => 'এই পাঠে আপনি শিখবেন মূল বিষয়গুলি সম্পর্কে। এটি একটি ফ্রি প্রিভিউ পাঠ যা সবাই দেখতে পারবেন।',
                'lesson_type' => 'text',
                'reading_time_minutes' => 10,
                'is_preview' => true,
                'is_mandatory' => true,
                'sort_order' => 1,
                'is_active' => true,
            ]);

            CourseLesson::create([
                'course_id' => $course->id,
                'module_id' => $module->id,
                'title' => 'পাঠ ২: বিস্তারিত আলোচনা',
                'content' => 'এই পাঠে বিষয়টির বিস্তারিত ব্যাখ্যা দেওয়া হয়েছে। এটি পেইড কনটেন্ট।',
                'lesson_type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_duration' => 1200,
                'video_provider' => 'youtube',
                'is_preview' => false,
                'is_mandatory' => true,
                'sort_order' => 2,
                'is_active' => true,
            ]);

            CourseLesson::create([
                'course_id' => $course->id,
                'module_id' => $module->id,
                'title' => 'পাঠ ৩: PDF রিসোর্স',
                'content' => 'এই পাঠে PDF ফাইল এবং অতিরিক্ত রিসোর্স রয়েছে।',
                'lesson_type' => 'pdf',
                'primary_file_path' => 'lessons/sample-lesson.pdf',
                'primary_file_type' => 'pdf',
                'primary_file_size' => 1048576,
                'attachments' => json_encode(['handout.pdf', 'notes.docx']),
                'resources' => json_encode(['https://example.com/resource1', 'https://example.com/resource2']),
                'is_preview' => false,
                'is_mandatory' => true,
                'sort_order' => 3,
                'is_active' => true,
            ]);

            // Create a quiz for the course
            $quiz = Quiz::create([
                'course_id' => $course->id,
                'title' => $course->title . ' - প্রাথমিক পরীক্ষা',
                'description' => 'এই পরীক্ষায় কোর্সের মূল বিষয়গুলি থেকে প্রশ্ন করা হবে।',
                'instructions' => 'প্রতিটি প্রশ্নের উত্তর সাবধানে পড়ুন এবং সঠিক উত্তর নির্বাচন করুন।',
                'type' => 'quiz',
                'time_limit_minutes' => 30,
                'max_attempts' => 3,
                'passing_score' => 70.00,
                'show_results_immediately' => true,
                'allow_review' => true,
                'is_available' => true,
            ]);

            // Create quiz questions
            $questions = [
                [
                    'type' => 'multiple_choice',
                    'question' => 'পবিত্র কুরআনে কতটি সূরা রয়েছে?',
                    'options' => ['১১২টি', '১১৪টি', '১১৬টি', '১১৮টি'],
                    'correct' => ['১১৪টি'],
                    'explanation' => 'পবিত্র কুরআনে মোট ১১ৄটি সূরা রয়েছে।'
                ],
                [
                    'type' => 'true_false',
                    'question' => 'ইসলামে পাঁচ ওয়াক্ত নামাজ ফরজ।',
                    'options' => ['সত্য', 'মিথ্যা'],
                    'correct' => ['সত্য'],
                    'explanation' => 'দৈনিক পাঁচ ওয়াক্ত নামাজ প্রতিটি মুসলমানের জন্য ফরজ।'
                ],
                [
                    'type' => 'short_answer',
                    'question' => 'ইসলামের পাঁচটি স্তম্ভের নাম লিখুন।',
                    'options' => [],
                    'correct' => ['কালেমা, নামাজ, রোজা, হজ, জাকাত'],
                    'explanation' => 'ইসলামের পাঁচটি স্তম্ভ হলো: কালেমা, নামাজ, রোজা, হজ ও জাকাত।'
                ]
            ];

            foreach ($questions as $index => $questionData) {
                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'options' => json_encode($questionData['options']),
                    'correct_answers' => json_encode($questionData['correct']),
                    'explanation' => $questionData['explanation'],
                    'points' => $questionData['type'] === 'short_answer' ? 5 : 2,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }

        // Create enrollment for test student
        if ($student) {
            $freeCourse = Course::where('is_free', true)->first();
            if ($freeCourse) {
                $enrollment = CourseEnrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $freeCourse->id,
                    'enrolled_at' => now()->subWeeks(2),
                    'progress_percentage' => 100,
                    'is_completed' => true,
                    'completed_at' => now()->subDays(3),
                    'final_score' => 85,
                    'certificate_issued' => true,
                    'last_accessed_at' => now()->subHours(5),
                ]);

                // Create a certificate
                Certificate::create([
                    'student_id' => $student->id,
                    'course_id' => $freeCourse->id,
                    'certificate_number' => 'IQRA-TEST001',
                    'verification_code' => 'TEST12345678',
                    'student_name' => $student->full_name,
                    'course_title' => $freeCourse->title,
                    'course_description' => Str::limit($freeCourse->full_description, 200),
                    'instructors' => ['উস্তাদ আবু বকর সিদ্দিক'],
                    'final_score' => 85,
                    'completion_date' => $enrollment->completed_at,
                    'issue_date' => $enrollment->completed_at,
                    'is_revoked' => false,
                ]);
            }
        }

        echo "Quick test data seeding completed!\n";
        echo "- Course modules and lessons created\n";
        echo "- Quizzes with questions created\n";
        echo "- Test student enrollment and certificate created\n";
        echo "- Certificate verification code: TEST12345678\n";
    }
}