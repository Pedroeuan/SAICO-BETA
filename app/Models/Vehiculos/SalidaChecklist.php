<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Model;

class SalidaChecklist extends Model
{
    protected $table = 'salidas_checklists';

    protected $fillable = [
        'salida_vehiculo_id',
        'tipo',
        'nivel_gasolina',
        'kilometraje',
        'limpio_exterior',
        'limpio_interior',
        'observaciones',
    ];

    public function salida()
    {
        return $this->belongsTo(SalidaVehiculo::class, 'salida_vehiculo_id');
    }
    public function checklists(){
        return $this->hasMany(SalidaChecklist::class,'salida_vehiculo_id');
    }
}
