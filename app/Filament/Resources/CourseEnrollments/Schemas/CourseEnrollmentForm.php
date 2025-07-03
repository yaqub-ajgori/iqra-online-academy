<?php

namespace App\Filament\Resources\CourseEnrollments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseEnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                // Student and Course Selection
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
                    
                // Enrollment Info
                DateTimePicker::make('enrolled_at')
                    ->label('Enrollment Date')
                    ->required()
                    ->default(now())
                    ->columnSpan(2),
                    
                Select::make('enrollment_type')
                    ->label('Enrollment Type')
                    ->options([
                        'paid' => 'Paid',
                        'free' => 'Free',
                        'scholarship' => 'Scholarship',
                        'trial' => 'Trial'
                    ])
                    ->default('paid')
                    ->required()
                    ->columnSpan(2),
                    
                // Payment Information
                Select::make('payment_id')
                    ->label('Related Payment')
                    ->relationship('payment', 'transaction_id', function ($query) {
                        return $query->with(['student.user', 'course']);
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                        "#{$record->transaction_id} - {$record->student->user->name} - ৳{$record->amount}"
                    )
                    ->searchable()
                    ->preload()
                    ->columnSpan(2),
                    
                TextInput::make('amount_paid')
                    ->label('Amount Paid')
                    ->required()
                    ->numeric()
                    ->prefix('৳')
                    ->default(0.0)
                    ->minValue(0)
                    ->columnSpan(1),
                    
                Select::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->default('pending')
                    ->required()
                    ->columnSpan(1),
                    
                // Progress Tracking
                TextInput::make('progress_percentage')
                    ->label('Progress (%)')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->columnSpan(1),
                    
                TextInput::make('lessons_completed')
                    ->label('Lessons Completed')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->columnSpan(1),
                    
                // Boolean fields in one row
                Toggle::make('is_completed')
                    ->label('Course Completed')
                    ->default(false)
                    ->columnSpan(1),
                    
                Toggle::make('is_active')
                    ->label('Active Enrollment')
                    ->default(true)
                    ->columnSpan(1),
                    
                // Hidden currency field
                TextInput::make('currency')
                    ->required()
                    ->default('BDT')
                    ->hidden(),
            ]);
    }
}
