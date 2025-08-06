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



// Certificate preview route for testing
Route::get('/certificates/preview', [App\Http\Controllers\Frontend\CertificateController::class, 'preview'])->name('certificates.preview');

// Certificate PDF download preview
Route::get('/certificates/preview/download', function () {
    // Create a sample certificate object with Islamic course data
    $certificate = (object) [
        'certificate_number' => 'IOA-2025-SAMPLE01',
        'verification_code' => 'PREV12345678',
        'student_name' => 'মোহাম্মদ আব্দুল্লাহ আল মামুন',
        'course_title' => 'কুরআন তিলাওয়াত ও তাজওয়ীদ - প্রাথমিক স্তর',
        'course_description' => 'এই কোর্সে শিক্ষার্থীরা সঠিক উচ্চারণে কুরআন তিলাওয়াত এবং তাজওয়ীদের মৌলিক নিয়মকানুন শিখবেন। কোর্সটিতে রয়েছে আরবি হরফের সঠিক উচ্চারণ, মাখরাজ, সিফাত এবং বিভিন্ন তাজওয়ীদী নিয়মের বিস্তারিত আলোচনা।',
        'instructors' => ['উস্তাদ মুহাম্মদ ইব্রাহিম', 'উস্তাদা ফাতিমা খাতুন'],
        'final_score' => 92.5,
        'completion_date' => now()->subDays(5),
        'issue_date' => now(),
        'expiry_date' => null,
    ];
    
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.template', compact('certificate'))
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'isPhpEnabled' => true,
                      'isRemoteEnabled' => true,
                      'defaultFont' => 'DejaVu Sans',
                      'dpi' => 150,
                  ]);
    
    return $pdf->download('sample-certificate.pdf');
})->name('certificates.preview.download');

// Profile routes would go here if needed
