<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    /**
     * Show the learning interface for a course
     */
    public function show(Course $course)
    {
        // Check if student is enrolled
        $enrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('student_id', Auth::user()->student->id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return redirect()->route('frontend.courses.show', $course->slug)
                ->with('error', 'আপনি এই কোর্সে নিবন্ধিত নন।');
        }

        // Load course with modules and lessons
        $course->load([
            'modules' => function ($query) {
                $query->orderBy('order');
            },
            'modules.lessons' => function ($query) {
                $query->orderBy('order');
            }
        ]);

        // Get completed lessons for this student
        $completedLessons = LessonProgress::where('student_id', Auth::user()->student->id)
            ->where('course_id', $course->id)
            ->pluck('lesson_id')
            ->toArray();

        // Mark lessons as completed
        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                $lesson->completed = in_array($lesson->id, $completedLessons);
            }
        }

        // Calculate progress
        $totalLessons = $course->modules->sum(function ($module) {
            return $module->lessons->count();
        });

        $completedCount = count($completedLessons);

        return Inertia::render('Frontend/LearningPage', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'modules' => $course->modules->map(function ($module) {
                    return [
                        'id' => $module->id,
                        'title' => $module->title,
                        'duration' => $module->duration ?? '0 min',
                        'lessons' => $module->lessons->map(function ($lesson) {
                            return [
                                'id' => $lesson->id,
                                'order' => $lesson->order,
                                'title' => $lesson->title,
                                'duration' => $lesson->duration ?? '0 min',
                                'type' => $lesson->type,
                                'video_url' => $lesson->video_url,
                                'content' => $lesson->content,
                                'pdf_url' => $lesson->pdf_url,
                                'audio_url' => $lesson->audio_url,
                                'completed' => $lesson->completed,
                                'module_id' => $lesson->module_id,
                            ];
                        })
                    ];
                }),
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedCount,
            ],
            'enrollment' => [
                'id' => $enrollment->id,
                'completed_lessons' => $completedCount,
                'progress_percentage' => $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100, 2) : 0,
            ],
            'course_slug' => $course->slug,
        ]);
    }

    /**
     * Mark a lesson as completed
     */
    public function completeLesson(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:course_lessons,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $student = Auth::user()->student;
        $lessonId = $request->lesson_id;
        $courseId = $request->course_id;

        // Check if student is enrolled in this course
        $enrollment = CourseEnrollment::where('course_id', $courseId)
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'আপনি এই কোর্সে নিবন্ধিত নন।'], 403);
        }

        // Check if lesson belongs to this course
        $lesson = CourseLesson::where('id', $lessonId)
            ->whereHas('module', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->first();

        if (!$lesson) {
            return response()->json(['error' => 'পাঠ পাওয়া যায়নি।'], 404);
        }

        // Create or update lesson progress
        LessonProgress::updateOrCreate(
            [
                'student_id' => $student->id,
                'course_id' => $courseId,
                'lesson_id' => $lessonId,
            ],
            [
                'completed_at' => now(),
                'status' => 'completed',
            ]
        );

        // Get updated completion count
        $completedCount = LessonProgress::where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'পাঠ সম্পূর্ণ হিসেবে চিহ্নিত হয়েছে।',
            'completed_lessons' => $completedCount,
        ]);
    }

    /**
     * Show a specific lesson
     */
    public function lesson(Course $course, CourseLesson $lesson)
    {
        // Check if student is enrolled
        $enrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('student_id', Auth::user()->student->id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return redirect()->route('frontend.courses.show', $course->slug)
                ->with('error', 'আপনি এই কোর্সে নিবন্ধিত নন।');
        }

        // Check if lesson belongs to this course
        if ($lesson->module->course_id !== $course->id) {
            abort(404);
        }

        // Check if lesson is completed
        $isCompleted = LessonProgress::where('student_id', Auth::user()->student->id)
            ->where('course_id', $course->id)
            ->where('lesson_id', $lesson->id)
            ->exists();

        return Inertia::render('Frontend/LearningPage', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
            ],
            'current_lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'type' => $lesson->type,
                'video_url' => $lesson->video_url,
                'content' => $lesson->content,
                'pdf_url' => $lesson->pdf_url,
                'audio_url' => $lesson->audio_url,
                'completed' => $isCompleted,
            ],
            'course_slug' => $course->slug,
        ]);
    }
} 