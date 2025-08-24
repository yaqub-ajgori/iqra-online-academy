<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Resources\Courses\CourseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview Course')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn () => route('frontend.courses.show', $this->record->slug))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->status === 'published'),


            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Let relation managers handle modules and lessons
        // Only handle course-specific data here
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Simply update the course record - let relation managers handle modules/lessons
        unset($data['modules']); // Remove modules data to prevent conflicts
        
        $record->update($data);

        return $record;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Course updated successfully!';
    }
}
