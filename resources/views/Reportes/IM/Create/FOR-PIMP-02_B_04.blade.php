@extends('adminlte::page')

@section('title', 'FOR-PIMP-02_B_04')

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
        .tabla-dureza th,
.tabla-dureza td {
    border: 1px solid #000 !important;
    font-size: 11px;
    padding: 3px;
    vertical-align: middle;
}

.tabla-dureza input {
    height: 22px;
    padding: 2px;
    text-align: center;
    border: none;
}

.tabla-dureza textarea {
    border: none;
    resize: none;
    min-height: 390px;
}

.mergeable-cell {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.mergeable-cell.selected-merge {
    background-color: #ffe3e3 !important;
    box-shadow: inset 0 0 0 2px #dc3545;
}

.mergeable-cell.selected-merge input {
    background-color: #ffd6d6 !important;
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.2) !important;
}

.merge-tools {
    gap: 8px;
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
            <form id="FOR-PIMP-02_B_04" action="{{route('Reportes_FOR_PIMP_02_B_04.store')}}" method="post" enctype="multipart/form-data">
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
                            <label class="col-form-label" for="inputSuccess">Proyecto:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Proyecto]" placeholder="Ejemplo: INGENIERÍA, PROCURA, CONSTRUCCIÓN DE DUCTOS MARINOS NUEVOS PARA MANEJO DE PRODUCCIÓN DE PLATAFORMAS GENÉRICAS, A INSTALARSE EN LA SONDA DE CAMPECHE, GOLFO DE MÉXICO ...">{{old('Detalles_Generales.Proyecto')}}</textarea>
                            @error('Proyecto')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Orden_Trabajo]" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . .">{{old('Detalles_Generales.Orden_Trabajo')}}</textarea>
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
                            <label class="col-form-label" for="inputSuccess">No. Isométrico:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[No_Isometrico]" placeholder="Ejemplo:" value="{{ old('Detalles_Generales.No_Isometrico') }}">
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

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Procedimiento:</label>
                            <input type="text" class="form-control  inputForm @error('Procedimiento') is-invalid @enderror" name="Detalles_Generales[Procedimiento]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Procedimiento')}}">
                            @error('Procedimiento')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Criterio de Evaluación:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Criterio_Evaluacion]" placeholder="Ejemplo:" value="{{ old('Detalles_Generales.Criterio_Evaluacion') }}">
                            @error('Criterio_Evaluacion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Trazabilidad:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Trazabilidad]" placeholder="Ejemplo:" value="{{ old('Detalles_Generales.Trazabilidad') }}">
                            @error('Trazabilidad')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">No. Junta:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[No_Junta]" placeholder="Ejemplo:" value="{{ old('Detalles_Generales.No_Junta') }}">
                            @error('No_Junta')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Temperatura de la Pieza:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Temperatura_pieza]" placeholder="Ejemplo:" value="{{ old('Detalles_Generales.Temperatura_pieza') }}">
                            @error('Temperatura_pieza')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Espesor/Cedúla:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Espesor_cedula]" placeholder="Ejemplo:" value="{{ old('Detalles_Generales.Espesor_cedula') }}">
                            @error('Espesor_cedula')
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

                    @php
                        $resolverMetodoEquipo = function ($equipo) {
                            $posiblesCampos = [
                                data_get($equipo, 'Metodo_Medicion'),
                                data_get($equipo, 'Metodo'),
                                data_get($equipo, 'Nombre_E_P_BP'),
                                data_get($equipo, 'Modelo'),
                                data_get($equipo, 'Marca'),
                                data_get($equipo, 'Serie'),
                            ];

                            foreach ($posiblesCampos as $valorCampo) {
                                $texto = mb_strtoupper(trim((string) $valorCampo));

                                if ($texto === '') {
                                    continue;
                                }

                                if (str_contains($texto, 'LEEB')) {
                                    return 'LEEB';
                                }

                                if (str_contains($texto, 'UCI')) {
                                    return 'UCI';
                                }

                            }

                            return '';
                        };
                    @endphp


                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            @php
                                $metodosEquipo = collect($idsGeneral_EyCs_Equipos ?? [])
                                    ->map($resolverMetodoEquipo)
                                    ->filter()
                                    ->unique()
                                    ->values();
                                if ($metodosEquipo->isEmpty() && collect($idsGeneral_EyCs_Equipos ?? [])->isNotEmpty()) {
                                    $metodosEquipo = collect(['LEEB', 'UCI']);
                                }
                            @endphp
                            <select class="form-select inputForm" id="metodoSelectE" name="Detalles_Generales[Metodo]">
                                <option value="" selected>Seleccione un Método</option>
                                @foreach($metodosEquipo as $metodo)
                                    <option value="{{ $metodo }}" {{ old('Detalles_Generales.Metodo') == $metodo ? 'selected' : '' }}>{{ $metodo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-center">
                            <select class="form-select inputForm" name="equipos" id="equiposSelect">
                            <option value="" selected disabled>Seleccione un Equipo</option> <!-- Opción por defecto -->
                                @foreach($idsGeneral_EyCs_Equipos as $equipo)
                                    @php
                                        $metodoEquipo = $resolverMetodoEquipo($equipo);
                                    @endphp
                                    <option value="{{ $equipo->idGeneral_EyC }}"
                                            data-marca="{{ $equipo->Marca }}"
                                            data-modelo="{{ $equipo->Modelo }}"
                                            data-ns="{{ $equipo->Serie }}"
                                            data-metodo="{{ $metodoEquipo }}">
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
                    
                    <!--***************************************** VALORES DE DUREZA MEDIDOS *****************************************-->
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded mb-2">VALORES PROMEDIO DE DUREZAS</div>

                    <table class="table table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th style="width:25%;">
                                    VALORES PROMEDIO DE DUREZAS:<br>
                                    <small>Average Hardness Values</small>
                                </th>
                                <th>
                                    METAL BASE<br>
                                    Base Metal<br>
                                    (A)
                                </th>
                                <th>
                                    ZAC<br>
                                    HAZ (B)
                                </th>
                                <th>
                                    SOLDADURA<br>
                                    Welding<br>
                                    (C)
                                </th>
                                <th>
                                    ZAC<br>
                                    HAZ (B1)
                                </th>
                                <th>
                                    METAL BASE<br>
                                    Base Metal<br>
                                    (B)
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <strong>ANTES DEL RELEVADO DE ESFUERZOS (HB):</strong><br>
                                    Before PWHT (HB)
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[ANTES_A]"
                                        value="{{ old('Dureza.ANTES_A') }}">
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[ANTES_B]"
                                        value="{{ old('Dureza.ANTES_B') }}">
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[ANTES_C]"
                                        value="{{ old('Dureza.ANTES_C') }}">
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[ANTES_B1]"
                                        value="{{ old('Dureza.ANTES_B1') }}">
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[ANTES_BM]"
                                        value="{{ old('Dureza.ANTES_BM') }}">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <strong>POSTERIOR AL RELEVADO DE ESFUERZOS (HB):</strong><br>
                                    After PWHT (HB)
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[DESPUES_A]"
                                        value="{{ old('Dureza.DESPUES_A') }}">
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[DESPUES_B]"
                                        value="{{ old('Dureza.DESPUES_B') }}">
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[DESPUES_C]"
                                        value="{{ old('Dureza.DESPUES_C') }}">
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[DESPUES_B1]"
                                        value="{{ old('Dureza.DESPUES_B1') }}">
                                </td>

                                <td>
                                    <input type="text" class="form-control"
                                        name="Dureza[DESPUES_BM]"
                                        value="{{ old('Dureza.DESPUES_BM') }}">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <fieldset disabled class="d-none">
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">ANTES O DESPUÉS DEL RELEVADO DE ESFUERZOS</div>

                    <div style="margin-bottom: 5px;"></div>

                    <table class="table table-bordered text-center align-middle tabla-dureza d-none">
                        <thead>
                            <tr>
                                <th colspan="2">
                                    DATOS DE LA JUNTA<br>
                                    <small>Join Data</small>
                                </th>

                                <th colspan="5">
                                    VALORES DE DUREZA (ESCALA BRINELL)<br>
                                    <small>Hardness Values (Brinell Scale)</small>
                                </th>

                                <th rowspan="2">
                                    OBSERVACIONES<br>
                                    <small>Remarks</small>
                                </th>
                            </tr>

                            <tr>
                                <th style="width: 22%;">
                                    DESCRIPCIÓN<br>
                                    <small>Description</small>
                                </th>

                                <th style="width: 10%;">
                                    HORARIOS TÉCNICOS<br>
                                    <small>Technical schedules</small>
                                </th>

                                <th>METAL BASE<br><small>Base Metal</small><br>(A)</th>
                                <th>ZAC<br><small>HAZ</small><br>B</th>
                                <th>SOLDADURA<br><small>Weld</small><br>(C)</th>
                                <th>ZAC<br><small>HAZ</small><br>(B1)</th>
                                <th>METAL BASE<br><small>Base Metal</small><br>(A1)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $horarios = ['12:00', '03:00', '06:00', '09:00'];
                            @endphp

                            @foreach ($horarios as $index => $horario)
                                @for ($i = 1; $i <= 5; $i++)
                                    <tr>
                                        @if ($index == 0 && $i == 1)
                                            <td rowspan="20">
                                                <textarea name="Datos_Dureza[DESCRIPCION]" class="form-control h-100"></textarea>
                                            </td>
                                        @endif

                                        @if ($i == 1)
                                            <td rowspan="5">
                                                <strong>{{ $horario }}</strong>
                                            </td>
                                        @endif

                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $horario }}][{{ $i }}][METAL_BASE_A]"></td>
                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $horario }}][{{ $i }}][ZAC_B]"></td>
                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $horario }}][{{ $i }}][SOLDADURA_C]"></td>
                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $horario }}][{{ $i }}][ZAC_B1]"></td>
                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $horario }}][{{ $i }}][METAL_BASE_A1]"></td>

                                        @if ($index == 0 && $i == 1)
                                            <td rowspan="20">
                                                <textarea name="Datos_Dureza[OBSERVACIONES]" class="form-control h-100"></textarea>
                                            </td>
                                        @endif
                                    </tr>
                                @endfor
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <input type="hidden" name="titulos_data" id="titulos_hidden_legacy">
                    <!--<button id="addBtn" type="button" class="btn btn-success custom-btn">Agregar Fila</button>-->
                    <div class="d-none">
                        <div>
                            <label for="numRowsLegacy">Número de Filas:</label>
                            <select id="numRowsLegacy" class="form-select">
                                @for ($i = 1; $i <= 500; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <button id="addBtnLegacy" type="button" class="btn btn-success custom-btn" onclick="agregarFilaIM0203()">Agregar Fila</button>

                        <button id="addTituloBtnLegacy" type="button" class="btn btn-success custom-btn" onclick="agregarTituloIM0203()">Agregar Título</button>

                        <button id="addLongBtnLegacy" type="button" class="btn btn-success custom-btn" onclick="agregarLongitudIM0203()">Agregar Longitud Inspeccionada</button>

                        <button id="preFillBtnLegacy" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                    </div>

                    </fieldset>

                    @php
                        $durezaRows = collect(old('Dureza', []))
                            ->filter(function ($row, $key) {
                                return is_numeric($key) && is_array($row);
                            })
                            ->values()
                            ->all();

                        if (empty($durezaRows)) {
                            $durezaRows = [[
                                'descripcion' => '',
                                'horario' => '',
                                'metal_base_a' => '',
                                'zac_b' => '',
                                'soldadura_c' => '',
                                'zac_b1' => '',
                                'metal_base_a1' => '',
                                'observaciones' => '',
                            ]];
                        }
                    @endphp

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 w-100 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="numRows" class="mb-0 fw-semibold">Número de filas:</label>
                            <select id="numRows" class="form-select" style="width: 100px;">
                                @for ($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <button id="addDurezaRowsBtn" type="button" class="btn btn-success custom-btn">Agregar fila</button>
                    </div>

                    <div class="d-flex flex-wrap align-items-center merge-tools mb-3">
                        <button id="mergeSelectedCellsBtn" type="button" class="btn btn-primary">Combinar celdas</button>
                        <button id="splitSelectedCellsBtn" type="button" class="btn btn-outline-secondary">Separar celdas</button>
                        <small class="text-muted">Selecciona celdas solo de `Descripción`, `Horario` u `Observaciones`.</small>
                    </div>

                    <div class="table-responsive mb-3">
                        <input type="hidden" name="Dureza_MergeConfig" id="durezaMergeConfig" value="{{ old('Dureza_MergeConfig', '[]') }}">
                        <table class="table table-bordered align-middle text-center" id="tablaDurezaBrinell">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 190px;">DESCRIPCIÓN<br><small>Description</small></th>
                                    <th style="min-width: 120px;">HORARIO<br><small>Schedule</small></th>
                                    <th style="min-width: 120px;">METAL BASE<br><small>Base Metal</small><br>(A)</th>
                                    <th style="min-width: 120px;">ZAC / HAZ<br>(B)</th>
                                    <th style="min-width: 120px;">SOLDADURA<br><small>Weld</small><br>(C)</th>
                                    <th style="min-width: 120px;">ZAC / HAZ<br>(B1)</th>
                                    <th style="min-width: 120px;">METAL BASE<br><small>Base Metal</small><br>(A1)</th>
                                    <th style="min-width: 180px;">OBSERVACIONES<br><small>Remarks</small></th>
                                    <th style="width: 80px;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="durezaBrinellBody">
                                @foreach($durezaRows as $index => $row)
                                    <tr>
                                        <td class="mergeable-cell" data-merge-field="descripcion"><input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][descripcion]" value="{{ $row['descripcion'] ?? '' }}"></td>
                                        <td class="mergeable-cell" data-merge-field="horario"><input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][horario]" value="{{ $row['horario'] ?? '' }}"></td>
                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][metal_base_a]" value="{{ $row['metal_base_a'] ?? '' }}"></td>
                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][zac_b]" value="{{ $row['zac_b'] ?? '' }}"></td>
                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][soldadura_c]" value="{{ $row['soldadura_c'] ?? '' }}"></td>
                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][zac_b1]" value="{{ $row['zac_b1'] ?? '' }}"></td>
                                        <td><input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][metal_base_a1]" value="{{ $row['metal_base_a1'] ?? '' }}"></td>
                                        <td class="mergeable-cell" data-merge-field="observaciones"><input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][observaciones]" value="{{ $row['observaciones'] ?? '' }}"></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm btnEliminarDureza">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label" for="observacionesEquipo">Observaciones:</label>
                            <textarea class="form-control is-waning" id="observacionesEquipo" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{ old('Datos_Equipo.Observaciones') }}</textarea>
                        </div>
                    </div>

                    <!--***************************************** FIN DATOS *****************************************-->

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
<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radiosCliente = document.querySelectorAll('input[name="TieneCliente"]');
        const clienteSelect = document.getElementById('campoClienteSelect');
        const clienteInput = document.getElementById('campoClienteInput');

        function toggleCliente() {
            const seleccionado = document.querySelector('input[name="TieneCliente"]:checked');
            if (!seleccionado || !clienteSelect || !clienteInput) return;

            if (seleccionado.value === 'si') {
                clienteSelect.style.display = 'block';
                clienteInput.style.display = 'none';
                clienteInput.value = '';
            } else {
                clienteSelect.style.display = 'none';
                clienteInput.style.display = 'block';
                clienteSelect.value = '';
                clienteInput.focus();
            }
        }

        radiosCliente.forEach(function (radio) {
            radio.addEventListener('change', toggleCliente);
        });

        toggleCliente();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const radiosContrato = document.getElementsByName('TieneContrato');
        const campoContrato = document.getElementById('campoContrato');

        radiosContrato.forEach(function (radio) {
            radio.addEventListener('change', async function () {
                sessionStorage.setItem('TieneContrato', this.value);

                if (!campoContrato) return;

                if (this.value === 'si') {
                    campoContrato.readOnly = false;
                    campoContrato.required = true;
                    campoContrato.value = '';
                    campoContrato.placeholder = 'Ejemplo: 640853841';
                    return;
                }

                campoContrato.readOnly = true;
                campoContrato.required = false;
                campoContrato.placeholder = 'Generando contrato interno...';

                try {
                    const response = await fetch('/api/siguiente-contrato-interno');
                    const data = await response.json();
                    campoContrato.value = data.siguiente || '';
                } catch (error) {
                    console.error('Error al obtener el contrato interno:', error);
                    alert('No se pudo generar el contrato interno');
                }
            });
        });

        const seleccionado = sessionStorage.getItem('TieneContrato');
        if (seleccionado) {
            const radioGuardado = Array.from(radiosContrato).find(function (radio) {
                return radio.value === seleccionado;
            });

            if (radioGuardado) {
                radioGuardado.checked = true;
                radioGuardado.dispatchEvent(new Event('change'));
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input, select, button, textarea').forEach(function (element) {
            if (element.tagName !== 'TEXTAREA') {
                element.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-PIMP-02_B_04');
        const imageCountSelect = document.getElementById('imageCount');
        const container = document.getElementById('imageFieldsContainer');
        const cropperImage = document.getElementById('cropperImage');
        const cropperModal = document.getElementById('cropperModal');
        const rotateLeftBtn = document.getElementById('rotateLeftBtn');
        const rotateRightBtn = document.getElementById('rotateRightBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveWithoutCropBtn = document.getElementById('saveWithoutCropBtn');
        const cropImageBtn = document.getElementById('cropImageBtn');

        if (!form || !imageCountSelect || !container || !cropperImage) return;

        const formId = form.id;
        let cropperInstance = null;
        let currentInput = null;

        function closeCropperModal() {
            if (window.jQuery && cropperModal) {
                $('#cropperModal').modal('hide');
            }
        }

        function openCropperModal() {
            if (window.jQuery && cropperModal) {
                $('#cropperModal').modal('show');
            }
        }

        function bindImageFieldEvents() {
            container.querySelectorAll('.imagen-hoja-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const index = this.dataset.index;
                    const hidden = document.getElementById(`imagenHojaValue${index}`);
                    if (hidden) {
                        hidden.value = this.checked ? 1 : 0;
                    }
                });
            });

            container.querySelectorAll('.remove-image').forEach(function (button) {
                button.addEventListener('click', function () {
                    const target = document.getElementById(`image-container-${this.dataset.index}`);
                    if (target) {
                        target.remove();
                    }

                    const currentCount = container.querySelectorAll('[id^="image-container-"]').length;
                    imageCountSelect.value = currentCount || '';
                    localStorage.setItem(formId + '_imageCount', currentCount);
                });
            });

            container.querySelectorAll('.image-input').forEach(function (input) {
                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    if (!file.type.startsWith('image/')) {
                        alert('Por favor, sube solo imágenes.');
                        e.target.value = '';
                        return;
                    }

                    currentInput = e.target;
                    const reader = new FileReader();

                    reader.onload = function (event) {
                        if (cropperInstance) {
                            cropperInstance.destroy();
                        }

                        cropperImage.src = event.target.result;
                        openCropperModal();

                        cropperInstance = new Cropper(cropperImage, {
                            aspectRatio: 4 / 3,
                            viewMode: 1,
                            autoCropArea: 1,
                            minContainerWidth: 760,
                            minContainerHeight: 600,
                            responsive: true
                        });
                    };

                    reader.readAsDataURL(file);
                });
            });
        }

        function generateImageFields(count) {
            const total = parseInt(count, 10) || 0;
            container.innerHTML = '';

            for (let i = 1; i <= total; i++) {
                const col = document.createElement('div');
                col.classList.add('col-sm-6');
                col.id = `image-container-${i}`;
                col.innerHTML = `
                    <div class="form-group">
                        <label for="image${i}">Imagen por subir ${i}:</label>
                        <input type="file" class="form-control image-input" id="image${i}" accept="image/*">

                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input imagen-hoja-checkbox" data-index="${i}" id="imagenHoja${i}">
                            <label class="form-check-label" for="imagenHoja${i}">
                                Imagen en una hoja
                            </label>
                        </div>

                        <input type="hidden" name="imagen_hoja[]" id="imagenHojaValue${i}" value="0">

                        <div class="image-preview mt-2" id="image${i}-preview"></div>
                        <textarea class="form-control mt-2" name="comments[]" placeholder="Comentario"></textarea>
                        <input type="hidden" name="images_base64[]" id="image${i}-base64">

                        <button type="button" class="btn btn-danger mt-2 remove-image" data-index="${i}">
                            Eliminar
                        </button>
                    </div>
                `;
                container.appendChild(col);
            }

            bindImageFieldEvents();
        }

        imageCountSelect.addEventListener('change', function () {
            localStorage.setItem(formId + '_imageCount', this.value || 0);
            generateImageFields(this.value);
        });

        if (rotateLeftBtn) {
            rotateLeftBtn.addEventListener('click', function () {
                if (cropperInstance) cropperInstance.rotate(-90);
            });
        }

        if (rotateRightBtn) {
            rotateRightBtn.addEventListener('click', function () {
                if (cropperInstance) cropperInstance.rotate(90);
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                closeCropperModal();
            });
        }

        if (saveWithoutCropBtn) {
            saveWithoutCropBtn.addEventListener('click', function () {
                if (!cropperInstance || !currentInput) return;

                try {
                    const imageData = cropperInstance.getImageData();
                    const rotation = cropperInstance.getData().rotate || 0;
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    if (Math.abs(rotation) % 180 === 90) {
                        canvas.width = imageData.naturalHeight;
                        canvas.height = imageData.naturalWidth;
                    } else {
                        canvas.width = imageData.naturalWidth;
                        canvas.height = imageData.naturalHeight;
                    }

                    ctx.translate(canvas.width / 2, canvas.height / 2);
                    ctx.rotate((rotation * Math.PI) / 180);
                    ctx.drawImage(
                        cropperInstance.element,
                        -imageData.naturalWidth / 2,
                        -imageData.naturalHeight / 2,
                        imageData.naturalWidth,
                        imageData.naturalHeight
                    );

                    const base64data = canvas.toDataURL();
                    const previewDiv = document.getElementById(`${currentInput.id}-preview`);
                    const hiddenInput = document.getElementById(`${currentInput.id}-base64`);

                    if (previewDiv) {
                        previewDiv.innerHTML = `<img src="${base64data}" class="img-fluid img-thumbnail" />`;
                    }

                    if (hiddenInput) {
                        hiddenInput.value = base64data;
                    }
                } catch (error) {
                    console.error('Error al guardar la imagen sin recortar:', error);
                }

                closeCropperModal();
            });
        }

        if (cropImageBtn) {
            cropImageBtn.addEventListener('click', function () {
                if (!cropperInstance || !currentInput) return;

                const croppedCanvas = cropperInstance.getCroppedCanvas();
                if (!croppedCanvas) return;

                const base64data = croppedCanvas.toDataURL();
                const previewDiv = document.getElementById(`${currentInput.id}-preview`);
                const hiddenInput = document.getElementById(`${currentInput.id}-base64`);

                if (previewDiv) {
                    previewDiv.innerHTML = `<img src="${base64data}" class="img-fluid img-thumbnail" />`;
                }

                if (hiddenInput) {
                    hiddenInput.value = base64data;
                }

                closeCropperModal();
            });
        }

        if (window.jQuery && cropperModal) {
            $('#cropperModal').on('hidden.bs.modal', function () {
                if (cropperInstance) {
                    cropperInstance.destroy();
                    cropperInstance = null;
                }
            });
        }

        const savedImageCount = localStorage.getItem(formId + '_imageCount');
        if (savedImageCount !== null && savedImageCount !== '') {
            imageCountSelect.value = savedImageCount;
            generateImageFields(savedImageCount);
        }

        form.addEventListener('submit', function () {
            localStorage.removeItem(formId + '_imageCount');
        });
    });

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

    function configurarMetodoYEquipo() {
        const $metodo = $('#metodoSelectE');
        const $equipo = $('#equiposSelect');
        const $marca = $('#marcaInputE');
        const $modelo = $('#modeloInputE');
        const $ns = $('#nsInputE');
        const $idEquipo = $('#IDInputE');
        const form = document.querySelector('form');
        const formId = form ? form.id : 'FOR-PIMP-02_B_04';
        const opcionesEquipo = $equipo.find('option').clone();

        function limpiarCamposEquipo() {
            $marca.val('');
            $modelo.val('');
            $ns.val('');
            $idEquipo.val('');
        }

        function filtrarEquipos(metodoSeleccionado) {
            $equipo.empty().append('<option value="" selected>Seleccione un Equipo</option>');

            opcionesEquipo.each(function() {
                const $opcion = $(this);
                const valor = $opcion.attr('value');

                if (!valor) {
                    return;
                }

                if (!metodoSeleccionado || ($opcion.data('metodo') || '') === metodoSeleccionado) {
                    $equipo.append($opcion.clone());
                }
            });
        }

        function actualizarDatosEquipo() {
            const $seleccionado = $equipo.find('option:selected');
            $marca.val($seleccionado.data('marca') || '');
            $modelo.val($seleccionado.data('modelo') || '');
            $ns.val($seleccionado.data('ns') || '');
            $idEquipo.val($equipo.val() || '');

            const metodoEquipo = $seleccionado.data('metodo') || '';
            if (metodoEquipo) {
                $metodo.val(metodoEquipo);
                localStorage.setItem(formId + '_metodo_equipo', metodoEquipo);
            }
        }

        const metodoLocal = localStorage.getItem(formId + '_metodo_equipo') || '';
        const equipoLocal = localStorage.getItem(formId + '_equipos');

        if (metodoLocal) {
            $metodo.val(metodoLocal);
        }

        filtrarEquipos($metodo.val());

        if (equipoLocal && $equipo.find('option[value="' + equipoLocal + '"]').length) {
            $equipo.val(equipoLocal);
            actualizarDatosEquipo();
        }

        $metodo.on('change', function() {
            filtrarEquipos($(this).val() || '');
            limpiarCamposEquipo();
            localStorage.setItem(formId + '_metodo_equipo', $(this).val() || '');
            localStorage.removeItem(formId + '_equipos');
        });

        $equipo.on('change', function() {
            actualizarDatosEquipo();
            localStorage.setItem(formId + '_equipos', $(this).val() || '');
        });
    }

    $(document).ready(function() {
        configurarMetodoYEquipo();
    });

    function construirFilaDureza(index, data = {}) {
        return `
            <tr>
                <td class="mergeable-cell" data-merge-field="descripcion"><input type="text" class="form-control inputForm" name="Dureza[${index}][descripcion]" value="${data.descripcion || ''}"></td>
                <td class="mergeable-cell" data-merge-field="horario"><input type="text" class="form-control inputForm" name="Dureza[${index}][horario]" value="${data.horario || ''}"></td>
                <td><input type="text" class="form-control inputForm" name="Dureza[${index}][metal_base_a]" value="${data.metal_base_a || ''}"></td>
                <td><input type="text" class="form-control inputForm" name="Dureza[${index}][zac_b]" value="${data.zac_b || ''}"></td>
                <td><input type="text" class="form-control inputForm" name="Dureza[${index}][soldadura_c]" value="${data.soldadura_c || ''}"></td>
                <td><input type="text" class="form-control inputForm" name="Dureza[${index}][zac_b1]" value="${data.zac_b1 || ''}"></td>
                <td><input type="text" class="form-control inputForm" name="Dureza[${index}][metal_base_a1]" value="${data.metal_base_a1 || ''}"></td>
                <td class="mergeable-cell" data-merge-field="observaciones"><input type="text" class="form-control inputForm" name="Dureza[${index}][observaciones]" value="${data.observaciones || ''}"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btnEliminarDureza">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    function renumerarFilasDureza() {
        $('#durezaBrinellBody tr').each(function(index) {
            $(this).find('input').each(function() {
                const currentName = $(this).attr('name') || '';
                const updatedName = currentName.replace(/Dureza\[\d+\]/, 'Dureza[' + index + ']');
                $(this).attr('name', updatedName);
            });
        });
    }

    function limpiarSeleccionMerge() {
        $('#durezaBrinellBody .mergeable-cell').removeClass('selected-merge');
    }

    let mergeSelectionAnchor = null;

    function guardarEstadoMerges() {
        const mergeState = [];

        $('#durezaBrinellBody .mergeable-cell[rowspan]').each(function() {
            const $cell = $(this);
            mergeState.push({
                row: $cell.closest('tr').index(),
                field: $cell.data('merge-field'),
                rowspan: parseInt($cell.attr('rowspan') || '1', 10),
                value: $cell.find('input').val() || ''
            });
        });

        $('#durezaMergeConfig').val(JSON.stringify(mergeState));
    }

    function restaurarEstadoMerges() {
        const rawState = $('#durezaMergeConfig').val();

        if (!rawState) {
            return;
        }

        let mergeState = [];

        try {
            mergeState = JSON.parse(rawState);
        } catch (error) {
            $('#durezaMergeConfig').val('[]');
            return;
        }

        if (!Array.isArray(mergeState)) {
            $('#durezaMergeConfig').val('[]');
            return;
        }

        mergeState.forEach(function(item) {
            const row = Number(item.row);
            const rowspan = Number(item.rowspan);
            const field = item.field;

            if (!field || !rowspan || rowspan < 2) {
                return;
            }

            const $masterRow = $('#durezaBrinellBody tr').eq(row);
            const $masterCell = $masterRow.find(`.mergeable-cell[data-merge-field="${field}"]`);

            if (!$masterCell.length) {
                return;
            }

            $masterCell.attr('rowspan', rowspan);
            $masterCell.find('input').val(item.value || '');

            for (let offset = 1; offset < rowspan; offset++) {
                const $row = $('#durezaBrinellBody tr').eq(row + offset);
                const $cell = $row.find(`.mergeable-cell[data-merge-field="${field}"]`);

                if ($cell.length) {
                    $cell.find('input').val(item.value || '');
                    $cell.hide();
                }
            }
        });
    }

    function seleccionarRangoMerge($startCell, $endCell) {
        const field = $startCell.data('merge-field');
        const startIndex = $startCell.closest('tr').index();
        const endIndex = $endCell.closest('tr').index();
        const minIndex = Math.min(startIndex, endIndex);
        const maxIndex = Math.max(startIndex, endIndex);

        limpiarSeleccionMerge();

        for (let rowIndex = minIndex; rowIndex <= maxIndex; rowIndex++) {
            $('#durezaBrinellBody tr')
                .eq(rowIndex)
                .find(`.mergeable-cell[data-merge-field="${field}"]:visible`)
                .addClass('selected-merge');
        }
    }

    function manejarSeleccionMerge($cell) {
        if (!$cell.length || !$cell.is(':visible')) {
            return;
        }

        if (!mergeSelectionAnchor) {
            limpiarSeleccionMerge();
            $cell.addClass('selected-merge');
            mergeSelectionAnchor = $cell;
            return;
        }

        const sameCell =
            mergeSelectionAnchor.closest('tr').index() === $cell.closest('tr').index() &&
            mergeSelectionAnchor.data('merge-field') === $cell.data('merge-field');

        if (sameCell) {
            $cell.toggleClass('selected-merge');
            mergeSelectionAnchor = $cell.hasClass('selected-merge') ? $cell : null;
            return;
        }

        if (mergeSelectionAnchor.data('merge-field') !== $cell.data('merge-field')) {
            limpiarSeleccionMerge();
            $cell.addClass('selected-merge');
            mergeSelectionAnchor = $cell;
            return;
        }

        seleccionarRangoMerge(mergeSelectionAnchor, $cell);
        mergeSelectionAnchor = null;
    }

    function descombinarCelda($cell) {
        if (!$cell.length) {
            return;
        }

        const mergeField = $cell.data('merge-field');
        const rowspan = parseInt($cell.attr('rowspan') || '1', 10);

        if (rowspan <= 1) {
            return;
        }

        const startIndex = $cell.closest('tr').index();
        $cell.removeAttr('rowspan');

        for (let offset = 1; offset < rowspan; offset++) {
            const $row = $('#durezaBrinellBody tr').eq(startIndex + offset);
            $row.find(`.mergeable-cell[data-merge-field="${mergeField}"]`).show();
        }

        guardarEstadoMerges();
    }

    function descombinarTodasLasCeldas() {
        $('#durezaBrinellBody .mergeable-cell[rowspan]').each(function() {
            descombinarCelda($(this));
        });
    }

    function sincronizarCeldasCombinadas($cell) {
        const rowspan = parseInt($cell.attr('rowspan') || '1', 10);
        const mergeField = $cell.data('merge-field');
        const value = $cell.find('input').val();
        const startIndex = $cell.closest('tr').index();

        if (rowspan <= 1) {
            return;
        }

        for (let offset = 1; offset < rowspan; offset++) {
            const $row = $('#durezaBrinellBody tr').eq(startIndex + offset);
            $row.find(`.mergeable-cell[data-merge-field="${mergeField}"] input`).val(value);
        }
    }

    function obtenerCeldasSeleccionadas() {
        return $('#durezaBrinellBody .mergeable-cell.selected-merge:visible');
    }

    function combinarCeldasSeleccionadas() {
        const $selected = obtenerCeldasSeleccionadas();

        if ($selected.length < 2) {
            alert('Selecciona al menos 2 celdas de la misma columna para combinar.');
            return;
        }

        const field = $selected.first().data('merge-field');
        const rowIndexes = $selected.map(function() {
            return $(this).closest('tr').index();
        }).get().sort((a, b) => a - b);

        const sameField = $selected.toArray().every(cell => $(cell).data('merge-field') === field);
        const consecutive = rowIndexes.every((rowIndex, position) => {
            return position === 0 || rowIndex === rowIndexes[position - 1] + 1;
        });

        if (!sameField || !consecutive) {
            alert('Solo puedes combinar celdas consecutivas de una misma columna.');
            return;
        }

        const $masterCell = $selected.first();
        const masterValue = $masterCell.find('input').val();
        $masterCell.attr('rowspan', $selected.length);

        $selected.slice(1).each(function() {
            const $cell = $(this);
            $cell.find('input').val(masterValue);
            $cell.hide();
        });

        limpiarSeleccionMerge();
        mergeSelectionAnchor = null;
        guardarEstadoMerges();
    }

    function separarCeldasSeleccionadas() {
        const $selected = obtenerCeldasSeleccionadas();

        if ($selected.length === 0) {
            alert('Selecciona una celda combinada para separar.');
            return;
        }

        $selected.each(function() {
            descombinarCelda($(this));
        });

        limpiarSeleccionMerge();
        mergeSelectionAnchor = null;
        guardarEstadoMerges();
    }

    function configurarTablaDurezaBrinell() {
        const $tbody = $('#durezaBrinellBody');
        const $addButton = $('#addDurezaRowsBtn');
        const $numRows = $('#numRows');
        const $mergeButton = $('#mergeSelectedCellsBtn');
        const $splitButton = $('#splitSelectedCellsBtn');

        restaurarEstadoMerges();

        $tbody.on('click', '.mergeable-cell', function() {
            manejarSeleccionMerge($(this));
        });

        $tbody.on('click', '.mergeable-cell input', function(e) {
            e.stopPropagation();
            manejarSeleccionMerge($(this).closest('.mergeable-cell'));
        });

        $tbody.on('input', '.mergeable-cell input', function() {
            sincronizarCeldasCombinadas($(this).closest('.mergeable-cell'));
        });

        $addButton.on('click', function() {
            descombinarTodasLasCeldas();
            const amount = parseInt($numRows.val(), 10) || 1;
            let startIndex = $tbody.find('tr').length;

            for (let i = 0; i < amount; i++) {
                $tbody.append(construirFilaDureza(startIndex + i));
            }

            renumerarFilasDureza();
            mergeSelectionAnchor = null;
            guardarEstadoMerges();
        });

        $mergeButton.on('click', function() {
            combinarCeldasSeleccionadas();
        });

        $splitButton.on('click', function() {
            separarCeldasSeleccionadas();
        });

        $tbody.on('click', '.btnEliminarDureza', function() {
            descombinarTodasLasCeldas();
            if ($tbody.find('tr').length === 1) {
                $(this).closest('tr').find('input').val('');
                return;
            }

            $(this).closest('tr').remove();
            renumerarFilasDureza();
            mergeSelectionAnchor = null;
            guardarEstadoMerges();
        });
    }

    /*FOR-PIMP-02_B_04*/
    document.addEventListener('DOMContentLoaded', function () {
        configurarTablaDurezaBrinell();

        const form = document.getElementById('FOR-PIMP-02_B_04');
        if (!form) return;
        const preFormBtn = document.getElementById('preFormBtn');

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('FOR-PIMP-02_B_03_Form_' + el.name, el.value);
            //});
        //});

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-PIMP-02_B_04_Form_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-PIMP-02_B_04_Form_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            guardarEstadoMerges();
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-PIMP-02_B_04_Form_' + el.name);
                //localStorage.clear();
            });
        });

        if (preFormBtn) {
            preFormBtn.addEventListener('click', function () {
                form.querySelectorAll('input, textarea, select').forEach(function (field) {
                    if (field.disabled || field.readOnly || !field.name) return;

                    const type = (field.type || '').toLowerCase();

                    if (['hidden', 'file', 'checkbox', 'radio', 'button', 'submit'].includes(type)) {
                        return;
                    }

                    if (field.tagName === 'SELECT') {
                        if ((field.value || '').trim() !== '') {
                            return;
                        }

                        const firstValidOption = Array.from(field.options).find(function (option) {
                            return option.value !== '' && !option.disabled;
                        });

                        if (firstValidOption) {
                            field.value = firstValidOption.value;
                            localStorage.setItem('FOR-PIMP-02_B_04_Form_' + field.name, field.value);
                        }
                        return;
                    }

                    if ((field.value || '').trim() !== '') {
                        return;
                    }

                    if (type === 'date') {
                        field.value = new Date().toISOString().split('T')[0];
                    } else {
                        field.value = '---';
                    }

                    localStorage.setItem('FOR-PIMP-02_B_04_Form_' + field.name, field.value);
                });

                $('#durezaBrinellBody .mergeable-cell[rowspan]').each(function () {
                    sincronizarCeldasCombinadas($(this));
                });

                guardarEstadoMerges();
            });
        }
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
@endsection
