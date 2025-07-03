<?php

namespace App\Filament\Resources\CourseEnrollments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CourseEnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->student->user->email ?? ''),
                    
                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->course->category->name ?? ''),
                    
                BadgeColumn::make('enrollment_type')
                    ->label('Type')
                    ->colors([
                        'success' => 'paid',
                        'info' => 'free',
                        'warning' => 'scholarship',
                        'gray' => 'trial',
                    ]),
                    
                TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . '%')
                    ->sortable(),
                    
                BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'purple' => 'refunded',
                    ]),
                    
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                SelectFilter::make('enrollment_type')
                    ->label('Enrollment Type')
                    ->options([
                        'paid' => 'Paid',
                        'free' => 'Free',
                        'scholarship' => 'Scholarship',
                        'trial' => 'Trial',
                    ]),
                    
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
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
            ->defaultSort('enrolled_at', 'desc')
            ->striped()
            ->emptyStateHeading('No enrollments yet')
            ->emptyStateDescription('Course enrollments will appear here once students enroll.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}
