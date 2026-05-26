<?php

namespace App\Models\Vehiculos;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EncuestaSatisfaccionVehicular extends Model
{
    protected $table = 'encuestas_satisfaccion_vehicular';

    protected $fillable = [
        'salida_vehiculo_id',
        'vehiculo_id',
        'user_id',
        'origen_respuesta',
        'calificacion_servicio',
        'calificacion_estado_unidad',
        'calificacion_tiempo_respuesta',
        'nps',
        'sentimiento',
        'comentario',
        'fecha_encuesta',
        'respondida_en',
    ];

    protected $casts = [
        'fecha_encuesta' => 'date',
        'respondida_en' => 'datetime',
    ];

    public function salidaVehiculo()
    {
        return $this->belongsTo(SalidaVehiculo::class, 'salida_vehiculo_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPromedioGeneralAttribute(): float
    {
        return round((
            (float) $this->calificacion_servicio +
            (float) $this->calificacion_estado_unidad +
            (float) $this->calificacion_tiempo_respuesta
        ) / 3, 2);
    }

    public function getCategoriaNpsAttribute(): string
    {
        $nps = (int) ($this->nps ?? 0);

        if ($nps >= 9) {
            return 'promotor';
        }

        if ($nps >= 7) {
            return 'pasivo';
        }

        return 'detractor';
    }
}
