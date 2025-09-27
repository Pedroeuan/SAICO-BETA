@extends('adminlte::page')

@section('title', 'FOR-01-PRO-INS-22')

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
            <form id="FOR-01-PRO-INS-22" action="{{route('Reportes_FOR_01_PRO_INS_22.store')}}" method="post" enctype="multipart/form-data">
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
                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                            <input type="text" class="form-control  inputForm @error('Contrato') is-invalid @enderror" name="Detalles_Generales[Contrato]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Contrato')}}">
                            @error('Contrato')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
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
                            <label class="col-form-label" for="inputSuccess">Tipo de Fluido</label>
                            <input type="text" class="form-control  inputForm @error('Tipo_Flu') is-invalid @enderror" name="Detalles_Generales[Tipo_Flu]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Tipo_Flu')}}">
                            @error('Tipo_Fluido')
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
                            <label class="col-form-label" for="inputSuccess">Temperatura de Operación</label>
                            <input type="text" class="form-control  inputForm @error('Temp_Op') is-invalid @enderror" name="Detalles_Generales[Temp_Op]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Temp_Op')}}">
                            @error('Temp_Op')
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
                            <label class="col-form-label" for="inputSuccess">Espesor Nominal / Cedula</label>
                            <input type="text" class="form-control  inputForm @error('Esp_Ced') is-invalid @enderror" name="Detalles_Generales[Esp_Ced]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Esp_Ced')}}">
                            @error('Esp_Ced')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Tuberia / UDC / Isometrico / Plano</label>
                            <input type="text" class="form-control  inputForm @error('Isometrico_Plano') is-invalid @enderror" name="Detalles_Generales[Isometrico_Plano]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Isometrico_Plano')}}">
                            @error('Tuberia_UDC_Isometrico_Plano')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
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
                            <label class="col-form-label" for="inputSuccess">Diametro Nominal NPS</label>
                            <input type="text" class="form-control  inputForm @error('Dia_NPS') is-invalid @enderror" name="Detalles_Generales[Dia_NPS]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Dia_NPS')}}">
                            @error('Dia_NPS')
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

                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DEL EQUIPO</div>

                    <div style="margin-bottom: 2px;"></div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">EQUIPO DE ONDAS GUIADAS</div>

                    <!-- Select para Equipos -->
                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">Equipos:</label>
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

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ANILLO TRANSDUCTOR 1</div>

                    <!-- Select para Accesorios -->
                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">TRANSDUCTORES:</label>
                                <select class="form-select inputForm" name="accesorios" id="accesoriosSelect">
                                    <option value="" selected disabled>Seleccione un Accesorio</option>
                                        @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                            <option value="{{ $accesorios->idGeneral_EyC }}"
                                                    data-marca="{{ $accesorios->Marca }}"
                                                    data-modelo="{{ $accesorios->Modelo }}"
                                                    data-ns="{{ $accesorios->Serie }}">
                                                    {{ $accesorios->Nombre_E_P_BP }}
                                            </option>
                                        @endforeach
                                </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">DIAMETRO PULG:</label>
                            <input type="text" class="form-control  inputForm" id="diametroInputA" name="Datos_Equipo[DIAMETRO_PULG]" placeholder="" value="{{old('Datos_Equipo.DIAMETRO_PULG')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA" name="Datos_Equipo[MARCA_AN1]" placeholder="" value="{{old('Datos_Equipo.MARCA_AN1')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA" name="Datos_Equipo[NS_AN1]" placeholder="" value="{{old('Datos_Equipo.NS_AN1')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ANILLO TRANSDUCTOR 2</div>

                    <!-- Select para Accesorios -->
                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <label class="col-form-label" for="inputSuccess">TRANSDUCTORES:</label>
                                <select class="form-select inputForm" name="accesorios" id="accesoriosSelect2">
                                    <option value="" selected disabled>Seleccione un Accesorio</option>
                                        @foreach($idsGeneral_EyCs_Accesorios as $accesorios)
                                            <option value="{{ $accesorios->idGeneral_EyC }}"
                                                    data-marca="{{ $accesorios->Marca }}"
                                                    data-modelo="{{ $accesorios->Modelo }}"
                                                    data-ns="{{ $accesorios->Serie }}">
                                                    {{ $accesorios->Nombre_E_P_BP }}
                                            </option>
                                        @endforeach
                                </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">DIAMETRO PULG:</label>
                            <input type="text" class="form-control  inputForm" id="diametroInputA2" name="Datos_Equipo[DIAMETRO_AN2]" placeholder="" value="{{old('Datos_Equipo.DIAMETRO_AN2')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA2" name="Datos_Equipo[MODELO_AN2]" placeholder="" value="{{old('Datos_Equipo.MODELO_MODELO_AN2')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA2" name="Datos_Equipo[NS_AN2]" placeholder="" value="{{old('Datos_Equipo.NS_AN2')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">-</div>


                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Número de Modelos:</label>
                            <input type="text" class="form-control  inputForm" id="numeroInputE" name="Datos_Equipo[Num_Mode]" placeholder="" value="{{old('Datos_Equipo.Num_Mode')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Número de Transductores:</label>
                            <input type="text" class="form-control  inputForm" id="numeroInputE" name="Datos_Equipo[NUM_TRANS]" placeholder="" value="{{old('Datos_Equipo.NUM_TRANS')}}">
                        </div>
                    </div>

                            <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                            <!--***************************************** INICIO RESULTADOS *****************************************-->

                            <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DE LA INSPECCIÓN</div>
                                    
                            <div style="margin-bottom: 5px;"></div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FRECUENCIA</label>
                            <input type="text" class="form-control  inputForm @error('Frecuencia') is-invalid @enderror" name="Datos_Equipo[Frecuencia]"  placeholder="" value="{{old('Datos_Equipo.Frecuencia')}}">
                            @error('Frecuencia')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ORIENTACIÓN DE LA TUBERIA</label>
                            <input type="text" class="form-control  inputForm @error('Ori_Tube') is-invalid @enderror" name="Datos_Equipo[Ori_Tube]"  placeholder="" value="{{old('Datos_Equipo.Ori_Tube')}}">
                            @error('Ori_Tube')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">REFERENCIA DE LA POSICIÓN DEL ANILLO</label>
                            <input type="text" class="form-control  inputForm @error('Ref_An') is-invalid @enderror" name="Datos_Equipo[Ref_An]"  placeholder="" value="{{old('Datos_Equipo.Ref_An')}}">
                            @error('Ref_An')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODO DE ONDA</label>
                            <input type="text" class="form-control  inputForm @error('Mod_Onda') is-invalid @enderror" name="Datos_Equipo[Mod_Onda]"  placeholder="" value="{{old('Datos_Equipo.Mod_Onda')}}">
                            @error('Mod_Onda')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">DIRECCIÓN DEL DISPARO</label>
                            <input type="text" class="form-control  inputForm @error('Dir_Dis') is-invalid @enderror" name="Datos_Equipo[Dir_Dis]"  placeholder="" value="{{old('Datos_Equipo.Dir_Dis')}}">
                            @error('Dir_Dis')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">DISTANCIA DE POSICIÓN DEL ANILLO</label>
                            <input type="text" class="form-control  inputForm @error('Dm_An') is-invalid @enderror" name="Datos_Equipo[Dm_An]"  placeholder="" value="{{old('Datos_Equipo.Dm_An')}}">
                            @error('Dm_An')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">PRESIÓN DE OPERACIÓN DEL ANILLO</label>
                            <input type="text" class="form-control  inputForm @error('Psi_an') is-invalid @enderror" name="Datos_Equipo[Psi_an]"  placeholder="" value="{{old('Datos_Equipo.Psi_an')}}">
                            @error('Psi_an')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">TIPO DE RECUBRIMIENTO</label>
                            <input type="text" class="form-control  inputForm @error('Tip_Rec') is-invalid @enderror" name="Datos_Equipo[Tip_Rec]"  placeholder="" value="{{old('Datos_Equipo.Tip_Rec')}}">
                            @error('Tip_Rec')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ANGULO DE ORIENTACIÓN DEL ANILLO</label>
                            <input type="text" class="form-control  inputForm @error('Ang_An') is-invalid @enderror" name="Datos_Equipo[Ang_An]"  placeholder="" value="{{old('Datos_Equipo.Ang_An')}}">
                            @error('Ang_An')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">COORDENADAS GPS</label>
                            <input type="text" class="form-control  inputForm @error('Coor_GPS') is-invalid @enderror" name="Datos_Equipo[Coor_GPS]"  placeholder="" value="{{old('Datos_Equipo.Coor_GPS')}}">
                            @error('Coor_GPS')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>


                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>
                        <div style="margin-bottom: 5px;"></div>
                            <div class="table-responsive">
                                <div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-info"></i> Importante</h5>
                                    <p>La primera fila es para el llenado automatico de cada una de las columnas del formato.</p>
                            </div>
                                <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-50">
                                <thead>
                                    <tr>
                                        <th class="align-middle" rowspan="2">#</th>
                                        <th class="align-middle" rowspan="2">ID</th>
                                        <th class="align-middle" rowspan="2">Elemento</th>
                                        <th class="align-middle" rowspan="2">Ønom (pulg)</th>
                                        <th class="align-middle" rowspan="2">Øext (pulg)</th>
                                        <th class="align-middle" rowspan="2">Long. (m)</th>
                                        <th class="align-middle" rowspan="2">Elementos idendificados</th>
                                        <th class="align-middle" colspan="2">Distancia del disparo (m)</th>
                                        <th class="align-middle" rowspan="2">No. Ind.</th>
                                        <th class="align-middle" rowspan="2">Distancia relativa al dato (m)</th>
                                        <th class="align-middle" rowspan="2" colspan="2">Horario Técnico</th>
                                        <th class="align-middle" colspan="3">Clasificación de la indicación o anomalía</th>
                                        <th class="align-middle" rowspan="2">porcentaje de reflexión (%)</th>
                                        <th class="align-middle" rowspan="2">Fotos No.</th>
                                        <th class="align-middle" rowspan="2">Observaciones</th>
                                        <th class="align-middle" rowspan="2">Eliminar</th>
                                    </tr>

                                    <tr>

                                        <th class="align-middle">(-X)</th>
                                        <th class="align-middle">(+X)</th>

                                        <th class="align-middle">Categoría</th>
                                        <th class="align-middle">Direccionalidad</th>
                                        <th class="align-middle">Clasificación</th>
                                    </tr>

                                    <tr id="inputRow">
                                        <th></th> <!-- Para ID vacío -->
                                        <th><input type="text" class="form-control default-input" data-column="1" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="2" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="3" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="4" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="5" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="6" style="width:100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="7" style="width:100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="8" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="9" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="10" style="width 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="11" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="12" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="13" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="14" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="15" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="16" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="17" style="width: 100px;"></th>
                                        <th><input type="text" class="form-control default-input" data-column="18" style="width: 100px;"></th>
                                        <th></th> <!-- Para botón de eliminar -->
                                    </tr>
                                </thead>

                                    <tbody>
                                    <!-- Filas dinámicas aparecerán aquí -->
                                    </tbody>
                            </table>
                        </div>

                        <p>

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


                        <table class="table table-bordered table-striped dt-responsive tablas">
                            <tr>
                                <td>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th colspan="6" class="p-2 alert alert-warning">SIMBOLOGÍA DEL REPORTE</th>
                                            </tr>

                                            <tr>
                                                <td><strong>PE:</strong></td>
                                                <td>FIN DE TUBERIA</td>
                                                <td><strong>V:</strong></td>
                                                <td>SOLDADURA CIRCUNFERENCIAL</td>
                                                <td><strong>BV:</strong></td>
                                                <td>SOLDADURA DE CODO</td>
                                            </tr>

                                            <tr>
                                                <td><strong>F:</strong></td>
                                                <td>BRIDA</td>
                                                <td><strong>SP:</strong></td>
                                                <td>SOPORTE DE TUBERIA</td>
                                                <td><strong>DZ:</strong></td>
                                                <td>ZONA MUERTA</td>
                                            </tr>

                                            <tr>
                                                <td><strong>HC:</strong></td>
                                                <td>ABRAZADERA DE SUJECIÓN</td>
                                                <td><strong>C1:</strong></td>
                                                <td>ANOMALÍA O IND. CATEGORIA 1</td>
                                                <td><strong>H.T.</strong></td>
                                                <td>HORARIO TÉCNICO</td>
                                            </tr>

                                            <tr>
                                                <td><strong>SB:</strong></td>
                                                <td>RAMAL</td>
                                                <td><strong>C2:</strong></td>
                                                <td>ANOMALÍA O IND. CATEGORIA 2</td>
                                                <td><strong>-X:</strong></td>
                                                <td>DISTANCIA NEGATIVA</td>
                                            </tr>

                                            <tr>
                                                <td><strong>IND:</strong></td>
                                                <td>INDICACIÓN</td>
                                                <td><strong>C3:</strong></td>
                                                <td>ANOMALÍA O IND. CATEGORIA 3</td>
                                                <td><strong>+X:</strong></td>
                                                <td>DISTANCIA POSITIVA</td>
                                            </tr>

                                        </thead>
                                    </table>
                                </td>
                            </tr>
                        </table>

                    <p>

                        <table class="table table-bordered table-striped dt-responsive tablas">
                            <tr>
                                <td>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th colspan="16" class="p-2 alert alert-warning">RELACIÓN ENTRE ÁNGULOS DE LA DIRECCIONALIDAD Y HORARIOS TÉCNICOS</th>
                                            </tr>

                                            <tr>
                                                <td><strong>ÁNGULO</strong></td>
                                                <td><strong>H.T.</strong></td>
                                                <td><strong>ÁNGULO</strong></td>
                                                <td><strong>H.T.</strong></td>
                                                <td><strong>ÁNGULO</strong></td>
                                                <td><strong>H.T.</strong></td>
                                                <td><strong>ÁNGULO</strong></td>
                                                <td><strong>H.T.</strong></td>
                                            </tr>

                                            <tr>
                                                <td>0°</td>
                                                <td>12:00</td>
                                                <td>90°</td>
                                                <td>03:00</td>
                                                <td>180°</td>
                                                <td>06:00</td>
                                                <td>270°</td>
                                                <td>09:00</td>
                                            </tr>

                                            <tr>
                                                <td>30°</td>
                                                <td>01:00</td>
                                                <td>120°</td>
                                                <td>04:00</td>
                                                <td>210°</td>
                                                <td>07:00</td>
                                                <td>300°</td>
                                                <td>10:00</td>
                                            </tr>

                                            <tr>
                                                <td>45°</td>
                                                <td>01:30</td>
                                                <td>135°</td>
                                                <td>04:30</td>
                                                <td>225°</td>
                                                <td>07:30</td>
                                                <td>315°</td>
                                                <td>10:30</td>
                                            </tr>

                                            <tr>
                                                <td>60°</td>
                                                <td>02:00</td>
                                                <td>150°</td>
                                                <td>05:00</td>
                                                <td>240°</td>
                                                <td>08:00</td>
                                                <td>330°</td>
                                                <td>11:00</td>
                                            </tr>

                                        </thead>
                                    </table>
                                </td>
                            </tr>
                        </table>

                    <p>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Observaciones:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{old('Observaciones')}}</textarea>
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
<script src="{{ asset('js/Reportes_Create.js') }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<script>
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
                            <td colspan="15">
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="text" class="form-control w-90" name="titulos[]" value="${item.text}" placeholder="Ingrese título">
                                    <td><button type="button" class="btn btn-danger btnEliminarTitulo">
                                        <i class="fa fa-times"  aria-hidden="true"></i>
                                    </button></td>
                                </div>
                            </td>
                        </tr>`;
                        $('#dynamicTable tbody').append(newTitle);
                    } else if (item.type === 'fila') {
                        let newRow = `<tr data-titulo="${item.titulo}">
                                        <td>${item.rowNumber} <input type="hidden" value="${item.rowNumber}"></td>
                                        <td><input type="text" class="form-control" name="ID[${item.titulo}]" value="${item.inputs[1]}" placeholder="ID"></td>
                                        <td><input type="text" class="form-control" name="Elemento[${item.titulo}]" value="${item.inputs[2]}" placeholder="Elemento"></td>
                                        <td><input type="text" class="form-control" name="nom_pulg[${item.titulo}]" value="${item.inputs[3]}" placeholder="Ønom (pulg)"></td>
                                        <td><input type="text" class="form-control" name="ext_pulg[${item.titulo}]" value="${item.inputs[4]}" placeholder="Øext (pulg)"></td>
                                        <td><input type="text" class="form-control" name="Long_m[${item.titulo}]" value="${item.inputs[5]}" placeholder="Long (m)"></td>
                                        <td><input type="text" class="form-control" name="Ele_iden[${item.titulo}]" value="${item.inputs[6]}" placeholder="Elementos idendificados"></td>
                                        <td><input type="text" class="form-control" name="-X[${item.titulo}]" value="${item.inputs[7]}" placeholder="(-X)"></td>
                                        <td><input type="text" class="form-control" name="+X[${item.titulo}]" value="${item.inputs[8]}" placeholder="(+X)"></td>
                                        <td><input type="text" class="form-control" name="No_Ind[${item.titulo}]" value="${item.inputs[9]}" placeholder="No Ind"></td>
                                        <td><input type="text" class="form-control" name="Dis_rela[${item.titulo}]" value="${item.inputs[10]}" placeholder="Distancia relativa"></td>
                                        <td><input type="text" class="form-control" name="HT1[${item.titulo}]" value="${item.inputs[11]}" placeholder="Horario Tecnico"></td>
                                        <td><input type="text" class="form-control" name="HT2[${item.titulo}] value="${item.inputs[12]}" placeholder="Horario Tecnico"></td>
                                        <td><input type="text" class="form-control" name="Cate[${item.titulo}]" value="${item.inputs[13]}" placeholder="Categoria"></td>
                                        <td><input type="text" class="form-control" name="Direc[${item.titulo}]" value="${item.inputs[14]}" placeholder="Direccionalidad"></td>
                                        <td><input type="text" class="form-control" name="Clas[${item.titulo}]" value="${item.inputs[15]}" placeholder="Clasificacion"></td>
                                        <td><input type="text" class="form-control" name="Porc_Refl[${item.titulo}]" value="${item.inputs[16]}" placeholder="porcentaje de reflexion (%)"></td>
                                        <td><input type="text" class="form-control" name="Fotos[${item.titulo}]" value="${item.inputs[17]}" placeholder="Fotos No"></td>
                                        <td><input type="text" class="form-control" name="Observaciones[${item.titulo}]" value="${item.inputs[18]}" placeholder="Observaciones"></td>
                                        <td><button type="button" class="btn btn-danger btnEliminar">   <i class="fa fa-times"  aria-hidden="true"></i></button></td>
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
                <td colspan="19">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control w-90" name="titulos[]" placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3 (DETALLE DE OREJA DE IZAJE 1/4)">
                        <td><button type="button" class="btn btn-danger btnEliminarTitulo">
                            <i class="fa fa-times"  aria-hidden="true"></i>
                        </button></td>
                    </div>
                </td>
            </tr>
        `;

        $('#dynamicTable tbody').append(newTitle);
        updateTitulos(); // Actualizar lista de títulos
        saveData();
        });

        $('#addBtn').click(function () {
            let numFilas = parseInt($('#numRows').val());
            // Recontar filas existentes que NO son títulos
            rowCountGlobal = $('#dynamicTable tbody tr:not(.titulo-row)').length;
            let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

            for (let i = 0; i < numFilas; i++) {
            rowCount++; // Incrementar el contador general de filas
            rowCountGlobal++; // Incrementar el contador global de filas Solo es visualmente esta variable

            let newRow = 

                    `<tr data-titulo="${lastTitle}">
                    <td>${rowCountGlobal} <input type="hidden" value="${rowCount}"></td>
                    <td><input type="text" class="form-control" name="ID[${lastTitle}][]" placeholder="ID"></td>
                    <td><input type="text" class="form-control" name="Elemento[${lastTitle}][]" placeholder="Elemento"></td>
                    <td><input type="text" class="form-control" name="nom_pulg[${lastTitle}][]" placeholder="Ønom (pulg)"></td>
                    <td><input type="text" class="form-control" name="ext_pulg[${lastTitle}][]" placeholder="Øext (pulg)"></td>
                    <td><input type="text" class="form-control" name="Long_m[${lastTitle}][]" placeholder="Long (m)"></td>
                    <td><input type="text" class="form-control" name="Ele_iden[${lastTitle}][]" placeholder="Elementos idendificados"></td>
                    <td><input type="text" class="form-control" name="-X[${lastTitle}][]" placeholder="(-X)"></td>
                    <td><input type="text" class="form-control" name="+X[${lastTitle}][]" placeholder="(+X)"></td>
                    <td><input type="text" class="form-control" name="No_Ind[${lastTitle}][]" placeholder="No_Ind"></td>
                    <td><input type="text" class="form-control" name="Dis_rela[${lastTitle}][]" placeholder="Distancia relativa al dato (m)"></td>
                    <td><input type="text" class="form-control" name="HT1[${lastTitle}][]" placeholder="Horario Tecnico"></td>
                    <td><input type="text" class="form-control" name="HT2[${lastTitle}][]" placeholder="Horario Tecnico"></td>
                    <td><input type="text" class="form-control" name="Cate[${lastTitle}][]" placeholder="Categoria"></td>
                    <td><input type="text" class="form-control" name="Direc[${lastTitle}][]" placeholder="Direccionalidad" ></td>
                    <td><input type="text" class="form-control" name="Clas[${lastTitle}][]" placeholder="Clasificacion" ></td>
                    <td><input type="text" class="form-control" name="Porc_Refl[${lastTitle}][]" placeholder="porcentaje de reflexión (%)" ></td>
                    <td><input type="text" class="form-control" name="Fotos[${lastTitle}][]" placeholder="Fotos No" ></td>
                    <td><input type="text" class="form-control" name="Observaciones[${lastTitle}][]" placeholder="Observaciones" ></td>
                    <td><button type="button" class="btn btn-danger btnEliminar">   <i class="fa fa-times"  aria-hidden="true"></i></button></td>
                    </tr>`;

                $('#dynamicTable tbody').append(newRow);
            }
            saveData();
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

        const selectedOptionLocalE = localStorage.getItem(document.querySelectorAll("form")[1].id+'_equipos');
        selectedOptionLocalE != null ?  ($('#equiposSelect').val(selectedOptionLocalE),actualizarInputsE()):"";

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect').on('change', function() {
                actualizarInputsE();
            });

        function actualizarInputsA() {
            var selectedOption = $('#accesoriosSelect').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var marca = selectedOption.data('marca') || '';
            var modelo = selectedOption.data('modelo') || '';
            var ns = selectedOption.data('ns') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#marcaInputA').val(marca);
            $('#modeloInputA').val(modelo);
            $('#nsInputA').val(ns);
        }

        const selectedOptionLocalA = localStorage.getItem(document.querySelectorAll("form")[1].id+'_accesorios');
        selectedOptionLocalA != null ?  ($('#accesoriosSelect').val(selectedOptionLocalA),actualizarInputsA()):"";

        // Evento cuando se cambia la selección en el select
        $('#accesoriosSelect').on('change', function() {
            actualizarInputsA();
        });

        function actualizarInputsA2() {
            var selectedOption = $('#accesoriosSelect2').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var marca = selectedOption.data('marca') || '';
            var modelo = selectedOption.data('modelo') || '';
            var ns = selectedOption.data('ns') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#marcaInputA2').val(marca);
            $('#modeloInputA2').val(modelo);
            $('#nsInputA2').val(ns);
        }

        const selectedOptionLocalA2 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_accesorios2');
        selectedOptionLocalA2 != null ?  ($('#accesoriosSelect2').val(selectedOptionLocalA2),actualizarInputsA2()):"";

        // Evento cuando se cambia la selección en el select
        $('#accesoriosSelect2').on('change', function() {
            actualizarInputsA2();
        });

    });

    /*FOR-01-PRO-INS-22*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-01-PRO-INS-22');
        if (!form) return;

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('FOR-01-PRO-INS-22_' + el.name, el.value);
            //});
        //});

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-01-PRO-INS-22_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-01-PRO-INS-22_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-01-PRO-INS-22_' + el.name);
                //localStorage.clear();
            });
        });
    });

</script>
@endsection