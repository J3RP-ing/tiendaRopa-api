<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrar middlewares personalizados con alias
        $middleware->alias([
            'auth.api' => \App\Http\Middleware\EnsureAuthenticated::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'ownership' => \App\Http\Middleware\EnsureOwnership::class,
            'api.logging' => \App\Http\Middleware\ApiLogging::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
