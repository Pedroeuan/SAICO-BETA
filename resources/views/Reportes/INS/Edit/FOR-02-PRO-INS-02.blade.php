@extends('adminlte::page')

@section('title', 'FOR-02-PRO-INS-02')

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

        #inputRow th {
        text-align: center; /* Centra horizontalmente el contenido */
        vertical-align: middle; /* Centra verticalmente el contenido */
        }

        #inputRow input {
            text-align: center; /* Centra el texto dentro del input */
            margin: auto; /* Centra el input dentro de la celda */
            display: block; /* Asegura que el input se comporte como un bloque */
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
                    <div class="card w-100">
                        <div class="card-body row w-100">
                            <form id="FOR-02-PRO-INS-02" action="{{ route('Reportes_FOR_02_PRO_INS_02.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                                <div style="margin-bottom: 2px;"></div>
                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                                            <input type="date" class="form-control  inputForm @error('Fecha') is-invalid @enderror" name="Detalles_Generales[Fecha]" value="{{ old('Detalles_Generales.Fecha', $Detalles_Generales['Fecha'] ?? '') }}">
                                            @error('Fecha')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Reporte</label>
                                            <input type="text" class="form-control  inputForm @error('No_Reporte') is-invalid @enderror" name="Detalles_Generales[No_Reporte]"  placeholder="Ejemplo: 077-8DUCTOS-24" value="{{old('Detalles_Generales.No_Reporte', $Detalles_Generales['No_Reporte'] ?? '')}}">
                                            @error('No_Reporte')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control  inputForm @error('Cliente') is-invalid @enderror" name="Detalles_Generales[Cliente]"  placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{old('Detalles_Generales.Cliente', $Detalles_Generales['Cliente'] ?? '')}}"">
                                            @error('Cliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control  inputForm @error('Contrato') is-invalid @enderror" name="Detalles_Generales[Contrato]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Contrato', $Detalles_Generales['Contrato'] ?? '')}}">
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <input type="text" class="form-control  inputForm @error('Proyecto') is-invalid @enderror" name="Detalles_Generales[Proyecto]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Proyecto', $Detalles_Generales['Proyecto'] ?? '')}}">
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo</label>
                                            <input type="text" class="form-control  inputForm @error('Orden_Trabajo') is-invalid @enderror" name="Detalles_Generales[Orden_Trabajo]"  placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . . " value="{{old('Detalles_Generales.Orden_Trabajo', $Detalles_Generales['Orden_Trabajo'] ?? '')}}">
                                            @error('Orden_Trabajo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Folio</label>
                                            <input type="text" class="form-control  inputForm @error('Folio') is-invalid @enderror" name="Detalles_Generales[Folio]"  placeholder="Ejemplo:" value="{{old('Detalles_Generales.Folio', $Detalles_Generales['Folio'] ?? '')}}">
                                            @error('Folio')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Partida</label>
                                            <input type="text" class="form-control  inputForm @error('Partida') is-invalid @enderror" name="Detalles_Generales[Partida]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Partida', $Detalles_Generales['Partida'] ?? '')}}">
                                            @error('Partida')
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
                                            <label class="col-form-label" for="inputSuccess">Isometrico/Plano</label>
                                            <input type="text" class="form-control  inputForm @error('Isometrico_Plano') is-invalid @enderror" name="Detalles_Generales[Isometrico_Plano]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Isometrico_Plano', $Detalles_Generales['Isometrico_Plano'] ?? '')}}">
                                            @error('Isometrico_Plano')
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
                                            <select class="form-control inputForm" name="consumibles" id="consumiblesSelect1">
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
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" id="marcaInputC1" name="Datos_Equipo[MARCA_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.MARCA_PARTICULAS', $Datos_Equipo['MARCA_PARTICULAS'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputC1" name="Datos_Equipo[MODELO_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.MODELO_PARTICULAS', $Datos_Equipo['MODELO_PARTICULAS'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm" id="loteInputC1" name="Datos_Equipo[LOTE_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.LOTE_PARTICULAS', $Datos_Equipo['LOTE_PARTICULAS'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIPO_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.TIPO_PARTICULAS', $Datos_Equipo['TIPO_PARTICULAS'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">COLOR:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[COLOR_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.COLOR_PARTICULAS', $Datos_Equipo['COLOR_PARTICULAS'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm"  name="Datos_Equipo[APLICACION_PARTICULAS]" placeholder="" value="{{old('Datos_Equipo.APLICACION_PARTICULAS', $Datos_Equipo['APLICACION_PARTICULAS'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">CONTRASTANTE</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">CONTRASTANTE:</label>
                                            <select class="form-control inputForm" name="consumibles" id="consumiblesSelect2">
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
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" id="marcaInputC" name="Datos_Equipo[MARCA_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.MARCA_CONTRASTE', $Datos_Equipo['MARCA_CONTRASTE'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputC" name="Datos_Equipo[MODELO_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.MODELO_CONTRASTE', $Datos_Equipo['MODELO_CONTRASTE'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm" id="loteInputC" name="Datos_Equipo[LOTE_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.LOTE_CONTRASTE', $Datos_Equipo['LOTE_CONTRASTE'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO:</label>
                                            <input type="text" class="form-control  inputForm" id="tipoInputC" name="Datos_Equipo[TIPO_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.TIPO_CONTRASTE', $Datos_Equipo['TIPO_CONTRASTE'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">COLOR:</label>
                                            <input type="text" class="form-control  inputForm" id="colorInputC" name="Datos_Equipo[COLOR_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.COLOR_CONTRASTE', $Datos_Equipo['COLOR_CONTRASTE'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm" id="aplicacionInputC" name="Datos_Equipo[APLICACION_CONTRASTE]" placeholder="" value="{{old('Datos_Equipo.APLICACION_CONTRASTE', $Datos_Equipo['APLICACION_CONTRASTE'] ?? '')}}">
                                        </div>
                                    </div>


                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">EQUIPO</div>

                                    <!-- Select para Equipos -->
                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">EQUIPOS:</label>
                                            <select class="form-control inputForm" name="equipos" id="equiposSelect">
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
                                            <input type="text" class="form-control  inputForm" id="nsInputE" name="Datos_Equipo[N_S_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.N_S_EQUIPO', $Datos_Equipo['N_S_EQUIPO'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">CORRIENTE:</label>
                                            <input type="text" class="form-control  inputForm" id="corrienteInputE" name="Datos_Equipo[CORRIENTE_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.CORRIENTE_EQUIPO', $Datos_Equipo['CORRIENTE_EQUIPO'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">DISTANCIA ENTRE PATAS:</label>
                                            <input type="text" class="form-control  inputForm" id="distanciaInputE" name="Datos_Equipo[DISTANCIA_PATAS_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.DISTANCIA_PATAS_EQUIPO', $Datos_Equipo['DISTANCIA_PATAS_EQUIPO'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="alert alert-secondary" role="alert"></div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO DE LUZ:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIPO_LUZ]" placeholder="" value="{{old('Datos_Equipo.TIPO_LUZ', $Datos_Equipo['TIPO_LUZ'] ?? '')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">INTENCIDAD:</label>                                            
                                            <div class="input-group">
                                                <input type="text" class="form-control  inputForm" name="Datos_Equipo[INTENCIDAD]" placeholder="" value="{{old('Datos_Equipo.INTENCIDAD', $Datos_Equipo['INTENCIDAD'] ?? '')}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Lx</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">CONDICIÓN SUPERFICIAL:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[CONDICION_SUPERFICIAL]" placeholder="" value="{{old('Datos_Equipo.CONDICION_SUPERFICIAL', $Datos_Equipo['CONDICION_SUPERFICIAL'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TEMPERATURA DE PRUEBA:</label>                                            
                                            <div class="input-group">
                                                <input type="text" class="form-control  inputForm" name="Datos_Equipo[TEMPERATURA_PRUEBA]" placeholder="" value="{{old('Datos_Equipo.TEMPERATURA_PRUEBA', $Datos_Equipo['TEMPERATURA_PRUEBA'] ?? '')}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">°C</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                                    <!--***************************************** INICIO RESULTADOS *****************************************-->

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>
                                    
                                    <div style="margin-bottom: 2px;"></div>

                                    <div class="table-responsive">
                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                                        <div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-info"></i> Importante</h5>
                                            <p>La primera fila es para el llenado automatico de cada una de las columnas del formato.</p>
                                        </div>
                                        <thead>
                                                <tr>
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
                                                @php $contador = 1; @endphp <!-- Inicializamos el contador antes de los bucles -->

                                                @if (!empty($Titulos_resultados) && count($Titulos_resultados) > 0)
                                                    @foreach($Titulos_resultados as $index => $titulo)
                                                        <!-- Fila del título -->
                                                        <tr class="titulo-row">
                                                            <td colspan="11">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <input type="text" class="form-control w-90" name="titulos[]" 
                                                                        value="{{ $titulo['titulo'] }}" 
                                                                        placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3 (DETALLE DE OREJA DE IZAJE 1/4)">
                                                                    <button type="button" class="btn btn-danger btnEliminarTitulo ml-2">
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <!-- Filas de resultados pertenecientes a este título -->
                                                        @foreach($Juntas_resultados as $junta)
                                                            <tr>
                                                                <td>{{ $contador }}</td> <!-- Usamos el contador global -->
                                                                <td><input type="text" class="form-control" name="componente[]" value="{{ $junta['componente'] }}" placeholder="componente"></td>
                                                                <td><input type="text" class="form-control" name="no_indicacion[]" value="{{ $junta['no_indicacion'] }}" placeholder="no_indicacion"></td>
                                                                <td><input type="text" class="form-control" name="tipo_indicacion[]" value="{{ $junta['tipo_indicacion'] }}" placeholder="tipo_indicacion"></td>
                                                                <td><input type="text" class="form-control" name="largo[]" value="{{ $junta['largo'] }}" placeholder="largo"></td>
                                                                <td><input type="text" class="form-control" name="ancho[]" value="{{ $junta['ancho'] }}" placeholder="ancho"></td>
                                                                <td><input type="text" class="form-control" name="diametro[]" value="{{ $junta['diametro'] }}" placeholder="Ø"></td>
                                                                <td><input type="text" class="form-control" name="ht[]" value="{{ $junta['ht'] }}" placeholder="ht"></td>
                                                                <td><input type="text" class="form-control" name="evaluacion[]" value="{{ $junta['evaluacion'] }}" placeholder="evaluacion"></td>
                                                                <td><input type="text" class="form-control" name="longitud_inspeccionada[]" value="{{ $junta['longitud_inspeccionada'] }}" placeholder="longitud_inspeccionada"></td>
                                                                <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                            </tr>
                                                            @php $contador++; @endphp <!-- Incrementamos el contador global -->
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    <!-- Si NO hay títulos, simplemente mostramos las filas -->
                                                    @foreach($Juntas_resultados as $junta)
                                                        <tr>
                                                            <td>{{ $contador }}</td>
                                                            <td><input type="text" class="form-control" name="componente[]" value="{{ $junta['componente'] }}" placeholder="componente"></td>
                                                            <td><input type="text" class="form-control" name="no_indicacion[]" value="{{ $junta['no_indicacion'] }}" placeholder="no_indicacion"></td>
                                                            <td><input type="text" class="form-control" name="tipo_indicacion[]" value="{{ $junta['tipo_indicacion'] }}" placeholder="tipo_indicacion"></td>
                                                            <td><input type="text" class="form-control" name="largo[]" value="{{ $junta['largo'] }}" placeholder="largo"></td>
                                                            <td><input type="text" class="form-control" name="ancho[]" value="{{ $junta['ancho'] }}" placeholder="ancho"></td>
                                                            <td><input type="text" class="form-control" name="diametro[]" value="{{ $junta['diametro'] }}" placeholder="Ø"></td>
                                                            <td><input type="text" class="form-control" name="ht[]" value="{{ $junta['ht'] }}" placeholder="ht"></td>
                                                            <td><input type="text" class="form-control" name="evaluacion[]" value="{{ $junta['evaluacion'] }}" placeholder="evaluacion"></td>
                                                            <td><input type="text" class="form-control" name="longitud_inspeccionada[]" value="{{ $junta['longitud_inspeccionada'] }}" placeholder="longitud_inspeccionada"></td>
                                                            <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                        </tr>
                                                        @php $contador++; @endphp <!-- Incrementamos el contador global -->
                                                    @endforeach
                                                @endif
                                            </tbody>
                                    </table>

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

                                        <button id="preFillBtn" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                                    </div>
                                    </div>
                                    <p>
                                    
                                    {{-- <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">LONGITUD TOTAL INSPECCIONADA:</div>
                                    <div>
                                        <div class="form-group">
                                        <input type="text" class="form-control  inputForm" name="Datos_Equipo[ACOPLANTE]" placeholder="" value="{{old('Datos_Equipo.ACOPLANTE')}}">
                                        </div>
                                    </div> --}}

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
                                                <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{old('Observaciones', $Datos_Equipo['Observaciones'] ?? '')}}</textarea>
                                            </div>
                                        </div>

                                        <!-- Select para elegir el número de firmas -->
                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">Número de Firmas:</div>
                                        <div class="col-sm-15">
                                            <div class="form-group">
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                                    <option value="2" {{ $numFirmas == 2 ? 'selected' : '' }}>2 Firmas</option>
                                                    <option value="3" {{ $numFirmas == 3 ? 'selected' : '' }}>3 Firmas</option>
                                                    <option value="4" {{ $numFirmas == 4 ? 'selected' : '' }}>4 Firmas</option>
                                                </select>
                                            </div>
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

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_TECNICO]" placeholder="Ejemplo: NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
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

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_TECNICO]" placeholder="Ejemplo: NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}"></td>
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
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelBtn">Cancelar</button>
                                                        <button type="button" class="btn btn-primary" id="cropImageBtn">Recortar y Guardar</button>
                                                        <button type="button" class="btn btn-primary" id="saveWithoutCropBtn">Guardar Sin Recortar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Campos para subir imágenes y comentarios -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image1">Imagen 1:</label>
                                                <input type="file" class="form-control" id="image1" name="image1" accept="image/*">
                                                <div style="margin-bottom: 2px;"></div>
                                                <div class="image-preview" id="image1-preview"></div>
                                                @if(isset($Fotos_Comentarios[0]['path']))
                                                    <img src="{{ asset(str_replace('public/', 'storage/', $Fotos_Comentarios[0]['path'])) }}" alt="Imagen 1" style="max-width: 100%; max-height: 100%;">
                                                @endif
                                                <textarea class="form-control mt-2" name="comment1" placeholder="Comentario para la imagen 1">{{ old('comment1', $Fotos_Comentarios[0]['comment'] ?? '') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image2">Imagen 2:</label>
                                                <input type="file" class="form-control" id="image2" name="image2" accept="image/*">
                                                <div style="margin-bottom: 2px;"></div>
                                                <div class="image-preview" id="image2-preview"></div>
                                                @if(isset($Fotos_Comentarios[1]['path']))
                                                    <img src="{{ asset(str_replace('public/', 'storage/', $Fotos_Comentarios[1]['path'])) }}" alt="Imagen 1" style="max-width: 100%; max-height: 100%;">
                                                @endif
                                                <textarea class="form-control mt-2" name="comment2" placeholder="Comentario para la imagen 2">{{ old('comment2', $Fotos_Comentarios[1]['comment'] ?? '') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image3">Imagen 3:</label>
                                                <input type="file" class="form-control" id="image3" name="image3" accept="image/*">
                                                <div style="margin-bottom: 2px;"></div>
                                                <div class="image-preview" id="image3-preview"></div>
                                                @if(isset($Fotos_Comentarios[2]['path']))
                                                    <img src="{{ asset(str_replace('public/', 'storage/', $Fotos_Comentarios[2]['path'])) }}" alt="Imagen 1" style="max-width: 100%; max-height: 100%;">
                                                @endif
                                                <textarea class="form-control mt-2" name="comment3" placeholder="Comentario para la imagen 3">{{ old('comment3', $Fotos_Comentarios[2]['comment'] ?? '') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image4">Imagen 4:</label>
                                                <input type="file" class="form-control" id="image4" name="image4" accept="image/*">
                                                <div style="margin-bottom: 2px;"></div>
                                                <div class="image-preview" id="image4-preview"></div>
                                                @if(isset($Fotos_Comentarios[3]['path']))
                                                    <img src="{{ asset(str_replace('public/', 'storage/', $Fotos_Comentarios[3]['path'])) }}" alt="Imagen 1" style="max-width: 100%; max-height: 100%;">
                                                @endif
                                                <textarea class="form-control mt-2" name="comment4" placeholder="Comentario para la imagen 4">{{ old('comment4', $Fotos_Comentarios[3]['comment'] ?? '') }}</textarea>
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

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>

        /*Prevenir el Enter*/
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

    /*Juntas-Resultados */
    $(document).ready(function () {
        var rowCount = $('#dynamicTable tbody tr:not(.titulo-row)').length; // Contar solo filas sin títulos

        function updateRowNumbers() {
            var counter = 1; // Iniciar el contador desde 1
            $('#dynamicTable tbody tr').each(function () {
                if (!$(this).hasClass('titulo-row')) { // Ignorar las filas de título
                    $(this).find('td:first').text(counter);
                    counter++;
                }
            });
            rowCount = counter - 1; // Actualizar el contador global
        }

        $('#addTituloBtn').click(function () {
            var newTitleRow = `<tr class="titulo-row">
                <td colspan="11">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control w-90" name="titulos[]" placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3 (DETALLE DE OREJA DE IZAJE 1/4)">
                        <button type="button" class="btn btn-danger btnEliminarTitulo ml-2">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
            $('#dynamicTable tbody').append(newTitleRow);
        });

        $('#dynamicTable').on('click', '.btnEliminarTitulo', function () {
            $(this).closest('tr').remove();
        });

        $('#addBtn').click(function () {
            var numRows = $('#numRows').val();
            for (var i = 0; i < numRows; i++) {
                rowCount++;
                var newRow = `<tr>
                    <td>${rowCount}</td>
                    <td><input type="text" class="form-control" name="componente[]" placeholder="No. Junta/Componente"></td>
                    <td><input type="text" class="form-control" name="no_indicacion[]" placeholder="No. Indicación"></td>
                    <td><input type="text" class="form-control" name="tipo_indicacion[]" placeholder="Tipo Indicación"></td>
                    <td><input type="text" class="form-control" name="largo[]" placeholder="LARGO"></td>
                    <td><input type="text" class="form-control" name="ancho[]" placeholder="ANCHO"></td>
                    <td><input type="text" class="form-control" name="diametro[]" placeholder="Ø"></td>
                    <td><input type="text" class="form-control" name="ht[]" placeholder="H.T." style="width: 120px;"></td>
                    <td><input type="text" class="form-control" name="evaluacion[]" placeholder="Evaluación"></td>
                    <td><input type="text" class="form-control" name="longitud_inspeccionada[]" placeholder="L.I."></td>
                    <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                </tr>`;
                $('#dynamicTable tbody').append(newRow);
            }
            updateRowNumbers(); // Recalcular la numeración después de agregar filas
        });

        $('#dynamicTable').on('click', '.btnEliminar', function () {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });

        $('#preFillBtn').click(function () {
            $('#dynamicTable tbody tr').each(function () {
                $(this).find('input').each(function () {
                    if ($(this).val() === '') {
                        $(this).val('----');
                    }
                });
            });
        });

        $('form').submit(function (e) {
            if ($('#dynamicTable tbody tr:not(.titulo-row)').length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La tabla no puede estar vacía. Por favor, agregue al menos una fila.',
                });
                return;
            }

            let submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true).text('Guardando...');
            submitButton.append(' <i class="fa fa-spinner fa-spin"></i>');
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
    const inputFields = document.querySelectorAll(".default-input");

        // Evento para actualizar filas cuando se escriba en los inputs superiores
        inputFields.forEach(input => {
            input.addEventListener("input", function () {
                const column = input.getAttribute("data-column");
                document.querySelectorAll(`#dynamicTable tbody tr`).forEach(row => {
                    const cellInput = row.querySelectorAll("td input")[column - 1];
                    if (cellInput) {
                        cellInput.value = input.value;
                    }
                });
            });
        });

    });

    $(document).ready(function() {
        var cropper;
        var selectedInput;

        // Función para leer la imagen seleccionada y mostrarla en el modal
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#cropperImage').attr('src', e.target.result);
                    $('#cropperModal').modal('show');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Cuando el input de archivo cambia (cuando se selecciona una imagen)
        $('input[type="file"]').change(function() {
            selectedInput = this;
            readURL(this);
        });

        // Inicializar el Cropper cuando se muestre el modal
        $('#cropperModal').on('shown.bs.modal', function() {
            var image = document.getElementById('cropperImage');
            cropper = new Cropper(image, {
                aspectRatio: 1, // Puedes cambiar el aspecto según tus necesidades
                viewMode: 2,
                autoCropArea: 1
            });
        }).on('hidden.bs.modal', function() {
            // Asegurarse de que el Cropper se destruye al cerrar el modal
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        // Acción para recortar la imagen y guardarla
        $('#cropImageBtn').click(function() {
            var canvas = cropper.getCroppedCanvas({
                width: 300, // Ajusta el tamaño de la imagen recortada
                height: 300
            });

            canvas.toBlob(function(blob) {
                var file = new File([blob], selectedInput.files[0].name, { type: 'image/jpeg' });
                var dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                selectedInput.files = dataTransfer.files;

                var previewId = '#' + $(selectedInput).attr('id') + '-preview';
                $(previewId).html(''); // Limpiar el contenido del contenedor de vista previa
                $(previewId).html('<img src="' + canvas.toDataURL('image/jpeg') + '" style="max-width: 100%; max-height: 100%;">');

                $('#cropperModal').modal('hide');
            }, 'image/jpeg');
        });

        // Acción para guardar la imagen sin recortarla
        $('#saveWithoutCropBtn').click(function() {
            var file = selectedInput.files[0];

            var dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            selectedInput.files = dataTransfer.files;

            var previewId = '#' + $(selectedInput).attr('id') + '-preview';
            $(previewId).html(''); // Limpiar el contenido del contenedor de vista previa
            $(previewId).html('<img src="' + URL.createObjectURL(file) + '" style="max-width: 100%; max-height: 100%;">');

            $('#cropperModal').modal('hide');
        });

        // Asegurarse de que el modal también se puede cerrar si se hace clic en "Cancelar" o en la "X"
        $('#cropperModal').on('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        // Hacer que el botón de cancelar cierre el modal
        $('#cancelBtn').click(function() {
            $('#cropperModal').modal('hide');
        });

        // Asegúrate de que la "X" también cierre el modal (Bootstrap la maneja por defecto, pero lo confirmamos aquí)
        $('.close').click(function() {
            $('#cropperModal').modal('hide');
        });

        // Limpiar la imagen previa cuando se selecciona una nueva imagen
        $('input[type="file"]').change(function() {
            var previewId = '#' + $(this).attr('id') + '-preview';
            $(previewId).html(''); // Limpiar el contenido del contenedor de vista previa
            $(this).siblings('img').remove(); // Eliminar la imagen previa cargada visualmente
        });
    });

    // Pre-Rellenado del formulario
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("preFormBtn").addEventListener("click", function () {
            // Seleccionar todos los inputs y textareas del formulario
            let inputs = document.querySelectorAll(".inputForm");
            let textareas = document.querySelectorAll("textarea");

            inputs.forEach(function (input) {
                if (input.value.trim() === "") { 
                    input.value = "---"; // Asignar "---" si está vacío
                }
            });

            textareas.forEach(function (textarea) {
                if (textarea.value.trim() === "") { 
                    textarea.value = "---"; // Asignar "---" si está vacío
                }
            });
        });
    });

    // Selección de Firmas
    document.addEventListener('DOMContentLoaded', function() {
        const numFirmasSelect = document.getElementById('numFirmas');
        const firmas2 = document.getElementById('firmas2');
        const firmas3 = document.getElementById('firmas3');
        const firmas4 = document.getElementById('firmas4');

        numFirmasSelect.addEventListener('change', function() {
            if (this.value == '2') {
                firmas2.style.display = 'block';
                firmas3.style.display = 'none';
                firmas4.style.display = 'none';
            } else if (this.value == '3') {
                firmas2.style.display = 'none';
                firmas3.style.display = 'block';
                firmas4.style.display = 'none';
            } else if (this.value == '4') {
                firmas2.style.display = 'none';
                firmas3.style.display = 'none';
                firmas4.style.display = 'block';
            }
        });

        // Inicializar la visibilidad de las secciones de firmas
        if (numFirmasSelect.value == '2') {
            firmas2.style.display = 'block';
            firmas3.style.display = 'none';
            firmas4.style.display = 'none';
        } else if (numFirmasSelect.value == '3') {
            firmas2.style.display = 'none';
            firmas3.style.display = 'block';
            firmas4.style.display = 'none';
        } else if (numFirmasSelect.value == '4') {
            firmas2.style.display = 'none';
            firmas3.style.display = 'none';
            firmas4.style.display = 'block';
        }
    });

    $(document).ready(function() {
            function actualizarInputsA() {
                var selectedOption = $('#consumiblesSelect1').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var lote = selectedOption.data('lote') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputC1').val(marca);
                $('#modeloInputC1').val(modelo);
                $('#loteInputC1').val(lote);
            }
                // Evento cuando se cambia la selección en el select
                $('#consumiblesSelect1').on('change', function() {
                    actualizarInputsA();
                });
            });

            $(document).ready(function() {
            function actualizarInputsA() {
                var selectedOption = $('#consumiblesSelect2').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var lote = selectedOption.data('lote') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputC2').val(marca);
                $('#modeloInputC2').val(modelo);
                $('#loteInputC2').val(lote);
            }
                // Evento cuando se cambia la selección en el select
                $('#consumiblesSelect2').on('change', function() {
                    actualizarInputsA();
                });
            });

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
        }

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect').on('change', function() {
                actualizarInputsE();
            });

        });

</script>
@endsection