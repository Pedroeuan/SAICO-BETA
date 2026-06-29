@extends('adminlte::page')

@section('title', 'FOR-PINS-14-01')

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
            <form id="FOR-PINS-14_01" action="{{route('Reportes_FOR_PINS_14_01.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn my-2">Rellenar Campos Vacios "---"</button>
                <div style="margin-bottom: 2px;"></div>
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                            <input type="date" class="form-control  inputForm @error('Fecha') is-invalid @enderror" name="Detalles_Generales[Fecha]"  placeholder="Ejemplo: DD/MM/AAAA" value="{{old('Detalles_Generales.Fecha')}}">
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
                                <label class="col-form-label">¿Cliente existente?
                                <span class="ml-3">
                                    <label class="mr-2">
                                        <input type="radio" name="TieneCliente" value="si" checked> Sí
                                    </label>
                                    <label>
                                        <input type="radio" name="TieneCliente" value="no"> No
                                    </label>
                                </span>
                            </label>
                            <!-- SELECT cuando es SI -->
                            <select id="campoClienteSelect" class="form-select" name="ClienteSelect">
                                <option value="" selected disabled>Seleccione un Cliente</option>
                                @foreach($Clientes as $Cliente)
                                    <option value="{{ $Cliente->Cliente }}">
                                        {{ $Cliente->Cliente }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- INPUT cuando es NO -->
                            <input type="text" id="campoClienteInput" class="form-control inputForm mt-2" name="ClienteInput" placeholder="Ingrese nombre del cliente" style="display:none;">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label"> ¿Contrato existente?
                                    <span class="ml-3">
                                        <label class="mr-2">
                                                <input type="radio" name="TieneContrato" value="si" checked> Sí
                                        </label>
                                        <label>
                                            <input type="radio" name="TieneContrato" value="no"> No
                                        </label>
                                    </span>
                                </label>

                                <!-- Input visible solo si es "SI" -->
                                <input type="text" id="campoContrato" class="form-control inputForm" name="Detalles_Generales[Contrato]" placeholder="Ejemplo: 640853841" value="{{ old('Detalles_Generales.Contrato') }}" required>
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

                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS Y AJUSTES DEL EQUIPO</div>

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
                            <input type="hidden" name="Datos_Equipo[ID_EQUIPO]" id="IDInputE" value="{{ old('Datos_Equipo.ID_EQUIPO') }}">
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

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ACOPLANTE (MARCA Y TIPO):</div>
                    <div>
                        <div class="form-group">
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ACOPLANTE]" placeholder="" value="{{old('Datos_Equipo.ACOPLANTE')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">LONGITUD DE CABLE:</div>
                    <div>
                        <div class="form-group">
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[LONG_CAB]" placeholder="" value="{{old('Datos_Equipo.LONG_CAB')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">BLOCK DE REFERENCIA</div>

                    <!-- Select para Block y Probeta -->
                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Block de Referecia:</label>
                            <select class="form-select inputForm" name="blockyprobeta" id="blockyprobetaSelect">
                            <option value="" selected disabled>Seleccione un Block de Referencia</option>
                                @foreach($idsGeneral_EyCs_BlockyProbeta as $blockyprobeta)
                                    <option value="{{ $blockyprobeta->idGeneral_EyC }}"
                                            data-nombre="{{ $blockyprobeta->Nombre_E_P_BP }}"
                                            data-ns="{{ $blockyprobeta->Serie }}">
                                        {{ $blockyprobeta->Nombre_E_P_BP }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_BLOCK]" id="IDInputbyp" value="{{ old('Datos_Equipo.ID_BLOCK') }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">NOMBRE:</label>
                            <input type="text" class="form-control  inputForm" id="nombreInputbyp" name="Datos_Equipo[NOMB_BLOCK]" placeholder="" value="{{old('Datos_Equipo.NOMB_BLOCK')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[NS_BLOCK]" placeholder="" value="{{old('Datos_Equipo.NS_BLOCK')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">SONDA #1</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Sonda #1:</label>
                            <select class="form-select inputForm" name="accesoriosSelect1" id="accesoriosSelect1">
                            <option value="" selected disabled>Seleccione una Sonda</option> <!-- Opción por defecto -->
                                    @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                        <option value="{{ $accesorios->idGeneral_EyC }}"
                                                data-marca="{{ $accesorios->Marca }}"
                                                data-modelo="{{ $accesorios->Modelo }}"
                                                data-ns="{{ $accesorios->Serie }}">
                                            {{ $accesorios->Nombre_E_P_BP }}
                                        </option>
                                    @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_SONDA1]" id="IDInputA1" value="{{ old('Datos_Equipo.ID_SONDA1') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA1" name="Datos_Equipo[MARCA_SONDA1]" placeholder="" value="{{old('Datos_Equipo.MARCA_SONDA1')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA1" name="Datos_Equipo[MODELO_SONDA1]" placeholder="" value="{{old('Datos_Equipo.MODELO_SONDA1')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA1" name="Datos_Equipo[NS_SONDA1]" placeholder="" value="{{old('Datos_Equipo.NS_SONDA1')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ZAPATA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ZAPATA_SONDA1]" placeholder="" value="{{old('Datos_Equipo.ZAPATA_SONDA1')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FRECUENCIA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_SONDA1]" placeholder="" value="{{old('Datos_Equipo.FREC_SONDA1')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">SONDA #2</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Sonda #2:</label>
                            <select class="form-select inputForm" name="accesoriosSelect2" id="accesoriosSelect2">
                            <option value="" selected disabled>Seleccione una Sonda</option> <!-- Opción por defecto -->
                                    @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                        <option value="{{ $accesorios->idGeneral_EyC }}"
                                                data-marca="{{ $accesorios->Marca }}"
                                                data-modelo="{{ $accesorios->Modelo }}"
                                                data-ns="{{ $accesorios->Serie }}">
                                            {{ $accesorios->Nombre_E_P_BP }}
                                        </option>
                                    @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_SONDA2]" id="IDInputA2" value="{{ old('Datos_Equipo.ID_SONDA2') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA2" name="Datos_Equipo[MARCA_SONDA2]" placeholder="" value="{{old('Datos_Equipo.MARCA_SONDA2')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA2" name="Datos_Equipo[MODELO_SONDA2]" placeholder="" value="{{old('Datos_Equipo.MODELO_SONDA2')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA2" name="Datos_Equipo[NS_SONDA2]" placeholder="" value="{{old('Datos_Equipo.NS_SONDA2')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ZAPATA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ZAPATA_SONDA2]" placeholder="" value="{{old('Datos_Equipo.ZAPATA_SONDA2')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FRECUENCIA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_SONDA2]" placeholder="" value="{{old('Datos_Equipo.FREC_SONDA2')}}">
                        </div>
                    </div>


                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">SONDA #3</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Sonda #3:</label>
                            <select class="form-select inputForm" name="accesoriosSelect3" id="accesoriosSelect3">
                            <option value="" selected disabled>Seleccione una Sonda</option> <!-- Opción por defecto -->
                                    @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                        <option value="{{ $accesorios->idGeneral_EyC }}"
                                                data-marca="{{ $accesorios->Marca }}"
                                                data-modelo="{{ $accesorios->Modelo }}"
                                                data-ns="{{ $accesorios->Serie }}">
                                            {{ $accesorios->Nombre_E_P_BP }}
                                        </option>
                                    @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_SONDA3]" id="IDInputA3" value="{{ old('Datos_Equipo.ID_SONDA3') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA3" name="Datos_Equipo[MARCA_SONDA3]" placeholder="" value="{{old('Datos_Equipo.MARCA_SONDA3')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA3" name="Datos_Equipo[MODELO_SONDA3]" placeholder="" value="{{old('Datos_Equipo.MODELO_SONDA3')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA3" name="Datos_Equipo[NS_SONDA3]" placeholder="" value="{{old('Datos_Equipo.NS_SONDA3')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ZAPATA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ZAPATA_SONDA3]" placeholder="" value="{{old('Datos_Equipo.ZAPATA_SONDA3')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FRECUENCIA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_SONDA3]" placeholder="" value="{{old('Datos_Equipo.FREC_SONDA3')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">SONDA #4</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Sonda #4:</label>
                            <select class="form-select inputForm" name="accesoriosSelect4" id="accesoriosSelect4">
                            <option value="" selected disabled>Seleccione una Sonda</option> <!-- Opción por defecto -->
                                    @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                        <option value="{{ $accesorios->idGeneral_EyC }}"
                                                data-marca="{{ $accesorios->Marca }}"
                                                data-modelo="{{ $accesorios->Modelo }}"
                                                data-ns="{{ $accesorios->Serie }}">
                                            {{ $accesorios->Nombre_E_P_BP }}
                                        </option>
                                    @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_SONDA4]" id="IDInputA4" value="{{ old('Datos_Equipo.ID_SONDA4') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA4" name="Datos_Equipo[MARCA_SONDA4]" placeholder="" value="{{old('Datos_Equipo.MARCA_SONDA4')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA4" name="Datos_Equipo[MODELO_SONDA4]" placeholder="" value="{{old('Datos_Equipo.MODELO_SONDA4')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA4" name="Datos_Equipo[NS_SONDA4]" placeholder="" value="{{old('Datos_Equipo.NS_SONDA4')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ZAPATA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ZAPATA_SONDA4]" placeholder="" value="{{old('Datos_Equipo.ZAPATA_SONDA4')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FRECUENCIA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_SONDA4]" placeholder="" value="{{old('Datos_Equipo.FREC_SONDA4')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">TRANSDUCTOR DE TOFD #1</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Transductor de TOFD #1:</label>
                            <select class="form-select inputForm" name="accesoriosSelect5" id="accesoriosSelect5">
                            <option value="" selected disabled>Seleccione una Sonda</option> <!-- Opción por defecto -->
                                    @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                        <option value="{{ $accesorios->idGeneral_EyC }}"
                                                data-marca="{{ $accesorios->Marca }}"
                                                data-modelo="{{ $accesorios->Modelo }}"
                                                data-ns="{{ $accesorios->Serie }}">
                                            {{ $accesorios->Nombre_E_P_BP }}
                                        </option>
                                    @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_TRANS1]" id="IDInputA5" value="{{ old('Datos_Equipo.ID_TRANS1') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA5" name="Datos_Equipo[MARCA_TRANS1]" placeholder="" value="{{old('Datos_Equipo.MARCA_TRANS1')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA5" name="Datos_Equipo[MODELO_TRANS1]" placeholder="" value="{{old('Datos_Equipo.MODELO_TRANS1')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA5" name="Datos_Equipo[NS_TRANS1]" placeholder="" value="{{old('Datos_Equipo.NS_TRANS1')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ZAPATA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ZAPATA_TRANS1]" placeholder="" value="{{old('Datos_Equipo.ZAPATA_TRANS1')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FRECUENCIA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_TRANS1]" placeholder="" value="{{old('Datos_Equipo.FREC_TRANS1')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">TRANSDUCTOR DE TOFD #2</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Transductor de TOFD #2:</label>
                            <select class="form-select inputForm" name="accesoriosSelect6" id="accesoriosSelect6">
                            <option value="" selected disabled>Seleccione una Sonda</option> <!-- Opción por defecto -->
                                    @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                        <option value="{{ $accesorios->idGeneral_EyC }}"
                                                data-marca="{{ $accesorios->Marca }}"
                                                data-modelo="{{ $accesorios->Modelo }}"
                                                data-ns="{{ $accesorios->Serie }}">
                                            {{ $accesorios->Nombre_E_P_BP }}
                                        </option>
                                    @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_TRANS2]" id="IDInputA6" value="{{ old('Datos_Equipo.ID_TRANS2') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA6" name="Datos_Equipo[MARCA_TRANS2]" placeholder="" value="{{old('Datos_Equipo.MARCA_TRANS2')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA6" name="Datos_Equipo[MODELO_TRANS2]" placeholder="" value="{{old('Datos_Equipo.MODELO_TRANS2')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA6" name="Datos_Equipo[NS_TRANS2]" placeholder="" value="{{old('Datos_Equipo.NS_TRANS2')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ZAPATA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ZAPATA_TRANS2]" placeholder="" value="{{old('Datos_Equipo.ZAPATA_TRANS2')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FRECUENCIA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_TRANS2]" placeholder="" value="{{old('Datos_Equipo.FREC_TRANS2')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">TRANSDUCTOR DE TOFD #3</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Transductor de TOFD #3:</label>
                            <select class="form-select inputForm" name="accesoriosSelect7" id="accesoriosSelect7">
                            <option value="" selected disabled>Seleccione una Sonda</option> <!-- Opción por defecto -->
                                    @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                        <option value="{{ $accesorios->idGeneral_EyC }}"
                                                data-marca="{{ $accesorios->Marca }}"
                                                data-modelo="{{ $accesorios->Modelo }}"
                                                data-ns="{{ $accesorios->Serie }}">
                                            {{ $accesorios->Nombre_E_P_BP }}
                                        </option>
                                    @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_TRANS3]" id="IDInputA7" value="{{ old('Datos_Equipo.ID_TRANS3') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA7" name="Datos_Equipo[MARCA_TRANS3]" placeholder="" value="{{old('Datos_Equipo.MARCA_TRANS3')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA7" name="Datos_Equipo[MODELO_TRANS3]" placeholder="" value="{{old('Datos_Equipo.MODELO_TRANS3')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA7" name="Datos_Equipo[NS_TRANS3]" placeholder="" value="{{old('Datos_Equipo.NS_TRANS3')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ZAPATA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ZAPATA_TRANS3]" placeholder="" value="{{old('Datos_Equipo.ZAPATA_TRANS3')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FRECUENCIA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_TRANS3]" placeholder="" value="{{old('Datos_Equipo.FREC_TRANS3')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">TRANSDUCTOR DE TOFD #4</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Transductor de TOFD #4:</label>
                            <select class="form-select inputForm" name="accesoriosSelect8" id="accesoriosSelect8">
                            <option value="" selected disabled>Seleccione una Sonda</option> <!-- Opción por defecto -->
                                    @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                        <option value="{{ $accesorios->idGeneral_EyC }}"
                                                data-marca="{{ $accesorios->Marca }}"
                                                data-modelo="{{ $accesorios->Modelo }}"
                                                data-ns="{{ $accesorios->Serie }}">
                                            {{ $accesorios->Nombre_E_P_BP }}
                                        </option>
                                    @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_TRANS4]" id="IDInputA8" value="{{ old('Datos_Equipo.ID_TRANS4') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA8" name="Datos_Equipo[MARCA_TRANS4]" placeholder="" value="{{old('Datos_Equipo.MARCA_TRANS4')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA8" name="Datos_Equipo[MODELO_TRANS4]" placeholder="" value="{{old('Datos_Equipo.MODELO_TRANS4')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA8" name="Datos_Equipo[NS_TRANS4]" placeholder="" value="{{old('Datos_Equipo.NS_TRANS4')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ZAPATA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ZAPATA_TRANS4]" placeholder="" value="{{old('Datos_Equipo.ZAPATA_TRANS4')}}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FRECUENCIA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_TRANS4]" placeholder="" value="{{old('Datos_Equipo.FREC_TRANS4')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ENCODER #1</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Encoder:</label>
                                <select class="form-select inputForm" name="equiposSelect2" id="equiposSelect2">
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
                            <input type="hidden" name="Datos_Equipo[ID_ENCODER1]" id="IDInputE2" value="{{ old('Datos_Equipo.ID_ENCODER1') }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputE2" name="Datos_Equipo[MARCA_ENCODER1]" placeholder="" value="{{old('Datos_Equipo.MARCA_ENCODER1')}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputE2" name="Datos_Equipo[MODELO_ENCODER1]" placeholder="" value="{{old('Datos_Equipo.MODELO_ENCODER1')}}">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputE2" name="Datos_Equipo[NS_ENCODER1]" placeholder="" value="{{old('Datos_Equipo.NS_ENCODER1')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">RESOLUCIÓN DE ESCANEO:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[RES_SCAN1]" placeholder="" value="{{old('Datos_Equipo.RES_SCAN1')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ENCODER #2</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Encoder:</label>
                                <select class="form-select inputForm" name="equiposSelect3" id="equiposSelect3">
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
                            <input type="hidden" name="Datos_Equipo[ID_ENCODER2]" id="IDInputE3" value="{{ old('Datos_Equipo.ID_ENCODER2') }}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputE3" name="Datos_Equipo[MARCA_ENCODER2]" placeholder="" value="{{old('Datos_Equipo.MARCA_ENCODER2')}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputE3" name="Datos_Equipo[MODELO_ENCODER2]" placeholder="" value="{{old('Datos_Equipo.MODELO_ENCODER2')}}">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputE3" name="Datos_Equipo[NS_ENCODER2]" placeholder="" value="{{old('Datos_Equipo.NS_ENCODER2')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">RESOLUCIÓN DE ESCANEO:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[RES_SCAN2]" placeholder="" value="{{old('Datos_Equipo.RES_SCAN2')}}">
                        </div>
                    </div>

                    <div class="alert alert-secondary" role="alert"></div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ANGULO DE INICIO:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputbyp" name="Datos_Equipo[ANG_INI]" placeholder="" value="{{old('Datos_Equipo.ANG_INI')}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ANGULO FINAL:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputbyp" name="Datos_Equipo[ANG_FIN]" placeholder="" value="{{old('Datos_Equipo.ANG_FIN')}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">VELOCIDAD:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[VELOCIDAD]" placeholder="" value="{{old('Datos_Equipo.VELOCIDAD')}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FILTRO:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[FILTRO]" placeholder="" value="{{old('Datos_Equipo.FILTRO')}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">CÓDIGO DE EVALUACIÓN:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputbyp" name="Datos_Equipo[COD_EVA]" placeholder="" value="{{old('Datos_Equipo.COD_EVA')}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">TIPO DE BARRIDO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputbyp" name="Datos_Equipo[TIP_BARR]" placeholder="" value="{{old('Datos_Equipo.TIP_BARR')}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">AREA DE ESCANEO:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[AREA_SCAN]" placeholder="" value="{{old('Datos_Equipo.AREA_SCAN')}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">PROCEDIMIENTO:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[PROCEDIMIENTO]" placeholder="" value="{{old('Datos_Equipo.PROCEDIMIENTO')}}">
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
                            <label class="col-form-label" for="inputSuccess">TIPO DE JUNTA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIP_JUNTA]" placeholder="" value="{{old('Datos_Equipo.TIP_JUNTA')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">RECHAZO:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[RECHAZO]" placeholder="" value="{{old('Datos_Equipo.RECHAZO')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">DIAMETRO:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[DIAMETRO]" placeholder="" value="{{old('Datos_Equipo.DIAMETRO')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">TEMPERATURA:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TEMP]" placeholder="" value="{{old('Datos_Equipo.TEMP')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ESPESOR:</label>
                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[ESPESOR]" placeholder="" value="{{old('Datos_Equipo.ESPESOR')}}">
                        </div>
                    </div>

                        <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                        <!--***************************************** INICIO RESULTADOS *****************************************-->

                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>
                                    
                        <div style="margin-bottom: 5px;"></div>

                        <div class="table-responsive">
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-info"></i> Importante</h5>
                                <p>La primera fila es para el llenado automatico de cada una de las columnas del formato.</p>
                            </div>
                        <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                            <thead>
                                <tr>
                                    <th rowspan="2">#</th>
                                    <th rowspan="2">Junta / Elemento</th>
                                    <th rowspan="2">Tipo de Indicación</th>
                                    <th rowspan="2">L (PLG)</th>
                                    <th rowspan="2">A (PLG)</th>
                                    <th rowspan="2">ALTURA (PLG)</th>
                                    <th colspan="2">EJE DE LA SOLDADURA</th>
                                    <th rowspan="2">DA (PROF)</th>
                                    <th rowspan="2">PA</th>
                                    <th rowspan="2">SA</th>
                                    <th rowspan="2">Tmin</th>
                                    <th rowspan="2">Datos del Archivo (Escaneo)</th>
                                    <th rowspan="2">Evaluación</th>
                                    <th rowspan="2">Fotos</th>
                                    <th rowspan="2">Eliminar</th>
                                </tr>
                                <tr>
                                    <th>X</th>
                                    <th>Y</th>
                                </tr>
                                <tr id="inputRow">
                                    <th></th> <!-- Para ID vacío -->
                                    <th><input type="text" class="form-control default-input" data-column="1" style="width: 100px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="2" style="width: 100px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="3" style="width: 80px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="4" style="width: 80px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="5" style="width: 60px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="6" style="width: 60px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="7" style="width: 60px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="8" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="9" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="10" style="width: 100px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="11" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="12" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="13" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="14" style="width: 70px;"></th>
                                    <th></th> <!-- Para botón de eliminar -->
                                </tr>
                            </thead>

                                <tbody>
                                <!-- Filas dinámicas aparecerán aquí -->
                                </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="titulos_data" id="titulos_hidden">
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

                        <button id="addLongBtn" type="button" class="btn btn-success custom-btn">Agregar Longitud Inspeccionada</button>

                        <button id="preFillBtn" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                    </div>

                    <p>

                    <div class="alert alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-info"></i> Información</h5>
                        <p> <b>SIR</b>= Sin indicaciones Relevantes <b>L</b>= Indicacion Lineal <b>R</b>= Indicacion Redondeada <b>A</b>= Aceptado 
                            <b>R</b>= Rechazado <b>FP</b>= Falta de Penetracion <b>FF</b>= Falta de Fusion <b>P</b>= Poros <b>PA</b>= Poros Agrupados
                            <b>LA</b>= Linea de Escoria (<b>DA</b>=Profundidad / <b>PA</b>=Distancia superficial / <b>SA</b>= Distancia angular)
                        </p>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Observaciones:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{old('Observaciones')}}</textarea>
                        </div>
                    </div>

                    <!-- Select para elegir el número de firmas -->
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">Número de Firmas:</div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                    <option value="1">1 Firma</option>
                                    <option value="2">2 Firmas</option>
                                    <option value="3">3 Firmas</option>
                                    <option value="4">4 Firmas</option>
                                </select>
                            </div>
                        </div>

                        <!-- 1 UNA FIRMA-->
                        <div id="firmas1" class="col-12">
                            <table class="table table-bordered table-striped dt-responsive tablas">
                                <thead>
                                    <tr>
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[Realizo]" placeholder="Ejemplo: Realizó" value="Realizó"></th>
                                    </tr>

                                    <tr>
                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                    </tr>

                                    <tr>
                                        <td>

                                            <div class="col-sm-50 d-flex justify-content-center">
                                                <div class="form-group text-center">

                                                    <select class="form-select inputForm" id="tecnicosSelect" name="Firmas_Reportes1[ID_TECNICO]">
                                                        <option value="" selected disabled>SELECCIÓN DE TÉCNICOS</option>
                                                        @foreach($Tecnicos as $Tecnico)
                                                            <option value="{{ $Tecnico->id }}"
                                                                    data-name="{{ $Tecnico->name }}">
                                                                {{ $Tecnico->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <!-- hidden si quieres guardar el ID explícitamente -->
                                                    <input type="hidden" name="Firmas_Reportes1[NOMBRE_TECNICO]" id="NOMBRE_TECNICO" value="{{old('Firmas_Reportes1.NOMBRE_TECNICO')}}">
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('CARGO_TECNICO')}}"></td>
                                    </tr>

                                    <tr>
                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                    </tr>
                                </thead>
                            </table>
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

                                        <td>
                                            
                                            <div class="col-sm-50 d-flex justify-content-center">
                                                <div class="form-group text-center">

                                                    <select class="form-select inputForm" id="tecnicosSelect2" name="Firmas_Reportes2[ID_TECNICO]">
                                                        <option value="" selected disabled>SELECCIÓN DE TÉCNICOS</option>
                                                        @foreach($Tecnicos as $Tecnico)
                                                            <option value="{{ $Tecnico->id }}"
                                                                    data-name="{{ $Tecnico->name }}">
                                                                {{ $Tecnico->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <!-- hidden si quieres guardar el ID explícitamente-->
                                                    <input type="hidden" name="Firmas_Reportes2[NOMBRE_TECNICO]" id="NOMBRE_TECNICO2" value="{{old('Firmas_Reportes2.NOMBRE_TECNICO')}}">
                                                </div>
                                            </div>
                                        </td>
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

                                        <td>
                                            
                                            <div class="col-sm-50 d-flex justify-content-center">
                                                <div class="form-group text-center">

                                                    <select class="form-select inputForm" id="tecnicosSelect3" name="Firmas_Reportes3[ID_TECNICO]">
                                                        <option value="" selected disabled>SELECCIÓN DE TÉCNICOS</option>
                                                        @foreach($Tecnicos as $Tecnico)
                                                            <option value="{{ $Tecnico->id }}"
                                                                    data-name="{{ $Tecnico->name }}">
                                                                {{ $Tecnico->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <!-- hidden si quieres guardar el ID explícitamente-->
                                                    <input type="hidden" name="Firmas_Reportes3[NOMBRE_TECNICO]" id="NOMBRE_TECNICO3" value="{{old('Firmas_Reportes3.NOMBRE_TECNICO')}}">
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO')}}"></td>
                                        <td></td>
                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_2DO_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_2DO_ENCARGADO')}}"></td>

                                    </tr>

                                    <tr>

                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('CARGO_TECNICO')}}"></td>
                                        <td></td>
                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO')}}"></td>
                                        <td></td>
                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_2DO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL SEGUNDO ENCARGADO" value="{{old('PUESTO_2DO_ENCARGADO')}}"></td>

                                    </tr>

                                    <tr>

                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                        <td></td>
                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO')}}"></td>
                                        <td></td>
                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_2DO_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO')}}"></td>

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

                                        <td>
                                            <div class="col-sm-50 d-flex justify-content-center">
                                                <div class="form-group text-center">

                                                    <select class="form-select inputForm" id="tecnicosSelect4" name="Firmas_Reportes4[ID_TECNICO]">
                                                        <option value="" selected disabled>SELECCIÓN DE TÉCNICOS</option>
                                                        @foreach($Tecnicos as $Tecnico)
                                                            <option value="{{ $Tecnico->id }}"
                                                                    data-name="{{ $Tecnico->name }}">
                                                                {{ $Tecnico->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <!-- hidden si quieres guardar el ID explícitamente-->
                                                    <input type="hidden" name="Firmas_Reportes4[NOMBRE_TECNICO]" id="NOMBRE_TECNICO4" value="{{old('Firmas_Reportes4.NOMBRE_TECNICO')}}">
                                                </div>
                                            </div></td>
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
                            <select class="form-control" id="imageCount" name="imageCount" autocomplete="off">
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
                        <p>

                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS SOLDADOR</div>
                        
                        <p>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Num. de Soldador:</label>
                                <input type="text" class="form-control  inputForm @error('Num_Soldador') is-invalid @enderror" name="Detalles_Generales[Num_Soldador]"  placeholder="Ejemplo: 12345" value="{{old('Detalles_Generales.Num_Soldador')}}">
                                @error('Num_Soldador')
                                        <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Nombre soldador/Iniciales:</label>
                                <input type="text" class="form-control  inputForm @error('Nombre_Soldador') is-invalid @enderror" name="Detalles_Generales[Nombre_Soldador]"  placeholder="Ejemplo: Juan Pérez" value="{{old('Detalles_Generales.Nombre_Soldador')}}">
                                @error('Nombre_Soldador')
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
    let tituloCount = 0; //contador de títulos creados (se incrementa al añadir un título).
    let rowCount = 0; //contador de filas por título (se reinicia a 0 cuando se crea un nuevo título).
    let rowCountGlobal = 0; //contador global/visual de filas (se usa para numerar las filas en la tabla).
    
    function restoreData() {//-----------------------------------------------------------Reemplazar todo el resotedara
        const data = JSON.parse(sessionStorage.getItem('dynamicTableData') || 'null');
        if (!data) return;

        // Helpers y configuración-CONFIGURAR CAMPOS DE ACUERDO A LOS NAMES DE CADA INPUT
        const fieldNames = [
        'no_junta',
        'Tip_Ind',
        'L_PGL',
        'A_PGL',
        'AL_PGL'
        ,'X','Y',
        'DA_PROF',
        'PA','SA',
        'TMIN',
        'SCAN',
        'EVAL',
        'FOTOS'];
        const placeholders = { //CONFIGURAR CAMPOS DE ACUERDO A LOS PLACEHOLDERS DE CADA INPUT
            no_junta: 'Junta / Eento', 
            Tip_Ind: 'Tipo de Indicación', 
            L_PGL: 'L (PLG)',
            A_PGL: 'A (PLG)', 
            AL_PGL: 'ALTURA (PLG)', 
            X: 'X', 
            Y: 'Y', 
            DA_PROF: 'DA (PROF)',
            PA: 'PA', 
            SA: 'SA', 
            TMIN: 'Tmin', 
            SCAN: 'Datos del Archivo (Escaneo)', 
            EVAL: 'Evaluación', 
            FOTOS: 'Fotos'
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
                <td colspan="15">
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
                    <td colspan="14">Longitud Inspeccionada</td>
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

            if ($rowsBlock.length >= 10) { 
                const $nfila = $rowsBlock
                    .not('.long-row')
                    .eq(9);

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
        //TERMINA  restoreData()
        
        $('#addTituloBtn').click(function () {
            verificarYAgregarLongitud();
            tituloCount++;
            rowCount = 0; // Reiniciar el contador de filas para este título
            // ID único: counter + timestamp (evita duplicados aunque el texto sea igual)
            const titleId = `titulo_${tituloCount}_${Date.now()}`;

            //-----------------------------------------Hacer ajuste del colspan="14" de acuerdo a la tabla
            
            let newTitle = `
            <tr class="titulo-row" data-titulo="${titleId}">
                <td colspan="15">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control w-90 titulo-text" name="titulos_text[${titleId}]" placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3 (DETALLE DE OREJA DE IZAJE 1/4)">
                        <input type="hidden" class="titulo-id" name="titulos_ids[]" value="${titleId}"> <!-- Campo oculto para el ID del título -->
                        <td><button type="button" class="btn btn-danger btnEliminarTitulo">
                            <i class="fa fa-times"  aria-hidden="true"></i>
                        </button></td>
                    </div>
                </td>
            </tr>
        `;

        $('#dynamicTable tbody').append(newTitle);
        updateTitulos(); // Actualizar lista de títulos
        //saveData(document.querySelectorAll("form")[1].id);
        // Guardar de forma robusta: usar el form relativo o id explícito
        saveData($(this).closest('form').attr('id'));
        });

        $('#addLongBtn').click(function () {
            verificarYAgregarLongitud();
            //let numFilas = parseInt($('#numRows').val());
            let numFilas = parseInt($('#numRows').val(), 10) || 0;
            // Recontar filas existentes que NO son títulos
            rowCountGlobal = $('#dynamicTable tbody tr').not('.titulo-row, .long-row').length;

            let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

            let newTitle = `
            <!--<tr class="titulo-row long-row" data-titulo="${lastTitle}">-->
                <tr class="long-row" data-titulo="${lastTitle}">
                <td colspan="14"> Longitud Inspeccionada</td>
                <td>
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control w-90 long-text" name="Long_Inspecc[${lastTitle}][]" placeholder="Ingrese Longitud Inspeccionada...">
                        <td><button type="button" class="btn btn-danger btnEliminar">
                            <i class="fa fa-times"  aria-hidden="true"></i>
                        </button></td>
                    </div>
                </td>
            </tr>
        `;

        $('#dynamicTable tbody').append(newTitle);
        updateTitulos(); // Actualizar lista de títulos
        //saveData(document.querySelectorAll("form")[1].id);
        // Guardar de forma robusta: usar el form relativo o id explícito
        saveData($(this).closest('form').attr('id'));
        });

        $('#addBtn').click(function () {
            verificarYAgregarLongitud();
            //let numFilas = parseInt($('#numRows').val());
            let numFilas = parseInt($('#numRows').val(), 10) || 0;
            // Recontar filas existentes que NO son títulos
            rowCountGlobal = $('#dynamicTable tbody tr').not('.titulo-row, .long-row').length;

            let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

            for (let i = 0; i < numFilas; i++) {
            rowCount++; // Incrementar el contador general de filas
            rowCountGlobal++; // Incrementar el contador global de filas Solo es visualmente esta variable

            let newRow = 
                    `<tr data-titulo="${lastTitle}">
                        <td>${rowCountGlobal} <input type="hidden" value="${rowCount}">
                        </td><td><input type="text" class="form-control" name="no_junta[${lastTitle}][]" placeholder="Junta / Elemento"></td>
                        <td><input type="text" class="form-control" name="Tip_Ind[${lastTitle}][]" placeholder="Tipo de Indicación"></td>
                        <td><input type="text" class="form-control" name="L_PGL[${lastTitle}][]" placeholder="L (PLG)"></td>
                        <td><input type="text" class="form-control" name="A_PGL[${lastTitle}][]" placeholder="A (PLG)"></td>
                        <td><input type="text" class="form-control" name="AL_PGL[${lastTitle}][]" placeholder="ALTURA (PLG)"></td>
                        <td><input type="text" class="form-control" name="X[${lastTitle}][]" placeholder="X"></td>
                        <td><input type="text" class="form-control" name="Y[${lastTitle}][]" placeholder="Y"></td>
                        <td><input type="text" class="form-control" name="DA_PROF[${lastTitle}][]" placeholder="DA (PROF)"></td>
                        <td><input type="text" class="form-control" name="PA[${lastTitle}][]" placeholder="PA"></td>
                        <td><input type="text" class="form-control" name="SA[${lastTitle}][]" placeholder="SA"></td>
                        <td><input type="text" class="form-control" name="TMIN[${lastTitle}][]" placeholder="Tmin"></td>
                        <td><input type="text" class="form-control" name="SCAN[${lastTitle}][]" placeholder="Datos del Archivo (Escaneo)"></td>
                        <td><input type="text" class="form-control" name="EVAL[${lastTitle}][]" placeholder="Evaluación"></td>
                        <td><input type="text" class="form-control" name="FOTOS[${lastTitle}][]" placeholder="Fotos"></td>
                        <td><button type="button" class="btn btn-danger btnEliminar">   <i class="fa fa-times"  aria-hidden="true"></i></button></td>
                    </tr>`;

                $('#dynamicTable tbody').append(newRow);
            }
            verificarYAgregarLongitud();
            //saveData(document.querySelectorAll("form")[1].id);
            saveData($(this).closest('form').attr('id'));
        }
    );
            // Restaurar datos al cargar la página
            restoreData();
});

    function verificarYAgregarLongitud() {

        const $rows = $('#dynamicTable tbody tr');

        let contadorBloque = 0;

        $rows.each(function () {

            const $row = $(this);

            // ✅ Si ya hay longitud → cerrar bloque
            if ($row.hasClass('long-row')) {
                contadorBloque = 0;
                return;
            }

            contadorBloque++;

            // 🎯 Cuando llega a 10 → insertar longitud
            if (contadorBloque === 10) {

                const lastTitle = $row.data('titulo') || 'sin_titulo';

                const newLong = `
                    <tr class="long-row" data-titulo="${lastTitle}">
                        <td colspan="14">Longitud Inspeccionada</td>
                        <td>
                            <input type="text"
                                class="form-control long-text"
                                name="Long_Inspecc[${lastTitle}][]">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btnEliminar">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>`;

                // 👉 evitar duplicado
                if (!$row.next().hasClass('long-row')) {
                    $row.after(newLong);
                }

                // 🔄 cerrar bloque
                contadorBloque = 0;
            }
        });
    }



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
            $('#IDInputE').val($('#equiposSelect').val() || '');
        }
        
            const selectedOptionLocalE = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Equipos');
            selectedOptionLocalE != null ?  ($('#equiposSelect').val(selectedOptionLocalE),actualizarInputsE()):"";

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect').on('change', function() {
                actualizarInputsE();
            });

            function actualizarInputsbyp() {
                var selectedOption = $('#blockyprobetaSelect').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var nombre = selectedOption.data('nombre') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#nombreInputbyp').val(nombre);
                $('#nsInputbyp').val(ns);
                $('#IDInputbyp').val($('#blockyprobetaSelect').val() || '');
            }

            const selectedOptionLocalbyp = localStorage.getItem(document.querySelectorAll("form")[1].id+'_ByP');
            selectedOptionLocalbyp != null ?  ($('#blockyprobetaSelect').val(selectedOptionLocalbyp),actualizarInputsbyp()):"";

            // Evento cuando se cambia la selección en el select
            $('#blockyprobetaSelect').on('change', function() {
                actualizarInputsbyp();
            });

            function actualizarInputsA1() {
                var selectedOption = $('#accesoriosSelect1').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA1').val(marca);
                $('#modeloInputA1').val(modelo);
                $('#nsInputA1').val(ns);
                $('#IDInputA1').val($('#accesoriosSelect1').val() || '');
            }

            const selectedOptionLocalA1 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Accesorios1');
            selectedOptionLocalA1 != null ?  ($('#accesoriosSelect1').val(selectedOptionLocalA1),actualizarInputsA1()):"";
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect1').on('change', function() {
                    actualizarInputsA1();
                });

            function actualizarInputsA2() {
                var selectedOption = $('#accesoriosSelect2').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA2').val(marca);
                $('#modeloInputA2').val(modelo);
                $('#nsInputA2').val(ns);
                $('#IDInputA2').val($('#accesoriosSelect2').val() || '');
            }

            const selectedOptionLocalA2 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Accesorios2');
            selectedOptionLocalA2 != null ?  ($('#accesoriosSelect2').val(selectedOptionLocalA2),actualizarInputsA2()):"";
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect2').on('change', function() {
                    actualizarInputsA2();
                });

            function actualizarInputsA3() {
                var selectedOption = $('#accesoriosSelect3').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA3').val(marca);
                $('#modeloInputA3').val(modelo);
                $('#nsInputA3').val(ns);
                $('#IDInputA3').val($('#accesoriosSelect3').val() || '');
            }

            const selectedOptionLocalA3 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Accesorios3');
            selectedOptionLocalA3 != null ?  ($('#accesoriosSelect3').val(selectedOptionLocalA3),actualizarInputsA3()):"";
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect3').on('change', function() {
                    actualizarInputsA3();
                });

            function actualizarInputsA4() {
                var selectedOption = $('#accesoriosSelect4').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA4').val(marca);
                $('#modeloInputA4').val(modelo);
                $('#nsInputA4').val(ns);
                $('#IDInputA4').val($('#accesoriosSelect4').val() || '');
            }

            const selectedOptionLocalA4 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Accesorios4');
            selectedOptionLocalA4 != null ?  ($('#accesoriosSelect4').val(selectedOptionLocalA4),actualizarInputsA4()):"";
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect4').on('change', function() {
                    actualizarInputsA4();
                });

            function actualizarInputsA5() {
                var selectedOption = $('#accesoriosSelect5').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA5').val(marca);
                $('#modeloInputA5').val(modelo);
                $('#nsInputA5').val(ns);
                $('#IDInputA5').val($('#accesoriosSelect5').val() || '');
            }

            const selectedOptionLocalA5 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Accesorios5');
            selectedOptionLocalA5 != null ?  ($('#accesoriosSelect5').val(selectedOptionLocalA5),actualizarInputsA5()):"";
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect5').on('change', function() {
                    actualizarInputsA5();
                });

            function actualizarInputsA6() {
                var selectedOption = $('#accesoriosSelect6').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA6').val(marca);
                $('#modeloInputA6').val(modelo);
                $('#nsInputA6').val(ns);
                $('#IDInputA6').val($('#accesoriosSelect6').val() || '');
            }

            const selectedOptionLocalA6 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Accesorios6');
            selectedOptionLocalA6 != null ?  ($('#accesoriosSelect6').val(selectedOptionLocalA6),actualizarInputsA6()):"";
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect6').on('change', function() {
                    actualizarInputsA6();
                });

            function actualizarInputsA7() {
                var selectedOption = $('#accesoriosSelect7').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA7').val(marca);
                $('#modeloInputA7').val(modelo);
                $('#nsInputA7').val(ns);
                $('#IDInputA7').val($('#accesoriosSelect7').val() || '');
            }

            const selectedOptionLocalA7 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Accesorios7');
            selectedOptionLocalA7 != null ?  ($('#accesoriosSelect7').val(selectedOptionLocalA7),actualizarInputsA7()):"";
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect7').on('change', function() {
                    actualizarInputsA7();
                });

            function actualizarInputsA8() {
                var selectedOption = $('#accesoriosSelect8').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA8').val(marca);
                $('#modeloInputA8').val(modelo);
                $('#nsInputA8').val(ns);
                $('#IDInputA8').val($('#accesoriosSelect8').val() || '');
            }

            const selectedOptionLocalA8 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Accesorios8');
            selectedOptionLocalA8 != null ?  ($('#accesoriosSelect8').val(selectedOptionLocalA8),actualizarInputsA8()):"";
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect8').on('change', function() {
                    actualizarInputsA8();
                });

        function actualizarInputsE2() {
            var selectedOption = $('#equiposSelect2').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var marca = selectedOption.data('marca') || '';
            var modelo = selectedOption.data('modelo') || '';
            var ns = selectedOption.data('ns') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#marcaInputE2').val(marca);
            $('#modeloInputE2').val(modelo);
            $('#nsInputE2').val(ns);
            $('#IDInputE2').val($('#equiposSelect2').val() || '');
        }
        
            const selectedOptionLocalE2 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Equipos2');
            selectedOptionLocalE2 != null ?  ($('#equiposSelect2').val(selectedOptionLocalE2),actualizarInputsE2()):"";

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect2').on('change', function() {
                actualizarInputsE2();
            });

            function actualizarInputsE3() {
            var selectedOption = $('#equiposSelect3').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var marca = selectedOption.data('marca') || '';
            var modelo = selectedOption.data('modelo') || '';
            var ns = selectedOption.data('ns') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#marcaInputE3').val(marca);
            $('#modeloInputE3').val(modelo);
            $('#nsInputE3').val(ns);
            $('#IDInputE3').val($('#equiposSelect3').val() || '');
        }
        
            const selectedOptionLocalE3 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Equipos3');
            selectedOptionLocalE3 != null ?  ($('#equiposSelect3').val(selectedOptionLocalE3),actualizarInputsE3()):"";

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect3').on('change', function() {
                actualizarInputsE3();
            });
    });

    /*FOR-PINS-14_01*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-PINS-14-01');
        if (!form) return;

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('FOR-PINS-14-01_' + el.name, el.value);
            //});
        //});

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-PINS-14-01_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-PINS-14-01_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-PINS-14-01_' + el.name);
                //localStorage.clear();
            });
        });
    });
</script>
@endsection
