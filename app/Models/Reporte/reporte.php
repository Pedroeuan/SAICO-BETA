<?php

namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class reporte extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idReportes',
        'idPrueba_Aplica',
        'Detalles_Generales',
        'Estatus',
    ];

    protected $table = 'Reportes';
    protected $primaryKey = 'idReportes';
    public $timestamps = false;
}
