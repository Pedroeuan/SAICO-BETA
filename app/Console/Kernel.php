<?php

namespace App\Console;

use App\Vehiculos\Jobs\RevisarVencimientosVehiculosJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Los comandos Artisan proporcionados por la aplicacion.
     *
     * @var array
     */
    protected $commands = [
        Commands\CrearNotificacionesCertificados::class,
        Commands\PublicarPublicacionesProgramadas::class,
    ];

    /**
     * Definir el programador de comandos de la aplicacion.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notificaciones:crear-certificados')->daily();
        $schedule->job(new RevisarVencimientosVehiculosJob)->daily();
        $schedule->command('publicaciones:procesar-programadas')->everyMinute()->withoutOverlapping();
    }

    protected $routeMiddleware = [
        // Otros middlewares...
    ];

    /**
     * Registrar los comandos de consola de la aplicacion.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
