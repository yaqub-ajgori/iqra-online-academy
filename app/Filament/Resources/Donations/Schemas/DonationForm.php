<?php

namespace App\Filament\Resources\Donations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Donor Name')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('phone')
                    ->label('Phone Number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('amount')
                    ->label('Donation Amount')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->prefix('৳'),
                    
                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'bkash' => 'bKash',
                        'nagad' => 'Nagad',
                        'rocket' => 'Rocket',
                        'bank' => 'Bank Transfer',
                        'cash' => 'Cash',
                    ])
                    ->required(),
                    
                TextInput::make('transaction_id')
                    ->label('Transaction ID')
                    ->required()
                    ->maxLength(255),
                    
                Textarea::make('reason')
                    ->label('Donation Reason')
                    ->rows(3)
                    ->maxLength(500),
            ])
            ->columns(2);
    }
}
