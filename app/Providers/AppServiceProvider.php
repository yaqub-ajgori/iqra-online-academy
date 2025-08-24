<?php

namespace App\Providers;

use App\Models\CourseLesson;
use App\Observers\CourseLessonObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        CourseLesson::observe(CourseLessonObserver::class);
    }
}
