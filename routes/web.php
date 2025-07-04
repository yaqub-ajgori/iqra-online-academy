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

// Auth Routes
require __DIR__.'/auth.php';

// Student Dashboard - redirect to student dashboard after login
Route::middleware(['auth'])->get('/dashboard', function () {
    return redirect()->route('frontend.student.dashboard');
})->name('dashboard');

Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');

Route::get('/', [HomeController::class, 'index'])->name('frontend.home');



// Profile routes would go here if needed
