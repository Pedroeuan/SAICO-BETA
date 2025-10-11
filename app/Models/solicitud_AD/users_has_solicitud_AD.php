<?php

namespace App\Models\solicitud_AD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_Has_solicitud_AD extends Model
{
    use HasFactory;

    protected $table = 'users_has_solicitud_AD';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'idsolicitud_AD',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud_AD::class, 'idsolicitud_AD');
    }
}
