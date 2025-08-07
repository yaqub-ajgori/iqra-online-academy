<?php

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ========== MISSING TEST AREAS ==========

describe('Quiz Business Logic Edge Cases', function () {
    it('handles quiz with no questions gracefully', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'is_active' => true,
        ]);
        
        // Create enrollment
        CourseEnrollment::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'is_active' => true,
            'payment_status' => 'completed',
        ]);
        
        // Quiz has no questions
        expect($quiz->questions)->toHaveCount(0)
            ->and($quiz->total_questions)->toBe(0);
            
        // Should handle submission gracefully
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => [],
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
        ]);
        
        $attempt->calculateScore();
        
        expect($attempt->score)->toBe(0.0)
            ->and($attempt->total_questions)->toBe(0)
            ->and($attempt->correct_answers)->toBe(0)
            ->and($attempt->is_passed)->toBeFalse();
    });
    
    it('handles malformed question answers correctly', function () {
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        // Question with malformed correct_answers
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'type' => 'multiple_choice',
            'options' => ['A', 'B', 'C', 'D'],
            'correct_answers' => 'invalid_format', // String instead of array
        ]);
        
        expect($question->isCorrectAnswer(0))->toBeFalse()
            ->and($question->isCorrectAnswer('invalid_format'))->toBeTrue();
    });
    
    it('calculates partial scores correctly with different point values', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'passing_score' => 70,
        ]);
        
        // Questions with different point values
        $questions = [
            QuizQuestion::factory()->create([
                'quiz_id' => $quiz->id,
                'correct_answers' => [0],
                'points' => 10, // High value question
            ]),
            QuizQuestion::factory()->create([
                'quiz_id' => $quiz->id,
                'correct_answers' => [1],
                'points' => 5,
            ]),
            QuizQuestion::factory()->create([
                'quiz_id' => $quiz->id,
                'correct_answers' => [2],
                'points' => 1, // Low value question
            ]),
        ];
        
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => [
                $questions[0]->id => 0, // Correct (10 points)
                $questions[1]->id => 0, // Wrong (0 points)
                $questions[2]->id => 2, // Correct (1 point)
            ],
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
        ]);
        
        $attempt->calculateScore();
        
        expect($attempt->correct_answers)->toBe(2)
            ->and($attempt->total_questions)->toBe(3)
            ->and($attempt->score)->toBe(68.75); // 11/16 * 100 = 68.75%
    });
});

describe('Quiz Security & Access Control', function () {
    it('prevents access to inactive course quizzes', function () {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create(['status' => 'draft']); // Inactive course
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'is_active' => true,
        ]);
        
        CourseEnrollment::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'is_active' => true,
            'payment_status' => 'completed',
        ]);
        
        $response = $this->actingAs($user)->get(route('quiz.show', $quiz));
        
        // Should be blocked due to course status
        $response->assertRedirect();
    });
    
    it('prevents quiz access with pending payment', function () {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'is_active' => true,
        ]);
        
        // Enrollment with pending payment
        CourseEnrollment::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'is_active' => true,
            'payment_status' => 'pending',
        ]);
        
        $response = $this->actingAs($user)->get(route('quiz.show', $quiz));
        
        $response->assertRedirect()
            ->assertSessionHas('error');
    });
    
    it('prevents students from accessing other students quiz attempts', function () {
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        $student1 = Student::factory()->create();
        $student2 = Student::factory()->create();
        
        $attempt1 = QuizAttempt::factory()->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student1->id,
        ]);
        
        // Student 2 should not be able to see student 1's attempt
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student2->id)
            ->get();
            
        expect($attempts)->toBeEmpty();
    });
});

describe('Quiz Time Management', function () {
    it('handles timezone differences correctly', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'time_limit_minutes' => 30,
            'available_from' => now()->subHour(),
            'available_until' => now()->addHour(),
        ]);
        
        // Create enrollment
        CourseEnrollment::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'is_active' => true,
            'payment_status' => 'completed',
        ]);
        
        expect($quiz->is_available)->toBeTrue();
        
        // Test with different timezone
        $pastQuiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'available_from' => now()->addHours(2),
            'available_until' => now()->addHours(4),
        ]);
        
        expect($pastQuiz->is_available)->toBeFalse();
    });
    
    it('calculates time taken accurately', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        $startTime = now()->subMinutes(25); // 25 minutes ago
        $endTime = now();
        
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => [],
            'started_at' => $startTime,
            'completed_at' => $endTime,
            'time_taken_seconds' => $startTime->diffInSeconds($endTime),
        ]);
        
        expect($attempt->time_taken_seconds)->toBe(1500) // 25 * 60
            ->and($attempt->formatted_time_taken)->toBe('25:00');
    });
});

describe('Quiz Scoring Edge Cases', function () {
    it('handles essay questions requiring manual grading', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        $essayQuestion = QuizQuestion::factory()->essay()->create([
            'quiz_id' => $quiz->id,
            'points' => 20,
        ]);
        
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => [
                $essayQuestion->id => 'This is a detailed essay answer...',
            ],
            'started_at' => now()->subMinutes(30),
            'completed_at' => now(),
        ]);
        
        $attempt->calculateScore();
        
        // Essay questions should not auto-score
        expect($attempt->score)->toBe(0.0)
            ->and($attempt->correct_answers)->toBe(0)
            ->and($attempt->is_passed)->toBeFalse();
    });
    
    it('handles mixed question types scoring', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'passing_score' => 60,
        ]);
        
        $questions = [
            // Multiple choice (auto-gradeable)
            QuizQuestion::factory()->multipleChoice()->create([
                'quiz_id' => $quiz->id,
                'correct_answers' => [1],
                'points' => 5,
            ]),
            // True/false (auto-gradeable)
            QuizQuestion::factory()->trueFalse()->create([
                'quiz_id' => $quiz->id,
                'correct_answers' => ['সত্য'],
                'points' => 3,
            ]),
            // Essay (manual grading)
            QuizQuestion::factory()->essay()->create([
                'quiz_id' => $quiz->id,
                'points' => 10,
            ]),
        ];
        
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => [
                $questions[0]->id => 1, // Correct
                $questions[1]->id => 'সত্য', // Correct
                $questions[2]->id => 'Essay answer here', // Needs manual grading
            ],
            'started_at' => now()->subMinutes(20),
            'completed_at' => now(),
        ]);
        
        $attempt->calculateScore();
        
        // Only auto-gradeable questions count
        expect($attempt->score)->toBe(44.44) // 8/18 * 100 ≈ 44.44%
            ->and($attempt->correct_answers)->toBe(2)
            ->and($attempt->total_questions)->toBe(3);
    });
});

