<?php

use App\Http\Middleware\ApiAuth;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isLeagueModerator;
use App\Http\Middleware\isSysModerator;
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
        $middleware->alias([
            'api-auth' => ApiAuth::class,
            'is-admin' => isAdmin::class,
            'is-sys-moderator' => isSysModerator::class,
            'is-league-moderator' => isLeagueModerator::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
