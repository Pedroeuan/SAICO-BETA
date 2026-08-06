<?php

namespace App\Http\Controllers\Procesamiento;

use App\Http\Controllers\Controller;
use App\Models\Procesamiento\TrabajoProcesamiento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TrabajoProcesamientoController extends Controller
{
    /** Devuelve solo trabajos pertenecientes al usuario autenticado. */
    public function estado(Request $request, string $trabajo): JsonResponse
    {
        $registro = $this->obtenerDelUsuario($request, $trabajo);

        $resultado = $registro->estado === TrabajoProcesamiento::ESTADO_COMPLETADO
            ? $registro->resultadoArray()
            : null;
        if ($registro->tipo === 'reporte_pdf' && is_array($resultado)) {
            // Nunca se expone la ruta absoluta de almacenamiento al navegador.
            $resultado = ['descarga_url' => route('procesamientos.descargar', $registro->id)];
        }

        return response()->json([
            'ok' => $registro->estado !== TrabajoProcesamiento::ESTADO_ERROR,
            'trabajo' => [
                'id' => $registro->id,
                'tipo' => $registro->tipo,
                'estado' => $registro->estado,
                'mensaje' => $registro->mensaje,
                // El resultado se libera exclusivamente cuando ya esta completo.
                'resultado' => $resultado,
            ],
        ], $registro->estado === TrabajoProcesamiento::ESTADO_ERROR ? 422 : 200);
    }

    /** Entrega un PDF privado unicamente a quien solicito su generacion. */
    public function descargar(Request $request, string $trabajo): BinaryFileResponse
    {
        $registro = $this->obtenerDelUsuario($request, $trabajo);
        abort_unless($registro->estado === TrabajoProcesamiento::ESTADO_COMPLETADO, 409);

        $ruta = (string) data_get($registro->resultadoArray(), 'ruta_pdf');
        abort_unless($ruta !== '' && is_file($ruta), 404);

        return response()->file($ruta, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reporte.pdf"',
        ]);
    }

    /** Centraliza la validacion de propiedad para impedir consultar UUID ajenos. */
    private function obtenerDelUsuario(Request $request, string $id): TrabajoProcesamiento
    {
        return TrabajoProcesamiento::query()
            ->whereKey($id)
            ->where('usuario_id', $request->user()->getAuthIdentifier())
            ->firstOrFail();
    }
}
