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
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;

    public function general_eyc()
    {
        return $this->belongsTo(general_eyc::class, 'idGeneral_EyC');
    }
}
