<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Admin Dashboard (for authenticated users)
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Settings and Auth Routes
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
