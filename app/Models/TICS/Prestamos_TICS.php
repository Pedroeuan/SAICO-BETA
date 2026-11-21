<?php

namespace App\Models\TICS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamos_TICS extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idPrestamos_TICS',
        'Folio',
        'Fecha_Solicitud',
        'Condicion_Entrega',
        'Entrega_Solicitud',
        'Recibe_Solicitud',
        'Fecha_Recepcion',
        'Condicion_Recepcion',
        'Entrega_Recepcion',
        'Recibe_Recepcion',
        'Estatus',
    ];
    protected $table = 'prestamos_tics';
    protected $primaryKey = 'idPrestamos_TICS';
    public $timestamps = false;
    
    use HasFactory;

      // Definir la relación de uno a muchos con DetalleSolicitud
    public function detalles_tics()
    {
        return $this->hasMany(Detalles_TICS::class, 'idPrestamos_TICS', 'idPrestamos_TICS');
    }

    public function getFormattedDateAttribute_Solicitud()
    {
        return \Carbon\Carbon::parse($this->attributes['Fecha_Solicitud'])->format('d-m-Y');
    }
    public function getFormattedDateAttribute_Recepcion()
    {
        return \Carbon\Carbon::parse($this->attributes['Fecha_Recepcion'])->format('d-m-Y');
    }
}
