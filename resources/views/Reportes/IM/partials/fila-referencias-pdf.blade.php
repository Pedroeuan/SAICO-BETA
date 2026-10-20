{{--
    Fila de propiedades mecánicas compartida por los PDF del 04_03 en español e inglés.
    Recibe Datos_Equipo y conserva el orden y color de cada referencia seleccionada.
    Si el reporte histórico no tiene selección múltiple, utiliza sus campos originales.
--}}
<tr>
@if(array_key_exists('NORMAS_REFERENCIA_COINCIDENCIAS', $Datos_Equipo))
    {{-- Paleta del 04_03: negro, azul, rojo, morado, verde y naranja. --}}
    @php($colores = ['#111111', '#1565c0', '#c62828', '#6a1b9a', '#2e7d32', '#ad5700'])
    @foreach(['norma', 'dureza_max', 'tension_min', 'cedencia_min', 'tension_max'] as $campo)
        <td>
        @foreach(($Datos_Equipo['NORMAS_REFERENCIA_COINCIDENCIAS'] ?? []) as $indice => $referencia)
            @if(!$loop->first) / @endif
            <span style="color: {{ $colores[$indice % count($colores)] }};font-weight:bold">{{ $referencia[$campo] ?? '—' }}</span>
        @endforeach
        </td>
    @endforeach
@else
    @foreach(['NORMA_REFERENCIA', 'DUREZA_BRINELL_MAX', 'RESISTENCIA_TENSION_MIN', 'RESISTENCIA_CEDENCIA_ESPECIFICADA', 'RESISTENCIA_TENSION_MAX'] as $campo)
        <td>{{ $Datos_Equipo[$campo] ?? '' }}</td>
    @endforeach
@endif
</tr>
