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


class FOR_02_PRO_INS_02Controller extends Controller
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

    public function FOR_02_PRO_INS_02_store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            /* Detalles Generales */
            'Detalles_Generales' => 'required|array',
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
    
            /* Datos del Equipo */
            'Datos_Equipo' => 'required|array',
            'Datos_Equipo.MARCA_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.LOTE_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.COLOR_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.APLICACION_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.LOTE_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.COLOR_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.APLICACION_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.CORRIENTE_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.DISTANCIA_PATAS_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_LUZ' => 'nullable|string|max:255',
            'Datos_Equipo.INTENCIDAD' => 'nullable|string|max:255',
            'Datos_Equipo.CONDICION_SUPERFICIAL' => 'nullable|string|max:255',
            'Datos_Equipo.TEMPERATURA_PRUEBA' => 'nullable|string|max:255',
            'Datos_Equipo.Observaciones' => 'nullable|string|max:255',

            /*Titulos Juntas */
            'titulos' => 'nullable|array',  // Asegura que sea un array
            'titulos.*' => 'string|max:255',  // Cada título debe ser un string válido
    
            /* Resultados Juntas */
            'componente' => 'nullable|array',
            'no_indicacion' => 'nullable|array',
            'tipo_indicacion' => 'nullable|array',
            'largo' => 'nullable|array',
            'ancho' => 'nullable|array',
            'diametro' => 'nullable|array',
            'ht' => 'nullable|array',
            'evaluacion' => 'nullable|array',
            'longitud_inspeccionada' => 'nullable|array',
    
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
    
        // Guardar Detalles Generales
        $Reportes = new reporte();
        $Reportes->idPrueba_Aplica = $request->input('idPrueba_Aplica');
        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);
        $Reportes->Estatus = "CREADO";
        $Reportes->save();
    
        // Guardar Resultados Juntas
        $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();
        $Grupo_Juntas_Detalles_Re->idReportes = $Reportes->idReportes;
        
        $Titulos_Juntas = [];
        
        if (!empty($validatedData['titulos'])) {
            foreach ($validatedData['titulos'] as $titulo) {
                $Titulos_Juntas[] = ['titulo' => $titulo];
            }
        }
        
        $Resultados_Juntas = [];
        foreach ($validatedData['componente'] as $index => $componente) {
            $Resultados_Juntas[] = [
                'componente' => $componente,
                'no_indicacion' => $validatedData['no_indicacion'][$index],
                'tipo_indicacion' => $validatedData['tipo_indicacion'][$index],
                'largo' => $validatedData['largo'][$index],
                'ancho' => $validatedData['ancho'][$index],
                'diametro' => $validatedData['diametro'][$index],
                'ht' => $validatedData['ht'][$index],
                'evaluacion' => $validatedData['evaluacion'][$index],
                'longitud_inspeccionada' => $validatedData['longitud_inspeccionada'][$index],
            ];
        }

        // Convertir a JSON
        $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = json_encode([
            'titulos' => $Titulos_Juntas,
            'resultados' => $Resultados_Juntas
        ]);
        // Convertir el array de resultados juntas a JSON
        //$ResultadosJuntas = json_encode($Resultados_Juntas);
        //$Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = $ResultadosJuntas;
        
        $Grupo_Juntas_Detalles_Re->save();
    
        // Guardar Firmas
        $Firmas_Reportes = new Firma_Reporte();
        $Firmas_Reportes->idReportes = $Reportes->idReportes;

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
        $Firmas_Reportes->save();
    
        // Guardar Fotos
        $Fotos_Reportes = new Fotos_Reporte();
        $Fotos_Reportes->idReportes = $Reportes->idReportes;
    
        $fotos = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("image$i")) {
                $image = $request->file("image$i");
                $path = $image->store("public/Reportes/FOR_02_PRO_INS_10/{$validatedData['Detalles_Generales']['Contrato']}/{$validatedData['Detalles_Generales']['No_Reporte']}/Fotos");
                $fotos[] = [
                    'path' => $path,
                    'comment' => $request->input("comment$i"),
                ];
            }
        }
    
        $Fotos_Reportes->Fotos_Reportes = json_encode($fotos);
        $Fotos_Reportes->save();
    
        // Redireccionar
        return redirect()->route('indexINS2', [
            'contratoSeleccionado' => $validatedData['Detalles_Generales']['Contrato'],
            'Proyecto' => $validatedData['Detalles_Generales']['Proyecto'],
        ]);
    }

    public function FOR_02_PRO_INS_02_update(Request $request, $id)
    {

        $Estatus = "ACTUALIZADO";

        // Validar los datos del formulario
        $validatedData = $request->validate([
            /* Detalles Generales */
            'Detalles_Generales' => 'required|array',
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

            /* Datos del Equipo */
            'Datos_Equipo' => 'required|array',
            'Datos_Equipo.MARCA_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.LOTE_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.COLOR_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.APLICACION_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.LOTE_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.COLOR_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.APLICACION_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.CORRIENTE_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.DISTANCIA_PATAS_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_LUZ' => 'nullable|string|max:255',
            'Datos_Equipo.INTENCIDAD' => 'nullable|string|max:255',
            'Datos_Equipo.CONDICION_SUPERFICIAL' => 'nullable|string|max:255',
            'Datos_Equipo.TEMPERATURA_PRUEBA' => 'nullable|string|max:255',
            'Datos_Equipo.Observaciones' => 'nullable|string|max:255',

            /*Titulos Juntas */
            'titulos' => 'nullable|array',  // Asegura que sea un array
            'titulos.*' => 'string|max:255',  // Cada título debe ser un string válido
    
            /* Resultados Juntas */
            'componente' => 'nullable|array',
            'no_indicacion' => 'nullable|array',
            'tipo_indicacion' => 'nullable|array',
            'largo' => 'nullable|array',
            'ancho' => 'nullable|array',
            'diametro' => 'nullable|array',
            'ht' => 'nullable|array',
            'evaluacion' => 'nullable|array',
            'longitud_inspeccionada' => 'nullable|array',
    
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

        $Titulos_Juntas = [];
        
        if (!empty($validatedData['titulos'])) {
            foreach ($validatedData['titulos'] as $titulo) {
                $Titulos_Juntas[] = ['titulo' => $titulo];
            }
        }

        $Resultados_Juntas = [];
        foreach ($validatedData['componente'] as $index => $componente) {
            $Resultados_Juntas[] = [
                'componente' => $componente,
                'no_indicacion' => $validatedData['no_indicacion'][$index],
                'tipo_indicacion' => $validatedData['tipo_indicacion'][$index],
                'largo' => $validatedData['largo'][$index],
                'ancho' => $validatedData['ancho'][$index],
                'diametro' => $validatedData['diametro'][$index],
                'ht' => $validatedData['ht'][$index],
                'evaluacion' => $validatedData['evaluacion'][$index],
                'longitud_inspeccionada' => $validatedData['longitud_inspeccionada'][$index],
            ];
        }
        // Convertir el array de resultados de titulo y juntas a JSON
        $TituloyJuntas = json_encode([
            'titulos' => $Titulos_Juntas,
            'resultados' => $Resultados_Juntas
        ]);

        // Actualizar el campo en la base de datos
        $Grupo_Juntas_Detalles_Re->update([
            'Juntas_Grupo_Re' => $TituloyJuntas // ✅ Se pasa el JSON directamente
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

        /*Fotos y Comentarios */
        // Procesar las imágenes y los comentarios

        // Obtener las rutas de las imágenes guardadas anteriormente
        $previousFotos = json_decode($Fotos_Reportes->Fotos_Reportes, true);

        // Procesar las nuevas imágenes y los comentarios
        $fotos = [];
        for ($i = 1; $i <= 4; $i++) {
            $comment = $request->input("comment$i", ""); // Obtener el comentario incluso si la imagen no cambia
            Log::info("Comentario recibido para imagen $i: ", ['comment' => $comment]);
        
            if ($request->hasFile("image$i")) {
                // Eliminar la imagen anterior si existe
                if (isset($previousFotos[$i - 1]['path']) && Storage::exists($previousFotos[$i - 1]['path'])) {
                    Storage::delete($previousFotos[$i - 1]['path']);
                }
        
                // Guardar la nueva imagen
                $image = $request->file("image$i");
                $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
                $Contrato = $validatedData['Detalles_Generales']['Contrato'];
                $path = $image->store("public/Reportes/FOR_02_PRO_INS_10/$Contrato/$No_Reporte/Fotos");
        
                $fotos[] = [
                    'path' => $path,
                    'comment' => $comment, // Guardar el comentario actualizado
                ];
            } else {
                // Mantener la imagen anterior pero actualizar el comentario si cambió
                if (isset($previousFotos[$i - 1])) {
                    $fotos[] = [
                        'path' => $previousFotos[$i - 1]['path'],
                        'comment' => $comment ?: $previousFotos[$i - 1]['comment'], // Si el nuevo comentario está vacío, mantener el anterior
                    ];
                }
            }
        }
        // Convertir el array de fotos a JSON
        $Fotos = json_encode($fotos);
        // Actualiza los detalles generales como JSON en la base de datos
        $Fotos_Reportes->update([
            'Fotos_Reportes' => $Fotos
        ]);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);

    }

    public function FOR_INS_02_02($id)
    {
        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        /*$Reporte = reporte::where('idReportes', $id)->first();
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
        $numFirmas = $Firmas_Reportes['numFirmas'];*/

        $Logo = public_path('images/Logo_AICO_R.jpg');

        // Obtener las fotos con su comentario
        /*if ($Fotos_Reportes) {
            $fotos = json_decode($Fotos_Reportes->Fotos_Reportes, true);
            $Fotos = [];
        
            for ($i = 0; $i < min(4, count($fotos)); $i++) { // Solo obtener hasta 4 imágenes
                $Fotos[] = [
                    'path' => public_path('storage/' . str_replace('public/', '', $fotos[$i]['path'])),
                    'comment' => $fotos[$i]['comment'] ?? ''
                ];
            }
        }*/

        $data = [
            'title' => 'Reporte_FOR-INS-02/02.PDF',
            'Logo' => $Logo,
            /*//Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            //Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            //Grupo_Juntas_Detalles_Re
            'Grupo_Juntas_Detalles_Re' => $Grupo_Juntas_Detalles_Re,
            //Total de Juntas
            'TotalJuntas' => $TotalJuntas,
            //Fotos_Reportes
            'Fotos' => $Fotos,
            //Numero de Firmas
            'numFirmas' => $numFirmas,
            //Firmas
            'Firmas_Reportes' => $Firmas_Reportes,*/
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_02_02_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_02_02_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros

        // Renderizar el PDF antes de obtener el canvas
        $dompdf = $pdf->getDomPDF();
        // Configurar márgenes personalizados (en milímetros)
        $options = $dompdf->getOptions();
        $options->set('isHtml5ParserEnabled', true); // Opcional, mejora compatibilidad
        $options->set('defaultPaperMargins', [20, 10, 20, 10]);  // [arriba, derecha, abajo, izquierda]
        $dompdf->setOptions($options);
        $dompdf->render(); // Renderiza el contenido del PDF para calcular todas las páginas

        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            
            // Usar una fuente válida predefinida en DomPDF
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;

            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario

            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });

        return $pdf->stream('Reporte_FOR_INS_02_02.PDF');
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
