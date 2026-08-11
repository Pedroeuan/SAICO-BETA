@extends('adminlte::page')

@section('title', 'FOR-PIMP-04_03')

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
        /* Acordeon visual del FOR-PIMP-04_03.
           Solo ordena la vista en bloques; no cambia nombres de campos ni datos enviados. */
        .saico-form-section {
            border: 1px solid #0d6efd;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.08);
        }

        .saico-form-section-header {
            cursor: pointer;
            user-select: none;
        }

        .saico-form-section-header .saico-section-icon {
            margin-left: auto;
            font-weight: bold;
            font-size: 1rem;
        }

        .saico-form-section-body {
            padding: 12px;
        }

        .saico-form-section.is-collapsed .saico-form-section-body {
            display: none !important;
        }

        /* Acordeon de herramientas metalograficas.
           Mantiene visible el titulo azul y oculta solo Fiji + conteo para reducir ruido visual. */
        .saico-metalografia-tools-section {
            border: 1px solid #0d6efd;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.08);
        }

        .saico-metalografia-tools-header {
            cursor: pointer;
            user-select: none;
        }

        .saico-metalografia-tools-header .saico-section-icon {
            margin-left: auto;
            font-weight: bold;
        }

        .saico-metalografia-tools-body {
            padding: 12px;
        }

        .saico-metalografia-tools-section.is-collapsed .saico-metalografia-tools-body {
            display: none !important;
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
            <form id="FOR-PIMP-04_03" action="{{route('Reportes_FOR_PIMP_04_03.store')}}" method="post" enctype="multipart/form-data">
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
                            <label class="col-form-label" for="proyecto0403Create">Proyecto:</label>
                            <textarea class="form-control  is-waning" id="proyecto0403Create" name="Detalles_Generales[Proyecto]" placeholder="Ejemplo: INGENIERÍA, PROCURA, CONSTRUCCIÓN DE DUCTOS MARINOS NUEVOS PARA MANEJO DE PRODUCCIÓN DE PLATAFORMAS GENÉRICAS, A INSTALARSE EN LA SONDA DE CAMPECHE, GOLFO DE MÉXICO ...">{{old('Detalles_Generales.Proyecto')}}</textarea>
                            @error('Proyecto')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="ordenTrabajo0403Create">Orden de Trabajo:</label>
                            <textarea class="form-control  is-waning" id="ordenTrabajo0403Create" name="Detalles_Generales[Orden_Trabajo]" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . .">{{old('Detalles_Generales.Orden_Trabajo')}}</textarea>
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
                            <label class="col-form-label" for="noIsometrico0403Create">No. Isométrico:</label>
                            <input type="text" class="form-control  is-waning" id="noIsometrico0403Create" name="Detalles_Generales[No_Isometrico]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.No_Isometrico')}}">
                            @error('No_Isometrico')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Nombre de la Pieza:</label>
                            <input type="text" class="form-control  inputForm @error('Nombre_Pieza') is-invalid @enderror" name="Detalles_Generales[Nombre_Pieza]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Nombre_Pieza')}}">
                            @error('Nombre_Pieza')
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
                            <label class="col-form-label" for="trazabilidad0403Create">Trazabilidad:</label>
                            <input type="text" class="form-control  is-waning" id="trazabilidad0403Create" name="Detalles_Generales[Trazabilidad]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Trazabilidad')}}">
                            @error('Trazabilidad')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
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
                            <label class="col-form-label" for="criterioEvaluacion0403Create">Criterio de Evaluación:</label>
                            <input type="text" class="form-control  is-waning" id="criterioEvaluacion0403Create" name="Detalles_Generales[Criterio_Evaluacion]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Criterio_Evaluacion')}}">
                            @error('Criterio_Evaluacion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Accesorio:</label>
                            <input type="text" class="form-control  inputForm @error('Accesorio') is-invalid @enderror" name="Detalles_Generales[Accesorio]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Accesorio')}}">
                            @error('Accesorio')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="tuberia0403Create">Tubería:</label>
                            <input type="text" class="form-control  is-waning" id="tuberia0403Create" name="Detalles_Generales[Tuberia]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Tuberia')}}">
                            @error('Tuberia')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="estructural0403Create">Estructural:</label>
                            <input type="text" class="form-control  is-waning" id="estructural0403Create" name="Detalles_Generales[Estructural]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Estructural')}}">
                            @error('Estructural')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">No. de Isometrico Y/O Plano:</label>
                            <input type="text" class="form-control  inputForm @error('No_Isometrico_Plano') is-invalid @enderror" name="Detalles_Generales[No_Isometrico_Plano]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.No_Isometrico_Plano')}}">
                            @error('No_Isometrico_Plano')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Observaciones y Notas:</label>
                            <input type="text" class="form-control  inputForm @error('Observaciones_Notas') is-invalid @enderror" name="Detalles_Generales[Observaciones_Notas]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Observaciones_Notas')}}">
                            @error('Observaciones_Notas')
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

                    <div style="margin-bottom: 5px;"></div>

                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-info"></i> Importante</h5>
                        <p>Puedes Seleccionar un equipo, menu o escribir directamente</p>
                    </div>
                    
                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ENSAYO DE DUREZA</div>

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

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ANÁLISIS QUÍMICO</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <select class="form-select inputForm" name="equipos1" id="equiposSelect1">
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
                            <input type="hidden" name="Datos_Equipo[ID_EQUIPO1]" id="IDInputE1" value="{{ old('Datos_Equipo.ID_EQUIPO1') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputE1" name="Datos_Equipo[MARCA_EQUIPO1]" placeholder="" value="{{old('Datos_Equipo.MARCA_EQUIPO1')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputE1" name="Datos_Equipo[MODELO_EQUIPO1]" placeholder="" value="{{old('Datos_Equipo.MODELO_EQUIPO1')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputE1" name="Datos_Equipo[NS_EQUIPO1]" placeholder="" value="{{old('Datos_Equipo.NS_EQUIPO1')}}">
                        </div>
                    </div>
                    
                    {{-- Tabla propia del 04_03: conserva exactamente diez posiciones y recupera old input. --}}
                    @php
                        $hardnessValues = old('Datos_Equipo.VALORES_DUREZA', []);
                        $hardnessValues = is_array($hardnessValues) ? array_values(array_slice($hardnessValues, 0, 10)) : [];
                        $hardnessValues = array_pad($hardnessValues, 10, '');
                    @endphp

                    {{-- Captura de dureza, propiedades obtenidas y valores de la norma de referencia. --}}
                    <div class="col-12">
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DE DUREZA</div>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered text-center align-middle mb-0">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th colspan="7">
                                            VALORES DE DUREZA MEDIDOS (ESCALA
                                            <input type="text" class="form-control form-control-sm d-inline-block text-center"
                                                style="width: 90px; height: 25px;" id="escalaDureza"
                                                name="Datos_Equipo[ESCALA_DUREZA]"
                                                value="{{ old('Datos_Equipo.ESCALA_DUREZA') }}" placeholder="XXX">)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (array_chunk($hardnessValues, 5, true) as $rowIndex => $hardnessRow)
                                        <tr>
                                            @foreach ($hardnessRow as $index => $hardnessValue)
                                                <td style="width: 12%;">
                                                    <input type="text" inputmode="decimal"
                                                        class="form-control text-center valor-dureza-medida @error('Datos_Equipo.VALORES_DUREZA.' . $index) is-invalid @enderror"
                                                        name="Datos_Equipo[VALORES_DUREZA][{{ $index }}]"
                                                        value="{{ $hardnessValue }}" aria-label="Valor de dureza {{ $index + 1 }}">
                                                </td>
                                            @endforeach
                                            @if ($rowIndex === 0)
                                                <th class="align-middle" style="width: 20%;" rowspan="2">PROMEDIO</th>
                                                <td class="align-middle" style="width: 20%;" rowspan="2">
                                                    <input type="text" class="form-control text-center font-weight-bold"
                                                        id="promedioDureza" name="Datos_Equipo[PROMEDIO_DUREZA]"
                                                        value="{{ old('Datos_Equipo.PROMEDIO_DUREZA') }}" readonly>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mb-3">
                            <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS OBTENIDOS DEL MATERIAL</div>
                            <table class="table table-bordered text-center align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 35%;">DESCRIPCIÓN DEL MATERIAL</th>
                                        <th>DUREZA BRINELL</th>
                                        <th style="width: 25%;">RESISTENCIA A LA TENSIÓN (KSI)</th>
                                        <th style="width: 22%;">RESISTENCIA A LA CEDENCIA (KSI)</th>
                                        <th>TAMAÑO DE GRANO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[DESCRIPCION_MATERIAL]" value="{{ old('Datos_Equipo.DESCRIPCION_MATERIAL') }}"></td>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[DUREZA_BRINELL]" value="{{ old('Datos_Equipo.DUREZA_BRINELL') }}"></td>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[RESISTENCIA_TENSION]" value="{{ old('Datos_Equipo.RESISTENCIA_TENSION') }}"></td>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[RESISTENCIA_CEDENCIA]" value="{{ old('Datos_Equipo.RESISTENCIA_CEDENCIA') }}"></td>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[TAMANO_GRANO]" value="{{ old('Datos_Equipo.TAMANO_GRANO') }}"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mb-3">
                            <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DE LA NORMA DE REFERENCIA</div>
                            <table class="table table-bordered text-center align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 24%;">NORMA DE REFERENCIA</th>
                                        <th>DUREZA BRINELL, MAX</th>
                                        <th style="width: 19%;">RESISTENCIA A LA TENSIÓN MÍNIMA ESPECIFICADA (KSI)</th>
                                        <th style="width: 28%;">RESISTENCIA A LA CEDENCIA ESPECIFICADA (KSI)</th>
                                        <th style="width: 20%;">RESISTENCIA A LA TENSIÓN MÁXIMA ESPECIFICADA (KSI)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[NORMA_REFERENCIA]" value="{{ old('Datos_Equipo.NORMA_REFERENCIA') }}"></td>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[DUREZA_BRINELL_MAX]" value="{{ old('Datos_Equipo.DUREZA_BRINELL_MAX') }}"></td>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[RESISTENCIA_TENSION_MIN]" value="{{ old('Datos_Equipo.RESISTENCIA_TENSION_MIN') }}"></td>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[RESISTENCIA_CEDENCIA_ESPECIFICADA]" value="{{ old('Datos_Equipo.RESISTENCIA_CEDENCIA_ESPECIFICADA') }}"></td>
                                        <td><input type="text" class="form-control" name="Datos_Equipo[RESISTENCIA_TENSION_MAX]" value="{{ old('Datos_Equipo.RESISTENCIA_TENSION_MAX') }}"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Datos metalográficos compartidos por 03_B/01, 04_02 y 04_03. --}}
                    @include('Reportes.IM.partials.datos-metalograficos', ['esEdicionMetalografica' => false])

                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->

                    {{-- Formulario de norma y análisis XRF propio del Create 04_03. --}}
                    @php
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
                            <label for="analisisPdfXrf"><strong>PDF del equipo XRF (1 hoja por archivo)</strong></label>
                            <input type="file" class="form-control-file @error('Analisis_PDF.*') is-invalid @enderror"
                                id="analisisPdfXrf" name="Analisis_PDF[]" accept="application/pdf,.pdf" multiple>
                            <small class="form-text text-muted">
                                Seleccione los PDF en orden. Los primeros tres corresponden a los disparos 1, 2 y 3; cada uno genera la tabla y la gráfica. Todos participan en el promedio.
                            </small>
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

                    {{-- Herramientas compartidas: fracción de fases con Fiji y conteo lineal de granos. --}}
                    @include('Reportes.IM.partials.fraccion-fases-imagej', ['analisisImagen' => []])
                    @include('Reportes.IM.partials.conteo-granos-lineal', ['conteoGranos' => []])

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
                                            <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[NOMBRE_TECNICO]" placeholder="Ejemplo: NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO')}}"></td>
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
                                    <tr>
                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                        <td><input type="text" class="form-control inputForm" name="Firmas_Reportes4[NUMERO_FICHA]" placeholder="NÚMERO DE FICHA" value="{{ old('Firmas_Reportes4.NUMERO_FICHA') }}"></td>
                                    </tr>
                                    
                                </thead>                            
                            </table>
                        </div>
                        

                        <p>

                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">FOTOS</div>
                        
                        <p>

                        {{-- Espacios 1 y 2: micrografía original y resultados editables del análisis. --}}
                        @include('Reportes.IM.partials.analisis-imagen-reporte-fotos', ['analisisImagen' => []])

                        {{-- Configura el modo "Agregar tamaño de grano" disponible en cada tarjeta de imagen. --}}
                        @include('Reportes.IM.partials.patron-grano-reporte', ['patronGrano' => []])

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

                        <div class="alert alert-info py-2">
                            Asigna a cada fotografía el número de hoja y su posición. Una hoja admite hasta cuatro posiciones o una fotografía de página completa.
                            Si no cuenta con PDF XRF, marque <strong>Asignar esta imagen a un disparo</strong>; cada disparo requiere dos imágenes.
                            Para una comparativa, marque <strong>Agregar tamaño de grano</strong> en una tarjeta vacía.
                            Al usar Fiji o un patrón comparativo, las posiciones que elija quedan reservadas y las fotografías manuales comienzan en el primer espacio disponible.
                        </div>
                        @error('foto_posicion')
                            <div class="alert alert-danger py-2">{{ $message }}</div>
                        @enderror

                        {{-- El JS compartido agrega posición, cuadro de texto y asignación manual de disparo. --}}
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
    // Agrupa el FOR-PIMP-04_03 en 5 bloques funcionales para reducir scroll.
    // El bloque 4 (Fiji + conteo de granos) lo controla el minimizador metalografico existente.
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-PIMP-04_03');
        if (!form || form.dataset.saicoSecciones0403Inicializadas === '1') return;

        form.dataset.saicoSecciones0403Inicializadas = '1';

        const normalizar = function (texto) {
            return (texto || '').replace(/\s+/g, ' ').trim().toUpperCase();
        };

        const encabezados = Array.from(form.querySelectorAll('.d-flex.justify-content-center.align-items-center.p-2.bg-primary.text-white.rounded'));
        const buscarEncabezado = function (texto) {
            return encabezados.find(function (encabezado) {
                return normalizar(encabezado.textContent).includes(texto);
            });
        };

        const inicioFraccion = form.querySelector('[data-imagej-phase]');
        const secciones = [
            { clave: 'generales', titulo: '1. Datos generales', inicio: buscarEncabezado('DATOS GENERALES'), fin: buscarEncabezado('DATOS DEL EQUIPO'), abierta: true },
            { clave: 'equipo', titulo: '2. Datos del equipo', inicio: buscarEncabezado('DATOS DEL EQUIPO'), fin: buscarEncabezado('NORMA Y RESULTADOS'), abierta: false },
            { clave: 'norma', titulo: '3. Norma y resultados', inicio: buscarEncabezado('NORMA Y RESULTADOS'), fin: inicioFraccion, abierta: false },
            { clave: 'firmas_fotos', titulo: '5. Firmas y fotos', inicio: buscarEncabezado('FIRMAS'), fin: null, abierta: false },
        ].filter(function (seccion) {
            return seccion.inicio;
        });

        secciones.forEach(function (seccion) {
            const contenedor = document.createElement('div');
            const cuerpo = document.createElement('div');
            const icono = document.createElement('span');
            const claveEstado = 'saico:FOR-PIMP-04_03:seccion:' + window.location.pathname + ':' + seccion.clave;
            const estadoGuardado = localStorage.getItem(claveEstado);
            const abierta = estadoGuardado === null ? seccion.abierta : estadoGuardado === '1';

            contenedor.className = 'col-12 saico-form-section mb-3' + (abierta ? '' : ' is-collapsed');
            cuerpo.className = 'saico-form-section-body row';
            icono.className = 'saico-section-icon';

            seccion.inicio.classList.add('saico-form-section-header');
            seccion.inicio.setAttribute('role', 'button');
            seccion.inicio.setAttribute('tabindex', '0');
            seccion.inicio.innerHTML = '<span>' + seccion.titulo + '</span>';
            seccion.inicio.appendChild(icono);

            const actualizarVista = function () {
                const cerrado = contenedor.classList.contains('is-collapsed');
                icono.textContent = cerrado ? '+' : '-';
                cuerpo.style.display = cerrado ? 'none' : 'flex';
            };

            const alternar = function () {
                contenedor.classList.toggle('is-collapsed');
                localStorage.setItem(claveEstado, contenedor.classList.contains('is-collapsed') ? '0' : '1');
                actualizarVista();
            };

            seccion.inicio.parentNode.insertBefore(contenedor, seccion.inicio);
            contenedor.appendChild(seccion.inicio);

            let nodo = contenedor.nextSibling;
            while (nodo && nodo !== seccion.fin) {
                const mover = nodo;
                nodo = nodo.nextSibling;
                cuerpo.appendChild(mover);
            }

            contenedor.appendChild(cuerpo);
            actualizarVista();

            seccion.inicio.addEventListener('click', alternar);
            seccion.inicio.addEventListener('keydown', function (evento) {
                if (evento.key === 'Enter' || evento.key === ' ') {
                    evento.preventDefault();
                    alternar();
                }
            });
        });
    });
