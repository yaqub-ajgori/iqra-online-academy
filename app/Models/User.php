<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's roles.
     */
    public function roles()
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Get the user's student profile.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the user's teacher profile.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('role_type', $role)->exists();
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Check if user is a teacher.
     */
    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user account is locked.
     */
    public function isLocked(): bool
    {
        if (!$this->is_locked) return false;
        if ($this->locked_until && now()->lessThan($this->locked_until)) return true;
        // Auto-unlock if time has passed
        if ($this->locked_until && now()->greaterThanOrEqualTo($this->locked_until)) {
            $this->is_locked = false;
            $this->locked_until = null;
            $this->save();
            return false;
        }
        return $this->is_locked;
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        if ($this->isStudent()) {
            $this->notify(new \App\Notifications\StudentResetPasswordNotification($token));
        } else {
            parent::sendPasswordResetNotification($token);
        }
    }

    /**
     * Determine if the user can access the given Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow both admin and teacher users to access the Filament admin panel
        return $this->isAdmin() || $this->isTeacher();
    }

    /**
     * Send the email verification notification (queued).
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\StudentVerifyEmail);
    }
}
