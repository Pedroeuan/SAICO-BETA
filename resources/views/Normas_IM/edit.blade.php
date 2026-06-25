
@extends('adminlte::page')

@section('title', 'Normas IM')

@section('css')
<style>
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }
</style>
@endsection

@section('content')
    <br>
    <br>
    <br>
        <h3 align="center">Alta de Normas para Integridad Mecanica</h3>
    <br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center"> 
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Normas / Especificaciones</a></li>
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header p-2-->
                
                <div class="card-body">
                    <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <form id="NormasIMForm" method="post" enctype="multipart/form-data" action="{{route('Normas_IM.update',['id'=>$Normas_IM->idnormas_im])}}">
                                    @csrf
                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Nombre/Especificación</label>
                                                    <input type="text" class="form-control inputForm @error('NombreESP') is-invalid @enderror" value="{{ $Normas_IM->Nombre_Espe }}" name="NombreESP" placeholder="Ejemplo: ASTM A105" >
                                                    @error('NombreESP')
                                                            <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Variable/Subtitulo</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $Normas_IM->Variable }}" name="Variable" placeholder="Ejemplo: Over 3⁄4 in. (19 mm) to 1 1⁄2 in. (38 mm)" >
                                                </div>
                                            </div>

                                    <div class="table-responsive">
                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Elemento Químico / Chemical Elements</th>
                                                <th>PROMEDIOS DE LA PIEZA ANALIZADA / Average of the analyzed Piece</th>
                                                <th>COMPOSICIÓN QUÍMICA TEÓRICA / Theoretical Chemical Composition</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>

                                            <tbody>
                                            <!-- Filas dinámicas aparecerán aquí -->
                                            </tbody>
                                    </table>
                                    </div>
                                    <input type="hidden" name="Normas_IM" id="titulos_hidden">
                                    <!--<button id="addBtn" type="button" class="btn btn-success custom-btn">Agregar Fila</button>-->
                                    <div class="d-flex justify-content-between align-items-center w-100 mb-3">
                                        <div>
                                            <label for="numRows">Número de Filas:</label>
                                            <select id="numRows" class="form-select">
                                                @for ($i = 1; $i <= 500; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <button id="addBtn" type="button" class="btn btn-success custom-btn">Agregar Fila</button>
                                    </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Observaciones</label>
                                                    <textarea class="form-control is-waning" id="inputSuccess" name="Observaciones" placeholder="Ejemplo:">{{ $Normas_IM->Observaciones }}</textarea>
                                                </div>
                                            </div>
                                            <div class="container">
                                                <div class="float-right">
                                                    <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                                </div>
                                                <!--<div class="float-left">
                                                    <button type="button" class="btn btn-info bg-success" id="guardarContinuarClientes">Guardar y continuar</button>
                                                </div>-->
                                            </div>

                                        </div>
                                </form>
                        </div><!--"class="tab-pane active" id="tab_1"-->
                    </div><!-- /.tab-content -->
                </div><!-- class="card-body" -->
                        <!-- Agrega más paneles de tabs según sea necesario -->
            </div><!-- /.card -->       
        </div><!-- class="col-sm-12" -->
    </div><!-- class="row justify-content-center" -->
</div><!--class="container" -->     
@stop


@section('js')
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script src="{{ asset('js/Normas_IM.js') }}"></script>

<script>

$(document).ready(function() {
    let tituloCount = 0; //contador de títulos creados (se incrementa al añadir un título).
    let rowCount = 0; //contador de filas por título (se reinicia a 0 cuando se crea un nuevo título).
    let rowCountGlobal = 0; //contador global/visual de filas (se usa para numerar las filas en la tabla).
     /*Juntas-Resultados */
    function restoreData() {//-----------------------------------------------------------Reemplazar todo el resotedara
        const data = JSON.parse(sessionStorage.getItem('dynamicTableData') || 'null');
        if (!data) return;

        // Helpers y configuración-CONFIGURAR CAMPOS DE ACUERDO A LOS NAMES DE CADA INPUT
        const fieldNames = [
        'Elemento',
        'Promedio',
        'Composicion',
    ];
        const placeholders = { //CONFIGURAR CAMPOS DE ACUERDO A LOS PLACEHOLDERS DE CADA INPUT
            Elemento: 'No.',
            Promedio: 'Promedio',
            Composicion: 'Composición',
        };
        function esc(v){ return String(v || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,"&#39;"); }

        // Limpiar tabla y contadores
        $('#dynamicTable tbody').empty();
        tituloCount = 0;
        rowCount = 0;
        rowCountGlobal = 0;

        // Recrear títulos (manteniendo el id único guardado)
        (data.titles || []).forEach(function(t){
            tituloCount++;
            const titleId = t.id || `titulo_${tituloCount}_${Date.now()}`;
            const titleText = esc(t.text || '');

            //-----------------------------------------Hacer ajuste del colspan="15" de acuerdo a la tabla
            const newTitle = `
            <tr class="titulo-row" data-titulo="${titleId}">
                <td colspan="11">
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" class="form-control w-90 titulo-text" name="titulos_text[${titleId}]" value="${titleText}" placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3 (DETALLE DE OREJA DE IZAJE 1/4)">
                    <input type="hidden" class="titulo-id" name="titulos_ids[]" value="${titleId}">
                    <td><button type="button" class="btn btn-danger btnEliminarTitulo">
                    <i class="fa fa-times" aria-hidden="true"></i>
                    </button></td>
                </div>
                </td>
            </tr>
            `;
            $('#dynamicTable tbody').append(newTitle);
        });

        // Recrear filas (inserción debajo del título correspondiente)
        (data.rows || []).forEach(function(r){
            const titleId = r.titleId || 'sin_titulo';
            const vals = r.values || r.fields || []; // acepta array u objeto

            const inputsHtml = fieldNames.map(function(fn, idx){
                const value = Array.isArray(vals) ? (vals[idx] || '') : (vals[fn] || '');
                return `<td><input type="text" class="form-control" name="${fn}[${titleId}][]" value="${esc(value)}" placeholder="${esc(placeholders[fn] || '')}"></td>`;
            }).join('');

            const $newRow = $(`<tr data-titulo="${titleId}">
                <td class="row-number">0 <input type="hidden" value="0"></td>
                ${inputsHtml}
                <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
            </tr>`);

            const $titleRow = $(`#dynamicTable tbody tr.titulo-row[data-titulo="${titleId}"]`);

            if ($titleRow.length) {
                // Si ya hay filas para ese título, insertar después de la última de ellas
                const $lastRowSameTitle = $titleRow.nextAll(`tr[data-titulo="${titleId}"]:not(.titulo-row)`).last();
                if ($lastRowSameTitle.length) {
                    $lastRowSameTitle.after($newRow);
                } else {
                    $titleRow.after($newRow);
                }
            } else {
                // Título no existe (sin_titulo u otro caso) -> agregar al final
                $('#dynamicTable tbody').append($newRow);
            }
        });

        // Recrear Longitudes guardadas (data.longs)
        (data.longs || []).forEach(function(l){

            const titleId = l.titleId || 'sin_titulo';
            const value   = esc(l.text || '');
            //-----------------------------------------Hacer ajuste del colspan="14" de acuerdo a la tabla
            const newLong = `
                <tr class="long-row" data-titulo="${titleId}">
                    <td colspan="10">Longitud Inspeccionada</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="text"
                                class="form-control w-90 long-text"
                                name="Long_Inspecc[${titleId}][]"
                                value="${value}"
                                placeholder="Ingrese Longitud Inspeccionada...">
                            <td>
                                <button type="button" class="btn btn-danger btnEliminar">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </div>
                    </td>
                </tr>
            `;
            //-----------------------------------------Hacer ajuste de las filas a poner contando titulos y longitudes
            // 🔎 Buscar filas reales del bloque
            const $titleRow = $(`#dynamicTable tbody tr.titulo-row[data-titulo="${titleId}"]`);
            const $rowsBlock = $titleRow.nextUntil('.titulo-row');

            if ($rowsBlock.length >= 10) { // si hay al menos 11 filas en el bloque
                const $nfila = $rowsBlock
                    .not('.long-row')
                    .eq(9); // fila índice 9 = fila 10 (0-based)

                if ($nfila.length) { 
                    $nfila.after(newLong);
                } else {
                    $rowsBlock.last().after(newLong);
                }
            } else {
                // fallback: al final del bloque
                $rowsBlock.last().after(newLong);
            }

        });

        // Reindexar numeración visible y actualizar contadores
        function reindexRows(){
            let idx = 0;
            $('#dynamicTable tbody tr').not('.titulo-row, .long-row').each(function(){
                idx++;

                const td = $(this).find('td').eq(0);
                const textNode = td.contents().filter(function(){ 
                    return this.nodeType === 3; 
                }).first();

                if (textNode.length) {
                    textNode[0].nodeValue = idx + ' ';
                } else {
                    const hidden = td.find('input[type="hidden"]').prop('outerHTML');
                    td.html(idx + ' ' + hidden);
                }

                td.find('input[type="hidden"]').val(idx);
            });

            rowCountGlobal = idx;

            const lastTitleId = $('.titulo-row').last().data('titulo');

            rowCount = lastTitleId 
                ? $('#dynamicTable tbody tr')
                    .not('.titulo-row, .long-row')
                    .filter(function(){
                        return $(this).data('titulo') === lastTitleId;
                    }).length 
                : 0;
        }
        reindexRows();

        // Actualizaciones finales y guardado
        if (typeof updateTitulos === 'function') updateTitulos();
        // Guardar con el form más cercano a la tabla (compatibilidad con tu saveData existente)
        const formId = $('#dynamicTable').closest('form').attr('id') || (document.querySelectorAll('form')[1] && document.querySelectorAll('form')[1].id);
        //if (formId && typeof saveData === 'function') saveData(formId);
        }

        $('#addBtn').click(function () {
            let numFilas = parseInt($('#numRows').val());
            // Recontar filas existentes que NO son títulos
            rowCountGlobal = $('#dynamicTable tbody tr').not('.titulo-row, .long-row').length;
            let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

            for (let i = 0; i < numFilas; i++) {
            rowCount++; // Incrementar el contador general de filas
            rowCountGlobal++; // Incrementar el contador global de filas Solo es visualmente esta variable

            let newRow = `
                <tr data-titulo="${lastTitle}">
                    <td>${rowCountGlobal} <input type="hidden" value="${rowCount}">
                    <td><input type="text" class="form-control" name="Elemento[${lastTitle}][]" placeholder="Elemento" value="${rowCountGlobal}"></td>
                    <td><input type="text" class="form-control" name="Promedio[${lastTitle}][]" placeholder="Promedio"></td>
                    <td><input type="text" class="form-control" name="Composicion[${lastTitle}][]" placeholder="Composicion"></td>
                    <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                </tr>
            `;

                $('#dynamicTable tbody').append(newRow);
            }
            //saveData(document.querySelectorAll("form")[1].id);
            saveData($(this).closest('form').attr('id'));
        }
    );
            // Restaurar datos al cargar la página
            restoreData();
});

/*Prevenir el Enter */
document.getElementById('NormasIMForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

// Guardar datos en localStorage al escribir
document.querySelectorAll('#NormasIMForm input, #NormasIMForm textarea, #NormasIMForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('NormasIMForm' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#NormasIMForm input, #NormasIMForm textarea, #NormasIMForm select').forEach(function(input) {
        let value = localStorage.getItem('NormasIMForm' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('NormasIMForm').addEventListener('submit', function() {
    document.querySelectorAll('#NormasIMForm input, #NormasIMForm textarea, #NormasIMForm select').forEach(function(input) {
        localStorage.removeItem('NormasIMForm' + input.name);
    });
});
</script>
@endsection