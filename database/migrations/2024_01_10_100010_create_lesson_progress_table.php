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
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('course_enrollments')->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('course_lessons')->onDelete('cascade');
            
            // Progress Details
            $table->boolean('is_completed')->default(false);
            $table->decimal('completion_percentage', 5, 2)->default(0.00);
            $table->integer('time_spent_seconds')->default(0);
            
            // Timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            
            // Additional Data
            $table->text('notes')->nullable(); // Student notes
            $table->json('bookmarks')->nullable(); // Saved positions
            
            $table->timestamps();
            
            // Indexes and Constraints
            $table->unique(['enrollment_id', 'lesson_id'], 'unique_enrollment_lesson');
            $table->index('enrollment_id', 'idx_progress_enrollment');
            $table->index('lesson_id', 'idx_progress_lesson');
            $table->index('is_completed', 'idx_progress_completed');
            $table->index('last_accessed_at', 'idx_progress_last_accessed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
}; 