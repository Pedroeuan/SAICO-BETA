<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consumibles extends Model
{
    protected $fillable = [
        'idConsumibles',
        'idGeneral_EyC',
        'Proveedor',
        'Tipo',
    ];
    protected $primaryKey = 'idConsumibles';
    public $timestamps = false; 
    use HasFactory;
}
