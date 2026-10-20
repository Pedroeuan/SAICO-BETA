(() => {
    const url = document.currentScript.dataset.coincidenciasUrl;
    const crearNormaUrl = document.currentScript.dataset.crearNormaUrl;
    const editarNormaUrl = document.currentScript.dataset.editarNormaUrl;
    const formularioId = document.currentScript.dataset.formId || 'FOR-PIMP-04_02';
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById(formularioId);
        if (!form) return;
        const campo = nombre => form.querySelector(`[name="Datos_Equipo[${nombre}]"]`);
        const tabla = campo('NORMA_REFERENCIA')?.closest('table');
        if (!tabla) return;
        const cuerpo = tabla.querySelector('tbody');
        const estado = document.createElement('p'); estado.className = 'text-muted my-2'; estado.setAttribute('role', 'status');
        tabla.after(estado);
        const inicial = JSON.parse(document.getElementById('referencias-manuales-0402-inicial')?.textContent || '{}');
        let manuales;
        try { manuales = JSON.parse(inicial.filas || '[]'); } catch { manuales = []; }
        const controles = document.createElement('div'); controles.className = 'd-flex flex-wrap align-items-end my-2'; controles.style.gap = '10px';
        const etiqueta = document.createElement('label'); etiqueta.htmlFor = 'modo-referencias-0402'; etiqueta.textContent = 'Origen de las normas de referencia';
        const modo = document.createElement('select'); modo.className = 'form-control'; modo.style.width = 'auto'; modo.style.maxWidth = '100%'; modo.id = 'modo-referencias-0402'; modo.name = 'Modo_Referencias_0402';
        modo.add(new Option('Seleccionar normas del catálogo', 'automatico'));
        modo.add(new Option('Captura manual (si no existe en el catálogo)', 'manual'));
        modo.value = inicial.modo || 'automatico';
        const payload = document.createElement('input'); payload.type = 'hidden'; payload.name = 'Referencias_Manuales_0402';
        const agregar = document.createElement('button'); agregar.type = 'button'; agregar.className = 'btn btn-outline-primary btn-sm mt-2'; agregar.textContent = '+ Agregar norma manual';
        controles.append(etiqueta, modo, payload, agregar); tabla.before(controles);
        const seleccionPayload = document.createElement('input'); seleccionPayload.type = 'hidden'; seleccionPayload.name = 'Referencias_Seleccionadas_0402';
        let seleccionInicial;
        try { seleccionInicial = JSON.parse(inicial.seleccion || '[]'); } catch { seleccionInicial = []; }
        const seleccionadas = new Set(seleccionInicial);
        let candidatas = []; let consultando = false;
        const clave = fila => `${fila.idnormas_im}:${fila.fila}`;
        const panelSeleccion = document.createElement('details'); panelSeleccion.className = 'referencias-seleccion-0402 mb-3';
        const resumenCatalogo = document.createElement('summary'); resumenCatalogo.className = 'referencias-desplegable';
        const tituloCatalogo = document.createElement('span'); tituloCatalogo.innerHTML = '<i class="fas fa-book mr-2" aria-hidden="true"></i>Normas de referencia';
        const cuentaCatalogo = document.createElement('span'); cuentaCatalogo.className = 'referencias-cuenta'; cuentaCatalogo.textContent = `${seleccionadas.size} seleccionadas`;
        const flechaCatalogo = document.createElement('i'); flechaCatalogo.className = 'fas fa-chevron-down referencias-flecha'; flechaCatalogo.setAttribute('aria-hidden', 'true');
        resumenCatalogo.append(tituloCatalogo, cuentaCatalogo, flechaCatalogo);
        const tituloSeleccion = document.createElement('h6'); tituloSeleccion.innerHTML = '<i class="fas fa-tasks mr-2" aria-hidden="true"></i>Marca las normas que deseas incluir';
        const ayudaSeleccion = document.createElement('p'); ayudaSeleccion.className = 'text-muted small mb-2'; ayudaSeleccion.textContent = 'Marca las referencias que incluirás en el reporte.';
        const cabecera = document.createElement('div'); cabecera.className = 'referencias-cabecera';
        const accionesNormas = document.createElement('div'); accionesNormas.className = 'd-flex'; accionesNormas.style.gap = '8px';
        if (crearNormaUrl) {
            const crearNorma = document.createElement('a'); crearNorma.href = crearNormaUrl; crearNorma.target = '_blank'; crearNorma.rel = 'noopener'; crearNorma.className = 'btn btn-success btn-sm';
            crearNorma.innerHTML = '<i class="fas fa-plus-circle mr-1" aria-hidden="true"></i>Crear norma'; accionesNormas.append(crearNorma);
        }
        const actualizarCatalogo = document.createElement('button'); actualizarCatalogo.type = 'button'; actualizarCatalogo.className = 'btn btn-outline-secondary btn-sm'; actualizarCatalogo.title = 'Actualizar catálogo después de guardar una norma'; actualizarCatalogo.setAttribute('aria-label', actualizarCatalogo.title);
        actualizarCatalogo.innerHTML = '<i class="fas fa-sync-alt" aria-hidden="true"></i>'; accionesNormas.append(actualizarCatalogo);
        cabecera.append(tituloSeleccion, accionesNormas);
        const tarjetas = document.createElement('div'); tarjetas.className = 'referencias-tarjetas';
        let pagina = 1;
        let soloSeleccionadas = false;
        const porPagina = 12;
        const normalizar = texto => String(texto).normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
        const filtros = document.createElement('div'); filtros.className = 'referencias-filtros';
        const busquedaLabel = document.createElement('label'); busquedaLabel.textContent = 'Buscar norma o grado';
        const busqueda = document.createElement('input'); busqueda.type = 'search'; busqueda.className = 'form-control form-control-sm'; busqueda.placeholder = 'Ej.: A182, F 316L…';
        busquedaLabel.append(busqueda);
        const normaLabel = document.createElement('label'); normaLabel.textContent = 'Filtrar por norma';
        const filtroNorma = document.createElement('select'); filtroNorma.className = 'form-control form-control-sm'; filtroNorma.add(new Option('Todas las normas', '')); normaLabel.append(filtroNorma);
        const verSeleccionadas = document.createElement('button'); verSeleccionadas.type = 'button'; verSeleccionadas.className = 'btn btn-outline-primary btn-sm'; verSeleccionadas.textContent = 'Ver seleccionadas'; verSeleccionadas.setAttribute('aria-pressed', 'false');
        filtros.append(busquedaLabel, normaLabel, verSeleccionadas);
        const vacio = document.createElement('p'); vacio.className = 'text-muted small my-3'; vacio.textContent = 'No hay referencias para estos filtros.'; vacio.hidden = true;
        const paginacion = document.createElement('nav'); paginacion.className = 'referencias-paginacion'; paginacion.setAttribute('aria-label', 'Páginas del catálogo de normas');
        const anterior = document.createElement('button'); anterior.type = 'button'; anterior.className = 'btn btn-outline-secondary btn-sm'; anterior.textContent = 'Anterior';
        const siguiente = document.createElement('button'); siguiente.type = 'button'; siguiente.className = 'btn btn-outline-secondary btn-sm'; siguiente.textContent = 'Siguiente';
        const paginaInfo = document.createElement('span'); paginaInfo.className = 'small'; paginaInfo.setAttribute('aria-live', 'polite');
        paginacion.append(anterior, paginaInfo, siguiente);
        const nombreNorma = fila => fila.nombre_norma || fila.norma.split(' / ')[0];
        const aplicarFiltros = () => {
            const palabras = normalizar(busqueda.value).split(/\s+/).filter(Boolean);
            const visibles = [...tarjetas.children].filter(card => palabras.every(p => card.dataset.busqueda.includes(p))
                && (!filtroNorma.value || card.dataset.norma === filtroNorma.value)
                && (!soloSeleccionadas || seleccionadas.has(card.dataset.clave)));
            const paginas = Math.max(1, Math.ceil(visibles.length / porPagina));
            pagina = Math.max(1, Math.min(pagina, paginas));
            const mostradas = new Set(visibles.slice((pagina - 1) * porPagina, pagina * porPagina));
            [...tarjetas.children].forEach(card => card.hidden = !mostradas.has(card));
            vacio.hidden = visibles.length > 0;
            anterior.disabled = pagina === 1; siguiente.disabled = pagina >= paginas;
            paginaInfo.textContent = visibles.length ? `Página ${pagina} de ${paginas} · ${(pagina - 1) * porPagina + 1}–${Math.min(pagina * porPagina, visibles.length)} de ${visibles.length}` : '0 resultados';
            verSeleccionadas.textContent = soloSeleccionadas ? `Ver todo el catálogo (${seleccionadas.size} seleccionadas)` : `Ver seleccionadas (${seleccionadas.size})`;
            verSeleccionadas.setAttribute('aria-pressed', String(soloSeleccionadas));
            verSeleccionadas.classList.toggle('active', soloSeleccionadas);
        };
        [busqueda, filtroNorma].forEach(control => control.addEventListener(control === busqueda ? 'input' : 'change', () => { pagina = 1; aplicarFiltros(); }));
        verSeleccionadas.addEventListener('click', () => { soloSeleccionadas = !soloSeleccionadas; pagina = 1; aplicarFiltros(); });
        anterior.addEventListener('click', () => { pagina--; aplicarFiltros(); });
        siguiente.addEventListener('click', () => { pagina++; aplicarFiltros(); });
        const contador = document.createElement('small'); contador.className = 'd-block mt-2 font-weight-bold'; contador.setAttribute('aria-live', 'polite');
        const contenidoCatalogo = document.createElement('div'); contenidoCatalogo.className = 'referencias-contenido';
        contenidoCatalogo.append(cabecera, ayudaSeleccion, filtros, tarjetas, vacio, paginacion, contador, seleccionPayload);
        panelSeleccion.append(resumenCatalogo, contenidoCatalogo); tabla.before(panelSeleccion);
        const estilo = document.createElement('style'); estilo.textContent = `
            .referencias-seleccion-0402{background:#f8fafc;border:1px solid #dbe3ee;border-radius:10px;padding:10px}
            .referencias-seleccion-0402 h6{margin-bottom:4px}
            .referencias-desplegable{display:flex;align-items:center;gap:10px;cursor:pointer;list-style:none;font-weight:600;color:#244b73;padding:4px;flex-wrap:wrap}
            .referencias-desplegable::-webkit-details-marker{display:none}
            .referencias-desplegable:focus-visible{outline:2px solid #2563eb;outline-offset:3px;border-radius:4px}
            .referencias-cuenta{font-size:12px;font-weight:500;background:#e3edfc;color:#245b98;padding:3px 9px;border-radius:12px}
            .referencias-flecha{margin-left:auto;transition:transform .15s}
            .referencias-seleccion-0402[open]>.referencias-desplegable .referencias-flecha{transform:rotate(180deg)}
            .referencias-contenido{border-top:1px solid #dbe3ee;margin-top:10px;padding-top:12px}
            .referencias-cabecera{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:8px}
            .referencia-cabecera{display:flex;align-items:flex-start;gap:6px}.referencia-cabecera>label{flex:1;min-width:0}.referencia-cabecera>a{flex-shrink:0;padding:2px 5px;color:#2563eb;border-radius:4px}.referencia-cabecera>a:hover{background:#dbeafe}
            .referencias-filtros{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr) auto;gap:10px;align-items:end;margin:12px 0}
            .referencias-filtros label{margin:0;font-size:12px;color:#475569;min-width:0}.referencias-filtros label .form-control{margin-top:4px}
            .referencias-paginacion{display:flex;align-items:center;justify-content:center;gap:12px;margin-top:12px}
            .referencias-tarjetas>[hidden]{display:none!important}
            @media(max-width:600px){.referencias-filtros{grid-template-columns:1fr}.referencias-paginacion{gap:8px}}
            .referencias-tarjetas{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,250px),1fr));gap:7px;align-items:start}
            .referencia-opcion{position:relative;background:#fff;border:1px solid #dbe3ee;border-left:3px solid #cbd5e1;border-radius:7px;padding:9px;transition:border-color .15s}
            .referencia-opcion.marcada{background:#f0f7ff;border-color:#2563eb;border-left-color:#2563eb}
            .referencia-opcion:focus-within{outline:2px solid #60a5fa;outline-offset:2px}
            .referencia-opcion .referencia-cabecera>label{display:flex;align-items:flex-start;gap:7px;cursor:pointer;margin:0 0 7px;font-weight:700;font-size:13px;line-height:1.3;color:#172b4d}
            .referencia-opcion input[type=checkbox]{width:16px;height:16px;flex-shrink:0;margin-top:1px;accent-color:#2563eb}
            .referencia-estado{display:none}
            .marcada .referencia-estado{display:inline-block;margin-left:auto;color:#1d4ed8;background:#dbeafe;border-radius:4px;padding:1px 4px;font-size:11px;flex-shrink:0}
            .referencia-valores{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));border-top:1px solid #e2e8f0;padding-top:6px;gap:5px}
            .referencia-valores>div{min-width:0}.referencia-valores>div+div{border-left:1px solid #e2e8f0;padding-left:5px}
            .referencia-valores span{display:block;color:#64748b;font-size:11px;margin-bottom:2px}
            .referencia-valores strong{display:block;color:#0f172a;font-size:14px;line-height:1.25;overflow-wrap:anywhere}
            .referencia-escala{margin-top:3px}.referencia-escala select{width:100%;font-size:11px;height:24px;padding:1px}
        `; document.head.append(estilo);
        const campos = ['norma', 'dureza_max', 'tension_min', 'cedencia_min', 'tension_max'];
        const titulos = ['Norma / grado', 'Dureza máxima HB/HBW', 'Tensión mínima (ksi)', 'Cedencia mínima (ksi)', 'Tensión máxima (ksi)'];
        let especificacionEditada = false;
        let primeraEspecificacion = true;
        const entradaEspecificacion = form.id === 'FOR-PIMP-04_03' ? campo('ESPECIFICACION_MATERIAL') : null;
        entradaEspecificacion?.addEventListener('input', () => { especificacionEditada = true; });
        const vincularEspecificacion = filas => {
            if (form.id === 'FOR-PIMP-04_03') {
                const especificacion = campo('ESPECIFICACION_MATERIAL');
                if (especificacion) {
                    especificacion.readOnly = false;
                    const sugerencia = [...new Set(filas.map(fila => String(fila.norma || '').trim()).filter(Boolean))].join(' / ');
                    // Edit conserva una redacción distinta de la sugerencia del catálogo.
                    if (primeraEspecificacion && especificacion.value.trim() && especificacion.value !== sugerencia) especificacionEditada = true;
                    primeraEspecificacion = false;
                    if (!especificacionEditada && especificacion.value !== sugerencia) {
                        especificacion.value = sugerencia;
                        especificacion.dispatchEvent(new Event('change', {bubbles: true}));
                    }
                }
            }
        };
        const pintar = filas => {
            vincularEspecificacion(filas);
            cuerpo.replaceChildren();
            if (!filas.length) return;
            const tr = document.createElement('tr');
            const colores = form.id === 'FOR-PIMP-04_03' ? ['#111111', '#1565c0', '#c62828', '#6a1b9a', '#2e7d32', '#ad5700'] : ['#c62828', '#1565c0', '#111111', '#6a1b9a', '#2e7d32', '#ad5700'];
            campos.forEach(campoReferencia => {
                const td = document.createElement('td');
                filas.forEach((fila, indice) => {
                    if (indice) td.append(document.createTextNode(' / '));
                    const valor = document.createElement('span'); valor.style.color = colores[indice % colores.length]; valor.style.fontWeight = '600';
                    valor.textContent = campoReferencia === 'norma' ? (fila.norma || '').replace(/\s*\/\s*/g, ' ') : (fila[campoReferencia] === '' ? '—' : fila[campoReferencia] ?? '—');
                    td.append(valor);
                });
                tr.append(td);
            });
            cuerpo.append(tr);
        };
        const actualizarSeleccion = () => {
            seleccionPayload.value = JSON.stringify([...seleccionadas]);
            const filas = [...seleccionadas].map(id => candidatas.find(fila => clave(fila) === id)).filter(Boolean);
            pintar(filas);
            window.referenciasQuimicas0402 = filas;
            document.dispatchEvent(new CustomEvent('referencias-0402:cambio', { detail: filas }));
            contador.textContent = `${filas.length} seleccionada(s) de ${candidatas.length} del catálogo`;
            cuentaCatalogo.textContent = `${filas.length} seleccionadas`;
            aplicarFiltros();
        };
        const dibujarCandidatas = () => {
            tarjetas.replaceChildren();
            candidatas.forEach(fila => {
                const card = document.createElement('div'); card.className = 'referencia-opcion';
                card.dataset.clave = clave(fila); card.dataset.norma = nombreNorma(fila); card.dataset.busqueda = normalizar(fila.norma);
                const label = document.createElement('label'); const check = document.createElement('input'); check.type = 'checkbox'; check.checked = seleccionadas.has(clave(fila));
                card.classList.toggle('marcada', check.checked);
                const nombre = document.createElement('span'); nombre.textContent = fila.norma; label.append(check, nombre);
                const cabeceraTarjeta = document.createElement('div'); cabeceraTarjeta.className = 'referencia-cabecera'; cabeceraTarjeta.append(label);
                if (editarNormaUrl) {
                    const editar = document.createElement('a'); editar.href = editarNormaUrl.replace('__NORMA__', encodeURIComponent(fila.idnormas_im)); editar.target = '_blank'; editar.rel = 'noopener'; editar.title = `Editar norma: ${fila.norma}`; editar.setAttribute('aria-label', editar.title);
                    editar.innerHTML = '<i class="fas fa-edit" aria-hidden="true"></i>'; cabeceraTarjeta.append(editar);
                }
                card.append(cabeceraTarjeta);
                const identificador = document.createElement('span'); identificador.className = 'referencia-estado';
                const actualizarEstado = () => { identificador.textContent = check.checked ? '✓' : ''; identificador.title = check.checked ? 'Seleccionada' : ''; };
                actualizarEstado(); label.append(identificador);
                const escalaLabel = document.createElement('div'); escalaLabel.className = 'referencia-escala';
                const escala = document.createElement('select'); escala.className = 'form-control form-control-sm';
                escala.name = `Escalas_Referencias_0402[${clave(fila)}]`;
                escala.setAttribute('aria-label', `Escala de dureza de ${fila.norma}`);
                escala.add(new Option('Escala del catálogo', ''));
                ['HB', 'HBW', 'HRC', 'HRB', 'HV'].forEach(valor => escala.add(new Option(valor, valor)));
                escala.value = inicial.escalas?.[clave(fila)] || '';
                const durezaOriginal = fila.dureza_max;
                const aplicarEscala = () => {
                    fila.dureza_max = escala.value && durezaOriginal ? `${String(durezaOriginal).replace(/\b(?:HBW|HB|HRC|HRB|HV)\b/gi, '').trim()} ${escala.value}` : durezaOriginal;
                    escala.disabled = !check.checked;
                    escalaLabel.hidden = !check.checked;
                };
                aplicarEscala();
                escala.addEventListener('change', () => { (inicial.escalas ??= {})[clave(fila)] = escala.value; aplicarEscala(); actualizarSeleccion(); });
                escalaLabel.append(escala); card.append(escalaLabel);
                const limites = document.createElement('div'); limites.className = 'referencia-valores';
                const formato = valor => valor === null || valor === undefined || String(valor).trim() === '' ? '—' : /^\d+(\.\d+)?$/.test(String(valor)) ? String(Number(valor)) : String(valor);
                const tension = `${formato(fila.tension_min)}${fila.tension_max != null ? ' – ' + formato(fila.tension_max) : ''}`;
                [['Tensión (ksi)', tension, 'fas fa-arrows-alt-h'], ['Cedencia (ksi)', formato(fila.cedencia_min), 'fas fa-compress-arrows-alt'], ['Dureza', formato(durezaOriginal), 'fas fa-gem']].forEach(([titulo, valor, icono]) => {
                    const bloque = document.createElement('div');
                    const encabezado = document.createElement('span');
                    const icon = document.createElement('i'); icon.className = icono; icon.setAttribute('aria-hidden', 'true');
                    encabezado.append(icon, document.createTextNode(` ${titulo}`));
                    const dato = document.createElement('strong'); dato.textContent = valor;
                    bloque.append(encabezado, dato); limites.append(bloque);
                    if (titulo === 'Dureza') bloque.append(escalaLabel);
                });
                card.append(limites);
                const quimica = document.createElement('div'); quimica.className = 'referencia-escala';
                const composicion = document.createElement('select'); composicion.className = 'form-control form-control-sm';
                composicion.name = `Composiciones_Referencias_0402[${clave(fila)}]`;
                composicion.setAttribute('aria-label', `Composición química de ${fila.norma}`);
                const opciones = fila.composiciones || [];
                composicion.add(new Option(opciones.length ? 'Elegir composición química' : 'Sin composición química registrada', ''));
                opciones.forEach(opcion => composicion.add(new Option(opcion.Variable, opcion.configuracion)));
                composicion.value = inicial.composiciones?.[clave(fila)] ?? (opciones.length === 1 ? String(opciones[0].configuracion) : '');
                composicion.title = 'Composición química: grado, producto y espesor';
                const actualizarComposicion = () => {
                    fila.composicion_seleccionada = opciones.find(opcion => String(opcion.configuracion) === composicion.value) || null;
                    composicion.disabled = !check.checked || !opciones.length;
                    quimica.hidden = !check.checked;
                };
                actualizarComposicion();
                composicion.addEventListener('change', () => { (inicial.composiciones ??= {})[clave(fila)] = composicion.value; actualizarComposicion(); actualizarSeleccion(); });
                quimica.append(composicion); card.append(quimica);
                check.addEventListener('change', () => {
                    if (check.checked) seleccionadas.add(clave(fila)); else seleccionadas.delete(clave(fila));
                    aplicarEscala();
                    actualizarEstado();
                    actualizarComposicion();
                    card.classList.toggle('marcada', check.checked); actualizarSeleccion();
                }); tarjetas.append(card);
            });
            actualizarSeleccion();
        };
        const actualizarManual = () => {
            vincularEspecificacion(manuales);
            payload.value = JSON.stringify(manuales);
            estado.textContent = 'Escribe directamente en la tabla. Cada color identifica una referencia y sus límites; el PDF conserva el mismo orden y los separadores /.';
        };
        const dibujarEditor = () => {
            cuerpo.replaceChildren();
            if (!manuales.length) { actualizarManual(); return; }
            const row = document.createElement('tr');
            const colores = form.id === 'FOR-PIMP-04_03' ? ['#111111', '#1565c0', '#c62828', '#6a1b9a', '#2e7d32', '#ad5700'] : ['#c62828', '#1565c0', '#111111', '#6a1b9a', '#2e7d32', '#ad5700'];
            campos.forEach((clave, columna) => {
                const td = document.createElement('td');
                manuales.forEach((fila, indice) => {
                    if (indice) td.append(document.createTextNode(' / '));
                    const grupo = document.createElement('span'); grupo.style.display = 'inline-flex'; grupo.style.alignItems = 'center'; grupo.style.maxWidth = '100%'; grupo.style.margin = '3px 0';
                    const input = document.createElement('input'); input.className = 'form-control form-control-sm'; input.value = fila[clave] ?? ''; input.setAttribute('aria-label', `${titulos[columna]}, referencia ${indice + 1}`);
                    input.style.color = colores[indice % colores.length]; input.style.fontWeight = '600'; input.style.borderColor = colores[indice % colores.length]; input.style.width = clave === 'norma' ? '170px' : '85px'; input.style.maxWidth = '100%';
                    if (clave === 'norma') input.maxLength = 255; else input.inputMode = 'decimal';
                    input.dataset.referenciaIndice = indice;
                    input.dataset.referenciaCampo = clave;
                    input.addEventListener('input', () => { fila[clave] = input.value; actualizarManual(); }); grupo.append(input);
                    if (clave === 'norma') {
                        const quitar = document.createElement('button'); quitar.type = 'button'; quitar.className = 'btn btn-link text-danger btn-sm px-1'; quitar.textContent = '×'; quitar.setAttribute('aria-label', `Eliminar referencia ${indice + 1}`); quitar.title = 'Eliminar esta norma y sus límites';
                        quitar.addEventListener('click', () => { manuales.splice(indice, 1); dibujarEditor(); }); grupo.append(quitar);
                    }
                    td.append(grupo);
                });
                row.append(td);
            });
            cuerpo.append(row); actualizarManual();
        };
        agregar.addEventListener('click', () => { if (manuales.length < 30) { manuales.push({ norma: '', dureza_max: '', tension_min: '', cedencia_min: '', tension_max: '' }); dibujarEditor(); } });
        let peticion = 0; let temporizador; let controlador;
        const buscar = async () => {
            const turno = ++peticion;
            controlador?.abort();
            panelSeleccion.hidden = modo.value === 'manual';
            seleccionPayload.disabled = modo.value === 'manual';
            agregar.hidden = modo.value !== 'manual';
            if (modo.value === 'manual') {
                window.referenciasQuimicas0402 = null;
                document.dispatchEvent(new CustomEvent('referencias-0402:cambio', { detail: null }));
                if (!manuales.length) manuales.push({ norma: '', dureza_max: '', tension_min: '', cedencia_min: '', tension_max: '' });
                dibujarEditor(); return;
            }
            cuerpo.replaceChildren();
            candidatas = []; tarjetas.replaceChildren(); contador.textContent = '';
            estado.textContent = 'Cargando normas y grados del catálogo...';
            controlador = new AbortController();
            consultando = true;
            try {
                const response = await fetch(url, { method: 'POST', signal: controlador.signal,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value },
                    body: JSON.stringify({}) });
                if (!response.ok) throw new Error('No se pudo consultar el catálogo. Revise su sesión y los valores capturados.');
                const datos = await response.json(); if (turno !== peticion) return;
                candidatas = datos.coincidencias;
                const filtroAnterior = filtroNorma.value;
                filtroNorma.replaceChildren(new Option('Todas las normas', ''));
                [...new Set(candidatas.map(nombreNorma))].sort((a, b) => a.localeCompare(b, 'es', { numeric: true })).forEach(nombre => filtroNorma.add(new Option(nombre, nombre)));
                filtroNorma.value = [...filtroNorma.options].some(opcion => opcion.value === filtroAnterior) ? filtroAnterior : '';
                const validas = new Set(candidatas.map(clave));
                [...seleccionadas].forEach(id => { if (!validas.has(id)) seleccionadas.delete(id); });
                dibujarCandidatas();
                estado.textContent = '';
            } catch (error) { if (error.name !== 'AbortError' && turno === peticion) estado.textContent = error.message; }
            finally { if (turno === peticion) consultando = false; }
        };
        modo.addEventListener('change', () => { clearTimeout(temporizador); buscar(); });
        actualizarCatalogo.addEventListener('click', () => { if (!consultando) buscar(); });
        form.addEventListener('submit', event => {
            if (modo.value === 'automatico') {
                if (consultando) { event.preventDefault(); estado.textContent = 'Espera a que termine la consulta y confirma las normas antes de guardar.'; }
                seleccionPayload.value = JSON.stringify([...seleccionadas]);
                return;
            }
            if (modo.value !== 'manual') return;
            cuerpo.querySelectorAll('[data-referencia-campo]').forEach(input => {
                const fila = manuales[Number(input.dataset.referenciaIndice)];
                if (fila) fila[input.dataset.referenciaCampo] = input.value;
            });
            payload.value = JSON.stringify(manuales);
        }, true);
        setTimeout(buscar, 0);
    });
})();
