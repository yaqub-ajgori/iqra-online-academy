<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Auth\AdminPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication Routes (Public)
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest only routes (not authenticated as admin)
    Route::middleware('guest')->group(function () {
        // Login
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
        
        // Forgot Password
        Route::get('/forgot-password', [AdminPasswordController::class, 'showForgotForm'])->name('password.request');
        Route::post('/forgot-password', [AdminPasswordController::class, 'sendResetLink'])->name('password.email');
        
        // Reset Password
        Route::get('/reset-password/{token}', [AdminPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [AdminPasswordController::class, 'resetPassword'])->name('password.update');
    });
    
    // Admin authenticated routes
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // Admin Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');
        
        // Admin Settings
        Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
        Route::patch('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
        
        // Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Course Management
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('index');
            Route::get('/create', [CourseController::class, 'create'])->name('create');
            Route::post('/', [CourseController::class, 'store'])->name('store');
            Route::get('/{course}', [CourseController::class, 'show'])->name('show');
            Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
            Route::put('/{course}', [CourseController::class, 'update'])->name('update');
            Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy');
        });
        
        // Student Management
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/{student}', [StudentController::class, 'show'])->name('show');
            Route::patch('/{student}/verify', [StudentController::class, 'verify'])->name('verify');
        });
        
        // Teacher Management
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/', [TeacherController::class, 'index'])->name('index');
            Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show');
            Route::patch('/{teacher}/verify', [TeacherController::class, 'verify'])->name('verify');
        });
        
        // Enrollment Management
        Route::prefix('enrollments')->name('enrollments.')->group(function () {
            Route::get('/', [EnrollmentController::class, 'index'])->name('index');
            Route::get('/{enrollment}', [EnrollmentController::class, 'show'])->name('show');
        });
        
        // Payment Management (Manual Payment System)
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('index');
            Route::get('/create', [PaymentController::class, 'create'])->name('create');
            Route::post('/', [PaymentController::class, 'store'])->name('store');
            Route::patch('/{payment}/confirm', [PaymentController::class, 'confirmPayment'])->name('confirm');
            Route::patch('/{payment}/reject', [PaymentController::class, 'rejectPayment'])->name('reject');
        });
    });
}); 