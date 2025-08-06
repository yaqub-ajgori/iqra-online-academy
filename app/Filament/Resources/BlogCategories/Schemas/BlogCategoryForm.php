<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Category Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),
                    
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->readOnly(),
                    
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->maxLength(500),
                    
                Select::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->placeholder('Select parent category (optional)'),
                    
                TextInput::make('icon')
                    ->label('Icon Class')
                    ->maxLength(100)
                    ->placeholder('e.g., heroicon-o-academic-cap'),
                    
                TextInput::make('color')
                    ->label('Color')
                    ->maxLength(7)
                    ->placeholder('#007bff'),
                    
                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                    
                Toggle::make('is_active')
                    ->label('Active Category')
                    ->default(true),
            ])
            ->columns(2);
    }
}
