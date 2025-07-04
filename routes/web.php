<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Foundation\Application;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Include Frontend Routes (Public facing Islamic LMS)
require __DIR__.'/frontend.php';

// Auth Routes
require __DIR__.'/auth.php';

// Student Dashboard - redirect to student dashboard after login
Route::middleware(['auth'])->get('/dashboard', function () {
    return redirect()->route('frontend.student.dashboard');
})->name('dashboard');

Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Temporary debugging route - REMOVE AFTER DEBUGGING
Route::get('/debug-course/{course}', function (\App\Models\Course $course) {
    $course->load([
        'teacher', 
        'category',
        'modules' => function ($query) {
            $query->orderBy('sort_order');
        },
        'modules.lessons' => function ($query) {
            $query->where('is_active', true)->orderBy('sort_order');
        }
    ]);

    $moduleData = $course->modules->map(function ($module) {
        return [
            'id' => $module->id,
            'title' => $module->title,
            'lessons_count' => $module->lessons->count(),
            'lessons' => $module->lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'is_active' => $lesson->is_active,
                    'duration' => $lesson->formatted_duration ?? '0:00',
                ];
            }),
        ];
    });

    return response()->json([
        'course_id' => $course->id,
        'course_title' => $course->title,
        'total_modules' => $course->modules->count(),
        'total_lessons' => $course->lessons()->count(),
        'active_lessons' => $course->lessons()->where('is_active', true)->count(),
        'modules_data' => $moduleData,
    ], 200, [], JSON_PRETTY_PRINT);
});

// Profile routes would go here if needed
