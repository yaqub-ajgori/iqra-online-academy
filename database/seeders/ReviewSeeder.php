<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnrollment;
use App\Models\CourseReview;
use App\Models\CourseReviewHelpfulVote;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating review test data...');

        // Create categories if they don't exist
        $categories = [
            'কুরআন অধ্যয়ন' => 'quran-study',
            'হাদিস শিক্ষা' => 'hadith-education', 
            'ফিকহ শাস্ত্র' => 'fiqh-studies',
            'ইসলামী ইতিহাস' => 'islamic-history',
            'আরবি ভাষা' => 'arabic-language',
        ];

        foreach ($categories as $name => $slug) {
            CourseCategory::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'is_active' => true]
            );
        }

        // Create teachers if they don't exist  
        $teacherNames = [
            'মাওলানা আবদুল করিম',
            'উস্তাজ মুহাম্মদ ইউসুফ',
            'হাফেজ আহমদ আলী',
            'ড. ফাতিমা খাতুন',
            'প্রফেসর জাকির হুসাইন',
        ];

        $teachers = [];
        foreach ($teacherNames as $name) {
            $email = strtolower(str_replace([' ', '.'], ['', ''], $name)) . '@iqra-academy.com';
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt('password'),
                ]
            );
            
            $teacher = Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $name,
                    'speciality' => 'ইসলামী শিক্ষা',
                    'experience' => 'অভিজ্ঞ ইসলামী শিক্ষাবিদ এবং গবেষক।',
                    'phone' => '01' . rand(700000000, 999999999),
                    'is_active' => true,
                ]
            );
            
            $teachers[] = $teacher;
        }

        // Create courses
        $courseData = [
            [
                'title' => 'কুরআন তিলাওয়াত ও তাজভীদ কোর্স',
                'category' => 'quran-study',
                'description' => 'সুন্দর ও শুদ্ধভাবে কুরআন তিলাওয়াত শিখুন। তাজভীদের নিয়ম-কানুন এবং প্রায়োগিক অনুশীলনের মাধ্যমে নিজের তিলাওয়াত উন্নত করুন।',
                'price' => 2500,
            ],
            [
                'title' => 'হাদিস শরীফ অধ্যয়ন',
                'category' => 'hadith-education', 
                'description' => 'সহীহ বুখারী ও মুসলিম শরীফের নির্বাচিত হাদিস নিয়ে বিস্তারিত আলোচনা। হাদিসের ব্যাখ্যা ও জীবনে প্রয়োগ।',
                'price' => 3000,
            ],
            [
                'title' => 'ইসলামী ফিকহ ও মাসায়েল',
                'category' => 'fiqh-studies',
                'description' => 'দৈনন্দিন জীবনের ইসলামী আইনকানুন। নামাজ, রোজা, হজ, যাকাত এবং লেনদেনের মাসায়েল।',
                'price' => 2800,
            ],
            [
                'title' => 'আরবি ভাষা শিক্ষা (প্রাথমিক)',
                'category' => 'arabic-language',
                'description' => 'কুরআন-হাদিস বোঝার জন্য আরবি ভাষার মৌলিক জ্ঞান অর্জন। ব্যাকরণ ও শব্দভাণ্ডার।',
                'price' => 2200,
            ],
            [
                'title' => 'ইসলামী ইতিহাস ও সীরাত',
                'category' => 'islamic-history',
                'description' => 'রাসূল (সা.) এর জীবনী এবং ইসলামী ইতিহাসের গুরুত্বপূর্ণ ঘটনাসমূহ। সাহাবাদের জীবনী।',
                'price' => 2000,
            ],
        ];

        $courses = [];
        foreach ($courseData as $index => $data) {
            $category = CourseCategory::where('slug', $data['category'])->first();
            $teacher = $teachers[$index % count($teachers)];
            $slug = \Str::slug($data['title']);
            
            $course = Course::firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $data['title'],
                    'full_description' => $data['description'],
                    'category_id' => $category->id,
                    'instructor_id' => $teacher->id,
                    'price' => $data['price'],
                    'status' => 'published',
                    'is_featured' => $index < 3, // First 3 courses are featured
                    'level' => 'beginner',
                    'currency' => 'BDT',
                    'duration_in_minutes' => rand(120, 480),
                ]
            );
            
            $courses[] = $course;
        }

        // Create students
        $studentData = [
            ['name' => 'মোহাম্মদ আবদুল্লাহ', 'email' => 'abdullah@example.com'],
            ['name' => 'ফাতিমা আক্তার', 'email' => 'fatima@example.com'],
            ['name' => 'আহমদ হাসান', 'email' => 'ahmad@example.com'],
            ['name' => 'আয়েশা খাতুন', 'email' => 'ayesha@example.com'],
            ['name' => 'উমর ফারুক', 'email' => 'umar@example.com'],
            ['name' => 'খাদিজা বেগম', 'email' => 'khadija@example.com'],
            ['name' => 'ইউসুফ আলী', 'email' => 'yusuf@example.com'],
            ['name' => 'জয়নব রহমান', 'email' => 'zainab@example.com'],
            ['name' => 'ইব্রাহিম খান', 'email' => 'ibrahim@example.com'],
            ['name' => 'মারিয়াম সিদ্দিকা', 'email' => 'mariam@example.com'],
        ];

        $students = [];
        foreach ($studentData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                ]
            );
            
            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $data['name'],
                    'phone' => '01' . rand(700000000, 999999999),
                    'is_active' => true,
                    'is_verified' => true,
                    'gender' => fake()->randomElement(['male', 'female']),
                    'city' => 'ঢাকা',
                    'country' => 'Bangladesh',
                ]
            );
            
            $students[] = $student;
        }

        // Create enrollments (students enrolled in courses)
        foreach ($students as $student) {
            // Each student enrolls in 2-4 random courses
            $coursesToEnroll = fake()->randomElements($courses, fake()->numberBetween(2, 4));
            
            foreach ($coursesToEnroll as $course) {
                CourseEnrollment::firstOrCreate([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                ], [
                    'is_active' => true,
                    'enrolled_at' => fake()->dateTimeBetween('-6 months', '-1 month'),
                    'progress_percentage' => fake()->numberBetween(30, 100),
                    'is_completed' => fake()->boolean(70), // 70% chance of completion
                    'enrollment_type' => 'paid',
                    'lessons_completed' => fake()->numberBetween(1, 10),
                ]);
            }
        }

        // Create detailed reviews with Islamic content
        $islamicReviews = [
            [
                'title' => 'অসাধারণ ইসলামিক শিক্ষা',
                'review' => 'মাশাআল্লাহ! এই কোর্সটি আমার ইসলামিক জ্ঞানে অনেক সমৃদ্ধি এনেছে। শিক্ষকের পদ্ধতি খুবই কার্যকর এবং বোধগম্য। প্রতিটি পাঠ স্পষ্ট এবং সহজভাবে উপস্থাপনা করা হয়েছে। আল্লাহ তাআলা শিক্ষকদের উত্তম প্রতিদান দিন।',
                'rating' => 5,
            ],
            [
                'title' => 'জীবন পরিবর্তনকারী কোর্স',
                'review' => 'আলহামদুলিল্লাহ! এই কোর্সের মাধ্যমে কুরআনের গভীর অর্থ বুঝতে পেরেছি। প্রতিটি ক্লাস খুবই মানসম্পন্ন ও তথ্যবহুল। শিক্ষকগণ অত্যন্ত ধৈর্যশীল এবং প্রতিটি প্রশ্নের সুন্দর উত্তর দিয়ে থাকেন।',
                'rating' => 5,
            ],
            [
                'title' => 'চমৎকার হাদিস ব্যাখ্যা',
                'review' => 'রাসূল (সা.) এর হাদিসের ব্যাখ্যা এত সুন্দরভাবে দেওয়া হয়েছে যা আমার হৃদয়ে গভীর প্রভাব ফেলেছে। বারাকাল্লাহু ফীকুম! প্রতিটি হাদিসের প্রেক্ষাপট এবং শিক্ষা নিয়ে বিস্তারিত আলোচনা করা হয়েছে।',
                'rating' => 5,
            ],
            [
                'title' => 'ব্যতিক্রমী ফিকহ শিক্ষা',
                'review' => 'দৈনন্দিন জীবনের ইসলামী আইনকানুন এত সহজভাবে শেখানো হয়েছে যা আমার জীবনযাত্রায় ইতিবাচক পরিবর্তন এনেছে। প্রতিটি মাসআলা স্পষ্ট দলিল-প্রমাণসহ বর্ণনা করা হয়েছে।',
                'rating' => 5,
            ],
            [
                'title' => 'আধ্যাত্মিক উন্নতি',
                'review' => 'সুবহানাল্লাহ! এই কোর্স শুধু জ্ঞানই দেয়নি, আধ্যাত্মিক উন্নতিও এনেছে। নামাজে খুশু-খুজু বৃদ্ধি পেয়েছে এবং আল্লাহর সাথে সম্পর্ক আরও গভীর হয়েছে।',
                'rating' => 5,
            ],
            [
                'title' => 'সময়োপযোগী শিক্ষা',
                'review' => 'আধুনিক যুগের সাথে ইসলামিক শিক্ষার চমৎকার সমন্বয়। অনলাইনে এত মানসম্পন্ন ইসলামিক কোর্স পেয়ে কৃতজ্ঞ। ভিডিও কোয়ালিটি এবং অডিও খুবই পরিষ্কার।',
                'rating' => 4,
            ],
            [
                'title' => 'হৃদয়স্পর্শী শিক্ষা',
                'review' => 'শুধু মুখস্থ নয়, বরং অন্তরে ইসলামের মূল্যবোধ প্রবেশ করিয়ে দিয়েছে এই কোর্স। জাযাকাল্লাহু খায়রান! শিক্ষার্থীদের সাথে শিক্ষকদের আচরণ অত্যন্ত সুন্দর।',
                'rating' => 5,
            ],
            [
                'title' => 'পরিবার ও সমাজের জন্য কল্যাণকর',
                'review' => 'এই কোর্স শুধু আমার নয়, আমার পুরো পরিবারের জন্য কল্যাণ বয়ে এনেছে। সবাইকে এই কোর্স করার পরামর্শ দিই। বিশেষ করে পারিবারিক বিষয়ে ইসলামের দিকনির্দেশনা খুবই কার্যকর।',
                'rating' => 5,
            ],
            [
                'title' => 'আরবি ভাষায় দক্ষতা বৃদ্ধি',
                'review' => 'আরবি ভাষা শেখার ক্ষেত্রে এই কোর্সটি অত্যন্ত সহায়ক। এখন কুরআন পড়তে আগের চেয়ে অনেক সহজ লাগে এবং কিছু কিছু অর্থ বুঝতে পারি।',
                'rating' => 4,
            ],
            [
                'title' => 'চমৎকার সীরাত শিক্ষা',
                'review' => 'রাসূল (সা.) এর জীবনী এত সুন্দরভাবে উপস্থাপন করা হয়েছে যে মনে হচ্ছে সেই যুগের ঘটনাগুলো চোখের সামনে ঘটছে। প্রতিটি ঘটনা থেকে শিক্ষা নিয়ে জীবনে প্রয়োগ করার চেষ্টা করছি।',
                'rating' => 5,
            ],
            [
                'title' => 'বিস্তারিত ও গভীর ইসলামিক জ্ঞান অর্জনের অভিজ্ঞতা',
                'review' => 'আস্সালামু আলাইকুম ওয়া রাহমাতুল্লাহি ওয়া বারাকাতুহু। আমি এই কোর্সে অংশগ্রহণ করে অত্যন্ত সন্তুষ্ট এবং কৃতজ্ঞ। আলহামদুলিল্লাহি রাব্বিল আলামীন! এই কোর্সটি আমার ইসলামিক জ্ঞানে এক নতুন মাত্রা যোগ করেছে। প্রথমত, শিক্ষকগণের জ্ঞানের গভীরতা ও উপস্থাপনার দক্ষতা সত্যিই প্রশংসনীয়। তাঁরা প্রতিটি বিষয় কুরআন ও সহীহ হাদিসের আলোকে এত সুন্দরভাবে ব্যাখ্যা করেছেন যে, জটিল বিষয়গুলোও সহজ হয়ে গেছে। দ্বিতীয়ত, কোর্সের কন্টেন্ট অত্যন্ত সুসংগঠিত এবং যুগোপযোগী। আধুনিক সমাজের চ্যালেঞ্জগুলোর ইসলামিক সমাধান নিয়ে যে আলোচনা করা হয়েছে, তা আমার দৈনন্দিন জীবনে অনেক সাহায্য করেছে। তৃতীয়ত, অনলাইন প্ল্যাটফর্মের ব্যবহার অত্যন্ত সহজ এবং ভিডিও কোয়ালিটি চমৎকার। চতুর্থত, শিক্ষার্থীদের প্রশ্নের উত্তর দেওয়ার ক্ষেত্রে শিক্ষকগণের ধৈর্য ও আন্তরিকতা সত্যিই হৃদয়স্পর্শী। পঞ্চমত, এই কোর্স শুধু তাত্ত্বিক জ্ঞানই দেয়নি, বরং ব্যবহারিক জীবনে ইসলাম চর্চার পথও দেখিয়েছে। আমার নামাজ, যিকির, তিলাওয়াত - সবকিছুতেই এক নতুন মাত্রা এসেছে। ষষ্ঠত, পারিবারিক জীবনে ইসলামিক মূল্যবোধ প্রতিষ্ঠার যে দিকনির্দেশনা পেয়েছি, তা অমূল্য। আমার স্ত্রী ও সন্তানদের সাথে সম্পর্কের উন্নতি হয়েছে। সপ্তমত, সমাজে একজন মুসলিম হিসেবে আমার দায়িত্ব ও কর্তব্য সম্পর্কে স্পষ্ট ধারণা পেয়েছি। অষ্টমত, আখিরাতের জবাবদিহিতার বিষয়টি আরও গভীরভাবে উপলব্ধি করেছি। নবমত, এই কোর্সের মাধ্যমে আমার ঈমান আরও শক্তিশালী হয়েছে এবং আল্লাহর উপর ভরসা বৃদ্ধি পেয়েছে। দশমত, আমি এই কোর্সটি সবার কাছে সুপারিশ করি, বিশেষ করে যারা সত্যিকারের ইসলামিক জীবন যাপন করতে চান। আল্লাহ তাআলা এই প্রতিষ্ঠানের সাথে যুক্ত সবাইকে উত্তম প্রতিদান দিন এবং এই উদ্যোগকে আরও বিস্তৃত করার তাওফীক দিন। আমীন ইয়া রাব্বাল আলামীন। বারাকাল্লাহু ফীকুম জামীআন।',
                'rating' => 5,
            ],
        ];

        // Create all enrollments first, then create reviews for enrolled students
        $enrollments = CourseEnrollment::all();
        $reviewIndex = 0;

        foreach ($enrollments as $enrollment) {
            // 80% chance that an enrolled student will leave a review and no existing review
            if (fake()->boolean(80) && $reviewIndex < count($islamicReviews)) {
                // Check if review already exists for this student-course combination
                $existingReview = CourseReview::where('course_id', $enrollment->course_id)
                    ->where('student_id', $enrollment->student_id)
                    ->first();
                
                if (!$existingReview) {
                    $reviewData = $islamicReviews[$reviewIndex % count($islamicReviews)];
                    
                    $review = CourseReview::create([
                        'course_id' => $enrollment->course_id,
                        'student_id' => $enrollment->student_id,
                        'user_id' => $enrollment->student->user_id,
                        'title' => $reviewData['title'],
                        'review_text' => $reviewData['review'],
                        'rating' => $reviewData['rating'],
                        'status' => fake()->randomElement(['approved', 'approved', 'approved', 'pending']), // 75% approved
                        'approved_at' => fake()->boolean(75) ? fake()->dateTimeBetween('-2 months', 'now') : null,
                        'approved_by' => fake()->boolean(75) ? User::first()->id : null,
                        'is_featured' => false, // Will be set later
                        'is_verified_purchase' => true, // All are verified since they're enrolled
                        'helpful_count' => fake()->numberBetween(0, 25),
                        'reported_count' => fake()->numberBetween(0, 2),
                        'metadata' => [
                            'progress_at_review' => $enrollment->progress_percentage,
                            'completed_course' => $enrollment->is_completed,
                        ],
                    ]);
                }

                $reviewIndex++;
            }
        }

        // Mark some high-rated reviews as featured (max 6)
        // First, mark the long review as featured for testing
        $longReview = CourseReview::where('title', 'বিস্তারিত ও গভীর ইসলামিক জ্ঞান অর্জনের অভিজ্ঞতা')->first();
        if ($longReview) {
            $longReview->update([
                'is_featured' => true,
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => User::first()->id,
            ]);
        }

        // Then mark other high-rated reviews as featured (max 5 more to make total 6)
        $featuredReviews = CourseReview::approved()
            ->where('rating', '>=', 4)
            ->where('is_featured', false) // Don't include the long review again
            ->orderBy('helpful_count', 'desc')
            ->orderBy('rating', 'desc')
            ->limit(5)
            ->get();

        foreach ($featuredReviews as $review) {
            $review->update(['is_featured' => true]);
        }

        // Create helpful votes for reviews (avoid duplicates)
        $reviews = CourseReview::all();
        foreach ($reviews as $review) {
            $voteCount = fake()->numberBetween(0, min($review->helpful_count, 5)); // Limit to prevent too many duplicates
            $usedVoters = [];
            
            for ($i = 0; $i < $voteCount; $i++) {
                // Mix of authenticated user votes and guest votes
                if (fake()->boolean(60)) {
                    // Authenticated user vote - avoid duplicates
                    $studentUserIds = collect($students)->pluck('user_id')->toArray();
                    $availableVoters = array_diff($studentUserIds, $usedVoters);
                    if (!empty($availableVoters)) {
                        $voterId = fake()->randomElement($availableVoters);
                        $usedVoters[] = $voterId;
                        
                        CourseReviewHelpfulVote::firstOrCreate([
                            'review_id' => $review->id,
                            'user_id' => $voterId,
                        ], [
                            'ip_address' => null,
                            'session_id' => null,
                        ]);
                    }
                } else {
                    // Guest vote
                    CourseReviewHelpfulVote::create([
                        'review_id' => $review->id,
                        'user_id' => null,
                        'ip_address' => fake()->ipv4,
                        'session_id' => fake()->uuid,
                    ]);
                }
            }
        }

        $totalReviews = CourseReview::count();
        $approvedReviews = CourseReview::approved()->count();
        $featuredCount = CourseReview::where('is_featured', true)->count();

        $this->command->info("✅ Review system seeded successfully!");
        $this->command->info("📊 Created {$totalReviews} reviews ({$approvedReviews} approved, {$featuredCount} featured)");
        $this->command->info("👥 Created " . count($students) . " students with enrollments");
        $this->command->info("📚 Created " . count($courses) . " courses across " . count($categories) . " categories");
    }
}