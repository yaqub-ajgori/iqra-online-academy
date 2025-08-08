<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
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

                                Select::make('type')
                                    ->label('Lesson Type')
                                    ->options([
                                        'text' => 'Text/Article',
                                        'video' => 'Video (YouTube/Vimeo)',
                                        'pdf' => 'PDF Document'
                                    ])
                                    ->required()
                                    ->default('text')
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
                                    ->visible(fn ($get) => in_array($get('type'), ['text', null]))
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
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
                                    ->helperText('YouTube or Vimeo video URL')
                                    ->placeholder('https://youtube.com/watch?v=... or https://vimeo.com/...')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->visible(fn ($get) => $get('type') === 'video'),

                        Section::make('PDF File')
                            ->schema([
                                FileUpload::make('file_path')
                                    ->label('PDF File')
                                    ->disk('public')
                                    ->directory('course-content')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(50 * 1024) // 50MB
                                    ->helperText('Upload PDF file (max 50MB)')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->visible(fn ($get) => $get('type') === 'pdf'),
                    ])
                    ->columnSpan(['lg' => 2, 'xl' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Lesson Settings')
                            ->schema([
                                Toggle::make('is_preview')
                                    ->label('Preview Lesson')
                                    ->helperText('Can be viewed without enrollment'),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1, 'xl' => 1]),
            ])
            ->columns([
                'sm' => 1,
                'md' => 2,
                'lg' => 3,
            ]);
    }
}