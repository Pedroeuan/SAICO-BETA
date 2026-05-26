@php
    $posiciones = [
        'delantera_izquierda' => 'Delantera izquierda',
        'delantera_derecha' => 'Delantera derecha',
        'trasera_izquierda' => 'Trasera izquierda',
        'trasera_derecha' => 'Trasera derecha',
        'refaccion' => 'Refaccion',
        'extra' => 'Extra',
    ];
    $estados = [
        'activa' => 'Activa',
        'rotada' => 'Rotada',
        'baja' => 'Baja',
    ];
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="posicion">Posicion</label>
            <select id="posicion" name="posicion" class="form-control @error('posicion') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                @foreach($posiciones as $value => $label)
                    <option value="{{ $value }}" @selected(old('posicion', $llanta->posicion ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('posicion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="estado">Estado</label>
            <select id="estado" name="estado" class="form-control @error('estado') is-invalid @enderror" required>
                @foreach($estados as $value => $label)
                    <option value="{{ $value }}" @selected(old('estado', $llanta->estado ?? 'activa') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" id="marca" name="marca" maxlength="100" class="form-control @error('marca') is-invalid @enderror" value="{{ old('marca', $llanta->marca ?? '') }}" required>
            @error('marca') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="modelo">Modelo</label>
            <input type="text" id="modelo" name="modelo" maxlength="100" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo', $llanta->modelo ?? '') }}">
            @error('modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="medida">Medida</label>
            <input type="text" id="medida" name="medida" maxlength="50" class="form-control @error('medida') is-invalid @enderror" value="{{ old('medida', $llanta->medida ?? '') }}">
            @error('medida') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="numero_serie">Numero de serie</label>
            <input type="text" id="numero_serie" name="numero_serie" maxlength="120" class="form-control @error('numero_serie') is-invalid @enderror" value="{{ old('numero_serie', $llanta->numero_serie ?? '') }}">
            @error('numero_serie') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="fecha_instalacion">Fecha instalacion</label>
            <input type="date" id="fecha_instalacion" name="fecha_instalacion" class="form-control @error('fecha_instalacion') is-invalid @enderror" value="{{ old('fecha_instalacion', isset($llanta) ? optional($llanta->fecha_instalacion)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            @error('fecha_instalacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="kilometraje_instalacion">KM instalacion</label>
            <input type="number" id="kilometraje_instalacion" name="kilometraje_instalacion" min="0" class="form-control @error('kilometraje_instalacion') is-invalid @enderror" value="{{ old('kilometraje_instalacion', $llanta->kilometraje_instalacion ?? ($vehiculo->kilometraje_actual ?? 0)) }}" required>
            @error('kilometraje_instalacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="fecha_baja">Fecha baja</label>
            <input type="date" id="fecha_baja" name="fecha_baja" class="form-control @error('fecha_baja') is-invalid @enderror" value="{{ old('fecha_baja', isset($llanta) ? optional($llanta->fecha_baja)->format('Y-m-d') : '') }}">
            @error('fecha_baja') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="kilometraje_baja">KM baja</label>
            <input type="number" id="kilometraje_baja" name="kilometraje_baja" min="0" class="form-control @error('kilometraje_baja') is-invalid @enderror" value="{{ old('kilometraje_baja', $llanta->kilometraje_baja ?? '') }}">
            @error('kilometraje_baja') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="costo">Costo</label>
            <input type="number" id="costo" name="costo" min="0" step="0.01" class="form-control @error('costo') is-invalid @enderror" value="{{ old('costo', $llanta->costo ?? '') }}">
            @error('costo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label for="observaciones">Observaciones</label>
    <textarea id="observaciones" name="observaciones" rows="3" maxlength="1000" class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $llanta->observaciones ?? '') }}</textarea>
    @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
