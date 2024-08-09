
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
<br>
    <h2>PreManifiesto de Salida y/o Resguardo</h2>
    <br>
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-info"></i> Importante</h5>
        <p>Llena los datos generales del manifiesto como se muestra en los ejemplos</p>
    </div>
    <!--FORMULARIO -->
    <form id="manifiestoForm" action="{{route('solicitudes.storeManifiesto')}}" method="post" enctype="multipart/form-data">
        @csrf 
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Cliente</label>
                        <input type="text" class="form-control inputForm" name="Cliente"  placeholder="Ejemplo: PROPETROL" value="{{old('Cliente')}}" required>
                        @error('Cliente')
                        <br>
                        <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        </br>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Folio</label>
                        <input type="text" class="form-control inputForm" name="Folio" placeholder="Ejemplo: PROP-040/24" value="{{old('Folio')}}" required>
                        @error('Folio')
                            <br>
                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                            </br>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Destino</label>
                        <input type="text" class="form-control inputForm" name="Destino" placeholder="Ejemplo: PATIO DE FABRICACIÓN PROTEXA" value="{{old('Destino')}}" required>
                        @error('Destino')
                        <br>
                        <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        </br>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Fecha de Salida</label>
                        <input type="date" class="form-control inputForm" name="Fecha_Salida" value="{{ $Solicitud->Fecha }}" readonly>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Trabajo</label>
                        <input type="text" class="form-control inputForm" name="Trabajo" placeholder="Ejemplo: Dureza" value="{{old('Trabajo')}}" required>
                        @error('Trabajo')
                        <br>
                        <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        </br>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Puesto</label>
                        <input type="text" class="form-control inputForm" name="Puesto" placeholder="Ejemplo: TEC. PND" value="{{old('Puesto')}}" required>
                        @error('Puesto')
                            <br>
                                <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                            </br>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Responsable</label>
                        <input type="text" class="form-control inputForm" name="Responsable" placeholder="Ejemplo: ALFREDO MARTINEZ TORRRES" value="{{old('Responsable')}}" required>
                        @error('Responsable')
                        <br>
                        <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        </br>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        
                        <div class="form-check form-switch form-check-reverse">
                        <label class="form-check-label" for="flexSwitchCheckReverse"><b>En Renta</b></label>
                            <input class="form-check-input" name="Renta" type="checkbox" id="flexSwitchCheckReverse">
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-info"></i> Importante</h5>
                    <p>Nota a): Los Equipos se entregan en las siguientes condiciones: limpios,  operables para su uso y quedan al resguardo del firmante, siendo su responsabilidad de cada uno de los equipos aquí mencionados, excepto de los consumibles. Se deberá mantener en buen estado y que NO sea deteriorado por condiciones ajenas a su fin establecido. En caso de extravío o daño injustificado se tendrá que justificar el percance ocurrido a través de un reporte  dirigido al  PCVE, para determinar  la Reposición  del Equipo/ y/o accesorio. <BR>
                    Nota b): El responsable y/o la persona que recibe el equipo y adicionales de este manifiesto se compromete con el cuidado del mismo. <br>
                    Nota c): Si se requiere adjuntar más información en el campo de obsevaciones se puede agregar otra página adicional o escribir en la parte de atrás del formato.
                    </p>
                </div>
            <!--Campo Oculto para pasar el id de Solicitud -->
            <input type="hidden" class="form-control inputForm" name="idSolicitud" placeholder="" value="{{ $Solicitud->idSolicitud }}">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Observaciones</label>
                        <textarea class="form-control is-waning" id="inputSuccess" name="Observaciones" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto.">{{old('Observaciones')}}</textarea>
                    </div>
                </div>           
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> ¡Bien hecho!</h5>
                Estos son los elementos que has aprobado
                </div>
                <div class="card-body">
                    <table id="TablaAprobados" class="table table-bordered" >
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>No.ECO</th>
                                <th>Marca</th>
                                <th>Ultima calibración</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($DetallesSolicitud as $detalle)
                                @php
                                    $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                                @endphp
                                <tr id="row-{{ $detalle->idDetalles_Solicitud }}">
                                    <td>{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                    <td>{{ $general->No_economico ?? 'N/A' }}</td>
                                    <td>{{ $general->Marca ?? 'N/A' }}</td>
                                    <td>{{ $general->Ultima_Fecha_calibracion ?? 'N/A' }}</td>
                                    <td>{{ $detalle->Cantidad ?? 'N/A' }}</td>
                                    <td>{{ $detalle->Unidad ?? 'N/A' }}</td>
                                </tr>
                                    @endforeach
                            </tbody>
                    </table>
                </div>
                <p>
                <div class="container">
                    <div class="float-right">
                        <button type="submit" class="btn btn-info bg-primary">Finalizar Manifiesto</button>
                    </div>
                    <div class="float-left">
                        <!--BOTÓN -->
                        <a href="{{ route('solicitud.manifiesto-regresar', ['id' => $Solicitud->idSolicitud]) }}" class="btn btn-success" role="button">Regresar</a>
                    </div>
                </div>
</form>
@stop

@section('js')
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endsection