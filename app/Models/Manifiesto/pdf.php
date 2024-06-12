<?php

namespace App\Models\Manifiesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pdf extends Model
{
    protected $table = 'datos'; // Si la tabla no sigue las convenciones de nombres de Laravel

    public function Detalles_manifiestos()
    {
        return $this->hasOne(equipos::class, 'idGeneral_EyC');
    }

    public function Manifiestos()
    {
        return $this->hasOne(equipos::class, 'idGeneral_EyC');
    }
    public function Detalles_Solicitud()
    {
        return $this->hasOne(equipos::class, 'idGeneral_EyC');
    }
    public function solicitud()
    {
        return $this->hasOne(equipos::class, 'idGeneral_EyC');
    }

}
