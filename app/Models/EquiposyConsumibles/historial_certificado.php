<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historial_certificado extends Model
{
    protected $fillable = [
        'idHistorial_certificados',
        'idCertificados',
        'idGeneral_EyC',
        'Certificado_Caducado',
        'Tipo',
        'Ultima_Fecha_calibracion',
    ];
    protected $table = 'historial_certificados';
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;
    
    public function certificado()
    {
        return $this->belongsTo(Certificado::class, 'idCertificados');
    }

}
