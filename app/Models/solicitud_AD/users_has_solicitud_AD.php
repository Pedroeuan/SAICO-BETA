<?php

namespace App\Models\solicitud_AD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\Usuario;
use App\Models\solicitud_AD\solicitud_AD;

class Users_Has_solicitud_AD extends Model
{
    use HasFactory;

    protected $table = 'users_has_solicitud_AD';
    //protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'idsolicitud_AD',
    ];

    // Relación hacia el usuario
    public function Users()
    {
        // belongsTo porque este registro "pertenece a" un usuario
        return $this->belongsTo(Usuario::class, 'users_id', 'id');
    }

    // Relación hacia la solicitud
    public function Solicitud_AD()
    {
        // belongsTo porque este registro "pertenece a" una solicitud
        return $this->belongsTo(Solicitud_AD::class, 'idsolicitud_AD', 'idsolicitud_AD');
    }

}
