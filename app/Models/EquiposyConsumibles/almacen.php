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
}
