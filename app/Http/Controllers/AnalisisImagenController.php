<?php

namespace App\Http\Controllers;

use App\Services\ServicioAnalisisImagenImageJ;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalisisImagenController extends Controller
{
    /**
     * Convierte temporalmente la imagen a 8 bits con Fiji y devuelve sus 256 frecuencias.
     * El navegador usa este histograma para que la previsualización coincida con ImageJ.
     */
    public function histograma(Request $request, ServicioAnalisisImagenImageJ $servicio): JsonResponse
    {
        $request->validate([
            'imagen' => 'required|file|mimes:jpg,jpeg,png,tif,tiff|max:25600',
        ]);

        try {
            // No se persiste el archivo: el servicio elimina su directorio temporal al terminar.
            return response()->json([
                'ok' => true,
                'imagen' => $servicio->obtenerHistograma8Bit($request->file('imagen')),
            ]);
        } catch (Throwable $error) {
            // El detalle técnico queda en el log; el usuario recibe un mensaje controlado.
            Log::error('No fue posible obtener el histograma de 8 bits con ImageJ.', [
                'usuario_id' => $request->user()?->getAuthIdentifier(),
                'error' => $error->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Fiji/ImageJ no pudo preparar el histograma de la imagen.',
            ], 422);
        }
    }

    /**
     * Ejecuta el flujo oficial: 8-bit, Threshold, Apply, Area Fraction y Measure.
     * El resultado y sus evidencias quedan asociados a un token del usuario autenticado.
     */
    public function fraccionFases(Request $request, ServicioAnalisisImagenImageJ $servicio): JsonResponse
    {
        $datos = $request->validate([
            'imagen' => 'required|file|mimes:jpg,jpeg,png,tif,tiff|max:25600',
            'umbral_minimo' => 'required|integer|between:0,255|lte:umbral_maximo',
            'umbral_maximo' => 'required|integer|between:0,255|gte:umbral_minimo',
            'fase_seleccionada' => 'required|in:perlita,ferrita',
        ]);

        try {
            // Los límites y la fase provienen de los controles manuales validados del técnico.
            $resultado = $servicio->procesarFraccionFases(
                $request->file('imagen'),
                (int) $datos['umbral_minimo'],
                (int) $datos['umbral_maximo'],
                $datos['fase_seleccionada'],
                (int) $request->user()->getAuthIdentifier()
            );

            return response()->json(['ok' => true, 'analisis' => $resultado]);
        } catch (Throwable $error) {
            // Fiji trabaja en segundo plano; cualquier fallo se registra para diagnóstico.
            Log::error('No fue posible procesar la fracción de fases con ImageJ.', [
                'usuario_id' => $request->user()?->getAuthIdentifier(),
                'error' => $error->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Fiji/ImageJ no pudo procesar la imagen. Revise la instalación y vuelva a intentarlo.',
            ], 422);
        }
    }
}
