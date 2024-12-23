<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Los comandos Artisan proporcionados por la aplicación.
     *
     * @var array
     */
    protected $commands = [
        // Registra aquí los comandos personalizados
        Commands\EnviarNotificacionesCalibracion::class, // Añade tu comando aquí
    ];

    /**
     * Definir el programador de comandos de la aplicación.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Programar el comando para que se ejecute diariamente
        //$schedule->command('notificaciones:calibracion')->daily(); //cada dia
        $schedule->command('notificaciones:calibracion')->everyMinute(); //cada minuto
    }

    /*Middleware Global: Si deseas que se ejecute en todas las solicitudes, debes registrarlo en el archivo app/Http/Kernel.php en la propiedad $middleware: */
    /*protected $middleware = [
        // Otros middlewares...
        \App\Http\Middleware\VerificarCertificados::class,
    ];*/

    protected $routeMiddleware = [
        // Otros middlewares...
        'verificar.certificados' => \App\Http\Middleware\VerificarCertificados::class,
    ];

    
    /**
     * Registrar los comandos de consola de la aplicación.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
