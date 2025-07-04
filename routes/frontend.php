<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\StudentDashboardController;
use App\Http\Controllers\Frontend\LearningController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ContactController;
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
Route::get('/about', [AboutController::class, 'index'])->name('frontend.about');
Route::get('/contact', [ContactController::class, 'index'])->name('frontend.contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('frontend.contact.submit');

// Course Routes
Route::prefix('courses')->name('frontend.courses.')->group(function () {
    Route::get('/', [App\Http\Controllers\Frontend\CourseController::class, 'index'])->name('index');
    
    Route::get('/{course:slug}', [App\Http\Controllers\Frontend\CourseController::class, 'show'])->name('show');
});

// Payment Routes
Route::prefix('payment')->name('frontend.payment.')->group(function () {
    Route::get('/checkout/{course}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/process/{course}', [PaymentController::class, 'processPayment'])->name('process');
});

// Student Dashboard (Protected - requires student authentication)
Route::middleware(['auth', 'student', 'verified'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('frontend.student.dashboard');
    Route::patch('/student/profile', [StudentDashboardController::class, 'updateProfile'])->name('frontend.student.profile.update');
    Route::patch('/student/password', [StudentDashboardController::class, 'changePassword'])->name('frontend.student.password.update');
});

// Learning Routes (Protected - requires student authentication and enrollment)
Route::middleware(['auth', 'student', 'verified'])->prefix('learning')->name('frontend.learning.')->group(function () {
    Route::get('/{course:slug}', [LearningController::class, 'show'])->name('show');
    Route::post('/complete-lesson', [LearningController::class, 'completeLesson'])->name('complete-lesson');
    Route::get('/{course:slug}/lesson/{lesson}', [LearningController::class, 'lesson'])->name('lesson');
});

// Authenticated Routes (for later when we add real authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // These will be used later when we implement real authentication
    // For now, we're using the non-authenticated versions above for UI testing
});

 