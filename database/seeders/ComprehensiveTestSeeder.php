<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogReaction;
use App\Models\BlogTag;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\CourseReview;
use App\Models\Donation;
use App\Models\LessonProgress;
use App\Models\Payment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComprehensiveTestSeeder extends Seeder
{
    private $testStudent;
    private $testCourses = [];

    /**
     * Run the comprehensive test data seeder.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting comprehensive test data seeding...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->truncateAllTables();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Step 1: Create Users and Roles (Foundation)
        $this->command->info('👥 Creating users and roles...');
        $this->createUsersAndRoles();

        // Step 2: Create Reference Data
        $this->command->info('📚 Creating reference data...');
        $this->createReferenceData();

        // Step 3: Create Course Structure
        $this->command->info('🎓 Creating course structure...');
        $this->createCourseStructure();

        // Step 4: Create Blog Content
        $this->command->info('✍️ Creating blog content...');
        $this->createBlogContent();

        // Step 5: Enroll test student in specific courses
        $this->command->info('🎯 Enrolling test student in courses...');
        $this->enrollTestStudent();

        // Step 6: Create Payments and Enrollments (Manual)
        $this->command->info('💰 Creating payments and enrollments...');
        $this->createPaymentsAndEnrollments();

        // Step 7: Create Learning Progress
        $this->command->info('📈 Creating learning progress...');
        $this->createLearningProgress();

        // Step 8: Create Quizzes and Assessments
        $this->command->info('📝 Creating quizzes and assessments...');
        $this->createQuizzesAndAssessments();

        // Step 9: Create Certificates
        $this->command->info('🏆 Creating certificates...');
        $this->createCertificates();

        // Step 10: Create Reviews and Engagement
        $this->command->info('⭐ Creating reviews and engagement data...');
        $this->createReviewsAndEngagement();

        // Step 11: Create Donations
        $this->command->info('💝 Creating donations...');
        $this->createDonations();

        $this->command->info('✅ Comprehensive test data seeding completed successfully!');
        $this->displaySummary();
    }

    /**
     * Truncate all tables to start fresh.
     */
    private function truncateAllTables(): void
    {
        $tables = [
            'blog_reactions', 'blog_comments', 'blog_post_tags', 'blog_posts', 'blog_tags', 'blog_categories',
            'donations', 'certificates', 'quiz_attempts', 'quiz_questions', 'quizzes',
            'lesson_progress', 'course_enrollments', 'payments', 'course_lessons', 'course_modules',
            'courses', 'course_categories', 'teachers', 'students', 'user_roles', 'users'
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
    }

    /**
     * Create users with different roles.
     */
    private function createUsersAndRoles(): void
    {
        // Create Admin User
        $adminUser = User::factory()->admin()->create([
            'name' => 'System Admin',
            'email' => 'admin@iqra.academy',
        ]);
        UserRole::factory()->admin()->create(['user_id' => $adminUser->id]);

        // Create Teachers (5)
        $teachers = User::factory()->count(5)->create();
        foreach ($teachers as $teacher) {
            UserRole::factory()->teacher()->create(['user_id' => $teacher->id]);
            Teacher::factory()->create([
                'user_id' => $teacher->id,
                'full_name' => $teacher->name,
            ]);
        }

        // Create Students (50)
        $students = User::factory()->count(50)->create();
        foreach ($students as $student) {
            UserRole::factory()->student()->create(['user_id' => $student->id]);
            Student::factory()->create([
                'user_id' => $student->id,
                'full_name' => $student->name,
            ]);
        }

        // Create some test users with known credentials
        $testStudent = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student@iqra.academy',
        ]);
        UserRole::factory()->student()->create(['user_id' => $testStudent->id]);
        $testStudentRecord = Student::factory()->create([
            'user_id' => $testStudent->id,
            'full_name' => $testStudent->name,
        ]);

        // Store test student for later enrollment
        $this->testStudent = $testStudentRecord;

        $testTeacher = User::factory()->create([
            'name' => 'Test Teacher',
            'email' => 'teacher@iqra.academy',
        ]);
        UserRole::factory()->teacher()->create(['user_id' => $testTeacher->id]);
        Teacher::factory()->create([
            'user_id' => $testTeacher->id,
            'full_name' => $testTeacher->name,
        ]);
    }

    /**
     * Create reference data (categories, tags).
     */
    private function createReferenceData(): void
    {
        // Course Categories
        CourseCategory::factory()->count(8)->create();

        // Blog Categories
        BlogCategory::factory()->count(8)->create();

        // Blog Tags
        BlogTag::factory()->count(25)->create();
    }

    /**
     * Create 3 comprehensive courses for thorough testing.
     */
    private function createCourseStructure(): void
    {
        $teachers = Teacher::all();
        $categories = CourseCategory::all();

        // Create exactly 3 comprehensive courses
        $this->testCourses[] = $this->createQuranCourse($categories->first(), $teachers->first());
        $this->testCourses[] = $this->createHadithCourse($categories->skip(1)->first(), $teachers->skip(1)->first());
        $this->testCourses[] = $this->createFiqhCourse($categories->skip(2)->first(), $teachers->skip(2)->first());
    }

    private function createQuranCourse($category, $teacher): Course
    {
        $course = Course::create([
            'category_id' => $category->id,
            'instructor_id' => $teacher->id,
            'title' => 'সম্পূর্ণ কুরআন শিক্ষা - তিলাওয়াত থেকে তাফসীর',
            'slug' => 'complete-quran-learning',
            'full_description' => 'এই কোর্সে আপনি কুরআন তিলাওয়াত, তাজভীদ, হিফজ এবং মৌলিক তাফসীর শিখবেন। প্রাথমিক থেকে উন্নত পর্যায় পর্যন্ত সম্পূর্ণ কুরআন শিক্ষা।',
            'price' => 3000.00,
            'discount_price' => 2500.00,
            'duration_in_minutes' => 24 * 7 * 60, // 24 weeks in minutes
            'level' => 'beginner',
            'status' => 'published',
            'is_featured' => true,
            'thumbnail_image' => 'courses/complete-quran.jpg',
            'published_at' => now(),
        ]);

        $this->createModulesAndLessons($course, [
            'কুরআনের পরিচয় ও গুরুত্ব' => [
                'কুরআনের ইতিহাস ও নাজিলের ধারাবাহিকতা' => ['type' => 'video', 'duration' => 45],
                'কুরআনের বৈশিষ্ট্য ও অলৌকিকত্ব' => ['type' => 'text', 'duration' => 30],
                'কুরআন অধ্যয়নের আদব ও নিয়মকানুন' => ['type' => 'mixed', 'duration' => 35],
                'প্রথম অধ্যায় মূল্যায়ন' => ['type' => 'quiz', 'duration' => 20],
            ],
            'আরবি বর্ণমালা ও তাজভীদ' => [
                'আরবি বর্ণমালা পরিচিতি ও উচ্চারণ' => ['type' => 'video', 'duration' => 50],
                'হরকত ও তানভীনের নিয়ম' => ['type' => 'video', 'duration' => 40],
                'মাখরাজ ও সিফাতের বিস্তারিত আলোচনা' => ['type' => 'mixed', 'duration' => 60],
                'মদ, গুন্নাহ ও ইদগামের নিয়ম' => ['type' => 'video', 'duration' => 55],
                'তাজভীদের ব্যবহারিক অনুশীলন' => ['type' => 'video', 'duration' => 45],
                'তাজভীদ পরীক্ষা' => ['type' => 'quiz', 'duration' => 25],
            ],
            'কুরআন তিলাওয়াত ও মুখস্থকরণ' => [
                'সূরা ফাতিহার বিস্তারিত শিক্ষা' => ['type' => 'video', 'duration' => 40],
                'সূরা বাকারার প্রথম ১০ আয়াত' => ['type' => 'mixed', 'duration' => 50],
                'ছোট সূরাসমূহের তিলাওয়াত (১০৮-১১৪)' => ['type' => 'video', 'duration' => 60],
                'দৈনিক তিলাওয়াতের নিয়ম ও আদব' => ['type' => 'text', 'duration' => 25],
                'হিফজের কৌশল ও পদ্ধতি' => ['type' => 'pdf', 'duration' => 35],
                'তিলাওয়াত মূল্যায়ন' => ['type' => 'quiz', 'duration' => 30],
            ],
        ]);

        return $course;
    }

    private function createHadithCourse($category, $teacher): Course
    {
        $course = Course::create([
            'category_id' => $category->id,
            'instructor_id' => $teacher->id,
            'title' => 'হাদীস শাস্ত্রে দক্ষতা অর্জন - সহীহ হাদীস অধ্যয়ন',
            'slug' => 'hadith-science-mastery',
            'full_description' => 'এই কোর্সে সহীহ হাদীসের সংগ্রহ, যাচাই-বাছাই এবং ব্যাখ্যার পদ্ধতি শিখবেন। সহীহ বুখারী, মুসলিম সহ প্রধান হাদীস গ্রন্থের পরিচয় ও নির্বাচিত হাদীস অধ্যয়ন।',
            'price' => 3500.00,
            'discount_price' => 2800.00,
            'duration_in_minutes' => 20 * 7 * 60, // 20 weeks in minutes
            'level' => 'intermediate',
            'status' => 'published',
            'is_featured' => true,
            'thumbnail_image' => 'courses/hadith-mastery.jpg',
            'published_at' => now(),
        ]);

        $this->createModulesAndLessons($course, [
            'হাদীস শাস্ত্রের মূলনীতি' => [
                'হাদীসের সংজ্ঞা ও প্রকারভেদ' => ['type' => 'text', 'duration' => 35],
                'সনদ ও মতনের পরিচয়' => ['type' => 'video', 'duration' => 40],
                'হাদীসের গ্রহণযোগ্যতার শর্তাবলী' => ['type' => 'mixed', 'duration' => 45],
                'জাল ও দুর্বল হাদীস চেনার উপায়' => ['type' => 'pdf', 'duration' => 30],
                'মূলনীতি পরীক্ষা' => ['type' => 'quiz', 'duration' => 25],
            ],
            'প্রধান হাদীস গ্রন্থ পরিচিতি' => [
                'সিহাহ সিত্তার ইতিহাস ও বৈশিষ্ট্য' => ['type' => 'text', 'duration' => 40],
                'সহীহ বুখারী ও মুসলিমের পরিচয়' => ['type' => 'video', 'duration' => 50],
                'সুনানে আরবাআর গুরুত্ব' => ['type' => 'mixed', 'duration' => 35],
                'মুসনাদ ও মুজামের পার্থক্য' => ['type' => 'text', 'duration' => 25],
                'হাদীস গ্রন্থ পরীক্ষা' => ['type' => 'quiz', 'duration' => 20],
            ],
            'আকীদা ও ইবাদতের হাদীস' => [
                'তাওহীদ বিষয়ক গুরুত্বপূর্ণ হাদীস' => ['type' => 'video', 'duration' => 55],
                'নামাজ সংক্রান্ত হাদীসসমূহ' => ['type' => 'mixed', 'duration' => 50],
                'রোজা ও হজ্জের হাদীস' => ['type' => 'video', 'duration' => 45],
                'যাকাত ও দানের হাদীস' => ['type' => 'text', 'duration' => 35],
                'ইবাদত পরীক্ষা' => ['type' => 'quiz', 'duration' => 30],
            ],
        ]);

        return $course;
    }

    private function createFiqhCourse($category, $teacher): Course
    {
        $course = Course::create([
            'category_id' => $category->id,
            'instructor_id' => $teacher->id,
            'title' => 'ইসলামী ফিকহের পূর্ণাঙ্গ অধ্যয়ন - ইবাদত থেকে মুয়ামালাত',
            'slug' => 'comprehensive-islamic-fiqh',
            'full_description' => 'এই কোর্সে ইসলামী আইনশাস্ত্রের সকল শাখা নিয়ে বিস্তারিত আলোচনা করা হবে। ইবাদত, মুয়ামালাত, পারিবারিক আইন এবং ফৌজদারি আইনের গভীর অধ্যয়ন।',
            'price' => 4000.00,
            'discount_price' => 3200.00,
            'duration_in_minutes' => 28 * 7 * 60, // 28 weeks in minutes
            'level' => 'advanced',
            'status' => 'published',
            'is_featured' => false,
            'thumbnail_image' => 'courses/comprehensive-fiqh.jpg',
            'published_at' => now(),
        ]);

        $this->createModulesAndLessons($course, [
            'উসূলুল ফিকহ - ফিকহের মূলনীতি' => [
                'ইসলামী আইনের উৎসসমূহ' => ['type' => 'text', 'duration' => 45],
                'কুরআন ও সুন্নাহর বিধানগত মর্যাদা' => ['type' => 'video', 'duration' => 55],
                'ইজমা ও কিয়াসের পরিচয়' => ['type' => 'mixed', 'duration' => 50],
                'ইজতিহাদ ও তাকলীদের বিধান' => ['type' => 'pdf', 'duration' => 40],
                'উসূল পরীক্ষা' => ['type' => 'quiz', 'duration' => 30],
            ],
            'তাহারাত ও পবিত্রতার বিধান' => [
                'পবিত্রতার গুরুত্ব ও প্রকারভেদ' => ['type' => 'video', 'duration' => 40],
                'অযু ও গোসলের বিস্তারিত বিধান' => ['type' => 'mixed', 'duration' => 55],
                'তায়াম্মুম ও নাপাকী দূরীকরণ' => ['type' => 'text', 'duration' => 35],
                'নারীদের বিশেষ বিধান' => ['type' => 'pdf', 'duration' => 30],
                'তাহারাত পরীক্ষা' => ['type' => 'quiz', 'duration' => 25],
            ],
            'সালাতের পূর্ণাঙ্গ আহকাম' => [
                'নামাজের শর্তাবলী ও রুকনসমূহ' => ['type' => 'video', 'duration' => 50],
                'পাঁচ ওয়াক্তের বিস্তারিত বিধান' => ['type' => 'mixed', 'duration' => 60],
                'জামাআত ও জুমার নামাজ' => ['type' => 'text', 'duration' => 45],
                'ঈদ, জানাজা ও বিশেষ নামাজ' => ['type' => 'video', 'duration' => 40],
                'সালাত পরীক্ষা' => ['type' => 'quiz', 'duration' => 30],
            ],
        ]);

        return $course;
    }

    private function createModulesAndLessons(Course $course, array $moduleData): void
    {
        $moduleOrder = 1;
        
        foreach ($moduleData as $moduleName => $lessons) {
            $module = CourseModule::create([
                'course_id' => $course->id,
                'title' => $moduleName,
                'sort_order' => $moduleOrder++,
            ]);

            $lessonOrder = 1;
            foreach ($lessons as $lessonName => $lessonData) {
                $lesson = CourseLesson::create([
                    'module_id' => $module->id,
                    'course_id' => $course->id,
                    'title' => $lessonName,
                    'description' => $lessonName . ' সম্পর্কে সম্পূর্ণ ও বিস্তারিত শিক্ষা',
                    'lesson_type' => $lessonData['type'],
                    'content' => $this->generateLessonContent($lessonData['type'], $lessonName),
                    'video_url' => $this->getVideoUrl($lessonData['type']),
                    'attachments' => $this->getAttachments($lessonData['type'], $lessonName),
                    'minimum_time_seconds' => $lessonData['duration'] * 60,
                    'sort_order' => $lessonOrder++,
                    'is_active' => true,
                    'is_preview' => $lessonOrder <= 2, // First 2 lessons are preview
                ]);

                // Create quiz for quiz lessons
                if ($lessonData['type'] === 'quiz') {
                    $this->createQuizForLesson($lesson, $course);
                }
            }
        }
    }

    private function getVideoUrl(string $type): ?string
    {
        if ($type === 'video' || $type === 'mixed') {
            $videoUrls = [
                'https://www.youtube.com/watch?v=7d16CpWp-ok', // Quran recitation
                'https://www.youtube.com/watch?v=Dj7dcXNtVqE', // Islamic lecture
                'https://www.youtube.com/watch?v=TpcbfxtdoI8', // Arabic learning
                'https://www.youtube.com/watch?v=FZ8ME_qRHyY', // Tajweed lesson
                'https://www.youtube.com/watch?v=POgFu4RE-5w', // Islamic education
            ];
            return $videoUrls[array_rand($videoUrls)];
        }
        return null;
    }

    private function getAttachments(string $type, string $title): ?string
    {
        if ($type === 'pdf' || $type === 'mixed') {
            return json_encode([
                [
                    'name' => $title . ' - পাঠ্য উপকরণ.pdf',
                    'url' => 'lessons/' . str_replace(' ', '_', $title) . '.pdf',
                    'size' => '2.5 MB'
                ],
                [
                    'name' => $title . ' - অনুশীলনী.pdf', 
                    'url' => 'lessons/' . str_replace(' ', '_', $title) . '_exercise.pdf',
                    'size' => '1.2 MB'
                ]
            ]);
        }
        return null;
    }

    private function generateLessonContent(string $type, string $title): string
    {
        $contents = [
            'video' => "# {$title}\n\nএই ভিডিও পাঠে আমরা **{$title}** সম্পর্কে বিস্তারিত আলোচনা করব।\n\n## পাঠের উদ্দেশ্য:\n- {$title} এর মূল ধারণা বোঝা\n- ইসলামী শিক্ষার আলোকে বিশ্লেষণ\n- ব্যবহারিক প্রয়োগের উপায় জানা\n\n## নির্দেশনা:\nভিডিওটি মনোযোগ সহকারে দেখুন এবং প্রয়োজনীয় নোট নিন।",
            'text' => "# {$title}\n\n## ভূমিকা\n\n{$title} ইসলামী জ্ঞানের একটি গুরুত্বপূর্ণ বিষয়।\n\n### মূল বিষয়সমূহ:\n1. **প্রাথমিক ধারণা** - কুরআন ও হাদীসের আলোকে\n2. **বিস্তারিত ব্যাখ্যা** - আলেমগণের মতামত\n3. **ব্যবহারিক প্রয়োগ** - আধুনিক জীবনে প্রয়োগ",
            'audio' => "# {$title} - অডিও পাঠ\n\nএই পাঠে **{$title}** সম্পর্কে বিস্তারিত অডিও আলোচনা রয়েছে।\n\n## শোনার নির্দেশনা:\n1. মনোযোগ দিয়ে শুনুন\n2. গুরুত্বপূর্ণ পয়েন্ট নোট নিন\n3. প্রয়োজনে পুনরায় শুনুন",
            'pdf' => "# {$title} - PDF উপকরণ\n\nএই পাঠে **{$title}** সংক্রান্ত গুরুত্বপূর্ণ PDF ডকুমেন্ট রয়েছে।\n\n## ডাউনলোড করুন:\n- মূল পাঠ্য উপকরণ\n- অনুশীলনী ও কুইজ\n\n**PDF ফাইল ডাউনলোড করতে লিঙ্কে ক্লিক করুন।**",
            'mixed' => "# {$title} - মিশ্র পাঠ\n\nএই **মিশ্র পাঠে** ভিডিও, অডিও এবং PDF সবধরনের উপকরণ রয়েছে।\n\n## অধ্যয়নের ক্রম:\n1. প্রথমে ভিডিও দেখুন\n2. এরপর অডিও শুনুন\n3. সবশেষে PDF পড়ুন",
            'quiz' => "# {$title} - মূল্যায়ন পরীক্ষা\n\nএই কুইজে আপনার জ্ঞান ও দক্ষতা পরীক্ষা করুন।\n\n## পরীক্ষার নির্দেশনা:\n- সময়সীমার মধ্যে উত্তর দিন\n- সকল প্রশ্নের উত্তর দেওয়ার চেষ্টা করুন\n- পাসিং স্কোর: ৭০%"
        ];

        return $contents[$type] ?? $contents['text'];
    }

    private function createQuizForLesson(CourseLesson $lesson, Course $course): void
    {
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'title' => $lesson->title,
            'description' => $lesson->title . ' সম্পর্কে আপনার জ্ঞান ও বোঝাপড়া পরীক্ষা করুন',
            'type' => 'quiz',
            'time_limit_minutes' => rand(20, 40),
            'max_attempts' => 3,
            'passing_score' => 70.00,
            'randomize_questions' => false,
            'show_results_immediately' => true,
            'allow_review' => true,
            'is_active' => true,
            'sort_order' => $lesson->sort_order,
        ]);

        // Update lesson to link to quiz
        $lesson->update(['quiz_id' => $quiz->id]);

        // Create comprehensive questions for the quiz
        $this->createComprehensiveQuestions($quiz, $lesson->title, $course->title);
    }

    private function createComprehensiveQuestions(Quiz $quiz, string $lessonTitle, string $courseTitle): void
    {
        $questions = [
            // Multiple Choice Questions
            [
                'question' => "{$lessonTitle} সম্পর্কে কোনটি সবচেয়ে গুরুত্বপূর্ণ?",
                'type' => 'multiple_choice',
                'options' => ['কুরআনের নির্দেশনা', 'হাদীসের আলোকে আমল', 'আলেমদের মতামত', 'সবগুলোই গুরুত্বপূর্ণ'],
                'correct_answers' => [3],
                'explanation' => 'ইসলামী শিক্ষায় কুরআন, হাদীস এবং আলেমদের সহীহ মতামত সবই গুরুত্বপূর্ণ।',
                'points' => 2.00,
            ],
            [
                'question' => "ইসলামী জ্ঞান অর্জনে {$lessonTitle} এর ভূমিকা কেমন?",
                'type' => 'multiple_choice',
                'options' => ['অত্যাবশ্যক', 'গুরুত্বপূর্ণ', 'ঐচ্ছিক', 'অপ্রয়োজনীয়'],
                'correct_answers' => [0],
                'explanation' => 'ইসলামী জ্ঞানের প্রতিটি শাখাই মুসলিম জীবনে অত্যাবশ্যক।',
                'points' => 2.00,
            ],
            // True/False Questions
            [
                'question' => "{$lessonTitle} শুধুমাত্র আলেমদের জন্য প্রয়োজনীয়।",
                'type' => 'true_false',
                'options' => null,
                'correct_answers' => ['মিথ্যা'],
                'explanation' => 'ইসলামী জ্ঞান সকল মুসলিমের জন্য প্রয়োজনীয়।',
                'points' => 1.50,
            ],
            [
                'question' => "কুরআন ও হাদীস {$lessonTitle} বিষয়ের মূল উৎস।",
                'type' => 'true_false',
                'options' => null,
                'correct_answers' => ['সত্য'],
                'explanation' => 'হ্যাঁ, কুরআন ও হাদীস ইসলামী জ্ঞানের প্রধান উৎস।',
                'points' => 1.50,
            ],
            // Short Answer Question
            [
                'question' => "{$lessonTitle} এর দুইটি প্রধান উপকারিতা লিখুন।",
                'type' => 'short_answer',
                'options' => null,
                'correct_answers' => ['আধ্যাত্মিক উন্নতি', 'চরিত্র গঠন', 'জ্ঞান বৃদ্ধি', 'পরকালীন মুক্তি'],
                'explanation' => 'এই বিষয়ের অনেক উপকারিতা রয়েছে।',
                'points' => 3.00,
            ],
            // Essay Question
            [
                'question' => "আপনার দৈনন্দিন জীবনে '{$lessonTitle}' কীভাবে প্রয়োগ করতে পারেন? বিস্তারিত আলোচনা করুন।",
                'type' => 'essay',
                'options' => null,
                'correct_answers' => ['manual_grading_required'],
                'explanation' => 'এই রচনামূলক প্রশ্নের উত্তর শিক্ষক কর্তৃক মূল্যায়ন করা হবে।',
                'points' => 5.00,
            ],
        ];

        $sortOrder = 1;
        foreach ($questions as $questionData) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'type' => $questionData['type'],
                'options' => $questionData['options'],
                'correct_answers' => $questionData['correct_answers'],
                'explanation' => $questionData['explanation'],
                'points' => $questionData['points'],
                'sort_order' => $sortOrder++,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Enroll test student in specific courses for testing.
     */
    private function enrollTestStudent(): void
    {
        if (!$this->testStudent || empty($this->testCourses)) {
            return;
        }

        // Enroll test student in first 2 courses (Quran and Hadith)
        $coursesToEnroll = array_slice($this->testCourses, 0, 2);

        foreach ($coursesToEnroll as $course) {
            // Create payment for the course
            $payment = Payment::factory()->create([
                'student_id' => $this->testStudent->id,
                'course_id' => $course->id,
                'amount' => $course->discount_price ?? $course->price,
                'status' => 'completed',
            ]);

            // Create enrollment
            $enrollment = CourseEnrollment::factory()->create([
                'student_id' => $this->testStudent->id,
                'course_id' => $course->id,
                'payment_id' => $payment->id,
                'enrollment_type' => 'paid',
                'amount_paid' => $payment->amount,
                'payment_status' => 'completed',
                'is_active' => true,
            ]);

            // Create some lesson progress for testing
            $lessons = $course->lessons()->orderBy('sort_order')->take(5)->get();
            foreach ($lessons as $index => $lesson) {
                LessonProgress::factory()->create([
                    'lesson_id' => $lesson->id,
                    'enrollment_id' => $enrollment->id,
                    'is_completed' => $index < 3, // Complete first 3 lessons
                    'completion_percentage' => $index < 3 ? 100.00 : ($index == 3 ? 65.00 : 0.00),
                ]);
            }

            // Update enrollment progress
            $enrollment->updateProgress();

            $this->command->info("✅ Enrolled test student in: {$course->title}");
        }
    }

    /**
     * Create blog content with engagement.
     */
    private function createBlogContent(): void
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        $authors = User::whereHas('roles', function($q) {
            $q->whereIn('role_type', ['admin', 'teacher']);
        })->get();

        // Create 30 blog posts
        foreach ($categories as $category) {
            $postsCount = fake()->numberBetween(3, 5);
            
            for ($i = 0; $i < $postsCount; $i++) {
                $post = BlogPost::factory()->published()->create([
                    'category_id' => $category->id,
                    'author_id' => $authors->random()->id,
                ]);

                // Attach random tags (2-5 tags per post)
                $postTags = $tags->random(fake()->numberBetween(2, 5));
                foreach ($postTags as $tag) {
                    DB::table('blog_post_tags')->insert([
                        'blog_post_id' => $post->id,
                        'blog_tag_id' => $tag->id,
                    ]);
                }
            }
        }

        // Create some featured posts
        BlogPost::factory()->featured()->published()->count(3)->create([
            'category_id' => $categories->random()->id,
            'author_id' => $authors->random()->id,
        ]);
    }

    /**
     * Create manual payments and enrollments.
     */
    private function createPaymentsAndEnrollments(): void
    {
        $students = Student::all();
        $courses = Course::all();

        // Create payments for 80% of students
        $studentsForPayments = $students->random($students->count() * 0.8);
        
        foreach ($studentsForPayments as $student) {
            // Each student pays for 1-3 courses
            $coursesToEnroll = $courses->random(fake()->numberBetween(1, 3));
            
            foreach ($coursesToEnroll as $course) {
                // Skip if already enrolled (e.g., test student)
                if (CourseEnrollment::where('student_id', $student->id)
                    ->where('course_id', $course->id)->exists()) {
                    continue;
                }

                // Create payment (manual verification)
                $payment = Payment::factory()->create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'amount' => $course->effective_price,
                    'status' => fake()->randomElement(['completed', 'pending']),
                ]);

                // Create enrollment linked to payment
                CourseEnrollment::factory()->create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'payment_id' => $payment->id,
                    'enrollment_type' => $course->is_free ? 'free' : 'paid',
                    'amount_paid' => $payment->amount,
                    'payment_status' => $payment->status,
                ]);
            }
        }

        // Create some free enrollments
        $studentsForFree = $students->random($students->count() * 0.3);
        $freeCourses = Course::where('is_free', true)->get();
        
        foreach ($studentsForFree as $student) {
            if ($freeCourses->isNotEmpty()) {
                $course = $freeCourses->random();
                
                // Check if not already enrolled
                if (!CourseEnrollment::where('student_id', $student->id)
                    ->where('course_id', $course->id)->exists()) {
                    
                    CourseEnrollment::factory()->free()->create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                    ]);
                }
            }
        }
    }

    /**
     * Create lesson progress for enrolled students.
     */
    private function createLearningProgress(): void
    {
        $enrollments = CourseEnrollment::where('is_active', true)->get();

        foreach ($enrollments as $enrollment) {
            $lessons = $enrollment->course->lessons;
            if ($lessons->count() == 0) continue;
            
            $lessonsToProgress = $lessons->random(fake()->numberBetween(1, $lessons->count()));
            
            foreach ($lessonsToProgress as $lesson) {
                // Skip if progress already exists (e.g., from test student enrollment)
                if (!LessonProgress::where('lesson_id', $lesson->id)
                    ->where('enrollment_id', $enrollment->id)->exists()) {
                    
                    LessonProgress::factory()->create([
                        'lesson_id' => $lesson->id,
                        'enrollment_id' => $enrollment->id,
                        'is_completed' => fake()->boolean(60),
                    ]);
                }
            }

            // Update enrollment progress
            $enrollment->updateProgress();
        }
    }

    /**
     * Create quizzes and quiz attempts.
     */
    private function createQuizzesAndAssessments(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            // Create 1-2 quizzes per course
            $quizCount = fake()->numberBetween(1, 2);
            
            for ($i = 0; $i < $quizCount; $i++) {
                $quiz = Quiz::factory()->create([
                    'course_id' => $course->id,
                    'sort_order' => $i + 1,
                ]);

                // Create 10-20 questions per quiz
                $questionCount = fake()->numberBetween(10, 20);
                for ($j = 1; $j <= $questionCount; $j++) {
                    QuizQuestion::factory()->create([
                        'quiz_id' => $quiz->id,
                        'sort_order' => $j,
                    ]);
                }

                // Create quiz attempts from enrolled students
                $enrollments = CourseEnrollment::where('course_id', $course->id)
                    ->where('is_active', true)->get();
                    
                foreach ($enrollments->random(min($enrollments->count(), 10)) as $enrollment) {
                    QuizAttempt::factory()->create([
                        'quiz_id' => $quiz->id,
                        'student_id' => $enrollment->student_id,
                        'total_questions' => $questionCount,
                    ]);
                }
            }
        }
    }

    /**
     * Create certificates for completed students.
     */
    private function createCertificates(): void
    {
        $completedEnrollments = CourseEnrollment::where('is_completed', true)->get();

        foreach ($completedEnrollments->random(min($completedEnrollments->count(), 30)) as $enrollment) {
            Certificate::factory()->create([
                'student_id' => $enrollment->student_id,
                'course_id' => $enrollment->course_id,
            ]);
        }
    }

    /**
     * Create reviews and engagement data.
     */
    private function createReviewsAndEngagement(): void
    {
        $enrollments = CourseEnrollment::where('is_active', true)->get();
        $users = User::all();
        $posts = BlogPost::where('status', 'published')->get();

        // Create course reviews (handle duplicates gracefully)
        $reviewsCreated = 0;
        $enrollmentsShuffled = $enrollments->shuffle();
        
        foreach ($enrollmentsShuffled as $enrollment) {
            if ($reviewsCreated >= 50) break; // Limit to 50 reviews
            
            try {
                CourseReview::factory()->approved()->create([
                    'course_id' => $enrollment->course_id,
                    'student_id' => $enrollment->student_id,
                    'user_id' => $enrollment->student->user_id,
                ]);
                $reviewsCreated++;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Skip duplicate and continue
                continue;
            }
        }

        // Create blog comments
        foreach ($posts as $post) {
            $commentCount = fake()->numberBetween(0, 8);
            
            for ($i = 0; $i < $commentCount; $i++) {
                $comment = BlogComment::factory()->approved()->create([
                    'blog_post_id' => $post->id,
                    'user_id' => $users->random()->id,
                ]);

                // Create some replies
                if (fake()->boolean(30)) {
                    BlogComment::factory()->approved()->create([
                        'blog_post_id' => $post->id,
                        'user_id' => $users->random()->id,
                        'parent_id' => $comment->id,
                    ]);
                }
            }

            // Create blog reactions
            $reactionCount = fake()->numberBetween(5, 50);
            for ($i = 0; $i < $reactionCount; $i++) {
                BlogReaction::factory()->create([
                    'blog_post_id' => $post->id,
                    'user_id' => fake()->boolean(70) ? $users->random()->id : null,
                    'session_id' => fake()->boolean(70) ? null : fake()->uuid(),
                ]);
            }
        }
    }

    /**
     * Create donation records.
     */
    private function createDonations(): void
    {
        $users = User::all();

        // Create 50 donations
        for ($i = 0; $i < 50; $i++) {
            Donation::factory()->create();
        }

        // Create some donations with specific reasons
        Donation::factory()->withReason('গরীব ছাত্র সহায়তা')->count(15)->create();
    }

    /**
     * Display seeding summary.
     */
    private function displaySummary(): void
    {
        $this->command->line('');
        $this->command->line('📊 <fg=green>SEEDING SUMMARY</fg=green>');
        $this->command->line('─────────────────');
        $this->command->line('👥 Users: ' . User::count());
        $this->command->line('🎓 Courses: ' . Course::count());
        $this->command->line('📚 Course Modules: ' . CourseModule::count());
        $this->command->line('📖 Course Lessons: ' . CourseLesson::count());
        $this->command->line('💰 Payments: ' . Payment::count());
        $this->command->line('✅ Enrollments: ' . CourseEnrollment::count());
        $this->command->line('📝 Blog Posts: ' . BlogPost::count());
        $this->command->line('💬 Blog Comments: ' . BlogComment::count());
        $this->command->line('🏆 Certificates: ' . Certificate::count());
        $this->command->line('⭐ Reviews: ' . CourseReview::count());
        $this->command->line('💝 Donations: ' . Donation::count());
        $this->command->line('');
        $this->command->line('<fg=green>🎉 Ready to test! Login credentials:</fg=green>');
        $this->command->line('Admin: admin@iqra.academy / password');
        $this->command->line('Teacher: teacher@iqra.academy / password');
        $this->command->line('Student: student@iqra.academy / password');
    }
}