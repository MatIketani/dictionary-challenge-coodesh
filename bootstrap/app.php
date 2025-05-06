<?php

use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        App\Providers\RouteServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ForceJsonResponse::class);
        $middleware->append(HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Turn all the exceptions into JSON responses on the Coodesh format.
        $exceptions->render(function (Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        });
    })
    ->create();
