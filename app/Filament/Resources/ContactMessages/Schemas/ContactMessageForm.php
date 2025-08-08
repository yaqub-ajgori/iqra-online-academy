<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Contact Details')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                    
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                    
                                TextInput::make('phone')
                                    ->label('Phone')
                                    ->maxLength(255),
                                    
                                TextInput::make('subject')
                                    ->label('Subject')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2),

                        Section::make('Message')
                            ->schema([
                                Textarea::make('message')
                                    ->label('Message')
                                    ->required()
                                    ->rows(6)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Status & Priority')
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'unread' => 'Unread',
                                        'read' => 'Read',
                                        'replied' => 'Replied',
                                        'resolved' => 'Resolved',
                                    ])
                                    ->default('unread')
                                    ->required(),

                                Select::make('priority')
                                    ->label('Priority')
                                    ->options([
                                        'low' => 'Low',
                                        'medium' => 'Medium',
                                        'high' => 'High',
                                        'urgent' => 'Urgent',
                                    ])
                                    ->default('medium')
                                    ->required(),
                            ]),

                        Section::make('Timestamps')
                            ->schema([
                                DateTimePicker::make('read_at')
                                    ->label('Read At')
                                    ->timezone('Asia/Dhaka'),

                                DateTimePicker::make('replied_at')
                                    ->label('Replied At')
                                    ->timezone('Asia/Dhaka'),
                            ])
                            ->collapsible()
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ]);
    }
}
