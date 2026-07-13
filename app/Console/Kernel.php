<?php

namespace App\Console;

use App\Vehiculos\Jobs\RevisarVencimientosVehiculosJob;
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
        Commands\CrearNotificacionesCertificados::class, 
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
        $schedule->command('notificaciones:crear-certificados')->daily(); //cada dia
        //$schedule->command('notificaciones:crear-certificados')->dailyAt('04:00'); //ejecutar en un horario específico (por ejemplo, a las 4 am)
        //$schedule->command('notificaciones:crear-certificados')->everyMinute(); //cada minuto
        //$schedule->command('notificaciones:crear-certificados')->dailyAt('02:00'); //ejecutar en un horario específico (por ejemplo, a las 2 am

        // vehiculos 
        $schedule->job(new RevisarVencimientosVehiculosJob)->daily();
    }

    protected $routeMiddleware = [
        // Otros middlewares...
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
