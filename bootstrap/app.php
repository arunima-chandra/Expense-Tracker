<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'mode.selected' => \App\Http\Middleware\EnsureModeSelected::class,
            'company.admin' => \App\Http\Middleware\EnsureCompanyAdmin::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\EnsureModeSelected::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
        });
    })->create();