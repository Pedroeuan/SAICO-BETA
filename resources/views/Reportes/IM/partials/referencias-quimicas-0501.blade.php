{{-- Solo composiciones químicas: admite varias normas y variantes, sin exigir datos de dureza. --}}
@php
    $opcionesQuimicas0501 = \App\Services\ReferenciasQuimicas0501::catalogo(\App\Models\Normas_IM\Normas_IM::orderBy('Nombre_Espe')->get());
    $historicaQuimica0501 = $Detalles_Generales['Norma_IM'] ?? [];
    $seleccionQuimica0501 = old('Norma_IM.Seleccion_Quimica', $historicaQuimica0501['Seleccion_Quimica'] ?? []);
    foreach (($historicaQuimica0501['Referencias_Quimicas'] ?? []) as $guardadaQuimica) {
        if (!in_array($guardadaQuimica['clave'], array_column($opcionesQuimicas0501, 'clave'), true)) $opcionesQuimicas0501[] = $guardadaQuimica;
    }
    if (!old('Norma_IM.seleccion_multiple') && !array_key_exists('Seleccion_Quimica', $historicaQuimica0501) && !empty($historicaQuimica0501['idnormas_im'])) {
        $claveHistorica0501 = $historicaQuimica0501['idnormas_im'] . ':base';
        // El reporte antiguo conserva su tabla aunque el catálogo tenga ahora otras configuraciones.
        $opcionesQuimicas0501 = array_values(array_filter($opcionesQuimicas0501, fn ($opcion) => $opcion['clave'] !== $claveHistorica0501));
        $opcionesQuimicas0501[] = ['clave' => $claveHistorica0501, 'idnormas_im' => $historicaQuimica0501['idnormas_im'], 'norma' => $historicaQuimica0501['Nombre_Espe'] ?? 'Norma guardada', 'composicion_seleccionada' => $historicaQuimica0501];
        $seleccionQuimica0501 = [$claveHistorica0501];
    }
@endphp
<div class="col-12 mb-3" data-referencias-quimicas-0501>
    <div class="quimica-panel">
    <input type="hidden" name="Norma_IM[seleccion_multiple]" value="1">
    <div class="quimica-cabecera">
        <div>
            <label class="font-weight-bold mb-1" for="buscarNormasQuimicas0501">Normas de composición química</label>
            <small class="d-block text-muted">Selecciona las referencias para comparar los elementos de la pieza.</small>
        </div>
        {{-- La nueva pestaña conserva el reporte que el técnico está capturando. --}}
        <a class="btn btn-outline-primary btn-sm" href="{{ route('Normas_IM.Create') }}" target="_blank" rel="noopener">
            <i class="fas fa-plus mr-1" aria-hidden="true"></i> Crear norma
        </a>
    </div>
    <div class="quimica-busqueda">
        <i class="fas fa-search" aria-hidden="true"></i>
        <input type="search" class="form-control" id="buscarNormasQuimicas0501" placeholder="Buscar norma, grado o variante">
    </div>
    <div class="quimica-subtitulo">Referencias seleccionadas <span data-quimica-total class="badge badge-light">0</span></div>
    <div data-quimica-seleccionadas class="quimica-seleccionadas"></div>
    <div class="quimica-subtitulo">Normas disponibles <small class="text-muted font-weight-normal">Puedes marcar varias</small></div>
    <div data-quimica-opciones class="quimica-opciones"></div>
    @error('Norma_IM.Seleccion_Quimica')<div class="text-danger">{{ $message }}</div>@enderror
    <script type="application/json" data-quimica-config>@json(['opciones' => $opcionesQuimicas0501, 'seleccion' => $seleccionQuimica0501, 'analisis' => $historicaQuimica0501['Analisis_PDF'] ?? []])</script>
    </div>
