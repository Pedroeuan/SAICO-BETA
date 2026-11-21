<?php

namespace App\Models\TICS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\EquiposyConsumibles\general_eyc;

class Detalles_TICS extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idDetalles_TICS',
        'idPrestamos_TICS',
        'idGeneral_EyC',
        'Cantidad',
        'Unidad',
    ];
    protected $table = 'detalles_tics';
    protected $primaryKey = 'idDetalles_TICS';
    public $timestamps = false; 

       // Definir la relación inversa de muchos a uno con Solicitud
    public function solicitud()
    {
        return $this->belongsTo(Prestamos_TICS::class, 'idPrestamos_TICS', 'idPrestamos_TICS');
    }

    public function generalEyc()
    {
        return $this->belongsTo(general_eyc::class, 'idGeneral_EyC', 'idGeneral_EyC');
    }
    use HasFactory;
}
