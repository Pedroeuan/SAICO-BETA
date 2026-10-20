{{--
    Imagen de un disparo del 04_03: recibe Disparos, PerfilesDisparos, celdaDisparo e indiceImagen.
    El índice 0 corresponde a elementos y el 1 al espectro. La celda conserva su tamaño.
    Olympus adapta y centra la imagen; solo su tabla de elementos aumenta 8 mm de altura,
    con límite para mantenerla dentro de la celda. Vanta conserva el ajuste habitual.
--}}
@php
    $esOlympus = ($PerfilesDisparos[$celdaDisparo] ?? 'vanta') === 'olympus';
    $rutaImagenDisparo = $Disparos[$celdaDisparo][$indiceImagen] ?? '';
    $alturaElementos = null;
    if ($esOlympus && $indiceImagen === 0 && is_file($rutaImagenDisparo)) {
        $medidas = @getimagesize($rutaImagenDisparo);
        if ($medidas && $medidas[0] > 0) {
            // Altura que tenía al ajustarse al 85 % de su celda, más 8 mm.
            $anchoUtil = (45 - 2 * 25.4 / 96) * 0.85;
            $alturaElementos = min(46, min(32, $anchoUtil * $medidas[1] / $medidas[0]) + 8);
        }
    }
@endphp
<div class="shot-image {{ $esOlympus ? 'olympus' : '' }}">
    <table class="shot-center"><tr><td>
        @if($rutaImagenDisparo)
            <img src="{{ $rutaImagenDisparo }}" alt="Disparo {{ $celdaDisparo }}"
                @if($alturaElementos !== null) style="width:85%;height:{{ $alturaElementos }}mm;max-height:46mm;object-fit:fill" @endif>
        @endif
    </td></tr></table>
</div>
