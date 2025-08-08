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
use App\Http\Controllers\Frontend\HomeController;


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

// Include Blog Routes
require __DIR__.'/blog.php';

// Auth Routes
require __DIR__.'/auth.php';

// Settings Routes
require __DIR__.'/settings.php';

// Dashboard - redirect based on user role
Route::middleware(['auth', 'verified'])->get('/dashboard', [App\Http\Controllers\DashboardRedirectController::class, 'index'])->name('dashboard');

Route::post('/donations', [DonationController::class, 'store'])
    ->middleware('throttle:5,10') // 5 attempts per 10 minutes
    ->name('donations.store');

Route::get('/', [HomeController::class, 'index'])->name('frontend.home');




// Profile routes would go here if needed
