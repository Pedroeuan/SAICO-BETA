<?php

namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo_Juntas_Detalles_Re extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idGrupo_Juntas_Detalles_Re',
        'idReportes',
        'Juntas_Grupo_Re',
    ];

    protected $table = 'Grupo_Juntas_Detalles_Re';
    protected $primaryKey = 'idGrupo_Juntas_Detalles_Re';
    public $timestamps = false;

    // Relación inversa con Reporte
    public function reporte()
    {
        return $this->belongsTo(Reporte::class, 'idReportes', 'idReportes');
    }
}
