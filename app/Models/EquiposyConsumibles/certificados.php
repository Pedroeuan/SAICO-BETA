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
    protected $table = 'certificados';
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;

    /*En el modelo certificados, define una función para la relación. 
    Si la relación es uno a muchos (un certificado puede tener muchos historiales), 
    puedes definirla de la siguiente manera:
 */
    public function historial_certificado()
    {
        return $this->hasMany(historial_certificado::class, 'idCertificados');
    }

}
