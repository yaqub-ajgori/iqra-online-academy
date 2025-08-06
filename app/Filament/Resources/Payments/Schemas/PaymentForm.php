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
            ->schema([
                Select::make('student_id')
                    ->label('Student')
                    ->relationship('student', 'full_name', function ($query) {
                        return $query->with('user');
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name ?? $record->full_name)
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $course = \App\Models\Course::find($state);
                            if ($course) {
                                $set('amount', $course->effective_price);
                            }
                        }
                    }),
                    
                TextInput::make('amount')
                    ->label('Payment Amount')
                    ->required()
                    ->numeric()
                    ->prefix('৳')
                    ->minValue(0)
                    ->readOnly(fn ($record) => $record !== null),
                    
                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'bkash' => 'bKash',
                        'nagad' => 'Nagad',
                        'rocket' => 'Rocket',
                        'bank_transfer' => 'Bank Transfer',
                        'card' => 'Credit/Debit Card',
                        'cash' => 'Cash',
                    ])
                    ->required()
                    ->live(),
                    
                TextInput::make('transaction_id')
                    ->label('Transaction ID')
                    ->maxLength(255),
                    
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
                    ->required(),
                    
                TextInput::make('sender_number')
                    ->label('Sender Mobile Number')
                    ->tel()
                    ->maxLength(20)
                    ->visible(fn ($get) => in_array($get('payment_method'), ['bkash', 'nagad', 'rocket'])),
                    
                TextInput::make('receiver_number')
                    ->label('Receiver Mobile Number')
                    ->tel()
                    ->maxLength(20)
                    ->visible(fn ($get) => in_array($get('payment_method'), ['bkash', 'nagad', 'rocket'])),
                    
                TextInput::make('bank_name')
                    ->label('Bank Name')
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('payment_method') === 'bank_transfer'),
                    
                TextInput::make('account_number')
                    ->label('Account Number')
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('payment_method') === 'bank_transfer'),
                    
                TextInput::make('branch_name')
                    ->label('Branch Name')
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('payment_method') === 'bank_transfer'),
                    
                TextInput::make('currency')
                    ->required()
                    ->default('BDT')
                    ->hidden(),
            ])
            ->columns(2);
    }
}
