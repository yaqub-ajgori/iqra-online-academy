<?php

namespace App\Filament\Resources\Donations\Schemas;

use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Donor Name')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('phone')
                    ->label('Phone Number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('amount')
                    ->label('Donation Amount (৳)')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                \Filament\Forms\Components\Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'bkash' => 'bKash',
                        'nagad' => 'Nagad',
                        'rocket' => 'Rocket',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('transaction_id')
                    ->label('Transaction ID')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('reason')
                    ->label('Donation Reason')
                    ->maxLength(255),
            ]);
    }
}
