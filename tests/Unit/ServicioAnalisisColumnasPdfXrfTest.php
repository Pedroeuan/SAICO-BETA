<?php

use App\Services\ServicioAnalisisColumnasPdfXrf;
use Smalot\PdfParser\Parser;

it('lee disparos OES con estadisticas en el mismo encabezado sin mezclarlas con los promedios', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    $analysis = $service->parseText(<<<'PDF'
1 2 3 4 5 <X> WarnMax SD
C % Conc 0.224 0.218 0.210 0.219 0.204 0.215 0.300 0.0079
Mn % Conc 0.83 0.84 0.84 0.85 0.83 0.84 1.40 0.0068
P % Conc 0.0086 0.0122 0.0084 0.0096 0.0085 0.0095 0.045 0.0016
S % Conc [C<]0.0020 [C<]0.0020 [C<]0.0020 [C<]0.0020 [C<]0.0020 [C<]0.0020 0.045 0
RSD
C % Conc 3.67
Mn % Conc 0.82
PDF);
    $results = $service->calculateForColumns($analysis, [1, 2, 3]);
    expect($analysis['columnas'])->toBe([1, 2, 3, 4, 5])
        ->and($analysis['filas']['C']['valores'])->toBe([1 => '0.224', 2 => '0.218', 3 => '0.210', 4 => '0.219', 5 => '0.204'])
        ->and($results['C']['promedio'])->toBe(0.2173)
        ->and($results['Mn']['promedio'])->toBe(0.8367)
        ->and($results['P']['promedio'])->toBe(0.0097)
        ->and($results['S']['calculable'])->toBeFalse()
        ->and($results['S']['promedio'])->toBeNull();
});

it('conserva la lectura de tablas con WarnMin y estadisticas separadas', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    $analysis = $service->parseText("1 2 3 WarnMin\nC % Conc 0.10 0.20 0.30 0.05\n<X> WarnMax SD\nC % Conc 0.20 0.50 0.01");
    expect($analysis['columnas'])->toBe([1, 2, 3])
        ->and($service->calculateForColumns($analysis, [1, 2, 3])['C']['promedio'])->toBe(0.2);
});
