<?php

use App\Services\ServicioAnalisisColumnasPdfXrf;
use Smalot\PdfParser\Parser;

it('excluye los limites C menor de la suma y del divisor', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    $analysis = $service->parseText("1 2 3\nS % Conc 0.0063 [C<]0.0020 [C<]0.0020\nP % Conc 0.0063 -- [C<]0.0020");
    $results = $service->calculateForColumns($analysis, [1, 2, 3]);
    expect($results['S']['calculable'])->toBeTrue()
        ->and($results['S']['promedio'])->toBe(0.0063)
        ->and($results['S']['valor_sugerido'])->toBeNull()
        ->and($results['S']['valores'][2])->toBe('[C<]0.0020')
        ->and($service->resolveAverage($results['S'], ''))->toBe(['promedio' => '0.0063', 'origen' => 'calculado'])
        ->and($results['P']['calculable'])->toBeFalse()
        ->and($results['P']['valor_sugerido'])->toBeNull();
});

it('propone el limite calificado repetido sin promediarlo y respeta la edicion del tecnico', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    $analysis = $service->parseText("7 8 9\nV % Conc [C<]0.0040 [C<]0.0040 [C<]0.0040\nS % Conc [C<]0.0020 0.0028 [C<]0.0020\nC % Conc -- -- --");
    $results = $service->calculateForColumns($analysis, [7, 8, 9]);
    expect($results['V']['calculable'])->toBeFalse()
        ->and($results['V']['promedio'])->toBeNull()
        ->and($results['V']['valor_repetido'])->toBe('[C<]0.0040')
        ->and($service->resolveAverage($results['V'], ''))->toBe(['promedio' => '[C<]0.0040', 'origen' => 'manual'])
        ->and($service->resolveAverage($results['V'], 'Revisado'))->toBe(['promedio' => 'Revisado', 'origen' => 'manual'])
        ->and($results['S']['valor_repetido'])->toBeNull()
        ->and($results['C']['valor_repetido'])->toBeNull();
});

it('consolida una columna repartida en tres hojas y respeta el orden solicitado', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    $analysis = $service->parseText(<<<'PDF'
7 8
C % Conc 0.153 0.148
Page 1 of 3
7 8
Mn % Conc 0.92 0.92
Page 2 of 3
7 8
P % Conc 0.0122 0.0127
11
C % Conc 0.142
Mn % Conc 0.87
P % Conc 0.0150
SD RSD
C % Conc 0.0184 14.16
PDF);
    $results = $service->calculateForColumns($analysis, [11, 7, 8]);
    expect($analysis['columnas'])->toBe([7, 8, 11])
        ->and($results['C']['valores'])->toBe([11 => '0.142', 7 => '0.153', 8 => '0.148'])
        ->and($results['Mn']['promedio'])->toBe(0.9033)
        ->and($results['P']['promedio'])->toBe(0.0133);
});

it('conserva filas incompletas y no calcula un promedio con menos disparos', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    $analysis = $service->parseText("7 8 9\nC % Conc 0.153 0.148\nMn % Conc 0.92 -- 0.87");
    $results = $service->calculateForColumns($analysis, [7, 8, 9]);
    expect($results['C']['valores'])->toBe([7 => '0.153', 8 => '0.148', 9 => ''])
        ->and($results['C']['calculable'])->toBeFalse()
        ->and($results['C']['promedio'])->toBeNull()
        ->and($results['Mn']['calculable'])->toBeFalse()
        ->and($service->calculateForColumns($analysis, [7, 8])['C']['promedio'])->toBe(0.1505);
});

it('rechaza valores contradictorios del mismo elemento y disparo en lugar de sobrescribirlos', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    expect(fn () => $service->parseText("7 8\nC % Conc 0.153 0.148\n7 8\nC % Conc 0.999 0.148"))
        ->toThrow(RuntimeException::class, 'disparo 7');
});

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

