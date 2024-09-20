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
        'Folio',
        'Tipo',
        'Cantidad',
        'Fecha',
        'Tierra_Costafuera',
    ];
    protected $table = 'historial_almacen';
    protected $primaryKey = 'idHistorial_almacen';
    public $timestamps = false; 

    use HasFactory;

    public function almacen()
    {
        return $this->belongsTo(almacen::class, 'idAlmacen');
    }

        public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['Fecha'])->format('d-m-Y');
    }

}
