@php
    $datosEquipoActual = $Datos_Equipo ?? [];
    $requiereEquipos = old('Datos_Equipo.REQUIERE_EQUIPOS', $datosEquipoActual['REQUIERE_EQUIPOS'] ?? 'no');
    $idsSeleccionados = old('Datos_Equipo.EQUIPOS_HERRAMIENTAS_IDS', $datosEquipoActual['EQUIPOS_HERRAMIENTAS_IDS'] ?? []);
    $idsSeleccionados = is_array($idsSeleccionados) ? array_map('intval', $idsSeleccionados) : [];

    $catalogoEquiposHerramientas = collect($idsGeneral_EyCs_Equipos ?? [])
        ->map(function ($item) {
            return [
                'id' => (int) $item->idGeneral_EyC,
                'tipo' => 'EQUIPOS',
                'nombre' => $item->Nombre_E_P_BP,
                'marca' => $item->Marca,
                'modelo' => $item->Modelo,
                'ns' => $item->Serie,
            ];
        })
        ->merge(
            collect($idsGeneral_EyCs_Herramientas ?? [])->map(function ($item) {
                return [
                    'id' => (int) $item->idGeneral_EyC,
                    'tipo' => 'HERRAMIENTAS',
                    'nombre' => $item->Nombre_E_P_BP,
                    'marca' => $item->Marca,
                    'modelo' => $item->Modelo,
                    'ns' => $item->Serie,
                ];
            })
        )
        ->sortBy('nombre')
        ->values();
@endphp

<div class="col-12">
    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">EQUIPOS Y HERRAMIENTAS</div>
</div>

<div class="col-sm-12">
    <div class="form-group">
        <label class="col-form-label d-block">¿Este formato requiere equipos o herramientas?</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input js-requiere-equipos" type="radio" name="Datos_Equipo[REQUIERE_EQUIPOS]" id="requiereEquiposSi" value="si" {{ $requiereEquipos === 'si' ? 'checked' : '' }}>
            <label class="form-check-label" for="requiereEquiposSi">Sí</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input js-requiere-equipos" type="radio" name="Datos_Equipo[REQUIERE_EQUIPOS]" id="requiereEquiposNo" value="no" {{ $requiereEquipos !== 'si' ? 'checked' : '' }}>
            <label class="form-check-label" for="requiereEquiposNo">No</label>
        </div>
    </div>
</div>

