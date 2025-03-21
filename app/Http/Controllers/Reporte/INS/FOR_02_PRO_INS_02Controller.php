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
        // Convertir el array de resultados juntas a JSON
        $ResultadosJuntas = json_encode($Resultados_Juntas);

        $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = $ResultadosJuntas;
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

    public function FOR_02_PRO_INS_02_update(Request $request)
    {

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
