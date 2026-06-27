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

        .mergeable-cell {
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .mergeable-cell:hover {
            background-color: #eef6ff !important;
        }

        .mergeable-cell.selected-merge {
            background-color: #ffe3e3 !important;
            box-shadow: inset 0 0 0 2px #dc3545, 0 0 0 1px #dc3545;
        }

        .mergeable-cell.selected-merge input {
            background-color: #ffd6d6 !important;
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.2) !important;
        }

        .mergeable-cell.merge-anchor {
            background-color: #d9ecff !important;
            box-shadow: inset 0 0 0 2px #0d6efd, 0 0 0 1px #0d6efd !important;
            transform: scale(1.01);
        }

        .mergeable-cell.merge-anchor input {
            background-color: #eef6ff !important;
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.18) !important;
        }

        .mergeable-cell.merge-preview {
            background-color: #fff3cd !important;
            box-shadow: inset 0 0 0 2px #f0ad4e, 0 0 0 1px #f0ad4e !important;
        }

        .mergeable-cell.merge-preview input {
            background-color: #fff8db !important;
        }

        .tabla-toolbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 18px;
            margin-bottom: 8px;
            padding: 8px 0;
            flex-wrap: wrap;
        }

        .toolbar-left {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 130px;
        }

        .toolbar-label {
            font-weight: 600;
            margin-bottom: 0;
            font-size: 13px;
        }

        .toolbar-select {
            width: 95px;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 16px;
            flex: 1;
            flex-wrap: wrap;
        }

        .toolbar-actions .btn {
            white-space: nowrap;
        }

        .toolbar-help {
            font-size: 12px;
            color: #6c757d;
            margin-top: 2px;
            margin-bottom: 6px;
        }

        .toolbar-divider {
            width: 100%;
            height: 2px;
            background-color: #0d6efd;
            opacity: 0.8;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .tabla-toolbar {
                align-items: flex-start;
            }

            .toolbar-actions {
                justify-content: flex-start;
                gap: 8px;
            }
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
            <form id="FOR-PIMP-02_B_04" action="{{route('Reportes_FOR_PIMP_02_B_04.update', $id)}}" method="post" enctype="multipart/form-data">
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
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[No_Isometrico]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.No_Isometrico', $Detalles_Generales['No_Isometrico'] ?? '')}}">
                            @error('No_Isometrico')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Nombre de la Pieza:</label>
                            <input type="text" class="form-control inputForm @error('Nom_Pieza') is-invalid @enderror" name="Detalles_Generales[Nom_Pieza]" placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Nom_Pieza', $Detalles_Generales['Nom_Pieza'] ?? '')}}">
                            @error('Nom_Pieza')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Material</label>
                            <input type="text" class="form-control inputForm @error('Material') is-invalid @enderror" name="Detalles_Generales[Material]" placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Material', $Detalles_Generales['Material'] ?? '')}}">
                            @error('Material')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Procedimiento:</label>
                            <input type="text" class="form-control inputForm @error('Procedimiento') is-invalid @enderror" name="Detalles_Generales[Procedimiento]" placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Procedimiento', $Detalles_Generales['Procedimiento'] ?? '')}}">
                            @error('Procedimiento')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Criterio de Evaluación:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Criterio_Evaluacion]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Criterio_Evaluacion', $Detalles_Generales['Criterio_Evaluacion'] ?? '')}}">
                            @error('Criterio_Evaluacion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Trazabilidad:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Trazabilidad]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Trazabilidad', $Detalles_Generales['Trazabilidad'] ?? '')}}">
                            @error('Trazabilidad')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">No. Junta:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[No_Junta]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.No_Junta', $Detalles_Generales['No_Junta'] ?? '')}}">
                            @error('No_Junta')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Temperatura de la Pieza:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Temperatura_pieza]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Temperatura_pieza', $Detalles_Generales['Temperatura_pieza'] ?? '')}}">
                            @error('Temperatura_pieza')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Espesor/Cédula:</label>
                            <input type="text" class="form-control inputForm is-waning" id="inputSuccess" name="Detalles_Generales[Espesor_cedula]" placeholder="Ejemplo:" value="{{old('Detalles_Generales.Espesor_cedula', $Detalles_Generales['Espesor_cedula'] ?? '')}}">
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
                            $posiblesCampos = [data_get($equipo, 'Metodo_Medicion'), data_get($equipo, 'Metodo'), data_get($equipo, 'Nombre_E_P_BP'), data_get($equipo, 'Modelo'), data_get($equipo, 'Marca'), data_get($equipo, 'Serie')];
                            foreach ($posiblesCampos as $valorCampo) {
                                $texto = mb_strtoupper(trim((string) $valorCampo));
                                if ($texto === '') { continue; }
                                if (str_contains($texto, 'LEEB')) { return 'LEEB'; }
                                if (str_contains($texto, 'UCI')) { return 'UCI'; }
                            }
                            return '';
                        };
                        $metodosEquipo = collect($idsGeneral_EyCs_Equipos ?? [])->map($resolverMetodoEquipo)->filter()->unique()->values();
                        if ($metodosEquipo->isEmpty() && collect($idsGeneral_EyCs_Equipos ?? [])->isNotEmpty()) { $metodosEquipo = collect(['LEEB', 'UCI']); }
                        $durezaPromedio = old('Dureza', $Datos_Equipo['DUREZA_PROMEDIO'] ?? []);
                        $durezaRows = collect(old('Dureza', $Datos_Equipo['DUREZA_ROWS'] ?? []))->filter(function ($row, $key) { return is_numeric($key) && is_array($row); })->values()->all();
                        if (empty($durezaRows)) {
                            $durezaRows = [[ 'descripcion' => '', 'horario' => '', 'metal_base_a' => '', 'zac_b' => '', 'soldadura_c' => '', 'zac_b1' => '', 'metal_base_a1' => '', 'observaciones' => '' ]];
                        }
                    @endphp

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <select class="form-select inputForm" id="metodoSelectE" name="Detalles_Generales[Metodo]">
                                <option value="" selected>Seleccione un Método</option>
                                @foreach($metodosEquipo as $metodo)
                                    <option value="{{ $metodo }}" @selected(old('Detalles_Generales.Metodo', $Detalles_Generales['Metodo'] ?? '') == $metodo)>{{ $metodo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-center">
                            <select class="form-select inputForm" name="equipos" id="equiposSelect">
                                <option value="" selected>Seleccione un Equipo</option>
                                @foreach($idsGeneral_EyCs_Equipos as $equipo)
                                    @php $metodoEquipo = $resolverMetodoEquipo($equipo); @endphp
                                    <option value="{{ $equipo->idGeneral_EyC }}" @selected(old('equipos', $Datos_Equipo['ID_EQUIPO'] ?? '') == $equipo->idGeneral_EyC) data-marca="{{ $equipo->Marca }}" data-modelo="{{ $equipo->Modelo }}" data-ns="{{ $equipo->Serie }}" data-metodo="{{ $metodoEquipo }}">{{ $equipo->Nombre_E_P_BP }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="Datos_Equipo[ID_EQUIPO]" id="IDInputE" value="{{ old('Datos_Equipo.ID_EQUIPO', $Datos_Equipo['ID_EQUIPO'] ?? '') }}">
                        </div>
                    </div>

                    <div class="col-sm-4"><div class="form-group"><label class="col-form-label" for="inputSuccess">MARCA:</label><input type="text" class="form-control  inputForm" id="marcaInputE" name="Datos_Equipo[MARCA_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.MARCA_EQUIPO', $Datos_Equipo['MARCA_EQUIPO'] ?? '')}}"></div></div>
                    <div class="col-sm-4"><div class="form-group"><label class="col-form-label" for="inputSuccess">MODELO:</label><input type="text" class="form-control  inputForm" id="modeloInputE" name="Datos_Equipo[MODELO_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.MODELO_EQUIPO', $Datos_Equipo['MODELO_EQUIPO'] ?? '')}}"></div></div>
                    <div class="col-sm-4"><div class="form-group"><label class="col-form-label" for="inputSuccess">N.S:</label><input type="text" class="form-control  inputForm" id="nsInputE" name="Datos_Equipo[NS_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.NS_EQUIPO', $Datos_Equipo['NS_EQUIPO'] ?? '')}}"></div></div>

                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded mb-2">VALORES PROMEDIO DE DUREZAS</div>
                    <table class="table table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th style="width:25%;">VALORES PROMEDIO DE DUREZAS:<br><small>Average Hardness Values</small>
                                </th>
                                <th>METAL BASE<br>Base Metal<br>(A)</th>
                                <th>ZAC<br>HAZ (B)</th>
                                <th>SOLDADURA<br>Welding<br>(C)</th>
                                <th>ZAC<br>HAZ (B1)</th>
                                <th>METAL BASE<br>Base Metal<br>(B)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>ANTES DEL RELEVADO DE ESFUERZOS (HB):</strong><br>Before PWHT (HB)</td>
                                    <td><input type="text" class="form-control" name="Dureza[ANTES_A]" value="{{ old('Dureza.ANTES_A', $durezaPromedio['ANTES_A'] ?? '') }}"></td>
                                    <td><input type="text" class="form-control" name="Dureza[ANTES_B]" value="{{ old('Dureza.ANTES_B', $durezaPromedio['ANTES_B'] ?? '') }}"></td>
                                    <td><input type="text" class="form-control" name="Dureza[ANTES_C]" value="{{ old('Dureza.ANTES_C', $durezaPromedio['ANTES_C'] ?? '') }}"></td>
                                    <td><input type="text" class="form-control" name="Dureza[ANTES_B1]" value="{{ old('Dureza.ANTES_B1', $durezaPromedio['ANTES_B1'] ?? '') }}"></td>
                                    <td><input type="text" class="form-control" name="Dureza[ANTES_BM]" value="{{ old('Dureza.ANTES_BM', $durezaPromedio['ANTES_BM'] ?? '') }}"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>POSTERIOR AL RELEVADO DE ESFUERZOS (HB):</strong>
                                        <br>After PWHT (HB)</td>
                                        <td>
                                            <input type="text" class="form-control" name="Dureza[DESPUES_A]" value="{{ old('Dureza.DESPUES_A', $durezaPromedio['DESPUES_A'] ?? '') }}"></td>
                                            <td>
                                                <input type="text" class="form-control" name="Dureza[DESPUES_B]" value="{{ old('Dureza.DESPUES_B', $durezaPromedio['DESPUES_B'] ?? '') }}"></td>
                                                <td><input type="text" class="form-control" name="Dureza[DESPUES_C]" value="{{ old('Dureza.DESPUES_C', $durezaPromedio['DESPUES_C'] ?? '') }}"></td>
                                                <td><input type="text" class="form-control" name="Dureza[DESPUES_B1]" value="{{ old('Dureza.DESPUES_B1', $durezaPromedio['DESPUES_B1'] ?? '') }}"></td>
                                                <td><input type="text" class="form-control" name="Dureza[DESPUES_BM]" value="{{ old('Dureza.DESPUES_BM', $durezaPromedio['DESPUES_BM'] ?? '') }}"></td>
                                            </tr>
                                        </tbody>
                    </table>
                    <div class="alert alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-info"></i> Información</h5>
                        <p> <b>Selecciona celdas solo de Descripcion, Horario u Observaciones.</b>
                        </p>                 
                    </div>
                    
                    @php
                        $normalizeMergeConfigView = function ($config) {
                            if (is_string($config)) {
                                $decoded = json_decode($config, true);
                                $config = is_array($decoded) ? $decoded : [];
                            }

                            if (!is_array($config)) {
                                return '[]';
                            }

                            $normalized = [];

                            foreach ($config as $merge) {
                                if (!is_array($merge)) {
                                    continue;
                                }

                                $row = isset($merge['row']) ? (int) $merge['row'] : (isset($merge['startRow']) ? (int) $merge['startRow'] : -1);
                                $rowspan = isset($merge['rowspan']) ? (int) $merge['rowspan'] : 1;
                                $field = (string) ($merge['field'] ?? '');

                                if ($row < 0 || $rowspan < 2 || $field === '') {
                                    continue;
                                }

                                $normalized[$row . '|' . $field] = [
                                    'row' => $row,
                                    'field' => $field,
                                    'rowspan' => $rowspan,
                                ];
                            }

                            return json_encode(array_values($normalized));
                        };

                        $durezaMergeInitial = $normalizeMergeConfigView(old('Dureza_MergeConfig', isset($Reporte) ? ($Reporte->dureza_merge_config ?? '[]') : ($Datos_Equipo['DUREZA_MERGE_CONFIG'] ?? '[]')));
                    @endphp
                    <div class="table-responsive mb-3"><input type="hidden" name="Dureza_MergeConfig" id="durezaMergeConfig" value="{{ $durezaMergeInitial }}">
                        <span id="durezaMergeSelectionInfo" class="text-primary fw-semibold ms-2"></span>
                        <table class="table table-bordered align-middle text-center tabla-dureza" id="tablaDurezaBrinell">
                            <thead class="table-light"><tr><th style="min-width: 190px;">DESCRIPCIÓN<br><small>Description</small></th>
                                <th style="min-width: 120px;">HORARIO<br><small>Schedule</small></th>
                                <th style="min-width: 120px;">METAL BASE<br><small>Base Metal</small><br>(A)</th>
                                <th style="min-width: 120px;">ZAC / HAZ<br>(B)</th>
                                <th style="min-width: 120px;">SOLDADURA<br><small>Weld</small><br>(C)</th>
                                <th style="min-width: 120px;">ZAC / HAZ<br>(B1)</th>
                                <th style="min-width: 120px;">METAL BASE<br><small>Base Metal</small><br>(A1)</th>
                                <th style="min-width: 180px;">OBSERVACIONES<br><small>Remarks</small></th>
                                <th style="width: 80px;">ACCIONES</th></tr>
                            </thead>
                            <tbody id="durezaBrinellBody">@foreach($durezaRows as $index => $row)
                                <tr>
                                    <td class="mergeable-cell" data-merge-field="descripcion">
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][descripcion]" value="{{ $row['descripcion'] ?? '' }}">
                                    </td>
                                    <td class="mergeable-cell" data-merge-field="horario">
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][horario]" value="{{ $row['horario'] ?? '' }}">
                                    </td>
                                    <td><input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][metal_base_a]" value="{{ $row['metal_base_a'] ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][zac_b]" value="{{ $row['zac_b'] ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][soldadura_c]" value="{{ $row['soldadura_c'] ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][zac_b1]" value="{{ $row['zac_b1'] ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][metal_base_a1]" value="{{ $row['metal_base_a1'] ?? '' }}">
                                    </td>
                                    <td class="mergeable-cell" data-merge-field="observaciones">
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][observaciones]" value="{{ $row['observaciones'] ?? '' }}">
                                    </td>
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
                    <div class="d-flex justify-content-between align-items-center w-100 mb-3">

                    <div class="d-flex align-items-center">
                        <label for="numRows" class="me-2 mb-0" style="white-space: nowrap;">
                            Número de filas:
                        </label>

                        <select id="numRows" class="form-select toolbar-select">
                            @for ($i = 1; $i <= 500; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button id="addDurezaRowsBtn" type="button" class="btn btn-success custom-btn">
                            Agregar fila
                        </button>

                        <button id="preFormBtn" type="button" class="btn btn-warning custom-btn">
                            Rellenar vacíos "---"
                        </button>

                        <button id="mergeSelectedCellsBtn" type="button" class="btn btn-primary custom-btn">
                            Combinar celdas
                        </button>

                        <button id="splitSelectedCellsBtn" type="button" class="btn btn-primary custom-btn">
                            Separar celdas
                        </button>
                    </div>

                </div>
                    
                </div>
                    <div class="col-12">
                        <div class="form-group">
                        <label class="col-form-label" for="observacionesEquipo">Observaciones:</label>
                        <textarea class="form-control is-waning" id="observacionesEquipo" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{ old('Datos_Equipo.Observaciones', $Datos_Equipo['Observaciones'] ?? '') }}</textarea>
                        </div>
                    </div>
<!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->

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
                                            <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[Realizo]" placeholder="Ejemplo: Realizó" value="{{ old('Realizo', $Firmas['Realizo'] ?? 'Realiz�') }}"></th>
                                        </tr>

                                        <tr>
                                            <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                        </tr>

                                        <tr>
                                            <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[NOMBRE_TECNICO]" placeholder="Ejemplo: NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}"></td>
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
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Realizo]" placeholder="Ejemplo: Realizó" value="{{ old('Realizo', $Firmas['Realizo'] ?? 'Realiz�') }}"></th>
                                        <td style="width: 30px;"></td>
                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="{{ old('Vobo1', $Firmas['Vobo1'] ?? 'Vo.Bo.') }}"></th>
                                    </tr>

                                    <tr>
                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                        <td></td>
                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                    </tr>

                                    <tr>
                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_TECNICO]" placeholder="Ejemplo: NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}"></td>
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

                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Realizo]" placeholder="Ejemplo: Realizó" value="{{ old('Realizo', $Firmas['Realizo'] ?? 'Realiz�') }}"></th>
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
                        <div id="firmas4" class="col-12" style="display: {{ ($Firmas['numFirmas'] ?? 1) == 4 ? 'block' : 'none' }};">
                            <table class="table table-bordered table-striped dt-responsive tablas">
                                <thead>
                                    <tr>

                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Realizo]" placeholder="Ejemplo: Realizó" value="{{ old('Realizo', $Firmas['Realizo'] ?? 'Realiz�') }}"></th>
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

                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS SOLDADOR</div>
                        
                        <p>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Num. de Soldador:</label>
                                <input type="text" class="form-control  inputForm @error('Num_Soldador') is-invalid @enderror" name="Detalles_Generales[Num_Soldador]"  placeholder="Ejemplo: 12345" value="{{old('Detalles_Generales.Num_Soldador', $Detalles_Generales['Num_Soldador'] ?? '')}}">
                                @error('Num_Soldador')
                                        <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Nombre soldador/Iniciales:</label>
                                <input type="text" class="form-control  inputForm @error('Nombre_Soldador') is-invalid @enderror" name="Detalles_Generales[Nombre_Soldador]"  placeholder="Ejemplo: Juan Pérez" value="{{old('Detalles_Generales.Nombre_Soldador', $Detalles_Generales['Nombre_Soldador'] ?? '')}}">
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
<script src="{{ asset('js/Reportes_Edit.js') }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('FOR-PIMP-02_B_04');
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

    /*FOR-PIMP-02_B_04*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-PIMP-02_B_04');
        if (!form) return;

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('FOR-PIMP-02_B_04_Form_' + el.name, el.value);
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
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-PIMP-02_B_04_Form_' + el.name);
                //localStorage.clear();
            });
        });
    });

function configurarMetodoYEquipo0204Edit() {
    const $metodo = $('#metodoSelectE');
    const $equipo = $('#equiposSelect');
    const $marca = $('#marcaInputE');
    const $modelo = $('#modeloInputE');
    const $ns = $('#nsInputE');
    const $idEquipo = $('#IDInputE');
    const form = document.getElementById('FOR-PIMP-02_B_04');
    const formId = form ? form.id : 'FOR-PIMP-02_B_04';
    const opcionesEquipo = $equipo.find('option').clone();

    function limpiarCamposEquipo() {
        $marca.val('');
        $modelo.val('');
        $ns.val('');
        $idEquipo.val('');
    }

    function filtrarEquipos(metodoSeleccionado) {
        $equipo.empty().append('<option value="">Seleccione un Equipo</option>');
        opcionesEquipo.each(function() {
            const $opcion = $(this);
            const valor = $opcion.attr('value');
            if (!valor) return;
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
    }

    const metodoGuardado = form.elements['Detalles_Generales[Metodo]']?.value || localStorage.getItem(formId + '_metodo_equipo') || '';
    const equipoGuardado = form.elements['Datos_Equipo[ID_EQUIPO]']?.value || localStorage.getItem(formId + '_equipos') || '';

    if (metodoGuardado) $metodo.val(metodoGuardado);
    filtrarEquipos($metodo.val());
    if (equipoGuardado && $equipo.find('option[value="' + equipoGuardado + '"]').length) {
        $equipo.val(equipoGuardado);
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

function construirFilaDureza0204Edit(index, data = {}) {
    return `<tr>
        <td class="mergeable-cell" data-merge-field="descripcion"><input type="text" class="form-control inputForm" name="Dureza[${index}][descripcion]" value="${data.descripcion || ''}"></td>
        <td class="mergeable-cell" data-merge-field="horario"><input type="text" class="form-control inputForm" name="Dureza[${index}][horario]" value="${data.horario || ''}"></td>
        <td><input type="text" class="form-control inputForm" name="Dureza[${index}][metal_base_a]" value="${data.metal_base_a || ''}"></td>
        <td><input type="text" class="form-control inputForm" name="Dureza[${index}][zac_b]" value="${data.zac_b || ''}"></td>
        <td><input type="text" class="form-control inputForm" name="Dureza[${index}][soldadura_c]" value="${data.soldadura_c || ''}"></td>
        <td><input type="text" class="form-control inputForm" name="Dureza[${index}][zac_b1]" value="${data.zac_b1 || ''}"></td>
        <td><input type="text" class="form-control inputForm" name="Dureza[${index}][metal_base_a1]" value="${data.metal_base_a1 || ''}"></td>
        <td class="mergeable-cell" data-merge-field="observaciones"><input type="text" class="form-control inputForm" name="Dureza[${index}][observaciones]" value="${data.observaciones || ''}"></td>
        <td><button type="button" class="btn btn-danger btn-sm btnEliminarDureza"><i class="fa fa-times" aria-hidden="true"></i></button></td>
    </tr>`;
}

function configurarTablaDureza0204Edit() {
    const $tbody = $('#durezaBrinellBody');
    const $addButton = $('#addDurezaRowsBtn');
    const $fillEmptyButton = $('#fillEmptyDurezaBtn');
    const $numRows = $('#numRows');
    const $mergeButton = $('#mergeSelectedCellsBtn');
    const $splitButton = $('#splitSelectedCellsBtn');
    let mergeSelectionAnchor = null;
    let mergeState = [];

    function renumerarFilasDureza() {
        $tbody.find('tr').each(function(index) {
            $(this).find('input').each(function() {
                const currentName = $(this).attr('name') || '';
                $(this).attr('name', currentName.replace(/Dureza\[\d+\]/, 'Dureza[' + index + ']'));
            });
        });
    }

    function limpiarSeleccionMerge() {
        $tbody.find('.mergeable-cell').removeClass('selected-merge merge-preview merge-anchor');
        $('#durezaMergeSelectionInfo').text('');
    }

    function obtenerTotalFilasDureza() {
        return $tbody.find('tr').length;
    }

    function obtenerClaveMerge(item) {
        return `${item.field}|${item.startRow}`;
    }

    function normalizarMergeState(state) {
        // Depura merges inválidos, fuera de rango o traslapados antes de aplicar la vista.
        const totalRows = obtenerTotalFilasDureza();
        const normalized = [];
        const occupied = {};

        (Array.isArray(state) ? state : [])
            .map(function(item) {
                return {
                    field: item?.field || '',
                    startRow: Number(item?.startRow ?? item?.row),
                    rowspan: Number(item?.rowspan)
                };
            })
            .sort(function(a, b) {
                if (a.field === b.field) {
                    return a.startRow - b.startRow;
                }

                return a.field.localeCompare(b.field);
            })
            .forEach(function(item) {
                if (!item.field || item.startRow < 0 || item.rowspan < 2) {
                    return;
                }

                if ((item.startRow + item.rowspan) > totalRows) {
                    return;
                }

                const mergeEnd = item.startRow + item.rowspan - 1;
                occupied[item.field] = occupied[item.field] || [];

                const overlaps = occupied[item.field].some(function(range) {
                    return item.startRow <= range.end && mergeEnd >= range.start;
                });

                if (overlaps) {
                    return;
                }

                occupied[item.field].push({ start: item.startRow, end: mergeEnd });
                normalized.push({
                    field: item.field,
                    startRow: item.startRow,
                    rowspan: item.rowspan
                });
            });

        return normalized;
    }

    function normalizeMergeConfig(config) {
        return normalizarMergeState(config).map(function(item) {
            return {
                row: Number(item.startRow ?? item.row ?? 0),
                field: item.field,
                rowspan: Number(item.rowspan)
            };
        });
    }

    function guardarEstadoMerges() {
        mergeState = normalizarMergeState(mergeState);
        $('#durezaMergeConfig').val(JSON.stringify(normalizeMergeConfig(mergeState)));
    }

    function leerEstadoMerges() {
        const rawState = $('#durezaMergeConfig').val();

        if (!rawState) {
            mergeState = [];
            return;
        }

        try {
            mergeState = normalizarMergeState(JSON.parse(rawState));
        } catch (error) {
            mergeState = [];
        }

        guardarEstadoMerges();
    }

    function limpiarVisualMerges() {
        $tbody.find('.mergeable-cell').each(function() {
            $(this)
                .show()
                .removeAttr('rowspan')
                .removeAttr('data-merge-hidden');
        });
    }

    function obtenerCeldaDureza(rowIndex, field) {
        return $tbody.find('tr').eq(rowIndex).find('.mergeable-cell[data-merge-field="' + field + '"]');
    }

    function obtenerMergePorCelda($cell) {
        if (!$cell.length) {
            return null;
        }

        const field = $cell.data('merge-field');
        const rowIndex = $cell.closest('tr').index();

        return mergeState.find(function(item) {
            return item.field === field &&
                rowIndex >= item.startRow &&
                rowIndex < (item.startRow + item.rowspan);
        }) || null;
    }

    function sincronizarValorEnMerge(mergeItem) {
        if (!mergeItem) {
            return;
        }

        const $masterCell = obtenerCeldaDureza(mergeItem.startRow, mergeItem.field);

        if (!$masterCell.length) {
            return;
        }

        const masterValue = $masterCell.find('input').val() || '';

        for (let offset = 1; offset < mergeItem.rowspan; offset++) {
            obtenerCeldaDureza(mergeItem.startRow + offset, mergeItem.field).find('input').val(masterValue);
        }
    }

    function aplicarEstadoMerges() {
        // Reconstruye la vista usando el estado persistido del hidden input.
        limpiarVisualMerges();
        mergeState = normalizarMergeState(mergeState);

        mergeState.forEach(function(item) {
            const $masterCell = obtenerCeldaDureza(item.startRow, item.field);

            if (!$masterCell.length) {
                return;
            }

            const masterValue = $masterCell.find('input').val() || '';
            $masterCell.attr('rowspan', item.rowspan);

            for (let offset = 1; offset < item.rowspan; offset++) {
                const $childCell = obtenerCeldaDureza(item.startRow + offset, item.field);

                if (!$childCell.length) {
                    continue;
                }

                $childCell
                    .attr('data-merge-hidden', 'true')
                    .find('input')
                    .val(masterValue);

                $childCell.hide();
            }
        });

        guardarEstadoMerges();
    }

    function obtenerRangoSeleccionado($startCell, $endCell) {
        const startRow = $startCell.closest('tr').index();
        const endRow = $endCell.closest('tr').index();

        return {
            field: $startCell.data('merge-field'),
            startRow: Math.min(startRow, endRow),
            endRow: Math.max(startRow, endRow)
        };
    }

    function pintarSeleccionMerge(range) {
        limpiarSeleccionMerge();

        for (let rowIndex = range.startRow; rowIndex <= range.endRow; rowIndex++) {
            obtenerCeldaDureza(rowIndex, range.field).addClass('selected-merge merge-preview');
        }

        $('#durezaMergeSelectionInfo').text(`Rango seleccionado: ${range.field} filas ${range.startRow + 1} a ${range.endRow + 1}`);
    }

    function mostrarAlertaMerge(message, icon = 'warning') {
        Swal.fire({
            icon: icon,
            title: 'Atencion',
            text: message,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3085d6',
            background: '#ffffff',
            width: 420
        });
    }

    function obtenerCeldasSeleccionadas() {
        return $tbody.find('.mergeable-cell.selected-merge:visible');
    }

    function existeConflictoEnRango(range) {
        return mergeState.some(function(item) {
            if (item.field !== range.field) {
                return false;
            }

            const mergeEnd = item.startRow + item.rowspan - 1;
            return range.startRow <= mergeEnd && range.endRow >= item.startRow;
        });
    }

    function combinarCeldasSeleccionadas() {
        const $selected = obtenerCeldasSeleccionadas();

        if ($selected.length < 2) {
            mostrarAlertaMerge('Selecciona al menos 2 celdas consecutivas de la misma columna para combinar.');
            return;
        }

        const field = $selected.first().data('merge-field');
        const rowIndexes = $selected.map(function() {
            return $(this).closest('tr').index();
        }).get().sort(function(a, b) {
            return a - b;
        });

        const sameField = $selected.toArray().every(function(cell) {
            return $(cell).data('merge-field') === field;
        });

        const consecutive = rowIndexes.every(function(rowIndex, position) {
            return position === 0 || rowIndex === rowIndexes[position - 1] + 1;
        });

        if (!sameField || !consecutive) {
            mostrarAlertaMerge('Solo puedes combinar celdas consecutivas de una misma columna.');
            return;
        }

        const range = {
            field: field,
            startRow: rowIndexes[0],
            endRow: rowIndexes[rowIndexes.length - 1]
        };
        if (existeConflictoEnRango(range)) {
            mostrarAlertaMerge('Primero separa la combinacion actual antes de crear una nueva en ese rango.');
            return;
        }

        if (existeConflictoEnRango(range)) {
            alert('Primero separa la combinación actual antes de crear una nueva en ese rango.');
            return;
        }

        mergeState.push({
            field: field,
            startRow: range.startRow,
            rowspan: rowIndexes.length
        });

        aplicarEstadoMerges();
        limpiarSeleccionMerge();
    }

    function separarCeldasSeleccionadas() {
        const $selected = obtenerCeldasSeleccionadas();
        if ($selected.length !== 1) {
            mostrarAlertaMerge('Selecciona la celda principal de una combinacion para separarla.');
            return;
        }

        if ($selected.length !== 1) {
            alert('Selecciona la celda principal de una combinación para separarla.');
            return;
        }

        const mergeItem = obtenerMergePorCelda($selected.first());
        if (!mergeItem || mergeItem.startRow !== $selected.first().closest('tr').index()) {
            mostrarAlertaMerge('Selecciona la celda principal de una combinacion para separarla.');
            return;
        }

        if (!mergeItem || mergeItem.startRow !== $selected.first().closest('tr').index()) {
            alert('Selecciona la celda principal de una combinación para separarla.');
            return;
        }

        mergeState = mergeState.filter(function(item) {
            return obtenerClaveMerge(item) !== obtenerClaveMerge(mergeItem);
        });

        aplicarEstadoMerges();
        limpiarSeleccionMerge();
    }

    function ajustarMergesDespuesDeEliminarFila(deletedRowIndex) {
        // Ajusta el merge afectado sin perder las combinaciones de otras columnas.
        mergeState = mergeState.reduce(function(accumulator, item) {
            const mergeEnd = item.startRow + item.rowspan - 1;

            if (deletedRowIndex < item.startRow) {
                accumulator.push({
                    field: item.field,
                    startRow: item.startRow - 1,
                    rowspan: item.rowspan
                });
                return accumulator;
            }

            if (deletedRowIndex > mergeEnd) {
                accumulator.push(item);
                return accumulator;
            }

            if (item.rowspan - 1 >= 2) {
                accumulator.push({
                    field: item.field,
                    startRow: item.startRow,
                    rowspan: item.rowspan - 1
                });
            }

            return accumulator;
        }, []);
    }

    console.log('MERGE CONFIG CARGADO DESDE HIDDEN:', document.getElementById('durezaMergeConfig')?.value);
    setTimeout(function () {
        leerEstadoMerges();
        console.log('APLICANDO MERGES AL INICIAR EDIT');
        aplicarEstadoMerges();
    }, 100);

    $tbody.on('click', '.mergeable-cell', function() {
        const $cell = $(this);
        if (!$cell.is(':visible')) return;

        if (!mergeSelectionAnchor) {
            limpiarSeleccionMerge();
            $cell.addClass('selected-merge merge-anchor');
            mergeSelectionAnchor = $cell;
            return;
        }

        const sameField = mergeSelectionAnchor.data('merge-field') === $cell.data('merge-field');
        const sameRow = mergeSelectionAnchor.closest('tr').index() === $cell.closest('tr').index();
        if (!sameField) {
            limpiarSeleccionMerge();
            mergeSelectionAnchor = null;
            mostrarAlertaMerge('Solo puedes seleccionar celdas de la misma columna para combinar.');
            return;
        }

        if (!sameField) {
            limpiarSeleccionMerge();
            mergeSelectionAnchor = null;
            alert('Solo puedes seleccionar celdas de la misma columna para combinar.');
            return;
        }

        if (sameRow) {
            limpiarSeleccionMerge();
            mergeSelectionAnchor = null;
            return;
        }

        pintarSeleccionMerge(obtenerRangoSeleccionado(mergeSelectionAnchor, $cell));
        mergeSelectionAnchor = null;
    });

    $tbody.on('click', '.mergeable-cell input', function(e) {
        e.stopPropagation();
        $(this).closest('.mergeable-cell').trigger('click');
    });

    $tbody.on('input', '.mergeable-cell input', function() {
        const mergeItem = obtenerMergePorCelda($(this).closest('.mergeable-cell'));
        sincronizarValorEnMerge(mergeItem);
        guardarEstadoMerges();
    });

    $tbody.on('click', '.btnEliminarDureza', function() {
        const $row = $(this).closest('tr');
        const rowIndex = $row.index();

        if ($tbody.find('tr').length === 1) {
            $row.find('input').val('');
            mergeState = [];
            aplicarEstadoMerges();
            guardarEstadoMerges();
            return;
        }

        $row.remove();
        ajustarMergesDespuesDeEliminarFila(rowIndex);
        renumerarFilasDureza();
        mergeSelectionAnchor = null;
        aplicarEstadoMerges();
        guardarEstadoMerges();
    });

    $addButton.on('click', function() {
        const amount = parseInt($numRows.val(), 10) || 1;
        let startIndex = $tbody.find('tr').length;

        for (let i = 0; i < amount; i++) {
            $tbody.append(construirFilaDureza0204Edit(startIndex + i));
        }

        renumerarFilasDureza();
        mergeSelectionAnchor = null;
        aplicarEstadoMerges();
        guardarEstadoMerges();
    });

    $fillEmptyButton.on('click', function() {
        $tbody.find('input[type="text"]').each(function() {
            if (!($.trim($(this).val() || ''))) {
                $(this).val('---');
            }
        });

        aplicarEstadoMerges();
        guardarEstadoMerges();
    });

    $mergeButton.on('click', function() {
        combinarCeldasSeleccionadas();
    });

    $splitButton.on('click', function() {
        separarCeldasSeleccionadas();
    });

    const form = document.getElementById('FOR-PIMP-02_B_04');
    form?.addEventListener('submit', function () {
        const mergeConfig = normalizeMergeConfig(mergeState);
        console.log('MERGE CONFIG ANTES DE ENVIAR:', mergeConfig);
        console.log('HIDDEN Dureza_MergeConfig:', document.getElementById('durezaMergeConfig')?.value);
        document.getElementById('durezaMergeConfig').value = JSON.stringify(mergeConfig);
        guardarEstadoMerges();
    });
}

document.addEventListener('DOMContentLoaded', function () {
    configurarMetodoYEquipo0204Edit();
    configurarTablaDureza0204Edit();
});
</script>
@endsection




