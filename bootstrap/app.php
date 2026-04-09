<?php

use App\Http\Middleware\checkAdmin;
use App\Http\Middleware\CheckSecretaire;
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'checkAdmin' => checkAdmin::class ,
            'CheckSecretaire' => CheckSecretaire::class

        ]);

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            if ($request->user() && $request->user()->role === 'ADMIN') {
                return route('admin.dashboard');
            }
            return route('dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
