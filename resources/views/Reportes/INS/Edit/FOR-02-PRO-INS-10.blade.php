@extends('adminlte::page')

@section('title', 'FOR-02-PRO-INS-10')

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

<h3 align="center">REPORTE DE: {{ $Prueba }}</h3>
<h3 align="center">FORMATO: {{ $Nombre_Formato }}</h3>
<h4 align="center">{{ $formatoNombrePersonalizado }}</h4>
<br>
                <section class="content w-100">
                    <div class="card w-100">
                        <div class="card-body row w-100">
                            <form id="FOR-02-PRO-INS-10" action="{{route('Reportes_FOR_02_PRO_INS_10.store')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn">Pre-rellenar Formulario "---"</button>
                                <div style="margin-bottom: 2px;"></div>
                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                                            <input type="date" class="form-control  inputForm @error('Fecha') is-invalid @enderror" name="Detalles_Generales[Fecha]"  value="{{ old('Detalles_Generales.Fecha', $Detalles_Generales['Fecha'] ?? '') }}">
                                            @error('Fecha')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Reporte</label>
                                            <input type="text" class="form-control  inputForm @error('No_Reporte') is-invalid @enderror" name="Detalles_Generales[No_Reporte]"  placeholder="Ejemplo: 077-8DUCTOS-24" value="{{old('Detalles_Generales.No_Reporte', $Detalles_Generales['No_Reporte'] ?? '')}}">
                                            @error('No_Reporte')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control  inputForm @error('Cliente') is-invalid @enderror" name="Detalles_Generales[Cliente]"  placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{old('Detalles_Generales.Cliente', $Detalles_Generales['Cliente'] ?? '')}}">
                                            @error('Cliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control  inputForm @error('Contrato') is-invalid @enderror" name="Detalles_Generales[Contrato]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Contrato', $Detalles_Generales['Contrato'] ?? '')}}">
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <input type="text" class="form-control  inputForm @error('Proyecto') is-invalid @enderror" name="Detalles_Generales[Proyecto]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Proyecto', $Detalles_Generales['Proyecto'] ?? '')}}">
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo</label>
                                            <input type="text" class="form-control  inputForm @error('Orden_Trabajo') is-invalid @enderror" name="Detalles_Generales[Orden_Trabajo]"  placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . . " value="{{old('Detalles_Generales.Orden_Trabajo', $Detalles_Generales['Orden_Trabajo'] ?? '')}}">
                                            @error('Orden_Trabajo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Folio</label>
                                            <input type="text" class="form-control  inputForm @error('Folio') is-invalid @enderror" name="Detalles_Generales[Folio]"  placeholder="Ejemplo:" value="{{old('Detalles_Generales.Folio', $Detalles_Generales['Folio'] ?? '')}}">
                                            @error('Folio')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Partida</label>
                                            <input type="text" class="form-control  inputForm @error('Partida') is-invalid @enderror" name="Detalles_Generales[Partida]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Partida', $Detalles_Generales['Partida'] ?? '')}}">
                                            @error('Partida')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Lugar</label>
                                            <input type="text" class="form-control  inputForm @error('Lugar') is-invalid @enderror" name="Detalles_Generales[Lugar]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Lugar', $Detalles_Generales['Lugar'] ?? '')}}">
                                            @error('Lugar')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Isometrico/Plano</label>
                                            <input type="text" class="form-control  inputForm @error('Isometrico_Plano') is-invalid @enderror" name="Detalles_Generales[Isometrico_Plano]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Isometrico_Plano', $Detalles_Generales['Isometrico_Plano'] ?? '')}}">
                                            @error('Isometrico_Plano')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Pieza</label>
                                            <input type="text" class="form-control  inputForm @error('Pieza') is-invalid @enderror" name="Detalles_Generales[Pieza]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Pieza', $Detalles_Generales['Pieza'] ?? '')}}">
                                            @error('Pieza')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Material</label>
                                            <input type="text" class="form-control  inputForm @error('Material') is-invalid @enderror" name="Detalles_Generales[Material]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Material', $Detalles_Generales['Material'] ?? '')}}">
                                            @error('Material')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Procedimiento</label>
                                            <input type="text" class="form-control  inputForm @error('Procedimiento') is-invalid @enderror" name="Detalles_Generales[Procedimiento]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Procedimiento', $Detalles_Generales['Procedimiento'] ?? '')}}">
                                            @error('Procedimiento')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Criterio de Evaluación</label>
                                            <input type="text" class="form-control  inputForm @error('Criterio_Evaluacion') is-invalid @enderror" name="Detalles_Generales[Criterio_Evaluacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Criterio_Evaluacion', $Detalles_Generales['Criterio_Evaluacion'] ?? '')}}">
                                            @error('Criterio_Evaluacion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="hidden" class="form-control  inputForm " name="idPrueba_Aplica" value="{{ $idPrueba_Aplica }}" readonly>
                                        </div>
                                    </div>

                                    @php 
                                    //dd($Datos_Equipo['MARCA_EQUIPO']);
                                    @endphp

                                    <!--***************************************** FIN DE DATOS GENERALES *****************************************-->
                                    <!--***************************************** INICIO DATOS DEL EQUIPO *****************************************-->

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DEL EQUIPO</div>

                                    <div style="margin-bottom: 2px;"></div>

                                    <div class="alert alert-info alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-info"></i> Importante</h5>
                                        <p>Puedes Seleccionar un equipo, accesorio o block del menu o escribir directamente</p>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">EQUIPO</div>

                                    <!-- Select para Equipos -->
                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Equipos:</label>
                                            <select class="form-control inputForm" name="equipos" id="equiposSelect">
                                            <option value="" selected disabled>Seleccione un equipo</option> <!-- Opción por defecto -->
                                                @foreach($idsGeneral_EyCs_Equipos as $equipo)
                                                    <option value="{{ $equipo->idGeneral_EyC }}"
                                                            data-marca="{{ $equipo->Marca }}"
                                                            data-modelo="{{ $equipo->Modelo }}"
                                                            data-ns="{{ $equipo->Serie }}">
                                                        {{ $equipo->Nombre_E_P_BP }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" id="marcaInputE" name="Datos_Equipo[MARCA_EQUIPO]" placeholder="" value="{{ old('Datos_Equipo.MARCA_EQUIPO', $Datos_Equipo['MARCA_EQUIPO'] ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputE" name="Datos_Equipo[MODELO_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.MODELO_EQUIPO', $Datos_Equipo['MODELO_EQUIPO'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" id="nsInputE" name="Datos_Equipo[N_S_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.N_S_EQUIPO', $Datos_Equipo['N_S_EQUIPO'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">TRANSDUCTOR</div>

                                    <!-- Select para Accesorios -->
                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Accesorios:</label>
                                            <select class="form-control inputForm" name="accesorios" id="accesoriosSelect">
                                            <option value="" selected disabled>Seleccione un Accesorio</option>
                                                @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                                    <option value="{{ $accesorios->idGeneral_EyC }}"
                                                            data-marca="{{ $accesorios->Marca }}"
                                                            data-modelo="{{ $accesorios->Modelo }}"
                                                            data-ns="{{ $accesorios->Serie }}">
                                                        {{ $accesorios->Nombre_E_P_BP }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" id="marcaInputA" name="Datos_Equipo[MARCA_TRANSDUCTOR]" placeholder="" value="{{old('Datos_Equipo.MARCA_TRANSDUCTOR', $Datos_Equipo['MARCA_TRANSDUCTOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputA" name="Datos_Equipo[MODELO_TRANSDUCTOR]" placeholder="" value="{{old('Datos_Equipo.MODELO_TRANSDUCTOR', $Datos_Equipo['MODELO_TRANSDUCTOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" id="nsInputA" name="Datos_Equipo[N_S_TRANSDUCTOR]" placeholder="" value="{{old('Datos_Equipo.N_S_TRANSDUCTOR', $Datos_Equipo['N_S_TRANSDUCTOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">FRECC:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FRECC_TRANSDUCTOR]" placeholder="" value="{{old('Datos_Equipo.FRECC_TRANSDUCTOR', $Datos_Equipo['FRECC_TRANSDUCTOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">BLOCK DE REFERENCIA</div>

                                    <!-- Select para Block y Probeta -->
                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Block y Probeta:</label>
                                            <select class="form-control inputForm" name="blockyprobeta" id="blockyprobetaSelect">
                                            <option value="" selected disabled>Seleccione un Block o Probeta</option>
                                                @foreach($idsGeneral_EyCs_BlockyProbeta as $blockyprobeta)
                                                    <option value="{{ $blockyprobeta->idGeneral_EyC }}"
                                                            data-marca="{{ $blockyprobeta->Marca }}"
                                                            data-modelo="{{ $blockyprobeta->Modelo }}"
                                                            data-ns="{{ $blockyprobeta->Serie }}">
                                                        {{ $blockyprobeta->Nombre_E_P_BP }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" id="marcaInputbyp" name="Datos_Equipo[MARCA_BLOCK]" placeholder="" value="{{old('Datos_Equipo.MARCA_BLOCK', $Datos_Equipo['MARCA_BLOCK'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputbyp" name="Datos_Equipo[MODELO_BLOCK]" placeholder="" value="{{old('Datos_Equipo.MODELO_BLOCK', $Datos_Equipo['MODELO_BLOCK'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[N_S_BLOCK]" placeholder="" value="{{old('Datos_Equipo.N_S_BLOCK', $Datos_Equipo['N_S_BLOCK'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ACOPLANTE (MARCA Y TIPO):</div>
                                    <div>
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ACOPLANTE]" placeholder="" value="{{old('Datos_Equipo.ACOPLANTE', $Datos_Equipo['ACOPLANTE'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">LONGITUD DEL CABLE:</div>
                                    <div>
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[LONGITUD_CABLE]" placeholder="" value="{{old('Datos_Equipo.LONGITUD_CABLE', $Datos_Equipo['LONGITUD_CABLE'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="alert alert-secondary" role="alert"></div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">GANANCIA:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control  inputForm" name="Datos_Equipo[GANANCIA]" placeholder="" value="{{ old('Datos_Equipo.GANANCIA', $Datos_Equipo['GANANCIA'] ?? '') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">db</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">RANGO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[RANGO]" placeholder="" value="{{old('Datos_Equipo.RANGO', $Datos_Equipo['RANGO'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">RECHAZO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[RECHAZO]" placeholder="" value="{{old('Datos_Equipo.RECHAZO', $Datos_Equipo['RECHAZO'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SUPERFICIE:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[SUPERFICIE]" placeholder="" value="{{old('Datos_Equipo.SUPERFICIE', $Datos_Equipo['SUPERFICIE'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">PINTURA:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[PINTURA]" placeholder="" value="{{old('Datos_Equipo.PINTURA', $Datos_Equipo['PINTURA'] ?? '')}}">
                                        </div>
                                    </div>

                                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                                    <!--***************************************** INICIO RESULTADOS *****************************************-->

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>
                                    
                                    <div style="margin-bottom: 2px;"></div>

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

                                        <button id="preFillBtn" type="button" class="btn btn-warning custom-btn">Pre-rellenar Tabla "---"</button>
                                    </div>

                                    <div class="table-responsive">
                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                                        <thead>
                                                <tr>
                                                    <th colspan="7">DATOS DEL MATERIAL</th>
                                                    <th colspan="8">DATOS DE LA INDICACIÓN</th>
                                                    <th colspan="4">RESULTADOS DE LA INSPECCIÓN</th>
                                                    <th rowspan="2">Observaciones</th>
                                                    <th rowspan="2">Eliminar</th>
                                                </tr>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Elemento / Tubo</th>
                                                    <th>No. Aceptación</th>
                                                    <th>No. Serie</th>
                                                    <th>No. Colada</th>
                                                    <th>tnominal</th>
                                                    <th>Ø</th>
                                                    <th>No.Ind.</th>
                                                    <th>Tipo de Indicación</th>
                                                    <th>NR (%)</th>
                                                    <th>NI (%)</th>
                                                    <th>H.T.</th>
                                                    <th>Prof</th>
                                                    <th>LA</th>
                                                    <th>LC</th>
                                                    <th>tmáx</th>
                                                    <th>tmin</th>
                                                    <th>Metros Lineales</th>
                                                    <th>Evaluación</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($Grupo_Juntas_Re as $index => $junta)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td><input type="text" class="form-control" name="elemento_tubo[]" value="{{ $junta['elemento_tubo'] }}" placeholder="Elemento / Tubo" style="width: 80px;"></td>
                                                        <td><input type="text" class="form-control" name="no_aceptacion[]" value="{{ $junta['no_aceptacion'] }}" placeholder="No. Aceptación" style="width: 80px;"></td>
                                                        <td><input type="text" class="form-control" name="no_serie[]" value="{{ $junta['no_serie'] }}" placeholder="No. Serie" style="width: 80px;"></td>
                                                        <td><input type="text" class="form-control" name="no_colada[]" value="{{ $junta['no_colada'] }}" placeholder="No. Colada" style="width: 100px;"></td>
                                                        <td><input type="text" class="form-control" name="tnominal[]" value="{{ $junta['tnominal'] }}" placeholder="tnominal" style="width: 80px;"></td>
                                                        <td><input type="text" class="form-control" name="diametro[]" value="{{ $junta['diametro'] }}" placeholder="Ø" style="width: 60px;"></td>
                                                        <td><input type="text" class="form-control" name="no_ind[]" value="{{ $junta['no_ind'] }}" placeholder="No.Ind." style="width: 50px;"></td>
                                                        <td><input type="text" class="form-control" name="tipo_indicacion[]" value="{{ $junta['tipo_indicacion'] }}" placeholder="Tipo de Indicación"></td>
                                                        <td><input type="text" class="form-control" name="nr[]" value="{{ $junta['nr'] }}" placeholder="NR (%)" style="width: 50px;"></td>
                                                        <td><input type="text" class="form-control" name="ni[]" value="{{ $junta['ni'] }}" placeholder="NI (%)" style="width: 50px;"></td>
                                                        <td><input type="text" class="form-control" name="ht[]" value="{{ $junta['ht'] }}" placeholder="H.T." style="width: 50px;"></td>
                                                        <td><input type="text" class="form-control" name="prof[]" value="{{ $junta['prof'] }}" placeholder="Prof" style="width: 50px;"></td>
                                                        <td><input type="text" class="form-control" name="la[]" value="{{ $junta['la'] }}" placeholder="LA" style="width: 50px;"></td>
                                                        <td><input type="text" class="form-control" name="lc[]" value="{{ $junta['lc'] }}" placeholder="LC" style="width: 50px;"></td>
                                                        <td><input type="text" class="form-control" name="tmax[]" value="{{ $junta['tmax'] }}" placeholder="tmáx" style="width: 80px;"></td>
                                                        <td><input type="text" class="form-control" name="tmin[]" value="{{ $junta['tmin'] }}" placeholder="tmin" style="width: 80px;"></td>
                                                        <td><input type="text" class="form-control" name="metros_lineales[]" value="{{ $junta['metros_lineales'] }}" placeholder="Metros Lineales" style="width: 60px;"></td>
                                                        <td><input type="text" class="form-control" name="evaluacion[]" value="{{ $junta['evaluacion'] }}" placeholder="Evaluación" style="width: 100px;"></td>
                                                        <td><input type="text" class="form-control" name="observaciones[]" value="{{ $junta['observaciones'] }}" placeholder="Observaciones" style="width: 150px;"></td>
                                                        <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                    </table>
                                    </div>
                                    <p>

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">SIMBOLOGÍA</div>

                                    <div style="margin-bottom: 2px;"></div>

                                    <table class="table table-bordered table-striped dt-responsive tablas">
                                        <tr>
                                            <td>
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th colspan="4" class="p-2 alert alert-warning">INDICACIONES Y HALLAZGOS</th>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>NPIR:</strong></td>
                                                            <td>NO PRESENTA INDICACIÓN RELEVANTE</td>
                                                            <td><strong>CI:</strong></td>
                                                            <td>CORROSIÓN INTERNA</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>I:</strong></td>
                                                            <td>INCLUSIÓN NO METÁLICA</td>
                                                            <td><strong>L:</strong></td>
                                                            <td>LAMINACIÓN</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>ZI:</strong></td>
                                                            <td>ZONA DE INCLUSIONES NO METALICAS</td>
                                                            <td colspan="2" rowspan="2"><strong></strong></td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>LE:</strong></td>
                                                            <td>LAMINACIÓN ESCALONADA</td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th colspan="6" class="p-2 alert alert-warning">SIMBOLOGÍA DEL REPORTE</th>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>A:</strong></td>
                                                            <td>ÁNGULO (°)</td>
                                                            <td><strong>LA:</strong></td>
                                                            <td>LONGITUD AXIAL (IN)</td>
                                                            <td rowspan="2"><strong>ta:</strong></td>
                                                            <td rowspan="2">ESPESOR DE LA PARED EN ZONA SANA ADYACENTE</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>G:</strong></td>
                                                            <td>GANANCIA (dB)</td>
                                                            <td><strong>LC:</strong></td>
                                                            <td>LONGITUD CIRCUNFERENCIAL (IN)</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>NR:</strong></td>
                                                            <td>NIVEL DE REFERENCIA (%)</td>
                                                            <td><strong>DNR:</strong></td>
                                                            <td>DISTANCIA DE NIVEL DE REFERENCIA (IN)</td>
                                                            <td><strong>H.T.</strong></td>
                                                            <td>HORARIO TÉCNICO</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>NI:</strong></td>
                                                            <td>NIVEL DE INDICACIÓN (%)</td>
                                                            <td><strong>Tmin:</strong></td>
                                                            <td>ESPESOR MÍNIMO REGISTRADO (PULG)</td>
                                                            <td><strong>d:</strong></td>
                                                            <td>PROFUNDIDAD DE LA INDICACION(IN)</td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Observaciones:</label>
                                                <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{old('Observaciones', $Datos_Equipo['Observaciones'] ?? '')}}</textarea>
                                            </div>
                                        </div>

                                        <!-- Select para elegir el número de firmas -->
                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">Número de Firmas:</div>
                                        <div class="col-sm-15">
                                            <div class="form-group">
                                                @php 
                                                //dd($numFirmas);
                                                @endphp
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                                    <option value="2" {{ $numFirmas == 2 ? 'selected' : '' }}>2 Firmas</option>
                                                    <option value="3" {{ $numFirmas == 3 ? 'selected' : '' }}>3 Firmas</option>
                                                    <option value="4" {{ $numFirmas == 4 ? 'selected' : '' }}>4 Firmas</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- 2 DOS FIRMAS-->
                                        <div id="firmas2" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th>Realizó</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_TECNICO]" placeholder="NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_ENCARGADO]" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO', $Firmas['NOMBRE_ENCARGADO'] ?? '')}}"></td>
                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[CARGO_TECNICO]" placeholder="CARGO DEL TECNICO" value="{{old('CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[PUESTO_ENCARGADO]" placeholder="PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO', $Firmas['PUESTO_ENCARGADO'] ?? '')}}"></td>
                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_ENCARGADO]" placeholder="EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO', $Firmas['EMPRESA_ENCARGADO'] ?? '')}}"></td>
                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 3 TRES FIRMAS-->
                                        <div id="firmas3" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th>Realizó</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_TECNICO]" placeholder="NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO]" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO', $Firmas['NOMBRE_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_2DO_ENCARGADO]" placeholder="NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_2DO_ENCARGADO', $Firmas['NOMBRE_2DO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO_TECNICO]" placeholder="CARGO DEL TECNICO" value="{{old('CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_ENCARGADO]" placeholder="PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO', $Firmas['PUESTO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_2DO_ENCARGADO]" placeholder="PUESTO DEL SEGUNDO ENCARGADO" value="{{old('PUESTO_2DO_ENCARGADO', $Firmas['PUESTO_2DO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_ENCARGADO]" placeholder="EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO', $Firmas['EMPRESA_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_2DO_ENCARGADO]" placeholder="EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO', $Firmas['EMPRESA_2DO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 4 CUATRO FIRMAS-->
                                        <div id="firmas4" class="col-12" style="display: none;">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th>Realizó</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_TECNICO]" placeholder="NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO]" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO', $Firmas['NOMBRE_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_2DO_ENCARGADO]" placeholder="NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_2DO_ENCARGADO', $Firmas['NOMBRE_2DO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_3RO_ENCARGADO]" placeholder="NOMBRE DEL TERCER ENCARGADO" value="{{old('NOMBRE_3RO_ENCARGADO', $Firmas['NOMBRE_3RO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO_TECNICO]" placeholder="CARGO DEL TECNICO" value="{{old('CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_ENCARGADO]" placeholder="PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO', $Firmas['PUESTO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_2DO_ENCARGADO]" placeholder="PUESTO DEL SEGUNDO ENCARGADO" value="{{old('PUESTO_2DO_ENCARGADO', $Firmas['PUESTO_2DO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_3RO_ENCARGADO]" placeholder="PUESTO DEL TERCER ENCARGADO" value="{{old('PUESTO_3RO_ENCARGADO', $Firmas['PUESTO_3RO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_ENCARGADO]" placeholder="EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO', $Firmas['EMPRESA_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_2DO_ENCARGADO]" placeholder="EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO', $Firmas['EMPRESA_2DO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_3RO_ENCARGADO]" placeholder="EMPRESA DEL TERCER ENCARGADO" value="{{old('EMPRESA_3RO_ENCARGADO', $Firmas['EMPRESA_3RO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <p>

                                        <!--IMAGENES CON COMENTARIOS-->

                                        <!-- Modal para recortar la imagen -->
                                        <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="cropperModalLabel">Recortar Imagen</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="img-container">
                                                            <img id="imageToCrop" src="" alt="Imagen a recortar" style="max-width: 100%;">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <button type="button" class="btn btn-primary" id="cropImageBtn">Recortar y Subir</button>
                                                        <button type="button" class="btn btn-primary" id="uploadWithoutCropBtn">Subir sin Recortar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Campos para subir imágenes y comentarios -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image1">Imagen 1:</label>
                                                <input type="file" class="form-control" id="image1" name="image1" accept="image/*">
                                                <div style="margin-bottom: 2px;"></div>
                                                <div class="image-preview" id="image1-preview"></div>
                                                @if(isset($Fotos_Comentarios[0]['path']))
                                                    <img src="{{ asset(str_replace('public/', 'storage/', $Fotos_Comentarios[0]['path'])) }}" alt="Imagen 1" style="max-width: 100%; max-height: 100%;">
                                                @endif
                                                <textarea class="form-control mt-2" name="comment1" placeholder="Comentario para la imagen 1">{{ old('comment1', $Fotos_Comentarios[0]['comment'] ?? '') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image2">Imagen 2:</label>
                                                <input type="file" class="form-control" id="image2" name="image2" accept="image/*">
                                                <div style="margin-bottom: 2px;"></div>
                                                <div class="image-preview" id="image2-preview"></div>
                                                @if(isset($Fotos_Comentarios[1]['path']))
                                                    <img src="{{ asset(str_replace('public/', 'storage/', $Fotos_Comentarios[1]['path'])) }}" alt="Imagen 1" style="max-width: 100%; max-height: 100%;">
                                                @endif
                                                <textarea class="form-control mt-2" name="comment2" placeholder="Comentario para la imagen 2">{{ old('comment1', $Fotos_Comentarios[1]['comment'] ?? '') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image3">Imagen 3:</label>
                                                <input type="file" class="form-control" id="image3" name="image3" accept="image/*">
                                                <div style="margin-bottom: 2px;"></div>
                                                <div class="image-preview" id="image3-preview"></div>
                                                @if(isset($Fotos_Comentarios[2]['path']))
                                                    <img src="{{ asset(str_replace('public/', 'storage/', $Fotos_Comentarios[2]['path'])) }}" alt="Imagen 1" style="max-width: 100%; max-height: 100%;">
                                                @endif
                                                <textarea class="form-control mt-2" name="comment3" placeholder="Comentario para la imagen 3">{{ old('comment1', $Fotos_Comentarios[2]['comment'] ?? '') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image4">Imagen 4:</label>
                                                <input type="file" class="form-control" id="image4" name="image4" accept="image/*">
                                                <div style="margin-bottom: 2px;"></div>
                                                <div class="image-preview" id="image4-preview"></div>
                                                @if(isset($Fotos_Comentarios[3]['path']))
                                                    <img src="{{ asset(str_replace('public/', 'storage/', $Fotos_Comentarios[3]['path'])) }}" alt="Imagen 1" style="max-width: 100%; max-height: 100%;">
                                                @endif
                                                <textarea class="form-control mt-2" name="comment4" placeholder="Comentario para la imagen 4">{{ old('comment1', $Fotos_Comentarios[3]['comment'] ?? '') }}</textarea>
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

<!-- Biblioteca para recorte de imagenes -->
<!-- Incluir Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<!-- Incluir Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

    /*Prevenir el Enter*/
    document.getElementById('FOR-02-PRO-INS-10').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    $(document).ready(function() {
        var rowCount = $('#dynamicTable tbody tr').length;

        function updateRowNumbers() {
            $('#dynamicTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
            rowCount = $('#dynamicTable tbody tr').length;
        }

        $('#addBtn').click(function() {
            var numRows = $('#numRows').val();
            for (var i = 0; i < numRows; i++) {
                rowCount++;
                var newRow = `<tr>
                    <td>${rowCount}</td>
                    <td><input type="text" class="form-control" name="elemento_tubo[]" placeholder="Elemento / Tubo" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="no_aceptacion[]" placeholder="No. Aceptación" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="no_serie[]" placeholder="No. Serie" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="no_colada[]" placeholder="No. Colada" style="width: 100px;"></td>
                    <td><input type="text" class="form-control" name="tnominal[]" placeholder="tnominal" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="diametro[]" placeholder="Ø" style="width: 60px;"></td>
                    <td><input type="text" class="form-control" name="no_ind[]" placeholder="No.Ind." style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="tipo_indicacion[]" placeholder="Tipo de Indicación"></td>
                    <td><input type="text" class="form-control" name="nr[]" placeholder="NR (%)" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="ni[]" placeholder="NI (%)" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="ht[]" placeholder="H.T." style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="prof[]" placeholder="Prof" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="la[]" placeholder="LA" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="lc[]" placeholder="LC" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="tmax[]" placeholder="tmáx" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="tmin[]" placeholder="tmin" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="metros_lineales[]" placeholder="Metros Lineales" style="width: 60px;"></td>
                    <td><input type="text" class="form-control" name="evaluacion[]" placeholder="Evaluación" style="width: 100px;"></td>
                    <td><input type="text" class="form-control" name="observaciones[]" placeholder="Observaciones" style="width: 150px;"></td>
                    <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                </tr>`;
                $('#dynamicTable tbody').append(newRow);
            }
        });

        $('#dynamicTable').on('click', '.btnEliminar', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });

        $('#preFillBtn').click(function() {
            $('#dynamicTable tbody tr').each(function() {
                $(this).find('input').each(function() {
                    if ($(this).val() === '') {
                        $(this).val('----');
                    }
                });
            });
        });

        // Mostrar vista previa de las imágenes seleccionadas

                // Mostrar vista previa de las imágenes seleccionadas
                $(document).ready(function() {
            var cropper;
            var currentInput;
            var currentPreview;

            // Función para mostrar la vista previa de la imagen
            function readURL(input, previewId) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewId).html('<img src="' + e.target.result + '" alt="Imagen" style="max-width: 100%; max-height: 100%;">');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Eventos para abrir el modal de recorte al seleccionar una imagen
            $('#image1').change(function() {
                currentInput = this;
                currentPreview = '#image1-preview';
                showCropperModal(this);
            });

            $('#image2').change(function() {
                currentInput = this;
                currentPreview = '#image2-preview';
                showCropperModal(this);
            });

            $('#image3').change(function() {
                currentInput = this;
                currentPreview = '#image3-preview';
                showCropperModal(this);
            });

            $('#image4').change(function() {
                currentInput = this;
                currentPreview = '#image4-preview';
                showCropperModal(this);
            });

            // Función para mostrar el modal de recorte
            function showCropperModal(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imageToCrop').attr('src', e.target.result);
                        $('#cropperModal').modal('show');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Inicializar Cropper.js cuando el modal se muestra
            $('#cropperModal').on('shown.bs.modal', function() {
                console.log('Modal abierto, inicializando Cropper');
                cropper = new Cropper(document.getElementById('imageToCrop'), {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                });
            });

            // Destruir Cropper.js cuando el modal se oculta
            $('#cropperModal').on('hidden.bs.modal', function() {
                console.log('Modal cerrado, destruyendo Cropper');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });

            // Evento para recortar la imagen y cerrar el modal
            $('#cropImageBtn').click(function() {
                if (cropper) {
                    var canvas = cropper.getCroppedCanvas({
                        width: 400,
                        height: 400,
                    });

                    canvas.toBlob(function(blob) {
                        var reader = new FileReader();
                        reader.readAsDataURL(blob);
                        reader.onloadend = function() {
                            var base64data = reader.result;
                            $(currentPreview).html('<img src="' + base64data + '" alt="Imagen" style="max-width: 100%; max-height: 100%;">');
                            $(currentInput).data('cropped', base64data);
                            $('#cropperModal').modal('hide');
                        }
                    });
                }
            });

            // Evento para subir la imagen sin recortar y cerrar el modal
            $('#uploadWithoutCropBtn').click(function() {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var base64data = e.target.result;
                    $(currentPreview).html('<img src="' + base64data + '" alt="Imagen" style="max-width: 100%; max-height: 100%;">');
                    $(currentInput).data('cropped', base64data);
                    $('#cropperModal').modal('hide');
                }
                reader.readAsDataURL(currentInput.files[0]);
            });

            // Evento para cerrar el modal manualmente al hacer clic en "Cancelar" o en la "X"
            $('.close, .btn-secondary').click(function() {
                console.log('Cerrar modal manualmente');
                $('#cropperModal').modal('hide');
            });

            // Evento para enviar el formulario con las imágenes recortadas
            $('form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('input[type="file"]').each(function() {
                    if ($(this).data('cropped')) {
                        var blob = dataURLtoBlob($(this).data('cropped'));
                        formData.append($(this).attr('name'), blob, $(this).attr('name') + '.png');
                    }
                });

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Respuesta del servidor:', response);
                    },
                    error: function(response) {
                        console.error('Error del servidor:', response);
                    }
                });
            });

            // Función para convertir una URL de datos (data URL) en un Blob
            function dataURLtoBlob(dataurl) {
                var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                    bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                while (n--) {
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new Blob([u8arr], { type: mime });
            }
        });

        /*function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(previewId).html('<img src="' + e.target.result + '" alt="Imagen" style="max-width: 100%; max-height: 100%;">');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#image1').change(function() {
            readURL(this, '#image1-preview');
        });

        $('#image2').change(function() {
            readURL(this, '#image2-preview');
        });

        $('#image3').change(function() {
            readURL(this, '#image3-preview');
        });

        $('#image4').change(function() {
            readURL(this, '#image4-preview');
        });*/


    });

    /*Pre-Rellenado del formulario */
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("preFormBtn").addEventListener("click", function () {
            // Seleccionar todos los inputs y textareas del formulario
            let inputs = document.querySelectorAll(".inputForm");
            let textareas = document.querySelectorAll("textarea");

            inputs.forEach(function (input) {
                if (input.value.trim() === "") { 
                    input.value = "---"; // Asignar "---" si está vacío
                }
            });

            textareas.forEach(function (textarea) {
                if (textarea.value.trim() === "") { 
                    textarea.value = "---"; // Asignar "---" si está vacío
                }
            });
        });
    });

        /*Selección de Firmas */

        document.addEventListener('DOMContentLoaded', function() {
        const numFirmasSelect = document.getElementById('numFirmas');
        const firmas2 = document.getElementById('firmas2');
        const firmas3 = document.getElementById('firmas3');
        const firmas4 = document.getElementById('firmas4');

        numFirmasSelect.addEventListener('change', function() {
            if (this.value == '2') {
                firmas2.style.display = 'block';
                firmas3.style.display = 'none';
                firmas4.style.display = 'none';
            }
            else if (this.value == '3') {
                firmas2.style.display = 'none';
                firmas3.style.display = 'block';
                firmas4.style.display = 'none';
            } else if (this.value == '4') {
                firmas2.style.display = 'none';
                firmas3.style.display = 'none';
                firmas4.style.display = 'block';
            }
        });

        // Inicializar la visibilidad de las secciones de firmas
        if (numFirmasSelect.value == '2') {
            firmas2.style.display = 'block';
            firmas3.style.display = 'none';
            firmas4.style.display = 'none';
        }
        else if (numFirmasSelect.value == '3') {
            firmas2.style.display = 'none';
            firmas3.style.display = 'block';
            firmas4.style.display = 'none';
        } else if (numFirmasSelect.value == '4') {
            firmas2.style.display = 'none';
            firmas3.style.display = 'none';
            firmas4.style.display = 'block';
        }
    });

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

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect').on('change', function() {
                actualizarInputsE();
            });

            // Seleccionar la primera opción si hay al menos una opción disponible
            /*if ($('#equiposSelect option').length > 0) {
                $('#equiposSelect').prop('selectedIndex', 0).trigger('change');
            }*/

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
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect').on('change', function() {
                    actualizarInputsA();
                });

                // Seleccionar la primera opción si hay al menos una opción disponible
                /*if ($('#accesoriosSelect option').length > 0) {
                    $('#accesoriosSelect').prop('selectedIndex', 0).trigger('change');
                }*/
                
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

            // Evento cuando se cambia la selección en el select
            $('#blockyprobetaSelect').on('change', function() {
                actualizarInputsbyp();
            });

            // Seleccionar la primera opción si hay al menos una opción disponible
            /*if ($('#blockyprobetaSelect option').length > 0) {
                $('#blockyprobetaSelect').prop('selectedIndex', 0).trigger('change');
            }*/
        });

    </script>
@endsection


