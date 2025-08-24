<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CourseLesson extends Model
{
    use HasFactory;

    /**
     * Alias for file_url to support frontend PDF display.
     */
    public function getPrimaryFileUrlAttribute(): ?string
    {
        return $this->file_url;
    }
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_id',
        'course_id', // Required for data integrity
        'title',
        'content',
        'description',
        'type',
        'video_url',
        'file_path',
        'is_preview',
        'sort_order',
        'is_active',
        'duration', // duration in minutes
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_preview' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
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
     * Get the quiz for this lesson.
     */
    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class, 'lesson_id')->active();
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
        return $query->where('type', $type);
    }



    /**
     * Get file URL (for PDF lessons).
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        if (str_starts_with($this->file_path, 'http')) {
            return $this->file_path;
        }

        return asset('storage/' . $this->file_path);
    }

    /**
     * Check if lesson has downloadable content.
     */
    public function getHasDownloadableContentAttribute(): bool
    {
        return $this->type === 'pdf' && !empty($this->file_path);
    }

    /**
     * Get lesson type icon for frontend display.
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'video' => 'video',
            'pdf' => 'file-text',
            default => 'book-open',
        };
    }

    /**
     * Get lesson type display text (existing usage).
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'video' => 'ভিডিও পাঠ',
            'pdf' => 'পিডিএফ ফাইল',
            default => 'টেক্সট পাঠ',
        };
    }

    /**
     * Get formatted duration display (e.g., "5 min").
     */
    public function getDurationDisplayAttribute(): ?string
    {
        if (is_null($this->duration)) {
            return null;
        }
        $minutes = (int) $this->duration;
        if ($minutes < 1) {
            return 'Less than 1 min';
        }
        return $minutes . ' min';
    }
}
