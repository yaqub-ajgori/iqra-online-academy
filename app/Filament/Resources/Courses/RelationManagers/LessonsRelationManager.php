<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Courses\Schemas\LessonForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('module.title')
                    ->label('Module')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->width('80px'),
                    
                TextColumn::make('title')
                    ->label('Lesson Title')
                    ->searchable()
                    ->sortable(),
                    
                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'video',
                        'success' => 'text',
                        'warning' => 'pdf',
                    ]),
                    
                IconColumn::make('is_preview')
                    ->label('Preview')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Lesson')
                    ->icon('heroicon-o-plus')
                    ->slideOver()
                    ->modalWidth('7xl')
                    ->mutateFormDataUsing(function (array $data): array {
                        try {
                            // Automatically set the course_id from the parent course
                            $course = $this->getOwnerRecord();
                            if ($course) {
                                $data['course_id'] = $course->id;
                            }
                            
                            return $data;
                        } catch (\Exception $e) {
                            throw new \Exception('Error creating lesson: ' . $e->getMessage());
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('7xl')
                    ->mutateFormDataUsing(function (array $data): array {
                        try {
                            // Ensure course_id is maintained on edit
                            $course = $this->getOwnerRecord();
                            if ($course) {
                                $data['course_id'] = $course->id;
                            }
                            
                            return $data;
                        } catch (\Exception $e) {
                            throw new \Exception('Error updating lesson: ' . $e->getMessage());
                        }
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
