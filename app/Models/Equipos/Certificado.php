<?php

namespace App\Models\Equipos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    protected $table = 'certificados'; // Nombre de la tabla en la base de datos
    use HasFactory;
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
