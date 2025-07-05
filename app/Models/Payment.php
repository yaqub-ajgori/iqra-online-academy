<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'status',
        'sender_number',
        'receiver_number',
        'bank_name',
        'account_number',
        'branch_name',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the student that made this payment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course for this payment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the enrollments that use this payment.
     */
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Scope to get only completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get only pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'completed';
    }

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($payment) {
            if ($payment->course_id) {
                $course = \App\Models\Course::find($payment->course_id);
                if ($course) {
                    $payment->amount = $course->effective_price;
                }
            }
        });
    }
}
