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
            ->first();

        if (!$enrollment) {
            return redirect()->route('frontend.courses.show', $course->slug)
                ->with('error', 'আপনি এই কোর্সে নিবন্ধিত নন।');
        }

        // Check if enrollment is active and payment is verified
        if (!$enrollment->is_active || $enrollment->payment_status !== 'completed') {
            return redirect()->route('frontend.student.dashboard')
                ->with('error', 'আপনার পেমেন্ট যাচাই হওয়ার পর এই কোর্সটি অ্যাক্সেস করতে পারবেন। অনুগ্রহ করে অপেক্ষা করুন।');
        }

        // Load course with modules and lessons
        $course->load([
            'modules' => function ($query) {
                $query->orderBy('sort_order');
            },
            'modules.lessons' => function ($query) {
                $query->orderBy('sort_order');
            },
            'modules.lessons.quiz' => function ($query) {
                $query->where('is_active', true);
            },
            'modules.lessons.quiz.questions' => function ($query) {
                $query->active()->orderBy('sort_order');
            }
        ]);

        // Get completed lessons for this student
        $completedLessons = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('is_completed', true)
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
                        'duration' => '0 min', // Duration is calculated from lessons, not stored on module
                        'lessons' => $module->lessons->map(function ($lesson) {
                            return [
                                'id' => $lesson->id,
                                'order' => $lesson->sort_order,
                                'title' => $lesson->title ?: 'Untitled Lesson',
                                'duration' => $lesson->formatted_duration ? $lesson->formatted_duration : ($lesson->video_duration ? gmdate('H:i:s', $lesson->video_duration) : '0 min'),
                                'type' => $lesson->lesson_type ?: 'mixed',
                                'video_url' => $lesson->video_url,
                                'content' => $lesson->content,
                                'primary_file_url' => $lesson->primary_file_url,
                                'primary_file_type' => $lesson->primary_file_type,
                                'attachments' => $lesson->downloadable_files,
                                'resources' => $lesson->resources,
                                'thumbnail_url' => $lesson->thumbnail_url,
                                'completed' => $lesson->completed,
                                'module_id' => $lesson->module_id,
                                'quiz' => $lesson->quiz ? [
                                    'id' => $lesson->quiz->id,
                                    'title' => $lesson->quiz->title,
                                    'description' => $lesson->quiz->description,
                                    'time_limit_minutes' => $lesson->quiz->time_limit_minutes,
                                    'passing_score' => $lesson->quiz->passing_score,
                                    'total_questions' => $lesson->quiz->questions->count(),
                                    'questions' => $lesson->quiz->questions->map(function ($question) {
                                        return [
                                            'id' => $question->id,
                                            'question' => $question->question,
                                            'type' => $question->type,
                                            'options' => $question->options,
                                            'formatted_options' => $question->formatted_options,
                                            'points' => $question->points,
                                            'sort_order' => $question->sort_order,
                                        ];
                                    }),
                                ] : null,
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

        // Get the course by ID to retrieve the slug
        $course = Course::findOrFail($courseId);

        // Check if student is enrolled in this course
        $enrollment = CourseEnrollment::where('course_id', $courseId)
            ->where('student_id', $student->id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('frontend.learning.show', $course->slug)
                ->with('error', 'আপনি এই কোর্সে নিবন্ধিত নন।');
        }

        // Check if enrollment is active and payment is verified
        if (!$enrollment->is_active || $enrollment->payment_status !== 'completed') {
            return redirect()->route('frontend.learning.show', $course->slug)
                ->with('error', 'আপনার পেমেন্ট যাচাই হওয়ার পর এই কোর্সটি অ্যাক্সেস করতে পারবেন।');
        }

        // Check if lesson belongs to this course
        $lesson = CourseLesson::where('id', $lessonId)
            ->whereHas('module', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->first();

        if (!$lesson) {
            return redirect()->route('frontend.learning.show', $course->slug)
                ->with('error', 'পাঠ পাওয়া যায়নি।');
        }

        // Create or update lesson progress
        $progressData = [
            'is_completed' => true,
            'completion_percentage' => 100.00,
            'completed_at' => now(),
            'last_accessed_at' => now(),
        ];
        $existingProgress = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('lesson_id', $lessonId)
            ->first();
        if (!$existingProgress || !$existingProgress->started_at) {
            $progressData['started_at'] = now();
        }
        LessonProgress::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'lesson_id' => $lessonId,
            ],
            $progressData
        );
        $enrollment->updateProgress();

        // Redirect back to the learning page using the course slug
        return redirect()->route('frontend.learning.show', $course->slug)
            ->with('success', 'পাঠ সম্পূর্ণ হিসেবে চিহ্নিত হয়েছে।');
    }

    /**
     * Show a specific lesson
     */
    public function lesson(Course $course, CourseLesson $lesson)
    {
        // Check if student is enrolled
        $enrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('student_id', Auth::user()->student->id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('frontend.courses.show', $course->slug)
                ->with('error', 'আপনি এই কোর্সে নিবন্ধিত নন।');
        }

        // Check if enrollment is active and payment is verified
        if (!$enrollment->is_active || $enrollment->payment_status !== 'completed') {
            return redirect()->route('frontend.student.dashboard')
                ->with('error', 'আপনার পেমেন্ট যাচাই হওয়ার পর এই কোর্সটি অ্যাক্সেস করতে পারবেন। অনুগ্রহ করে অপেক্ষা করুন।');
        }

        // Check if lesson belongs to this course
        if ($lesson->module->course_id !== $course->id) {
            abort(404);
        }

        // Load quiz relationship if it's a quiz lesson
        if ($lesson->lesson_type === 'quiz' && $lesson->quiz_id) {
            $lesson->load(['quiz' => function ($query) {
                $query->where('is_active', true);
            }, 'quiz.questions' => function ($query) {
                $query->active()->orderBy('sort_order');
            }]);
        }

        // Check if lesson is completed
        $isCompleted = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('lesson_id', $lesson->id)
            ->where('is_completed', true)
            ->exists();

        return Inertia::render('Frontend/LearningPage', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
            ],
            'current_lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title ?: 'Untitled Lesson',
                'type' => $lesson->lesson_type ?: 'mixed',
                'video_url' => $lesson->video_url,
                'content' => $lesson->content,
                'primary_file_url' => $lesson->primary_file_url,
                'primary_file_type' => $lesson->primary_file_type,
                'attachments' => $lesson->downloadable_files,
                'resources' => $lesson->resources,
                'thumbnail_url' => $lesson->thumbnail_url,
                'completed' => $isCompleted,
                'quiz' => $lesson->quiz ? [
                    'id' => $lesson->quiz->id,
                    'title' => $lesson->quiz->title,
                    'description' => $lesson->quiz->description,
                    'time_limit_minutes' => $lesson->quiz->time_limit_minutes,
                    'passing_score' => $lesson->quiz->passing_score,
                    'total_questions' => $lesson->quiz->questions->count(),
                    'questions' => $lesson->quiz->questions->map(function ($question) {
                        return [
                            'id' => $question->id,
                            'question' => $question->question,
                            'type' => $question->type,
                            'options' => $question->options,
                            'formatted_options' => $question->formatted_options,
                            'points' => $question->points,
                            'sort_order' => $question->sort_order,
                        ];
                    }),
                ] : null,
            ],
            'course_slug' => $course->slug,
        ]);
    }
} 