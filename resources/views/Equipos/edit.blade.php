
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
<br>
<h3 align="center"> Edición de equipos</h3>
<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Equipos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Consumibles</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Accesorios</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Blocks</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_5" data-toggle="tab">Complementos</a></li>
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header -->

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            @php
                            //<form action="{{route('editEquipos', $id)}}" method="post" enctype="multipart/form-data">
                                @endphp
                            <form action="{{ route('editEquipos.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                              @csrf
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP" value="{{ $generalEyC->Nombre_E_P_BP }}" onclick="cambiarColor(this.value)" placeholder="Ejemplo: Yugo">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" value="{{ $generalEyC->No_economico }}" placeholder="Ejemplo: ECO-001">
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" value="{{ $generalEyC->Marca }}" placeholder="Ejemplo: MANGAFLUX">
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" value="{{ $generalEyC->Modelo }}" placeholder="Ejemplo: DPM">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" value="{{ $generalEyC->Serie }}" placeholder="Ejemplo: N3199">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" value="{{ $generalEyC->Ubicacion }}" placeholder="Ejemplo: OFICINA">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" value="{{ $generalEyC->Almacenamiento }}" placeholder="Ejemplo: TEMPERATURA AMBIENTE, SIN POLVO, SIN HUMEDAD E INDIRECTO AL SOL">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" value="{{ $generalEyC->SAT }}" placeholder="Ejemplo: 41116500">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" value="{{ $generalEyC->BMPRO }}" placeholder="Ejemplo: 5K010014">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" ></input>
                                        </div>
                                    </div>
                                    @if ($generalEyC->Factura != 'N/A')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank">VER FACTURA</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'N/A')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN FACTURA</a>                                                
                                        </div>
                                    </div>
                                   @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Destino</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Destino }}" name="Destino" placeholder="Ejemplo: Swiber Quetzal">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Disponibilidad_Estado }}" name="Disponibilidad_Estado" placeholder="Ejemplo: SI/NO">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Foto</label>
                                            <input type="file" class="form-control inputForm" name="Foto">
                                        </div>
                                    </div>
                                    @if ($generalEyC->Foto != 'N/A')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Foto) }}" target="_blank">VER FOTO</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Foto == 'N/A')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN FOTO</a>                                                
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                          <label class="col-form-label" for="inputSuccess">Proceso</label>
                                          <!--<input type="text" class="form-control inputForm" name="Proceso" placeholder="Enter ...">-->
                                          <select class="form-control select2" style="width: 100%;" name="Proceso">
                                                <option>Elige un Proceso</option>
                                                <option value="TODOS" @if ($generalConEquipos->Proceso =='TODOS') selected="selected" @endif>TODOS</option>
                                                <option value="PINS"  @if ($generalConEquipos->Proceso =='PINS') selected="selected" @endif>PINS</option>
                                                <option value="PIMP"  @if ($generalConEquipos->Proceso =='PIMP') selected="selected" @endif>PIMP</option>
                                                <option value="PISO"  @if ($generalConEquipos->Proceso =='PISO') selected="selected" @endif>PISO</option>
                                                <option value="Q.C"  @if ($generalConEquipos->Proceso =='Q.C') selected="selected" @endif>Q.C</option>
                                                <option value="N/A"  @if ($generalConEquipos->Proceso =='N/A') selected="selected" @endif>N/A</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Método</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConEquipos->Metodo }}" name="Metodo" placeholder="Ejemplo: Verif. de Maq. de Soldar">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <label class="col-form-label" for="inputSuccess">Tipo Equipo</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConEquipos->Tipo_E }}" name="Tipo_E" placeholder="Electro Magnetico">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No de certificado</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConCertificados->No_certificado }}" name="No_certificado" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado actual</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="">
                                        </div>
                                    </div>
                                    @if ($generalConCertificados->Certificado_Actual != 'N/A')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalConCertificados->Certificado_Actual) }}" target="_blank">VER CERTIFICADO</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'N/A')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN CERTIFICADO</a>                                                
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ultima calibración</label>
                                            <input type="date" class="form-control inputForm" value="{{ $generalConCertificados->Fecha_calibracion }}" name="Fecha_calibracion">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Próxima calibración</label>
                                            <input type="date" class="form-control inputForm" value="{{ $generalConCertificados->Prox_fecha_calibracion }}" name="Prox_fecha_calibracion">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Tipo" value="EQUIPOS">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">

                                            <label class="col-form-label" for="inputSuccess">Comentario</label>
                                            <textarea class="form-control is-waning" id="inputSuccess" name="Comentario" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto.">{{ $generalEyC->Comentario }}</textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                            <!-- Contenido de la primera pestaña -->
                        <div class="tab-pane" id="tab_2">
                            <form action="">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP" onclick="cambiarColor(this.value)" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Destino</label>
                                            <input type="text" class="form-control inputForm" name="Destino" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <input type="text" class="form-control inputForm" name="Disponibilidad" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proveedor</label>
                                            <input type="text" class="form-control inputForm" name="Proveedor" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Foto</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Tipo" value="CONSUMIBLES">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Comentario</label>
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Enter ..."></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab_3">
                            <form action="">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP" onclick="cambiarColor(this.value)" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Destino</label>
                                            <input type="text" class="form-control inputForm" name="Destino" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <input type="text" class="form-control inputForm" name="Disponibilidad" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Foto</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proveedor</label>
                                            <input type="text" class="form-control inputForm" name="Proveedor" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Tipo" value="ACCESORIOS">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Comentario</label>
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Enter ..."></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab_4">
                            <form action="">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP" onclick="cambiarColor(this.value)" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Destino</label>
                                            <input type="text" class="form-control inputForm" name="Destino" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <input type="text" class="form-control inputForm" name="Disponibilidad" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Foto</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha de calibración</label>
                                            <input type="date" class="form-control inputForm" name="Fecha_calibracion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado de calibración</label>
                                            <input type="file" class="form-control inputForm" name="Num_certificado_calibracion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Tipo" value="BLOCK Y PROBETA">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Comentario</label>
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Enter ..."></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab_5">
                            <form action="">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP" onclick="cambiarColor(this.value)" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Destino</label>
                                            <input type="text" class="form-control inputForm" name="Destino" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <input type="text" class="form-control inputForm" name="Disponibilidad" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Foto</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado de calibración</label>
                                            <input type="file" class="form-control inputForm" name="Num_certificado_calibracion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Tipo" value="COMPLEMENTOS">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Comentario</label>
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Enter ..."></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Agrega más paneles de tabs según sea necesario -->
                    </div><!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div><!-- /.card -->       

@stop


<!--@push('scripts')
    <script>
        function cambiarColor(input){
            $('.inputForm')keypress(function(event){
                console.log("hola");
            })
            console.log(inputForm);
        }
    </script>
@endpush-->

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    {{--<link rel="stylesheet" href="vendor/adminlte\dist/css/Equipos.scss">--}}
@stop
