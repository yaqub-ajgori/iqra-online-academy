<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_post_id',
        'user_id',
        'parent_id',
        'author_name',
        'author_email',
        'content',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    /**
     * Get the child comments (replies).
     */
    public function replies(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->approved()->orderBy('created_at');
    }

    /**
     * Scope to get only approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get only pending comments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get only spam comments.
     */
    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    /**
     * Get the author name (from user or guest).
     */
    public function getAuthorNameAttribute($value)
    {
        if ($this->user_id && $this->user) {
            return $this->user->name;
        }
        return $value;
    }

    /**
     * Get the author email (from user or guest).
     */
    public function getAuthorEmailAttribute($value)
    {
        if ($this->user_id && $this->user) {
            return $this->user->email;
        }
        return $value;
    }

    /**
     * Get the author avatar.
     */
    public function getAuthorAvatarAttribute(): string
    {
        if ($this->user_id && $this->user) {
            // You can implement user avatar logic here
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->author_name ?? 'Guest');
    }
}