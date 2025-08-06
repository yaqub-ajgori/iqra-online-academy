<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Multiple instructors support - pivot table
        Schema::create('course_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['primary', 'secondary', 'guest'])->default('secondary');
            $table->text('bio')->nullable(); // Instructor bio for this specific course
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Unique constraint - same teacher can't be added twice to same course
            $table->unique(['course_id', 'teacher_id']);
            
            // Indexes
            $table->index('course_id');
            $table->index('teacher_id');
            $table->index(['course_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_instructors');
    }
};