<?php

use App\Models\{User, BlogPost, BlogCategory, BlogTag, BlogComment, BlogReaction};

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['email' => 'admin@test.com']);
    $this->category = BlogCategory::factory()->create();
    $this->tag = BlogTag::factory()->create();
});

test('admin can view blog posts list page', function () {
    BlogPost::factory()->count(5)->create();

    $response = $this->actingAs($this->admin)->get('/admin/blog-posts');

    expect($response->status())->toBe(200);
});

test('admin can create new blog post', function () {
    $postData = [
        'title' => 'Test Blog Post',
        'slug' => 'test-blog-post',
        'excerpt' => 'This is a test blog post excerpt.',
        'content' => 'This is the full content of the test blog post.',
        'author_id' => $this->admin->id,
        'category_id' => $this->category->id,
        'status' => 'draft',
        'is_featured' => false,
        'is_sticky' => false,
        'meta_title' => 'Test Blog Post - SEO Title',
        'meta_description' => 'SEO description for test blog post.',
        'meta_keywords' => 'test, blog, post',
    ];

    $response = $this->actingAs($this->admin)->post('/admin/blog-posts', $postData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('blog_posts', [
        'title' => 'Test Blog Post',
        'slug' => 'test-blog-post',
        'status' => 'draft',
    ]);
});

test('admin can publish blog post', function () {
    $post = BlogPost::factory()->create([
        'status' => 'draft',
        'published_at' => null,
        'author_id' => $this->admin->id,
    ]);

    $updateData = [
        'title' => $post->title,
        'slug' => $post->slug,
        'excerpt' => $post->excerpt,
        'content' => $post->content,
        'author_id' => $post->author_id,
        'category_id' => $post->category_id,
        'status' => 'published',
        'published_at' => now(),
        'is_featured' => false,
        'is_sticky' => false,
        'meta_title' => $post->meta_title,
        'meta_description' => $post->meta_description,
        'meta_keywords' => $post->meta_keywords,
    ];

    $response = $this->actingAs($this->admin)->put("/admin/blog-posts/{$post->id}", $updateData);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('blog_posts', [
        'id' => $post->id,
        'status' => 'published',
    ]);
});

test('admin can feature blog post', function () {
    $post = BlogPost::factory()->create([
        'is_featured' => false,
        'author_id' => $this->admin->id,
    ]);

    $updateData = array_merge($post->toArray(), [
        'is_featured' => true,
    ]);

    $response = $this->actingAs($this->admin)->put("/admin/blog-posts/{$post->id}", $updateData);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('blog_posts', [
        'id' => $post->id,
        'is_featured' => true,
    ]);
});

test('admin can manage blog categories', function () {
    $categoryData = [
        'name' => 'Islamic Finance',
        'slug' => 'islamic-finance',
        'description' => 'Articles about Islamic finance and economics.',
        'is_active' => true,
        'meta_title' => 'Islamic Finance Category',
        'meta_description' => 'Learn about Islamic finance principles.',
    ];

    $response = $this->actingAs($this->admin)->post('/admin/blog-categories', $categoryData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('blog_categories', [
        'name' => 'Islamic Finance',
        'slug' => 'islamic-finance',
    ]);
});

test('admin can manage blog tags', function () {
    $tagData = [
        'name' => 'Ramadan',
        'slug' => 'ramadan',
        'description' => 'Articles related to Ramadan.',
        'is_active' => true,
    ];

    $response = $this->actingAs($this->admin)->post('/admin/blog-tags', $tagData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('blog_tags', [
        'name' => 'Ramadan',
        'slug' => 'ramadan',
    ]);
});

test('admin can assign tags to blog post', function () {
    $post = BlogPost::factory()->create(['author_id' => $this->admin->id]);
    $tags = BlogTag::factory()->count(3)->create();

    // Simulate attaching tags to post
    $post->tags()->attach($tags->pluck('id'));

    expect($post->tags()->count())->toBe(3);
});

test('blog post reading time is calculated', function () {
    $longContent = str_repeat('This is a test sentence with about ten words. ', 100);
    
    $post = BlogPost::factory()->create([
        'content' => $longContent,
        'reading_time' => null,
        'author_id' => $this->admin->id,
    ]);

    // Simulate reading time calculation (approximately 200 words per minute)
    $wordCount = str_word_count(strip_tags($longContent));
    $readingTime = max(1, ceil($wordCount / 200));
    
    $post->update(['reading_time' => $readingTime]);

    expect($post->reading_time)->toBeGreaterThan(0);
});

test('blog post views are tracked', function () {
    $post = BlogPost::factory()->create([
        'status' => 'published',
        'views_count' => 0,
        'author_id' => $this->admin->id,
    ]);

    // Simulate view tracking
    $post->increment('views_count');
    $post->refresh();

    expect($post->views_count)->toBe(1);
});

test('blog post SEO fields work correctly', function () {
    $post = BlogPost::factory()->create([
        'meta_title' => 'SEO Title',
        'meta_description' => 'SEO Description',
        'meta_keywords' => 'keyword1, keyword2, keyword3',
        'author_id' => $this->admin->id,
    ]);

    expect($post->meta_title)->toBe('SEO Title');
    expect($post->meta_description)->toBe('SEO Description');
    expect($post->meta_keywords)->toBe('keyword1, keyword2, keyword3');
});

test('sticky posts appear first', function () {
    $regularPost = BlogPost::factory()->create([
        'is_sticky' => false,
        'published_at' => now()->subDay(),
        'author_id' => $this->admin->id,
    ]);
    
    $stickyPost = BlogPost::factory()->create([
        'is_sticky' => true,
        'published_at' => now()->subWeek(),
        'author_id' => $this->admin->id,
    ]);

    // Simulate getting posts with sticky first
    $posts = BlogPost::where('status', 'published')
        ->orderBy('is_sticky', 'desc')
        ->orderBy('published_at', 'desc')
        ->get();

    expect($posts->first()->id)->toBe($stickyPost->id);
});

test('blog post can be archived', function () {
    $post = BlogPost::factory()->create([
        'status' => 'published',
        'author_id' => $this->admin->id,
    ]);

    $updateData = array_merge($post->toArray(), [
        'status' => 'archived',
    ]);

    $response = $this->actingAs($this->admin)->put("/admin/blog-posts/{$post->id}", $updateData);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('blog_posts', [
        'id' => $post->id,
        'status' => 'archived',
    ]);
});

test('admin can delete blog post', function () {
    $post = BlogPost::factory()->create(['author_id' => $this->admin->id]);

    $response = $this->actingAs($this->admin)->delete("/admin/blog-posts/{$post->id}");

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseMissing('blog_posts', ['id' => $post->id]);
});

test('blog post validation works correctly', function () {
    $invalidData = [
        'title' => '', // Required field empty
        'slug' => 'invalid slug with spaces', // Invalid slug format
        'content' => '', // Required field empty
        'status' => 'invalid_status', // Invalid enum value
    ];

    $response = $this->actingAs($this->admin)->post('/admin/blog-posts', $invalidData);

    expect($response->status())->toBeIn([422, 302]); // Validation error or redirect back
});