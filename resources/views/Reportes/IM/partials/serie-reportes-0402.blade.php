{{--
    Configuración de series para 04_02 y 04_03; serieFormato cambia el título mostrado.
    Sin SerieReporte solicita la cantidad inicial; con una serie permite ampliar el total.
    La numeración de hojas se calcula en el servidor usando las páginas de cada integrante.
--}}
<div class="col-12 mb-3">
    <div class="border rounded p-3 bg-light">
        <h6 class="font-weight-bold text-primary"><i class="fas fa-copy mr-1" aria-hidden="true"></i> Serie de formatos {{ $serieFormato ?? '04_02' }}</h6>
        @if(!empty($SerieReporte))
            <span class="badge badge-primary p-2">Formato {{ $SerieReporte->numero_orden }} de {{ $SerieReporte->cantidad_planificada }}</span>
            <details class="mt-2" @if($errors->has('Serie_Reportes.nueva_cantidad')) open @endif>
                <summary class="text-primary">Ampliar cantidad de formatos</summary>
                <label class="mt-2">Nuevo total de formatos
                    <input type="number" class="form-control" name="Serie_Reportes[nueva_cantidad]" min="{{ $SerieReporte->cantidad_planificada + 1 }}" max="999" value="{{ old('Serie_Reportes.nueva_cantidad') }}">
                </label>
            </details>
        @else
            <label for="cantidad-formatos-0402">Cantidad total de formatos de esta serie</label>
            <input id="cantidad-formatos-0402" class="form-control" style="max-width:200px" type="number" name="Serie_Reportes[cantidad_planificada]" value="{{ old('Serie_Reportes.cantidad_planificada', 1) }}" min="1" max="999" required>
        @endif
        <small class="d-block text-muted mt-2">Usa «Siguiente reporte → Consecutivo» para crear cada formato. Las mediciones, análisis y fotografías se capturan nuevamente.</small>
        @error('Serie_Reportes.cantidad_planificada')<div class="text-danger">{{ $message }}</div>@enderror
        @error('Serie_Reportes.nueva_cantidad')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
</div>
