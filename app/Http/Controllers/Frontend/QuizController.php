<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class QuizController extends Controller
{
    /**
     * Show a quiz for taking.
     */
    public function show(Quiz $quiz)
    {
        // Check if user can take this quiz
        if (!$quiz->canUserTakeQuiz(Auth::id())) {
            return redirect()->back()->with('error', 'You cannot take this quiz at this time.');
        }

        // Load quiz with questions and course
        $quiz->load([
            'questions' => function ($query) {
                $query->orderBy('sort_order');
            },
            'course',
            'lesson'
        ]);

        // Get user's previous attempts
        $userAttempts = $quiz->attemptsFor(Auth::id())->orderBy('created_at', 'desc')->get();

        return Inertia::render('Frontend/Quiz', [
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'passing_score' => $quiz->passing_score,
                'time_limit_minutes' => $quiz->time_limit_minutes,
                'max_attempts' => $quiz->max_attempts,
                'total_questions' => $quiz->questions->count(),
                'course_id' => $quiz->course_id,
                'course_slug' => $quiz->course?->slug,
                'lesson_id' => $quiz->lesson_id,
                'questions' => $quiz->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question' => $question->question,
                        'type' => $question->type,
                        'options' => $question->options,
                        'points' => $question->points,
                    ];
                }),
            ],
            'userAttempts' => $userAttempts->map(function ($attempt) {
                return [
                    'id' => $attempt->id,
                    'score' => $attempt->score,
                    'is_passed' => $attempt->is_passed,
                    'completed_at' => $attempt->completed_at?->format('Y-m-d H:i:s'),
                    'time_spent' => $attempt->formatted_time_spent,
                ];
            }),
            'canTakeQuiz' => $quiz->canUserTakeQuiz(Auth::id()),
            'attemptsLeft' => $quiz->max_attempts - $userAttempts->count(),
        ]);
    }

    /**
     * Submit quiz answers and calculate results.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        try {
            $request->validate([
                'answers' => 'required|array',
                'started_at' => 'required|date',
            ]);

            // Check if user can still take this quiz
            if (!$quiz->canUserTakeQuiz(Auth::id())) {
                return back()->with('error', 'You cannot submit this quiz.');
            }
        } catch (\Exception $e) {
            \Log::error('Quiz validation error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Invalid quiz submission data.']);
        }

        try {
            // Calculate time spent
            $startedAt = new \DateTime($request->started_at);
            $completedAt = now();
            $timeSpentSeconds = $completedAt->diffInSeconds($startedAt);

            // Create quiz attempt
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::id(),
                'answers' => $request->answers,
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
                'time_spent_seconds' => $timeSpentSeconds,
            ]);

            // Calculate score
            $attempt->calculateScore();

            // Reload quiz data with results
            $quiz->load([
                'questions' => function ($query) {
                    $query->orderBy('sort_order');
                },
                'course',
                'lesson'
            ]);

            // Get updated user attempts
            $userAttempts = $quiz->attemptsFor(Auth::id())->orderBy('created_at', 'desc')->get();

            return Inertia::render('Frontend/Quiz', [
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'description' => $quiz->description,
                    'passing_score' => $quiz->passing_score,
                    'time_limit_minutes' => $quiz->time_limit_minutes,
                    'max_attempts' => $quiz->max_attempts,
                    'total_questions' => $quiz->questions->count(),
                    'course_id' => $quiz->course_id,
                    'course_slug' => $quiz->course?->slug,
                    'lesson_id' => $quiz->lesson_id,
                    'questions' => $quiz->questions->map(function ($question) {
                        return [
                            'id' => $question->id,
                            'question' => $question->question,
                            'type' => $question->type,
                            'options' => $question->options,
                            'points' => $question->points,
                        ];
                    }),
                ],
                'userAttempts' => $userAttempts->map(function ($attempt) {
                    return [
                        'id' => $attempt->id,
                        'score' => $attempt->score,
                        'is_passed' => $attempt->is_passed,
                        'completed_at' => $attempt->completed_at?->format('Y-m-d H:i:s'),
                        'time_spent' => $attempt->formatted_time_spent,
                    ];
                }),
                'canTakeQuiz' => $quiz->canUserTakeQuiz(Auth::id()),
                'attemptsLeft' => $quiz->max_attempts - $userAttempts->count(),
                'quiz_results' => [
                    'score' => $attempt->score,
                    'correct_answers' => $attempt->correct_answers,
                    'total_questions' => $attempt->total_questions,
                    'is_passed' => $attempt->is_passed,
                    'time_spent' => $attempt->formatted_time_spent,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Quiz submission error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to submit quiz. Please try again.']);
        }
    }
}
