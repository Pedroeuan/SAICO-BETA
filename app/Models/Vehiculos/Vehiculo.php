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
}
