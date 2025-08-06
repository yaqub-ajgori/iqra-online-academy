<?php

namespace App\Filament\Resources\Quizzes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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
                    ->weight('bold')
                    ->description(fn ($record) => $record->course->title ?? ''),

                TextColumn::make('course.title')
                    ->label('Course')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'info' => 'quiz',
                        'warning' => 'exam',
                        'success' => 'assessment',
                    ]),

                TextColumn::make('total_questions')
                    ->label('Questions')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->questions()->count())
                    ->suffix(' questions'),

                TextColumn::make('time_limit_minutes')
                    ->label('Time Limit')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? "{$state} min" : 'No limit'),

                TextColumn::make('passing_score')
                    ->label('Passing Score')
                    ->sortable()
                    ->suffix('%'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'quiz' => 'Quiz',
                        'exam' => 'Exam',
                        'assessment' => 'Assessment'
                    ]),

                SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Active Status'),
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
            ->emptyStateHeading('No quizzes yet')
            ->emptyStateDescription('Create your first quiz to get started.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}