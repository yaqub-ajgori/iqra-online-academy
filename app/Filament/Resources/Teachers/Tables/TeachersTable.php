<?php

namespace App\Filament\Resources\Teachers\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class TeachersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('speciality')
                    ->label('Specialization')
                    ->searchable()
                    ->placeholder('Not specified'),
                    
                Tables\Columns\TextColumn::make('experience')
                    ->label('Exp')
                    ->suffix(' yrs')
                    ->placeholder('N/A'),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                    
                Tables\Columns\ImageColumn::make('profile_picture')
                    ->label('Avatar')
                    ->disk('public')
                    ->circular()
                    ->size(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Account')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Teachers')
                    ->placeholder('All Teachers')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
                    
                Tables\Filters\SelectFilter::make('speciality')
                    ->label('Specialization')
                    ->options(function () {
                        return \App\Models\Teacher::query()
                            ->whereNotNull('speciality')
                            ->pluck('speciality', 'speciality')
                            ->unique()
                            ->toArray();
                    }),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make()
                    ->before(function (\Filament\Actions\DeleteAction $action, $record) {
                        // Check if teacher has courses
                        $coursesCount = $record->courses()->count();
                        if ($coursesCount > 0) {
                            Notification::make()
                                ->title('Cannot Delete Teacher')
                                ->body("This teacher has {$coursesCount} course(s) assigned. Please reassign the courses to another teacher first.")
                                ->danger()
                                ->persistent()
                                ->send();
                            
                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->before(function (\Filament\Actions\DeleteBulkAction $action, $records) {
                            $blockedTeachers = [];
                            
                            foreach ($records as $record) {
                                $coursesCount = $record->courses()->count();
                                if ($coursesCount > 0) {
                                    $blockedTeachers[] = "{$record->full_name} ({$coursesCount} courses)";
                                }
                            }
                            
                            if (!empty($blockedTeachers)) {
                                Notification::make()
                                    ->title('Cannot Delete Teachers')
                                    ->body('The following teachers have courses assigned: ' . implode(', ', $blockedTeachers) . '. Please reassign the courses first.')
                                    ->danger()
                                    ->persistent()
                                    ->send();
                                
                                $action->cancel();
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No teachers yet')
            ->emptyStateDescription('Teacher profiles will appear here once created.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}
