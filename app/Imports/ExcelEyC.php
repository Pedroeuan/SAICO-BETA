<?php

namespace App\Imports;

use App\Models\MExcelEyC;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\Historial_Almacen;
use App\Models\EquiposyConsumibles\accesorios;
use App\Models\EquiposyConsumibles\block_y_probeta;
use App\Models\EquiposyConsumibles\herramientas;
use App\Models\EquiposyConsumibles\historial_certificado;
use App\Models\EquiposyConsumibles\detalles_kits;
use App\Models\EquiposyConsumibles\kits;

class ExcelEyC implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Asumiendo que tus columnas están en el orden correcto y que coinciden con el archivo Excel
        $generalEyC = general_eyc::create([
            'idGeneral_EyC' => $row['idGeneral_EyC'],
            'Nombre_E_P_BP' => $row['Nombre_E_P_BP'],
            'No_economico' => $row['No_economico'],
            'Serie' => $row['Serie'],
            'Marca' => $row['Marca'],
            'Modelo' => $row['Modelo'],
            'Ubicacion' => $row['Ubicacion'],
            'Almacenamiento' => $row['Almacenamiento'],
            'Comentario' => $row['Comentario'],
            'SAT' => $row['SAT'],
            'BMPRO' => $row['BMPRO'],
            'Factura' => $row['Factura'],
            'Tipo' => $row['Tipo'],
            'Foto' => $row['Foto'],
            'Disponibilidad_Estado' => $row['Disponibilidad_Estado'],
            // Añade más campos si es necesario
        ]);

        // También puedes guardar en la tabla Almacen
        Almacen::create([
            'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
            'Lote' => $row['Lote'],
            'Stock' => $row['Stock'],
        ]);

        // Devuelve una instancia del modelo para realizar operaciones adicionales si es necesario
        return $generalEyC;
    }
}
