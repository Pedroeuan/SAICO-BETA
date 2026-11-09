/** Calendario compartido de equipos. Solo consulta los datos serializados por Blade. */
(() => {
    'use strict';
    const root = document.getElementById('equipos-calendario');
    if (!root) return;
    const eventos = JSON.parse(document.getElementById('equipo-cal-datos').textContent);
    const mes = document.getElementById('equipo-cal-mes');
    const anioFiltro = document.getElementById('equipo-cal-anio');
    const periodo = document.getElementById('equipo-cal-periodo');
    const anual = document.getElementById('equipo-cal-anual');
    const buscar = document.getElementById('equipo-cal-buscar');
    const filtroEstado = document.getElementById('equipo-cal-estado');
    const grid = document.getElementById('equipo-cal-grid');
    const dialog = document.getElementById('equipo-cal-detalle');
    const hoy = new Date();
    const isoHoy = `${hoy.getFullYear()}-${String(hoy.getMonth() + 1).padStart(2, '0')}-${String(hoy.getDate()).padStart(2, '0')}`;
    const mesActual = isoHoy.slice(0, 7);
    // Comparar días a medianoche UTC evita desfases por zona horaria y horario de verano.
    const fechaUTC = valor => Date.parse(`${valor}T00:00:00Z`);
    const estado = evento => {
        if (!evento.fecha) return 'Sin fecha';
        const dias = Math.round((fechaUTC(evento.fecha) - fechaUTC(isoHoy)) / 86400000);
        return dias < 0 ? 'Vencido' : dias <= 30 ? 'Próximo a vencer' : 'Vigente';
    };
    const formato = valor => valor ? valor.split('-').reverse().join('/') : 'Sin fecha';
    const nodo = (tag, texto, clase) => {
        const elemento = document.createElement(tag);
        if (texto) elemento.textContent = texto;
        if (clase) elemento.className = clase;
        return elemento;
    };
    // Reutilizar el diálogo permite pasar de las actividades del día a una ficha sin anidar modales.
    function detalle(evento) {
        const contenido = document.getElementById('equipo-cal-contenido');
        const titulo = nodo('h3', evento.nombre, 'equipo-detalle-titulo');
        titulo.id = 'equipo-cal-detalle-titulo';
        const identidad = nodo('div', '', 'equipo-detalle-identidad');
        identidad.append(nodo('span', evento.numero, 'equipo-detalle-numero'), titulo);
        contenido.replaceChildren(identidad);
        const datos = nodo('dl', '', 'equipo-detalle-datos');
        for (const [titulo, valor] of Object.entries({Tipo: evento.tipo, Marca: evento.marca, Modelo: evento.modelo, Serie: evento.serie, 'Última fecha': formato(evento.ultima), 'Próxima fecha': formato(evento.fecha), Estado: estado(evento), Certificado: evento.certificado})) {
            const campo = nodo('div', '', 'equipo-detalle-campo');
            if (titulo === 'Estado' || titulo === 'Próxima fecha') campo.classList.add('equipo-detalle-destacado');
            campo.append(nodo('dt', titulo), nodo('dd', valor || 'Sin dato'));
            datos.append(campo);
        }
        contenido.append(datos);
        if (evento.foto && /\.(png|jpe?g|webp|gif)$/i.test(evento.foto)) {
            const figura = nodo('figure', '', 'equipo-detalle-foto');
            const img = nodo('img'); img.src = evento.foto; img.alt = evento.nombre;
            figura.append(img);
            contenido.append(figura);
        } else if (evento.foto && /\.pdf(?:[?#].*)?$/i.test(evento.foto)) {
            const presentacion = nodo('div', '', 'equipo-detalle-pdf');
            const visor = nodo('object');
            visor.data = `${evento.foto.split('#')[0]}#page=1&zoom=page-width&navpanes=0`;
            visor.type = 'application/pdf';
            visor.setAttribute('aria-label', `Hoja de presentación de ${evento.nombre}`);
            const enlace = nodo('a', 'Abrir hoja de presentación en PDF', 'equipo-detalle-boton');
            enlace.href = evento.foto; enlace.target = '_blank'; enlace.rel = 'noopener';
            visor.append(nodo('p', 'La vista previa de PDF no está disponible en este navegador.'), enlace);
            presentacion.append(visor);
            contenido.append(presentacion);
        }
        const acciones = nodo('div', '', 'equipo-detalle-acciones');
        const esPresentacionPdf = /\.pdf(?:[?#].*)?$/i.test(evento.foto || '');
        for (const [titulo, url, icono] of [[esPresentacionPdf ? 'Ver presentación PDF' : 'Ver fotografía / archivo', evento.foto, esPresentacionPdf ? 'fas fa-file-pdf' : 'fas fa-image'], ['Ver certificado', evento.documento, 'fas fa-file-alt'], ['Abrir ficha / editar', evento.editar, 'fas fa-edit']]) {
            if (!url) continue;
            const enlace = nodo('a', titulo, url === evento.editar ? 'equipo-detalle-boton equipo-detalle-primario' : 'equipo-detalle-boton'); enlace.href = url; enlace.target = '_blank'; enlace.rel = 'noopener'; acciones.append(enlace);
            const imagenIcono = nodo('i', '', icono);
            imagenIcono.setAttribute('aria-hidden', 'true');
            enlace.prepend(imagenIcono);
        }
        contenido.append(acciones);
        if (!dialog.open) dialog.showModal();
        dialog.scrollTop = 0;
    }
    // Ante varios eventos en una fecha, el color representa el estado más urgente.
    function estadoDia(eventosDia) {
        const estados = eventosDia.map(estado);
        return estados.includes('Vencido') ? 'Vencido' : estados.includes('Próximo a vencer') ? 'Próximo a vencer' : 'Vigente';
    }
    function detalleDia(fecha, eventosDia) {
        const contenido = document.getElementById('equipo-cal-contenido');
        const titulo = nodo('h3', `Actividades del ${formato(fecha)}`, 'equipo-detalle-titulo');
        titulo.id = 'equipo-cal-detalle-titulo';
        contenido.replaceChildren(titulo, nodo('p', `${eventosDia.length} actividades registradas. Selecciona un equipo para consultar su ficha.`, 'equipo-dia-ayuda'));
        const lista = nodo('div', '', 'equipo-dia-lista');
        eventosDia.forEach(evento => {
            const tarjeta = nodo('button', '', 'equipo-dia-tarjeta');
            tarjeta.type = 'button'; tarjeta.dataset.estado = estado(evento);
            tarjeta.append(nodo('span', formato(fecha), 'equipo-dia-fecha'), nodo('strong', evento.numero), nodo('span', evento.nombre, 'equipo-dia-nombre'), nodo('span', evento.tipo, 'equipo-dia-tipo'), nodo('span', estado(evento), 'equipo-dia-estado'));
            tarjeta.addEventListener('click', () => {
                detalle(evento);
                const volver = nodo('button', '‹ Volver a las actividades del día', 'equipo-detalle-boton equipo-dia-volver');
                volver.type = 'button'; volver.addEventListener('click', () => detalleDia(fecha, eventosDia));
                contenido.prepend(volver);
                volver.focus();
            });
            lista.append(tarjeta);
        });
        contenido.append(lista);
        if (!dialog.open) dialog.showModal();
    }
    function configurarDia(botonDia, fecha, eventosDia) {
        botonDia.type = 'button';
        botonDia.dataset.estado = estadoDia(eventosDia);
        botonDia.title = `${formato(fecha)} · ${eventosDia.length} actividades · ${estadoDia(eventosDia)}`;
        botonDia.setAttribute('aria-label', botonDia.title);
        botonDia.addEventListener('click', () => {
            if (eventosDia.length === 1) detalle(eventosDia[0]);
            else detalleDia(fecha, eventosDia);
        });
    }
    function boton(evento) {
        const btn = nodo('button', '', 'equipo-cal-evento');
        btn.append(nodo('strong', evento.numero), nodo('span', evento.nombre, 'equipo-cal-nombre'), nodo('span', evento.tipo, 'equipo-cal-tipo'), nodo('span', estado(evento), 'equipo-cal-estado'));
        btn.type = 'button'; btn.dataset.estado = estado(evento); btn.addEventListener('click', () => detalle(evento)); return btn;
    }
    // Búsqueda y estado se comparten entre las vistas mensual y anual.
    // Los registros sin fecha permanecen fuera de cualquier año supuesto.
    function render() {
        if (!/^\d{4}-\d{2}$/.test(mes.value)) return;
        const [anio, numeroMes] = mes.value.split('-').map(Number);
        const esAnual = periodo.value === 'anio';
        anioFiltro.value = String(anio);
        mes.closest('label').hidden = esAnual;
        grid.parentElement.hidden = esAnual;
        anual.hidden = !esAnual;
        document.getElementById('equipo-cal-titulo').textContent = esAnual ? `Año ${anio}` : new Date(anio, numeroMes - 1, 1).toLocaleDateString('es-MX', { month: 'long', year: 'numeric' });
        const termino = buscar.value.trim().toLocaleLowerCase('es');
        const filtrados = eventos.filter(e => `${e.numero} ${e.nombre} ${e.serie} ${e.marca} ${e.modelo}`.toLocaleLowerCase('es').includes(termino) && (!filtroEstado.value || estado(e) === filtroEstado.value));
        const delMes = filtrados.filter(e => e.fecha && e.fecha.slice(0, 7) === mes.value);
        const delAnio = filtrados.filter(e => e.fecha && e.fecha.slice(0, 4) === String(anio));
        anual.replaceChildren();
        if (esAnual) {
            for (let m = 1; m <= 12; m++) {
                const tarjeta = nodo('section', '', 'equipo-cal-mes-anual');
                const tituloMes = new Date(anio, m - 1, 1).toLocaleDateString('es-MX', { month: 'long' });
                tarjeta.append(nodo('h5', tituloMes));
                const mini = nodo('div', '', 'equipo-cal-mini');
                ['L', 'M', 'X', 'J', 'V', 'S', 'D'].forEach(d => mini.append(nodo('strong', d)));
                const prefijo = `${anio}-${String(m).padStart(2, '0')}`;
                const diasMes = new Date(anio, m, 0).getDate();
                const desplazamiento = (new Date(anio, m - 1, 1).getDay() + 6) % 7;
                for (let i = 0; i < desplazamiento; i++) mini.append(nodo('span'));
                for (let dia = 1; dia <= diasMes; dia++) {
                    const fecha = `${prefijo}-${String(dia).padStart(2, '0')}`;
                    const eventosDia = delAnio.filter(e => e.fecha === fecha);
                    const celda = nodo(eventosDia.length ? 'button' : 'span', String(dia));
                    if (fecha === isoHoy) celda.classList.add('equipo-cal-mini-hoy');
                    if (eventosDia.length) {
                        celda.classList.add('equipo-cal-mini-evento');
                        configurarDia(celda, fecha, eventosDia);
                    }
                    mini.append(celda);
                }
                tarjeta.append(mini);
                const eventosMes = delAnio.filter(e => e.fecha.slice(0, 7) === prefijo);
                tarjeta.append(nodo('p', `${eventosMes.length} eventos`, 'equipo-cal-total-mes'));
                anual.append(tarjeta);
            }
        }
        grid.replaceChildren();
        ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'].forEach(d => grid.append(nodo('div', d, 'equipo-cal-cabecera')));
        const inicio = (new Date(anio, numeroMes - 1, 1).getDay() + 6) % 7;
        for (let i = 0; i < inicio; i++) grid.append(nodo('div', '', 'equipo-cal-dia'));
        const dias = new Date(anio, numeroMes, 0).getDate();
        for (let dia = 1; dia <= dias; dia++) {
            const fecha = `${mes.value}-${String(dia).padStart(2, '0')}`;
            const eventosDia = delMes.filter(e => e.fecha === fecha);
            const celda = nodo('div', '', 'equipo-cal-dia'); celda.dataset.hoy = String(fecha === isoHoy);
            const numeroDia = nodo(eventosDia.length ? 'button' : 'strong', String(dia), 'equipo-cal-numero-dia');
            if (eventosDia.length) { configurarDia(numeroDia, fecha, eventosDia); celda.dataset.estado = estadoDia(eventosDia); }
            celda.append(numeroDia);
            eventosDia.forEach(e => celda.append(boton(e))); grid.append(celda);
        }
        for (let i = 0; i < (7 - (inicio + dias) % 7) % 7; i++) grid.append(nodo('div', '', 'equipo-cal-dia equipo-cal-vacio'));
        const sinFecha = filtrados.filter(e => !e.fecha);
        document.getElementById('equipo-cal-sin-fecha').replaceChildren(...(sinFecha.length ? sinFecha.map(boton) : [nodo('p', 'No hay equipos sin fecha para estos filtros.')]));
        const cantidad = esAnual ? delAnio.length : delMes.length;
        document.getElementById('equipo-cal-resumen').textContent = `${cantidad} eventos en ${esAnual ? 'el año' : 'el mes'} seleccionado. ${sinFecha.length} sin fecha (sin año asignado). ${cantidad ? '' : 'No hay eventos para este periodo y filtros.'}`;
    }
    // Ocultar el contenedor DataTables completo conserva sus controles al volver a la tabla.
    root.querySelectorAll('[data-vista]').forEach(btn => btn.addEventListener('click', () => {
        const calendario = btn.dataset.vista === 'calendario';
        document.getElementById('equipo-cal-panel').hidden = !calendario;
        const tabla = document.getElementById('tablaJs');
        const contenedor = tabla.closest('.dt-container') || tabla;
        contenedor.hidden = calendario;
        root.querySelectorAll('[data-vista]').forEach(b => { const activo = b === btn; b.setAttribute('aria-pressed', String(activo)); b.classList.toggle('btn-primary', activo); b.classList.toggle('btn-outline-primary', !activo); });
        if (calendario) render();
        else if (window.jQuery && window.jQuery.fn.dataTable) window.jQuery(tabla).DataTable().columns.adjust();
    }));
    mes.value = mesActual;
    const anios = new Set(Array.from({length: 11}, (_, i) => hoy.getFullYear() - 5 + i));
    eventos.forEach(e => { if (e.fecha) anios.add(Number(e.fecha.slice(0, 4))); });
    [...anios].sort((a, b) => a - b).forEach(a => { const opcion = nodo('option', String(a)); opcion.value = String(a); anioFiltro.append(opcion); });
    anioFiltro.value = String(hoy.getFullYear());
    anioFiltro.addEventListener('change', () => { mes.value = `${anioFiltro.value}-${mes.value.slice(5)}`; render(); });
    periodo.addEventListener('change', render);
    [mes, filtroEstado].forEach(e => e.addEventListener('change', render));
    buscar.addEventListener('input', render);
    document.getElementById('equipo-cal-hoy').addEventListener('click', () => { mes.value = mesActual; periodo.value = 'mes'; render(); });
    for (const [id, cambio] of [['equipo-cal-anterior', -1], ['equipo-cal-siguiente', 1]]) {
        document.getElementById(id).addEventListener('click', () => {
            const [anio, numeroMes] = mes.value.split('-').map(Number);
            const destino = new Date(anio + (periodo.value === 'anio' ? cambio : 0), numeroMes - 1 + (periodo.value === 'anio' ? 0 : cambio), 1);
            if (![...anioFiltro.options].some(o => o.value === String(destino.getFullYear()))) {
                const opcion = nodo('option', String(destino.getFullYear())); opcion.value = String(destino.getFullYear()); anioFiltro.append(opcion);
            }
            mes.value = `${destino.getFullYear()}-${String(destino.getMonth() + 1).padStart(2, '0')}`;
            render();
        });
    }
    document.getElementById('equipo-cal-cerrar').addEventListener('click', () => dialog.close());
    // La exportación recibe todos los eventos; el exportador selecciona el año completo.
    document.getElementById('equipo-cal-excel').addEventListener('click', async function () {
        this.disabled = true;
        try {
            await window.exportarCalendarioEquipos({ anio: Number(anioFiltro.value), eventos, estado, titulo: root.querySelector('.equipo-cal-encabezado h3').textContent, logoUrl: root.dataset.logo });
        } catch (error) {
            console.error('No se pudo exportar el calendario', error);
            window.alert('No se pudo generar el Excel. Recarga la página e inténtalo nuevamente.');
        } finally { this.disabled = false; }
    });
    render();
})();
