<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historial_certificado extends Model
{
    protected $fillable = [
        'idHistorial_certificados',
        'idGeneral_EyC',
        'Certificado_Caducado',
        'Tipo',
        'Ultima_Fecha_calibracion',
    ];
    protected $table = 'historial_certificados';
    protected $primaryKey = 'idHistorial_certificados';
    public $timestamps = false; 
    use HasFactory;
}
