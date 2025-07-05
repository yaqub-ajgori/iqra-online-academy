<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
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
                    
                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state) => '৳' . number_format($state, 2))
                    ->sortable(),
                    
                BadgeColumn::make('payment_method')
                    ->label('Method')
                    ->colors([
                        'success' => 'bkash',
                        'info' => 'nagad',
                        'warning' => 'rocket',
                        'primary' => 'bank_transfer',
                        'gray' => 'card',
                    ]),
                    
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'gray' => 'cancelled',
                        'purple' => 'refunded',
                    ]),
                    
                TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ]),
                    
                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'bkash' => 'Bkash',
                        'nagad' => 'Nagad',
                        'rocket' => 'Rocket',
                        'bank_transfer' => 'Bank Transfer',
                        'card' => 'Card',
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
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('No payments yet')
            ->emptyStateDescription('Payments will appear here once students make course purchases.')
            ->emptyStateIcon('heroicon-o-credit-card');
    }
}
