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
        'idFirmas_Reportes',
        'idGrupo_Juntas_Detalles_Re',
        'idReportes_Pruebas',
        'Contrato',
        'Estatus',
    ];

    protected $table = 'Reportes';
    protected $primaryKey = 'idReportes';
    public $timestamps = false;
}
