<style>
    .btn-limpiar-filtros { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; min-height:44px; box-sizing:border-box; border:1px solid #cbd5df; border-radius:7px; background:#f3f7fb; color:#214f79; text-decoration:none; font-weight:600; }
    .btn-limpiar-filtros:visited { color:#214f79; }
    .btn-limpiar-filtros:hover { background:#e4edf5; border-color:#214f79; }
    .btn-limpiar-filtros:focus-visible { outline:2px solid #214f79; outline-offset:3px; }
    .btn-limpiar-filtros svg { width:18px; height:18px; }
    .contrato-grafica { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border:1px solid #dce7ef; border-radius:7px; background:#edf3f8; color:#214f79; text-decoration:none; font-weight:600; }
    .contrato-grafica:visited { color:#214f79; }
    .contrato-grafica:hover { background:#214f79; color:white; }
    .contrato-grafica:focus-visible, .serie-control:focus-visible { outline:2px solid #214f79; outline-offset:3px; }
    .contrato-grafica svg { width:18px; height:18px; }
    .serie-control { border:1px solid #dce7ef; border-radius:20px; background:white; padding:8px 12px; cursor:pointer; font:inherit; }
    .serie-control[aria-pressed=false] { opacity:.5; text-decoration:line-through; }
    .grafica-panel [data-serie] { transition:opacity .2s; }
    .grafica-panel [data-serie][hidden] { display:none; }
    .grafica-tooltip { position:fixed; z-index:100; max-width:260px; background:#173b5c; color:white; padding:10px 12px; border-radius:8px; box-shadow:0 4px 12px #0002; font-size:13px; pointer-events:none; }
    .grafica-panel svg path, .grafica-panel svg circle { transition:opacity .15s; }
    .estadisticas-tabs { display:flex; gap:4px; border-bottom:1px solid #cbd5df; margin:20px 0; overflow-x:auto; }
    .estadisticas-tabs button { display:inline-flex; align-items:center; gap:8px; background:transparent; color:#214f79; border:1px solid transparent; padding:12px 16px; border-radius:6px 6px 0 0; cursor:pointer; white-space:nowrap; font:inherit; }
    .estadisticas-tabs svg { width:20px; height:20px; flex-shrink:0; }
    .estadisticas-tabs button[aria-selected=true] { background:#edf3f8; border-color:#cbd5df; border-bottom-color:#214f79; font-weight:600; }
    .estadisticas-tabs button:focus-visible { outline:2px solid #214f79; outline-offset:-3px; }
    .estadisticas-panel [role=tabpanel][hidden] { display:none; }
    .estadisticas-panel { max-width:1100px; margin:0 auto 32px; padding:24px; background:white; border-radius:14px; box-shadow:0 3px 16px #00000012; text-align:left; }
    .estadisticas-panel h2, .estadisticas-panel h3 { color:#214f79; }
    .estadisticas-panel p { color:#526575; line-height:1.5; }
    .estadisticas-filtro { display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin:20px 0; }
    .estadisticas-filtro select, .estadisticas-filtro input[type=date] { padding:10px; border:1px solid #cbd5df; border-radius:6px; max-width:100%; box-sizing:border-box; }
    .filtro-campo { display:flex; flex-direction:column; gap:6px; min-width:140px; max-width:100%; }
    .estadisticas-filtro { align-items:flex-end; }
    .estadisticas-error { color:#a32020; }
    .estadisticas-graficas { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:20px; margin-top:24px; }
    .filtros-avanzados { flex-basis:100%; }
    .filtros-avanzados summary { cursor:pointer; color:#214f79; padding:12px 0; }
    .pastel-contenido { display:grid; grid-template-columns:1fr; gap:12px; align-items:center; }
    @media(max-width:600px) { .pastel-contenido { grid-template-columns:1fr; } }
    .grafica-panel { border:1px solid #dce7ef; border-radius:10px; padding:18px; min-width:0; }
    .grafica-panel h3 { margin-top:0; }
    .grafica-panel p { font-size:13px; }
    .ensayos-pastel svg { display:block; width:100%; max-width:280px; height:auto; margin:16px auto; }
    .ensayos-leyenda { list-style:none; padding:0; margin:16px 0 0; }
    .ensayos-leyenda li { display:flex; align-items:flex-start; gap:8px; margin:12px 0; font-size:13px; color:#526575; overflow-wrap:anywhere; }
    .ensayos-leyenda .color-ensayo { width:12px; height:12px; border-radius:3px; flex-shrink:0; margin-top:2px; }
    .ensayos-leyenda strong { display:block; color:#214f79; margin-top:4px; }
    @media(max-width:800px) { .estadisticas-graficas { grid-template-columns:1fr; } }
    .indicadores-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; }
    .indicador { background:#f3f7fb; border:1px solid #dce7ef; border-radius:10px; padding:20px; }
    .indicador strong { display:block; font-size:32px; color:#214f79; margin-bottom:8px; }
    .indicador span { color:#526575; }
    .estado-documentacion { display:inline-block; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:600; }
    .estado-sin_reportes { background:#edf1f5; color:#526575; }
    .estado-pendientes { background:#fff2d9; color:#785314; }
    .estado-firmados { background:#e1f3ea; color:#246044; }
    .btn-pendientes { display:inline-flex; align-items:center; gap:9px; padding:11px 16px; margin-top:10px; border:1px solid #e5c68f; border-radius:8px; background:#fff2d9; color:#785314; font-size:14px; font-weight:600; text-decoration:none; transition:background .15s, border-color .15s; box-sizing:border-box; max-width:100%; }
    .btn-pendientes:visited { color:#785314; }
    .btn-pendientes:hover { background:#ffe6b5; border-color:#b7791f; }
    .btn-pendientes:focus-visible { outline:2px solid #b7791f; outline-offset:3px; }
    .btn-pendientes svg { width:22px; height:22px; flex-shrink:0; }
    .estadisticas-tabla { overflow-x:auto; max-width:100%; }
    .estadisticas-tabla table { width:100%; border-collapse:collapse; text-align:left; }
    .estadisticas-tabla th, .estadisticas-tabla td { padding:14px 10px; border-bottom:1px solid #e4eaf0; }
    .estadisticas-tabla th { background:#f3f7fb; color:#214f79; }
    @media(max-width:700px) { .indicadores-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } .estadisticas-panel { padding:16px; } }
    @media(max-width:380px) { .indicadores-grid { grid-template-columns:1fr; } }
    @media(max-width:600px) {
        .estadisticas-filtro .filtro-campo { width:100%; min-width:0; }
        .estadisticas-filtro select { width:100%; }
        .estadisticas-filtro select, .estadisticas-filtro button { font-size:16px; min-height:44px; }
        .estadisticas-tabla table { min-width:520px; }
        .indicador { padding:16px; min-width:0; overflow-wrap:anywhere; }
        .btn-pendientes { min-height:44px; }
    }
</style>
<section class="estadisticas-panel" aria-labelledby="titulo-estadisticas">
    <h2 id="titulo-estadisticas">Estadísticas de mis servicios</h2>
    <p>Tus reportes y el estado de su documentación.</p>
    @if($errors->any())
        <div class="estadisticas-error" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
    @endif
    <form method="GET" action="{{ request()->url() }}" class="estadisticas-filtro">
        <input type="hidden" name="vista" value="estadisticas">
        <input type="hidden" name="seccion" id="seccion-estadisticas" value="{{ $seccionEstadisticas }}">
        <div class="filtro-campo"><label for="filtro-contrato">Contrato</label>
        <select id="filtro-contrato" name="contrato">
            <option value="">Todos mis contratos</option>
            @foreach($contratos->keys() as $contrato)
                <option value="{{ $contrato }}" @selected($contratoSeleccionado === (string) $contrato)>{{ $contrato }}</option>
            @endforeach
        </select>
        </div>
        <div class="filtro-campo"><label for="buscar-estadisticas">Buscar en las tablas</label><input id="buscar-estadisticas" type="search" name="buscar" value="{{ $busqueda }}" maxlength="150" placeholder="Contrato, proyecto o reporte" style="padding:10px;border:1px solid #cbd5df;border-radius:6px;max-width:100%;box-sizing:border-box"></div><button type="submit" class="btn-contrato">Consultar</button>
        @if($contratoSeleccionado !== '' || $busqueda !== '')
            <a class="btn-limpiar-filtros" data-limpiar-filtros href="{{ request()->url() . '?' . http_build_query(['vista' => 'estadisticas', 'seccion' => $seccionEstadisticas]) }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 10a9 9 0 1 1 2 8M3 4v6h6" /></svg>
                <span>Limpiar filtros</span>
            </a>
        @endif
    </form>
    <div class="estadisticas-tabs" role="tablist" aria-label="Secciones de estadísticas">
        <button type="button" role="tab" id="tab-resumen" aria-controls="panel-resumen" aria-selected="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg><span>Resumen</span></button>
        <button type="button" role="tab" id="tab-graficas" aria-controls="panel-graficas" aria-selected="false" tabindex="-1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M3 3v18h18M7 16v-5m5 5V7m5 9V4"/></svg><span>Gráficas</span></button>
        <button type="button" role="tab" id="tab-pendientes" aria-controls="panel-pendientes" aria-selected="false" tabindex="-1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8l5 5v3M13 3v5h5M7 12h4"/><circle cx="17" cy="17" r="5"/><path d="M17 14v3l2 1"/></svg><span>Reportes pendientes</span></button>
    </div>
    <div id="panel-resumen" role="tabpanel" aria-labelledby="tab-resumen" tabindex="0">    <div class="indicadores-grid">
        <div class="indicador"><strong>{{ $estadisticas['reportes'] }}</strong><span>Total de reportes</span></div>
        <div class="indicador"><strong>{{ $estadisticas['firmados'] }}</strong><span>Reportes liberados</span></div>
        <div class="indicador"><strong>{{ $estadisticas['reportes'] - $estadisticas['firmados'] }}</strong><span>Pendientes de liberación</span></div>
    </div>
    <h3 id="tabla-ordenes">Mis reportes por orden</h3>
    <div class="estadisticas-tabla">
        <table>
            <thead><tr><th scope="col">Contrato / Proyecto</th><th scope="col">Reportes</th><th scope="col">Documentación</th><th scope="col">Consulta</th></tr></thead>
            <tbody>
                @forelse($ordenesPaginadas as $orden)
                    @php $reportesOrden = $reportesEstadistica->where('idOrden_Servicio', $orden->idOrden_Servicio)->unique('idReportes'); @endphp
                    <tr>
                        <td><strong>{{ $orden->Contrato }}</strong><br>{{ $orden->Proyecto_actividad ?: 'Sin proyecto registrado' }}</td>
                        <td>{{ $reportesOrden->count() }}</td>
                        <td><span class="estado-documentacion estado-{{ $orden->estado_documentacion }}">{{ $estadosDocumentacion[$orden->estado_documentacion] }}</span><br><small>{{ $orden->reportes_firmados }} de {{ $orden->total_reportes }} reportes liberados en la orden</small></td>
                        <td><a class="btn-contrato" href="{{ route('Reportes.Clientes', ['token' => request()->route('token'), 'idOrden_Servicio' => $orden->idOrden_Servicio] + $contextoEstadisticas) }}">Ver reportes</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4">No hay órdenes para esta selección.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('Reportes_publicos.partials.paginacion', ['paginador' => $ordenesPaginadas, 'etiqueta' => 'Páginas de órdenes'])
    <p><small>La liberación se registra cuando está disponible el PDF firmado del reporte.</small></p>
</div>
    <div id="panel-graficas" role="tabpanel" aria-labelledby="tab-graficas" tabindex="0">    <div class="estadisticas-graficas">
    <section class="grafica-panel" aria-labelledby="titulo-ensayos">
    <h3 id="titulo-ensayos">Cantidad de reportes por ensayo</h3>
    @if($estadisticas['ensayos']->isNotEmpty())
        @php
            $totalEnsayos = $estadisticas['ensayos']->sum();
            $angulo = -M_PI / 2;
            $coloresEnsayos = ['#214f79', '#a82e45', '#287b78', '#74549a', '#98641c', '#4b6f39', '#9b486f', '#485fc2'];
        @endphp
        <div class="pastel-contenido">
        <div class="ensayos-pastel">
            <svg viewBox="0 0 300 300" role="img" aria-labelledby="ensayos-grafica-titulo ensayos-grafica-descripcion">
                <title id="ensayos-grafica-titulo">Reportes por tipo de ensayo</title>
                <desc id="ensayos-grafica-descripcion">@foreach($estadisticas['ensayos'] as $ensayo => $cantidad){{ $ensayo }}: {{ $cantidad }} {{ $cantidad === 1 ? 'reporte' : 'reportes' }}. @endforeach</desc>
                @foreach($estadisticas['ensayos'] as $ensayo => $cantidad)
                    @php
                        $porcion = $cantidad / $totalEnsayos;
                        $fin = $angulo + $porcion * 2 * M_PI;
                        $color = $coloresEnsayos[$loop->index % count($coloresEnsayos)];
                        $x1 = 150 + 140 * cos($angulo); $y1 = 150 + 140 * sin($angulo);
                        $x2 = 150 + 140 * cos($fin); $y2 = 150 + 140 * sin($fin);
                        $medio = ($angulo + $fin) / 2;
                        $angulo = $fin;
                    @endphp
                    @if($porcion === 1.0 || $cantidad === $totalEnsayos)
                        <circle cx="150" cy="150" r="140" fill="{{ $color }}"><title>{{ $ensayo }}: {{ $cantidad }} reportes (100%)</title></circle>
                    @else
                        <path d="M 150 150 L {{ $x1 }} {{ $y1 }} A 140 140 0 {{ $porcion > 0.5 ? 1 : 0 }} 1 {{ $x2 }} {{ $y2 }} Z" fill="{{ $color }}" stroke="white" stroke-width="2"><title>{{ $ensayo }}: {{ $cantidad }} reportes ({{ round($porcion * 100, 1) }}%)</title></path>
                    @endif
                    @if($porcion >= 0.06)
                        <text x="{{ $porcion == 1 ? 150 : 150 + 92 * cos($medio) }}" y="{{ $porcion == 1 ? 150 : 150 + 92 * sin($medio) }}" text-anchor="middle" dominant-baseline="middle" fill="white" font-size="14" font-weight="600">{{ round($porcion * 100, 1) }}%</text>
                    @endif
                @endforeach
            </svg>
        </div>
        <ul class="ensayos-leyenda">
            @foreach($estadisticas['ensayos'] as $ensayo => $cantidad)
                <li><span class="color-ensayo" style="background:{{ $coloresEnsayos[$loop->index % count($coloresEnsayos)] }}" aria-hidden="true"></span><div>{{ $ensayo }}<strong>{{ $cantidad }} {{ $cantidad === 1 ? 'reporte' : 'reportes' }} · {{ round($cantidad / $totalEnsayos * 100, 1) }}%</strong></div></li>
            @endforeach
        </ul>
        </div>
    @else
        <p>No hay reportes registrados para esta selección.</p>
    @endif
    </section>
    <section class="grafica-panel" aria-labelledby="titulo-firmas">
        <h3 id="titulo-firmas">Liberación de reportes</h3>
        <p>{{ $contratoSeleccionado !== '' ? 'Contrato: ' . $contratoSeleccionado : 'Todos tus contratos' }}</p>
        @php
            $totalFirmas = $estadisticas['reportes'];
            $pendientesFirma = $totalFirmas - $estadisticas['firmados'];
            $porcentajeFirmado = $totalFirmas ? $estadisticas['firmados'] / $totalFirmas * 100 : 0;
            $longitudFirmada = $porcentajeFirmado / 100 * 2 * M_PI * 105;
        @endphp
        @if($totalFirmas)
            <div class="ensayos-pastel">
                <svg viewBox="0 0 300 300" role="img" aria-labelledby="firmas-grafica-titulo firmas-grafica-descripcion">
                    <title id="firmas-grafica-titulo">Reportes liberados y pendientes de liberación</title>
                    <desc id="firmas-grafica-descripcion">{{ $estadisticas['firmados'] }} liberados y {{ $pendientesFirma }} pendientes de liberación, de {{ $totalFirmas }} reportes.</desc>
                    <circle cx="150" cy="150" r="105" fill="none" stroke="#b7791f" stroke-width="45" />
                    @if($estadisticas['firmados'])
                        <circle cx="150" cy="150" r="105" fill="none" stroke="#287b78" stroke-width="45" stroke-dasharray="{{ $longitudFirmada }} {{ 2 * M_PI * 105 }}" transform="rotate(-90 150 150)" />
                    @endif
                    <text x="150" y="145" text-anchor="middle" fill="#214f79" font-size="30" font-weight="600">{{ round($porcentajeFirmado) }}%</text>
                    <text x="150" y="170" text-anchor="middle" fill="#526575" font-size="14">liberados</text>
                </svg>
            </div>
            <ul class="ensayos-leyenda">
                <li><span class="color-ensayo" style="background:#287b78" aria-hidden="true"></span><div>Liberados<strong>{{ $estadisticas['firmados'] }} {{ $estadisticas['firmados'] === 1 ? 'reporte' : 'reportes' }}</strong></div></li>
                <li><span class="color-ensayo" style="background:#b7791f" aria-hidden="true"></span><div>Pendientes de liberación<strong>{{ $pendientesFirma }} {{ $pendientesFirma === 1 ? 'reporte' : 'reportes' }}</strong></div></li>
            </ul>
            @if($pendientesFirma)
                <a class="btn-pendientes" href="#detalle-pendientes" aria-controls="detalle-pendientes" data-ver-pendientes>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M10 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8l5 5v3M13 3v5h5M7 8h2M7 12h4M7 16h2" />
                        <circle cx="17" cy="17" r="5" /><path d="M17 14v3l2 1" />
                    </svg>
                    <span>Ver reportes pendientes de liberación</span>
                </a>
                <p><strong>{{ $pendientesFirma === 1 ? 'Queda 1 reporte por liberar' : 'Quedan ' . $pendientesFirma . ' reportes por liberar' }}{{ $contratoSeleccionado !== '' ? ' en este contrato.' : ' entre tus contratos.' }}</strong></p>
            @else
                <p><strong>Todos los reportes{{ $contratoSeleccionado !== '' ? ' de este contrato' : '' }} están liberados.</strong></p>
            @endif
        @else
            <p>{{ $contratoSeleccionado !== '' ? 'Este contrato no tiene reportes registrados.' : 'No tienes reportes registrados en tus contratos.' }}</p>
        @endif
    </section>
    </div>
    @if($contratoSeleccionado === '' && $liberacionPorContrato->isNotEmpty())
    <section class="grafica-panel" aria-labelledby="titulo-contratos" style="margin-top:20px">
        <h3 id="titulo-contratos">Liberación de reportes por contrato</h3>
        <p>Compara reportes liberados y pendientes. Los contratos con más pendientes aparecen primero.</p>
        <p><button type="button" class="serie-control" data-toggle-serie="liberados" aria-pressed="true" style="color:#287b78">● Liberados</button> <button type="button" class="serie-control" data-toggle-serie="pendientes" aria-pressed="true" style="color:#b7791f">● Pendientes de liberación</button></p>
        @php $maximoContrato = max(1, $liberacionPorContrato->max('total')); @endphp
        @foreach($liberacionPorContrato as $contrato => $conteo)
            <div style="margin:18px 0">
                <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:8px">
                    <a class="contrato-grafica" href="{{ request()->url() . '?' . http_build_query(['vista' => 'estadisticas', 'contrato' => $contrato, 'seccion' => 'graficas']) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 7V4h7l2 3h9v13H3Z"/></svg>{{ $contrato }}<span aria-hidden="true">→</span></a>
                    <span>{{ $conteo['liberados'] }} liberados / {{ $conteo['pendientes'] }} pendientes</span>
                </div>
                <div style="display:flex;height:24px;background:#edf3f8;border-radius:5px;overflow:hidden">
                    <span data-serie="liberados" data-tooltip="{{ $contrato }}: {{ $conteo['liberados'] }} liberados" tabindex="0" style="width:{{ $conteo['liberados'] / $maximoContrato * 100 }}%;background:#287b78"></span>
                    <span data-serie="pendientes" data-tooltip="{{ $contrato }}: {{ $conteo['pendientes'] }} pendientes de liberación" tabindex="0" style="width:{{ $conteo['pendientes'] / $maximoContrato * 100 }}%;background:#b7791f"></span>
                </div>
                @if(!$conteo['total'])<small>Sin reportes registrados</small>@endif
            </div>
        @endforeach
        <p><small>Escala común: de 0 a {{ $maximoContrato }} reportes. Selecciona un contrato para consultar su detalle.</small></p>
    </section>
    @endif
    <details class="filtros-avanzados">
    <summary>Ver actividad mensual</summary>
    <section class="grafica-panel" aria-labelledby="titulo-mensual">
        <h3 id="titulo-mensual">Actividad mensual</h3>
        <p>Cantidad de reportes según su fecha. Los meses sin actividad dentro del periodo aparecen en cero.</p>
        @if($estadisticas['mensuales']->isNotEmpty())
            @php
                $meses = $estadisticas['mensuales'];
                $cantidadMeses = $meses->count();
                $maxMensual = max(1, $meses->max());
                $puntosMensuales = [];
                foreach ($meses->values() as $indice => $cantidad) {
                    $x = $cantidadMeses === 1 ? 440 : 60 + $indice / ($cantidadMeses - 1) * 760;
                    $y = 240 - $cantidad / $maxMensual * 180;
                    $puntosMensuales[] = [$x, $y];
                }
                $trazoMensual = implode(' ', array_map(fn ($punto) => implode(',', $punto), $puntosMensuales));
            @endphp
            <svg viewBox="0 0 880 310" style="display:block;width:100%;height:auto;min-height:220px" role="img" aria-labelledby="mensual-linea-titulo mensual-linea-descripcion">
                <title id="mensual-linea-titulo">Evolución mensual de reportes</title>
                <desc id="mensual-linea-descripcion">@foreach($meses as $mes => $cantidad){{ $mes }}: {{ $cantidad }} reportes. @endforeach</desc>
                @for($i = 0; $i <= 4; $i++)
                    @php $nivel = $i * $maxMensual / 4; $yNivel = 240 - $i * 45; @endphp
                    <line x1="60" y1="{{ $yNivel }}" x2="820" y2="{{ $yNivel }}" stroke="#e4eaf0" />
                    <text x="48" y="{{ $yNivel + 4 }}" text-anchor="end" fill="#526575" font-size="12">{{ round($nivel, 1) }}</text>
                @endfor
                @if($cantidadMeses > 1)
                    <polygon points="{{ $puntosMensuales[0][0] }},240 {{ $trazoMensual }} {{ $puntosMensuales[$cantidadMeses - 1][0] }},240" fill="#214f79" opacity="0.08" />
                    <polyline points="{{ $trazoMensual }}" fill="none" stroke="#214f79" stroke-width="3" stroke-linejoin="round" />
                @endif
                @foreach($meses as $mes => $cantidad)
                    @php [$xMes, $yMes] = $puntosMensuales[$loop->index]; $nomMes = \Carbon\CarbonImmutable::parse($mes . '-01')->locale('es')->translatedFormat('M Y'); @endphp
                    <circle cx="{{ $xMes }}" cy="{{ $yMes }}" r="5" fill="#214f79" stroke="white" stroke-width="2"><title>{{ $nomMes }}: {{ $cantidad }} reportes</title></circle>
                    @if($cantidad > 0)
                        <text x="{{ $xMes }}" y="{{ $yMes - 12 }}" text-anchor="middle" fill="#214f79" font-size="13" font-weight="600">{{ $cantidad }}</text>
                    @endif
                    @if($loop->first || $loop->last || $loop->index % max(1, (int) ceil($cantidadMeses / 6)) === 0)
                        <text x="{{ $xMes }}" y="270" text-anchor="middle" fill="#526575" font-size="12">{{ $nomMes }}</text>
                    @endif
                @endforeach
            </svg>
            <p><small>Señala un punto para consultar el mes y su cantidad de reportes.</small></p>
        @else
            <p>No hay reportes con fecha válida para graficar.</p>
        @endif
        @if($estadisticas['sin_fecha'])<p>{{ $estadisticas['sin_fecha'] }} reportes sin fecha válida no aparecen en esta gráfica.</p>@endif
    </section>
    </details>
</div>
    <div id="panel-pendientes" role="tabpanel" aria-labelledby="tab-pendientes" tabindex="0">    @if($estadisticas['reportes'] > $estadisticas['firmados'])
        <div id="detalle-pendientes">
            <h3>Reportes pendientes de liberación ({{ $estadisticas['reportes'] - $estadisticas['firmados'] }})</h3>
            <div class="estadisticas-tabla">
                <table>
                    <thead><tr><th scope="col">Reporte</th><th scope="col">Contrato</th><th scope="col">Tipo de ensayo</th><th scope="col">Consulta</th></tr></thead>
                    <tbody>
                        @forelse($pendientesPaginados as $pendiente)
                            @php $ordenPendiente = $ordenesEstadistica->firstWhere('idOrden_Servicio', $pendiente->idOrden_Servicio); @endphp
                            <tr>
                                <td>{{ $pendiente->numero ?: 'Reporte #' . $pendiente->idReportes }}</td>
                                <td>{{ $ordenPendiente->Contrato }}</td>
                                <td>{{ $pendiente->ensayo }}</td>
                                <td><a class="btn-contrato" href="{{ route('Reportes.Clientes', ['token' => request()->route('token'), 'idOrden_Servicio' => $pendiente->idOrden_Servicio] + array_merge($contextoEstadisticas, ['seccion' => 'pendientes'])) . '#reporte-' . $pendiente->idReportes }}">Ver reporte</a></td>
                            </tr>
                        @empty<tr><td colspan="4">No hay pendientes que coincidan con la búsqueda.</td></tr>@endforelse
                    </tbody>
                </table>
            </div>
            @include('Reportes_publicos.partials.paginacion', ['paginador' => $pendientesPaginados, 'etiqueta' => 'Páginas de reportes pendientes'])
        </div>
    @else<p>No hay reportes pendientes de liberación para este contrato.</p>
    @endif
</div>
</section>
<script>
(() => {
    const tooltip = document.createElement('div');
    tooltip.className = 'grafica-tooltip';
    tooltip.hidden = true;
    document.body.appendChild(tooltip);
    document.querySelectorAll('.grafica-panel svg path, .grafica-panel svg circle').forEach(segmento => {
        const titulo = segmento.querySelector('title');
        if (titulo) { segmento.dataset.tooltip = titulo.textContent; segmento.setAttribute('tabindex', '0'); }
    });
    document.querySelectorAll('[data-tooltip]').forEach(elemento => {
        elemento.setAttribute('aria-label', elemento.dataset.tooltip);
        function mostrar(evento) {
            tooltip.textContent = elemento.dataset.tooltip;
            tooltip.hidden = false;
            const rect = elemento.getBoundingClientRect();
            const x = evento.clientX || rect.left;
            const y = evento.clientY || rect.top;
            tooltip.style.left = Math.max(8, Math.min(x + 12, window.innerWidth - tooltip.offsetWidth - 8)) + 'px';
            tooltip.style.top = Math.max(8, Math.min(y + 12, window.innerHeight - tooltip.offsetHeight - 8)) + 'px';
        }
        elemento.addEventListener('pointermove', mostrar);
        elemento.addEventListener('focus', mostrar);
        elemento.addEventListener('pointerleave', () => tooltip.hidden = true);
        elemento.addEventListener('blur', () => tooltip.hidden = true);
        elemento.addEventListener('keydown', evento => { if (evento.key === 'Escape') tooltip.hidden = true; });
    });
    document.querySelectorAll('[data-toggle-serie]').forEach(boton => {
        boton.addEventListener('click', () => {
            const mostrar = boton.getAttribute('aria-pressed') !== 'true';
            boton.setAttribute('aria-pressed', String(mostrar));
            document.querySelectorAll('[data-serie="' + boton.dataset.toggleSerie + '"]').forEach(segmento => segmento.hidden = !mostrar);
            tooltip.hidden = true;
        });
    });
    const tabs = [...document.querySelectorAll('.estadisticas-tabs [role="tab"]')];
    function activar(tab, foco = false) {
        document.getElementById('seccion-estadisticas').value = tab.id.replace('tab-', '');
        const limpiar = document.querySelector('[data-limpiar-filtros]');
        if (limpiar) {
            const url = new URL(limpiar.href);
            url.searchParams.set('seccion', tab.id.replace('tab-', ''));
            limpiar.href = url.toString();
        }
        tabs.forEach(item => {
            const activo = item === tab;
            item.setAttribute('aria-selected', String(activo));
            item.tabIndex = activo ? 0 : -1;
            document.getElementById(item.getAttribute('aria-controls')).hidden = !activo;
        });
        if (foco) tab.focus();
    }
    tabs.forEach((tab, indice) => {
        tab.addEventListener('click', () => activar(tab));
        tab.addEventListener('keydown', evento => {
            let destino;
            if (evento.key === 'ArrowRight') destino = (indice + 1) % tabs.length;
            if (evento.key === 'ArrowLeft') destino = (indice + tabs.length - 1) % tabs.length;
            if (evento.key === 'Home') destino = 0;
            if (evento.key === 'End') destino = tabs.length - 1;
            if (destino !== undefined) { evento.preventDefault(); activar(tabs[destino], true); }
        });
    });
    document.querySelectorAll('[data-ver-pendientes]').forEach(enlace => {
        enlace.addEventListener('click', () => activar(document.getElementById('tab-pendientes')));
    });
    const parametros = new URLSearchParams(location.search);
    const seccion = location.hash === '#detalle-pendientes' ? 'pendientes'
        : location.hash === '#tabla-ordenes' ? 'resumen' : parametros.get('seccion');
    activar(document.getElementById('tab-' + seccion) || document.getElementById('tab-resumen'));
})();
</script>
