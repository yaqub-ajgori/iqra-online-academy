<?php

use App\Models\{User, Student, Teacher, Course, Quiz, QuizQuestion, QuizAttempt, CourseEnrollment};

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Create test data
    $this->student = Student::factory()->create();
    $this->user = User::factory()->create();
    $this->student->update(['user_id' => $this->user->id]);
    
    $this->teacher = Teacher::factory()->create();
    $this->course = Course::factory()->create(['instructor_id' => $this->teacher->id]);
    
    // Enroll student in course
    CourseEnrollment::factory()->create([
        'student_id' => $this->student->id,
        'course_id' => $this->course->id,
        'is_active' => true,
    ]);
    
    $this->quiz = Quiz::factory()->create([
        'course_id' => $this->course->id,
        'time_limit_minutes' => 30,
        'max_attempts' => 3,
        'passing_score' => 70.00,
        'is_active' => true,
    ]);
});

test('enrolled student can view quiz page', function () {
    $response = $this->actingAs($this->user)->get("/courses/{$this->course->id}/quizzes/{$this->quiz->id}");

    expect($response->status())->toBe(200);
});

test('non-enrolled student cannot access quiz', function () {
    $otherStudent = Student::factory()->create();
    $otherUser = User::factory()->create();
    $otherStudent->update(['user_id' => $otherUser->id]);

    $response = $this->actingAs($otherUser)->get("/courses/{$this->course->id}/quizzes/{$this->quiz->id}");

    expect($response->status())->toBeIn([403, 302]);
});

test('student can start quiz attempt', function () {
    // Create quiz questions
    QuizQuestion::factory()->count(5)->create(['quiz_id' => $this->quiz->id]);

    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/quizzes/{$this->quiz->id}/start");

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('quiz_attempts', [
        'quiz_id' => $this->quiz->id,
        'student_id' => $this->student->id,
        'completed_at' => null,
    ]);
});

test('student can submit quiz answers', function () {
    // Create quiz questions
    $questions = QuizQuestion::factory()->count(3)->create([
        'quiz_id' => $this->quiz->id,
        'type' => 'multiple_choice',
        'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C'],
        'correct_answers' => ['A'],
    ]);

    // Start attempt
    $attempt = QuizAttempt::factory()->create([
        'quiz_id' => $this->quiz->id,
        'student_id' => $this->student->id,
        'started_at' => now(),
        'completed_at' => null,
    ]);

    // Submit answers
    $answers = [];
    foreach ($questions as $question) {
        $answers[$question->id] = 'A'; // All correct answers
    }

    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/quizzes/{$this->quiz->id}/submit", [
        'answers' => $answers,
        'attempt_id' => $attempt->id,
    ]);

    expect($response->status())->toBeIn([200, 302]);
    
    $attempt->refresh();
    expect($attempt->completed_at)->not->toBeNull();
    expect($attempt->score)->toBe(100.00);
    expect($attempt->is_passed)->toBeTrue();
});

test('quiz scoring works correctly', function () {
    // Create 4 questions with different point values
    $question1 = QuizQuestion::factory()->create([
        'quiz_id' => $this->quiz->id,
        'correct_answers' => ['A'],
        'points' => 1.00,
    ]);
    
    $question2 = QuizQuestion::factory()->create([
        'quiz_id' => $this->quiz->id,
        'correct_answers' => ['B'],
        'points' => 2.00,
    ]);
    
    $question3 = QuizQuestion::factory()->create([
        'quiz_id' => $this->quiz->id,
        'correct_answers' => ['C'],
        'points' => 2.00,
    ]);

    // Start attempt
    $attempt = QuizAttempt::factory()->create([
        'quiz_id' => $this->quiz->id,
        'student_id' => $this->student->id,
        'started_at' => now(),
        'completed_at' => null,
    ]);

    // Submit mixed answers (2 correct, 1 wrong)
    $answers = [
        $question1->id => 'A', // Correct (1 point)
        $question2->id => 'B', // Correct (2 points)
        $question3->id => 'A', // Wrong (0 points)
    ];

    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/quizzes/{$this->quiz->id}/submit", [
        'answers' => $answers,
        'attempt_id' => $attempt->id,
    ]);

    expect($response->status())->toBeIn([200, 302]);
    
    $attempt->refresh();
    expect($attempt->correct_answers)->toBe(2);
    expect($attempt->total_questions)->toBe(3);
    // Score should be 60% (3 points out of 5 total points)
    expect((float)$attempt->score)->toBe(60.00);
    expect($attempt->is_passed)->toBeFalse(); // Below 70% passing score
});

