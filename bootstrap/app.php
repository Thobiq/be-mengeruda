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
        then: function () {
            \Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('api/sso')
                ->group(__DIR__.'/../routes/api_sso.php');

            \Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('api')
                ->group(__DIR__.'/../routes/api_surat.php');

            \Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('api/tourism')
                ->group(__DIR__.'/../routes/api_tourism.php');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
