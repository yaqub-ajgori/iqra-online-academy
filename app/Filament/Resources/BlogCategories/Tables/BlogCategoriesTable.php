<?php

namespace App\Filament\Resources\BlogCategories\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class BlogCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->description ? \Illuminate\Support\Str::limit($record->description, 50) : null),
                    
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->sortable()
                    ->placeholder('Root Category')
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('children_count')
                    ->label('Subcategories')
                    ->counts('children')
                    ->sortable()
                    ->alignCenter(),
                    
                Tables\Columns\TextColumn::make('posts_count')
                    ->label('Posts')
                    ->counts('posts')
                    ->sortable()
                    ->alignCenter(),
                    
                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->placeholder('No icon')
                    ->toggleable(),
                    
                Tables\Columns\ColorColumn::make('color')
                    ->label('Color')
                    ->placeholder('No color')
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Categories')
                    ->placeholder('All Categories')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
                    
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name')
                    ->placeholder('All Categories'),
                    
                Tables\Filters\Filter::make('has_children')
                    ->label('Has Subcategories')
                    ->query(fn ($query) => $query->has('children')),
                    
                Tables\Filters\Filter::make('has_posts')
                    ->label('Has Posts')
                    ->query(fn ($query) => $query->has('posts')),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->emptyStateHeading('No blog categories yet')
            ->emptyStateDescription('Create your first blog category to organize your posts.')
            ->emptyStateIcon('heroicon-o-folder');
    }
}
