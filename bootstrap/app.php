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
    ->withMiddleware(function ($middleware) {
<<<<<<< Updated upstream
=======

        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();

            if ($user && strtolower(trim((string) $user->role)) === 'admin') {
                return route('admin.dashboard');
            }

            return route('dashboard');
        });

>>>>>>> Stashed changes
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
