<?php

namespace App\Imports;

use App\Models\ImExcelEyC;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\certificados;

class ImporExcelEyC implements ToModel, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Validación: si una fila está vacía, no la procesa
        if (empty($row['idgeneral_eyc']) || empty($row['nombre_e_p_bp'])) {
            return null; // Evita crear un registro si no hay datos válidos
        }
        //dd($row);
         // Asumiendo que tus columnas están en el orden correcto y que coinciden con el archivo Excel
        $generalEyC = general_eyc::create([
            'idGeneral_EyC' => $row['idgeneral_eyc'], // Clave en minúsculas
            'Nombre_E_P_BP' => $row['nombre_e_p_bp'],
            'No_economico' => $row['no_economico'],
            'Serie' => $row['serie'],
            'Marca' => $row['marca'],
            'Modelo' => $row['modelo'],
            'Ubicacion' => $row['ubicacion'],
            'Almacenamiento' => $row['almacenamiento'], 
            'Comentario' => $row['comentario'],
            'SAT' => $row['sat'],
            'BMPRO' => $row['bmpro'],
            'Factura' => $row['factura'],
            'Tipo' => $row['tipo'],
            'Foto' => $row['foto'],
            'Disponibilidad_Estado' => $row['disponibilidad_estado'],
        ]);

        // También puedes guardar en la tabla Almacen
        almacen::create([
            'idAlmacen' => $row['idalmacen'], // Clave en minúsculas
            'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
            'Lote' => $row['lote'],
            'Stock' => $row['stock'],
        ]);

        certificados::create([
            'idCertificados' => $row['idcertificados'], // Clave en minúsculas
            'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
            'No_certificado' => $row['no_certificado'],
            'Certificado_Actual' => $row['certificado_actual'],
            'Fecha_calibracion' => $row['fecha_calibracion'],
            'Prox_fecha_calibracion' => $row['prox_fecha_calibracion'],
        ]);

        // Devuelve una instancia del modelo para realizar operaciones adicionales si es necesario
        return $generalEyC;

    }
}
