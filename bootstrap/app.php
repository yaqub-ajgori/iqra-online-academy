<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Inertia\Inertia;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Register custom middleware
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'student' => StudentMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom 404 error page for frontend
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not found.'], 404);
            }
            
            // Check if this is a frontend route (not admin routes)
            if (!str_starts_with($request->path(), 'admin')) {
                return Inertia::render('Errors/NotFound', [
                    'status' => 404,
                    'message' => 'Page not found',
                    'auth' => [
                        'user' => null
                    ]
                ])->toResponse($request)->setStatusCode(404);
            }
        });

        // Custom error pages for other HTTP errors
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
            }
            
            // Check if this is a frontend route (not admin routes)
            if (!str_starts_with($request->path(), 'admin')) {
                $status = $e->getStatusCode();
                
                // Only handle specific error codes
                if (in_array($status, [419, 429, 500, 503])) {
                    return Inertia::render('Errors/Error', [
                        'status' => $status,
                        'title' => $e->getMessage(),
                        'message' => $e->getMessage(),
                        'description' => 'দুঃখিত, একটি সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।',
                        'auth' => [
                            'user' => null
                        ]
                    ])->toResponse($request)->setStatusCode($status);
                }
            }
        });
    })->create();
