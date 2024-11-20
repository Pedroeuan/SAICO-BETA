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
        return $this->belongsTo(certificados::class, 'idGeneral_EyC');
    }

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['Ultima_Fecha_calibracion'])->format('d-m-Y');
    }

    public function general_eyc()
    {
        return $this->certificado->belongsTo(general_eyc::class, 'idGeneral_EyC');
    }

}
