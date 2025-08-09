<?php

namespace App\Filament\Resources\Quizzes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuizzesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Quiz Title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                    
                TextColumn::make('course.title')
                    ->label('Course')
                    ->sortable()
                    ->searchable()
                    ->placeholder('No course assigned')
                    ->wrap(),
                    
                TextColumn::make('lesson.title')
                    ->label('Lesson')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Course level quiz')
                    ->wrap(),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'secondary',
                    }),
                    
                TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions')
                    ->sortable(),
                    
                TextColumn::make('passing_score')
                    ->label('Pass %')
                    ->suffix('%')
                    ->sortable(),
                    
                TextColumn::make('time_limit_minutes')
                    ->label('Time Limit')
                    ->suffix(' min')
                    ->placeholder('No limit')
                    ->sortable(),
                    
                TextColumn::make('max_attempts')
                    ->label('Max Attempts')
                    ->sortable(),
                    
                TextColumn::make('attempts_count')
                    ->label('Attempts')
                    ->counts('attempts')
                    ->sortable(),
                    
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                    
                SelectFilter::make('course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
