<?php

namespace App\Filament\Resources\Certificates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('certificate_number')
                    ->label('Certificate #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('student_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->student->email ?? ''),

                TextColumn::make('course_title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('final_score')
                    ->label('Score')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? "{$state}%" : 'N/A')
                    ->color(fn ($state) => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger')),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'valid',
                        'warning' => 'expired',
                        'danger' => 'revoked',
                        'gray' => 'unverified',
                    ]),

                TextColumn::make('issue_date')
                    ->label('Issued')
                    ->date()
                    ->sortable(),

                TextColumn::make('verification_code')
                    ->label('Verification Code')
                    ->limit(12)
                    ->copyable()
                    ->toggleable(),

                IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'valid' => 'Valid',
                        'expired' => 'Expired',
                        'revoked' => 'Revoked',
                        'unverified' => 'Unverified'
                    ]),

                TernaryFilter::make('is_verified')
                    ->label('Verification Status'),

                TernaryFilter::make('is_revoked')
                    ->label('Revocation Status'),
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
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('No certificates yet')
            ->emptyStateDescription('Certificates will appear here when students complete courses.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}