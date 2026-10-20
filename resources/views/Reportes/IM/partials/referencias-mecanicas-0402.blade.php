{{--
    Inicializa el selector de referencias mecánicas para Crear y Editar del 04_02 y 04_03.
    referenciasFormId identifica el formulario; los nombres terminados en 0402 se conservan
    porque son el contrato compartido con JavaScript y los controladores.
    old() recupera la selección del técnico cuando el formulario vuelve por validación.
--}}
@php
    $modoReferencias = old('Modo_Referencias_0402', $Datos_Equipo['MODO_REFERENCIAS_0402'] ?? 'automatico');
    $referenciasManuales = ($Datos_Equipo['MODO_REFERENCIAS_0402'] ?? '') === 'manual' ? ($Datos_Equipo['NORMAS_REFERENCIA_COINCIDENCIAS'] ?? []) : [];
    // Conserva la referencia de reportes 04_03 creados antes del selector múltiple.
    if (($referenciasFormId ?? '') === 'FOR-PIMP-04_03' && !array_key_exists('NORMAS_REFERENCIA_COINCIDENCIAS', $Datos_Equipo ?? []) && !empty($Datos_Equipo['NORMA_REFERENCIA'])) {
        $modoReferencias = old('Modo_Referencias_0402', 'manual');
        $referenciasManuales = [[
            'norma' => $Datos_Equipo['NORMA_REFERENCIA'], 'dureza_max' => $Datos_Equipo['DUREZA_BRINELL_MAX'] ?? '',
            'tension_min' => $Datos_Equipo['RESISTENCIA_TENSION_MIN'] ?? '', 'cedencia_min' => $Datos_Equipo['RESISTENCIA_CEDENCIA_ESPECIFICADA'] ?? '',
            'tension_max' => $Datos_Equipo['RESISTENCIA_TENSION_MAX'] ?? '',
        ]];
    }
    $referenciasSeleccionadas = ($Datos_Equipo['MODO_REFERENCIAS_0402'] ?? '') !== 'manual'
        ? array_values(array_map(fn ($fila) => $fila['idnormas_im'].':'.$fila['fila'], array_filter($Datos_Equipo['NORMAS_REFERENCIA_COINCIDENCIAS'] ?? [], fn ($fila) => isset($fila['idnormas_im'], $fila['fila'])))) : [];
@endphp
@php
    $escalasReferencias = [];
    $composicionesReferencias = [];
    foreach (($Datos_Equipo['NORMAS_REFERENCIA_COINCIDENCIAS'] ?? []) as $referencia) {
        if (isset($referencia['idnormas_im'], $referencia['fila'])) $escalasReferencias[$referencia['idnormas_im'].':'.$referencia['fila']] = $referencia['escala_dureza'] ?? '';
        if (isset($referencia['idnormas_im'], $referencia['fila'])) $composicionesReferencias[$referencia['idnormas_im'].':'.$referencia['fila']] = $referencia['composicion_seleccionada']['configuracion'] ?? '';
    }
@endphp
<script type="application/json" id="referencias-manuales-0402-inicial">{!! json_encode(['composiciones' => old('Composiciones_Referencias_0402', $composicionesReferencias), 'escalas' => old('Escalas_Referencias_0402', $escalasReferencias), 'modo' => $modoReferencias, 'filas' => old('Referencias_Manuales_0402', json_encode($referenciasManuales)), 'seleccion' => old('Referencias_Seleccionadas_0402', json_encode($referenciasSeleccionadas))], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
<script src="{{ asset('js/referencias-mecanicas-0402.js') }}?v={{ filemtime(public_path('js/referencias-mecanicas-0402.js')) }}" data-form-id="{{ $referenciasFormId ?? 'FOR-PIMP-04_02' }}" data-coincidencias-url="{{ route('Reportes_FOR_PIMP_04_02.coincidencias_mecanicas') }}" data-crear-norma-url="{{ route('Normas_IM.Create') }}" data-editar-norma-url="{{ route('Normas_IM.Edit', ['id' => '__NORMA__']) }}"></script>
