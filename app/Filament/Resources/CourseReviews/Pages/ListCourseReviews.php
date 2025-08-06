<?php

namespace App\Filament\Resources\CourseReviews\Pages;

use App\Filament\Resources\CourseReviews\CourseReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseReviews extends ListRecords
{
    protected static string $resource = CourseReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}