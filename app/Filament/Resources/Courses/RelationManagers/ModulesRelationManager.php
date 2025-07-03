<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Module Title')
                    ->required()
                    ->maxLength(255)
                    ->default(''),
                    
                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(fn () => max(1, ($this->getOwnerRecord()->modules()->count() ?? 0) + 1))
                    ->minValue(1)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->width('80px'),
                    
                TextColumn::make('title')
                    ->label('Module Title')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('lessons_count')
                    ->label('Lessons')
                    ->counts('lessons')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                    
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Module')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Automatically set the course_id from the parent course
                        $data['course_id'] = $this->getOwnerRecord()->id;
                        
                        // Ensure required fields have defaults
                        $data['title'] = $data['title'] ?? '';
                        $data['sort_order'] = $data['sort_order'] ?? 1;
                        
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Ensure course_id is maintained on edit
                        $data['course_id'] = $this->getOwnerRecord()->id;
                        
                        // Ensure required fields have defaults
                        $data['title'] = $data['title'] ?? '';
                        $data['sort_order'] = $data['sort_order'] ?? 1;
                        
                        return $data;
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
