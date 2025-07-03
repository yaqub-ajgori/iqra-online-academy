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
        // Load modules and lessons for editing
        $data['modules'] = $this->record->modules()
            ->with('lessons')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($module) {
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'sort_order' => $module->sort_order,
                    'is_active' => $module->is_active,
                    'lessons' => $module->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'content' => $lesson->content,
                            'lesson_type' => $lesson->lesson_type,
                            'video_url' => $lesson->video_url,
                            'video_duration' => $lesson->video_duration,
                            'video_provider' => $lesson->video_provider,
                            'reading_time_minutes' => $lesson->reading_time_minutes,
                            'is_preview' => $lesson->is_preview,
                            'is_mandatory' => $lesson->is_mandatory,
                            'sort_order' => $lesson->sort_order,
                            'is_active' => $lesson->is_active,
                        ];
                    })->toArray(),
                ];
            })->toArray();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Extract modules data for separate processing
        $modulesData = $data['modules'] ?? [];
        unset($data['modules']);

        // Update the main course record
        $record->update($data);

        // Handle modules and lessons updates
        $this->syncModulesAndLessons($record, $modulesData);

        return $record;
    }

    protected function syncModulesAndLessons(Model $record, array $modulesData): void
    {
        $existingModuleIds = [];
        
        foreach ($modulesData as $moduleData) {
            $lessonsData = $moduleData['lessons'] ?? [];
            unset($moduleData['lessons']);

            if (isset($moduleData['id'])) {
                // Update existing module
                $module = $record->modules()->find($moduleData['id']);
                if ($module) {
                    $module->update($moduleData);
                    $existingModuleIds[] = $module->id;
                }
            } else {
                // Create new module
                $module = $record->modules()->create($moduleData);
                $existingModuleIds[] = $module->id;
            }

            if ($module) {
                $this->syncLessons($module, $record, $lessonsData);
            }
        }

        // Delete removed modules
        $record->modules()->whereNotIn('id', $existingModuleIds)->delete();
    }

    protected function syncLessons($module, $course, array $lessonsData): void
    {
        $existingLessonIds = [];

        foreach ($lessonsData as $lessonData) {
            $lessonData['module_id'] = $module->id;
            $lessonData['course_id'] = $course->id;

            if (isset($lessonData['id'])) {
                // Update existing lesson
                $lesson = $module->lessons()->find($lessonData['id']);
                if ($lesson) {
                    $lesson->update($lessonData);
                    $existingLessonIds[] = $lesson->id;
                }
            } else {
                // Create new lesson
                $lesson = $module->lessons()->create($lessonData);
                $existingLessonIds[] = $lesson->id;
            }
        }

        // Delete removed lessons
        $module->lessons()->whereNotIn('id', $existingLessonIds)->delete();
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Course updated successfully!';
    }
}
