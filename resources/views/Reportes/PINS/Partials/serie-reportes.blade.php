{{-- Configuracion compartida de la numeracion consecutiva entre reportes PND. --}}
<div class="col-12 mb-3">
    <div class="border rounded p-3 bg-light">
        <h6 class="font-weight-bold text-primary">
            <i class="fas fa-copy mr-1" aria-hidden="true"></i>
            Serie de reportes · {{ $formatoSeriePnd }}
        </h6>
        @if(!empty($SerieReporte))
            <span class="badge badge-primary p-2">Reporte {{ $SerieReporte->numero_orden }} de {{ $SerieReporte->cantidad_planificada }}</span>
            <details class="mt-2" @if($errors->has('Serie_Reportes.nueva_cantidad')) open @endif>
                <summary class="text-primary">Ampliar cantidad de reportes</summary>
                <label class="mt-2" for="serie-pnd-nueva-cantidad">Nuevo total de reportes</label>
                <input id="serie-pnd-nueva-cantidad" type="number" class="form-control" style="max-width:200px"
                    name="Serie_Reportes[nueva_cantidad]" min="{{ $SerieReporte->cantidad_planificada + 1 }}"
                    max="999" value="{{ old('Serie_Reportes.nueva_cantidad') }}">
            </details>
        @else
            <label for="serie-pnd-cantidad">Cantidad total de reportes de esta serie</label>
            <input id="serie-pnd-cantidad" type="number" class="form-control" style="max-width:200px"
                name="Serie_Reportes[cantidad_planificada]" min="1" max="999"
                value="{{ old('Serie_Reportes.cantidad_planificada', 1) }}" required>
        @endif
        <small class="d-block text-muted mt-2">
            Use «Siguiente reporte → Consecutivo» para continuar esta serie. La numeración de páginas
            continúa entre sus PDF; los resultados y fotografías se capturan en cada reporte.
        </small>
        @error('Serie_Reportes.cantidad_planificada')<div class="text-danger">{{ $message }}</div>@enderror
        @error('Serie_Reportes.nueva_cantidad')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
</div>
