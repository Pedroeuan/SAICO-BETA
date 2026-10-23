{{--
    Selector reutilizable de metalografía: recibe nombre, valor y opciones del catálogo.
    Admite escoger un valor existente o escribir otro; el campo oculto envía el valor final.
    La flecha identifica visualmente el desplegable en 04_02 y 04_03.
--}}
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
    <div class="metalografia-selector-control">
    <select class="form-control form-control-sm text-center" data-catalogo-metalografia-selector
        aria-label="{{ $etiqueta ?? 'Seleccionar valor' }}">
        <option value="">{{ $textoVacio ?? 'Seleccione una opción' }}</option>
        @foreach($opcionesCatalogo as $opcionCatalogo)
            <option value="{{ $opcionCatalogo }}">{{ $opcionCatalogo }}</option>
        @endforeach
        <option value="__nuevo__">Agregar otra opción…</option>
    </select>
    <i class="fas fa-chevron-down metalografia-selector-icono" aria-hidden="true" style="display:none"></i>
    </div>
    <input type="text" class="form-control form-control-sm text-center mt-1 d-none"
        maxlength="{{ $maximo ?? 255 }}" placeholder="{{ $textoNuevo ?? 'Escriba el nuevo valor' }}"
        data-catalogo-metalografia-nuevo>
</div>

@once
    <style>
        :is(#FOR-PIMP-04_02, #FOR-PIMP-04_03, #FOR-PIMP-03_B_01) .metalografia-selector-control { position:relative; }
        :is(#FOR-PIMP-04_02, #FOR-PIMP-04_03, #FOR-PIMP-03_B_01) .metalografia-selector-control select {
            appearance:none; -webkit-appearance:none; background-image:none;
            padding-right:27px; padding-left:7px; cursor:pointer; border-radius:5px;
        }
        :is(#FOR-PIMP-04_02, #FOR-PIMP-04_03, #FOR-PIMP-03_B_01) .metalografia-selector-control .metalografia-selector-icono {
            display:block!important; position:absolute; right:9px; top:50%; transform:translateY(-50%);
            font-size:11px; color:#3674b5; pointer-events:none;
        }
        :is(#FOR-PIMP-04_02, #FOR-PIMP-04_03, #FOR-PIMP-03_B_01) .metalografia-selector-control:focus-within .metalografia-selector-icono { color:#0056b3; }
    </style>
@endonce

