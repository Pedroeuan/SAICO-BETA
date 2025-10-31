<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\solicitud_AD\solicitud_AD;

class Usuario extends Model
{
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'id',
        'name',
        'email',
        'password',
        'rol',
        'Estatus',
    ];
    protected $table = 'users';
    protected $primaryKey = 'id';
    //public $timestamps = false; 

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])->format('d-m-Y');
    }
    public function Solicitudes_AD()
    {
        return $this->belongsToMany(
            Solicitud_AD::class,
            'users_has_solicitud', // tabla pivote
            'users_id',            // FK en pivote hacia User
            'idsolicitud_AD'      // FK en pivote hacia SolicitudAD
        );
    }
}
