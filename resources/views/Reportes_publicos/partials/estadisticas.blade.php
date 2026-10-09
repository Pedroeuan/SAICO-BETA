<style>
    .estadisticas-panel { 
        border:1px solid #e2eaf2; 
    }
    .estadisticas-encabezado { 
        display:flex; 
        align-items:center; 
        gap:16px; padding:20px; 
        background:linear-gradient(120deg,#edf4fb,#f8fafc); 
        border-radius:12px; 
        border-left:4px solid #214f79; 
        margin-bottom:20px; 
    }
    .estadisticas-encabezado-icono { 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        width:52px; 
        height:52px; 
        flex-shrink:0; 
        border-radius:12px; 
        color:white; 
        background:#214f79; 
    }
    .estadisticas-encabezado-icono svg { 
        width:28px; 
        height:28px; 
    }
    .estadisticas-encabezado h2 { 
        margin:0 0 6px; 
        font-size:24px; 
    }
    .estadisticas-encabezado p { 
        margin:0; 
        font-size:14px; 
    }
    .estadisticas-panel .estadisticas-filtro { 
        background:#f8fafc; 
        border:1px solid #e2eaf2; 
        border-radius:10px; 
        padding:16px; 
    }
    .estadisticas-panel .indicador { 
        position:relative; 
        background:white; 
        border-radius:12px; 
        border-top:4px solid #214f79; 
        box-shadow:0 4px 14px #214f790a; 
        padding:24px; 
    }
    .estadisticas-panel .indicador:nth-child(2) { 
        border-top-color:#287b78; 
        background:#f4fbf9; 
    }
    .estadisticas-panel .indicador:nth-child(2) strong { 
        color:#287b78; 
    }
    .estadisticas-panel .indicador:nth-child(3) { 
        border-top-color:#b7791f; 
        background:#fffbf2; 
    }
    .estadisticas-panel .indicador:nth-child(3) strong { 
        color:#996216; 
    }
    .estadisticas-panel .indicador strong { 
        font-size:38px; 
    }
    .estadisticas-panel .grafica-panel { 
        box-shadow:0 4px 16px #214f7908; 
    }
    .estadisticas-panel .grafica-panel h3 { 
        padding-bottom:12px; 
        border-bottom:1px solid #edf1f5; 
        font-size:18px; 
    }
    .estadisticas-panel .estadisticas-tabs { 
        gap:8px; 
        padding:6px; 
        background:#f1f5f9; 
        border:0; 
        border-radius:10px; 
    }
    .estadisticas-panel .estadisticas-tabs button { 
        border:0; 
        border-radius:7px; 
        min-height:44px; 
    }
    .estadisticas-panel .estadisticas-tabs button[aria-selected=true] { 
        background:white; 
        color:#214f79; 
        box-shadow:0 2px 8px #214f7915; 
    }
    .estadisticas-panel .estadisticas-tabla tbody tr:hover { 
        background:#f7fafc; 
    }
    @media(max-width:600px) { 
        .estadisticas-encabezado 
        { 
            padding:16px; gap:12px; 
        } 
        .estadisticas-encabezado h2 { 
            font-size:20px; 
        } 
        .estadisticas-panel .indicador 
        { 
            padding:18px; 
        } 
    }
    .btn-limpiar-filtros { 
        display:inline-flex; 
        align-items:center; 
        gap:8px; 
        padding:10px 14px; 
        min-height:44px; 
        box-sizing:border-box; 
        border:1px solid #cbd5df; 
        border-radius:7px; 
        background:#f3f7fb; 
        color:#214f79; 
        text-decoration:none; 
        font-weight:600; 
    }
    .btn-limpiar-filtros:visited { 
        color:#214f79; 
    }
    .btn-limpiar-filtros:hover { 
        background:#e4edf5; 
        border-color:#214f79; 
    }
    .btn-limpiar-filtros:focus-visible { 
        outline:2px solid #214f79; 
        outline-offset:3px; 
    }
    .btn-limpiar-filtros svg { 
        width:18px; 
        height:18px; 
    }
    .contrato-grafica { 
        display:inline-flex; 
        align-items:center; 
        gap:8px; 
        padding:8px 12px; 
        border:1px solid #dce7ef; 
        border-radius:7px; 
        background:#edf3f8; 
        color:#214f79; 
        text-decoration:none; 
        font-weight:600; 
    }
    .contrato-grafica:visited { 
        color:#214f79; 
    }
    .contrato-grafica:hover { 
        background:#214f79; 
        color:white; 
    }
    .contrato-grafica:focus-visible, 
    .serie-control:focus-visible { 
        outline:2px solid #214f79; 
        outline-offset:3px; 
    }
    .contrato-grafica svg { 
        width:18px; height:18px; 
    }
    .serie-control { 
        border:1px solid #dce7ef; 
        border-radius:20px; 
        background:white; 
        padding:8px 12px; 
        cursor:pointer; 
        font:inherit; 
    }
    .serie-control[aria-pressed=false] { 
        opacity:.5; 
        text-decoration:line-through; 
    }
    .grafica-panel [data-serie] { 
        transition:opacity .2s; 
    }
    .grafica-panel [data-serie][hidden] { 
        display:none; 
    }
    .grafica-tooltip { 
        position:fixed; 
        z-index:100; 
        max-width:260px; 
        background:#173b5c; 
        color:white; 
        padding:10px 12px; 
        border-radius:8px; 
        box-shadow:0 4px 12px #0002; 
        font-size:13px; 
        pointer-events:none; 
    }
    .grafica-panel svg path, 
    .grafica-panel svg circle { 
        transition:opacity .15s; 
    }
    .estadisticas-tabs { 
        display:flex; 
        gap:4px; 
        border-bottom:1px solid #cbd5df; 
        margin:20px 0; 
        overflow-x:auto; 
    }
    .estadisticas-tabs button { 
        display:inline-flex; 
        align-items:center; 
        gap:8px; 
        background:transparent; 
        color:#214f79; 
        border:1px solid transparent; 
        padding:12px 16px; 
        border-radius:6px 6px 0 0; 
        cursor:pointer; 
        white-space:nowrap; 
        font:inherit; 
    }
    .estadisticas-tabs svg { 
        width:20px; 
        height:20px; 
        flex-shrink:0; 
    }
    .estadisticas-tabs button[aria-selected=true] { 
        background:#edf3f8; 
        border-color:#cbd5df; 
        border-bottom-color:#214f79; 
        font-weight:600; 
    }
    .estadisticas-tabs button:focus-visible { 
        outline:2px solid #214f79; 
        outline-offset:-3px; 
    }
    .estadisticas-panel [role=tabpanel][hidden] { 
        display:none; 
    }
    .estadisticas-panel { 
        max-width:1100px; 
        margin:0 auto 32px; 
        padding:24px; 
        background:white; 
        border-radius:14px; 
        box-shadow:0 3px 16px #00000012; 
        text-align:left; 
    }
    .estadisticas-panel h2, 
    .estadisticas-panel h3 { 
        color:#214f79; 
    }
    .estadisticas-panel p { 
        color:#526575; 
        line-height:1.5; 
    }
    .estadisticas-filtro { 
        display:flex; 
        gap:12px; 
        align-items:center; 
        flex-wrap:wrap; 
        margin:20px 0; 
    }
    .estadisticas-filtro select, 
    .estadisticas-filtro input[type=date] { 
        padding:10px; 
        border:1px solid #cbd5df; 
        border-radius:6px; 
        max-width:100%; 
        box-sizing:border-box; 
    }
    .filtro-campo { 
        display:flex; 
        flex-direction:column; 
        gap:6px; min-width:140px; 
        max-width:100%; 
    }
    .estadisticas-filtro { 
        align-items:flex-end; 
    }
    .estadisticas-error { 
        color:#a32020; 
    }
    .estadisticas-graficas { 
        display:grid; 
        grid-template-columns:repeat(2,minmax(0,1fr)); 
        gap:20px; 
        margin-top:24px; 
    }
    .filtros-avanzados { 
        flex-basis:100%; 
    }
    .filtros-avanzados summary { 
        cursor:pointer; 
        color:#214f79; 
        padding:12px 0; 
    }
    .pastel-contenido { 
        display:grid; 
        grid-template-columns:1fr; 
        gap:12px; 
        align-items:center; 
    }
    @media(max-width:600px) { .pastel-contenido { grid-template-columns:1fr; } }
    .grafica-panel {
        border:1px solid #dce7ef;
        border-radius:12px;
        padding:20px;
        min-width:0;
        background:white;
        transition:box-shadow .2s;
    }
    .grafica-panel:hover {
        box-shadow:0 4px 16px rgba(33,79,121,.08);
    }
    .grafica-panel h3 {
        margin-top:0;
        display:flex;
        align-items:center;
        gap:8px;
    }
    .grafica-panel h3::before {
        content:'';
        width:4px;
        height:20px;
        border-radius:2px;
        background:linear-gradient(180deg,#214f79,#3a7ab5);
        flex-shrink:0;
    }
    .grafica-panel p {
        font-size:13px;
    }
    .ensayos-pastel svg { 
        display:block; 
        width:100%; 
        max-width:280px; 
        height:auto; 
        margin:16px auto; 
    }
    .ensayos-leyenda { 
        list-style:none; 
        padding:0; 
        margin:16px 0 0; 
    }
    .ensayos-leyenda li { 
        display:flex; 
        align-items:flex-start; 
        gap:8px; 
        margin:12px 0; 
        font-size:13px; 
        color:#526575; 
        overflow-wrap:anywhere; 
    }
    .ensayos-leyenda .color-ensayo { 
        width:12px; 
        height:12px; 
        border-radius:3px; 
        flex-shrink:0; 
        margin-top:2px; 
    }
    .ensayos-leyenda strong { 
        display:block; 
        color:#214f79; 
        margin-top:4px; 
    }
    @media(max-width:800px) { .estadisticas-graficas { grid-template-columns:1fr; } }
    .indicadores-grid { 
        display:grid; 
        grid-template-columns:repeat(3,minmax(0,1fr)); 
        gap:14px; 
    }
    .indicador { 
        background:#f3f7fb; 
        border:1px solid #dce7ef; 
        border-radius:10px; 
        padding:20px; 
    }
    .indicador strong { 
        display:block; 
        font-size:32px; 
        color:#214f79; 
        margin-bottom:8px; 
    }
    .indicador span { 
        color:#526575; 
    }
    .estado-documentacion {
        display:inline-block;
        padding:5px 10px;
        border-radius:6px;
        font-size:12px;
        font-weight:600;
        letter-spacing:.2px;
    }
    .estado-sin_reportes {
        background:#edf1f5;
        color:#526575;
    }
    .estado-pendientes {
        background:#fff2d9;
        color:#785314;
        border:1px solid #f0d9a8;
    }
    .estado-firmados {
        background:#e1f3ea;
        color:#246044;
        border:1px solid #b8dfc8;
    }
    .btn-pendientes { 
        display:inline-flex; 
        align-items:center; gap:9px; 
        padding:11px 16px; 
        margin-top:10px; 
        border:1px solid #e5c68f; 
        border-radius:8px; 
        background:#fff2d9; 
        color:#785314; 
        font-size:14px; 
        font-weight:600; 
        text-decoration:none; 
        transition:background .15s, border-color .15s; 
        box-sizing:border-box; 
        max-width:100%; 
    }
    .btn-pendientes:visited { 
        color:#785314; 
    }
    .btn-pendientes:hover { 
        background:#ffe6b5; 
        border-color:#b7791f; 
    }
    .btn-pendientes:focus-visible { 
        outline:2px solid #b7791f; 
        outline-offset:3px; 
    }
    .btn-pendientes svg { 
        width:22px; 
        height:22px; 
        flex-shrink:0; 
    }
    .estadisticas-tabla { 
        overflow-x:auto; 
        max-width:100%; 
    }
    .estadisticas-tabla table {
        width:100%;
        border-collapse:collapse;
        text-align:left;
    }
    .estadisticas-tabla thead th {
        background:linear-gradient(135deg,#f0f5fa,#e8f0f8);
        color:#214f79;
        font-weight:700;
        font-size:13px;
        text-transform:uppercase;
        letter-spacing:.4px;
        padding:14px 12px;
        border-bottom:2px solid #dce7ef;
    }
    .estadisticas-tabla th,
    .estadisticas-tabla td {
        padding:14px 12px;
        border-bottom:1px solid #e4eaf0;
    }
    .estadisticas-tabla tbody tr {
        transition:background .15s;
    }
    .estadisticas-tabla tbody tr:hover {
        background:#f7fafc;
    }
    .estadisticas-tabla tbody tr:last-child td {
        border-bottom:none;
    }
    @media(max-width:700px) { 
        .indicadores-grid 
        { 
        grid-template-columns:repeat(2,minmax(0,1fr)); 
        } 
        .estadisticas-panel { 
            padding:16px; 
        } 
    }
    @media(max-width:380px) { 
        .indicadores-grid 
        { 
            grid-template-columns:1fr; 
        } 
    }
    @media(max-width:600px) {
        .estadisticas-filtro
        .filtro-campo {
            width:100%;
            min-width:0;
        }
        .estadisticas-filtro select {
            width:100%;
        }
        .estadisticas-filtro select,
        .estadisticas-filtro button {
            font-size:16px;
            min-height:44px;
        }
        .estadisticas-tabla table {
            min-width:520px;
        }
        .indicador {
            padding:16px;
            min-width:0;
            overflow-wrap:anywhere;
        }
        .btn-pendientes {
            min-height:44px;
        }
    }

    /* ===== RESUMEN INTELIGENTE ===== */
    .resumen-inteligente {
        background:linear-gradient(135deg,#1a3f66 0%,#214f79 40%,#2a6a8f 100%);
        border-radius:14px;
        padding:24px 28px;
        color:white;
        margin-bottom:20px;
        position:relative;
        overflow:hidden;
        box-shadow:0 6px 24px rgba(33,79,121,.25);
    }
    .resumen-inteligente::before {
        content:'';
        position:absolute;
        top:-40px;
        right:-40px;
        width:180px;
        height:180px;
        border-radius:50%;
        background:rgba(255,255,255,.06);
    }
    .resumen-inteligente::after {
        content:'';
        position:absolute;
        bottom:-60px;
        right:60px;
        width:120px;
        height:120px;
        border-radius:50%;
        background:rgba(255,255,255,.04);
    }
    .resumen-inteligente-header {
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:14px;
        position:relative;
        z-index:1;
    }
    .resumen-inteligente-header svg {
        width:22px;
        height:22px;
        flex-shrink:0;
    }
    .resumen-inteligente-header h3 {
        margin:0;
        font-size:16px;
        font-weight:700;
        color:white;
        letter-spacing:.3px;
    }
    .resumen-inteligente p {
        margin:0 0 10px;
        color:rgba(255,255,255,.92);
        font-size:15px;
        line-height:1.65;
        position:relative;
        z-index:1;
    }
    .resumen-inteligente p:last-child {
        margin-bottom:0;
    }
    .resumen-inteligente strong {
        color:white;
        font-weight:700;
    }
    .resumen-inteligente .insight-chips {
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-top:14px;
        position:relative;
        z-index:1;
    }
    .insight-chip {
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:6px 12px;
        border-radius:20px;
        background:rgba(255,255,255,.15);
        border:1px solid rgba(255,255,255,.2);
        font-size:13px;
        color:white;
        font-weight:500;
    }
    .insight-chip svg {
        width:14px;
        height:14px;
        flex-shrink:0;
    }

    /* ===== KPIs MEJORADOS ===== */
    .kpi-icono {
        display:flex;
        align-items:center;
        justify-content:center;
        width:44px;
        height:44px;
        border-radius:10px;
        margin-bottom:12px;
    }
    .kpi-icono svg {
        width:22px;
        height:22px;
    }
    .kpi-icono-azul { background:#e8f0f8; color:#214f79; }
    .kpi-icono-verde { background:#e1f3ea; color:#246044; }
    .kpi-icono-ambar { background:#fff2d9; color:#996216; }
    .kpi-progreso {
        margin-top:14px;
        height:6px;
        border-radius:3px;
        background:#e4eaf0;
        overflow:hidden;
    }
    .kpi-progreso-barra {
        height:100%;
        border-radius:3px;
        transition:width .6s ease;
    }
    .kpi-progreso-verde .kpi-progreso-barra { background:linear-gradient(90deg,#287b78,#3a9e8f); }
    .kpi-progreso-ambar .kpi-progreso-barra { background:linear-gradient(90deg,#b7791f,#d4a03a); }
    .kpi-progreso-azul .kpi-progreso-barra { background:linear-gradient(90deg,#214f79,#3a7ab5); }
    .kpi-subtexto {
        display:block;
        margin-top:8px;
        font-size:12px;
        color:#7a8a99;
    }
    .indicador .kpi-valor-fila {
        display:flex;
        align-items:baseline;
        gap:8px;
    }
    .indicador .kpi-porcentaje {
        font-size:16px;
        font-weight:700;
    }
    .kpi-porcentaje-verde { color:#287b78; }
    .kpi-porcentaje-ambar { color:#b7791f; }
    .kpi-porcentaje-azul { color:#214f79; }

    /* ===== ACCION RAPIDA ===== */
    .accion-rapida {
        display:flex;
        align-items:center;
        gap:12px;
        margin-top:16px;
        padding:14px 18px;
        border-radius:10px;
        border:1px solid #e5c68f;
        background:linear-gradient(135deg,#fff9ef,#fff2d9);
    }
    .accion-rapida svg {
        width:22px;
        height:22px;
        flex-shrink:0;
        color:#b7791f;
    }
    .accion-rapida p {
        margin:0;
        font-size:14px;
        color:#785314;
        font-weight:500;
    }
    .accion-rapida a {
        margin-left:auto;
        white-space:nowrap;
    }

    /* ===== BARRAS POR CONTRATO ===== */
    .contrato-barra-fila {
        margin:20px 0;
    }
    .contrato-barra-encabezado {
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:8px;
    }
    .contrato-barra-datos {
        display:flex;
        align-items:center;
        gap:10px;
        font-size:13px;
        color:#526575;
    }
    .contrato-barra-pct {
        display:inline-flex;
        align-items:center;
        padding:3px 10px;
        border-radius:12px;
        font-size:12px;
        font-weight:700;
        letter-spacing:.2px;
    }
    .contrato-barra-pct-ok {
        background:#e1f3ea;
        color:#246044;
    }
    .contrato-barra-pct-medio {
        background:#fff2d9;
        color:#785314;
    }
    .contrato-barra-pct-bajo {
        background:#fde8e8;
        color:#a32020;
    }
    .contrato-barra {
        display:flex;
        height:28px;
        background:#edf3f8;
        border-radius:8px;
        overflow:hidden;
        box-shadow:inset 0 1px 3px rgba(0,0,0,.06);
    }
    .contrato-barra span[data-serie] {
        transition:width .4s ease, opacity .2s;
        min-width:0;
    }
    .contrato-barra span[data-serie="liberados"] {
        background:linear-gradient(90deg,#287b78,#3a9e8f);
    }
    .contrato-barra span[data-serie="pendientes"] {
        background:linear-gradient(90deg,#b7791f,#d4a03a);
    }
    .contrato-barra-nota {
        display:flex;
        align-items:center;
        gap:5px;
        margin-top:6px;
        font-size:12px;
        color:#7a8a99;
    }
    .contrato-barra-nota svg {
        width:14px;
        height:14px;
        flex-shrink:0;
    }
    .contrato-barra-nota-ok {
        color:#246044;
        font-weight:600;
    }
    @media(max-width:600px) {
        .contrato-barra-encabezado {
            flex-direction:column;
            align-items:flex-start;
        }
        .contrato-barra-datos {
            flex-wrap:wrap;
        }
    }

    @media(max-width:600px) {
        .resumen-inteligente { padding:18px 20px; }
        .resumen-inteligente p { font-size:14px; }
        .accion-rapida { flex-wrap:wrap; }
        .accion-rapida a { margin-left:0; width:100%; justify-content:center; }
    }
</style>
<section class="estadisticas-panel" aria-labelledby="titulo-estadisticas">
    <header class="estadisticas-encabezado">
        <span class="estadisticas-encabezado-icono" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M3 3v18h18M7 16v-5m5 5V7m5 9V4"/></svg></span>
        <div>
            <h2 id="titulo-estadisticas">Estadísticas de mis servicios</h2>
            @if(($estadisticas['reportes'] ?? 0) > 0)
                <p>{{ $estadisticas['reportes'] }} {{ $estadisticas['reportes'] === 1 ? 'reporte' : 'reportes' }} · {{ $estadisticas['firmados'] }} liberados · {{ $estadisticas['reportes'] - $estadisticas['firmados'] }} en proceso</p>
            @else
                <p>Consulta el avance de tus reportes y servicios</p>
            @endif
        </div>
    </header>
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
        <button type="button" role="tab" id="tab-pendientes" aria-controls="panel-pendientes" aria-selected="false" tabindex="-1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8l5 5v3M13 3v5h5M7 12h4"/><circle cx="17" cy="17" r="5"/><path d="M17 14v3l2 1"/></svg><span>Reportes en proceso</span></button>
    </div>
    <div id="panel-resumen" role="tabpanel" aria-labelledby="tab-resumen" tabindex="0">
    @php
        $totalReportes = $estadisticas['reportes'];
        $totalFirmados = $estadisticas['firmados'];
        $totalPendientes = $totalReportes - $totalFirmados;
        $porcentajeLiberado = $totalReportes > 0 ? round($totalFirmados / $totalReportes * 100) : 0;
        $topEnsayoNombre = $estadisticas['ensayos']->keys()->first();
        $topEnsayoCantidad = $estadisticas['ensayos']->first();
        $contratoCritico = $liberacionPorContrato->first();
        $contratoCriticoNombre = $liberacionPorContrato->keys()->first();
        $mesesActivos = $estadisticas['mensuales'];
        $ultimoMesClave = $mesesActivos->isNotEmpty() ? $mesesActivos->keys()->last() : null;
        $ultimoMesCantidad = $mesesActivos->isNotEmpty() ? $mesesActivos->last() : 0;
        $penultimoMesCantidad = $mesesActivos->count() >= 2 ? $mesesActivos->slice(-2, 1)->first() : null;
        $tendencia = 'estable';
        if ($penultimoMesCantidad !== null) {
            if ($ultimoMesCantidad > $penultimoMesCantidad) $tendencia = 'creciente';
            elseif ($ultimoMesCantidad < $penultimoMesCantidad) $tendencia = 'decreciente';
        }
        $nombreUltimoMes = $ultimoMesClave ? \Carbon\CarbonImmutable::parse($ultimoMesClave . '-01')->locale('es')->translatedFormat('F Y') : '';
        $escopo = $contratoSeleccionado !== '' ? 'del contrato <strong>' . e($contratoSeleccionado) . '</strong>' : 'de todos tus contratos';
    @endphp

    @if($totalReportes > 0)
        <div class="resumen-inteligente" role="region" aria-label="Resumen inteligente de tus estadísticas">
            <div class="resumen-inteligente-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"/><path d="M9 21h6M10 18h4"/></svg>
                <h3>Resumen de tu actividad</h3>
            </div>
            <p>
                Tienes <strong>{{ $totalReportes }} {{ $totalReportes === 1 ? 'reporte' : 'reportes' }}</strong> {{ $escopo }}.
                De ellos, <strong>{{ $totalFirmados }} {{ $totalFirmados === 1 ? 'está liberado' : 'están liberados' }}</strong>
                ({{ $porcentajeLiberado }}%) y <strong>{{ $totalPendientes }} {{ $totalPendientes === 1 ? 'sigue en proceso' : 'siguen en proceso' }}</strong>.
            </p>
            @if($topEnsayoNombre && $topEnsayoCantidad)
                <p>
                    El ensayo más solicitado es <strong>{{ $topEnsayoNombre }}</strong> con {{ $topEnsayoCantidad }} {{ $topEnsayoCantidad === 1 ? 'reporte' : 'reportes' }}.
                </p>
            @endif
            @if($ultimoMesClave && $ultimoMesCantidad > 0)
                <p>
                    En <strong>{{ $nombreUltimoMes }}</strong> se generaron <strong>{{ $ultimoMesCantidad }} {{ $ultimoMesCantidad === 1 ? 'reporte' : 'reportes' }}</strong>
                    @if($tendencia === 'creciente')
                        , un aumento respecto al mes anterior.
                    @elseif($tendencia === 'decreciente')
                        , una ligera baja respecto al mes anterior.
                    @else
                        , manteniendo la actividad del mes anterior.
                    @endif
                </p>
            @endif
            <div class="insight-chips">
                <span class="insight-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="M7 16v-5m5 5V7m5 9V4"/></svg>
                    {{ $estadisticas['ordenes'] }} {{ $estadisticas['ordenes'] === 1 ? 'orden' : 'órdenes' }} · {{ $estadisticas['contratos'] }} {{ $estadisticas['contratos'] === 1 ? 'contrato' : 'contratos' }}
                </span>
                @if($porcentajeLiberado === 100)
                    <span class="insight-chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                        Todo liberado
                    </span>
                @elseif($porcentajeLiberado >= 50)
                    <span class="insight-chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        {{ $porcentajeLiberado }}% liberado
                    </span>
                @else
                    <span class="insight-chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 9v4M12 17h.01"/><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                        {{ $totalPendientes }} en proceso
                    </span>
                @endif
            </div>
        </div>
    @endif

    <div class="indicadores-grid">
        <div class="indicador">
            <div class="kpi-icono kpi-icono-azul" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            </div>
            <strong>{{ $totalReportes }}</strong>
            <span>Total de reportes</span>
            <div class="kpi-progreso kpi-progreso-azul" aria-hidden="true">
                <div class="kpi-progreso-barra" style="width:100%"></div>
            </div>
            <small class="kpi-subtexto">{{ $estadisticas['ordenes'] }} {{ $estadisticas['ordenes'] === 1 ? 'orden activa' : 'órdenes activas' }}</small>
        </div>
        <div class="indicador">
            <div class="kpi-icono kpi-icono-verde" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
            </div>
            <div class="kpi-valor-fila">
                <strong>{{ $totalFirmados }}</strong>
                <span class="kpi-porcentaje kpi-porcentaje-verde">{{ $porcentajeLiberado }}%</span>
            </div>
            <span>Reportes liberados</span>
            <div class="kpi-progreso kpi-progreso-verde" aria-hidden="true">
                <div class="kpi-progreso-barra" style="width:{{ $porcentajeLiberado }}%"></div>
            </div>
            <small class="kpi-subtexto">Con PDF firmado disponible</small>
        </div>
        <div class="indicador">
            <div class="kpi-icono kpi-icono-ambar" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div class="kpi-valor-fila">
                <strong>{{ $totalPendientes }}</strong>
                <span class="kpi-porcentaje kpi-porcentaje-ambar">{{ $totalReportes > 0 ? round((1 - $porcentajeLiberado / 100) * 100) : 0 }}%</span>
            </div>
            <span>En proceso</span>
            <div class="kpi-progreso kpi-progreso-ambar" aria-hidden="true">
                <div class="kpi-progreso-barra" style="width:{{ $totalReportes > 0 ? 100 - $porcentajeLiberado : 0 }}%"></div>
            </div>
            <small class="kpi-subtexto">Esperando liberación</small>
        </div>
    </div>

    @if($totalPendientes > 0 && $contratoCritico && $contratoCritico['pendientes'] > 0)
        <div class="accion-rapida" role="note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8l5 5v3M13 3v5h5M7 12h4M7 16h2"/><circle cx="17" cy="17" r="5"/><path d="M17 14v3l2 1"/></svg>
            <p>
                @if($contratoSeleccionado === '')
                    El contrato <strong>{{ $contratoCriticoNombre }}</strong> tiene más reportes en proceso ({{ $contratoCritico['pendientes'] }}).
                @else
                    Este contrato tiene {{ $totalPendientes }} {{ $totalPendientes === 1 ? 'reporte en proceso' : 'reportes en proceso' }}.
                @endif
            </p>
            <a class="btn-pendientes" href="#detalle-pendientes" aria-controls="detalle-pendientes" data-ver-pendientes style="margin-top:0">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                <span>Ver pendientes</span>
            </a>
        </div>
    @endif

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
                    <title id="firmas-grafica-titulo">Reportes liberados y en proceso</title>
                    <desc id="firmas-grafica-descripcion">{{ $estadisticas['firmados'] }} liberados y {{ $pendientesFirma }} en proceso, de {{ $totalFirmas }} reportes.</desc>
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
                <li><span class="color-ensayo" style="background:#b7791f" aria-hidden="true"></span><div>En proceso<strong>{{ $pendientesFirma }} {{ $pendientesFirma === 1 ? 'reporte' : 'reportes' }}</strong></div></li>
            </ul>
            @if($pendientesFirma)
                <a class="btn-pendientes" href="#detalle-pendientes" aria-controls="detalle-pendientes" data-ver-pendientes>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M10 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8l5 5v3M13 3v5h5M7 8h2M7 12h4M7 16h2" />
                        <circle cx="17" cy="17" r="5" /><path d="M17 14v3l2 1" />
                    </svg>
                    <span>Ver reportes en proceso</span>
                </a>
                <p><strong>{{ $pendientesFirma === 1 ? 'Hay 1 reporte en proceso' : 'Hay ' . $pendientesFirma . ' reportes en proceso' }}{{ $contratoSeleccionado !== '' ? ' en este contrato.' : ' entre tus contratos.' }}</strong></p>
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
        <p>Cada barra representa el 100% de los reportes de ese contrato. La parte verde son los liberados y la ámbar los que siguen en proceso.</p>
        <p><button type="button" class="serie-control" data-toggle-serie="liberados" aria-pressed="true" style="color:#287b78">● Liberados</button> <button type="button" class="serie-control" data-toggle-serie="pendientes" aria-pressed="true" style="color:#b7791f">● En proceso</button></p>
        @foreach($liberacionPorContrato as $contrato => $conteo)
            @php
                $totalContrato = max(1, $conteo['total']);
                $pctLiberados = $conteo['total'] > 0 ? $conteo['liberados'] / $totalContrato * 100 : 0;
                $pctPendientes = $conteo['total'] > 0 ? $conteo['pendientes'] / $totalContrato * 100 : 0;
                $completo = $conteo['total'] > 0 && $conteo['pendientes'] === 0;
            @endphp
            <div class="contrato-barra-fila">
                <div class="contrato-barra-encabezado">
                    <a class="contrato-grafica" href="{{ request()->url() . '?' . http_build_query(['vista' => 'estadisticas', 'contrato' => $contrato, 'seccion' => 'graficas']) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 7V4h7l2 3h9v13H3Z"/></svg>{{ $contrato }}<span aria-hidden="true">→</span></a>
                    <span class="contrato-barra-datos">
                        @if($conteo['total'] > 0)
                            <span class="contrato-barra-pct {{ $completo ? 'contrato-barra-pct-ok' : ($pctLiberados >= 50 ? 'contrato-barra-pct-medio' : 'contrato-barra-pct-bajo') }}">{{ round($pctLiberados) }}% liberado</span>
                        @endif
                        <span>{{ $conteo['liberados'] }} de {{ $conteo['total'] }} liberados</span>
                    </span>
                </div>
                @if($conteo['total'] > 0)
                    <div class="contrato-barra" role="img" aria-label="{{ $contrato }}: {{ $conteo['liberados'] }} de {{ $conteo['total'] }} reportes liberados ({{ round($pctLiberados) }}%)">
                        <span data-serie="liberados" data-tooltip="{{ $contrato }}: {{ $conteo['liberados'] }} liberados ({{ round($pctLiberados) }}%)" tabindex="0" style="width:{{ $pctLiberados }}%"></span>
                        <span data-serie="pendientes" data-tooltip="{{ $contrato }}: {{ $conteo['pendientes'] }} en proceso ({{ round($pctPendientes) }}%)" tabindex="0" style="width:{{ $pctPendientes }}%"></span>
                    </div>
                    @if($completo)
                        <small class="contrato-barra-nota contrato-barra-nota-ok">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                            Todos los reportes liberados
                        </small>
                    @elseif($conteo['pendientes'] > 0)
                        <small class="contrato-barra-nota">{{ $conteo['pendientes'] }} {{ $conteo['pendientes'] === 1 ? 'reporte sigue' : 'reportes siguen' }} en proceso</small>
                    @endif
                @else
                    <small class="contrato-barra-nota">Sin reportes registrados</small>
                @endif
            </div>
        @endforeach
        <p><small>Selecciona un contrato para consultar su detalle. El porcentaje se calcula sobre el total de reportes de cada contrato.</small></p>
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
            <h3>Reportes en proceso ({{ $estadisticas['reportes'] - $estadisticas['firmados'] }})</h3>
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
                        @empty<tr><td colspan="4">No hay reportes en proceso que coincidan con la búsqueda.</td></tr>@endforelse
                    </tbody>
                </table>
            </div>
            @include('Reportes_publicos.partials.paginacion', ['paginador' => $pendientesPaginados, 'etiqueta' => 'Páginas de reportes en proceso'])
        </div>
    @else<p>No hay reportes en proceso para este contrato.</p>
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
