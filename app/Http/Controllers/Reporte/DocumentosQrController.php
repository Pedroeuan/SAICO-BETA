<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;
use App\Models\Admin\Usuario;
use App\Services\DocumentosUsuarioReporte;
use Illuminate\Http\Request;

/**
 * Vista previa de C.V. y certificados desde Integridad Mecánica y PINS 03_02.
 * Consulta los mismos documentos que el generador del QR, sin guardar ni generar reportes.
 * La ruta utiliza el middleware auth; este controlador excluye además el rol Cliente.
 */
class DocumentosQrController extends Controller
{
    /** Lista los PDF seleccionados en JSON o entrega uno de ellos para abrirlo en otra pestaña. */
    public function index(Request $request, DocumentosUsuarioReporte $documentos)
    {
        abort_if($request->user()->rol === 'Cliente', 403);
        // Comprueba técnico y formato del catálogo; archivo es el índice del PDF en la selección actual.
        $datos = $request->validate([
            'tecnico' => 'required|integer|exists:users,id',
            'formato' => ['required', 'string', 'exists:Formato,Nombre', 'regex:#^(?:FOR-PIMP-|FOR-PINS-(?:03-02|04-01|16-01)$)#'],
            'procedimiento' => 'nullable|integer',
            'categoria' => 'required|in:cv_completo,cv_reporte,certificados',
            'tipo_cv_reporte' => 'required|in:general,servicio',
            'archivo' => 'nullable|integer|min:0',
        ]);
        // Usa al técnico del formulario, que puede ser distinto del usuario autenticado.
        $usuario = Usuario::findOrFail($datos['tecnico']);
        // Cada botón consulta solo su categoría; el servicio aplica los filtros de formato y procedimiento.
        $seleccion = [$datos['categoria'] => true, 'tipo_cv_reporte' => $datos['tipo_cv_reporte']];
        $rutas = $documentos->rutas($usuario, $seleccion, $datos['formato'],
            !empty($datos['procedimiento']) ? (int) $datos['procedimiento'] : null);

        // Recalcula las rutas al abrir el PDF: el cliente envía un índice, nunca una ruta libre.
        if ($request->has('archivo')) {
            $ruta = $rutas[$datos['archivo']] ?? null;
            $real = $ruta ? $this->resolver($ruta) : null;
            abort_unless($real, 404, 'El PDF seleccionado no está disponible.');
            return response()->file($real, ['Content-Type' => 'application/pdf', 'Cache-Control' => 'no-store']);
        }

        // Devuelve nombres, disponibilidad y enlaces de vista previa sin exponer las rutas físicas.
        $archivos = [];
        foreach ($rutas as $indice => $ruta) {
            $nombre = $ruta === $usuario->cv_pdf ? 'C.V. completo' : null;
            foreach ($usuario->documentos_reportes ?? [] as $documento) {
                if ($documento['pdf'] === $ruta) $nombre = $documento['nombre'] ?? 'C.V. / certificado';
            }
            $archivos[] = [
                'nombre' => $nombre ?? 'Documento PDF',
                'disponible' => $this->resolver($ruta) !== null,
                'url' => route('reportes.documentos-qr', array_merge($datos, ['archivo' => $indice])),
            ];
        }
        // Evita reutilizar una respuesta anterior al cambiar de técnico o de tipo de C.V.
        return response()->json(['tecnico' => $usuario->name, 'archivos' => $archivos])
            ->header('Cache-Control', 'no-store');
    }

    /** Busca archivos actuales y rutas históricas, aceptando únicamente archivos dentro de storage. */
    private function resolver(string $ruta): ?string
    {
        // Normaliza separadores y prefijos guardados en distintas versiones del sistema.
        $relativa = ltrim(str_replace('\\', '/', $ruta), '/');
        $relativa = preg_replace('#^(?:public/)?storage/#', '', $relativa);
        foreach ([\Illuminate\Support\Facades\Storage::disk('public')->path($relativa), storage_path($relativa)] as $candidato) {
            // realpath resuelve enlaces y segmentos relativos antes de comprobar el directorio permitido.
            $real = realpath($candidato);
            $base = realpath(storage_path());
            if ($real && $base && str_starts_with($real, $base . DIRECTORY_SEPARATOR) && is_file($real)) {
                return $real;
            }
        }
        return null;
    }
}
