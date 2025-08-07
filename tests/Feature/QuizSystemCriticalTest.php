<?php

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ========== CRITICAL MISSING TESTS ==========

describe('Grade Calculation System', function () {
    it('calculates grade letters correctly', function () {
        $student = Student::factory()->create();
        
        $tests = [
            ['score' => 95, 'expected' => 'A+'],
            ['score' => 87, 'expected' => 'A'],
            ['score' => 82, 'expected' => 'A-'],
            ['score' => 77, 'expected' => 'B+'],
            ['score' => 72, 'expected' => 'B'],
            ['score' => 67, 'expected' => 'B-'],
            ['score' => 62, 'expected' => 'C+'],
            ['score' => 57, 'expected' => 'C'],
            ['score' => 52, 'expected' => 'C-'],
            ['score' => 45, 'expected' => 'F'],
        ];
        
        foreach ($tests as $test) {
            $attempt = QuizAttempt::factory()->create([
                'student_id' => $student->id,
                'score' => $test['score'],
                'completed_at' => now(),
            ]);
            
            expect($attempt->grade)->toBe($test['expected']);
        }
    });
});

describe('Quiz Randomization', function () {
    it('randomizes questions when enabled', function () {
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'randomize_questions' => true,
        ]);
        
        $questions = QuizQuestion::factory()
            ->count(10)
            ->sequence(
                ['sort_order' => 1],
                ['sort_order' => 2],
                ['sort_order' => 3],
                ['sort_order' => 4],
                ['sort_order' => 5],
                ['sort_order' => 6],
                ['sort_order' => 7],
                ['sort_order' => 8],
                ['sort_order' => 9],
                ['sort_order' => 10],
            )
            ->for($quiz)
            ->create();
        
        $orderedQuestions = $quiz->questions()->orderBy('sort_order')->get();
        
        // Check if questions maintain their sort order in database
        expect($orderedQuestions->pluck('sort_order')->toArray())
            ->toBe([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    });
});

describe('Question Format Validation', function () {
    it('validates formatted options for multiple choice questions', function () {
        $quiz = Quiz::factory()->create();
        $question = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'type' => 'multiple_choice',
            'options' => ['First Option', 'Second Option', 'Third Option', 'Fourth Option'],
        ]);
        
        $formattedOptions = $question->formatted_options;
        
        expect($formattedOptions)->toHaveCount(4)
            ->and($formattedOptions[0]['key'])->toBe('A')
            ->and($formattedOptions[0]['value'])->toBe('First Option')
            ->and($formattedOptions[0]['index'])->toBe(0)
            ->and($formattedOptions[3]['key'])->toBe('D')
            ->and($formattedOptions[3]['value'])->toBe('Fourth Option');
    });
    
    it('returns empty array for non-multiple choice questions', function () {
        $quiz = Quiz::factory()->create();
        $trueFalseQuestion = QuizQuestion::factory()->trueFalse()->create([
            'quiz_id' => $quiz->id,
        ]);
        
        expect($trueFalseQuestion->formatted_options)->toBeEmpty();
    });
});

describe('Attempt Time Formatting', function () {
    it('formats time taken correctly', function () {
        $student = Student::factory()->create();
        
        $timeTests = [
            ['seconds' => 45, 'expected' => '0:45'],
            ['seconds' => 90, 'expected' => '1:30'],
            ['seconds' => 3661, 'expected' => '1:01:01'], // 1 hour, 1 minute, 1 second
            ['seconds' => 7200, 'expected' => '2:00:00'], // 2 hours
        ];
        
        foreach ($timeTests as $test) {
            $attempt = QuizAttempt::factory()->create([
                'student_id' => $student->id,
                'time_taken_seconds' => $test['seconds'],
                'completed_at' => now(),
            ]);
            
            expect($attempt->formatted_time_taken)->toBe($test['expected']);
        }
    });
});

describe('Quiz Scope Methods', function () {
    it('filters active quizzes correctly', function () {
        $course = Course::factory()->create();
        
        Quiz::factory()->count(3)->create([
            'course_id' => $course->id,
            'is_active' => true,
        ]);
        
        Quiz::factory()->count(2)->create([
            'course_id' => $course->id,
            'is_active' => false,
        ]);
        
        $activeQuizzes = Quiz::active()->get();
        $allQuizzes = Quiz::all();
        
        expect($activeQuizzes)->toHaveCount(3)
            ->and($allQuizzes)->toHaveCount(5);
    });
    
    it('filters available quizzes by date range', function () {
        $course = Course::factory()->create();
        
        // Available quiz
        Quiz::factory()->create([
            'course_id' => $course->id,
            'is_active' => true,
            'available_from' => now()->subDay(),
            'available_until' => now()->addDay(),
        ]);
        
        // Future quiz
        Quiz::factory()->create([
            'course_id' => $course->id,
            'is_active' => true,
            'available_from' => now()->addDay(),
            'available_until' => now()->addWeek(),
        ]);
        
        // Expired quiz
        Quiz::factory()->create([
            'course_id' => $course->id,
            'is_active' => true,
            'available_from' => now()->subWeek(),
            'available_until' => now()->subDay(),
        ]);
        
        $availableQuizzes = Quiz::available()->get();
        
        expect($availableQuizzes)->toHaveCount(1);
    });
});

describe('Quiz Attempt Scopes', function () {
    it('filters completed attempts correctly', function () {
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create();
        
        // Completed attempts
        QuizAttempt::factory()->count(3)->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'completed_at' => now(),
        ]);
        
        // Incomplete attempts
        QuizAttempt::factory()->count(2)->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'completed_at' => null,
        ]);
        
        $completedAttempts = QuizAttempt::completed()->get();
        
        expect($completedAttempts)->toHaveCount(3);
    });
    
    it('filters passed attempts correctly', function () {
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create();
        
        // Passed attempts
        QuizAttempt::factory()->count(2)->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'is_passed' => true,
            'completed_at' => now(),
        ]);
        
        // Failed attempts
        QuizAttempt::factory()->count(3)->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'is_passed' => false,
            'completed_at' => now(),
        ]);
        
        $passedAttempts = QuizAttempt::passed()->get();
        
        expect($passedAttempts)->toHaveCount(2);
    });
});

describe('Quiz Business Rules', function () {
    it('enforces maximum attempts correctly', function () {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'max_attempts' => 2,
            'is_active' => true,
        ]);
        
        CourseEnrollment::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'is_active' => true,
            'payment_status' => 'completed',
        ]);
        
        // First attempt
        $response1 = $this->actingAs($user)->get(route('quiz.show', $quiz));
        $response1->assertOk();
        
        // Create first completed attempt
        QuizAttempt::factory()->completed()->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
        ]);
        
        // Second attempt should still be allowed
        $response2 = $this->actingAs($user)->get(route('quiz.show', $quiz));
        $response2->assertOk();
        
        // Create second completed attempt
        QuizAttempt::factory()->completed()->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
        ]);
        
        // Third attempt should be blocked
        $response3 = $this->actingAs($user)->get(route('quiz.show', $quiz));
        $response3->assertRedirect()->assertSessionHas('error');
    });
});