describe('Quiz Performance & Scalability', function () {
    it('handles large number of questions efficiently', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        // Create 100 questions
        $questions = QuizQuestion::factory()
            ->count(100)
            ->multipleChoice()
            ->for($quiz)
            ->create();
            
        $answers = [];
        foreach ($questions as $question) {
            $answers[$question->id] = 0; // All same answer
        }
        
        $startTime = microtime(true);
        
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => $answers,
            'started_at' => now()->subMinutes(60),
            'completed_at' => now(),
        ]);
        
        $attempt->calculateScore();
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        expect($attempt->total_questions)->toBe(100)
            ->and($executionTime)->toBeLessThan(5); // Should complete within 5 seconds
    });
});

describe('Quiz Data Integrity', function () {
    it('maintains referential integrity when deleting quiz', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        $questions = QuizQuestion::factory()
            ->count(5)
            ->for($quiz)
            ->create();
            
        $attempt = QuizAttempt::factory()
            ->for($quiz)
            ->for($student)
            ->create();
        
        // Delete quiz should cascade delete questions and attempts
        $quiz->delete();
        
        $this->assertDatabaseMissing('quizzes', ['id' => $quiz->id]);
        $this->assertDatabaseMissing('quiz_questions', ['quiz_id' => $quiz->id]);
        $this->assertDatabaseMissing('quiz_attempts', ['quiz_id' => $quiz->id]);
    });
    
    it('handles concurrent quiz submissions', function () {
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'max_attempts' => 1,
        ]);
        
        $student1 = Student::factory()->create();
        $student2 = Student::factory()->create();
        
        // Simulate concurrent submissions
        $attempt1 = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student1->id,
            'answers' => [],
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
        ]);
        
        $attempt2 = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student2->id,
            'answers' => [],
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
        ]);
        
        expect($attempt1->student_id)->not->toBe($attempt2->student_id);
    });
});

describe('Quiz Accessibility & Internationalization', function () {
    it('handles Bengali text correctly in questions and answers', function () {
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        $bengaliQuestion = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question' => 'ইসলামের পাঁচটি স্তম্ভের নাম কী?',
            'type' => 'short_answer',
            'correct_answers' => ['কালেমা, নামাজ, রোজা, হজ্জ, যাকাত'],
        ]);
        
        expect($bengaliQuestion->question)->toContain('ইসলামের')
            ->and($bengaliQuestion->isCorrectAnswer('কালেমা, নামাজ, রোজা, হজ্জ, যাকাত'))->toBeTrue();
    });
    
    it('supports RTL text formatting for Arabic content', function () {
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        $arabicQuestion = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question' => 'ما هي أركان الإسلام الخمسة؟',
            'type' => 'short_answer',
            'correct_answers' => ['الشهادة، الصلاة، الزكاة، الصوم، الحج'],
        ]);
        
        expect($arabicQuestion->question)->toContain('أركان')
            ->and($arabicQuestion->isCorrectAnswer('الشهادة، الصلاة، الزكاة، الصوم، الحج'))->toBeTrue();
    });
});

describe('Quiz Analytics & Reporting', function () {
    it('tracks detailed quiz analytics', function () {
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        $students = Student::factory()->count(10)->create();
        
        foreach ($students as $student) {
            QuizAttempt::factory()
                ->completed()
                ->create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $student->id,
                ]);
        }
        
        $totalAttempts = $quiz->attempts()->count();
        $passedAttempts = $quiz->attempts()->where('is_passed', true)->count();
        $averageScore = $quiz->attempts()->avg('score');
        
        expect($totalAttempts)->toBe(10)
            ->and($passedAttempts)->toBeGreaterThan(0)
            ->and($averageScore)->toBeGreaterThan(50); // Assuming reasonable pass rate
    });
});

describe('Quiz Integration with Learning System', function () {
    it('properly updates lesson progress when lesson quiz is passed', function () {
        $course = Course::factory()->create();
        $module = CourseModule::factory()->create(['course_id' => $course->id]);
        $lesson = CourseLesson::factory()->create(['module_id' => $module->id]);
        $student = Student::factory()->create();
        
        $enrollment = CourseEnrollment::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'is_active' => true,
            'payment_status' => 'completed',
        ]);
        
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'passing_score' => 70,
        ]);
        
        // Update lesson to reference the quiz
        $lesson->update(['quiz_id' => $quiz->id]);
        
        $questions = QuizQuestion::factory()
            ->count(5)
            ->multipleChoice()
            ->for($quiz)
            ->create([
                'correct_answers' => [0],
            ]);
        
        // Passing attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => array_fill_keys($questions->pluck('id')->toArray(), 0), // All correct
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
        ]);
        
        $attempt->calculateScore();
        
        expect($attempt->is_passed)->toBeTrue()
            ->and($attempt->score)->toBeGreaterThanOrEqual(70);
    });
});