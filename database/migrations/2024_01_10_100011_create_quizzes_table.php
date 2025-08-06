<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quizzes/Exams table
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->constrained('course_lessons')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['quiz', 'exam', 'assessment'])->default('quiz');
            
            // Quiz Settings
            $table->integer('time_limit_minutes')->nullable(); // Time limit in minutes
            $table->integer('max_attempts')->default(3);
            $table->decimal('passing_score', 5, 2)->default(70.00); // Passing percentage
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_results_immediately')->default(true);
            $table->boolean('allow_review')->default(true);
            
            // Scheduling
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index('course_id');
            $table->index('lesson_id');
            $table->index('type');
            $table->index(['course_id', 'sort_order']);
        });
        
        // Quiz Questions table
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer', 'essay'])->default('multiple_choice');
            $table->json('options')->nullable(); // For multiple choice questions
            $table->json('correct_answers'); // Correct answer(s)
            $table->text('explanation')->nullable(); // Explanation for the answer
            $table->decimal('points', 8, 2)->default(1.00);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('quiz_id');
            $table->index(['quiz_id', 'sort_order']);
        });
        
        // Student Quiz Attempts table
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->json('answers'); // Student's answers
            $table->decimal('score', 5, 2)->nullable(); // Percentage score
            $table->integer('correct_answers')->default(0);
            $table->integer('total_questions')->default(0);
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_taken_seconds')->nullable();
            $table->boolean('is_passed')->default(false);
            $table->text('feedback')->nullable();
            $table->timestamps();
            
            $table->index('quiz_id');
            $table->index('student_id');
            $table->index(['student_id', 'quiz_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};