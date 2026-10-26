<section class="usuario-documentos" aria-label="C.V. para reportes y certificados">
    <div class="usuario-documentos-encabezado">
        <div>
            <h6>C.V. para reportes y certificados</h6>
            <p>Documentación disponible para anexar a los reportes.</p>
        </div>
        <button type="button" class="usuario-boton-archivo" id="agregar-documento-reporte"><i class="fas fa-plus" aria-hidden="true"></i> Agregar C.V. o certificado</button>
    </div>
    <div>
        @if(!empty($Usuario->documentos_reportes ?? []))
        <details class="usuario-pdf-desplegable" @if(old('documentos_eliminar')) open @endif>
            <summary>
                <i class="far fa-folder-open" aria-hidden="true"></i>
                <span>PDF cargados <span class="usuario-pdf-contador">{{ count($Usuario->documentos_reportes) }}</span></span>
                <i class="fas fa-chevron-down usuario-pdf-flecha" aria-hidden="true"></i>
            </summary>
            <div class="usuario-pdf-lista" role="region" aria-label="PDF cargados" tabindex="0">
        @foreach(($Usuario->documentos_reportes ?? []) as $documento)
            <div class="border rounded p-3 mb-2 usuario-documento-guardado">
                <div class="usuario-documento-titulo">
                    <strong><i class="far fa-file-pdf" aria-hidden="true"></i> {{ $documento['nombre'] }}</strong>
                    <a class="usuario-boton-archivo" href="{{ asset('storage/'.$documento['pdf']) }}" target="_blank" rel="noopener" aria-label="Ver PDF: {{ $documento['nombre'] }}"><i class="far fa-eye" aria-hidden="true"></i> Ver PDF</a>
                </div>
                <span>{{ ['cv_general' => 'C.V. general', 'cv_servicio' => 'C.V. por servicio', 'certificado' => 'Certificado'][$documento['tipo']] ?? '' }}</span>
                <span>{{ $documento['formato'] ?? 'Todos los formatos' }}</span>
                @if(!empty($documento['procedimiento_id']))
                    <span>Procedimiento: {{ $procedimientosDocumentos->firstWhere('idProcedimiento', $documento['procedimiento_id'])?->Nombre ?? $documento['procedimiento_id'] }}</span>
                @endif
                <label><input type="checkbox" name="documentos_eliminar[]" value="{{ $documento['id'] }}" @checked(in_array($documento['id'], old('documentos_eliminar', [])))> Retirar del usuario</label>
            </div>
        @endforeach
            </div>
        </details>
        @endif
        @if(empty($Usuario->documentos_reportes ?? []))
            <div class="usuario-documentos-vacio" id="documentos-reportes-vacio">
                <i class="far fa-folder-open" aria-hidden="true"></i>
                <span>Aún no hay documentos para reportes.<small>Agregue un C.V. general, uno por servicio o un certificado.</small></span>
            </div>
        @endif
        <div id="documentos-reportes-nuevos"></div>
        <small class="usuario-documentos-nota"><i class="fas fa-info-circle" aria-hidden="true"></i> Solo PDF · Hasta 20 MB por archivo</small>
        @foreach($errors->getMessages() as $campo => $mensajes)
            @if(str_starts_with($campo, 'documentos_') || $campo === 'cv_pdf')
                @foreach($mensajes as $mensaje)<div class="text-danger">{{ $mensaje }}</div>@endforeach
            @endif
        @endforeach
        @if($errors->any())
            <small class="d-block">Vuelva a seleccionar los archivos nuevos antes de guardar.</small>
        @endif
    </div>
