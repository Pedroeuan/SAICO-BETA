<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accesorios extends Model
{
    protected $fillable = [
        'idAccesorios',
        'idGeneral_EyC',
        'Proveedor',
    ];
    //protected $table = 'accesorios';
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;
}
