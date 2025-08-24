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
    protected static string $relationship = 'directLessons';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Courses\Schemas\LessonForm::configure($schema);
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure course_id is set when creating lessons
        $courseId = $this->getOwnerRecord()->getKey();
        if (!$courseId) {
            throw new \Exception('Course ID is required to create a lesson.');
        }
        $data['course_id'] = $courseId;
        
        // Validate that the selected module belongs to this course
        if (isset($data['module_id'])) {
            $moduleExists = \App\Models\CourseModule::where('id', $data['module_id'])
                ->where('course_id', $courseId)
                ->exists();
            
            if (!$moduleExists) {
                throw new \Exception('Selected module does not belong to this course.');
            }
        }
        
        return $data;
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure course_id is set when updating lessons
        $courseId = $this->getOwnerRecord()->getKey();
        if (!$courseId) {
            throw new \Exception('Course ID is required to update a lesson.');
        }
        $data['course_id'] = $courseId;
        
        // Validate that the selected module belongs to this course
        if (isset($data['module_id'])) {
            $moduleExists = \App\Models\CourseModule::where('id', $data['module_id'])
                ->where('course_id', $courseId)
                ->exists();
            
            if (!$moduleExists) {
                throw new \Exception('Selected module does not belong to this course.');
            }
        }
        
        return $data;
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

                TextColumn::make('duration_display')
                    ->label('Duration')
                    ->sortable(),
                    
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Lesson')
                    ->icon('heroicon-o-plus')
                    ->slideOver()
                    ->modalWidth('7xl'),
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('7xl'),
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
