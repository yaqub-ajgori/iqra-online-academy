<?php

use App\Models\{User, Student, Teacher, Course, Quiz, QuizQuestion, QuizAttempt};

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Create admin user
    $this->admin = User::factory()->create(['email' => 'admin@test.com']);
    
    // Create test data
    $this->teacher = Teacher::factory()->create();
    $this->course = Course::factory()->create(['instructor_id' => $this->teacher->id]);
});

test('admin can view quizzes list page', function () {
    Quiz::factory()->count(5)->create(['course_id' => $this->course->id]);

    $response = $this->actingAs($this->admin)->get('/admin/quizzes');

    expect($response->status())->toBe(200);
});

test('admin can view quiz creation page', function () {
    $response = $this->actingAs($this->admin)->get('/admin/quizzes/create');

    expect($response->status())->toBe(200);
});

test('admin can create new quiz', function () {
    $quizData = [
        'course_id' => $this->course->id,
        'title' => 'Test Quiz',
        'description' => 'This is a test quiz description.',
        'type' => 'quiz',
        'time_limit_minutes' => 30,
        'max_attempts' => 3,
        'passing_score' => 70.00,
        'randomize_questions' => false,
        'show_results_immediately' => true,
        'allow_review' => true,
        'is_active' => true,
        'sort_order' => 1,
    ];

    $response = $this->actingAs($this->admin)->post('/admin/quizzes', $quizData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('quizzes', [
        'title' => 'Test Quiz',
        'course_id' => $this->course->id,
        'type' => 'quiz',
    ]);
});

test('admin can view quiz edit page', function () {
    $quiz = Quiz::factory()->create(['course_id' => $this->course->id]);

    $response = $this->actingAs($this->admin)->get("/admin/quizzes/{$quiz->id}/edit");

    expect($response->status())->toBe(200);
});

test('admin can update quiz', function () {
    $quiz = Quiz::factory()->create([
        'course_id' => $this->course->id,
        'title' => 'Original Quiz Title',
    ]);

    $updateData = [
        'course_id' => $this->course->id,
        'title' => 'Updated Quiz Title',
        'description' => 'Updated quiz description',
        'type' => 'exam',
        'time_limit_minutes' => 60,
        'max_attempts' => 2,
        'passing_score' => 80.00,
        'randomize_questions' => true,
        'show_results_immediately' => false,
        'allow_review' => false,
        'is_active' => true,
        'sort_order' => 2,
    ];

    $response = $this->actingAs($this->admin)->put("/admin/quizzes/{$quiz->id}", $updateData);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('quizzes', [
        'id' => $quiz->id,
        'title' => 'Updated Quiz Title',
        'type' => 'exam',
        'time_limit_minutes' => 60,
        'passing_score' => 80.00,
    ]);
});

test('admin can delete quiz', function () {
    $quiz = Quiz::factory()->create(['course_id' => $this->course->id]);

    $response = $this->actingAs($this->admin)->delete("/admin/quizzes/{$quiz->id}");

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseMissing('quizzes', ['id' => $quiz->id]);
});

test('admin can manage quiz questions', function () {
    $quiz = Quiz::factory()->create(['course_id' => $this->course->id]);

    $questionData = [
        'quiz_id' => $quiz->id,
        'question' => 'What is the first pillar of Islam?',
        'type' => 'multiple_choice',
        'options' => [
            'A' => 'Salah (Prayer)',
            'B' => 'Shahada (Declaration of Faith)',
            'C' => 'Zakat (Charity)',
            'D' => 'Hajj (Pilgrimage)'
        ],
        'correct_answers' => ['B'],
        'explanation' => 'Shahada is the first pillar of Islam.',
        'points' => 2.00,
        'sort_order' => 1,
        'is_active' => true,
    ];

    $response = $this->actingAs($this->admin)->post('/admin/quiz-questions', $questionData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('quiz_questions', [
        'quiz_id' => $quiz->id,
        'question' => 'What is the first pillar of Islam?',
        'type' => 'multiple_choice',
    ]);
});

test('admin can create true/false question', function () {
    $quiz = Quiz::factory()->create(['course_id' => $this->course->id]);

    $questionData = [
        'quiz_id' => $quiz->id,
        'question' => 'Islam has five pillars.',
        'type' => 'true_false',
        'options' => ['True', 'False'],
        'correct_answers' => ['True'],
        'explanation' => 'Yes, Islam has five pillars.',
        'points' => 1.00,
        'sort_order' => 1,
        'is_active' => true,
    ];

    $response = $this->actingAs($this->admin)->post('/admin/quiz-questions', $questionData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('quiz_questions', [
        'quiz_id' => $quiz->id,
        'type' => 'true_false',
    ]);
});

test('admin can view quiz attempts', function () {
    $quiz = Quiz::factory()->create(['course_id' => $this->course->id]);
    $student = Student::factory()->create();
    
    QuizAttempt::factory()->count(3)->create([
        'quiz_id' => $quiz->id,
        'student_id' => $student->id,
    ]);

    $response = $this->actingAs($this->admin)->get('/admin/quiz-attempts');

    expect($response->status())->toBe(200);
});

test('quiz validation works correctly', function () {
    $invalidData = [
        'title' => '', // Required field empty
        'time_limit_minutes' => -10, // Invalid time limit
        'max_attempts' => 0, // Invalid max attempts
        'passing_score' => 150, // Invalid percentage
    ];

    $response = $this->actingAs($this->admin)->post('/admin/quizzes', $invalidData);

    expect($response->status())->toBeIn([422, 302]); // Validation error or redirect back
});

test('admin can schedule quiz availability', function () {
    $quiz = Quiz::factory()->create(['course_id' => $this->course->id]);

    $updateData = [
        'course_id' => $this->course->id,
        'title' => $quiz->title,
        'description' => $quiz->description,
        'type' => $quiz->type,
        'time_limit_minutes' => $quiz->time_limit_minutes,
        'max_attempts' => $quiz->max_attempts,
        'passing_score' => $quiz->passing_score,
        'randomize_questions' => $quiz->randomize_questions,
        'show_results_immediately' => $quiz->show_results_immediately,
        'allow_review' => $quiz->allow_review,
        'available_from' => now()->addDay(),
        'available_until' => now()->addWeek(),
        'is_active' => true,
        'sort_order' => $quiz->sort_order,
    ];

    $response = $this->actingAs($this->admin)->put("/admin/quizzes/{$quiz->id}", $updateData);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('quizzes', [
        'id' => $quiz->id,
    ]);
    
    $updatedQuiz = Quiz::find($quiz->id);
    expect($updatedQuiz->available_from)->not->toBeNull();
    expect($updatedQuiz->available_until)->not->toBeNull();
});

test('admin can configure quiz settings', function () {
    $quiz = Quiz::factory()->create(['course_id' => $this->course->id]);

    $settingsData = [
        'course_id' => $this->course->id,
        'title' => $quiz->title,
        'description' => $quiz->description,
        'type' => 'assessment',
        'time_limit_minutes' => 45,
        'max_attempts' => 1,
        'passing_score' => 85.00,
        'randomize_questions' => true,
        'show_results_immediately' => false,
        'allow_review' => true,
        'is_active' => true,
        'sort_order' => $quiz->sort_order,
    ];

    $response = $this->actingAs($this->admin)->put("/admin/quizzes/{$quiz->id}", $settingsData);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('quizzes', [
        'id' => $quiz->id,
        'type' => 'assessment',
        'time_limit_minutes' => 45,
        'max_attempts' => 1,
        'passing_score' => 85.00,
        'randomize_questions' => true,
        'show_results_immediately' => false,
    ]);
});