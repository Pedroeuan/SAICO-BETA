@extends('adminlte::page')

@section('title', 'FOR-PIMP-06_B_01')

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
            <form id="FOR-PIMP-06_B_01" action="{{route('Reportes_FOR_PIMP_06_B_01.store')}}" method="post" enctype="multipart/form-data">
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
                            <label class="col-form-label" for="inputSuccess">No. Reporte:</label>
                            <input type="text" class="form-control  inputForm @error('No_Reporte') is-invalid @enderror" name="Detalles_Generales[No_Reporte]"  placeholder="Ejemplo: 077-8DUCTOS-24" value="{{old('Detalles_Generales.No_Reporte')}}">
                            @error('No_Reporte')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">
                                ¿Cliente existente?
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
                            <select id="campoClienteSelect"
                                    class="form-select"
                                    name="ClienteSelect">
                                <option value="" selected disabled>Seleccione un Cliente</option>
                                @foreach($Clientes as $Cliente)
                                    <option value="{{ $Cliente->Cliente }}">
                                        {{ $Cliente->Cliente }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- INPUT cuando es NO -->
                            <input type="text"
                                id="campoClienteInput"
                                class="form-control inputForm mt-2"
                                name="ClienteInput"
                                placeholder="Ingrese nombre del cliente"
                                style="display:none;">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">
                                ¿Contrato existente?
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
                            <input type="text"
                                id="campoContrato"
                                class="form-control inputForm"
                                name="Detalles_Generales[Contrato]"
                                placeholder="Ejemplo: 640853841"
                                value="{{ old('Detalles_Generales.Contrato') }}"
                                required>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Contrato:</label>
                            <input type="text" class="form-control  inputForm @error('Proyecto') is-invalid @enderror" name="Detalles_Generales[Proyecto]"  placeholder="Ejemplo: Mexicali" value="{{old('Detalles_Generales.Proyecto')}}">
                            @error('Proyecto')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="ordenTrabajo0601Create">Orden de Trabajo:</label>
                            <textarea class="form-control  is-waning" id="ordenTrabajo0601Create" name="Detalles_Generales[Orden_Trabajo]" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . .">{{old('Detalles_Generales.Orden_Trabajo')}}</textarea>
                            @error('Orden_Trabajo')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Folio:</label>
                            <input type="text" class="form-control  inputForm @error('Folio') is-invalid @enderror" name="Detalles_Generales[Folio]"  placeholder="Ejemplo:" value="{{old('Detalles_Generales.Folio')}}">
                            @error('Folio')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Partida:</label>
                            <input type="text" class="form-control  inputForm @error('Partida') is-invalid @enderror" name="Detalles_Generales[Partida]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Partida')}}">
                            @error('Partida')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Instalación:</label>
                            <input type="text" class="form-control  inputForm @error('Instalacion') is-invalid @enderror" name="Detalles_Generales[Instalacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Instalacion')}}">
                            @error('Instalacion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="noIsometrico0601Create">No. Isométrico:</label>
                            <input type="text" class="form-control inputForm is-waning" id="noIsometrico0601Create" name="Detalles_Generales[No_Isometrico]" placeholder="Ejemplo:" value="{{ old('Detalles_Generales.No_Isometrico') }}">
                            @error('No_Isometrico')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Nombre de la Pieza:</label>
                            <input type="text" class="form-control  inputForm @error('Nom_Pieza') is-invalid @enderror" name="Detalles_Generales[Nom_Pieza]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Nom_Pieza')}}">
                            @error('Nom_Pieza')
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

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Procedimiento:</label>
                            {{-- Igual que PINS, el procedimiento pertenece al formato y no se captura manualmente. --}}
                            <input type="text" class="form-control inputForm @error('Procedimiento') is-invalid @enderror" name="Detalles_Generales[Procedimiento]" value="{{ old('Detalles_Generales.Procedimiento', $Procedimiento->Nombre ?? '') }}" readonly>
                            <input type="hidden" name="Detalles_Generales[idProcedimiento]" value="{{ $Procedimiento->idProcedimiento ?? '' }}">
                            @error('Procedimiento')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Criterio de Evaluación:</label>
                            <input type="text" class="form-control  inputForm @error('Criterio_Evaluacion') is-invalid @enderror" name="Detalles_Generales[Criterio_Evaluacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Criterio_Evaluacion')}}">
                            @error('Criterio_Evaluacion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="trazabilidad0601Create">Trazabilidad:</label>
                            <input type="text" class="form-control inputForm is-waning" id="trazabilidad0601Create" name="Detalles_Generales[Trazabilidad]" placeholder="Ejemplo:" value="{{ old('Detalles_Generales.Trazabilidad') }}">
                            @error('Trazabilidad')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="noJunta0601Create">No. Junta</label>
                            <input type="text" class="form-control inputForm is-waning" id="noJunta0601Create" name="Detalles_Generales[No_Junta]" placeholder="Ejemplo:" value="{{ old('Detalles_Generales.No_Junta') }}">
                            @error('No_Junta')
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

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <select class="form-select inputForm" name="equipos" id="equiposSelect">
                            <option value="" selected disabled>Seleccione un Equipo</option> <!-- Opción por defecto -->
                                @foreach($idsGeneral_EyCs_Equipos as $equipo)
                                    <option value="{{ $equipo->idGeneral_EyC }}"
                                            data-marca="{{ $equipo->Marca }}"
                                            data-modelo="{{ $equipo->Modelo }}"
                                            data-ns="{{ $equipo->Serie }}"
                                            data-metodo="{{ data_get($equipo, 'Metodo_Medicion', '') }}">
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
                    <!--***************************************** FIN DATOS *****************************************-->

                    @php
                        $nombresNormasIM = collect($NormasIM ?? [])->pluck('Nombre_Espe')->filter()->unique()->sort()->values();
                    @endphp
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded my-2">
                        NORMA Y RESULTADOS DEL ANALISIS QUIMICO
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="normaIMNombre">Norma/Especificacion:</label>
                            <select class="form-control" id="normaIMNombre">
                                <option value="">Seleccione una norma</option>
                                @foreach($nombresNormasIM as $nombreNorma)
                                    <option value="{{ $nombreNorma }}">{{ $nombreNorma }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="normaIMRegistro">Tabla/Variable/Subtitulo:</label>
                            <select class="form-control @error('Norma_IM.idnormas_im') is-invalid @enderror" id="normaIMRegistro" name="Norma_IM[idnormas_im]" disabled>
                                <option value="">Primero seleccione una norma</option>
                            </select>
                            @error('Norma_IM.idnormas_im')
                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>
                    {{-- Carga XRF del 06_B_01: la selección de norma permanece en este formulario. --}}
                    <div class="col-12">
                        <div class="form-group border rounded p-3 bg-light">
                            <label for="analisisPdfXrf"><strong>PDF del equipo XRF (1 hoja por archivo)</strong></label>
                            <input type="file" class="form-control-file @error('Analisis_PDF.*') is-invalid @enderror"
                                id="analisisPdfXrf" name="Analisis_PDF[]" accept="application/pdf,.pdf" multiple>
                            <small class="form-text text-muted">Seleccione los PDF en orden. Los primeros tres se asignan a los disparos 1, 2 y 3; cada uno genera la tabla y la gráfica. El promedio usa todos los PDF seleccionados.</small>
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-primary" id="extraerAnalisisPdfBtn">Extraer datos y calcular promedio</button>
                                <span class="ml-2 text-muted d-none" id="estadoAnalisisPdf"></span>
                            </div>
                            @error('Analisis_PDF.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-12 d-none" id="vistaAnalisisPdf">
                        <div class="alert alert-warning d-none" id="alertasAnalisisPdf"></div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="tablaAnalisisPdf">
                                <thead></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div id="recortesXrfDisparos" class="mt-3"></div>
                    </div>
                    <div class="col-12 d-none" id="normaIMResultadosContainer">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped w-100" id="tablaNormaIM">
                                <thead><tr>
                                    <th>Elemento Quimico / Chemical Element</th>
                                    <th>Promedio de la Pieza Analizada / Average</th>
                                    <th>Composicion Quimica Teorica / Theoretical Composition</th>
                                </tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="alert alert-light border d-none" id="normaIMObservacionesContainer">
                            <strong>Observaciones de la norma:</strong>
                            <div id="normaIMObservaciones" style="white-space: pre-line;"></div>
                        </div>
                    </div>

                    <!-- Select para elegir el número de firmas -->
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded my-2">Número de Firmas:</div>
                        <div class="col-sm-15">
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
                                                        <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                        <select class="form-select inputForm" id="tecnicosSelect" name="Firmas_Reportes1[ID_TECNICO]">
                                                            <option value="" selected disabled>Seleccione un Técnico</option>

                                                            @foreach($Tecnicos as $Tecnico)
                                                                <option value="{{ $Tecnico->id }}"
                                                                        data-name="{{ $Tecnico->name }}">
                                                                    {{ $Tecnico->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <input type="hidden" name="Firmas_Reportes1[NOMBRE_TECNICO]" id="NOMBRE_TECNICO" value="{{ old('Firmas_Reportes1.NOMBRE_TECNICO') }}">
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
                                                    <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                    <select class="form-select inputForm" id="tecnicosSelect2" name="Firmas_Reportes2[ID_TECNICO]">
                                                        <option value="" selected disabled>Seleccione un Técnico</option>

                                                        @foreach($Tecnicos as $Tecnico)
                                                            <option value="{{ $Tecnico->id }}"
                                                                    data-name="{{ $Tecnico->name }}">
                                                                {{ $Tecnico->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <input type="hidden" name="Firmas_Reportes2[NOMBRE_TECNICO]" id="NOMBRE_TECNICO2" value="{{ old('Firmas_Reportes2.NOMBRE_TECNICO') }}">
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
                                                    <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                    <select class="form-select inputForm" id="tecnicosSelect3" name="Firmas_Reportes3[ID_TECNICO]">
                                                        <option value="" selected disabled>Seleccione un Técnico</option>

                                                        @foreach($Tecnicos as $Tecnico)
                                                            <option value="{{ $Tecnico->id }}"
                                                                    data-name="{{ $Tecnico->name }}">
                                                                {{ $Tecnico->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <input type="hidden" name="Firmas_Reportes3[NOMBRE_TECNICO]" id="NOMBRE_TECNICO3" value="{{ old('Firmas_Reportes3.NOMBRE_TECNICO') }}">
                                                </div>
                                            </div>
                                        </td>
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
                                    <tr>
                                        <td></td><td></td><td></td><td></td>
                                        <td><input type="text" class="form-control inputForm" name="Firmas_Reportes3[NUMERO_FICHA]" placeholder="NÚMERO DE FICHA" value="{{ old('Firmas_Reportes3.NUMERO_FICHA') }}"></td>
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
                                                    <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                    <select class="form-select inputForm" id="tecnicosSelect4" name="Firmas_Reportes4[ID_TECNICO]">
                                                        <option value="" selected disabled>Seleccione un Técnico</option>

                                                        @foreach($Tecnicos as $Tecnico)
                                                            <option value="{{ $Tecnico->id }}"
                                                                    data-name="{{ $Tecnico->name }}">
                                                                {{ $Tecnico->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <input type="hidden" name="Firmas_Reportes4[NOMBRE_TECNICO]" id="NOMBRE_TECNICO4" value="{{ old('Firmas_Reportes4.NOMBRE_TECNICO') }}">
                                                </div>
                                            </div>
                                        </td>
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
                                    <tr>
                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                        <td><input type="text" class="form-control inputForm" name="Firmas_Reportes4[NUMERO_FICHA]" placeholder="NÚMERO DE FICHA" value="{{ old('Firmas_Reportes4.NUMERO_FICHA') }}"></td>
                                    </tr>
                                    
                                </thead>                            
                            </table>
                        </div>
                        <p>

                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">FOTOS Y DISPAROS</div>

                        <div class="alert alert-info mt-2 mb-2">
                            Las fotos adicionales permiten elegir número de hoja y posición. Si una imagen pertenece a un disparo, márcala y selecciona cuál; cada disparo debe tener exactamente dos imágenes.
                        </div>
                        @error('numero_disparo')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        {{-- Activa el selector, la vista previa y la descripción del tamaño de grano en una tarjeta vacía. --}}
                        @include('Reportes.IM.partials.patron-grano-reporte', ['patronGrano' => []])

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

                        <div id="imageFieldsContainer" class="row" data-layout-fotos-manual="1">
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
<script src="{{ asset('js/Reportes_Create-For-02-06-IM.js') }}?v={{ filemtime(public_path('js/Reportes_Create-For-02-06-IM.js')) }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="{{ asset('js/Reportes_Fotos_Posicionables_02_B_04.js') }}?v={{ filemtime(public_path('js/Reportes_Fotos_Posicionables_02_B_04.js')) }}"></script>
{{-- El patrón comparte la tarjeta, el selector de hoja, la posición y el cuadro de texto de las fotos. --}}
<script src="{{ asset('js/patron-grano-reporte.js') }}?v={{ filemtime(public_path('js/patron-grano-reporte.js')) }}"></script>
<script>

    function verificarYAgregarLongitud() {
        let contadorBloque = 0;

        $('#dynamicTable tbody tr').each(function () {
            const $row = $(this);

            if ($row.hasClass('long-row')) {
                contadorBloque = 0;
                return;
            }

            if ($row.hasClass('titulo-row')) {
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

    function configurarSelectEquipo(selectId, marcaId, modeloId, nsId, idEquipoId, localStorageName, metodoId = null) {
        function actualizarInputs() {
            const selectedOption = $('#' + selectId).find('option:selected');

            $('#' + marcaId).val(selectedOption.data('marca') || '');
            $('#' + modeloId).val(selectedOption.data('modelo') || '');
            $('#' + nsId).val(selectedOption.data('ns') || '');
            $('#' + idEquipoId).val($('#' + selectId).val() || '');

            if (metodoId) {
                $('#' + metodoId).val(selectedOption.data('metodo') || '');
            }
        }

        const form = document.querySelector('form');
        const formId = form ? form.id : 'FOR-PIMP-02_B_03';
        const selectedOptionLocal = localStorage.getItem(formId + '_' + localStorageName);

        if (selectedOptionLocal != null) {
            $('#' + selectId).val(selectedOptionLocal);
            actualizarInputs();
        }

        $('#' + selectId).on('change', function() {
            actualizarInputs();
            localStorage.setItem(formId + '_' + localStorageName, $(this).val());
        });
    }

    $(document).ready(function() {
        configurarSelectEquipo('equiposSelect', 'marcaInputE', 'modeloInputE', 'nsInputE', 'IDInputE', 'equipos', 'metodoInputE');
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

    function guardarTablaIM0203() {
        if (typeof saveDataIM0203 === 'function') saveDataIM0203();
    }

    function renumerarTablaIM0203() {
        let index = 0;
        $('#dynamicTable tbody tr').not('.titulo-row, .long-row').each(function() {
            index++;
            $(this).find('td:first').html(index + ' <input type="hidden" value="' + index + '">');
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

        if (typeof verificarYAgregarLongitud === 'function') verificarYAgregarLongitud();
        renumerarTablaIM0203();
        guardarTablaIM0203();
    }

    function agregarTituloIM0203() {
        if (typeof verificarYAgregarLongitud === 'function') verificarYAgregarLongitud();
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
        if (typeof verificarYAgregarLongitud === 'function') verificarYAgregarLongitud();
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

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const catalogo = @json($NormasIM ?? []);
    const nombreSelect = document.getElementById('normaIMNombre');
    const registroSelect = document.getElementById('normaIMRegistro');
    const contenedor = document.getElementById('normaIMResultadosContainer');
    const tbody = document.querySelector('#tablaNormaIM tbody');
    const observacionesBox = document.getElementById('normaIMObservacionesContainer');
    const observaciones = document.getElementById('normaIMObservaciones');
    const idInicial = @json(old('Norma_IM.idnormas_im'));
    const promediosIniciales = @json(old('Norma_IM.Promedio', []));
    let nombreActual = '';
    let registroActual = '';

    if (!nombreSelect || !registroSelect || !tbody) return;

    function tienePromedios() {
        return Array.from(tbody.querySelectorAll('input')).some(input => input.value.trim() !== '');
    }

    function limpiarResultados() {
        tbody.innerHTML = '';
        contenedor.classList.add('d-none');
        observaciones.textContent = '';
        observacionesBox.classList.add('d-none');
    }

    function cargarTablas(nombre) {
        registroSelect.innerHTML = '<option value="">Seleccione una tabla o variable</option>';
        const opciones = catalogo.filter(item => item.Nombre_Espe === nombre);
        opciones.forEach(item => {
            const option = document.createElement('option');
            option.value = item.idnormas_im;
            option.textContent = item.Variable || 'Sin variable/subtitulo';
            registroSelect.appendChild(option);
        });
        registroSelect.disabled = opciones.length === 0;
        return opciones;
    }

    function mostrarNorma(norma, promedios) {
        tbody.innerHTML = '';
        (norma.Tabla || []).forEach((fila, index) => {
            const tr = document.createElement('tr');
            const elemento = document.createElement('td');
            const promedio = document.createElement('td');
            const composicion = document.createElement('td');
            const input = document.createElement('input');

            elemento.textContent = fila.Elemento || '';
            composicion.textContent = fila.Composicion || '';
            input.type = 'text';
            input.className = 'form-control text-center';
            input.name = 'Norma_IM[Promedio][' + index + ']';
            input.dataset.elemento = fila.Elemento || '';
            input.value = promedios[index] ?? fila.Promedio ?? '';
            input.placeholder = 'Capture el promedio';
            promedio.appendChild(input);
            tr.append(elemento, promedio, composicion);
            tbody.appendChild(tr);
        });

        observaciones.textContent = norma.Observaciones || '';
        observacionesBox.classList.toggle('d-none', !norma.Observaciones);
        contenedor.classList.remove('d-none');
    }

    nombreSelect.addEventListener('change', function () {
        if (nombreActual && this.value !== nombreActual && tienePromedios()
            && !window.confirm('Al cambiar la norma se eliminaran los promedios capturados. Desea continuar?')) {
            this.value = nombreActual;
            return;
        }

        nombreActual = this.value;
        registroActual = '';
        limpiarResultados();
        const opciones = cargarTablas(this.value);
        if (opciones.length === 1) {
            registroSelect.value = opciones[0].idnormas_im;
            registroActual = String(opciones[0].idnormas_im);
            mostrarNorma(opciones[0], []);
        }
    });

    registroSelect.addEventListener('change', function () {
        if (registroActual && this.value !== registroActual && tienePromedios()
            && !window.confirm('Al cambiar la tabla se eliminaran los promedios capturados. Desea continuar?')) {
            this.value = registroActual;
            return;
        }

        registroActual = this.value;
        const norma = catalogo.find(item => String(item.idnormas_im) === String(this.value));
        norma ? mostrarNorma(norma, []) : limpiarResultados();
    });

    document.addEventListener('norma-im:creada', function (event) {
        const norma = event.detail;
        if (!norma?.idnormas_im) return;

        if (!catalogo.some(item => String(item.idnormas_im) === String(norma.idnormas_im))) {
            catalogo.push(norma);
        }
        if (!Array.from(nombreSelect.options).some(option => option.value === norma.Nombre_Espe)) {
            nombreSelect.add(new Option(norma.Nombre_Espe, norma.Nombre_Espe));
        }

        nombreSelect.value = norma.Nombre_Espe;
        nombreActual = norma.Nombre_Espe;
        cargarTablas(norma.Nombre_Espe);
        registroSelect.value = norma.idnormas_im;
        registroActual = String(norma.idnormas_im);
        mostrarNorma(norma, []);
    });

    if (idInicial) {
        const norma = catalogo.find(item => String(item.idnormas_im) === String(idInicial));
        if (norma) {
            nombreSelect.value = norma.Nombre_Espe;
            nombreActual = norma.Nombre_Espe;
            cargarTablas(norma.Nombre_Espe);
            registroSelect.value = norma.idnormas_im;
            registroActual = String(norma.idnormas_im);
            mostrarNorma(norma, promediosIniciales || []);
        }
    }
});
// Sincroniza los selects de técnicos con el campo oculto que se guarda para el reporte, siguiendo el patrón de PINS.
    document.addEventListener('DOMContentLoaded', function () {
        for (let indice = 1; indice <= 4; indice++) {
            const sufijo = indice === 1 ? '' : indice;
            const select = document.getElementById('tecnicosSelect' + sufijo);
            const nombre = document.getElementById('NOMBRE_TECNICO' + sufijo);

            if (!select || !nombre) continue;

            select.addEventListener('change', function () {
                const opcion = select.options[select.selectedIndex];
                nombre.value = opcion ? (opcion.getAttribute('data-name') || '') : '';
            });
        }
    });
</script>

{{-- Comparte solamente el comportamiento JS; la ruta y los campos pertenecen al 06_B_01. --}}
@include('Reportes.IM.partials.script-compartido-pdf-xrf')

@endsection
