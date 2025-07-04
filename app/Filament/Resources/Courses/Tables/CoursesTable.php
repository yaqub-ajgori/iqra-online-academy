<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_image')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->circular()
                    ->size(50),
                    
                TextColumn::make('title')
                    ->label('Course Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->category->name ?? ''),
                    
                TextColumn::make('instructor.full_name')
                    ->label('Instructor')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('price')
                    ->label('Price')
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        if ($record->is_free) {
                            return 'Free';
                        }
                        if ($record->discount_price && $record->discount_expires_at && $record->discount_expires_at->isFuture()) {
                            return '৳' . number_format($record->discount_price);
                        }
                        return '৳' . number_format($record->price);
                    }),
                    
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'review', 
                        'success' => 'published',
                        'danger' => 'archived',
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Under Review', 
                        'published' => 'Published',
                        'archived' => 'Archived'
                    ]),
                    
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil'),
                    
                DeleteAction::make()
                    ->icon('heroicon-o-trash'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('No courses yet')
            ->emptyStateDescription('Create your first course to get started.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}
