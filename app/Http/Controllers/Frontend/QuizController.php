<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizController extends Controller
{
    /**
     * Show the quiz page.
     */
    public function show(Quiz $quiz)
    {
        // Check if quiz is active
        if (!$quiz->is_active) {
            return redirect()->route('frontend.student.dashboard')
                ->with('error', 'এই কুইজটি বর্তমানে উপলব্ধ নয়।');
        }

        // Check if student is enrolled in the course
        $student = auth()->user()->student;
        $enrollment = $student->enrollments()
            ->where('course_id', $quiz->course_id)
            ->where('is_active', true)
            ->where('payment_status', 'completed')
            ->first();

        if (!$enrollment) {
            return redirect()->route('frontend.courses.show', $quiz->course->slug)
                ->with('error', 'এই কুইজটি অ্যাক্সেস করতে আপনাকে কোর্সে নিবন্ধিত হতে হবে।');
        }

        // Load quiz with questions
        $quiz->load(['questions' => function ($query) {
            $query->active()->orderBy('sort_order');
        }]);

        return Inertia::render('Frontend/QuizPage', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * Submit quiz answers.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $student = auth()->user()->student;

        // Create attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => $request->answers,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        // Calculate score
        $attempt->calculateScore();

        // Return results
        return Inertia::render('Frontend/QuizResults', [
            'quiz' => $quiz->load('course'),
            'attempt' => $attempt,
        ]);
    }
}