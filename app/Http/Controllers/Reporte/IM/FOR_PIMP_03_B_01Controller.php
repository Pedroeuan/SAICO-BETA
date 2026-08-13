<?php

namespace App\Http\Controllers\Reporte\IM;

use App\Http\Controllers\Controller;
use App\Models\Admin\Usuario;
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
use App\Models\EquiposyConsumibles\certificados;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;
use App\Models\OrdenServicio\Orden_Servicio_Prueba;
use App\Models\OrdenServicio\Grupo_Juntas_Detalles_OS;
use App\Services\ServicioAnalisisImagenImageJ;
use App\Services\ServicioMetalografiaReporte;
use App\Services\ServicioPatronGranoReporte;
use App\Models\Procedimientos\Procedimiento;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/*PDF */
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Barryvdh\DomPDF\Facade\Pdf;

class FOR_PIMP_03_B_01Controller extends Controller
{
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

        $candidates[] = storage_path('app/public/' . $ruta);
        $candidates[] = storage_path($ruta);
        $candidates[] = public_path($ruta);
        $candidates[] = public_path('storage/' . $ruta);
        $candidates[] = public_path('public/' . $ruta);

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
            if (!empty($envCandidate)) {
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
                $command = 'where.exe ' . escapeshellarg($candidate) . ' 2>nul';
                exec($command, $output, $exitCode);

                if ($exitCode === 0 && !empty($output[0])) {
                    return trim($output[0]);
                }
            } else {
                $command = 'command -v ' . escapeshellarg($candidate) . ' 2>/dev/null';
                exec($command, $output, $exitCode);

                if ($exitCode === 0 && !empty($output[0])) {
                    return trim($output[0]);
                }
            }
        }

        return null;
    }
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
        $posicionNormalizada = in_array($posicion, $posicionesPermitidas, true)
            ? $posicion
            : $posicionesPredeterminadas[$indice % 4];

        return [
            'pagina' => max(1, (int) ($pagina ?: (intdiv($indice, 4) + 1))),
            'posicion' => $posicionNormalizada,
            'una_hoja' => $posicionNormalizada === 'pagina_completa' ? 1 : 0,
        ];
    }

    /**
     * Reserva dentro del PDF principal las dos celdas elegidas para Fiji y evita
     * que una fotografía manual las sustituya silenciosamente.
     */
    private function validarDistribucionFotosDelRequest(Request $request): void
    {
        $imagesBase64 = $request->input('images_base64', []);
        $existingImages = $request->input('existing_images', []);
        $fotoEsTexto = $request->input('foto_es_texto', []);
        $fotoPaginas = $request->input('foto_pagina', []);
        $fotoPosiciones = $request->input('foto_posicion', []);
        $eliminadas = array_map(
            'strval',
            array_filter($request->input('deleted_images', []), static fn ($indice) => $indice !== '')
        );
        $ocupadas = [];

        if ($request->boolean('Analisis_Imagen_Usar_Reporte')
            && trim((string) $request->input('Analisis_Imagen_Token', '')) !== '') {
            $layout = app(ServicioMetalografiaReporte::class)->normalizarLayoutAnalisis(
                $request->input('Analisis_Reporte_Layout')
            );

            foreach ([$layout['imagen'], $layout['resultados']] as $celda) {
                $pagina = (int) $celda['pagina'];
                $posicion = (string) $celda['posicion'];
                $ocupadas[$pagina] = $ocupadas[$pagina] ?? [];

                if (($posicion === 'pagina_completa' && $ocupadas[$pagina] !== [])
                    || in_array('pagina_completa', $ocupadas[$pagina], true)
                    || in_array($posicion, $ocupadas[$pagina], true)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'foto_posicion' => "La distribución de Fiji repite una posición en la hoja {$pagina}.",
                    ]);
                }
                $ocupadas[$pagina][] = $posicion;
            }
        }

        if ($request->boolean('Patron_Grano.activo')
            && (int) $request->input('Patron_Grano.id', 0) > 0) {
            $layoutPatron = $this->normalizeFotoLayout(
                $request->input('Patron_Grano.layout.pagina', 1),
                $request->input('Patron_Grano.layout.posicion', 'abajo_izquierda'),
                2
            );
            $pagina = $layoutPatron['pagina'];
            $posicion = $layoutPatron['posicion'];
            $ocupadas[$pagina] = $ocupadas[$pagina] ?? [];

            if (($posicion === 'pagina_completa' && $ocupadas[$pagina] !== [])
                || in_array('pagina_completa', $ocupadas[$pagina], true)
                || in_array($posicion, $ocupadas[$pagina], true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'foto_posicion' => "La posición del tamaño de grano ya está ocupada en la hoja {$pagina}.",
                ]);
            }
            $ocupadas[$pagina][] = $posicion;
        }

        foreach (array_unique(array_merge(
            array_keys($imagesBase64),
            array_keys($existingImages),
            array_keys($fotoEsTexto)
        )) as $index) {
            if (in_array((string) $index, $eliminadas, true)
                || (empty($imagesBase64[$index])
                    && empty($existingImages[$index])
                    && empty($fotoEsTexto[$index]))) {
                continue;
            }

            $layout = $this->normalizeFotoLayout(
                $fotoPaginas[$index] ?? null,
                $fotoPosiciones[$index] ?? null,
                (int) $index
            );
            $pagina = $layout['pagina'];
            $posicion = $layout['posicion'];
            $ocupadas[$pagina] = $ocupadas[$pagina] ?? [];

            if (($posicion === 'pagina_completa' && $ocupadas[$pagina] !== [])
                || in_array('pagina_completa', $ocupadas[$pagina], true)
                || in_array($posicion, $ocupadas[$pagina], true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'foto_posicion' => "La posición " . str_replace('_', ' ', $posicion)
                        . " de la hoja {$pagina} ya está ocupada.",
                ]);
            }
            $ocupadas[$pagina][] = $posicion;
        }
    }

    public function Datos_QR($datosParaCrearQR)
    {
        $Contrato = $datosParaCrearQR['Contrato'] ?? 'SinContrato';
        $No_Reporte = $datosParaCrearQR['No_Reporte'] ?? 'SinReporte';
        $token = $datosParaCrearQR['qr_token'] ?? null;
        $idProcedimiento = $datosParaCrearQR['idProcedimiento'] ?? null;
        $idTecnico = $datosParaCrearQR['ID_TECNICO'] ?? null;

        $idsConsumibles = array_filter([
            $datosParaCrearQR['idEquipo'] ?? null,
            $datosParaCrearQR['idEquipo1'] ?? null,
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
            if (!$ruta) {
                return false;
            }

            return !in_array(trim(strtoupper($ruta)), $rutasInvalidas);
        });

        /*
        |--------------------------------------------------------------------------
        | DIRECTORIO TEMPORAL
        |--------------------------------------------------------------------------
        */

        $directorioTemporal = storage_path("app/temp_pdfs/FOR_PIMP_03_B_01/{$Contrato}/{$No_Reporte}");

        if (!File::exists($directorioTemporal)) {
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

            if (!$rutaOriginal || !File::exists($rutaOriginal)) {
                Log::warning('PDF no encontrado para anexar en QR 04_02', [
                    'rutaDb' => $rutaPdf,
                    'rutaOriginal' => $rutaOriginal,
                    'rutasProbadas' => $this->getPdfCandidatePaths($rutaPdf),
                ]);
                continue;
            }

            $nombreArchivo = basename($rutaOriginal);
            $rutaTemporal = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivo;

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
        $directorioQR = storage_path("app/public/Reportes/FOR_PIMP_03_B_01/{$Contrato}/{$No_Reporte}/QR_REPORTES");

        if (!File::exists($directorioQR)) {
            File::makeDirectory($directorioQR, 0777, true);
        }

        $rutaQrCompleta = $directorioQR . DIRECTORY_SEPARATOR . $nombreQR;

        \QrCode::format('svg')
            ->size(300)
            ->margin(0)
            ->generate($rutaPublicaPdf, $rutaQrCompleta);

        $rutaQrPublica = "storage/Reportes/FOR_PIMP_03_B_01/{$Contrato}/{$No_Reporte}/QR_REPORTES/" . $nombreQR;

        /*
        |--------------------------------------------------------------------------
        | VALIDAR PDFs
        |--------------------------------------------------------------------------
        */

        if (empty($pdfsTemporales)) {
            Log::warning('No hay PDFs válidos para unir.');

            return [
                'pdf' => null,
                'qr' => $rutaQrPublica
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | UNIR PDFs
        |--------------------------------------------------------------------------
        */

        $pdf = new Fpdi();
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
                        escapeshellarg($ghostscript) . ' -sDEVICE=pdfwrite '
                        . '-dCompatibilityLevel=1.4 '
                        . '-dNOPAUSE '
                        . '-dQUIET '
                        . '-dBATCH '
                        . '-sOutputFile=' . escapeshellarg($archivoCompatible) . ' '
                        . escapeshellarg($archivoPdf);

                    exec($comando, $salidaGhostscript, $codigoGhostscript);

                    if ($codigoGhostscript === 0 && File::exists($archivoCompatible)) {
                        $archivoParaImportar = $archivoCompatible;
                    } else {
                        Log::warning('Ghostscript no pudo compatibilizar PDF 04_02; se intentara leer original.', [
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
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        $directorioFinal = "Reportes/FOR_PIMP_03_B_01/{$Contrato}/{$No_Reporte}/";
        $rutaDirectorioFinal = storage_path("app/public/" . $directorioFinal);

        if (!File::exists($rutaDirectorioFinal)) {
            File::makeDirectory($rutaDirectorioFinal, 0777, true);
        }

        $nombreArchivoFinal = "QR_FOR_PIMP_03_B_01_{$Contrato}_{$No_Reporte}.pdf";
        $rutaPdfFinal = $rutaDirectorioFinal . $nombreArchivoFinal;

        $pdf->Output($rutaPdfFinal, 'F');

        File::deleteDirectory($directorioTemporal);

        return [
            'pdf' => "storage/" . $directorioFinal . $nombreArchivoFinal,
            'qr' => $rutaQrPublica
        ];
    }


    public function OS_OC($datosParaCrearOS_OC)
    {
        $idPrueba_Aplica = $datosParaCrearOS_OC['idPrueba_Aplica'];
        $Cliente = $datosParaCrearOS_OC['Cliente'];
        $Lugar = $datosParaCrearOS_OC['Lugar'] ?? $datosParaCrearOS_OC['Instalacion'] ?? 'ESPERA DE DATOS';
        $Contrato= $datosParaCrearOS_OC['Contrato'];
        //$Contrato = trim(strtoupper($datosParaCrearOS_OC['Contrato']));
        $Proyecto = $datosParaCrearOS_OC['Proyecto'];
        $Material = $datosParaCrearOS_OC['Material'];
        $Isometrico_Plano = $datosParaCrearOS_OC['Isometrico_Plano'] ?? $datosParaCrearOS_OC['No_Isometrico'] ?? 'ESPERA DE DATOS';
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
            $idCliente = $BusquedaCliente->idClientes; // O el campo que sea clave primaria
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

    public function FOR_PIMP_03_B_01_store(Request $request, ServicioAnalisisImagenImageJ $servicioImagen)
    {
        $Estatus = "CREADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
            /*DETALLES GENERALES */
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
            'Detalles_Generales.Nom_pieza' => 'nullable|string',
            'Detalles_Generales.Accesorio' => 'nullable|string',
            'Detalles_Generales.Tuberia' => 'nullable|string',
            'Detalles_Generales.Estructural' => 'nullable|string',
            'Detalles_Generales.Observaciones' => 'nullable|string',
            'Detalles_Generales.Elementos_Soldados' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.No_Junta' => 'nullable|string',
            'Detalles_Generales.Trazabilidad' => 'nullable|string',
            'Detalles_Generales.Espesores' => 'nullable|string',
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.idProcedimiento' => 'nullable|integer',
            'Detalles_Generales.Codigo_Diseno' => 'nullable|string',
            'Detalles_Generales.Diam_Nominal' => 'nullable|string',
            'Detalles_Generales.Reporte_Antes_Relevado' => 'nullable|string',
            'Detalles_Generales.Reporte_Despues_Relevado' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            // Datos confiables generados por Fiji y distribución dentro del PDF principal.
            'Analisis_Imagen_Token' => 'nullable|uuid',
            'Analisis_Imagen_Usar_Reporte' => 'nullable|boolean',
            'Analisis_Reporte_Comentario_Imagen' => 'nullable|string|max:500',
            'Analisis_Reporte_Descripcion' => 'nullable|string|max:20000',
            'Analisis_Reporte_Layout' => 'nullable|array',
            'Analisis_Reporte_Layout.*.pagina' => 'nullable|integer|min:1|max:999',
            'Analisis_Reporte_Layout.*.posicion' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            'Conteo_Granos_JSON' => 'nullable|json|max:100000',
            'Patron_Grano' => 'nullable|array',
            'Patron_Grano.id' => 'nullable|required_if:Patron_Grano.activo,1|integer|min:1',
            'Patron_Grano.descripcion' => 'nullable|required_with:Patron_Grano.id|string|max:500',
            'Patron_Grano.activo' => 'nullable|boolean',
            'Patron_Grano.usar_version_catalogo' => 'nullable|boolean',
            'Patron_Grano.layout' => 'nullable|array',
            'Patron_Grano.layout.pagina' => 'nullable|integer|min:1|max:999',
            'Patron_Grano.layout.posicion' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO' => 'nullable|string',

            'Datos_Equipo.MARCA_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO1' => 'nullable|string',

            'Datos_Equipo.TEMPERATURA_INICIAL' => 'nullable|string',
            'Datos_Equipo.HORA_INICIO' => 'nullable|string',
            'Datos_Equipo.VELOCIDAD_CALENTAMIENTO' => 'nullable|string',
            'Datos_Equipo.HORA_FINAL' => 'nullable|string',
            'Datos_Equipo.TEMPERATURA_SOSTENIMIENTO' => 'nullable|string',
            'Datos_Equipo.DIA_INICIO' => 'nullable|string',
            'Datos_Equipo.TIEMPO_SOSTENIMIENTO' => 'nullable|string',
            'Datos_Equipo.DIA_FINAL' => 'nullable|string',
            'Datos_Equipo.VEL_ENFRIAMIENTO' => 'nullable|string',
            'Datos_Equipo.NO_GRAFICA' => 'nullable|string',
            'Datos_Equipo.VEL_GRAFICADOR' => 'nullable|string',
            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.MATERIAL_PANO' => 'nullable|string',
            'Datos_Equipo.LIJAS_DESBASTE' => 'nullable|array|max:6',
            'Datos_Equipo.LIJAS_DESBASTE.*' => 'nullable|string|max:50',
            'Datos_Equipo.MATERIAL_ABRASIVO' => 'nullable|string',
            'Datos_Equipo.REACTIVO' => 'nullable|string',
            'Datos_Equipo.TIEMPO_ATAQUE' => 'nullable|string',
            'Datos_Equipo.FASES_PRESENTES' => 'nullable|string',
            'Datos_Equipo.ESPECIFICACION_MATERIAL' => 'nullable|string',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',

            // Una celda manual puede contener una fotografía o únicamente texto.
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string|max:5000',
            'foto_es_texto' => 'nullable|array',
            'foto_es_texto.*' => 'nullable|boolean',

            //Validar el campo NumFirmas
            'numFirmas' => 'nullable|integer|in:1,2,3,4',

            /*1 FIRMAS */
            'Firmas_Reportes1' => 'required|array',  // Asegura que es un array

            'Firmas_Reportes1.Realizo' => 'nullable|string',
            'Firmas_Reportes1.ID_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.EMPRESA_TECNICO' => 'nullable|string',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string',
            'Firmas_Reportes2.ID_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.Vobo1' => 'nullable|string',

            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string',

            /*3 FIRMAS */
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

            /*4 FIRMAS */
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
        ]);

        $this->validarDistribucionFotosDelRequest($request);

        /*Detalles Generales y Datos del Equipo */
        $Reportes = new reporte();  // Modelo de la tabla donde guardas los datos
        $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();  // Modelo de la tabla donde guardas los datos
        $Firmas_Reportes = new Firma_Reporte();  // Modelo de la tabla donde guardas los datos
        $Fotos_Reportes = new Fotos_Reporte();  // Modelo de la tabla donde guardas los datos
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
        if ($request->TieneContrato === "no") {

            // Si el usuario alteró el valor o no llegó, se recalcula en backend
            $actual = $request->Detalles_Generales['Contrato'] ?? null;

            // Verificar que realmente tenga el formato correcto
            if (!$actual || !preg_match('/^AICO-INT-\d{4}$/', $actual)) {

                // Seguridad: volver a calcular el consecutivo
                $registros = reporte::orderBy('idReportes', 'DESC')->get();
                $ultimoNumero = 0;

                foreach ($registros as $r) {
                    $json = json_decode($r->Detalles_Generales, true);

                    if (!empty($json['Contrato']) && str_starts_with($json['Contrato'], 'AICO-INT-')) {
                        $n = intval(str_replace('AICO-INT-', '', $json['Contrato']));
                        if ($n > $ultimoNumero) $ultimoNumero = $n;
                        break;
                    }
                }

                $nuevo = "AICO-INT-" . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);

                $validatedData['Detalles_Generales']['Contrato'] = $nuevo;

            } else {
                // Si el frontend envió un contrato válido, se utiliza ese
                $validatedData['Detalles_Generales']['Contrato'] = $actual;
            }
        }
        // Conserva una copia propia de la imagen maestra para proteger el reporte histórico.
        $servicioPatronGrano = app(ServicioPatronGranoReporte::class);
        $patronGrano = $servicioPatronGrano->construirHistorico(
            $request,
            'FOR_PIMP_03_B_01',
            (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
            (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? '')
        );
        if ($patronGrano !== null) {
            $validatedData['Detalles_Generales']['PATRON_GRANO'] = $patronGrano;
        }

        $servicioMetalografia = app(ServicioMetalografiaReporte::class);
        // El token evita aceptar rutas manipuladas: el servidor recupera el resultado perteneciente al usuario.
        if (!empty($validatedData['Analisis_Imagen_Token'])) {
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN'] = $servicioImagen->obtenerPorToken(
                $validatedData['Analisis_Imagen_Token'],
                (int) Auth::id()
            );
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['usar_en_reporte'] =
                !empty($validatedData['Analisis_Imagen_Usar_Reporte']);
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['comentario_imagen_reporte'] =
                trim((string) ($validatedData['Analisis_Reporte_Comentario_Imagen'] ?? ''));
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['descripcion_reporte'] =
                trim((string) ($validatedData['Analisis_Reporte_Descripcion'] ?? ''));
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['layout_reporte'] =
                $servicioMetalografia->normalizarLayoutAnalisis($validatedData['Analisis_Reporte_Layout'] ?? []);
        }
        // Los totales del conteo se recalculan en backend antes de formar parte del reporte.
        $conteoGranos = $servicioMetalografia->normalizarConteoGranos(
            $validatedData['Conteo_Granos_JSON'] ?? null
        );
        if ($conteoGranos !== null) {
            $validatedData['Detalles_Generales']['CONTEO_GRANOS'] = $conteoGranos;
        }
        // Guardar Detalles_Generales como JSON en la base de datos
        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        // Guardar Datos_Equipo como JSON en la base de datos
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);

        $Reportes->Estatus = $Estatus; // Asignar el estatus

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
            'idEquipo1' => $validatedData['Datos_Equipo']['ID_EQUIPO1'] ?? null,
            // El técnico seleccionado permite anexar su CV al QR, igual que en PINS.
            'ID_TECNICO' => $request->input('Firmas_Reportes1.ID_TECNICO')
                ?? $request->input('Firmas_Reportes2.ID_TECNICO')
                ?? $request->input('Firmas_Reportes3.ID_TECNICO')
                ?? $request->input('Firmas_Reportes4.ID_TECNICO'),
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
        $Grupo_Juntas_Detalles_Re->idReportes = $idReportes;

        $titulos_json = $request->input('titulos_data', '[]');
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("no.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = 21; //Agregar 1 + que en create y edit para que la longitud entre en el mismo bloque

        $bloques = []; //agregar
        $bloqueActual = [];//agregar
        $contador = 0;//agregar
        /*//agregar
        |--------------------------------------------------------------------------
        | FUNCIONES AUXILIARES
        |--------------------------------------------------------------------------
        */
        $cerrarBloque = function () use (&$bloques, &$bloqueActual, &$contador) {
            if (!empty($bloqueActual)) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }
        };

        $agregarElemento = function ($elemento) use (&$bloques, &$bloqueActual, &$contador, $maxFilasPorBloque) {
            if ($contador >= $maxFilasPorBloque) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }

            $bloqueActual[] = $elemento;
            $contador++;
        };


        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 1) {
            $validatedData['Firmas_Reportes1']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes1']);
        }
        else if ($numFirmas == 2) {
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
        $imagesBase64 = $request->input('images_base64', []);
        $fotoEsTexto = $request->input('foto_es_texto', []);
        $fotoPaginas = $request->input('foto_pagina', []);
        $fotoPosiciones = $request->input('foto_posicion', []);
        $hayElementosFoto = !empty(array_filter($imagesBase64)) || !empty(array_filter($fotoEsTexto));
        if($hayElementosFoto)
        {
        $imagenesGuardadas = []; // Para almacenar rutas de imágenes guardadas

        foreach (array_unique(array_merge(array_keys($imagesBase64), array_keys($fotoEsTexto))) as $index) {
            $base64Image = $imagesBase64[$index] ?? null;
            $esCuadroTexto = !empty($fotoEsTexto[$index]);
            if (empty($base64Image) && !$esCuadroTexto) {
                continue;
            }

            $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
            $Contrato = $validatedData['Detalles_Generales']['Contrato'];

            // Decodificar Base64
            if (!$esCuadroTexto) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));

            // Nombre único
            $imageName = 'imagen_' . time() . '_' . $index . '.png';

            $rutaCarpeta = "public/Reportes/FOR_PIMP_03_B_01/{$Contrato}/{$No_Reporte}/Fotos";

                Storage::put("{$rutaCarpeta}/{$imageName}", $image);
                $rutaPublicaFoto = "storage/Reportes/FOR_PIMP_03_B_01/{$Contrato}/{$No_Reporte}/Fotos/{$imageName}";
            } else {
                $rutaPublicaFoto = null;
            }

            $distribucionFoto = $this->normalizeFotoLayout(
                $fotoPaginas[$index] ?? null,
                $fotoPosiciones[$index] ?? null,
                $index
            );

            // ✔ Detalles Junta activado
            $detallesJunta = isset($request->detalles_junta_check[$index]) 
                                ? (bool)$request->detalles_junta_check[$index] 
                                : false;

            // Si detalles junta está activo, guardar datos
            $datosJunta = null;

            if ($detallesJunta) {
                $datosJunta = [
                    'junta' => $request->junta[$index] ?? null,
                    'no_indicacion' => $request->no_indicacion[$index] ?? null,
                    'tipo_indicacion' => $request->tipo_indicacion[$index] ?? null,
                    'longitud' => $request->longitud[$index] ?? null,
                    'profundidad' => $request->profundidad[$index] ?? null,
                    'nivel_referencia' => $request->nivel_referencia[$index] ?? null,
                    'distancia_nivel' => $request->distancia_nivel[$index] ?? null,
                    'direccion_sonda' => $request->direccion_sonda[$index] ?? null,
                    'recubrimiento' => $request->recubrimiento[$index] ?? null,
                ];
            }

            $imagenesGuardadas[] = [
                'ruta' => $rutaPublicaFoto,
                'comentario' => $request->comments[$index] ?? null,
                'es_cuadro_texto' => $esCuadroTexto ? 1 : 0,
                'una_hoja' => $distribucionFoto['una_hoja'],
                'pagina' => $distribucionFoto['pagina'],
                'posicion' => $distribucionFoto['posicion'],
                'detalles_junta' => $detallesJunta,
                'datos_junta' => $datosJunta
            ];
        }

        // Convertir el array de fotos a JSON
        $Fotos = json_encode($imagenesGuardadas); 

        // Guardar en la base de datos
        $Fotos_Reportes->idReportes = $idReportes;
        $Fotos_Reportes->Fotos_Reportes = $Fotos;
        $Fotos_Reportes->save();
    }else{
        $imagenesGuardadas = [];
        $Fotos = json_encode($imagenesGuardadas);
        $Fotos = json_encode($imagenesGuardadas); 
        $Fotos_Reportes->idReportes = $idReportes;
        $Fotos_Reportes->Fotos_Reportes = $Fotos;
        $Fotos_Reportes->save();
    }

        $Cliente = $validatedData['Detalles_Generales']['Cliente'];
        $Instalacion = $validatedData['Detalles_Generales']['Instalacion'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'] ?? '';
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
            'ResultadosJuntas' => $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re,
            'idSolicitud' => $idSolicitud,
            'idReportes' => $idReportes,
            
        ];

        $this->OS_OC($datosParaCrearOS_OC);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_PIMP_03_B_01_update(Request $request, $id, ServicioAnalisisImagenImageJ $servicioImagen)
    {
        $Estatus = "ACTUALIZADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
                        /*DETALLES GENERALES */
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
            'Detalles_Generales.Nom_pieza' => 'nullable|string',
            'Detalles_Generales.Accesorio' => 'nullable|string',
            'Detalles_Generales.Tuberia' => 'nullable|string',
            'Detalles_Generales.Estructural' => 'nullable|string',
            'Detalles_Generales.Observaciones' => 'nullable|string',
            'Detalles_Generales.Elementos_Soldados' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.No_Junta' => 'nullable|string',
            'Detalles_Generales.Trazabilidad' => 'nullable|string',
            'Detalles_Generales.Espesores' => 'nullable|string',
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.idProcedimiento' => 'nullable|integer',
            'Detalles_Generales.Codigo_Diseno' => 'nullable|string',
            'Detalles_Generales.Diam_Nominal' => 'nullable|string',
            'Detalles_Generales.Reporte_Antes_Relevado' => 'nullable|string',
            'Detalles_Generales.Reporte_Despues_Relevado' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Analisis_Imagen_Token' => 'nullable|uuid',
            'Analisis_Imagen_Usar_Reporte' => 'nullable|boolean',
            'Analisis_Reporte_Comentario_Imagen' => 'nullable|string|max:500',
            'Analisis_Reporte_Descripcion' => 'nullable|string|max:20000',
            'Analisis_Reporte_Layout' => 'nullable|array',
            'Analisis_Reporte_Layout.*.pagina' => 'nullable|integer|min:1|max:999',
            'Analisis_Reporte_Layout.*.posicion' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            'Conteo_Granos_JSON' => 'nullable|json|max:100000',
            'Patron_Grano' => 'nullable|array',
            'Patron_Grano.id' => 'nullable|required_if:Patron_Grano.activo,1|integer|min:1',
            'Patron_Grano.descripcion' => 'nullable|required_with:Patron_Grano.id|string|max:500',
            'Patron_Grano.activo' => 'nullable|boolean',
            'Patron_Grano.usar_version_catalogo' => 'nullable|boolean',
            'Patron_Grano.layout' => 'nullable|array',
            'Patron_Grano.layout.pagina' => 'nullable|integer|min:1|max:999',
            'Patron_Grano.layout.posicion' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO' => 'nullable|string',

            'Datos_Equipo.MARCA_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO1' => 'nullable|string',

            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.MATERIAL_PANO' => 'nullable|string',
            'Datos_Equipo.LIJAS_DESBASTE' => 'nullable|array|max:6',
            'Datos_Equipo.LIJAS_DESBASTE.*' => 'nullable|string|max:50',
            'Datos_Equipo.MATERIAL_ABRASIVO' => 'nullable|string',
            'Datos_Equipo.REACTIVO' => 'nullable|string',
            'Datos_Equipo.TIEMPO_ATAQUE' => 'nullable|string',
            'Datos_Equipo.FASES_PRESENTES' => 'nullable|string',
            'Datos_Equipo.ESPECIFICACION_MATERIAL' => 'nullable|string',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',

            // Conserva en edición el modo de cuadro de texto de cada celda.
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string|max:5000',
            'foto_es_texto' => 'nullable|array',
            'foto_es_texto.*' => 'nullable|boolean',
            //Validar el campo NumFirmas
            'numFirmas' => 'nullable|integer|in:1,2,3,4',

            /*1 FIRMAS */
            'Firmas_Reportes1' => 'required|array',  // Asegura que es un array

            'Firmas_Reportes1.Realizo' => 'nullable|string',
            'Firmas_Reportes1.ID_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.EMPRESA_TECNICO' => 'nullable|string',

            /*2 FIRMAS */
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

            /*3 FIRMAS */
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

            /*4 FIRMAS */
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
        ]);

        $this->validarDistribucionFotosDelRequest($request);

        $detallesRequest = $request->input('Detalles_Generales', []);
        $validatedData['Detalles_Generales']['Codigo_Diseno'] = $validatedData['Detalles_Generales']['Codigo_Diseno']
            ?? $detallesRequest['Codigo_Diseno']
            ?? null;
        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes',$id)->first();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes',$id)->first();
        $Firmas = Firma_Reporte::firstOrNew(['idReportes' => $id]);
        $Fotos_Reportes = Fotos_Reporte::where('idReportes',$id)->first();

        if ($request->TieneCliente === 'si') {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteSelect;
        } elseif ($request->TieneCliente === 'no') {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteInput;
        }

        if ($request->TieneContrato === 'no') {
            $contratoInterno = $validatedData['Detalles_Generales']['Contrato'] ?? null;

            if (!$contratoInterno || !preg_match('/^AICO-INT-\d{4}$/', $contratoInterno)) {
                $ultimoNumero = 0;

                foreach (reporte::orderBy('idReportes', 'DESC')->get() as $reporteExistente) {
                    $detalles = json_decode($reporteExistente->Detalles_Generales, true);

                    if (!empty($detalles['Contrato']) && str_starts_with($detalles['Contrato'], 'AICO-INT-')) {
                        $ultimoNumero = max($ultimoNumero, (int) str_replace('AICO-INT-', '', $detalles['Contrato']));
                    }
                }

                $validatedData['Detalles_Generales']['Contrato'] = 'AICO-INT-'
                    . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);
            }
        }

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        // 1. Obtener los detalles actuales que ya están en la base de datos
        $detallesActuales = json_decode($Reporte->Detalles_Generales, true) ?? [];
        $datosEquipoActuales = json_decode($Reporte->Datos_Equipo, true) ?? [];

        if ($request->hasFile('Detalles_Generales.Reporte_Firmado')) {
            
            // 1. ELIMINAR ARCHIVO ANTERIOR (si existe)
            if (!empty($detallesActuales['Reporte_Firmado'])) {
                // Convertimos la ruta de la base de datos (storage/...) de vuelta a la ruta del disco (public/...)
                $archivoViejo = str_replace('storage/', 'public/', $detallesActuales['Reporte_Firmado']);
                
                if (Storage::exists($archivoViejo)) {
                    Storage::delete($archivoViejo);
                }
            }

            // 2. PROCESAR NUEVO ARCHIVO
            $file = $request->file('Detalles_Generales.Reporte_Firmado');
            $rutaBase = "public/Reportes/FOR_PIMP_03_B_01/{$Contrato}/{$No_Reporte}/Reporte_Firmado";
            $nombreArchivo = 'Reporte_Firmado_' . $No_Reporte . '_' . time() . '.pdf';
            
            $file->storeAs($rutaBase, $nombreArchivo);

            $rutaPublica = str_replace('public/', 'storage/', $rutaBase) . '/' . $nombreArchivo;
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $rutaPublica;

        } else {
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $detallesActuales['Reporte_Firmado'] ?? null;
        }

        // Conservar cualquier informacion previa que no venga en el formulario de edicion.
        $validatedData['Detalles_Generales'] = array_merge($detallesActuales, $validatedData['Detalles_Generales']);
        $validatedData['Datos_Equipo'] = array_merge($datosEquipoActuales, $validatedData['Datos_Equipo']);

        $rutaPatronAnterior = (string) ($detallesActuales['PATRON_GRANO']['ruta_imagen'] ?? '');
        $servicioPatronGrano = app(ServicioPatronGranoReporte::class);
        $patronGrano = $servicioPatronGrano->construirHistorico(
            $request,
            'FOR_PIMP_03_B_01',
            (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
            (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? ''),
            is_array($detallesActuales['PATRON_GRANO'] ?? null)
                ? $detallesActuales['PATRON_GRANO']
                : null
        );
        if ($patronGrano === null) {
            unset($validatedData['Detalles_Generales']['PATRON_GRANO']);
        } else {
            $validatedData['Detalles_Generales']['PATRON_GRANO'] = $patronGrano;
        }

        $servicioMetalografia = app(ServicioMetalografiaReporte::class);
        // Un token nuevo reemplaza el análisis; sin token se conserva el histórico ya guardado.
        if (!empty($validatedData['Analisis_Imagen_Token'])) {
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN'] = $servicioImagen->obtenerPorToken(
                $validatedData['Analisis_Imagen_Token'],
                (int) Auth::id()
            );
        }
        if (is_array($validatedData['Detalles_Generales']['ANALISIS_IMAGEN'] ?? null)) {
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['usar_en_reporte'] =
                !empty($validatedData['Analisis_Imagen_Usar_Reporte']);
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['comentario_imagen_reporte'] =
                trim((string) ($validatedData['Analisis_Reporte_Comentario_Imagen'] ?? ''));
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['descripcion_reporte'] =
                trim((string) ($validatedData['Analisis_Reporte_Descripcion'] ?? ''));
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['layout_reporte'] =
                $servicioMetalografia->normalizarLayoutAnalisis($validatedData['Analisis_Reporte_Layout'] ?? []);
        }
        $conteoGranos = $servicioMetalografia->normalizarConteoGranos(
            $validatedData['Conteo_Granos_JSON'] ?? null
        );
        if ($conteoGranos !== null) {
            $validatedData['Detalles_Generales']['CONTEO_GRANOS'] = $conteoGranos;
        }
        
        $validatedData['Datos_Equipo']['ID_EQUIPO'] = $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? ($datosEquipoActuales['ID_EQUIPO'] ?? null);
        $validatedData['Datos_Equipo']['ID_EQUIPO1'] = $validatedData['Datos_Equipo']['ID_EQUIPO1'] ?? ($datosEquipoActuales['ID_EQUIPO1'] ?? null);
        $validatedData['Datos_Equipo']['ID_SONDA'] = $validatedData['Datos_Equipo']['ID_SONDA'] ?? ($datosEquipoActuales['ID_SONDA'] ?? null);
        $validatedData['Datos_Equipo']['ID_BLOCK'] = $validatedData['Datos_Equipo']['ID_BLOCK'] ?? ($datosEquipoActuales['ID_BLOCK'] ?? null);
        $validatedData['Datos_Equipo']['QR_TOKEN'] = $validatedData['Datos_Equipo']['QR_TOKEN'] ?? ($datosEquipoActuales['QR_TOKEN'] ?? (string) Str::uuid());

        $datosParaCrearQR = [
            'Contrato' => $validatedData['Detalles_Generales']['Contrato'] ?? null,
            'No_Reporte' => $validatedData['Detalles_Generales']['No_Reporte'] ?? null,
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'idEquipo' => $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? null,
            'idEquipo1' => $validatedData['Datos_Equipo']['ID_EQUIPO1'] ?? null,
            // El técnico seleccionado permite anexar su CV al QR, igual que en PINS.
            'ID_TECNICO' => $request->input('Firmas_Reportes1.ID_TECNICO')
                ?? $request->input('Firmas_Reportes2.ID_TECNICO')
                ?? $request->input('Firmas_Reportes3.ID_TECNICO')
                ?? $request->input('Firmas_Reportes4.ID_TECNICO'),
            'idProcedimiento' => $validatedData['Detalles_Generales']['idProcedimiento'] ?? null,
        ];

        $resultadoQR = $this->Datos_QR($datosParaCrearQR);
        $validatedData['Datos_Equipo']['QR_PDF'] = $resultadoQR['qr'] ?? ($datosEquipoActuales['QR_PDF'] ?? null);
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = $resultadoQR['pdf'] ?? ($datosEquipoActuales['PDF_UNIFICADO'] ?? null);

        // Actualiza los detalles generales como JSON en la base de datos
        $Reporte->update([
            'Detalles_Generales' => json_encode($validatedData['Detalles_Generales']),
            'Datos_Equipo' => json_encode($validatedData['Datos_Equipo']) 
        ]);

        // Solo después de confirmar el update se elimina la copia histórica sustituida.
        $rutaPatronNueva = (string) ($validatedData['Detalles_Generales']['PATRON_GRANO']['ruta_imagen'] ?? '');
        $servicioPatronGrano->eliminarCopiaSustituida($rutaPatronAnterior, $rutaPatronNueva);

        $titulos_json = $request->input('titulos_data', '[]');
        //dd($titulos_json);
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("no.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = 21; //Agregar 1 + que en create y edit para que la longitud entre en el mismo bloque

        $bloques = []; //agregar
        $bloqueActual = [];//agregar
        $contador = 0;//agregar
        /*//agregar
        |--------------------------------------------------------------------------
        | FUNCIONES AUXILIARES
        |--------------------------------------------------------------------------
        */
        $cerrarBloque = function () use (&$bloques, &$bloqueActual, &$contador) {
            if (!empty($bloqueActual)) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }
        };

        $agregarElemento = function ($elemento) use (&$bloques, &$bloqueActual, &$contador, $maxFilasPorBloque) {
            if ($contador >= $maxFilasPorBloque) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }

            $bloqueActual[] = $elemento;
            $contador++;
        };

        /*
        |--------------------------------------------------------------------------
        | 1. BLOQUE SIN TITULO
        |--------------------------------------------------------------------------
        */
                $longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
                // Debe coincidir con verificarYAgregarLongitud() del JS: inserta una longitud cada 15 filas
                $filasPorLongitud = 20;
                for ($i = 0; $i < $numFilasSin; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $sinTituloKey,
                    'data' => [
                        'no' => $request->input("no.$sinTituloKey.$i"),
                        'junta' => $request->input("junta.$sinTituloKey.$i"),
                        'lado' => $request->input("lado.$sinTituloKey.$i"),
                        'no_ind' => $request->input("no_ind.$sinTituloKey.$i"),
                        'tipo_ind' => $request->input("tipo_ind.$sinTituloKey.$i"),
                        'long' => $request->input("long.$sinTituloKey.$i"),
                        'prof' => $request->input("prof.$sinTituloKey.$i"),
                        'NR' => $request->input("NR.$sinTituloKey.$i"),
                        'dnr' => $request->input("dnr.$sinTituloKey.$i"),
                        'evaluacion' => $request->input("evaluacion.$sinTituloKey.$i"),
                        'archivo' => $request->input("archivo.$sinTituloKey.$i"),
                        'long_ins' => $request->input("long_ins.$sinTituloKey.$i"),
                    ]
                    ]);

                    // Cada 15 filas, intercalar la longitud correspondiente (replica el orden del DOM)
                    if (($i + 1) % $filasPorLongitud === 0) {
                        $idxLong = intdiv($i, $filasPorLongitud);
                        if (isset($longitudesSin[$idxLong])) {
                            $agregarElemento([
                                'tipo' => 'longitud',
                                'grupo' => $sinTituloKey,
                                'valor' => $longitudesSin[$idxLong]
                            ]);
                            $cerrarBloque();
                        }
                    }
                }

                // Longitudes restantes (si el usuario agregó longitudes manuales extra o el último bloque tiene <15 filas)
                $longsUsadas = intdiv($numFilasSin, $filasPorLongitud);
                $totalLongs = count($longitudesSin);
                for ($j = $longsUsadas; $j < $totalLongs; $j++) {
                    $agregarElemento([
                        'tipo' => 'longitud',
                        'grupo' => $sinTituloKey,
                        'valor' => $longitudesSin[$j]
                    ]);
                    $cerrarBloque();
                }

        /*
        |--------------------------------------------------------------------------
        | 2. TITULOS + FILAS + LONGITUDES
        |--------------------------------------------------------------------------
        */

        foreach ($titulos as $tituloObj) {
            $tituloKey = $tituloObj['id'];   // ej. "titulo_1"
            $tituloText = $tituloObj['text']; // texto real

            // agregar título
            $agregarElemento([
                'tipo' => 'titulo',
                'grupo' => $tituloKey,
                'texto' => $tituloText
            ]);

            $filas = $request->input("no.$tituloKey", []);
            $numFilas = count($filas);
        
            //$resultados = [];
        
            for ($i = 0; $i < $numFilas; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $tituloKey,
                    'data' => [
                    'no' => $request->input("no.$tituloKey.$i"),
                    'junta' => $request->input("junta.$tituloKey.$i"),
                    'lado' => $request->input("lado.$tituloKey.$i"),
                    'no_ind' => $request->input("no_ind.$tituloKey.$i"),
                    'tipo_ind' => $request->input("tipo_ind.$tituloKey.$i"),
                    'long' => $request->input("long.$tituloKey.$i"),
                    'prof' => $request->input("prof.$tituloKey.$i"),
                    'NR' => $request->input("NR.$tituloKey.$i"),
                    'dnr' => $request->input("dnr.$tituloKey.$i"),
                    'evaluacion' => $request->input("evaluacion.$tituloKey.$i"),
                    'archivo' => $request->input("archivo.$tituloKey.$i"),
                    'long_ins' => $request->input("long_ins.$tituloKey.$i"),
                    ]
                ]);
            }

            // Obtener longitud inspeccionada asociada a este título (si existe)
            $longitudes = $request->input("Long_Inspecc.$tituloKey", []); //Agregar

                foreach ($longitudes as $long) {
                    $agregarElemento([
                        'tipo' => 'longitud',
                        'grupo' => $tituloKey,
                        'valor' => $long
                    ]);

                    // cerrar bloque al encontrar longitud
                    $cerrarBloque();
                }
        }
        /*
        |--------------------------------------------------------------------------
        | 3. CERRAR SI QUEDAN ELEMENTOS
        |--------------------------------------------------------------------------
        */
        $cerrarBloque();
        /*
        |--------------------------------------------------------------------------
        | 4. GUARDAR
        |--------------------------------------------------------------------------
        */
        // Actualizar o crear el campo en la base de datos
        if ($Grupo_Juntas_Detalles_Re) {
            $Grupo_Juntas_Detalles_Re->update([
                'Juntas_Grupo_Re' => json_encode($bloques, JSON_UNESCAPED_UNICODE)
            ]);
        } else {
            $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();
            $Grupo_Juntas_Detalles_Re->idReportes = $id;
            $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = json_encode($bloques, JSON_UNESCAPED_UNICODE);
            $Grupo_Juntas_Detalles_Re->save();
        }

        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 1) {
            $validatedData['Firmas_Reportes1']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas1 = json_encode($validatedData['Firmas_Reportes1']);
            $Firmas->Firmas = $Firmas1;
            $Firmas->save();
        }
        else if ($numFirmas == 2) {
            $validatedData['Firmas_Reportes2']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas2 = json_encode($validatedData['Firmas_Reportes2']);
            $Firmas->Firmas = $Firmas2;
            $Firmas->save();
        }
        else if ($numFirmas == 3) {
            $validatedData['Firmas_Reportes3']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas3 = json_encode($validatedData['Firmas_Reportes3']);
            $Firmas->Firmas = $Firmas3;
            $Firmas->save();
        }
        else{
            $validatedData['Firmas_Reportes4']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas4 = json_encode($validatedData['Firmas_Reportes4']);
            $Firmas->Firmas = $Firmas4;
            $Firmas->save();
        } 

        /* Fotos y Comentarios */
        // Obtener los valores necesarios para la ruta personalizada
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'] ?? ''; // Asegurar que Contrato está definido

        // Ruta base para guardar las imágenes
        $rutaCarpeta = "public/Reportes/FOR_PIMP_03_B_01/{$Contrato}/{$No_Reporte}/Fotos";

        // Obtener las imágenes existentes
        $existingImages = $request->input('existing_images', []);
        $comments = $request->input('comments', []);
        $imagesBase64 = $request->input('images_base64', []);
        $fotoEsTexto = $request->input('foto_es_texto', []);
        $deletedImages = $request->input('deleted_images', []);
        $fotoPaginas = $request->input('foto_pagina', []);
        $fotoPosiciones = $request->input('foto_posicion', []);
        //Log::info('Imágenes eliminadas recibidas:', ['deletedImages' => $deletedImages]);
        $detallesJuntaCheck = $request->input('detalles_junta_check', []);

        $junta = $request->input('junta', []);
        $noIndicacion = $request->input('no_indicacion', []);
        $tipoIndicacion = $request->input('tipo_indicacion', []);
        $longitud = $request->input('longitud', []);
        $profundidad = $request->input('profundidad', []);
        $nivelReferencia = $request->input('nivel_referencia', []);
        $distanciaNivel = $request->input('distancia_nivel', []);
        $direccionSonda = $request->input('direccion_sonda', []);
        $recubrimiento = $request->input('recubrimiento', []);
        

        $getDetallesJunta = function ($index) use (
            $detallesJuntaCheck,
            $junta,
            $noIndicacion,
            $tipoIndicacion,
            $longitud,
            $profundidad,
            $nivelReferencia,
            $distanciaNivel,
            $direccionSonda,
            $recubrimiento,
        ) {
            $activo = $detallesJuntaCheck[$index] ?? 0;

            if (!$activo) {
                return [
                    'detalles_junta' => 0,
                    'datos_junta' => null
                ];
            }

            return [
                'detalles_junta' => 1,
                'datos_junta' => [
                    'junta' => $junta[$index] ?? null,
                    'no_indicacion' => $noIndicacion[$index] ?? null,
                    'tipo_indicacion' => $tipoIndicacion[$index] ?? null,
                    'longitud' => $longitud[$index] ?? null,
                    'profundidad' => $profundidad[$index] ?? null,
                    'nivel_referencia' => $nivelReferencia[$index] ?? null,
                    'distancia_nivel' => $distanciaNivel[$index] ?? null,
                    'direccion_sonda' => $direccionSonda[$index] ?? null,
                    'recubrimiento' => $recubrimiento[$index] ?? null,
                ]
            ];
        };

        $getDistribucionFoto = function ($index) use ($fotoPaginas, $fotoPosiciones) {
            return $this->normalizeFotoLayout(
                $fotoPaginas[$index] ?? null,
                $fotoPosiciones[$index] ?? null,
                $index
            );
        };
        // **1️⃣ Eliminar imágenes marcadas para borrar**
        foreach ($deletedImages as $index) {
            if (isset($existingImages[$index])) {
                $rutaImagen = str_replace('storage/', 'public/', $existingImages[$index]);

                // Eliminar del almacenamiento
                if (Storage::exists($rutaImagen)) {
                    Storage::delete($rutaImagen);
                    //Log::info("Imagen eliminada: {$rutaImagen}");
                } else {
                    //Log::warning("No se encontró la imagen para eliminar: {$rutaImagen}");
                }

                // Eliminar de `existingImages` para que no se guarde en la BD
                unset($existingImages[$index]);
            }

            // También elimina el estado de una celda de texto, aunque no tuviera ruta física.
            unset(
                $fotoEsTexto[$index],
                $imagesBase64[$index],
                $comments[$index],
                $fotoPaginas[$index],
                $fotoPosiciones[$index]
            );
        }

        // **Reiniciar el array antes de procesar imágenes**
        $imagenesGuardadas = [];

        // **Evitar duplicados en las rutas ya guardadas**
        $rutasGuardadas = [];

        // **2️⃣ Procesar imágenes existentes**
        foreach ($existingImages as $index => $ruta) {
            // El modo texto conserva la celda y el comentario, sin mantener un archivo de imagen.
            if (!empty($fotoEsTexto[$index])) {
                $rutaImagenPublic = str_replace('storage/', 'public/', (string) $ruta);
                if ($ruta !== '' && Storage::exists($rutaImagenPublic)) {
                    Storage::delete($rutaImagenPublic);
                }
                $detalles = $getDetallesJunta($index);
                $distribucionFoto = $getDistribucionFoto($index);
                $imagenesGuardadas[] = [
                    'ruta' => null,
                    'comentario' => $comments[$index] ?? '',
                    'es_cuadro_texto' => 1,
                    'una_hoja' => $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
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
                $imageName = 'imagen_' . time() . '_' . $index . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs($rutaCarpeta, $imageName);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (!in_array($rutaNueva, $rutasGuardadas)) {
                $detalles = $getDetallesJunta($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva ?? $ruta,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => $getDistribucionFoto($index)['una_hoja'],
                    'pagina' => $getDistribucionFoto($index)['pagina'],
                    'posicion' => $getDistribucionFoto($index)['posicion'],
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
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
                $detalles = $getDetallesJunta($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva ?? $ruta,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => $getDistribucionFoto($index)['una_hoja'],
                    'pagina' => $getDistribucionFoto($index)['pagina'],
                    'posicion' => $getDistribucionFoto($index)['posicion'],
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            } else {
                // **Mantener la imagen existente**
                if (!empty($ruta) && !in_array($ruta, $rutasGuardadas)) {
                $detalles = $getDetallesJunta($index);

                $imagenesGuardadas[] = [
                    'ruta' => $ruta,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => $getDistribucionFoto($index)['una_hoja'],
                    'pagina' => $getDistribucionFoto($index)['pagina'],
                    'posicion' => $getDistribucionFoto($index)['posicion'],
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
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

            // Permite una celda de texto nueva aun cuando no existe archivo de imagen.
            if (!empty($fotoEsTexto[$index])) {
                $detalles = $getDetallesJunta($index);
                $distribucionFoto = $getDistribucionFoto($index);
                $imagenesGuardadas[] = [
                    'ruta' => null,
                    'comentario' => $comments[$index] ?? '',
                    'es_cuadro_texto' => 1,
                    'una_hoja' => $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
                continue;
            }

            if (!empty($base64Image)) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $imageName = 'imagen_' . time() . '_' . $index . '.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen en el almacenamiento
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                if (!in_array($rutaNueva, $rutasGuardadas)) {
                $detalles = $getDetallesJunta($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva ?? $ruta,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => $getDistribucionFoto($index)['una_hoja'],
                    'pagina' => $getDistribucionFoto($index)['pagina'],
                    'posicion' => $getDistribucionFoto($index)['posicion'],
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
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
            $Fotos_Reportes = new Fotos_Reporte();
            $Fotos_Reportes->idReportes = $id;
            $Fotos_Reportes->Fotos_Reportes = json_encode(array_values($imagenesGuardadas));
            $Fotos_Reportes->save();
        }

        //Log::info('Imágenes finales guardadas en BD:', ['imagenesGuardadas' => $imagenesGuardadas]);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'] ?? '';

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_PIMP_03_B_01($id)
    {

        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes', $id)->first();
        $Grupo_Juntas_Detalles_Re_Model = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $Firmas_Reportes = Firma_Reporte::where('idReportes', $id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes', $id)->first();

        // Decodificar el campo Detalles_Generales para obtener el nombre del proyecto
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true);
        // Decodificar el campo Datos_Equipo para obtener el nombre del proyecto
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true);
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Grupo_Juntas_Detalles_Re = $Grupo_Juntas_Detalles_Re_Model
            ? json_decode($Grupo_Juntas_Detalles_Re_Model->Juntas_Grupo_Re, true)
            : [];

        if (!is_array($Grupo_Juntas_Detalles_Re)) {
            $Grupo_Juntas_Detalles_Re = [];
        }

        $totalTitulos = 0;
        $totalFilas = 0;

        foreach ($Grupo_Juntas_Detalles_Re as $bloque) {
            if (!is_array($bloque)) continue;

            foreach ($bloque as $item) {
                if (($item['tipo'] ?? '') === 'titulo') {
                    $totalTitulos++;
                }

                if (($item['tipo'] ?? '') === 'fila') {
                    $totalFilas++;
                }
            }
        }

        $totalTitulosYFilas = $totalTitulos + $totalFilas;

        $Firmas_Reportes = json_decode($Firmas_Reportes->Firmas, true);
        $numFirmas = $Firmas_Reportes['numFirmas'];

        $Logo = public_path('images/Logo_AICO_R.jpg');
        $qrPdf = !empty($Datos_Equipo['QR_PDF']) ? public_path(str_replace('storage/', 'storage/', $Datos_Equipo['QR_PDF'])) : null;
        $Fotos = [];
        $totalFotos = 0;
        // Obtener las fotos con su comentario
        if ($Fotos_Reportes) {
            $fotos = json_decode($Fotos_Reportes->Fotos_Reportes, true) ?? [];
            $totalFotos = count($fotos); // Contar el total de imágenes
        
            foreach ($fotos as $foto) {
                $esCuadroTexto = !empty($foto['es_cuadro_texto']);
                $rutaFoto = $esCuadroTexto
                    ? null
                    : storage_path('app/public/' . str_replace('storage/', '', $foto['ruta'] ?? ''));

                // Un cuadro de texto no tiene ruta física y aun así debe llegar a la plantilla.
                if (!$esCuadroTexto && !File::exists($rutaFoto)) {
                    continue;
                }

                $detallesActivo = $foto['detalles_junta'] ?? 0;
                $datosJunta = $foto['datos_junta'] ?? null;

                $Fotos[] = [
                    'path' => $rutaFoto,
                    'comment' => $foto['comentario'] ?? '',
                    'es_cuadro_texto' => $esCuadroTexto ? 1 : 0,
                    'una_hoja'  => $foto['una_hoja'] ?? 0,
                    'pagina' => max(1, (int) ($foto['pagina'] ?? (intdiv(count($Fotos), 4) + 1))),
                    'posicion' => $foto['posicion']
                        ?? (!empty($foto['una_hoja']) ? 'pagina_completa' : null),

                    // 🔥 NUEVO
                    'detalles_junta' => $detallesActivo,
                    'datos_junta' => $datosJunta,
                ];
            }
        }

        // Fiji se integra en esta misma plantilla principal; no se genera ni concatena un PDF de fotos.
        app(ServicioMetalografiaReporte::class)->agregarAnalisisAlPdf($Fotos, $Detalles_Generales, $Datos_Equipo);
        // El tamaño de grano usa la misma celda y proporciones que cualquier fotografía del formato.
        app(ServicioPatronGranoReporte::class)->agregarAlPdf($Fotos, $Detalles_Generales);
        $totalFotos = count($Fotos);

        $data = [
            'title' => 'Reporte_FOR-PIMP-03-B-01.PDF',
            'Logo' => $Logo,
            //Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            //Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            'QR_PDF' => $qrPdf,
            //Grupo_Juntas_Detalles_Re
            'Grupo_Juntas_Detalles_Re' => $Grupo_Juntas_Detalles_Re,
            //Total de Juntas
            /*'totalTitulos' => $totalTitulos,
            'totalFilas' => $totalFilas,*/
            'totalTitulosYFilas' => $totalTitulosYFilas,
            //Fotos_Reportes
            'Fotos' => $Fotos,
            //Total de Fotos
            'totalFotos' => $totalFotos,
            //Numero de Firmas
            'numFirmas' => $numFirmas,
            //Firmas
            'Firmas_Reportes' => $Firmas_Reportes,
        ];

        // El reporte y sus fotografías pertenecen a una sola hoja.
        $pdf = PDF::loadView('Reportes.ReportesPDFIM.Reporte_FOR_PIMP_03_B_01_PDF', $data)->setPaper('letter', 'portrait');

        return response($pdf->output(), 200)
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
