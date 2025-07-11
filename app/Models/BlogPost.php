<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'author_id',
        'category_id',
        'status',
        'is_featured',
        'is_sticky',
        'published_at',
        'reading_time',
        'views_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_sticky' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'reading_time' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            
            // Calculate reading time
            if (empty($post->reading_time)) {
                $post->reading_time = self::calculateReadingTime($post->content);
            }
            
            // Set meta data if not provided
            if (empty($post->meta_title)) {
                $post->meta_title = $post->title;
            }
            if (empty($post->meta_description)) {
                $post->meta_description = Str::limit(strip_tags($post->content), 160);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            
            // Recalculate reading time if content changed
            if ($post->isDirty('content')) {
                $post->reading_time = self::calculateReadingTime($post->content);
            }
        });
    }

    /**
     * Calculate reading time in minutes.
     */
    protected static function calculateReadingTime($content): int
    {
        $wordsPerMinute = 200;
        $wordCount = str_word_count(strip_tags($content));
        return max(1, ceil($wordCount / $wordsPerMinute));
    }

    /**
     * Get the author of the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the category of the post.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    /**
     * Get the tags for the post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tags', 'blog_post_id', 'blog_tag_id');
    }

    /**
     * Get the comments for the post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id');
    }

    /**
     * Get approved comments for the post.
     */
    public function approvedComments(): HasMany
    {
        return $this->comments()->approved()->whereNull('parent_id');
    }

    /**
     * Get the reactions for the post.
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(BlogReaction::class, 'blog_post_id');
    }

    /**
     * Get likes count.
     */
    public function getLikesCountAttribute(): int
    {
        return $this->reactions()->where('type', 'like')->count();
    }

    /**
     * Get helpful count.
     */
    public function getHelpfulCountAttribute(): int
    {
        return $this->reactions()->where('type', 'helpful')->count();
    }

    /**
     * Scope to get only published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope to get only featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get only sticky posts.
     */
    public function scopeSticky($query)
    {
        return $query->where('is_sticky', true);
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }

        return asset('storage/' . $this->featured_image);
    }

    /**
     * Get related posts optimized with caching.
     */
    public function relatedPosts($limit = 3)
    {
        // Cache tag IDs to avoid N+1 query
        $tagIds = $this->tags()->pluck('blog_tag_id')->toArray();
        
        return self::published()
            ->where('id', '!=', $this->id)
            ->with(['author', 'category'])
            ->where(function ($query) use ($tagIds) {
                $query->where('category_id', $this->category_id);
                
                if (!empty($tagIds)) {
                    $query->orWhereHas('tags', function ($q) use ($tagIds) {
                        $q->whereIn('blog_tag_id', $tagIds);
                    });
                }
            })
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$this->category_id])
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount()
    {
        $this->increment('views_count');
    }
}