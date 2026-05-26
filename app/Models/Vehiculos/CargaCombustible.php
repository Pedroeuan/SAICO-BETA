<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Model;

class CargaCombustible extends Model
{
    protected $table = 'cargas_combustible';

    protected $fillable = [
        'vehiculo_id',
        'fecha_carga',
        'kilometraje',
        'litros',
        'costo_total',
        'precio_por_litro',
        'tipo_combustible',
        'proveedor',
        'tanque_lleno',
        'ticket_url',
        'observaciones',
    ];

    protected $casts = [
        'fecha_carga' => 'date',
        'litros' => 'decimal:3',
        'costo_total' => 'decimal:2',
        'precio_por_litro' => 'decimal:4',
        'tanque_lleno' => 'boolean',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }
}
