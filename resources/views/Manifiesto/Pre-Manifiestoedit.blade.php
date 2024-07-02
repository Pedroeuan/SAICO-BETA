
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
<br>
<br>
    <div class="float-left">
        <a href="{{ route('solicitud.manifiesto-regresar', ['id' => $Solicitud->idSolicitud]) }}" class="btn btn-success" role="button">Regresar</a>
    </div>
                <h3 align="center">PreManifiesto de Salida y/o Resguardo</h3>
                    <form id="manifiestoForm" action="{{ route('solicitudes.updateSolicitud', ['id' => $Solicitud->idSolicitud]) }}" method="post" enctype="multipart/form-data">
                                @csrf 
                            <div class="row">
                                <div class="col-sm-4">
                                        <div class="form-group">
                                            @php
                                            //dd($Manifiestos->Cliente);
                                            @endphp
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control inputForm" name="Cliente"  placeholder="Ejemplo: PROPETROL" value="{{ $Manifiestos->Cliente }}" required>
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
                                            <input type="text" class="form-control inputForm" name="Folio" placeholder="Ejemplo: PROP-040/24" value="{{ $Manifiestos->Folio }}" required>
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
                                            <input type="text" class="form-control inputForm" name="Destino" placeholder="Ejemplo: PATIO DE FABRICACIÓN PROTEXA" value="{{ $Manifiestos->Destino }}" required>
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
                                            <input type="date" class="form-control inputForm" id="fecha" name="Fecha_Salida" value="{{ $Manifiestos->Fecha_Salida }}" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Trabajo</label>
                                            <input type="text" class="form-control inputForm" name="Trabajo" placeholder="Ejemplo: Dureza" value="{{ $Manifiestos->Trabajo }}" required>
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
                                            <input type="text" class="form-control inputForm" name="Puesto" placeholder="Ejemplo: TEC. PND" value="{{ $Manifiestos->Puesto }}" required>
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
                                            <input type="text" class="form-control inputForm" name="Responsable" placeholder="Ejemplo: ALFREDO MARTINEZ TORRRES" value="{{ $Manifiestos->Responsable }}" required>
                                            @error('Responsable')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!--Campo Oculto para pasar el id de Solicitud -->
                                <input type="hidden" class="form-control inputForm" name="idSolicitud" placeholder="" value="{{ $Solicitud->idSolicitud }}">

                                    <h3 align="center">Salida de Equipos y Adicionales</h3>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Observaciones</label>
                                            <textarea class="form-control is-waning" id="inputSuccess" name="Observaciones" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto.">{{ $Manifiestos->Observaciones }}</textarea>
                                        </div>
                                    </div>
                                


                        <h3 align="center">Equipos y consumibles aprobados</h3>
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

                            
                            <div class="container">
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-info bg-primary">Finalizar Manifiesto</button>
                                </div>
                            </div>

                    </form>
@stop

@section('js')
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>

@endsection