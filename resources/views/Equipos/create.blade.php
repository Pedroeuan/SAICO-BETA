
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
<br>
<h3 align="center"> Formulario de Alta de datos</h3>
<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
        <div id="tab-warning" class="alert alert-warning text-center" style="display: none;">
                Por favor, seleccione una pestaña.
            </div>
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center">
                        <li class="nav-item"><a class="nav-link" href="#tab_1" data-toggle="tab">Equipos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Consumibles</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Accesorios</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Block Y Probeta</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_5" data-toggle="tab">Herramientas</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_6" data-toggle="tab">Kits</a></li>
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header p-2-->
                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane" id="tab_1">
                            <form id="equiposForm" action="{{route('general_eyc.storeEquipos')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Ejemplo: Yugo" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" placeholder="Ejemplo: ECO-001" value="{{old('No_economico')}}">
                                            @error('No_economico')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Ejemplo: MANGAFLUX" value="{{old('Marca')}}">
                                            @error('Marca')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Ejemplo: DPM" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Ejemplo: N3199" value="{{old('Serie')}}">
                                            @error('Serie')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Ejemplo: OFICINA" value="{{old('Ubicacion')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Ejemplo: TEMPERATURA AMBIENTE, SIN POLVO, SIN HUMEDAD E INDIRECTO AL SOL" value="{{old('Almacenamiento')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control-file inputForm" name="Factura"></input>
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Hoja de presentación</label>
                                            <input type="file" class="form-control-file inputForm" name="Foto">
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No de certificado</label>
                                            <input type="text" class="form-control inputForm" name="No_certificado" placeholder="" value="{{old('No_certificado')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado actual</label>
                                            <input type="file" class="form-control-file inputForm" name="Certificado_Actual" placeholder="">
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ultima calibración</label>
                                            <input type="date" class="form-control inputForm" id="fecha" name="Fecha_calibracion" value="{{ old('Fecha_calibracion') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Próxima calibración</label>
                                            <input type="date" class="form-control inputForm" name="Prox_fecha_calibracion" value="{{ old('Prox_fecha_calibracion') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Ejemplo: 41116500" value="{{old('SAT')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Ejemplo: 5K010014" value="{{old('BMPRO')}}">
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
                                            <textarea class="form-control is-waning" id="inputSuccess" name="Comentario" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto.">{{old('Comentario')}}</textarea>
                                        </div>
                                    </div>

                                    <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>
                                        <div class="float-left">
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarEquipos">Guardar y continuar</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <!-- CONSUMIBLES -->
                        <div class="tab-pane" id="tab_2">
                            <form id="consumiblesForm" action="{{route('general_eyc.storeConsumibles')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Ejemplo: Cable de Corriente" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Ejemplo: KARL DEUTSCH" value="{{old('Marca')}}">
                                            @error('Marca')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Ejemplo: DPM" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Lote</label>
                                            <input type="text" class="form-control inputForm" name="Lote" placeholder="Ejemplo: 4092" value="{{old('Lote')}}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Ejemplo: N3199" value="{{old('Serie')}}">
                                            @error('Serie')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Ejemplo: OFICINA" value="{{old('Ubicacion')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Ejemplo: TEMPERATURA AMBIENTE, SIN POLVO, SIN HUMEDAD E INDIRECTO AL SOL" value="{{old('Almacenamiento')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control-file inputForm" name="Factura"></input>
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proveedor</label>
                                            <input type="text" class="form-control inputForm" name="Proveedor" placeholder="Brüder NDT " value="{{old('Proveedor')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ficha técnica</label>
                                            <input type="file" class="form-control inputForm" name="Foto" >
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No de certificado</label>
                                            <input type="text" class="form-control inputForm" name="No_certificado" placeholder="" value="{{old('No_certificado')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado actual</label>
                                            <input type="file" class="form-control-file inputForm" name="Certificado_Actual" placeholder="">
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha Caducidad</label>
                                            <input type="date" class="form-control inputForm" name="Fecha_calibracion" value="{{ old('Fecha_calibracion') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Stock</label>
                                            <input type="number" class="form-control inputForm" name="Stock" placeholder="Ejemplo: 1.2.3..20.." value="{{ old('Stock') }}">
                                            @error('Stock')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Ejemplo: 41116500" value="{{old('SAT')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Ejemplo: 5K010014" value="{{old('BMPRO')}}">
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
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Ejemplo: LOTE AGOTADO">{{old('Comentario')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>
                                        <div class="float-left">
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarConsumibles">Guardar y continuar</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <!--ACCESORIOS -->
                        <div class="tab-pane" id="tab_3">
                            <form id="accesoriosForm" action="{{route('general_eyc.storeAccesorios')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Ejemplo: Cable DUAL con cubierta de acero inox. (uso rudo)" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" placeholder="Ejemplo: No. AICO-001" value="{{old('No_economico')}}">
                                            @error('No_economico')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Ejemplo: Brüder NDT" value="{{old('Marca')}}">
                                            @error('Marca')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Ejemplo: DPM" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Ejemplo: N3199" value="{{old('Serie')}}">
                                            @error('Serie')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Ejemplo: OFICINA" value="{{old('Ubicacion')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Ejemplo: TEMPERATURA AMBIENTE, SIN POLVO, SIN HUMEDAD E INDIRECTO AL SOL" value="{{old('Almacenamiento')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control-file inputForm" name="Factura"></input>
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual">
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proveedor</label>
                                            <input type="text" class="form-control inputForm" name="Proveedor" placeholder="Ejemplo: ZION" value="{{ old('Proveedor') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Ejemplo: 41116500" value="{{old('SAT')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Ejemplo: 5K010014" value="{{old('BMPRO')}}">
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
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Ejemplo: SE REPORTA FALLA Y RUIDO CON EL CABLE.">{{old('Comentario')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>
                                        <div class="float-left">
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarAccesorios">Guardar y continuar</button>
                                        </div>
                                    </div>


                                </div>
                            </form>
                        </div>
                        <!-- BLOCKS -->
                        <div class="tab-pane" id="tab_4">
                            <form id="blocksForm" action="{{route('general_eyc.storeBlocks')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    
                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Ejemplo: BLOCK ASME T= 3/4"" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" placeholder="Ejemplo: No. ECO-B-034" value="{{old('No_economico')}}">
                                            @error('No_economico')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Ejemplo: Brüder NDT" value="{{old('Marca')}}">
                                            @error('Marca')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Ejemplo: 5-STEPS-ACERO" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Ejemplo: 102021CUT05" value="{{old('Serie')}}">
                                            @error('Serie')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Ejemplo: OFICINA" value="{{old('Ubicacion')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Ejemplo: TEMPERATURA AMBIENTE, SIN POLVO, SIN HUMEDAD E INDIRECTO AL SOL" value="{{old('Almacenamiento')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control-file inputForm" name="Factura"></input>
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Hoja de presentación</label>
                                            <input type="file" class="form-control-file inputForm" name="Foto">
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha de calibración</label>
                                            <input type="date" class="form-control inputForm" name="Fecha_calibracion" value="{{old('Fecha_calibracion')}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No Certificado</label>
                                            <input type="text" class="form-control inputForm" name="No_Certificado" placeholder="Ejemplo: C01085" value="{{old('No_Certificado')}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado de calibración</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" >
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Plano</label>
                                            <input type="file" class="form-control inputForm" name="Plano" >
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Ejemplo: 41116500" value="{{old('SAT')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Ejemplo: 5K010014" value="{{old('BMPRO')}}">
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
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Ejemplo: NUEVO LLEGA xxxxxxx 1018 STEEL">{{old('Comentario')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>
                                        <div class="float-left">
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarBlocks">Guardar y continuar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--HERRAMIENTAS -->
                        <div class="tab-pane" id="tab_5">
                            <form id="herramientasForm" action="{{ route('general_eyc.storeHerramientas') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Ejemplo: Sonda cableada regular" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" placeholder="Ejemplo: No. AD-003" value="{{old('No_economico')}}">
                                            @error('No_economico')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Ejemplo: DeFelsko" value="{{old('Marca')}}">
                                            @error('Marca')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Ejemplo: FS" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Ejemplo: 190776" value="{{old('Serie')}}">
                                            @error('Serie')
                                                <br>
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                                </br>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Ejemplo: OFICINA" value="{{old('Ubicacion')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Ejemplo: TEMPERATURA AMBIENTE, SIN POLVO, SIN HUMEDAD E INDIRECTO AL SOL" value="{{old('Almacenamiento')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control-file inputForm" name="Factura"></input>
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Garantía</label>
                                            <input type="file" class="form-control inputForm" name="Garantia" >
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ficha técnica</label>
                                            <input type="file" class="form-control inputForm" name="Foto" >
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado Actual</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" >
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Plano</label>
                                            <input type="file" class="form-control inputForm" name="Plano" >
                                            @if ($errors->any())
                                                <div class="alert alert-warning">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Ejemplo: 41116500" value="{{old('SAT')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Ejemplo: 5K010014" value="{{old('BMPRO')}}">
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
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Ejemplo: CUENTA CON GUARDA Y MANERAL">{{old('Comentario')}}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>

                                        <div class="float-left">
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarHerramientas">Guardar y continuar</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <!-- Agrega más paneles de tabs según sea necesario -->
                        <!--KITS -->
                        <div class="tab-pane" id="tab_6">
                            <form id="kitForm" action="{{ route('GuardarKits.agregarKits') }}" method="post" enctype="multipart/form-data" >
                                @csrf
                                <div class="box">
                                        <div class="d-flex justify-content-between align-items-center mb-3">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Nombre</label>
                                                    <input type="text" class="form-control inputForm" name="Nombre" placeholder="Ejemplo: Kit de Liquidos">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Prueba</label>
                                                    <input type="text" class="form-control inputForm" name="Prueba" placeholder="Ejemplo: Liquidos">
                                                </div>
                                            </div>

                                        </div>

                                </div>
                                
                                <h5 align="center">Inventario Disponible</h5>
                                    <!-- Tabla de Elementos Disponibles -->
                                <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Num. Económico</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>NS</th>
                                                <th>Disponibilidad</th>
                                                <th>Fecha calibración</th>
                                                <th>Hoja de Presentación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($generalConCertificados as $general_eyc)
                                            <tr data-id="{{ $general_eyc->idGeneral_EyC }}">
                                                <td>{{ $general_eyc->Nombre_E_P_BP }}</td>
                                                <td>{{ $general_eyc->No_economico }}</td>
                                                <td>{{ $general_eyc->Marca }}</td>
                                                <td>{{ $general_eyc->Modelo }}</td>
                                                <td>{{ $general_eyc->Serie }}</td>
                                                <td>
                                                    @if($general_eyc->Disponibilidad_Estado=='DISPONIBLE')
                                                        <button type="button" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i></button>
                                                        @elseif($general_eyc->Disponibilidad_Estado=='NO DISPONIBLE')
                                                        <button type="button" class="btn btn-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></button>
                                                        @elseif($general_eyc->Disponibilidad_Estado=='FUERA DE SERVICIO/BAJA')
                                                        <button type="button" class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"></i></button>
                                                        @elseif($general_eyc->Disponibilidad_Estado=='ESPERA DE DATO')
                                                        <button type="button" class="btn btn-info"><i class="far fa-clock" aria-hidden="true"></i></button>
                                                    @endif
                                                </td>

                                                @if($general_eyc->certificados)
                                                    @if($general_eyc->Tipo=='EQUIPOS' || $general_eyc->Tipo=='BLOCK Y PROBETA')
                                                            @if($general_eyc->certificados->Fecha_calibracion=='2001-01-01')
                                                                <td scope="row">SIN FECHA ASIGNADA</td>
                                                                @else
                                                                <td scope="row">{{$general_eyc->certificados->Fecha_calibracion}}</td>
                                                            @endif
                                                        @else
                                                        <td scope="row">N/A</td>
                                                    @endif
                                                @endif

                                                <td>
                                                    @if ($general_eyc->Foto != 'ESPERA DE DATO')
                                                    <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Foto) }}" role="button" target="_blank"><i class="fa fa-eye"></i></a>
                                                    @else
                                                    <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-success btnAgregar" data-id="{{ $general_eyc->idGeneral_EyC }}"><i class="fas fa-plus-circle" aria-hidden="true"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                </table>

                                <!-- Tabla de Elementos Seleccionados -->
                                <table id="tablaSeleccionados" class="table table-bordered table-striped dt-responsive tablas">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Num. Económico</th>
                                                <th>Marca</th>
                                                <th>Ultima calibración</th>
                                                <th>Cantidad</th>
                                                <th>Unidad</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                </table>

                                <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>

                                        <div class="float-left">
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarKits">Guardar y continuar</button>
                                        </div>
                                </div>

                            </form>
                        </div><!--"class="tab-pane" id="tab_6""-->

                    </div><!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div><!-- /.card -->   
        </div><!-- class="col-sm-12" -->
    </div><!--  class="row justify-content-center" -->
</div><!-- class="container" -->     
@stop

@section('js')
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

let table = new DataTable('#tablaJs', {
    //destroy: true, // Asegura que cualquier instancia previa de DataTables se destruya antes de crear una nueva
    //pageLength: 5, // Especifica cuántas entradas deseas mostrar por página
    //lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]], // Añade 5 a las opciones del menú
    language: {
                    "decimal": "",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna ascendente",
                        "sortDescending": ": activar para ordenar la columna descendente"
                    }
                }
});

    function actualizarTabla() {
        $.ajax({
            url: '/obtenerDatosActualizados', // Cambia esta URL a la ruta que devuelve los datos actualizados
            method: 'GET',
            success: function(response) {
                // Vaciar el contenido actual de la tabla
                $('#tablaJs tbody').empty();

                // Iterar sobre los datos recibidos y agregarlos a la tabla
                response.forEach(function(item) {
                    var disponibilidad = '';
                    switch(item.Disponibilidad_Estado) {
                        case 'DISPONIBLE':
                            disponibilidad = '<button type="button" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i></button>';
                            break;
                        case 'NO DISPONIBLE':
                            disponibilidad = '<button type="button" class="btn btn-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></button>';
                            break;
                        case 'FUERA DE SERVICIO/BAJA':
                            disponibilidad = '<button type="button" class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"></i></button>';
                            break;
                        case 'ESPERA DE DATO':
                            disponibilidad = '<button type="button" class="btn btn-info"><i class="far fa-clock" aria-hidden="true"></i></button>';
                            break;
                    }

                    var hojaPresentacion = item.Foto != 'ESPERA DE DATO'
                        ? '<a class="btn btn-primary" href="/storage/' + item.Foto + '" role="button" target="_blank"><i class="fa fa-eye"></i></a>'
                        : '<a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>';

                    var row = '<tr data-id="' + item.idGeneral_EyC + '">' +
                            '<td>' + item.Nombre_E_P_BP + '</td>' +
                            '<td>' + item.No_economico + '</td>' +
                            '<td>' + item.Marca + '</td>' +
                            '<td>' + item.Modelo + '</td>' +
                            '<td>' + item.Serie + '</td>' +
                            '<td>' + disponibilidad + '</td>' +
                            '<td>' + (item.certificados ? item.certificados.Fecha_calibracion : 'N/A') + '</td>' +
                            '<td>' + hojaPresentacion + '</td>' +
                            '<td><button type="button" class="btn btn-success btnAgregar" data-id="' + item.idGeneral_EyC + '"><i class="fas fa-plus-circle" aria-hidden="true"></i></button></td>' +
                            '</tr>';

                    $('#tablaJs tbody').append(row);
                });

                // Reattach the listeners after updating the table
                attachAddListeners();
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener los datos:', error);
            }
        });
    }

    $(document).ready(function() {
    // Actualizar la tabla cada 30 segundos (30000 milisegundos)
    setInterval(actualizarTabla, 30000);

    // Llamar a la función una vez al cargar la página
    actualizarTabla();
});

// Funciones para adjuntar los listeners
function attachAddListeners() {
    document.querySelectorAll('.btnAgregar').forEach(function(button) {
        button.addEventListener('click', function() {
            let row = this.closest('tr');
            let id = this.dataset.id;

            // Verificar si el elemento ya está en la tabla de seleccionados
            if (document.querySelector(`#tablaSeleccionados tr[data-id='${id}']`)) {
                return; // Si ya está, no hacemos nada
            }

            // Clonar la fila y agregar campos de cantidad y unidad
            let newRow = document.createElement('tr');
            newRow.setAttribute('data-id', id);
            newRow.innerHTML = `
                <td>${row.cells[0].innerText}</td>
                <td>${row.cells[1].innerText}</td>
                <td>${row.cells[2].innerText}</td>
                <td>${row.cells[6].innerText}</td>
                <td><input type="number" class="form-control cantidad" name="cantidad_${id}" required></td>
                <td><input type="text" class="form-control unidad" name="unidad_${id}" required></td>
                <td><button type="button" class="btn btn-danger btnEliminar" data-id="${id}"><i class="fas fa-minus-circle" aria-hidden="true"></i></button></td>
            `;

            // Agregar la nueva fila a la tabla de seleccionados
            document.querySelector('#tablaSeleccionados tbody').appendChild(newRow);

            // Re-attach the delete listeners to the new button
            attachDeleteListeners();
        });
    });
}

function attachDeleteListeners() {
    document.querySelectorAll('.btnEliminar').forEach(function(button) {
        button.addEventListener('click', function() {
            let row = this.closest('tr');
            row.remove();
        });
    });
}

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

    /*Prevenir el Enter Kits*/
document.getElementById('kitForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

function attachAddListeners() {
    document.querySelectorAll('.btnAgregar').forEach(function(button) {
        button.addEventListener('click', function() {
            let row = this.closest('tr');
            let id = this.dataset.id;

            // Verificar si el elemento ya está en la tabla de seleccionados
            if (document.querySelector(`#tablaSeleccionados tr[data-id='${id}']`)) {
                return; // Si ya está, no hacemos nada
            }

            // Clonar la fila y agregar campos de cantidad y unidad
            let newRow = document.createElement('tr');
            newRow.setAttribute('data-id', id);
            newRow.innerHTML = `
                <td>${row.cells[0].innerText}</td>
                <td>${row.cells[1].innerText}</td>
                <td>${row.cells[2].innerText}</td>
                <td>${row.cells[6].innerText}</td>
                <td><input type="number" class="form-control cantidad" name="cantidad_${id}" required></td>
                <td><input type="text" class="form-control unidad" name="unidad_${id}" required></td>
                <td><button type="button" class="btn btn-danger btnEliminar" data-id="${id}"><i class="fas fa-minus-circle" aria-hidden="true"></i></button></td>
            `;

            // Agregar la nueva fila a la tabla de seleccionados
            document.querySelector('#tablaSeleccionados tbody').appendChild(newRow);

            // Re-attach the delete listeners to the new button
            attachDeleteListeners();
        });
    });
}


function attachDeleteListeners() {
    document.querySelectorAll('.btnEliminar').forEach(function(button) {
        button.addEventListener('click', function() {
            let row = this.closest('tr');
            row.remove();
        });
    });
}

// Attach listeners when the DOM content is loaded
attachAddListeners();
attachDeleteListeners();

// Manejar el envío del formulario
document.querySelector('#kitForm').addEventListener('submit', function(event) {
    let selectedRows = document.querySelectorAll('#tablaSeleccionados tbody tr');
    let kitData = [];

    selectedRows.forEach(function(row) {
        let id = row.dataset.id;
        let cantidad = row.querySelector('.cantidad').value;
        let unidad = row.querySelector('.unidad').value;

        kitData.push({
            idGeneral_EyC: id,
            cantidad: cantidad,
            unidad: unidad
        });

        // Crear inputs ocultos para enviar los datos de cantidad y unidad
        let inputCantidad = document.createElement('input');
        inputCantidad.type = 'hidden';
        inputCantidad.name = `kitData[${id}][cantidad]`;
        inputCantidad.value = cantidad;
        document.querySelector('#kitForm').appendChild(inputCantidad);

        let inputUnidad = document.createElement('input');
        inputUnidad.type = 'hidden';
        inputUnidad.name = `kitData[${id}][unidad]`;
        inputUnidad.value = unidad;
        document.querySelector('#kitForm').appendChild(inputUnidad);
    });

    // Añadir los datos al formulario como campos ocultos
    kitData.forEach(function(item) {
        let inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = `kitData[${item.idGeneral_EyC}][idGeneral_EyC]`;
        inputId.value = item.idGeneral_EyC;
        document.querySelector('#kitForm').appendChild(inputId);
    });
});

document.querySelector('#kitForm').addEventListener('submit', function(event) {
    if (!validateForm()) {
        event.preventDefault(); // Evitar que el formulario se envíe si no pasa la validación
    }
});

function validateForm() {
    let form = document.getElementById('kitForm');
    let nombre = form.querySelector('[name="Nombre"]').value;
    let prueba = form.querySelector('[name="Prueba"]').value;

    let selectedRows = document.querySelectorAll('#tablaSeleccionados tbody tr');
    let camposVacios = [];

    if (!nombre) {
        camposVacios.push('Nombre');
    }
    if (!prueba) {
        camposVacios.push('Prueba');
    }

    if (selectedRows.length === 0) {
        camposVacios.push('Elementos seleccionados');
    }

    selectedRows.forEach(function(row) {
        let cantidad = row.querySelector('.cantidad').value;
        let unidad = row.querySelector('.unidad').value;

        if (!cantidad) {
            camposVacios.push('Cantidad');
        }
        if (!unidad) {
            camposVacios.push('Unidad');
        }
    });

    if (camposVacios.length > 0) {
        Swal.fire({
            title: 'Error',
            text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return false;
    }
    return true;
}

// Guardar y continuar
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('guardarContinuarKits').addEventListener('click', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de manera convencional

        if (!validateForm()) {
            return; // No continuar si la validación falla
        }

        var form = document.getElementById('kitForm');
        var formData = new FormData(form);

        // Agregar datos de los kits seleccionados
        let selectedRows = document.querySelectorAll('#tablaSeleccionados tbody tr');
        selectedRows.forEach(function(row, index) {
            let id = row.getAttribute('data-id');
            let cantidad = row.querySelector('.cantidad').value;
            let unidad = row.querySelector('.unidad').value;

            formData.append(`kitData[${index}][idGeneral_EyC]`, id);
            formData.append(`kitData[${index}][cantidad]`, cantidad);
            formData.append(`kitData[${index}][unidad]`, unidad);
        });

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Datos guardados',
                    text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });

                // Limpiar el formulario
                form.reset();

                // Limpiar la tabla de elementos seleccionados
                document.querySelector('#tablaSeleccionados tbody').innerHTML = '';
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += ' - ' + xhr.responseJSON.message;
                }
                console.error('Error al enviar formulario:', errorMessage);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});

    /*HERRAMIENTAS*/
    document.addEventListener('DOMContentLoaded', function() {
    // Evento click para el botón "Guardar y continuar"
    document.getElementById('guardarContinuarHerramientas').addEventListener('click', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de manera convencional

        var form = document.getElementById('herramientasForm');
        var formData = new FormData(form);

        // Obtener los valores de los campos
        var nombre = formData.get('Nombre_E_P_BP');
            var numeroEconomico = formData.get('No_economico');
            var marca = formData.get('Marca');
            var modelo = formData.get('Modelo');
            var serie = formData.get('Serie');

            // Validaciones
            var camposVacios = [];

            if (!nombre) {
                camposVacios.push('Nombre');
            }
            if (!numeroEconomico) {
                camposVacios.push('Número Económico');
            }
            if (!marca) {
                camposVacios.push('Marca');
            }
            if (!modelo) {
                camposVacios.push('Modelo');
            }
            if (!serie) {
                camposVacios.push('Número de Serie');
            }

            if (camposVacios.length > 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Usar SweetAlert2 para mostrar una alerta atractiva
                Swal.fire({
                    title: 'Datos guardados',
                    text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });

                // Limpiar el formulario
                form.reset();
            },
            error: function(xhr, status, error) {
                // Usar SweetAlert2 para mostrar una alerta de error
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});

/*BLOCKS*/
document.addEventListener('DOMContentLoaded', function() {
    // Evento click para el botón "Guardar y continuar"
    document.getElementById('guardarContinuarBlocks').addEventListener('click', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de manera convencional

        var form = document.getElementById('blocksForm');
        var formData = new FormData(form);

        // Obtener los valores de los campos
        var nombre = formData.get('Nombre_E_P_BP');
            var numeroEconomico = formData.get('No_economico');
            var marca = formData.get('Marca');
            var modelo = formData.get('Modelo');
            var serie = formData.get('Serie');

            // Validaciones
            var camposVacios = [];

            if (!nombre) {
                camposVacios.push('Nombre');
            }
            if (!numeroEconomico) {
                camposVacios.push('Número Económico');
            }
            if (!marca) {
                camposVacios.push('Marca');
            }
            if (!modelo) {
                camposVacios.push('Modelo');
            }
            if (!serie) {
                camposVacios.push('Número de Serie');
            }

            if (camposVacios.length > 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Usar SweetAlert2 para mostrar una alerta atractiva
                Swal.fire({
                    title: 'Datos guardados',
                    text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });

                // Limpiar el formulario
                form.reset();
            },
            error: function(xhr, status, error) {
                // Usar SweetAlert2 para mostrar una alerta de error
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});

/*ACCESORIOS*/
document.addEventListener('DOMContentLoaded', function() {
    // Evento click para el botón "Guardar y continuar"
    document.getElementById('guardarContinuarAccesorios').addEventListener('click', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de manera convencional

        var form = document.getElementById('accesoriosForm');
        var formData = new FormData(form);

        // Obtener los valores de los campos
        var nombre = formData.get('Nombre_E_P_BP');
            var numeroEconomico = formData.get('No_economico');
            var marca = formData.get('Marca');
            var modelo = formData.get('Modelo');
            var serie = formData.get('Serie');

            // Validaciones
            var camposVacios = [];

            if (!nombre) {
                camposVacios.push('Nombre');
            }
            if (!numeroEconomico) {
                camposVacios.push('Número Económico');
            }
            if (!marca) {
                camposVacios.push('Marca');
            }
            if (!modelo) {
                camposVacios.push('Modelo');
            }
            if (!serie) {
                camposVacios.push('Número de Serie');
            }

            if (camposVacios.length > 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Usar SweetAlert2 para mostrar una alerta atractiva
                Swal.fire({
                    title: 'Datos guardados',
                    text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });

                // Limpiar el formulario
                form.reset();
            },
            error: function(xhr, status, error) {
                // Usar SweetAlert2 para mostrar una alerta de error
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});

/*CONSUMIBLES*/
document.addEventListener('DOMContentLoaded', function() {
    // Evento click para el botón "Guardar y continuar"
    document.getElementById('guardarContinuarConsumibles').addEventListener('click', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de manera convencional

        var form = document.getElementById('consumiblesForm');
        var formData = new FormData(form);

        // Obtener los valores de los campos
            var nombre = formData.get('Nombre_E_P_BP');
            var marca = formData.get('Marca');
            var modelo = formData.get('Modelo');
            var serie = formData.get('Serie');
            var stock = formData.get('Stock');

            // Validaciones
            var camposVacios = [];

            if (!nombre) {
                camposVacios.push('Nombre');
            }
            if (!marca) {
                camposVacios.push('Marca');
            }
            if (!modelo) {
                camposVacios.push('Modelo');
            }
            if (!serie) {
                camposVacios.push('Número de Serie');
            }
            if (!stock) {
                camposVacios.push('Stock');
            }

            if (camposVacios.length > 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Usar SweetAlert2 para mostrar una alerta atractiva
                Swal.fire({
                    title: 'Datos guardados',
                    text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
                // Limpiar el formulario
                form.reset();
            },
            error: function(xhr, status, error) {
                // Usar SweetAlert2 para mostrar una alerta de error
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});

/*Equipos*/
document.addEventListener('DOMContentLoaded', function() {
    // Evento click para el botón "Guardar y continuar"
    document.getElementById('guardarContinuarEquipos').addEventListener('click', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de manera convencional

        var form = document.getElementById('equiposForm');
        var formData = new FormData(form);

         // Obtener los valores de los campos
            var nombre = formData.get('Nombre_E_P_BP');
            var numeroEconomico = formData.get('No_economico');
            var marca = formData.get('Marca');
            var modelo = formData.get('Modelo');
            var serie = formData.get('Serie');

            // Validaciones
            var camposVacios = [];

            if (!nombre) {
                camposVacios.push('Nombre');
            }
            if (!numeroEconomico) {
                camposVacios.push('Número Económico');
            }
            if (!marca) {
                camposVacios.push('Marca');
            }
            if (!modelo) {
                camposVacios.push('Modelo');
            }
            if (!serie) {
                camposVacios.push('Número de Serie');
            }

            if (camposVacios.length > 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            // Enviar el formulario usando AJAX
        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Usar SweetAlert2 para mostrar una alerta atractiva
                Swal.fire({
                    title: 'Datos guardados',
                    text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
                // Limpiar el formulario
                form.reset();
            },
            error: function(xhr, status, error) {
                // Usar SweetAlert2 para mostrar una alerta de error
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});

   // Espera a que el documento esté listo
    document.addEventListener("DOMContentLoaded", function() {
        // Obtiene el elemento de pestañas
        var tabs = document.querySelectorAll('.nav-pills .nav-link');

        // Itera sobre cada pestaña
        tabs.forEach(function(tab) {
            // Añade un evento de clic a cada pestaña
            tab.addEventListener('click', function() {
                // Obtiene el id de la pestaña activa
                var activeTab = tab.getAttribute('href');

                // Guarda el id de la pestaña activa en localStorage
                localStorage.setItem('activeTab', activeTab);
            });
        });

        // Obtiene el id de la pestaña activa desde localStorage
        var activeTab = localStorage.getItem('activeTab');

        // Si hay una pestaña activa guardada en localStorage, la muestra
        if (activeTab) {
            var tabLink = document.querySelector('.nav-pills .nav-link[href="' + activeTab + '"]');
            if (tabLink) {
                tabLink.click(); // Activa la pestaña guardada
            }
        }
    });

       // Espera a que el documento esté listo
        document.addEventListener("DOMContentLoaded", function() {
        var tabs = document.querySelectorAll('.nav-pills .nav-link');
        var warningMessage = document.getElementById('tab-warning');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var activeTab = tab.getAttribute('href');
                localStorage.setItem('activeTab', activeTab);
                warningMessage.style.display = 'none'; // Oculta el mensaje cuando se selecciona una pestaña
            });
        });

        var activeTab = localStorage.getItem('activeTab');

        if (activeTab) {
            var tabLink = document.querySelector('.nav-pills .nav-link[href="' + activeTab + '"]');
            if (tabLink) {
                tabLink.click(); // Activa la pestaña guardada
                warningMessage.style.display = 'none'; // Oculta el mensaje si hay una pestaña seleccionada
            }
        } else {
            warningMessage.style.display = 'block'; // Muestra el mensaje si no hay ninguna pestaña seleccionada
        }
    });
</script>

@endsection
