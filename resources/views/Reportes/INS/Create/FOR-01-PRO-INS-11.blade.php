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

<h3 align="center">REPORTE DE: {{ $Prueba->Nombre }}</h3>
<h3 align="center">FORMATO: {{$Nombre_Formato}}</h3>
<h4 align="center">{{$formatoNombrePersonalizado}}</h4>
<br>
    <section class="content w-100">
        <div class="card w-100 p-3">
            <div class="card-body w-100">
                <form id="FOR-02-PRO-INS-11" action="{{route('Reportes_FOR_01_PRO_INS_11.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    
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

                        <!-- Select para elegir el número de firmas -->
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded my-2">Número de Firmas:</div>
                        <div class="col-sm-15">
                            <div class="form-group">
                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                    <option value="1">J-1</option>
                                    <option value="2">J-2</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Ojo izquierdo:</label>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Sin corrección</label>
                                <input type="text" class="form-control  inputForm" id="modeloInputbyp" name="Datos_Equipo[Ojo_derecho_SC]" placeholder="" value="{{old('Datos_Equipo.Ojo_derecho')}}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Con corrección</label>
                                <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[Ojo_izquierdo_CC]" placeholder="" value="{{old('Datos_Equipo.Ojo_izquierdo')}}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Ojo derecho:</label>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess"></label>
                                <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[Ojo_derecho_CN]" placeholder="Cumple / No cumple" value="{{old('Datos_Equipo.Ojo_derecho')}}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess"></label>
                                <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[Ojo_izquierdo_CN]" placeholder="Cumple / No cumple" value="{{old('Datos_Equipo.Ojo_izquierdo')}}">
                            </div>
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
                                <input type="text" class="form-control  inputForm" name="Detalles_Generales[Datos_Del_Examinador_Nombre]" placeholder="" value="{{old('Detalles_Generales.Datos_Del_Examinador_Nombre')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Dirección:</label>
                            <input type="text" class="form-control  inputForm" name="Detalles_Generales[Direccion]" placeholder="" value="{{old('Detalles_Generales.Direccion')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Telefono:</label>
                            <input type="text" class="form-control  inputForm" name="Detalles_Generales[Telefono]" placeholder="" value="{{old('Detalles_Generales.Telefono')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Registro / Licencia:</label>
                                <input type="text" class="form-control  inputForm" name="Detalles_Generales[COND_SUPER]" placeholder="" value="{{old('Detalles_Generales.COND_SUPER')}}">
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

    /*FOR-01-PRO-INS-11*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-01-PRO-INS-11');
        if (!form) return;

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('FOR-01-PRO-INS-11_' + el.name, el.value);
            //});
        //});

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-01-PRO-INS-11_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-01-PRO-INS-11_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-01-PRO-INS-11_' + el.name);
                //localStorage.clear();
            });
        });
    });
</script>
@endsection