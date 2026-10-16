@php
    $configuraciones = $norma->Tablas_Adicionales['quimica_configuraciones'] ?? \App\Services\ComposicionNormaIM::desdeFilas($tabla ?? []);
    $configuraciones = \App\Services\ComposicionNormaIM::normalizarLimites($configuraciones);
    $jsonQuimica = old('Quimica_Configuraciones', json_encode($configuraciones, JSON_UNESCAPED_UNICODE));
@endphp
<input type="hidden" name="Quimica_Configuraciones" id="quimica-configuraciones-json" value="{{ $jsonQuimica }}">
<div id="composicion-configuraciones">
    <style>
        #composicion-configuraciones .quimica-seccion { border: 1px solid #dce4ed; border-radius: 10px; padding: 16px; margin-bottom: 16px; background: #fff; }
        #composicion-configuraciones .quimica-titulo { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; font-size: 1.1rem; font-weight: 600; }
        #composicion-configuraciones .quimica-paso { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 10px; background: #eaf1fb; color: #245b98; flex-shrink: 0; }
        #composicion-configuraciones .quimica-descripcion { color: #64748b; margin-bottom: 14px; }
        #composicion-configuraciones .quimica-selector { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 12px; align-items: end; margin-bottom: 16px;padding:12px;background:#f5f8fc;border-radius:8px; }
        #composicion-configuraciones .quimica-selector-acciones { display:flex;gap:8px;flex-wrap:wrap; }
        #composicion-configuraciones .btn { border-radius: 6px; padding: 8px 12px; font-weight: 500; }
        #composicion-configuraciones #quimica-nueva { white-space: nowrap; background: #21774c; border-color: #21774c; }
        #composicion-configuraciones .form-control { border-radius: 7px; min-height: 40px; }
        #composicion-configuraciones label { color: #334155; font-weight: 600; margin-bottom: 7px; }
        #composicion-configuraciones #quimica-resumen { background: #f0f6fd; border: 1px solid #d7e6f6; color: #244b72; border-radius: 8px; margin: 4px 0 0; }
        #composicion-configuraciones .quimica-herramientas { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
        #composicion-configuraciones .quimica-guardar { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; border-top: 1px solid #e2e8f0; padding-top: 18px; margin-top: 20px; }
        #composicion-configuraciones .quimica-secundarias { display: flex; flex-wrap: wrap; gap: 10px; }
        #composicion-configuraciones details { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 14px; }
        #composicion-configuraciones summary { cursor: pointer; color: #334155; font-weight: 600; }
        #composicion-configuraciones details[open] summary { margin-bottom: 16px; }
        #composicion-configuraciones .quimica-campos { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px 18px; }
        #composicion-configuraciones .quimica-campos .form-group { margin:0;min-width:0; }
        #composicion-configuraciones .quimica-campos .quimica-campo-completo { grid-column:1/-1; }
        #composicion-configuraciones .quimica-datos-titulo { margin:0 0 12px;padding-bottom:8px;border-bottom:1px solid #e2e8f0;font-size:14px;font-weight:600;color:#244b72; }
        #composicion-configuraciones .quimica-campos .form-control::placeholder { font-size:12px;color:#8794a5; }
        #composicion-configuraciones .quimica-campos { margin-bottom:14px; }
        #composicion-configuraciones .quimica-tabla { table-layout:fixed;min-width:780px;margin-bottom:0; }
        #composicion-configuraciones .quimica-tabla th,#composicion-configuraciones .quimica-tabla td { padding:8px;vertical-align:middle; }
        #composicion-configuraciones .quimica-tabla .form-control { width:100%;min-width:0;min-height:34px; }
        #composicion-configuraciones .quimica-tabla td:last-child { text-align:center; }
        #composicion-configuraciones .quimica-tabla td:last-child .btn { padding:6px 9px; }
        #composicion-configuraciones .quimica-herramientas { align-items:center;justify-content:space-between; }
        #composicion-configuraciones .quimica-guardar { background:#f8fafc;padding:12px;border:1px solid #e2e8f0;border-radius:8px; }
        #composicion-configuraciones #quimica-matriz th,#composicion-configuraciones #quimica-matriz td { padding:8px; }
        @media(max-width:575px) { #composicion-configuraciones .quimica-campos{grid-template-columns:1fr} #composicion-configuraciones .quimica-guardar>button{width:100%} #composicion-configuraciones .quimica-selector-acciones{display:grid;grid-template-columns:1fr 1fr} }
        @media (max-width: 767px) { #composicion-configuraciones .quimica-selector { grid-template-columns: 1fr; } #composicion-configuraciones .quimica-seccion { padding: 15px; } #composicion-configuraciones #quimica-nueva { white-space: normal; } }
    </style>
    <section class="quimica-seccion" aria-labelledby="quimica-titulo-configuracion">
    <h5 id="quimica-titulo-configuracion" class="quimica-titulo"><span class="quimica-paso">1</span><span>Identifica la tabla de composición</span></h5>
    <p class="quimica-descripcion">Selecciona una tabla registrada o crea otra para un producto, espesor o grado diferente.</p>
    <div class="quimica-selector">
        <div><label for="quimica-configuracion"><i class="fas fa-list mr-1" aria-hidden="true"></i>Seleccionar tabla para editar</label><select class="form-control" id="quimica-configuracion"></select></div>
        <div class="quimica-selector-acciones">
            <button type="button" class="btn btn-success" id="quimica-nueva"><i class="fas fa-plus mr-1" aria-hidden="true"></i>Nueva tabla</button>
            <button type="button" class="btn btn-outline-secondary" id="quimica-duplicar"><i class="fas fa-copy mr-1" aria-hidden="true"></i>Duplicar tabla</button>
        </div>
    </div>
    <h6 class="quimica-datos-titulo"><i class="fas fa-pen mr-1" aria-hidden="true"></i>Datos de la tabla seleccionada</h6>
    <div class="quimica-campos">
        @foreach(['grado' => ['Nombre del grado / variante (opcional)', '', 'Ej.: Grado A, F 316L, A36'], 'producto' => ['Nombre del producto', 'productos-quimica', 'Ej.: Placas, barras, perfiles estructurales'], 'espesor' => ['Espesor aplicable (opcional)', 'espesores-quimica', 'Ej.: >20 y ≤40 mm'], 'ancho' => ['Ancho aplicable (opcional)', 'anchos-quimica', 'Ej.: >380 mm'], 'condiciones' => ['Condiciones o notas (opcional)', '', 'Escribe las condiciones que aplican a esta composición']] as $campo => $config)
            <div class="form-group {{ $campo === 'condiciones' ? 'quimica-campo-completo' : '' }}"><label for="quimica-{{ $campo }}">{{ $config[0] }}</label><input class="form-control" id="quimica-{{ $campo }}" data-config-campo="{{ $campo }}" placeholder="{{ $config[2] }}" @if($config[1]) list="{{ $config[1] }}" @endif maxlength="2000"></div>
        @endforeach
    </div>
    <datalist id="productos-quimica"><option value="Perfiles estructurales"><option value="Placas"><option value="Barras"><option value="Barras / placas de ancho ≤380 mm"></datalist>
    <datalist id="anchos-quimica"><option value=">380 mm"><option value="≤380 mm"></datalist>
    <datalist id="espesores-quimica">@foreach(['Todos', '≤20 mm', '>20 y ≤40 mm', '>40 y ≤65 mm', '>65 y ≤100 mm', '>100 mm', '>40 y ≤100 mm'] as $rango)<option value="{{ $rango }}">@endforeach</datalist>
    <p id="quimica-resumen" class="alert alert-info" aria-live="polite"></p>
    </section>
    <section class="quimica-seccion" aria-labelledby="quimica-titulo-limites">
    <h5 id="quimica-titulo-limites" class="quimica-titulo"><span class="quimica-paso">2</span><span>Límites de composición química</span></h5>
    <p>Los límites vacíos no están establecidos. Registra por elemento cualquier condición, por ejemplo: «Cuando se especifica acero al cobre».</p>
    <div class="table-responsive"><table class="table table-bordered table-sm quimica-tabla"><colgroup><col style="width:14%"><col style="width:13%"><col style="width:13%"><col style="width:26%"><col style="width:26%"><col style="width:8%"></colgroup><thead><tr><th>Elemento</th><th>Mínimo (%)</th><th>Máximo (%)</th><th>Criterio original / fórmula</th><th>Condición de aplicación</th><th><span class="sr-only">Eliminar</span></th></tr></thead><tbody id="quimica-limites"></tbody></table></div>
    <div class="quimica-herramientas">
    <button type="button" class="btn btn-outline-primary" id="quimica-elemento"><i class="fas fa-plus mr-1" aria-hidden="true"></i>Agregar elemento</button>
    </div>

    <div class="quimica-guardar"><div class="quimica-secundarias">
    <button type="button" class="btn btn-outline-secondary" id="quimica-restablecer"><i class="fas fa-undo mr-1" aria-hidden="true"></i>Restablecer</button>
    <button type="button" class="btn btn-outline-danger" id="quimica-eliminar"><i class="fas fa-trash-alt mr-1" aria-hidden="true"></i>Eliminar tabla</button>
    </div>
    <button type="submit" class="btn btn-primary" name="guardar_seccion" value="quimica"><i class="fas fa-save mr-1" aria-hidden="true"></i>Guardar composición química</button>
    </div></section>
    <section class="quimica-seccion" aria-labelledby="quimica-titulo-consulta">
    <h5 id="quimica-titulo-consulta" class="quimica-titulo"><span class="quimica-paso">3</span><span>Consulta de valores registrados</span></h5>
    <label for="quimica-categoria">Producto y clasificación por ancho</label>
    <select id="quimica-categoria" class="form-control mb-3"></select>
    <div id="quimica-matriz" class="table-responsive"></div>
    </section>
</div>
