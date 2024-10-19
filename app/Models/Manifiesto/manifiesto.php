<?php

namespace App\Models\Manifiesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EquiposyConsumibles\devolucion;

class manifiesto extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idManifiestos',
        'idSolicitud',
        'Cliente',
        'Folio',
        'Destino',
        'Trabajo',
        'Puesto',
        'Responsable',
        'Observaciones',
    ];
    protected $table = 'Manifiestos';
    protected $primaryKey = 'idManifiestos';
    public $timestamps = false; 

    // Definir la relación inversa con DetallesSolicitud
    public function detallesSolicitud()
    {
        return $this->belongsTo(detalles_solicitud::class);
    }

    public function devolucion()
    {
        return $this->hasMany(devolucion::class, 'idManifiestos');
    }
    
    use HasFactory;
}
