<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_Almacen extends Model
{
    protected $fillable = [
        'idHistorial_almacen',
        'idAlmacen',
        'idGeneral_EyC',
        'Tipo',
        'Cantidad',
        'Fecha',
        'Tierra_Costafuera',
        'Folio',
    ];
    protected $table = 'historial_almacen';
    protected $primaryKey = 'idHistorial_almacen';
    public $timestamps = false; 

    use HasFactory;

    public function almacen()
    {
        return $this->belongsTo(almacen::class, 'idAlmacen');
    }
}
