<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->columnSpan(2),
                DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->columnSpan(2),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->columnSpan(2),
            ]);
    }
}
