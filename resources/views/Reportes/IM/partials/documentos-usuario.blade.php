@php
    $documentosQR = old('Datos_Equipo.Documentos_Usuario', $Datos_Equipo['Documentos_Usuario'] ?? ['cv_completo' => 1]);
@endphp
<section class="documentos-qr" aria-labelledby="documentos-qr-titulo">
    <div class="documentos-qr-encabezado">
        <span class="documentos-qr-icono"><i class="fas fa-qrcode" aria-hidden="true"></i></span>
        <div>
            <h5 id="documentos-qr-titulo">Documentos del técnico para el QR</h5>
            <p>Seleccione qué documentos se anexarán al reporte.</p>
        </div>
    </div>
    <div class="documentos-qr-opciones">
        @foreach(['cv_completo' => 'C.V. completo', 'cv_reporte' => 'C.V. para reportes', 'certificados' => 'Certificados aplicables'] as $campo => $etiqueta)
        <div class="documentos-qr-opcion">
            <input type="hidden" name="Datos_Equipo[Documentos_Usuario][{{ $campo }}]" value="0">
            <label class="documentos-qr-seleccion">
                <input type="checkbox" name="Datos_Equipo[Documentos_Usuario][{{ $campo }}]" value="1" @checked(!empty($documentosQR[$campo]))>
                {{ $etiqueta }}
                <i class="{{ $campo === 'certificados' ? 'fas fa-award' : 'far fa-file-pdf' }}" aria-hidden="true"></i>
            </label>
            <p>{{ ['cv_completo' => 'Currículum completo del técnico.', 'cv_reporte' => 'Seleccione el C.V. del técnico que acompañará este reporte.', 'certificados' => 'Todos los certificados que apliquen al servicio.'][$campo] }}</p>
            @if($campo === 'cv_reporte')
        <label class="documentos-qr-tipo" for="tipo-cv-reporte">¿Qué C.V. desea adjuntar?</label>
        <select id="tipo-cv-reporte" name="Datos_Equipo[Documentos_Usuario][tipo_cv_reporte]" class="form-control">
            <option value="general" @selected(($documentosQR['tipo_cv_reporte'] ?? 'general') === 'general')>C.V. general · Todos los servicios</option>
            <option value="servicio" @selected(($documentosQR['tipo_cv_reporte'] ?? '') === 'servicio')>C.V. del servicio · Este reporte</option>
        </select>
            @endif
        </div>
        @endforeach
    </div>
    <div class="documentos-qr-nota">
        <i class="fas fa-info-circle" aria-hidden="true"></i>
        <span>Los archivos se cargan en <strong>Usuarios → Documentos</strong>. Los documentos por servicio deben corresponder al formato {{ $formatoDocumentos ?? 'FOR-PIMP-04/03' }} y al procedimiento seleccionado.</span>
    </div>
        @foreach($errors->getMessages() as $campo => $mensajes)
            @if(str_starts_with($campo, 'Datos_Equipo.Documentos_Usuario'))
                @foreach($mensajes as $mensaje)<div class="text-danger">{{ $mensaje }}</div>@endforeach
            @endif
        @endforeach
</section>
@once
<style>
    .documentos-qr { margin: 24px 0; padding: 22px; border: 1px solid #e2e8f0; border-radius: 10px; background: #fff; }
    .documentos-qr-encabezado { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
    .documentos-qr-icono { display: flex; align-items: center; justify-content: center; flex-shrink: 0; width: 42px; height: 42px; border-radius: 10px; background: #f0f4f9; color: #52657b; font-size: 19px; }
    .documentos-qr .documentos-qr-encabezado h5 { margin: 0 0 4px; font-size: 16px; font-weight: 600; color: #334155; }
    .documentos-qr .documentos-qr-encabezado p { margin: 0; font-size: 13px; color: #64748b; }
    .documentos-qr-opciones { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .documentos-qr-opcion { padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fafbfd; transition: border-color .15s, background .15s; }
    .documentos-qr-opcion:has(input:checked) { border-color: #91aecb; background: #f3f7fb; }
    .documentos-qr .documentos-qr-seleccion { display: flex; align-items: center; gap: 9px; margin: 0 0 10px; color: #334155; font-size: 14px; font-weight: 600; cursor: pointer; }
    .documentos-qr-seleccion input { width: 16px; height: 16px; flex-shrink: 0; margin: 0; accent-color: #526f91; }
    .documentos-qr-seleccion i { margin-left: auto; color: #8798ac; font-size: 18px; }
    .documentos-qr-opcion p { margin: 0; color: #64748b; font-size: 12px; line-height: 1.55; }
    .documentos-qr .documentos-qr-tipo { display: block; margin: 15px 0 6px; font-size: 12px; font-weight: 500; color: #52657b; }
    .documentos-qr .form-control { height: auto; min-height: 36px; padding: 7px 10px; border: 1px solid #d6dfeb; border-radius: 6px; font-size: 13px; background-color: #fff; }
    .documentos-qr-nota { display: flex; align-items: flex-start; gap: 9px; margin-top: 16px; color: #64748b; font-size: 12px; line-height: 1.6; }
    .documentos-qr-nota i { margin-top: 4px; color: #94a3b8; }
    @media (max-width: 991px) { .documentos-qr-opciones { grid-template-columns: 1fr; } }
    @media (max-width: 575px) { .documentos-qr { padding: 16px; } }
</style>
@endonce
