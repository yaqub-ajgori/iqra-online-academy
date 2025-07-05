<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'speciality',
        'experience',
        'profile_picture',
        'phone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only active teachers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get profile picture attribute as full URL.
     */
    public function getProfilePictureUrlAttribute(): ?string
    {
        if (!$this->profile_picture) {
            return null;
        }

        // If the path already includes 'http', it's a full URL
        if (str_starts_with($this->profile_picture, 'http')) {
            return $this->profile_picture;
        }

        // Otherwise, build the full URL from the public storage path
        return asset('storage/' . $this->profile_picture);
    }
}
