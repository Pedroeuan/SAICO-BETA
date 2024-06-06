<?php

namespace App\Models\Solicitudes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    protected $primaryKey = 'idSolicitud';
    public $timestamps = false; 

       // Definir la relación inversa de muchos a uno con Solicitud
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }
    
    use HasFactory;
}