</div>
{{-- Selector y estilos comunes del 05 y 06: se incluyen una sola vez por formulario. --}}
@once
<style>
/* Diseño compartido por Crear y Editar; no modifica los nombres enviados al controlador. */
    [data-referencias-quimicas-0501] 
    .quimica-panel { 
        border:1px solid #dce3ed;
        border-radius:10px;
        padding:16px;
        background:#fff; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-cabecera { 
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:14px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-cabecera .btn { 
        white-space:nowrap;
        border-radius:6px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-busqueda { 
        position:relative;
        margin-bottom:14px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-busqueda i { 
        position:absolute;
        left:12px;
        top:50%;
        transform:translateY(-50%);
        color:#7b8799;
        pointer-events:none; }
    [data-referencias-quimicas-0501] 
    .quimica-busqueda input { 
        padding-left:35px;
        border-radius:7px;
        background:#f8fafc; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-subtitulo { 
        display:flex;
        align-items:center;
        gap:8px;
        font-size:12px;
        font-weight:600;
        color:#475569;
        margin:12px 0 8px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-seleccionadas { 
        display:flex;
        flex-wrap:wrap;
        gap:8px;min-height:32px; }
    [data-referencias-quimicas-0501] 
    .quimica-etiqueta { 
        border:1px solid #dce3ed;
        border-left:4px solid currentColor;
        border-radius:6px;
        padding:7px 10px;
        font-size:12px;
        font-weight:600;
        background:#f8fafc; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opciones { 
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:8px;
        max-height:230px;
        overflow:auto;
        padding:2px 5px 2px 0; 
    }
    [data-referencias-quimicas-0501] .quimica-opcion { 
        display:flex;
        align-items:center;
        gap:10px;
        border:1px solid #e2e8f0;
        border-radius:7px;
        padding:10px 12px;
        margin:0;cursor:pointer;
        font-weight:500;
        min-width:0; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opcion:hover { 
        background:#f4f8ff;border-color:#9abcf0; }
    [data-referencias-quimicas-0501] 
    .quimica-opcion.is-selected { 
        background:#eff6ff;
        border-color:#93b7ec; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opcion:focus-within { 
        outline:2px solid #93b7ec;
        outline-offset:1px; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opcion input { 
        flex:0 0 auto;
        margin:0;
        accent-color:#1769d2; 
    }
    [data-referencias-quimicas-0501] 
    .quimica-opcion[hidden] { 
        display:none; 
    }
    @media(max-width:650px) { [data-referencias-quimicas-0501] .quimica-opciones { grid-template-columns:1fr; } }

</style>
<script>
window.iniciarReferenciasQuimicasIM = function (form, alCambiar) {
    // El 05_01 selecciona composiciones químicas directamente, sin catálogo mecánico.
    let referenciasQuimicas0501 = [];
    const panelQuimico = form.querySelector('[data-referencias-quimicas-0501]');
    let restaurarQuimicas = null;
    if (panelQuimico) {
        const configuracion = JSON.parse(panelQuimico.querySelector('[data-quimica-config]').textContent);
        const opciones = configuracion.opciones || [];
        let seleccion = [...(configuracion.seleccion || [])];
        const lista = panelQuimico.querySelector('[data-quimica-opciones]');
        const marcadas = panelQuimico.querySelector('[data-quimica-seleccionadas]');
        const colores = ['#111111', '#1565c0', '#c62828', '#6a1b9a', '#2e7d32', '#ad5700'];
        // Un solo valor estable permite recuperar el orden exacto del borrador local.
        const orden = document.createElement('input'); orden.type = 'hidden';
        orden.name = 'Norma_IM[Seleccion_Quimica_Orden]'; panelQuimico.append(orden);
        function sincronizarQuimicas() {
            orden.value = JSON.stringify(seleccion);
            referenciasQuimicas0501 = seleccion.map(clave => opciones.find(item => item.clave === clave)).filter(Boolean);
            marcadas.replaceChildren();
            panelQuimico.querySelector('[data-quimica-total]').textContent = referenciasQuimicas0501.length;
            lista.querySelectorAll('[data-clave-quimica]').forEach(check => {
                check.checked = seleccion.includes(check.dataset.claveQuimica);
                check.closest('label').classList.toggle('is-selected', seleccion.includes(check.dataset.claveQuimica));
            });
            if (!referenciasQuimicas0501.length) {
                const ayuda = document.createElement('small'); ayuda.className = 'text-muted';
                ayuda.textContent = 'Marca una norma de la lista para comenzar.'; marcadas.append(ayuda);
            }
            referenciasQuimicas0501.forEach((item, indice) => {
                const etiqueta = document.createElement('div');
                etiqueta.style.color = colores[indice % colores.length];
                etiqueta.className = 'quimica-etiqueta';
                etiqueta.textContent = (indice + 1) + '. ' + item.norma;
                const campo = document.createElement('input');
                campo.type = 'hidden'; campo.name = 'Norma_IM[Seleccion_Quimica][]'; campo.value = item.clave;
                marcadas.append(etiqueta, campo);
            });
            alCambiar(referenciasQuimicas0501);
        }
        opciones.forEach(item => {
            const etiqueta = document.createElement('label'); etiqueta.className = 'quimica-opcion';
            const check = document.createElement('input'); check.type = 'checkbox';
            check.autocomplete = 'off';
            check.dataset.claveQuimica = item.clave;
            check.checked = seleccion.includes(item.clave);
            check.addEventListener('change', () => {
                seleccion = seleccion.filter(clave => clave !== item.clave);
                if (check.checked) seleccion.push(item.clave);
                sincronizarQuimicas(); form.dispatchEvent(new Event('change', {bubbles: true}));
            });
            etiqueta.append(check, document.createTextNode(item.norma)); lista.append(etiqueta);
        });
        if (!opciones.length) lista.textContent = 'No hay normas con composición química registrada.';
        panelQuimico.querySelector('input[type="search"]').addEventListener('input', function () {
            const texto = this.value.trim().toLocaleLowerCase();
            Array.from(lista.children).forEach(item => { item.hidden = !item.textContent.toLocaleLowerCase().includes(texto); });
        });
        sincronizarQuimicas();
        restaurarQuimicas = function () {
            try {
                seleccion = JSON.parse(orden.value).filter(clave => opciones.some(item => item.clave === clave));
            } catch (error) { return; }
            lista.querySelectorAll('[data-clave-quimica]').forEach(check => { check.checked = seleccion.includes(check.dataset.claveQuimica); });
            sincronizarQuimicas();
        };
        // Al volver con Atrás, el navegador puede restaurar checks sin disparar change.
        window.addEventListener('pageshow', restaurarQuimicas);
    }

    return restaurarQuimicas;
};

// Adaptador del 06: reutiliza el selector multiple comun sin cambiar su flujo de varios PDF.
document.addEventListener('DOMContentLoaded', function () {
    // Espera a que los formularios restauren sus valores iniciales antes de enlazar el 06.
    queueMicrotask(function () {
    const form = document.getElementById('FOR-PIMP-06_B_01');
    if (!form || !window.iniciarReferenciasQuimicasIM) return;
    const registro = form.querySelector('#normaIMRegistro');
    const cuerpo = form.querySelector('#tablaNormaIM tbody');
    const colores = ['#111111', '#1565c0', '#c62828', '#6a1b9a', '#2e7d32', '#ad5700'];
    const valores = new Map();
    let referencias = [];
    let analisis = JSON.parse(form.querySelector('[data-quimica-config]').textContent).analisis || [];
    const clave = elemento => String(elemento || '').trim().toLowerCase();
    const recordar = () => cuerpo.querySelectorAll('input[data-elemento]').forEach(input => valores.set(clave(input.dataset.elemento), input.value));
    function dibujar(recalcular = false) {
        recordar();
        const filas = new Map();
        referencias.forEach((ref, indice) => (ref.composicion_seleccionada?.Tabla || []).forEach(fila => {
            const id = clave(fila.Elemento);
            if (!filas.has(id)) filas.set(id, {elemento: fila.Elemento, limites: []});
            filas.get(id).limites.push({indice, valor: fila.Composicion || '', norma: ref.norma});
        }));
        cuerpo.replaceChildren();
        Array.from(filas.entries()).forEach(([id, fila], indice) => {
            const tr = document.createElement('tr');
            const elemento = document.createElement('td'); elemento.textContent = fila.elemento;
            const promedio = document.createElement('td');
            const input = document.createElement('input'); input.type = 'text'; input.className = 'form-control text-center';
            input.name = 'Norma_IM[Promedio][' + indice + ']'; input.dataset.elemento = fila.elemento;
            input.value = valores.get(id) || 'ND';
            if ((recalcular || !valores.has(id)) && analisis.length) {
                input.value = 'ND';
                const lecturas = analisis.map(item => Object.values(item.lecturas || {}).find(valor => clave(valor.elemento) === id));
                if (lecturas.every(valor => valor && valor.calificador == null && Number.isFinite(Number(valor.valor)))) input.value = (lecturas.reduce((suma, valor) => suma + Number(valor.valor), 0) / lecturas.length).toFixed(4);
            }
            promedio.append(input);
            const limites = document.createElement('td');
            fila.limites.forEach((limite, pos) => {
                if (pos) limites.append(document.createTextNode(' / '));
                const texto = document.createElement('span'); texto.style.color = colores[limite.indice % colores.length]; texto.style.fontWeight = '600';
                texto.textContent = limite.valor; texto.title = limite.norma; limites.append(texto);
            });
            tr.append(elemento, promedio, limites); cuerpo.append(tr);
        });
        form.querySelector('#normaIMResultadosContainer').classList.toggle('d-none', !filas.size);
        const observaciones = form.querySelector('#normaIMObservaciones'); observaciones.replaceChildren();
        referencias.forEach((ref, indice) => {
            const nota = document.createElement('div'); nota.style.color = colores[indice % colores.length];
            nota.textContent = ref.composicion_seleccionada?.Observaciones || '';
            if (nota.textContent) observaciones.append(nota);
        });
        form.querySelector('#normaIMObservacionesContainer').classList.toggle('d-none', !observaciones.childElementCount);
    }
    window.iniciarReferenciasQuimicasIM(form, function (seleccion) {
        referencias = seleccion;
        registro.replaceChildren(new Option(seleccion[0]?.norma || '', seleccion[0]?.idnormas_im || ''));
        registro.disabled = false;
        dibujar();
    });
    form.addEventListener('saico:xrf-analizado', function (evento) {
        analisis = evento.detail || [];
        recordar();
        dibujar(true);
    });
    });
});

</script>
@endonce
