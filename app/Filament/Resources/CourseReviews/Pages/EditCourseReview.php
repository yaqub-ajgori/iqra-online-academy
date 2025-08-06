<?php

namespace App\Filament\Resources\CourseReviews\Pages;

use App\Filament\Resources\CourseReviews\CourseReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseReview extends EditRecord
{
    protected static string $resource = CourseReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Auto-update approval timestamp when status changes to approved
        if ($data['status'] === \App\Models\CourseReview::STATUS_APPROVED && !$this->record->approved_at) {
            $data['approved_at'] = now();
            $data['approved_by'] = auth()->id();
        }

        // Clear approval data when status changes away from approved/rejected
        if (!in_array($data['status'], [\App\Models\CourseReview::STATUS_APPROVED, \App\Models\CourseReview::STATUS_REJECTED])) {
            $data['approved_at'] = null;
            $data['approved_by'] = null;
            $data['moderation_notes'] = null;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}