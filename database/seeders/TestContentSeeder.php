<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Student;
use App\Models\CourseEnrollment;
use App\Models\Certificate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestContentSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::first();
        $student = Student::first();

        if (!$course || !$student) {
            echo "No course or student found. Run BasicSeeder first.\n";
            return;
        }

        // Create course module
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
            'content' => 'এই পাঠে আপনি শিখবেন মূল বিষয়গুলি সম্পর্কে। এটি একটি ফ্রি প্রিভিউ পাঠ।',
            'lesson_type' => 'text',
            'sort_order' => 1,
        ]);

        CourseLesson::create([
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'পাঠ ২: ভিডিও লেকচার',
            'content' => 'এই ভিডিও লেকচারে বিস্তারিত আলোচনা করা হয়েছে।',
            'lesson_type' => 'video',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_duration' => 1200,
            'sort_order' => 2,
        ]);

        // Create a quiz
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'প্রাথমিক পরীক্ষা - ' . $course->title,
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
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'পবিত্র কুরআনে কতটি সূরা রয়েছে?',
            'type' => 'multiple_choice',
            'options' => json_encode(['১১২টি', '১১৪টি', '১১৬টি', '১১৮টি']),
            'correct_answers' => json_encode(['১১৪টি']),
            'explanation' => 'পবিত্র কুরআনে মোট ১১৪টি সূরা রয়েছে।',
            'points' => 2,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'ইসলামে পাঁচ ওয়াক্ত নামাজ ফরজ।',
            'type' => 'true_false',
            'options' => json_encode(['সত্য', 'মিথ্যা']),
            'correct_answers' => json_encode(['সত্য']),
            'explanation' => 'দৈনিক পাঁচ ওয়াক্ত নামাজ প্রতিটি মুসলমানের জন্য ফরজ।',
            'points' => 1,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'ইসলামের পাঁচটি স্তম্ভের নাম লিখুন।',
            'type' => 'short_answer',
            'options' => json_encode([]),
            'correct_answers' => json_encode(['কালেমা, নামাজ, রোজা, হজ, জাকাত']),
            'explanation' => 'ইসলামের পাঁচটি স্তম্ভ হলো: কালেমা, নামাজ, রোজা, হজ ও জাকাত।',
            'points' => 5,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Create enrollment for test student
        $enrollment = CourseEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()->subWeeks(2),
            'progress_percentage' => 100,
            'is_completed' => true,
            'completed_at' => now()->subDays(3),
            'final_score' => 85,
            'certificate_issued' => true,
            'last_accessed_at' => now()->subHours(5),
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
            'completion_date' => $enrollment->completed_at,
            'issue_date' => $enrollment->completed_at,
            'is_revoked' => false,
        ]);

        echo "Test content seeding completed!\n";
        echo "- Course modules and lessons created\n";
        echo "- Quiz with 3 questions created\n";
        echo "- Student enrollment and certificate created\n";
        echo "- Test certificate verification code: TEST12345678\n";
        echo "\nQuiz ID: " . $quiz->id . " (for testing quiz routes)\n";
    }
}