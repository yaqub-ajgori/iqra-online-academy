<?php

namespace App\Filament\Resources\BlogPosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlogPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?name=Blog+Post'))
                    ->toggleable(),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'কুরআন শিক্ষা' => 'primary',
                        'হাদিস' => 'success',
                        'ইসলামিক জীবনযাত্রা' => 'warning',
                        'ইসলামের ইতিহাস' => 'info',
                        'ফিকহ ও মাসআলা' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'review' => 'warning',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor(Color::Yellow)
                    ->falseColor(Color::Gray)
                    ->toggleable(),
                IconColumn::make('is_sticky')
                    ->label('Sticky')
                    ->boolean()
                    ->trueIcon('heroicon-o-bookmark')
                    ->falseIcon('heroicon-o-bookmark')
                    ->trueColor(Color::Blue)
                    ->falseColor(Color::Gray)
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('reading_time')
                    ->label('Reading')
                    ->suffix(' min')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->separator(',')
                    ->limit(3)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Review',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('is_featured')
                    ->label('Featured only')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
                Filter::make('is_sticky')
                    ->label('Sticky only')
                    ->query(fn (Builder $query): Builder => $query->where('is_sticky', true)),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('60s');
    }
}