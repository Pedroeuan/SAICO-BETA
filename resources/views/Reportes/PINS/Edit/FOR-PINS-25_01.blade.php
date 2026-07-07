@extends('adminlte::page')

@section('title', 'FOR-PINS-25-01')

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
<h3 align="center">REPORTE DE: {{ $Prueba }}</h3>
<h3 align="center">FORMATO: {{ $Nombre_Formato }}</h3>
<h4 align="center">{{ $formatoNombrePersonalizado }}</h4>
<br>
                <section class="content w-100">
                    <div class="card w-100">
                        <div class="card-body row w-100">
                            <form id="FOR-PINS-25-01" action="{{ route('Reportes_FOR_PINS_25_01.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                                <div style="margin-bottom: 2px;"></div>
                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                                            <input type="date" class="form-control  inputForm @error('Fecha') is-invalid @enderror" name="Detalles_Generales[Fecha]"  placeholder="Ejemplo: DD/MM/AAAA" value="{{ old('Detalles_Generales.Fecha', $Detalles_Generales['Fecha'] ?? '') }}">
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
                                            <input type="text" class="form-control  inputForm @error('Cliente') is-invalid @enderror" name="Detalles_Generales[Cliente]"  placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{old('Detalles_Generales.Cliente', $Detalles_Generales['Cliente'] ?? '')}}" readonly>
                                            @error('Cliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control  inputForm @error('Contrato') is-invalid @enderror" name="Detalles_Generales[Contrato]"  placeholder="Ejemplo: 640853841" value="{{old('Detalles_Generales.Contrato', $Detalles_Generales['Contrato'] ?? '')}}" readonly>
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Proyecto]" placeholder="Ejemplo: INGENIERÍA, PROCURA, CONSTRUCCIÓN DE DUCTOS MARINOS NUEVOS PARA MANEJO DE PRODUCCIÓN DE PLATAFORMAS GENÉRICAS, A INSTALARSE EN LA SONDA DE CAMPECHE, GOLFO DE MÉXICO ...">{{old('Detalles_Generales.Proyecto', $Detalles_Generales['Proyecto'] ?? '')}}</textarea>
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Orden_Trabajo]" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . .">{{old('Detalles_Generales.Orden_Trabajo', $Detalles_Generales['Orden_Trabajo'] ?? '')}}</textarea>
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
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Isometrico_Plano]" placeholder="Ejemplo: D-7205-TENTOK-A-Q-200 / D-7205-TENTOK-A-Q-201 / D-7205-TENTOK-A-Q-202 / D-7205-TENTOK-A-Q-203 / D-7205-TENTOK-A-Q-204 / D-7205-TENTOK-A-Q-205 /D-7205-TENTOK-A-Q-206 / D-7205-TENTOK-A-Q-207 / D-7205-TENTOK-A-Q-208 / D-7205-TENTOK-A-Q-209 . . . .">{{old('Detalles_Generales.Isometrico_Plano', $Detalles_Generales['Isometrico_Plano'] ?? '')}}</textarea>
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
                                            <input type="text" class="form-control  inputForm @error('Procedimiento') is-invalid @enderror" name="Detalles_Generales[Procedimiento]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Procedimiento', $Detalles_Generales['Procedimiento'] ?? '')}}" readonly>
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

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Tipo e Intensidad de Iluminación</label>
                                            <input type="text" class="form-control  inputForm @error('Iluminacion') is-invalid @enderror" name="Detalles_Generales[Iluminacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Iluminacion', $Detalles_Generales['Iluminacion'] ?? '')}}">
                                            @error('Iluminacion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Tipo de Inspección</label>
                                            <input type="text" class="form-control  inputForm @error('Inspeccion') is-invalid @enderror" name="Detalles_Generales[Inspeccion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Inspeccion', $Detalles_Generales['Inspeccion'] ?? '')}}">
                                            @error('Inspeccion')
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
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm " name="Detalles_Generales[idProcedimiento]" value="{{ $idProcedimiento }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>
                                    @php
                                        $datosPINS25 = $Grupo_Juntas_Re ?? [];
                                        $Componentes_Detalles_Re = $datosPINS25['componentes'] ?? [];
                                        $Inspeccion_Detalles_Re = $datosPINS25['inspeccion'] ?? $datosPINS25;
                                    @endphp

                                    <div style="margin-bottom: 5px;"></div>

                                    <div class="table-responsive">
                                        <div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-info"></i> Importante</h5>
                                            <p>La primera fila es para el llenado automatico de cada una de las columnas del formato.</p>
                                        </div>
                                        <table id="componentesTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>ID</th>
                                                    <th>Descripción del Elemento</th>
                                                    <th>Ø nom (in)</th>
                                                    <th>Longitud (in)</th>
                                                    <th>Tipo de Conexión</th>
                                                    <th>Servicio</th>
                                                    <th>Clase</th>
                                                    <th>Especificación de material</th>
                                                    <th>Observaciones</th>
                                                    <th>Eliminar</th>
                                                </tr>
                                                <tr id="componentesInputRow">
                                                    <th></th>
                                                    <th><input type="text" class="form-control componentes-default-input" data-column="1" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control componentes-default-input" data-column="2" style="width: 240px;"></th>
                                                    <th><input type="text" class="form-control componentes-default-input" data-column="3" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control componentes-default-input" data-column="4" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control componentes-default-input" data-column="5" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control componentes-default-input" data-column="6" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control componentes-default-input" data-column="7" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control componentes-default-input" data-column="8" style="width: 140px;"></th>
                                                    <th><input type="text" class="form-control componentes-default-input" data-column="9" style="width: 140px;"></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $componentesContador = 1; @endphp
                                                @foreach ($Componentes_Detalles_Re as $bloque)
                                                    @foreach ($bloque as $item)
                                                        @php $componentesTitleId = $item['grupo'] ?? $item['id'] ?? 'sin_titulo'; @endphp
                                                        @if (($item['tipo'] ?? '') == 'titulo')
                                                            <tr class="componentes-title-row" data-titulo="{{ $componentesTitleId }}">
                                                                <td colspan="10">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <input type="text" class="form-control w-90 componentes-titulo-text" name="componentes_titulos_text[{{ $componentesTitleId }}]" value="{{ $item['texto'] ?? '' }}" placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3">
                                                                        <input type="hidden" name="componentes_titulos_ids[]" value="{{ $componentesTitleId }}">
                                                                        <button type="button" class="btn btn-danger btnEliminarTituloComponente ml-2">
                                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @if (($item['tipo'] ?? '') == 'fila')
                                                            <tr class="componentes-data-row" data-titulo="{{ $componentesTitleId }}">
                                                                <td><span class="componentes-row-number">{{ $componentesContador }}</span></td>
                                                                <td><input type="text" class="form-control componentes-id-input" name="Componentes_ID[{{ $componentesTitleId }}][]" value="{{ $item['data']['ID'] ?? $componentesContador }}" placeholder="ID"></td>
                                                                <td><input type="text" class="form-control" name="Componentes_Descripcion_del_Elemento[{{ $componentesTitleId }}][]" value="{{ $item['data']['Descripcion_del_Elemento'] ?? '' }}" placeholder="Descripcion del Elemento"></td>
                                                                <td><input type="text" class="form-control" name="Componentes_0[{{ $componentesTitleId }}][]" value="{{ $item['data']['0'] ?? '' }}" placeholder="Ø nom (in)"></td>
                                                                <td><input type="text" class="form-control" name="Componentes_Longitud_in[{{ $componentesTitleId }}][]" value="{{ $item['data']['Longitud_(in)'] ?? '' }}" placeholder="Longitud (m)"></td>
                                                                <td><input type="text" class="form-control" name="Componentes_Tipo_conexion[{{ $componentesTitleId }}][]" value="{{ $item['data']['Tipo_conexion'] ?? '' }}" placeholder="Tipo de Conexión"></td>
                                                                <td><input type="text" class="form-control" name="Componentes_Servicio[{{ $componentesTitleId }}][]" value="{{ $item['data']['servicio'] ?? '' }}" placeholder="Servicio"></td>
                                                                <td><input type="text" class="form-control" name="Componentes_Clase[{{ $componentesTitleId }}][]" value="{{ $item['data']['Clase'] ?? '' }}" placeholder="Clase"></td>
                                                                <td><input type="text" class="form-control" name="Componentes_Especificacion_material[{{ $componentesTitleId }}][]" value="{{ $item['data']['Especificación_material'] ?? '' }}" placeholder="Especificación de material"></td>
                                                                <td><input type="text" class="form-control" name="Componentes_Observaciones[{{ $componentesTitleId }}][]" value="{{ $item['data']['Observaciones'] ?? '' }}" placeholder="Observaciones"></td>
                                                                <td><button type="button" class="btn btn-danger btnEliminarComponente"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                            </tr>
                                                            @php $componentesContador++; @endphp
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <input type="hidden" name="componentes_titulos_data" id="componentes_titulos_hidden">
                                    <input type="hidden" name="Tabla_CombinacionConfig_Componentes" id="tablaCombinacionConfigComponentes" value="{{ old('Tabla_CombinacionConfig_Componentes', $Datos_Equipo['TABLA_COMBINACION_CONFIG_COMPONENTES'] ?? '[]') }}">
                                    <div class="d-flex justify-content-between align-items-center w-100 mb-3">
                                        <div>
                                            <label for="componentesNumRows">Número de Filas:</label>
                                            <select id="componentesNumRows" class="form-select">
                                                @for ($i = 1; $i <= 500; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <button id="componentesAddBtn" type="button" class="btn btn-success custom-btn">Agregar Fila</button>
                                        <button id="componentesAddTituloBtn" type="button" class="btn btn-success custom-btn">Agregar Título</button>
                                        <button id="componentesPreFillBtn" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                                    </div>

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
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Descripción del elemento</th>
                                                <th>Ø nom. (in.)</th>
                                                <th>Tipo de material</th>
                                                <th>Descripción de la discontinuidad, indicación.</th>
                                                <th>No. Ind.</th>
                                                <th>LA (in.)</th>
                                                <th>LC (in.)</th>
                                                <th>d(in)</th>
                                                <th><span style="font-size: 20px; position: relative; top: 3px;"><sup>t</sup></span>a(in)</th>
                                                <th>Horario Técnico</th>
                                                <th>SC o referencia</th>
                                                <th>Dictamen y/o Recomendación</th>
                                                <th>No. Foto</th>
                                                <th>Eliminar</th>
                                            </tr>

                                                <tr id="inputRow">
                                                    <th></th> <!-- Para ID vacío -->
                                                    <th><input type="text" class="form-control default-input" data-column="1" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="2" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="3" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="4" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="5" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="6" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="7" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="8" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="9" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="10" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="11" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="12" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="13" style="width: 100px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="14" style="width: 100px;"></th>
                                                    <th></th> <!-- Para botón de eliminar -->
                                                </tr>
                                        </thead>
                                        <tbody>
                                            @php 
                                                $contador = 1; 
                                            @endphp

                                            @foreach ($Inspeccion_Detalles_Re as $bloque)
                                                @foreach ($bloque as $item)

                                                    @php
                                                        $titleId = $item['grupo'] ?? 'sin_titulo';
                                                    @endphp
                                                    <!-- TITULOS -->
                                                    @if ($item['tipo'] == 'titulo')
                                                        <tr class="titulo-row" data-titulo="{{ $titleId }}">
                                                            <td colspan="15">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <input type="text"
                                                                        class="form-control w-90 titulo-text"
                                                                        name="titulos_text[{{ $titleId }}]"
                                                                        value="{{ $item['texto'] }}"
                                                                        placeholder="Ingrese título...">

                                                                    <input type="hidden" class="titulo-id" name="titulos_ids[]" value="{{ $titleId }}">

                                                                    <td>
                                                                        <button type="button" class="btn btn-danger btnEliminarTitulo">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </td>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <!-- FILAS -->
                                                    @if ($item['tipo'] == 'fila')
                                                        <tr data-titulo="{{ $titleId }}">
                                                            <td>{{ $contador }} <input type="hidden" value="{{ $contador }}"></td>
                                                            <td><input type="text" class="form-control" name='ID[{{ $titleId }}][]' value="{{ $item['data']['ID'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='Descripcion_del_Elemento[{{ $titleId }}][]' value="{{ $item['data']['Descripcion_del_Elemento'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='0_nom[{{ $titleId }}][]' value="{{ $item['data']['0_nom'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='Tipo_material[{{ $titleId }}][]' value="{{ $item['data']['Tipo_material'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='Descripcion_discontinuidad[{{ $titleId }}][]' value="{{ $item['data']['Descripcion_discontinuidad'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='No_indicacion[{{ $titleId }}][]' value="{{ $item['data']['No_indicacion'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='LA[{{ $titleId }}][]' value="{{ $item['data']['LA'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='LC[{{ $titleId }}][]' value="{{ $item['data']['LC'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='d[{{ $titleId }}][]' value="{{ $item['data']['d'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='ta[{{ $titleId }}][]' value="{{ $item['data']['ta'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='t_h[{{ $titleId }}][]' value="{{ $item['data']['t_h'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='Referencia[{{ $titleId }}][]' value="{{ $item['data']['Referencia'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='Dictamen[{{ $titleId }}][]' value="{{ $item['data']['Dictamen'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control" name='No_foto[{{ $titleId }}][]' value="{{ $item['data']['No_foto'] ?? '' }}"></td>
                                                            <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                        </tr>

                                                        @php $contador++; @endphp
                                                    @endif
                                                    <!-- LONGITUD (CIERRA BLOQUE) -->
                                                    @if ($item['tipo'] == 'longitud')
                                                        <tr class="long-row" data-titulo="{{ $titleId }}">
                                                            <td colspan="14">Longitud Inspeccionada</td>

                                                            <td>
                                                                <input type="text"
                                                                    class="form-control long-text"
                                                                    name="Long_Inspecc[{{ $titleId }}][]"
                                                                    value="{{ $item['valor'] }}">
                                                            </td>

                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-danger btnEliminar">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            </tbody>
                                    </table>
                                    </div>
                                    <input type="hidden" id="titulos_hidden" name="titulos_data">
                                    <input type="hidden" name="Tabla_CombinacionConfig" id="tablaCombinacionConfig" value="{{ old('Tabla_CombinacionConfig', $Datos_Equipo['TABLA_COMBINACION_CONFIG'] ?? '[]') }}">
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

                                        <!--button id="addLongBtn" type="button" class="btn btn-success custom-btn">Agregar Longitud Inspeccionada</button>--->

                                        <button id="preFillBtn" type="button" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                                    </div>
                                    <p>

                                        <table class="table table-bordered table-striped dt-responsive tablas">
                                            <tr>
                                                <td>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th colspan="10" class="p-2 alert alert-warning">NOMENCLATURAS</th>
                                                            </tr>
    
                                                            <tr>
                                                                <td><strong>DNR:</strong></td>
                                                                <td>DISTANCIA DE NIVEL DE REFERENCIA</td>
                                                                <td><strong>d:</strong></td>
                                                                <td>PROFUNDIDAD DE LA INDICACIÓN</td>
                                                                <td><strong>ta</strong></td>
                                                                <td>ESPESOR DE LA PARED EN ZONA SANA ADYACENTE</td>
                                                                <td><strong>C.E. GEN.:</strong></td>
                                                                <td>CORROSIÓN EXTERNA GENERALIZADA</td>
                                                                <td><strong>SIR:</strong></td>
                                                                <td>SIN INDICACIONES RELEVANTES</td>
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
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">.
                                                    <option value="1" {{ $numFirmas == 1 ? 'selected' : '' }}>1 Firma</option>
                                                    <option value="2" {{ $numFirmas == 2 ? 'selected' : '' }}>2 Firmas</option>
                                                    <option value="3" {{ $numFirmas == 3 ? 'selected' : '' }}>3 Firmas</option>
                                                    <option value="4" {{ $numFirmas == 4 ? 'selected' : '' }}>4 Firmas</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- 1 DOS FIRMAS-->
                                        <div id="firmas1" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[Realizo]" placeholder="Ejemplo: Realizo" value="{{old('Realizo', $Firmas['Realizo'] ?? '')}}"></th>
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

                                                                        <!-- hidden si quieres guardar el ID explícitamente -->
                                                                        <input type="hidden" name="Firmas_Reportes1[ID_TECNICO]" id="IDTECNICO" value="{{old('Firmas_Reportes1.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                        <input type="text" class="form-control  inputForm" name="Firmas_Reportes1[NOMBRE_TECNICO]" id="NOMBRE_TECNICO" value="{{old('Firmas_Reportes1.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                    </div>
                                                            </div>
                                                            
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

                                                                        <!-- hidden si quieres guardar el ID explícitamente -->
                                                                        <input type="hidden" name="Firmas_Reportes2[ID_TECNICO]" id="IDTECNICO2" value="{{old('Firmas_Reportes2.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                        <input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_TECNICO]" id="NOMBRE_TECNICO2" value="{{old('Firmas_Reportes2.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        <td>

                                                        </td>
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

                                                                        <!-- hidden si quieres guardar el ID explícitamente -->
                                                                        <input type="hidden" name="Firmas_Reportes3[ID_TECNICO]" id="IDTECNICO3" value="{{old('Firmas_Reportes3.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                        <input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_TECNICO]" id="NOMBRE_TECNICO3" value="{{old('Firmas_Reportes3.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        <td>

                                                        </td>

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

                                                                    <!-- hidden si quieres guardar el ID explícitamente -->
                                                                    <input type="hidden" name="Firmas_Reportes4[ID_TECNICO]" id="IDTECNICO4" value="{{old('Firmas_Reportes4.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                    <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                    <input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_TECNICO]" id="NOMBRE_TECNICO4" value="{{old('Firmas_Reportes4.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                </div>
                                                            </div>
                                                        </td>
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
                                        <div class="form-group">
                                            <label for="imageCount">Número de imágenes a subir:</label>
                                            <select class="form-control" id="imageCount" name="imageCount" autocomplete="off">
                                                <option value="">Selecciona Cuantas Imagenes Quieres Agregar</option>
                                                @for ($i = 1; $i <= 50; $i++)
                                                    <option value="{{ $i }}">{{ $i }} Imagen{{ $i > 1 ? 'es' : '' }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="w-100">

                                            @if(!empty($Fotos_Comentarios))
                                                <div class="row">
                                                    @foreach($Fotos_Comentarios as $index => $foto)
                                                        <div class="col-sm-6" id="image-container-{{ $index }}">
                                                            <div class="form-group">
                                                                <!-- Vista previa de la imagen existente -->
                                                                <label for="replace_image_{{ $index }}">Imagen Subida {{ $index + 1 }}:</label>
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
                                                                <!-- Campo para seleccionar una nueva imagen -->
                                                                <input type="file" class="form-control image-input mt-2" id="replace_image_{{ $index }}" name="replace_images[{{ $index }}]" accept="image/*">

                                                                <!-- Campo para el comentario -->
                                                                <textarea class="form-control mt-2" name="comments[{{ $index }}]" placeholder="Comentario">{{ $foto['comentario'] }}</textarea>

                                                                <!-- Campo oculto para la imagen en base64 -->
                                                                <input type="hidden" name="images_base64[{{ $index }}]" id="replace_image_{{ $index }}-base64">

                                                                <!-- Campo oculto para mantener la ruta de la imagen existente -->
                                                                <input type="hidden" name="existing_images[{{ $index }}]" value="{{ $foto['ruta'] }}">

                                                                <!-- Campo oculto para marcar imágenes eliminadas -->
                                                                <input type="hidden" name="deleted_images[]" id="deleted_image_{{ $index }}" value="">

                                                                <!-- Botón de eliminación -->
                                                                <button type="button" class="btn btn-danger mt-2 remove-image" data-index="{{ $index }}">Eliminar</button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p>No hay imágenes disponibles.</p>
                                            @endif

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
                                                <input type="text" class="form-control  inputForm @error('Num_Soldador') is-invalid @enderror" name="Detalles_Generales[Num_Soldador]"  placeholder="Ejemplo: 12345" value="{{ old('Detalles_Generales.Num_Soldador', $Detalles_Generales['Num_Soldador'] ?? '') }}">
                                                @error('Num_Soldador')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Nombre soldador/Iniciales:</label>
                                                <input type="text" class="form-control  inputForm @error('Nombre_Soldador') is-invalid @enderror" name="Detalles_Generales[Nombre_Soldador]"  placeholder="Ejemplo: Juan Pérez" value="{{ old('Detalles_Generales.Nombre_Soldador', $Detalles_Generales['Nombre_Soldador'] ?? '') }}">
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
<script src="{{ asset('js/Reportes_Edit.js') }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<script>
    $(document).ready(function() {
        let componentesTituloCount = $('#componentesTable tbody tr.componentes-title-row').length;
        let componentesRowCount = $('#componentesTable tbody tr.componentes-data-row').length;
        let componentesMergeAdmin = null;

        // Inicializa la combinacion independiente de la tabla de componentes.
        function inicializarCombinacionComponentes() {
            if (!window.ReportesCombinacionCeldasAgrupadas || !$('#componentesTable tbody').length) {
                return;
            }

            componentesMergeAdmin = window.ReportesCombinacionCeldasAgrupadas.crearAdministrador({
                tbodySelector: '#componentesTable tbody',
                hiddenSelector: '#tablaCombinacionConfigComponentes',
                modeToggleAfterSelector: '#componentesPreFillBtn',
                dataRowSelector: 'tr.componentes-data-row',
                inferirColumnasCombinables: true
            });

            componentesMergeAdmin.init();
        }

        // Reaplica merges al cambiar la estructura de la tabla.
        function refrescarCombinacionComponentes() {
            if (componentesMergeAdmin) {
                componentesMergeAdmin.refresh();
            }
        }

        function componentesLastTitle() {
            const $lastTitle = $('#componentesTable tbody tr.componentes-title-row').last();
            return $lastTitle.length ? $lastTitle.data('titulo') : 'sin_titulo';
        }

        function componentesUpdateTitulos() {
            const titulos = [];
            $('#componentesTable tbody tr.componentes-title-row').each(function() {
                const id = $(this).data('titulo');
                const text = $(this).find('.componentes-titulo-text').val() || '';
                titulos.push({ id, text });
            });

            $('#componentes_titulos_hidden').val(JSON.stringify(titulos));
        }

        function componentesUpdateRowNumbers() {
            componentesRowCount = 0;
            $('#componentesTable tbody tr.componentes-data-row').each(function() {
                componentesRowCount++;
                $(this).find('.componentes-row-number').text(componentesRowCount);
                $(this).find('.componentes-id-input').val(componentesRowCount);
            });
        }

        $('#componentesAddTituloBtn').click(function() {
            componentesTituloCount++;

            const titleId = `componentes_titulo_${componentesTituloCount}_${Date.now()}`;
            const newTitle = `
                <tr class="componentes-title-row" data-titulo="${titleId}">
                    <td colspan="10">
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="text" class="form-control w-90 componentes-titulo-text" name="componentes_titulos_text[${titleId}]" placeholder="Ingrese título Ejemplo: SKID I PIEZA NO-3">
                            <input type="hidden" name="componentes_titulos_ids[]" value="${titleId}">
                            <button type="button" class="btn btn-danger btnEliminarTituloComponente ml-2">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;

            $('#componentesTable tbody').append(newTitle);
            componentesUpdateTitulos();
            refrescarCombinacionComponentes();
        });

        $('#componentesAddBtn').click(function() {
            const numFilas = parseInt($('#componentesNumRows').val(), 10) || 1;
            const lastTitle = componentesLastTitle();

            for (let i = 0; i < numFilas; i++) {
                componentesRowCount++;

                const newRow = `
                    <tr class="componentes-data-row" data-titulo="${lastTitle}">
                        <td><span class="componentes-row-number">${componentesRowCount}</span></td>
                        <td><input type="text" class="form-control componentes-id-input" name="Componentes_ID[${lastTitle}][]" value="${componentesRowCount}" placeholder="ID"></td>
                        <td><input type="text" class="form-control" name="Componentes_Descripcion_del_Elemento[${lastTitle}][]" placeholder="Descripcion del Elemento"></td>
                        <td><input type="text" class="form-control" name="Componentes_0[${lastTitle}][]" placeholder="Ø nom (in)"></td>
                        <td><input type="text" class="form-control" name="Componentes_Longitud_in[${lastTitle}][]" placeholder="Longitud (m)"></td>
                        <td><input type="text" class="form-control" name="Componentes_Tipo_conexion[${lastTitle}][]" placeholder="Tipo de Conexión"></td>
                        <td><input type="text" class="form-control" name="Componentes_Servicio[${lastTitle}][]" placeholder="Servicio"></td>
                        <td><input type="text" class="form-control" name="Componentes_Clase[${lastTitle}][]" placeholder="Clase"></td>
                        <td><input type="text" class="form-control" name="Componentes_Especificacion_material[${lastTitle}][]" placeholder="Especificación de material"></td>
                        <td><input type="text" class="form-control" name="Componentes_Observaciones[${lastTitle}][]" placeholder="Observaciones"></td>
                        <td><button type="button" class="btn btn-danger btnEliminarComponente"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                    </tr>`;

                $('#componentesTable tbody').append(newRow);
            }

            refrescarCombinacionComponentes();
        });

        $('#componentesTable').on('click', '.btnEliminarComponente', function() {
            const $fila = $(this).closest('tr');

            if (componentesMergeAdmin) {
                componentesMergeAdmin.handleDeleteRow($fila);
            }

            $fila.remove();
            componentesUpdateRowNumbers();
            refrescarCombinacionComponentes();
        });

        $('#componentesTable').on('click', '.btnEliminarTituloComponente', function() {
            const $title = $(this).closest('tr.componentes-title-row');
            const titleId = $title.data('titulo');

            if (componentesMergeAdmin) {
                componentesMergeAdmin.clearGroup(titleId);
            }

            $('#componentesTable tbody tr').filter(function() {
                return $(this).data('titulo') === titleId;
            }).remove();

            componentesUpdateTitulos();
            componentesUpdateRowNumbers();
            refrescarCombinacionComponentes();
        });

        $('#componentesTable').on('input', '.componentes-titulo-text', componentesUpdateTitulos);

        $('#componentesPreFillBtn').click(function() {
            $('#componentesTable tbody tr.componentes-data-row input').each(function() {
                if ($(this).val() === '') {
                    $(this).val('----');
                }
            });

            refrescarCombinacionComponentes();
        });

        document.querySelectorAll('#componentesInputRow .componentes-default-input').forEach(function(input) {
            input.addEventListener('input', function() {
                const column = parseInt(input.getAttribute('data-column'), 10);

                if (isNaN(column)) return;

                document.querySelectorAll('#componentesTable tbody tr.componentes-data-row').forEach(function(row) {
                    const target = row.querySelector(`td:nth-child(${column + 1}) input`);
                    if (target) {
                        target.value = input.value;
                    }
                });
            });
        });

        inicializarCombinacionComponentes();
        componentesUpdateTitulos();
        componentesUpdateRowNumbers();
        refrescarCombinacionComponentes();

        $('form').on('submit', componentesUpdateTitulos);
    });

/*Juntas-Resultados */
    $(document).ready(function() {
        let tituloCount = $('.titulo-row').length;
        //let tituloCount = 0; //contador de títulos creados (se incrementa al añadir un título).
        let rowCount = 0; //contador de filas por título (se reinicia a 0 cuando se crea un nuevo título).
        let rowCountGlobal = 0; //contador global/visual de filas (se usa para numerar las filas en la tabla).

            $('#addTituloBtn').click(function () {
                verificarYAgregarLongitud();
                tituloCount++;
                rowCount = 0; // Reiniciar el contador de filas para este título
                // ID único: counter + timestamp (evita duplicados aunque el texto sea igual)
                const titleId = `titulo_${tituloCount}_${Date.now()}`;

                let newTitle = `
                <tr class="titulo-row" data-titulo="${titleId}">
                    <td colspan="15">
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
            });

            $('#addLongBtn').click(function () {
                verificarYAgregarLongitud();
                //let numFilas = parseInt($('#numRows').val());
                let numFilas = parseInt($('#numRows').val(), 10) || 1;
                // Recontar filas existentes que NO son títulos
                rowCountGlobal = $('#dynamicTable tbody tr').not('.titulo-row, .long-row').length;

                let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

                let newTitle = `
                <!--<tr class="titulo-row long-row" data-titulo="${lastTitle}">-->
                    <tr class="long-row" data-titulo="${lastTitle}">
                    <td colspan="14"> Longitud Inspeccionada</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="text" class="form-control w-90 long-text" name="Long_Inspecc[${lastTitle}][]">
                            <td><button type="button" class="btn btn-danger btnEliminar">
                                <i class="fa fa-times"  aria-hidden="true"></i>
                            </button></td>
                        </div>
                    </td>
                </tr>
            `;

            $('#dynamicTable tbody').append(newTitle);
            updateTitulos(); // Actualizar lista de títulos
            });

            $('#addBtn').click(function () {
                verificarYAgregarLongitud();
                //let numFilas = parseInt($('#numRows').val());
                let numFilas = parseInt($('#numRows').val(), 10) || 1;
                // Recontar filas existentes que NO son títulos
                //rowCountGlobal = $('#dynamicTable tbody tr:not(.titulo-row)').length;
                rowCountGlobal = $('#dynamicTable tbody tr').not('.titulo-row, .long-row').length;

                let lastTitle = $('.titulo-row').length > 0 ? $('.titulo-row').last().data('titulo') : 'sin_titulo';

                for (let i = 0; i < numFilas; i++) {
                rowCount++; // Incrementar el contador general de filas
                rowCountGlobal++; // Incrementar el contador global de filas Solo es visualmente esta variable

                let newRow = 
                    `<tr data-titulo="${lastTitle}">
                    <td>${rowCountGlobal} <input type="hidden" value="${rowCount}"></td>
                    <td><input type="text" class="form-control" name="ID[${lastTitle}][]" placeholder="ID" value="${rowCountGlobal}"></td>
                    <td><input type="text" class="form-control" name="Descripcion_del_Elemento[${lastTitle}][]" placeholder="Descripción del Elemento"></td>
                    <td><input type="text" class="form-control" name="0_nom[${lastTitle}][]" placeholder="Ø nom. (in.)"></td>
                    <td><input type="text" class="form-control" name="Tipo_material[${lastTitle}][]" placeholder="Tipo de Material"></td>
                    <td><input type="text" class="form-control" name="Descripcion_discontinuidad[${lastTitle}][]" placeholder="Descripción de la discontinuidad, indicación."></td>
                    <td><input type="text" class="form-control" name="No_indicacion[${lastTitle}][]" placeholder="No. Indicación"></td>
                    <td><input type="text" class="form-control" name="LA[${lastTitle}][]" placeholder="LA (in.)"></td>
                    <td><input type="text" class="form-control" name="LC[${lastTitle}][]" placeholder="LC (in)"></td>
                    <td><input type="text" class="form-control" name="d[${lastTitle}][]" placeholder="d (in)"></td>
                    <td><input type="text" class="form-control" name="ta[${lastTitle}][]" placeholder="ta (in)"></td>
                    <td><input type="text" class="form-control" name="t_h[${lastTitle}][]" placeholder="Horario Técnico"></td>
                    <td><input type="text" class="form-control" name="Referencia[${lastTitle}][]" placeholder="SC o referencia"></td>
                    <td><input type="text" class="form-control" name="Dictamen[${lastTitle}][]" placeholder="Dictamen y/o Recomendación"></td>
                    <td><input type="text" class="form-control" name="No_foto[${lastTitle}][]" placeholder="No. Foto"></td>
                    <td><button type="button" class="btn btn-danger btnEliminar">   <i class="fa fa-times"  aria-hidden="true"></i></button></td>
                </tr>`;

                $('#dynamicTable tbody').append(newRow);
            }
            verificarYAgregarLongitud();
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
            // Actualizar el campo oculto con [{id,text},...]
            updateTitulos();
            // Eliminar los datos de sessionStorage
            //sessionStorage.removeItem('dynamicTableData'); // Borra solo los datos de la tabla
            //sessionStorage.clear(); // Alternativa: Borra todo el sessionStorage
            // Deshabilitar el botón de submit y cambiar el texto (opcional)
            let submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true).text('Guardando...');
            // Opcional: Agregar un indicador de carga
            submitButton.append(' <i class="fa fa-spinner fa-spin"></i>');
        });

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
            if (contadorBloque === 22) {

                const lastTitle = $row.data('titulo') || 'sin_titulo';

                /*const newLong = `
                    <tr class="long-row" data-titulo="${lastTitle}">
                        <td colspan="14">Longitud Inspeccionada</td>
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
                }*/

                // 🔄 cerrar bloque
                contadorBloque = 0;
            }
        });
    }


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

            function actualizarInputsbyp() {
                var selectedOption = $('#blockyprobetaSelect').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var nombre = selectedOption.data('nombre') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#nombreInputbyp').val(nombre);
                $('#nsInputbyp').val(ns);
            }

            // Evento cuando se cambia la selección en el select
            $('#blockyprobetaSelect').on('change', function() {
                actualizarInputsbyp();
            });

            function actualizarInputsA1() {
                var selectedOption = $('#accesoriosSelect1').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA1').val(marca);
                $('#modeloInputA1').val(modelo);
                $('#nsInputA1').val(ns);
            }
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect1').on('change', function() {
                    actualizarInputsA1();
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
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect2').on('change', function() {
                    actualizarInputsA2();
                });

            function actualizarInputsA3() {
                var selectedOption = $('#accesoriosSelect3').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA3').val(marca);
                $('#modeloInputA3').val(modelo);
                $('#nsInputA3').val(ns);
            }
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect3').on('change', function() {
                    actualizarInputsA3();
                });

            function actualizarInputsA4() {
                var selectedOption = $('#accesoriosSelect4').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA4').val(marca);
                $('#modeloInputA4').val(modelo);
                $('#nsInputA4').val(ns);
            }
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect4').on('change', function() {
                    actualizarInputsA4();
                });

            function actualizarInputsA5() {
                var selectedOption = $('#accesoriosSelect5').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA5').val(marca);
                $('#modeloInputA5').val(modelo);
                $('#nsInputA5').val(ns);
            }
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect5').on('change', function() {
                    actualizarInputsA5();
                });

            function actualizarInputsA6() {
                var selectedOption = $('#accesoriosSelect6').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA6').val(marca);
                $('#modeloInputA6').val(modelo);
                $('#nsInputA6').val(ns);
            }
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect6').on('change', function() {
                    actualizarInputsA6();
                });

            function actualizarInputsA7() {
                var selectedOption = $('#accesoriosSelect7').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA7').val(marca);
                $('#modeloInputA7').val(modelo);
                $('#nsInputA7').val(ns);
            }
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect7').on('change', function() {
                    actualizarInputsA7();
                });

            function actualizarInputsA8() {
                var selectedOption = $('#accesoriosSelect8').find('option:selected');

                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var ns = selectedOption.data('ns') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#marcaInputA8').val(marca);
                $('#modeloInputA8').val(modelo);
                $('#nsInputA8').val(ns);
            }
            
                // Evento cuando se cambia la selección en el select
                $('#accesoriosSelect8').on('change', function() {
                    actualizarInputsA8();
                });

        function actualizarInputsE2() {
            var selectedOption = $('#equiposSelect2').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var marca = selectedOption.data('marca') || '';
            var modelo = selectedOption.data('modelo') || '';
            var ns = selectedOption.data('ns') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#marcaInputE2').val(marca);
            $('#modeloInputE2').val(modelo);
            $('#nsInputE2').val(ns);
        }

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect2').on('change', function() {
                actualizarInputsE2();
            });

            function actualizarInputsE3() {
            var selectedOption = $('#equiposSelect3').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var marca = selectedOption.data('marca') || '';
            var modelo = selectedOption.data('modelo') || '';
            var ns = selectedOption.data('ns') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#marcaInputE3').val(marca);
            $('#modeloInputE3').val(modelo);
            $('#nsInputE3').val(ns);
        }

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect3').on('change', function() {
                actualizarInputsE3();
            });
    });
</script>
<script src="{{ asset('js/Reportes_CombinacionCeldasAgrupadas.js') }}"></script>
<script src="{{ asset('js/Reportes_CombinacionCeldasAgrupadas_Edit.js') }}"></script>
@endsection
