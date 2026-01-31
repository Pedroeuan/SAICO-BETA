<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    //
    protected $fillable = [
        'placa',
        'marca',
        'modelo',
        'anio',
        'placa',
    ];
    public function salida()
    {
        return $this->hasMany(SalidaVehiculo::class);
    }
}
