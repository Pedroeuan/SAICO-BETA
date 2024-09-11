<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class devolucion extends Model
{
    protected $fillable = [
        'idDevoluciones',
        'idGeneral_EyC',
        'idAlmacen',
        'idHidtorial_almacen',
        'Estado_Cantidad',
    ];
    protected $table = 'Devoluciones';
    protected $primaryKey = 'idDevoluciones';
    public $timestamps = false; 
    
    use HasFactory;
}
