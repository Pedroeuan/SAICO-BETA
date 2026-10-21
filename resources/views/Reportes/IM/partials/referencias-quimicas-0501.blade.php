{{-- Solo composiciones químicas: admite varias normas y variantes, sin exigir datos de dureza. --}}
@php
    $opcionesQuimicas0501 = \App\Services\ReferenciasQuimicas0501::catalogo(\App\Models\Normas_IM\Normas_IM::orderBy('Nombre_Espe')->get());
    $historicaQuimica0501 = $Detalles_Generales['Norma_IM'] ?? [];
    $seleccionQuimica0501 = old('Norma_IM.Seleccion_Quimica', $historicaQuimica0501['Seleccion_Quimica'] ?? []);
    foreach (($historicaQuimica0501['Referencias_Quimicas'] ?? []) as $guardadaQuimica) {
        if (!in_array($guardadaQuimica['clave'], array_column($opcionesQuimicas0501, 'clave'), true)) $opcionesQuimicas0501[] = $guardadaQuimica;
    }
    if (!old('Norma_IM.seleccion_multiple') && !array_key_exists('Seleccion_Quimica', $historicaQuimica0501) && !empty($historicaQuimica0501['idnormas_im'])) {
        $claveHistorica0501 = $historicaQuimica0501['idnormas_im'] . ':base';
        // El reporte antiguo conserva su tabla aunque el catálogo tenga ahora otras configuraciones.
        $opcionesQuimicas0501 = array_values(array_filter($opcionesQuimicas0501, fn ($opcion) => $opcion['clave'] !== $claveHistorica0501));
        $opcionesQuimicas0501[] = ['clave' => $claveHistorica0501, 'idnormas_im' => $historicaQuimica0501['idnormas_im'], 'norma' => $historicaQuimica0501['Nombre_Espe'] ?? 'Norma guardada', 'composicion_seleccionada' => $historicaQuimica0501];
        $seleccionQuimica0501 = [$claveHistorica0501];
    }
@endphp
<div class="col-12 mb-3" data-referencias-quimicas-0501>
    <div class="quimica-panel">
    <input type="hidden" name="Norma_IM[seleccion_multiple]" value="1">
    <div class="quimica-cabecera">
        <div>
            <label class="font-weight-bold mb-1" for="buscarNormasQuimicas0501">Normas de composición química</label>
            <small class="d-block text-muted">Selecciona las referencias para comparar los elementos de la pieza.</small>
        </div>
        {{-- La nueva pestaña conserva el reporte que el técnico está capturando. --}}
        <a class="btn btn-outline-primary btn-sm" href="{{ route('Normas_IM.Create') }}" target="_blank" rel="noopener">
            <i class="fas fa-plus mr-1" aria-hidden="true"></i> Crear norma
        </a>
    </div>
    <div class="quimica-busqueda">
        <i class="fas fa-search" aria-hidden="true"></i>
        <input type="search" class="form-control" id="buscarNormasQuimicas0501" placeholder="Buscar norma, grado o variante">
    </div>
    <div class="quimica-subtitulo">Referencias seleccionadas <span data-quimica-total class="badge badge-light">0</span></div>
    <div data-quimica-seleccionadas class="quimica-seleccionadas"></div>
    <div class="quimica-subtitulo">Normas disponibles <small class="text-muted font-weight-normal">Puedes marcar varias</small></div>
    <div data-quimica-opciones class="quimica-opciones"></div>
    @error('Norma_IM.Seleccion_Quimica')<div class="text-danger">{{ $message }}</div>@enderror
    <script type="application/json" data-quimica-config>@json(['opciones' => $opcionesQuimicas0501, 'seleccion' => $seleccionQuimica0501])</script>
    </div>
</div>
@once
<style>
    /* Diseño compartido por Crear y Editar; no modifica los nombres enviados al controlador. */
    [data-referencias-quimicas-0501] 
    .quimica-panel { 
        border:1px solid #dce3ed;
        border-radius:10px;
        padding:16px;
        background:#fff; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-cabecera { 
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:14px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-cabecera .btn { 
        white-space:nowrap;
        border-radius:6px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-busqueda { 
        position:relative;
        margin-bottom:14px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-busqueda i { 
        position:absolute;
        left:12px;
        top:50%;
        transform:translateY(-50%);
        color:#7b8799;
        pointer-events:none; }
    [data-referencias-quimicas-0501] 
    .quimica-busqueda input { 
        padding-left:35px;
        border-radius:7px;
        background:#f8fafc; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-subtitulo { 
        display:flex;
        align-items:center;
        gap:8px;
        font-size:12px;
        font-weight:600;
        color:#475569;
        margin:12px 0 8px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-seleccionadas { 
        display:flex;
        flex-wrap:wrap;
        gap:8px;min-height:32px; }
    [data-referencias-quimicas-0501] 
    .quimica-etiqueta { 
        border:1px solid #dce3ed;
        border-left:4px solid currentColor;
        border-radius:6px;
        padding:7px 10px;
        font-size:12px;
        font-weight:600;
        background:#f8fafc; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opciones { 
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:8px;
        max-height:230px;
        overflow:auto;
        padding:2px 5px 2px 0; 
    }
    [data-referencias-quimicas-0501] .quimica-opcion { 
        display:flex;
        align-items:center;
        gap:10px;
        border:1px solid #e2e8f0;
        border-radius:7px;
        padding:10px 12px;
        margin:0;cursor:pointer;
        font-weight:500;
        min-width:0; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opcion:hover { 
        background:#f4f8ff;border-color:#9abcf0; }
    [data-referencias-quimicas-0501] 
    .quimica-opcion.is-selected { 
        background:#eff6ff;
        border-color:#93b7ec; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opcion:focus-within { 
        outline:2px solid #93b7ec;
        outline-offset:1px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opcion input { 
        flex:0 0 auto;
        margin:0;
        accent-color:#1769d2; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opcion[hidden] { 
        display:none; 
    }
    @media(max-width:650px) { [data-referencias-quimicas-0501] .quimica-opciones { grid-template-columns:1fr; } }
</style>
@endonce
