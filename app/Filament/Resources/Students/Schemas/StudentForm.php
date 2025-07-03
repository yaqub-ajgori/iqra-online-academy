<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpan(2),
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->columnSpan(1),
                DatePicker::make('date_of_birth')
                    ->label('Date of Birth')
                    ->columnSpan(1),
                Select::make('gender')
                    ->label('Gender')
                    ->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])
                    ->columnSpan(1),
                TextInput::make('profile_picture')
                    ->label('Profile Picture')
                    ->columnSpan(1),
                Textarea::make('address')
                    ->label('Address')
                    ->columnSpanFull(),
                TextInput::make('city')
                    ->label('City')
                    ->columnSpan(1),
                TextInput::make('district')
                    ->label('District')
                    ->columnSpan(1),
                TextInput::make('country')
                    ->label('Country')
                    ->required()
                    ->default('Bangladesh')
                    ->columnSpan(1),
                TextInput::make('postal_code')
                    ->label('Postal Code')
                    ->columnSpan(1),
                TextInput::make('occupation')
                    ->label('Occupation')
                    ->columnSpan(1),
                TextInput::make('education_level')
                    ->label('Education Level')
                    ->columnSpan(1),
                Textarea::make('bio')
                    ->label('Bio')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->columnSpan(1),
                Toggle::make('is_verified')
                    ->label('Verified')
                    ->columnSpan(1),
            ]);
    }
}
