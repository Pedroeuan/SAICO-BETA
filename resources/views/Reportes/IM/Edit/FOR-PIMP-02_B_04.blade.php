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

        /* Encabezado blanco y compacto como las tablas de captura del sistema. */
        .tabla-dureza thead th {
            background-color: #fff !important;
            color: #111 !important;
            font-weight: 600;
            padding: 8px 6px;
            vertical-align: middle;
        }

        .tabla-dureza input {
            height: 30px;
            padding: 4px 6px;
            text-align: center;
            border: 1px solid #adb5bd;
            border-radius: 4px;
        }

        /*
         * El alternado se limita a las mediciones. Descripcion, horario y
         * observaciones permanecen blancos porque pueden abarcar varias filas.
         */
        #durezaBrinellBody > tr:nth-child(even) > td[data-merge-field="metal_base_a"]:not(.selected-merge):not(.merge-anchor):not(.merge-preview),
        #durezaBrinellBody > tr:nth-child(even) > td[data-merge-field="zac_b"]:not(.selected-merge):not(.merge-anchor):not(.merge-preview),
        #durezaBrinellBody > tr:nth-child(even) > td[data-merge-field="soldadura_c"]:not(.selected-merge):not(.merge-anchor):not(.merge-preview),
        #durezaBrinellBody > tr:nth-child(even) > td[data-merge-field="zac_b1"]:not(.selected-merge):not(.merge-anchor):not(.merge-preview),
        #durezaBrinellBody > tr:nth-child(even) > td[data-merge-field="metal_base_a1"]:not(.selected-merge):not(.merge-anchor):not(.merge-preview),
        #durezaBrinellBody > tr:nth-child(even) > td.numero-fila,
        #durezaBrinellBody > tr:nth-child(even) > td:last-child {
            --bs-table-bg: #e2e6ea;
            --bs-table-accent-bg: #e2e6ea;
            background-color: #e2e6ea !important;
        }

        #durezaBrinellBody > tr > td:not(.selected-merge):not(.merge-anchor):not(.merge-preview) input {
            background-color: #ffffff !important;
        }

        .tabla-plantilla-dureza th,
        .tabla-plantilla-dureza td {
            border: 1px solid #000 !important;
            font-size: 11px;
            padding: 3px;
            vertical-align: middle;
        }

        .tabla-plantilla-dureza input {
            height: 24px;
            padding: 2px;
            text-align: center;
            border: 1px solid #ced4da;
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
                    <button id="preFormBtn" type="button" class="btn btn-warning my-2">Rellenar Campos Vacios "---"</button>
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
                        $usarPlantillaDureza = empty($durezaRows);
                        $filaDurezaVacia = [
                            'descripcion' => '',
                            'horario' => '',
                            'metal_base_a' => '',
                            'zac_b' => '',
                            'soldadura_c' => '',
                            'zac_b1' => '',
                            'metal_base_a1' => '',
                            'observaciones' => '',
                        ];
                        $durezaMergePredeterminada = [
                            ['startRow' => 0, 'field' => 'descripcion', 'rowspan' => 20],
                            ['startRow' => 0, 'field' => 'horario', 'rowspan' => 5],
                            ['startRow' => 5, 'field' => 'horario', 'rowspan' => 5],
                            ['startRow' => 10, 'field' => 'horario', 'rowspan' => 5],
                            ['startRow' => 15, 'field' => 'horario', 'rowspan' => 5],
                            ['startRow' => 0, 'field' => 'observaciones', 'rowspan' => 20],
                        ];

                        if ($usarPlantillaDureza) {
                            // Solo los reportes sin filas guardadas reciben la plantilla inicial.
                            $durezaRows = array_fill(0, 20, $filaDurezaVacia);
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
                                <th>METAL BASE<br>Base Metal<br>(A1)</th>
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
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded mb-2">ANTES O DESPUÉS DEL RELEVADO DE ESFUERZOS</div>
                    <div class="alert alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-info"></i> Información</h5>
                        <p> <b>Activa la combinación, selecciona la primera y la última celda del rango. Para separar, selecciona la celda combinada.</b>
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

                        $mergeConfigGuardada = isset($Reporte)
                            ? ($Reporte->dureza_merge_config ?? ($Datos_Equipo['DUREZA_MERGE_CONFIG'] ?? '[]'))
                            : ($Datos_Equipo['DUREZA_MERGE_CONFIG'] ?? '[]');
                        $mergeConfigRecibida = old('Dureza_MergeConfig', $mergeConfigGuardada);

                        if ($usarPlantillaDureza && ($mergeConfigRecibida === null || $mergeConfigRecibida === '' || $mergeConfigRecibida === '[]')) {
                            $mergeConfigRecibida = $durezaMergePredeterminada;
                        }

                        $durezaMergeInitial = $normalizeMergeConfigView($mergeConfigRecibida);
                    @endphp
                    <div class="table-responsive mb-3">
    <input type="hidden" name="Dureza_MergeConfig" id="durezaMergeConfig" value="{{ $durezaMergeInitial }}">
    <div class="toolbar-help">
        Activa la combinación, selecciona la primera y la última celda del rango. Para separar, selecciona la celda combinada.
        <span id="durezaMergeSelectionInfo" class="text-primary fw-semibold ms-2"></span>
    </div>
    <div class="toolbar-divider"></div>
    <div class="table-responsive mb-2">
        <table class="table table-bordered align-middle text-center tabla-dureza" id="tablaDurezaBrinell">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 60px;">No.</th>
                                    <th colspan="2">DATOS DE LA JUNTA</th>
                                    <th colspan="5">VALORES DE DUREZA (ESCALA BRINELL)</th>
                                    <th rowspan="2" style="min-width: 220px;">OBSERVACIONES</th>
                                    <th rowspan="2" style="width: 80px;">Eliminar</th>
                                </tr>
                                <tr>
                                    <th style="min-width: 210px;">DESCRIPCIÓN<br></th>
                                    <th style="min-width: 130px;">HORARIOS TÉCNICOS</th>
                                    <th style="min-width: 125px;">METAL BASE<br><small>Base Metal</small><br>(A)</th>
                                    <th style="min-width: 125px;">ZAC<br><small>HAZ</small><br>(B)</th>
                                    <th style="min-width: 125px;">SOLDADURA<br><small>Weld</small><br>(C)</th>
                                    <th style="min-width: 125px;">ZAC<br><small>HAZ</small><br>(B1)</th>
                                    <th style="min-width: 125px;">METAL BASE<br><small>Base Metal</small><br>(A1)</th>
                                </tr>
                            </thead>
                            <tbody id="durezaAutoFillBody">
                                <tr>
                                    <td></td>
                                    <td><input type="text" class="form-control inputForm" data-auto-fill-field="descripcion"></td>
                                    <td><input type="text" class="form-control inputForm" data-auto-fill-field="horario"></td>
                                    <td><input type="text" class="form-control inputForm" data-auto-fill-field="metal_base_a"></td>
                                    <td><input type="text" class="form-control inputForm" data-auto-fill-field="zac_b"></td>
                                    <td><input type="text" class="form-control inputForm" data-auto-fill-field="soldadura_c"></td>
                                    <td><input type="text" class="form-control inputForm" data-auto-fill-field="zac_b1"></td>
                                    <td><input type="text" class="form-control inputForm" data-auto-fill-field="metal_base_a1"></td>
                                    <td><input type="text" class="form-control inputForm" data-auto-fill-field="observaciones"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody id="durezaBrinellBody">@foreach($durezaRows as $index => $row)
                                <tr>
                                    <td class="numero-fila">{{ $index + 1 }}</td>
                                    <td class="mergeable-cell" data-merge-field="descripcion">
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][descripcion]" value="{{ $row['descripcion'] ?? '' }}">
                                    </td>
                                    <td class="mergeable-cell" data-merge-field="horario">
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][horario]" value="{{ $row['horario'] ?? '' }}">
                                    </td>
                                    <td class="mergeable-cell" data-merge-field="metal_base_a">
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][metal_base_a]" value="{{ $row['metal_base_a'] ?? '' }}">
                                    </td>
                                    <td class="mergeable-cell" data-merge-field="zac_b">
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][zac_b]" value="{{ $row['zac_b'] ?? '' }}">
                                    </td>
                                    <td class="mergeable-cell" data-merge-field="soldadura_c">
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][soldadura_c]" value="{{ $row['soldadura_c'] ?? '' }}">
                                    </td>
                                    <td class="mergeable-cell" data-merge-field="zac_b1">
                                        <input type="text" class="form-control inputForm" name="Dureza[{{ $index }}][zac_b1]" value="{{ $row['zac_b1'] ?? '' }}">
                                    </td>
                                    <td class="mergeable-cell" data-merge-field="metal_base_a1">
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
        <div>
            <label for="numRows" class="toolbar-label">Número de filas:</label>
            <select id="numRows" class="form-select toolbar-select">
                @for ($i = 1; $i <= 500; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="toolbar-actions">
            <button id="addDurezaRowsBtn" type="button" class="btn btn-success custom-btn">Agregar fila</button>
            <button id="fillEmptyDurezaBtn" type="button" class="btn btn-warning custom-btn">Rellenar vacíos "---"</button>
            <button id="toggleCombinacionBtn" type="button" class="btn btn-success custom-btn">Activar combinación</button>
        </div>
    </div>

                    <div class="col-12">
                        <div class="form-group">
                        <label class="col-form-label" for="observacionesEquipo">Conclusión:</label>
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
                                            <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[Realizo]" placeholder="Ejemplo: Realizó" value="{{ old('Realizo', $Firmas['Realizo'] ?? 'Realizó') }}"></th>
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
                        </div>

                        <div data-layout-fotos-manual="1">
                        @if(!empty($Fotos_Comentarios))
                            <div class="row">
                                @foreach($Fotos_Comentarios as $index => $foto)
                                    <div class="col-sm-6"
                                        id="image-container-{{ $index }}"
                                        data-foto-pagina="{{ $foto['pagina'] ?? (intdiv($index, 4) + 1) }}"
                                        data-foto-posicion="{{ $foto['posicion'] ?? (!empty($foto['una_hoja']) ? 'pagina_completa' : ['arriba_izquierda', 'arriba_derecha', 'abajo_izquierda', 'abajo_derecha'][$index % 4]) }}"
                                        data-foto-hoja-completa="{{ !empty($foto['una_hoja']) ? 1 : 0 }}">
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
<script src="{{ asset('js/Reportes_Fotos_Posicionables_02_B_04.js') }}"></script>
<script src="{{ asset('js/Reportes_Dureza_Promedio_02_B_04.js') }}"></script>
<script src="{{ asset('js/Reportes_CombinacionCeldas.js') }}"></script>
<script src="{{ asset('js/Reportes_Edit-FOR-PIMP-02_B_04.js') }}?v={{ filemtime(public_path('js/Reportes_Edit-FOR-PIMP-02_B_04.js')) }}"></script>
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

document.addEventListener('DOMContentLoaded', function () {
    configurarMetodoYEquipo0204Edit();
});
</script>
@endsection






