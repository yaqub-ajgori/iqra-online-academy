<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\FileUpload; 
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('User Account Information')
                    ->description('Create login credentials for the teacher')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required(fn (callable $get) => empty($get('user_id')))
                            ->disabled(fn (callable $get) => !empty($get('user_id')))
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation !== 'create') return;
                                // Auto-fill teacher full_name from user name
                                $set('full_name', $state);
                            }),
                            
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required(fn (callable $get) => empty($get('user_id')))
                            ->disabled(fn (callable $get) => !empty($get('user_id')))
                            ->unique('users', 'email')
                            ->maxLength(255),
                            
                        TextInput::make('temp_password')
                            ->label('Temporary Password')
                            ->password()
                            ->disabled(fn (callable $get) => !empty($get('user_id')))
                            ->default(fn() => 'Teacher' . rand(1000, 9999))
                            ->helperText('Teacher will be required to change this password on first login')
                            ->revealable(),
                    ])
                    ->columns(2),
                    
                Section::make('Teacher Profile Information')
                    ->schema([
                        Select::make('user_id')
                            ->label('Link to Existing User (Optional)')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Leave empty to create new user account')
                            ->helperText('Select only if linking to an existing user account')
                            ->live(),
                            
                        TextInput::make('full_name')
                            ->label('Display Name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('This will be displayed publicly'),
                            
                        TextInput::make('speciality')
                            ->label('Specialization')
                            ->maxLength(255)
                            ->placeholder('e.g., Quran Studies, Islamic History, Arabic Language'),
                            
                        TextInput::make('experience')
                            ->label('Years of Experience')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(50)
                            ->suffix('years'),
                            
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20),
                            
                        FileUpload::make('profile_picture')
                            ->label('Profile Picture')
                            ->image()
                            ->disk('public')
                            ->directory('teachers/avatars')
                            ->imageEditor()
                            ->maxSize(2048),
                            
                        Toggle::make('is_active')
                            ->label('Active Teacher')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
