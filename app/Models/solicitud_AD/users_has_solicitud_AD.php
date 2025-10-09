<?php

namespace App\Models\solicitud_AD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users_has_solicitud_AD extends Model
{
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'id',
        'idsolicitud_AD',
    ];
    //protected $table = 'users_has_solicitud_AD';
    //protected $primaryKey = 'id';

    public $timestamps = false; 

}
