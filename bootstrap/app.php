<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware -> alias([
            'Token' => \App\Http\Middleware\JwtMiddleware::class,
<<<<<<< HEAD
            'Company' => \App\Http\Middleware\Company::class,
=======
            'Student' => \App\Http\Middleware\StudentMiddleware::class,
>>>>>>> 3c218e10693593154f67116bec07cc453f936373
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
