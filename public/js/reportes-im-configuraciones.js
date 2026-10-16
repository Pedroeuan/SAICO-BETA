window.configurarComposicionesReporte = (catalogo, historica, anterior, anteriorNorma) => {
    const registro = document.getElementById('normaIMRegistro');
    const nombre = document.getElementById('normaIMNombre');
    if (!registro) return catalogo;
    const contenedor = document.createElement('div');
    contenedor.className = 'col-12 col-lg-4 mb-3 seleccion-norma-0402';
    const label = document.createElement('label'); label.innerHTML = '<i class="fas fa-flask mr-1" aria-hidden="true"></i>Configuración química'; label.htmlFor = 'normaIMConfiguracion';
    const selector = document.createElement('select'); selector.className = 'form-control'; selector.id = 'normaIMConfiguracion'; selector.name = 'Norma_IM[configuracion]';
    const cambio = document.createElement('input'); cambio.type = 'hidden'; cambio.name = 'Norma_IM[configuracion_cambiada]'; cambio.value = '0';
    const ayuda = document.createElement('small'); ayuda.className = 'd-block text-muted mt-1'; ayuda.textContent = 'Las opciones pertenecen únicamente a la norma y al registro seleccionados.';
    contenedor.append(label, selector, cambio, ayuda); registro.closest('.col-sm-6, .col-md-6').after(contenedor);
    [nombre, registro].forEach(campo => {
        const columna = campo.closest('.col-sm-6, .col-md-6');
        columna.classList.remove('col-sm-6', 'col-md-6');
        columna.classList.add('col-12', 'col-lg-4', 'seleccion-norma-0402');
    });
    const estilo = document.createElement('style');
    estilo.textContent = '.seleccion-norma-0402 label{display:block;min-height:24px;font-weight:600;color:#334155}.seleccion-norma-0402 .form-control{border-radius:7px;min-height:40px}.acciones-norma-0402{display:flex;flex-wrap:wrap;gap:10px;align-items:center;padding:12px 15px;margin-bottom:16px;background:#f5f8fc;border:1px solid #e0e8f0;border-radius:8px}.acciones-norma-0402 .btn{border-radius:6px;padding:8px 13px}';
    document.head.append(estilo);
    const acciones = document.createElement('div'); acciones.className = 'col-12';
    const barra = document.createElement('div'); barra.className = 'acciones-norma-0402';
    const nueva = document.createElement('a'); nueva.className = 'btn btn-success btn-sm'; nueva.target = '_blank'; nueva.rel = 'noopener';
    nueva.innerHTML = '<i class="fas fa-plus-circle mr-1" aria-hidden="true"></i>Crear norma y composición';
    const editar = document.createElement('a'); editar.className = 'btn btn-outline-primary btn-sm'; editar.target = '_blank'; editar.rel = 'noopener';
    editar.innerHTML = '<i class="fas fa-edit mr-1" aria-hidden="true"></i>Editar composición de esta norma';
    const nota = document.createElement('small'); nota.className = 'text-muted'; nota.textContent = 'Abre el catálogo en otra pestaña. Después de guardar, actualiza este formato para cargar los cambios.';
    barra.append(nueva, editar, nota); acciones.append(barra); contenedor.after(acciones);
    const tituloRegistro = document.querySelector('label[for="normaIMRegistro"]');
    if (tituloRegistro) tituloRegistro.innerHTML = '<i class="fas fa-layer-group mr-1" aria-hidden="true"></i>Grado / variante';
    const tituloNorma = document.querySelector('label[for="normaIMNombre"]');
    if (tituloNorma) tituloNorma.innerHTML = '<i class="fas fa-book mr-1" aria-hidden="true"></i>Norma / especificación';
    const originales = new Map(catalogo.map(norma => [String(norma.idnormas_im), { ...norma }]));
    let cargado = '';
    let restaurado = false;
    const cargar = () => {
        const norma = catalogo.find(n => String(n.idnormas_im) === registro.value && n.Nombre_Espe === nombre.value);
        const opciones = norma?.Configuraciones || [];
        const clave = registro.value;
        if (cargado !== clave || !norma) {
            selector.replaceChildren(new Option(opciones.length ? 'Seleccione un grado o configuración' : 'Sin configuraciones químicas registradas', ''));
            opciones.forEach(opcion => selector.add(new Option(opcion.Variable, opcion.configuracion)));
            const inicial = !restaurado && String(anteriorNorma ?? historica?.idnormas_im) === clave ? anterior ?? historica?.configuracion : null;
            if (inicial !== null && inicial !== undefined) {
                if (![...selector.options].some(o => o.value === String(inicial))) selector.add(new Option(historica.Variable || 'Configuración histórica', inicial));
                selector.value = inicial;
                restaurado = true;
            }
            cargado = clave;
        }
        const disponible = norma && (opciones.length || (String(historica?.idnormas_im) === clave && historica?.configuracion !== undefined));
        contenedor.hidden = false;
        selector.disabled = !disponible;
        editar.hidden = !norma;
        if (norma && window.normasIMEdicionUrl) editar.href = window.normasIMEdicionUrl.replace('__NORMA__', encodeURIComponent(clave));
        if (!norma) return;
        if (!originales.has(clave)) originales.set(clave, { ...norma });
        const seleccion = opciones.find(o => String(o.configuracion) === selector.value);
        const guardada = cambio.value === '0' && String(historica?.idnormas_im) === clave && String(historica?.configuracion) === selector.value ? historica : null;
        Object.assign(norma, guardada || seleccion || originales.get(clave));
    };
    registro.addEventListener('change', cargar, true);
    nombre.addEventListener('change', () => {
        catalogo.forEach(norma => { if (originales.has(String(norma.idnormas_im))) Object.assign(norma, originales.get(String(norma.idnormas_im))); });
        cargado = '';
        cambio.value = '1';
        selector.replaceChildren();
        selector.disabled = true;
        contenedor.hidden = false;
        setTimeout(cargar, 0);
    }, true);
    document.addEventListener('norma-im:creada', () => {
        cargado = ''; cambio.value = '1';
        setTimeout(cargar, 0);
    });
    selector.addEventListener('change', () => { cambio.value = '1'; cargar(); registro.dispatchEvent(new Event('change', { bubbles: true })); });
    setTimeout(() => {
        nueva.href = window.normasIMFormularioUrl || '#';
        const crear = document.getElementById('abrirAltaRapidaNormaIM');
        if (crear) {
            crear.innerHTML = '<i class="fas fa-plus mr-1" aria-hidden="true"></i>Crear norma con composición';
            crear.title = 'Registrar una norma y sus elementos sin salir del formato 04_02';
            crear.className = 'btn btn-outline-success btn-sm mt-2';
        }
        cargar();
        if (selector.value !== '') registro.dispatchEvent(new Event('change', { bubbles: true }));
    }, 0);
    return catalogo;
};
