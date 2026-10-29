
@extends('adminlte::page')

@section('title', 'Orden de Compra')

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
<h3 align="center">Registro de Orden de Compra</h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="OC" action="{{route('OC.storeOC')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">

                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label">
                                                ¿Contrato existente?
                                            </label>

                                            <div class="ml-3">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="TieneContrato" id="contrato_si" value="si" checked>
                                                    <label class="form-check-label" for="contrato_si">Sí</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="TieneContrato" id="contrato_no" value="no">
                                                    <label class="form-check-label" for="contrato_no">No</label>
                                                </div>
                                            </div>

                                            <!-- Input visible solo si es "SI" -->
                                            <input type="text"
                                                id="campoContrato"
                                                class="form-control inputForm"
                                                name="Contrato"
                                                placeholder="Ejemplo: 640853841"
                                                value="{{ old('Contrato') }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Num. De Cotizacion</label>
                                            <input type="text" class="form-control inputForm @error('NumCotizacion') is-invalid @enderror" name="NumCotizacion"  placeholder="Ejemplo: 12345" value="{{old('NumCotizacion')}}">
                                            @error('NumCotizacion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Solicitud del cliente</label>
                                            <input type="text" class="form-control inputForm @error('SolicitudCliente') is-invalid @enderror" name="SolicitudCliente"  placeholder="Ejemplo: 12345" value="{{old('SolicitudCliente')}}">
                                            @error('SolicitudCliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Objetivo del servicio:</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Tipo_servicio" placeholder="Ejemplo: Patio de fabricación" value="Muelle, Ciudad del Carmen">{{old('Tipo_servicio')}}</textarea>
                                            @error('Tipo_servicio')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                <div class="row">
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
                                            <label class="col-form-label" for="inputSuccess">Contacto</label>
                                            <input type="text" class="form-control inputForm @error('Contacto') is-invalid @enderror" name="Contacto"  placeholder="Ejemplo: Juan Pérez" value="{{old('Contacto')}}">
                                            @error('Contacto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Puesto</label>
                                            <input type="text" class="form-control inputForm @error('Puesto') is-invalid @enderror" name="Puesto"  placeholder="Ejemplo: Ingeniero de Software" value="{{old('Puesto')}}">
                                            @error('Puesto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                                            <input type="date" class="form-control inputForm @error('Fecha_solicitud') is-invalid @enderror" name="Fecha_solicitud" value="{{ old('Fecha_solicitud', now()->format('Y-m-d')) }}">
                                            @error('Fecha_solicitud')
                                                <div class="invalid-feedback">
                                                    <span>{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ciudad</label>
                                            <input type="text" class="form-control inputForm @error('Ciudad') is-invalid @enderror" name="Ciudad"  placeholder="Ejemplo: Ciudad de México" value="Cd del Carmen">
                                            @error('Ciudad')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Teléfono</label>
                                            <input type="text" class="form-control inputForm @error('Telefono') is-invalid @enderror" name="Telefono"  placeholder="Ejemplo: 555-1234" value="{{ old('Telefono') }}">
                                            @error('Telefono')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label">
                                                Correo
                                            </label>

                                            <input type="email"
                                                class="form-control inputForm @error('Correo') is-invalid @enderror"
                                                value="{{ old('Correo') }}"
                                                name="Correo"
                                                placeholder="Ejemplo: hola@protexa.mx">

                                            @error('Correo')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Lugar/Trabajo</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Lugar_trabajo" placeholder="Ejemplo: Patio de fabricación" value="">{{old('Lugar_trabajo', 'Muelle, Ciudad del Carmen')}}</textarea>
                                            @error('Lugar_trabajo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Vigencia</label>
                                            <input type="text" class="form-control inputForm @error('Vigencia') is-invalid @enderror" name="Vigencia"  placeholder="Ejemplo: 1 año" value="{{old('Vigencia', '15 Dias')}}">
                                            @error('Vigencia')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número de Orden de Compra</label>
                                            <input type="text" class="form-control inputForm @error('Numero_OC') is-invalid @enderror" name="Numero_OC"  placeholder="Ejemplo: 76810" value="{{old('Numero_OC')}}">
                                            @error('Numero_OC')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Requisición</label>
                                            <input type="text" class="form-control inputForm @error('Requisicion') is-invalid @enderror" name="Requisicion" placeholder="Ejemplo: 107068-2" value="{{old('Requisicion')}}">
                                            @error('Requisicion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Proyecto" placeholder="Ejemplo: Patio de fabricación" value="Muelle, Ciudad del Carmen">{{old('Proyecto')}}</textarea>
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>



                                    {{--<div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha</label>
                                            <input type="date" class="form-control inputForm" name="Fecha_solicitud" value="{{ old('Fecha_solicitud') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Tipo de Servicio</label>
                                            <input type="text" class="form-control inputForm" name="Tipo_servicio" placeholder="Ejemplo: PT, R.G., MT, UT, DUREZA " value="{{old('Tipo_servicio')}}">
                                            </div>
                                    </div>--}}

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cargar Orden de Compra Original</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="OC_archivo" placeholder="">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Versión</label>
                                            <input type="text" class="form-control inputForm @error('Version') is-invalid @enderror" name="Version"  value="{{old('Version' , 'V1')}}" readonly>
                                            @error('Version')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Estatus" value="OC">
                                        </div>
                                    </div>

                                    <input type="hidden" id="dynamicTableData" name="dynamicTableData">

                                    <button id="addRowBtn" type="button" class="btn-redondo">Agregar Detalles</button>

                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Catalogo</th>
                                                <th>Descripción</th>
                                                <th>Imagen</th>
                                                <th>Cantidad</th>
                                                <th>Unidad/Medida</th>
                                                <th>Valor Unitario</th>
                                                <th>Valor Total</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas dinámicas aparecerán aquí -->
                                        </tbody>
                                    </table>

                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">Cuenta</div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Subtotal</label>
                                            <input type="text" class="form-control inputForm @error('Subtotal') is-invalid @enderror" name="Subtotal" placeholder="Ejemplo: 1000" value="{{old('Subtotal')}}">
                                            @error('Subtotal')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">IVA (16%)</label>
                                            <input type="text" class="form-control inputForm @error('IVA') is-invalid @enderror" name="IVA" placeholder="Ejemplo: 160" value="{{old('IVA')}}">
                                            @error('IVA')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Total</label>
                                            <input type="text" class="form-control inputForm @error('Total') is-invalid @enderror" name="Total" placeholder="Ejemplo: 1160" value="{{old('Total')}}">
                                            @error('Total')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <p>
                                    <p>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">NOTAS:</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Notas" placeholder="Ejemplo: Notas" value="Muelle, Ciudad del Carmen">{{old('Notas')}}</textarea>
                                            @error('Notas')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">CONDICIONES DE PAGO:</label>
                                        <select id="condicion-pago-select" class="form-select" name="CP">
                                            <option value="" {{ old('CP') ? '' : 'selected' }} disabled>Seleccione una Condición de Pago</option>
                                            @foreach($Condiciones_Pago as $CP)
                                                <option value="{{ $CP->idCondiciones_Pago }}" data-descripcion="{{ $CP->Descripcion }}" {{ old('CP') == $CP->idCondiciones_Pago ? 'selected' : '' }}>
                                                    {{ $CP->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                            <br>
                                            <textarea class="form-control  is-waning" id="condiciones-pago-textarea" name="Condiciones_pago" placeholder="Ejemplo: Condiciones de pago">{{old('Condiciones_pago')}}</textarea>
                                            @error('Condiciones_pago')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">CONDICIONES DE GENERALES:</label>
                                            <select id="condicion-general-select" class="form-select" name="CG">
                                                <option value="" {{ old('CG') ? '' : 'selected' }} disabled>Seleccione una Condición General</option>
                                                @foreach($Condiciones_Generales as $CG)
                                                    <option value="{{ $CG->idCondiciones_Generales }}" data-descripcion="{{ $CG->Descripcion }}" {{ old('CG') == $CG->idCondiciones_Generales ? 'selected' : '' }}>
                                                        {{ $CG->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <br>
                                            <textarea class="form-control  is-waning" id="condiciones-generales-textarea" name="Condiciones_generales" placeholder="Ejemplo: Condiciones generales">{{old('Condiciones_generales')}}</textarea>
                                            @error('Condiciones_generales')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>
                                    <p>
                                    <p>
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
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[Realizo]" placeholder="Ejemplo: Realizó" value=""></th>
                                                    </tr>

                                                    <tr>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[NOMBRE_ENCARGADO1]" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO1')}}"></td>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[CARGO1]" placeholder="Ejemplo: CARGO" value="{{old('CARGO1')}}"></td>
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

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Realizo]" placeholder="Ejemplo: Realizó" value="Aprobó"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Vobo1]" placeholder="Ejemplo: Vo.Bo." value="Elaboró"></th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_ENCARGADO1]" placeholder="NOMBRE DEL ENCARGADO" value="ING. . . ."></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_ENCARGADO2]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="Ing. Stephani De La Torre Camacho"></td>
                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[CARGO1]" placeholder="Ejemplo: CARGO" value="Responsable de . . ."></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[CARGO2]" placeholder="Ejemplo: CARGO" value="Encargada de Ventas"></td>
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

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Realizo]" placeholder="Ejemplo: Realizó" value=""></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo1]" placeholder="Ejemplo: Vo.Bo." value=""></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo2]" placeholder="Ejemplo: Vo.Bo." value=""></th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO1]" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO1')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO2]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO2')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO3]" placeholder="Ejemplo: NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_ENCARGADO3')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO1]" placeholder="Ejemplo: CARGO" value="{{old('CARGO1')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO2]" placeholder="Ejemplo: CARGO" value="{{old('CARGO2')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO3]" placeholder="Ejemplo: CARGO" value="{{old('CARGO3')}}"></td>

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

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Realizo]" placeholder="Ejemplo: Realizó" value=""></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo1]" placeholder="Ejemplo: Vo.Bo." value=""></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo2]" placeholder="Ejemplo: Vo.Bo." value=""></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo3]" placeholder="Ejemplo: Vo.Bo." value=""></th>

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

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO1]" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO1')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO2]" placeholder="NOMBRE DEL ENCARGADO" value="{{old('NOMBRE_ENCARGADO2')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO3]" placeholder="NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('NOMBRE_ENCARGADO3')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO4]" placeholder="NOMBRE DEL TERCER ENCARGADO" value="{{old('NOMBRE_ENCARGADO4')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO1]" placeholder="CARGO" value="{{old('CARGO1')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO2]" placeholder="CARGO" value="{{old('CARGO2')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO3]" placeholder="CARGO" value="{{old('CARGO3')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO4]" placeholder="CARGO" value="{{old('CARGO4')}}"></td>

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
    var catalogo = @json($catalogo ?? []);
</script>
<script src="{{ asset('js/OC.js') }}"></script>
<script>
    [
        ['condicion-pago-select', 'condiciones-pago-textarea'],
        ['condicion-general-select', 'condiciones-generales-textarea']
    ].forEach(([selectId, textareaId]) => {
        const select = document.getElementById(selectId);
        const textarea = document.getElementById(textareaId);

        select.addEventListener('change', () => {
            textarea.value = select.selectedOptions[0].dataset.descripcion || '';
        });
    });
</script>
@endsection
