<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class almacen extends Model
{
    protected $fillable = [
        'idAlmacen',
        'idGeneral_EyC',
        'No_certificado',
        'Lote',
        'Stock',
    ];
    protected $table = 'almacen';
    protected $primaryKey = 'idAlmacen';
    public $timestamps = false; 

    use HasFactory;

    public function general_eyc()
    {
        return $this->belongsTo(general_eyc::class, 'idGeneral_EyC');
    }

        public function Historial_almacen()
    {
        return $this->hasMany(Historial_Almacen::class, 'idAlmacen');
    }
    
}
