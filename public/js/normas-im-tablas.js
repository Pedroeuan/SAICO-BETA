document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.getElementById('otras-tablas');
    if (contenedor) {
        let tablas = JSON.parse(document.getElementById('otras-tablas-datos').textContent) || [];
        const elemento = (tag, texto, clase) => {
            const nodo = document.createElement(tag);
            if (texto) nodo.textContent = texto;
            if (clase) nodo.className = clase;
            return nodo;
        };
        const dibujar = () => {
            contenedor.replaceChildren();
            if (!tablas.length) {
                const vacio = elemento('div', '', 'tabla-personalizada-vacia');
                vacio.innerHTML = '<i class="fas fa-table" aria-hidden="true"></i><strong>Agrega tu primera tabla</strong><span>Define sus columnas y captura los datos de la norma.</span>';
                contenedor.append(vacio);
            }
            tablas.forEach((tabla, i) => {
                const card = elemento('div', '', 'tabla-personalizada');
                const base = `Tablas_Adicionales[otras][${i}]`;
                const input = (nombre, valor, etiqueta, cambiar) => {
                    const campo = elemento('input', '', 'form-control form-control-sm');
                    campo.name = nombre;
                    campo.value = valor || '';
                    campo.placeholder = etiqueta;
                    campo.setAttribute('aria-label', etiqueta);
                    campo.maxLength = nombre.includes('[filas]') ? 2000 : 255;
                    campo.addEventListener('input', () => cambiar(campo.value));
                    return campo;
                };
                const boton = (texto, accion, icono = 'fa-plus', clase = 'btn-outline-primary') => {
                    const btn = elemento('button', '', `btn btn-sm ${clase}`);
                    const icon = elemento('i', '', `fas ${icono}`); icon.setAttribute('aria-hidden', 'true');
                    btn.append(icon, document.createTextNode(` ${texto}`));
                    btn.type = 'button';
                    btn.addEventListener('click', () => { accion(); dibujar(); });
                    return btn;
                };
                const encabezado = elemento('div', '', 'tabla-personalizada-cabecera');
                const nombre = elemento('strong');
                nombre.innerHTML = '<i class="fas fa-table mr-2" aria-hidden="true"></i>';
                nombre.append(document.createTextNode(`Tabla personalizada ${i + 1}`));
                encabezado.append(nombre, elemento('span', `${tabla.columnas.length} columnas · ${tabla.filas.length} filas`, 'tabla-personalizada-conteo'));
                card.append(encabezado);
                const contenido = elemento('div', '', 'tabla-personalizada-contenido'); card.append(contenido);
                contenido.append(elemento('label', 'Título / referencia de la tabla'));
                contenido.append(input(`${base}[titulo]`, tabla.titulo, 'Ejemplo: Tabla 5 — Ensayo de impacto', valor => tabla.titulo = valor));
                const scroll = elemento('div', '', 'table-responsive mt-3');
                const grid = elemento('table', '', 'table table-bordered table-sm');
                grid.style.minWidth = `${tabla.columnas.length * 190 + 54}px`;
                const anchos = elemento('colgroup');
                tabla.columnas.forEach(() => anchos.append(elemento('col')));
                const anchoAccion = elemento('col'); anchoAccion.style.width = '54px'; anchos.append(anchoAccion); grid.append(anchos);
                const head = elemento('thead');
                const titulos = elemento('tr');
                tabla.columnas.forEach((titulo, c) => {
                    const celda = elemento('th');
                    const encabezadoColumna = elemento('div', '', 'tabla-columna-encabezado');
                    encabezadoColumna.append(input(`${base}[columnas][${c}]`, titulo, 'Nombre de columna', valor => tabla.columnas[c] = valor));
                    const quitarColumna = boton('', () => {
                        if (tabla.columnas.length === 1) return;
                        tabla.columnas.splice(c, 1);
                        tabla.filas.forEach(fila => fila.splice(c, 1));
                    }, 'fa-times', 'btn-outline-danger');
                    quitarColumna.title = `Eliminar columna ${c + 1}`;
                    quitarColumna.setAttribute('aria-label', `Eliminar columna ${c + 1}`);
                    quitarColumna.disabled = tabla.columnas.length === 1;
                    encabezadoColumna.append(quitarColumna); celda.append(encabezadoColumna);
                    titulos.append(celda);
                });
                titulos.append(elemento('th', '', 'tabla-personalizada-eliminar'));
                head.append(titulos);
                grid.append(head);
                const body = elemento('tbody');
                tabla.filas.forEach((fila, f) => {
                    const row = elemento('tr');
                    tabla.columnas.forEach((titulo, c) => {
                        const celda = elemento('td');
                        celda.append(input(`${base}[filas][${f}][${c}]`, fila[c], titulo, valor => fila[c] = valor));
                        row.append(celda);
                    });
                    const celda = elemento('td');
                    const quitarFila = boton('', () => tabla.filas.splice(f, 1), 'fa-trash-alt', 'btn-outline-danger');
                    quitarFila.setAttribute('aria-label', `Eliminar fila ${f + 1}`); quitarFila.title = `Eliminar fila ${f + 1}`;
                    celda.className = 'text-center'; celda.append(quitarFila);
                    row.append(celda);
                    body.append(row);
                });
                grid.append(body);
                scroll.append(grid);
                contenido.append(scroll);
                const acciones = elemento('div', '', 'tabla-personalizada-acciones');
                const agregarColumna = boton('Agregar columna', () => {
                    if (tabla.columnas.length >= 12) return;
                    tabla.columnas.push('');
                    tabla.filas.forEach(fila => fila.push(''));
                }, 'fa-columns');
                agregarColumna.disabled = tabla.columnas.length >= 12;
                const agregarFila = boton('Agregar fila', () => {
                    if (tabla.filas.length < 25) tabla.filas.push(tabla.columnas.map(() => ''));
                }, 'fa-plus', 'btn-primary');
                agregarFila.disabled = tabla.filas.length >= 25;
                acciones.append(agregarColumna, agregarFila, boton('Eliminar tabla', () => tablas.splice(i, 1), 'fa-trash-alt', 'btn-outline-danger ml-auto'));
                card.append(acciones);
                contenedor.append(card);
            });
        };
        tablas.forEach(tabla => { tabla.filas = tabla.filas || []; });
        document.getElementById('agregar-otra-tabla').addEventListener('click', () => {
            if (tablas.length >= 10) return;
            tablas.push({ titulo: '', columnas: ['Grado', 'Propiedad', 'Valor / criterio'], filas: [['', '', '']] });
            dibujar();
        });
        dibujar();
    }
    document.querySelectorAll('[data-tabla-norma]').forEach(panel => {
        const clave = panel.dataset.tablaNorma;
        const cuerpo = panel.querySelector('tbody');
        if (!panel.querySelector('.agregar-filas-norma')) return;
        const limite = clave === 'quimica' ? 100 : 25;
        const renumerar = () => cuerpo.querySelectorAll('tr').forEach((fila, indice) => {
            fila.querySelector('.numero-fila').textContent = indice + 1;
            if (clave === 'quimica') fila.querySelectorAll('[data-contexto]').forEach(input => {
                input.name = `Contexto[${indice}][${input.dataset.campo}]`;
            });
            if (clave !== 'quimica') fila.querySelectorAll('[data-campo]').forEach(input => {
                input.name = `Tablas_Adicionales[${clave}][${indice}][${input.dataset.campo}]`;
            });
        });
        panel.querySelector('.agregar-filas-norma').addEventListener('click', () => {
            const cantidad = Math.max(1, Math.min(limite, Number(panel.querySelector('input[type="number"]').value) || 1));
            for (let i = 0; i < cantidad && cuerpo.children.length < limite; i++) {
                const fila = panel.querySelector('template').content.cloneNode(true);
                const grado = fila.querySelector('[data-campo="grado"]');
                if (grado) grado.value = document.getElementById('norma-variable').value;
                cuerpo.appendChild(fila);
            }
            renumerar();
        });
        cuerpo.addEventListener('click', event => {
            const boton = event.target.closest('.quitar-fila-norma');
            if (!boton) return;
            boton.closest('tr').remove();
            renumerar();
        });
    });
    const form = document.getElementById('NormasIMForm');
    const pestaña = document.getElementById('norma-pestana-inicial')?.value;
    if (pestaña && window.jQuery) window.jQuery(`#tab-${pestaña}`).tab('show');
    form?.addEventListener('click', event => {
        const boton = event.target.closest('button[type="submit"]');
        if (!boton) return;
        const seccion = boton.name === 'guardar_seccion' ? boton.value : null;
        form.querySelectorAll('.tab-pane').forEach(panel => {
            panel.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.disabled && !input.dataset.deshabilitadoPorSeccion) return;
                const deshabilitar = seccion && panel.id !== `panel-${seccion}`;
                input.disabled = Boolean(deshabilitar);
                if (deshabilitar) input.dataset.deshabilitadoPorSeccion = '1';
                else delete input.dataset.deshabilitadoPorSeccion;
            });
        });
    });
    form?.addEventListener('invalid', event => {
        const panel = event.target.closest('.tab-pane');
        if (panel && window.jQuery) window.jQuery(`a[href="#${panel.id}"]`).tab('show');
    }, true);
});
