<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;

class ServicioAnalisisColumnasPdfXrf
{
    public function __construct(private readonly Parser $parser) {}

    public function parseUploadedFile(UploadedFile $file): array
    {
        $document = $this->parser->parseFile($file->getRealPath());
        $result = $this->parseText($document->getText());
        $result['archivo'] = $file->getClientOriginalName();
        $result['paginas'] = count($document->getPages());
        return $result;
    }

    public function parseText(string $text): array
    {
        $text = str_replace(["\r\n", "\r", "\xC2\xA0"], ["\n", "\n", ' '], $text);
        $text = (string) preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', ' ', $text);
        $lines = array_values(array_filter(array_map(
            static fn (string $line): string => trim((string) preg_replace('/\s+/u', ' ', $line)),
            explode("\n", $text)
        ), static fn (string $line): bool => $line !== ''));

        $columns = [];
        $rows = [];
        $readingTable = false;

        foreach ($lines as $line) {
            if (!$readingTable && preg_match('/^((?:\d+\s+){2,}\d+)\s+WarnMin$/iu', $line, $match)) {
                preg_match_all('/\d+/', $match[1], $numbers);
                $columns = array_values(array_unique(array_map('intval', $numbers[0])));
                $readingTable = count($columns) >= 3;
                continue;
            }
            if (!$readingTable) continue;
            if (preg_match('/^<X>\s+WarnMax\s+SD\s+RSD$/iu', $line)) break;
            if (!preg_match('/^([A-Z][a-z]?)\s+%\s+Conc\s+(.+)$/u', $line, $match)) continue;

            $tokens = preg_split('/\s+/u', trim($match[2])) ?: [];
            if (count($tokens) < count($columns)) continue;
            $values = [];
            foreach ($columns as $position => $column) {
                $values[$column] = (string) ($tokens[$position] ?? '');
            }
            $element = $this->canonicalElement($match[1]);
            $rows[$element] = ['elemento' => $element, 'unidad' => '%', 'tipo' => 'Conc', 'valores' => $values];
        }

        if (count($columns) < 3 || $rows === []) {
            throw new \RuntimeException('No se encontró la tabla de concentraciones con columnas numeradas en el PDF.');
        }

        return ['columnas' => $columns, 'filas' => $rows];
    }

    public function calculateForColumns(array $analysis, array $selectedColumns): array
    {
        $selectedColumns = array_values(array_unique(array_map('intval', $selectedColumns)));
        if (count($selectedColumns) !== 3) {
            throw new \InvalidArgumentException('Deben seleccionarse exactamente tres columnas diferentes.');
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
            $calculable = count($numericValues) === 3;
            $results[$element] = [
                'elemento' => $element,
                'valores' => $rawValues,
                'calculable' => $calculable,
                'promedio' => $calculable ? round(array_sum($numericValues) / 3, 4) : null,
                'motivo' => $calculable ? null : 'Capture el valor manual únicamente para este elemento.',
            ];
        }
        return $results;
    }

    public function resolveAverage(?array $result, string $manualValue): array
    {
        if (!empty($result['calculable']) && $result['promedio'] !== null) {
            return ['promedio' => number_format((float) $result['promedio'], 4, '.', ''), 'origen' => 'calculado'];
        }
        $manualValue = trim($manualValue);
        return ['promedio' => $manualValue, 'origen' => $manualValue === '' ? 'pendiente_manual' : 'manual'];
    }

    public function canonicalElement(string $element): string
    {
        $element = trim($element);
        return $element === '' ? '' : strtoupper(substr($element, 0, 1)) . strtolower(substr($element, 1));
    }

    private function plainNumericValue(string $value): ?float
    {
        $value = trim(str_replace(',', '.', $value));
        return preg_match('/^-?\d+(?:\.\d+)?$/', $value) ? (float) $value : null;
    }
}
