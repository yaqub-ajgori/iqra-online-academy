<?php

namespace App\Filament\Traits;

trait AdminOnlyResource
{
    /**
     * Only admins can view this resource
     */
    public static function canViewAny(): bool
    {
        return auth()->user() && auth()->user()->isAdmin();
    }
    
    /**
     * Only admins can create
     */
    public static function canCreate(): bool
    {
        return auth()->user() && auth()->user()->isAdmin();
    }
}