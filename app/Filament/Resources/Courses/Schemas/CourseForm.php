<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Course Title')
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
                            
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                            
                        Select::make('level')
                            ->label('Difficulty Level')
                            ->options([
                                'beginner' => 'Beginner', 
                                'intermediate' => 'Intermediate', 
                                'advanced' => 'Advanced'
                            ])
                            ->required(),
                    ]),
                    
                Section::make('Content')
                    ->schema([
                        RichEditor::make('full_description')
                            ->label('Course Description')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'link',
                                'h2',
                                'h3',
                            ]),
                            
                        FileUpload::make('thumbnail_image')
                            ->label('Course Thumbnail')
                            ->image()
                            ->directory('courses/thumbnails')
                            ->disk('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('450'),
                    ]),
                    
                Section::make('Instructors')
                    ->schema([
                        Select::make('instructor_id')
                            ->label('Primary Instructor')
                            ->relationship('instructor', 'full_name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled(fn () => auth()->user()->isTeacher()),

                        Select::make('instructors')
                            ->label('Additional Instructors')
                            ->relationship('instructors', 'full_name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->disabled(fn () => auth()->user()->isTeacher()),
                    ])
                    ->hidden(fn () => auth()->user()->isTeacher()),
                    
                Section::make('Pricing')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_free')
                            ->label('Free Course')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => 
                                $state ? $set('price', 0) : null
                            )
                            ->columnSpanFull(),
                            
                        TextInput::make('price')
                            ->label('Course Price')
                            ->required()
                            ->numeric()
                            ->default(0.0)
                            ->prefix('৳')
                            ->minValue(0)
                            ->disabled(fn (callable $get) => $get('is_free')),
                            
                        TextInput::make('discount_price')
                            ->label('Discount Price')
                            ->numeric()
                            ->prefix('৳')
                            ->minValue(0)
                            ->disabled(fn (callable $get) => $get('is_free')),
                            
                        DateTimePicker::make('discount_expires_at')
                            ->label('Discount Expires')
                            ->after('today')
                            ->native(false)
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Settings')
                    ->columns(2)
                    ->schema([
                        TextInput::make('duration_in_minutes')
                            ->label('Duration (Minutes)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('minutes'),
                            
                        Select::make('status')
                            ->label('Course Status')
                            ->options(fn () => auth()->user()->isTeacher() 
                                ? [
                                    'draft' => 'Draft',
                                    'review' => 'Under Review'
                                ]
                                : [
                                    'draft' => 'Draft',
                                    'review' => 'Under Review',
                                    'published' => 'Published',
                                    'archived' => 'Archived'
                                ]
                            )
                            ->required()
                            ->default('draft'),
                            
                        DateTimePicker::make('published_at')
                            ->label('Publish Date & Time')
                            ->default(now())
                            ->native(false)
                            ->hidden(fn () => auth()->user()->isTeacher()),
                            
                        Toggle::make('is_featured')
                            ->label('Featured Course')
                            ->default(false)
                            ->hidden(fn () => auth()->user()->isTeacher()),
                    ]),
                    
                // Hidden currency field
                TextInput::make('currency')
                    ->required()
                    ->default('BDT')
                    ->hidden(),
            ]);
    }
}
