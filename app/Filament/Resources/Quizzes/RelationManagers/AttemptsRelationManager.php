<?php

namespace App\Filament\Resources\Quizzes\RelationManagers;

use Filament\Schemas\Components;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'attempts';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make('Attempt Details')
                    ->schema([
                        Components\Select::make('student_id')
                            ->label('Student')
                            ->relationship('student', 'full_name')
                            ->disabled()
                            ->columnSpan(2),

                        Components\DateTimePicker::make('started_at')
                            ->label('Started At')
                            ->disabled()
                            ->columnSpan(1),

                        Components\DateTimePicker::make('completed_at')
                            ->label('Completed At')
                            ->disabled()
                            ->columnSpan(1),

                        Components\TextInput::make('score')
                            ->label('Score (%)')
                            ->disabled()
                            ->suffix('%')
                            ->columnSpan(1),

                        Components\TextInput::make('correct_answers')
                            ->label('Correct Answers')
                            ->disabled()
                            ->columnSpan(1),

                        Components\TextInput::make('total_questions')
                            ->label('Total Questions')
                            ->disabled()
                            ->columnSpan(1),

                        Components\TextInput::make('formatted_time_taken')
                            ->label('Time Taken')
                            ->disabled()
                            ->columnSpan(1),

                        Components\Toggle::make('is_passed')
                            ->label('Passed')
                            ->disabled()
                            ->columnSpan(1),

                        Components\Textarea::make('feedback')
                            ->label('Feedback')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(4),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('started_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('score')
                    ->label('Score')
                    ->suffix('%')
                    ->numeric(decimalPlaces: 1)
                    ->color(fn ($state) => match(true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('grade')
                    ->label('Grade')
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        str_starts_with($state, 'A') => 'success',
                        str_starts_with($state, 'B') => 'primary',
                        str_starts_with($state, 'C') => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\TextColumn::make('correct_answers')
                    ->label('Correct')
                    ->formatStateUsing(fn ($state, Model $record) => "{$state}/{$record->total_questions}")
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('formatted_time_taken')
                    ->label('Time Taken')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_passed')
                    ->label('Passed')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Completed' : 'In Progress')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_passed')
                    ->label('Passed')
                    ->placeholder('All Attempts')
                    ->trueLabel('Passed Only')
                    ->falseLabel('Failed Only'),

                Tables\Filters\Filter::make('completed')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('completed_at'))
                    ->label('Completed Only'),

                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Components\DatePicker::make('from')
                            ->label('From Date'),
                        Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('started_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('started_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateDescription('No quiz attempts yet');
    }
}