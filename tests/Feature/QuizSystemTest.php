<?php

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Quiz Creation', function () {
    it('can create a quiz with questions', function () {
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'title' => 'Test Quiz',
            'passing_score' => 70,
            'max_attempts' => 3,
        ]);
        
        $questions = QuizQuestion::factory()
            ->count(5)
            ->for($quiz)
            ->create();
        
        expect($quiz)->toBeInstanceOf(Quiz::class)
            ->and($quiz->title)->toBe('Test Quiz')
            ->and($quiz->questions)->toHaveCount(5)
            ->and($quiz->total_questions)->toBe(5);
    });
    
    it('can create different types of questions', function () {
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        $multipleChoice = QuizQuestion::factory()
            ->multipleChoice()
            ->for($quiz)
            ->create();
            
        $trueFalse = QuizQuestion::factory()
            ->trueFalse()
            ->for($quiz)
            ->create();
            
        $shortAnswer = QuizQuestion::factory()
            ->shortAnswer()
            ->for($quiz)
            ->create();
            
        expect($multipleChoice->type)->toBe('multiple_choice')
            ->and($multipleChoice->options)->toBeArray()
            ->and($trueFalse->type)->toBe('true_false')
            ->and($shortAnswer->type)->toBe('short_answer');
    });
    
    it('can associate quiz with lesson', function () {
        $course = Course::factory()->create();
        $module = CourseModule::factory()->create(['course_id' => $course->id]);
        $lesson = CourseLesson::factory()->create(['module_id' => $module->id]);
        
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
        ]);
        
        $lesson->update(['quiz_id' => $quiz->id]);
        
        expect($quiz->lesson)->toBeInstanceOf(CourseLesson::class)
            ->and($quiz->lesson->id)->toBe($lesson->id)
            ->and($lesson->fresh()->quiz_id)->toBe($quiz->id);
    });
});

describe('Quiz Availability', function () {
    it('checks if quiz is available based on dates', function () {
        $course = Course::factory()->create();
        $availableQuiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'available_from' => now()->subDay(),
            'available_until' => now()->addDay(),
            'is_active' => true,
        ]);
        
        $unavailableQuiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'available_from' => now()->addDay(),
            'available_until' => now()->addWeek(),
            'is_active' => true,
        ]);
        
        expect($availableQuiz->is_available)->toBeTrue()
            ->and($unavailableQuiz->is_available)->toBeFalse();
    });
    
    it('respects is_active flag', function () {
        $course = Course::factory()->create();
        $inactiveQuiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'is_active' => false,
        ]);
        
        expect($inactiveQuiz->is_available)->toBeFalse();
    });
});

describe('Quiz Attempts', function () {
    it('can create quiz attempt', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'max_attempts' => 3,
            'passing_score' => 70,
        ]);
        
        $questions = QuizQuestion::factory()
            ->count(3)
            ->multipleChoice()
            ->for($quiz)
            ->create();
        
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => [
                $questions[0]->id => 0,
                $questions[1]->id => 1,
                $questions[2]->id => 2,
            ],
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
            'time_taken_seconds' => 600,
        ]);
        
        expect($attempt)->toBeInstanceOf(QuizAttempt::class)
            ->and($attempt->quiz_id)->toBe($quiz->id)
            ->and($attempt->student_id)->toBe($student->id);
    });
    
    it('calculates score correctly', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'passing_score' => 70,
        ]);
        
        // Create questions with known answers
        $questions = collect([
            QuizQuestion::factory()->for($quiz)->create([
                'type' => 'multiple_choice',
                'options' => ['A', 'B', 'C', 'D'],
                'correct_answers' => [0],
                'points' => 1,
            ]),
            QuizQuestion::factory()->for($quiz)->create([
                'type' => 'multiple_choice',
                'options' => ['A', 'B', 'C', 'D'],
                'correct_answers' => [1],
                'points' => 1,
            ]),
            QuizQuestion::factory()->for($quiz)->create([
                'type' => 'multiple_choice',
                'options' => ['A', 'B', 'C', 'D'],
                'correct_answers' => [2],
                'points' => 1,
            ]),
        ]);
        
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => [
                $questions[0]->id => 0, // Correct
                $questions[1]->id => 1, // Correct
                $questions[2]->id => 0, // Wrong
            ],
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
        ]);
        
        $attempt->calculateScore();
        
        expect($attempt->correct_answers)->toBe(2)
            ->and($attempt->total_questions)->toBe(3)
            ->and($attempt->score)->toBeGreaterThan(60)
            ->and($attempt->score)->toBeLessThan(70);
    });
    
    it('determines pass/fail based on passing score', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'passing_score' => 70,
        ]);
        
        $questions = collect([
            QuizQuestion::factory()->for($quiz)->create([
                'correct_answers' => [0],
                'points' => 1,
            ]),
            QuizQuestion::factory()->for($quiz)->create([
                'correct_answers' => [1],
                'points' => 1,
            ]),
            QuizQuestion::factory()->for($quiz)->create([
                'correct_answers' => [2],
                'points' => 1,
            ]),
        ]);
        
        // Passing attempt (all correct)
        $passingAttempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => [
                $questions[0]->id => 0,
                $questions[1]->id => 1,
                $questions[2]->id => 2,
            ],
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
        ]);
        
        $passingAttempt->calculateScore();
        
        // Failing attempt (one correct)
        $failingAttempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => [
                $questions[0]->id => 0, // Correct
                $questions[1]->id => 0, // Wrong
                $questions[2]->id => 0, // Wrong
            ],
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
        ]);
        
        $failingAttempt->calculateScore();
        
        expect($passingAttempt->is_passed)->toBeTrue()
            ->and($passingAttempt->score)->toBe(100.0)
            ->and($failingAttempt->is_passed)->toBeFalse()
            ->and($failingAttempt->score)->toBeLessThan(70);
    });
});

