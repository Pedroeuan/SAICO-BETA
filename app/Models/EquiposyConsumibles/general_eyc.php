<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class general_eyc extends Model
{
    protected $table = 'general_eyc';
    protected $primaryKey = 'idGeneral_EyC';

    public function certificados()
    {
        //dd($this->hasOne(Certificados::class, 'idGeneral_EyC'));
        return $this->hasOne(Certificados::class, 'idGeneral_EyC');
    }

    use HasFactory;
}
