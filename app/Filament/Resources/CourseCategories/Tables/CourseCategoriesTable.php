<?php

namespace App\Filament\Resources\CourseCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\QueryException;

class CourseCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, $record) {
                        // Check if category has courses
                        $coursesCount = $record->courses()->count();
                        if ($coursesCount > 0) {
                            Notification::make()
                                ->title('Cannot Delete Category')
                                ->body("This category has {$coursesCount} course(s) assigned to it. Please reassign or delete the courses first.")
                                ->danger()
                                ->persistent()
                                ->send();
                            
                            $action->cancel();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, $records) {
                            $blockedCategories = [];
                            
                            foreach ($records as $record) {
                                $coursesCount = $record->courses()->count();
                                if ($coursesCount > 0) {
                                    $blockedCategories[] = "{$record->name} ({$coursesCount} courses)";
                                }
                            }
                            
                            if (!empty($blockedCategories)) {
                                Notification::make()
                                    ->title('Cannot Delete Categories')
                                    ->body('The following categories have courses assigned: ' . implode(', ', $blockedCategories) . '. Please reassign or delete the courses first.')
                                    ->danger()
                                    ->persistent()
                                    ->send();
                                
                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}
