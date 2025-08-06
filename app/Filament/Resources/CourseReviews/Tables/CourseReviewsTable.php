<?php

namespace App\Filament\Resources\CourseReviews\Tables;

use App\Models\CourseReview;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Filament\Forms;
use Illuminate\Database\Eloquent\Collection;

class CourseReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Review Title')
                    ->searchable()
                    ->limit(25)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('review_text')
                    ->label('Review')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->review_text),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => CourseReview::STATUS_PENDING,
                        'success' => CourseReview::STATUS_APPROVED,
                        'danger' => CourseReview::STATUS_REJECTED,
                        'secondary' => CourseReview::STATUS_HIDDEN,
                    ])
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_verified_purchase')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('helpful_count')
                    ->label('Helpful')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Approved')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Not approved'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        CourseReview::STATUS_PENDING => 'Pending',
                        CourseReview::STATUS_APPROVED => 'Approved',
                        CourseReview::STATUS_REJECTED => 'Rejected',
                        CourseReview::STATUS_HIDDEN => 'Hidden',
                    ]),

                SelectFilter::make('rating')
                    ->options([
                        5 => '5 Stars',
                        4 => '4 Stars',
                        3 => '3 Stars',
                        2 => '2 Stars',
                        1 => '1 Star',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Reviews'),

                Tables\Filters\TernaryFilter::make('is_verified_purchase')
                    ->label('Verified Purchase'),

                SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === CourseReview::STATUS_PENDING)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->approve(auth()->user());
                        Notification::make()
                            ->title('Review approved successfully')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === CourseReview::STATUS_PENDING)
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (array $data, $record) {
                        $record->reject(auth()->user(), $data['reason']);
                        Notification::make()
                            ->title('Review rejected')
                            ->success()
                            ->send();
                    }),

                Action::make('feature')
                    ->label('Feature for Homepage')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === CourseReview::STATUS_APPROVED && !$record->is_featured)
                    ->requiresConfirmation()
                    ->modalHeading('Feature Review for Homepage')
                    ->modalDescription('This review will be displayed on the homepage testimonials section. Only featured reviews appear on the homepage.')
                    ->action(function ($record) {
                        // Check current featured count
                        $featuredCount = CourseReview::where('is_featured', true)->count();
                        
                        if ($featuredCount >= 6) {
                            Notification::make()
                                ->title('Maximum featured reviews reached')
                                ->body('You can only have 6 featured reviews at a time. Please unfeature another review first.')
                                ->warning()
                                ->send();
                            return;
                        }
                        
                        $record->markAsFeatured();
                        Notification::make()
                            ->title('Review featured successfully')
                            ->body('This review will now appear on the homepage testimonials.')
                            ->success()
                            ->send();
                    }),

                Action::make('unfeature')
                    ->label('Remove from Homepage')
                    ->icon('heroicon-o-star')
                    ->color('gray')
                    ->visible(fn ($record) => $record->is_featured)
                    ->requiresConfirmation()
                    ->modalHeading('Remove from Homepage')
                    ->modalDescription('This review will no longer appear in the homepage testimonials section.')
                    ->action(function ($record) {
                        $record->unmarkAsFeatured();
                        Notification::make()
                            ->title('Review removed from homepage')
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('approve_bulk')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->status === CourseReview::STATUS_PENDING) {
                                    $record->approve(auth()->user());
                                    $count++;
                                }
                            }
                            
                            Notification::make()
                                ->title("Approved {$count} reviews")
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('reject_bulk')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('reason')
                                ->label('Rejection Reason')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (array $data, Collection $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->status === CourseReview::STATUS_PENDING) {
                                    $record->reject(auth()->user(), $data['reason']);
                                    $count++;
                                }
                            }
                            
                            Notification::make()
                                ->title("Rejected {$count} reviews")
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('feature_bulk')
                        ->label('Feature for Homepage')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Feature Selected Reviews')
                        ->modalDescription('Selected approved reviews will be featured on the homepage. Maximum 6 reviews can be featured.')
                        ->action(function (Collection $records) {
                            $currentFeatured = CourseReview::where('is_featured', true)->count();
                            $approved = $records->filter(fn($r) => $r->status === CourseReview::STATUS_APPROVED && !$r->is_featured);
                            $available = max(0, 6 - $currentFeatured);
                            
                            if ($available === 0) {
                                Notification::make()
                                    ->title('No space for more featured reviews')
                                    ->body('Maximum 6 reviews can be featured. Remove some first.')
                                    ->warning()
                                    ->send();
                                return;
                            }
                            
                            $toFeature = $approved->take($available);
                            $count = 0;
                            
                            foreach ($toFeature as $review) {
                                $review->markAsFeatured();
                                $count++;
                            }
                            
                            $skipped = $approved->count() - $count;
                            $message = "Featured {$count} reviews";
                            if ($skipped > 0) {
                                $message .= ", skipped {$skipped} (limit reached)";
                            }
                            
                            Notification::make()
                                ->title($message)
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('unfeature_bulk')
                        ->label('Remove from Homepage')
                        ->icon('heroicon-o-star')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->is_featured) {
                                    $record->unmarkAsFeatured();
                                    $count++;
                                }
                            }
                            
                            Notification::make()
                                ->title("Removed {$count} reviews from homepage")
                                ->success()
                                ->send();
                        }),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}