document.addEventListener('DOMContentLoaded', () => {
    const payload = document.getElementById('quimica-configuraciones-json');
    if (!payload) return;
    let configuraciones;
    try { configuraciones = JSON.parse(payload.value); } catch { configuraciones = []; }
    const original = JSON.parse(JSON.stringify(configuraciones));
    let actual = 0;
    const selector = document.getElementById('quimica-configuracion');
    const categoria = document.getElementById('quimica-categoria');
    const cuerpo = document.getElementById('quimica-limites');
    const crear = (tag, texto = '', clase = '') => {
        const nodo = document.createElement(tag);
        nodo.textContent = texto;
        nodo.className = clase;
        return nodo;
    };
    const etiqueta = config => [config.producto || 'General', config.ancho, config.espesor, config.grado].filter(Boolean).join(' / ');
    const grupo = config => JSON.stringify([config.producto || 'General', config.ancho || '']);
    const sincronizar = () => {
        payload.value = JSON.stringify(configuraciones);
        [...selector.options].forEach((opcion, i) => opcion.textContent = etiqueta(configuraciones[i]));
        const norma = document.getElementById('norma-nombre').value;
        document.getElementById('quimica-resumen').textContent = configuraciones[actual] ? `${norma} / ${etiqueta(configuraciones[actual])}` : 'Agrega una configuración para capturar sus elementos.';
        consultar();
    };
    const consultar = () => {
        const anterior = categoria.value;
        categoria.replaceChildren();
        const grupos = new Map(configuraciones.map(config => [grupo(config), [config.producto || 'General', config.ancho].filter(Boolean).join(' / ')]));
        grupos.forEach((titulo, clave) => {
            const opcion = crear('option', titulo); opcion.value = clave; categoria.append(opcion);
        });
        if (grupos.has(anterior)) categoria.value = anterior;
        const casos = configuraciones.filter(config => grupo(config) === categoria.value);
        const matriz = document.getElementById('quimica-matriz');
        matriz.replaceChildren();
        if (!casos.length) { matriz.append(crear('p', 'Sin configuraciones.')); return; }
        const tabla = crear('table', '', 'table table-bordered table-sm');
        const encabezado = crear('thead'); const titulos = crear('tr'); titulos.append(crear('th', 'Elemento (%)'));
        casos.forEach(config => { const th = crear('th', [config.espesor || 'Sin clasificación por espesor', config.grado].filter(Boolean).join(' / ')); th.title = config.condiciones || ''; titulos.append(th); });
        encabezado.append(titulos); tabla.append(encabezado);
        const body = crear('tbody');
        const elementos = new Set(casos.flatMap(config => config.elementos.map(fila => fila.elemento)));
        elementos.forEach(simbolo => {
            const row = crear('tr'); row.append(crear('th', simbolo));
            casos.forEach(config => {
                const fila = config.elementos.find(item => item.elemento === simbolo);
                let valor = '—';
                if (fila) {
                    const min = fila.minimo !== null && fila.minimo !== '' && fila.minimo !== undefined;
                    const max = fila.maximo !== null && fila.maximo !== '' && fila.maximo !== undefined;
                    valor = min && max ? `${fila.minimo}–${fila.maximo}` : min ? `${fila.minimo} mín.` : max ? `${fila.maximo} máx.` : '—';
                    if (fila.criterio) valor = valor === '—' ? fila.criterio : `${valor}; ${fila.criterio}`;
                    if (fila.condicion) valor += ` (${fila.condicion})`;
                }
                row.append(crear('td', valor));
            });
            body.append(row);
        });
        tabla.append(body); matriz.append(tabla);
    };
    const dibujar = () => {
        selector.replaceChildren();
        configuraciones.forEach((config, i) => { const option = crear('option', etiqueta(config)); option.value = i; selector.append(option); });
        selector.value = actual;
        const config = configuraciones[actual];
        document.querySelectorAll('[data-config-campo]').forEach(input => { input.value = config?.[input.dataset.configCampo] || ''; input.disabled = !config; });
        cuerpo.replaceChildren();
        (config?.elementos || []).forEach((fila, i) => {
            const row = crear('tr');
            ['elemento', 'minimo', 'maximo', 'criterio', 'condicion'].forEach(campo => {
                const td = crear('td'); const input = crear('input', '', 'form-control form-control-sm');
                input.value = fila[campo] ?? ''; input.setAttribute('aria-label', campo);
                if (campo === 'minimo' || campo === 'maximo') { input.type = 'number'; input.step = 'any'; input.min = 0; input.max = 100; }
                else input.maxLength = campo === 'elemento' ? 100 : 2000;
                input.addEventListener('input', () => { fila[campo] = input.value === '' ? null : input.value; sincronizar(); });
                td.append(input); row.append(td);
            });
            const td = crear('td'); const quitar = crear('button', '', 'btn btn-outline-danger btn-sm'); quitar.type = 'button';
            const icono = crear('i', '', 'fas fa-trash-alt'); icono.setAttribute('aria-hidden', 'true'); quitar.append(icono);
            quitar.title = `Eliminar elemento ${fila.elemento || i + 1}`; quitar.setAttribute('aria-label', quitar.title);
            quitar.addEventListener('click', () => { config.elementos.splice(i, 1); dibujar(); }); td.append(quitar); row.append(td); cuerpo.append(row);
        });
        sincronizar();
    };
    selector.addEventListener('change', () => { actual = Number(selector.value); dibujar(); });
    document.querySelectorAll('[data-config-campo]').forEach(input => input.addEventListener('input', () => {
        if (configuraciones[actual]) { configuraciones[actual][input.dataset.configCampo] = input.value; sincronizar(); }
    }));
    document.getElementById('quimica-nueva').addEventListener('click', () => {
        if (configuraciones.length >= 50) return;
        configuraciones.push({ producto: '', ancho: '', espesor: '', grado: '', condiciones: '', elementos: ['C', 'Mn', 'P', 'S', 'Si', 'Cu'].map(elemento => ({ elemento, minimo: null, maximo: null, criterio: '', condicion: '' })) });
        original.push(JSON.parse(JSON.stringify(configuraciones[configuraciones.length - 1])));
        actual = configuraciones.length - 1; dibujar();
    });
    document.getElementById('quimica-elemento').addEventListener('click', () => {
        const config = configuraciones[actual]; if (!config || config.elementos.length >= 100) return;
        config.elementos.push({ elemento: '', minimo: null, maximo: null, criterio: '', condicion: '' }); dibujar();
    });
    document.getElementById('quimica-duplicar').addEventListener('click', () => {
        if (!configuraciones[actual] || configuraciones.length >= 50) return;
        const copia = JSON.parse(JSON.stringify(configuraciones[actual]));
        copia.espesor = '';
        configuraciones.push(copia);
        original.push(JSON.parse(JSON.stringify(copia)));
        actual = configuraciones.length - 1;
        dibujar();
        document.getElementById('quimica-espesor').focus();
        document.getElementById('quimica-resumen').textContent += ' · Cambia el producto, ancho, espesor o grado para identificar el nuevo caso.';
    });
    document.getElementById('quimica-eliminar').addEventListener('click', () => { configuraciones.splice(actual, 1); original.splice(actual, 1); actual = Math.max(0, actual - 1); dibujar(); });
    document.getElementById('quimica-restablecer').addEventListener('click', () => {
        if (original[actual]) configuraciones[actual] = JSON.parse(JSON.stringify(original[actual]));
        dibujar();
    });
    categoria.addEventListener('change', consultar);
    document.getElementById('norma-nombre').addEventListener('input', sincronizar);
    dibujar();
});
