@extends('adminlte::page')

@section('title', 'FOR-PIMP-02_B_03')

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

<h3 align="center">REPORTE DE: {{ is_object($Prueba) ? $Prueba->Nombre : $Prueba }}</h3>
<h3 align="center">FORMATO: {{$Nombre_Formato}}</h3>
<h4 align="center">{{$formatoNombrePersonalizado}}</h4>
<br>
<section class="content w-100">
    <div class="card w-100 p-3">
        <div class="card-body w-100">
            <form id="FOR-PIMP-02_B_03" action="{{route('Reportes_FOR_PIMP_02_B_03.update', $id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn my-2">Rellenar Campos Vacios "---"</button>
                <div style="margin-bottom: 2px;"></div>
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                            <input type="date" class="form-control  inputForm @error('Fecha') is-invalid @enderror" name="Detalles_Generales[Fecha]"  placeholder="Ejemplo: DD/MM/AAAA" value="{{old('Detalles_Generales.Fecha', $Detalles_Generales['Fecha'] ?? '')}}">
                            @error('Fecha')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">No. Reporte:</label>
                            <input type="text" class="form-control  inputForm @error('No_Reporte') is-invalid @enderror" name="Detalles_Generales[No_Reporte]"  placeholder="Ejemplo: 077-8DUCTOS-24" value="{{old('Detalles_Generales.No_Reporte', $Detalles_Generales['No_Reporte'] ?? '')}}">
                            @error('No_Reporte')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Cliente:</label>
                            <input type="text" class="form-control inputForm @error('Cliente') is-invalid @enderror" name="Detalles_Generales[Cliente]" placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{ old('Detalles_Generales.Cliente', $Detalles_Generales['Cliente'] ?? '') }}" readonly>
                            @error('Cliente')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Contrato:</label>
                            <input type="text"
                                id="campoContrato"
                                class="form-control inputForm"
                                name="Detalles_Generales[Contrato]"
                                placeholder="Ejemplo: 640853841"
                                value="{{ old('Detalles_Generales.Contrato', $Detalles_Generales['Contrato'] ?? '') }}"
                                readonly>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Proyecto:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Proyecto]" placeholder="Ejemplo: INGENIERÍA, PROCURA, CONSTRUCCIÓN DE DUCTOS MARINOS NUEVOS PARA MANEJO DE PRODUCCIÓN DE PLATAFORMAS GENÉRICAS, A INSTALARSE EN LA SONDA DE CAMPECHE, GOLFO DE MÉXICO ...">{{old('Detalles_Generales.Proyecto', $Detalles_Generales['Proyecto'] ?? '')}}</textarea>
                            @error('Proyecto')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Orden_Trabajo]" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . .">{{old('Detalles_Generales.Orden_Trabajo', $Detalles_Generales['Orden_Trabajo'] ?? '')}}</textarea>
                            @error('Orden_Trabajo')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Folio:</label>
                            <input type="text" class="form-control  inputForm @error('Folio') is-invalid @enderror" name="Detalles_Generales[Folio]"  placeholder="Ejemplo:" value="{{old('Detalles_Generales.Folio', $Detalles_Generales['Folio'] ?? '')}}">
                            @error('Folio')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Partida:</label>
                            <input type="text" class="form-control  inputForm @error('Partida') is-invalid @enderror" name="Detalles_Generales[Partida]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Partida', $Detalles_Generales['Partida'] ?? '')}}">
                            @error('Partida')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Instalación:</label>
                            <input type="text" class="form-control  inputForm @error('Instalacion') is-invalid @enderror" name="Detalles_Generales[Instalacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Instalacion', $Detalles_Generales['Instalacion'] ?? '')}}">
                            @error('Instalacion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">No. Isométrico:</label>
                            <input type="text" class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[No_Isometrico]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.No_Isometrico', $Detalles_Generales['No_Isometrico'] ?? '')}}">
                            @error('No_Isometrico')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Nombre de la Pieza:</label>
                            <input type="text" class="form-control  inputForm @error('Nom_Pieza') is-invalid @enderror" name="Detalles_Generales[Nom_Pieza]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Nom_Pieza', $Detalles_Generales['Nom_Pieza'] ?? '')}}">
                            @error('Nom_Pieza')
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
                            <label class="col-form-label" for="inputSuccess">Accesorio:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Accesorio]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Accesorio', $Detalles_Generales['Accesorio'] ?? '')}}">
                            @error('Accesorio')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Trazabilidad:</label>
                            <input type="text" class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Trazabilidad]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Trazabilidad', $Detalles_Generales['Trazabilidad'] ?? '')}}">
                            @error('Trazabilidad')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Tuberia:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Tuberia]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Tuberia', $Detalles_Generales['Tuberia'] ?? '')}}">
                            @error('Tuberia')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Procedimiento:</label>
                            {{-- Edit conserva el procedimiento historico y su relacion asignada. --}}
                            <input type="text" class="form-control inputForm @error('Procedimiento') is-invalid @enderror" name="Detalles_Generales[Procedimiento]" value="{{ old('Detalles_Generales.Procedimiento', $Detalles_Generales['Procedimiento'] ?? '') }}" readonly>
                            <input type="hidden" name="Detalles_Generales[idProcedimiento]" value="{{ $Detalles_Generales['idProcedimiento'] ?? ($idProcedimiento ?? '') }}">
                            @error('Procedimiento')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Estructural:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Estructural]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Estructural', $Detalles_Generales['Estructural'] ?? '')}}">
                            @error('Estructural')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <!--***************************************** INICIO DATOS DE LA PRUEBA *****************************************-->
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DE LA PRUEBA</div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {{-- Conserva el método histórico y permite cambiarlo únicamente entre LEEB y UCI. --}}
                            <label class="col-form-label" for="metodoSelect">MÉTODO:</label>
                            <select class="form-select inputForm @error('Detalles_Generales.Metodo') is-invalid @enderror" id="metodoSelect" name="Detalles_Generales[Metodo]" required>
                                @php($metodoSeleccionado = old('Detalles_Generales.Metodo', $Detalles_Generales['Metodo'] ?? ''))
                                <option value="" disabled {{ $metodoSeleccionado ? '' : 'selected' }}>Seleccione un método</option>
                                <option value="LEEB" {{ $metodoSeleccionado === 'LEEB' ? 'selected' : '' }}>LEEB</option>
                                <option value="UCI" {{ $metodoSeleccionado === 'UCI' ? 'selected' : '' }}>UCI</option>
                            </select>
                            @error('Detalles_Generales.Metodo')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">TEMPERATURA DE LA PIEZA:</label>
                            <input type="text" class="form-control inputForm @error('Temp_Pieza') is-invalid @enderror" name="Detalles_Generales[Temp_Pieza]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Temp_Pieza', $Detalles_Generales['Temp_Pieza'] ?? '')}}">
                            @error('Temp_Pieza')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ESPESOR/CEDÚLA:</label>
                            <input type="text" class="form-control inputForm @error('Esp_Ced') is-invalid @enderror" name="Detalles_Generales[Esp_Ced]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Esp_Ced', $Detalles_Generales['Esp_Ced'] ?? '')}}">
                            @error('Esp_Ced')
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
                    <!--***************************************** INICIO DATOS DEL EQUIPO *****************************************-->
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DEL EQUIPO</div>

                    <div style="margin-bottom: 2px;"></div>

                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-info"></i> Importante</h5>
                        <p>Puedes Seleccionar un equipo del menu o escribir directamente</p>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">EQUIPO</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
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
                            <input type="text" class="form-control  inputForm" id="marcaInputE" name="Datos_Equipo[MARCA_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.MARCA_EQUIPO', $Datos_Equipo['MARCA_EQUIPO'] ?? '')}}">
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

                    <!--***************************************** VALORES DE DUREZA MEDIDOS *****************************************-->
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">
                        VALORES DE DUREZA MEDIDOS (ESCALA&nbsp;
                        <input type="text" class="form-control form-control-sm d-inline-block text-center"
                            style="width: 100px; height: 25px;" name="Datos_Equipo[ESCALA_DUREZA]"
                            value="{{ old('Datos_Equipo.ESCALA_DUREZA', $Datos_Equipo['ESCALA_DUREZA'] ?? '') }}" placeholder="Ej. HRC" aria-label="Escala de dureza">)
                    </div>

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
                                    <th colspan="5"></th>
                                    <th rowspan="1">Eliminar</th>
                                </tr>
                                <tr id="inputRow">
                                    <th><input type="text" class="form-control default-input" data-column="1" style="width: 100px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="2" style="width: 100px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="3" style="width: 100px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="4" style="width: 100px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="5" style="width: 100px;"></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <input type="hidden" name="titulos_data" id="titulos_hidden">

                    <div class="d-flex justify-content-between align-items-center w-100 mb-3">
                        <div>
                            <label for="numRows">Número de Filas:</label>
                            <select id="numRows" class="form-select">
                                @for ($i = 1; $i <= 500; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <button id="addBtn" type="button" class="btn btn-success custom-btn" onclick="agregarFilaIM0203()">Agregar Fila</button>
                        <button id="addTituloBtn" type="button" class="btn btn-success custom-btn" onclick="agregarTituloIM0203()">Agregar Título</button>
                        <button id="addLongBtn" type="button" class="btn btn-success custom-btn" onclick="agregarLongitudIM0203()">Agregar Longitud Inspeccionada</button>
                        <button id="preFillBtn" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                    </div>

                    <table class="table table-bordered">
                        <tr>
                            <td><strong>DUREZA PROMEDIO MEDIDO</strong></td>
                            <td><input type="text" class="form-control inputForm" name="Datos_Equipo[DUREZA_PROMEDIO_MEDIDO]" value="{{ old('Datos_Equipo.DUREZA_PROMEDIO_MEDIDO', $Datos_Equipo['DUREZA_PROMEDIO_MEDIDO'] ?? '') }}" readonly></td>
                        </tr>
                        <tr>
                            <td><strong>DUREZA DE ACUERDO A LA ESPECIFICACIÓN DE REFERENCIA</strong></td>
                            <td><input type="text" class="form-control inputForm" name="Datos_Equipo[DUREZA_ESPECIFICACION_REFERENCIA]" value="{{ old('Datos_Equipo.DUREZA_ESPECIFICACION_REFERENCIA', $Datos_Equipo['DUREZA_ESPECIFICACION_REFERENCIA'] ?? '') }}"></td>
                        </tr>
                    </table>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Observaciones:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{ old('Datos_Equipo.Observaciones', $Datos_Equipo['Observaciones'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!--***************************************** FIN DATOS *****************************************-->

                    <!-- Select para elegir el número de firmas -->
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded my-2">Número de Firmas:</div>
                        <div class="col-sm-15">
                            <div class="form-group">
                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                    <option value="1" @selected(($Firmas['numFirmas'] ?? 1) == 1)>1 Firma</option>
                                    <option value="2" @selected(($Firmas['numFirmas'] ?? 1) == 2)>2 Firmas</option>
                                    <option value="3" @selected(($Firmas['numFirmas'] ?? 1) == 3)>3 Firmas</option>
                                    <option value="4" @selected(($Firmas['numFirmas'] ?? 1) == 4)>4 Firmas</option>
                                </select>
                            </div>
                        </div>
                        
                            <!-- 1 UNA FIRMA-->
                            <div id="firmas1" class="col-12" style="display: {{ ($Firmas['numFirmas'] ?? 1) == 1 ? 'block' : 'none' }};">
                                <table class="table table-bordered table-striped dt-responsive tablas">
                                    <thead>
                                        <tr>
                                            <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[Realizo]" placeholder="Ejemplo: Realizó" value="{{ old('Realizo', $Firmas['Realizo'] ?? 'Realizó') }}"></th>
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

                                                        {{-- Guarda el ID del técnico seleccionado para el QR/CV. --}}
                                                        <input type="hidden" name="Firmas_Reportes1[ID_TECNICO]" id="IDTECNICO" value="{{ old('Firmas_Reportes1.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '') }}">
                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                        <input type="text" class="form-control  inputForm" name="Firmas_Reportes1[NOMBRE_TECNICO]" id="NOMBRE_TECNICO" value="{{ old('Firmas_Reportes1.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '') }}" readonly>
                                                    </div>
                                                </div>
                                            </td>
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
                        <div id="firmas2" class="col-12" style="display: {{ ($Firmas['numFirmas'] ?? 1) == 2 ? 'block' : 'none' }};">
                            <table class="table table-bordered table-striped dt-responsive tablas">
                                <thead>
                                    <tr>
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Realizo]" placeholder="Ejemplo: Realizó" value="{{ old('Realizo', $Firmas['Realizo'] ?? 'Realizó') }}"></th>
                                        <td style="width: 30px;"></td>
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="{{ old('Vobo1', $Firmas['Vobo1'] ?? 'Vo.Bo.') }}"></th>
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

                                                    {{-- Guarda el ID del técnico seleccionado para el QR/CV. --}}
                                                    <input type="hidden" name="Firmas_Reportes2[ID_TECNICO]" id="IDTECNICO2" value="{{ old('Firmas_Reportes2.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '') }}">
                                                    <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                    <input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_TECNICO]" id="NOMBRE_TECNICO2" value="{{ old('Firmas_Reportes2.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '') }}" readonly>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
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
                        <div id="firmas3" class="col-12" style="display: {{ ($Firmas['numFirmas'] ?? 1) == 3 ? 'block' : 'none' }};">
                            <table class="table table-bordered table-striped dt-responsive tablas">
                                <thead>
                                    <tr>

                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Realizo]" placeholder="Ejemplo: Realizó" value="{{ old('Realizo', $Firmas['Realizo'] ?? 'Realizó') }}"></th>
                                        <td style="width: 30px;"></td>
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="{{ old('Vobo1', $Firmas['Vobo1'] ?? 'Vo.Bo.') }}"></th>
                                        <td style="width: 30px;"></td>
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo2]" placeholder="Ejemplo: Vo.Bo." value="{{ old('Vobo2', $Firmas['Vobo2'] ?? 'Vo.Bo.') }}"></th>

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

                                                    {{-- Guarda el ID del técnico seleccionado para el QR/CV. --}}
                                                    <input type="hidden" name="Firmas_Reportes3[ID_TECNICO]" id="IDTECNICO3" value="{{ old('Firmas_Reportes3.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '') }}">
                                                    <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                    <input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_TECNICO]" id="NOMBRE_TECNICO3" value="{{ old('Firmas_Reportes3.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '') }}" readonly>
                                                </div>
                                            </div>
                                        </td>
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
                                    <tr>
                                        <td></td><td></td><td></td><td></td>
                                        <td><input type="text" class="form-control inputForm" name="Firmas_Reportes3[NUMERO_FICHA]" placeholder="NÚMERO DE FICHA" value="{{ old('Firmas_Reportes3.NUMERO_FICHA', $Firmas['NUMERO_FICHA'] ?? '') }}"></td>
                                    </tr>
                                </thead>                            
                            </table>
                        </div>

                        <!-- 4 CUATRO FIRMAS-->
                        <div id="firmas4" class="col-12" style="display: {{ ($Firmas['numFirmas'] ?? 1) == 4 ? 'block' : 'none' }};">
                            <table class="table table-bordered table-striped dt-responsive tablas">
                                <thead>
                                    <tr>

                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Realizo]" placeholder="Ejemplo: Realizó" value="{{ old('Realizo', $Firmas['Realizo'] ?? 'Realizó') }}"></th>
                                        <td style="width: 30px;"></td>
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="{{ old('Vobo1', $Firmas['Vobo1'] ?? 'Vo.Bo.') }}"></th>
                                        <td style="width: 30px;"></td>
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo2]" placeholder="Ejemplo: Vo.Bo." value="{{ old('Vobo2', $Firmas['Vobo2'] ?? 'Vo.Bo.') }}"></th>
                                        <td style="width: 30px;"></td>
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo3]" placeholder="Ejemplo: Vo.Bo." value="{{ old('Vobo3', $Firmas['Vobo3'] ?? 'Vo.Bo.') }}"></th>

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

                                                    {{-- Guarda el ID del técnico seleccionado para el QR/CV. --}}
                                                    <input type="hidden" name="Firmas_Reportes4[ID_TECNICO]" id="IDTECNICO4" value="{{ old('Firmas_Reportes4.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '') }}">
                                                    <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                    <input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_TECNICO]" id="NOMBRE_TECNICO4" value="{{ old('Firmas_Reportes4.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '') }}" readonly>
                                                </div>
                                            </div>
                                        </td>
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
                                    <tr>
                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                        <td><input type="text" class="form-control inputForm" name="Firmas_Reportes4[NUMERO_FICHA]" placeholder="NÚMERO DE FICHA" value="{{ old('Firmas_Reportes4.NUMERO_FICHA', $Firmas['NUMERO_FICHA'] ?? '') }}"></td>
                                    </tr>
                                    
                                </thead>                            
                            </table>
                        </div>
                        <p>

                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">FOTOS</div>

                        <p>

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
                                            <label for="replace_image_{{ $index }}">Imagen subida {{ $index + 1 }}:</label>

                                            <div class="image-preview mt-2">
                                                <img src="{{ asset($foto['ruta']) }}" class="img-fluid img-thumbnail" alt="Imagen Reporte">
                                            </div>

                                            <div class="form-check mt-2">
                                                <input type="checkbox"
                                                    class="form-check-input imagen-hoja-checkbox"
                                                    data-index="{{ $index }}"
                                                    id="imagenHoja{{ $index }}"
                                                    {{ !empty($foto['una_hoja']) && $foto['una_hoja'] == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="imagenHoja{{ $index }}">
                                                    Imagen en una hoja
                                                </label>
                                            </div>

                                            <input type="hidden" name="imagen_hoja[{{ $index }}]" id="imagenHojaValue{{ $index }}" value="{{ $foto['una_hoja'] ?? 0 }}">
                                            <input type="file" class="form-control image-input mt-2" id="replace_image_{{ $index }}" name="replace_images[{{ $index }}]" accept="image/*">
                                            <textarea class="form-control mt-2" name="comments[{{ $index }}]" placeholder="Comentario">{{ $foto['comentario'] ?? '' }}</textarea>
                                            <input type="hidden" name="images_base64[{{ $index }}]" id="replace_image_{{ $index }}-base64">
                                            <input type="hidden" name="existing_images[{{ $index }}]" value="{{ $foto['ruta'] }}">
                                            <input type="hidden" name="deleted_images[]" id="deleted_image_{{ $index }}" value="">
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
                                        <button type="button" id="rotateLeftBtn" class="btn btn-info">Rotar -90°</button>
                                        <button type="button" id="rotateRightBtn" class="btn btn-info">Rotar +90°</button>
                                        <button type="button" class="btn btn-primary" id="cropImageBtn">Recortar y Guardar</button>
                                        <button type="button" class="btn btn-success" id="saveWithoutCropBtn">Guardar Sin Recortar</button>
                                    </div>
                                </div>
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
                                <button type="submit" class="btn btn-info bg-primary">Actualizar</button>
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
<script src="{{ asset('js/Reportes_Create-For-02-06-IM.js') }}"></script>
<script src="{{ asset('js/promedio-dureza-02-b-03.js') }}?v={{ filemtime(public_path('js/promedio-dureza-02-b-03.js')) }}"></script>
<script src="{{ asset('js/Reportes_Edit.js') }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('FOR-PIMP-02_B_03');
    if (!form) return;

    const detallesGenerales = @json($Detalles_Generales ?? []);
    const datosEquipo = @json($Datos_Equipo ?? []);
    const firmas = @json($Firmas ?? []);

    function setValue(name, value) {
        if (value === undefined || value === null) return;

        const field = form.elements[name];
        if (!field) return;

        if (field instanceof RadioNodeList) {
            field.value = value;
            return;
        }

        field.value = value;
    }

    Object.keys(detallesGenerales).forEach(function (key) {
        setValue('Detalles_Generales[' + key + ']', detallesGenerales[key]);
    });

    Object.keys(datosEquipo).forEach(function (key) {
        setValue('Datos_Equipo[' + key + ']', datosEquipo[key]);
    });

    if (firmas.numFirmas) {
        setValue('numFirmas', firmas.numFirmas);
        const numFirmasSelect = document.getElementById('numFirmas');
        if (numFirmasSelect) {
            numFirmasSelect.dispatchEvent(new Event('change'));
        }
    }

    ['Firmas_Reportes1', 'Firmas_Reportes2', 'Firmas_Reportes3', 'Firmas_Reportes4'].forEach(function (grupo) {
        Object.keys(firmas).forEach(function (key) {
            setValue(grupo + '[' + key + ']', firmas[key]);
        });
    });
});

 /*Selects */
    /* Selects de equipos */
$(document).ready(function() {

    function configurarSelectEquipo(selectId, marcaId, modeloId, nsId, idEquipoId, localStorageName) {
        function actualizarInputs() {
            var selectedOption = $('#' + selectId).find('option:selected');

            var marca = selectedOption.data('marca') || '';
            var modelo = selectedOption.data('modelo') || '';
            var ns = selectedOption.data('ns') || '';

            $('#' + marcaId).val(marca);
            $('#' + modeloId).val(modelo);
            $('#' + nsId).val(ns);
            $('#' + idEquipoId).val($('#' + selectId).val() || '');
        }

        const formId = document.querySelector("form").id;
        const selectedOptionLocal = localStorage.getItem(formId + '_' + localStorageName);

        if (selectedOptionLocal != null) {
            $('#' + selectId).val(selectedOptionLocal);
            actualizarInputs();
        }

        $('#' + selectId).on('change', function() {
            actualizarInputs();

            const formId = document.querySelector("form").id;
            localStorage.setItem(formId + '_' + localStorageName, $(this).val());
        });
    }

    configurarSelectEquipo(
        'equiposSelect',
        'marcaInputE',
        'modeloInputE',
        'nsInputE',
        'IDInputE',
        'equipos'
    );

});

    /*FOR-PIMP-02_B_03*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-PIMP-02_B_03');
        if (!form) return;

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('FOR-PIMP-02_B_03_Form_' + el.name, el.value);
            //});
        //});

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-PIMP-02_B_03_Form_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-PIMP-02_B_03_Form_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-PIMP-02_B_03_Form_' + el.name);
                //localStorage.clear();
            });
        });
    });

    function escaparHtmlIM0203(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function guardarTablaIM0203() {
        const titulos = [];
        $('#dynamicTable tbody .titulo-row').each(function() {
            const id = $(this).data('titulo');
            const text = $(this).find('.titulo-text').val() || '';
            if (id) {
                titulos.push({ id: id, text: text });
            }
        });

        $('#titulos_hidden').val(JSON.stringify(titulos));
    }

    function renumerarTablaIM0203() {
        let index = 0;
        $('#dynamicTable tbody tr').not('.titulo-row, .long-row').each(function() {
            index++;
            $(this).find('td:first').html(index + ' <input type="hidden" value="' + index + '">');
        });
    }

    function verificarYAgregarLongitud() {
        let contadorBloque = 0;

        $('#dynamicTable tbody tr').each(function () {
            const $row = $(this);

            if ($row.hasClass('long-row') || $row.hasClass('titulo-row')) {
                contadorBloque = 0;
                return;
            }

            contadorBloque++;

            if (contadorBloque === 10 && !$row.next().hasClass('long-row')) {
                const lastTitle = $row.data('titulo') || 'sin_titulo';
                $row.after(
                    '<tr class="long-row" data-titulo="' + lastTitle + '">' +
                    '<td colspan="6"><div class="d-flex align-items-center">' +
                    '<span class="mr-2">Longitud Inspeccionada</span>' +
                    '<input type="text" class="form-control long-text" name="Long_Inspecc[' + lastTitle + '][]">' +
                    '</div></td>' +
                    '<td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times"></i></button></td>' +
                    '</tr>'
                );
                contadorBloque = 0;
            }
        });
    }

    function agregarFilaIM0203() {
        const amount = parseInt($('#numRows').val(), 10) || 1;
        const lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';
        const fields = [
            ['valor_dureza1', 'Valor Dureza 1'],
            ['valor_dureza2', 'Valor Dureza 2'],
            ['valor_dureza3', 'Valor Dureza 3'],
            ['valor_dureza4', 'Valor Dureza 4'],
            ['valor_dureza5', 'Valor Dureza 5']
        ];
        let currentRows = $('#dynamicTable tbody tr').not('.titulo-row, .long-row').length;

        for (let i = 0; i < amount; i++) {
            currentRows++;
            const inputsHtml = fields.map(function(field) {
                return '<td><input type="text" class="form-control" name="' + field[0] + '[' + lastTitle + '][]" placeholder="' + field[1] + '"></td>';
            }).join('');

            $('#dynamicTable tbody').append(
                '<tr data-titulo="' + lastTitle + '">' +
                '<td>' + currentRows + ' <input type="hidden" value="' + currentRows + '"></td>' +
                inputsHtml +
                '<td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>' +
                '</tr>'
            );
        }

        verificarYAgregarLongitud();
        renumerarTablaIM0203();
        guardarTablaIM0203();
    }

    function agregarTituloIM0203() {
        verificarYAgregarLongitud();
        const titleId = 'titulo_' + ($('.titulo-row').length + 1) + '_' + Date.now();

        $('#dynamicTable tbody').append(
            '<tr class="titulo-row" data-titulo="' + titleId + '">' +
            '<td colspan="6"><div class="d-flex align-items-center">' +
            '<input type="text" class="form-control w-90 titulo-text" name="titulos_text[' + titleId + ']" placeholder="Ingrese titulo Ejemplo: SKID I PIEZA NO-3 (DETALLE DE OREJA DE IZAJE 1/4)">' +
            '<input type="hidden" class="titulo-id" name="titulos_ids[]" value="' + titleId + '">' +
            '</div></td>' +
            '<td><button type="button" class="btn btn-danger btnEliminarTitulo"><i class="fa fa-times" aria-hidden="true"></i></button></td>' +
            '</tr>'
        );

        guardarTablaIM0203();
    }

    function agregarLongitudIM0203() {
        verificarYAgregarLongitud();
        const lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

        $('#dynamicTable tbody').append(
            '<tr class="long-row" data-titulo="' + lastTitle + '">' +
            '<td colspan="6"><div class="d-flex align-items-center">' +
            '<span class="mr-2">Longitud Inspeccionada</span>' +
            '<input type="text" class="form-control w-90 long-text" name="Long_Inspecc[' + lastTitle + '][]" placeholder="Ingrese Longitud Inspeccionada...">' +
            '</div></td>' +
            '<td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>' +
            '</tr>'
        );

        guardarTablaIM0203();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const datosTabla = @json($Grupo_Juntas_Re ?? []);
        const tbody = $('#dynamicTable tbody');
        if (!tbody.length || !Array.isArray(datosTabla)) return;

        const items = [];
        datosTabla.forEach(function(bloque) {
            if (bloque && bloque.tipo) {
                items.push(bloque);
            } else if (Array.isArray(bloque)) {
                bloque.forEach(function(item) {
                    if (item && item.tipo) items.push(item);
                });
            }
        });

        let currentTitle = 'sin_titulo';
        let rowIndex = 0;
        const titulos = [];

        items.forEach(function(item) {
            const grupo = item.grupo || currentTitle || 'sin_titulo';

            if (item.tipo === 'titulo') {
                currentTitle = grupo;
                const text = item.texto || item.text || '';
                titulos.push({ id: grupo, text: text });
                tbody.append(
                    '<tr class="titulo-row" data-titulo="' + escaparHtmlIM0203(grupo) + '">' +
                    '<td colspan="6"><div class="d-flex align-items-center">' +
                    '<input type="text" class="form-control w-90 titulo-text" name="titulos_text[' + escaparHtmlIM0203(grupo) + ']" value="' + escaparHtmlIM0203(text) + '">' +
                    '<input type="hidden" class="titulo-id" name="titulos_ids[]" value="' + escaparHtmlIM0203(grupo) + '">' +
                    '</div></td>' +
                    '<td><button type="button" class="btn btn-danger btnEliminarTitulo"><i class="fa fa-times" aria-hidden="true"></i></button></td>' +
                    '</tr>'
                );
                return;
            }

            if (item.tipo === 'fila') {
                const data = item.data || {};
                rowIndex++;
                tbody.append(
                    '<tr data-titulo="' + escaparHtmlIM0203(grupo) + '">' +
                    '<td>' + rowIndex + ' <input type="hidden" value="' + rowIndex + '"></td>' +
                    '<td><input type="text" class="form-control" name="valor_dureza1[' + escaparHtmlIM0203(grupo) + '][]" value="' + escaparHtmlIM0203(data.valor_dureza1) + '" placeholder="Valor Dureza 1"></td>' +
                    '<td><input type="text" class="form-control" name="valor_dureza2[' + escaparHtmlIM0203(grupo) + '][]" value="' + escaparHtmlIM0203(data.valor_dureza2) + '" placeholder="Valor Dureza 2"></td>' +
                    '<td><input type="text" class="form-control" name="valor_dureza3[' + escaparHtmlIM0203(grupo) + '][]" value="' + escaparHtmlIM0203(data.valor_dureza3) + '" placeholder="Valor Dureza 3"></td>' +
                    '<td><input type="text" class="form-control" name="valor_dureza4[' + escaparHtmlIM0203(grupo) + '][]" value="' + escaparHtmlIM0203(data.valor_dureza4) + '" placeholder="Valor Dureza 4"></td>' +
                    '<td><input type="text" class="form-control" name="valor_dureza5[' + escaparHtmlIM0203(grupo) + '][]" value="' + escaparHtmlIM0203(data.valor_dureza5) + '" placeholder="Valor Dureza 5"></td>' +
                    '<td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>' +
                    '</tr>'
                );
                return;
            }

            if (item.tipo === 'longitud') {
                tbody.append(
                    '<tr class="long-row" data-titulo="' + escaparHtmlIM0203(grupo) + '">' +
                    '<td colspan="6"><div class="d-flex align-items-center">' +
                    '<span class="mr-2">Longitud Inspeccionada</span>' +
                    '<input type="text" class="form-control w-90 long-text" name="Long_Inspecc[' + escaparHtmlIM0203(grupo) + '][]" value="' + escaparHtmlIM0203(item.valor) + '" placeholder="Ingrese Longitud Inspeccionada...">' +
                    '</div></td>' +
                    '<td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>' +
                    '</tr>'
                );
            }
        });

        $('#titulos_hidden').val(JSON.stringify(titulos));

        $(document).on('click', '#dynamicTable .btnEliminar, #dynamicTable .btnEliminarTitulo', function() {
            $(this).closest('tr').remove();
            renumerarTablaIM0203();
            guardarTablaIM0203();
        });

        $(document).on('input', '#dynamicTable .titulo-text', guardarTablaIM0203);
    });

</script>
@endsection
