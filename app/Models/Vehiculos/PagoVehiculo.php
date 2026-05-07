<?php
// app/Models/Vehiculos/PagoVehiculo.php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Model;

class PagoVehiculo extends Model
{
    protected $table = 'pagos_vehiculo';

    protected $fillable = [
        'vehiculo_id',
        'tipo_pago',
        'anio',
        'monto',
        'fecha_pago',
        'comprobante_url',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }
}
