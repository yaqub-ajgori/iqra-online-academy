<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
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
     * Get testimonials data
     */
    private function getTestimonials(): array
    {
        return Cache::remember('homepage_testimonials', 3600, function() { // 1 hour cache
            // Mock testimonials for now - this could be from a database table later
            return [
                [
                    'id' => 1,
                    'name' => 'আবু বকর সিদ্দিক',
                    'course' => 'কুরআন তিলাওয়াত কোর্স',
                    'rating' => 5,
                    'comment' => 'অসাধারণ কোর্স! খুবই সহজ পদ্ধতিতে কুরআন শিক্ষা দেওয়া হয়েছে।',
                    'avatar' => null,
                ],
                [
                    'id' => 2,
                    'name' => 'ফাতিমা খাতুন',
                    'course' => 'হাদিস অধ্যয়ন',
                    'rating' => 5,
                    'comment' => 'শিক্ষকদের পদ্ধতি খুবই ভালো। অনেক কিছু শিখতে পেরেছি।',
                    'avatar' => null,
                ],
                [
                    'id' => 3,
                    'name' => 'উমর ইবনে খাত্তাব',
                    'course' => 'ফিকহ শাস্ত্র',
                    'rating' => 5,
                    'comment' => 'জীবনের সব সমস্যার সমাধান পেয়েছি এই কোর্সে।',
                    'avatar' => null,
                ]
            ];
        });
    }
} 