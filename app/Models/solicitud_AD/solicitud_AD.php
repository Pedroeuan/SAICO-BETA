<?php

namespace App\Models\solicitud_AD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitud_AD extends Model
{
    use HasFactory;

    protected $table = 'solicitud_AD';
    protected $primaryKey = 'idsolicitud_AD';

    protected $fillable = [
        'fecha',
        'estatus',
        'comentario',
    ];

    public $timestamps = false; // si tu tabla no tiene created_at / updated_at
}

