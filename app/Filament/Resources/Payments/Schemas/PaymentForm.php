<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                // Student and Course Selection (Full Width)
                Select::make('student_id')
                    ->label('Student')
                    ->relationship('student', 'name', function ($query) {
                        return $query->with('user');
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name ?? $record->name)
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpan(2),
                    
                Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpan(2),
                    
                // Payment Details
                TextInput::make('amount')
                    ->label('Payment Amount')
                    ->required()
                    ->numeric()
                    ->prefix('৳')
                    ->minValue(0)
                    ->columnSpan(1),
                    
                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'bkash' => 'Bkash',
                        'nagad' => 'Nagad',
                        'rocket' => 'Rocket',
                        'bank_transfer' => 'Bank Transfer',
                        'card' => 'Card',
                    ])
                    ->required()
                    ->columnSpan(1),
                    
                TextInput::make('transaction_id')
                    ->label('Transaction ID')
                    ->columnSpan(2),
                    
                Select::make('status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->default('pending')
                    ->required()
                    ->columnSpanFull(),
                    
                // Mobile Banking Details (conditionally visible)
                TextInput::make('sender_number')
                    ->label('Sender Number')
                    ->tel()
                    ->columnSpan(2),
                    
                TextInput::make('receiver_number')
                    ->label('Receiver Number')
                    ->tel()
                    ->columnSpan(2),
                    
                // Bank Details (conditionally visible)
                TextInput::make('bank_name')
                    ->label('Bank Name')
                    ->columnSpan(2),
                    
                TextInput::make('account_number')
                    ->label('Account Number')
                    ->columnSpan(1),
                    
                TextInput::make('branch_name')
                    ->label('Branch Name')
                    ->columnSpan(1),
                    
                // Hidden currency field
                TextInput::make('currency')
                    ->required()
                    ->default('BDT')
                    ->hidden(),
            ]);
    }
}
