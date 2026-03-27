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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback',
        ]);
        $middleware->trustProxies(at: '*');
        $middleware->prependToGroup('web', \App\Http\Middleware\DebugRequestStart::class);
        $middleware->append(\App\Http\Middleware\SetCurrency::class);
        $middleware->append(\App\Http\Middleware\IncreaseUploadTimeout::class);
        $middleware->alias([
            'json.request' => \App\Http\Middleware\JsonRequestMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
