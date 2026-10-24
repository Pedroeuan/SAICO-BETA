<?php

namespace App\Services;

use App\Models\Admin\Usuario;
use App\Models\Formato\formato;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentosUsuarioReporte
{
    public function catalogo()
    {
        return DB::table('Formato as f')
            ->leftJoin('Prueba as p', 'p.idPrueba', '=', 'f.idPrueba')
            ->select('f.idFormato', 'f.Nombre', 'f.idProcedimiento', 'p.Nombre as servicio')
            ->orderBy('p.Nombre')->orderBy('f.Nombre')->get();
    }

    public function validar(Request $request): void
    {
        $request->validate([
            'cv_pdf' => 'nullable|file|mimes:pdf|max:20480',
            'documentos_nuevos' => 'nullable|array|max:30',
            'documentos_nuevos.*' => 'array:tipo,formato_id,procedimiento_id,pdf',
            'documentos_nuevos.*.tipo' => 'required|in:cv_general,cv_servicio,certificado',
            'documentos_nuevos.*.formato_id' => 'nullable|integer|exists:Formato,idFormato|required_if:documentos_nuevos.*.tipo,cv_servicio',
            'documentos_nuevos.*.procedimiento_id' => 'nullable|integer|exists:procedimiento,idProcedimiento',
            'documentos_nuevos.*.pdf' => 'required|file|mimes:pdf|max:20480',
            'documentos_eliminar' => 'nullable|array',
            'documentos_eliminar.*' => 'required|uuid',
        ]);

        foreach ($request->input('documentos_nuevos', []) as $indice => $documento) {
            $formato = !empty($documento['formato_id']) ? formato::find($documento['formato_id']) : null;
            if (($documento['tipo'] ?? '') !== 'cv_general' && $formato?->idProcedimiento
                && !empty($documento['procedimiento_id'])
                && (int) $documento['procedimiento_id'] !== (int) $formato->idProcedimiento) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "documentos_nuevos.$indice.procedimiento_id" => 'El procedimiento debe corresponder al servicio seleccionado.',
                ]);
            }
        }
    }

    public function guardar(Request $request, Usuario $usuario): void
    {
        $eliminar = $request->input('documentos_eliminar', []);
        $documentos = array_values(array_filter(
            $usuario->documentos_reportes ?? [],
            fn (array $documento): bool => !in_array($documento['id'], $eliminar, true)
        ));

        foreach ($request->input('documentos_nuevos', []) as $indice => $datos) {
            $archivo = $request->file("documentos_nuevos.$indice.pdf");
            $formato = $datos['tipo'] !== 'cv_general' && !empty($datos['formato_id'])
                ? formato::findOrFail($datos['formato_id']) : null;
            $tipoNombre = ['cv_general' => 'C.V. general para reportes', 'cv_servicio' => 'C.V.', 'certificado' => 'Certificado'][$datos['tipo']];
            $documentos[] = [
                'id' => (string) Str::uuid(),
                'tipo' => $datos['tipo'],
                'nombre' => $tipoNombre . ($formato ? ' · ' . $formato->Nombre : ''),
                'formato_id' => $formato?->idFormato,
                'formato' => $formato?->Nombre,
                'procedimiento_id' => $datos['tipo'] === 'cv_general' ? null : ($formato?->idProcedimiento ?: ($datos['procedimiento_id'] ?? null)),
                'pdf' => $archivo->store('usuarios/documentos-reportes', 'public'),
            ];
        }

        // Los PDF retirados se conservan: pueden estar incluidos en reportes históricos.
        $usuario->documentos_reportes = $documentos;
    }

    public function rutas(?Usuario $usuario, ?array $seleccion, string $formato, ?int $procedimiento): array
    {
        if (!$usuario) {
            return [];
        }

        // Compatibilidad para reportes anteriores que adjuntaban siempre el C.V. completo.
        $seleccion ??= ['cv_completo' => true];
        $rutas = [];
        if (!empty($seleccion['cv_completo']) && $usuario->cv_pdf) {
            $rutas[] = $usuario->cv_pdf;
        }

        $aplicables = array_filter($usuario->documentos_reportes ?? [], function (array $documento) use ($formato, $procedimiento): bool {
            return (empty($documento['formato']) || $documento['formato'] === $formato)
                && (empty($documento['procedimiento_id']) || (int) $documento['procedimiento_id'] === $procedimiento);
        });

        if (!empty($seleccion['cv_reporte'])) {
            $tipo = $seleccion['tipo_cv_reporte'] ?? 'general';
            foreach ($aplicables as $documento) {
                if ($documento['tipo'] === ($tipo === 'servicio' ? 'cv_servicio' : 'cv_general')) {
                    $rutas[] = $documento['pdf'];
                }
            }
        }
        if (!empty($seleccion['certificados'])) {
            foreach ($aplicables as $documento) {
                if ($documento['tipo'] === 'certificado') {
                    $rutas[] = $documento['pdf'];
                }
            }
        }

        return array_values(array_unique($rutas));
    }
}
