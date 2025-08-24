<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix CASCADE DELETE constraints that are causing data loss
     * 
     * The current CASCADE DELETE constraints are automatically deleting
     * modules and lessons when courses are deleted, causing critical data loss.
     */
    public function up(): void
    {
        // 1. Fix course_modules foreign key - change from CASCADE to RESTRICT
        Schema::table('course_modules', function (Blueprint $table) {
            // Drop existing foreign key with CASCADE
            $table->dropForeign(['course_id']);
            
            // Add new foreign key with RESTRICT (prevents deletion if modules exist)
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('RESTRICT')
                  ->onUpdate('CASCADE');
        });

        // 2. Fix course_lessons foreign key - change from CASCADE to RESTRICT
        Schema::table('course_lessons', function (Blueprint $table) {
            // Drop existing foreign key with CASCADE
            $table->dropForeign(['module_id']);
            
            // Add new foreign key with RESTRICT (prevents module deletion if lessons exist)
            $table->foreign('module_id')
                  ->references('id')
                  ->on('course_modules')
                  ->onDelete('RESTRICT')
                  ->onUpdate('CASCADE');
        });

        // 3. Fix course_enrollments - this should probably stay CASCADE or SET NULL
        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            
            // Use RESTRICT to prevent course deletion if there are enrollments
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('RESTRICT')
                  ->onUpdate('CASCADE');
        });

        // 4. Fix course_reviews - RESTRICT to prevent deletion if reviews exist
        Schema::table('course_reviews', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('RESTRICT')
                  ->onUpdate('CASCADE');
        });

        // 5. Fix payments - should probably be RESTRICT too
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('RESTRICT')
                  ->onUpdate('CASCADE');
        });

        // Note: certificates, quizzes, and course_instructors can stay CASCADE
        // as they're more tightly coupled to courses
    }

    /**
     * Reverse the migration (restore CASCADE if needed)
     */
    public function down(): void
    {
        // Restore original CASCADE constraints
        Schema::table('course_modules', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
        });

        Schema::table('course_lessons', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->foreign('module_id')
                  ->references('id')
                  ->on('course_modules')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
        });

        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
        });

        Schema::table('course_reviews', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
        });
    }
};
