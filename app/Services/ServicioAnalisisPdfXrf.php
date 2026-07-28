<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;

class ServicioAnalisisPdfXrf
{
    /** Símbolos químicos aceptados para evitar interpretar como elementos otros renglones del PDF. */
    private const ELEMENTS = [
        'Al', 'Si', 'P', 'S', 'Ti', 'V', 'Cr', 'Mn', 'Fe', 'Co', 'Ni', 'Cu',
        'Zn', 'Nb', 'Mo', 'W', 'Pb', 'Bi', 'Sn', 'Mg', 'Ca', 'As', 'Sb', 'Zr',
        'Ta', 'Hf', 'Re', 'C', 'B', 'N',
    ];

    public function __construct(private readonly Parser $parser)
    {
    }

    /** Lee un PDF XRF de una sola hoja y devuelve metadatos y lecturas normalizadas. */
    public function parseUploadedFile(UploadedFile $file): array
    {
        $document = $this->parser->parseFile($file->getRealPath());
        $pages = $document->getPages();

        if (count($pages) !== 1) {
            throw new \RuntimeException('El archivo debe contener exactamente una hoja.');
        }

        $result = $this->parseText($document->getText());
        $result['archivo'] = $file->getClientOriginalName();
        $result['paginas'] = count($pages);

        if (empty($result['lecturas'])) {
            throw new \RuntimeException('No se encontraron lecturas químicas en el PDF. Verifique que contenga texto seleccionable.');
        }

        return $result;
    }

    /** Convierte el texto extraído del equipo en una estructura independiente del orden visual del PDF. */
    public function parseText(string $text): array
    {
        $text = str_replace(["\r\n", "\r", "\xC2\xA0"], ["\n", "\n", ' '], $text);
        // Algunos analizadores XRF separan visualmente el texto con caracteres
        // de control (por ejemplo 0x01) en lugar de espacios normales.
        $text = (string) preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', ' ', $text);
        $lines = array_values(array_filter(array_map(
            static fn (string $line): string => trim((string) preg_replace('/\s+/u', ' ', $line)),
            explode("\n", $text)
        ), static fn (string $line): bool => $line !== ''));
        $flat = implode("\n", $lines);

        $metadata = [
            'numero_serie' => $this->capture($flat, '/Serial\s*Number\s*:\s*([^\n]+)/iu'),
            'fecha_hora' => $this->capture($flat, '/Time\s*:\s*(\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2})/iu'),
            'metodo' => $this->capture($flat, '/Method\s*:\s*([^\n]+)/iu'),
            'daily_id' => $this->capture($flat, '/Daily\s*ID\s*:\s*([^\s\n]+)/iu'),
            'tiempo_transcurrido' => $this->capture($flat, '/Elapsed\s*Time\s*:\s*([^\n]+)/iu'),
            'aleacion_detectada' => $this->capture($flat, '/Elapsed\s*Time\s*:\s*[^\n]+\n([^\n]+)/iu'),
        ];

        $symbols = implode('|', array_map('preg_quote', self::ELEMENTS));
        $readings = [];

        foreach ($lines as $index => $line) {
            if (!preg_match('/^(' . $symbols . ')\s+([<>≤≥]?\s*\d+(?:[.,]\d+)?)\s+(\d+(?:[.,]\d+)?)(?:\s+(.*))?$/iu', $line, $match)) {
                continue;
            }

            $element = $this->canonicalElement($match[1]);
            $rawValue = preg_replace('/\s+/u', '', $match[2]);
            $qualifier = preg_match('/^[<>≤≥]/u', $rawValue, $qualifierMatch) ? $qualifierMatch[0] : null;
            $numericValue = (float) str_replace(',', '.', preg_replace('/^[<>≤≥]/u', '', $rawValue));
            $specification = trim($match[4] ?? '');

            if ($specification === '' && isset($lines[$index + 1]) && $this->looksLikeSpecification($lines[$index + 1])) {
                $specification = $lines[$index + 1];
            }

            $readings[$element] = [
                'elemento' => $element,
                'valor' => $numericValue,
                'valor_original' => $rawValue,
                'calificador' => $qualifier,
                'incertidumbre_3sigma' => (float) str_replace(',', '.', $match[3]),
                'especificacion_pdf' => $specification,
            ];
        }

        return [
            'metadatos' => $metadata,
            'lecturas' => $readings,
        ];
    }

    /** Calcula cada promedio únicamente cuando la lectura no contiene calificadores como < o >. */
    public function averageForElements(array $analyses, array $elements): array
    {
        $averages = [];

        foreach ($elements as $element) {
            $canonical = $this->canonicalElement((string) $element);
            $values = [];

            foreach ($analyses as $analysis) {
                $reading = $analysis['lecturas'][$canonical] ?? null;
                if ($reading !== null && $reading['calificador'] === null) {
                    $values[] = (float) $reading['valor'];
                }
            }

            $averages[$canonical] = [
                'promedio' => $values === [] ? null : round(array_sum($values) / count($values), 4),
                'valores' => $values,
                'cantidad' => count($values),
                'esperados' => count($analyses),
            ];
        }

        return $averages;
    }

    /** Conserva la capitalización oficial del símbolo químico para comparar lecturas y normas. */
    public function canonicalElement(string $element): string
    {
        foreach (self::ELEMENTS as $symbol) {
            if (strcasecmp(trim($element), $symbol) === 0) {
                return $symbol;
            }
        }

        return trim($element);
    }

    /** Impide mezclar PDF de grados distintos o incompatibles con la norma seleccionada. */
    public function assertCompatibleWithNorm(array $analyses, string $specification, string $variable): void
    {
        $grades = array_values(array_unique(array_filter(array_map(
            fn (array $analysis): ?string => $this->detectedGrade($analysis),
            $analyses
        ))));

        if (count($grades) > 1) {
            throw new \RuntimeException(
                'Los PDF no corresponden al mismo grado: ' . implode(', ', $grades) . '.'
            );
        }

        if ($grades === []) {
            return;
        }

        $selectedNorm = strtoupper((string) preg_replace('/[^A-Z0-9]/i', '', $specification . $variable));
        $detectedGrade = $grades[0];

        if (!str_contains($selectedNorm, $detectedGrade)) {
            throw new \RuntimeException(
                "El equipo detectó el grado {$detectedGrade}, pero la norma/tabla seleccionada es {$specification} {$variable}."
            );
        }
    }

    /** Extrae grados comunes del nombre de aleación reportado por el equipo, por ejemplo P22 o TP316. */
    public function detectedGrade(array $analysis): ?string
    {
        $alloy = (string) ($analysis['metadatos']['aleacion_detectada'] ?? '');

        if (!preg_match('/\b(TP|P|F)\s*[- ]?\s*(\d+[A-Z0-9]*)\b/i', $alloy, $match)) {
            return null;
        }

        return strtoupper($match[1] . $match[2]);
    }

    /** Devuelve la primera captura de una expresión regular o null si el dato no existe. */
    private function capture(string $text, string $pattern): ?string
    {
        return preg_match($pattern, $text, $match) ? trim($match[1]) : null;
    }

    /** Reconoce renglones de especificación para asociarlos con el elemento anterior. */
    private function looksLikeSpecification(string $line): bool
    {
        return (bool) preg_match('/^(?:[<>≤≥]?\s*\d+(?:[.,]\d+)?\s+[<>≤≥]?\s*\d+(?:[.,]\d+)?|Resid\.?|No\s*Spec)/iu', $line);
    }
}
