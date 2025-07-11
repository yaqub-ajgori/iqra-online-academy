<?php

use App\Http\Controllers\Frontend\BlogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
|
| Here are the blog routes for the Iqra Online Academy.
| These routes handle blog listing, individual posts, categories, and tags.
|
*/

Route::prefix('blog')->name('frontend.blog.')->group(function () {
    // Blog main pages
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
    
    // Category and tag pages
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/author/{id}', [BlogController::class, 'author'])->name('author');
    
    // Individual blog post
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
    
    // Comment and reaction routes with rate limiting
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/comments', [BlogController::class, 'storeComment'])->name('comments.store');
        Route::post('/posts/{post}/react', [BlogController::class, 'reactToPost'])->name('posts.react');
    });
    
    // RSS feed
    Route::get('/feed', [BlogController::class, 'feed'])->name('feed');
});