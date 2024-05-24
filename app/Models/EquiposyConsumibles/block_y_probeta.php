<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class block_y_probeta extends Model
{
    protected $table = 'block_probeta';
    protected $primaryKey = 'idBlock_probeta';
    public $timestamps = false; 
    use HasFactory;
}
