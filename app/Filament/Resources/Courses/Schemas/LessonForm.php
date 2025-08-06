<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Lesson Content')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Lesson Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Textarea::make('description')
                                    ->label('Short Description')
                                    ->rows(2)
                                    ->columnSpanFull()
                                    ->helperText('Brief description of the lesson'),

                                Select::make('lesson_type')
                                    ->label('Lesson Type')
                                    ->options([
                                        'video' => 'Video',
                                        'text' => 'Text/Article',
                                        'pdf' => 'PDF Document',
                                        'quiz' => 'Quiz',
                                        'assignment' => 'Assignment',
                                        'live' => 'Live Session',
                                        'mixed' => 'Mixed Content'
                                    ])
                                    ->default('video')
                                    ->required()
                                    ->live()
                                    ->columnSpan(1),

                                TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                RichEditor::make('content')
                                    ->label('Lesson Content')
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ]),
                            ])
                            ->columns(2),

                        Section::make('Video Content')
                            ->schema([
                                TextInput::make('video_url')
                                    ->label('Video URL')
                                    ->url()
                                    ->maxLength(500)
                                    ->helperText('YouTube, Vimeo, or direct video URL')
                                    ->columnSpan(1),

                                TextInput::make('video_duration')
                                    ->label('Video Duration (seconds)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('seconds')
                                    ->columnSpan(1),
                            ])
                            ->columns(2)
                            ->visible(fn ($get) => in_array($get('lesson_type'), ['video', 'mixed'])),

                        Section::make('File Content')
                            ->schema([
                                FileUpload::make('primary_file_path')
                                    ->label('Primary File')
                                    ->disk('public')
                                    ->directory('course-content')
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                    ->maxSize(50 * 1024) // 50MB
                                    ->helperText('Upload PDF, DOC, or DOCX files (max 50MB)')
                                    ->columnSpanFull(),

                                Repeater::make('attachments')
                                    ->label('Additional Attachments')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('File Name')
                                            ->required()
                                            ->columnSpan(1),

                                        FileUpload::make('path')
                                            ->label('File')
                                            ->required()
                                            ->disk('public')
                                            ->directory('course-attachments')
                                            ->maxSize(25 * 1024) // 25MB
                                            ->columnSpan(1),

                                        Select::make('type')
                                            ->label('File Type')
                                            ->options([
                                                'pdf' => 'PDF Document',
                                                'doc' => 'Word Document',
                                                'ppt' => 'Presentation',
                                                'image' => 'Image',
                                                'other' => 'Other'
                                            ])
                                            ->required()
                                            ->columnSpan(1),
                                    ])
                                    ->columns(3)
                                    ->addActionLabel('Add Attachment')
                                    ->collapsible()
                                    ->columnSpanFull(),

                                Repeater::make('resources')
                                    ->label('External Resources')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Resource Title')
                                            ->required()
                                            ->columnSpan(1),

                                        TextInput::make('url')
                                            ->label('URL')
                                            ->url()
                                            ->required()
                                            ->columnSpan(1),

                                        Select::make('type')
                                            ->label('Resource Type')
                                            ->options([
                                                'article' => 'Article',
                                                'video' => 'Video',
                                                'book' => 'Book',
                                                'website' => 'Website',
                                                'tool' => 'Tool',
                                                'other' => 'Other'
                                            ])
                                            ->required()
                                            ->columnSpan(1),
                                    ])
                                    ->columns(3)
                                    ->addActionLabel('Add Resource')
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ])
                            ->visible(fn ($get) => in_array($get('lesson_type'), ['pdf', 'text', 'mixed'])),

                        Section::make('Lesson Media')
                            ->schema([
                                FileUpload::make('thumbnail')
                                    ->label('Lesson Thumbnail')
                                    ->image()
                                    ->disk('public')
                                    ->directory('lesson-thumbnails')
                                    ->helperText('Optional thumbnail for the lesson'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Lesson Settings')
                            ->schema([
                                Toggle::make('is_preview')
                                    ->label('Preview Lesson')
                                    ->helperText('Can be viewed without enrollment'),

                                Toggle::make('is_mandatory')
                                    ->label('Mandatory')
                                    ->default(true)
                                    ->helperText('Required for course completion'),

                                Toggle::make('requires_completion')
                                    ->label('Requires Completion')
                                    ->helperText('Student must mark as completed'),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),

                        Section::make('Completion Requirements')
                            ->schema([
                                TextInput::make('minimum_time_seconds')
                                    ->label('Minimum Time (seconds)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('seconds')
                                    ->helperText('Minimum time student must spend on lesson'),
                            ])
                            ->visible(fn ($get) => $get('requires_completion')),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}