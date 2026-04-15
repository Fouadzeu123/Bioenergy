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
        // Exclure le webhook Notch Pay du CSRF (requête POST depuis les serveurs Notch Pay)
        $middleware->validateCsrfTokens(except: [
            'webhooks/notchpay',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\ProcessAutomatedTasks::class,
            \App\Http\Middleware\StoreReferralCode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
