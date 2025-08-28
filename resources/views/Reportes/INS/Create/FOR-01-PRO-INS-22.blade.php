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
            <form id="FOR-01-PRO-INS-18" action="{{route('Reportes_FOR_01_PRO_INS_22.store')}}" method="post" enctype="multipart/form-data">
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
                            <input type="text" class="form-control  inputForm @error('Tipo_de_Fluido') is-invalid @enderror" name="Detalles_Generales[Tipo_de_Fluido]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Tipo_de_Fluido')}}">
                            @error('Tipo_de_Fluido')
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
                            <input type="text" class="form-control  inputForm @error('Temperatura_de_Operacion') is-invalid @enderror" name="Detalles_Generales[Temperatura_de_Operacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Temperatura_de_Operacion')}}">
                            @error('Temperatura_de_Operacion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Lugar</label>
                            <textarea class="form-control  inputForm @error('Lugar') is-invalid @enderror" name="Detalles_Generales[Lugar]"  placeholder="Ejemplo:  ">{{old('Detalles_Generales.Lugar')}}</textarea>
                            @error('Lugar')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Espesor Nominal / Cedula</label>
                            <input type="text" class="form-control  inputForm @error('Espesor_Nominal_Cedula') is-invalid @enderror" name="Detalles_Generales[Espesor_Nominal_Cedula]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Espesor_Nominal_Cedula')}}">
                            @error('Espesor_Nominal_Cedula')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Tuberia / UDC / Isometrico / Plano</label>
                            <input type="text" class="form-control  inputForm @error('Tuberia_UDC_Isometrico_Plano') is-invalid @enderror" name="Detalles_Generales[Tuberia_UDC_Isometrico_Plano]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Tuberia_UDC_Isometrico_Plano')}}">
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
                            <input type="text" class="form-control  inputForm @error('Diametro_Nominal_NPS') is-invalid @enderror" name="Detalles_Generales[Diametro_Nominal_NPS]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Diametro_Nominal_NPS')}}">
                            @error('Diametro_Nominal_NPS')
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

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputA" name="Datos_Equipo[MARCA_SONDA]" placeholder="" value="{{old('Datos_Equipo.MARCA_SONDA')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputA" name="Datos_Equipo[MODELO_SONDA]" placeholder="" value="{{old('Datos_Equipo.MODELO_SONDA')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputA" name="Datos_Equipo[NS_SONDA]" placeholder="" value="{{old('Datos_Equipo.NS_SONDA')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ANILLO TRANSDUCTOR 2</div>

                    <div class="col-sm-50 d-flex justify-content-center">
                        <div class="form-group text-center">
                            <select class="form-select inputForm" name="blockyprobetaSelect" id="blockyprobetaSelect">
                            <option value="" selected disabled>Seleccione block de calibración</option> <!-- Opción por defecto -->
                                @foreach($idsGeneral_EyCs_BlockyProbeta as $blockyProbeta)
                                    <option value="{{ $blockyProbeta->idGeneral_EyC }}"
                                            data-marca="{{ $blockyProbeta->Marca }}"
                                            data-modelo="{{ $blockyProbeta->Modelo }}"
                                            data-ns="{{ $blockyProbeta->Serie }}">
                                        {{ $blockyProbeta->Nombre_E_P_BP }}
                                    </option>
                                @endforeach 
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputbyp" name="Datos_Equipo[MARCA_BLOCK]" placeholder="" value="{{old('Datos_Equipo.MARCA_BLOCK')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                            <input type="text" class="form-control  inputForm" id="modeloInputbyp" name="Datos_Equipo[MODELO_BLOCK]" placeholder="" value="{{old('Datos_Equipo.MODELO_BLOCK')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputbyp" name="Datos_Equipo[NS_BLOCK]" placeholder="" value="{{old('Datos_Equipo.NS_BLOCK')}}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">CABLE</div>

                    <div class="alert alert-secondary" role="alert"></div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Número de Modelos:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputE" name="Datos_Equipo[Numero_de_Modelos]" placeholder="" value="{{old('Datos_Equipo.Numero_de_Modelos')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Número de Transductores:</label>
                            <input type="text" class="form-control  inputForm" id="nsInputE" name="Datos_Equipo[GAN_HZ]" placeholder="" value="{{old('Datos_Equipo.GAN_HZ')}}">
                        </div>
                    </div>

                    </div>
                            <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                            <!--***************************************** INICIO RESULTADOS *****************************************-->

                            <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DE LA INSPECCIÓN</div>
                                    
                            <div style="margin-bottom: 5px;"></div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Frecuencia:</label>
                                            <input type="date" class="form-control  inputForm @error('Frecuencia') is-invalid @enderror" name="Detalles_Generales[Frecuencia]"  placeholder="" value="{{old('Detalles_Generales.Frecuencia')}}">
                                            @error('Frecuencia')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orientación de la Tuberia:</label>
                                            <input type="date" class="form-control  inputForm @error('Orientacion_de_la_Tuberia') is-invalid @enderror" name="Detalles_Generales[Orientacion_de_la_Tuberia]"  placeholder="" value="{{old('Detalles_Generales.Orientacion_de_la_Tuberia')}}">
                                            @error('Orientacion_de_la_Tuberia')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Referencia de la Posición del Anillo:</label>
                                            <input type="date" class="form-control  inputForm @error('Referencia_de_la_Posicion_del_Anillo') is-invalid @enderror" name="Detalles_Generales[Referencia_de_la_Posicion_del_Anillo]"  placeholder="" value="{{old('Detalles_Generales.Referencia_de_la_Posicion_del_Anillo')}}">
                                            @error('Referencia_de_la_Posicion_del_Anillo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modo de Onda:</label>
                                            <input type="date" class="form-control  inputForm @error('Modo_de_Onda') is-invalid @enderror" name="Detalles_Generales[Modo_de_Onda]"  placeholder="" value="{{old('Detalles_Generales.Modo_de_Onda')}}">
                                            @error('Modo_de_Onda')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Dirección del Disparo:</label>
                                            <input type="date" class="form-control  inputForm @error('Direccion_del_Disparo') is-invalid @enderror" name="Detalles_Generales[Direccion_del_Disparo]"  placeholder="" value="{{old('Detalles_Generales.Direccion_del_Disparo')}}">
                                            @error('Direccion_del_Disparo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Distancia de Posición del Anillo:</label>
                                            <input type="date" class="form-control  inputForm @error('Distancia_de_Posicion_del_Anillo') is-invalid @enderror" name="Detalles_Generales[Distancia_de_Posicion_del_Anillo]"  placeholder="" value="{{old('Detalles_Generales.Distancia_de_Posicion_del_Anillo')}}">
                                            @error('Distancia_de_Posicion_del_Anillo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Presión de Operación del Anillo:</label>
                                            <input type="date" class="form-control  inputForm @error('Presion_de_Operacion_del_anillo') is-invalid @enderror" name="Detalles_Generales[Presion_de_Operacion_del_anillo]"  placeholder="" value="{{old('Detalles_Generales.Presion_de_Operacion_del_anillo')}}">
                                            @error('Presion_de_Operacion_del_anillo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Tipo de Recubrimiento:</label>
                                            <input type="date" class="form-control  inputForm @error('Tipo_de_Recubrimiento') is-invalid @enderror" name="Detalles_Generales[Tipo_de_Recubrimiento]"  placeholder="" value="{{old('Detalles_Generales.Tipo_de_Recubrimiento')}}">
                                            @error('Tipo_de_Recubrimiento')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Angulo de Orientación del Anillo:</label>
                                            <input type="date" class="form-control  inputForm @error('Angulo_de_Orientacion_del_Anillo') is-invalid @enderror" name="Detalles_Generales[Angulo_de_Orientacion_del_Anillo]"  placeholder="" value="{{old('Detalles_Generales.Angulo_de_Orientacion_del_Anillo')}}">
                                            @error('Angulo_de_Orientacion_del_Anillo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Coordenadas GPS:</label>
                                            <input type="date" class="form-control  inputForm @error('Coordenadas_GPS') is-invalid @enderror" name="Detalles_Generales[Coordenadas_GPS]"  placeholder="" value="{{old('Detalles_Generales.Coordenadas_GPS')}}">
                                            @error('Coordenadas_GPS')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                            <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>

                            <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle" rowspan="2">ID</th>
                                    <th class="align-middle" colspan="2">Elemento</th>
                                    <th class="align-middle" colspan="1">Ønom (pulg)</th>
                                    <th class="align-middle" colspan="1">Øext (pulg)</th>
                                    <th class="align-middle" colspan="1">Long. (m)</th>
                                    <th class="align-middle" colspan="2">Elementos idendificados</th>
                                    <th class="align-middle" colspan="2">Distacia del disparo (m)</th>
                                    <th class="align-middle" colspan="1">No. Ind.</th>
                                    <th class="align-middle" colspan="2">Distancia relativa al dato (m)</th>
                                    <th class="align-middle" colspan="2">Horario Técnico</th>
                                    <th class="align-middle" colspan="3">Clasificación de la indicación o anomalía</th>
                                    <th class="align-middle" colspan="2">porcentaje de reflexión (%)</th>
                                    <th class="align-middle" colspan="1">Fotos No.</th>
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
                                    <th><input type="text" class="form-control default-input" data-column="3" style="width: 80px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="4" style="width: 80px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="5" style="width: 60px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="6" style="width:60px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="7" style="width:60px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="8" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="9" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="10" style="width 100px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="11" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="12" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="13" style="width: 70px;"></th>
                                    <th><input type="text" class="form-control default-input" data-column="14" style="width: 70px;"></th>
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
                                                <td><strong>C12:</strong></td>
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
                                                <th colspan="6" class="p-2 alert alert-warning">RELACIÓN ENTRE ÁNGULOS DE LA DIRECCIONALIDAD Y HORARIOS TÉCNICOS</th>
                                            </tr>

                                            <tr>
                                                <td><strong>ÁNGULO</strong></td>
                                                <td>0°</td>
                                                <td>30°</td>
                                                <td>45°</td>
                                                <td>60°</td>
                                                <td><strong>H.T.</strong></td>
                                                <td>12:00</td>
                                                <td>01:00</td>
                                                <td>01:30</td>
                                                <td>02:00</td>
                                                <td><strong>ÁNGULO</strong></td>
                                                <td>90°</td>
                                                <td>120°</td>
                                                <td>135°</td>
                                                <td>150°</td>
                                                <td><strong>H.T.</strong></td>
                                                <td>03:00</td>
                                                <td>04:00</td>
                                                <td>04:30</td>
                                                <td>05:00</td>
                                                <td><strong>ÁNGULO</strong></td>
                                                <td>180°</td>
                                                <td>210°</td>
                                                <td>225°</td>
                                                <td>240°</td>
                                                <td><strong>H.T.</strong></td>
                                                <td>06:00</td>
                                                <td>07:00</td>
                                                <td>07:30</td>
                                                <td>08:00</td>
                                                <td><strong>ÁNGULO</strong></td>
                                                <td>270°</td>
                                                <td>300°</td>
                                                <td>315°</td>
                                                <td>330°</td>
                                                <td><strong>H.T.</strong></td>
                                                <td>09:00</td>
                                                <td>10:00</td>
                                                <td>10:30</td>
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
            const savedData = sessionStorage.getItem('dynamicTableData');
            if (savedData) {
                const tableData = JSON.parse(savedData);
                
                // Restaurar contadores
                tituloCount = tableData.filter(item => item.type === 'titulo').length;
                rowCountGlobal = tableData.filter(item => item.type === 'fila').length;
                
                tableData.forEach((item) => {
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
                                        <td><input type="text" class="form-control" name="Elemento[${item.titulo}]" value="${item.inputs[1]}" placeholder="Elemento"></td>
                                        <td><input type="text" class="form-control" name="Ønom_(pulg)[${item.titulo}]" value="${item.inputs[2]}" placeholder="Ønom_(pulg)"></td>
                                        <td><input type="text" class="form-control" name="Øext_(pulg)[${item.titulo}]" value="${item.inputs[3]}" placeholder="Øext_(pulg)"></td>
                                        <td><input type="text" class="form-control" name="Long_(m)[${item.titulo}]" value="${item.inputs[4]}" placeholder="Long_(m)"></td>
                                        <td><input type="text" class="form-control" name=Elementos_idendificados[${item.titulo}]" value="${item.inputs[5]}" placeholder="Elementos_idendificados"></td>
                                        <td><input type="text" class="form-control" name="(-X)[${item.titulo}]" value="${item.inputs[6]}" placeholder="(-X)"></td>
                                        <td><input type="text" class="form-control" name="(+X)[${item.titulo}]" value="${item.inputs[7]}" placeholder="(+X)"></td>
                                        <td><input type="text" class="form-control" name="No_Ind[${item.titulo}]" value="${item.inputs[8]}" placeholder="No_Ind"></td>
                                        <td><input type="text" class="form-control" name="Distancia_relativa_al_dato_(m)[${item.titulo}]" value="${item.inputs[9]}" placeholder="Distancia_relativa_al_dato_(m)"></td>
                                        <td><input type="text" class="form-control" name="Horario_Tecnico[${item.titulo}]" value="${item.inputs[13]}" placeholder="Horario_Tecnico"></td>
                                        <td><input type="text" class="form-control" name="Categoría[${item.titulo}]" value="${item.inputs[14]}" placeholder="Categoria"></td>
                                        <td><input type="text" class="form-control" name="Direccionalidad[${item.titulo}]" value="${item.inputs[14]}" placeholder="Direccionalidad"></td>
                                        <td><input type="text" class="form-control" name="Clasificacion[${item.titulo}]" value="${item.inputs[14]}" placeholder="Clasificacion"></td>
                                        <td><input type="text" class="form-control" name="porcentaje_de_reflexion_(%)[${item.titulo}]" value="${item.inputs[14]}" placeholder="porcentaje_de_reflexion_(%)"></td>
                                        <td><input type="text" class="form-control" name="Fotos_No[${item.titulo}]" value="${item.inputs[14]}" placeholder="Fotos_No"></td>
                                        <td><input type="text" class="form-control" name="Observaciones[${item.titulo}]" value="${item.inputs[14]}" placeholder="Observaciones"></td>
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
                <td colspan="14">
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
                    <td>${rowCountGlobal} <input type="hidden" value="${rowCount}">
                    </td><td><input type="text" class="form-control" name="ID[${lastTitle}][]" placeholder="ID"></td>
                    <td><input type="text" class="form-control" name="Elemento[${lastTitle}][]" placeholder="Elemento"></td>
                    <td><input type="text" class="form-control" name="Ønom_(pulg)[${lastTitle}][]" placeholder="Ønom_(pulg)"></td>
                    <td><input type="text" class="form-control" name="Øext_(pulg)[${lastTitle}][]" placeholder="Øext_(pulg)"></td>
                    <td><input type="text" class="form-control" name="Long_(m)[${lastTitle}][]" placeholder="Long_(m)"></td>
                    <td><input type="text" class="form-control" name="Elementos_idendificados[${lastTitle}][]" placeholder="Elementos_idendificados"></td>
                    <td><input type="text" class="form-control" name="(-X)[${lastTitle}][]" placeholder="(-X)"></td>
                    <td><input type="text" class="form-control" name="(+X)[${lastTitle}][]" placeholder="(+X)"></td>
                    <td><input type="text" class="form-control" name="No_Ind[${lastTitle}][]" placeholder="No_Ind"></td>
                    <td><input type="text" class="form-control" name="Distancia_relativa_al_dato_(m)[${lastTitle}][]" placeholder=""Distancia_relativa_al_dato_(m)"></td>
                    <td><input type="text" class="form-control" name="Horario_Tecnico[${lastTitle}][]" placeholder="Horario_Tecnico"></td>
                    <td><input type="text" class="form-control" name=Categoria[${lastTitle}][]" placeholder="Categoria"></td>
                    <td><input type="text" class="form-control" name="Direccionalidad[${lastTitle}][]" placeholder="Direccionalidad" ></td>
                    <td><input type="text" class="form-control" name=Clasificacion[${lastTitle}][]" placeholder="Clasificacion" ></td>
                    <td><input type="text" class="form-control" name="porcentaje_de_reflexión_(%)[${lastTitle}][]" placeholder="porcentaje_de_reflexión_(%)" ></td>
                    <td><input type="text" class="form-control" name="Fotos_No[${lastTitle}][]" placeholder="Fotos_No" ></td>
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