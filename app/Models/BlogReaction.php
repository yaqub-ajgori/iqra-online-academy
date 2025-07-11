<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_post_id',
        'user_id',
        'session_id',
        'ip_address',
        'type',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Get the post that owns the reaction.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    /**
     * Get the user that owns the reaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only likes.
     */
    public function scopeLikes($query)
    {
        return $query->where('type', 'like');
    }

    /**
     * Scope to get only helpful reactions.
     */
    public function scopeHelpful($query)
    {
        return $query->where('type', 'helpful');
    }
}