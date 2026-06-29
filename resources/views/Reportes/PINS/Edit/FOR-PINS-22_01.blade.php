@extends('adminlte::page')

@section('title', 'FOR-PINS-22/01')

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
                    <div class="card w-100 p-3">
                        <div class="card-body w-100">
                            <form id="FOR-PINS-22_01" action="{{ route('Reportes_FOR_PINS_22_01.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>No se pudo guardar la actualización.</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row">
                                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn my-2">Rellenar Campos Vacios "---"</button>
                                <div style="margin-bottom: 2px;"></div>
                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                                            <input type="date" class="form-control  inputForm @error('Fecha') is-invalid @enderror" name="Detalles_Generales[Fecha]"  placeholder="Ejemplo: DD/MM/AAAA" value="{{ old('Detalles_Generales.Fecha', $Detalles_Generales['Fecha'] ?? '') }}">
                                            @error('Fecha')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. de Reporte</label>
                                            <input type="text"
                                                class="form-control inputForm @error('Detalles_Generales.No_Reporte') is-invalid @enderror"
                                                name="Detalles_Generales[No_Reporte]"
                                                placeholder="Ejemplo: 077-8DUCTOS-24"
                                                value="{{ old('Detalles_Generales.No_Reporte', $Detalles_Generales['No_Reporte'] ?? '') }}"
                                                readonly>
                                            @error('Detalles_Generales.No_Reporte')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control  inputForm @error('Cliente') is-invalid @enderror" name="Detalles_Generales[Cliente]"  placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{old('Detalles_Generales.Cliente', $Detalles_Generales['Cliente'] ?? '')}}" readonly>
                                            @error('Cliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control  inputForm @error('Contrato') is-invalid @enderror" name="Detalles_Generales[Contrato]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Contrato', $Detalles_Generales['Contrato'] ?? '')}}" readonly>
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Identificación</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Identificacion]" placeholder="Ejemplo: INGENIERÍA, PROCURA, CONSTRUCCIÓN DE DUCTOS MARINOS NUEVOS PARA MANEJO DE PRODUCCIÓN DE PLATAFORMAS GENÉRICAS, A INSTALARSE EN LA SONDA DE CAMPECHE, GOLFO DE MÉXICO ...">{{old('Detalles_Generales.Identificacion', $Detalles_Generales['Identificacion'] ?? '')}}</textarea>
                                            @error('Identificacion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Serie</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Serie]" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . .">{{old('Detalles_Generales.Serie', $Detalles_Generales['Serie'] ?? '')}}</textarea>
                                            @error('Serie')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fabricante</label>
                                            <input type="text" class="form-control  inputForm @error('Fabricante') is-invalid @enderror" name="Detalles_Generales[Fabricante]"  placeholder="Ejemplo:" value="{{old('Detalles_Generales.Fabricante', $Detalles_Generales['Fabricante'] ?? '')}}">
                                            @error('Fabricante')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número de Tubos</label>
                                            <input type="text" class="form-control  inputForm @error('Numero_Tubos') is-invalid @enderror" name="Detalles_Generales[Numero_Tubos]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Numero_Tubos', $Detalles_Generales['Numero_Tubos'] ?? '')}}">
                                            @error('Numero_Tubos')
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
                                            <label class="col-form-label" for="inputSuccess">Diametro</label>
                                            <input type="text" class="form-control  inputForm @error('Diametro') is-invalid @enderror" name="Detalles_Generales[Diametro]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Diametro', $Detalles_Generales['Diametro'] ?? '')}}">
                                            @error('Diametro')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Longitud</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Longitud]" placeholder="Ejemplo: D-7205-TENTOK-A-Q-200 / D-7205-TENTOK-A-Q-201 / D-7205-TENTOK-A-Q-202 / D-7205-TENTOK-A-Q-203 / D-7205-TENTOK-A-Q-204 / D-7205-TENTOK-A-Q-205 /D-7205-TENTOK-A-Q-206 / D-7205-TENTOK-A-Q-207 / D-7205-TENTOK-A-Q-208 / D-7205-TENTOK-A-Q-209 . . . .">{{old('Detalles_Generales.Longitud', $Detalles_Generales['Longitud'] ?? '')}}</textarea>
                                            @error('Longitud')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Año de fabricación</label>
                                            <input type="text" class="form-control  inputForm @error('Año_Fabricacion') is-invalid @enderror" name="Detalles_Generales[Año_Fabricacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Año_Fabricacion', $Detalles_Generales['Año_Fabricacion'] ?? '')}}">
                                            @error('Año_Fabricacion')
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

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Procedimiento</label>
                                            <input type="text" class="form-control  inputForm @error('Procedimiento') is-invalid @enderror" name="Detalles_Generales[Procedimiento]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Procedimiento', $Detalles_Generales['Procedimiento'] ?? '')}}">
                                            @error('Procedimiento')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Criterio de Evaluación</label>
                                            <input type="text" class="form-control  inputForm @error('Criterio_Evaluacion') is-invalid @enderror" name="Detalles_Generales[Criterio_Evaluacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Criterio_Evaluacion', $Detalles_Generales['Criterio_Evaluacion'] ?? '')}}">
                                            @error('Criterio_Evaluacion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="alert alert-info alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-info"></i> Importante</h5>
                                        <p>Puedes Seleccionar un equipo, bobinas y un block del menu o escribir directamente</p>
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
                                            <input type="hidden" name="Datos_Equipo[ID_EQUIPO]" id="IDInputE" value="{{ old('Datos_Equipo.ID_EQUIPO', $Datos_Equipo['ID_EQUIPO'] ?? '') }}">
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
                                            <input type="text" class="form-control  inputForm" id="nsInputE" name="Datos_Equipo[NS_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.NS_EQUIPO', $Datos_Equipo['NS_EQUIPO'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">BOBINA</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Bobina:</label>
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
                                            <input type="hidden" name="Datos_Equipo[ID_BP]" id="IDInputA" value="{{ old('Datos_Equipo.ID_BP', $Datos_Equipo['ID_BP'] ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" id="marcaInputA" name="Datos_Equipo[MARCA_BP]" placeholder="" value="{{old('Datos_Equipo.MARCA_BP', $Datos_Equipo['MARCA_BP'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputA" name="Datos_Equipo[MODELO_BP]" placeholder="" value="{{old('Datos_Equipo.MODELO_BP', $Datos_Equipo['MODELO_BP'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" id="nsInputA" name="Datos_Equipo[NS_BP]" placeholder="" value="{{old('Datos_Equipo.NS_BP', $Datos_Equipo['NS_BP'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">FREC:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[FREC_BP]" placeholder="" value="{{old('Datos_Equipo.FREC_BP', $Datos_Equipo['FREC_BP'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">BLOCK DE REFERENCIA</div>

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
                                            <input type="hidden" name="Datos_Equipo[ID_BLOCK]" id="IDInputbyp" value="{{ old('Datos_Equipo.ID_BLOCK', $Datos_Equipo['ID_BLOCK'] ?? '') }}">
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
                                            <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[NS_BLOCK]" placeholder="" value="{{old('Datos_Equipo.NS_BLOCK', $Datos_Equipo['NS_BLOCK'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">CERTIFICADO DE CALIBRACIÓN DEL EQUIPO:</div>
                                    <div>
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[CER_CALIBRACION]" placeholder="" value="{{old('Datos_Equipo.CER_CALIBRACION', $Datos_Equipo['CER_CALIBRACION'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">VIGENCIA DE CALIBRACIÓN DEL EQUIPO</div>
                                    <div>
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[VIG_CALIBRACION]" placeholder="" value="{{old('Datos_Equipo.VIG_CALIBRACION', $Datos_Equipo['VIG_CALIBRACION'] ?? '')}}">
                                        </div>
                                    </div>
                                    
                                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                                    <!--***************************************** INICIO RESULTADOS *****************************************-->

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>
                                    
                                    <div style="margin-bottom: 2px;"></div>

                                    <div class="table-responsive">
                                        <div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-info"></i> Importante</h5>
                                            <p>La primera fila es para el llenado automatico de cada una de las columnas del formato.</p>
                                        </div>
                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Elemento</th>
                                                <th>Referencia (mm)</th>
                                                <th>Fase °</th>
                                                <th>Ganancia</th>
                                                <th>Alcance de Inspección</th>
                                                <th>Canal</th>
                                                <th>%Insp.</th>
                                                <th>%Obstr.</th>
                                                <th>Fila</th>
                                                <th>Columna</th>
                                                <th>Tipo de Alarma</th>
                                                <th>Longitud de la Franja</th>
                                                <th>Observaciones</th>
                                                <th>Eliminar</th>
                                            </tr>

                                            <tr id="inputRow">
                                                <th></th>
                                                <th><input type="text" class="form-control default-input" data-column="1" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="2" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="3" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="4" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="5" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="6" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="7" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="8" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="9" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="10" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="11" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="12" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="13" style="width: 100px;"></th>
                                                <th><input type="text" class="form-control default-input" data-column="14" style="width: 100px;"></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                            <tbody> 
                                            @php 
                                                $contador = 1; 
                                            @endphp

                                            @foreach ($Grupo_Juntas_Re as $bloque)
                                                @foreach ($bloque as $item)
                                                    @php
                                                        $titleId = $item['grupo'] ?? 'sin_titulo';
                                                    @endphp
                                                    <!-- TITULOS -->
                                                    @if ($item['tipo'] == 'titulo')
                                                        <tr class="titulo-row" data-titulo="{{ $titleId }}">
                                                            <td colspan="15">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <input type="text"
                                                                        class="form-control w-90 titulo-text"
                                                                        name="titulos_text[{{ $titleId }}]"
                                                                        value="{{ $item['texto'] }}"
                                                                        placeholder="Ingrese título...">

                                                                    <input type="hidden" class="titulo-id" name="titulos_ids[]" value="{{ $titleId }}">

                                                                    <td>
                                                                        <button type="button" class="btn btn-danger btnEliminarTitulo">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </td>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <!-- FILAS -->
                                                    @if ($item['tipo'] == 'fila')
                                                        <tr data-titulo="{{ $titleId }}">
                                                            <td>{{ $contador }} <input type="hidden" value="{{ $contador }}"></td>
                                                            <td><input type="text" class="form-control" name='ID[{{ $titleId}}][]' value="{{ $item['data']['ID'] }}"></td>
                                                            <td><input type="text" class="form-control" name='elemento[{{ $titleId }}][]' value="{{ $item['data']['elemento'] }}"></td>
                                                            <td><input type="text" class="form-control" name='referencia[{{ $titleId }}][]' value="{{ $item['data']['referencia'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='fase[{{ $titleId }}][]' value="{{ $item['data']['fase'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='ganancia[{{ $titleId }}][]' value="{{ $item['data']['ganancia'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='alcanse_inspeccion[{{ $titleId }}][]' value="{{ $item['data']['alcanse_inspeccion'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='canal[{{ $titleId }}][]' value="{{ $item['data']['canal'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='inspeccion[{{ $titleId }}][]' value="{{ $item['data']['inspeccion'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='obstruccion[{{ $titleId }}][]' value="{{ $item['data']['obstruccion'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='fila[{{ $titleId }}][]' value="{{ $item['data']['fila'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='columna[{{ $titleId }}][]' value="{{ $item['data']['columna'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='tipo_alarma[{{ $titleId }}][]' value="{{ $item['data']['tipo_alarma'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='longitud_franja[{{ $titleId }}][]' value="{{ $item['data']['longitud_franja'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='observaciones[{{ $titleId }}][]' value="{{ $item['data']['observaciones'] }}"></td>
                                                            <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                        </tr>
                                                        @php $contador++; @endphp
                                                    @endif
                                                    <!-- LONGITUD (CIERRA BLOQUE) -->
                                                    @if ($item['tipo'] == 'longitud')
                                                        <tr class="long-row" data-titulo="{{ $titleId }}">
                                                            <td colspan="14">Longitud Inspeccionada</td>

                                                            <td>
                                                                <input type="text"
                                                                    class="form-control long-text"
                                                                    name="Long_Inspecc[{{ $titleId }}][]"
                                                                    value="{{ $item['valor'] }}">
                                                            </td>

                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-danger btnEliminar">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                    <input type="hidden" id="titulos_hidden" name="titulos_data">
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

                                            <button id="addLongBtn" type="button" class="btn btn-success custom-btn">Agregar Longitud Inspeccionada</button>

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
                                                                <td>NO PRESENTA INDICACIONES RELEVANTES</td>
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
                                                                <td style="text-align:left;">ÁNGULO (°)</td>
                                                                <td><strong>LA:</strong></td>
                                                                <td style="text-align:left;">LONGITUD AXIAL (IN)</td>
                                                                <td rowspan="2"><strong>t<sub>a</sub></strong></td>
                                                                <td rowspan="2" style="text-align:left;">ESPESOR DE LA PARED EN ZONA<br>SANA ADYACENTE</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>G:</strong></td>
                                                                <td style="text-align:left;">GANANCIA (dB)</td>
                                                                <td><strong>LC</strong></td>
                                                                <td style="text-align:left;">LONGITUD CIRCUNFERENCIAL (IN)</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>NR:</strong></td>
                                                                <td style="text-align:left;">NIVEL DE REFERENCIA (%)</td>
                                                                <td><strong>DNR</strong></td>
                                                                <td style="text-align:left;">DISTANCIA DE NIVEL DE REFERENCIA (IN)</td>
                                                                <td><strong>H.T.</strong></td>
                                                                <td style="text-align:left;">HORARIO TÉCNICO</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>NI:</strong></td>
                                                                <td style="text-align:left;">NIVEL DE INDICACIÓN (%)</td>
                                                                <td><strong>t<sub>min</sub></strong></td>
                                                                <td style="text-align:left;">ESPESOR MÍNIMO REGISTRADO (PULG)</td>
                                                                <td><strong>d</strong></td>
                                                                <td style="text-align:left;">PROFUNDIDAD DE LA INDICACION(IN)</td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Observaciones:</label>
                                                <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[OBS]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{old('OBS', $Datos_Equipo['OBS'] ?? '')}}</textarea>
                                            </div>
                                        </div>
                                        </div>

                                        <!-- Select para elegir el número de firmas -->
                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">Número de Firmas:</div>
                                        <div class="col-sm-15">
                                            <div class="form-group">
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">.
                                                    <option value="1" {{ $numFirmas == 1 ? 'selected' : '' }}>1 Firma</option>
                                                    <option value="2" {{ $numFirmas == 2 ? 'selected' : '' }}>2 Firmas</option>
                                                    <option value="3" {{ $numFirmas == 3 ? 'selected' : '' }}>3 Firmas</option>
                                                    <option value="4" {{ $numFirmas == 4 ? 'selected' : '' }}>4 Firmas</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- 1 DOS FIRMAS-->
                                        <div id="firmas1" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[Realizo]" placeholder="Ejemplo: Realizo" value="{{old('Realizo', $Firmas['Realizo'] ?? '')}}"></th>
                                                    </tr>

                                                    <tr>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                    </tr>

                                                    <tr>

                                                        <td>
                                                            <div class="col-sm-50 d-flex justify-content-center">
                                                                    <div class="form-group text-center">
                                                                    <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                                        <select class="form-select inputForm" id="tecnicosSelect">
                                                                            <option value="" selected disabled>Seleccione un Técnico</option>

                                                                            @foreach($Tecnicos as $Tecnico)
                                                                                <option value="{{ $Tecnico->id }}"
                                                                                        data-name="{{ $Tecnico->name }}">
                                                                                    {{ $Tecnico->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        <!-- hidden si quieres guardar el ID explícitamente -->
                                                                        <input type="hidden" name="Firmas_Reportes1[ID_TECNICO]" id="IDTECNICO" value="{{old('Firmas_Reportes1.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                        <input type="text" class="form-control  inputForm" name="Firmas_Reportes1[NOMBRE_TECNICO]" id="NOMBRE_TECNICO" value="{{old('Firmas_Reportes1.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                    </div>
                                                            </div>
                                                            
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
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

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Realizo]" placeholder="Ejemplo: Realizo" value="{{old('Realizo', $Firmas['Realizo'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Vobo1]" placeholder="Ejemplo: Vobo1" value="{{old('Vobo1', $Firmas['Vobo1'] ?? '')}}"></th>
                                                    
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
                                                                    <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                                        <select class="form-select inputForm" id="tecnicosSelect2">
                                                                            <option value="" selected disabled>Seleccione un Técnico</option>

                                                                            @foreach($Tecnicos as $Tecnico)
                                                                                <option value="{{ $Tecnico->id }}"
                                                                                        data-name="{{ $Tecnico->name }}">
                                                                                    {{ $Tecnico->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        <!-- hidden si quieres guardar el ID explícitamente -->
                                                                        <input type="hidden" name="Firmas_Reportes2[ID_TECNICO]" id="IDTECNICO2" value="{{old('Firmas_Reportes2.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                        <input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_TECNICO]" id="NOMBRE_TECNICO2" value="{{old('Firmas_Reportes2.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        <td>

                                                        </td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO', $Firmas['NOMBRE_ENCARGADO'] ?? '')}}"></td>
                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[PUESTO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO', $Firmas['PUESTO_ENCARGADO'] ?? '')}}"></td>
                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO', $Firmas['EMPRESA_ENCARGADO'] ?? '')}}"></td>
                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 3 TRES FIRMAS-->
                                        <div id="firmas3" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Realizo]" placeholder="Ejemplo: Realizo" value="{{old('Realizo', $Firmas['Realizo'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo1]" placeholder="Ejemplo: Vobo1" value="{{old('Vobo1', $Firmas['Vobo1'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo2]" placeholder="Ejemplo: Vobo2" value="{{old('Vobo2', $Firmas['Vobo2'] ?? '')}}"></th>

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
                                                                    <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                                        <select class="form-select inputForm" id="tecnicosSelect3">
                                                                            <option value="" selected disabled>Seleccione un Técnico</option>

                                                                            @foreach($Tecnicos as $Tecnico)
                                                                                <option value="{{ $Tecnico->id }}"
                                                                                        data-name="{{ $Tecnico->name }}">
                                                                                    {{ $Tecnico->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        <!-- hidden si quieres guardar el ID explícitamente -->
                                                                        <input type="hidden" name="Firmas_Reportes3[ID_TECNICO]" id="IDTECNICO3" value="{{old('Firmas_Reportes3.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                        <input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_TECNICO]" id="NOMBRE_TECNICO3" value="{{old('Firmas_Reportes3.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        <td>

                                                        </td>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO', $Firmas['NOMBRE_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_2DO_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_2DO_ENCARGADO', $Firmas['NOMBRE_2DO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO', $Firmas['PUESTO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_2DO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL SEGUNDO ENCARGADO" value="{{old('PUESTO_2DO_ENCARGADO', $Firmas['PUESTO_2DO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO', $Firmas['EMPRESA_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_2DO_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO', $Firmas['EMPRESA_2DO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 4 CUATRO FIRMAS-->
                                        <div id="firmas4" class="col-12" style="display: none;">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Realizo]" placeholder="Ejemplo: Realizo" value="{{old('Realizo', $Firmas['Realizo'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo1]" placeholder="Ejemplo: Vobo1" value="{{old('Vobo1', $Firmas['Vobo1'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo2]" placeholder="Ejemplo: Vobo2" value="{{old('Vobo2', $Firmas['Vobo2'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo3]" placeholder="Ejemplo: Vobo3" value="{{old('Vobo3', $Firmas['Vobo3'] ?? '')}}"></th>

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
                                                                <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                                    <select class="form-select inputForm" id="tecnicosSelect4">
                                                                        <option value="" selected disabled>Seleccione un Técnico</option>

                                                                        @foreach($Tecnicos as $Tecnico)
                                                                            <option value="{{ $Tecnico->id }}"
                                                                                    data-name="{{ $Tecnico->name }}">
                                                                                {{ $Tecnico->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                    <!-- hidden si quieres guardar el ID explícitamente -->
                                                                    <input type="hidden" name="Firmas_Reportes4[ID_TECNICO]" id="IDTECNICO4" value="{{old('Firmas_Reportes4.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                    <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                    <input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_TECNICO]" id="NOMBRE_TECNICO4" value="{{old('Firmas_Reportes4.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO', $Firmas['NOMBRE_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_2DO_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_2DO_ENCARGADO', $Firmas['NOMBRE_2DO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_3RO_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL TERCER ENCARGADO" value="{{old('NOMBRE_3RO_ENCARGADO', $Firmas['NOMBRE_3RO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO', $Firmas['PUESTO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_2DO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL SEGUNDO ENCARGADO" value="{{old('PUESTO_2DO_ENCARGADO', $Firmas['PUESTO_2DO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_3RO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL TERCER ENCARGADO" value="{{old('PUESTO_3RO_ENCARGADO', $Firmas['PUESTO_3RO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO', $Firmas['EMPRESA_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_2DO_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO', $Firmas['EMPRESA_2DO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_3RO_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL TERCER ENCARGADO" value="{{old('EMPRESA_3RO_ENCARGADO', $Firmas['EMPRESA_3RO_ENCARGADO'] ?? '')}}"></td>

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

                                        @if(!empty($Fotos_Comentarios))
                                            <div class="row">
                                                @foreach($Fotos_Comentarios as $index => $foto)
                                                    <div class="col-sm-6" id="image-container-{{ $index }}">
                                                        <div class="form-group">
                                                            <!-- Vista previa de la imagen existente -->
                                                            <label for="replace_image_{{ $index }}">Imagen Subida {{ $index + 1 }}:</label>
                                                            <div class="image-preview mt-2">
                                                                <img src="{{ asset($foto['ruta']) }}" class="img-fluid img-thumbnail" alt="Imagen Reporte">
                                                            </div>

                                                            <div class="form-check mt-2">
                                                                <input type="checkbox"class="form-check-input imagen-hoja-checkbox" data-index="{{ $index }}"id="imagenHoja{{ $index }}"{{ !empty($foto['una_hoja']) && $foto['una_hoja'] == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="imagenHoja{{ $index }}">Imagen en una hoja</label>
                                                            </div>
                                                            <input type="hidden" name="imagen_hoja[{{ $index }}]" id="imagenHojaValue{{ $index }}" value="{{ $foto['una_hoja'] ?? 0 }}">
                                                            <!-- Campo para seleccionar una nueva imagen -->
                                                            <input type="file" class="form-control image-input mt-2" id="replace_image_{{ $index }}" name="replace_images[{{ $index }}]" accept="image/*">

                                                            <!-- Campo para el comentario -->
                                                            <textarea class="form-control mt-2" name="comments[{{ $index }}]" placeholder="Comentario">{{ $foto['comentario'] }}</textarea>

                                                            <!-- Campo oculto para la imagen en base64 -->
                                                            <input type="hidden" name="images_base64[{{ $index }}]" id="replace_image_{{ $index }}-base64">

                                                            <!-- Campo oculto para mantener la ruta de la imagen existente -->
                                                            <input type="hidden" name="existing_images[{{ $index }}]" value="{{ $foto['ruta'] }}">

                                                            <!-- Campo oculto para marcar imágenes eliminadas -->
                                                            <input type="hidden" name="deleted_images[]" id="deleted_image_{{ $index }}" value="">

                                                            <!-- Botón de eliminación -->
                                                            <button type="button" class="btn btn-danger mt-2 remove-image" data-index="{{ $index }}">Eliminar</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p>No hay imágenes disponibles.</p>
                                        @endif

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
                                                <input type="text" class="form-control  inputForm @error('Num_Soldador') is-invalid @enderror" name="Detalles_Generales[Num_Soldador]"  placeholder="Ejemplo: 12345" value="{{ old('Detalles_Generales.Num_Soldador', $Detalles_Generales['Num_Soldador'] ?? '') }}">
                                                @error('Num_Soldador')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Nombre soldador/Iniciales:</label>
                                                <input type="text" class="form-control  inputForm @error('Nombre_Soldador') is-invalid @enderror" name="Detalles_Generales[Nombre_Soldador]"  placeholder="Ejemplo: Juan Pérez" value="{{ old('Detalles_Generales.Nombre_Soldador', $Detalles_Generales['Nombre_Soldador'] ?? '') }}">
                                                @error('Nombre_Soldador')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                @enderror
                                            </div>
                                        </div>

                                        <p>

                                        <div class="d-flex justify-content-center align-items-center p-2 bg-success text-white rounded">SUBIR REPORTE FIRMADO</div>
                                                        
                                        <p>

                                        <div class="row justify-content-center text-center">
                                            {{-- Columna para Subir/Sustituir Archivo --}}
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess"> 
                                                        @if ($Detalles_Generales['Reporte_Firmado'] ?? '') 
                                                            SUSTITUIR REPORTE FIRMADO 
                                                        @else 
                                                            SUBIR REPORTE FIRMADO 
                                                        @endif
                                                    </label>
                                                    <input type="file" class="form-control-file inputForm" name="Detalles_Generales[Reporte_Firmado]">
                                                    @if ($errors->any())
                                                        <div class="invalid-feedback d-block">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Columna para Ver Reporte (Solo aparece si existe el archivo) --}}
                                            @if ($Detalles_Generales['Reporte_Firmado'] ?? '')
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="inputSuccess">Ver Reporte Firmado</label>  
                                                        <div>                                           
                                                            <a href="{{ asset($Detalles_Generales['Reporte_Firmado']) }}" target="_blank" class="btn btn-primary long-button" role="button">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>                                                                                    
                                                        </div> 
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="container">
                                            <div class="float-right">
                                                <button type="submit" class="btn btn-info bg-primary">Guardar</button>
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
<script src="{{ asset('js/Reportes_Edit.js') }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<script>
    /*Juntas-Resultados */
    /*Juntas-Resultados */
$(document).ready(function() {
    let tituloCount = $('.titulo-row').length;
    let rowCount = 0;
    let rowCountGlobal = 0;

        $('#addTituloBtn').click(function () {
            verificarYAgregarLongitud();
            tituloCount++;
            rowCount = 0;
            const titleId = `titulo_${tituloCount}_${Date.now()}`;

            let newTitle = `
            <tr class="titulo-row" data-titulo="${titleId}">
                <td colspan="15">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control w-90 titulo-text" name="titulos_text[${titleId}]" placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3 (DETALLE DE OREJA DE IZAJE 1/4)">
                        <input type="hidden" class="titulo-id" name="titulos_ids[]" value="${titleId}">
                        <td><button type="button" class="btn btn-danger btnEliminarTitulo">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button></td>
                    </div>
                </td>
            </tr>
            `;

            $('#dynamicTable tbody').append(newTitle);
            updateTitulos();
        });

        $('#addBtn').click(function () {
            verificarYAgregarLongitud();
            let numFilas = parseInt($('#numRows').val(), 10) || 0;
            rowCountGlobal = $('#dynamicTable tbody tr').not('.titulo-row, .long-row').length;

            let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

            for (let i = 0; i < numFilas; i++) {
                rowCount++;
                rowCountGlobal++;

                let newRow = `
                <tr data-titulo="${lastTitle}">
                    <td>${rowCountGlobal} <input type="hidden" value="${rowCountGlobal}"></td>
                    <td><input type="text" class="form-control" name="ID[${lastTitle}][]" placeholder="ID" value="${rowCountGlobal}"></td>
                    <td><input type="text" class="form-control" name="elemento[${lastTitle}][]" placeholder="Elemento"></td>
                    <td><input type="text" class="form-control" name="referencia[${lastTitle}][]" placeholder="Referencia (mm)"></td>
                    <td><input type="text" class="form-control" name="fase[${lastTitle}][]" placeholder="Fase"></td>
                    <td><input type="text" class="form-control" name="ganancia[${lastTitle}][]" placeholder="Ganancia"></td>
                    <td><input type="text" class="form-control" name="alcanse_inspeccion[${lastTitle}][]" placeholder="Alcance de Inspección"></td>
                    <td><input type="text" class="form-control" name="canal[${lastTitle}][]" placeholder="Canal"></td>
                    <td><input type="text" class="form-control" name="inspeccion[${lastTitle}][]" placeholder="%Insp"></td>
                    <td><input type="text" class="form-control" name="obstruccion[${lastTitle}][]" placeholder="%Obstru"></td>
                    <td><input type="text" class="form-control" name="fila[${lastTitle}][]" placeholder="Fila"></td>
                    <td><input type="text" class="form-control" name="columna[${lastTitle}][]" placeholder="Columna"></td>
                    <td><input type="text" class="form-control" name="tipo_alarma[${lastTitle}][]" placeholder="Tipo de Alarma"></td>
                    <td><input type="text" class="form-control" name="longitud_franja[${lastTitle}][]" placeholder="Longitud de la Franja"></td>
                    <td><input type="text" class="form-control" name="observaciones[${lastTitle}][]" placeholder="Observaciones"></td>
                    <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                </tr>`;

                $('#dynamicTable tbody').append(newRow);
            }

            verificarYAgregarLongitud();
        });

        $('#addLongBtn').click(function () {
            verificarYAgregarLongitud();
            let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

            let newTitle = `
                <tr class="long-row" data-titulo="${lastTitle}">
                    <td colspan="14"> Longitud Inspeccionada</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="text" class="form-control w-90 long-text" name="Long_Inspecc[${lastTitle}][]">
                            <td><button type="button" class="btn btn-danger btnEliminar">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button></td>
                        </div>
                    </td>
                </tr>
            `;

            $('#dynamicTable tbody').append(newTitle);
            updateTitulos();
        });

        $('form').submit(function(e) {
            if ($('#dynamicTable tbody tr').length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La tabla no puede estar vacía. Por favor, agregue al menos una fila.',
                });
                return;
            }

            updateTitulos();
            let submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true).text('Guardando...');
            submitButton.append(' <i class="fa fa-spinner fa-spin"></i>');
        });

    });

    function verificarYAgregarLongitud() {

        const $rows = $('#dynamicTable tbody tr');

        let contadorBloque = 0;

        $rows.each(function () {

            const $row = $(this);

            if ($row.hasClass('long-row')) {
                contadorBloque = 0;
                return;
            }

            contadorBloque++;

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

                if (!$row.next().hasClass('long-row')) {
                    $row.after(newLong);
                }

                contadorBloque = 0;
            }
        });
    }

    /*Selects */
    $(document).ready(function() {
        function actualizarInputsE() {
            var selectedOption = $('#equiposSelect').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var marca = selectedOption.data('marca') || '';
            var modelo = selectedOption.data('modelo') || '';
            var ns = selectedOption.data('ns') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#IDInputE').val(selectedOption.val() || '');
            $('#marcaInputE').val(marca);
            $('#modeloInputE').val(modelo);
            $('#nsInputE').val(ns);
        }

            if ($('#IDInputE').val()) {
                $('#equiposSelect').val($('#IDInputE').val());
                actualizarInputsE();
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
                $('#IDInputA').val(selectedOption.val() || '');
                $('#marcaInputA').val(marca);
                $('#modeloInputA').val(modelo);
                $('#nsInputA').val(ns);
            }

                if ($('#IDInputA').val()) {
                    $('#accesoriosSelect').val($('#IDInputA').val());
                    actualizarInputsA();
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
                $('#IDInputbyp').val(selectedOption.val() || '');
                $('#marcaInputbyp').val(marca);
                $('#modeloInputbyp').val(modelo);
                $('#nsInputbyp').val(ns);
            }

            if ($('#IDInputbyp').val()) {
                $('#blockyprobetaSelect').val($('#IDInputbyp').val());
                actualizarInputsbyp();
            }

            // Evento cuando se cambia la selección en el select
            $('#blockyprobetaSelect').on('change', function() {
                actualizarInputsbyp();
            });
        });

</script>
@endsection

