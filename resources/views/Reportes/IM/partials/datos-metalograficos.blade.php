@php
    $datosMetalograficos = is_array($Datos_Equipo ?? null) ? $Datos_Equipo : [];
    $catalogosMetalograficos = is_array($CatalogosMetalografiaIM ?? null) ? $CatalogosMetalografiaIM : [];
    $esEdicionMetalografica = !empty($esEdicionMetalografica ?? false);
    // Los reportes históricos no guardaban las lijas porque eran fijas; Edit conserva esos valores como respaldo.
    $lijasHistoricas = $esEdicionMetalografica ? ['240', '320', '400', '500', '1000', '1500'] : array_fill(0, 6, '');
    $lijasMetalograficas = old(
        'Datos_Equipo.LIJAS_DESBASTE',
        is_array($datosMetalograficos['LIJAS_DESBASTE'] ?? null)
            ? $datosMetalograficos['LIJAS_DESBASTE']
            : $lijasHistoricas
    );
    $lijasMetalograficas = is_array($lijasMetalograficas) ? $lijasMetalograficas : $lijasHistoricas;
    $lijasMetalograficas = array_pad(array_slice($lijasMetalograficas, 0, 6), 6, '');
@endphp

{{-- Bloque único para que 03_B/01, 04_02 y 04_03 compartan campos y comportamiento. --}}
<div class="col-12 px-0" data-datos-metalograficos>
    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">ANÁLISIS METALOGRÁFICO</div>
    <div class="table-responsive mb-3">
        <table class="table table-bordered text-center align-middle mb-0">
            <colgroup>
                <col style="width: 12%;"><col style="width: 10%;"><col style="width: 12%;">
                <col style="width: 10%;"><col style="width: 12%;"><col style="width: 10%;">
                <col style="width: 12%;"><col style="width: 11%;"><col style="width: 11%;">
            </colgroup>
            <thead class="bg-primary text-white">
                <tr>
                    <th colspan="3">NÚMERO DE LIJA PARA EL DESBASTE</th>
                    <th colspan="2">MATERIAL PARA EL PULIDO</th>
                    <th colspan="2">DATOS DE ATAQUE QUÍMICO</th>
                    <th>FASES PRESENTES</th>
                    <th>ESPECIFICACIÓN APROXIMADA DEL MATERIAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @for($indiceLija = 0; $indiceLija < 3; $indiceLija++)
                        <td>
                            @include('Reportes.IM.partials.selector-catalogo-metalografia', [
                                'nombre' => "Datos_Equipo[LIJAS_DESBASTE][$indiceLija]",
                                'valor' => $lijasMetalograficas[$indiceLija],
                                'opciones' => $catalogosMetalograficos['lijas'] ?? [],
                                'etiqueta' => 'Número de lija ' . ($indiceLija + 1),
                                'textoVacio' => 'Lija',
                                'textoNuevo' => 'Número de lija',
                                'maximo' => 50,
                            ])
                        </td>
                    @endfor
                    <th class="bg-light">PAÑO</th>
                    <td><input type="text" class="form-control text-center" name="Datos_Equipo[MATERIAL_PANO]" value="{{ old('Datos_Equipo.MATERIAL_PANO', $datosMetalograficos['MATERIAL_PANO'] ?? '') }}"></td>
                    <th class="bg-light">REACTIVO</th>
                    <td>
                        @include('Reportes.IM.partials.selector-catalogo-metalografia', [
                            'nombre' => 'Datos_Equipo[REACTIVO]',
                            'valor' => old('Datos_Equipo.REACTIVO', $datosMetalograficos['REACTIVO'] ?? ''),
                            'opciones' => $catalogosMetalograficos['reactivos'] ?? [],
                            'etiqueta' => 'Reactivo',
                            'textoVacio' => 'Seleccione reactivo',
                            'textoNuevo' => 'Escriba el reactivo',
                        ])
                    </td>
                    <td rowspan="2">
                        @include('Reportes.IM.partials.selector-catalogo-metalografia', [
                            'nombre' => 'Datos_Equipo[FASES_PRESENTES]',
                            'valor' => old('Datos_Equipo.FASES_PRESENTES', $datosMetalograficos['FASES_PRESENTES'] ?? ''),
                            'opciones' => $catalogosMetalograficos['fases'] ?? [],
                            'etiqueta' => 'Fases presentes',
                            'textoVacio' => 'Seleccione fases',
                            'textoNuevo' => 'Escriba las fases presentes',
                            'maximo' => 1000,
                        ])
                    </td>
                    <td rowspan="2"><textarea class="form-control text-center h-100" rows="3" name="Datos_Equipo[ESPECIFICACION_MATERIAL]">{{ old('Datos_Equipo.ESPECIFICACION_MATERIAL', $datosMetalograficos['ESPECIFICACION_MATERIAL'] ?? '') }}</textarea></td>
                </tr>
                <tr>
                    @for($indiceLija = 3; $indiceLija < 6; $indiceLija++)
                        <td>
                            @include('Reportes.IM.partials.selector-catalogo-metalografia', [
                                'nombre' => "Datos_Equipo[LIJAS_DESBASTE][$indiceLija]",
                                'valor' => $lijasMetalograficas[$indiceLija],
                                'opciones' => $catalogosMetalograficos['lijas'] ?? [],
                                'etiqueta' => 'Número de lija ' . ($indiceLija + 1),
                                'textoVacio' => 'Lija',
                                'textoNuevo' => 'Número de lija',
                                'maximo' => 50,
                            ])
                        </td>
                    @endfor
                    <th class="bg-light">ABRASIVO</th>
                    <td>
                        @include('Reportes.IM.partials.selector-catalogo-metalografia', [
                            'nombre' => 'Datos_Equipo[MATERIAL_ABRASIVO]',
                            'valor' => old('Datos_Equipo.MATERIAL_ABRASIVO', $datosMetalograficos['MATERIAL_ABRASIVO'] ?? ''),
                            'opciones' => $catalogosMetalograficos['abrasivos'] ?? [],
                            'etiqueta' => 'Abrasivo',
                            'textoVacio' => 'Seleccione abrasivo',
                            'textoNuevo' => 'Escriba el abrasivo',
                        ])
                    </td>
                    <th class="bg-light">TIEMPO</th>
                    <td><input type="text" class="form-control text-center" name="Datos_Equipo[TIEMPO_ATAQUE]" value="{{ old('Datos_Equipo.TIEMPO_ATAQUE', $datosMetalograficos['TIEMPO_ATAQUE'] ?? '') }}"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@once
    <script src="{{ asset('js/catalogos-metalografia-im.js') }}?v={{ filemtime(public_path('js/catalogos-metalografia-im.js')) }}" defer></script>
@endonce
