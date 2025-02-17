<?php

namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fotos_Reporte extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idFotos_Reportes',
        'idReportes',
        'Fotos_Reportes',
    ];

    protected $table = 'Fotos_Reportes';
    protected $primaryKey = 'idFotos_Reportes';
    public $timestamps = false;

    // Relación inversa con Reporte
    public function reporte()
    {
        return $this->belongsTo(Reporte::class, 'idReportes', 'idReportes');
    }
}
