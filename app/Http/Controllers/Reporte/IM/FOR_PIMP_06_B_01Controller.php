<?php

namespace App\Http\Controllers\Reporte\IM;

use App\Http\Controllers\Controller;
use App\Models\Admin\Usuario;
use App\Models\Clientes\clientes;
use App\Models\detallesOC\detallesOC;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Formato\formato;
use App\Models\Lineal_Ideal\Lineal_Ideal;
use App\Models\Normas_IM\Normas_IM;
use App\Models\OC\OC;
use App\Models\OrdenServicio\Firmantes_OS;
use App\Models\OrdenServicio\Grupo_Juntas_Detalles_OS;
use App\Models\OrdenServicio\Orden_Servicio;
use App\Models\OrdenServicio\Orden_Servicio_Prueba;
use App\Models\Procedimientos\Procedimiento;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;
use App\Models\Reporte\reporte;
use App\Services\ServicioAnalisisPdfXrf;
use App\Services\ServicioJuntasReporteIM;
use App\Services\ServicioPatronGranoReporte;
use App\Services\ServicioSerieReportes;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class FOR_PIMP_06_B_01Controller extends Controller
{
    public function FOR_PIMP_06_B_01_store(Request $request)
    {
        // Restaura los PDF multiples que quedaron asociados al UUID del formulario.
        app(\App\Services\Procesamiento\ServicioArchivosProcesamiento::class)->restaurarXrf($request);
        // Validar los Detalles_Generales
        $validatedData = $request->validate($this->reglasValidacion());

        $imagenesParaValidar = [];
        foreach ($request->input('images_base64', []) as $index => $base64Image) {
            if (! empty($base64Image) && ! $request->boolean("foto_es_texto.{$index}")) {
                $imagenesParaValidar[] = [
                    'es_disparo' => ! empty($request->input("es_disparo.{$index}")),
                    'numero_disparo' => $request->input("numero_disparo.{$index}"),
                ];
            }
        }
        $this->validarFotosDeDisparos($imagenesParaValidar);

        /* Detalles Generales y Datos del Equipo */
        $Reportes = new reporte;  // Modelo de la tabla donde guardas los datos
        $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re;  // Modelo de la tabla donde guardas los datos
        $Firmas_Reportes = new Firma_Reporte;  // Modelo de la tabla donde guardas los datos
        $Fotos_Reportes = new Fotos_Reporte;  // Modelo de la tabla donde guardas los datos
        $idPrueba_Aplica = $request->input('idPrueba_Aplica');

        $Reportes->idPrueba_Aplica = $idPrueba_Aplica;

        // ==========================
        // Lógica para manejar Cliente
        // ==========================
        if ($request->TieneCliente === 'si') {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteSelect;
        } else {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteInput;
        }
        // ==========================
        // Lógica para manejar Contrato
        // ==========================
        // Lógica para manejar el campo Contrato
        if ($request->TieneContrato === 'no') {

            // Si el usuario alteró el valor o no llegó, se recalcula en backend
            $actual = $request->Detalles_Generales['Contrato'] ?? null;

            // Verificar que realmente tenga el formato correcto
            if (! $actual || ! preg_match('/^AICO-INT-\d{4}$/', $actual)) {

                // Seguridad: volver a calcular el consecutivo
                $registros = reporte::orderBy('idReportes', 'DESC')->get();
                $ultimoNumero = 0;

                foreach ($registros as $r) {
                    $json = json_decode($r->Detalles_Generales, true);

                    if (! empty($json['Contrato']) && str_starts_with($json['Contrato'], 'AICO-INT-')) {
                        $n = intval(str_replace('AICO-INT-', '', $json['Contrato']));
                        if ($n > $ultimoNumero) {
                            $ultimoNumero = $n;
                        }
                        break;
                    }
                }

                $nuevo = 'AICO-INT-'.str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);

                $validatedData['Detalles_Generales']['Contrato'] = $nuevo;

            } else {
                // Si el frontend envió un contrato válido, se utiliza ese
                $validatedData['Detalles_Generales']['Contrato'] = $actual;
            }
        }

        // Guarda una copia estable de la norma y, si existen, las rutas de sus PDF XRF.
        // La norma ya no se guarda en Detalles_Generales: se guarda en Juntas_Grupo_Re.
        $normaIM = $this->construirNormaIM($request);
        if ($normaIM !== null) {
            if ($request->hasFile('Analisis_PDF')) {
                $this->guardarPdfsXrf(
                    $request->file('Analisis_PDF', []),
                    $normaIM['Analisis_PDF'],
                    (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
                    (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? '')
                );
            }
        }

        // Congela el patrón elegido dentro del expediente para proteger el reporte histórico.
        // El patrón ya no se guarda en Detalles_Generales: se guarda en Juntas_Grupo_Re.
        $servicioPatronGrano = app(ServicioPatronGranoReporte::class);
        $patronGrano = $servicioPatronGrano->construirHistorico(
            $request,
            'FOR_PIMP_06_B_01',
            (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
            (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? '')
        );

        // Guardar Detalles_Generales como JSON en la base de datos
        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        // Guardar Datos_Equipo como JSON en la base de datos
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);

        $Reportes->Estatus = 'CREADO';

        // Guardar el registro en la base de datos
        $Reportes->save();

        /*
        |--------------------------------------------------------------------------
        | DATOS PARA CREAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $validatedData['Datos_Equipo']['QR_TOKEN'] = $validatedData['Datos_Equipo']['QR_TOKEN'] ?? (string) Str::uuid();

        $datosParaCrearQR = [
            'Contrato' => $validatedData['Detalles_Generales']['Contrato'] ?? null,
            'No_Reporte' => $validatedData['Detalles_Generales']['No_Reporte'] ?? null,
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'idEquipo' => $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? null,
            // El técnico seleccionado permite anexar su CV al QR, igual que en PINS.
            'ID_TECNICO' => $this->construirFirmas($validatedData)['ID_TECNICO'] ?? null,
            'idProcedimiento' => $validatedData['Detalles_Generales']['idProcedimiento'] ?? null,
        ];

        /*
        |--------------------------------------------------------------------------
        | GENERAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $resultadoQR = $this->Datos_QR($datosParaCrearQR);
        $validatedData['Datos_Equipo']['QR_PDF'] = $resultadoQR['qr'] ?? null;
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = $resultadoQR['pdf'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | GUARDAR RUTAS EN DATOS_EQUIPO
        |--------------------------------------------------------------------------
        */

        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);
        $Reportes->save();

        // Obtener el idReportes del registro recién creado
        $idReportes = $Reportes->idReportes;
        // El primer reporte abre su serie inmediatamente; los consecutivos se
        // incorporan desde el boton Siguiente reporte.
        app(ServicioSerieReportes::class)->iniciar(
            (int) $idReportes,
            (int) $request->input('Serie_Reportes.cantidad_planificada')
        );

        // Bloques + Norma_IM + Patron_Grano se guardan juntos en Grupo_Juntas_Detalles_Re.
        $bloques = []; // El formulario XRF no captura filas de inspeccion.
        $Grupo_Juntas_Detalles_Re->idReportes = $idReportes;
        $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = app(ServicioJuntasReporteIM::class)
            ->armar($bloques, $normaIM, $patronGrano);
        $Grupo_Juntas_Detalles_Re->save();

        /* Firmas */
        $Firmas_Reportes->Firmas = json_encode($this->construirFirmas($validatedData));
        $Firmas_Reportes->idReportes = $idReportes;
        $Firmas_Reportes->save();

        /* Fotos y Comentarios */
        $imagesBase64 = $request->input('images_base64', []);
        // La distribución de disparos se persiste junto con cada fotografía para reconstruir el PDF.
        $esDisparo = $request->input('es_disparo', []);
        $numeroDisparo = $request->input('numero_disparo', []);
        $fotoPaginas = $request->input('foto_pagina', []);
        $fotoPosiciones = $request->input('foto_posicion', []);
        $fotoEsTexto = $request->input('foto_es_texto', []);
        $comentariosFoto = $request->input('comments', []);
        $imagenesGuardadas = [];
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $indicesFoto = array_unique(array_merge(array_keys($imagesBase64), array_keys($fotoEsTexto)));
        foreach ($indicesFoto as $index) {
            $base64Image = $imagesBase64[$index] ?? null;
            $esCuadroTexto = ! empty($fotoEsTexto[$index]);
            if (empty($base64Image) && ! $esCuadroTexto) {
                continue;
            }

            // Un cuadro de texto no crea un archivo artificial; conserva solo contenido y distribución.
            $rutaPublicaFoto = null;
            if (! $esCuadroTexto) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $imageName = 'imagen_'.time().'_'.$index.'.png';
                $rutaCarpeta = "public/Reportes/FOR_PIMP_06_B_01/{$Contrato}/{$No_Reporte}/Fotos";
                Storage::put("{$rutaCarpeta}/{$imageName}", $image);
                $rutaPublicaFoto = "storage/Reportes/FOR_PIMP_06_B_01/{$Contrato}/{$No_Reporte}/Fotos/{$imageName}";
            }

            // Los disparos usan un acomodo fijo de dos imágenes; las demás fotos usan el diseño manual.
            $distribucionFoto = $this->normalizeStoredPhotoLayout(
                $fotoPaginas[$index] ?? null,
                $fotoPosiciones[$index] ?? null,
                $index,
                ! $esCuadroTexto && ! empty($esDisparo[$index]),
                $numeroDisparo[$index] ?? null
            );

            $imagenesGuardadas[] = [
                'ruta' => $rutaPublicaFoto,
                'comentario' => $comentariosFoto[$index] ?? null,
                'es_cuadro_texto' => $esCuadroTexto ? 1 : 0,
                'una_hoja' => $distribucionFoto['una_hoja'],
                'pagina' => $distribucionFoto['pagina'],
                'posicion' => $distribucionFoto['posicion'],
                'es_disparo' => ! $esCuadroTexto && ! empty($esDisparo[$index]) ? 1 : 0,
                'numero_disparo' => ! $esCuadroTexto && ! empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
            ];
        }

        $Fotos_Reportes->idReportes = $idReportes;
        $Fotos_Reportes->Fotos_Reportes = json_encode($imagenesGuardadas);
        $Fotos_Reportes->save();

        $Cliente = $validatedData['Detalles_Generales']['Cliente'];
        $Instalacion = $validatedData['Detalles_Generales']['Instalacion'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'] ?? 'ESPERA DE DATOS';
        $Material = $validatedData['Detalles_Generales']['Material'];
        $idSolicitud = $validatedData['Detalles_Generales']['idSolicitud'];
        $No_Isometrico = $validatedData['Detalles_Generales']['No_Isometrico'];

        $datosParaCrearOS_OC = [
            'idPrueba_Aplica' => $idPrueba_Aplica,
            'Cliente' => $Cliente,
            'Lugar' => $Instalacion,
            'Contrato' => $Contrato,
            'Proyecto' => $Proyecto,
            'Material' => $Material,
            'Isometrico_Plano' => $No_Isometrico,
            'idSolicitud' => $idSolicitud,
            'idReportes' => $idReportes,

        ];

        $this->OS_OC($datosParaCrearOS_OC);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];

        // El PDF final se prepara en segundo plano al terminar de guardar. El
        // tecnico puede continuar usando el sistema sin esperar en esta pagina.
        try {
            app(\App\Services\ServicioPdfGenerado::class)->programar(
                (int) $idReportes,
                '06_B_01',
                'es',
                (int) $request->user()->getAuthIdentifier()
            );
        } catch (\Throwable $error) {
            // La preparacion anticipada es una optimizacion: un fallo de cola no
            // debe convertir en error un reporte que ya fue guardado correctamente.
            Log::warning('No fue posible programar anticipadamente el PDF 06_B_01.', [
                'reporte_id' => (int) $idReportes,
                'error' => $error->getMessage(),
            ]);
        }

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_PIMP_06_B_01_update(Request $request, $id)
    {
        // Edit usa la misma validacion de propiedad y estado que Create.
        app(\App\Services\Procesamiento\ServicioArchivosProcesamiento::class)->restaurarXrf($request);
        // Validar los Detalles_Generales
        $validatedData = $request->validate($this->reglasValidacion(true));

        // Validar los disparos antes de modificar el reporte o almacenar el PDF firmado.
        $existingImagesParaValidar = $request->input('existing_images', []);
        $imagesBase64ParaValidar = $request->input('images_base64', []);
        $deletedImagesParaValidar = array_values(array_filter(
            $request->input('deleted_images', []),
            static fn ($index) => $index !== null && $index !== ''
        ));
        $esDisparoParaValidar = $request->input('es_disparo', []);
        $numeroDisparoParaValidar = $request->input('numero_disparo', []);

        $imagenesParaValidar = [];
        $indicesFotos = array_unique(array_merge(
            array_keys($existingImagesParaValidar),
            array_keys($imagesBase64ParaValidar)
        ));
        foreach ($indicesFotos as $index) {
            if ($request->boolean("foto_es_texto.{$index}")
                || in_array((string) $index, array_map('strval', $deletedImagesParaValidar), true)
                || (! isset($existingImagesParaValidar[$index]) && empty($imagesBase64ParaValidar[$index]))) {
                continue;
            }

            $imagenesParaValidar[] = [
                'es_disparo' => ! empty($esDisparoParaValidar[$index]),
                'numero_disparo' => $numeroDisparoParaValidar[$index] ?? null,
            ];
        }
        $this->validarFotosDeDisparos($imagenesParaValidar);

        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes', $id)->firstOrFail();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $Firmas = Firma_Reporte::firstOrNew(['idReportes' => $id]);
        $Fotos_Reportes = Fotos_Reporte::where('idReportes', $id)->first();

        // La cantidad se captura una sola vez. Los consecutivos heredan el total
        // original y solo lo cambian mediante la accion explicita "Ampliar serie".
        $servicioSeries = app(ServicioSerieReportes::class);
        $serieActual = $servicioSeries->obtener((int) $id);
        if (! $serieActual) {
            $cantidadInicial = (int) $request->input('Serie_Reportes.cantidad_planificada', 0);
            if ($cantidadInicial < 1) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'Serie_Reportes.cantidad_planificada' => 'Indique la cantidad total para configurar este reporte anterior.',
                ]);
            }

            $servicioSeries->iniciar(
                (int) $id,
                $cantidadInicial
            );
        } elseif ($request->filled('Serie_Reportes.nueva_cantidad')) {
            $nuevaCantidad = (int) $request->input('Serie_Reportes.nueva_cantidad');
            if ($nuevaCantidad <= (int) $serieActual->cantidad_planificada) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'Serie_Reportes.nueva_cantidad' => 'El nuevo total debe ser mayor que el total actual de la serie.',
                ]);
            }

            try {
                $servicioSeries->actualizarCantidad(
                    (int) $id,
                    $nuevaCantidad
                );
            } catch (\RuntimeException $exception) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'Serie_Reportes.nueva_cantidad' => $exception->getMessage(),
                ]);
            }
        }

        // actualizarCantidad() guarda SERIE_REPORTES directamente en la base.
        // Este modelo fue consultado antes de esa operacion; se refresca para
        // impedir que el guardado general posterior restaure el total anterior.
        $Reporte->refresh();

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        // 1. Obtener los detalles actuales que ya están en la base de datos
        $detallesActuales = json_decode($Reporte->Detalles_Generales, true) ?? [];
        $datosEquipoActuales = json_decode($Reporte->Datos_Equipo, true) ?? [];

        // Estado actual de Juntas (bloques, Norma_IM y Patron_Grano). Si el reporte es
        // anterior a este cambio, Norma_IM y PATRON_GRANO se toman de Detalles_Generales.
        $servicioJuntas = app(ServicioJuntasReporteIM::class);
        $juntasActuales = $servicioJuntas->normalizar(
            $Grupo_Juntas_Detalles_Re?->Juntas_Grupo_Re,
            $detallesActuales
        );

        // Las rutas anteriores se conservan hasta confirmar el reemplazo por los PDF nuevos.
        $rutasPdfsXrfAnteriores = [];

        foreach (($juntasActuales['Norma_IM']['Analisis_PDF'] ?? []) as $analisisAnterior) {
            if (is_array($analisisAnterior) && ! empty($analisisAnterior['ruta'])) {
                $rutasPdfsXrfAnteriores[] = (string) $analisisAnterior['ruta'];
            }
        }

        if ($request->hasFile('Detalles_Generales.Reporte_Firmado')) {

            // 1. ELIMINAR ARCHIVO ANTERIOR (si existe)
            if (! empty($detallesActuales['Reporte_Firmado'])) {
                // Convertimos la ruta de la base de datos (storage/...) de vuelta a la ruta del disco (public/...)
                $archivoViejo = str_replace('storage/', 'public/', $detallesActuales['Reporte_Firmado']);

                if (Storage::exists($archivoViejo)) {
                    Storage::delete($archivoViejo);
                }
            }

            // 2. PROCESAR NUEVO ARCHIVO
            $file = $request->file('Detalles_Generales.Reporte_Firmado');
            $rutaBase = "public/Reportes/FOR_PIMP_06_B_01/{$Contrato}/{$No_Reporte}/Reporte_Firmado";
            $nombreArchivo = 'Reporte_Firmado_'.$No_Reporte.'_'.time().'.pdf';

            $file->storeAs($rutaBase, $nombreArchivo);

            $rutaPublica = str_replace('public/', 'storage/', $rutaBase).'/'.$nombreArchivo;
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $rutaPublica;

        } else {
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $detallesActuales['Reporte_Firmado'] ?? null;
        }

        // Conservar cualquier informacion previa que no venga en el formulario de edicion.
        $validatedData['Detalles_Generales'] = array_merge($detallesActuales, $validatedData['Detalles_Generales']);
        $validatedData['Datos_Equipo'] = array_merge($datosEquipoActuales, $validatedData['Datos_Equipo']);

        // Mezcla la selección actual con la copia histórica cuando no se cargan PDF nuevos.
        // La norma se guarda en Juntas_Grupo_Re (ya no en Detalles_Generales).
        $normaIM = $this->construirNormaIM($request, $juntasActuales['Norma_IM']);
        if ($normaIM !== null) {
            if ($request->hasFile('Analisis_PDF')) {
                $this->guardarPdfsXrf(
                    $request->file('Analisis_PDF', []),
                    $normaIM['Analisis_PDF'],
                    (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
                    (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? '')
                );
            }
        } else {
            // Sin norma en este envío: se conserva la guardada (igual que antes).
            $normaIM = $juntasActuales['Norma_IM'];
        }

        // Edit conserva la copia histórica salvo que el técnico seleccione otra versión del catálogo.
        // El patrón se guarda en Juntas_Grupo_Re (ya no en Detalles_Generales).
        $patronAnterior = $juntasActuales['Patron_Grano'];
        $rutaPatronAnterior = (string) ($patronAnterior['ruta_imagen'] ?? '');
        $servicioPatronGrano = app(ServicioPatronGranoReporte::class);
        $patronGrano = $servicioPatronGrano->construirHistorico(
            $request,
            'FOR_PIMP_06_B_01',
            (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
            (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? ''),
            $patronAnterior
        );

        $validatedData['Datos_Equipo']['ID_EQUIPO'] = $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? ($datosEquipoActuales['ID_EQUIPO'] ?? null);
        $validatedData['Datos_Equipo']['QR_TOKEN'] = $validatedData['Datos_Equipo']['QR_TOKEN'] ?? ($datosEquipoActuales['QR_TOKEN'] ?? (string) Str::uuid());

        $datosParaCrearQR = [
            'Contrato' => $validatedData['Detalles_Generales']['Contrato'] ?? null,
            'No_Reporte' => $validatedData['Detalles_Generales']['No_Reporte'] ?? null,
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'idEquipo' => $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? null,
            // El técnico seleccionado permite anexar su CV al QR, igual que en PINS.
            'ID_TECNICO' => $this->construirFirmas($validatedData)['ID_TECNICO'] ?? null,
            'idProcedimiento' => $validatedData['Detalles_Generales']['idProcedimiento'] ?? null,
        ];

        $resultadoQR = $this->Datos_QR($datosParaCrearQR);
        $validatedData['Datos_Equipo']['QR_PDF'] = $resultadoQR['qr'] ?? ($datosEquipoActuales['QR_PDF'] ?? null);
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = $resultadoQR['pdf'] ?? ($datosEquipoActuales['PDF_UNIFICADO'] ?? null);

        // Norma y patron se guardan en Juntas_Grupo_Re; elimina las copias antiguas.
        unset($validatedData['Detalles_Generales']['Norma_IM'], $validatedData['Detalles_Generales']['PATRON_GRANO']);

        // Actualiza los detalles generales como JSON en la base de datos
        $Reporte->update([
            'Detalles_Generales' => json_encode($validatedData['Detalles_Generales']),
            'Datos_Equipo' => json_encode($validatedData['Datos_Equipo']),
            'Estatus' => 'ACTUALIZADO',
        ]);

        // La copia anterior se elimina solo después de que el reporte apunta correctamente a la nueva.
        $rutaPatronNueva = (string) ($patronGrano['ruta_imagen'] ?? '');
        $servicioPatronGrano->eliminarCopiaSustituida($rutaPatronAnterior, $rutaPatronNueva);

        // Las filas historicas se conservan; el formulario 06 no las edita.
        $bloques = $juntasActuales['bloques'];

        $juntasJson = $servicioJuntas->armar($bloques, $normaIM, $patronGrano);

        // Actualizar o crear el campo en la base de datos
        if ($Grupo_Juntas_Detalles_Re) {
            $Grupo_Juntas_Detalles_Re->update(['Juntas_Grupo_Re' => $juntasJson]);
        } else {
            $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re;
            $Grupo_Juntas_Detalles_Re->idReportes = $id;
            $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = $juntasJson;
            $Grupo_Juntas_Detalles_Re->save();
        }

        /* Firmas */
        $Firmas->Firmas = json_encode($this->construirFirmas($validatedData));
        $Firmas->save();
        /* Fotos y Comentarios */
        // Obtener los valores necesarios para la ruta personalizada
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'] ?? ''; // Asegurar que Contrato está definido

        // Ruta base para guardar las imágenes
        $rutaCarpeta = "public/Reportes/FOR_PIMP_06_B_01/{$Contrato}/{$No_Reporte}/Fotos";

        // Obtener las imágenes existentes
        $existingImages = $request->input('existing_images', []);
        $comments = $request->input('comments', []);
        $imagesBase64 = $request->input('images_base64', []);
        $fotoEsTexto = $request->input('foto_es_texto', []);
        $deletedImages = $request->input('deleted_images', []);
        $esDisparo = $request->input('es_disparo', []);
        $numeroDisparo = $request->input('numero_disparo', []);
        $fotoPaginas = $request->input('foto_pagina', []);
        $fotoPosiciones = $request->input('foto_posicion', []);
        // Centraliza la regla de distribución para reemplazos, imágenes existentes y altas nuevas.
        $getDistribucionFoto = function ($index) use ($fotoPaginas, $fotoPosiciones, $esDisparo, $numeroDisparo) {
            return $this->normalizeStoredPhotoLayout(
                $fotoPaginas[$index] ?? null,
                $fotoPosiciones[$index] ?? null,
                $index,
                ! empty($esDisparo[$index]),
                $numeroDisparo[$index] ?? null
            );
        };
        // **1️⃣ Eliminar imágenes marcadas para borrar**
        foreach ($deletedImages as $index) {
            if (isset($existingImages[$index])) {
                $rutaImagen = str_replace('storage/', 'public/', $existingImages[$index]);

                // Eliminar del almacenamiento
                if (Storage::exists($rutaImagen)) {
                    Storage::delete($rutaImagen);
                }

                // Eliminar de `existingImages` para que no se guarde en la BD
                unset($existingImages[$index]);
                unset($fotoEsTexto[$index], $imagesBase64[$index], $comments[$index]);
            }
        }

        // **Reiniciar el array antes de procesar imágenes**
        $imagenesGuardadas = [];

        // **Evitar duplicados en las rutas ya guardadas**
        $rutasGuardadas = [];

        // **2️⃣ Procesar imágenes existentes**
        foreach ($existingImages as $index => $ruta) {
            // Un espacio de descripción conserva su texto y distribución sin exigir una imagen.
            if (! empty($fotoEsTexto[$index])) {
                $distribucionFoto = $getDistribucionFoto($index);
                $imagenesGuardadas[] = [
                    'ruta' => $ruta ?: null,
                    'comentario' => $comments[$index] ?? '',
                    'es_cuadro_texto' => 1,
                    'una_hoja' => $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],
                    'es_disparo' => 0,
                    'numero_disparo' => null,
                ];
                if (! empty($ruta)) {
                    $rutasGuardadas[] = $ruta;
                }

                continue;
            }

            if ($request->hasFile("replace_images.$index") && empty($imagesBase64[$index])) {
                // **Reemplazo de imagen existente**
                $newImage = $request->file("replace_images.$index");

                // Eliminar imagen anterior si existe
                $rutaImagenPublic = str_replace('storage/', 'public/', $ruta);
                if (Storage::exists($rutaImagenPublic)) {
                    Storage::delete($rutaImagenPublic);
                }

                // Guardar la nueva imagen
                $imageName = 'imagen_'.time().'_'.$index.'.'.$newImage->getClientOriginalExtension();
                $path = $newImage->storeAs($rutaCarpeta, $imageName);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (! in_array($rutaNueva, $rutasGuardadas)) {
                    $distribucionFoto = $getDistribucionFoto($index);

                    $imagenesGuardadas[] = [
                        'ruta' => $rutaNueva,
                        'comentario' => $comments[$index] ?? '',
                        'es_cuadro_texto' => 0,
                        'una_hoja' => $distribucionFoto['una_hoja'],
                        'pagina' => $distribucionFoto['pagina'],
                        'posicion' => $distribucionFoto['posicion'],
                        'es_disparo' => ! empty($esDisparo[$index]) ? 1 : 0,
                        'numero_disparo' => ! empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                    ];
                    $rutasGuardadas[] = $rutaNueva; // Guardar ruta para evitar duplicados
                }
            } elseif (! empty($imagesBase64[$index])) {
                // **Procesar imágenes en Base64**
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imagesBase64[$index]));
                $imageName = 'imagen_'.time().'_'.$index.'.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (! in_array($rutaNueva, $rutasGuardadas)) {
                    $distribucionFoto = $getDistribucionFoto($index);

                    $imagenesGuardadas[] = [
                        'ruta' => $rutaNueva,
                        'comentario' => $comments[$index] ?? '',
                        'es_cuadro_texto' => 0,
                        'una_hoja' => $distribucionFoto['una_hoja'],
                        'pagina' => $distribucionFoto['pagina'],
                        'posicion' => $distribucionFoto['posicion'],
                        'es_disparo' => ! empty($esDisparo[$index]) ? 1 : 0,
                        'numero_disparo' => ! empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                    ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            } else {
                // **Mantener la imagen existente**
                if (! in_array($ruta, $rutasGuardadas)) {
                    $distribucionFoto = $getDistribucionFoto($index);

                    $imagenesGuardadas[] = [
                        'ruta' => $ruta,
                        'comentario' => $comments[$index] ?? '',
                        'es_cuadro_texto' => 0,
                        'una_hoja' => $distribucionFoto['una_hoja'],
                        'pagina' => $distribucionFoto['pagina'],
                        'posicion' => $distribucionFoto['posicion'],
                        'es_disparo' => ! empty($esDisparo[$index]) ? 1 : 0,
                        'numero_disparo' => ! empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                    ];
                    $rutasGuardadas[] = $ruta;
                }
            }
        }

        // **3️⃣ Procesar nuevas imágenes Base64**
        foreach (array_unique(array_merge(array_keys($imagesBase64), array_keys($fotoEsTexto))) as $index) {
            $base64Image = $imagesBase64[$index] ?? null;
            if (isset($existingImages[$index])) {
                continue; // ⛔ ya fue procesada arriba
            }

            // Las tarjetas en modo descripción se guardan aunque su campo de imagen esté vacío.
            if (! empty($fotoEsTexto[$index])) {
                $distribucionFoto = $getDistribucionFoto($index);
                $imagenesGuardadas[] = [
                    'ruta' => null,
                    'comentario' => $comments[$index] ?? '',
                    'es_cuadro_texto' => 1,
                    'una_hoja' => $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],
                    'es_disparo' => 0,
                    'numero_disparo' => null,
                ];

                continue;
            }

            if (! empty($base64Image)) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $imageName = 'imagen_'.time().'_'.$index.'.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen en el almacenamiento
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                if (! in_array($rutaNueva, $rutasGuardadas)) {
                    $distribucionFoto = $getDistribucionFoto($index);

                    $imagenesGuardadas[] = [
                        'ruta' => $rutaNueva,
                        'comentario' => $comments[$index] ?? '',
                        'es_cuadro_texto' => 0,
                        'una_hoja' => $distribucionFoto['una_hoja'],
                        'pagina' => $distribucionFoto['pagina'],
                        'posicion' => $distribucionFoto['posicion'],
                        'es_disparo' => ! empty($esDisparo[$index]) ? 1 : 0,
                        'numero_disparo' => ! empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                    ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            }
        }

        // **4️⃣ Guardar las imágenes actualizadas en la BD**
        if ($Fotos_Reportes) {
            $Fotos_Reportes->update([
                'Fotos_Reportes' => json_encode(array_values($imagenesGuardadas)), // Se usa array reindexado
            ]);
        } else {
            $Fotos_Reportes = new Fotos_Reporte;
            $Fotos_Reportes->idReportes = $id;
            $Fotos_Reportes->Fotos_Reportes = json_encode(array_values($imagenesGuardadas));
            $Fotos_Reportes->save();
        }

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'] ?? 'ESPERA DE DATOS';

        if ($request->hasFile('Analisis_PDF')) {
            foreach (array_unique($rutasPdfsXrfAnteriores) as $rutaAnterior) {
                $rutaDisco = str_replace('storage/', 'public/', $rutaAnterior);
                if (Storage::exists($rutaDisco)) {
                    Storage::delete($rutaDisco);
                }
            }
        }

        // Cualquier cambio produce una huella distinta y programa una nueva
        // version; el PDF anterior nunca se entrega como si estuviera vigente.
        try {
            app(\App\Services\ServicioPdfGenerado::class)->programar(
                (int) $id,
                '06_B_01',
                'es',
                (int) $request->user()->getAuthIdentifier()
            );
        } catch (\Throwable $error) {
            Log::warning('No fue posible reprogramar el PDF 06_B_01 actualizado.', [
                'reporte_id' => (int) $id,
                'error' => $error->getMessage(),
            ]);
        }

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_PIMP_06_B_01($id)
    {

        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes', $id)->firstOrFail();
        $Grupo_Juntas_Detalles_Re_Model = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $Firmas_Reportes = Firma_Reporte::where('idReportes', $id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes', $id)->first();

        // Decodificar el campo Detalles_Generales para obtener el nombre del proyecto
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true) ?: [];
        // Decodificar el campo Datos_Equipo para obtener el nombre del proyecto
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true) ?: [];

        // Bloques, Norma_IM y Patron_Grano se leen de Juntas (con compatibilidad para
        // reportes anteriores que los tenían en Detalles_Generales).
        $juntas = app(ServicioJuntasReporteIM::class)->normalizar(
            $Grupo_Juntas_Detalles_Re_Model?->Juntas_Grupo_Re,
            is_array($Detalles_Generales) ? $Detalles_Generales : []
        );

        $NormaIM = is_array($juntas['Norma_IM'] ?? null) ? $juntas['Norma_IM'] : [];

        // El servicio del patron necesita esta clave solo en memoria para el PDF.
        if ($juntas['Patron_Grano'] !== null) {
            $Detalles_Generales['PATRON_GRANO'] = $juntas['Patron_Grano'];
        } else {
            unset($Detalles_Generales['PATRON_GRANO']);
        }

        $Firmas_Reportes = json_decode($Firmas_Reportes?->Firmas ?? '[]', true) ?: [];
        $numFirmas = $Firmas_Reportes['numFirmas'] ?? 1;

        $Logo = public_path('images/Logo_AICO_R.jpg');
        $qrPdf = ! empty($Datos_Equipo['QR_PDF']) ? public_path($Datos_Equipo['QR_PDF']) : null;
        $qrPdf = ($qrPdf && File::exists($qrPdf)) ? $qrPdf : null;
        $Fotos = [];
        $Disparos = [];
        // Obtener las fotos con su comentario
        if ($Fotos_Reportes) {
            $fotos = json_decode($Fotos_Reportes->Fotos_Reportes, true) ?? [];

            foreach ($fotos as $foto) {
                $esCuadroTexto = !empty($foto['es_cuadro_texto']);
                $rutaFoto = null;
                if (! $esCuadroTexto) {
                    $rutaGuardada = trim((string) ($foto['ruta'] ?? ''));

                    // Una ruta vacia resolvia a storage/app/public (una carpeta existente),
                    // y Dompdf intentaba leerla como imagen. El anexo solo admite archivos reales.
                    if ($rutaGuardada === '') {
                        continue;
                    }

                    $rutaFoto = storage_path('app/public/'.ltrim(str_replace('storage/', '', $rutaGuardada), '/\\'));

                    if (! File::isFile($rutaFoto)) {
                        continue;
                    }
                }

                if (! empty($foto['es_disparo'])) {
                    $numeroDisparo = (int) ($foto['numero_disparo'] ?? 0);
                    if (in_array($numeroDisparo, [1, 2, 3], true)) {
                        $Disparos[$numeroDisparo][] = $rutaFoto;
                    }

                    continue;
                }

                $indiceFotoAdicional = count($Fotos);
                $distribucionFoto = $this->normalizeFotoLayout(
                    $foto['pagina'] ?? null,
                    $foto['posicion'] ?? null,
                    $indiceFotoAdicional
                );

                $Fotos[] = [
                    'path' => $rutaFoto,
                    'comment' => $foto['comentario'] ?? '',
                    'es_cuadro_texto' => $esCuadroTexto ? 1 : 0,
                    'una_hoja' => $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],

                ];
            }
        }

        // El tamaño de grano participa en la misma cuadrícula posicionable que las fotografías normales.
        app(ServicioPatronGranoReporte::class)->agregarAlPdf($Fotos, $Detalles_Generales);

        $data = [
            'Logo' => $Logo,
            // Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            // Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            'NormaIM' => $NormaIM,
            'QR_PDF' => $qrPdf,
            // Fotos_Reportes
            'Fotos' => $Fotos,
            'Disparos' => $Disparos,
            // Numero de Firmas
            'numFirmas' => $numFirmas,
            // Firmas
            'Firmas_Reportes' => $Firmas_Reportes,
        ];

        // Generar el PDF principal en orientación vertical
        $pdf1 = Pdf::loadView('Reportes.ReportesPDFIM.Reporte_FOR_PIMP_06_B_01_PDF', $data)->setPaper('letter', 'portrait');
        $pdf2Content = null;
        $pageCount2 = 0;

        if (! empty($Fotos)) {
            $pdf2 = Pdf::loadView('Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_06_B_01_PDF', $data)->setPaper('letter', 'portrait');
            $pdf2Content = $pdf2->output();
        }

        // Generar el PDF adicional en orientación vertical

        // Combinar los PDFs
        $pdf1Content = $pdf1->output();

        // Crear objetos FPDI independientes para contar páginas
        $tempPdf1 = new Fpdi;
        $pageCount1 = $tempPdf1->setSourceFile(StreamReader::createByString($pdf1Content));

        if ($pdf2Content) {
            $tempPdf2 = new Fpdi;
            $pageCount2 = $tempPdf2->setSourceFile(StreamReader::createByString($pdf2Content));
        }

        // Ahora sí combinamos
        $combinedPdf = new Fpdi;
        $totalPageCount = $pageCount1 + $pageCount2;
        // La pagina visible se calcula sobre toda la serie, no solo sobre el PDF actual.
        $paginacionSerie = app(ServicioSerieReportes::class)->registrarPaginas((int) $id, $totalPageCount);
        $paginaInicialSerie = $paginacionSerie['pagina_inicial'];
        $totalSerie = $paginacionSerie['total_estimado'];

        // Añadir páginas del primer PDF
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        for ($i = 1; $i <= $pageCount1; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $tamanoPagina = $combinedPdf->getTemplateSize($tplId);
            $orientacion = $tamanoPagina['width'] > $tamanoPagina['height'] ? 'L' : 'P';
            $combinedPdf->AddPage($orientacion, [$tamanoPagina['width'], $tamanoPagina['height']]);
            $combinedPdf->useTemplate($tplId, 0, 0, $tamanoPagina['width'], $tamanoPagina['height']);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY($orientacion === 'L' ? 220 : 153.5, 25.5);
            $paginaActualSerie = $paginaInicialSerie + $i - 1;
            $combinedPdf->MultiCell(24, 3.5, "$paginaActualSerie DE $totalSerie"."\n"."$paginaActualSerie of $totalSerie", 0, 'C');
        }

        // Añadir páginas del segundo PDF

        if ($pdf2Content) {
            $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
            for ($i = 1; $i <= $pageCount2; $i++) {
                $tplId = $combinedPdf->importPage($i);
                $tamanoPagina = $combinedPdf->getTemplateSize($tplId);
                $orientacion = $tamanoPagina['width'] > $tamanoPagina['height'] ? 'L' : 'P';
                $combinedPdf->AddPage($orientacion, [$tamanoPagina['width'], $tamanoPagina['height']]);
                $combinedPdf->useTemplate($tplId, 0, 0, $tamanoPagina['width'], $tamanoPagina['height']);
                $combinedPdf->SetFont('Arial', 'B', 8);
                $paginaActual = $paginaInicialSerie + $pageCount1 + $i - 1;
                $combinedPdf->SetXY($orientacion === 'L' ? 220 : 153.5, 25.5);
                $combinedPdf->MultiCell(24, 3.5, "$paginaActual DE $totalSerie"."\n"."$paginaActual of $totalSerie", 0, 'C');
            }
        }

        return response($combinedPdf->Output('Reporte_FOR_PIMP_06_B_01.PDF', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
    }

    /** Devuelve las rutas posibles de un PDF guardado en BD sin asumir un solo prefijo. */
    private function getPdfCandidatePaths($rutaDb)
    {
        if (empty($rutaDb)) {
            return [];
        }

        $ruta = trim(str_replace('\\', '/', $rutaDb));
        if ($ruta === '') {
            return [];
        }

        $ruta = preg_replace('#^/?storage/#', '', $ruta);
        $ruta = preg_replace('#^/?public/#', '', $ruta);
        $ruta = ltrim($ruta, '/');

        $candidates = [];

        if (preg_match('#^([A-Za-z]:[\\/]|/)#', $rutaDb)) {
            $candidates[] = $rutaDb;
        }

        $candidates[] = storage_path('app/public/'.$ruta);
        $candidates[] = storage_path($ruta);
        $candidates[] = public_path($ruta);
        $candidates[] = public_path('storage/'.$ruta);
        $candidates[] = public_path('public/'.$ruta);

        return array_values(array_unique($candidates));
    }

    /** Resuelve el archivo real para facturas, certificados y procedimientos anexados al QR. */
    private function resolvePdfPath($rutaDb)
    {
        foreach ($this->getPdfCandidatePaths($rutaDb) as $candidate) {
            if ($candidate && is_file($candidate)) {
                return $candidate;
            }
        }

        $ruta = trim(str_replace('\\', '/', $rutaDb));
        $ruta = preg_replace('#^/?storage/#', '', $ruta);
        $ruta = preg_replace('#^/?public/#', '', $ruta);
        $ruta = ltrim($ruta, '/');

        $storagePublicDisk = Storage::disk('public');
        if ($storagePublicDisk->exists($ruta)) {
            return $storagePublicDisk->path($ruta);
        }

        return null;
    }

    /** Busca Ghostscript por variable de entorno, rutas comunes o PATH antes de compatibilizar PDFs. */
    private function detectGhostscriptBinary()
    {
        $candidates = [];

        foreach ([getenv('GHOSTSCRIPT_BIN'), getenv('GS_BIN'), getenv('GS_PATH')] as $envCandidate) {
            if (! empty($envCandidate)) {
                $candidates[] = $envCandidate;
            }
        }

        $candidates = array_merge($candidates, [
            'C:\\Program Files\\gs\\gs10.07.1\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs10.07.1\\bin\\gswin64c',
            'C:\\Program Files\\gs\\gs10.03.1\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs10.03.1\\bin\\gswin64c',
            'C:\\Program Files\\gs\\gs10.02.1\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs10.02.1\\bin\\gswin64c',
            'C:\\Program Files\\gs\\gs9.56.1\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs9.56.1\\bin\\gswin64c',
            'C:\\Program Files\\gs\\gs9.55.0\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs9.55.0\\bin\\gswin64c',
        ]);

        if (is_dir('C:\\Program Files\\gs')) {
            foreach (glob('C:\\Program Files\\gs\\*\\bin\\gswin64c*') ?: [] as $path) {
                $candidates[] = $path;
            }
            foreach (glob('C:\\Program Files\\gs\\*\\bin\\gswin32c*') ?: [] as $path) {
                $candidates[] = $path;
            }
            foreach (glob('C:\\Program Files\\gs\\*\\bin\\gs*') ?: [] as $path) {
                $candidates[] = $path;
            }
        }

        foreach (['gswin64c.exe', 'gswin64c', 'gswin32c.exe', 'gswin32c', 'gs.exe', 'gs'] as $name) {
            $candidates[] = $name;
        }

        foreach (array_unique($candidates) as $candidate) {
            if (empty($candidate)) {
                continue;
            }

            if (is_string($candidate) && is_file($candidate)) {
                return $candidate;
            }

            if (PHP_OS_FAMILY === 'Windows') {
                $command = 'where.exe '.escapeshellarg($candidate).' 2>nul';
                exec($command, $output, $exitCode);

                if ($exitCode === 0 && ! empty($output[0])) {
                    return trim($output[0]);
                }
            } else {
                $command = 'command -v '.escapeshellarg($candidate).' 2>/dev/null';
                exec($command, $output, $exitCode);

                if ($exitCode === 0 && ! empty($output[0])) {
                    return trim($output[0]);
                }
            }
        }

        return null;
    }

    /** Traduce los fallos del lector PDF en errores vinculados al archivo correspondiente. */
    private function analizarPdfsXrf(array $archivos, ServicioAnalisisPdfXrf $service): array
    {
        $analisis = [];

        foreach ($archivos as $index => $archivo) {
            try {
                $analisis[] = $service->parseUploadedFile($archivo);
            } catch (\Throwable $exception) {
                Log::warning('No se pudo extraer el PDF XRF.', [
                    'archivo' => $archivo->getClientOriginalName(),
                    'error' => $exception->getMessage(),
                ]);

                throw \Illuminate\Validation\ValidationException::withMessages([
                    "Analisis_PDF.{$index}" => "No se pudo leer {$archivo->getClientOriginalName()}: {$exception->getMessage()}",
                ]);
            }
        }

        return $analisis;
    }

    /** Conserva los PDF originales y registra la ruta dentro del análisis histórico. */
    private function guardarPdfsXrf(array $archivos, array &$analisis, string $contrato, string $numeroReporte): void
    {
        $contratoSeguro = Str::slug($contrato ?: 'sin-contrato');
        $reporteSeguro = Str::slug($numeroReporte ?: 'sin-reporte');
        $directorio = "public/Reportes/FOR_PIMP_06_B_01/{$contratoSeguro}/{$reporteSeguro}/Analisis_XRF";

        foreach ($archivos as $index => $archivo) {
            $base = Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'analisis-xrf';
            $nombre = $base.'-'.Str::lower(Str::random(8)).'.pdf';
            $archivo->storeAs($directorio, $nombre);

            if (isset($analisis[$index])) {
                $analisis[$index]['ruta'] = str_replace('public/', 'storage/', $directorio).'/'.$nombre;
            }
        }
    }

    /**
     * Evita mezclar grados entre archivos. Una diferencia contra la norma elegida
     * se informa, pero no bloquea el calculo porque la seleccion del usuario manda.
     */
    private function validarCompatibilidadXrf(
        array $analisis,
        string $nombreEspecificacion,
        string $variable,
        ServicioAnalisisPdfXrf $service
    ): ?string {
        try {
            $service->assertCompatibleWithNorm(
                $analisis,
                $nombreEspecificacion,
                $variable
            );

            return null;
        } catch (\RuntimeException $exception) {
            if (! str_starts_with($exception->getMessage(), 'Los PDF no corresponden al mismo grado:')) {
                return $exception->getMessage()
                    .' Se calcularon los resultados con la norma seleccionada por el usuario.';
            }

            throw \Illuminate\Validation\ValidationException::withMessages([
                'Norma_IM.idnormas_im' => $exception->getMessage(),
            ]);
        }
    }

    /** Asegura páginas y cuadrantes válidos para las fotos normales del reporte. */
    private function normalizeFotoLayout($pagina, $posicion, $index): array
    {
        $posicionesPermitidas = [
            'arriba_izquierda',
            'arriba_derecha',
            'abajo_izquierda',
            'abajo_derecha',
            'pagina_completa',
        ];
        $posicionesPredeterminadas = array_slice($posicionesPermitidas, 0, 4);
        $indice = max(0, (int) $index);
        $paginaNormalizada = max(1, (int) ($pagina ?: (intdiv($indice, 4) + 1)));
        $posicionNormalizada = in_array($posicion, $posicionesPermitidas, true)
            ? $posicion
            : $posicionesPredeterminadas[$indice % 4];

        return [
            'pagina' => $paginaNormalizada,
            'posicion' => $posicionNormalizada,
            'una_hoja' => $posicionNormalizada === 'pagina_completa' ? 1 : 0,
        ];
    }

    /** Separa el acomodo fijo de disparos del acomodo manual de fotografías generales. */
    private function normalizeStoredPhotoLayout(
        $pagina,
        $posicion,
        $index,
        bool $esDisparo,
        $numeroDisparo
    ): array {
        if (! $esDisparo) {
            return $this->normalizeFotoLayout($pagina, $posicion, $index);
        }

        $numero = max(1, min(3, (int) $numeroDisparo));

        return [
            'pagina' => $numero,
            'posicion' => ((int) $index % 2 === 0) ? 'arriba_izquierda' : 'arriba_derecha',
            'una_hoja' => 0,
        ];
    }

    /** Exige exactamente dos fotografías para cada disparo utilizado. */
    private function validarFotosDeDisparos(array $imagenes): void
    {
        $conteo = [1 => 0, 2 => 0, 3 => 0];

        foreach ($imagenes as $imagen) {
            if (empty($imagen['es_disparo'])) {
                continue;
            }

            $numero = (int) ($imagen['numero_disparo'] ?? 0);
            if (! isset($conteo[$numero])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'numero_disparo' => 'Selecciona a qué disparo pertenece cada fotografía marcada.',
                ]);
            }
            $conteo[$numero]++;
        }

        foreach ($conteo as $numero => $cantidad) {
            if ($cantidad !== 0 && $cantidad !== 2) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'numero_disparo' => "El {$numero}° disparo debe tener exactamente dos fotografías.",
                ]);
            }
        }
    }

    /** Genera una copia histórica de la norma para que el reporte no cambie si cambia el catálogo. */
    private function construirNormaIM(Request $request, ?array $normaHistorica = null): ?array
    {
        $idNorma = (int) $request->input('Norma_IM.idnormas_im', 0);

        if ($idNorma <= 0) {
            return null;
        }

        $norma = Normas_IM::find($idNorma);

        if ($norma) {
            $nombreEspecificacion = $norma->Nombre_Espe;
            $variable = $norma->Variable;
            $observaciones = $norma->Observaciones;
            $filasCatalogo = json_decode($norma->Tabla, true);
        } elseif (
            is_array($normaHistorica)
            && (int) ($normaHistorica['idnormas_im'] ?? 0) === $idNorma
        ) {
            // El reporte conserva su copia aunque la norma se elimine del catalogo.
            $nombreEspecificacion = $normaHistorica['Nombre_Espe'] ?? '';
            $variable = $normaHistorica['Variable'] ?? '';
            $observaciones = $normaHistorica['Observaciones'] ?? '';
            $filasCatalogo = $normaHistorica['Tabla'] ?? [];
        } else {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'Norma_IM.idnormas_im' => 'La norma seleccionada ya no esta disponible.',
            ]);
        }

        $filasCatalogo = is_array($filasCatalogo) ? $filasCatalogo : [];
        $promedios = $request->input('Norma_IM.Promedio', []);
        $analisisPdf = [];

        if ($request->hasFile('Analisis_PDF')) {
            $service = app(ServicioAnalisisPdfXrf::class);
            $analisisPdf = $this->analizarPdfsXrf($request->file('Analisis_PDF', []), $service);
            $this->validarCompatibilidadXrf(
                $analisisPdf,
                (string) $nombreEspecificacion,
                (string) $variable,
                $service
            );
            $elementos = array_map(
                static fn ($fila) => is_array($fila) ? (string) ($fila['Elemento'] ?? '') : '',
                $filasCatalogo
            );
            $promediosExtraidos = $service->averageForElements($analisisPdf, $elementos);

            foreach ($filasCatalogo as $indice => $filaCatalogo) {
                if (! is_array($filaCatalogo)) {
                    continue;
                }

                $elemento = $service->canonicalElement((string) ($filaCatalogo['Elemento'] ?? ''));
                $resultado = $promediosExtraidos[$elemento] ?? null;
                // Un elemento ausente, ND o limitado con < / > no genera un
                // promedio parcial engañoso: el resultado oficial queda como ND.
                $promedios[$indice] = (string) ($resultado['valor_reporte'] ?? 'ND');
            }
        } elseif (is_array($normaHistorica)
            && (int) ($normaHistorica['idnormas_im'] ?? 0) === $idNorma) {
            $analisisPdf = is_array($normaHistorica['Analisis_PDF'] ?? null)
                ? $normaHistorica['Analisis_PDF']
                : [];
        }

        $filas = [];

        foreach ($filasCatalogo as $indice => $filaCatalogo) {
            if (! is_array($filaCatalogo)) {
                continue;
            }

            $promedioGuardado = trim((string) ($promedios[$indice] ?? ($filaCatalogo['Promedio'] ?? '')));

            $filas[] = [
                'Elemento' => (string) ($filaCatalogo['Elemento'] ?? ''),
                // Evita celdas vacias en la edicion y en el PDF historico.
                'Promedio' => $promedioGuardado !== '' ? $promedioGuardado : 'ND',
                'Composicion' => (string) ($filaCatalogo['Composicion'] ?? ''),
            ];
        }

        return [
            'idnormas_im' => $idNorma,
            'Nombre_Espe' => $nombreEspecificacion,
            'Variable' => $variable,
            'Observaciones' => $observaciones,
            'Tabla' => $filas,
            'Analisis_PDF' => $analisisPdf,
        ];
    }

    /**
     * Construye los bloques (filas, títulos y longitudes) a partir del request.
     * No toca BD ni archivos: solo lee el request. Misma lógica que usaba update().
     */
    private function Datos_QR($datosParaCrearQR)
    {
        $Contrato = $datosParaCrearQR['Contrato'] ?? 'SinContrato';
        $No_Reporte = $datosParaCrearQR['No_Reporte'] ?? 'SinReporte';
        $token = $datosParaCrearQR['qr_token'] ?? null;
        $idProcedimiento = $datosParaCrearQR['idProcedimiento'] ?? null;
        $idTecnico = $datosParaCrearQR['ID_TECNICO'] ?? null;

        $idsConsumibles = array_filter([
            $datosParaCrearQR['idEquipo'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | OBTENER FACTURAS Y CERTIFICADOS
        |--------------------------------------------------------------------------
        */

        $facturas = general_eyc::whereIn('idGeneral_EyC', $idsConsumibles)
            ->whereNotNull('Factura')
            ->pluck('Factura')
            ->toArray();

        $certificados = certificados::whereIn('idGeneral_EyC', $idsConsumibles)
            ->whereNotNull('Certificado_Actual')
            ->pluck('Certificado_Actual')
            ->toArray();

        // El QR de IM debe anexar los documentos de equipos y, cuando exista, el procedimiento usado.
        $procedimientos = $idProcedimiento
            ? Procedimiento::where('idProcedimiento', $idProcedimiento)
                ->whereNotNull('PDF')
                ->pluck('PDF')
                ->toArray()
            : [];

        // Igual que en PINS, si se seleccionó un técnico se anexa su CV al PDF vinculado por QR.
        $tecnicos = $idTecnico
            ? Usuario::where('id', $idTecnico)
                ->whereNotNull('cv_pdf')
                ->pluck('cv_pdf')
                ->toArray()
            : [];

        $todasLasRutas = array_values(array_merge($facturas, $certificados, $procedimientos, $tecnicos));

        /*
        |--------------------------------------------------------------------------
        | FILTRAR RUTAS INVALIDAS
        |--------------------------------------------------------------------------
        */

        $rutasInvalidas = ['EN ESPERA DE DATOS', 'ESPERA DE DATO', 'N/A'];

        $rutasValidas = array_filter($todasLasRutas, function ($ruta) use ($rutasInvalidas) {
            if (! $ruta) {
                return false;
            }

            return ! in_array(trim(strtoupper($ruta)), $rutasInvalidas);
        });

        /*
        |--------------------------------------------------------------------------
        | DIRECTORIO TEMPORAL
        |--------------------------------------------------------------------------
        */

        $directorioTemporal = storage_path("app/temp_pdfs/FOR_PIMP_06_B_01/{$Contrato}/{$No_Reporte}");

        if (! File::exists($directorioTemporal)) {
            File::makeDirectory($directorioTemporal, 0777, true);
        }

        $pdfsTemporales = [];

        /*
        |--------------------------------------------------------------------------
        | COPIAR PDFs TEMPORALES
        |--------------------------------------------------------------------------
        */

        foreach ($rutasValidas as $rutaPdf) {
            $rutaOriginal = $this->resolvePdfPath($rutaPdf);

            if (! $rutaOriginal || ! File::exists($rutaOriginal)) {
                Log::warning('PDF no encontrado para anexar en QR 06_B_01', [
                    'rutaDb' => $rutaPdf,
                    'rutaOriginal' => $rutaOriginal,
                    'rutasProbadas' => $this->getPdfCandidatePaths($rutaPdf),
                ]);

                continue;
            }

            $nombreArchivo = basename($rutaOriginal);
            $rutaTemporal = $directorioTemporal.DIRECTORY_SEPARATOR.$nombreArchivo;

            File::copy($rutaOriginal, $rutaTemporal);
            $pdfsTemporales[] = $rutaTemporal;
        }

        /*
        |--------------------------------------------------------------------------
        | GENERAR TOKEN QR PUBLICO
        |--------------------------------------------------------------------------
        */

        $rutaPublicaPdf = route('qr.reporte', ['token' => $token]);
        $nombreQR = "QR_{$Contrato}_{$No_Reporte}.svg";
        $directorioQR = storage_path("app/public/Reportes/FOR_PIMP_06_B_01/{$Contrato}/{$No_Reporte}/QR_REPORTES");

        if (! File::exists($directorioQR)) {
            File::makeDirectory($directorioQR, 0777, true);
        }

        $rutaQrCompleta = $directorioQR.DIRECTORY_SEPARATOR.$nombreQR;

        \QrCode::format('svg')
            ->size(300)
            ->margin(0)
            ->generate($rutaPublicaPdf, $rutaQrCompleta);

        $rutaQrPublica = "storage/Reportes/FOR_PIMP_06_B_01/{$Contrato}/{$No_Reporte}/QR_REPORTES/".$nombreQR;

        /*
        |--------------------------------------------------------------------------
        | VALIDAR PDFs
        |--------------------------------------------------------------------------
        */

        if (empty($pdfsTemporales)) {
            Log::warning('No hay PDFs válidos para unir.');

            return [
                'pdf' => null,
                'qr' => $rutaQrPublica,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | UNIR PDFs
        |--------------------------------------------------------------------------
        */

        $pdf = new Fpdi;
        $ghostscript = $this->detectGhostscriptBinary();

        foreach ($pdfsTemporales as $archivoPdf) {
            try {
                /*
                |--------------------------------------------------------------------------
                | HACER PDF COMPATIBLE CON FPDI
                |--------------------------------------------------------------------------
                */

                $archivoCompatible = str_replace('.pdf', '_compatible.pdf', $archivoPdf);
                $archivoParaImportar = $archivoPdf;

                if ($ghostscript) {
                    $comando =
                        escapeshellarg($ghostscript).' -sDEVICE=pdfwrite '
                        .'-dCompatibilityLevel=1.4 '
                        .'-dNOPAUSE '
                        .'-dQUIET '
                        .'-dBATCH '
                        .'-sOutputFile='.escapeshellarg($archivoCompatible).' '
                        .escapeshellarg($archivoPdf);

                    exec($comando, $salidaGhostscript, $codigoGhostscript);

                    if ($codigoGhostscript === 0 && File::exists($archivoCompatible)) {
                        $archivoParaImportar = $archivoCompatible;
                    } else {
                        Log::warning('Ghostscript no pudo compatibilizar PDF 06_B_01; se intentara leer original.', [
                            'archivo' => $archivoPdf,
                            'codigo' => $codigoGhostscript,
                        ]);
                    }
                }

                $cantidadPaginas = $pdf->setSourceFile($archivoParaImportar);

                for ($pagina = 1; $pagina <= $cantidadPaginas; $pagina++) {
                    $template = $pdf->importPage($pagina);
                    $size = $pdf->getTemplateSize($template);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($template);
                }
            } catch (\Exception $e) {
                Log::error('Error procesando PDF', [
                    'archivo' => $archivoPdf,
                    'error' => $e->getMessage(),
                ]);

                continue;
            }
        }

        $directorioFinal = "Reportes/FOR_PIMP_06_B_01/{$Contrato}/{$No_Reporte}/";
        $rutaDirectorioFinal = storage_path('app/public/'.$directorioFinal);

        if (! File::exists($rutaDirectorioFinal)) {
            File::makeDirectory($rutaDirectorioFinal, 0777, true);
        }

        $nombreArchivoFinal = "QR_FOR_PIMP_06_B_01_{$Contrato}_{$No_Reporte}.pdf";
        $rutaPdfFinal = $rutaDirectorioFinal.$nombreArchivoFinal;

        $pdf->Output($rutaPdfFinal, 'F');

        File::deleteDirectory($directorioTemporal);

        return [
            'pdf' => 'storage/'.$directorioFinal.$nombreArchivoFinal,
            'qr' => $rutaQrPublica,
        ];
    }

    private function OS_OC($datosParaCrearOS_OC)
    {
        $idPrueba_Aplica = $datosParaCrearOS_OC['idPrueba_Aplica'];
        $Cliente = $datosParaCrearOS_OC['Cliente'];
        $Lugar = $datosParaCrearOS_OC['Lugar'] ?? $datosParaCrearOS_OC['Instalacion'] ?? 'ESPERA DE DATOS';
        $Contrato = $datosParaCrearOS_OC['Contrato'];
        $Proyecto = $datosParaCrearOS_OC['Proyecto'] ?? 'ESPERA DE DATOS';
        $Material = $datosParaCrearOS_OC['Material'];
        $Isometrico_Plano = $datosParaCrearOS_OC['Isometrico_Plano'] ?? $datosParaCrearOS_OC['No_Isometrico'] ?? 'ESPERA DE DATOS';
        $idSolicitud = $datosParaCrearOS_OC['idSolicitud'];
        $idReportes = $datosParaCrearOS_OC['idReportes'];
        $EsperaDato = 'ESPERA DE DATOS';

        $Orden_Servicio = new Orden_Servicio;
        $Orden_Servicio_Prueba = new Orden_Servicio_Prueba;
        $Firmantes_OS = new Firmantes_OS;
        $Grupo_Juntas_Detalles_OS = new Grupo_Juntas_Detalles_OS;
        $OC = new OC;
        $Detalles_OC = new detallesOC;
        $Lineal_Ideal = new Lineal_Ideal;

        $BusquedaCliente = clientes::where('Cliente', 'like', '%'.$Cliente.'%')->first();

        if (! $BusquedaCliente) {
            $BusquedaCliente = new clientes;
            $BusquedaCliente->Cliente = $Cliente;
            $BusquedaCliente->RFC = $EsperaDato;
            $BusquedaCliente->Telefono = $EsperaDato;
            $BusquedaCliente->Correo = $EsperaDato;
            $BusquedaCliente->Logo = $EsperaDato;
            $BusquedaCliente->portal_token = (string) Str::uuid();
            $BusquedaCliente->save();
        }

        $idCliente = $BusquedaCliente->idClientes;
        $BusquedaContratoOS = Orden_Servicio::where('Contrato', $Contrato)->first();

        if ($BusquedaContratoOS) {
            $idOrdenServicio = $BusquedaContratoOS->idOrden_Servicio;
        } else {
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
            $Grupo_Juntas_Detalles_OS->Juntas_grupo = null; // XRF no captura filas de inspeccion.
            $Grupo_Juntas_Detalles_OS->save();

        }

        $BusquedaContratoOC = OC::where('Contrato', $Contrato)->first();

        if ($BusquedaContratoOC) {
            $idOC = $BusquedaContratoOC->idOC;
        } else {
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->idClientes = $idCliente;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001/01/01';
            $OC->Tipo_Servicio = $EsperaDato;
            $OC->Estatus = 'OC';
            $OC->OC_archivo = $EsperaDato;
            $OC->save();

            $idOC = $OC->idOC;
            $Detalles_OC->idOC = $idOC;
            $Detalles_OC->Detalles = '[]';
            $Detalles_OC->NumCotizacion = $EsperaDato;
            $Detalles_OC->SolicitudCliente = $EsperaDato;
            $Detalles_OC->Contacto = $EsperaDato;
            $Detalles_OC->Puesto = $EsperaDato;
            $Detalles_OC->Ciudad = $EsperaDato;
            $Detalles_OC->Telefono = $EsperaDato;
            $Detalles_OC->Correo = $EsperaDato;
            $Detalles_OC->Vigencia = $EsperaDato;
            $Detalles_OC->Notas = $EsperaDato;
            $Detalles_OC->Condiciones_pago = $EsperaDato;
            $Detalles_OC->Condiciones_generales = $EsperaDato;
 	        $Detalles_OC->save();
        }

        $Lineal_Ideal->idOC = $idOC;
        $Lineal_Ideal->idOrden_Servicio = $idOrdenServicio;
        $Lineal_Ideal->idSolicitud = $idSolicitud;
        $Lineal_Ideal->idReportes = $idReportes;
        $Lineal_Ideal->idEncuesta = null;
        $Lineal_Ideal->Estatus = 'CREADO';
        $Lineal_Ideal->save();

    }

    /** Usa el mismo grupo de firmas para el guardado, el PDF y el expediente QR. */
    private function construirFirmas(array $datos): array
    {
        $cantidad = (int) ($datos['numFirmas'] ?? 1);
        $firmas = $datos['Firmas_Reportes'.$cantidad] ?? [];
        $firmas['numFirmas'] = $cantidad;

        return $firmas;
    }

    /** Valida lo que capturan Create y Edit; Edit admite la norma historica. */
    private function reglasValidacion(bool $actualizando = false): array
    {
        $reglas = [
            /* DETALLES GENERALES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.Fecha' => 'nullable|date',
            'Detalles_Generales.No_Reporte' => 'nullable|string',
            'Detalles_Generales.Cliente' => 'nullable|string',
            'Detalles_Generales.Contrato' => 'nullable|string',
            'Detalles_Generales.Proyecto' => 'nullable|string',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string',
            'Detalles_Generales.Folio' => 'nullable|string',
            'Detalles_Generales.Partida' => 'nullable|string',
            'Detalles_Generales.Instalacion' => 'nullable|string',
            'Detalles_Generales.No_Isometrico' => 'nullable|string',
            'Detalles_Generales.Nom_Pieza' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.No_Junta' => 'nullable|string',
            'Detalles_Generales.Trazabilidad' => 'nullable|string',
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.idProcedimiento' => 'nullable|integer',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            // El primer reporte define una sola vez el total planeado de la serie.
            'Serie_Reportes.cantidad_planificada' => 'required|integer|min:1|max:999',

            /* DATOS DEL EQUIPO Y OBSERVACIONES */
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO' => 'nullable|string',

            'Norma_IM' => 'nullable|array',
            'Norma_IM.idnormas_im' => 'nullable|required_with:Analisis_PDF|integer|exists:Normas_IM,idnormas_im',
            'Norma_IM.Promedio' => 'nullable|array',
            'Norma_IM.Promedio.*' => 'nullable|string|max:255',
            'Analisis_PDF' => 'nullable|array|max:10',
            'Analisis_PDF.*' => 'file|mimes:pdf|max:10240',
            'Patron_Grano' => 'nullable|array',
            'Patron_Grano.id' => 'nullable|required_if:Patron_Grano.activo,1|integer|min:1',
            'Patron_Grano.descripcion' => 'nullable|required_with:Patron_Grano.id|string|max:500',
            'Patron_Grano.activo' => 'nullable|boolean',
            'Patron_Grano.usar_version_catalogo' => 'nullable|boolean',
            'Patron_Grano.layout' => 'nullable|array',
            'Patron_Grano.layout.pagina' => 'nullable|integer|min:1|max:999',
            'Patron_Grano.layout.posicion' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string|max:5000',
            'foto_es_texto' => 'nullable|array',
            'foto_es_texto.*' => 'nullable|boolean',
            'foto_pagina' => 'nullable|array',
            'foto_pagina.*' => 'nullable|integer|min:1',
            'foto_posicion' => 'nullable|array',
            'foto_posicion.*' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',

            // Validar el campo NumFirmas
            'numFirmas' => 'required|integer|in:1,2,3,4',

            /* 1 FIRMAS */
            'Firmas_Reportes1' => 'required|array',  // Asegura que es un array

            'Firmas_Reportes1.Realizo' => 'nullable|string',
            'Firmas_Reportes1.ID_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.EMPRESA_TECNICO' => 'nullable|string',

            /* 2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string',
            'Firmas_Reportes2.Vobo1' => 'nullable|string',

            'Firmas_Reportes2.ID_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string',

            /* 3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.Realizo' => 'nullable|string',
            'Firmas_Reportes3.Vobo1' => 'nullable|string',
            'Firmas_Reportes3.Vobo2' => 'nullable|string',

            'Firmas_Reportes3.ID_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.NUMERO_FICHA' => 'nullable|string',

            /* 4 FIRMAS */
            'Firmas_Reportes4' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes4.Realizo' => 'nullable|string',
            'Firmas_Reportes4.Vobo1' => 'nullable|string',
            'Firmas_Reportes4.Vobo2' => 'nullable|string',
            'Firmas_Reportes4.Vobo3' => 'nullable|string',

            'Firmas_Reportes4.ID_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_3RO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NUMERO_FICHA' => 'nullable|string',
        ];

        if ($actualizando) {
            $reglas['Detalles_Generales.Reporte_Firmado'] = 'nullable|file|mimes:pdf';
            $reglas['Serie_Reportes.cantidad_planificada'] = 'nullable|integer|min:1|max:999';
            $reglas['Serie_Reportes.nueva_cantidad'] = 'nullable|integer|min:1|max:999';
            $reglas['Norma_IM.idnormas_im'] = 'nullable|required_with:Analisis_PDF|integer';
        }

        return $reglas;
    }
}
