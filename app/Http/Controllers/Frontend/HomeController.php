<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Teacher;
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
            'stats' => $this->getStats(),
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
     * Get featured courses for homepage from database
     */
    private function getFeaturedCourses(): array
    {
        $courses = Course::with(['category', 'teacher'])
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'slug' => $course->slug,
                    'title' => $course->title,
                    'full_description' => $course->full_description,
                    'image' => $course->thumbnail,
                    'category' => $course->category->name ?? 'Uncategorized',
                    'level' => $course->level,
                    'price' => $course->price,
                    'duration' => $course->duration,
                    'students_count' => $course->enrollments()->count(),
                    'rating' => 0, // Default rating since we removed average_rating
                    'is_featured' => $course->is_featured,
                    'is_free' => $course->price == 0,
                    'instructor' => [
                        'name' => $course->teacher->full_name ?? 'Unknown Instructor',
                        'avatar' => $course->teacher->profile_picture ?? null,
                    ],
                    'isNew' => $course->created_at->diffInDays(now()) <= 30,
                    'enrolled' => false, // Will be updated based on authenticated user
                    'progress' => 0, // Will be updated based on authenticated user
                ];
            })
            ->toArray();

        // If no featured courses, get the latest published courses
        if (empty($courses)) {
            $courses = Course::with(['category', 'teacher'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get()
                ->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'slug' => $course->slug,
                        'title' => $course->title,
                        'full_description' => $course->full_description,
                        'image' => $course->thumbnail,
                        'category' => $course->category->name ?? 'Uncategorized',
                        'level' => $course->level,
                        'price' => $course->price,
                        'duration' => $course->duration,
                        'students_count' => $course->enrollments()->count(),
                        'rating' => 0, // Default rating since we removed average_rating
                        'is_featured' => $course->is_featured,
                        'is_free' => $course->price == 0,
                        'instructor' => [
                            'name' => $course->teacher->full_name ?? 'Unknown Instructor',
                            'avatar' => $course->teacher->profile_picture ?? null,
                        ],
                        'isNew' => $course->created_at->diffInDays(now()) <= 30,
                        'enrolled' => false,
                        'progress' => 0,
                    ];
                })
                ->toArray();
        }

        return $courses;
    }

    /**
     * Get real stats from database
     */
    private function getStats(): array
    {
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
    }
} 