<?php

namespace App\Models\Solicitudes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Manifiesto\manifiesto;

class detalles_solicitud extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idDetalles_Solicitud',
        'idSolicitud',
        'idGeneral',
        'Cantidad',
        'Unidad',
    ];
    protected $table = 'detalles_solicitud';
    protected $primaryKey = 'idDetalles_Solicitud';
    public $timestamps = false; 

       // Definir la relación inversa de muchos a uno con Solicitud
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'idSolicitud', 'idSolicitud');
    }
    
    // Definir la relación con Manifiestos
    public function manifiesto()
    {
        return $this->hasMany(manifiesto::class, 'idManifiestos');
    }

    use HasFactory;
}
