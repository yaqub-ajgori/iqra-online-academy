<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                            
                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required(),
                            
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->prefix('+880'),
                            
                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->native(false),
                            
                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other'
                            ]),
                            
                        FileUpload::make('profile_picture')
                            ->label('Profile Picture')
                            ->image()
                            ->disk('public')
                            ->directory('student-profiles')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('200')
                            ->imageResizeTargetHeight('200'),
                    ]),
                    
                Section::make('Address Information')
                    ->columns(2) 
                    ->schema([
                        Textarea::make('address')
                            ->label('Address')
                            ->rows(2)
                            ->columnSpanFull(),
                            
                        TextInput::make('city')
                            ->label('City'),
                            
                        TextInput::make('district')
                            ->label('District'),
                            
                        TextInput::make('country')
                            ->label('Country')
                            ->required()
                            ->default('Bangladesh'),
                            
                        TextInput::make('postal_code')
                            ->label('Postal Code'),
                    ]),
                    
                Section::make('Additional Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('occupation')
                            ->label('Occupation'),
                            
                        Select::make('education_level')
                            ->label('Education Level')
                            ->options([
                                'primary' => 'Primary',
                                'secondary' => 'Secondary',
                                'higher_secondary' => 'Higher Secondary',
                                'undergraduate' => 'Undergraduate',
                                'graduate' => 'Graduate',
                                'postgraduate' => 'Postgraduate',
                                'other' => 'Other'
                            ]),
                            
                        Textarea::make('bio')
                            ->label('Biography')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Account Status')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                            
                        Toggle::make('is_verified')
                            ->label('Verified')
                            ->default(false),
                    ]),
            ]);
    }
}
