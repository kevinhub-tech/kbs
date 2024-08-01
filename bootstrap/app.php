<?php

use App\Http\Middleware\AdminOnlyAccess;
use App\Http\Middleware\UserOnlyAccess;
use App\Http\Middleware\VendorOnlyAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'user-only' => UserOnlyAccess::class,
            'admin-only' => AdminOnlyAccess::class,
            'vendor-only' => VendorOnlyAccess::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
