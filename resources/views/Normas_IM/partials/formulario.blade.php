@php
    $norma = $Normas_IM ?? null;
    $definiciones = \App\Services\TablasNormasIM::definiciones();
    $quimica = $tabla ?? [];
    if (old('Elemento') !== null) {
        $quimica = [];
        foreach (old('Elemento', []) as $i => $elemento) {
            $quimica[] = ['Elemento' => $elemento, 'Promedio' => old('Promedio.'.$i), 'Composicion' => old('Composicion.'.$i), 'Aplicabilidad' => old('Contexto.'.$i, [])];
        }
    }
    if (!$quimica) $quimica = [['Elemento' => '', 'Promedio' => '', 'Composicion' => '']];
@endphp
<div class="container-fluid py-4">
    <h3><i class="fas fa-book mr-2" aria-hidden="true"></i>{{ $norma ? 'Editar norma' : 'Registrar norma' }}</h3>
    <p class="text-muted">Completa solo lo que aplique. Guarda cada pestaña por separado y continúa después.</p>
    <style>
        #NormasIMForm .nav-tabs .nav-link { padding: .8rem 1rem; }
        #NormasIMForm .tab-content { background: #fff; border-radius: 0 0 .5rem .5rem; }
        #NormasIMForm table thead th { background: #eef3f8; vertical-align: middle; }
        #NormasIMForm .table-responsive { max-height: 520px; }
        #NormasIMForm table thead { position: sticky; top: 0; z-index: 1; }
        #NormasIMForm .form-control:focus { border-color: #4288c4; }
        #panel-otras .tabla-personalizada { margin:0 0 18px;border:1px solid #dce4ef;border-radius:10px;background:#fff;overflow:hidden; }
        #panel-otras .tabla-personalizada-cabecera { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding:12px 16px;background:#edf4fc;border-bottom:1px solid #dce4ef;color:#244b73; }
        #panel-otras .tabla-personalizada-conteo { padding:4px 10px;border:1px solid #dce4ef;border-radius:16px;background:#fff;font-size:12px;white-space:nowrap; }
        #panel-otras .tabla-personalizada-contenido { padding:16px; }
        #panel-otras .tabla-personalizada-contenido>label { display:block;margin-bottom:8px; }
        #panel-otras .tabla-personalizada .table { table-layout:fixed;width:100%;margin:0; }
        #panel-otras .tabla-personalizada th,#panel-otras .tabla-personalizada td { padding:9px;vertical-align:middle; }
        #panel-otras .tabla-personalizada .form-control { width:100%;min-width:0;min-height:34px;border-radius:5px; }
        #panel-otras .tabla-columna-encabezado { display:flex;align-items:center;gap:7px; }
        #panel-otras .tabla-columna-encabezado .form-control { flex:1; }
        #panel-otras .tabla-columna-encabezado .btn { flex-shrink:0;padding:5px 7px; }
        #panel-otras .tabla-personalizada-acciones { display:flex;align-items:center;flex-wrap:wrap;gap:10px;padding:12px 16px;border-top:1px solid #e2e8f0;background:#f8fafc; }
        #panel-otras .tabla-personalizada-acciones .btn { margin:0;border-radius:6px; }
        #panel-otras .tabla-personalizada-acciones .ml-auto { margin-left:auto!important; }
        #panel-otras .tablas-personalizadas-guardar { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding-top:16px;margin-top:6px;border-top:1px solid #dce4ef; }
        #panel-otras .tabla-personalizada-vacia { display:flex;flex-direction:column;align-items:center;gap:8px;padding:24px;border:1px dashed #b9cce0;border-radius:8px;color:#52657a; }
        @media(max-width:575px) { #panel-otras .tabla-personalizada-contenido{padding:10px} #panel-otras .tablas-personalizadas-guardar .btn{width:100%} }
    </style>
    @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form id="NormasIMForm" method="post" action="{{ $norma ? route('Normas_IM.update', ['id' => $norma->idnormas_im]) : route('Normas_IM.store') }}" class="card card-body">
        <input type="hidden" id="norma-pestana-inicial" value="{{ old('guardar_seccion', session('pestana_norma', 'quimica')) }}">
        @csrf
        <div class="row">
            <div class="col-md-6 form-group"><label for="norma-nombre">Nombre / Especificación</label><input class="form-control" id="norma-nombre" name="NombreESP" value="{{ old('NombreESP', $norma->Nombre_Espe ?? '') }}" maxlength="255" required></div>
            <div class="col-md-6 form-group"><label for="norma-variable">Variable / Subtítulo / Grado</label><input class="form-control" id="norma-variable" name="Variable" value="{{ old('Variable', $norma->Variable ?? '') }}" maxlength="255"></div>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            @foreach(['tratamiento' => 'Tratamiento térmico', 'quimica' => 'Composición química', 'mecanica' => 'Propiedades mecánicas y dureza', 'otras' => 'Otras tablas de la norma'] as $clave => $titulo)
                <li class="nav-item"><a class="nav-link {{ $clave === 'quimica' ? 'active' : '' }}" id="tab-{{ $clave }}" data-toggle="tab" href="#panel-{{ $clave }}" role="tab" aria-controls="panel-{{ $clave }}" aria-selected="{{ $clave === 'quimica' ? 'true' : 'false' }}"><i class="fas {{ ['tratamiento' => 'fa-fire', 'quimica' => 'fa-flask', 'mecanica' => 'fa-ruler-combined', 'otras' => 'fa-table'][$clave] }} mr-1" aria-hidden="true"></i>{{ $titulo }}</a></li>
            @endforeach
        </ul>
        <div class="tab-content border p-3">
            @foreach(['tratamiento', 'quimica', 'mecanica'] as $clave)
                @php
                    $esQuimica = $clave === 'quimica';
                    $columnas = $esQuimica ? ['Elemento' => 'Elemento químico', 'Promedio' => 'Promedio de la pieza analizada', 'Composicion' => 'Composición química teórica'] + \App\Services\TablasNormasIM::aplicabilidad() : $definiciones[$clave]['columnas'];
                    $filas = $esQuimica ? $quimica : old('Tablas_Adicionales.'.$clave, $norma->Tablas_Adicionales[$clave] ?? []);
                    if (!$filas || !is_array($filas)) $filas = [[]];
                    if ($clave === 'mecanica') $filas = array_map(fn ($fila) => \App\Services\TablasNormasIM::separarTension($fila), $filas);
                    if ($clave === 'mecanica') {
                        unset($columnas['elongacion'], $columnas['reduccion']);
                        $columnas['producto'] = 'Tipo de producto (opcional)';
                        $columnas['ancho'] = 'Ancho aplicable y unidad (opcional)';
                        $columnas['espesor'] = 'Espesor aplicable y unidad (opcional)';
                        $columnas['condiciones'] = 'Condiciones de aplicación (opcional)';
                    }
                @endphp
                <div class="tab-pane fade {{ $esQuimica ? 'show active' : '' }}" id="panel-{{ $clave }}" role="tabpanel" aria-labelledby="tab-{{ $clave }}" data-tabla-norma="{{ $clave }}">
                    @if($esQuimica)
                        @include('Normas_IM.partials.composicion-configuraciones')
                    @else
                    @if(!$esQuimica)<input type="hidden" name="Tablas_Adicionales[{{ $clave }}]" value="">@endif
                    <p>{{ $esQuimica ? 'Captura los elementos y su composición química.' : ($clave === 'mecanica' ? 'Captura la tensión mínima y máxima en sus campos separados, con su unidad. Deja vacío el límite que la norma no establezca.' : 'Agrega las filas aplicables al grado. Puedes escribir mínimos, intervalos y ambos sistemas de unidades tal como aparecen en la norma.') }}</p>
                    <small class="d-block mb-2">Si los valores cambian por producto, ancho o espesor, agrega filas para cada caso e indica el intervalo y su unidad. Si no aplica, deja esos campos vacíos. Cada tabla conserva sus propias condiciones.</small>
                    <div class="table-responsive"><table class="table table-bordered table-sm">
                        <thead><tr><th>#</th>@foreach($columnas as $titulo)<th style="min-width: 160px">{{ $titulo }}</th>@endforeach @if($clave === 'mecanica')<th>Datos adicionales (opcional)</th>@endif<th>Eliminar</th></tr></thead>
                        <tbody>@foreach($filas as $indice => $fila)@include('Normas_IM.partials.fila')@endforeach</tbody>
                    </table></div>
                    <template>@include('Normas_IM.partials.fila', ['indice' => 0, 'fila' => []])</template>
                    <div class="d-flex align-items-center"><label for="cantidad-{{ $clave }}" class="mr-2">Número de filas:</label><input class="form-control mr-2" style="width:90px" id="cantidad-{{ $clave }}" type="number" value="1" min="1" max="{{ $esQuimica ? 100 : 25 }}"><button class="btn btn-success agregar-filas-norma" type="button">Agregar filas</button></div>
                    @if(!$esQuimica)
                        <div class="mt-3 text-right"><button type="submit" name="guardar_seccion" value="{{ $clave }}" class="btn btn-primary"><i class="fas fa-save mr-1" aria-hidden="true"></i>Guardar esta tabla</button></div>
                    @endif
                    @endif
                </div>
            @endforeach
            <div class="tab-pane fade" id="panel-otras" role="tabpanel" aria-labelledby="tab-otras">
                <p>Agrega las tablas que necesite esta norma, con sus propios títulos, columnas y datos.</p>
                <input type="hidden" name="Tablas_Adicionales[otras]" value="">
                <div id="otras-tablas"></div>
                <div class="tablas-personalizadas-guardar">
                    <button class="btn btn-outline-success" type="button" id="agregar-otra-tabla"><i class="fas fa-plus-circle mr-1" aria-hidden="true"></i>Agregar tabla personalizada</button>
                    <button class="btn btn-primary" type="submit" name="guardar_seccion" value="otras"><i class="fas fa-save mr-1" aria-hidden="true"></i>Guardar tablas personalizadas</button>
                </div>
                <script type="application/json" id="otras-tablas-datos">{!! json_encode(old('Tablas_Adicionales.otras', $norma->Tablas_Adicionales['otras'] ?? []), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
            </div>
        </div>
        <div class="form-group mt-3"><label for="norma-observaciones">Observaciones</label><textarea class="form-control" id="norma-observaciones" name="Observaciones" maxlength="5000">{{ old('Observaciones', $norma->Observaciones ?? '') }}</textarea></div>
        <div class="text-right"><a href="{{ route('index.Normas_IM') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1" aria-hidden="true"></i>Volver</a> <button class="btn btn-outline-primary" type="submit"><i class="fas fa-save mr-1" aria-hidden="true"></i>Guardar todas las pestañas</button></div>
    </form>
</div>
