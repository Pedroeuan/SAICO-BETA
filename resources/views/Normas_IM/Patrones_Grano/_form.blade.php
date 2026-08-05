@php
    $nombreActual = old('nombre', $patron->nombre ?? '');
@endphp

{{-- El operador solo captura el nombre normalizado y la imagen maestra correspondiente. --}}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="nombre">Nombre del grano</label>
            <input id="nombre" name="nombre" type="text" maxlength="50" required
                value="{{ $nombreActual }}"
                placeholder="Ejemplo: Grano 3.5"
                class="form-control @error('nombre') is-invalid @enderror">
            <small class="form-text text-muted">Puede registrar nuevos valores en el futuro; no existe una lista fija.</small>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="imagen">Imagen maestra</label>
            <input id="imagen" name="imagen" type="file" accept="image/png,image/jpeg"
                class="form-control-file @error('imagen') is-invalid @enderror"
                {{ isset($patron) ? '' : 'required' }}>
            <small class="form-text text-muted">Formatos permitidos: JPG y PNG. Tamaño máximo: 25 MB.</small>
            @error('imagen')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <img id="vistaPreviaPatron"
            src="{{ isset($patron) ? asset('storage/' . $patron->ruta_imagen) : '' }}"
            class="img-fluid img-thumbnail {{ isset($patron) ? '' : 'd-none' }}"
            style="max-height: 420px"
            alt="Vista previa del patrón comparativo">
    </div>
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('Patrones_Grano_IM.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">{{ isset($patron) ? 'Actualizar' : 'Guardar' }}</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const entrada = document.getElementById('imagen');
    const previa = document.getElementById('vistaPreviaPatron');

    // La vista previa permite comprobar que el archivo corresponde al número antes de guardarlo.
    entrada?.addEventListener('change', function () {
        const archivo = this.files?.[0];
        if (!archivo) return;

        previa.src = URL.createObjectURL(archivo);
        previa.classList.remove('d-none');
        previa.onload = function () { URL.revokeObjectURL(previa.src); };
    });
});
</script>
