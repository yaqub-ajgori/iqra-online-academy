<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Resources\Courses\CourseResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract nested data for separate processing
        $modulesData = $data['modules'] ?? [];
        unset($data['modules']);

        // Store modules data for later processing
        $this->modulesData = $modulesData;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Create the course record first
        $record = static::getModel()::create($data);

        // Now handle modules and lessons
        if (!empty($this->modulesData)) {
            foreach ($this->modulesData as $moduleData) {
                // Extract lessons from module data
                $lessonsData = $moduleData['lessons'] ?? [];
                unset($moduleData['lessons']);

                // Create the module
                $module = $record->modules()->create($moduleData);

                // Create lessons for this module
                if (!empty($lessonsData)) {
                    foreach ($lessonsData as $lessonData) {
                        // Add both module_id and course_id for proper relationships
                        $lessonData['module_id'] = $module->id;
                        $lessonData['course_id'] = $record->id;
                        
                        $module->lessons()->create($lessonData);
                    }
                }
            }
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Course created successfully! You can now add more content or publish it.';
    }

    // Property to store modules data temporarily
    protected array $modulesData = [];
}
