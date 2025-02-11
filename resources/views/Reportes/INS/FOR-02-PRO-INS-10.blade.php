@extends('adminlte::page')

@section('title', 'Crear Reporte')

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
                    <div class="card w-100">
                        <div class="card-body row w-100">
                            <form id="FOR-02-PRO-INS-10" action="{{route('ReportesINS.store')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                                            <input type="date" class="form-control  inputForm @error('Fecha') is-invalid @enderror" name="Fecha"  placeholder="Ejemplo: DD/MM/AAAA" value="{{old('Fecha')}}">
                                            @error('Fecha')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Reporte</label>
                                            <input type="text" class="form-control  inputForm @error('No_Reporte') is-invalid @enderror" name="No_Reporte"  placeholder="Ejemplo: 077-8DUCTOS-24" value="{{old('No_Reporte')}}">
                                            @error('No_Reporte')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control  inputForm @error('Cliente') is-invalid @enderror" name="Cliente"  placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{old('No_Reporte')}}">
                                            @error('Cliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control  inputForm @error('Contrato') is-invalid @enderror" name="Contrato"  placeholder="Ejemplo: 640853841" value="{{old('Contrato')}}">
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <input type="text" class="form-control  inputForm @error('Proyecto') is-invalid @enderror" name="Proyecto"  placeholder="Ejemplo: 640853841" value="{{old('Proyecto')}}">
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo</label>
                                            <input type="text" class="form-control  inputForm @error('Orden_Trabajo') is-invalid @enderror" name="Orden_Trabajo"  placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . . " value="{{old('Orden_Trabajo')}}">
                                            @error('Orden_Trabajo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Folio</label>
                                            <input type="text" class="form-control  inputForm @error('Folio') is-invalid @enderror" name="Proyecto"  placeholder="Ejemplo:" value="{{old('Folio')}}">
                                            @error('Folio')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Partida</label>
                                            <input type="text" class="form-control  inputForm @error('Partida') is-invalid @enderror" name="Partida"  placeholder="Ejemplo:  " value="{{old('Partida')}}">
                                            @error('Partida')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Lugar</label>
                                            <input type="text" class="form-control  inputForm @error('Lugar') is-invalid @enderror" name="Lugar"  placeholder="Ejemplo:  " value="{{old('Lugar')}}">
                                            @error('Lugar')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Isometrico/Plano</label>
                                            <input type="text" class="form-control  inputForm @error('Isometrico_Plano') is-invalid @enderror" name="Isometrico_Plano"  placeholder="Ejemplo:  " value="{{old('Isometrico_Plano')}}">
                                            @error('Isometrico_Plano')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Pieza</label>
                                            <input type="text" class="form-control  inputForm @error('Pieza') is-invalid @enderror" name="Pieza"  placeholder="Ejemplo:  " value="{{old('Pieza')}}">
                                            @error('Pieza')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Material</label>
                                            <input type="text" class="form-control  inputForm @error('Material') is-invalid @enderror" name="Material"  placeholder="Ejemplo:  " value="{{old('Material')}}">
                                            @error('Material')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Procedimiento</label>
                                            <input type="text" class="form-control  inputForm @error('Procedimiento') is-invalid @enderror" name="Procedimiento"  placeholder="Ejemplo:  " value="{{old('Procedimiento')}}">
                                            @error('Procedimiento')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Criterio de Evaluación</label>
                                            <input type="text" class="form-control  inputForm @error('Criterio_Evaluacion') is-invalid @enderror" name="Criterio_Evaluacion"  placeholder="Ejemplo:  " value="{{old('Criterio_Evaluacion')}}">
                                            @error('Criterio_Evaluacion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm" name="Detalles_Generales">
                                        </div>
                                    </div>


                                    <!--***************************************** FIN DE DATOS GENERALES *****************************************-->
                                    <!--***************************************** INICIO DATOS DEL EQUIPO *****************************************-->

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DEL EQUIPO</div>

                                    <div style="margin-bottom: 2px;"></div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">EQUIPO</div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" name="MARCA_EQUIPO" placeholder="" value="{{old('MARCA_EQUIPO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" name="MODELO_EQUIPO" placeholder="" value="{{old('MODELO_EQUIPO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" name="N_S_EQUIPO" placeholder="" value="{{old('N_S_EQUIPO')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">TRANSDUCTOR</div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" name="MARCA_TRANSDUCTOR" placeholder="" value="{{old('MARCA_TRANSDUCTOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" name="MODELO_TRANSDUCTOR" placeholder="" value="{{old('MODELO_TRANSDUCTOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" name="N_S_TRANSDUCTOR" placeholder="" value="{{old('N_S_TRANSDUCTOR')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">FRECC:</label>
                                            <input type="text" class="form-control  inputForm" name="FRECC_TRANSDUCTOR" placeholder="" value="{{old('FRECC_TRANSDUCTOR')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">BLOCK DE REFERENCIA</div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm" name="MARCA_BLOCK" placeholder="" value="{{old('MARCA_BLOCK')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm" name="MODELO_BLOCK" placeholder="" value="{{old('MODELO_BLOCK')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N.S:</label>
                                            <input type="text" class="form-control  inputForm" name="N_S_BLOCK" placeholder="" value="{{old('N_S_BLOCK')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">ACOPLANTE (MARCA Y TIPO):</div>
                                    <div>
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm" name="ACOPLANTE" placeholder="" value="{{old('ACOPLANTE')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">LONGITUD DEL CABLE:</div>
                                    <div>
                                        <div class="form-group">
                                            <input type="text" class="form-control  inputForm" name="LONGITUD_CABLE" placeholder="" value="{{old('LONGITUD_CABLE')}}">
                                        </div>
                                    </div>

                                    <div class="alert alert-secondary" role="alert"></div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">GANANCIA:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control  inputForm" name="GANANCIA" placeholder="" value="{{ old('GANANCIA') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">db</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">RANGO:</label>
                                            <input type="text" class="form-control  inputForm" name="RANGO" placeholder="" value="{{old('RANGO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">RECHAZO:</label>
                                            <input type="text" class="form-control  inputForm" name="RECHAZO" placeholder="" value="{{old('RECHAZO')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SUPERFICIE:</label>
                                            <input type="text" class="form-control  inputForm" name="SUPERFICIE" placeholder="" value="{{old('SUPERFICIE')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">PINTURA:</label>
                                            <input type="text" class="form-control  inputForm" name="PINTURA" placeholder="" value="{{old('PINTURA')}}">
                                        </div>
                                    </div>

                                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                                    <!--***************************************** INICIO RESULTADOS *****************************************-->

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>
                                    
                                    <div style="margin-bottom: 2px;"></div>

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

                                        <button id="preFillBtn" type="button" class="btn btn-warning custom-btn">Pre-rellenar "---"</button>
                                    </div>

                                    <div class="table-responsive">
                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                                        <thead>
                                                <tr>
                                                    <th colspan="7">DATOS DEL MATERIAL</th>
                                                    <th colspan="8">DATOS DE LA INDICACIÓN</th>
                                                    <th colspan="4">RESULTADOS DE LA INSPECCIÓN</th>
                                                    <th rowspan="2">Observaciones</th>
                                                    <th rowspan="2">Eliminar</th>
                                                </tr>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Elemento / Tubo</th>
                                                    <th>No. Aceptación</th>
                                                    <th>No. Serie</th>
                                                    <th>No. Colada</th>
                                                    <th>tnominal</th>
                                                    <th>Ø</th>
                                                    <th>No.Ind.</th>
                                                    <th>Tipo de Indicación</th>
                                                    <th>NR (%)</th>
                                                    <th>NI (%)</th>
                                                    <th>H.T.</th>
                                                    <th>Prof</th>
                                                    <th>LA</th>
                                                    <th>LC</th>
                                                    <th>tmáx</th>
                                                    <th>tmin</th>
                                                    <th>Metros Lineales</th>
                                                    <th>Evaluación</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <!-- Filas dinámicas aparecerán aquí -->
                                            </tbody>
                                    </table>
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
                                                            <th colspan="4" class="p-2 alert alert-warning">INDICACIONES Y HALLAZGOS</th>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>NPIR:</strong></td>
                                                            <td>NO PRESENTA INDICACIÓN RELEVANTE</td>
                                                            <td><strong>CI:</strong></td>
                                                            <td>CORROSIÓN INTERNA</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>I:</strong></td>
                                                            <td>INCLUSIÓN NO METÁLICA</td>
                                                            <td><strong>L:</strong></td>
                                                            <td>LAMINACIÓN</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>ZI:</strong></td>
                                                            <td>ZONA DE INCLUSIONES NO METALICAS</td>
                                                            <td colspan="2" rowspan="2"><strong></strong></td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>LE:</strong></td>
                                                            <td>LAMINACIÓN ESCALONADA</td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th colspan="6" class="p-2 alert alert-warning">SIMBOLOGÍA DEL REPORTE</th>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>A:</strong></td>
                                                            <td>ÁNGULO (°)</td>
                                                            <td><strong>LA:</strong></td>
                                                            <td>LONGITUD AXIAL (IN)</td>
                                                            <td rowspan="2"><strong>ta:</strong></td>
                                                            <td rowspan="2">ESPESOR DE LA PARED EN ZONA SANA ADYACENTE</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>G:</strong></td>
                                                            <td>GANANCIA (dB)</td>
                                                            <td><strong>LC:</strong></td>
                                                            <td>LONGITUD CIRCUNFERENCIAL (IN)</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>NR:</strong></td>
                                                            <td>NIVEL DE REFERENCIA (%)</td>
                                                            <td><strong>DNR:</strong></td>
                                                            <td>DISTANCIA DE NIVEL DE REFERENCIA (IN)</td>
                                                            <td><strong>H.T.</strong></td>
                                                            <td>HORARIO TÉCNICO</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>NI:</strong></td>
                                                            <td>NIVEL DE INDICACIÓN (%)</td>
                                                            <td><strong>Tmin:</strong></td>
                                                            <td>ESPESOR MÍNIMO REGISTRADO (PULG)</td>
                                                            <td><strong>d:</strong></td>
                                                            <td>PROFUNDIDAD DE LA INDICACION(IN)</td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Observaciones:</label>
                                                <textarea class="form-control  is-waning" id="inputSuccess" name="Observaciones" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{old('Observaciones')}}</textarea>
                                            </div>
                                        </div>

                                        <!-- Select para elegir el número de firmas -->
                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">Número de Firmas:</div>
                                        <div class="col-sm-15">
                                            <div class="form-group">
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                                    <option value="3">3 Firmas</option>
                                                    <option value="4">4 Firmas</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- 3 TRES FIRMAS-->
                                        <div id="firmas3" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th>Realizó</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="NOMBRE_TECNICO" placeholder="NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="NOMBRE_ENCARGADO" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="NOMBRE_2DO_ENCARGADO" placeholder="NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_2DO_ENCARGADO')}}"></td>

                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="CARGO_TECNICO" placeholder="CARGO DEL TECNICO" value="{{old('CARGO_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="PUESTO_ENCARGADO" placeholder="PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="PUESTO_2DO_ENCARGADO" placeholder="PUESTO DEL SEGUNDO ENCARGADO" value="{{old('PUESTO_2DO_ENCARGADO')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="EMPRESA_TECNICO" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="EMPRESA_ENCARGADO" placeholder="EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="EMPRESA_2DO_ENCARGADO" placeholder="EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO')}}"></td>

                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 4 CUATRO FIRMAS-->
                                        <div id="firmas4" class="col-12" style="display: none;">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th>Realizó</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>
                                                        <td style="width: 30px;"></td>
                                                        <th>Vo.Bo.</th>

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

                                                        <td><input type="text" class="form-control  inputForm" name="NOMBRE_TECNICO" placeholder="NOMBRE DEL TÉCNICO" value="{{old('NOMBRE_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="NOMBRE_ENCARGADO" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="NOMBRE_2DO_ENCARGADO" placeholder="NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_2DO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="NOMBRE_3RO_ENCARGADO" placeholder="NOMBRE DEL TERCER ENCARGADO" value="{{old('NOMBRE_3RO_ENCARGADO')}}"></td>

                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="CARGO_TECNICO" placeholder="CARGO DEL TECNICO" value="{{old('CARGO_TECNICO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="PUESTO_ENCARGADO" placeholder="PUESTO DEL ENCARGADO" value="{{old('PUESTO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="PUESTO_2DO_ENCARGADO" placeholder="PUESTO DEL SEGUNDO ENCARGADO" value="{{old('PUESTO_2DO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="PUESTO_3RO_ENCARGADO" placeholder="PUESTO DEL TERCER ENCARGADO" value="{{old('PUESTO_3RO_ENCARGADO')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="EMPRESA_TECNICO" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="EMPRESA_ENCARGADO" placeholder="EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="EMPRESA_2DO_ENCARGADO" placeholder="EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="EMPRESA_3RO_ENCARGADO" placeholder="EMPRESA DEL TERCER ENCARGADO" value="{{old('EMPRESA_3RO_ENCARGADO')}}"></td>

                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>
                                        
                                        <p>

                                        <!--IMAGENES CON COMENTARIOS-->

                                        <!-- Campos para subir imágenes y comentarios -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image1">Imagen 1:</label>
                                                <input type="file" class="form-control" id="image1" name="image1" accept="image/*">
                                                <div class="image-preview" id="image1-preview"></div>
                                                <textarea class="form-control mt-2" name="comment1" placeholder="Comentario para la imagen 1"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image2">Imagen 2:</label>
                                                <input type="file" class="form-control" id="image2" name="image2" accept="image/*">
                                                <div class="image-preview" id="image2-preview"></div>
                                                <textarea class="form-control mt-2" name="comment2" placeholder="Comentario para la imagen 2"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image3">Imagen 3:</label>
                                                <input type="file" class="form-control" id="image3" name="image3" accept="image/*">
                                                <div class="image-preview" id="image3-preview"></div>
                                                <textarea class="form-control mt-2" name="comment3" placeholder="Comentario para la imagen 3"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image4">Imagen 4:</label>
                                                <input type="file" class="form-control" id="image4" name="image4" accept="image/*">
                                                <div class="image-preview" id="image4-preview"></div>
                                                <textarea class="form-control mt-2" name="comment4" placeholder="Comentario para la imagen 4"></textarea>
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
<script>

    /*Prevenir el Enter*/
    document.getElementById('FOR-02-PRO-INS-10').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    $(document).ready(function() {
        var rowCount = 0;

        function updateRowNumbers() {
            $('#dynamicTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
            rowCount = $('#dynamicTable tbody tr').length;
        }

        $('#addBtn').click(function() {
            var numRows = $('#numRows').val();
            for (var i = 0; i < numRows; i++) {
                rowCount++;
                var newRow = `<tr>
                    <td>${rowCount}</td>
                    <td><input type="text" class="form-control" name="elemento_tubo[]" placeholder="Elemento / Tubo" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="no_aceptacion[]" placeholder="No. Aceptación" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="no_serie[]" placeholder="No. Serie" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="no_colada[]" placeholder="No. Colada" style="width: 100px;"></td>
                    <td><input type="text" class="form-control" name="tnominal[]" placeholder="tnominal" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="diametro[]" placeholder="Ø" style="width: 60px;"></td>
                    <td><input type="text" class="form-control" name="no_ind[]" placeholder="No.Ind." style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="tipo_indicacion[]" placeholder="Tipo de Indicación"></td>
                    <td><input type="text" class="form-control" name="nr[]" placeholder="NR (%)" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="ni[]" placeholder="NI (%)" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="ht[]" placeholder="H.T." style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="prof[]" placeholder="Prof" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="la[]" placeholder="LA" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="lc[]" placeholder="LC" style="width: 50px;"></td>
                    <td><input type="text" class="form-control" name="tmax[]" placeholder="tmáx" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="tmin[]" placeholder="tmin" style="width: 80px;"></td>
                    <td><input type="text" class="form-control" name="metros_lineales[]" placeholder="Metros Lineales" style="width: 60px;"></td>
                    <td><input type="text" class="form-control" name="evaluacion[]" placeholder="Evaluación" style="width: 100px;"></td>
                    <td><input type="text" class="form-control" name="observaciones[]" placeholder="Observaciones" style="width: 150px;"></td>
                    <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                </tr>`;
                $('#dynamicTable tbody').append(newRow);
            }
        });

        $('#dynamicTable').on('click', '.btnEliminar', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });

        $('#preFillBtn').click(function() {
            $('#dynamicTable tbody tr').each(function() {
                $(this).find('input').each(function() {
                    if ($(this).val() === '') {
                        $(this).val('----');
                    }
                });
            });
        });

        // Mostrar vista previa de las imágenes seleccionadas
        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(previewId).html('<img src="' + e.target.result + '" alt="Imagen" style="max-width: 100%; max-height: 100%;">');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#image1').change(function() {
            readURL(this, '#image1-preview');
        });

        $('#image2').change(function() {
            readURL(this, '#image2-preview');
        });

        $('#image3').change(function() {
            readURL(this, '#image3-preview');
        });

        $('#image4').change(function() {
            readURL(this, '#image4-preview');
        });


    });

        /*Selección de Firmas */

        document.addEventListener('DOMContentLoaded', function() {
        const numFirmasSelect = document.getElementById('numFirmas');
        const firmas3 = document.getElementById('firmas3');
        const firmas4 = document.getElementById('firmas4');

        numFirmasSelect.addEventListener('change', function() {
            if (this.value == '3') {
                firmas3.style.display = 'block';
                firmas4.style.display = 'none';
            } else if (this.value == '4') {
                firmas3.style.display = 'none';
                firmas4.style.display = 'block';
            }
        });

        // Inicializar la visibilidad de las secciones de firmas
        if (numFirmasSelect.value == '3') {
            firmas3.style.display = 'block';
            firmas4.style.display = 'none';
        } else if (numFirmasSelect.value == '4') {
            firmas3.style.display = 'none';
            firmas4.style.display = 'block';
        }
    });

    </script>
@endsection


