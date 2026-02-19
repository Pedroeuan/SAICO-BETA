<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Vehiculo extends Model
{
    //
    protected $fillable = [
        'placa',
        'marca',
        'modelo',
        'anio',
        'estatus',
        'kilometraje_actual',
        'poliza_seguro_pdf',
        'poliza_seguro_vencimiento',
        'tarjeta_circulacion_pdf',
        'tarjeta_circulacion_vencimiento',
        'documentacion_estatus',
    ];
    
    protected $casts = [
        'poliza_seguro_vencimiento' => 'date',
        'tarjeta_circulacion_vencimiento' => 'date',
    ];
    public function salidas()
    {
        return $this->hasMany(SalidaVehiculo::class);
    }

    public function salidaActiva()
    {
        return $this->hasOne(SalidaVehiculo::class)->where('estatus','activo');

    }
    public function getEstadoAttribute()
    {
        if($this->estatus === 'inactivo') {
            return 'inactivo';
            }
            
        return $this->salidaActiva ? 'ocupado' : 'disponible';

    }

    protected static function boot(){
        parent::boot();

        static::saving(function($vehiculo){ 
            //si falta algun pdf
            if(!$vehiculo->poliza_seguro_pdf || !$vehiculo->tarjeta_circulacion_pdf){
                $vehiculo->documentacion_estatus = 'incompleta';
                return;
            }
            //si falta fechas
            if(!$vehiculo->poliza_seguro_vencimiento || !$vehiculo->tarjeta_circulacion_vencimiento){
                $vehiculo->documentacion_estatus = 'incompleta';
                return;
            }
            
            $poliza=Carbon::parse($vehiculo->poliza_seguro_vencimiento);
            $tarjeta=Carbon::parse($vehiculo->tarjeta_circulacion_vencimiento);

            // Si la póliza está vencida, eliminar el PDF
            if($poliza->lt(now())){
                if($vehiculo->poliza_seguro_pdf && Storage::disk('public')->exists($vehiculo->poliza_seguro_pdf)){
                    Storage::disk('public')->delete($vehiculo->poliza_seguro_pdf);
                }
                $vehiculo->poliza_seguro_pdf = null;
            }

            // Si la tarjeta está vencida, eliminar el PDF
            if($tarjeta->lt(now())){
                if($vehiculo->tarjeta_circulacion_pdf && Storage::disk('public')->exists($vehiculo->tarjeta_circulacion_pdf)){
                    Storage::disk('public')->delete($vehiculo->tarjeta_circulacion_pdf);
                }
                $vehiculo->tarjeta_circulacion_pdf = null;
            }

            if($poliza->lt(now())|| $tarjeta->lt(now())){
                $vehiculo->documentacion_estatus='vencida';
            }else{
                $vehiculo->documentacion_estatus='completa';
            }
        });
    }
}
