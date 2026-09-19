
@extends('adminlte::page')

@section('title', 'Orden de Trabajo/Servicio/Compra')

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
        #addRowBtn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #addRowBtn:hover {
            background-color: #0056b3;
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
<h3 align="center">Registro de Orden de Trabajo/Servicio </h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="OT_SForm" action="{{ route('editOT_S.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf 

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label">Cliente</label>
                                            <input type="text" class="form-control inputForm" name="Cliente" placeholder="Ejemplo: 640853841" value="{{ $Nombre_Cliente}}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha</label>
                                                @if($OT->Fecha == '2001-01-01')
                                                    <input type="date" class="form-control inputForm" name="Fecha">
                                                @else
                                                    <input type="date" class="form-control inputForm" name="Fecha" value="{{ $OT->Fecha }}">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Lugar</label>
                                            <input type="text" class="form-control inputForm @error('Lugar') is-invalid @enderror" name="Lugar" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . " value="{{ $OT->Lugar }}">
                                            @error('Lugar')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label">Contrato</label>
                                        <input type="text" class="form-control inputForm" name="Contrato" placeholder="Ejemplo: 640853841" value="{{ $OT->Contrato}}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Proyecto" placeholder="Ejemplo: INGENIERÍA, PROCURA, CONSTRUCCIÓN DE DUCTOS MARINOS NUEVOS PARA MANEJO DE PRODUCCIÓN DE PLATAFORMAS GENÉRICAS, A INSTALARSE EN LA SONDA DE CAMPECHE, GOLFO DE MÉXICO ...">{{ $OT->Proyecto_actividad }}</textarea>
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Material</label>
                                            <input type="text" class="form-control  inputForm @error('Material') is-invalid @enderror" name="Material"  placeholder="Ejemplo:  " value="{{ $OT->Material }}">
                                            @error('Material')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Isometrico/Plano</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Plano_isometrico" placeholder="Ejemplo: D-7205-TENTOK-A-Q-200 / D-7205-TENTOK-A-Q-201 / D-7205-TENTOK-A-Q-202 / D-7205-TENTOK-A-Q-203 / D-7205-TENTOK-A-Q-204 / D-7205-TENTOK-A-Q-205 /D-7205-TENTOK-A-Q-206 / D-7205-TENTOK-A-Q-207 / D-7205-TENTOK-A-Q-208 / D-7205-TENTOK-A-Q-209 . . . .">{{ $OT->Plano_isometrico }}</textarea>
                                            @error('Plano_isometrico')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo Cliente/AICO</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="OT_archivo" placeholder="">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            @if ($OT->OT_archivo === 'ESPERA DE DATOS' || $OT->OT_archivo === 'ESPERA DE DATO')
                                            <label class="col-form-label" for="inputSuccess">No se encontro Orden de Trabajo</label>                                              
                                                <a target="_blank" role="button" class="btn btn-secondary long-button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                                 
                                            @else
                                            
                                                <label class="col-form-label" for="inputSuccess">Ver Orden de Trabajo Actual</label>  
                                                <div>                                            
                                                    <a href="{{ asset('storage/' . $OT->OT_archivo) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                                                                     
                                                </div>
                                            @endif 
                                        </div>
                                    </div>

                                    <!--<div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo AICO</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="OC_archivo" placeholder="">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>-->

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <!--<label class="col-form-label" for="inputSuccess">Tipo</label>
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Estatus" value="OC">-->
                                        </div>
                                    </div>

                                    <input type="hidden" id="dynamicTableData" name="dynamicTableData">

                                    <button id="addRowBtn" type="button" class="btn-redondo">Agregar Detalles</button>

                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Descripción/Actividades</th>
                                                <th>Unidad</th>
                                                <th>Cantidad</th>
                                                <th>Procesos</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($detallesOT as $indice => $detalle)
                                                <tr>
                                                    <td>{{ $indice + 1 }}</td>
                                                    <td><input type="text" class="form-control" name="descripcion[]" placeholder="Descripción/Actividades" value="{{ $detalle['descripcion'] ?? '' }}"></td>
                                                    <td><input type="text" class="form-control" name="unidad[]" placeholder="Unidad/Medida" value="{{ $detalle['unidad'] ?? '' }}"></td>
                                                    <td><input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" value="{{ $detalle['cantidad'] ?? '' }}"></td>
                                                    <td><textarea class="form-control" name="procesos[]" placeholder="Procesos">{{ $detalle['procesos'] ?? '' }}</textarea></td>
                                                    <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <p>
                                    <p>
                                        <!-- Select para elegir el número de firmas -->
                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">Número de Firmas:</div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                @php
                                                    //dd($numFirmas);
                                                @endphp
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">
                                                    <option value="1" {{ $numFirmas == 1 ? 'selected' : '' }}>1 Firma</option>
                                                    <option value="2" {{ $numFirmas == 2 ? 'selected' : '' }}>2 Firmas</option>
                                                    <option value="3" {{ $numFirmas == 3 ? 'selected' : '' }}>3 Firmas</option>
                                                    <option value="4" {{ $numFirmas == 4 ? 'selected' : '' }}>4 Firmas</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- 1 UNA FIRMA-->
                                        <div id="firmas1" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[Realizo]" placeholder="Ejemplo: Realizó" value="{{ $Firmas['Realizo'] ?? '' }}"></th>
                                                    </tr>

                                                    <tr>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[NOMBRE_ENCARGADO1]" placeholder="NOMBRE DEL ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO1'] ?? '' }}"></td>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[CARGO1]" placeholder="Ejemplo: CARGO" value="{{ $Firmas['CARGO1'] ?? '' }}"></td>
                                                    </tr>

                                                    {{--<tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                    </tr>--}}
                                                </thead>
                                            </table>
                                        </div>

                                        <!-- 2 DOS FIRMAS-->
                                        <div id="firmas2" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Realizo]" placeholder="Ejemplo: Realizó" value="{{ $Firmas['Realizo'] ?? '' }}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="{{ $Firmas['Vobo1'] ?? '' }}"></th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_ENCARGADO1]" placeholder="NOMBRE DEL ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO1'] ?? 'ING. JORGE ROGELIO MOO OROPEZA' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_ENCARGADO2]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO2'] ?? 'ING. CESAR E. GARDUÑO ACOSTA' }}"></td>
                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[CARGO1]" placeholder="Ejemplo: CARGO" value="{{ $Firmas['CARGO1'] ?? 'Responsable del Proceso de Ventas' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[CARGO2]" placeholder="Ejemplo: CARGO" value="{{ $Firmas['CARGO2'] ?? 'Director General' }}"></td>
                                                    </tr>

                                                    {{--<tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO')}}"></td>
                                                    </tr>--}}
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 3 TRES FIRMAS-->
                                        <div id="firmas3" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Realizo]" placeholder="Ejemplo: Realizó" value="{{ $Firmas['Realizo'] ?? '' }}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="{{ $Firmas['Vobo1'] ?? '' }}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo2]" placeholder="Ejemplo: Vo.Bo." value="{{ $Firmas['Vobo2'] ?? '' }}"></th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO1]" placeholder="NOMBRE DEL ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO1'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO2]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO2'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO3]" placeholder="Ejemplo: NOMBRE DEL SEGUNDO ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO3'] ?? '' }}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO1]" placeholder="Ejemplo: CARGO" value="{{ $Firmas['CARGO1'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO2]" placeholder="Ejemplo: CARGO" value="{{ $Firmas['CARGO2'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO3]" placeholder="Ejemplo: CARGO" value="{{ $Firmas['CARGO3'] ?? '' }}"></td>

                                                    </tr>

                                                    {{--<tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_2DO_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO')}}"></td>
                                                    </tr>--}}
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 4 CUATRO FIRMAS-->
                                        <div id="firmas4" class="col-12" style="display: none;">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Realizo]" placeholder="Ejemplo: Realizó" value="{{ $Firmas['Realizo'] ?? '' }}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="{{ $Firmas['Vobo1'] ?? '' }}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo2]" placeholder="Ejemplo: Vo.Bo." value="{{ $Firmas['Vobo2'] ?? '' }}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo3]" placeholder="Ejemplo: Vo.Bo." value="{{ $Firmas['Vobo3'] ?? '' }}"></th>

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

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO1]" placeholder="NOMBRE DEL ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO1'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO2]" placeholder="NOMBRE DEL ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO2'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO3]" placeholder="NOMBRE DEL SEGUNDO ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO3'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO4]" placeholder="NOMBRE DEL TERCER ENCARGADO" value="{{ $Firmas['NOMBRE_ENCARGADO4'] ?? '' }}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO1]" placeholder="CARGO" value="{{ $Firmas['CARGO1'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO2]" placeholder="CARGO" value="{{ $Firmas['CARGO2'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO3]" placeholder="CARGO" value="{{ $Firmas['CARGO3'] ?? '' }}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO4]" placeholder="CARGO" value="{{ $Firmas['CARGO4'] ?? '' }}"></td>

                                                    </tr>

                                                    {{--<tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_TECNICO]" placeholder="" value="Asesoría e Inspección en Construcción Costa Fuera, S.C." readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_ENCARGADO]" placeholder="EMPRESA DEL ENCARGADO" value="{{old('EMPRESA_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_2DO_ENCARGADO]" placeholder="EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('EMPRESA_2DO_ENCARGADO')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_3RO_ENCARGADO]" placeholder="EMPRESA DEL TERCER ENCARGADO" value="{{old('EMPRESA_3RO_ENCARGADO')}}"></td>
                                                    </tr>--}}
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                    <p>
                                    <p>
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
<script src="{{ asset('js/OT_S_edit.js') }}"></script>
<script>
</script>
@endsection