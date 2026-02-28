<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $table = 'mantenimientos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'vehiculo_id',
        'tipo',
        'descripcion',
        'fecha',
        'kilometraje',
        'costo',
        'proxima_revision_fecha',
        'proxima_revision_km',
        'factura_pdf',
        'factura_numero',
        'factura_fecha',
        'factura_monto',
    ];

    protected $casts = [
        'fecha' => 'date',
        'proxima_revision_fecha' => 'date',
        'factura_fecha' => 'date',
        'costo' => 'decimal:2',
        'factura_monto' => 'decimal:2',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }
}
