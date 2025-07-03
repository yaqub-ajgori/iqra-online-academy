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
            $table->enum('lesson_type', ['video', 'text', 'quiz', 'assignment', 'live'])->default('video');
            
            // Video Content
            $table->string('video_url', 500)->nullable();
            $table->integer('video_duration')->nullable(); // Duration in seconds
    
            // Access Control
            $table->boolean('is_preview')->default(false); // Can be viewed without enrollment
            $table->boolean('is_mandatory')->default(true);
            
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