describe('Quiz Controller', function () {
    it('prevents quiz access without enrollment', function () {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'is_active' => true,
        ]);
        
        $response = $this->actingAs($user)->get(route('quiz.show', $quiz));
        
        $response->assertRedirect()
            ->assertSessionHas('error');
    });
    
    it('enforces max attempts limit', function () {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'max_attempts' => 3,
            'is_active' => true,
        ]);
        
        // Create enrollment
        CourseEnrollment::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'is_active' => true,
            'payment_status' => 'completed',
        ]);
        
        // Create max attempts
        QuizAttempt::factory()
            ->count(3)
            ->completed()
            ->create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
            ]);
        
        $response = $this->actingAs($user)->get(route('quiz.show', $quiz));
        
        $response->assertRedirect()
            ->assertSessionHas('error');
    });
    
    it('can submit quiz answers', function () {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
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
        
        $questions = QuizQuestion::factory()
            ->count(5)
            ->for($quiz)
            ->create();
        
        $answers = [];
        foreach ($questions as $question) {
            $answers[$question->id] = 0;
        }
        
        $response = $this->actingAs($user)->post(route('quiz.submit', $quiz), [
            'answers' => $answers,
            'started_at' => now()->subMinutes(5)->toISOString(),
        ]);
        
        $response->assertRedirect()
            ->assertSessionHas('quiz_results');
            
        $this->assertDatabaseHas('quiz_attempts', [
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
        ]);
    });
    
    it('validates time limit', function () {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'time_limit_minutes' => 30,
            'is_active' => true,
        ]);
        
        // Create enrollment
        CourseEnrollment::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'is_active' => true,
            'payment_status' => 'completed',
        ]);
        
        $questions = QuizQuestion::factory()
            ->count(5)
            ->for($quiz)
            ->create();
        
        $answers = [];
        foreach ($questions as $question) {
            $answers[$question->id] = 0;
        }
        
        // Submit after time limit
        $response = $this->actingAs($user)->post(route('quiz.submit', $quiz), [
            'answers' => $answers,
            'started_at' => now()->subMinutes(35)->toISOString(),
        ]);
        
        $response->assertSessionHasErrors('quiz');
    });
});

describe('Question Answer Validation', function () {
    it('validates multiple choice answers correctly', function () {
        $question = QuizQuestion::factory()->multipleChoice()->create([
            'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
            'correct_answers' => [1], // Option B is correct
        ]);
        
        expect($question->isCorrectAnswer(1))->toBeTrue()
            ->and($question->isCorrectAnswer(0))->toBeFalse()
            ->and($question->isCorrectAnswer(2))->toBeFalse();
    });
    
    it('validates true/false answers correctly', function () {
        $question = QuizQuestion::factory()->trueFalse()->create([
            'correct_answers' => ['সত্য'],
        ]);
        
        expect($question->isCorrectAnswer('সত্য'))->toBeTrue()
            ->and($question->isCorrectAnswer('মিথ্যা'))->toBeFalse();
    });
    
    it('validates short answer with case insensitivity', function () {
        $question = QuizQuestion::factory()->shortAnswer()->create([
            'correct_answers' => ['Allah', 'ALLAH'],
        ]);
        
        expect($question->isCorrectAnswer('allah'))->toBeTrue()
            ->and($question->isCorrectAnswer('ALLAH'))->toBeTrue()
            ->and($question->isCorrectAnswer('Allah'))->toBeTrue()
            ->and($question->isCorrectAnswer('wrong'))->toBeFalse();
    });
});

describe('Quiz Statistics', function () {
    it('tracks attempt statistics', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        // Create various attempts
        QuizAttempt::factory()->count(3)->completed()->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
        ]);
        
        QuizAttempt::factory()->count(2)->failed()->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
        ]);
        
        $attempts = $quiz->attempts;
        $passedAttempts = $attempts->where('is_passed', true);
        $failedAttempts = $attempts->where('is_passed', false);
        
        expect($attempts)->toHaveCount(5)
            ->and($passedAttempts->count())->toBeGreaterThan(0)
            ->and($failedAttempts->count())->toBeGreaterThan(0);
    });
    
    it('calculates best score for student', function () {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $quiz = Quiz::factory()->create(['course_id' => $course->id]);
        
        QuizAttempt::factory()->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'score' => 60,
            'completed_at' => now(),
        ]);
        
        QuizAttempt::factory()->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'score' => 85,
            'completed_at' => now(),
        ]);
        
        QuizAttempt::factory()->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'score' => 75,
            'completed_at' => now(),
        ]);
        
        $bestScore = $quiz->attempts()
            ->where('student_id', $student->id)
            ->max('score');
            
        expect($bestScore)->toBe(85.0);
    });
});