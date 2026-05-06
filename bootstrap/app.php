<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('notificaciones:crear-certificados')->daily();
        if (class_exists(\App\Vehiculos\Jobs\RevisarVencimientosVehiculosJob::class)) {
            $schedule->job(new \App\Vehiculos\Jobs\RevisarVencimientosVehiculosJob)->daily();
        }
        $schedule->command('publicaciones:procesar-programadas')->everyMinute()->withoutOverlapping(10);
    })
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
