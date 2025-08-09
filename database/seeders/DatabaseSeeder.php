<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use App\Models\Payment;
use App\Models\CourseEnrollment;
use App\Models\LessonProgress;
use App\Models\CourseReview;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogPost;
use App\Models\BlogComment;
use App\Models\BlogReaction;
use App\Models\Donation;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\Certificate;
use App\Models\ContactMessage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear tables for idempotent seeding in local/dev
        if (app()->environment(['local', 'development', 'testing'])) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            foreach ([
                'course_review_helpful_votes', 'course_reviews', 'lesson_progress', 'course_lessons', 'course_modules',
                'course_enrollments', 'payments', 'certificates', 'courses', 'course_categories',
                'blog_post_tags', 'blog_reactions', 'blog_comments', 'blog_posts', 'blog_tags', 'blog_categories',
                'donations', 'quiz_attempts', 'quiz_questions', 'quizzes',
                'students', 'teachers', 'user_roles', 'users',
            ] as $table) {
                DB::table($table)->truncate();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // 1) Core users and roles (always create 1 admin, 1 teacher, 1 student with known credentials)
        $admin = User::factory()->create([
            'name' => 'Md Saeedul Mostafa',
            'email' => 'saeedul.mostafa@gmail.com',
            'password' => Hash::make('saeedul.mostafa@gmail.com'),
        ]);
        UserRole::factory()->create(['user_id' => $admin->id, 'role_type' => 'admin']);
    }
}