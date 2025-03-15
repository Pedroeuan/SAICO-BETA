<?php

namespace App\Models\OrdenServicio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orden_Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idOrden_Servicio',
        'idClientes',
        'Fecha',
        'Lugar',
        'Contrato',
        'Proyecto_actividad',
        'Material',
        'Plano_isometrico',
    ];

    protected $table = 'Orden_Servicio';
    protected $primaryKey = 'idOrden_Servicio';
    public $timestamps = false;
}
