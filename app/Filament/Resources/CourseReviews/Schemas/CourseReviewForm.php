<?php

namespace App\Filament\Resources\CourseReviews\Schemas;

use App\Models\CourseReview;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class CourseReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn ($record) => $record !== null),

                Select::make('student_id')
                    ->label('Student')
                    ->relationship('student', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn ($record) => $record !== null),

                TextInput::make('title')
                    ->label('Review Title')
                    ->maxLength(255),

                Textarea::make('review_text')
                    ->label('Review Content')
                    ->required()
                    ->rows(4)
                    ->maxLength(2000),

                Select::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ])
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        CourseReview::STATUS_PENDING => 'Pending',
                        CourseReview::STATUS_APPROVED => 'Approved',
                        CourseReview::STATUS_REJECTED => 'Rejected',
                        CourseReview::STATUS_HIDDEN => 'Hidden',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === CourseReview::STATUS_APPROVED) {
                            $set('approved_at', now());
                            $set('approved_by', auth()->id());
                        } elseif ($state === CourseReview::STATUS_REJECTED) {
                            $set('approved_by', auth()->id());
                        }
                    }),

                DateTimePicker::make('approved_at')
                    ->label('Approved At')
                    ->disabled()
                    ->visible(fn ($get) => in_array($get('status'), [CourseReview::STATUS_APPROVED, CourseReview::STATUS_REJECTED])),

                Select::make('approved_by')
                    ->label('Approved By')
                    ->relationship('approver', 'name')
                    ->disabled()
                    ->visible(fn ($get) => in_array($get('status'), [CourseReview::STATUS_APPROVED, CourseReview::STATUS_REJECTED])),

                Textarea::make('moderation_notes')
                    ->label('Moderation Notes')
                    ->rows(3)
                    ->visible(fn ($get) => $get('status') === CourseReview::STATUS_REJECTED),

                Toggle::make('is_featured')
                    ->label('Featured Review'),

                Toggle::make('is_verified_purchase')
                    ->label('Verified Purchase')
                    ->disabled(),

                TextInput::make('helpful_count')
                    ->label('Helpful Votes')
                    ->numeric()
                    ->disabled()
                    ->default(0),

                TextInput::make('reported_count')
                    ->label('Report Count')
                    ->numeric()
                    ->disabled()
                    ->default(0),
            ])
            ->columns(2);
    }
}