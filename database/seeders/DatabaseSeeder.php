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

        // 1) Core users and roles
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@iqra.test',
            'password' => Hash::make('password'),
        ]);
        UserRole::factory()->create(['user_id' => $admin->id, 'role_type' => 'admin']);

        // Teachers and Students
        $teachers = Teacher::factory()->count(1)->create();
        foreach ($teachers as $teacher) {
            UserRole::factory()->create(['user_id' => $teacher->user_id, 'role_type' => 'teacher']);
        }

        $students = Student::factory()->count(1)->create();
        foreach ($students as $student) {
            UserRole::factory()->create(['user_id' => $student->user_id, 'role_type' => 'student']);
        }

        // 2) Blog
        $blogCategories = BlogCategory::factory()->count(6)->create();
        $blogTags = BlogTag::factory()->count(10)->create();
        $blogPosts = BlogPost::factory()->count(20)->create([
            'category_id' => fn () => $blogCategories->random()->id,
        ]);
        // Attach tags and reactions/comments
        foreach ($blogPosts as $post) {
            $post->tags()->attach($blogTags->random(rand(2, 5))->pluck('id'));
            // Comments
            BlogComment::factory()->count(rand(1, 4))->create([
                'blog_post_id' => $post->id,
                'user_id' => $students->random()->user_id,
            ]);
            // Reactions
            BlogReaction::factory()->count(rand(5, 20))->create([
                'blog_post_id' => $post->id,
                'user_id' => $students->random()->user_id,
            ]);
        }

        // 3) Courses & Curriculum
        $categories = CourseCategory::factory()->count(6)->create();
        $courses = Course::factory()->count(12)->create([
            'category_id' => fn () => $categories->random()->id,
            'instructor_id' => fn () => $teachers->random()->id,
            'status' => 'published',
            'published_at' => now()->subDays(rand(1, 120)),
        ]);

        // Create modules and lessons per course
        foreach ($courses as $course) {
            $modules = CourseModule::factory()->count(rand(3, 6))->create(['course_id' => $course->id]);
            foreach ($modules as $module) {
                CourseLesson::factory()->count(rand(3, 8))->create([
                    'module_id' => $module->id,
                    'course_id' => $course->id,
                ]);
            }
        }

        // 4) Payments & Enrollments
        // Create completed payments and enrollments for students across courses
        foreach ($students as $student) {
            // Each student enrolls in 1-4 courses
            $selectedCourses = $courses->random(rand(1, 4));
            foreach ($selectedCourses as $course) {
                $isPaid = !$course->is_free;
                $payment = null;
                if ($isPaid) {
                    $payment = Payment::factory()->completed()->create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'amount' => $course->price,
                        'currency' => $course->currency,
                    ]);
                }

                $enrollment = CourseEnrollment::factory()->create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'enrollment_type' => $isPaid ? 'paid' : 'free',
                    'payment_id' => $payment?->id,
                    'amount_paid' => $isPaid ? $course->price : 0,
                    'currency' => $course->currency,
                    'payment_status' => $isPaid ? 'completed' : 'completed',
                    'progress_percentage' => rand(0, 100),
                    'lessons_completed' => rand(0, 12),
                    'is_active' => true,
                    'is_completed' => false,
                ]);

                // Simulate lesson progress
                $courseLessons = CourseLesson::where('course_id', $course->id)->inRandomOrder()->get();
                $takeLessons = min(8, $courseLessons->count());
                if ($takeLessons > 0) {
                    $pickLessons = $takeLessons === 1 ? 1 : rand(1, $takeLessons);
                    $lessonsSubset = $courseLessons->random($pickLessons);
                    if ($pickLessons === 1) {
                        $lessonsSubset = collect([$lessonsSubset]);
                    }
                    foreach ($lessonsSubset as $lesson) {
                    LessonProgress::factory()->create([
                        'enrollment_id' => $enrollment->id,
                        'lesson_id' => $lesson->id,
                        'is_completed' => fake()->boolean(50),
                        'completion_percentage' => fake()->numberBetween(0, 100),
                        'time_spent_seconds' => fake()->numberBetween(120, 3600),
                        'started_at' => now()->subDays(rand(1, 60)),
                        'completed_at' => fake()->boolean(40) ? now()->subDays(rand(0, 30)) : null,
                        'last_accessed_at' => now()->subDays(rand(0, 5)),
                    ]);
                    }
                }

                // Randomly complete some enrollments and issue certificates via model logic
                if (fake()->boolean(40)) {
                    $enrollment->update([
                        'is_completed' => true,
                        'progress_percentage' => 100,
                        'lessons_completed' => CourseLesson::where('course_id', $course->id)->count(),
                    ]);
                    // Trigger certificate creation using domain logic
                    $enrollment->markAsCompleted();
                }
            }
        }

        // 5) Reviews & helpful votes
        foreach (CourseEnrollment::where('is_completed', true)->get() as $completed) {
            CourseReview::factory()->create([
                'course_id' => $completed->course_id,
                'student_id' => $completed->student_id,
                'user_id' => $completed->student->user_id,
                'status' => 'approved',
                'approved_at' => now()->subDays(rand(1, 30)),
                'is_verified_purchase' => true,
            ]);
        }

        // 6) Donations (site-wide)
        if (class_exists(Donation::class)) {
            Donation::factory()->count(15)->create();
        }

        // 6b) Contact messages
        if (class_exists(ContactMessage::class)) {
            \App\Models\ContactMessage::factory()->count(10)->create();
        }

        // 7) Quizzes with questions and attempts
        foreach ($courses as $course) {
            $courseLessons = CourseLesson::where('course_id', $course->id)->inRandomOrder()->take(1)->get();
            foreach ($courseLessons as $lesson) {
                $quiz = Quiz::create([
                    'title' => 'Quiz for ' . $course->title,
                    'description' => 'Basic understanding check.',
                    'course_id' => $course->id,
                    'lesson_id' => $lesson->id,
                    'passing_score' => 60,
                    'time_limit_minutes' => 15,
                    'max_attempts' => 3,
                    'status' => 'active',
                    'sort_order' => 1,
                ]);

                // Add a few questions
                $questionIds = [];
                for ($i = 1; $i <= 5; $i++) {
                    $question = \App\Models\QuizQuestion::create([
                        'quiz_id' => $quiz->id,
                        'question' => 'Question ' . $i . ' for ' . $course->title,
                        'type' => 'multiple_choice',
                        'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
                        'correct_answer' => 0,
                        'points' => 10,
                        'sort_order' => $i,
                    ]);
                    $questionIds[] = $question->id;
                }

                // Create attempts for a few students
                $attemptersCount = min($students->count(), rand(1, 3));
                $attempters = $attemptersCount === 1 ? collect([$students->random()]) : $students->random($attemptersCount);
                foreach ($attempters as $student) {
                    $startedAt = now()->subDays(rand(0, 30))->subMinutes(rand(5, 60));
                    $completedAt = (clone $startedAt)->addMinutes(rand(5, 30));
                    // Build random answers map
                    $answers = [];
                    foreach ($questionIds as $qid) {
                        $answers[$qid] = rand(0, 3);
                    }

                    \App\Models\QuizAttempt::create([
                        'quiz_id' => $quiz->id,
                        'user_id' => $student->user_id,
                        'answers' => $answers,
                        'score' => rand(0, 100),
                        'correct_answers' => rand(0, 5),
                        'total_questions' => 5,
                        'is_passed' => fake()->boolean(50),
                        'started_at' => $startedAt,
                        'completed_at' => $completedAt,
                        'time_spent_seconds' => $completedAt->diffInSeconds($startedAt),
                    ]);
                }
            }
        }

        // 8) Helpful votes for reviews
        $reviews = CourseReview::inRandomOrder()->take(25)->get();
        foreach ($reviews as $review) {
            // votes by random students
            $votersCount = min($students->count(), rand(1, 3));
            $voters = $votersCount === 1 ? collect([$students->random()]) : $students->random($votersCount);
            foreach ($voters as $student) {
                \App\Models\CourseReviewHelpfulVote::create([
                    'review_id' => $review->id,
                    'user_id' => $student->user_id,
                    'ip_address' => null,
                    'session_id' => null,
                ]);
            }
        }

        // Ensure at least one certificate exists and is valid
        if (Certificate::count() === 0) {
            $anyEnrollment = CourseEnrollment::first();
            if ($anyEnrollment) {
                Certificate::createForEnrollment($anyEnrollment);
            }
        }
    }
}