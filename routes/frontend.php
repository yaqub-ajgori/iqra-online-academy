<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\StudentDashboardController;
use App\Http\Controllers\Frontend\LearningController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\CertificateController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\QuizController;
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
Route::get('/daily-verse', function () {
    return inertia('Frontend/DailyVerse');
})->name('frontend.daily-verse');
Route::get('/quran', function () {
    return inertia('Frontend/QuranReader');
})->name('frontend.quran');
Route::post('/contact', [ContactController::class, 'submit'])
    ->middleware('throttle:5,15') // 5 attempts per 15 minutes
    ->name('frontend.contact.submit');

// Course Routes
Route::prefix('courses')->name('frontend.courses.')->group(function () {
    Route::get('/', [App\Http\Controllers\Frontend\CourseController::class, 'index'])->name('index');
    
    Route::get('/{course:slug}', [App\Http\Controllers\Frontend\CourseController::class, 'show'])->name('show');
    
    // Review Routes
    Route::get('/{course:slug}/reviews', [ReviewController::class, 'index'])->name('reviews');
    
    // Protected review routes (requires student authentication)
    Route::middleware(['auth', 'student', 'verified'])->group(function () {
        Route::post('/{course:slug}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/{course:slug}/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    });
    
    // Review helpful votes (both authenticated and guest)
    Route::post('/reviews/{review}/helpful', [ReviewController::class, 'markHelpful'])
        ->middleware('throttle:10,60') // 10 votes per minute
        ->name('reviews.helpful');
    
    // Review reporting
    Route::post('/reviews/{review}/report', [ReviewController::class, 'report'])
        ->middleware('throttle:5,60') // 5 reports per minute
        ->name('reviews.report');
});

// Payment Routes with rate limiting
Route::prefix('payment')->name('frontend.payment.')->group(function () {
    Route::get('/checkout/{course}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/process/{course}', [PaymentController::class, 'processPayment'])
        ->middleware('throttle:3,10') // 3 attempts per 10 minutes
        ->name('process');
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

// Quiz Routes (Protected - requires student authentication)
Route::middleware(['auth', 'student', 'verified'])->prefix('quiz')->name('quiz.')->group(function () {
    Route::get('/{quiz}', [QuizController::class, 'show'])->name('show');
    Route::post('/{quiz}/submit', [QuizController::class, 'submit'])->name('submit');
});

// Certificate Routes
Route::prefix('certificates')->name('certificates.')->group(function () {
    // Certificate preview for students
    Route::get('/preview/{course?}', [CertificateController::class, 'preview'])->name('preview');
    
    // Public certificate verification
    Route::get('/verify', [CertificateController::class, 'verify'])->name('verify');
    Route::post('/verify', [CertificateController::class, 'verifySubmit'])->name('verify.submit');
});

// API Routes for AJAX requests
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/testimonials', [ReviewController::class, 'getFeaturedReviews'])->name('testimonials');
});

// Authenticated Routes (for later when we add real authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // These will be used later when we implement real authentication
    // For now, we're using the non-authenticated versions above for UI testing
});

 