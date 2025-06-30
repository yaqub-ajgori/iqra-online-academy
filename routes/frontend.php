<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Here are the frontend routes for the Iqra Online Academy LMS.
| These routes handle public-facing pages and course-related functionality.
|
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('frontend.home');

// Static Pages
Route::get('/about', [HomeController::class, 'about'])->name('frontend.about');
Route::get('/contact', [HomeController::class, 'contact'])->name('frontend.contact');

// Course Routes (to be implemented)
Route::prefix('courses')->name('frontend.courses.')->group(function () {
    Route::get('/', function () {
        return inertia('Frontend/CoursesPage');
    })->name('index');
    
    Route::get('/{course:slug}', function () {
        return inertia('Frontend/CourseDetailsPage');
    })->name('show');
    
    Route::get('/{course:slug}/preview', function () {
        return inertia('Frontend/CoursePreviewPage');
    })->name('preview');
});

// Payment Routes
Route::prefix('payment')->name('frontend.payment.')->group(function () {
    Route::get('/checkout/{course}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/process/{course}', [PaymentController::class, 'processPayment'])->name('process');
});

// Student Dashboard (for UI testing - no auth required)
Route::get('/student/dashboard', function () {
    return inertia('Frontend/StudentDashboard');
})->name('frontend.student.dashboard');

// Learning Routes (for UI testing - no auth required)
Route::prefix('learn')->name('frontend.learn')->group(function () {
    Route::get('/{course}', function () {
        return inertia('Frontend/LearningPage');
    });
    
    Route::get('/{course}/lesson/{lesson}', function () {
        return inertia('Frontend/LessonPage');
    })->name('.lesson');
});

// Authenticated Routes (for later when we add real authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // These will be used later when we implement real authentication
    // For now, we're using the non-authenticated versions above for UI testing
});

 