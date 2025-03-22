<?php

namespace App\Models\OrdenServicio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orden_Servicio_Prueba extends Model
{
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idOrden_Servicio',
        'idPrueba_Aplica',
    ];

    protected $table = 'Orden_Servicio_Prueba';
    //protected $primaryKey = 'idOrden_Servicio';
    public $timestamps = false;

    public function ordenServicio()
    {
        return $this->belongsTo(Orden_Servicio::class, 'orden_servicio_id');
    }
}
