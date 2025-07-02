<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display the courses listing page
     */
    public function index(Request $request): Response
    {
        $query = Course::with(['category', 'teacher'])
            ->where('status', 'published');

        // Robust search filter with full-text search
        if ($search = $request->input('search')) {
            // Use full-text search for MySQL
            $dbDriver = DB::getDriverName();
            if ($dbDriver === 'mysql') {
                $query->whereRaw('MATCH(title, full_description) AGAINST (? IN BOOLEAN MODE)', [$search]);
            } else {
                // Fallback for other DBs
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('full_description', 'like', "%{$search}%");
                });
            }
        }

        $courses = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        // Transform courses for frontend - match HomeController structure
        $courses->getCollection()->transform(function ($course) {
            return [
                'id' => $course->id,
                'slug' => $course->slug,
                'title' => $course->title,
                'description' => $course->full_description, // Match HomeController field name
                'image' => $course->thumbnail,
                'category' => $course->category->name ?? 'Uncategorized',
                'level' => $course->level,
                'price' => $course->price,
                'duration' => $course->duration_hours ? $course->duration_hours . ' ঘন্টা' : 'স্ব-নির্ধারিত',
                'students_count' => $course->enrollments()->count(),
                'rating' => 0, // Default rating since we removed average_rating
                'instructor' => [
                    'name' => $course->teacher->full_name ?? 'Unknown Instructor',
                    'avatar' => $course->teacher->profile_picture ?? null,
                ],
                'isNew' => $course->created_at->diffInDays(now()) <= 30,
                'enrolled' => false,
                'progress' => 0,
                'created_at' => $course->created_at->toISOString(), // Add for sorting
            ];
        });

        return Inertia::render('Frontend/CoursesPage', [
            'courses' => $courses,
            'search' => $search,
            'stats' => [
                'total_courses' => Course::where('status', 'published')->count(),
                'total_categories' => CourseCategory::count(),
            ]
        ]);
    }

    /**
     * Display the course details page
     */
    public function show(Course $course): Response
    {
        if ($course->status !== 'published') {
            abort(404);
        }

        $courseData = [
            'id' => $course->id,
            'slug' => $course->slug,
            'title' => $course->title,
            'full_description' => $course->full_description,
            'content' => $course->content,
            'image' => $course->thumbnail,
            'category' => $course->category->name ?? 'সাধারণ',
            'level' => $course->level ?? 'beginner',
            'price' => $course->price ?? 0,
            'duration' => $course->duration ?? 'স্ব-নির্ধারিত',
            'students_count' => $course->enrollments_count ?? 0,
            'rating' => 0, // Default rating since we removed average_rating
            'instructor' => [
                'name' => $course->teacher->full_name ?? 'অজানা শিক্ষক',
                'avatar' => $course->teacher->profile_picture ?? null,
                'bio' => $course->teacher->speciality ?? '',
            ],
            'isNew' => $course->created_at->diffInDays(now()) <= 30,
            'enrolled' => false, // Will be updated based on authenticated user
            'progress' => 0, // Will be updated based on authenticated user
            'modules' => $course->modules->map(function ($module) {
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'lessons_count' => $module->lessons->count(),
                    'duration' => $module->duration ?? 'স্ব-নির্ধারিত',
                ];
            }),
            'created_at' => $course->created_at->format('Y-m-d'),
            'updated_at' => $course->updated_at->format('Y-m-d'),
        ];

        // Get related courses
        $relatedCourses = Course::with(['category', 'teacher'])
            ->where('status', 'published')
            ->where('id', '!=', $course->id)
            ->where('category_id', $course->category_id)
            ->limit(3)
            ->get()
            ->map(function ($relatedCourse) {
                return [
                    'id' => $relatedCourse->id,
                    'slug' => $relatedCourse->slug,
                    'title' => $relatedCourse->title,
                    'full_description' => $relatedCourse->full_description,
                    'image' => $relatedCourse->thumbnail,
                    'category' => $relatedCourse->category->name ?? 'সাধারণ',
                    'level' => $relatedCourse->level ?? 'beginner',
                    'price' => $relatedCourse->price ?? 0,
                    'duration' => $relatedCourse->duration ?? 'স্ব-নির্ধারিত',
                    'students_count' => $relatedCourse->enrollments_count ?? 0,
                    'rating' => 0, // Default rating since we removed average_rating
                    'instructor' => [
                        'name' => $relatedCourse->teacher->full_name ?? 'অজানা শিক্ষক',
                        'avatar' => $relatedCourse->teacher->profile_picture ?? null
                    ],
                    'isNew' => $relatedCourse->created_at->diffInDays(now()) <= 30,
                ];
            });

        return Inertia::render('Frontend/CourseDetailsPage', [
            'course' => $courseData,
            'relatedCourses' => $relatedCourses,
        ]);
    }


} 