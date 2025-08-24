<?php

namespace App\Observers;

use App\Models\CourseLesson;

class CourseLessonObserver
{
    /**
     * Handle the CourseLesson "creating" event.
     */
    public function creating(CourseLesson $courseLesson): void
    {
        // Ensure course_id is set before creating
        if (!$courseLesson->course_id && $courseLesson->module_id) {
            $module = $courseLesson->module;
            if ($module) {
                $courseLesson->course_id = $module->course_id;
            }
        }
        
        // Validate that course_id is set
        if (!$courseLesson->course_id) {
            throw new \Exception('Course ID is required for creating a lesson.');
        }
    }

    /**
     * Handle the CourseLesson "updating" event.
     */
    public function updating(CourseLesson $courseLesson): void
    {
        // Ensure course_id is preserved during updates
        if (!$courseLesson->course_id && $courseLesson->module_id) {
            $module = $courseLesson->module;
            if ($module) {
                $courseLesson->course_id = $module->course_id;
            }
        }
        
        // Validate that course_id is set
        if (!$courseLesson->course_id) {
            throw new \Exception('Course ID is required for updating a lesson.');
        }
    }
}
