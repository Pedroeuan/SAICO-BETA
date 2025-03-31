<?php

namespace App\Http\Controllers\Reporte\INS;

use App\Http\Controllers\Controller;

use App\Models\OC\OC;
use App\Models\Prueba\prueba;
use App\Models\Formato\formato;
use App\Models\Reporte\reporte;
use App\Models\Clientes\clientes;
use App\Models\detallesOC\detallesOC;
use App\Models\Manifiesto\manifiesto;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Lineal_Ideal\Lineal_Ideal;
use App\Models\Norma_Codigo\norma_codigo;
use App\Models\OrdenServicio\Firmantes_OS;
use App\Models\PruebaAplica\Prueba_Aplica;
use App\Models\OrdenServicio\Orden_Servicio;
use App\Models\EquiposyConsumibles\devolucion;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;
use App\Models\OrdenServicio\Orden_Servicio_Prueba;
use App\Models\OrdenServicio\Grupo_Juntas_Detalles_OS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/*PDF */
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Barryvdh\DomPDF\Facade\Pdf;

class FOR_02_PRO_INS_10Controller extends Controller
{

    public function OS_OC($datosParaCrearOS_OC)
    {
        $idPrueba_Aplica = $datosParaCrearOS_OC['idPrueba_Aplica'];
        $Cliente = $datosParaCrearOS_OC['Cliente'];
        $Lugar = $datosParaCrearOS_OC['Lugar'];
        $Contrato= $datosParaCrearOS_OC['Contrato'];
        //$Contrato = trim(strtoupper($datosParaCrearOS_OC['Contrato']));
        $Proyecto = $datosParaCrearOS_OC['Proyecto'];
        $Material = $datosParaCrearOS_OC['Material'];
        $Isometrico_Plano = $datosParaCrearOS_OC['Isometrico_Plano'];
        $Pieza = $datosParaCrearOS_OC['Pieza'];
        $Norma_cod_Criterio_Eva = $datosParaCrearOS_OC['Norma_cod_Criterio_Eva'];
        $ResultadosJuntas = $datosParaCrearOS_OC['ResultadosJuntas'];
        $idSolicitud = $datosParaCrearOS_OC['idSolicitud'];
        $idReportes = $datosParaCrearOS_OC['idReportes'];

        $Orden_Servicio = new Orden_Servicio;
        $Orden_Servicio_Prueba = new Orden_Servicio_Prueba;
        $Firmantes_OS = new Firmantes_OS;
        $Grupo_Juntas_Detalles_OS = new Grupo_Juntas_Detalles_OS;
        $OC = new OC;
        $Detalles_OC = new detallesOC;
        $Lineal_Ideal = new Lineal_Ideal;

        $BusquedaCliente = clientes::where('Cliente', 'like', '%' . $Cliente . '%')->first();

        if ($BusquedaCliente) {
            $idCliente = $BusquedaCliente->idCliente; // O el campo que sea clave primaria
            //$nombreReal = $BusquedaCliente->Cliente; // Nombre exacto encontrado
            $BusquedaContratoOS = Orden_Servicio::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOS)
            {
                $idOrdenServicio = $BusquedaContratoOS->idOrden_Servicio;
            } else{
            $Orden_Servicio->idClientes = $idCliente;
            $Orden_Servicio->Fecha = '2001/01/01';
            $Orden_Servicio->Lugar = $Lugar;
            $Orden_Servicio->Contrato = $Contrato;
            $Orden_Servicio->Proyecto_actividad = $Proyecto;
            $Orden_Servicio->Material = $Material;
            $Orden_Servicio->Plano_isometrico = $Isometrico_Plano;
            $Orden_Servicio->save();

            // Obtén el ID del registro recién creado
            $idOrdenServicio = $Orden_Servicio->idOrden_Servicio;

            $Orden_Servicio_Prueba->idOrden_Servicio = $idOrdenServicio;
            $Orden_Servicio_Prueba->idPrueba_Aplica = $idPrueba_Aplica;
            $Orden_Servicio_Prueba->save();

            $Firmantes_OS->idOrden_Servicio = $idOrdenServicio;
            $Firmantes_OS->Nombre_Cargo = '[]';
            $Firmantes_OS->save();

            $Grupo_Juntas_Detalles_OS->idOrden_Servicio = $idOrdenServicio;
            $Grupo_Juntas_Detalles_OS->Juntas_grupo = $ResultadosJuntas;
            $Grupo_Juntas_Detalles_OS->save();

            }

            $BusquedaContratoOC = OC::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOC)
            {
                $idOC = $BusquedaContratoOC->idOC;
            } else{
            $EsperaDato = "ESPERA DE DATOS";
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001/01/01';
            $OC->Tipo_Servicio = $EsperaDato;
            $OC->Estatus = 'OC';
            $OC->OC_archivo = $EsperaDato;
            $OC->save();

            $idOC = $OC->idOC;
            $Detalles_OC->idOC = $idOC;
            $Detalles_OC->Detalles = $EsperaDato;
            $Detalles_OC->save();
            }
            
