@extends('adminlte::page')

@section('title', 'FOR-01-PRO-INS-11')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

<style>
        table {
            width: 100%; /* Opcional: Para que ocupe todo el ancho disponible */
            border-collapse: collapse; /* Elimina los espacios entre bordes */
        }

        table th, table td {
            text-align: center; /* Centra el texto horizontalmente */
            vertical-align: middle; /* Centra el texto verticalmente */
            padding: 8px; /* Espaciado interno para mayor claridad */
        }

        table input {
            text-align: center; /* Centra el texto dentro de los inputs */
            box-sizing: border-box; /* Garantiza que los inputs respeten los bordes */
        }

        .image-preview {
            width: 100%;
            max-width: 200px;
            height: 200px;
            border: 1px solid #ddd;
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain; /* Cambia a 'contain' para que la imagen se ajuste dentro del contenedor sin recortarse */
        }

        .custom-btn {
        background-color: #198754 !important; /* Verde */
        color: white !important;
        border: none !important;
        border-radius: 5px !important;
        cursor: pointer !important;
        }

        .custom-btn:hover {
            background-color: #218838 !important; /* Verde más oscuro */
        }
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
<br>
    <section class="content w-100">
        <div class="card w-100 p-3">
            <div class="card-body w-100">
                <form id="FOR-02-PRO-INS-10" action="{{route('Reportes_FOR_01_PRO_INS_10.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <h3 align="center">REPORTE DE: {{ $Prueba->Nombre }}</h3>
                    <h3 align="center">FORMATO: {{$Nombre_Formato}}</h3>
                    <h4 align="center">{{$formatoNombrePersonalizado}}</h4>
                    
                    <div class="row">
                    <button id="preFormBtn" type="button" class="btn btn-warning custom-btn my-2">Rellenar Campos Vacios "---"</button>
                    <div style="margin-bottom: 2px;"></div>
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Nombre</label>
                                <input type="text" class="form-control  inputForm @error('Nombre') is-invalid @enderror" name="Detalles_Generales[Nombre]"  placeholder="Ejemplo:" value="{{old('Detalles_Generales.Nombre')}}">
                                @error('No_Reporte')
                                        <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="hidden" class="form-control  inputForm " name="Detalles_Generales[idSolicitud]" value="{{ $idSolicitud }}" readonly>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="hidden" class="form-control  inputForm " name="idPrueba_Aplica" value="{{ $idPrueba_Aplica }}" readonly>
                            </div>
                        </div>

                        <!--***************************************** FIN DE DATOS GENERALES *****************************************-->
                        <!--***************************************** INICIO DATOS DEL EQUIPO *****************************************-->

                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">AGUDEZA VISUAL CERCANA</div>

                        <div style="margin-bottom: 2px;"></div>

                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-info"></i> Nota</h5>
                            <p>Se deberá establecer N/A en los campos que queden sin informacion.</p>
                        </div>

                                                <div class="col-sm-15 my-2">
                                            <div class="form-group">
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                                    <option value="2"> J-1</option>
                                                    <option value="3"> J-2</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="firmas2" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>
                                                        <label>Sin corrección &nbsp;&nbsp; Con corrección</label>
                                                        <th><input type="text" class="form-control  inputForm" name="Ojo_izquierdo" placeholder="Ejemplo: Ojo izquierdo" value="Ojo izquierdo" readonly></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Ojo_derecho" placeholder="Ejemplo: Ojo derecho" value="Ojo derecho" readonly></th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Ojo_izquierdo" placeholder="" value="{{old('Ojo_izquierdo')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Ojo_izquierdo" placeholder="" value="{{old('Ojo_izquierdo')}}"></td>
                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Ojo_derecho" placeholder="" value="{{old('Ojo_derecho')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Ojo_derecho" placeholder="" value="{{old('Ojo_derecho')}}"></td>
                                                    </tr>

                                                    <tr>

                                                </thead>                            
                                            </table>
                                        </div>
                        <div class="d-flex justify-content-center align-items-centerp-4 mb-3 bg-secondary text-white rounded">CONTRASTE DE COLOR / VISION DE COLORES </div>
                            <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-info"></i> Nota</h5>
                            <p>Se deberá establecer N/A en las pruebas no realizada.</p>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Ishihara:</label>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Ojo derecho:</label>
                                <input type="text" class="form-control  inputForm" id="modeloInputbyp" name="Datos_Equipo[Ojo_derecho]" placeholder="Cumple / No cumple" value="{{old('Datos_Equipo.Ojo_derecho')}}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Ojo izquierdo:</label>
                                <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[Ojo_izquierdo]" placeholder="Cumple / No cumple" value="{{old('Datos_Equipo.Ojo_izquierdo')}}">
                            </div>
                        </div>

                        
                        <div class="d-flex justify-content-center align-items-centerp-4 mb-3 bg-secondary text-white rounded">Placas Pseudoisocromáticas </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Placas Pseudoisocromáticas </label>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Diferenciación Rojo / Verde:</label>
                                <input type="text" class="form-control  inputForm" id="modeloInputbyp" name="Datos_Equipo[Diferenciación_Rojo_Verde]" placeholder="Cumple / No cumple" value="{{old('Datos_Equipo.Diferenciación_Rojo_Verde')}}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Diferenciación Azul / Amarillo:</label>
                                <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[Diferenciación_Azul_Amarillo]" placeholder="Cumple / No cumple" value="{{old('Datos_Equipo.Diferenciación_Azul_Amarillo')}}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">Datos Del Examinador</div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Nombre:</label>
                                <input type="text" class="form-control  inputForm" name="Datos_Equipo[GANANCIA]" placeholder="" value="{{old('Datos_Equipo.GANANCIA')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Dirección:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[Direccion]" placeholder="" value="{{old('Datos_Equipo.Direccion')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Telefono:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[Telefono]" placeholder="" value="{{old('Datos_Equipo.Telefono')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Registro / Licencia:</label>
                                <input type="text" class="form-control  inputForm" name="Datos_Equipo[COND_SUPER]" placeholder="" value="{{old('Datos_Equipo.COND_SUPER')}}">
                            </div>
                        </div>


                        
                        <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                        <!--***************************************** INICIO RESULTADOS *****************************************-->

                        

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Firma:</label>
                                </div>
                            </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Fecha:</label>
                                <input type="date" class="form-control  inputForm @error('Fecha') is-invalid @enderror" name="Detalles_Generales[Fecha]"  placeholder="Ejemplo: DD/MM/AAAA" value="{{old('Detalles_Generales.Fecha')}}">
                                @error('Fecha')
                                        <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                @enderror
                            </div>
                        </div>

                            <div class="container">
                                <div class="float-right">
                                    <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                </div>

                                <div class="float-left">
                                    <!--<button type="button" class="btn btn-info bg-success" id="guardarContinuarOC">Guardar y continuar</button>-->
                                </div>
                            </div>

                    </div>
                </form>
            </div>
        </div>
    </section>
@stop


@section('js')
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script src="{{ asset('js/Reportes_Create.js') }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">


<script>
    $(document).ready(function() {
        let tituloCount = 0;
        let rowCount = 0;
        let rowCountGlobal = 0;

        function restoreData() {
            //const savedData = sessionStorage.getItem('dynamicTableData');
            const savedData = JSON.parse(sessionStorage.getItem('dynamicTableData_' + document.querySelectorAll("form")[1].id));
            if (savedData) {
                // Restaurar contadores
                tituloCount = savedData.filter(item => item.type === 'titulo').length;
                rowCountGlobal = savedData.filter(item => item.type === 'fila').length;
                
                savedData.forEach((item) => {
                    if (item.type === 'titulo') {
                        let newTitle = `
                        <tr class="titulo-row" data-titulo="${item.id}">
                            <td colspan="22">
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="text" class="form-control w-90" name="titulos[]" value="${item.text}" placeholder="Ingrese título">
                                    <td><button type="button" class="btn btn-danger btnEliminarTitulo">
                                        <i class="fa fa-times"  aria-hidden="true"></i>
                                    </button></td>
                                </div>
                            </td>
                        </tr>`;
                        $('#dynamicTable tbody').append(newTitle);
                    } else if (item.type === 'fila') {
                        let newRow = `<tr data-titulo="${item.titulo}">
                            <td>${item.rowNumber} <input type="hidden" value="${item.rowNumber}"></td>
                            <td><input type="text" class="form-control" name="ID[${item.titulo}][]" value="${item.inputs[1]}" placeholder="ID"></td>
                            <td><input type="text" class="form-control" name="Elemento[${item.titulo}][]" value="${item.inputs[2]}" placeholder="Elemento"></td>
                            <td><input type="text" class="form-control" name="Nivel[${item.titulo}][]" value="${item.inputs[3]}" placeholder="Nivel"></td>
                            <td><input type="text" class="form-control" name="nom[${item.titulo}][]" value="${item.inputs[4]}" placeholder="Ønom"></td>
                            <td><input type="text" class="form-control" name="ext[${item.titulo}][]" value="${item.inputs[5]}" placeholder="Øext"></td>
                            <td><input type="text" class="form-control" name="no_ind[${item.titulo}][]" value="${item.inputs[6]}" placeholder="No. Indicación"></td>
                            <td><input type="text" class="form-control" name="Tipo_ind[${item.titulo}][]" value="${item.inputs[7]}" placeholder="Tipo de Indicación"></td>
                            <td><input type="text" class="form-control" name="G[${item.titulo}][]" value="${item.inputs[8]}" placeholder="G (dB)"></td>
                            <td><input type="text" class="form-control" name="NR[${item.titulo}][]" value="${item.inputs[9]}" placeholder="NR (%)"></td>
                            <td><input type="text" class="form-control" name="NI[${item.titulo}][]" value="${item.inputs[10]}" placeholder="NI (%)"></td>
                            <td><input type="text" class="form-control" name="DNR[${item.titulo}][]" value="${item.inputs[11]}" placeholder="DNR"></td>
                            <td><input type="text" class="form-control" name="Hora_Tec[${item.titulo}][]" value="${item.inputs[12]}" placeholder="Horario Técnico"></td>
                            <td><input type="text" class="form-control" name="sc[${item.titulo}][]" value="${item.inputs[13]}" placeholder="S.C."></td>
                            <td><input type="text" class="form-control" name="la[${item.titulo}][]" value="${item.inputs[14]}" placeholder="LA"></td>
                            <td><input type="text" class="form-control" name="lc[${item.titulo}][]" value="${item.inputs[15]}" placeholder="LC"></td>
                            <td><input type="text" class="form-control" name="tmin[${item.titulo}][]" value="${item.inputs[16]}" placeholder="tmin"></td>
                            <td><input type="text" class="form-control" name="d[${item.titulo}][]" value="${item.inputs[17]}" placeholder="d"></td>
                            <td><input type="text" class="form-control" name="ta[${item.titulo}][]" value="${item.inputs[18]}" placeholder="ta"></td>
                            <td><input type="text" class="form-control" name="Perd_Mate[${item.titulo}][]" value="${item.inputs[19]}" placeholder="Perdida de Material (%)"></td>
                            <td><input type="text" class="form-control" name="fotos[${item.titulo}][]" value="${item.inputs[20]}" placeholder="Fotos No."></td>
                            <td><input type="text" class="form-control" name="Observaciones[${item.titulo}][]" value="${item.inputs[21]}" placeholder="Observaciones"></td>
                            <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times"  aria-hidden="true"></i></button></td>
                        </tr>`;
                        $('#dynamicTable tbody').append(newRow);
                    }
                });
                updateRowNumbers();
                updateTitulos();
            }
        }

        $('#addTituloBtn').click(function () {
            tituloCount++;
            rowCount = 0; // Reiniciar el contador de filas para este título

            let newTitle = `
            <tr class="titulo-row" data-titulo="titulo_${tituloCount}">
                <td colspan="22">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control w-90" name="titulos[]" placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3 (DETALLE DE OREJA DE IZAJE 1/4)">
                        <td><button type="button" class="btn btn-danger btnEliminarTitulo">
                            <i class="fa fa-times"  aria-hidden="true"></i>
                        </button></td>
                    </div>
                </td>
            </tr>
        `;

        $('#dynamicTable tbody').append(newTitle);
        updateTitulos(); // Actualizar lista de títulos
        saveData(document.querySelectorAll("form")[1].id);
        });

        $('#addBtn').click(function () {
            let numFilas = parseInt($('#numRows').val());
            // Recontar filas existentes que NO son títulos
            rowCountGlobal = $('#dynamicTable tbody tr:not(.titulo-row)').length;
            let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

            for (let i = 0; i < numFilas; i++) {
            rowCount++; // Incrementar el contador general de filas
            rowCountGlobal++; // Incrementar el contador global de filas Solo es visualmente esta variable

            let newRow = 
                `<tr data-titulo="${lastTitle}">
                    <td>${rowCountGlobal} <input type="hidden" value="${rowCount}"></td>
                    <td><input type="text" class="form-control" name="ID[${lastTitle}][]" placeholder="ID" value="${rowCountGlobal}"></td>
                    <td><input type="text" class="form-control" name="Elemento[${lastTitle}][]" placeholder="Elemento"></td>
                    <td><input type="text" class="form-control" name="Nivel[${lastTitle}][]" placeholder="Nivel"></td>
                    <td><input type="text" class="form-control" name="nom[${lastTitle}][]" placeholder="Ønom"></td>
                    <td><input type="text" class="form-control" name="ext[${lastTitle}][]" placeholder="Øext"></td>
                    <td><input type="text" class="form-control" name="no_ind[${lastTitle}][]" placeholder="No. Indicación"></td>
                    <td><input type="text" class="form-control" name="Tipo_ind[${lastTitle}][]" placeholder="Tipo de Indicación"></td>
                    <td><input type="text" class="form-control" name="G[${lastTitle}][]" placeholder="G (dB)"></td>
                    <td><input type="text" class="form-control" name="NR[${lastTitle}][]" placeholder="NR (%)"></td>
                    <td><input type="text" class="form-control" name="NI[${lastTitle}][]" placeholder="NI (%)"></td>
                    <td><input type="text" class="form-control" name="DNR[${lastTitle}][]" placeholder="DNR"></td>
                    <td><input type="text" class="form-control" name="Hora_Tec[${lastTitle}][]" placeholder="Horario Técnico"></td>
                    <td><input type="text" class="form-control" name="sc[${lastTitle}][]" placeholder="S.C."></td>
                    <td><input type="text" class="form-control" name="la[${lastTitle}][]" placeholder="LA"></td>
                    <td><input type="text" class="form-control" name="lc[${lastTitle}][]" placeholder="LC"></td>
                    <td><input type="text" class="form-control" name="tmin[${lastTitle}][]" placeholder="tmin"></td>
                    <td><input type="text" class="form-control" name="d[${lastTitle}][]" placeholder="d"></td>
                    <td><input type="text" class="form-control" name="ta[${lastTitle}][]" placeholder="ta"></td>
                    <td><input type="text" class="form-control" name="Perd_Mate[${lastTitle}][]" placeholder="Perdida de Material (%)"></td>
                    <td><input type="text" class="form-control" name="fotos[${lastTitle}][]" placeholder="Fotos No."></td>
                    <td><input type="text" class="form-control" name="Observaciones[${lastTitle}][]" placeholder="Observaciones"></td>
                    <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times"  aria-hidden="true"></i></button></td>
                    
                </tr>`;

                $('#dynamicTable tbody').append(newRow);
            }
            saveData(document.querySelectorAll("form")[1].id);
        }
    );

        $('form').submit(function(e) {
            // Validar que la tabla no esté vacía
            if ($('#dynamicTable tbody tr').length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La tabla no puede estar vacía. Por favor, agregue al menos una fila.',
                });
                return;
            }

            // Eliminar los datos de sessionStorage
            //sessionStorage.removeItem('dynamicTableData'); // Borra solo los datos de la tabla
            sessionStorage.clear(); // Alternativa: Borra todo el sessionStorage
            // Deshabilitar el botón de submit y cambiar el texto (opcional)
            let submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true).text('Guardando...');
            // Opcional: Agregar un indicador de carga
            submitButton.append(' <i class="fa fa-spinner fa-spin"></i>');
        });

            // Restaurar datos al cargar la página
            restoreData();
    });

    /*Selects */
    $(document).ready(function() {
        function actualizarInputsE() {
            var selectedOption = $('#equiposSelect').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var marca = selectedOption.data('marca') || '';
            var modelo = selectedOption.data('modelo') || '';
            var ns = selectedOption.data('ns') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#marcaInputE').val(marca);
            $('#modeloInputE').val(modelo);
            $('#nsInputE').val(ns);
        }

            const selectedOptionLocalE = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Equipos');
            selectedOptionLocalE != null ?  ($('#equiposSelect').val(selectedOptionLocalE),actualizarInputsE()):"";

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect').on('change', function() {
                actualizarInputsE();
            });
        });

        $(document).ready(function() {
            function actualizarInputsA() {
                var selectedOption = $('#accesoriosSelect').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA').val(marca);
                $('#modeloInputA').val(modelo);
                $('#nsInputA').val(ns);
            }

            const selectedOptionLocalA = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Accesorios');
            selectedOptionLocalA != null ?  ($('#accesoriosSelect').val(selectedOptionLocalA),actualizarInputsA()):"";

                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect').on('change', function() {
                    actualizarInputsA();
                });
                
            });

        $(document).ready(function() {
            function actualizarInputsbyp() {
                var selectedOption = $('#blockyprobetaSelect').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputbyp').val(marca);
                $('#modeloInputbyp').val(modelo);
                $('#nsInputbyp').val(ns);
            }

            const selectedOptionLocalbyp = localStorage.getItem(document.querySelectorAll("form")[1].id+'_ByP');
            selectedOptionLocalbyp != null ?  ($('#blockyprobetaSelect').val(selectedOptionLocalbyp),actualizarInputsbyp()):"";

            // Evento cuando se cambia la selección en el select
            $('#blockyprobetaSelect').on('change', function() {
                actualizarInputsbyp();
            });
        });

    /*FOR-01-PRO-INS-10*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-01-PRO-INS-10');
        if (!form) return;

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('FOR-01-PRO-INS-10_' + el.name, el.value);
            //});
        //});

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-01-PRO-INS-10_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-01-PRO-INS-10_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-01-PRO-INS-10_' + el.name);
                //localStorage.clear();
            });
        });
    });
</script>
@endsection