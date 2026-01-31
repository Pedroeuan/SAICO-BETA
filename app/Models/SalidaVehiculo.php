<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalidaVehiculo extends Model
{
    //
    protected $table = 'salidas_vehiculos';

    protected $fillable =[
        'vehiculo_id',
        'chofer_id',
        'solicitado_por',
        'fecha_salida',
        'fecha_regreso',
        'regreso',
        'estatus',
    ];
    public function vehiculo(){
        return $this->belongsTo(Vehiculo::class);
    }
    public function chofer(){
        return $this->belongsTo(User::class,'chofer_id');
    }
    public function solicitado_por(){
        return $this->belongsTo(User::class,'solicitado_por');
    }
}
