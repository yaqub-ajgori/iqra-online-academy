<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Post Content')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                        // Auto-generate slug if it's empty or matches the old slug
                                        if (empty($get('slug')) || ($get('slug') ?? '') === Str::slug($old)) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Auto-generated from title. Will be used in URL.'),
                                Textarea::make('excerpt')
                                    ->label('Excerpt')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->helperText('A short summary of the post'),
                                RichEditor::make('content')
                                    ->label('Content')
                                    ->required()
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

                        Section::make('Featured Image')
                            ->schema([
                                FileUpload::make('featured_image')
                                    ->label('Featured Image')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('blog-images')
                                    ->visibility('public')
                                    ->helperText('Recommended size: 1200x630px'),
                            ]),

                        Section::make('SEO Settings')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->helperText('Leave empty to use post title'),
                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->helperText('Leave empty to use excerpt'),
                                Textarea::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->rows(2)
                                    ->helperText('Comma-separated keywords'),
                            ])
                            ->collapsible()
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Post Details')
                            ->schema([
                                Select::make('author_id')
                                    ->label('Author')
                                    ->relationship('author', 'name')
                                    ->default(auth()->id())
                                    ->required()
                                    ->searchable(),
                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Select::make('tags')
                                    ->label('Tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Section::make('Publishing')
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'review' => 'Review',
                                        'published' => 'Published',
                                        'archived' => 'Archived'
                                    ])
                                    ->default('draft')
                                    ->required(),
                                DateTimePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->default(now())
                                    ->timezone('Asia/Dhaka'),
                                Toggle::make('is_featured')
                                    ->label('Featured Post')
                                    ->helperText('Show on homepage'),
                                Toggle::make('is_sticky')
                                    ->label('Sticky Post')
                                    ->helperText('Pin to top of blog'),
                            ]),

                        Section::make('Statistics')
                            ->schema([
                                TextInput::make('reading_time')
                                    ->label('Reading Time (minutes)')
                                    ->numeric()
                                    ->disabled()
                                    ->helperText('Auto-calculated'),
                                TextInput::make('views_count')
                                    ->label('Views')
                                    ->numeric()
                                    ->disabled()
                                    ->default(0),
                            ])
                            ->collapsible()
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}