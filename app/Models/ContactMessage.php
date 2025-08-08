<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'priority',
        'metadata',
        'read_at',
        'replied_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    // Accessors
    protected function isUnread(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'unread',
        );
    }

    protected function isHighPriority(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->priority, ['high', 'urgent']),
        );
    }

    // Mutators
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    public function markAsReplied(): void
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now(),
        ]);
    }

    public function markAsResolved(): void
    {
        $this->update([
            'status' => 'resolved',
        ]);
    }

    // Helper methods
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'unread' => 'warning',
            'read' => 'info',
            'replied' => 'success',
            'resolved' => 'gray',
            default => 'gray',
        };
    }

    public function getPriorityBadgeColor(): string
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'gray',
        };
    }
}