it('excluye la tabla estadistica completa que comienza por WarnMin sin sobrescribir los disparos', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    $analysis = $service->parseText(<<<'PDF'
1 2 3 4 5
C % Conc 0.81 0.0233 0.0181 0.058 0.0223
Mn % Conc 6.74 0.49 0.50 0.51 0.51
6 7 8 9 10
C % Conc 0.0196 0.032 0.0197 0.0255 0.0202
Mn % Conc 0.51 0.50 0.51 0.52 0.50
WarnMin <X> WarnMax SD RSD
C % Conc -- 0.0265 0.300 0.0125 46.99
Mn % Conc 0.290 0.51 1.06 0.0075 1.48
PDF);
    $results = $service->calculateForColumns($analysis, [5, 6, 8]);
    expect($results['C']['valores'])->toBe([5 => '0.0223', 6 => '0.0196', 8 => '0.0197'])
        ->and($results['C']['promedio'])->toBe(0.0205)
        ->and($results['Mn']['promedio'])->toBe(0.51);
});

it('une disparos entre paginas y excluye WarnMax SD RSD para las columnas 7 8 y 11', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    $texto = <<<'PDF'
6 7 8 9 10
C % Conc 0.106 0.153 0.148 0.138 --
Mn % Conc 0.94 0.92 0.92 5.09 --
P % Conc 0.0092 0.0122 0.0127 [C<]0.0030 --
S % Conc [C<]0.0020 [C<]0.0020 [C<]0.0020 [C<]0.0020 --
Page 1 of 3
6 7 8 9 10
Fe % Conc 96.2 98.2 98.2 89.0 100.0
11 12 13 WarnMin <X>
C % Conc 0.142 0.117 0.134 -- 0.130
Mn % Conc 0.87 0.91 2.12 0.290 [W>]1.13
P % Conc 0.0150 0.0157 [C<]0.0030 -- 0.0103
S % Conc 0.0028 [C<]0.0020 [C<]0.0020 -- [C<]0.0020
Fe % Conc 98.3 98.2 91.6 -- 96.4
WarnMax SD RSD
C % Conc 0.300 0.0184 14.16
Mn % Conc 1.06 0.49 43.30
P % Conc 0.035 0.0044 42.37
Page 2 of 3
WarnMax SD RSD
Fe % Conc -- 2.52 2.61
PDF;
    $segmentos = $service->parseTableSegments($texto);
    $analysis = $service->parseText($texto);
    $results = $service->calculateForColumns($analysis, [7, 8, 11]);
    expect(array_map(fn ($segmento) => count($segmento['filas']), $segmentos))->toBe([4, 1, 5])
        ->and($results['C']['valores'])->toBe([7 => '0.153', 8 => '0.148', 11 => '0.142'])
        ->and($results['C']['promedio'])->toBe(0.1477)
        ->and($results['Mn']['promedio'])->toBe(0.9033)
        ->and($results['P']['promedio'])->toBe(0.0133)
        ->and($results['Fe']['promedio'])->toBe(98.2333)
        ->and($results['S']['calculable'])->toBeTrue()
        ->and($results['S']['promedio'])->toBe(0.0028);
});

it('calcula As con dos disparos numericos sin sumar el tercer limite', function () {
    $service = new ServicioAnalisisColumnasPdfXrf(new Parser());
    $analysis = $service->parseText("1 2 3\nAs % Conc 0.0059 0.0077 [C<]0.0050\nCu % Conc 0 [C<]0.0020 0.0040");
    $results = $service->calculateForColumns($analysis, [1, 2, 3]);
    expect($results['As']['promedio'])->toBe(0.0068)
        ->and($results['As']['lecturas_utilizadas'])->toBe(2)
        ->and($results['As']['limites_excluidos'])->toBe(1)
        ->and($results['As']['valores'][3])->toBe('[C<]0.0050')
        ->and($service->resolveAverage($results['As'], '0.0062')['promedio'])->toBe('0.0068')
        ->and($results['Cu']['promedio'])->toBe(0.002);
});
