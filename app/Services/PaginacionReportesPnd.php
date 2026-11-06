<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

/** Configuracion y numeracion comun para los formatos PND, con las mismas series de IM. */
class PaginacionReportesPnd
{
    public function __construct(private ServicioSerieReportes $series) {}

    public static function admite(?string $formato): bool
    {
        // El 12 no tiene vistas de edicion ni plantillas PDF en el proyecto.
        return $formato !== 'FOR-PINS-12-01' && str_starts_with($formato ?? '', 'FOR-PINS-');
    }

    /** Valida antes de guardar y recupera la serie del servidor, nunca del formulario. */
    public function prepararCaptura(Request $request, ?int $id = null): ?array
    {
        $request->validate([
            'Serie_Reportes' => 'sometimes|array:cantidad_planificada,nueva_cantidad',
            'Serie_Reportes.cantidad_planificada' => 'nullable|integer|min:1|max:999',
            'Serie_Reportes.nueva_cantidad' => 'nullable|integer|min:1|max:999',
        ]);
        $serie = $id ? $this->series->obtener($id) : null;
        if ($serie && $request->filled('Serie_Reportes.nueva_cantidad') &&
            (int) $request->input('Serie_Reportes.nueva_cantidad') <= (int) $serie->cantidad_planificada) {
            throw ValidationException::withMessages([
                'Serie_Reportes.nueva_cantidad' => 'El nuevo total debe ser mayor que el actual.',
            ]);
        }
        return $serie?->toArray();
    }

    /** Se ejecuta despues de persistir el reporte y conserva su pertenencia a la serie. */
    public function guardarConfiguracion(Request $request, int $id, string $formato): void
    {
        $serie = $this->series->obtener($id);
        if (!$serie) {
            $this->series->iniciar($id, (int) ($request->input('Serie_Reportes.cantidad_planificada') ?: 1), $formato);
        } elseif ($request->filled('Serie_Reportes.nueva_cantidad')) {
            $this->series->actualizarCantidad($id, (int) $request->input('Serie_Reportes.nueva_cantidad'));
        }
    }

    /** Cambia cuando otro integrante agrega hojas, se elimina o se amplia el total. */
    public function firma(int $id): string
    {
        return $this->series->firmaPaginacion($id);
    }

    /**
     * Une las secciones y estampa una sola secuencia, respetando la posicion y
     * orientacion de cada formato. Los PDF de documentos del QR no pasan por aqui.
     */
    public function combinar(int $id, array $documentos): Fpdi
    {
        $pdf = new Fpdi;
        $pdf->SetAutoPageBreak(false);
        $cantidades = [];
        foreach ($documentos as $documento) {
            $cantidades[] = $pdf->setSourceFile(StreamReader::createByString($documento['content']));
        }
        $total = array_sum($cantidades);
        $rango = $this->series->registrarPaginas($id, $total);
        $paginaSerie = (int) $rango['pagina_inicial'];
        foreach ($documentos as $indice => $documento) {
            $pdf->setSourceFile(StreamReader::createByString($documento['content']));
            for ($pagina = 1; $pagina <= $cantidades[$indice]; $pagina++, $paginaSerie++) {
                $plantilla = $pdf->importPage($pagina);
                $orientacion = strtoupper($documento['orientation']);
                // Conserva el tamano usado por los encabezados originales de PND.
                $pdf->AddPage($orientacion);
                $pdf->useTemplate($plantilla, 0, 0, $documento['width'], $documento['height']);
                if ($pagina === 1 && !empty($documento['skip_first_number'])) continue;
                $pdf->SetFont('Arial', 'B', 8);
                $y = $pagina === $cantidades[$indice]
                    ? ($documento['page_number_y_last'] ?? $documento['page_number_y'])
                    : $documento['page_number_y'];
                $pdf->SetXY($documento['page_number_x'], $y);
                $pdf->Cell(0, 10, $paginaSerie . ' de ' . $rango['total_estimado'], 0, 0, 'C');
            }
        }
        return $pdf;
    }
}
