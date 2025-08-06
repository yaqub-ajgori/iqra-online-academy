<?php

namespace App\Filament\Resources\CourseEnrollments\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class CourseEnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->student->user->email ?? ''),
                    
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->course->category->name ?? ''),
                    
                Tables\Columns\TextColumn::make('payment.amount')
                    ->label('Paid Amount')
                    ->formatStateUsing(fn ($state, $record) => $state ? '৳' . number_format($state, 2) : ($record->course ? '৳' . number_format($record->course->effective_price, 2) : '-'))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('enrollment_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'paid' => 'success',
                        'free' => 'info', 
                        'scholarship' => 'warning',
                        'trial' => 'gray',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . '%')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    }),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('enrollment_type')
                    ->label('Enrollment Type')
                    ->options([
                        'paid' => 'Paid',
                        'free' => 'Free',
                        'scholarship' => 'Scholarship',
                        'trial' => 'Trial',
                    ]),
                    
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('enrolled_at', 'desc')
            ->striped()
            ->emptyStateHeading('No enrollments yet')
            ->emptyStateDescription('Course enrollments will appear here once students enroll.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}
