<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->longText('description')->nullable(); // Additional lesson description
            $table->enum('lesson_type', ['video', 'text', 'quiz', 'assignment', 'live', 'pdf', 'mixed'])->default('video');
            
            // Video Content
            $table->string('video_url', 500)->nullable();
            $table->integer('video_duration')->nullable(); // Duration in seconds
            
            // File Content (PDFs, documents, etc.)
            $table->json('attachments')->nullable(); // Multiple file attachments
            $table->json('resources')->nullable(); // Additional resources/links
            $table->string('primary_file_path')->nullable(); // Main lesson file
            $table->string('primary_file_type')->nullable(); // pdf, doc, etc.
            $table->bigInteger('primary_file_size')->nullable(); // File size in bytes
            $table->string('thumbnail')->nullable(); // Lesson thumbnail
    
            // Access Control
            $table->boolean('is_preview')->default(false); // Can be viewed without enrollment
            $table->boolean('is_mandatory')->default(true);
            
            // Completion Requirements
            $table->boolean('requires_completion')->default(false);
            $table->integer('minimum_time_seconds')->nullable(); // Minimum time to spend on lesson
            
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('module_id', 'idx_lessons_module');
            $table->index('course_id', 'idx_lessons_course');
            $table->index('lesson_type', 'idx_lessons_type');
            $table->index('sort_order', 'idx_lessons_order');
            $table->index('is_preview', 'idx_lessons_preview');
            $table->index(['module_id', 'sort_order'], 'idx_lessons_module_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
    }
}; 