<div class="col-12 {{ $requiereEquipos === 'si' ? '' : 'd-none' }}" id="bloqueEquiposHerramientas">
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-info"></i> Importante</h5>
        <p>Puedes seleccionar un equipo o herramienta del menú y agregarlo a la tabla, o quitarlo si no corresponde.</p>
    </div>

    <div class="row align-items-end">
        <div class="col-sm-9">
            <div class="form-group">
                <label class="col-form-label" for="equiposHerramientasCatalogo">Equipo/Herramienta:</label>
                <select class="form-select inputForm" id="equiposHerramientasCatalogo">
                    <option value="" selected>Seleccione un equipo o herramienta</option>
                    @foreach($catalogoEquiposHerramientas as $item)
                        <option
                            value="{{ $item['id'] }}"
                            data-tipo="{{ $item['tipo'] }}"
                            data-nombre="{{ $item['nombre'] }}"
                            data-marca="{{ $item['marca'] }}"
                            data-modelo="{{ $item['modelo'] }}"
                            data-ns="{{ $item['ns'] }}"
                        >
                            {{ $item['nombre'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <button type="button" class="btn btn-success custom-btn w-100" id="agregarEquipoHerramientaBtn">Agregar</button>
            </div>
        </div>
    </div>

    <select
        class="d-none"
        id="equiposHerramientasSelect"
        name="Datos_Equipo[EQUIPOS_HERRAMIENTAS_IDS][]"
        multiple
    >
        @foreach($catalogoEquiposHerramientas as $item)
            <option
                value="{{ $item['id'] }}"
                data-tipo="{{ $item['tipo'] }}"
                data-nombre="{{ $item['nombre'] }}"
                data-marca="{{ $item['marca'] }}"
                data-modelo="{{ $item['modelo'] }}"
                data-ns="{{ $item['ns'] }}"
                {{ in_array((int) $item['id'], $idsSeleccionados, true) ? 'selected' : '' }}
            >
                {{ $item['nombre'] }}
            </option>
        @endforeach
    </select>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>N.S.</th>
                    <th style="width: 90px;">Eliminar</th>
                </tr>
            </thead>
            <tbody id="equiposHerramientasDetalleBody"></tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('.js-requiere-equipos');
    const bloque = document.getElementById('bloqueEquiposHerramientas');
    const catalogo = document.getElementById('equiposHerramientasCatalogo');
    const select = document.getElementById('equiposHerramientasSelect');
    const agregarBtn = document.getElementById('agregarEquipoHerramientaBtn');
    const tbody = document.getElementById('equiposHerramientasDetalleBody');

    if (!bloque || !catalogo || !select || !agregarBtn || !tbody) {
        return;
    }

    function getCatalogoOptionByValue(value) {
        return Array.from(catalogo.options).find(function (option) {
            return option.value === value;
        });
    }

    function renderDetalle() {
        tbody.innerHTML = '';

        Array.from(select.selectedOptions).forEach(function (option) {
            const row = document.createElement('tr');
            row.dataset.id = option.value;

            row.innerHTML =
                '<td>' + (option.dataset.nombre || '') + '</td>' +
                '<td>' + (option.dataset.marca || '') + '</td>' +
                '<td>' + (option.dataset.modelo || '') + '</td>' +
                '<td>' + (option.dataset.ns || '') + '</td>' +
                '<td><button type="button" class="btn btn-danger btn-sm js-quitar-equipo-herramienta" data-id="' + option.value + '"><i class="fa fa-times" aria-hidden="true"></i></button></td>';

            tbody.appendChild(row);
        });

        if (!tbody.children.length) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = '<td colspan="5">No hay equipos/herramientas agregados.</td>';
            tbody.appendChild(emptyRow);
        }
    }

    function agregarSeleccionado() {
        const selectedCatalogo = catalogo.options[catalogo.selectedIndex];

        if (!selectedCatalogo || !selectedCatalogo.value) {
            return;
        }

        const optionOculta = Array.from(select.options).find(function (option) {
            return option.value === selectedCatalogo.value;
        });

        if (optionOculta) {
            optionOculta.selected = true;
        }

        catalogo.value = '';
        renderDetalle();
    }

    function quitarSeleccionado(id) {
        const optionOculta = Array.from(select.options).find(function (option) {
            return option.value === id;
        });

        if (optionOculta) {
            optionOculta.selected = false;
        }

        renderDetalle();
    }

    function syncVisibility() {
        const requiere = document.querySelector('.js-requiere-equipos:checked');
        const mostrar = requiere && requiere.value === 'si';

        bloque.classList.toggle('d-none', !mostrar);

        if (!mostrar) {
            Array.from(select.options).forEach(function (option) {
                option.selected = false;
            });
            catalogo.value = '';
        }

        renderDetalle();
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', syncVisibility);
    });

    agregarBtn.addEventListener('click', agregarSeleccionado);

    tbody.addEventListener('click', function (event) {
        const button = event.target.closest('.js-quitar-equipo-herramienta');

        if (button) {
            quitarSeleccionado(button.dataset.id);
        }
    });

    Array.from(select.selectedOptions).forEach(function (option) {
        const catalogoOption = getCatalogoOptionByValue(option.value);

        if (catalogoOption) {
            option.dataset.tipo = catalogoOption.dataset.tipo || option.dataset.tipo || '';
            option.dataset.nombre = catalogoOption.dataset.nombre || option.dataset.nombre || option.textContent;
            option.dataset.marca = catalogoOption.dataset.marca || option.dataset.marca || '';
            option.dataset.modelo = catalogoOption.dataset.modelo || option.dataset.modelo || '';
            option.dataset.ns = catalogoOption.dataset.ns || option.dataset.ns || '';
        }
    });

    syncVisibility();
});
</script>
