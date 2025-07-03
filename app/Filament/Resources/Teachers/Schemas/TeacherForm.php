<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('speciality')
                    ->label('Speciality')
                    ->columnSpan(2),
                TextInput::make('experience')
                    ->label('Experience')
                    ->columnSpan(1),
                TextInput::make('profile_picture')
                    ->label('Profile Picture')
                    ->columnSpan(2),
                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->columnSpan(1),
                Toggle::make('is_active')
                    ->label('Active')
                    ->columnSpan(1),
            ]);
    }
}
