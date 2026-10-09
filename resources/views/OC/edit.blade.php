
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
<h3 align="center">Edición de la Orden de Compra</h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="OC" action="{{ route('OC.updateOC', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control inputForm" name="Contrato" placeholder="Ejemplo: 640853841" value="{{ $OC->Contrato}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Num. De Cotizacion</label>
                                            <input type="text" class="form-control inputForm @error('NumCotizacion') is-invalid @enderror" name="NumCotizacion"  placeholder="Ejemplo: 12345" value="{{ $detallesOCM->NumCotizacion}}">
                                            @error('NumCotizacion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Solicitud del cliente</label>
                                            <input type="text" class="form-control inputForm @error('SolicitudCliente') is-invalid @enderror" name="SolicitudCliente"  placeholder="Ejemplo: 12345" value="{{ $detallesOCM->SolicitudCliente }}">
                                            @error('SolicitudCliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Objetivo del servicio:</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Tipo_servicio" placeholder="Ejemplo: Patio de fabricación" value="Muelle, Ciudad del Carmen">{{old('Tipo_servicio', $OC->Tipo_servicio)}}</textarea>
                                            @error('Tipo_servicio')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label">Cliente</label>
                                            <input type="text" class="form-control inputForm" name="Cliente" placeholder="Ejemplo: 640853841" value="{{ $Nombre_Cliente}}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contacto</label>
                                            <input type="text" class="form-control inputForm @error('Contacto') is-invalid @enderror" name="Contacto"  placeholder="Ejemplo: Juan Pérez" value="{{$detallesOCM->Contacto}}">
                                            @error('Contacto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Puesto</label>
                                            <input type="text" class="form-control inputForm @error('Puesto') is-invalid @enderror" name="Puesto"  placeholder="Ejemplo: Ingeniero de Software" value="{{ $detallesOCM->Puesto }}">
                                            @error('Puesto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha</label>
                                            @if($OC->Fecha_solicitud == '2001-01-01')
                                                    <input type="date" class="form-control inputForm" name="Fecha_solicitud">
                                                @else
                                                    <input type="date" class="form-control inputForm" name="Fecha_solicitud" value="{{ $OC->Fecha_solicitud }}">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ciudad</label>
                                            <input type="text" class="form-control inputForm @error('Ciudad') is-invalid @enderror" name="Ciudad"  placeholder="Ejemplo: Ciudad de México" value="{{ $detallesOCM->Ciudad }}">
                                            @error('Ciudad')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Teléfono</label>
                                            <input type="text" class="form-control inputForm @error('Telefono') is-invalid @enderror" name="Telefono"  placeholder="Ejemplo: 555-1234" value="{{ $detallesOCM->Telefono }}">
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
                                                value="{{ $detallesOCM->Correo }}"
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
                                            <input type="text" class="form-control inputForm @error('Lugar_trabajo') is-invalid @enderror" name="Lugar_trabajo" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . " value="{{ $OC->Lugar_trabajo }}">
                                            @error('Lugar_trabajo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Vigencia</label>
                                            <input type="text" class="form-control inputForm @error('Vigencia') is-invalid @enderror" name="Vigencia"  placeholder="Ejemplo: 1 año" value="{{ $detallesOCM->Vigencia }}">
                                            @error('Vigencia')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número de Orden de Compra</label>
                                            <input type="text" class="form-control inputForm @error('Numero_OC') is-invalid @enderror" name="Numero_OC"  placeholder="Ejemplo: 76810" value="{{ $OC->Num_OC }}">
                                            @error('Numero_OC')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Requisición</label>
                                            <input type="text" class="form-control inputForm @error('Proyecto') is-invalid @enderror" name="Requisicion" placeholder="Ejemplo: 107068-2" value="{{$OC->Requisicion}}">
                                            @error('Requisicion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Proyecto" placeholder="Ejemplo: Patio de fabricación" value="Muelle, Ciudad del Carmen">{{$OC->Proyecto}}</textarea>
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Actualizar Orden de Compra Actual</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="OC_archivo" placeholder="">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            @if ($OC->OC_archivo === 'ESPERA DE DATOS' || $OC->OC_archivo === 'ESPERA DE DATO')
                                            <label class="col-form-label" for="inputSuccess">No se encontro Orden de Compra</label>                                              
                                                <a target="_blank" role="button" class="btn btn-secondary long-button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                                 
                                            @else
                                            
                                                <label class="col-form-label" for="inputSuccess">Ver Orden de Compra Actual</label>  
                                                <div>                                            
                                                    <a href="{{ asset('storage/' . $OC->OC_archivo) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                                                                     
                                                </div>
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
                                            @foreach($detallesOC ?? [] as $indice => $detalle)
                                                <tr>
                                                    <td>{{ $indice + 1 }}</td>
                                                    <td>
                                                        <select class="form-control catalogo-select">
                                                            <option value="">-- Seleccionar --</option>
                                                            ${catalogoData.map(item => `<option value="${item.idCatalogo_OC}" data-descripcion="${item.Descripcion}" data-unidad="${item.Unidad}" data-imagen="${item.Imagen || ''}">${item.Nombre}</option>`).join('')}
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="descripcion[]" placeholder="Descripción" value="{{ $detalle['descripcion'] ?? '' }}"></td>
                                                    <td class="text-center">
                                                        <img class="catalogo-preview-img" src="" alt="Imagen del catálogo" style="width: 52px; height: 52px; object-fit: contain; border-radius: 8px; border: 1px solid #ddd; display: none;">
                                                        <span class="catalogo-preview-placeholder text-muted small" style="display: block;">Sin imagen</span>
                                                    </td>
                                                    <td><input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" value="{{ $detalle['cantidad'] ?? '' }}"></td>
                                                    <td><input type="text" class="form-control" name="unidad[]" placeholder="Unidad/Medida" value="{{ $detalle['unidad'] ?? '' }}"></td>
                                                    <td><input type="number" class="form-control" name="valor_unitario[]" placeholder="Valor Unitario" value="{{ $detalle['valor_unitario'] ?? '' }}"></td>
                                                    <td><input type="number" class="form-control" name="valor_total[]" placeholder="Valor Total" value="{{ $detalle['valor_total'] ?? '' }}"></td>
                                                    <td><textarea class="form-control" name="descripcion[]" placeholder="Descripcion">{{ $detalle['descripcion'] ?? '' }}</textarea></td>
                                                    <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                                </tr>
                                            @endforeach
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
<script src="{{ asset('js/OC_edit.js') }}"></script>
<script>


    </script>
@endsection


