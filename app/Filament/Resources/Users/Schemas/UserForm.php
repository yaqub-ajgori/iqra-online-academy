<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn ($record) => $record === null)
                    ->minLength(8)
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                    
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->required(fn ($record) => $record === null)
                    ->same('password')
                    ->dehydrated(false),
                    
                DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->nullable()
                    ->displayFormat('Y-m-d H:i'),
                    
                Select::make('role_types')
                    ->label('User Roles')
                    ->multiple()
                    ->options([
                        'admin' => 'Administrator',
                        'teacher' => 'Teacher',
                        'student' => 'Student',
                    ])
                    ->default(['student'])
                    ->afterStateHydrated(function (Select $component, $record) {
                        if ($record) {
                            $component->state($record->roles->pluck('role_type')->toArray());
                        }
                    }),
                    
                Toggle::make('is_active')
                    ->label('Active User')
                    ->default(true)
                    ->visible(false), // Not in fillable, but can be added later
            ])
            ->columns(2);
    }
}
