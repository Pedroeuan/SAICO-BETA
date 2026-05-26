<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="fecha_carga">Fecha de carga</label>
            <input
                type="date"
                id="fecha_carga"
                name="fecha_carga"
                class="form-control @error('fecha_carga') is-invalid @enderror"
                value="{{ old('fecha_carga', isset($carga) ? optional($carga->fecha_carga)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                required
            >
            @error('fecha_carga') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="kilometraje">Kilometraje</label>
            <input
                type="number"
                id="kilometraje"
                name="kilometraje"
                min="0"
                class="form-control @error('kilometraje') is-invalid @enderror"
                value="{{ old('kilometraje', isset($carga) ? $carga->kilometraje : ($vehiculo->kilometraje_actual ?? 0)) }}"
                required
            >
            @error('kilometraje') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="litros">Litros</label>
            <input
                type="number"
                id="litros"
                name="litros"
                min="0.001"
                step="0.001"
                class="form-control @error('litros') is-invalid @enderror"
                value="{{ old('litros', isset($carga) ? $carga->litros : '') }}"
                required
            >
            @error('litros') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="costo_total">Costo total</label>
            <input
                type="number"
                id="costo_total"
                name="costo_total"
                min="0"
                step="0.01"
                class="form-control @error('costo_total') is-invalid @enderror"
                value="{{ old('costo_total', isset($carga) ? $carga->costo_total : '') }}"
                required
            >
            @error('costo_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="tipo_combustible">Tipo de combustible</label>
            <select
                id="tipo_combustible"
                name="tipo_combustible"
                class="form-control @error('tipo_combustible') is-invalid @enderror"
                required
            >
                @php($tipoSeleccionado = old('tipo_combustible', isset($carga) ? $carga->tipo_combustible : 'magna'))
                <option value="magna" @selected($tipoSeleccionado === 'magna')>Magna</option>
                <option value="premium" @selected($tipoSeleccionado === 'premium')>Premium</option>
                <option value="diesel" @selected($tipoSeleccionado === 'diesel')>Diesel</option>
                <option value="otro" @selected($tipoSeleccionado === 'otro')>Otro</option>
            </select>
            @error('tipo_combustible') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="proveedor">Estacion / proveedor</label>
            <input
                type="text"
                id="proveedor"
                name="proveedor"
                maxlength="150"
                class="form-control @error('proveedor') is-invalid @enderror"
                value="{{ old('proveedor', isset($carga) ? $carga->proveedor : '') }}"
            >
            @error('proveedor') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="ticket_url">Ticket / comprobante</label>
            <input
                type="file"
                id="ticket_url"
                name="ticket_url"
                accept=".pdf,.jpg,.jpeg,.png"
                class="form-control @error('ticket_url') is-invalid @enderror"
            >
            @error('ticket_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if(isset($carga) && $carga->ticket_url)
                <small class="form-text text-muted">
                    Archivo actual:
                    <a href="{{ asset('storage/'.$carga->ticket_url) }}" target="_blank" rel="noopener">ver comprobante</a>
                </small>
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-check">
        <input
            type="checkbox"
            id="tanque_lleno"
            name="tanque_lleno"
            value="1"
            class="form-check-input"
            @checked(old('tanque_lleno', isset($carga) ? $carga->tanque_lleno : false))
        >
        <label class="form-check-label" for="tanque_lleno">Tanque lleno</label>
    </div>
</div>

<div class="form-group">
    <label for="observaciones">Observaciones</label>
    <textarea
        id="observaciones"
        name="observaciones"
        rows="3"
        maxlength="1000"
        class="form-control @error('observaciones') is-invalid @enderror"
    >{{ old('observaciones', isset($carga) ? $carga->observaciones : '') }}</textarea>
    @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
