<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'certificate_number',
        'student_name',
        'course_title',
        'course_description',
        'instructors',
        'final_score',
        'completion_date',
        'issue_date',
        'expiry_date',
        'certificate_path',
        'template_used',
        'verification_code',
        'is_verified',
        'is_revoked',
        'revoked_at',
        'revocation_reason',
        'metadata',
    ];

    protected $casts = [
        'instructors' => 'array',
        'final_score' => 'decimal:2',
        'completion_date' => 'date',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_verified' => 'boolean',
        'is_revoked' => 'boolean',
        'revoked_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            // Generate unique certificate number
            if (empty($certificate->certificate_number)) {
                $certificate->certificate_number = self::generateCertificateNumber();
            }
            
            // Generate unique verification code
            if (empty($certificate->verification_code)) {
                $certificate->verification_code = self::generateVerificationCode();
            }
            
            // Set issue date if not provided
            if (empty($certificate->issue_date)) {
                $certificate->issue_date = now();
            }
        });
    }

    /**
     * Get the student that owns the certificate.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course that the certificate is for.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scope: Valid certificates only.
     */
    public function scopeValid($query)
    {
        return $query->where('is_verified', true)
                    ->where('is_revoked', false);
    }

    /**
     * Scope: Non-expired certificates.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>=', now());
        });
    }

    /**
     * Check if certificate is valid.
     */
    public function getIsValidAttribute(): bool
    {
        return $this->is_verified && 
               !$this->is_revoked && 
               ($this->expiry_date === null || $this->expiry_date->isFuture());
    }

    /**
     * Get certificate status.
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_revoked) {
            return 'revoked';
        }
        
        if (!$this->is_verified) {
            return 'unverified';
        }
        
        if ($this->expiry_date && $this->expiry_date->isPast()) {
            return 'expired';
        }
        
        return 'valid';
    }

    /**
     * Get public verification URL.
     */
    public function getVerificationUrlAttribute(): string
    {
        return route('certificates.verify', $this->verification_code);
    }

    /**
     * Get certificate download URL.
     */
    public function getDownloadUrlAttribute(): string
    {
        if (!$this->certificate_path) {
            return '';
        }
        
        return asset('storage/' . $this->certificate_path);
    }

    /**
     * Generate unique certificate number.
     */
    public static function generateCertificateNumber(): string
    {
        $year = date('Y');
        $prefix = "IOA-{$year}-";
        
        // Get the highest existing certificate number for this year
        $lastCertificate = self::where('certificate_number', 'LIKE', $prefix . '%')
            ->orderByRaw("CAST(SUBSTRING(certificate_number, LENGTH('{$prefix}') + 1) AS UNSIGNED) DESC")
            ->first();
            
        if ($lastCertificate) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastCertificate->certificate_number, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            // First certificate of the year
            $nextNumber = 1;
        }
        
        // Format with leading zeros (3 digits)
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique verification code.
     */
    public static function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (self::where('verification_code', $code)->exists());
        
        return $code;
    }

    /**
     * Revoke the certificate.
     */
    public function revoke(string $reason): void
    {
        $this->update([
            'is_revoked' => true,
            'revoked_at' => now(),
            'revocation_reason' => $reason,
        ]);
    }

    /**
     * Restore the certificate.
     */
    public function restore(): void
    {
        $this->update([
            'is_revoked' => false,
            'revoked_at' => null,
            'revocation_reason' => null,
        ]);
    }
}