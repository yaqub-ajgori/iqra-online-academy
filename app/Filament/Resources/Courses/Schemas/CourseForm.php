<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                // Course Title and Slug (Full Width)
                TextInput::make('title')
                    ->label('Course Title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                    
                TextInput::make('slug')
                    ->label('URL Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique()
                    ->columnSpanFull(),
                    
                // Course Description (Full Width)
                RichEditor::make('full_description')
                    ->label('Course Description')
                    ->columnSpanFull(),
                    
                // Category and Level (Half width each)
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpan(2),
                    
                Select::make('level')
                    ->label('Difficulty Level')
                    ->options([
                        'beginner' => 'Beginner', 
                        'intermediate' => 'Intermediate', 
                        'advanced' => 'Advanced'
                    ])
                    ->required()
                    ->columnSpan(2),
                    
                // Instructor (Full Width)
                Select::make('instructor_id')
                    ->label('Instructor')
                    ->relationship('instructor', 'full_name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                    
                // Thumbnail Upload (Full Width)
                FileUpload::make('thumbnail_image')
                    ->label('Course Thumbnail')
                    ->image()
                    ->directory('courses/thumbnails')
                    ->disk('public')
                    ->columnSpanFull(),
                    
                // Pricing Section
                TextInput::make('price')
                    ->label('Course Price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('৳')
                    ->minValue(0)
                    ->columnSpan(1),
                    
                TextInput::make('discount_price')
                    ->label('Discount Price')
                    ->numeric()
                    ->prefix('৳')
                    ->minValue(0)
                    ->columnSpan(1),
                    
                DateTimePicker::make('discount_expires_at')
                    ->label('Discount Expires')
                    ->after('today')
                    ->columnSpan(2),
                    
                // Duration and Status
                TextInput::make('duration_in_minutes')
                    ->label('Duration (Minutes)')
                    ->numeric()
                    ->minValue(0)
                    ->suffix('minutes')
                    ->columnSpan(1),
                    
                Select::make('status')
                    ->label('Course Status')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Under Review',
                        'published' => 'Published',
                        'archived' => 'Archived'
                    ])
                    ->required()
                    ->default('draft')
                    ->columnSpan(1),
                    
                DateTimePicker::make('published_at')
                    ->label('Publish Date & Time')
                    ->default(now())
                    ->columnSpan(2),
                    
                // Boolean Fields in One Row
                Toggle::make('is_free')
                    ->label('Free Course')
                    ->default(false)
                    ->columnSpan(1)
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $state ? $set('price', 0) : null
                    ),
                    
                Toggle::make('is_featured')
                    ->label('Featured Course')
                    ->default(false)
                    ->columnSpan(1),
                    
                // Hidden currency field
                TextInput::make('currency')
                    ->required()
                    ->default('BDT')
                    ->hidden(),
            ]);
    }
}
