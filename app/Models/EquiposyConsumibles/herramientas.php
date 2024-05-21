<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class herramientas extends Model
{
    protected $fillable = [
        'idGeneral_EyC',
        'Garantia',
    ];
    protected $table = 'equipos_tools_complementos';
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;
}
