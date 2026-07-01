@extends('adminlte::page')

@section('title', 'FOR-PINS-03/02')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

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

        #inputRow th {
        text-align: center; /* Centra horizontalmente el contenido */
        vertical-align: middle; /* Centra verticalmente el contenido */
        }

        #inputRow input {
            text-align: center; /* Centra el texto dentro del input */
            margin: auto; /* Centra el input dentro de la celda */
            display: block; /* Asegura que el input se comporte como un bloque */
        }

        .mergeable-cell {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .mergeable-cell:hover {
            background-color: #e7f1ff;
        }

        .mergeable-cell.selected-merge,
        .mergeable-cell.merge-preview {
            background-color: #cfe2ff !important;
        }

        .mergeable-cell.merge-anchor {
            background-color: #9ec5fe !important;
            box-shadow: inset 0 0 0 2px #0d6efd;
        }

        .merge-help-box {
            position: relative;
            display: grid;
            gap: 12px;
            margin-bottom: 16px;
            padding: 16px 18px 16px 22px;
            border: 1px solid #f3d07a;
            border-radius: 16px;
            background:
                radial-gradient(circle at top right, rgba(255, 193, 7, 0.16), transparent 28%),
                linear-gradient(135deg, #fffaf0 0%, #fff3cd 52%, #fff8e6 100%);
            box-shadow: 0 10px 24px rgba(180, 129, 0, 0.12);
            overflow: hidden;
        }

        .merge-help-box::before {
            content: '';
            position: absolute;
            top: 12px;
            bottom: 12px;
            left: 0;
            width: 5px;
            border-radius: 0 10px 10px 0;
            background: linear-gradient(180deg, #d39e00 0%, #ffcd39 100%);
        }

        .merge-help-header {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #8a5a00;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .merge-help-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: linear-gradient(135deg, #d39e00 0%, #ffcd39 100%);
            color: #5f4300;
            box-shadow: 0 8px 18px rgba(211, 158, 0, 0.22);
            font-size: 16px;
        }

        .merge-help-text {
            color: #7a5a17;
            font-size: 13px;
            line-height: 1.55;
            max-width: 900px;
        }

        .merge-selection-info {
            min-height: 48px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(255, 253, 245, 0.92);
            border: 1px dashed #e3c16a;
        }

        .merge-selection-info:empty::before {
            content: 'Selecciona una celda inicial y otra final para previsualizar el rango de combinacion.';
            color: #9a7933;
            font-size: 12px;
        }

        .merge-selection-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            background: linear-gradient(135deg, #fff0b8 0%, #ffe08a 100%);
            color: #7a5200;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #e1bc53;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
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
                    <div class="card w-100">
                        <div class="card-body row w-100">
                            <form id="FOR-PINS-03_02" action="{{route('Reportes_FOR_PINS_03_02.store')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
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

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DE LA INSPECCIÓN</div>

                                    <div style="margin-bottom: 2px;"></div>

                                    <div class="alert alert-info alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-info"></i> Importante</h5>
                                        <p>Puedes Seleccionar una particula, contrastante y un equipo del menu o escribir directamente</p>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">PARTICULAS</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">PARTICULAS:</label>
                                            <select class="form-select inputForm" name="consumibles" id="consumiblesSelect1">
                                            <option value="" selected disabled>Seleccione una Particula</option> <!-- Opción por defecto -->
                                                @foreach($idsGeneral_EyCs_Consumibles as $Consumibles)
                                                    <option value="{{ $Consumibles->idGeneral_EyC }}"
                                                            data-marca="{{ $Consumibles->Marca }}"
                                                            data-modelo="{{ $Consumibles->Modelo }}"
                                                            data-lote="{{ $Consumibles->almacen->Lote }}">
                                                        {{ $Consumibles->Nombre_E_P_BP }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="Datos_Equipo[ID_PARTICULAS]" id="IDInputC1" value="{{ old('Datos_Equipo.ID_PARTICULAS') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" id="marcaInputC1" name="Datos_Equipo[MARCA_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.MARCA_PARTICULAS')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputC1" name="Datos_Equipo[MODELO_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.MODELO_PARTICULAS')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm" id="loteInputC1" name="Datos_Equipo[LOTE_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.LOTE_PARTICULAS')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIPO_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.TIPO_PARTICULAS')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">COLOR:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[COLOR_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.COLOR_PARTICULAS')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm"  name="Datos_Equipo[APLICACION_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.APLICACION_PARTICULAS')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">CONTRASTANTE</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">CONTRASTANTE:</label>
                                            <select class="form-select inputForm" name="consumibles" id="consumiblesSelect2">
                                            <option value="" selected disabled>Seleccione un Contrastante</option> <!-- Opción por defecto -->
                                                @foreach($idsGeneral_EyCs_Consumibles as $Consumibles)
                                                    <option value="{{ $Consumibles->idGeneral_EyC }}"
                                                            data-marca="{{ $Consumibles->Marca }}"
                                                            data-modelo="{{ $Consumibles->Modelo }}"
                                                            data-lote="{{ $Consumibles->almacen->Lote }}">
                                                        {{ $Consumibles->Nombre_E_P_BP }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="Datos_Equipo[ID_CONSTRASTANTE]" id="IDInputC2" value="{{ old('Datos_Equipo.ID_CONSTRASTANTE') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" id="marcaInputC2" name="Datos_Equipo[MARCA_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.MARCA_CONTRASTE')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputC2" name="Datos_Equipo[MODELO_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.MODELO_CONTRASTE')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm" id="loteInputC2" name="Datos_Equipo[LOTE_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.LOTE_CONTRASTE')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO:</label>
                                            <input type="text" class="form-control  inputForm" id="tipoInputC" name="Datos_Equipo[TIPO_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.TIPO_CONTRASTE')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">COLOR:</label>
                                            <input type="text" class="form-control  inputForm" id="colorInputC" name="Datos_Equipo[COLOR_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.COLOR_CONTRASTE')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm" id="aplicacionInputC" name="Datos_Equipo[APLICACION_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.APLICACION_CONTRASTE')}}">
                                        </div>
                                    </div>


                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">EQUIPO</div>

                                    <!-- Select para Equipos -->
                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">EQUIPOS:</label>
                                            <select class="form-select inputForm" name="equipos" id="equiposSelect">
                                            <option value="" selected disabled>Seleccione un equipo</option> <!-- Opción por defecto -->
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
                                            <input type="text" class="form-control  inputForm" id="nsInputE" name="Datos_Equipo[N_S_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.N_S_EQUIPO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">CORRIENTE:</label>
                                            <input type="text" class="form-control  inputForm" id="corrienteInputE" name="Datos_Equipo[CORRIENTE_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.CORRIENTE_EQUIPO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">DISTANCIA ENTRE PATAS:</label>
                                            <input type="text" class="form-control  inputForm" id="distanciaInputE" name="Datos_Equipo[DISTANCIA_PATAS_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.DISTANCIA_PATAS_EQUIPO')}}">
                                        </div>
                                    </div>

                                    <div class="alert alert-secondary" role="alert"></div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO DE LUZ:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIPO_LUZ]" placeholder="" value="{{ old('Datos_Equipo.TIPO_LUZ') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">INTENCIDAD:</label>                                            
                                            <div class="input-group">
                                                <input type="text" class="form-control  inputForm" name="Datos_Equipo[INTENCIDAD]" placeholder="" value="{{ old('Datos_Equipo.INTENCIDAD') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Lx</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">CONDICIÓN SUPERFICIAL:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[CONDICION_SUPERFICIAL]" placeholder="" value="{{old('Datos_Equipo.CONDICION_SUPERFICIAL')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TEMPERATURA DE PRUEBA:</label>                                            
                                            <div class="input-group">
                                                <input type="text" class="form-control  inputForm" name="Datos_Equipo[TEMPERATURA_PRUEBA]" placeholder="" value="{{ old('Datos_Equipo.TEMPERATURA_PRUEBA') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">°C</span>
                                                </div>
                                            </div>
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
                                                    <th rowspan="2">NO.</th>
                                                    <th rowspan="2">No. de Junta / Componente</th>
                                                    <th rowspan="2">No. Indicación</th>
                                                    <th rowspan="2">Tipo de Indicación</th>
                                                    <th colspan="3">DIM. DE INDICACIÓN</th>
                                                    <th colspan="1">LOCALIZACIÓN</th>
                                                    <th rowspan="2">Evaluación</th>
                                                    <th rowspan="2">Longitud Inspeccionada</th>
                                                    <th rowspan="2">Eliminar</th>
                                                </tr>

                                                <tr>
                                                    <th>LARGO</th>
                                                    <th>ANCHO</th>
                                                    <th>Ø</th>
                                                    <th>H.T.</th>
                                                </tr>

                                                <tr id="inputRow">
                                                    <th></th> <!-- Para ID vacío -->
                                                    <th><input type="text" class="form-control default-input" data-column="1"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="2"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="3"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="4"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="5"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="6"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="7" style="width: 120px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="8"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="9"></th>
                                                    <th></th> <!-- Para botón de eliminar -->
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <!-- Filas dinámicas aparecerán aquí -->
                                            </tbody>
                                    </table>
                                    </div>
                                    <input type="hidden" name="titulos_data" id="titulos_hidden">
                                    <input type="hidden" name="Tabla_CombinacionConfig" id="tablaCombinacionConfig" value="{{ old('Tabla_CombinacionConfig', '[]') }}">
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
                                    <div class="merge-help-box">
                                        <div class="merge-help-header">
                                            <span class="merge-help-icon"><i class="bi bi-columns-gap"></i></span>
                                            <span>Combinacion de Celdas</span>
                                        </div>
                                        <div class="merge-help-text">
                                            Puedes combinar cualquier columna de captura dentro del mismo bloque. No incluye numeracion, eliminar ni filas de longitud.
                                        </div>
                                        <div id="tablaMergeSelectionInfo" class="merge-selection-info"></div>
                                    </div>

                                    <p>

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">SIMBOLOGÍA</div>

                                    <div style="margin-bottom: 2px;"></div>

                                    <table class="table table-bordered table-striped dt-responsive tablas">
                                        <tr>
                                            <td>
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th colspan="6" class="p-2 alert alert-warning">INDICACIONES Y HALLAZGOS</th>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>NPIR:</strong></td>
                                                            <td>NO PRESENTA INDICACIÓN RELEVANTE</td>
                                                            <td><strong>DM:</strong></td>
                                                            <td>DAÑO MECÁNICO</td>
                                                            <td><strong>PT:</strong></td>
                                                            <td>POROSIDAD TUBULAR</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>G:</strong></td>
                                                            <td>GRIETA</td>
                                                            <td><strong>S:</strong></td>
                                                            <td>SOCAVADO</td>
                                                            <td><strong>C:</strong></td>
                                                            <td>CRATER</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>ZG:</strong></td>
                                                            <td>ZONA DE GRIETAS</td>
                                                            <td><strong>P:</strong></td>
                                                            <td>POROSIDAD</td>
                                                            <td><strong>IL:</strong></td>
                                                            <td>INDICACIÓN LINEAL</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>FF:</strong></td>
                                                            <td>FALTA DE FUSIÓN</td>
                                                            <td><strong>ZP:</strong></td>
                                                            <td>ZONA DE POROS</td>
                                                            <td><strong>IR:</strong></td>
                                                            <td>INDICACIÓN REDONDEADA</td>
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
     /*Juntas-Resultados */
    function restoreData() {//-----------------------------------------------------------Reemplazar todo el resotedara
        const data = JSON.parse(sessionStorage.getItem('dynamicTableData') || 'null');
        if (!data) return;

        // Helpers y configuración-CONFIGURAR CAMPOS DE ACUERDO A LOS NAMES DE CADA INPUT
        const fieldNames = [
        'no',
        'componente',
        'no_ind',
        'tipo_indicacion',
        'largo',
        'ancho',
        'diametro',
        'ht',
        'evaluacion',
        'long_insp'];
        const placeholders = { //CONFIGURAR CAMPOS DE ACUERDO A LOS PLACEHOLDERS DE CADA INPUT
            no: 'No.',
            componente: 'No. Junta/Componente', 
            no_ind: 'No.Ind.', 
            tipo_indicacion: 'Tipo de Indicación',
            largo: 'LARGO', 
            ancho: 'ANCHO', 
            diametro: 'Ø', 
            ht: 'H.T.', 
            evaluacion: 'Evaluación',
            long_insp: 'L.I.',
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
                <td colspan="11">
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
                    <td colspan="10">Longitud Inspeccionada</td>
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

            if ($rowsBlock.length >= 10) { // si hay al menos 11 filas en el bloque
                const $nfila = $rowsBlock
                    .not('.long-row')
                    .eq(9); // fila índice 9 = fila 10 (0-based)

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

        $('#addTituloBtn').click(function () {
            verificarYAgregarLongitud();
            tituloCount++;
            rowCount = 0; // Reiniciar el contador de filas para este título
            // ID único: counter + timestamp (evita duplicados aunque el texto sea igual)
            const titleId = `titulo_${tituloCount}_${Date.now()}`;

            let newTitle = `
            <tr class="titulo-row" data-titulo="${titleId}">
                <td colspan="11">
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
                <tr class="long-row" data-titulo="${lastTitle}">
                <td colspan="10"> Longitud Inspeccionada</td>
                <td>
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control w-90 long-text" name="Long_Inspecc[${lastTitle}][]"
                        placeholder="Ingrese Longitud Inspeccionada...">
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
            let numFilas = parseInt($('#numRows').val());
            // Recontar filas existentes que NO son títulos
            rowCountGlobal = $('#dynamicTable tbody tr').not('.titulo-row, .long-row').length;
            let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

            for (let i = 0; i < numFilas; i++) {
            rowCount++; // Incrementar el contador general de filas
            rowCountGlobal++; // Incrementar el contador global de filas Solo es visualmente esta variable

            let newRow = `
                <tr data-titulo="${lastTitle}">
                    <td>${rowCountGlobal} <input type="hidden" value="${rowCount}">
                    <td><input type="text" class="form-control" name="no[${lastTitle}][]" placeholder="NO" value="${rowCountGlobal}"></td>
                    <td><input type="text" class="form-control" name="componente[${lastTitle}][]" placeholder="No. Junta/Componente"></td>
                    <td><input type="text" class="form-control" name="no_ind[${lastTitle}][]" placeholder="No.Ind."></td>
                    <td><input type="text" class="form-control" name="tipo_indicacion[${lastTitle}][]" placeholder="Tipo de Indicación"></td>
                    <td><input type="text" class="form-control" name="largo[${lastTitle}][]" placeholder="LARGO"></td>
                    <td><input type="text" class="form-control" name="ancho[${lastTitle}][]" placeholder="ANCHO"></td>
                    <td><input type="text" class="form-control" name="diametro[${lastTitle}][]" placeholder="Ø"></td>
                    <td><input type="text" class="form-control" name="ht[${lastTitle}][]" placeholder="H.T."></td>
                    <td><input type="text" class="form-control" name="evaluacion[${lastTitle}][]" placeholder="Evaluación"></td>
                    <td><input type="text" class="form-control" name="long_insp[${lastTitle}][]" placeholder="L.I."></td>
                    <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                </tr>
            `;

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
                        <td colspan="10">Longitud Inspeccionada</td>
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
    /*Selects */
    $(document).ready(function() {
            function actualizarInputsC1() {
                var selectedOption = $('#consumiblesSelect1').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var lote = selectedOption.data('lote') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputC1').val(marca);
                $('#modeloInputC1').val(modelo);
                $('#loteInputC1').val(lote);
                $('#IDInputC1').val($('#consumiblesSelect1').val() || '');

            }

            const selectedOptionLocalCons1 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Consumible1');
            selectedOptionLocalCons1 != null ?  ($('#consumiblesSelect1').val(selectedOptionLocalCons1),actualizarInputsC1()):"";

                // Evento cuando se cambia la selección en el select
                $('#consumiblesSelect1').on('change', function() {
                    actualizarInputsC1();
                });

            function actualizarInputsC2() {
                var selectedOption = $('#consumiblesSelect2').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var lote = selectedOption.data('lote') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputC2').val(marca);
                $('#modeloInputC2').val(modelo);
                $('#loteInputC2').val(lote);
                $('#IDInputC2').val($('#consumiblesSelect2').val() || '');
            }

            const selectedOptionLocalCons2 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Consumible2');
            selectedOptionLocalCons2 != null ?  ($('#consumiblesSelect2').val(selectedOptionLocalCons2),actualizarInputsC2()):"";

                // Evento cuando se cambia la selección en el select
                $('#consumiblesSelect2').on('change', function() {
                    actualizarInputsC2();
                });

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

        });

    /*FOR-PINS-03_02*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-PINS-03_02');
        if (!form) return;

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('FOR-PINS-03_02_' + el.name, el.value);
            //});
        //});

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-PINS-03_02_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-PINS-03_02_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-PINS-03_02_' + el.name);
                //localStorage.clear();
            });
        });
    });
</script>
<script src="{{ asset('js/Reportes_CombinacionCeldasAgrupadas.js') }}"></script>
<script src="{{ asset('js/Reportes_CombinacionCeldasAgrupadas_Create.js') }}"></script>
@endsection
