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
            
            // Completion
            $table->boolean('is_completed')->default(false);
            
            // Access Control
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes and Constraints
            $table->unique(['student_id', 'course_id'], 'unique_student_course');
            $table->index('student_id', 'idx_enrollments_student');
            $table->index('course_id', 'idx_enrollments_course');
            $table->index('payment_status', 'idx_enrollments_status');
            $table->index('progress_percentage', 'idx_enrollments_progress');
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