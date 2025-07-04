<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DonationController;

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
