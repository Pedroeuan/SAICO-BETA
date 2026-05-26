<div class="form-row">
    <div class="form-group col-md-4">
        <label for="calificacion_servicio">Calidad del servicio</label>
        <select name="calificacion_servicio" id="calificacion_servicio" class="form-control @error('calificacion_servicio') is-invalid @enderror" required>
            <option value="">Selecciona</option>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ (int) old('calificacion_servicio') === $i ? 'selected' : '' }}>{{ $i }} / 5</option>
            @endfor
        </select>
        @error('calificacion_servicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="calificacion_estado_unidad">Estado de la unidad</label>
        <select name="calificacion_estado_unidad" id="calificacion_estado_unidad" class="form-control @error('calificacion_estado_unidad') is-invalid @enderror" required>
            <option value="">Selecciona</option>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ (int) old('calificacion_estado_unidad') === $i ? 'selected' : '' }}>{{ $i }} / 5</option>
            @endfor
        </select>
        @error('calificacion_estado_unidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="calificacion_tiempo_respuesta">Tiempo de respuesta</label>
        <select name="calificacion_tiempo_respuesta" id="calificacion_tiempo_respuesta" class="form-control @error('calificacion_tiempo_respuesta') is-invalid @enderror" required>
            <option value="">Selecciona</option>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ (int) old('calificacion_tiempo_respuesta') === $i ? 'selected' : '' }}>{{ $i }} / 5</option>
            @endfor
        </select>
        @error('calificacion_tiempo_respuesta') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group">
    <label for="nps">Del 0 al 10, que tanto recomendarias el servicio vehicular interno?</label>
    <input type="number" min="0" max="10" name="nps" id="nps" value="{{ old('nps') }}" class="form-control @error('nps') is-invalid @enderror" required>
    <small class="form-text text-muted">0 a 6 detractor, 7 a 8 pasivo, 9 a 10 promotor.</small>
    @error('nps') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="comentario">Comentario estrategico</label>
    <textarea name="comentario" id="comentario" rows="4" class="form-control @error('comentario') is-invalid @enderror" placeholder="Cuéntanos qué funcionó bien, qué retrasó el servicio o qué mejorarías.">{{ old('comentario') }}</textarea>
    @error('comentario') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