test('student cannot exceed max attempts', function () {
    // Create 3 attempts (max allowed)
    QuizAttempt::factory()->count(3)->create([
        'quiz_id' => $this->quiz->id,
        'student_id' => $this->student->id,
        'completed_at' => now(),
    ]);

    // Try to start 4th attempt
    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/quizzes/{$this->quiz->id}/start");

    expect($response->status())->toBeIn([403, 422, 302]);
});

test('quiz time limit is enforced', function () {
    QuizQuestion::factory()->count(3)->create(['quiz_id' => $this->quiz->id]);

    // Create attempt that started 35 minutes ago (exceeds 30 min limit)
    $attempt = QuizAttempt::factory()->create([
        'quiz_id' => $this->quiz->id,
        'student_id' => $this->student->id,
        'started_at' => now()->subMinutes(35),
        'completed_at' => null,
    ]);

    // Try to submit after time limit
    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/quizzes/{$this->quiz->id}/submit", [
        'answers' => [],
        'attempt_id' => $attempt->id,
    ]);

    expect($response->status())->toBeIn([403, 422, 302]);
});

test('student can review quiz results if allowed', function () {
    $this->quiz->update(['allow_review' => true]);
    
    $attempt = QuizAttempt::factory()->create([
        'quiz_id' => $this->quiz->id,
        'student_id' => $this->student->id,
        'completed_at' => now(),
        'score' => 85.00,
        'is_passed' => true,
    ]);

    $response = $this->actingAs($this->user)->get("/courses/{$this->course->id}/quizzes/{$this->quiz->id}/attempts/{$attempt->id}/review");

    expect($response->status())->toBe(200);
});

test('student cannot review quiz if not allowed', function () {
    $this->quiz->update(['allow_review' => false]);
    
    $attempt = QuizAttempt::factory()->create([
        'quiz_id' => $this->quiz->id,
        'student_id' => $this->student->id,
        'completed_at' => now(),
    ]);

    $response = $this->actingAs($this->user)->get("/courses/{$this->course->id}/quizzes/{$this->quiz->id}/attempts/{$attempt->id}/review");

    expect($response->status())->toBeIn([403, 302]);
});

test('inactive quiz cannot be accessed', function () {
    $this->quiz->update(['is_active' => false]);

    $response = $this->actingAs($this->user)->get("/courses/{$this->course->id}/quizzes/{$this->quiz->id}");

    expect($response->status())->toBeIn([403, 404, 302]);
});

test('scheduled quiz respects availability window', function () {
    // Quiz available tomorrow
    $this->quiz->update([
        'available_from' => now()->addDay(),
        'available_until' => now()->addWeek(),
    ]);

    $response = $this->actingAs($this->user)->get("/courses/{$this->course->id}/quizzes/{$this->quiz->id}");

    expect($response->status())->toBeIn([403, 302]);
});

test('true false questions work correctly', function () {
    $question = QuizQuestion::factory()->create([
        'quiz_id' => $this->quiz->id,
        'type' => 'true_false',
        'question' => 'Islam has five pillars.',
        'options' => ['True', 'False'],
        'correct_answers' => ['True'],
    ]);

    $attempt = QuizAttempt::factory()->create([
        'quiz_id' => $this->quiz->id,
        'student_id' => $this->student->id,
        'started_at' => now(),
        'completed_at' => null,
    ]);

    $response = $this->actingAs($this->user)->post("/courses/{$this->course->id}/quizzes/{$this->quiz->id}/submit", [
        'answers' => [$question->id => 'True'],
        'attempt_id' => $attempt->id,
    ]);

    expect($response->status())->toBeIn([200, 302]);
    
    $attempt->refresh();
    expect($attempt->score)->toBe(100.00);
    expect($attempt->is_passed)->toBeTrue();
});