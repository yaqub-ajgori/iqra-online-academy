<?php

namespace App\Filament\Resources\Quizzes\RelationManagers;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components;
use Filament\Schemas\Components\Grid;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                        Textarea::make('question')
                            ->label('Question')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Grid::make(4)
                            ->schema([
                                Select::make('type')
                                    ->label('Question Type')
                                    ->options([
                                        'multiple_choice' => 'Multiple Choice',
                                        'true_false' => 'True/False',
                                        'short_answer' => 'Short Answer',
                                        'essay' => 'Essay',
                                    ])
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('points')
                                    ->label('Points')
                                    ->numeric()
                                    ->minValue(0.5)
                                    ->maxValue(100)
                                    ->step(0.5)
                                    ->default(1)
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),
                            ]),

                        Repeater::make('options')
                            ->label('Answer Options')
                            ->simple(
                                TextInput::make('option')
                                    ->label('Option')
                                    ->required()
                            )
                            ->minItems(2)
                            ->maxItems(6)
                            ->defaultItems(4)
                            ->visible(fn ($get) => $get('type') === 'multiple_choice')
                            ->columnSpanFull()
                            ->columns(2),

                        TagsInput::make('correct_answers')
                            ->label('Correct Answer(s)')
                            ->placeholder('Enter correct answer(s)')
                            ->required()
                            ->helperText(fn ($get) => match($get('type')) {
                                'multiple_choice' => 'Enter the index of correct option (0 for first, 1 for second, etc.)',
                                'true_false' => 'Enter "সত্য" or "মিথ্যা"',
                                'short_answer' => 'Enter all acceptable answers',
                                default => 'Enter the correct answer(s)'
                            })
                            ->columnSpanFull(),

                        Textarea::make('explanation')
                            ->label('Explanation')
                            ->helperText('Explain why this is the correct answer')
                            ->rows(3)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
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
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('6xl'),
            ])
            ->actions([
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('6xl'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('6xl'),
            ]);
    }
}