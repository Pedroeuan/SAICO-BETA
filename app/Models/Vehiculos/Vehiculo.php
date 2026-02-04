<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    //
    protected $fillable = [
        'placa',
        'marca',
        'modelo',
        'anio',
        'estatus',
    ];
    public function salida()
    {
        return $this->hasMany(SalidaVehiculo::class);
    }
}
