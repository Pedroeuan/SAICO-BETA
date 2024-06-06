<?php

namespace App\Models\Solicitudes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitudes extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idSolicitud',
        'tecnico',
        'Fecha',
        'Estatus',
    ];
    protected $table = 'solicitud';
    protected $primaryKey = 'idSolicitud';
    public $timestamps = false; 
    use HasFactory;

      // Definir la relación de uno a muchos con DetalleSolicitud
    public function detalles_solicitud()
    {
        return $this->hasMany(detalles_solicitud::class, 'idSolicitud');
    }
}
