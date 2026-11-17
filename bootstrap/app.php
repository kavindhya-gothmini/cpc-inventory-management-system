<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\ForceHttps;

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
        |
        | Register custom middleware aliases.
        |
        */

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | HTTPS Enforcement
        |--------------------------------------------------------------------------
        |
        | Enforce HTTPS only in production.
        | Local development using HTTP remains supported.
        |
        */

        $middleware->append(ForceHttps::class);

        /*
        |--------------------------------------------------------------------------
        | Global Security Headers
        |--------------------------------------------------------------------------
        |
        | Apply security headers and browser cache protection
        | to application HTTP responses.
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
        | Configure production error reporting separately.
        |
        */

    })
    ->create();
