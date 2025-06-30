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
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            // Enrollment Details
            $table->timestamp('enrolled_at')->useCurrent();
            $table->enum('enrollment_type', ['paid', 'free', 'scholarship', 'trial'])->default('paid');
            
            // Payment Information
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->string('currency', 3)->default('BDT');
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            
            // Course Progress
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->integer('lessons_completed')->default(0);
            $table->integer('quizzes_completed')->default(0);
            $table->integer('assignments_completed')->default(0);
            
            // Completion
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->string('completion_certificate_url', 500)->nullable();
            $table->timestamp('certificate_issued_at')->nullable();
            
            // Access Control
            $table->boolean('is_active')->default(true);
            $table->timestamp('access_expires_at')->nullable(); // For time-limited access
            $table->timestamp('last_accessed_at')->nullable();
            
            // Performance
            $table->decimal('final_score', 5, 2)->nullable(); // Overall course score
            $table->enum('final_grade', ['A+', 'A', 'B+', 'B', 'C+', 'C', 'F'])->nullable();
            
            $table->timestamps();
            
            // Indexes and Constraints
            $table->unique(['student_id', 'course_id'], 'unique_student_course');
            $table->index('student_id', 'idx_enrollments_student');
            $table->index('course_id', 'idx_enrollments_course');
            $table->index('payment_status', 'idx_enrollments_status');
            $table->index('progress_percentage', 'idx_enrollments_progress');
            $table->index('is_completed', 'idx_enrollments_completed');
            $table->index('enrolled_at', 'idx_enrollments_enrolled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
    }
}; 