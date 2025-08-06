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
     * Display the specified quiz.
     */
    public function show(Quiz $quiz)
    {
        // Check if quiz is available
        if (!$quiz->is_available) {
            abort(404, 'Quiz is not available at this time.');
        }

        // Check if student is enrolled in the course
        $student = auth()->user()->student;
        $enrollment = $student->courseEnrollments()
            ->where('course_id', $quiz->course_id)
            ->first();

        if (!$enrollment) {
            abort(403, 'You must be enrolled in this course to take the quiz.');
        }

        // Load quiz with active questions
        $quiz->load(['questions' => function ($query) {
            $query->active()->orderBy('sort_order');
        }]);

        // Check if student has exceeded maximum attempts
        $attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return redirect()->route('quiz.results', [
                'quiz' => $quiz,
                'message' => 'You have exceeded the maximum number of attempts for this quiz.'
            ]);
        }

        // Get previous attempts
        $previousAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->completed()
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Frontend/QuizPlayer', [
            'quiz' => $quiz,
            'attemptCount' => $attemptCount,
            'maxAttempts' => $quiz->max_attempts,
            'previousAttempts' => $previousAttempts,
        ]);
    }

    /**
     * Submit quiz answers.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'started_at' => 'required|date',
        ]);

        $student = auth()->user()->student;

        // Check attempt limits
        $attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return back()->withErrors(['message' => 'Maximum attempts exceeded.']);
        }

        // Calculate time taken
        $startedAt = \Carbon\Carbon::parse($validated['started_at']);
        $timeTaken = $startedAt->diffInSeconds(now());

        // Create attempt record
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => $validated['answers'],
            'started_at' => $startedAt,
            'completed_at' => now(),
            'time_taken_seconds' => $timeTaken,
        ]);

        // Calculate score
        $attempt->calculateScore();

        return redirect()->route('quiz.results', $attempt);
    }

    /**
     * Show quiz results.
     */
    public function results(QuizAttempt $attempt)
    {
        // Ensure the attempt belongs to the current student
        if ($attempt->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        $attempt->load(['quiz.questions', 'quiz.course']);

        // Get detailed results if quiz allows review
        $detailedResults = null;
        if ($attempt->quiz->allow_review) {
            $detailedResults = $this->getDetailedResults($attempt);
        }

        return Inertia::render('Frontend/QuizResults', [
            'attempt' => $attempt,
            'detailedResults' => $detailedResults,
            'canRetake' => $this->canRetakeQuiz($attempt),
        ]);
    }

    /**
     * Get detailed quiz results for review.
     */
    private function getDetailedResults(QuizAttempt $attempt): array
    {
        $questions = $attempt->quiz->questions()->active()->orderBy('sort_order')->get();
        $results = [];

        foreach ($questions as $question) {
            $studentAnswer = $attempt->answers[$question->id] ?? null;
            $isCorrect = $question->isCorrectAnswer($studentAnswer);

            $results[] = [
                'question' => $question->question,
                'type' => $question->type,
                'options' => $question->formatted_options,
                'student_answer' => $studentAnswer,
                'correct_answers' => $question->correct_answers,
                'is_correct' => $isCorrect,
                'explanation' => $question->explanation,
                'points' => $question->points,
                'earned_points' => $isCorrect ? $question->points : 0,
            ];
        }

        return $results;
    }

    /**
     * Check if student can retake the quiz.
     */
    private function canRetakeQuiz(QuizAttempt $attempt): bool
    {
        $attemptCount = QuizAttempt::where('quiz_id', $attempt->quiz_id)
            ->where('student_id', $attempt->student_id)
            ->count();

        return $attemptCount < $attempt->quiz->max_attempts;
    }
}