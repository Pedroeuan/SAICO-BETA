<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ISO extends Model
{
    protected $fillable = [
        'idISO',
        'idGeneral_EyC',
        'NombreISO',
        'Alcance',
        'Frec_Cali_Mant_Prev',
        'Frec_Man_Inter_Time',
        'Frec_Verificacion',
        'Usado',
        'Nuevo',
    ];
    protected $table = 'ISO';
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;

    public function getFormattedDateMAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['Frec_Cali_Mant_Prev'])->format('d-m-Y');
    }
        public function getFormattedDate2MAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['Frec_Man_Inter_Time'])->format('d-m-Y');
    }
}
