<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class certificados extends Model
{
    protected $fillable = [
        'idcertificados',
        'idGeneral_EyC',
        'No_certificado',
        'Certificado_Actual',
        'Fecha_calibracion',
        'Prox_fecha_calibracion'
    ];
    protected $primaryKey = 'idcertificados';
    public $timestamps = false; 
    use HasFactory;

}
