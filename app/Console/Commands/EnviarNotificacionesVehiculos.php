<?php

namespace App\Console\Commands;

use App\Models\Notificacion\Notificacion;
use App\Models\User;
use App\Models\Vehiculos\Vehiculo;
use App\Notifications\NotificacionCertificadoMailable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EnviarNotificacionesVehiculos extends Command
{
    protected $signature = 'notificaciones:vehiculos-vencimientos';

    protected $description = 'Envía avisos de vencimiento de documentación de vehículos';

    /**
     * Días en que se envía un aviso antes del vencimiento.
     */
    private const DIAS_AVISO = [45, 40, 35, 30, 25, 20, 15, 10, 7, 5, 0];

    public function handle(): int
    {
        $destinatarios = User::query()
            ->where('Estatus', 'ALTA')
            ->whereIn('rol', ['Super Administrador', 'Administrador'])
            ->whereNotNull('email')
            ->get();

        if ($destinatarios->isEmpty()) {
            $this->warn('No hay administradores activos con correo para notificar.');

            return self::SUCCESS;
        }

        $documentos = [
            'poliza_seguro_vencimiento' => [
                'nombre' => 'Póliza de seguro',
                'asunto' => 'Póliza',
            ],
            'tarjeta_circulacion_vencimiento' => [
                'nombre' => 'Tarjeta de circulación',
                'asunto' => 'Tarj. circulación',
            ],
            'tenencia_vencimiento' => [
                'nombre' => 'Tenencia',
                'asunto' => 'Tenencia',
            ],
        ];

        foreach (self::DIAS_AVISO as $diasRestantes) {
            $fecha = Carbon::today()->addDays($diasRestantes)->toDateString();

            foreach ($documentos as $columna => $documento) {
                $vehiculos = Vehiculo::query()
                    ->whereDate($columna, $fecha)
                    ->get();

                foreach ($vehiculos as $vehiculo) {
                    $this->notificarVehiculo(
                        $vehiculo,
                        $documento['nombre'],
                        $documento['asunto'],
                        $fecha,
                        $diasRestantes,
                        $destinatarios,
                    );
                }
            }
        }

        return self::SUCCESS;
    }

    private function notificarVehiculo(
        Vehiculo $vehiculo,
        string $documento,
        string $asuntoDocumento,
        string $fecha,
        int $diasRestantes,
        $destinatarios,
    ): void {
        $fechaFormateada = Carbon::parse($fecha)->format('d/m/Y');
        $nombreVehiculo = trim("{$vehiculo->marca} {$vehiculo->modelo}");
        if ($diasRestantes === 0) {
            $mensajeCorto = "{$nombreVehiculo}: {$asuntoDocumento} VENCIDA";
            $mensajeLargo = "La {$documento} del vehículo {$nombreVehiculo} está VENCIDA (Fecha de vencimiento: {$fechaFormateada}).";
            $mensajeLargoEmail = "La {$documento} del vehículo <strong>{$nombreVehiculo}</strong><br>está <span style='color: #E01A22;'>VENCIDA</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>{$fechaFormateada}</span>)";
        } else {
            $mensajeCorto = "{$nombreVehiculo}: {$asuntoDocumento} Prox. a VENCER en {$diasRestantes} días";
            $mensajeLargo = "La {$documento} del vehículo {$nombreVehiculo} está próximo a VENCER en {$diasRestantes} días (Fecha de vencimiento: {$fechaFormateada}).";
            $mensajeLargoEmail = "La {$documento} del vehículo <strong>{$nombreVehiculo}</strong><br>está próximo a <span style='color: #E01A22;'>VENCER en {$diasRestantes} días</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>{$fechaFormateada}</span>)";
        }
        $url = url('/vehiculos');

        foreach ($destinatarios as $usuario) {
            $notificacionExiste = Notificacion::query()
                ->where('users_id', $usuario->id)
                ->where('Mensaje_Corto', $mensajeCorto)
                ->where('Mensaje_Largo', $mensajeLargo)
                ->exists();

            if ($notificacionExiste) {
                continue;
            }

            Notificacion::create([
                'users_id' => $usuario->id,
                'Mensaje_Corto' => $mensajeCorto,
                'Mensaje_Largo' => $mensajeLargo,
                'url' => $url,
                'leida' => false,
            ]);

            try {
                $usuario->notify(new NotificacionCertificadoMailable(
                    $mensajeCorto,
                    $mensajeLargoEmail,
                    $url,
                ));
            } catch (\Throwable $e) {
                Log::error('No se pudo enviar aviso de vencimiento de vehículo.', [
                    'vehiculo_id' => $vehiculo->id,
                    'usuario_id' => $usuario->id,
                    'email' => $usuario->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
