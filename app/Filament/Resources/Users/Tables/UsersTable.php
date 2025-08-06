<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('roles.role_type')
                    ->label('Roles')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'admin' => 'danger',
                        'teacher' => 'success',
                        'student' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'admin' => 'Administrator',
                        'teacher' => 'Teacher',
                        'student' => 'Student',
                        default => ucfirst($state),
                    })
                    ->separator(','),
                    
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->email_verified_at !== null)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student Profile')
                    ->placeholder('No student profile')
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('teacher.full_name')
                    ->label('Teacher Profile')
                    ->placeholder('No teacher profile')
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->placeholder('All Users')
                    ->trueLabel('Verified Only')
                    ->falseLabel('Unverified Only')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('email_verified_at'),
                        false: fn ($query) => $query->whereNull('email_verified_at'),
                    ),
                    
                Tables\Filters\SelectFilter::make('roles')
                    ->label('User Role')
                    ->relationship('roles', 'role_type')
                    ->options([
                        'admin' => 'Administrator',
                        'teacher' => 'Teacher',
                        'student' => 'Student',
                    ]),
                    
                Tables\Filters\Filter::make('has_student_profile')
                    ->label('Has Student Profile')
                    ->query(fn ($query) => $query->whereHas('student')),
                    
                Tables\Filters\Filter::make('has_teacher_profile')
                    ->label('Has Teacher Profile')
                    ->query(fn ($query) => $query->whereHas('teacher')),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No users yet')
            ->emptyStateDescription('User accounts will appear here once created.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
