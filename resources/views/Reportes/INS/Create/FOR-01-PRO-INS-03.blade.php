@extends('adminlte::page')

@section('title', 'FOR-01-PRO-INS-03')

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
                        <div class="card-body  w-100">
                            <form id="FOR-01-PRO-INS-03" action="{{route('Reportes_FOR_01_PRO_INS_03.store')}}" method="post" enctype="multipart/form-data">
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
                                            <label class="col-form-label" for="inputSuccess">No. Reporte</label>
                                            <input type="text" class="form-control  inputForm @error('No_Reporte') is-invalid @enderror" name="Detalles_Generales[No_Reporte]"  placeholder="Ejemplo: 077-8DUCTOS-24" value="{{old('Detalles_Generales.No_Reporte')}}">
                                            @error('No_Reporte')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control  inputForm @error('Cliente') is-invalid @enderror" name="Detalles_Generales[Cliente]"  placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{old('Detalles_Generales.Cliente')}}">
                                            @error('Cliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            
                                           <div class="d-flex justify-content-between align-items-center w-100">
                                                <label class="col-form-label mb-0" for="flexSwitchCheckDefault">Contrato</label>
                                                <div class="form-check form-switch mb-0">
                                                    <input title="Marcar si el contrato es interno" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="Detalles_Generales[Contrato_Activo]" value="1" {{ old('Detalles_Generales.Contrato_Activo') ? 'checked' : '' }}>
                                                </div>
                                            </div>

                                            
                                            <input type="text" class="form-control  inputForm @error('Contrato') is-invalid @enderror" name="Detalles_Generales[Contrato]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Contrato')}}">
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>
                                    

                                    <!--div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control  inputForm @error('Contrato') is-invalid @enderror" name="Detalles_Generales[Contrato]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Contrato')}}">
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div-->

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
                                            <label class="col-form-label" for="inputSuccess">Código Aplicable</label>
                                            <input type="text" class="form-control  inputForm @error('Codigo_Aplicable') is-invalid @enderror" name="Detalles_Generales[Codigo_Aplicable]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Codigo_Aplicable')}}">
                                            @error('Codigo_Aplicable')
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

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded mb-2">DATOS DE LA INSPECCIÓN</div>

                                    <!--div style="margin-x: 2px;"></!--div-->

                                    <div class="alert alert-info alert-dismissible mb-2">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-info"></i> Importante</h5>
                                        <p>Puedes Seleccionar un penetrante, removedor y un reveleador del menu o escribir directamente</p>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">PENETRANTES</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Penetrantes:</label>
                                            <select class="form-select inputForm" name="Consumible1" id="consumiblesSelect1">
                                            <option value="" selected disabled>Seleccione un Penetrante</option> <!-- Opción por defecto -->
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
                                            <input type="text" class="form-control  inputForm" id="marcaInputC1" name="Datos_Equipo[MARCA_PENETRANTES]" placeholder="" value="{{old('Datos_Equipo.MARCA_PENETRANTES')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputC1" name="Datos_Equipo[MODELO_PENETRANTES]" placeholder="" value="{{old('Datos_Equipo.MODELO_PENETRANTES')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm" id="loteInputC1" name="Datos_Equipo[LOTE_PENETRANTES]" placeholder="" value="{{old('Datos_Equipo.LOTE_PENETRANTES')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIEMPO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIEMPO_PENETRANTES]" placeholder="" value="{{old('Datos_Equipo.TIEMPO_PENETRANTES')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[APLICACION_PENETRANTES]" placeholder="" value="{{old('Datos_Equipo.APLICACION_PENETRANTES')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO/GRUPO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIPO_GRUPO_PENETRANTES]" placeholder="" value="{{old('Datos_Equipo.TIPO_GRUPO_PENETRANTES')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">REMOVEDOR</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Removedor:</label>
                                            <select class="form-select inputForm" name="Consumible2" id="consumiblesSelect2">
                                            <option value="" selected disabled>Seleccione un Removedor</option> <!-- Opción por defecto -->
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
                                            <input type="text" class="form-control  inputForm" id="marcaInputC2" name="Datos_Equipo[MARCA_REMOVEDOR]" placeholder="" value="{{old('Datos_Equipo.MARCA_REMOVEDOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputC2" name="Datos_Equipo[MODELO_REMOVEDOR]" placeholder="" value="{{old('Datos_Equipo.MODELO_REMOVEDOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm" id="loteInputC2" name="Datos_Equipo[LOTE_REMOVEDOR]" placeholder="" value="{{old('Datos_Equipo.LOTE_REMOVEDOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIEMPO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIEMPO_REMOVEDOR]" placeholder="" value="{{old('Datos_Equipo.TIEMPO_REMOVEDOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[APLICACION_REMOVEDOR]" placeholder="" value="{{old('Datos_Equipo.APLICACION_REMOVEDOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO/GRUPO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIPO_GRUPO_REMOVEDOR]" placeholder="" value="{{old('Datos_Equipo.TIPO_GRUPO_REMOVEDOR')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">REVELEADOR</div>

                                    <!-- Select para Reveleador -->
                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Reveleador:</label>
                                            <select class="form-select inputForm" name="Consumible3" id="consumiblesSelect3">
                                            <option value="" selected disabled>Seleccione un Reveleador</option> <!-- Opción por defecto -->
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
                                            <input type="text" class="form-control  inputForm" id="marcaInputC3" name="Datos_Equipo[MARCA_REVELEADOR]" placeholder="" value="{{old('Datos_Equipo.MARCA_REVELEADOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" id="modeloInputC3" name="Datos_Equipo[MODELO_REVELEADOR]" placeholder="" value="{{old('Datos_Equipo.MODELO_REVELEADOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm" id="loteInputC3" name="Datos_Equipo[LOTE_REVELEADOR]" placeholder="" value="{{old('Datos_Equipo.LOTE_REVELEADOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIEMPO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIEMPO_REVELEADOR]" placeholder="" value="{{old('Datos_Equipo.TIEMPO_REVELEADOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[APLICACION_REVELEADOR]" placeholder="" value="{{old('Datos_Equipo.APLICACION_REVELEADOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO/GRUPO:</label>
                                            <input type="text" class="form-control  inputForm" name="Datos_Equipo[TIPO_GRUPO_REVELEADOR]" placeholder="" value="{{old('Datos_Equipo.TIPO_GRUPO_REVELEADOR')}}">
                                        </div>
                                    </div>

                                    <div class="alert alert-secondary d-none" role="alert"></div>

                                    <div class="col-sm-12"> 
                                        <hr style="border: none; height: 3px; background-color: black;">
                                    </div>

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
                                                    <th rowspan="2" class="text-center align-middle">No.</th>
                                                    <th rowspan="2" class="text-center align-middle">No. de Junta / Componente</th>
                                                    <th rowspan="2" class="text-center align-middle">No. Indicación</th>
                                                    <th rowspan="2" class="text-center align-middle">Tipo de Indicación</th>
                                                    <th colspan="3" class="text-center align-middle">DIM. DE INDICACIÓN</th>
                                                    <th colspan="1" class="text-center align-middle">LOCALIZACIÓN</th>
                                                    <th rowspan="2" class="text-center align-middle">Evaluación</th>
                                                    <th rowspan="2" class="text-center align-middle">Longitud Inspeccionada</th>
                                                    <th rowspan="2" class="text-center align-middle">Eliminar</th>
                                                </tr>

                                                <tr>
                                                    <th class="text-center align-middle">LARGO</th>
                                                    <th class="text-center align-middle">ANCHO</th>
                                                    <th class="text-center align-middle">Ø</th>
                                                    <th class="text-center align-middle">H.T.</th>
                                                </tr>

                                                <tr id="inputRow">
                                                    <th></th> <!-- Para ID vacío -->
                                                    <th><input type="text" class="form-control default-input" data-column="1" style="width:150px; "></th>
                                                    <th><input type="text" class="form-control default-input" data-column="2" style="width:120px"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="3" style="width: 140px"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="4" style="width: 80px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="5" style="width: 80px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="6" style="width: 80px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="7" style="width: 120px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="8" style="width: 120px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="9" style="width: 120px;"></th>
                                                    <th></th> <!-- Para botón de eliminar -->
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <!-- Filas dinámicas aparecerán aquí -->
                                            </tbody>
                                    </table>
                                    </div>

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
                                        <div class="col-sm-15 my-2">
                                            <div class="form-group">
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                                    <option value="2">2 Firmas</option>
                                                    <option value="3">3 Firmas</option>
                                                    <option value="4">4 Firmas</option>
                                                </select>
                                            </div>
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
                                            <select class="form-control form-select" id="imageCount" name="imageCount" autocomplete="off">
                                                <option value="">Selecciona Cuantas Imagenes Quieres Agregar</option>
                                                @for ($i = 1; $i <= 50; $i++)
                                                    <option value="{{ $i }}">{{ $i }} Imagen{{ $i > 1 ? 'es' : '' }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div id="msgImgNoSave"  class="alert alert-info alert-dismissible d-none">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-info"></i> Importante</h5>
                                            <p>
                                                Las imágenes se han eliminado de la caché por motivos de <strong>privacidad</strong> 
                                                y <strong>seguridad</strong>. Por favor, vuelve a cargarlas o adjúntalas de nuevo.
                                            </p>
                                        </div>

                                        <div class="w-100">
                                            <div id="imageFieldsContainer" class="row">
                                                <!-- Aquí se agregarán dinámicamente los campos -->
                                            </div>
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
/*Juntas-Resultados */
    $(document).ready(function() {
        let tituloCount = 0;
        let rowCount = 0;
        let rowCountGlobal = 0;

        function restoreData() {
            const savedData = JSON.parse(sessionStorage.getItem('dynamicTableData_' + document.querySelectorAll("form")[1].id));
            if (savedData) {
                // Restaurar contadores
                tituloCount = savedData.filter(item => item.type === 'titulo').length;
                rowCountGlobal = savedData.filter(item => item.type === 'fila').length;
                
                savedData.forEach((item) => {
                    if (item.type === 'titulo') {
                        let newTitle = `
                        <tr class="titulo-row" data-titulo="${item.id}">
                            <td colspan="10">
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="text" class="form-control w-90" name="titulos[]" value="${item.text}" placeholder="Ingrese título">
                                    <td><button type="button" class="btn btn-danger btnEliminarTitulo ml-2">
                                        <i class="bi bi-trash"  aria-hidden="true"></i>
                                    </button></td>
                                </div>
                            </td>
                        </tr>`;
                        $('#dynamicTable tbody').append(newTitle);
                    } else if (item.type === 'fila') {
                        let newRow = `
                        <tr data-titulo="${item.titulo}">
                            <td class="text-center align-middle">${item.rowNumber} <input type="hidden" value="${item.rowNumber}"></td>
                            <td><input type="text" class="form-control" name="componente[${item.titulo}][]" value="${item.inputs[1]}" placeholder="No. de Junta / Componente"></td>
                            <td><input type="text" class="form-control" name="no_indicacion[${item.titulo}][]" value="${item.inputs[2]}" placeholder="No. Indicación"></td>
                            <td><input type="text" class="form-control" name="tipo_indicacion[${item.titulo}][]" value="${item.inputs[3]}" placeholder="Tipo de Indicación"></td>
                            <td><input type="text" class="form-control" name="largo[${item.titulo}][]" value="${item.inputs[4]}" placeholder="Largo"></td>
                            <td><input type="text" class="form-control" name="ancho[${item.titulo}][]" value="${item.inputs[5]}" placeholder="Ancho"></td>
                            <td><input type="text" class="form-control" name="diametro[${item.titulo}][]" value="${item.inputs[6]}" placeholder="Ø"></td>
                            <td><input type="text" class="form-control" name="ht[${item.titulo}][]" value="${item.inputs[7]}" placeholder="H.T."></td>
                            <td><input type="text" class="form-control" name="evaluacion[${item.titulo}][]" value="${item.inputs[8]}" placeholder="Evaluación"></td>
                            <td><input type="text" class="form-control" name="long_inspeccionada[${item.titulo}][]" value="${item.inputs[9]}" placeholder="Longitud Inspeccionada"></td>
                            <td><button type="button" class="btn btn-danger btnEliminar">   <i class="bi bi-trash"  aria-hidden="true"></i></button></td>
                        </tr>`;
                        $('#dynamicTable tbody').append(newRow);
                    }
                });
                updateRowNumbers();
                updateTitulos();
            }
        }

        $('#addTituloBtn').click(function () {
            tituloCount++;
            rowCount = 0; // Reiniciar el contador de filas para este título

            let newTitle = `
            <tr class="titulo-row" data-titulo="titulo_${tituloCount}">
                <td colspan="10">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control w-90" name="titulos[]" placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3 (DETALLE DE OREJA DE IZAJE 1/4)">
                        <td><button type="button" class="btn btn-danger btnEliminarTitulo ml-2">
                            <i class="bi bi-trash"  aria-hidden="true"></i>
                        </button></td>
                    </div>
                </td>
            </tr>
        `;

        $('#dynamicTable tbody').append(newTitle);
        updateTitulos(); // Actualizar lista de títulos
        saveData(document.querySelectorAll("form")[1].id);
        });

        $('#addBtn').click(function () {
            let numFilas = parseInt($('#numRows').val());
            // Recontar filas existentes que NO son títulos
            rowCountGlobal = $('#dynamicTable tbody tr:not(.titulo-row)').length;
            let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

            for (let i = 0; i < numFilas; i++) {
            rowCount++; // Incrementar el contador general de filas
            rowCountGlobal++; // Incrementar el contador global de filas Solo es visualmente esta variable

            let newRow = `
                <tr data-titulo="${lastTitle}">
                    <td class="text-center align-middle">${rowCountGlobal} <input type="hidden" value="${rowCount}"></td>
                    <td><input type="text" class="form-control" name="componente[${lastTitle}][]" placeholder="No. de Junta / Componente"></td>
                    <td><input type="text" class="form-control" name="no_indicacion[${lastTitle}][]" placeholder="No. Indicación"></td>
                    <td><input type="text" class="form-control" name="tipo_indicacion[${lastTitle}][]" placeholder="Tipo de Indicación"></td>
                    <td><input type="text" class="form-control" name="largo[${lastTitle}][]" placeholder="Largo"></td>
                    <td><input type="text" class="form-control" name="ancho[${lastTitle}][]" placeholder="Ancho"></td>
                    <td><input type="text" class="form-control" name="diametro[${lastTitle}][]" placeholder="Ø"></td>
                    <td><input type="text" class="form-control" name="ht[${lastTitle}][]" placeholder="H.T."></td>
                    <td><input type="text" class="form-control" name="evaluacion[${lastTitle}][]" placeholder="Evaluación"></td>
                    <td><input type="text" class="form-control" name="long_inspeccionada[${lastTitle}][]" placeholder="Longitud Inspeccionada"></td>
                    <td><button type="button" class="btn btn-danger btnEliminar">   <i class="bi bi-trash"  aria-hidden="true"></i></button></td>
                </tr>
            `;

                $('#dynamicTable tbody').append(newRow);
            }
            saveData(document.querySelectorAll("form")[1].id);
        }
    );
        
        $('form').submit(function(e) {
            // Validar que la tabla no esté vacía
            if ($('#dynamicTable tbody tr').length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La tabla no puede estar vacía. Por favor, agregue al menos una fila.',
                });
                return;
            }
            
            // Eliminar los datos de sessionStorage
            //sessionStorage.removeItem('dynamicTableData'); // Borra solo los datos de la tabla
            /*Object.keys(localStorage).forEach(function(key) {
                if (key.startsWith('FOR-01-PRO-INS-08_')) localStorage.removeItem(key);
            });*/
            sessionStorage.clear(); // Alternativa: Borra todo el sessionStorage
            // Deshabilitar el botón de submit y cambiar el texto (opcional)
            let submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true).text('Guardando...');
            // Opcional: Agregar un indicador de carga
            submitButton.append(' <i class="fa fa-spinner fa-spin"></i>');
        });

            // Restaurar datos al cargar la página
            restoreData();
    });

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
            }


            // Evento cuando se cambia la selección en el select
            $('#consumiblesSelect1').on('change', function() {
                actualizarInputsC1();
            });

            const selectedOptionLocal = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Consumible1');
            selectedOptionLocal != null ?  ($('#consumiblesSelect1').val(selectedOptionLocal),actualizarInputsC1()):"";

            // Cuando se cambia la selección
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
            }

            const selectedOptionLocal2 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Consumible2');
            selectedOptionLocal2 != null ?  ($('#consumiblesSelect2').val(selectedOptionLocal2),actualizarInputsC2()):"";

            // Evento cuando se cambia la selección en el select
            $('#consumiblesSelect2').on('change', function() {
                actualizarInputsC2();
            });
            
            function actualizarInputsC3() {
                var selectedOption = $('#consumiblesSelect3').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var lote = selectedOption.data('lote') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputC3').val(marca);
                $('#modeloInputC3').val(modelo);
                $('#loteInputC3').val(lote);
            }

            const selectedOptionLocal3 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Consumible3');
            selectedOptionLocal3 != null ?  ($('#consumiblesSelect3').val(selectedOptionLocal3),actualizarInputsC3()):"";

            // Evento cuando se cambia la selección en el select
            $('#consumiblesSelect3').on('change', function() {
                actualizarInputsC3();
            });
        });

    /*FOR-01-PRO-INS-03*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-01-PRO-INS-03');
        if (!form) return;

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('FOR-01-PRO-INS-03_' + el.name, el.value);
            //});
        //});

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-01-PRO-INS-03_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-01-PRO-INS-03_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-01-PRO-INS-03_' + el.name);
                //localStorage.clear();
            });
        });
    });
    </script>
@endsection