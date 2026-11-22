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
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Las fotografias Base64 no deben copiarse a la sesion al fallar una validacion.
        $exceptions->dontFlash([
            'images_base64',
            'imagen1_base64', 'imagen2_base64', 'imagen3_base64', 'imagen4_base64', 'imagen5_base64',
        ]);
    })->create();
