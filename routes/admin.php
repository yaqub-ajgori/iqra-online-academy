<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Auth\AdminPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseCategoryController;
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
        Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:3,1')->name('login.submit');
        
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
        
        // Course Category Management
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CourseCategoryController::class, 'index'])->name('index');
            Route::get('/create', [CourseCategoryController::class, 'create'])->name('create');
            Route::post('/', [CourseCategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CourseCategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CourseCategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CourseCategoryController::class, 'destroy'])->name('destroy');
            Route::patch('/{category}/toggle-status', [CourseCategoryController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Course Management
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('index');
            Route::get('/create', [CourseController::class, 'create'])->name('create');
            Route::post('/', [CourseController::class, 'store'])->name('store');
            Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
            Route::put('/{course}', [CourseController::class, 'update'])->name('update');
            Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy');
            Route::patch('/{course}/toggle-featured', [CourseController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::patch('/{course}/update-status', [CourseController::class, 'updateStatus'])->name('update-status');
            
            // Course Builder - Unified Interface
            Route::get('/{course}/builder', [CourseController::class, 'builder'])->name('builder');
            Route::post('/{course}/builder/modules', [CourseController::class, 'storeModule'])->name('builder.store-module');
            Route::put('/{course}/builder/modules/{module}', [CourseController::class, 'updateModule'])->name('builder.update-module');
            Route::delete('/{course}/builder/modules/{module}', [CourseController::class, 'deleteModule'])->name('builder.delete-module');
            Route::post('/{course}/builder/modules/{module}/lessons', [CourseController::class, 'storeLesson'])->name('builder.store-lesson');
            Route::put('/{course}/builder/lessons/{lesson}', [CourseController::class, 'updateLesson'])->name('builder.update-lesson');
            Route::delete('/{course}/builder/lessons/{lesson}', [CourseController::class, 'deleteLesson'])->name('builder.delete-lesson');
            Route::post('/{course}/builder/reorder', [CourseController::class, 'reorderContent'])->name('builder.reorder');
        });
        
        // Student Management
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/', [StudentController::class, 'store'])->name('store');
            Route::get('/{student}', [StudentController::class, 'show'])->name('show');
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
            Route::put('/{student}', [StudentController::class, 'update'])->name('update');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
        });
        
        // Teacher Management
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/', [TeacherController::class, 'index'])->name('index');
            Route::get('/create', [TeacherController::class, 'create'])->name('create');
            Route::post('/', [TeacherController::class, 'store'])->name('store');
            Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show');
            Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit');
            Route::put('/{teacher}', [TeacherController::class, 'update'])->name('update');
            Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy');
            Route::patch('/{teacher}/toggle-verification', [TeacherController::class, 'toggleVerification'])->name('toggle-verification');
            Route::patch('/{teacher}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Enrollment Management
        Route::prefix('enrollments')->name('enrollments.')->group(function () {
            Route::get('/', [EnrollmentController::class, 'index'])->name('index');
            Route::get('/create', [EnrollmentController::class, 'create'])->name('create');
            Route::post('/', [EnrollmentController::class, 'store'])->name('store');
            Route::get('/{enrollment}', [EnrollmentController::class, 'show'])->name('show');
            Route::get('/{enrollment}/edit', [EnrollmentController::class, 'edit'])->name('edit');
            Route::put('/{enrollment}', [EnrollmentController::class, 'update'])->name('update');
            Route::delete('/{enrollment}', [EnrollmentController::class, 'destroy'])->name('destroy');
            Route::patch('/{enrollment}/toggle-status', [EnrollmentController::class, 'toggleStatus'])->name('toggle-status');
            Route::patch('/{enrollment}/update-progress', [EnrollmentController::class, 'updateProgress'])->name('update-progress');
        });
        
        // Payment Management
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('index');
            Route::get('/create', [PaymentController::class, 'create'])->name('create');
            Route::post('/', [PaymentController::class, 'store'])->name('store');
            Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
            Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
            Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
            Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
            Route::patch('/{payment}/update-status', [PaymentController::class, 'updateStatus'])->name('update-status');
            Route::patch('/{payment}/approve', [PaymentController::class, 'approve'])->name('approve');
            Route::patch('/{payment}/reject', [PaymentController::class, 'reject'])->name('reject');
        });
    });
}); 