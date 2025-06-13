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
    ->withMiddleware(function (Middleware $middleware) {
        return [
            'middleware' => [
                \App\Http\Middleware\TrustProxies::class,
                \Illuminate\Http\Middleware\HandleCors::class,
                \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
                \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
                \App\Http\Middleware\TrimStrings::class,
            ],
            'aliases' => [
                'auth' => \App\Http\Middleware\Authenticate::class,
                'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
                // Otros alias que uses
            ],
        ];
    })


    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
