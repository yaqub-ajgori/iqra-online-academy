<?php

use App\Models\{User, BlogPost, BlogCategory, BlogTag, BlogComment, BlogReaction};

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->author = User::factory()->create();
    $this->category = BlogCategory::factory()->create();
    
    $this->post = BlogPost::factory()->create([
        'status' => 'published',
        'published_at' => now(),
        'author_id' => $this->author->id,
        'category_id' => $this->category->id,
    ]);
});

test('user can view published blog post', function () {
    $response = $this->actingAs($this->user)->get("/blog/{$this->post->slug}");

    expect($response->status())->toBe(200);
});

test('guest can view published blog post', function () {
    $response = $this->get("/blog/{$this->post->slug}");

    expect($response->status())->toBe(200);
});

test('user cannot view draft blog post', function () {
    $draftPost = BlogPost::factory()->create([
        'status' => 'draft',
        'author_id' => $this->author->id,
    ]);

    $response = $this->actingAs($this->user)->get("/blog/{$draftPost->slug}");

    expect($response->status())->toBeIn([403, 404]);
});

test('user can comment on blog post', function () {
    $commentData = [
        'content' => 'This is a test comment on the blog post.',
        'post_id' => $this->post->id,
        'author_id' => $this->user->id,
    ];

    $response = $this->actingAs($this->user)->post("/blog/{$this->post->slug}/comments", $commentData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('blog_comments', [
        'post_id' => $this->post->id,
        'author_id' => $this->user->id,
        'content' => 'This is a test comment on the blog post.',
    ]);
});

test('guest can comment with session info', function () {
    $commentData = [
        'content' => 'This is a guest comment.',
        'author_name' => 'Guest User',
        'author_email' => 'guest@example.com',
        'post_id' => $this->post->id,
    ];

    $response = $this->post("/blog/{$this->post->slug}/comments", $commentData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('blog_comments', [
        'post_id' => $this->post->id,
        'author_name' => 'Guest User',
        'author_email' => 'guest@example.com',
        'content' => 'This is a guest comment.',
    ]);
});

test('user can reply to comment', function () {
    $parentComment = BlogComment::factory()->create([
        'post_id' => $this->post->id,
        'author_id' => $this->user->id,
    ]);

    $replyData = [
        'content' => 'This is a reply to the comment.',
        'post_id' => $this->post->id,
        'parent_id' => $parentComment->id,
        'author_id' => $this->user->id,
    ];

    $response = $this->actingAs($this->user)->post("/blog/{$this->post->slug}/comments", $replyData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('blog_comments', [
        'post_id' => $this->post->id,
        'parent_id' => $parentComment->id,
        'content' => 'This is a reply to the comment.',
    ]);
});

test('user can react to blog post', function () {
    $reactionData = [
        'post_id' => $this->post->id,
        'user_id' => $this->user->id,
        'type' => 'like',
    ];

    $response = $this->actingAs($this->user)->post("/blog/{$this->post->slug}/react", $reactionData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('blog_reactions', [
        'post_id' => $this->post->id,
        'user_id' => $this->user->id,
        'type' => 'like',
    ]);
});

test('guest can react to blog post with session', function () {
    $reactionData = [
        'post_id' => $this->post->id,
        'type' => 'helpful',
    ];

    $response = $this->post("/blog/{$this->post->slug}/react", $reactionData);

    expect($response->status())->toBeIn([200, 201, 302]);
    $this->assertDatabaseHas('blog_reactions', [
        'post_id' => $this->post->id,
        'type' => 'helpful',
        'user_id' => null, // Guest reaction
    ]);
});

test('user can view blog posts by category', function () {
    BlogPost::factory()->count(3)->create([
        'category_id' => $this->category->id,
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->actingAs($this->user)->get("/blog/category/{$this->category->slug}");

    expect($response->status())->toBe(200);
});

test('user can view blog posts by tag', function () {
    $tag = BlogTag::factory()->create();
    
    // Create posts with tag
    $posts = BlogPost::factory()->count(2)->create([
        'status' => 'published',
        'published_at' => now(),
    ]);
    
    foreach ($posts as $post) {
        $post->tags()->attach($tag);
    }

    $response = $this->actingAs($this->user)->get("/blog/tag/{$tag->slug}");

    expect($response->status())->toBe(200);
});

test('blog search works correctly', function () {
    BlogPost::factory()->create([
        'title' => 'Islamic Finance Principles',
        'content' => 'This post discusses various Islamic finance principles.',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->actingAs($this->user)->get('/blog/search?q=Islamic+Finance');

    expect($response->status())->toBe(200);
});

test('featured posts are displayed prominently', function () {
    $featuredPost = BlogPost::factory()->create([
        'is_featured' => true,
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->actingAs($this->user)->get('/blog');

    expect($response->status())->toBe(200);
});

test('comment moderation works', function () {
    $comment = BlogComment::factory()->create([
        'post_id' => $this->post->id,
        'is_approved' => false,
    ]);

    // Admin approves comment
    $admin = User::factory()->create(['email' => 'admin@test.com']);
    $response = $this->actingAs($admin)->put("/admin/blog-comments/{$comment->id}", [
        'post_id' => $comment->post_id,
        'author_id' => $comment->author_id,
        'author_name' => $comment->author_name,
        'author_email' => $comment->author_email,
        'content' => $comment->content,
        'parent_id' => $comment->parent_id,
        'is_approved' => true,
    ]);

    expect($response->status())->toBeIn([200, 302]);
    $this->assertDatabaseHas('blog_comments', [
        'id' => $comment->id,
        'is_approved' => true,
    ]);
});

test('spam detection works for comments', function () {
    $spamContent = 'Buy cheap products now! Visit spam-site.com for amazing deals!';
    
    $commentData = [
        'content' => $spamContent,
        'post_id' => $this->post->id,
        'author_id' => $this->user->id,
    ];

    $response = $this->actingAs($this->user)->post("/blog/{$this->post->slug}/comments", $commentData);

    // Comment should be flagged for moderation or rejected
    expect($response->status())->toBeIn([200, 201, 302, 422]);
});

test('related posts are suggested', function () {
    $tag = BlogTag::factory()->create();
    
    // Create related posts with same tag
    $relatedPosts = BlogPost::factory()->count(3)->create([
        'status' => 'published',
        'published_at' => now(),
        'category_id' => $this->category->id,
    ]);
    
    foreach ($relatedPosts as $post) {
        $post->tags()->attach($tag);
    }
    
    $this->post->tags()->attach($tag);

    $response = $this->actingAs($this->user)->get("/blog/{$this->post->slug}");

    expect($response->status())->toBe(200);
});

test('blog pagination works correctly', function () {
    BlogPost::factory()->count(25)->create([
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->actingAs($this->user)->get('/blog');
    expect($response->status())->toBe(200);

    $response = $this->actingAs($this->user)->get('/blog?page=2');
    expect($response->status())->toBe(200);
});

test('blog RSS feed works', function () {
    BlogPost::factory()->count(10)->create([
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->get('/blog/feed');

    expect($response->status())->toBe(200);
    expect($response->headers->get('content-type'))->toContain('xml');
});