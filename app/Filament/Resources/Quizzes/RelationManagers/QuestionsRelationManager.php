<?php

namespace App\Filament\Resources\Quizzes\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    protected static ?string $recordTitleAttribute = 'question';
    protected static ?string $title = 'Quiz Questions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('question')
                    ->label('Question')
                    ->required()
                    ->rows(3)
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Select::make('type')
                    ->label('Question Type')
                    ->options([
                        'multiple_choice' => 'Multiple Choice',
                        'true_false' => 'True/False',
                        'short_answer' => 'Short Answer',
                    ])
                    ->default('multiple_choice')
                    ->required()
                    ->live(),

                // Multiple Choice Options
                Repeater::make('options')
                    ->label('Answer Options')
                    ->schema([
                        TextInput::make('option')
                            ->label('Option')
                            ->required(),
                    ])
                    ->visible(fn ($get) => $get('type') === 'multiple_choice')
                    ->addActionLabel('Add Option')
                    ->minItems(2)
                    ->maxItems(6)
                    ->columnSpanFull()
                    ->mutateDehydratedStateUsing(function ($state) {
                        return array_map(fn($item) => $item['option'], $state ?? []);
                    }),

                // Correct Answer for Multiple Choice
                Select::make('correct_answer')
                    ->label('Correct Answer')
                    ->options(function (callable $get) {
                        if ($get('type') === 'multiple_choice') {
                            $options = $get('options') ?? [];
                            $choices = [];
                            foreach ($options as $index => $option) {
                                $optionText = is_array($option) ? ($option['option'] ?? '') : $option;
                                if ($optionText) {
                                    $choices[$index] = chr(65 + $index) . '. ' . $optionText;
                                }
                            }
                            return $choices;
                        }
                        return [];
                    })
                    ->visible(fn ($get) => $get('type') === 'multiple_choice')
                    ->required(),

                // Correct Answer for True/False
                Select::make('correct_answer')
                    ->label('Correct Answer')
                    ->options([
                        'সত্য' => 'সত্য (True)',
                        'মিথ্যা' => 'মিথ্যা (False)',
                    ])
                    ->visible(fn ($get) => $get('type') === 'true_false')
                    ->required(),

                // Correct Answer for Short Answer
                TextInput::make('correct_answer')
                    ->label('Correct Answer')
                    ->visible(fn ($get) => $get('type') === 'short_answer')
                    ->required()
                    ->helperText('Case insensitive matching'),

                TextInput::make('points')
                    ->label('Points')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required(),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width(50),

                TextColumn::make('question')
                    ->label('Question')
                    ->limit(100)
                    ->wrap(),

                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'multiple_choice' => 'Multiple Choice',
                        'true_false' => 'True/False',
                        'short_answer' => 'Short Answer',
                        default => $state,
                    }),

                TextColumn::make('points')
                    ->label('Points')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order');
    }
}