            $Lineal_Ideal->idOC = $idOC;
            $Lineal_Ideal->idOrden_Servicio = $idOrdenServicio;
            $Lineal_Ideal->idSolicitud = $idSolicitud;
            $Lineal_Ideal->idReportes = $idReportes;
            $Lineal_Ideal->Estatus = 'CREADO';
            $Lineal_Ideal->save();

        } else {
            // Cliente no encontrado
            $Cliente = "POR DEFINIR";
            $Busqueda2Cliente = clientes::where('Cliente', $Cliente)->first();
            // Si no existe, crea el cliente "POR DEFINIR"
            if (!$Busqueda2Cliente) {
                $Busqueda2Cliente = new clientes();
                $Busqueda2Cliente->Cliente = $Cliente;
                $Busqueda2Cliente->RFC = 'N/A';
                $Busqueda2Cliente->Telefono = 'N/A';
                $Busqueda2Cliente->Correo = 'N/A';
                $Busqueda2Cliente->save();
            }

            $BusquedaContratoOS = Orden_Servicio::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOS)
            {
                $idOrdenServicio = $BusquedaContratoOS->idOrden_Servicio;
            } else{
            // Obtén el ID del cliente "POR DEFINIR"
            $idClientes = $Busqueda2Cliente->idClientes;
            $Orden_Servicio->idClientes = $idClientes;
            $Orden_Servicio->Fecha = '2001/01/01';
            $Orden_Servicio->Lugar = $Lugar;
            $Orden_Servicio->Contrato = $Contrato;
            $Orden_Servicio->Proyecto_actividad = $Proyecto;
            $Orden_Servicio->Material = $Material;
            $Orden_Servicio->Plano_isometrico = $Isometrico_Plano;
            $Orden_Servicio->save();

            // Obtén el ID del registro recién creado
            $idOrdenServicio = $Orden_Servicio->idOrden_Servicio;

            $Orden_Servicio_Prueba->idOrden_Servicio = $idOrdenServicio;
            $Orden_Servicio_Prueba->idPrueba_Aplica = $idPrueba_Aplica;
            $Orden_Servicio_Prueba->save();

            $Firmantes_OS->idOrden_Servicio = $idOrdenServicio;
            $Firmantes_OS->Nombre_Cargo = '[]';
            $Firmantes_OS->save();

            $Grupo_Juntas_Detalles_OS->idOrden_Servicio = $idOrdenServicio;
            $Grupo_Juntas_Detalles_OS->Juntas_grupo = $ResultadosJuntas;
            $Grupo_Juntas_Detalles_OS->save();

            }

            $BusquedaContratoOC = OC::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOC)
            {
                $idOC = $BusquedaContratoOC->idOC;
            } else{
            $EsperaDato = "ESPERA DE DATOS";
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001/01/01';
            $OC->Tipo_Servicio = $EsperaDato;
            $OC->Estatus = 'OC';
            $OC->OC_archivo = $EsperaDato;
            $OC->save();

            $idOC = $OC->idOC;
            $Detalles_OC->idOC = $idOC;
            $Detalles_OC->Detalles = $EsperaDato;
            $Detalles_OC->save();
            }

            $Lineal_Ideal->idOC = $idOC;
            $Lineal_Ideal->idOrden_Servicio = $idOrdenServicio;
            $Lineal_Ideal->idSolicitud = $idSolicitud;
            $Lineal_Ideal->idReportes = $idReportes;
            $Lineal_Ideal->Estatus = 'CREADO';
            $Lineal_Ideal->save();
        }

    }

    public function FOR_02_PRO_INS_10_store(Request $request)
    {
        $Estatus = "CREADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
            /*DETALLES GENERALES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.Fecha' => 'nullable|date',
            'Detalles_Generales.No_Reporte' => 'required|string|max:255',
            'Detalles_Generales.Cliente' => 'nullable|string|max:255',
            'Detalles_Generales.Contrato' => 'nullable|string|max:255',
            'Detalles_Generales.Proyecto' => 'nullable|string|max:255',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string|max:255',
            'Detalles_Generales.Folio' => 'nullable|string|max:255',
            'Detalles_Generales.Partida' => 'nullable|string|max:255',
            'Detalles_Generales.Lugar' => 'nullable|string|max:255',
            'Detalles_Generales.Isometrico_Plano' => 'nullable|string|max:255',
            'Detalles_Generales.Pieza' => 'nullable|string|max:255',
            'Detalles_Generales.Material' => 'nullable|string|max:255',
            'Detalles_Generales.Procedimiento' => 'nullable|string|max:255',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string|max:255',
            'Detalles_Generales.idSolicitud' => 'nullable|string|max:255',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.FREC_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.ACOPLANTE' => 'nullable|string|max:255',
            'Datos_Equipo.LONGITUD_CABLE' => 'nullable|string|max:255',
            'Datos_Equipo.GANANCIA' => 'nullable|string|max:255',
            'Datos_Equipo.RANGO' => 'nullable|string|max:255',
            'Datos_Equipo.RECHAZO' => 'nullable|string|max:255',
            'Datos_Equipo.SUPERFICIE' => 'nullable|string|max:255',
            'Datos_Equipo.PINTURA' => 'nullable|string|max:255',
            'Datos_Equipo.Observaciones' => 'nullable|string|max:255',

            /*Resultados_Juntas*/
            /* FILAS DINÁMICAS */
            'elemento_tubo' => 'nullable|array',
            'no_aceptacion' => 'nullable|array',
            'no_serie' => 'nullable|array',
            'no_colada' => 'nullable|array',
            'tnominal' => 'nullable|array',
            'diametro' => 'nullable|array',
            'no_ind' => 'nullable|array',
            'tipo_indicacion' => 'nullable|array',
            'nr' => 'nullable|array',
            'ni' => 'nullable|array',
            'ht' => 'nullable|array',
            'prof' => 'nullable|array',
            'la' => 'nullable|array',
            'lc' => 'nullable|array',
            'tmax' => 'nullable|array',
            'tmin' => 'nullable|array',
            'metros_lineales' => 'nullable|array',
            'evaluacion' => 'nullable|array',
            'observaciones' => 'nullable|array',

            //Validar el campo NumFirmas
            'numFirmas' => 'nullable|integer|in:2,3,4',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes2.Vobo1' => 'nullable|string|max:255',

            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string|max:255',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes3.Vobo1' => 'nullable|string|max:255',
            'Firmas_Reportes3.Vobo2' => 'nullable|string|max:255',

            'Firmas_Reportes3.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.NOMBRE_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.NOMBRE_2DO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes3.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.PUESTO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.PUESTO_2DO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes3.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.EMPRESA_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.EMPRESA_2DO_ENCARGADO' => 'nullable|string|max:255',

            /*4 FIRMAS */
            'Firmas_Reportes4' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes4.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo1' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo2' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo3' => 'nullable|string|max:255',

            'Firmas_Reportes4.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_3RO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes4.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_3RO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes4.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_3RO_ENCARGADO' => 'nullable|string|max:255',
        ]);

        //En la validación de Laravel, nullable significa que el campo puede estar vacío (nulo) 
        // y no se aplicarán las demás reglas de validación si el campo está vacío. Esto es útil 
        // cuando tienes campos opcionales en tu formulario.

        /*Detalles Generales y Datos del Equipo */
        $Reportes = new reporte();  // Modelo de la tabla donde guardas los datos
        $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();  // Modelo de la tabla donde guardas los datos
        $Firmas_Reportes = new Firma_Reporte();  // Modelo de la tabla donde guardas los datos
        $Fotos_Reportes = new Fotos_Reporte();  // Modelo de la tabla donde guardas los datos
        $idPrueba_Aplica = $request->input('idPrueba_Aplica');

        $Reportes->idPrueba_Aplica = $idPrueba_Aplica;

        //$Reportes->Contrato = json_encode($validatedData['Detalles_Generales']['Contrato']); //Fila Contrato en la Tabla Reportes, Borrar por si acaso
        // Guardar Detalles_Generales como JSON en la base de datos
        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        // Guardar Datos_Equipo como JSON en la base de datos
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);

        $Reportes->Estatus = $Estatus; // Asignar el estatus

        // Guardar el registro en la base de datos   
        $Reportes->save();

        // Obtener el idReportes del registro recién creado
        $idReportes = $Reportes->idReportes;

            /*Resultados Juntas*/
            // Guardar las filas dinámicas
            $Resultados_Juntas = [];
            foreach ($validatedData['elemento_tubo'] as $index => $elemento_tubo) {
                $Resultados_Juntas[] = [
                    'elemento_tubo' => $elemento_tubo,
                    'no_aceptacion' => $validatedData['no_aceptacion'][$index],
                    'no_serie' => $validatedData['no_serie'][$index],
                    'no_colada' => $validatedData['no_colada'][$index],
                    'tnominal' => $validatedData['tnominal'][$index],
                    'diametro' => $validatedData['diametro'][$index],
                    'no_ind' => $validatedData['no_ind'][$index],
                    'tipo_indicacion' => $validatedData['tipo_indicacion'][$index],
                    'nr' => $validatedData['nr'][$index],
                    'ni' => $validatedData['ni'][$index],
                    'ht' => $validatedData['ht'][$index],
                    'prof' => $validatedData['prof'][$index],
                    'la' => $validatedData['la'][$index],
                    'lc' => $validatedData['lc'][$index],
                    'tmax' => $validatedData['tmax'][$index],
                    'tmin' => $validatedData['tmin'][$index],
                    'metros_lineales' => $validatedData['metros_lineales'][$index],
                    'evaluacion' => $validatedData['evaluacion'][$index],
                    'observaciones' => $validatedData['observaciones'][$index],
                ];
            }
            // Convertir el array de resultados juntas a JSON
            $ResultadosJuntas = json_encode($Resultados_Juntas);

            $Grupo_Juntas_Detalles_Re->idReportes = $idReportes;
            //Guardar Datos_Equipo como JSON en la base de datos
            $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = $ResultadosJuntas;
            $Grupo_Juntas_Detalles_Re->save();

        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 2) {
            $validatedData['Firmas_Reportes2']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes2']);
        }
        else if ($numFirmas == 3) {
            $validatedData['Firmas_Reportes3']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes3']);
        }
        else{
            $validatedData['Firmas_Reportes4']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes4']);
        }

        $Firmas_Reportes->idReportes = $idReportes;
        $Firmas_Reportes->save();

        /* Fotos y Comentarios */
        $imageCount = $request->input('imageCount'); // Número de imágenes
        $imagenesGuardadas = []; // Para almacenar rutas de imágenes guardadas

        foreach ($request->images_base64 as $index => $base64Image) {
            $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
            $Contrato = $validatedData['Detalles_Generales']['Contrato'];

            // Decodificar Base64
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
            
            // Crear un nombre único para la imagen
            $imageName = 'imagen_' . time() . '_' . $index . '.png';

            // Definir la ruta personalizada
            $rutaCarpeta = "public/Reportes/FOR_02_PRO_INS_10/{$Contrato}/{$No_Reporte}/Fotos";
            
            // Guardar la imagen en la ruta personalizada
            Storage::put("{$rutaCarpeta}/{$imageName}", $image);

            // Guardar la ruta en el array con su comentario correspondiente
            $imagenesGuardadas[] = [
                'ruta' => "storage/Reportes/FOR_02_PRO_INS_10/{$Contrato}/{$No_Reporte}/Fotos/{$imageName}",
                'comentario' => $request->comments[$index] ?? null, // Guardar comentario si existe
            ];
        }

        // Convertir el array de fotos a JSON
        $Fotos = json_encode($imagenesGuardadas); 

        // Guardar en la base de datos
        $Fotos_Reportes->idReportes = $idReportes;
        $Fotos_Reportes->Fotos_Reportes = $Fotos;
        $Fotos_Reportes->save();

        $Cliente = $validatedData['Detalles_Generales']['Cliente'];
        $Lugar = $validatedData['Detalles_Generales']['Lugar'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];
        $Material = $validatedData['Detalles_Generales']['Material'];
        $idSolicitud = $validatedData['Detalles_Generales']['idSolicitud'];
        $Isometrico_Plano = $validatedData['Detalles_Generales']['Isometrico_Plano'];
        $Pieza = $validatedData['Detalles_Generales']['Pieza'];
        $Norma_cod_Criterio_Eva = $validatedData['Detalles_Generales']['Criterio_Evaluacion'];

        $datosParaCrearOS_OC = [
            'idPrueba_Aplica' => $idPrueba_Aplica,
            'Cliente' => $Cliente,
            'Lugar' => $Lugar,
            'Contrato' => $Contrato,
            'Proyecto' => $Proyecto,
            'Material' => $Material,
            'Isometrico_Plano' => $Isometrico_Plano,
            'Pieza' => $Pieza,
            'ResultadosJuntas' => $ResultadosJuntas,
            'Norma_cod_Criterio_Eva' => $Norma_cod_Criterio_Eva,
            'idSolicitud' => $idSolicitud,
            'idReportes' => $idReportes,
            
        ];

        $this->OS_OC($datosParaCrearOS_OC);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_02_PRO_INS_10_update(Request $request, $id)
    {
        $Estatus = "ACTUALIZADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
            /*DETALLES GENERALES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.Fecha' => 'nullable|date',
            'Detalles_Generales.No_Reporte' => 'required|string|max:255',
            'Detalles_Generales.Cliente' => 'nullable|string|max:255',
            'Detalles_Generales.Contrato' => 'nullable|string|max:255',
            'Detalles_Generales.Proyecto' => 'nullable|string|max:255',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string|max:255',
            'Detalles_Generales.Folio' => 'nullable|string|max:255',
            'Detalles_Generales.Partida' => 'nullable|string|max:255',
            'Detalles_Generales.Lugar' => 'nullable|string|max:255',
            'Detalles_Generales.Isometrico_Plano' => 'nullable|string|max:255',
            'Detalles_Generales.Pieza' => 'nullable|string|max:255',
            'Detalles_Generales.Material' => 'nullable|string|max:255',
            'Detalles_Generales.Procedimiento' => 'nullable|string|max:255',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string|max:255',
            'Detalles_Generales.idSolicitud' => 'nullable|string|max:255',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.FREC_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.ACOPLANTE' => 'nullable|string|max:255',
            'Datos_Equipo.LONGITUD_CABLE' => 'nullable|string|max:255',
            'Datos_Equipo.GANANCIA' => 'nullable|string|max:255',
            'Datos_Equipo.RANGO' => 'nullable|string|max:255',
            'Datos_Equipo.RECHAZO' => 'nullable|string|max:255',
            'Datos_Equipo.SUPERFICIE' => 'nullable|string|max:255',
            'Datos_Equipo.PINTURA' => 'nullable|string|max:255',
            'Datos_Equipo.Observaciones' => 'nullable|string|max:255',

            /*Resultados_Juntas*/
            /* FILAS DINÁMICAS */
            'elemento_tubo' => 'required|array',
            'no_aceptacion' => 'required|array',
            'no_serie' => 'required|array',
            'no_colada' => 'required|array',
            'tnominal' => 'required|array',
            'diametro' => 'required|array',
            'no_ind' => 'required|array',
            'tipo_indicacion' => 'required|array',
            'nr' => 'required|array',
            'ni' => 'required|array',
            'ht' => 'required|array',
            'prof' => 'required|array',
            'la' => 'required|array',
            'lc' => 'required|array',
            'tmax' => 'required|array',
            'tmin' => 'required|array',
            'metros_lineales' => 'required|array',
            'evaluacion' => 'required|array',
            'observaciones' => 'required|array',

            //Validar el campo NumFirmas
            'numFirmas' => 'required|integer|in:2,3,4',

             /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes2.Vobo1' => 'nullable|string|max:255',

            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string|max:255',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes3.Vobo1' => 'nullable|string|max:255',
            'Firmas_Reportes3.Vobo2' => 'nullable|string|max:255',

            'Firmas_Reportes3.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.NOMBRE_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.NOMBRE_2DO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes3.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.PUESTO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.PUESTO_2DO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes3.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.EMPRESA_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.EMPRESA_2DO_ENCARGADO' => 'nullable|string|max:255',

            /*4 FIRMAS */
            'Firmas_Reportes4' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes4.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo1' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo2' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo3' => 'nullable|string|max:255',

            'Firmas_Reportes4.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_3RO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes4.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_3RO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes4.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_3RO_ENCARGADO' => 'nullable|string|max:255',
        ]);

        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes',$id)->first();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes',$id)->first();
        $Firmas = Firma_Reporte::where('idReportes',$id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes',$id)->first();

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];

        // Actualiza los detalles generales como JSON en la base de datos
        $Reporte->update([
            'Detalles_Generales' => json_encode($validatedData['Detalles_Generales']),
            'Datos_Equipo' => json_encode($validatedData['Datos_Equipo']) 
        ]);

        /*Resultados Juntas*/
        // Guardar las filas dinámicas
        $Resultados_Juntas = [];
        foreach ($validatedData['elemento_tubo'] as $index => $elemento_tubo) {
            $Resultados_Juntas[] = [
                'elemento_tubo' => $elemento_tubo,
                'no_aceptacion' => $validatedData['no_aceptacion'][$index],
                'no_serie' => $validatedData['no_serie'][$index],
                'no_colada' => $validatedData['no_colada'][$index],
                'tnominal' => $validatedData['tnominal'][$index],
                'diametro' => $validatedData['diametro'][$index],
                'no_ind' => $validatedData['no_ind'][$index],
                'tipo_indicacion' => $validatedData['tipo_indicacion'][$index],
                'nr' => $validatedData['nr'][$index],
                'ni' => $validatedData['ni'][$index],
                'ht' => $validatedData['ht'][$index],
                'prof' => $validatedData['prof'][$index],
                'la' => $validatedData['la'][$index],
                'lc' => $validatedData['lc'][$index],
                'tmax' => $validatedData['tmax'][$index],
                'tmin' => $validatedData['tmin'][$index],
                'metros_lineales' => $validatedData['metros_lineales'][$index],
                'evaluacion' => $validatedData['evaluacion'][$index],
                'observaciones' => $validatedData['observaciones'][$index],
            ];
        }

        // Convertir el array de resultados juntas a JSON
        $ResultadosJuntas = json_encode($Resultados_Juntas);

        // Actualiza los detalles generales como JSON en la base de datos
        $Grupo_Juntas_Detalles_Re->update([
            'Juntas_Grupo_Re' => $ResultadosJuntas
        ]);

        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 2) {
            $validatedData['Firmas_Reportes2']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas2 = json_encode($validatedData['Firmas_Reportes2']);
            $Firmas->update([
                'Firmas' => $Firmas2
            ]);
        }
        else if ($numFirmas == 3) {
            $validatedData['Firmas_Reportes3']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas3 = json_encode($validatedData['Firmas_Reportes3']);
            $Firmas->update([
                'Firmas' => $Firmas3
            ]);
        }
        else{
            $validatedData['Firmas_Reportes4']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas4 = json_encode($validatedData['Firmas_Reportes4']);
            $Firmas->update([
                'Firmas' => $Firmas4
            ]);
        } 

        /* Fotos y Comentarios */
        // Obtener los valores necesarios para la ruta personalizada
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'] ?? ''; // Asegurar que Contrato está definido

        // Ruta base para guardar las imágenes
        $rutaCarpeta = "public/Reportes/FOR_02_PRO_INS_10/{$Contrato}/{$No_Reporte}/Fotos";

        // Obtener las imágenes existentes
        $existingImages = $request->input('existing_images', []);
        $comments = $request->input('comments', []);
        $imagesBase64 = $request->input('images_base64', []);
        $deletedImages = $request->input('deleted_images', []);

        //Log::info('Imágenes eliminadas recibidas:', ['deletedImages' => $deletedImages]);

        // **1️⃣ Eliminar imágenes marcadas para borrar**
        foreach ($deletedImages as $index) {
            if (isset($existingImages[$index])) {
                $rutaImagen = str_replace('storage/', 'public/', $existingImages[$index]);

                // Eliminar del almacenamiento
                if (Storage::exists($rutaImagen)) {
                    Storage::delete($rutaImagen);
                    Log::info("Imagen eliminada: {$rutaImagen}");
                } else {
                    //Log::warning("No se encontró la imagen para eliminar: {$rutaImagen}");
                }

                // Eliminar de `existingImages` para que no se guarde en la BD
                unset($existingImages[$index]);
            }
        }

        // **Reiniciar el array antes de procesar imágenes**
        $imagenesGuardadas = [];

        // **Evitar duplicados en las rutas ya guardadas**
        $rutasGuardadas = [];

        // **2️⃣ Procesar imágenes existentes**
        foreach ($existingImages as $index => $ruta) {
            if ($request->hasFile("replace_images.$index")) {
                // **Reemplazo de imagen existente**
                $newImage = $request->file("replace_images.$index");

                // Eliminar imagen anterior si existe
                $rutaImagenPublic = str_replace('storage/', 'public/', $ruta);
                if (Storage::exists($rutaImagenPublic)) {
                    Storage::delete($rutaImagenPublic);
                }

                // Guardar la nueva imagen
                $imageName = 'imagen_' . time() . '_' . $index . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs($rutaCarpeta, $imageName);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (!in_array($rutaNueva, $rutasGuardadas)) {
                    $imagenesGuardadas[] = [
                        'ruta' => $rutaNueva,
                        'comentario' => $comments[$index] ?? '',
                    ];
                    $rutasGuardadas[] = $rutaNueva; // Guardar ruta para evitar duplicados
                }
            } elseif (!empty($imagesBase64[$index])) {
                // **Procesar imágenes en Base64**
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imagesBase64[$index]));
                $imageName = 'imagen_' . time() . '_' . $index . '.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (!in_array($rutaNueva, $rutasGuardadas)) {
                    $imagenesGuardadas[] = [
                        'ruta' => $rutaNueva,
                        'comentario' => $comments[$index] ?? '',
                    ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            } else {
                // **Mantener la imagen existente**
                if (!in_array($ruta, $rutasGuardadas)) {
                    $imagenesGuardadas[] = [
                        'ruta' => $ruta,
                        'comentario' => $comments[$index] ?? '',
                    ];
                    $rutasGuardadas[] = $ruta;
                }
            }
        }

        // **3️⃣ Procesar nuevas imágenes Base64**
        foreach ($imagesBase64 as $index => $base64Image) {
            if (!empty($base64Image)) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $imageName = 'imagen_' . time() . '_' . $index . '.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen en el almacenamiento
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (!in_array($rutaNueva, $rutasGuardadas)) {
                    $imagenesGuardadas[] = [
                        'ruta' => $rutaNueva,
                        'comentario' => $comments[$index] ?? '',
                    ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            }
        }

        // **4️⃣ Guardar las imágenes actualizadas en la BD**
        $Fotos_Reportes->update([
            'Fotos_Reportes' => json_encode(array_values($imagenesGuardadas)), // Se usa array reindexado
        ]);

        //Log::info('Imágenes finales guardadas en BD:', ['imagenesGuardadas' => $imagenesGuardadas]);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }


    public function FOR_INS_10_02($id)
    {
        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes', $id)->first();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $Firmas_Reportes = Firma_Reporte::where('idReportes', $id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes', $id)->first();

        // Decodificar el campo Detalles_Generales para obtener el nombre del proyecto
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true);
        // Decodificar el campo Datos_Equipo para obtener el nombre del proyecto
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true);
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Grupo_Juntas_Detalles_Re = json_decode($Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re, true);
        $TotalJuntas = count($Grupo_Juntas_Detalles_Re);
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Firmas_Reportes = json_decode($Firmas_Reportes->Firmas, true);
        $numFirmas = $Firmas_Reportes['numFirmas'];

        $Logo = public_path('images/Logo_AICO_R.jpg');
        // Obtener las fotos con su comentario
        if ($Fotos_Reportes) {
            $fotos = json_decode($Fotos_Reportes->Fotos_Reportes, true);
            $totalFotos = count($fotos); // Contar el total de imágenes
            $Fotos = [];
        
            foreach ($fotos as $foto) { // Recorrer todas las imágenes sin límite
                $Fotos[] = [
                    'path' => storage_path('app/public/' . str_replace('storage/', '', $foto['ruta'])),
                    'comment' => $foto['comentario'] ?? ''
                ];
            }
        }

        $data = [
            'title' => 'Reporte_FOR-INS-10/02.PDF',
            'Logo' => $Logo,
            //Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            //Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            //Grupo_Juntas_Detalles_Re
            'Grupo_Juntas_Detalles_Re' => $Grupo_Juntas_Detalles_Re,
            //Total de Juntas
            'TotalJuntas' => $TotalJuntas,
            //Fotos_Reportes
            'Fotos' => $Fotos,
            //Total de Fotos
            'totalFotos' => $totalFotos,
            //Numero de Firmas
            'numFirmas' => $numFirmas,
            //Firmas
            'Firmas_Reportes' => $Firmas_Reportes,
        ];

        // Generar el PDF principal en orientación horizontal
        $pdf1 = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_10_02_PDF', $data)->setPaper('letter', 'landscape');

        // Generar el PDF adicional en orientación vertical
        $pdf2 = PDF::loadView('Reportes.ReportesFotosPDF.Reporte_FOTOS_FOR_INS_10_02_PDF', $data)->setPaper('letter', 'portrait');

        // Combinar los PDFs
        $pdf1Content = $pdf1->output();
        $pdf2Content = $pdf2->output();

       // Crear objetos FPDI independientes para contar páginas
        $tempPdf1 = new Fpdi();
        $pageCount1 = $tempPdf1->setSourceFile(StreamReader::createByString($pdf1Content));

        $tempPdf2 = new Fpdi();
        $pageCount2 = $tempPdf2->setSourceFile(StreamReader::createByString($pdf2Content));

        // Ahora sí combinamos
        $combinedPdf = new Fpdi();
        $totalPageCount = $pageCount1 + $pageCount2;

        // Añadir páginas del primer PDF
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        for ($i = 1; $i <= $pageCount1; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('L');
            $combinedPdf->useTemplate($tplId, 0, 0, 297, 210);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(179, -181);
            $combinedPdf->Cell(0, 10, "$i de $totalPageCount", 0, 0, 'C');
        }

        // Añadir páginas del segundo PDF
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
        for ($i = 1; $i <= $pageCount2; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('P');
            $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(138, -265.5);
            // Para que el conteo sea consecutivo
            $combinedPdf->Cell(0, 10, ($i + $pageCount1) . " de $totalPageCount", 0, 0, 'C');
        }

        return response($combinedPdf->Output('Reporte_FOR_INS_10_02.PDF', 'I'), 200)
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reporte $reporte)
    {
        //
    }
}
