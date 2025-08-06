<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLesson extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_id',
        'course_id',
        'title',
        'content',
        'description',
        'lesson_type',
        'video_url',
        'video_duration',
        'attachments',
        'resources',
        'primary_file_path',
        'primary_file_type',
        'primary_file_size',
        'thumbnail',
        'is_preview',
        'is_mandatory',
        'requires_completion',
        'minimum_time_seconds',
        'sort_order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_preview' => 'boolean',
        'is_mandatory' => 'boolean',
        'requires_completion' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'video_duration' => 'integer',
        'minimum_time_seconds' => 'integer',
        'primary_file_size' => 'integer',
        'attachments' => 'array',
        'resources' => 'array',
    ];

    /**
     * Get the module that owns the lesson.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    /**
     * Get the course that owns the lesson.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the lesson progress records.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id');
    }

    /**
     * Scope: Filter active lessons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Scope: Filter by lesson type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('lesson_type', $type);
    }



    /**
     * Get formatted video duration
     */
    public function getFormattedDurationAttribute(): ?string
    {
        if (!$this->video_duration) {
            return null;
        }

        $hours = floor($this->video_duration / 3600);
        $minutes = floor(($this->video_duration % 3600) / 60);
        $seconds = $this->video_duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get primary file URL.
     */
    public function getPrimaryFileUrlAttribute(): ?string
    {
        if (!$this->primary_file_path) {
            return null;
        }

        if (str_starts_with($this->primary_file_path, 'http')) {
            return $this->primary_file_path;
        }

        return asset('storage/' . $this->primary_file_path);
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute(): ?string
    {
        if (!$this->primary_file_size) {
            return null;
        }

        $bytes = $this->primary_file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }

        return asset('storage/' . $this->thumbnail);
    }

    /**
     * Check if lesson has downloadable content.
     */
    public function getHasDownloadableContentAttribute(): bool
    {
        return !empty($this->primary_file_path) || 
               !empty($this->attachments);
    }

    /**
     * Get all downloadable files.
     */
    public function getDownloadableFilesAttribute(): array
    {
        $files = [];
        
        // Add primary file
        if ($this->primary_file_path) {
            $files[] = [
                'name' => basename($this->primary_file_path),
                'url' => $this->primary_file_url,
                'type' => $this->primary_file_type,
                'size' => $this->primary_file_size,
                'formatted_size' => $this->formatted_file_size,
            ];
        }
        
        // Add attachments
        if ($this->attachments) {
            foreach ($this->attachments as $attachment) {
                $files[] = [
                    'name' => $attachment['name'] ?? basename($attachment['path']),
                    'url' => str_starts_with($attachment['path'], 'http') 
                        ? $attachment['path'] 
                        : asset('storage/' . $attachment['path']),
                    'type' => $attachment['type'] ?? null,
                    'size' => $attachment['size'] ?? null,
                    'formatted_size' => isset($attachment['size']) 
                        ? $this->formatBytes($attachment['size']) 
                        : null,
                ];
            }
        }
        
        return $files;
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
