<?php

namespace App\Models\Equipos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'general_eyc'; // Nombre de la tabla en la base de datos
    use HasFactory;

    public function certificado()
    {
        return $this->hasOne(Certificado::class);
    }
}