</section>
<template id="plantilla-documento-reporte">
    <div class="border rounded p-3 mb-3 usuario-documento-nuevo">
        <small class="d-block mb-3">Seleccione el servicio y suba su PDF. El nombre y la referencia del formato se asignan automáticamente.</small>
        <div class="usuario-campos">
        <div>
        <label>Tipo de documento</label>
        <select name="documentos_nuevos[__indice__][tipo]" class="form-control" data-documento-tipo required>
            <option value="cv_general">C.V. general para reportes</option>
            <option value="cv_servicio">C.V. por servicio</option>
            <option value="certificado">Certificado</option>
        </select>
        </div>
        <div data-documento-vinculo hidden>
        <label>Servicio / formato</label>
        <select name="documentos_nuevos[__indice__][formato_id]" class="form-control" data-documento-servicio disabled>
            <option value="">Seleccione un servicio</option>
            @foreach(($serviciosDocumentos ?? collect()) as $servicioDocumento)
                <option value="{{ $servicioDocumento->idFormato }}" data-procedimiento="{{ $servicioDocumento->idProcedimiento }}">{{ $servicioDocumento->servicio }} · {{ $servicioDocumento->Nombre }}@if($servicioDocumento->idProcedimiento) · {{ $procedimientosDocumentos->firstWhere('idProcedimiento', $servicioDocumento->idProcedimiento)?->Nombre }}@endif</option>
            @endforeach
        </select>
        </div>
        <div data-documento-vinculo hidden>
        <label>Procedimiento</label>
        <select name="documentos_nuevos[__indice__][procedimiento_id]" class="form-control" data-documento-procedimiento disabled>
            <option value="">Todos los procedimientos</option>
            @foreach($procedimientosDocumentos as $procedimientoDocumento)
                <option value="{{ $procedimientoDocumento->idProcedimiento }}">{{ $procedimientoDocumento->Nombre }}</option>
            @endforeach
        </select>
        <small>Se asigna el procedimiento del servicio seleccionado.</small>
        </div>
        <div class="usuario-campo-archivo">
        <label>Documento PDF</label>
        <input type="file" name="documentos_nuevos[__indice__][pdf]" accept="application/pdf" class="form-control" required>
        </div>
        </div>
        <button type="button" class="btn btn-outline-danger mt-3" data-quitar-documento>Quitar</button>
    </div>
</template>
<script>
(() => {
    let indice = 0;
    const nuevos = document.getElementById('documentos-reportes-nuevos');
    const vacio = document.getElementById('documentos-reportes-vacio');
    const actualizarVinculo = tarjeta => {
        const tipo = tarjeta.querySelector('[data-documento-tipo]').value;
        const servicio = tarjeta.querySelector('[data-documento-servicio]');
        const procedimiento = tarjeta.querySelector('[data-documento-procedimiento]');
        const general = tipo === 'cv_general';
        tarjeta.querySelectorAll('[data-documento-vinculo]').forEach(campo => campo.hidden = general);
        servicio.disabled = general;
        servicio.required = tipo === 'cv_servicio';
        const asignado = general ? '' : servicio.selectedOptions[0]?.dataset.procedimiento || '';
        procedimiento.value = asignado;
        procedimiento.disabled = general || Boolean(asignado);
        if (general) { servicio.value = ''; procedimiento.value = ''; }
    };
    nuevos.addEventListener('change', event => {
        if (event.target.matches('[data-documento-tipo], [data-documento-servicio]')) {
            actualizarVinculo(event.target.closest('.usuario-documento-nuevo'));
        }
    });
    document.getElementById('agregar-documento-reporte').addEventListener('click', () => {
        nuevos.insertAdjacentHTML('beforeend',
            document.getElementById('plantilla-documento-reporte').innerHTML.replaceAll('__indice__', indice++));
        if (vacio) vacio.hidden = true;
        nuevos.lastElementChild.querySelector('select').focus();
    });
    nuevos.addEventListener('click', event => {
        const quitar = event.target.closest('[data-quitar-documento]');
        if (!quitar) return;
        quitar.closest('.usuario-documento-nuevo').remove();
        if (vacio) vacio.hidden = nuevos.childElementCount > 0;
    });
})();
</script>
