<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Teacher;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(): Response
    {
        $courses = Course::with(['instructor', 'category'])
            ->withCount(['enrollments', 'modules', 'lessons'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): Response
    {
        $categories = CourseCategory::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $instructors = Teacher::where('is_active', true)
            ->orderBy('full_name')
            ->get(['id', 'full_name']);

        return Inertia::render('Admin/Courses/Create', [
            'categories' => $categories,
            'instructors' => $instructors,
        ]);
    }

    /**
     * Store a newly created course.
     */
    public function store(CourseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        
        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Course::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle thumbnail image upload
        if ($request->hasFile('thumbnail_image')) {
            $path = $request->file('thumbnail_image')->store('course-thumbnails', 'public');
            $validated['thumbnail_image'] = $path;
        } else {
            unset($validated['thumbnail_image']); // Remove from data if no file
        }

        // Handle free course pricing
        if ($validated['is_free']) {
            $validated['price'] = 0.00;
            $validated['discount_price'] = null;
            $validated['discount_expires_at'] = null;
        }

        // Set published timestamp if publishing
        if ($validated['status'] === 'published' && !array_key_exists('published_at', $validated)) {
            $validated['published_at'] = now();
        }

        $course = Course::create($validated);

        return redirect()->route('admin.courses.builder', $course)
            ->with('success', 'Course created successfully! Now build your course content.');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): Response
    {
        $course->loadCount(['enrollments']);

        $categories = CourseCategory::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $instructors = Teacher::where('is_active', true)
            ->orderBy('full_name')
            ->get(['id', 'full_name']);

        return Inertia::render('Admin/Courses/Edit', [
            'course' => $course,
            'categories' => $categories,
            'instructors' => $instructors,
        ]);
    }

    /**
     * Update the specified course.
     */
    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        // Update slug if title changed
        if ($validated['title'] !== $course->title) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $slug;
        }

        // Handle thumbnail image upload
        if ($request->hasFile('thumbnail_image')) {
            // Delete old image if exists
            if ($course->thumbnail_image && Storage::disk('public')->exists($course->thumbnail_image)) {
                Storage::disk('public')->delete($course->thumbnail_image);
            }
            
            $path = $request->file('thumbnail_image')->store('course-thumbnails', 'public');
            $validated['thumbnail_image'] = $path;
        } else {
            unset($validated['thumbnail_image']); // Keep existing image if no new file
        }

        // Handle free course pricing
        if ($validated['is_free']) {
            $validated['price'] = 0.00;
            $validated['discount_price'] = null;
            $validated['discount_expires_at'] = null;
        }

        // Set published timestamp if publishing for first time
        if ($validated['status'] === 'published' && 
            $course->status !== 'published' && 
            !$course->published_at) {
            $validated['published_at'] = now();
        }

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): RedirectResponse
    {
        // Check if course has enrollments
        if ($course->enrollments()->count() > 0) {
            return back()->withErrors([
                'delete' => 'এই কোর্সে শিক্ষার্থী নথিভুক্ত রয়েছে। কোর্স মুছে ফেলা যাবে না।'
            ]);
        }

        // Check if course has payments
        if ($course->payments()->count() > 0) {
            return back()->withErrors([
                'delete' => 'এই কোর্সের জন্য পেমেন্ট রেকর্ড রয়েছে। কোর্স মুছে ফেলা যাবে না।'
            ]);
        }

        $course->delete();

        return redirect()->route('admin.courses.index');
    }

    /**
     * Toggle course featured status.
     */
    public function toggleFeatured(Course $course): RedirectResponse
    {
        $course->update([
            'is_featured' => !$course->is_featured
        ]);
        
        return back();
    }

    /**
     * Update course status.
     */
    public function updateStatus(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,review,published,archived'
        ]);

        // Set published timestamp if publishing for first time
        if ($validated['status'] === 'published' && 
            $course->status !== 'published' && 
            !$course->published_at) {
            $validated['published_at'] = now();
        }

        $course->update($validated);

        return back();
    }

    /**
     * Show the Course Builder interface.
     */
    public function builder(Course $course): Response
    {
        $course->load([
            'instructor',
            'category',
            'modules.lessons' => function ($query) {
                $query->orderBy('sort_order');
            }
        ]);

        $course->loadCount(['enrollments', 'modules', 'lessons']);

        return Inertia::render('Admin/Courses/Builder', [
            'course' => $course,
        ]);
    }

    /**
     * Store a new module.
     */
    public function storeModule(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['course_id'] = $course->id;
        
        // Auto-generate sort order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = CourseModule::where('course_id', $course->id)->max('sort_order') + 1;
        }

        CourseModule::create($validated);

        return back()->with('success', 'Module added successfully!');
    }

    /**
     * Update a module.
     */
    public function updateModule(Request $request, Course $course, CourseModule $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $module->update($validated);

        return back()->with('success', 'Module updated successfully!');
    }

    /**
     * Delete a module.
     */
    public function deleteModule(Course $course, CourseModule $module): RedirectResponse
    {
        // Check if module has lessons
        if ($module->lessons()->count() > 0) {
            return back()->with('error', 'Cannot delete module that contains lessons. Please delete lessons first.');
        }

        $module->delete();

        return back()->with('success', 'Module deleted successfully!');
    }

    /**
     * Store a new lesson.
     */
    public function storeLesson(Request $request, Course $course, CourseModule $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'lesson_type' => 'required|in:video,text,mixed',
            'video_url' => 'nullable|string|max:500',
            'video_duration' => 'nullable|integer|min:0',
            'video_provider' => 'nullable|in:youtube,vimeo,local',
            'reading_time_minutes' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_preview' => 'boolean',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Custom validation: lesson must have either video content OR text content (or both)
        $hasVideoContent = !empty($request->video_url);
        $hasTextContent = !empty($request->content) && !empty(trim(strip_tags($request->content)));
        
        if (!$hasVideoContent && !$hasTextContent) {
            return back()->withErrors([
                'content' => 'A lesson must have either video content or text content (or both).',
                'video_url' => 'A lesson must have either video content or text content (or both).'
            ]);
        }

        // Set lesson type based on content
        if ($hasVideoContent && $hasTextContent) {
            $validated['lesson_type'] = 'mixed';
        } elseif ($hasVideoContent) {
            $validated['lesson_type'] = 'video';
        } else {
            $validated['lesson_type'] = 'text';
        }

        $validated['course_id'] = $course->id;
        $validated['module_id'] = $module->id;
        
        // Auto-generate sort order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = CourseLesson::where('module_id', $module->id)->max('sort_order') + 1;
        }

        CourseLesson::create($validated);

        return back()->with('success', 'Lesson added successfully!');
    }

    /**
     * Update a lesson.
     */
    public function updateLesson(Request $request, Course $course, CourseLesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'lesson_type' => 'required|in:video,text,mixed',
            'video_url' => 'nullable|string|max:500',
            'video_duration' => 'nullable|integer|min:0',
            'video_provider' => 'nullable|in:youtube,vimeo,local',
            'reading_time_minutes' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_preview' => 'boolean',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Custom validation: lesson must have either video content OR text content (or both)
        $hasVideoContent = !empty($request->video_url);
        $hasTextContent = !empty($request->content) && !empty(trim(strip_tags($request->content)));
        
        if (!$hasVideoContent && !$hasTextContent) {
            return back()->withErrors([
                'content' => 'A lesson must have either video content or text content (or both).',
                'video_url' => 'A lesson must have either video content or text content (or both).'
            ]);
        }

        // Set lesson type based on content
        if ($hasVideoContent && $hasTextContent) {
            $validated['lesson_type'] = 'mixed';
        } elseif ($hasVideoContent) {
            $validated['lesson_type'] = 'video';
        } else {
            $validated['lesson_type'] = 'text';
        }

        $lesson->update($validated);

        return back()->with('success', 'Lesson updated successfully!');
    }

    /**
     * Delete a lesson.
     */
    public function deleteLesson(Course $course, CourseLesson $lesson): RedirectResponse
    {
        $lesson->delete();

        return back()->with('success', 'Lesson deleted successfully!');
    }

    /**
     * Reorder course content (modules and lessons).
     */
    public function reorderContent(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'modules' => 'required|array',
            'modules.*.id' => 'required|integer|exists:course_modules,id',
            'modules.*.sort_order' => 'required|integer|min:0',
            'lessons' => 'sometimes|array',
            'lessons.*.id' => 'required|integer|exists:course_lessons,id',
            'lessons.*.sort_order' => 'required|integer|min:0',
        ]);

        // Update module orders
        foreach ($validated['modules'] as $moduleData) {
            CourseModule::where('id', $moduleData['id'])
                ->where('course_id', $course->id)
                ->update(['sort_order' => $moduleData['sort_order']]);
        }

        // Update lesson orders if provided
        if (isset($validated['lessons'])) {
            foreach ($validated['lessons'] as $lessonData) {
                CourseLesson::where('id', $lessonData['id'])
                    ->where('course_id', $course->id)
                    ->update(['sort_order' => $lessonData['sort_order']]);
            }
        }

        return back()->with('success', 'Content reordered successfully!');
    }
} 