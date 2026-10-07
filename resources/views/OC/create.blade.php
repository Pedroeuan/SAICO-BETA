
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
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Lugar_trabajo" placeholder="Ejemplo: Patio de fabricación" value="Muelle, Ciudad del Carmen">{{old('Lugar_trabajo')}}</textarea>
                                            @error('Lugar_trabajo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Vigencia</label>
                                            <input type="text" class="form-control inputForm @error('Vigencia') is-invalid @enderror" name="Vigencia"  placeholder="Ejemplo: 1 año" value="{{old('Vigencia')}}">
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
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Condiciones_pago" placeholder="Ejemplo: Condiciones de pago" value="Muelle, Ciudad del Carmen">{{old('Condiciones_pago')}}</textarea>
                                            @error('Condiciones_pago')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">CONDICIONES DE GENERALES:</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Condiciones_generales" placeholder="Ejemplo: Condiciones generales" value="Muelle, Ciudad del Carmen">{{old('Condiciones_generales')}}</textarea>
                                            @error('Condiciones_generales')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
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
    var catalogo = @json($catalogo ?? []);
</script>
<script src="{{ asset('js/OC.js') }}"></script>
<script>

</script>
@endsection


