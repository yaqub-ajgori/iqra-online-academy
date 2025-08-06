<?php

namespace App\Filament\Traits;

use Filament\Notifications\Notification;
use Illuminate\Database\QueryException;

trait HandlesConstraintViolations
{
    /**
     * Handle constraint violation exceptions and show user-friendly notifications.
     */
    protected function handleConstraintViolation(QueryException $e, string $entityName = 'record'): void
    {
        $errorCode = $e->errorInfo[1] ?? null;
        
        // Foreign key constraint violation
        if ($errorCode === 1451) {
            $this->showConstraintViolationNotification($entityName, 'delete', 'This record is being used by other data and cannot be deleted.');
            return;
        }
        
        // Unique constraint violation
        if ($errorCode === 1062) {
            $this->showConstraintViolationNotification($entityName, 'save', 'A record with this information already exists.');
            return;
        }
        
        // Generic constraint violation
        if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
            $this->showConstraintViolationNotification($entityName, 'save', 'This operation violates data integrity rules.');
            return;
        }
        
        // Re-throw if not a handled constraint violation
        throw $e;
    }
    
    /**
     * Show a user-friendly constraint violation notification.
     */
    private function showConstraintViolationNotification(string $entityName, string $action, string $reason): void
    {
        $title = match($action) {
            'delete' => "Cannot Delete " . ucfirst($entityName),
            'save' => "Cannot Save " . ucfirst($entityName),
            default => "Operation Failed"
        };
        
        Notification::make()
            ->title($title)
            ->body($reason)
            ->danger()
            ->persistent()
            ->send();
    }
    
    /**
     * Execute an action with constraint violation handling.
     */
    protected function executeWithConstraintHandling(callable $action, string $entityName = 'record'): mixed
    {
        try {
            return $action();
        } catch (QueryException $e) {
            $this->handleConstraintViolation($e, $entityName);
            return false;
        }
    }
}