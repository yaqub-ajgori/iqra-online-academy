<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        'is_locked',
        'locked_until',
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
            'is_locked' => 'boolean',
            'locked_until' => 'datetime',
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
        return $this->roles()->where('role_type', $role)->where('is_active', true)->exists();
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
        if ($this->isAdmin()) {
            $this->notify(new \App\Notifications\AdminResetPasswordNotification($token));
        } elseif ($this->isStudent()) {
            $this->notify(new \App\Notifications\StudentResetPasswordNotification($token));
        } else {
            parent::sendPasswordResetNotification($token);
        }
    }
}
