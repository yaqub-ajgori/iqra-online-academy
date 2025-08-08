<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use App\Models\Course;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Quiz Information')
                    ->schema([
                        TextInput::make('title')
                            ->label('Quiz Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter quiz title'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Enter quiz description (optional)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Assignment')
                    ->description('Assign this quiz to a course or specific lesson')
                    ->columns(2)
                    ->schema([
                        Select::make('course_id')
                            ->label('Course')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select a course')
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('lesson_id', null)),
                            
                        Select::make('lesson_id')
                            ->label('Lesson (Optional)')
                            ->placeholder('Select a lesson')
                            ->searchable()
                            ->relationship(
                                name: 'lesson',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query, callable $get) => 
                                    $get('course_id') ? $query->where('course_id', $get('course_id')) : $query->whereNull('id')
                            )
                            ->helperText('If selected, quiz will appear after this lesson. Otherwise, quiz will be available at course level.'),
                    ]),

                Section::make('Quiz Settings')
                    ->columns(3)
                    ->schema([
                        TextInput::make('passing_score')
                            ->label('Passing Score (%)')
                            ->required()
                            ->numeric()
                            ->default(70)
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->helperText('Minimum score required to pass'),
                            
                        TextInput::make('time_limit_minutes')
                            ->label('Time Limit (Minutes)')
                            ->numeric()
                            ->minValue(1)
                            ->suffix('min')
                            ->placeholder('No limit')
                            ->helperText('Leave empty for no time limit'),
                            
                        TextInput::make('max_attempts')
                            ->label('Max Attempts')
                            ->required()
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->helperText('Maximum attempts allowed'),
                    ]),

                Section::make('Status & Ordering')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active', 
                                'inactive' => 'Inactive'
                            ])
                            ->default('active')
                            ->required()
                            ->helperText('Only active quizzes are visible to students'),
                            
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                    ]),
            ]);
    }
}
