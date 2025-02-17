<?php

namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Firma_Reporte extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idFirmas_Reportes',
        'idReportes',
        'Firmas',
    ];

    protected $table = 'Firmas_Reportes';
    protected $primaryKey = 'idFirmas_Reportes';
    public $timestamps = false;

    // Relación inversa con Reporte
    public function reporte()
    {
        return $this->belongsTo(Reporte::class, 'idReportes', 'idReportes');
    }
}
