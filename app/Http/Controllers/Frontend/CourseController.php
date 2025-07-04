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

        // Load course relationships with only active lessons
        $course->load([
            'teacher', 
            'category',
            'modules' => function ($query) {
                $query->orderBy('sort_order');
            },
            'modules.lessons' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }
        ]);footer fixed

        $user = auth()->user();
        $isAuthenticated = $user !== null;
        $isEnrolled = false;
        if ($isAuthenticated) {
            $isEnrolled = $course->enrollments()->where('student_id', $user->id)->exists();
        }

        $courseData = [
            'id' => $course->id,
            'slug' => $course->slug,
            'title' => $course->title,
            'full_description' => $course->full_description,
            'content' => $course->content,
            'image' => $course->thumbnail,
            'price' => $course->price ?? 0,
            'duration' => $course->duration,
            'total_lessons' => $course->total_lessons_count,
            'students_count' => $course->enrollments_count ?? 0,
            'rating' => 0, // Default rating since we removed average_rating
            'instructor' => [
                'name' => $course->teacher->full_name ?? 'অজানা শিক্ষক',
                'avatar' => $course->teacher->profile_picture ?? null,
                'bio' => $course->teacher->speciality ?? '',
                'experience' => $course->teacher->experience ?? '',
            ],
            'isNew' => $course->created_at->diffInDays(now()) <= 30,
            'enrolled' => $isEnrolled,
            'progress' => 0, // Will be updated based on authenticated user
            'modules' => $course->modules->map(function ($module) {
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'lessons_count' => $module->lessons->count(),
                    'lessons' => $module->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'duration' => $lesson->formatted_duration ?? '0:00',
                        ];
                    }),
                ];
            }),
            'created_at' => $course->created_at->format('Y-m-d'),
            'updated_at' => $course->updated_at->format('Y-m-d'),
        ];

        return Inertia::render('Frontend/CourseDetailsPage', [
            'course' => $courseData,
            'isAuthenticated' => $isAuthenticated,
            'isEnrolled' => $isEnrolled,
        ]);
    }


} 