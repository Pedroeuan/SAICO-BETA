<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Notificacion\Notificacion;
use App\Models\EquiposyConsumibles\certificados;
use Illuminate\Support\Facades\Log;

class VerificarCertificados
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $hoy = Carbon::now();

            // Agregar registro para confirmar la ejecución
            Log::info('Middleware VerificarCertificados ejecutado para el usuario: ' . $user->id);


            // Rango de fechas a verificar
            $rangos = [30, 15, 7];

            // Obtener certificados en los rangos de 30, 15 y 7 días ANTES de la fecha actual
            $certificados = certificados::where(function($query) use ($hoy, $rangos) {
                foreach ($rangos as $dias) {
                    // Cambiamos 'addDays' por 'subDays' para restar días en lugar de sumar
                    $query->orWhereBetween('Prox_fecha_calibracion', [$hoy->copy()->subDays($dias), $hoy->copy()->subDays($dias - 1)]);
                }
            })->get();

            // Generar notificaciones para los certificados encontrados
            foreach ($certificados as $certificado) {
                $diasRestantes = $certificado->Prox_fecha_calibracion->diffInDays($hoy);
                $mensajeL = "El No certificado: {$certificado->No_certificado} está próximo a vencer en {$diasRestantes} días.";
                $mensajeC = "Un certificado caduca en {$diasRestantes} días.";

                // Crear registro en la tabla de notificaciones
                Notificacion::create([
                    'users_id' => $user->id,
                    'Mensaje_Corto' => $mensajeC,
                    'Mensaje_Largo' => $mensajeL,
                ]);

                // Opcional: Mostrar una notificación en la interfaz de usuario
                session()->flash('alert', $mensajeC);
            }
        }

        return $next($request);
    }
}