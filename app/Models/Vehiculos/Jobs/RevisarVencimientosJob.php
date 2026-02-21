<?php
namespace App\Vehiculos\Jobs;

use App\Models\Vehiculos\Vehiculo;
use App\Models\Notificacion\Notificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RevisarVencimientosVehiculosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        //notificacion con 30 dias de anticipacion 
        $hoy =now();
        $en30=now()->addDays(30);
        $vehiculosProximos= Vehiculo::whereBetween('poliza_seguro_vencimiento',[$hoy,$en30])
        ->orWhereBetween('tarjeta_circulacion_vencimiento',[$hoy,$en30])->get();
        foreach ($vehiculosProximos as $vehiculo){
            $mensajeCorto = 'vehiculo próximo a vencer';
            $mensajeLargo = 'El vehículo' . $vehiculo->placa . 'tiene documentación próxima a vencer.';
            $url = '/vehiculos';

            $existe = Notificacion::where('users_id',1)->where('Mensaje_corto',$mensajeCorto)
            ->where('Mensaje_Largo', $mensajeLargo)->first();

            if(!$existe){
                Notificacion::create([
                    'users_id'=>1,
                    'Mensaje_Corto' => $mensajeCorto,
                    'Mensaje_Largo' => $mensajeLargo,
                    'url' => $url,
                    'leida' => 0
                 ]);
            }
        }

        //duplicidad de notificacion 
        $vehiculos = Vehiculo::where('documentacion_estatus', 'vencida')->get();
        foreach ($vehiculos as $vehiculo) {
            $mensajeCorto = 'vehiculo con documentacion vencida';
            $mensajeLargo =  'El vehiculo'. $vehiculo->placa . 'tiene documentación vencida.';
            $url = '/vehiculos';
            $existe=Notificacion::where('users_id',1)->where('Mensaje_corto',$mensajeCorto)->where('Mensaje_largo',$mensajeLargo)->firs();

        if(!$existe){
            Notificacion::create([
                'users_id' => 1, // admin
                'Mensaje_Corto' => 'Vehículo vencido',
                'Mensaje_Largo' => 'El vehículo ',
                'url'=>$url,
                'leida' => 0
                ]);
            }
        }
        }
    }
