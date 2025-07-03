<?php

namespace App\Filament\Resources\CourseCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                TextInput::make('name')
                    ->label('Category Name')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->columnSpan(2),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->columnSpan(1),
            ]);
    }
}
