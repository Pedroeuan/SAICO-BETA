<?php

namespace App\Models\Firmantes_OC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firmantes_OC extends Model
{
        use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idFirmantes_OC',
        'idOC',
        'Nombre_Cargo',
    ];
    protected $table = 'Firmantes_OC';
    protected $primaryKey = 'idFirmantes_OC';
    public $timestamps = false; 
}
