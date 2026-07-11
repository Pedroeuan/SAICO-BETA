<?php

namespace App\Support\Reportes;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Solicitudes\detalles_solicitud;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PinsEquiposQrSupport
{
    public static function obtenerCatalogoEquiposHerramientasPorSolicitud($idSolicitud): array
    {
        if (empty($idSolicitud)) {
            return [];
        }

        $ids = detalles_solicitud::where('idSolicitud', $idSolicitud)
            ->pluck('idGeneral_EyC')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($ids)) {
            return [];
        }

        return general_eyc::whereIn('idGeneral_EyC', $ids)
            ->whereIn('Tipo', ['EQUIPOS', 'HERRAMIENTAS'])
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    (int) $item->idGeneral_EyC => [
                        'id' => (int) $item->idGeneral_EyC,
                        'tipo' => (string) $item->Tipo,
                        'nombre' => (string) ($item->Nombre_E_P_BP ?? ''),
                        'marca' => (string) ($item->Marca ?? ''),
                        'modelo' => (string) ($item->Modelo ?? ''),
                        'ns' => (string) ($item->Serie ?? ''),
                    ],
                ];
            })
            ->all();
    }

    public static function prepararDatosEquipoSeleccionados(array $datosEquipo, array $catalogo, array $idsSeleccionados = []): array
    {
        $idsNormalizados = collect($idsSeleccionados)
            ->flatten()
            ->filter(function ($id) {
                return is_numeric($id);
            })
            ->map(function ($id) {
                return (int) $id;
            })
            ->unique()
            ->values()
            ->all();

        $requiereEquiposCrudo = strtolower((string) ($datosEquipo['REQUIERE_EQUIPOS'] ?? 'no'));
        $requiereEquipos = in_array($requiereEquiposCrudo, ['si', 'sí', 'sÃ­'], true) || !empty($idsNormalizados)
            ? 'si'
            : 'no';
        $datosEquipo['REQUIERE_EQUIPOS'] = $requiereEquipos;

        if ($requiereEquipos !== 'si') {
            $datosEquipo['EQUIPOS_HERRAMIENTAS_IDS'] = [];
            $datosEquipo['EQUIPOS_HERRAMIENTAS'] = [];

            return $datosEquipo;
        }

        $seleccionados = [];

        foreach ($idsNormalizados as $id) {
            if (isset($catalogo[$id])) {
                $seleccionados[] = $catalogo[$id];
            }
        }

        $datosEquipo['EQUIPOS_HERRAMIENTAS_IDS'] = array_column($seleccionados, 'id');
        $datosEquipo['EQUIPOS_HERRAMIENTAS'] = array_values($seleccionados);

        return $datosEquipo;
    }

    public static function normalizarDatosEquipoSeleccionadosExistentes(array $datosEquipo, array $catalogo): array
    {
        $idsSeleccionados = $datosEquipo['EQUIPOS_HERRAMIENTAS_IDS'] ?? [];

        if (!is_array($idsSeleccionados)) {
            $idsSeleccionados = [];
        }

        $requiereEquipos = strtolower((string) ($datosEquipo['REQUIERE_EQUIPOS'] ?? 'no'));

        if (in_array($requiereEquipos, ['si', 'sí'], true)) {
            $datosEquipo['REQUIERE_EQUIPOS'] = 'si';
        } else {
            $datosEquipo['REQUIERE_EQUIPOS'] = 'no';
        }

        if ($datosEquipo['REQUIERE_EQUIPOS'] !== 'si') {
            return $datosEquipo;
        }

        if (!empty($datosEquipo['EQUIPOS_HERRAMIENTAS']) && is_array($datosEquipo['EQUIPOS_HERRAMIENTAS'])) {
            return $datosEquipo;
        }

        return self::prepararDatosEquipoSeleccionados($datosEquipo, $catalogo, $idsSeleccionados);
    }

    public static function generarQrPublico(string $codigoFormato, string $contrato, string $noReporte, string $token): ?string
    {
        if ($token === '') {
            return null;
        }

        $contratoSeguro = self::normalizarSegmentoRuta($contrato, 'SinContrato');
        $reporteSeguro = self::normalizarSegmentoRuta($noReporte, 'SinReporte');
        $rutaPublicaPdf = route('qr.reporte', ['token' => $token]);
        $nombreQr = "QR_{$contratoSeguro}_{$reporteSeguro}.svg";
        $directorioQr = storage_path("app/public/Reportes/{$codigoFormato}/{$contratoSeguro}/{$reporteSeguro}/QR_REPORTES");

        if (!File::exists($directorioQr)) {
            File::makeDirectory($directorioQr, 0777, true);
        }

        $rutaQrCompleta = $directorioQr . DIRECTORY_SEPARATOR . $nombreQr;

        \QrCode::format('svg')
            ->size(300)
            ->margin(0)
            ->generate($rutaPublicaPdf, $rutaQrCompleta);

        return "storage/Reportes/{$codigoFormato}/{$contratoSeguro}/{$reporteSeguro}/QR_REPORTES/{$nombreQr}";
    }

    public static function obtenerRutaPdfCachePublica(string $codigoFormato, string $contrato, string $noReporte): string
    {
        $contratoSeguro = self::normalizarSegmentoRuta($contrato, 'SinContrato');
        $reporteSeguro = self::normalizarSegmentoRuta($noReporte, 'SinReporte');
        $nombreArchivo = "Reporte_{$codigoFormato}_{$reporteSeguro}.pdf";

        return "storage/Reportes/{$codigoFormato}/{$contratoSeguro}/{$reporteSeguro}/PDF_CACHE/{$nombreArchivo}";
    }

    public static function guardarPdfCacheado(string $pdfOutput, string $codigoFormato, string $contrato, string $noReporte): string
    {
        $rutaPublica = self::obtenerRutaPdfCachePublica($codigoFormato, $contrato, $noReporte);
        $rutaDisco = str_replace('storage/', 'public/', $rutaPublica);

        Storage::put($rutaDisco, $pdfOutput);

        return $rutaPublica;
    }

    public static function eliminarArchivoPublico(?string $rutaPublica): void
    {
        if (empty($rutaPublica)) {
            return;
        }

        $rutaDisco = str_replace('storage/', 'public/', $rutaPublica);

        if (Storage::exists($rutaDisco)) {
            Storage::delete($rutaDisco);
        }
    }

    public static function resolverRutaPublicaAbsoluta(?string $rutaPublica): ?string
    {
        if (empty($rutaPublica)) {
            return null;
        }

        return public_path(str_replace('storage/', 'storage/', $rutaPublica));
    }

    public static function existeArchivoPublico(?string $rutaPublica): bool
    {
        if (empty($rutaPublica)) {
            return false;
        }

        $rutaDisco = str_replace('storage/', 'public/', $rutaPublica);

        return Storage::exists($rutaDisco);
    }

    private static function normalizarSegmentoRuta(?string $valor, string $fallback): string
    {
        $valor = trim((string) $valor);

        if ($valor === '') {
            return $fallback;
        }

        return preg_replace('/[\\\\\\/:"*?<>|]+/', '_', $valor) ?: $fallback;
    }
}
