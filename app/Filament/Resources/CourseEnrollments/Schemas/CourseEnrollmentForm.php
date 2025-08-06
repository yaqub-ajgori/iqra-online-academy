<?php

namespace App\Filament\Resources\CourseEnrollments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseEnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Enrollment Details')
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
                            ->preload(),
                            
                        DateTimePicker::make('enrolled_at')
                            ->label('Enrollment Date')
                            ->required()
                            ->default(now())
                            ->native(false),
                            
                        Select::make('enrollment_type')
                            ->label('Enrollment Type')
                            ->options([
                                'paid' => 'Paid',
                                'free' => 'Free',
                                'scholarship' => 'Scholarship',
                                'trial' => 'Trial'
                            ])
                            ->default('paid')
                            ->required(),
                    ])
                    ->columns(2),
                    
                Section::make('Payment Information')
                    ->schema([
                        Select::make('payment_id')
                            ->label('Related Payment')
                            ->relationship('payment', 'transaction_id', function ($query) {
                                return $query->with(['student.user', 'course']);
                            })
                            ->getOptionLabelFromRecordUsing(fn ($record) => 
                                "#{$record->transaction_id} - {$record->student->user->name} - ৳{$record->amount}")
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $payment = \App\Models\Payment::find($state);
                                    if ($payment) {
                                        $set('amount_paid', $payment->amount);
                                        $set('payment_status', $payment->status);
                                    }
                                }
                            })
                            ->columnSpanFull(),
                            
                        TextInput::make('amount_paid')
                            ->label('Amount Paid')
                            ->required()
                            ->numeric()
                            ->prefix('৳')
                            ->default(0.0)
                            ->minValue(0),
                            
                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->columns(2),
                    
                Section::make('Progress Tracking')
                    ->schema([
                        TextInput::make('progress_percentage')
                            ->label('Progress Percentage')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%'),
                            
                        TextInput::make('lessons_completed')
                            ->label('Lessons Completed') 
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                            
                        Toggle::make('is_active')
                            ->label('Active Enrollment')
                            ->default(true),
                            
                        Toggle::make('is_completed')
                            ->label('Course Completed')
                            ->default(false),
                    ])
                    ->columns(2),
                    
                // Hidden fields
                TextInput::make('currency')
                    ->default('BDT')
                    ->hidden(),
            ]);
    }
}
