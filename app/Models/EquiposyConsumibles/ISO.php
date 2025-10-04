<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ISO extends Model
{
    protected $fillable = [
        'idISO',
        'idGeneral_EyC',
        'NombreISO',
        'Alcance',
        'Frec_Cali_Mant_Prev',
        'Frec_Man_Inter_Time',
        'Frec_Verificacion',
    ];
    protected $table = 'ISO';
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;
}
