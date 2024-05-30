<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class block_y_probeta extends Model
{
    protected $table = 'block_probeta';
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;
    public function general_eyc()
    {
        return $this->belongsTo(general_eyc::class, 'idGeneral_EyC');
    }
}
