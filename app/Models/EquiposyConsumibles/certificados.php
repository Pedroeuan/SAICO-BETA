<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class certificados extends Model
{
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;

}
