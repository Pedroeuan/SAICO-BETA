<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalles_kits extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idDetalles_Kits',
        'idGeneral_EyC',
        'idKits',
        'Cantidad',
        'Unidad',
    ];
    protected $table = 'detalles_kits';
    protected $primaryKey = 'idDetalles_Kits';
    public $timestamps = false; 

       // Definir la relación inversa de muchos a uno con Solicitud
    public function solicitud()
    {
        return $this->belongsTo(kits::class, 'idKits', 'idKits');
    }
    
    use HasFactory;
}
