@extends('adminlte::page')

@section('title', 'FOR-PINS-17-01_01')

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
    @php
        $imagenesPorNumero = [];
        if($Fotos_Comentarios){
            foreach($Fotos_Comentarios as $foto){
                $imagenesPorNumero[$foto['imagen']] = $foto['ruta'];
                }
            }
    @endphp
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
    <div class="card w-100 p-3">
        <div class="card-body w-100">
            <form id="FOR-PINS-17-01_01" action="{{route('Reportes_FOR_PINS_17_01_01.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn my-2">Rellenar Campos Vacios "---"</button>
                <div style="margin-bottom: 2px;"></div>
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS GENERALES</div>

                    <!--IMAGEN 5-->
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="form-group">
                            <label class="col-form-label">Imagen 5</label>
                            <input type="file" class="form-control image-input" id="imagen5" name="imagen5" accept="image/*">
                            <!-- preview de nueva imagen -->
                            <div id="imagen5-preview" class="mt-2"></div>
                            <!-- base64 que se enviará al controlador -->
                            <input type="hidden" id="imagen5-base64" name="imagen5_base64">
                            <!-- ruta de imagen existente -->
                            <input type="hidden" name="imagen5_old" value="{{ $imagenesPorNumero[5] ?? '' }}">
                            <!-- mostrar imagen actual -->
                            @if(isset($imagenesPorNumero[5]))
                            <img id="imagen5-old-preview" src="{{ asset($imagenesPorNumero[5]) }}" class="img-fluid rounded shadow-sm mt-2" style="max-width:250px;">
                            @endif

                        </div>
                    </div>

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
                            <label class="col-form-label" for="inputSuccess">Equipo</label>
                            <input type="text" class="form-control  inputForm @error('Equipo') is-invalid @enderror" name="Detalles_Generales[Equipo]"  placeholder="Ejemplo:" value="{{old('Detalles_Generales.Equipo', $Detalles_Generales['Equipo'] ?? '')}}">
                            @error('Equipo')
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
                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                            <input type="text" class="form-control  inputForm @error('Ubicacion') is-invalid @enderror" name="Detalles_Generales[Ubicacion]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Ubicacion', $Detalles_Generales['Ubicacion'] ?? '')}}">
                            @error('Ubicacion')
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
                            <label class="col-form-label" for="inputSuccess">Hora de Inspeccíon</label>
                            <input type="text" class="form-control  inputForm @error('H_Inspeccion') is-invalid @enderror" name="Detalles_Generales[H_Inspeccion]"  placeholder="Ejemplo:  18:00 HRS" value="{{old('Detalles_Generales.H_Inspeccion', $Detalles_Generales['H_Inspeccion'] ?? '')}}">
                            @error('H_Inspeccion')
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

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">ESTÁNDAR DE REFERENCIA:</label>
                            <input type="text" class="form-control  inputForm @error('Stndr_refe') is-invalid @enderror" name="Detalles_Generales[Stndr_refe]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Stndr_refe', $Detalles_Generales['Stndr_refe'] ?? '')}}">
                            @error('Stndr_refe')
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
                    <!--***************************************** FIN DE DATOS GENERALES *****************************************-->
                    <!--***************************************** INICIO DATOS DEL EQUIPO *****************************************-->

                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS Y AJUSTES DEL EQUIPO</div>

                    <div style="margin-bottom: 2px;"></div>

                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">EQUIPO</div>

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
                            <input type="hidden" name="Datos_Equipo[ID_EQUIPO]" id="IDInputE" value="{{ old('Datos_Equipo.ID_EQUIPO', $Datos_Equipo['ID_EQUIPO'] ?? '') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                            <input type="text" class="form-control  inputForm" id="marcaInputE" name="Datos_Equipo[MARCA_EQUIPO]" placeholder="" value="{{ old('Datos_Equipo.MARCA_EQUIPO', $Datos_Equipo['MARCA_EQUIPO'] ?? '') }}">
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
                            <input type="text" class="form-control  inputForm" id="nsInputE" name="Datos_Equipo[NS_EQUIPO]" placeholder="" value="{{old('Datos_Equipo.NS_EQUIPO', $Datos_Equipo['NS_EQUIPO'] ?? '')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">FECHA DE CALIBRACIÓN:</label>
                            <input type="text" class="form-control  inputForm" id="fechaInputE" name="Datos_Equipo[FEC_CAL]" placeholder="" value="{{old('Datos_Equipo.FEC_CAL', $Datos_Equipo['FEC_CAL'] ?? '')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">CERTIFICADO POR:</label>
                            <input type="text" class="form-control  inputForm" id="certificadoInputE" name="Datos_Equipo[CER_POR]" placeholder="" value="{{old('Datos_Equipo.CER_POR', $Datos_Equipo['CER_POR'] ?? '')}}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">RANGO DE MEDICIÓN:</label>
                            <input type="text" class="form-control  inputForm" id="rangoInputE" name="Datos_Equipo[RAN_MED]" placeholder="" value="{{old('Datos_Equipo.RAN_MED', $Datos_Equipo['RAN_MED'] ?? '')}}">
                        </div>
                    </div>
                    
                    <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                    <!--***************************************** INICIO RESULTADOS *****************************************-->
                    <div class="col-12">
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">
                            <input type="text" class="form-control inputForm @error('Datos_Equipo.Stndr_refe1') is-invalid @enderror" 
                            name="Datos_Equipo[Stndr_refe1]"placeholder="" value="{{old('Datos_Equipo.Stndr_refe1', $Datos_Equipo['Stndr_refe1'] ?? '')}}">

                            @error('Datos_Equipo.Stndr_refe1')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!--IMAGEN 1-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Imagen 1</label>
                            <input type="file" class="form-control image-input" id="imagen1" name="imagen1" accept="image/*">
                            <!-- preview de nueva imagen -->
                            <div id="imagen1-preview" class="mt-2"></div>
                            <!-- base64 que se enviará al controlador -->
                            <input type="hidden" id="imagen1-base64" name="imagen1_base64">
                            <!-- ruta de imagen existente -->
                            <input type="hidden" name="imagen1_old" value="{{ $imagenesPorNumero[1] ?? '' }}">
                            <!-- mostrar imagen actual -->
                            @if(isset($imagenesPorNumero[1]))
                            <img id="imagen1-old-preview" src="{{ asset($imagenesPorNumero[1]) }}" class="img-fluid rounded shadow-sm mt-2" style="max-width:250px;">
                            @endif

                        </div>
                    </div>

                        <!--IMAGEN 2-->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Imagen 2</label>
                                <input type="file" class="form-control image-input" id="imagen2" name="imagen2" accept="image/*">
                                <!-- preview de nueva imagen -->
                                <div id="imagen2-preview" class="mt-2"></div>
                                <!-- base64 que se enviará al controlador -->
                                <input type="hidden" id="imagen2-base64" name="imagen2_base64">
                                <!-- ruta de imagen existente -->
                                <input type="hidden" name="imagen2_old" value="{{ $imagenesPorNumero[2] ?? '' }}">
                                @if(isset($imagenesPorNumero[2]))

                                <div class="mt-2">

                                <img id="imagen2-old-preview" src="{{ asset($imagenesPorNumero[2]) }}"
                                    class="img-fluid rounded shadow-sm mt-2"
                                    style="max-width:250px;">

                                </div>

                                @endif
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Termograma</label>
                                <input type="text" class="form-control inputForm" name="Datos_Equipo[termograma1]" value="{{old('Datos_Equipo.termograma1', $Datos_Equipo['termograma1'] ?? '')}}">
                            </div>

                            <div class="col-md-6">
                                <label>Emisividad</label>
                                <input type="text" class="form-control inputForm" name="Datos_Equipo[emisividad1]" value="{{old('Datos_Equipo.emisividad1', $Datos_Equipo['emisividad1'] ?? '')}}">
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="col-12">
                            <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">
                            <input type="text" class="form-control inputForm @error('Datos_Equipo.Stndr_refe2') is-invalid @enderror" 
                            name="Datos_Equipo[Stndr_refe2]"placeholder="" value="{{old('Datos_Equipo.Stndr_refe2', $Datos_Equipo['Stndr_refe2'] ?? '')}}">

                                @error('Datos_Equipo.Stndr_refe2')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <!--IMAGEN 3-->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Imagen 3</label>
                                <input type="file" class="form-control image-input" id="imagen3" name="imagen3" accept="image/*">
                                <!-- preview de nueva imagen -->
                                <div id="imagen3-preview" class="mt-2"></div>
                                <!-- base64 que se enviará al controlador -->
                                <input type="hidden" id="imagen3-base64" name="imagen3_base64">
                                <!-- ruta de imagen existente -->
                                <input type="hidden" name="imagen3_old" value="{{ $imagenesPorNumero[3] ?? '' }}">
                                @if(isset($imagenesPorNumero[3]))

                                <img id="imagen3-old-preview" src="{{ asset($imagenesPorNumero[3]) }}"
                                    class="img-fluid rounded shadow-sm mt-2"
                                    style="max-width:250px;">

                                @endif
                            </div>
                        </div>
                        <!--IMAGEN 4-->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Imagen 4</label>
                                <input type="file" class="form-control image-input" id="imagen4" name="imagen4" accept="image/*">
                                <!-- preview de nueva imagen -->
                                <div id="imagen4-preview" class="mt-2"></div>
                                <!-- base64 que se enviará al controlador -->
                                <input type="hidden" id="imagen4-base64" name="imagen4_base64">
                                <!-- ruta de imagen existente -->
                                <input type="hidden" name="imagen4_old" value="{{ $imagenesPorNumero[4] ?? '' }}">
                                @if(isset($imagenesPorNumero[4]))

                                <img id="imagen4-old-preview" src="{{ asset($imagenesPorNumero[4]) }}"
                                    class="img-fluid rounded shadow-sm mt-2"
                                    style="max-width:250px;">

                                @endif
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

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Termograma</label>
                            <input type="text" class="form-control inputForm" name="Datos_Equipo[termograma2]" value="{{old('Datos_Equipo.termograma2', $Datos_Equipo['termograma2'] ?? '')}}">
                        </div>

                        <div class="col-md-6">
                            <label>Emisividad</label>
                            <input type="text" class="form-control inputForm" name="Datos_Equipo[emisividad2]" value="{{old('Datos_Equipo.emisividad2', $Datos_Equipo['emisividad2'] ?? '')}}">
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <!--***************************************** FIN DE DATOS GENERALES *****************************************-->
                    <!--***************************************** INICIO DATOS DEL EQUIPO *****************************************-->

                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DE OPERACIÓN</div>

                    <div style="margin-bottom: 2px;"></div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">VOLTAJE:</label>
                                            <input type="text" class="form-control  inputForm" id="voltajeInputE" name="Datos_Equipo[voltaje]" placeholder="" value="{{old('Datos_Equipo.voltaje', $Datos_Equipo['voltaje'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">CARGA AMP:</label>
                                            <input type="text" class="form-control  inputForm" id="cargaInputE" name="Datos_Equipo[CARGA_AMP]" placeholder="" value="{{old('Datos_Equipo.CARGA_AMP', $Datos_Equipo['CARGA_AMP'] ?? '')}}">
                                        </div>
                                    </div>

                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="6" class="p-2 alert alert-warning text-center">
                                                                TABLA DE SEVERIDAD
                                                            </th>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-success">
                                                                <label>
                                                                    <input type="radio" 
                                                                        name="Datos_Equipo[severidad]" 
                                                                        value="bueno" 
                                                                        {{ isset($Datos_Equipo['severidad']) && $Datos_Equipo['severidad'] == 'bueno' ? 'checked' : '' }}>
                                                                    <strong>BUENO</strong>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                El equipo se encuentra en condiciones de operación óptimas.
                                                            </td>

                                                            <td class="text-warning">
                                                                <label>
                                                                    <input type="radio" 
                                                                        name="Datos_Equipo[severidad]" 
                                                                        value="preventivo"
                                                                        {{ isset($Datos_Equipo['severidad']) && $Datos_Equipo['severidad'] == 'preventivo' ? 'checked' : '' }}>
                                                                    <strong>PREVENTIVO</strong>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                Media anomalía, programar el paro del equipo para su reparación.
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="color:#d4b000;">
                                                                <label>
                                                                    <input type="radio" 
                                                                        name="Datos_Equipo[severidad]" 
                                                                        value="moderado"
                                                                        {{ isset($Datos_Equipo['severidad']) && $Datos_Equipo['severidad'] == 'moderado' ? 'checked' : '' }}>
                                                                    <strong>MODERADO</strong>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                Ligera anomalía, mantener en observación y programar reparación.                                                            </td>

                                                            <td class="text-danger">
                                                                <label>
                                                                    <input type="radio" 
                                                                        name="Datos_Equipo[severidad]" 
                                                                        value="no_aceptable"
                                                                        {{ isset($Datos_Equipo['severidad']) && $Datos_Equipo['severidad'] == 'no_aceptable' ? 'checked' : '' }}>
                                                                    <strong>NO ACEPTABLE</strong>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                Anomalía severa, se recomienda parar el equipo para su intervención.
                                                            </td>
                                                        </tr>

                                                    </thead>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Observaciones:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: Dentro del aspecto general no se observaron temperaturas">{{old('Datos_Equipo.Observaciones', $Datos_Equipo['Observaciones'] ?? '')}}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Nota:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Nota]" placeholder="Ejemplo: Se utilizó las técnicas de análisis comparativa cualitativa/comparativa">{{old('Datos_Equipo.Nota', $Datos_Equipo['Nota'] ?? '')}}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Recomendaciones:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Recomendaciones]" placeholder="Ejemplo: 1.- Mantener bajo observación el equipo.">{{old('Datos_Equipo.Recomendaciones', $Datos_Equipo['Recomendaciones'] ?? '')}}</textarea>
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
<script src="{{ asset('js/Reportes_Edit-For-01-16_17.js') }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<script>

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
            $('#IDInputE').val($('#equiposSelect').val() || $('#IDInputE').val() || '');
        }

        const selectedOptionLocalE = localStorage.getItem(document.querySelectorAll("form")[1].id+'_equipos');
        $('#equiposSelect').val($('#IDInputE').val());
        if (!$('#equiposSelect').val() && selectedOptionLocalE != null) {
            $('#equiposSelect').val(selectedOptionLocalE);
        }
        actualizarInputsE();

            // Evento cuando se cambia la selección en el select
            $('#equiposSelect').on('change', function() {
                actualizarInputsE();
            });
                });
    /*FOR-PINS-17-01_01*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('FOR-PINS-17-01_01');
        if (!form) return;

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('FOR-PINS-17-01_01_' + el.name, el.value);
            });

        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.value) {
                const value = localStorage.getItem('FOR-PINS-17-01_01_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
                localStorage.removeItem('FOR-PINS-17-01_01_' + el.name);
                //localStorage.clear();
            });
        });
    });

</script>
@endsection
