@php
    $patronHistorico = is_array($patronGrano ?? null) ? $patronGrano : [];
    $catalogoPatrones = collect($PatronesGranoIM ?? [])->values();
    $patronOld = old('Patron_Grano');
    $tienePatronOld = is_array($patronOld);
    $idSeleccionado = (string) ($tienePatronOld ? ($patronOld['id'] ?? '') : ($patronHistorico['id'] ?? ''));
    $patronActivo = $tienePatronOld ? !empty($patronOld['activo']) : $idSeleccionado !== '';
    $usarVersionCatalogo = (bool) ($tienePatronOld
        ? ($patronOld['usar_version_catalogo'] ?? false)
        : false);
    $descripcionPatron = (string) ($tienePatronOld
        ? ($patronOld['descripcion'] ?? '')
        : ($patronHistorico['descripcion'] ?? ''));
    $layoutPatron = array_merge(
        ['pagina' => 1, 'posicion' => 'abajo_izquierda'],
        is_array(($tienePatronOld ? ($patronOld['layout'] ?? null) : ($patronHistorico['layout'] ?? null)))
            ? ($tienePatronOld ? $patronOld['layout'] : $patronHistorico['layout'])
            : []
    );

    // El navegador recibe la URL de la copia histórica, nunca la ruta física del servidor.
    $rutaHistorica = (string) ($patronHistorico['ruta_imagen'] ?? '');
    $patronHistoricoNavegador = $usarVersionCatalogo ? [] : array_merge($patronHistorico, [
        'url_imagen' => $rutaHistorica !== '' ? asset($rutaHistorica) : '',
    ]);
@endphp

{{--
    Configuración única del modo "tamaño de grano". La interfaz visible se inserta dentro
    de las tarjetas creadas por "Número de imágenes a subir" para conservar el flujo conocido.
--}}
<div data-grain-pattern-config>
    <input type="hidden" name="Patron_Grano[activo]" value="{{ $patronActivo ? 1 : 0 }}"
        data-grain-pattern-active>
    <input type="hidden" name="Patron_Grano[id]" value="{{ $idSeleccionado }}"
        data-grain-pattern-id>
    <input type="hidden" name="Patron_Grano[descripcion]" value="{{ $descripcionPatron }}"
        data-grain-pattern-description>
    <input type="hidden" name="Patron_Grano[usar_version_catalogo]"
        value="{{ $usarVersionCatalogo ? 1 : 0 }}" data-grain-pattern-use-current>
    <input type="hidden" name="Patron_Grano[layout][pagina]"
        value="{{ max(1, (int) ($layoutPatron['pagina'] ?? 1)) }}" data-grain-pattern-page>
    <input type="hidden" name="Patron_Grano[layout][posicion]"
        value="{{ $layoutPatron['posicion'] ?? 'abajo_izquierda' }}" data-grain-pattern-position>

    {{-- Los errores permanecen visibles aunque el patrón se represente dentro de una tarjeta dinámica. --}}
    @error('Patron_Grano.id')<div class="alert alert-danger py-2">{{ $message }}</div>@enderror
    @error('Patron_Grano.descripcion')<div class="alert alert-danger py-2">{{ $message }}</div>@enderror

    <script type="application/json" data-grain-pattern-catalog>@json($catalogoPatrones)</script>
    <script type="application/json" data-grain-pattern-historical>@json($patronHistoricoNavegador)</script>
</div>
