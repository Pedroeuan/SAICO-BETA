@php
    $opcionesCatalogo = collect($opciones ?? [])
        ->map(fn ($opcion) => trim((string) $opcion))
        ->filter()
        ->unique(fn ($opcion) => mb_strtolower($opcion, 'UTF-8'))
        ->values();
    $valorCatalogo = trim((string) ($valor ?? ''));
@endphp

{{--
    El campo oculto conserva el nombre estable que reciben los controladores.
    El select hace visibles los valores reutilizables y la caja aparece solo al crear uno nuevo.
--}}
<div data-catalogo-metalografia>
    <input type="hidden" name="{{ $nombre }}" value="{{ $valorCatalogo }}" data-catalogo-metalografia-valor>
    <select class="form-control form-control-sm text-center" data-catalogo-metalografia-selector
        aria-label="{{ $etiqueta ?? 'Seleccionar valor' }}">
        <option value="">{{ $textoVacio ?? 'Seleccione una opción' }}</option>
        @foreach($opcionesCatalogo as $opcionCatalogo)
            <option value="{{ $opcionCatalogo }}">{{ $opcionCatalogo }}</option>
        @endforeach
        <option value="__nuevo__">+ Escribir nuevo...</option>
    </select>
    <input type="text" class="form-control form-control-sm text-center mt-1 d-none"
        maxlength="{{ $maximo ?? 255 }}" placeholder="{{ $textoNuevo ?? 'Escriba el nuevo valor' }}"
        data-catalogo-metalografia-nuevo>
</div>

