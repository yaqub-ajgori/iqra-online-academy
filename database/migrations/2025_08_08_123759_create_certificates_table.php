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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            
            // Core certificate information
            $table->string('certificate_number')->unique()->index();
            $table->string('verification_code')->unique()->index();
            
            // Relationships
            $table->foreignId('enrollment_id')->constrained('course_enrollments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            
            // Certificate details
            $table->string('student_name');
            $table->string('course_title');
            $table->text('course_description')->nullable();
            $table->string('instructor_name')->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->integer('total_lessons')->default(0);
            
            // Status and metadata
            $table->enum('status', ['issued', 'revoked', 'expired'])->default('issued');
            $table->datetime('issued_at');
            $table->datetime('completed_at');
            $table->datetime('expires_at')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->datetime('revoked_at')->nullable();
            $table->foreignId('revoked_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Administrative
            $table->foreignId('issued_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('metadata')->nullable(); // For additional certificate data
            
            // Indexes for performance
            $table->index(['student_id', 'status']);
            $table->index(['course_id', 'status']);
            $table->index(['status', 'issued_at']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
