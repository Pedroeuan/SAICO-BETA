<?php

namespace App\Models\solicitud_AD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Usuario;

class solicitud_AD extends Model
{
    use HasFactory;

    protected $table = 'solicitud_AD';
    protected $primaryKey = 'idsolicitud_AD';

    protected $fillable = [
        'fecha',
        'estatus',
        'comentario',
        'Tema',
    ];

    public $timestamps = false; // si tu tabla no tiene created_at / updated_at
    public function users()
    {
        return $this->belongsToMany(
            Usuario::class,
            'users_has_solicitud',
            'idsolicitud_AD',  // FK en pivote hacia SolicitudAD
            'users_id'          // FK en pivote hacia User
        );
    }
        public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['fecha'])->format('d-m-Y');
    }
}

