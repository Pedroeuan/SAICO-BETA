<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class general_eyc extends Model
{
    protected $table = 'general_eyc';
    protected $primaryKey = 'idGeneral_EyC';
    /*En Laravel, por defecto, Eloquent espera que las tablas de la base de datos 
    tengan las columnas created_at y updated_at para registrar la fecha y hora de creación 
    y actualización de los registros. Si estás utilizando migraciones para crear tus tablas, 
    es posible que hayas olvidado agregar estas columnas o no hayas ejecutado las migraciones correctamente.
    Para resolver este error, puedes hacer lo siguiente:
    Si no necesitas las columnas created_at y updated_at, puedes desactivar su uso agregando la propiedad 
    $timestamps a tu modelo y estableciéndola en false: */
    public $timestamps = false; 


    public function certificados()
    {
        //dd($this->hasOne(Certificados::class, 'idGeneral_EyC'));
        return $this->hasOne(Certificados::class, 'idGeneral_EyC');
    }

    use HasFactory;
}
