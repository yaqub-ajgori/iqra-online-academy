<?php

namespace App\Filament\Resources\Certificates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Certificate Details')
                            ->schema([
                                Select::make('student_id')
                                    ->label('Student')
                                    ->relationship('student', 'full_name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                Select::make('course_id')
                                    ->label('Course')
                                    ->relationship('course', 'title')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                TextInput::make('certificate_number')
                                    ->label('Certificate Number')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Auto-generated')
                                    ->columnSpan(1),

                                TextInput::make('verification_code')
                                    ->label('Verification Code')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Auto-generated')
                                    ->columnSpan(1),

                                TextInput::make('student_name')
                                    ->label('Student Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('course_title')
                                    ->label('Course Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Textarea::make('course_description')
                                    ->label('Course Description')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                TextInput::make('final_score')
                                    ->label('Final Score (%)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%')
                                    ->columnSpan(1),

                                TextInput::make('template_used')
                                    ->label('Template')
                                    ->default('default')
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Using default Islamic template')
                                    ->columnSpan(1),
                            ])
                            ->columns(2),

                        Section::make('Certificate File')
                            ->schema([
                                FileUpload::make('certificate_path')
                                    ->label('Certificate PDF')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->disk('public')
                                    ->directory('certificates')
                                    ->visibility('public')
                                    ->helperText('Upload the generated certificate PDF'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Dates')
                            ->schema([
                                DatePicker::make('completion_date')
                                    ->label('Completion Date')
                                    ->required()
                                    ->default(now()),

                                DatePicker::make('issue_date')
                                    ->label('Issue Date')
                                    ->required()
                                    ->default(now()),

                                DatePicker::make('expiry_date')
                                    ->label('Expiry Date')
                                    ->after('issue_date')
                                    ->helperText('Leave empty for no expiry'),
                            ]),

                        Section::make('Status')
                            ->schema([
                                Toggle::make('is_verified')
                                    ->label('Verified')
                                    ->default(true)
                                    ->helperText('Whether the certificate is verified'),

                                Toggle::make('is_revoked')
                                    ->label('Revoked')
                                    ->default(false)
                                    ->helperText('Mark as revoked to invalidate'),

                                Textarea::make('revocation_reason')
                                    ->label('Revocation Reason')
                                    ->rows(3)
                                    ->visible(fn ($get) => $get('is_revoked'))
                                    ->helperText('Required if certificate is revoked'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}