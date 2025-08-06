<?php

namespace App\Filament\Resources\CourseReviews\Pages;

use App\Filament\Resources\CourseReviews\CourseReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseReview extends CreateRecord
{
    protected static string $resource = CourseReviewResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set the user_id based on the student
        if (isset($data['student_id'])) {
            $student = \App\Models\Student::find($data['student_id']);
            if ($student) {
                $data['user_id'] = $student->user_id;
            }
        }

        // Set default status to pending
        $data['status'] = \App\Models\CourseReview::STATUS_PENDING;
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}