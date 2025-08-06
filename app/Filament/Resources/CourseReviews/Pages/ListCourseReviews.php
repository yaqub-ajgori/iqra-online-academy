<?php

namespace App\Filament\Resources\CourseReviews\Pages;

use App\Filament\Resources\CourseReviews\CourseReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CourseReview;

class ListCourseReviews extends ListRecords
{
    protected static string $resource = CourseReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Reviews'),
            
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourseReview::STATUS_PENDING))
                ->badge(CourseReview::where('status', CourseReview::STATUS_PENDING)->count())
                ->badgeColor('warning'),
            
            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourseReview::STATUS_APPROVED))
                ->badge(CourseReview::where('status', CourseReview::STATUS_APPROVED)->count())
                ->badgeColor('success'),
            
            'featured' => Tab::make('Featured (Homepage)')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_featured', true))
                ->badge(CourseReview::where('is_featured', true)->count() . '/6')
                ->badgeColor('primary'),
            
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CourseReview::STATUS_REJECTED))
                ->badge(CourseReview::where('status', CourseReview::STATUS_REJECTED)->count())
                ->badgeColor('danger'),
            
            'high_rated' => Tab::make('High Rated (4-5 Stars)')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('rating', '>=', 4))
                ->badge(CourseReview::where('rating', '>=', 4)->count())
                ->badgeColor('success'),
            
            'verified' => Tab::make('Verified Purchase')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_verified_purchase', true))
                ->badge(CourseReview::where('is_verified_purchase', true)->count())
                ->badgeColor('info'),
        ];
    }
}