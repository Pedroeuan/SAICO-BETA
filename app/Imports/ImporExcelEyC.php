<?php

namespace App\Imports;

use App\Models\ImExcelEyC;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; // Importa esta clase para convertir fechas de Excel
use Illuminate\Support\Facades\Log;

use App\Models\TICS\TICS;
use App\Models\EquiposyConsumibles\ISO;
use App\Models\EquiposyConsumibles\clasificacion;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\accesorios;
use App\Models\EquiposyConsumibles\block_y_probeta;
use App\Models\EquiposyConsumibles\herramientas;
use App\Models\EquiposyConsumibles\Historial_Almacen;


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
        // Validar si la fila contiene un idGeneral_EyC
        if (empty($row['no_economico'])) { 
            return null; // Evita procesar filas sin un idGeneral_EyC
        }

        // Buscar si ya existe un registro de general_eyc para evitar duplicados
        $generalEyC = general_eyc::updateOrCreate([
            //'idGeneral_EyC' => $row['idgeneral_eyc']], // Condición para encontrar el registro
            'No_economico' => $row['no_economico']], // Condición para encontrar el registro
            [
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
            'Tipo' => $row['tipog'],
            'Foto' => $row['foto'],
            'Disponibilidad_Estado' => $row['disponibilidad_estado'],
        ]);

        $almacenRow = null;

        if (!empty($row['idalmacen']) && isset($row['stock'])) {
            $almacenRow = almacen::updateOrCreate(
                ['idAlmacen' => $row['idalmacen'], 
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC],
                ['Lote' => $row['lote'],
                'Stock' => $row['stock'],
                'Unidad' => $row['unidad']],
            );
        }

            if (!empty($row['idhistorial_almacen']) && $almacenRow) {
            $fecha = is_numeric($row['fecha']) 
                ? Date::excelToDateTimeObject($row['fecha'])->format('Y-m-d')
                : $row['fecha'];

            Historial_Almacen::updateOrCreate(
                ['idHistorial_almacen' => $row['idhistorial_almacen']],
                [
                    'idAlmacen' => $almacenRow->idAlmacen,
                    'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
                    'Folio' => $row['folio'],
                    'Tipo' => $row['tipoh'],
                    'Cantidad' => $row['cantidad'],
                    'Fecha' => $fecha,
                    'Tierra_Costafuera' => $row['tierra_costafuera'],
                ]
            );
        }

        // Comprobamos si hay datos para almacenar en la tabla Certificados
        if (!empty($row['idcertificados'])) {
            $fechaCalibracion = is_numeric($row['fecha_calibracion']) 
                ? Date::excelToDateTimeObject($row['fecha_calibracion'])->format('Y-m-d')
                : $row['fecha_calibracion'];

                $proxFechaCalibracion = is_numeric($row['prox_fecha_calibracion']) 
                ? Date::excelToDateTimeObject($row['prox_fecha_calibracion'])->format('Y-m-d')
                : $row['prox_fecha_calibracion'];

                $FechaVerificacion = is_numeric($row['Fecha_verificacion']) 
                ? Date::excelToDateTimeObject($row['Fecha_verificacion'])->format('Y-m-d')
                : $row['Fecha_verificacion'];

                $proxFechaVerificacion = is_numeric($row['Prox_fecha_verificacion']) 
                ? Date::excelToDateTimeObject($row['Prox_fecha_verificacion'])->format('Y-m-d')
                : $row['Prox_fecha_verificacion'];

                $FechaMantenimiento = is_numeric($row['Fecha_mantenimiento']) 
                ? Date::excelToDateTimeObject($row['Fecha_mantenimiento'])->format('Y-m-d')
                : $row['Fecha_mantenimiento'];

                $proxFechaCproxFechaMantenimientoalibracion = is_numeric($row['Prox_fecha_mantenimiento']) 
                ? Date::excelToDateTimeObject($row['Prox_fecha_mantenimiento'])->format('Y-m-d')
                : $row['Prox_fecha_mantenimiento'];

            certificados::updateOrCreate([
                'idCertificados' => $row['idcertificados'],
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
                'No_certificado' => $row['no_certificado'],
                'Certificado_Actual' => $row['certificado_actual'],
                'Fecha_calibracion' => $fechaCalibracion,
                'Prox_fecha_calibracion' => $proxFechaCalibracion,
                
                'Fecha_verificacion' => $FechaVerificacion,
                'Prox_fecha_verificacion' => $proxFechaVerificacion,
                'Fecha_mantenimiento' => $FechaMantenimiento,
                'Prox_fecha_mantenimiento' => $proxFechaMantenimiento,
            ]);
        }

        // Comprobamos si hay datos para almacenar en la tabla Equipos
        if (!empty($row['idequipos'])) {
            equipos::updateOrCreate([
                'idEquipos' => $row['idequipos'],
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
                'Num_reporte' => $row['num_reporte'],
            ]);
        }

        // Comprobamos si hay datos para almacenar en la tabla Consumibles
        if (!empty($row['idconsumibles'])) {
            consumibles::updateOrCreate([
                'idConsumibles' => $row['idconsumibles'],
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
                'Proveedor' => $row['proveedorc'],
            ]);
        }

        // Comprobamos si hay datos para almacenar en la tabla Accesorios
        if (!empty($row['idaccesorios'])) {
            accesorios::updateOrCreate([
                'idAccesorios' => $row['idaccesorios'],
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
                'Proveedor' => $row['proveedora'],
            ]);
        }

        // Comprobamos si hay datos para almacenar en la tabla Block y Probeta
        if (!empty($row['idblock_probeta'])) {
            block_y_probeta::updateOrCreate([
                'idBlock_probeta' => $row['idblock_probeta'],
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
                'Plano' => $row['planob'],
            ]);
        }

        // Comprobamos si hay datos para almacenar en la tabla Herramientas
        if (!empty($row['idequipos_tools_complementos'])) {
            herramientas::updateOrCreate([
                'idEquipos_Tools_Complementos' => $row['idequipos_tools_complementos'],
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
                'Garantia' => $row['garantia'],
                'Plano' => $row['planoh'],
            ]);
        }

        // Comprobamos si hay datos para almacenar en la tabla TICS
        if (!empty($row['idTICS'])) {
            TICS::updateOrCreate([
                'idTICS' => $row['idTICS'],
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
            ]);
        }

        // Comprobamos si hay datos para almacenar en la tabla Clasificacion
        if (!empty($row['idClasificacion'])) {
            clasificacion::updateOrCreate([
                'idClasificacion' => $row['idClasificacion'],
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
                'NombreC' => $row['nombrec'],
            ]);
        }

        // Comprobamos si hay datos para almacenar en la tabla Clasificacion
        if (!empty($row['idISO'])) {
            ISO::updateOrCreate([
                'idISO' => $row['idISO'],
                'idGeneral_EyC' => $generalEyC->idGeneral_EyC,
                'NombreISO' => $row['NombreISO'],
                'Alcance' => $row['alcance'],
                'Frec_Cali_Mant_Prev' => $row['Frec_Cali_Mant_Prev'],
                'Frec_Man_Inter_Time' => $row['Frec_Man_Inter_Time'],
                'Frec_Verificacion' => $row['Frec_Verificacion'],
                'Usado' => $row['usado'],
                'Nuevo' => $row['nuevo'],
            ]);
        }
        // Retorna el registro general_eyc, aunque no es estrictamente necesario si no se necesita más adelante.
        return $generalEyC;
    }

}
