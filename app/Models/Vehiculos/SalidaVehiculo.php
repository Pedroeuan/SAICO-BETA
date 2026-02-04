<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Model;

class SalidaVehiculo extends Model
{
    //
    protected $table = 'salidas_vehiculo';

    protected $fillable =[
        'vehiculo_id',
        'chofer_id',
        'solicitado_por',
        'fecha_salida',
        'fecha_regreso',
        'motivo',
        'estatus',
    ];
    public function vehiculo(){
        return $this->belongsTo(Vehiculo::class);
    }
    public function chofer(){
        return $this->belongsTo(User::class,'chofer_id');
    }
    public function solicitante(){
        return $this->belongsTo(User::class,'solicitado_por');//f
    }
}
