<?php

namespace App\Filament\Resources\Certificates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;


class CertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('certificate_number')
                    ->label('Certificate Number')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                    
                TextColumn::make('student_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('course_title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                    
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'issued',
                        'danger' => 'revoked',
                        'warning' => 'expired',
                    ]),
                    
                TextColumn::make('final_score')
                    ->label('Final Score')
                    ->suffix('%')
                    ->numeric()
                    ->sortable(),
                    
                TextColumn::make('issued_at')
                    ->label('Issued Date')
                    ->dateTime('M j, Y')
                    ->sortable(),
                    
                TextColumn::make('verification_code')
                    ->label('Verification Code')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('instructor_name')
                    ->label('Instructor')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('completed_at')
                    ->label('Completed Date')
                    ->dateTime('M j, Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('total_lessons')
                    ->label('Lessons')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'issued' => 'Issued',
                        'revoked' => 'Revoked', 
                        'expired' => 'Expired',
                    ]),
                    
                SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->searchable(),
                    
                SelectFilter::make('student_id')
                    ->label('Student')
                    ->relationship('student.user', 'name')
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Revoke Certificate')
                    ->modalDescription('Are you sure you want to revoke this certificate? This action cannot be undone.')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('revocation_reason')
                            ->label('Reason for Revocation')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (array $data, $record): void {
                        $record->revoke($data['revocation_reason'], Auth::id());
                        Notification::make()
                            ->title('Certificate Revoked')
                            ->body('The certificate has been successfully revoked.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status === 'issued'),

                Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Restore Certificate')
                    ->modalDescription('Are you sure you want to restore this certificate? This will mark it as issued again.')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'issued',
                            'revocation_reason' => null,
                            'revoked_at' => null,
                            'revoked_by' => null,
                        ]);
                        Notification::make()
                            ->title('Certificate Restored')
                            ->body('The certificate has been re-enabled and is now valid.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status === 'revoked'),

                Action::make('view_certificate')
                    ->label('Preview Certificate')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn ($record) => route('certificates.preview', $record->course->slug))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->status === 'issued'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
