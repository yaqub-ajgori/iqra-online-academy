<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use App\Models\Quiz;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Models\CourseReview;
use App\Models\QuizQuestion;// ...existing code...

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
        $teacher = \App\Models\Teacher::factory()->create();

        // --- Realistic Islamic Courses, Modules, Lessons, Quizzes ---
        $courseData = [
            [
                'title' => 'Tajweed: The Art of Quranic Recitation',
                'category' => 'Quran',
                'description' => 'Master the rules of Tajweed and recite the Quran beautifully.',
                'modules' => [
                    ['title' => 'Introduction to Tajweed', 'lessons' => ['What is Tajweed?', 'Importance of Tajweed']],
                    ['title' => 'Makharij & Sifaat', 'lessons' => ['Points of Articulation', 'Characteristics of Letters']],
                ],
                'quiz' => [
                    ['question' => 'What is Tajweed?', 'answer' => 'Rules of Quranic recitation'],
                    ['question' => 'Name a Makharij.', 'answer' => 'Throat'],
                ],
            ],
            [
                'title' => '40 Hadith of Imam Nawawi',
                'category' => 'Hadith',
                'description' => 'Study the famous 40 Hadith collection with explanation.',
                'modules' => [
                    ['title' => 'Introduction', 'lessons' => ['Who was Imam Nawawi?', 'Significance of the 40 Hadith']],
                    ['title' => 'Selected Hadiths', 'lessons' => ['Hadith 1: Intentions', 'Hadith 2: Islam, Iman, Ihsan']],
                ],
                'quiz' => [
                    ['question' => 'Who compiled the 40 Hadith?', 'answer' => 'Imam Nawawi'],
                    ['question' => 'What is the first hadith about?', 'answer' => 'Intentions'],
                ],
            ],
            [
                'title' => 'Introduction to Fiqh',
                'category' => 'Fiqh',
                'description' => 'Learn the basics of Islamic jurisprudence.',
                'modules' => [
                    ['title' => 'Sources of Fiqh', 'lessons' => ['Quran', 'Sunnah', 'Ijma', 'Qiyas']],
                    ['title' => 'Schools of Thought', 'lessons' => ['Hanafi', 'Shafi', 'Maliki', 'Hanbali']],
                ],
                'quiz' => [
                    ['question' => 'Name a source of Fiqh.', 'answer' => 'Quran'],
                    ['question' => 'How many main madhabs?', 'answer' => 'Four'],
                ],
            ],
            [
                'title' => 'Aqeedah: Islamic Creed',
                'category' => 'Aqeedah',
                'description' => 'Understand the core beliefs of Islam.',
                'modules' => [
                    ['title' => 'Pillars of Iman', 'lessons' => ['Belief in Allah', 'Belief in Angels', 'Belief in Books']],
                    ['title' => 'Tawheed', 'lessons' => ['Types of Tawheed', 'Shirk']],
                ],
                'quiz' => [
                    ['question' => 'What is Tawheed?', 'answer' => 'Oneness of Allah'],
                    ['question' => 'How many pillars of Iman?', 'answer' => 'Six'],
                ],
            ],
            [
                'title' => 'History of the Prophets',
                'category' => 'History',
                'description' => 'Explore the stories of the Prophets in Islam.',
                'modules' => [
                    ['title' => 'Adam to Ibrahim', 'lessons' => ['Story of Adam', 'Story of Ibrahim']],
                    ['title' => 'Musa & Isa', 'lessons' => ['Story of Musa', 'Story of Isa']],
                ],
                'quiz' => [
                    ['question' => 'Who was the first prophet?', 'answer' => 'Adam'],
                    ['question' => 'Who split the sea?', 'answer' => 'Musa'],
                ],
            ],
            [
                'title' => 'Arabic Language Basics',
                'category' => 'Arabic Language',
                'description' => 'Start your journey to understanding Arabic.',
                'modules' => [
                    ['title' => 'Alphabet', 'lessons' => ['Arabic Letters', 'Pronunciation']],
                    ['title' => 'Basic Grammar', 'lessons' => ['Nouns & Verbs', 'Simple Sentences']],
                ],
                'quiz' => [
                    ['question' => 'How many Arabic letters?', 'answer' => '28'],
                    ['question' => 'What is a noun in Arabic?', 'answer' => 'Ism'],
                ],
            ],
        ];

        $categories = array_unique(array_column($courseData, 'category'));
        $courseCategories = collect($categories)->mapWithKeys(fn($cat) => [
            $cat => CourseCategory::factory()->create(['name' => $cat])
        ]);

        foreach ($courseData as $data) {
            $course = Course::factory()->create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . uniqid(),
                'full_description' => $data['description'],
                'category_id' => $courseCategories[$data['category']]->id,
                'level' => 'beginner',
                'instructor_id' => $teacher->id,
                'price' => 0,
                'currency' => 'BDT',
                'discount_price' => null,
                'discount_expires_at' => null,
                'duration_in_minutes' => 1000,
                'status' => 'published',
                'is_featured' => false,
                'is_free' => false,
                'published_at' => now(),
            ]);
            foreach ($data['modules'] as $mod) {
                $module = CourseModule::factory()->create([
                    'course_id' => $course->id,
                    'title' => $mod['title'],
                ]);
                foreach ($mod['lessons'] as $lessonTitle) {
                    CourseLesson::factory()->create([
                        'module_id' => $module->id,
                        'course_id' => $course->id,
                        'title' => $lessonTitle,
                        'content' => "Lesson: $lessonTitle",
                        'description' => 'Short description for ' . $lessonTitle,
                        'type' => 'text',
                        'video_url' => null,
                        'file_path' => null,
                        'is_preview' => false,
                        'sort_order' => 0,
                        'is_active' => true,
                    ]);
                }
            }
            $quiz = Quiz::factory()->create([
                'course_id' => $course->id,
                'lesson_id' => null,
                'title' => 'Quiz for ' . $data['title'],
                'description' => 'Quiz for ' . $data['title'],
                'passing_score' => 70,
                'time_limit_minutes' => 30,
                'max_attempts' => 3,
                'status' => 'active',
                'sort_order' => 0,
            ]);
            foreach ($data['quiz'] as $q) {
                QuizQuestion::factory()->create([
                    'quiz_id' => $quiz->id,
                    'question' => $q['question'],
                    'type' => 'multiple_choice',
                    'options' => json_encode(['Option 1', 'Option 2', 'Option 3', 'Option 4']),
                    'correct_answer' => $q['answer'],
                    'points' => 1,
                    'sort_order' => 0,
                ]);
            }
        }

        // --- 12 Real Islamic Blog Articles ---
        $blogArticles = [
            ['title' => 'The Virtues of Ramadan', 'category' => 'Fiqh', 'content' => 'Ramadan is a blessed month...'],
            ['title' => 'The Life of Prophet Muhammad (SAW)', 'category' => 'History', 'content' => 'The Prophet Muhammad (SAW) was born in Makkah...'],
            ['title' => 'Understanding Tawheed', 'category' => 'Aqeedah', 'content' => 'Tawheed is the oneness of Allah...'],
            ['title' => 'The Importance of Salah', 'category' => 'Fiqh', 'content' => 'Salah is the second pillar of Islam...'],
            ['title' => 'Stories of the Prophets: Musa (AS)', 'category' => 'History', 'content' => 'Musa (AS) was sent to free the Children of Israel...'],
            ['title' => 'The Etiquettes of Seeking Knowledge', 'category' => 'Hadith', 'content' => 'Seeking knowledge is an obligation...'],
            ['title' => 'The Miracle of the Quran', 'category' => 'Quran', 'content' => 'The Quran is the final revelation...'],
            ['title' => 'The Angels in Islam', 'category' => 'Aqeedah', 'content' => 'Angels are created from light...'],
            ['title' => 'Learning Arabic: Why and How', 'category' => 'Arabic Language', 'content' => 'Arabic is the language of the Quran...'],
            ['title' => 'The Five Pillars of Islam', 'category' => 'Aqeedah', 'content' => 'The five pillars are Shahada, Salah, Zakah, Sawm, Hajj...'],
            ['title' => 'The Compilation of Hadith', 'category' => 'Hadith', 'content' => 'Hadith were compiled by great scholars...'],
            ['title' => 'Women in Islam', 'category' => 'History', 'content' => 'Women have a high status in Islam...'],
        ];
        $blogCategories = collect($categories)->mapWithKeys(fn($cat) => [
            $cat => BlogCategory::factory()->create(['name' => $cat])
        ]);
        foreach ($blogArticles as $article) {
            BlogPost::factory()->create([
                'title' => $article['title'],
                'category_id' => $blogCategories[$article['category']]->id,
                'content' => $article['content'],
                'status' => 'published',
            ]);
        }

        // --- 6 Student Reviews with Different Statuses ---
        $statuses = ['pending', 'approved', 'rejected', 'under_review', 'flagged', 'archived'];
        $students = Student::factory()->count(6)->create();
        $courses = Course::all();
        foreach (range(0, 5) as $i) {
            CourseReview::factory()->create([
                'student_id' => $students[$i]->id,
                'user_id' => $students[$i]->user_id,
                'course_id' => $courses->random()->id,
                'review_text' => 'This is a test review with status: ' . $statuses[$i],
                'rating' => rand(3,5),
                'title' => 'Review Title',
                'status' => 'pending',
                'approved_at' => null,
                'approved_by' => null,
                'moderation_notes' => null,
                'is_featured' => false,
                'is_verified_purchase' => false,
                'helpful_count' => 0,
                'reported_count' => 0,
                'metadata' => json_encode(['completion_status' => 'completed']),
            ]);
        }
    }
}