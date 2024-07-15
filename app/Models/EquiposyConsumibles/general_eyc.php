<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class general_eyc extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idGeneral_EyC',
        'Nombre_E_P_BP',
        'No_economico',
        'Serie',
        'Marca',
        'Modelo',
        'Ubicacion',
        'Almacenamiento',
        'Comentario',
        'SAT',
        'BMPRO',
        'Destino',
        'Tipo',
        'Foto',
        'Disponibilidad_Estado',
    ];
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

    public function equipos()
    {
        return $this->hasOne(equipos::class, 'idGeneral_EyC');
    }

    public function certificados()
    {
        return $this->hasOne(certificados::class, 'idGeneral_EyC');
    }

    public function consumibles()
    {
        return $this->hasOne(consumibles::class, 'idGeneral_EyC');
    }

    public function accesorios()
    {
        return $this->hasOne(accesorios::class, 'idGeneral_EyC');
    }

    public function blocks()
    {
        return $this->hasOne(block_y_probeta::class, 'idGeneral_EyC');
    }

    public function herramientas()
    {
        return $this->hasOne(herramientas::class, 'idGeneral_EyC');
    }

    public function historial_certificado()
    {
        return $this->hasOne(historial_certificado::class, 'idGeneral_EyC');
    }

    public function almacen()
    {
        return $this->hasOne(almacen::class, 'idGeneral_EyC');
    }

    public function historialAlmacen()
    {
        return $this->hasManyThrough(Historial_Almacen::class, Almacen::class, 'idGeneral_EyC', 'idAlmacen');
    }


    use HasFactory;
}
