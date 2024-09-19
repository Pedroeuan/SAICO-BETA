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
    ];
    protected $table = 'consumibles';
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;
}
