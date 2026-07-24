<?php

use App\Services\ServicioAnalisisPdfXrf;
use Smalot\PdfParser\Parser;

// Verifica extracción de metadatos, lecturas químicas y promedio de tres PDF equivalentes.
it('extrae lecturas XRF y calcula promedios para los elementos seleccionados', function () {
    $service = new ServicioAnalisisPdfXrf(new Parser());
    $text = <<<'XRF'
Chemistry
Serial\x01Number\x01:\x01844296
Time\x01:\x012026-05-18\x0116:02:38 Method\x01:\x01AlloyPlus
Daily ID : 42
Elapsed Time : 10 s
2 1-4 Cr 2.25 Cr (P22)
El % +/- 3σ
Si 0.47 0.13
0.00 0.50
P 0.549 0.087
0.00 0.03
S 0.021 0.065
0.00 0.03
Cr 2.423 0.065
2.00 2.50
Mn 0.452 0.060
0.30 0.60
Fe 94.71 0.19
95.30 97.10
Ni 0.061 0.042 Resid. 0.2
Cu 0.129 0.024
0.00 0.50
Zn 0.131 0.020 No Spec
Mo 1.061 0.021
0.90 1.10
XRF;

    $text = str_replace('\\x01', "\x01", $text);
    $first = $service->parseText($text);
    $second = $service->parseText(str_replace('Cr 2.423', 'Cr 2.223', $text));
    $third = $service->parseText(str_replace('Cr 2.423', 'Cr 2.323', $text));
    $averages = $service->averageForElements([$first, $second, $third], ['Cr', 'Mo', 'P']);

    expect($first['metadatos']['numero_serie'])->toBe('844296')
        ->and($first['metadatos']['metodo'])->toBe('AlloyPlus')
        ->and($first['metadatos']['aleacion_detectada'])->toBe('2 1-4 Cr 2.25 Cr (P22)')
        ->and($first['lecturas']['P']['valor'])->toBe(0.549)
        ->and($first['lecturas']['P']['especificacion_pdf'])->toBe('0.00 0.03')
        ->and($first['lecturas']['Fe']['valor'])->toBe(94.71)
        ->and($first['lecturas'])->not->toHaveKey('Fe&Cu')
        ->and($averages['Cr']['promedio'])->toBe(2.323)
        ->and($averages['Cr']['cantidad'])->toBe(3)
        ->and($averages['Mo']['promedio'])->toBe(1.061);
});

// Los valores con calificadores y los elementos ausentes no deben producir promedios engañosos.
it('no promedia valores calificados ni elementos ausentes', function () {
    $service = new ServicioAnalisisPdfXrf(new Parser());
    $qualified = $service->parseText("P <0.01 0.002\n0.00 0.03");
    $numeric = $service->parseText("P 0.02 0.002\n0.00 0.03");
    $averages = $service->averageForElements([$qualified, $numeric], ['P', 'Cr']);

    expect($averages['P']['cantidad'])->toBe(1)
        ->and($averages['P']['esperados'])->toBe(2)
        ->and($averages['Cr']['promedio'])->toBeNull();
});

// Rechaza tanto una norma incompatible como una mezcla de grados entre archivos cargados.
it('exige que el grado detectado coincida con la norma y con todos los PDF', function () {
    $service = new ServicioAnalisisPdfXrf(new Parser());
    $p22 = ['metadatos' => ['aleacion_detectada' => '2 1-4 Cr 2.25 Cr (P22)']];
    $p11 = ['metadatos' => ['aleacion_detectada' => '1 1-4 Cr 1-2 Mo (P11)']];

    expect(fn () => $service->assertCompatibleWithNorm([$p22, $p22], 'ASTM A335', 'GR. P22'))
        ->not->toThrow(\RuntimeException::class)
        ->and(fn () => $service->assertCompatibleWithNorm([$p22], 'ASTM A135', 'TABLA 1'))
        ->toThrow(\RuntimeException::class, 'detectó el grado P22')
        ->and(fn () => $service->assertCompatibleWithNorm([$p22, $p11], 'ASTM A335', 'GR. P22'))
        ->toThrow(\RuntimeException::class, 'P22, P11');
});
