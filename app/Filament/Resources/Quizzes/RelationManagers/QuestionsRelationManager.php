<?php

namespace App\Filament\Resources\Quizzes\RelationManagers;

use Filament\Schemas\Components;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make()
                    ->schema([
                        Components\Textarea::make('question')
                            ->label('Question')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Components\Select::make('type')
                            ->label('Question Type')
                            ->options([
                                'multiple_choice' => 'Multiple Choice',
                                'true_false' => 'True/False',
                                'short_answer' => 'Short Answer',
                                'essay' => 'Essay',
                            ])
                            ->required()
                            ->columnSpan(1),

                        Components\TextInput::make('points')
                            ->label('Points')
                            ->numeric()
                            ->minValue(0.5)
                            ->maxValue(100)
                            ->step(0.5)
                            ->default(1)
                            ->required()
                            ->columnSpan(1),

                        Components\Repeater::make('options')
                            ->label('Answer Options')
                            ->schema([
                                Components\TextInput::make('option')
                                    ->label('Option')
                                    ->required(),
                            ])
                            ->minItems(2)
                            ->maxItems(6)
                            ->defaultItems(4)
                            ->columnSpanFull(),

                        Components\TagsInput::make('correct_answers')
                            ->label('Correct Answer(s)')
                            ->placeholder('Enter correct answer(s)')
                            ->required()
                            ->columnSpanFull(),

                        Components\Textarea::make('explanation')
                            ->label('Explanation')
                            ->helperText('Explain why this is the correct answer')
                            ->rows(2)
                            ->columnSpanFull(),

                        Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),

                        Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),
                
                Tables\Columns\TextColumn::make('question')
                    ->label('Question')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'multiple_choice',
                        'success' => 'true_false',
                        'warning' => 'short_answer',
                        'danger' => 'essay',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'multiple_choice' => 'Multiple Choice',
                        'true_false' => 'True/False',
                        'short_answer' => 'Short Answer',
                        'essay' => 'Essay',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('points')
                    ->label('Points')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'multiple_choice' => 'Multiple Choice',
                        'true_false' => 'True/False',
                        'short_answer' => 'Short Answer',
                        'essay' => 'Essay',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}