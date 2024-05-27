
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
                <ul class="nav nav-pills justify-content-center"> 
                    @if($generalEyC->Tipo=='EQUIPOS')
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Equipos</a></li>
                    @endif
                    @if($generalEyC->Tipo=='CONSUMIBLES')
                        <li class="nav-item"><a class="nav-link active" href="#tab_2" data-toggle="tab">Consumibles</a></li>
                    @endif
                    @if($generalEyC->Tipo=='ACCESORIOS')
                        <li class="nav-item"><a class="nav-link active" href="#tab_3" data-toggle="tab">Accesorios</a></li>
                    @endif
                    @if($generalEyC->Tipo=='BLOCK Y PROBETA')
                        <li class="nav-item"><a class="nav-link active" href="#tab_4" data-toggle="tab">Block y Probeta</a></li>
                    @endif
                    @if($generalEyC->Tipo=='HERRAMIENTAS')
                        <li class="nav-item"><a class="nav-link active" href="#tab_5" data-toggle="tab">HERRAMIENTAS</a></li>
                    @endif
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header -->  
                <div class="card-body">
                 @if($generalEyC->Tipo=='EQUIPOS')  
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                              <form action="{{ route('editEquipos.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                              @csrf
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP" value="{{ $generalEyC->Nombre_E_P_BP }}" onclick="cambiarColor(this.value)"  placeholder="Ejemplo: Yugo">
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
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" ></input>
                                        </div>
                                    </div>
                                    @if ($generalEyC->Factura != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank">VER FACTURA</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN FACTURA</a>                                                
                                        </div>
                                    </div>
                                   @endif
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
                                    @if ($generalEyC->Foto != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Foto) }}" target="_blank">VER FOTO</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Foto == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN FOTO</a>                                                
                                        </div>
                                    </div>
                                    @endif
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
                                    @if ($generalConCertificados->Certificado_Actual != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalConCertificados->Certificado_Actual) }}" target="_blank">VER CERTIFICADO</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'ESPERA DE DATO')
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
                                            @if($generalConCertificados->Fecha_calibracion == '2001-01-01')
                                            <input type="date" class="form-control inputForm" name="Fecha_calibracion">
                                            @else
                                            <input type="date" class="form-control inputForm" value="{{ $generalConCertificados->Fecha_calibracion }}" name="Fecha_calibracion">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Próxima calibración</label>
                                            @if($generalConCertificados->Prox_fecha_calibracion =='2001-01-01')
                                            <input type="date" class="form-control inputForm" name="Prox_fecha_calibracion">
                                            @else
                                            <input type="date" class="form-control inputForm" value="{{ $generalConCertificados->Prox_fecha_calibracion }}" name="Prox_fecha_calibracion">
                                            @endif
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
                                    <div class="container">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                            <!-- Contenido de la primera pestaña -->
                    @endif
                    @if($generalEyC->Tipo=='CONSUMIBLES')
                            <div class="tab-pane" id="tab_2">
                            <form action="{{ route('editConsumibles.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Nombre_E_P_BP }}" name="Nombre_E_P_BP"  placeholder="Ejemplo: Yugo">
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
                                            <label class="col-form-label" for="inputSuccess">Lote</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConAlmacen->Lote }}" name="Lote" placeholder="Enter ...">
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
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    @if ($generalEyC->Factura != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank">VER FACTURA</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN FACTURA</a>                                                
                                        </div>
                                    </div>
                                   @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <input type="text" class="form-control inputForm" name="Disponibilidad_Estado" value="{{ $generalEyC->Disponibilidad_Estado }}" name="Disponibilidad_Estado" placeholder="Ejemplo: SI/NO">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proveedor</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConConsumibles->Proveedor }}" name="Proveedor" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ficha técnica</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    @if ($generalEyC->Foto != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Foto) }}" target="_blank">VER FICHA TÉCNICA</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Foto == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN FICHA TÉCNICA</a>                                                
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConCertificados->No_certificado }}" name="No_certificado">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Stock</label>
                                            <input type="number" class="form-control inputForm" value="{{ $generalConAlmacen->Stock }}" name="Stock" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Tierra/Costa Fuera</label>
                                            <!--<input type="text" class="form-control inputForm" name="Proceso" placeholder="Enter ...">-->
                                           <!-- <select class="form-control select2" style="width: 100%;" name="Tipo_TI_CO">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="TIERRA" @if ($generalConConsumibles->Tipo == 'TIERRA') selected="selected" @endif>TIERRA</option>
                                                <option value="COSTA FUERA" @if ($generalConConsumibles->Tipo == 'COSTA FUERA') selected="selected" @endif>COSTA FUERA</option>
                                            </select>
                                        </div>
                                    </div> -->
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
                                            <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Tipo" value="CONSUMIBLES">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Comentario</label>
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto.">{{ $generalEyC->Comentario }}</textarea>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--ACCESORIOS -->
                    @endif
                    @if($generalEyC->Tipo=='ACCESORIOS')
                        <div class="tab-pane" id="tab_3">
                        <form action="{{ route('editAccesorios.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Nombre_E_P_BP }}" name="Nombre_E_P_BP"  placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->No_economico }}" name="No_economico" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Marca }}" name="Marca" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Modelo }}" name="Modelo" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Serie }}" name="Serie" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Ubicacion }}" name="Ubicacion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Almacenamiento }}" name="Almacenamiento" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" value="{{ $generalEyC->Factura }}" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    @if ($generalEyC->Factura != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank">VER FACTURA</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN FACTURA</a>                                                
                                        </div>
                                    </div>
                                   @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Disponibilidad_Estado }}" name="Disponibilidad_Estado" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado</label>
                                            <input type="file" class="form-control inputForm"  name="Certificado_Actual" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    @if ($generalConCertificados->Certificado_Actual != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalConCertificados->Certificado_Actual) }}" target="_blank">VER CERTIFICADO</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN CERTIFICADO</a>                                                
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proveedor</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConAccesorios->Proveedor }}" name="Proveedor" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->SAT }}" name="SAT" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->SAT }}" name="SAT" placeholder="Enter ...">
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
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Enter ...">{{ $generalEyC->Comentario }}</textarea>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if($generalEyC->Tipo=='BLOCK Y PROBETA')
                        <div class="tab-pane" id="tab_4">
                        <form action="{{ route('editBlocks.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Nombre_E_P_BP }}" name="Nombre_E_P_BP"  placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->No_economico }}" name="No_economico" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Marca }}" name="Marca" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Modelo }}" name="Modelo" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Serie }}" name="Serie" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Ubicacion }}" name="Ubicacion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Almacenamiento }}" name="Almacenamiento" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" value="{{ $generalEyC->Factura }}" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    @if ($generalEyC->Factura != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank">VER FACTURA</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN FACTURA</a>                                                
                                        </div>
                                    </div>
                                   @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Disponibilidad_Estado }}" name="Disponibilidad_Estado" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Hoja de presentación</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    @if ($generalEyC->Foto != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Foto) }}" target="_blank">VER HOJA DE PRESENTACIÓN</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Foto == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN HOJA DE PRESENTACIÓN</a>                                                
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha de calibración</label>
                                            @if($generalConCertificados->Fecha_calibracion == '2001-01-01')
                                            <input type="date" class="form-control inputForm"  name="Fecha_calibracion" placeholder="Enter ...">
                                            @else
                                            <input type="date" class="form-control inputForm" value="{{ $generalConCertificados->Fecha_calibracion }}" name="Fecha_calibracion" placeholder="Enter ...">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No Certificado</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConCertificados->No_certificado }}" name="No_certificado" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado de calibración / Plano</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="Enter ..." multiple>
                                        </div>
                                    </div>
                                    @if ($generalConCertificados->Certificado_Actual != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalConCertificados->Certificado_Actual) }}" target="_blank">VER CERTIFICADO</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN CERTIFICADO</a>                                                
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->SAT }}" name="SAT" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->SAT }}" name="SAT" placeholder="Enter ...">
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
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Enter ...">{{ $generalEyC->Comentario }}</textarea>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if($generalEyC->Tipo=='HERRAMIENTAS')
                        <div class="tab-pane" id="tab_5">
                        <form action="{{ route('editHerramientas.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Nombre_E_P_BP }}" name="Nombre_E_P_BP"  placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->No_economico }}" name="No_economico" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Marca }}" name="Marca" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Modelo }}" name="Modelo" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Serie }}" name="Serie" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Ubicacion }}" name="Ubicacion" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Almacenamiento }}" name="Almacenamiento" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" value="{{ $generalEyC->Factura }}" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    @if ($generalEyC->Factura != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank">VER FACTURA</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN FACTURA</a>                                                
                                        </div>
                                    </div>
                                   @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->Disponibilidad_Estado }}" name="Disponibilidad_Estado" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Garantía</label>
                                            <input type="file" class="form-control inputForm" name="Garantia" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    @if ($generalConHerramientas->Garantia != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">                                              
                                                <a href="{{ asset('storage/' . $generalConHerramientas->Garantia) }}" target="_blank">VER GARANTIA</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalConHerramientas->Garantia == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">                                               
                                                <a target="_blank">SIN Garantia</a>                                                
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ficha técnica</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    @if ($generalEyC->Foto != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">                                             
                                                <a href="{{ asset('storage/' . $generalEyC->Foto) }}" target="_blank">VER FICHA TÉCNICA</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Foto == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">                                            
                                                <a target="_blank">SIN FICHA TÉCNICA</a>                                                
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado Actual</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    @if ($generalConCertificados->Certificado_Actual != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">                                             
                                                <a href="{{ asset('storage/' . $generalEyC->Certificado_Actual) }}" target="_blank">VER CERTIFICADO ACTUAL</a>                                                
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">                                            
                                                <a target="_blank">SIN CERTIFICADO ACTUAL</a>                                                
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @php
                                    /*
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado de calibración / Planos</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual[]" placeholder="Enter ..." multiple>
                                        </div>
                                    </div>

                                    
                                        // Decode the JSON string to get the array of file paths
                                    /*    $certificadosPaths = json_decode($generalConCertificados->Certificado_Actual, true);
                                   
                                    @if($certificadosPaths && is_array($certificadosPaths))
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            @foreach ($certificadosPaths as $certificadoPath)
                                                <a href="{{ asset('storage/' . $certificadoPath) }}" target="_blank">VER CERTIFICADO/PLANO</a>
                                                <br> <!-- Salto de línea -->
                                            @endforeach
                                        </div>
                                    </div>
                                        @else
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                             <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                                                <a target="_blank">SIN CERTIFICADO/PLANO</a>                                                
                                            </div>
                                        </div>
                                    @endif
                                    */
                                    @endphp
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->SAT }}" name="SAT" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->SAT }}" name="SAT" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Tipo" value="HERRAMIENTAS">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Comentario</label>
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Enter ...">{{ $generalEyC->Comentario }}</textarea>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
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
