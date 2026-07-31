<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;

class ServicioAnalisisColumnasPdfXrf
{
    /** Inyecta el lector utilizado para extraer el texto de los archivos PDF. */
    public function __construct(private readonly Parser $parser) {}

    /** Lee un PDF cargado y devuelve sus columnas, filas y datos generales. */
    public function parseUploadedFile(UploadedFile $file): array
    {
        $document = $this->parser->parseFile($file->getRealPath());
        $result = $this->parseText($document->getText());
        $result['archivo'] = $file->getClientOriginalName();
        $result['paginas'] = count($document->getPages());
        return $result;
    }

    /** Consolida en una sola estructura las tablas encontradas en el texto del PDF. */
    public function parseText(string $text): array
    {
        $segments = $this->parseTableSegments($text);
        $columns = [];
        $rows = [];

        foreach ($segments as $segment) {
            $columns = array_values(array_unique(array_merge($columns, $segment['columnas'])));
            foreach ($segment['filas'] as $row) {
                $element = $row['elemento'];
                $rows[$element] = [
                    'elemento' => $element,
                    'unidad' => '%',
                    'tipo' => 'Conc',
                    'valores' => array_replace($rows[$element]['valores'] ?? [], $row['valores']),
                ];
            }
        }
        sort($columns);

        if ($columns === [] || $rows === []) {
            throw new \RuntimeException('No se encontró la tabla de concentraciones con columnas numeradas en el PDF.');
        }

        return ['columnas' => $columns, 'filas' => $rows];
    }

    /** Separa cada bloque de disparos, incluso cuando una tabla continúa en otra hoja. */
    public function parseTableSegments(string $text): array
    {
        $text = str_replace(["\r\n", "\r", "\xC2\xA0"], ["\n", "\n", ' '], $text);
        $text = (string) preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', ' ', $text);
        $lines = array_values(array_filter(array_map(
            static fn (string $line): string => trim((string) preg_replace('/\s+/u', ' ', $line)),
            explode("\n", $text)
        ), static fn (string $line): bool => $line !== ''));

        $segments = [];
        $current = null;

        $finishCurrent = static function () use (&$current, &$segments): void {
            if (is_array($current) && $current['filas'] !== []) {
                $segments[] = $current;
            }
            $current = null;
        };

        foreach ($lines as $line) {
            $headerMatch = preg_match('/^((?:\d+\s+){1,19}\d+)(?:\s+WarnMin(?:\s+.*)?)?$/iu', $line, $match)
                || preg_match('/^(\d+)\s+WarnMin(?:\s+.*)?$/iu', $line, $match);
            if ($headerMatch) {
                preg_match_all('/\d+/', $match[1], $numbers);
                $candidateColumns = array_values(array_unique(array_map('intval', $numbers[0])));
                $candidateColumns = array_values(array_filter(
                    $candidateColumns,
                    static fn (int $column): bool => $column >= 1 && $column <= 20
                ));
                $isConsecutive = $candidateColumns !== [];
                foreach ($candidateColumns as $position => $column) {
                    if ($position > 0 && $column !== $candidateColumns[$position - 1] + 1) {
                        $isConsecutive = false;
                        break;
                    }
                }
                if (!$isConsecutive) continue;

                $finishCurrent();
                $current = ['columnas' => $candidateColumns, 'filas' => []];
                continue;
            }
            if ($current === null) continue;
            // SD/RSD pertenece a la tabla estadística y no debe mezclarse con la continuación de los disparos.
            if (preg_match('/^(?:<X>\s+WarnMax|(?:SD\s+)?RSD(?:\s|$))/iu', $line)) {
                $finishCurrent();
                continue;
            }
            if (!preg_match('/^([A-Z][a-z]?)\s+%\s+Conc\s+(.+)$/u', $line, $match)) continue;

            $tokens = preg_split('/\s+/u', trim($match[2])) ?: [];
            if (count($tokens) < count($current['columnas'])) continue;
            $values = [];
            foreach ($current['columnas'] as $position => $column) {
                $values[$column] = (string) ($tokens[$position] ?? '');
            }
            $element = $this->canonicalElement($match[1]);
            $current['filas'][] = [
                'elemento' => $element,
                'unidad' => '%',
                'tipo' => 'Conc',
                'valores' => $values,
            ];
        }
        $finishCurrent();

        if ($segments === []) {
            throw new \RuntimeException('No se encontró la tabla de concentraciones con columnas numeradas en el PDF.');
        }

        return $segments;
    }

    /** Calcula el promedio por elemento usando de una a tres columnas seleccionadas. */
    public function calculateForColumns(array $analysis, array $selectedColumns): array
    {
        $selectedColumns = array_values(array_unique(array_map('intval', $selectedColumns)));
        if (count($selectedColumns) < 1 || count($selectedColumns) > 3) {
            throw new \InvalidArgumentException('Deben seleccionarse entre una y tres columnas diferentes.');
        }
        foreach ($selectedColumns as $column) {
            if (!in_array($column, array_map('intval', $analysis['columnas'] ?? []), true)) {
                throw new \InvalidArgumentException("La columna {$column} no existe en el PDF.");
            }
        }

        $results = [];
        foreach (($analysis['filas'] ?? []) as $element => $row) {
            $rawValues = [];
            $numericValues = [];
            foreach ($selectedColumns as $column) {
                $raw = trim((string) ($row['valores'][$column] ?? ''));
                $rawValues[$column] = $raw;
                $numeric = $this->plainNumericValue($raw);
                if ($numeric !== null) $numericValues[] = $numeric;
            }
            $calculable = count($numericValues) === count($selectedColumns);
            $results[$element] = [
                'elemento' => $element,
                'valores' => $rawValues,
                'calculable' => $calculable,
                'promedio' => $calculable ? round(array_sum($numericValues) / count($selectedColumns), 4) : null,
                'motivo' => $calculable ? null : 'Capture el valor manual únicamente para este elemento.',
            ];
        }
        return $results;
    }

    /** Devuelve el promedio calculado o conserva el valor manual cuando no puede calcularse. */
    public function resolveAverage(?array $result, string $manualValue): array
    {
        if (!empty($result['calculable']) && $result['promedio'] !== null) {
            return ['promedio' => number_format((float) $result['promedio'], 4, '.', ''), 'origen' => 'calculado'];
        }
        $manualValue = trim($manualValue);
        return ['promedio' => $manualValue, 'origen' => $manualValue === '' ? 'pendiente_manual' : 'manual'];
    }

    /** Normaliza el simbolo quimico para usar siempre la misma capitalizacion. */
    public function canonicalElement(string $element): string
    {
        $element = trim($element);
        return $element === '' ? '' : strtoupper(substr($element, 0, 1)) . strtolower(substr($element, 1));
    }

    /** Convierte un texto numerico simple a decimal y rechaza valores no numericos. */
    private function plainNumericValue(string $value): ?float
    {
        $value = trim(str_replace(',', '.', $value));
        return preg_match('/^-?\d+(?:\.\d+)?$/', $value) ? (float) $value : null;
    }
}
