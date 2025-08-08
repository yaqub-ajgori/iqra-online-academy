<?php

namespace App\Filament\Resources\Certificates\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('certificate_number')
                    ->required(),
                TextInput::make('verification_code')
                    ->required(),
                Select::make('enrollment_id')
                    ->relationship('enrollment', 'id')
                    ->required(),
                Select::make('student_id')
                    ->relationship('student', 'id')
                    ->required(),
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),
                TextInput::make('student_name')
                    ->required(),
                TextInput::make('course_title')
                    ->required(),
                Textarea::make('course_description')
                    ->columnSpanFull(),
                TextInput::make('instructor_name'),
                TextInput::make('final_score')
                    ->numeric(),
                TextInput::make('total_lessons')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options(['issued' => 'Issued', 'revoked' => 'Revoked', 'expired' => 'Expired'])
                    ->default('issued')
                    ->required(),
                DateTimePicker::make('issued_at')
                    ->required(),
                DateTimePicker::make('completed_at')
                    ->required(),
                DateTimePicker::make('expires_at'),
                Textarea::make('revocation_reason')
                    ->columnSpanFull(),
                DateTimePicker::make('revoked_at'),
                TextInput::make('revoked_by')
                    ->numeric(),
                TextInput::make('issued_by')
                    ->numeric(),
                TextInput::make('metadata'),
            ]);
    }
}
