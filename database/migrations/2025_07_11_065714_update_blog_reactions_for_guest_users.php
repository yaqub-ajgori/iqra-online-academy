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
        Schema::table('blog_reactions', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['user_id']);
            $table->dropUnique(['blog_post_id', 'user_id', 'type']);
            
            // Make user_id nullable for guest reactions
            $table->foreignId('user_id')->nullable()->change();
            
            // Add session_id for guest tracking
            $table->string('session_id')->nullable()->after('user_id');
            $table->string('ip_address')->nullable()->after('session_id');
            
            // Recreate foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Create new unique constraint that handles both auth and guest users
            $table->index(['blog_post_id', 'user_id', 'session_id', 'type'], 'blog_reactions_unique_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_reactions', function (Blueprint $table) {
            // Drop the new columns and indexes
            $table->dropIndex('blog_reactions_unique_idx');
            $table->dropForeign(['user_id']);
            $table->dropColumn(['session_id', 'ip_address']);
            
            // Restore original constraints
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['blog_post_id', 'user_id', 'type']);
        });
    }
};
