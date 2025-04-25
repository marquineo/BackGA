<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(function () {
        require base_path('routes/web.php');
        require base_path('routes/api.php');
    })

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'entrenadores',
            'entrenadores/*',
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
