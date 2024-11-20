
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
    <br>
    <br>
    <br>
        <h3 align="center"> Edición de 
                        @if($generalEyC->Tipo=='EQUIPOS')
                            Equipos
                        @endif
                        @if($generalEyC->Tipo=='CONSUMIBLES')
                            Consumibles
                        @endif
                        @if($generalEyC->Tipo=='ACCESORIOS')
                            Accesorios
                        @endif
                        @if($generalEyC->Tipo=='BLOCK Y PROBETA')
                            Block y Probeta
                        @endif
                        @if($generalEyC->Tipo=='HERRAMIENTAS')
                            Herramientas
                        @endif
        </h3>

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
                        <li class="nav-item"><a class="nav-link active" href="#tab_5" data-toggle="tab">Herramientas</a></li>
                    @endif
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header -->  
                <div class="card-body">
                @if($generalEyC->Tipo=='EQUIPOS')  
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <form id="equiposForm" action="{{ route('editEquipos.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
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
                                            <input type="text" class="form-control inputForm @error('No_economico') is-invalid @enderror" name="No_economico" value="{{ $generalEyC->No_economico }}" placeholder="Ejemplo: ECO-001">
                                            @error('No_economico')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
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
                                            <input type="text" class="form-control inputForm @error('Serie') is-invalid @enderror" name="Serie" value="{{ $generalEyC->Serie }}" placeholder="Ejemplo: N3199">
                                            @error('Serie')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
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
                                            <label class="col-form-label" for="inputSuccess">Ver Factura</label>
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->    
                                            <div>                                           
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                           
                                            </div> 
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <label class="col-form-label" for="inputSuccess">No se encontraron Facturas</label>    
                                            <div>
                                                <a target="_blank" role="button" class="btn btn-secondary long-button"><i class="fa fa-ban" aria-hidden="true"></i></a>     
                                            </div>                                                                                    
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'DISPONIBLE') selected="selected" @endif >DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'NO DISPONIBLE') selected="selected" @endif >NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" @if($generalEyC->Disponibilidad_Estado == 'FUERA DE SERVICIO/BAJA') selected="selected" @endif >FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Hoja de presentación</label>
                                            <input type="file" class="form-control inputForm" name="Foto">
                                        </div>
                                    </div>
                                    @if ($generalEyC->Foto != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <label class="col-form-label" for="inputSuccess">Ver Foto</label> 
                                            <div>                                             
                                                <a href="{{ asset('storage/' . $generalEyC->Foto) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                            
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Foto == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->   
                                            <label class="col-form-label" for="inputSuccess">No se encontraron Fotos</label> 
                                            <div>
                                                <a target="_blank" role="button" class="btn btn-secondary long-button"><i class="fa fa-ban" aria-hidden="true"></i></a>   
                                            </div>                                                                                     
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
                                            <label class="col-form-label" for="inputSuccess">Ver Certificado</label>  
                                            <div>                                            
                                                <a href="{{ asset('storage/' . $generalConCertificados->Certificado_Actual) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                                                                     
                                            </div> 
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->   
                                            <label class="col-form-label" for="inputSuccess">No se encontraron Certificados</label>                                              
                                                <a target="_blank" role="button" class="btn btn-secondary long-button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                                 
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
                            <form id="consumiblesForm" action="{{ route('editConsumibles.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
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
                                    <!--<div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" value="{{ $generalEyC->Serie }}" placeholder="Ejemplo: N3199">
                                        </div>
                                    </div>-->
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
                                            <label class="col-form-label" for="inputSuccess">Ver Factura</label> 
                                            <div>
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                               
                                            </div>                                              
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit --> 
                                            <label class="col-form-label" for="inputSuccess">No se encontraron Facturas</label>  
                                            <div>
                                                <a target="_blank" role="button" class="btn btn-secondary long-button"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                            </div>                                                                                              
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'DISPONIBLE') selected="selected" @endif >DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'NO DISPONIBLE') selected="selected" @endif >NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" @if($generalEyC->Disponibilidad_Estado == 'FUERA DE SERVICIO/BAJA') selected="selected" @endif >FUERA DE SERVICIO/BAJA</option>
                                            </select>
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
                                            <label class="col-form-label" for="inputSuccess">Ver Ficha técnica</label>      
                                                <div>                                       
                                                    <a href="{{ asset('storage/' . $generalEyC->Foto) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>  
                                                </div>                                              
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Foto == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <label class="col-form-label" for="inputSuccess">No hay Ficha técnica</label>   
                                            <div>
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                               
                                            </div>                                           
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No de Certificado</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConCertificados->No_certificado }}" name="No_certificado">
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
                                            <label class="col-form-label" for="inputSuccess">Ver Certificado</label>     
                                            <div>                                          
                                                <a href="{{ asset('storage/' . $generalConCertificados->Certificado_Actual) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                            
                                            </div> 
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <label class="col-form-label" for="inputSuccess">No hay Certificado</label> 
                                            <div>
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                            
                                            </div>                                              
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha Caducidad</label>
                                            @if($generalConCertificados->Fecha_calibracion == '2001-01-01')
                                            <input type="date" class="form-control inputForm" name="Fecha_calibracion">
                                            @else
                                            <input type="date" class="form-control inputForm" value="{{ $generalConCertificados->Fecha_calibracion }}" name="Fecha_calibracion">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Stock</label>
                                            <input type="number" class="form-control inputForm" value="{{ $generalConAlmacen->Stock }}" name="Stock" placeholder="Enter ...">
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
                        <form id="accesoriosForm" action="{{ route('editAccesorios.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
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
                                            <input type="text" class="form-control inputForm @error('No_economico') is-invalid @enderror" value="{{ $generalEyC->No_economico }}" name="No_economico" placeholder="Enter ...">
                                            @error('No_economico')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
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
                                            <label class="col-form-label" for="inputSuccess">Ver Factura</label>
                                            <div>                                          
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                             
                                            </div> 
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit --> 
                                            <label class="col-form-label" for="inputSuccess">No hay Facturas</label>
                                            <div>                                              
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                                                                          
                                            </div> 
                                        </div>
                                    </div>
                                @endif
                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'DISPONIBLE') selected="selected" @endif >DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'NO DISPONIBLE') selected="selected" @endif >NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" @if($generalEyC->Disponibilidad_Estado == 'FUERA DE SERVICIO/BAJA') selected="selected" @endif >FUERA DE SERVICIO/BAJA</option>
                                            </select>
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
                                            <label class="col-form-label" for="inputSuccess">Ver Certificado</label> 
                                            <div>                                         
                                                <a href="{{ asset('storage/' . $generalConCertificados->Certificado_Actual) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                               
                                            </div> 
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit --> 
                                            <label class="col-form-label" for="inputSuccess">No hay Certificados</label>   
                                            <div>
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                            
                                            </div>                                             
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
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->BMPRO }}" name="BMPRO" placeholder="Enter ...">
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
                        <form id="blocksForm" action="{{ route('editBlocks.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
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
                                            <label class="col-form-label" for="inputSuccess">Ver Factura</label>
                                            <div>                                             
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                               
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit --> 
                                            <label class="col-form-label" for="inputSuccess">No hay Facturas</label>  
                                            <div>                                            
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                                                                            
                                            </div> 
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'DISPONIBLE') selected="selected" @endif >DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'NO DISPONIBLE') selected="selected" @endif >NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" @if($generalEyC->Disponibilidad_Estado == 'FUERA DE SERVICIO/BAJA') selected="selected" @endif >FUERA DE SERVICIO/BAJA</option>
                                            </select>
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
                                            <label class="col-form-label" for="inputSuccess">Ver Hoja de presentación</label>
                                                <div>                                             
                                                    <a href="{{ asset('storage/' . $generalEyC->Foto) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                              
                                                </div> 
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Foto == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit --> 
                                            <label class="col-form-label" for="inputSuccess">No hay Hoja de presentación</label>                                               
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                                
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No Certificado</label>
                                            <input type="text" class="form-control inputForm" value="{{ $generalConCertificados->No_certificado }}" name="No_certificado" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado de calibración </label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="Enter ..." multiple>
                                        </div>
                                    </div>
                                    @if ($generalConCertificados->Certificado_Actual != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <label class="col-form-label" for="inputSuccess">Ver Certificado </label> 
                                            <div>                                             
                                                <a href="{{ asset('storage/' . $generalConCertificados->Certificado_Actual) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                              
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit --> 
                                            <label class="col-form-label" for="inputSuccess">No hay Certificado de calibración</label>
                                            <div>                                              
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                               
                                            </div> 
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
                                            <label class="col-form-label" for="inputSuccess">Plano</label>
                                            <input type="file" class="form-control inputForm" name="Plano" placeholder="Enter ..." multiple>
                                        </div>
                                    </div>
                                    
                                    @if ($generalConBlocks->Plano != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->   
                                            <label class="col-form-label" for="inputSuccess">Ver plano</label>
                                            <div>                                            
                                                <a href="{{ asset('storage/' . $generalConBlocks->Plano) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                              
                                            </div> 
                                        </div>
                                    </div>
                                    @elseif($generalConBlocks->Plano == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <label class="col-form-label" for="inputSuccess">No hay plano</label>
                                            <div>                                              
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                             
                                            </div>
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
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->BMPRO }}" name="BMPRO" placeholder="Enter ...">
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
                        <form id="herramientasForm" action="{{ route('editHerramientas.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
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
                                            <label class="col-form-label" for="inputSuccess">Ver Factura</label>
                                            <div>
                                                <a href="{{ asset('storage/' . $generalEyC->Factura) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                               
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Factura == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->   
                                            <label class="col-form-label" for="inputSuccess">No hay Factura</label>
                                            <div>                                             
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                               
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'DISPONIBLE') selected="selected" @endif >DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" @if($generalEyC->Disponibilidad_Estado == 'NO DISPONIBLE') selected="selected" @endif >NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" @if($generalEyC->Disponibilidad_Estado == 'FUERA DE SERVICIO/BAJA') selected="selected" @endif >FUERA DE SERVICIO/BAJA</option>
                                            </select>
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
                                            <label class="col-form-label" for="inputSuccess">Ver Garantía</label>    
                                                <div>                                        
                                                    <a href="{{ asset('storage/' . $generalConHerramientas->Garantia) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                               
                                                </div>
                                        </div>
                                    </div>
                                    @elseif($generalConHerramientas->Garantia == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <label class="col-form-label" for="inputSuccess">No hay Garantía</label>
                                        <div class="form-group">                                               
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                               
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ficha técnica</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    @if ($generalEyC->Foto != 'ESPERA DE DATO')
                                    <div class="col-sm-6">
                                        <div class="form-group">   
                                            <label class="col-form-label" for="inputSuccess">Ver Ficha técnica</label> 
                                            <div>                                         
                                                <a href="{{ asset('storage/' . $generalEyC->Foto) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                          
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($generalEyC->Foto == 'ESPERA DE DATO')
                                    <div class="col-sm-6">
                                        <div class="form-group"> 
                                            <label class="col-form-label" for="inputSuccess">No hay Ficha técnica</label> 
                                            <div>                                          
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                              
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado Actual</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    @if ($generalConCertificados->Certificado_Actual != 'ESPERA DE DATO')
                                    <div class="col-sm-6">
                                        <div class="form-group">  
                                            <label class="col-form-label" for="inputSuccess">Ver Certificado Actual</label>
                                            <div>                                        
                                                <a href="{{ asset('storage/' . $generalConCertificados->Certificado_Actual) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                               
                                            </div>   
                                        </div>
                                    </div>
                                    @elseif($generalConCertificados->Certificado_Actual == 'ESPERA DE DATO')
                                    <div class="col-sm-6">
                                    <label class="col-form-label" for="inputSuccess">No hay Certificado</label>
                                        <div class="form-group">                                            
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                               
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Plano</label>
                                            <input type="file" class="form-control inputForm" name="Plano" placeholder="Enter ..." multiple>
                                        </div>
                                    </div>
                                    @php 
                                    //dd($generalConHerramientas);
                                    @endphp
                                    @if ($generalConHerramientas->Plano != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit --> 
                                            <label class="col-form-label" for="inputSuccess">Ver Plano</label>   
                                            <div>
                                                <a href="{{ asset('storage/' . $generalConHerramientas->Plano) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                               
                                            </div>                                            
                                        </div>
                                    </div>
                                    @elseif($generalConHerramientas->Plano == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->   
                                            <label class="col-form-label" for="inputSuccess">No hay Plano</label>   
                                            <div>                                            
                                                <a target="_blank" class="btn btn-secondary long-button" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                               
                                            </div> 
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
                                            <input type="text" class="form-control inputForm" value="{{ $generalEyC->BMPRO }}" name="BMPRO" placeholder="Enter ...">
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

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    {{--<link rel="stylesheet" href="vendor/adminlte\dist/css/Equipos.scss">--}}
@stop

@section('js')
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

<Script>
    /*Prevenir el Enter Equipos*/
document.getElementById('equiposForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Prevenir el Enter Consumibles*/
document.getElementById('consumiblesForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Prevenir el Enter Accesorios*/
document.getElementById('accesoriosForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Prevenir el Enter Blocks*/
document.getElementById('blocksForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Prevenir el Enter Herramientas*/
document.getElementById('herramientasForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });
</script>
@endsection