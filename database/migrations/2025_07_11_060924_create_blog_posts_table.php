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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->onDelete('set null');
            $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_sticky')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('reading_time')->nullable();
            $table->integer('views_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('slug');
            $table->index('author_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_sticky');
            $table->index('published_at');
            $table->index('views_count');
            $table->fullText(['title', 'content', 'excerpt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
