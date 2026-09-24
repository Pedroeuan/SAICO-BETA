<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Models\Clientes\clientes;
use App\Models\Encuesta\Encuesta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EncuestaSatisfaccionController extends Controller
{
    /**
     * Guarda una encuesta por orden de servicio. Los datos del cliente y del
     * proyecto se toman del servidor; nunca del formulario del portal.
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        $cliente = clientes::where('portal_token', $token)->firstOrFail();

        $validated = $request->validate([
            'idOrden_Servicio' => ['required', 'integer'],
            'Preguntas' => ['required', 'array', 'size:10'],
            'Preguntas.PREGUNTA_1' => ['required', 'integer', 'between:1,5'],
            'Preguntas.PREGUNTA_2' => ['required', 'integer', 'between:1,5'],
            'Preguntas.PREGUNTA_3' => ['required', 'integer', 'between:1,5'],
            'Preguntas.PREGUNTA_4' => ['required', 'integer', 'between:1,5'],
            'Preguntas.PREGUNTA_5' => ['required', 'integer', 'between:1,5'],
            'Preguntas.PREGUNTA_6' => ['required', 'integer', 'between:1,5'],
            'Preguntas.PREGUNTA_7' => ['required', 'integer', 'between:1,5'],
            'Preguntas.PREGUNTA_8' => ['required', 'integer', 'between:1,5'],
            'Preguntas.PREGUNTA_9' => ['required', 'integer', 'between:1,5'],
            'Preguntas.PREGUNTA_10' => ['required', 'integer', 'between:1,5'],
            'Comentario' => ['nullable', 'string', 'max:2000'],
            'Firma.nombre' => ['required', 'string', 'max:200'],
            'Firma.confirmacion' => ['accepted'],
            'Firma.metodo' => ['required', 'in:dibujar,imagen,documento'],
            'Firma.imagen' => ['required_if:Firma.metodo,dibujar', 'nullable', 'string', 'max:500000'],
            'Firma.archivo_imagen' => ['required_if:Firma.metodo,imagen', 'nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $metodo = $validated['Firma']['metodo'];

        // dibujar e imagen quedan como PNG base64; documento queda sin firma hasta que suban el archivo.
        $firmaImagen = match ($metodo) {
            'dibujar' => $this->validarFirma($validated['Firma']['imagen']),
            'imagen'  => $this->procesarImagenSubida($request->file('Firma.archivo_imagen')),
            default   => null,
        };

        $orden = DB::table('orden_servicio')
            ->where('idOrden_Servicio', $validated['idOrden_Servicio'])
            ->where('idClientes', $cliente->idClientes)
            ->first();

        if (!$orden) {
            abort(403, 'El servicio no pertenece al cliente del portal.');
        }

        if (!$this->servicioTieneReportesFirmados((int) $orden->idOrden_Servicio)) {
            throw ValidationException::withMessages([
                'idOrden_Servicio' => 'La encuesta estará disponible al finalizar y firmar todos los reportes del servicio.',
            ]);
        }

        try {
            DB::transaction(function () use ($validated, $cliente, $orden, $metodo, $firmaImagen): void {
                if (Encuesta::where('idOrden_Servicio', $orden->idOrden_Servicio)->lockForUpdate()->exists()) {
                    throw ValidationException::withMessages([
                        'idOrden_Servicio' => 'Este servicio ya cuenta con una encuesta contestada.',
                    ]);
                }

                $firmaCliente = [
                    'Nombre' => $validated['Firma']['nombre'],
                    'Metodo' => $metodo,
                    'Fecha'  => now()->format('Y-m-d H:i:s'),
                ];
                if ($firmaImagen) {
                    $firmaCliente['Imagen'] = $firmaImagen;
                }

                $preguntas = $validated['Preguntas'];
                $promedio = round(array_sum($preguntas) / count($preguntas), 2);

                Encuesta::create([
                    'idOrden_Servicio' => $orden->idOrden_Servicio,
                    'idClientes' => $cliente->idClientes,
                    'Detalles_Generales' => json_encode([
                        'Fecha' => now()->format('Y-m-d'),
                        'Cliente' => $cliente->Cliente,
                        'Contrato' => $orden->Contrato,
                        'Proyecto' => $orden->Proyecto_actividad,
                        'Telefono' => $cliente->Telefono,
                    ], JSON_UNESCAPED_UNICODE),
                    'Preguntas' => json_encode($preguntas, JSON_UNESCAPED_UNICODE),
                    'Promedio' => $promedio,
                    'Comentario' => $validated['Comentario'] ?? null,
                    'Firmas' => json_encode(['CLIENTE' => $firmaCliente], JSON_UNESCAPED_UNICODE),
                    'Estatus' => 'CONTESTADA',
                ]);
            });
        } catch (\Illuminate\Database\QueryException $exception) {
            if ((int) $exception->errorInfo[1] === 1062) {
                throw ValidationException::withMessages([
                    'idOrden_Servicio' => 'Este servicio ya cuenta con una encuesta contestada.',
                ]);
            }

            throw $exception;
        }

        $encuesta = Encuesta::where('idOrden_Servicio', $orden->idOrden_Servicio)->firstOrFail();

        return redirect()
            ->route('Reportes.Clientes', [
                'token' => $token,
                'idOrden_Servicio' => $orden->idOrden_Servicio,
            ])
            ->with('encuesta_guardada', $metodo === 'documento'
                ? 'Encuesta guardada. Descárguela, fírmela a mano y súbala.'
                : 'Gracias. Tu encuesta de satisfacción fue registrada.')
            ->with('encuesta_id_descarga', $encuesta->idEncuesta);
    }
    public function descargarPdf(string $token, int $idEncuesta)
    {
        $cliente = clientes::where('portal_token', $token)->firstOrFail();
        $encuesta = Encuesta::where('idEncuesta', $idEncuesta)
            ->where('idClientes', $cliente->idClientes)
            ->firstOrFail();

        $firma = json_decode($encuesta->Firmas, true)['CLIENTE'] ?? [];

        // Ya subieron el archivo firmado a mano: se entrega ese archivo.
        if (($firma['Metodo'] ?? null) === 'documento' && !empty($firma['Archivo'])) {
            abort_unless(Storage::disk('local')->exists($firma['Archivo']), 404, 'Archivo no encontrado.');

            $extension = pathinfo($firma['Archivo'], PATHINFO_EXTENSION);

            return Storage::disk('local')->download($firma['Archivo'], "Encuesta_firmada_{$encuesta->idEncuesta}.{$extension}");
        }

        // Firma dibujada/imagen, o encuesta pendiente de firma manual (PDF sin firma para imprimir).
        $detalles = json_decode($encuesta->Detalles_Generales, true) ?: [];
        $preguntas = json_decode($encuesta->Preguntas, true) ?: [];

        $textosPreguntas = $this->textosPreguntas();
        $calificacionFinal = $this->calificacionFinal($preguntas);
        $clasificacion = $this->clasificacionFinal($calificacionFinal);

        return Pdf::loadView('Encuestas.satisfaccion-pdf', compact(
            'encuesta',
            'detalles',
            'preguntas',
            'firma',
            'textosPreguntas',
            'calificacionFinal',
            'clasificacion'
        ))->download("Encuesta_satisfaccion_{$encuesta->idEncuesta}.pdf");
    }

    private function servicioTieneReportesFirmados(int $idOrdenServicio): bool
    {
        $reportes = DB::table('lineal_ideal as li')
            ->join('reportes as r', 'r.idReportes', '=', 'li.idReportes')
            ->where('li.idOrden_Servicio', $idOrdenServicio)
            ->pluck('r.Detalles_Generales');

        if ($reportes->isEmpty()) {
            return false;
        }

        return $reportes->every(function ($detalles): bool {
            $datos = json_decode($detalles, true) ?: [];

            return !empty($datos['Reporte_Firmado']);
        });
    }

    private function validarFirma(string $firma): string
    {
        if (!preg_match('/^data:image\\/png;base64,([A-Za-z0-9+\\/]+=*)$/', $firma, $coincidencias)) {
            throw ValidationException::withMessages([
                'Firma.imagen' => 'La firma debe ser una imagen PNG válida.',
            ]);
        }

        $binario = base64_decode($coincidencias[1], true);

        if ($binario === false || strlen($binario) > 300000) {
            throw ValidationException::withMessages([
                'Firma.imagen' => 'La imagen de la firma es demasiado grande.',
            ]);
        }

        return $firma;
    }

    private function textosPreguntas(): array
    {
        return [
            '¿Cómo califica el servicio que le brindó AICO S.C.?',
            '¿Cómo considera la atención del personal de ventas?',
            '¿Cómo es el trato y la atención del personal hacia usted y/o sus representantes?',
            '¿Considera que todo el personal se encuentra capacitado e idóneo para realizar los servicios?',
            '¿Cómo califica las instalaciones, elementos, productos o equipos empleados en el servicio?',
            '¿Se utiliza adecuadamente el equipo de protección personal por el personal de AICO S.C.?',
            '¿Qué tan eficiente considera la entrega de los reportes?',
            '¿En qué escala considera el profesionalismo del servicio brindado?',
            '¿Recomendaría los servicios que AICO S.C. le brindó?',
            '¿El servicio que le brindó AICO S.C. cumplió con sus expectativas?',
        ];
    }

    /** Regla del formato FOR-PVEN-01/03: cada nivel equivale a nivel × 2 %. */
    private function calificacionFinal(array $preguntas): int
    {
        return array_sum(array_map('intval', $preguntas)) * 2;
    }

    private function clasificacionFinal(int $calificacion): string
    {
        return match (true) {
            $calificacion >= 90 => 'Excelente',
            $calificacion >= 80 => 'Bueno',
            $calificacion >= 70 => 'Aceptable',
            $calificacion >= 60 => 'Regular',
            default => 'Malo',
        };
    }

    private function procesarImagenSubida(UploadedFile $archivo): string
    {
        $origen = @imagecreatefromstring((string) file_get_contents($archivo->getRealPath()));

        if (!$origen) {
            throw ValidationException::withMessages([
                'Firma.archivo_imagen' => 'La imagen de la firma no es válida.',
            ]);
        }

        $ancho = imagesx($origen);
        $alto = imagesy($origen);
        $anchoFinal = min($ancho, 600);
        $altoFinal = (int) round($alto * ($anchoFinal / $ancho));

        $destino = imagecreatetruecolor($anchoFinal, $altoFinal);
        imagealphablending($destino, false);
        imagesavealpha($destino, true);
        imagefill($destino, 0, 0, imagecolorallocatealpha($destino, 255, 255, 255, 127));
        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $anchoFinal, $altoFinal, $ancho, $alto);

        ob_start();
        imagepng($destino, null, 6);
        $png = (string) ob_get_clean();

        imagedestroy($origen);
        imagedestroy($destino);

        if (strlen($png) > 300000) {
            throw ValidationException::withMessages([
                'Firma.archivo_imagen' => 'La imagen de la firma es demasiado grande.',
            ]);
        }

        return 'data:image/png;base64,' . base64_encode($png);
    }
    public function subirFirmada(Request $request, string $token, int $idEncuesta): RedirectResponse
    {
        $cliente = clientes::where('portal_token', $token)->firstOrFail();
        $encuesta = Encuesta::where('idEncuesta', $idEncuesta)
            ->where('idClientes', $cliente->idClientes)
            ->firstOrFail();

        $request->validate([
            'archivo' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
        ], [
            'archivo.required' => 'Seleccione la encuesta firmada.',
            'archivo.mimes' => 'El archivo debe ser PDF, PNG o JPG.',
            'archivo.max' => 'El archivo no debe superar 5 MB.',
        ]);

        $firmas = json_decode($encuesta->Firmas, true) ?: [];
        $firmaCliente = $firmas['CLIENTE'] ?? [];

        if (($firmaCliente['Metodo'] ?? null) !== 'documento' || !empty($firmaCliente['Archivo'])) {
            throw ValidationException::withMessages([
                'archivo' => 'Esta encuesta no admite subir un archivo firmado.',
            ]);
        }

        $firmaCliente['Archivo'] = $request->file('archivo')->store('encuestas/firmadas', 'local');
        $firmaCliente['Fecha_firmada'] = now()->format('Y-m-d H:i:s');
        $firmas['CLIENTE'] = $firmaCliente;

        $encuesta->update(['Firmas' => json_encode($firmas, JSON_UNESCAPED_UNICODE)]);

        return back()->with('encuesta_guardada', 'Encuesta firmada recibida correctamente.');
    }
}
