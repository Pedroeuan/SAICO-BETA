@extends('adminlte::page')

@section('title', 'FOR-PIMP-05_B_01')

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
            <form id="FOR-PIMP-05_B_01" action="{{route('Reportes_FOR_PIMP_05_B_01.update', $id)}}" method="post" enctype="multipart/form-data"
                data-validation-errors="{{ $errors->any() ? 1 : 0 }}">
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
                            <input type="text" class="form-control  inputForm @error('Nombre_Pieza') is-invalid @enderror" name="Detalles_Generales[Nombre_Pieza]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Nombre_Pieza', $Detalles_Generales['Nombre_Pieza'] ?? '')}}">
                            @error('Nombre_Pieza')
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
                            <label class="col-form-label" for="inputSuccess">Trazabilidad:</label>
                            <input type="text" class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Trazabilidad]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Trazabilidad', $Detalles_Generales['Trazabilidad'] ?? '')}}">
                            @error('Trazabilidad')
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
                            <label class="col-form-label" for="inputSuccess">Criterio de Evaluación:</label>
                            <input type="text" class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Criterio_Evaluacion]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Criterio_Evaluacion', $Detalles_Generales['Criterio_Evaluacion'] ?? '')}}">
                            @error('Criterio_Evaluacion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Accesorio:</label>
                            <input type="text" class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Accesorio]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Accesorio', $Detalles_Generales['Accesorio'] ?? '')}}">
                            @error('Accesorio')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Tubería:</label>
                            <input type="text" class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Tuberia]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Tuberia', $Detalles_Generales['Tuberia'] ?? '')}}">
                            @error('Tuberia')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Estructural:</label>
                            <input type="text" class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Estructural]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Estructural', $Detalles_Generales['Estructural'] ?? '')}}">
                            @error('Estructural')
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

                    <!--***************************************** COMPOSICIÓN QUÍMICA  DE LA PIEZA *****************************************-->
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded my-2">COMPOSICIÓN QUÍMICA  DE LA PIEZA:</div>
                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->

                    {{-- Formulario de norma y análisis XRF propio del Edit 04_03. --}}
                    @php
                        $normaHistoricaXrf = $Detalles_Generales['Norma_IM'] ?? null;
                        $nombresNormasIM = collect($NormasIM ?? [])->pluck('Nombre_Espe')->filter()->unique()->sort()->values();
                    @endphp
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded my-2">
                        NORMA Y RESULTADOS DEL ANÁLISIS QUÍMICO
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="normaIMNombre">Norma/Especificación:</label>
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
                            <label for="normaIMRegistro">Tabla/Variable/Subtítulo:</label>
                            <select class="form-control @error('Norma_IM.idnormas_im') is-invalid @enderror"
                                id="normaIMRegistro" name="Norma_IM[idnormas_im]" disabled>
                                <option value="">Primero seleccione una norma</option>
                            </select>
                            @error('Norma_IM.idnormas_im')
                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group border rounded p-3 bg-light">
                            <label for="analisisPdfXrf"><strong>PDF del equipo XRF</strong></label>
                            <input type="file" class="form-control-file @error('Analisis_PDF') is-invalid @enderror"
                                id="analisisPdfXrf" name="Analisis_PDF" accept="application/pdf,.pdf">
                            <small class="form-text text-muted">
                                Suba un PDF; se detectarán sus disparos y podrá seleccionar hasta tres. Un PDF nuevo reemplaza el análisis anterior.
                            </small>
                            @php($columnasXrfGuardadas = old('XRF_Columnas', $normaHistoricaXrf['Columnas_Seleccionadas'] ?? []))
                            <div class="d-flex flex-wrap mt-2">
                                @foreach(range(1, 20) as $columnaXrf)
                                    <label class="form-check mr-4 {{ in_array($columnaXrf, array_map('intval', $columnasXrfGuardadas)) ? '' : 'd-none' }}"><input class="form-check-input columna-xrf" type="checkbox"
                                        name="XRF_Columnas[]" value="{{ $columnaXrf }}" @checked(in_array($columnaXrf, array_map('intval', $columnasXrfGuardadas)))> {{ $columnaXrf }}</label>
                                @endforeach
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-primary" id="extraerAnalisisPdfBtn">Extraer datos y calcular promedio</button>
                                <span class="ml-2 text-muted d-none" id="estadoAnalisisPdf"></span>
                            </div>
                            @error('Analisis_PDF.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            @if (!empty(($normaHistoricaXrf['Analisis_PDF'] ?? [])))
                                <div class="mt-3">
                                    <strong>PDF guardados:</strong>
                                    @foreach ($normaHistoricaXrf['Analisis_PDF'] as $analisisGuardado)
                                        @if (!empty($analisisGuardado['ruta']))
                                            <a class="btn btn-sm btn-outline-secondary ml-1" target="_blank" href="{{ asset($analisisGuardado['ruta']) }}">
                                                {{ $analisisGuardado['archivo'] ?? 'Ver PDF' }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
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
                        <div id="capturaXrfUnica" class="mt-3"></div>
                    </div>
                    <div class="col-12 d-none" id="normaIMResultadosContainer">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped w-100" id="tablaNormaIM">
                                <thead><tr>
                                    <th>Elemento químico / Chemical element</th>
                                    <th>Promedio de la pieza analizada / Average</th>
                                    <th>Composición química teórica / Theoretical composition</th>
                                </tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="alert alert-light border d-none" id="normaIMObservacionesContainer">
                            <strong>Observaciones de la norma:</strong>
                            <div id="normaIMObservaciones" style="white-space: pre-line;"></div>
                        </div>
                    </div>
                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                    <div class="col-12">
                        <div class="form-group">
                        <label class="col-form-label" for="observacionesEquipo">Observaciones o Conclusión:</label>
                        <textarea class="form-control is-waning" id="observacionesEquipo" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{ old('Datos_Equipo.Observaciones', $Datos_Equipo['Observaciones'] ?? '') }}</textarea>
                        </div>
                    </div>
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

                        <div class="alert alert-info py-2">
                            Asigna a cada fotografía el número de hoja y su posición. Una hoja admite hasta cuatro posiciones o una fotografía de página completa.
                            Si no cuenta con PDF XRF, marque <strong>Asignar esta imagen a un disparo</strong>; cada disparo requiere dos imágenes.
                        </div>

                        {{-- Conserva imágenes existentes y permite cambiar posición, texto o número de disparo. --}}
                        <div data-layout-fotos-manual="1">
                        @if(!empty($Fotos_Comentarios))
                            <div class="row">
                                @foreach($Fotos_Comentarios as $index => $foto)
                                    <div class="col-sm-6" id="image-container-{{ $index }}"
                                        data-foto-pagina="{{ $foto['pagina'] ?? (intdiv($index, 4) + 1) }}"
                                        data-foto-posicion="{{ $foto['posicion'] ?? (!empty($foto['una_hoja']) ? 'pagina_completa' : ['arriba_izquierda', 'arriba_derecha', 'abajo_izquierda', 'abajo_derecha'][$index % 4]) }}"
                                        data-foto-hoja-completa="{{ !empty($foto['una_hoja']) ? 1 : 0 }}"
                                        data-foto-es-texto="{{ !empty($foto['es_cuadro_texto']) ? 1 : 0 }}">
                                        <div class="form-group">
                                            <label for="replace_image_{{ $index }}">Imagen subida {{ $index + 1 }}:</label>

                                            <div class="image-preview mt-2">
                                                @if(empty($foto['es_cuadro_texto']) && !empty($foto['ruta']))
                                                    <img src="{{ asset($foto['ruta']) }}" class="img-fluid img-thumbnail" alt="Imagen Reporte">
                                                @endif
                                            </div>

                                            <div class="form-check mt-2">
                                                <input type="hidden" name="es_disparo[{{ $index }}]" id="esDisparoValue{{ $index }}" value="{{ !empty($foto['es_disparo']) ? 1 : 0 }}">
                                                <input type="checkbox" class="form-check-input foto-disparo-checkbox" data-index="{{ $index }}" id="esDisparo{{ $index }}" @checked(!empty($foto['es_disparo']))>
                                                <label class="form-check-label" for="esDisparo{{ $index }}">Asignar esta imagen a un disparo</label>
                                            </div>
                                            <div class="mt-2 numero-disparo-container {{ !empty($foto['es_disparo']) ? '' : 'd-none' }}" id="numeroDisparoContainer{{ $index }}">
                                                <label for="numeroDisparo{{ $index }}">Disparo:</label>
                                                <select class="form-control" name="numero_disparo[{{ $index }}]" id="numeroDisparo{{ $index }}">
                                                    <option value="">Seleccione un disparo</option>
                                                    <option value="1" @selected(($foto['numero_disparo'] ?? '') == 1)>1er. disparo</option>
                                                    <option value="2" @selected(($foto['numero_disparo'] ?? '') == 2)>2do. disparo</option>
                                                    <option value="3" @selected(($foto['numero_disparo'] ?? '') == 3)>3er. disparo</option>
                                                </select>
                                                <small class="text-muted">Alternativa cuando no se cuenta con PDF XRF. Cada disparo requiere dos imágenes.</small>
                                            </div>
                                            <input type="file" class="form-control image-input mt-2" id="replace_image_{{ $index }}" name="replace_images[{{ $index }}]" accept="image/*">
                                            <textarea class="form-control mt-2" name="comments[{{ $index }}]" placeholder="Comentario">{{ $foto['comentario'] ?? '' }}</textarea>
                                            <input type="hidden" name="images_base64[{{ $index }}]" id="replace_image_{{ $index }}-base64">
                                            <input type="hidden" name="existing_images[{{ $index }}]" value="{{ $foto['ruta'] ?? '' }}">
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
<script src="{{ asset('js/Reportes_Edit.js') }}?v={{ filemtime(public_path('js/Reportes_Edit.js')) }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="{{ asset('js/Reportes_Editor_Imagenes_05_B_01.js') }}?v={{ filemtime(public_path('js/Reportes_Editor_Imagenes_05_B_01.js')) }}"></script>
<script src="{{ asset('js/Reportes_Fotos_Posicionables_02_B_04.js') }}?v={{ filemtime(public_path('js/Reportes_Fotos_Posicionables_02_B_04.js')) }}"></script>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('FOR-PIMP-05_B_01');
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

    configurarSelectEquipo(
        'equiposSelect1',
        'marcaInputE1',
        'modeloInputE1',
        'nsInputE1',
        'IDInputE1',
        'equipos1'
    );
});
// Sincroniza los selects de técnicos con los campos ocultos/visibles que se guardan para el reporte, siguiendo el patrón de PINS.
    document.addEventListener('DOMContentLoaded', function () {
        for (let indice = 1; indice <= 4; indice++) {
            const sufijo = indice === 1 ? '' : indice;
            const select = document.getElementById('tecnicosSelect' + sufijo);
            const idTecnico = document.getElementById('IDTECNICO' + sufijo);
            const nombre = document.getElementById('NOMBRE_TECNICO' + sufijo);

            if (!select || !idTecnico || !nombre) continue;

            if (idTecnico.value) {
                select.value = idTecnico.value;
            }

            select.addEventListener('change', function () {
                const opcion = select.options[select.selectedIndex];
                idTecnico.value = select.value || '';
                nombre.value = opcion ? (opcion.getAttribute('data-name') || '') : '';
            });
        }
    });
</script>
{{-- Ruta propia del formato; únicamente la lógica de navegador se reutiliza. --}}
@php($xrfExtractionRoute = route('Reportes_FOR_PIMP_05_B_01.extraer_analisis'))
@php($xrfDetectColumnsOnFileChange = true)
@include('Reportes.IM.partials.script-columnas-pdf-xrf-05-b-01')
@endsection
