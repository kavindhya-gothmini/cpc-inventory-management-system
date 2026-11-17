<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SecurityHeaders;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases
        |--------------------------------------------------------------------------
        */

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Global Security Middleware
        |--------------------------------------------------------------------------
        |
        | Apply security headers to all application HTTP responses.
        |
        */

        $middleware->append(SecurityHeaders::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /*
        |--------------------------------------------------------------------------
        | Exception Handling
        |--------------------------------------------------------------------------
        |
        | Keep Laravel's default exception handling.
        | Custom production error handling can be configured here later.
        |
        */

    })
    ->create();
