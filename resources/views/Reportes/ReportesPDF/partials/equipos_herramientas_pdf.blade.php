@php
    $equiposHerramientas = $Datos_Equipo['EQUIPOS_HERRAMIENTAS'] ?? [];
    $requiereEquipos = strtolower((string) ($Datos_Equipo['REQUIERE_EQUIPOS'] ?? 'no'));
    $requiereEquipos = in_array($requiereEquipos, ['si', 'sí', 'sÃ­'], true) ? 'si' : 'no';
@endphp

@if($requiereEquipos === 'si' && !empty($equiposHerramientas))
    <table class="datosinspeccion" style="margin-bottom: 6px;">
        <thead class="encabezadoAzul">
            <tr>
                <th colspan="4">DATOS Y AJUSTES DEL EQUIPO</th>
            </tr>
        </thead>

        <thead>
            <tr class="celdaGris">
                <th>Equipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>N.S.</th>
            </tr>
        </thead>

        <tbody>
            @foreach($equiposHerramientas as $item)
                <tr>
                    <td>{{ $item['nombre'] ?? '' }}</td>
                    <td>{{ $item['marca'] ?? '' }}</td>
                    <td>{{ $item['modelo'] ?? '' }}</td>
                    <td>{{ $item['ns'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
