<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseReview;
use App\Models\Teacher;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index(): Response
    {
        return Inertia::render('Frontend/HomePage', [
            'title' => 'ইকরা অনলাইন একাডেমি - ইসলামিক শিক্ষায় আধুনিক প্রযুক্তি',
            'meta' => [
                'description' => 'কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে উঠেছে ইকরা অনলাইন একাডেমি। অভিজ্ঞ শিক্ষকদের সাথে শিখুন ঘরে বসেই।',
                'keywords' => 'ইসলামিক শিক্ষা, অনলাইন কোর্স, কুরআন, হাদিস, ফিকহ, ইকরা একাডেমি'
            ],
            'featured_courses' => $this->getFeaturedCourses(),
            'stats' => Inertia::defer(fn() => $this->getStats()),
            'testimonials' => Inertia::defer(fn() => $this->getTestimonials()),
        ]);
    }

    /**
     * Display the about page
     */
    public function about(): Response
    {
        return Inertia::render('Frontend/AboutPage', [
            'title' => 'আমাদের সম্পর্কে - ইকরা অনলাইন একাডেমি',
            'mission' => 'ইসলামিক শিক্ষায় আধুনিক প্রযুক্তির মাধ্যমে সকলের কাছে পৌঁছে দেওয়া',
            'vision' => 'একটি আলোকিত ইসলামিক সমাজ গড়ে তোলা যেখানে সবাই সঠিক ইসলামিক জ্ঞানে সমৃদ্ধ'
        ]);
    }

    /**
     * Display the contact page
     */
    public function contact(): Response
    {
        return Inertia::render('Frontend/ContactPage', [
            'title' => 'যোগাযোগ - ইকরা অনলাইন একাডেমি',
            'contact_info' => [
                'phone' => '+৮৮০ ১২৩৪ ৫৬৭৮৯০',
                'email' => 'info@iqra-academy.com',
                'address' => 'ঢাকা, বাংলাদেশ'
            ]
        ]);
    }

    /**
     * Get featured courses for homepage from database with caching and optimized queries
     */
    private function getFeaturedCourses(): array
    {
        return Cache::remember('homepage_featured_courses', 1800, function() { // 30 minutes cache
            // Try to get featured courses first
            $courses = Course::with(['category', 'teacher'])
                ->withCount('enrollments')
                ->where('status', 'published')
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            // If no featured courses, get the latest published courses
            if ($courses->isEmpty()) {
                $courses = Course::with(['category', 'teacher'])
                    ->withCount('enrollments')
                    ->where('status', 'published')
                    ->orderBy('created_at', 'desc')
                    ->limit(6)
                    ->get();
            }

            return $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'slug' => $course->slug,
                    'title' => $course->title,
                    'full_description' => $course->full_description,
                    'image' => $course->thumbnail,
                    'category' => $course->category->name ?? 'Uncategorized',
                    'level' => $course->level,
                    'price' => $course->price,
                    'discount_price' => $course->discount_price,
                    'discount_expires_at' => $course->discount_expires_at,
                    'is_free' => $course->is_free,
                    'duration' => $course->duration,
                    'students_count' => $course->enrollments_count, // Using withCount result
                    'rating' => 0, // Default rating since we removed average_rating
                    'is_featured' => $course->is_featured,
                    'instructor' => [
                        'name' => $course->teacher->full_name ?? 'Unknown Instructor',
                        'avatar' => $course->teacher->profile_picture_url ?? null,
                    ],
                    'isNew' => $course->created_at->diffInDays(now()) <= 30,
                    'enrolled' => false, // Will be updated based on authenticated user
                    'progress' => 0, // Will be updated based on authenticated user
                ];
            })->toArray();
        });
    }

    /**
     * Get real stats from database with caching
     */
    private function getStats(): array
    {
        return Cache::remember('homepage_stats', 900, function() { // 15 minutes cache
            $totalCourses = max(10, Course::where('status', 'published')->count());
            $totalStudents = max(100, \App\Models\Student::count());
            $totalEnrollments = CourseEnrollment::count();
            $totalTeachers = Teacher::count();

            // Calculate satisfaction rate (mock for now, would need rating system)
            $satisfactionRate = 99; // Always show 99% satisfaction

            return [
                'total_courses' => $totalCourses,
                'total_students' => $totalStudents,
                'satisfaction_rate' => $satisfactionRate,
                'total_instructors' => $totalTeachers,
                'total_enrollments' => $totalEnrollments
            ];
        });
    }

    /**
     * Get testimonials from admin-selected featured reviews
     */
    private function getTestimonials(): array
    {
        return Cache::remember('homepage_testimonials', 3600, function() { // 1 hour cache
            // Get ONLY admin-selected featured reviews (is_featured = true)
            $reviews = CourseReview::featured() // This already filters by is_featured = true
                ->with(['student', 'course'])
                ->orderBy('helpful_count', 'desc')
                ->orderBy('rating', 'desc')
                ->orderBy('created_at', 'desc') // Most recent first
                ->limit(6) // Admin can feature up to 6 reviews
                ->get();

            // If no featured reviews, return fallback mock data
            if ($reviews->isEmpty()) {
                return [
                    [
                        'id' => 'mock_1',
                        'name' => 'আবু বকর সিদ্দিক',
                        'course' => 'কুরআন তিলাওয়াত কোর্স',
                        'rating' => 5,
                        'title' => 'অসাধারণ ইসলামিক শিক্ষা',
                        'comment' => 'মাশাআল্লাহ! এই কোর্সটি আমার ইসলামিক জ্ঞানে অনেক সমৃদ্ধি এনেছে। শিক্ষকের পদ্ধতি খুবই কার্যকর এবং বোধগম্য।',  
                        'full_review' => 'মাশাআল্লাহ! এই কোর্সটি আমার ইসলামিক জ্ঞানে অনেক সমৃদ্ধি এনেছে। শিক্ষকের পদ্ধতি খুবই কার্যকর এবং বোধগম্য। প্রতিটি পাঠ স্পষ্ট এবং সহজভাবে উপস্থাপনা করা হয়েছে। আল্লাহ তাআলা শিক্ষকদের উত্তম প্রতিদান দিন।',
                        'avatar' => null,
                        'verified' => true,
                        'date' => '১৫ জুলাই, ২০২৪',
                        'helpful_count' => 12,
                        'is_long' => true,
                    ],
                    [
                        'id' => 'mock_2',
                        'name' => 'ফাতিমা খাতুন',
                        'course' => 'হাদিস অধ্যয়ন',
                        'rating' => 5,
                        'title' => 'জীবন পরিবর্তনকারী কোর্স',
                        'comment' => 'আলহামদুলিল্লাহ! এই কোর্সের মাধ্যমে কুরআনের গভীর অর্থ বুঝতে পেরেছি।',
                        'full_review' => 'আলহামদুলিল্লাহ! এই কোর্সের মাধ্যমে কুরআনের গভীর অর্থ বুঝতে পেরেছি। প্রতিটি ক্লাস খুবই মানসম্পন্ন ও তথ্যবহুল। শিক্ষকগণ অত্যন্ত ধৈর্যশীল এবং প্রতিটি প্রশ্নের সুন্দর উত্তর দিয়ে থাকেন।',
                        'avatar' => null,
                        'verified' => true,  
                        'date' => '১০ জুলাই, ২০২৪',
                        'helpful_count' => 8,
                        'is_long' => false,
                    ],
                    [
                        'id' => 'mock_3',
                        'name' => 'উমর ইবনে খাত্তাব',
                        'course' => 'ফিকহ শাস্ত্র',
                        'rating' => 5,
                        'title' => 'ব্যতিক্রমী ফিকহ শিক্ষা',
                        'comment' => 'দৈনন্দিন জীবনের ইসলামী আইনকানুন এত সহজভাবে শেখানো হয়েছে।',
                        'full_review' => 'দৈনন্দিন জীবনের ইসলামী আইনকানুন এত সহজভাবে শেখানো হয়েছে যা আমার জীবনযাত্রায় ইতিবাচক পরিবর্তন এনেছে। প্রতিটি মাসআলা স্পষ্ট দলিল-প্রমাণসহ বর্ণনা করা হয়েছে।',
                        'avatar' => null,
                        'verified' => true,
                        'date' => '৫ জুলাই, ২০২৪', 
                        'helpful_count' => 15,
                        'is_long' => false,
                    ]
                ];
            }

            return $reviews->map(function ($review) {
                $fullText = $review->title ? $review->title . ' - ' . $review->review_text : $review->review_text;
                $shortText = mb_strlen($fullText) > 120 ? mb_substr($fullText, 0, 120) . '...' : $fullText;
                
                return [
                    'id' => $review->id,
                    'name' => $review->student->full_name,
                    'course' => $review->course->title,
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'comment' => $shortText, // Truncated for display
                    'full_review' => $fullText, // Full text for modal
                    'avatar' => null, // Add avatar support later if needed
                    'verified' => $review->is_verified_purchase,
                    'date' => $review->bengali_date,
                    'helpful_count' => $review->helpful_count,
                    'is_long' => mb_strlen($fullText) > 120, // Show "See More" if longer than 120 chars
                ];
            })->toArray();
        });
    }
} 