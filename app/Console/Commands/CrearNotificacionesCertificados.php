<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Notificacion\NotificacionController;

class CrearNotificacionesCertificados extends Command
{
    /**
     * Nombre del comando
     */
    protected $signature = 'notificaciones:crear-certificados';

    /**
     * Descripción
     */
    protected $description = 'Genera las notificaciones y envía correos de certificados';

    /**
     * Ejecutar comando
     */
    public function handle()
    {
        $this->info('Iniciando generación de notificaciones...');

        app(NotificacionController::class)
            ->crearNotificacionesCertificados();

        $this->info('Proceso terminado.');

        return Command::SUCCESS;
    }
}