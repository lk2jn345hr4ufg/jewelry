<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'api.token' => \App\Http\Middleware\VerifyApiToken::class,
        ]);
        $middleware->append(\App\Http\Middleware\HandleRedirects::class);
        $middleware->web(append: [\App\Http\Middleware\ProtectSite::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // API clients get JSON even without an Accept header.
        $exceptions->shouldRenderJsonWhen(fn ($request) => $request->is('api/*'));

        // Laravel puts the first validation message in "message"; the API
        // contract asks for a fixed phrase there instead.
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });
    })->create();
