@extends('adminlte::page')

@section('title', 'FOR-01-PRO-INS-08')

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
<h3 align="center">REPORTE DE: {{ $Prueba->Nombre }}</h3>
<h3 align="center">FORMATO: {{$Nombre_Formato}}</h3>
<h4 align="center">{{$formatoNombrePersonalizado}}</h4>
<br>
                <section class="content w-100">
                    <div class="card w-100 p-3">
                        <div class="card-body w-100">
                            <form id="FOR-01-PRO-INS-08" action="{{route('Reportes_FOR_01_PRO_INS_08.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn my-2">Rellenar Campos Vacios "---"</button>
                                <div style="margin-bottom: 2px;"></div>
                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                                            {{--<input type="date" class="form-control inputForm @error('Fecha') is-invalid @enderror" name="Detalles_Generales[Fecha]" placeholder="Ejemplo: DD/MM/AAAA" value="{{old('Detalles_Generales.Fecha')}}"> --}}
                                            <input type="date" class="form-control inputForm @error('Detalles_Generales.Fecha') is-invalid @enderror" name="Detalles_Generales[Fecha]" placeholder="Ejemplo: DD/MM/AAAA" value="{{ old('Detalles_Generales.Fecha') }}">
                                            @error('Fecha')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Reporte</label>
                                            <input type="text" class="form-control  inputForm @error('No_Reporte') is-invalid @enderror" name="Detalles_Generales[No_Reporte]"  placeholder="Ejemplo: 077-8DUCTOS-24" value="{{old('Detalles_Generales.No_Reporte')}}">
                                            @error('No_Reporte')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control  inputForm @error('Cliente') is-invalid @enderror" name="Detalles_Generales[Cliente]"  placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{old('Detalles_Generales.Cliente')}}">
                                            @error('Cliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control  inputForm @error('Contrato') is-invalid @enderror" name="Detalles_Generales[Contrato]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Contrato')}}">
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Proyecto]" placeholder="Ejemplo: INGENIERÍA, PROCURA, CONSTRUCCIÓN DE DUCTOS MARINOS NUEVOS PARA MANEJO DE PRODUCCIÓN DE PLATAFORMAS GENÉRICAS, A INSTALARSE EN LA SONDA DE CAMPECHE, GOLFO DE MÉXICO ...">{{old('Detalles_Generales.Proyecto')}}</textarea>
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Orden_Trabajo]" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . .">{{old('Detalles_Generales.Orden_Trabajo')}}</textarea>
                                            @error('Orden_Trabajo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Folio</label>
                                            <input type="text" class="form-control  inputForm @error('Folio') is-invalid @enderror" name="Detalles_Generales[Folio]"  placeholder="Ejemplo:" value="{{old('Detalles_Generales.Folio')}}">
                                            @error('Folio')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Partida</label>
                                            <input type="text" class="form-control  inputForm @error('Partida') is-invalid @enderror" name="Detalles_Generales[Partida]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Partida')}}">
                                            @error('Partida')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Lugar</label>
                                            <input type="text" class="form-control  inputForm @error('Lugar') is-invalid @enderror" name="Detalles_Generales[Lugar]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Lugar')}}">
                                            @error('Lugar')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Isometrico/Plano</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Isometrico_Plano]" placeholder="Ejemplo: D-7205-TENTOK-A-Q-200 / D-7205-TENTOK-A-Q-201 / D-7205-TENTOK-A-Q-202 / D-7205-TENTOK-A-Q-203 / D-7205-TENTOK-A-Q-204 / D-7205-TENTOK-A-Q-205 /D-7205-TENTOK-A-Q-206 / D-7205-TENTOK-A-Q-207 / D-7205-TENTOK-A-Q-208 / D-7205-TENTOK-A-Q-209 . . . .">{{old('Detalles_Generales.Isometrico_Plano')}}</textarea>
                                            @error('Isometrico_Plano')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Pieza</label>
                                            <input type="text" class="form-control  inputForm @error('Pieza') is-invalid @enderror" name="Detalles_Generales[Pieza]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Pieza')}}">
                                            @error('Pieza')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Material</label>
                                            <input type="text" class="form-control  inputForm @error('Material') is-invalid @enderror" name="Detalles_Generales[Material]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Material')}}">
                                            @error('Material')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Procedimiento</label>
                                            <input type="text" class="form-control  inputForm @error('Procedimiento') is-invalid @enderror" name="Detalles_Generales[Procedimiento]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Procedimiento')}}">
                                            @error('Procedimiento')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Criterio de Evaluación</label>
                                            <input type="text" class="form-control  inputForm @error('Criterio_Evaluacion') is-invalid @enderror" name="Detalles_Generales[Criterio_Evaluacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Criterio_Evaluacion')}}">
                                            @error('Criterio_Evaluacion')
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

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DEL EQUIPO</div>

                                    <div style="margin-bottom: 2px;"></div>

                                    <div class="alert alert-info alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-info"></i> Importante</h5>
                                        <p>Puedes Seleccionar un equipo, transductor y un block del menu o escribir directamente</p>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">EQUIPO</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Equipos:</label>
                                            <select class="form-select inputForm" name="equipos" id="equiposSelect">
                                            <option value="" selected disabled>Seleccione un Equipo</option> <!-- Opción por defecto -->
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
                                            <input type="text" class="form-control  inputForm" id="marcaInputE" name="Datos_Equipo[MARCA_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.MARCA_EQUIPO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputE" name="Datos_Equipo[MODELO_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.MODELO_EQUIPO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" id="nsInputE" name="Datos_Equipo[NS_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.NS_EQUIPO')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">TRANSDUCTOR</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Transductores:</label>
                                            <select class="form-select inputForm" name="accesorios" id="accesoriosSelect">
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
                                            <input type="text" class="form-control  inputForm" id="marcaInputA" name="Datos_Equipo[MARCA_TR]" placeholder="" value="{{old('Datos_Equipo.MARCA_TR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputA" name="Datos_Equipo[MODELO_TR]" placeholder="" value="{{old('Datos_Equipo.MODELO_TR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" id="nsInputA" name="Datos_Equipo[NS_TR]" placeholder="" value="{{old('Datos_Equipo.NS_TR')}}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">FREC:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_TR]" placeholder="" value="{{old('Datos_Equipo.FREC_TR')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">BLOCK DE REFERENCIA</div>

                                    <!-- Select para Block y Probeta -->
                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Block de Referencia:</label>
                                            <select class="form-select inputForm" name="blockyprobeta" id="blockyprobetaSelect">
                                            <option value="" selected disabled>Seleccione un Block de Referencia</option>
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
                                            <input type="text" class="form-control  inputForm" id="marcaInputbyp" name="Datos_Equipo[MARCA_BLOCK]" placeholder="" value="{{old('Datos_Equipo.MARCA_BLOCK')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputbyp" name="Datos_Equipo[MODELO_BLOCK]" placeholder="" value="{{old('Datos_Equipo.MODELO_BLOCK')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[NS_BLOCK]" placeholder="" value="{{old('Datos_Equipo.NS_BLOCK')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ACOPLANTE:</div>
                                    <div>
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ACOPLANTE]" placeholder="" value="{{old('Datos_Equipo.ACOPLANTE')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">LONGITUD DEL CABLE</div>
                                    <div>
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[LONG_CABLE]" placeholder="" value="{{old('Datos_Equipo.LONG_CABLE')}}">
                                        </div>
                                    </div>

                                    <div class="alert alert-secondary" role="alert"></div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">GANANCIA:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[GANANCIA]" placeholder="" value="{{old('Datos_Equipo.GANANCIA')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">RANGO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[RANGO]" placeholder="" value="{{old('Datos_Equipo.RANGO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">RECHAZO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[RECHAZO]" placeholder="" value="{{old('Datos_Equipo.RECHAZO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">PRESIÓN DE OPERACIÓN:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[PRESION_OPERACION]" placeholder="" value="{{old('Datos_Equipo.PRESION_OPERACION')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">PRESIÓN MÁXIMA DE OPERACIÓN:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[PRES_MAX_OPE]" placeholder="" value="{{old('Datos_Equipo.PRES_MAX_OPE')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TEMPERATURA MÁXIMA DE OPERACIÓN:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TEMP_MAX_OP]" placeholder="" value="{{old('Datos_Equipo.TEMP_MAX_OP')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="for-group">
                                            <label class="col-form-label" for="inputSuccess">CONDICIÓN SUPERFICIAL:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[CONDICION_SUPERFICIAL]" placeholder="" value="{{old('Datos_Equipo.CONDICION_SUPERFICIAL')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">ESTADO DE PINTURA:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ESTADO_PINTURA]" placeholder="" value="{{old('Datos_Equipo.ESTADO_PINTURA')}}">
                                        </div>
                                    </div>
                                    
                                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                                    <!--***************************************** INICIO RESULTADOS *****************************************-->

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>
                                    
                                    <div style="margin-bottom: 2px;"></div>

                                    <div class="table-responsive">
                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                                        <div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-info"></i> Importante</h5>
                                            <p>La primera fila es para el llenado automatico de cada una de las columnas del formato.</p>
                                        </div>
                                        <thead>
                                            <tr>
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">No. de Junta</th>
                                                <th colspan="2">Elemento de Referencia</th>
                                                <th rowspan="2">Ø</th>
                                                <th rowspan="2">No. Ind.</th>
                                                <th rowspan="2">Tipo de Indicación</th>
                                                <th rowspan="2">A (°)</th>
                                                <th rowspan="2">G (dB)</th>
                                                <th rowspan="2">NR (%)</th>
                                                <th rowspan="2">NI (%)</th>
                                                <th colspan="2">DSR</th>
                                                <th rowspan="2">Horario Técnico</th>
                                                <th rowspan="2">No. de Pierna</th>
                                                <th rowspan="2">S</th>
                                                <th rowspan="2">L</th>
                                                <th rowspan="2">D</th>
                                                <th rowspan="2">tmin</th>
                                                <th rowspan="2">Evaluación</th>
                                                <th rowspan="2">Fotos No.</th>
                                                <th rowspan="2">Observaciones</th>
                                                <th rowspan="2">Eliminar</th>
                                            </tr>

                                            <tr>
                                                <th>Lado A</th>
                                                <th>Lado B</th>
                                                <th>X</th>
                                                <th>Y</th>
                                            </tr>

                                            <tr id="inputRow">
                                                <th></th> <!-- Para ID vacío -->
                                                <th><input type="text" class="form-control default-input" data-column="1" ></th>
                                                <th><input type="text" class="form-control default-input" data-column="2"></th>
                                                <th><input type="text" class="form-control default-input" data-column="3"></th>
                                                <th><input type="text" class="form-control default-input" data-column="4"></th>
                                                <th><input type="text" class="form-control default-input" data-column="5"></th>
                                                <th><input type="text" class="form-control default-input" data-column="6"></th>
                                                <th><input type="text" class="form-control default-input" data-column="7"></th>
                                                <th><input type="text" class="form-control default-input" data-column="8"></th>
                                                <th><input type="text" class="form-control default-input" data-column="9"></th>
                                                <th><input type="text" class="form-control default-input" data-column="10"></th>
                                                <th><input type="text" class="form-control default-input" data-column="11"></th>
                                                <th><input type="text" class="form-control default-input" data-column="12"></th>
                                                <th><input type="text" class="form-control default-input" data-column="13"></th>
                                                <th><input type="text" class="form-control default-input" data-column="14"></th>
                                                <th><input type="text" class="form-control default-input" data-column="15"></th>
                                                <th><input type="text" class="form-control default-input" data-column="16"></th>
                                                <th><input type="text" class="form-control default-input" data-column="17"></th>
                                                <th><input type="text" class="form-control default-input" data-column="18"></th>
                                                <th><input type="text" class="form-control default-input" data-column="19"></th>
                                                <th><input type="text" class="form-control default-input" data-column="20"></th>
                                                <th><input type="text" class="form-control default-input" data-column="21"></th>
                                                <th></th> <!-- Para botón de eliminar -->
                                            </tr>
                                        </thead>

                                            <tbody>
                                            <!-- Filas dinámicas aparecerán aquí -->
                                            </tbody>
                                    </table>
                                    </div>

                                    <p>

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

                                            <button id="addTituloBtn" type="button" class="btn btn-success custom-btn">Agregar Título</button>

                                            <button id="preFillBtn" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                                        </div>

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
                                                                <td><strong>FP:</strong></td>
                                                                <td>FALTA DE PENETRACIÓN</td>
                                                            </tr>
    
                                                            <tr>
                                                                <td><strong>FF:</strong></td>
                                                                <td>FALTA DE FUSIÓN</td>
                                                                <td><strong>G:</strong></td>
                                                                <td>GRIETA</td>
                                                            </tr>
    
                                                            <tr>
                                                                <td><strong>IE:</strong></td>
                                                                <td>INCLUSIÓN DE ESCORIA</td>
                                                                {{--<td colspan="2" rowspan="2"><strong></strong></td>--}}                                                           
                                                                <td><strong>IVL:</strong></td>
                                                                <td>INDICACIÓN VOLUMÉTRICA</td>
                                                            </tr>

                                                            <tr>
                                                                <td><strong>IL:</strong></td>
                                                                <td>INDICACIÓN LINEAL</td>
                                                                <td><strong>IE:</strong></td>
                                                                <td>INDICACIÓN TRANSVERSAL</td>
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
                                                                <td><strong>NI:</strong></td>
                                                                <td>NIVEL DE INDICACIÓN</td>
                                                                <td><strong>d</strong></td>
                                                                <td>PROFUNDIDAD (PULG)</td>
                                                            </tr>
    
                                                            <tr>
                                                                <td><strong>G:</strong></td>
                                                                <td>GANANCIA (dB)</td>
                                                                <td><strong>L:</strong></td>
                                                                <td>LONGITUD DE LA INDICACIÓN</td>
                                                                <td colspan="2" rowspan="3"><strong></strong></td>                                                   
                                                            </tr>
    
                                                            <tr>
                                                                <td><strong>NR:</strong></td>
                                                                <td>NIVEL DE REFERENCIA (%)</td>
                                                                <td><strong>DSR:</strong></td>
                                                                <td>DISTANCIA DE LA INDICACIÓN</td>
                                                            </tr>
    
                                                            <tr>
                                                                <td><strong>S:</strong></td>
                                                                <td>DISTANCIA ANGULAR (Pulg)</td>
                                                                <td><strong>Tmin:</strong></td>
                                                                <td>ESPESOR MÍNIMO REGISTRADO (PULG)</td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Observaciones:</label>
                                                <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{old('Observaciones')}}</textarea>
                                            </div>
                                        </div>

                                        <!-- Select para elegir el número de firmas -->
                                        <div class="d-flex justify-content-center align-items-center p-2 mb-3 mt-3 bg-primary text-white rounded">Número de Firmas:</div>
                                        <div class="col-sm-15">
                                            <div class="form-group">
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                                    <option value="2">2 Firmas</option>
                                                    <option value="3">3 Firmas</option>
                                                    <option value="4">4 Firmas</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- 2 DOS FIRMAS-->
                                        <div id="firmas2" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Realizo]" placeholder="Ejemplo: Realizó" value="Realizó"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="Vo.Bo."></th>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_TECNICO]" placeholder="Ejemplo: NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO')}}"></td>
                                                    </tr>                                
                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('CARGO_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[PUESTO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO')}}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO')}}"></td>
                                                    </tr>
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 3 TRES FIRMAS-->
                                        <div id="firmas3" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Realizo]" placeholder="Ejemplo: Realizó" value="Realizó"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="Vo.Bo."></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo2]" placeholder="Ejemplo: Vo.Bo." value="Vo.Bo."></th>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_TECNICO]" placeholder="NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO]" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_2DO_ENCARGADO]" placeholder="NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_2DO_ENCARGADO')}}"></td>
                                                    </tr>                                
                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO_TECNICO]" placeholder="CARGO DEL TECNICO" value="{{old('CARGO_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_ENCARGADO]" placeholder="PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_2DO_ENCARGADO]" placeholder="PUESTO DEL SEGUNDO ENCARGADO" value="{{old('PUESTO_2DO_ENCARGADO')}}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_ENCARGADO]" placeholder="EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_2DO_ENCARGADO]" placeholder="EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO')}}"></td>
                                                    </tr>
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 4 CUATRO FIRMAS-->
                                        <div id="firmas4" class="col-12" style="display: none;">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Realizo]" placeholder="Ejemplo: Realizó" value="Realizó"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="Vo.Bo."></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo2]" placeholder="Ejemplo: Vo.Bo." value="Vo.Bo."></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo3]" placeholder="Ejemplo: Vo.Bo." value="Vo.Bo."></th>
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
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_TECNICO]" placeholder="NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO]" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_2DO_ENCARGADO]" placeholder="NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_2DO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_3RO_ENCARGADO]" placeholder="NOMBRE DEL TERCER ENCARGADO" value="{{old('NOMBRE_3RO_ENCARGADO')}}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO_TECNICO]" placeholder="CARGO DEL TECNICO" value="{{old('CARGO_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_ENCARGADO]" placeholder="PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_2DO_ENCARGADO]" placeholder="PUESTO DEL SEGUNDO ENCARGADO" value="{{old('PUESTO_2DO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_3RO_ENCARGADO]" placeholder="PUESTO DEL TERCER ENCARGADO" value="{{old('PUESTO_3RO_ENCARGADO')}}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_ENCARGADO]" placeholder="EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_2DO_ENCARGADO]" placeholder="EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_3RO_ENCARGADO]" placeholder="EMPRESA DEL TERCER ENCARGADO" value="{{old('EMPRESA_3RO_ENCARGADO')}}"></td>
                                                    </tr>
                                                </thead>                            
                                            </table>
                                        </div>
                                        
                                        <p>

                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">FOTOS</div>
                                        
                                        <p>

                                        <!--IMAGENES CON COMENTARIOS-->
                                        <div class="form-group">
                                            <label for="imageCount">Número de imágenes a subir:</label>
                                            <select class="form-select" id="imageCount" name="imageCount" autocomplete="off">
                                                <option value="">Selecciona Cuantas Imagenes Quieres Agregar</option>
                                                @for ($i = 1; $i <= 50; $i++)
                                                    <option value="{{ $i }}">{{ $i }} Imagen{{ $i > 1 ? 'es' : '' }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div id="imageFieldsContainer" class="row">
                                            <!-- Aquí se agregarán dinámicamente los campos -->
                                        </div>

                                        <!-- Modal para recortar la imagen -->
                                        <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Recortar Imagen</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="img-container">
                                                            <img id="cropperImage" src="" style="max-width: 100%;">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelBtn">Cancelar</button>
                                                        <button type="button" id="rotateLeftBtn" class="btn btn-info">⟲ Rotar -90°</button>
                                                        <button type="button" id="rotateRightBtn" class="btn btn-info">⟳ Rotar +90°</button>
                                                        <button type="button" class="btn btn-primary" id="cropImageBtn">Recortar y Guardar</button>
                                                        <button type="button" class="btn btn-success" id="saveWithoutCropBtn">Guardar Sin Recortar</button>
                                                    </div>
                                                </div>
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
            const savedData = sessionStorage.getItem('dynamicTableData');
            if (savedData) {
                const tableData = JSON.parse(savedData);
                
                // Restaurar contadores
                tituloCount = tableData.filter(item => item.type === 'titulo').length;
                rowCountGlobal = tableData.filter(item => item.type === 'fila').length;
                
                tableData.forEach((item) => {
                    if (item.type === 'titulo') {
                        let newTitle = `
                        <tr class="titulo-row" data-titulo="${item.id}">
                            <td colspan="22">
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="text" class="form-control w-90" name="titulos[]" value="${item.text}" placeholder="Ingrese título">
                                    <td><button type="button" class="btn btn-danger btnEliminarTitulo">
                                        <i class="bi bi-trash"  aria-hidden="true"></i>
                                    </button></td>
                                </div>
                            </td>
                        </tr>`;
                        $('#dynamicTable tbody').append(newTitle);
                    } else if (item.type === 'fila') {
                        let newRow = `<tr data-titulo="${item.titulo}">
                            <td>${item.rowNumber} <input type="hidden" value="${item.rowNumber}"></td>
                            <td><input type="text" class="form-control" name="no_junta[${item.titulo}][]" value="${item.inputs[1]}" placeholder="No. de Junta" style="width: 100px;"></td>
                            <td><input type="text" class="form-control" name="lado_a[${item.titulo}][]" value="${item.inputs[2]}" placeholder="Lado A" style="width: 80px;"></td>
                            <td><input type="text" class="form-control" name="lado_b[${item.titulo}][]" value="${item.inputs[3]}" placeholder="Lado B" style="width: 80px;"></td>
                            <td><input type="text" class="form-control" name="diametro[${item.titulo}][]" value="${item.inputs[4]}" placeholder="Ø" style="width: 80px;"></td>
                            <td><input type="text" class="form-control" name="no_indicacion[${item.titulo}][]" value="${item.inputs[5]}" placeholder="No. Indicación" style="width: 80px;"></td>
                            <td><input type="text" class="form-control" name="tipo_indicacion[${item.titulo}][]" value="${item.inputs[6]}" placeholder="Tipo de Indicación" style="width: 80px;"></td>
                            <td><input type="text" class="form-control" name="Ang[${item.titulo}][]" value="${item.inputs[7]}" placeholder="A (°)" style="width: 80px;"></td>
                            <td><input type="text" class="form-control" name="Gdb[${item.titulo}][]" value="${item.inputs[8]}" placeholder="G (dB)" style="width: 80px;"></td>
                            <td><input type="text" class="form-control" name="nr[${item.titulo}][]" value="${item.inputs[9]}" placeholder="NR (%)" style="width: 95px;"></td>
                            <td><input type="text" class="form-control" name="ni[${item.titulo}][]" value="${item.inputs[10]}" placeholder="NI (%)" style="width: 70px;"></td>
                            <td><input type="text" class="form-control" name="x[${item.titulo}][]" value="${item.inputs[11]}" placeholder="X" style="width: 70px;"></td>
                            <td><input type="text" class="form-control" name="y[${item.titulo}][]" value="${item.inputs[12]}" placeholder="Y" style="width: 70px;"></td>
                            <td><input type="text" class="form-control" name="horario_tecnico[${item.titulo}][]" value="${item.inputs[13]}" placeholder="Horario Técnico" style="width: 80px;"></td>
                            <td><input type="text" class="form-control" name="no_pierna[${item.titulo}][]" value="${item.inputs[14]}" placeholder="No. de Pierna" style="width: 80px;"></td>
                            <td><input type="text" class="form-control" name="s[${item.titulo}][]" value="${item.inputs[15]}" placeholder="S" style="width: 95px;"></td>
                            <td><input type="text" class="form-control" name="l[${item.titulo}][]" value="${item.inputs[16]}" placeholder="L" style="width: 70px;"></td>
                            <td><input type="text" class="form-control" name="d[${item.titulo}][]" value="${item.inputs[17]}" placeholder="D" style="width: 70px;"></td>
                            <td><input type="text" class="form-control" name="tmin[${item.titulo}][]" value="${item.inputs[18]}" placeholder="tmin" style="width: 70px;"></td>
                            <td><input type="text" class="form-control" name="evaluacion[${item.titulo}][]" value="${item.inputs[19]}" placeholder="Evaluación" style="width: 70px;"></td>
                            <td><input type="text" class="form-control" name="fotos[${item.titulo}][]" value="${item.inputs[20]}" placeholder="Fotos No." style="width: 95px;"></td>
                            <td><input type="text" class="form-control" name="observaciones[${item.titulo}][]" value="${item.inputs[21]}" placeholder="Observaciones" style="width: 130px;"></td>
                            <td><button type="button" class="btn btn-danger btnEliminar">   <i class="bi bi-trash"  aria-hidden="true"></i></button></td>
                            
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
                            <i class="bi bi-trash"  aria-hidden="true"></i>
                        </button></td>
                    </div>
                </td>
            </tr>
        `;

        $('#dynamicTable tbody').append(newTitle);
        updateTitulos(); // Actualizar lista de títulos
        saveData();
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
                        <td><input type="text" class="form-control" name="no_junta[${lastTitle}][]" placeholder="No. de Junta" style="width: 100px;"></td>
                        <td><input type="text" class="form-control" name="lado_a[${lastTitle}][]" placeholder="Lado A" style="width: 80px;"></td>
                        <td><input type="text" class="form-control" name="lado_b[${lastTitle}][]" placeholder="Lado B" style="width: 80px;"></td>
                        <td><input type="text" class="form-control" name="diametro[${lastTitle}][]" placeholder="Ø" style="width: 80px;"></td>
                        <td><input type="text" class="form-control" name="no_indicacion[${lastTitle}][]" placeholder="No. Indicación" style="width: 80px;"></td>
                        <td><input type="text" class="form-control" name="tipo_indicacion[${lastTitle}][]" placeholder="Tipo de Indicación" style="width: 80px;"></td>
                        <td><input type="text" class="form-control" name="Ang[${lastTitle}][]" placeholder="A (°)" style="width: 80px;"></td>
                        <td><input type="text" class="form-control" name="Gdb[${lastTitle}][]" placeholder="G (dB)" style="width: 80px;"></td>
                        <td><input type="text" class="form-control" name="nr[${lastTitle}][]" placeholder="NR (%)" style="width: 95px;"></td>
                        <td><input type="text" class="form-control" name="ni[${lastTitle}][]" placeholder="NI (%)" style="width: 70px;"></td>
                        <td><input type="text" class="form-control" name="x[${lastTitle}][]" placeholder="X" style="width: 70px;"></td>
                        <td><input type="text" class="form-control" name="y[${lastTitle}][]" placeholder="Y" style="width: 70px;"></td>
                        <td><input type="text" class="form-control" name="horario_tecnico[${lastTitle}][]" placeholder="Horario Técnico" style="width: 80px;"></td>
                        <td><input type="text" class="form-control" name="no_pierna[${lastTitle}][]" placeholder="No. de Pierna" style="width: 80px;"></td>
                        <td><input type="text" class="form-control" name="s[${lastTitle}][]" placeholder="S" style="width: 95px;"></td>
                        <td><input type="text" class="form-control" name="l[${lastTitle}][]" placeholder="L" style="width: 70px;"></td>
                        <td><input type="text" class="form-control" name="d[${lastTitle}][]" placeholder="D" style="width: 70px;"></td>
                        <td><input type="text" class="form-control" name="tmin[${lastTitle}][]" placeholder="tmin" style="width: 70px;"></td>
                        <td><input type="text" class="form-control" name="evaluacion[${lastTitle}][]" placeholder="Evaluación" style="width: 70px;"></td>
                        <td><input type="text" class="form-control" name="fotos[${lastTitle}][]" placeholder="Fotos No." style="width: 95px;"></td>
                        <td><input type="text" class="form-control" name="observaciones[${lastTitle}][]" placeholder="Observaciones" style="width: 130px;"></td>
                        <td><button type="button" class="btn btn-danger btnEliminar">   <i class="bi bi-trash"  aria-hidden="true"></i></button></td>
                        
                    </tr>`;

                $('#dynamicTable tbody').append(newRow);
            }
            saveData();
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

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect').on('change', function() {
                actualizarInputsE();
            });



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

    });



    
</script>
@endsection