<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Model;

class SalidaEventoFlujo extends Model
{
    protected $table = 'salidas_eventos_flujo';

    protected $fillable = [
        'salida_vehiculo_id',
        'user_id',
        'rol',
        'evento',
        'paso',
        'pantalla',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