</script>

<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script src="{{ asset('js/Reportes_Create_IM_02.js') }}?v={{ filemtime(public_path('js/Reportes_Create_IM_02.js')) }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="{{ asset('js/Reportes_Fotos_Posicionables_02_B_04.js') }}?v={{ filemtime(public_path('js/Reportes_Fotos_Posicionables_02_B_04.js')) }}"></script>
<script>
    // Minimiza solo las herramientas pesadas de metalografia: Fiji + conteo lineal.
    // No cambia campos, rutas ni datos enviados; unicamente reorganiza la vista para produccion.
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-PIMP-04_03');
        if (!form || form.dataset.saicoMetalografiaTools === '1') return;

        const phase = form.querySelector('[data-imagej-phase]');
        const grain = form.querySelector('[data-grain-counter]');
        if (!phase) return;

        form.dataset.saicoMetalografiaTools = '1';

        const header = phase.querySelector('.d-flex.justify-content-center.align-items-center.p-2.bg-primary.text-white.rounded');
        if (!header) return;

        const section = document.createElement('div');
        const body = document.createElement('div');
        const icon = document.createElement('span');
        const storageKey = 'saico:' + form.id + ':metalografia-tools:' + window.location.pathname;
        const savedState = localStorage.getItem(storageKey);
        const opened = savedState === null ? false : savedState === '1';

        section.className = 'col-12 saico-metalografia-tools-section my-3' + (opened ? '' : ' is-collapsed');
        body.className = 'saico-metalografia-tools-body row';
        icon.className = 'saico-section-icon';

        header.classList.add('saico-metalografia-tools-header');
        header.setAttribute('role', 'button');
        header.setAttribute('tabindex', '0');
        header.innerHTML = '<span>4. Fraccion de fases y granos</span>';
        header.appendChild(icon);

        const refresh = function () {
            const closed = section.classList.contains('is-collapsed');
            icon.textContent = closed ? '+' : '-';
            body.style.display = closed ? 'none' : 'flex';
        };

        const toggle = function () {
            section.classList.toggle('is-collapsed');
            localStorage.setItem(storageKey, section.classList.contains('is-collapsed') ? '0' : '1');
            refresh();
        };

        phase.parentNode.insertBefore(section, phase);
        section.appendChild(header);
        body.appendChild(phase);
        if (grain) body.appendChild(grain);
        section.appendChild(body);
        refresh();

        header.addEventListener('click', toggle);
        header.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggle();
            }
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

    /*FOR-PIMP-04_03*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-PIMP-04_03');
        if (!form) return;

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-PIMP-04_03_Form_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-PIMP-04_03_Form_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-PIMP-04_03_Form_' + el.name);
                //localStorage.clear();
            });
        });
    });

</script>

{{-- Ruta propia del formato; el comportamiento de extracción sí se comparte mediante JavaScript. --}}
@php($xrfExtractionRoute = route('Reportes_FOR_PIMP_04_03.extraer_analisis'))
@include('Reportes.IM.partials.script-compartido-pdf-xrf')
{{-- Comportamiento común de las herramientas metalográficas. --}}
<script src="{{ asset('js/analisis-fraccion-fases-imagej.js') }}?v={{ filemtime(public_path('js/analisis-fraccion-fases-imagej.js')) }}"></script>
<script src="{{ asset('js/conteo-granos-lineal.js') }}?v={{ filemtime(public_path('js/conteo-granos-lineal.js')) }}"></script>
<script src="{{ asset('js/reporte-metalografico-fotos.js') }}?v={{ filemtime(public_path('js/reporte-metalografico-fotos.js')) }}"></script>
<script src="{{ asset('js/patron-grano-reporte.js') }}?v={{ filemtime(public_path('js/patron-grano-reporte.js')) }}"></script>
@endsection
