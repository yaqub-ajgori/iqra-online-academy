<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Quiz Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            )
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->readOnly()
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Select::make('course_id')
                            ->label('Course')
                            ->relationship('course', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('lesson_id')
                            ->label('Lesson (Optional)')
                            ->relationship('lesson', 'title')
                            ->searchable()
                            ->preload(),

                        Select::make('type')
                            ->label('Quiz Type')
                            ->options([
                                'quiz' => 'Quiz',
                                'exam' => 'Exam', 
                                'assessment' => 'Assessment'
                            ])
                            ->default('quiz')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Settings')
                    ->columns(2)
                    ->schema([
                        TextInput::make('time_limit_minutes')
                            ->label('Time Limit (Minutes)')
                            ->numeric()
                            ->minValue(1)
                            ->suffix('minutes'),

                        TextInput::make('max_attempts')
                            ->label('Maximum Attempts')
                            ->numeric()
                            ->minValue(1)
                            ->default(3)
                            ->required(),

                        TextInput::make('passing_score')
                            ->label('Passing Score (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(70)
                            ->suffix('%')
                            ->required(),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make('Options')
                    ->columns(2)
                    ->schema([
                        Toggle::make('randomize_questions')
                            ->label('Randomize Questions'),

                        Toggle::make('show_results_immediately')
                            ->label('Show Results Immediately')
                            ->default(true),

                        Toggle::make('allow_review')
                            ->label('Allow Review')
                            ->default(true),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
                    
                Section::make('Scheduling')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('available_from')
                            ->label('Available From')
                            ->native(false),

                        DateTimePicker::make('available_until')
                            ->label('Available Until')
                            ->after('available_from') 
                            ->native(false),
                    ]),
            ]);
    }
}