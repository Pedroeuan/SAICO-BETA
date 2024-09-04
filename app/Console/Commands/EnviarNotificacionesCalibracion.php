<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\User;
use App\Models\Admin\Usuario;
use App\Notifications\NotificacionesEyC;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EnviarNotificacionesCalibracion extends Command
{
    protected $signature = 'notificaciones:calibracion';
    protected $description = 'Enviar notificaciones de calibración próximas a los usuarios correspondientes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $hoy = Carbon::today();

        // Buscar los certificados con fechas próximas (30, 15, 7 días)
        $certificados = certificados::whereIn('Prox_fecha_calibracion', [
            $hoy->copy()->addDays(30),
            $hoy->copy()->addDays(15),
            $hoy->copy()->addDays(7),
        ])->get();

        // Obtener los usuarios que deben recibir la notificación
        $usuarios = User::whereIn('rol', ['Equipos', 'Super Administrador'])->get();

        foreach ($certificados as $certificado) {
            $diasRestantes = $hoy->diffInDays($certificado->prox_fecha_calibracion);
            
            foreach ($usuarios as $usuario) {
                $usuario->notify(new NotificacionesEyC($certificado, $diasRestantes));
                //dd('Notificación enviada a: ' . $usuario->email);
            }
        }
        //dd($usuarios);

        $this->info('Notificaciones enviadas correctamente.');
    }
}
