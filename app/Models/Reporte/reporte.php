<?php

namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class reporte extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'idReportes',
        'idPrueba_Aplica',
        'Detalles_Generales',
        'Datos_Equipo',
        'Estatus',
        'comentarios',
    ];

    protected $table = 'Reportes';
    protected $primaryKey = 'idReportes';
    public $timestamps = false;

    // Relación uno a muchos con Fotos_Reportes
    public function fotos()
    {
        return $this->hasMany(Fotos_Reporte::class, 'idReportes', 'idReportes');
    }

    // Relación uno a muchos con Firmas_Reportes
    public function firmas()
    {
        return $this->hasMany(Firma_Reporte::class, 'idReportes', 'idReportes');
    }

    // Relación uno a muchos con Grupo_Juntas_Detalles_Re
    public function grupoJuntasDetalles()
    {
        return $this->hasMany(Grupo_Juntas_Detalles_Re::class, 'idReportes', 'idReportes');
    }
}
