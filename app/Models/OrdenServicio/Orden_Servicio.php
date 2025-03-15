<?php

namespace App\Models\OrdenServicio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orden_Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idOrden_Servicio',
        'idClientes',
        'Fecha',
        'Lugar',
        'Contrato',
        'Proyecto_actividad',
        'Material',
        'Plano_isometrico',
    ];

    protected $table = 'Orden_Servicio';
    protected $primaryKey = 'idOrden_Servicio';
    public $timestamps = false;

        // Relación con Orden_Servicio_Prueba
        public function Orden_Servicio_Prueba()
        {
            return $this->hasMany(Orden_Servicio_Prueba::class, 'idOrden_Servicio'); // usa la clave foránea correcta
        }
    
        // Relación con Grupo_Juntas_Detalles_OS
        public function Grupo_Juntas_Detalles_OS()
        {
            return $this->hasMany(Grupo_Juntas_Detalles_OS::class, 'idOrden_Servicio');
        }
    
        // Relación con Firmantes_OS
        public function Firmantes_OS()
        {
            return $this->hasMany(Firmantes_OS::class, 'idOrden_Servicio');
        }
}
