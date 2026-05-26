<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Model;

class HistorialLlanta extends Model
{
    protected $table = 'historial_llantas';

    protected $fillable = [
        'vehiculo_id',
        'posicion',
        'marca',
        'modelo',
        'medida',
        'numero_serie',
        'fecha_instalacion',
        'kilometraje_instalacion',
        'fecha_baja',
        'kilometraje_baja',
        'costo',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_instalacion' => 'date',
        'fecha_baja' => 'date',
        'costo' => 'decimal:2',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function getKmRecorridosAttribute(): ?int
    {
        if (is_null($this->kilometraje_baja)) {
            return null;
        }

        return max(((int) $this->kilometraje_baja) - ((int) $this->kilometraje_instalacion), 0);
    }
}
