<section aria-labelledby="titulo-resultados-quimicos" style="margin-top:24px">
    <h3 id="titulo-resultados-quimicos">Resultados técnicos · Análisis químico</h3>
    <p>{{ $resultadosQuimicos->count() }} reportes del formato 06. Consulta la composición medida y la referencia registrada.</p>
    <div class="indicadores-grid">
        <div class="indicador"><strong>{{ $resultadosQuimicos->where('comparacion', 'coincide')->count() }}</strong><span>Especificación coincidente</span></div>
        <div class="indicador"><strong>{{ $resultadosQuimicos->where('comparacion', 'no_coincide')->count() }}</strong><span>Especificación no coincidente</span></div>
        <div class="indicador"><strong>{{ $resultadosQuimicos->where('comparacion', 'sin_datos')->count() }}</strong><span>Sin datos para comparar</span></div>
    </div>
    @foreach($resultadosQuimicos as $resultado)
        @php
            $etiquetas = ['coincide' => 'Coincide', 'no_coincide' => 'No coincide', 'sin_datos' => 'Sin datos para comparar'];
            $colorEstado = ['coincide' => 'firmados', 'no_coincide' => 'pendientes', 'sin_datos' => 'sin_reportes'];
        @endphp
        <details class="grafica-panel filtros-avanzados" style="margin-bottom:12px">
            <summary><strong>{{ $resultado['numero'] }}</strong> · {{ $resultado['contrato'] }}<br><span class="estado-documentacion estado-{{ $colorEstado[$resultado['comparacion']] }}">{{ $etiquetas[$resultado['comparacion']] }}</span></summary>
            <p><strong>Material:</strong> {{ $resultado['material'] ?: 'Sin material registrado' }}<br>
                <strong>Criterio de evaluación:</strong> {{ $resultado['criterio'] ?: 'Sin criterio registrado' }}<br>
                <strong>Norma / especificación de referencia:</strong>
                <span @if($resultado['comparacion'] === 'no_coincide') class="estado-documentacion estado-pendientes" @endif>{{ $resultado['especificacion'] ?: 'Sin especificación registrada' }}
                @if($resultado['variable']) · {{ $resultado['variable'] }} @endif</span>
            </p>
            <div class="estadisticas-tabla"><table>
                <thead><tr><th scope="col">Elemento</th><th scope="col">Promedio medido</th><th scope="col">Composición teórica</th></tr></thead>
                <tbody>
                    @forelse($resultado['filas'] as $fila)
                        @if(is_array($fila))
                            <tr><td>{{ $fila['Elemento'] ?? 'Sin elemento' }}</td><td>{{ ($fila['Promedio'] ?? '') !== '' ? $fila['Promedio'] : 'Sin dato' }}</td><td>{{ ($fila['Composicion'] ?? '') !== '' ? $fila['Composicion'] : 'Sin referencia' }}</td></tr>
                        @endif
                    @empty
                        <tr><td colspan="3">No hay una tabla de composición registrada en este reporte.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
            <p><small>Se compara el criterio con la norma y su variable, ignorando mayúsculas, espacios y el separador visual «·». La coincidencia no evalúa los límites de composición química.</small></p>
            <a class="btn-contrato" href="{{ route('Reportes.Clientes', ['token' => request()->route('token'), 'idOrden_Servicio' => $resultado['orden']]) . '#reporte-' . $resultado['id'] }}">Ver reporte</a>
        </details>
    @endforeach
</section>
