<?php

namespace App\Filament\Resources\Certificates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
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
                    ->description(fn ($record) => $record->student->email ?? 'N/A'),

                TextColumn::make('course_title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->limit(30),


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

                TextColumn::make('completion_date')
                    ->label('Completed')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('verification_code')
                    ->label('Verification Code')
                    ->limit(12)
                    ->copyable()
                    ->toggleable(),

                IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_revoked')
                    ->label('Revoked')
                    ->boolean()
                    ->sortable()
                    ->color(fn ($state) => $state ? 'danger' : 'success'),

                TextColumn::make('created_at')
                    ->label('System Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Action::make('revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Revoke Certificate')
                    ->modalDescription('Are you sure you want to revoke this certificate?')
                    ->action(function ($record) {
                        $record->update([
                            'is_revoked' => true,
                            'revoked_at' => now(),
                            'revoked_reason' => 'Revoked by administrator'
                        ]);
                    })
                    ->visible(fn ($record) => !$record->is_revoked),

                Action::make('undo_revoke')
                    ->label('Undo Revoke')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Restore Certificate')
                    ->modalDescription('Are you sure you want to restore this certificate?')
                    ->action(function ($record) {
                        $record->update([
                            'is_revoked' => false,
                            'revoked_at' => null,
                            'revoked_reason' => null
                        ]);
                    })
                    ->visible(fn ($record) => $record->is_revoked),

                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->url(fn ($record) => route('certificates.download', $record))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->certificate_path),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('No certificates yet')
            ->emptyStateDescription('Certificates will appear here when students complete courses.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}