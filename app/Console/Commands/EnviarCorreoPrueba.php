<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\NotificacionCertificadoMailable;
use App\Models\User;

class EnviarCorreoPrueba extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'correo:enviar-prueba {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar correo de prueba a la dirección indicada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');

        if (!$email) {
            $this->error('Falta la opción --email');
            return 1;
        }

        $this->info('Preparando envío de prueba a: ' . $email);

        // Intentar obtener usuario existente
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Crear instancia temporal del modelo User (no guardada)
            $user = new User();
            $user->email = $email;
            $user->name = 'Prueba Remitente';
        }

        $asunto = 'Prueba de notificación - ' . date('Y-m-d H:i:s');
        $mensaje = 'Este es un correo de prueba enviado desde la aplicación para verificar la entrega.';
        $url = url('/');

        try {
            $user->notify(new NotificacionCertificadoMailable($asunto, $mensaje, $url));
            $this->info('Notificación enviada (intento realizado). Revisa los logs para más detalles.');
        } catch (\Throwable $e) {
            $this->error('Error enviando correo: ' . $e->getMessage());
            $this->line($e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}
