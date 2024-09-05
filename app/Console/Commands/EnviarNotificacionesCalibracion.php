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

        // Buscar los certificados con fechas próximas (30, 15, 7 días antes)
        $certificados = certificados::whereIn('Prox_fecha_calibracion', [
            $hoy->copy()->subDays(30),  // 30 días antes
            $hoy->copy()->subDays(15),  // 15 días antes
            $hoy->copy()->subDays(7),   // 7 días antes
        ])->get();
    
        // Obtener los usuarios que deben recibir la notificación
        $usuarios = User::whereIn('rol', ['Equipos', 'Super Administrador'])->get();
    
        foreach ($certificados as $certificado) {
            $diasRestantes = $hoy->diffInDays($certificado->Prox_fecha_calibracion);
            
            foreach ($usuarios as $usuario) {
                $usuario->notify(new NotificacionesEyC($certificado, $diasRestantes));
            }
        }
    
        $this->info('Notificaciones enviadas correctamente.');
    }
}
