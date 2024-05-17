<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class almacen extends Model
{
    protected $fillable = [
        'idGeneral_EyC',
        'No_certificado',
        'Lote',
        'Stock',
    ];
    protected $table = 'almacen';
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;
}
