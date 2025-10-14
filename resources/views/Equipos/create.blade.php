
@extends('adminlte::page')

@section('title', 'Registro E y C')

<style>
    #tablaJs td {
        text-align: center; /* Centra el contenido horizontalmente */
    }
    #tablaJs th {
        text-align: center; /* Centra el texto del encabezado horizontalmente */
    }

    #tablaSeleccionados td {
        text-align: center; /* Centra el contenido horizontalmente */
    }
    #tablaSeleccionados th {
        text-align: center; /* Centra el texto del encabezado horizontalmente */
    }

    .custom-container {
        max-width: 1405px;
    }
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }


</style>

@section('content')
<br>
<br>
<br>
<br>
<h3 align="center"> Formulario para el alta de datos</h3>
<br>

<div class="custom-container">
    <div class="row justify-content-center">
        <div class="col-sm-12">

            <div id="tab-warning" class="alert alert-warning text-center" style="display: none;">
                Por favor, seleccione una pestaña.
            </div>

            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center">
                        @if($rol == 'Laboratorio')
                        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Equipos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Consumibles</a></li>
                        @else
                        <li class="nav-item"><a class="nav-link" href="#tab_1" data-toggle="tab">TIC´S</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Equipos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Consumibles</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Accesorios</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_5" data-toggle="tab">Block Y Probeta</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_6" data-toggle="tab">Herramientas</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_7" data-toggle="tab">Kits</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_8" data-toggle="tab">Importar</a></li>
                        @endif
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header p-2-->
                <div class="card-body">
                    <div class="tab-content">

                    <!--TICS -->
                    <div class="tab-pane" id="tab_1">
                            <form id="TICSForm" action="{{route('general_eyc.storeTICS')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    
                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm @error('Nombre_E_P_BP') is-invalid @enderror" name="Nombre_E_P_BP"  placeholder="Ejemplo: Yugo" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">NOMBRE</label>
                                            <select class="form-control select2" style="width: 100%;" name="Nombre_E_P_BP">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="COMPUTADORA" {{ old('Nombre_E_P_BP') == 'ADAPTADOR' ? 'selected' : '' }}>ADAPTADOR</option>
                                                <option value="ESCRITORIO" {{ old('Nombre_E_P_BP') == 'ESCRITORIO' ? 'selected' : '' }}>ESCRITORIO</option>
                                                <option value="LAPTOP" {{ old('Nombre_E_P_BP') == 'LAPTOP' ? 'selected' : '' }}>LAPTOP</option>
                                                <option value="ELIMINADOR DE CORRIENTE" {{ old('Nombre_E_P_BP') == 'ELIMINADOR DE CORRIENTE' ? 'selected' : '' }}>ELIMINADOR DE CORRIENTE</option>
                                                <option value="IMPRESORA" {{ old('Nombre_E_P_BP') == 'IMPRESORA' ? 'selected' : '' }}>IMPRESORA</option>
                                                <option value="TINTA" {{ old('Nombre_E_P_BP') == 'TINTA' ? 'selected' : '' }}>TINTA</option>
                                                <option value="ESCANER" {{ old('Nombre_E_P_BP') == 'ESCANER' ? 'selected' : '' }}>ESCANER</option>
                                                <option value="MODEM PORTATIL" {{ old('Nombre_E_P_BP') == 'MODEM PORTATIL' ? 'selected' : '' }}>MODEM PORTATIL</option>
                                                <option value="USB" {{ old('Nombre_E_P_BP') == 'USB' ? 'selected' : '' }}>USB</option>
                                                <option value="MOUSE" {{ old('Nombre_E_P_BP') == 'MOUSE' ? 'selected' : '' }}>MOUSE</option>
                                                <option value="TECLADO" {{ old('Nombre_E_P_BP') == 'TECLADO' ? 'selected' : '' }}>TECLADO</option>
                                                <option value="ADAPTADOR" {{ old('Nombre_E_P_BP') == 'ADAPTADOR' ? 'selected' : '' }}>ADAPTADOR</option>
                                            </select>
                                        </div>
                                    </div>-->

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">ID</label>
                                            <input type="text" class="form-control inputForm @error('ID') is-invalid @enderror" name="ID" placeholder="Ejemplo: ID-58" value="{{old('ID')}}">
                                            @error('ID')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm @error('Marca') is-invalid @enderror" name="Marca" placeholder="Ejemplo: DELL" value="{{old('Marca')}}">
                                            @error('Marca')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm @error('Modelo') is-invalid @enderror" name="Modelo" placeholder="Ejemplo: L500" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Serie</label>
                                            <input type="text" class="form-control inputForm @error('Serie') is-invalid @enderror" name="Serie" placeholder="Ejemplo: SD45N3199" value="{{old('Serie')}}">
                                            @error('Serie')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
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
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Ejemplo: 2da cajon de la caja blanca" value="{{old('Almacenamiento')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Factura">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
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
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Foto">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Stock</label>
                                            <input type="number" class="form-control inputForm @error('Stock') is-invalid @enderror" name="Stock" placeholder="Ejemplo: 1.2.3..20.." value="{{ old('Stock') }}">
                                            @error('Stock')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Unidad</label>
                                            <input type="text" class="form-control inputForm" name="Unidad" placeholder="Ejemplo: PZ, Bote, Caja, etc" value="PZA" @if($rol!='Super Administrador' || $rol!='Administrador')readonly @endif>
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
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Tipo" value="TICS">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Clasificacion" value="N/A">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="ISO" value="N/A">
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
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarTICS">Guardar y continuar</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <!--EQUIPOS -->
                        <div class="tab-pane" id="tab_2">
                            <form id="equiposForm" action="{{route('general_eyc.storeEquipos')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm @error('Nombre_E_P_BP') is-invalid @enderror" name="Nombre_E_P_BP"  placeholder="Ejemplo: Yugo" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">@if($rol == 'Laboratorio') ID @else Número Económico @endif</label>
                                            <input type="text" class="form-control inputForm @error('No_economico') is-invalid @enderror" name="No_economico" placeholder="Ejemplo: ECO-001" value="{{old('No_economico')}}">
                                            @error('No_economico')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm @error('Marca') is-invalid @enderror" name="Marca" placeholder="Ejemplo: MANGAFLUX" value="{{old('Marca')}}">
                                            @error('Marca')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm @error('Modelo') is-invalid @enderror" name="Modelo" placeholder="Ejemplo: DPM" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Serie</label>
                                            <input type="text" class="form-control inputForm @error('Serie') is-invalid @enderror" name="Serie" placeholder="Ejemplo: N3199" value="{{old('Serie')}}">
                                            @error('Serie')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    @if($rol == 'Laboratorio' )
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Alcance</label>
                                                <input type="text" class="form-control inputForm" name="Alcance" placeholder="Ejemplo: PT-MT/UT (PAUT)/UT (HR & HA)" value="{{old('Alcance')}}">
                                            </div>
                                        </div>
                                    @endif

                                    @if($rol != 'Laboratorio')
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
                                    @endif

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Factura">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">@if($rol != 'Laboratorio')Disponibilidad @else Estatus @endif</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected" value="">Elige un Tipo</option>
                                                @if($rol == 'Laboratorio')
                                                    <option value="Equipo Disponible" {{ old('Disponibilidad_Estado') == 'Equipo Disponible' ? 'selected' : '' }}>Equipo Disponible</option> 
                                                    <option value="Equipo Fuera de Servicio" {{ old('Disponibilidad_Estado') == 'Equipo Fuera de Servicio' ? 'selected' : '' }}>Equipo Fuera de Servicio</option>
                                                    <option value="En Servicio" {{ old('Disponibilidad_Estado') == 'En Servicio' ? 'selected' : '' }}>En Servicio </option>
                                                    <option value="Equipo en Resguardo" {{ old('Disponibilidad_Estado') == 'Equipo en Resguardo' ? 'selected' : '' }}>Equipo en Resguardo</option>
                                                @else
                                                    <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                    <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                    <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    @if($rol != 'Laboratorio')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Hoja de presentación</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Foto">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No de certificado</label>
                                            <input type="text" class="form-control inputForm" name="No_certificado" placeholder="" value="{{old('No_certificado')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado actual</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Certificado_Actual" placeholder="">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">@if($rol == 'Laboratorio' ) Fecha Calibración @else Ultima calibración @endif</label>
                                            <input type="date" class="form-control inputForm" id="fechac" name="Fecha_calibracion" value="{{ old('Fecha_calibracion') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">@if($rol == 'Laboratorio' ) Siguiente Calibración @else Próxima calibración @endif</label>
                                            <input type="date" class="form-control inputForm" name="Prox_fecha_calibracion" value="{{ old('Prox_fecha_calibracion') }}">
                                        </div>
                                    </div>

                                    @if($rol == 'Laboratorio' )
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Fecha Verificación</label>
                                                <input type="date" class="form-control inputForm" id="fechav" name="Fecha_verificacion" value="{{ old('Fecha_verificacion') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess"> Siguiente Verificación</label>
                                                <input type="date" class="form-control inputForm" name="Prox_fecha_verificacion" value="{{ old('Prox_fecha_verificacion') }}">
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha Mantenimiento</label>
                                            <input type="date" class="form-control inputForm" id="fecham" name="Fecha_mantenimiento" value="{{ old('Fecha_mantenimiento') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess"> @if($rol == 'Laboratorio' ) Siguiente Mantenimiento @else Fecha de Proximo Mantenimiento @endif</label>
                                            <input type="date" class="form-control inputForm" name="Prox_fecha_mantenimiento" value="{{ old('Prox_fecha_mantenimiento') }}">
                                        </div>
                                    </div>

                                    @if($rol != 'Laboratorio' )
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Numero de Reporte</label>
                                                <input type="text" class="form-control inputForm" name="Num_Reporte" placeholder="Ejemplo: 042-2025" value="{{old('Num_Reporte')}}">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess"> @if($rol == 'Laboratorio' ) Frecuencia de Calibración  @else Mantenimiento Preventivo @endif</label>
                                            <input type="text" class="form-control inputForm" name="Frec_Cali_Mant_Prev" @if($rol == 'Laboratorio' ) placeholder="Ejemplo: ANUAL" @else placeholder="Ejemplo: SI/NO/N/A" @endif value="{{ old('Frec_Cali_Mant_Prev') }}">
                                        </div>
                                    </div>

                                    <!--<div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess"> @if($rol == 'Laboratorio' ) Frecuencia de Mantenimiento @else Intervalo de Tiempo @endif</label>
                                            <input type="text" class="form-control inputForm" name="Frec_Man_Inter_Time" @if($rol == 'Laboratorio' )  placeholder="Ejemplo: ANUAL" @else placeholder="Ejemplo: 12/6 MESES - N/A" @endif value="{{ old('Frec_Man_Inter_Time') }}">
                                        </div>
                                    </div>

                                    @if($rol == 'Laboratorio' )
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Frecuencia de Verificación</label>
                                                <input type="text" class="form-control inputForm" name="Frec_Verificacion" placeholder="Ejemplo: ANUAL" value="{{old('Frec_Verificacion')}}">
                                            </div>
                                        </div>
                                    @endif

                                    @if($rol != 'Laboratorio')
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
                                    @endif

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Clasificación</label>
                                            <select class="form-control select2" style="width: 100%;" name="Clasificacion" required>
                                                <option selected="selected" value="ESPERA DE DATO">Elige el tipo de inspección que pertenece</option>
                                                <option value="PND" {{ old('Clasificacion') == 'PND' ? 'selected' : '' }}>PND</option>
                                                <option value="IM" {{ old('Clasificacion') == 'IM' ? 'selected' : '' }}>IM</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">ISO</label>
                                            <select class="form-control select2" style="width: 100%;" name="ISO" required>
                                                <option>Elige el tipo de ISO</option>
                                                @if ($rol == 'Equipos')<option selected="selected" value="9001" {{ old('ISO') == '9001' ? 'selected' : '' }}>9001</option>@endif
                                                @if ($rol == 'Laboratorio')<option selected="selected" value="17025" {{ old('ISO') == '17025' ? 'selected' : '' }}>17025</option> @endif
                                            </select>
                                        </div>
                                    </div>-->

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Unidad</label>
                                            <input type="text" class="form-control inputForm" name="Unidad" placeholder="Ejemplo: PZ, Bote, Caja, etc" value="PZA" @if($rol!='Super Administrador' || $rol!='Administrador')readonly @endif required>
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
                        <div class="tab-pane" id="tab_3">
                            <form id="consumiblesForm" action="{{route('general_eyc.storeConsumibles')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            @if($rol != 'Laboratorio')
                                                <input type="text" class="form-control inputForm @error('Nombre_E_P_BP') is-invalid @enderror" name="Nombre_E_P_BP"  placeholder="Ejemplo: Yugo" value="{{old('Nombre_E_P_BP')}}">
                                                @else
                                                <textarea class="form-control is-waning" id="inputSuccess" name="Nombre_E_P_BP" placeholder="Ejemplo:  Bote en aerosol de liquido penetrante visiblere movible con solvente o post emulsificable.">{{old('Nombre_E_P_BP')}}</textarea>
                                            @endif
                                            
                                            @error('Nombre_E_P_BP')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm @error('Marca') is-invalid @enderror" name="Marca" placeholder="Ejemplo: KARL DEUTSCH" value="{{old('Marca')}}">
                                            @error('Marca')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm @error('Modelo') is-invalid @enderror" name="Modelo" placeholder="Ejemplo: DPM" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">@if($rol =='Laboratorio') No. SERIE / No. DE LOTE @else Lote @endif</label>
                                            <input type="text" class="form-control inputForm" name="Lote" placeholder="Ejemplo: 4092" value="{{old('Lote')}}">
                                        </div>
                                    </div>

                                    @if($rol == 'Laboratorio' )
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Alcance</label>
                                                <input type="text" class="form-control inputForm" name="Alcance" placeholder="Ejemplo: PT-MT/UT (PAUT)/UT (HR & HA)" value="{{old('Alcance')}}">
                                            </div>
                                        </div>
                                    @endif

                                    @if($rol != 'Laboratorio')
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
                                    @endif
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Factura"></input>
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">@if($rol != 'Laboratorio')Disponibilidad @else Estatus @endif</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado" required>
                                                <option selected="selected" value="">Elige un Tipo</option>
                                                {{--@if($rol == 'Laboratorio')
                                                    <option value="Equipo Disponible" {{ old('Disponibilidad_Estado') == 'Equipo Disponible' ? 'selected' : '' }}>Equipo Disponible</option> 
                                                    <option value="Equipo Fuera de Servicio" {{ old('Disponibilidad_Estado') == 'Equipo Fuera de Servicio' ? 'selected' : '' }}>Equipo Fuera de Servicio</option>
                                                    <option value="En Servicio" {{ old('Disponibilidad_Estado') == 'En Servicio' ? 'selected' : '' }}>En Servicio </option>
                                                    <option value="Equipo en Resguardo" {{ old('Disponibilidad_Estado') == 'Equipo en Resguardo' ? 'selected' : '' }}>Equipo en Resguardo</option>
                                                @else--}}
                                                    <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                    <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                    <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                                {{--@endif--}}
                                            </select>
                                        </div>
                                    </div>

                                    @if($rol != 'Laboratorio')
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Proveedor</label>
                                                <input type="text" class="form-control inputForm" name="Proveedor" placeholder="Brüder NDT " value="{{old('Proveedor')}}">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ficha técnica</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Foto" >
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
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
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Certificado_Actual" placeholder="">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
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
                                            <label class="col-form-label" for="inputSuccess">@if($rol != 'Laboratorio' )Stock @else Stock Total (Usado y NO Usado) @endif</label>
                                            <input type="number" class="form-control inputForm @error('Stock') is-invalid @enderror" name="Stock" placeholder="Ejemplo: 1.2.3..20.." value="{{ old('Stock') }}">
                                            @error('Stock')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    @if($rol == 'Laboratorio' )
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Usado</label>
                                                <input type="number" class="form-control inputForm @error('Usado') is-invalid @enderror" name="Usado" placeholder="Ejemplo: 1.2.3..20.." value="{{ old('Usado') }}">
                                                @error('Usado')
                                                        <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($rol == 'Laboratorio' )
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Nuevo</label>
                                                <input type="number" class="form-control inputForm @error('Nuevo') is-invalid @enderror" name="Nuevo" placeholder="Ejemplo: 1.2.3..20.." value="{{ old('Usado') }}">
                                                @error('Nuevo')
                                                        <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Unidad</label>
                                            <input type="text" class="form-control inputForm" name="Unidad" placeholder="Ejemplo: PZ, Bote, Caja, etc" value="PZA">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Clasificación</label>
                                            <select class="form-control select2" style="width: 100%;" name="Clasificacion" required>
                                                <option selected="selected" value="ESPERA DE DATO">Elige el tipo de inspección que pertenece</option>
                                                <option value="PND" {{ old('Clasificacion') == 'PND' ? 'selected' : '' }}>PND</option>
                                                <option value="IM" {{ old('Clasificacion') == 'IM' ? 'selected' : '' }}>IM</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">ISO</label>
                                            <select class="form-control select2" style="width: 100%;" name="ISO" required>
                                                <option>Elige el tipo de ISO que pertenece</option>
                                                @if ($rol == 'Equipos')<option selected="selected" value="9001" {{ old('ISO') == '9001' ? 'selected' : '' }}>9001</option>@endif
                                                @if ($rol == 'Laboratorio')<option selected="selected" value="17025" {{ old('ISO') == '17025' ? 'selected' : '' }}>17025</option> @endif
                                            </select>
                                        </div>
                                    </div>
                                    
                                    @if($rol == 'Laboratorio')
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Fecha de adquisición /Fecha de alta</label>
                                                <input type="date" class="form-control inputForm" name="Fecha_Alta" value="{{ old('Fecha_Alta') }}">
                                            </div>
                                        </div>
                                    @endif

                                    @if($rol != 'Laboratorio')
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
                                    @endif
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
                        <div class="tab-pane" id="tab_4">
                            <form id="accesoriosForm" action="{{route('general_eyc.storeAccesorios')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm @error('Nombre_E_P_BP') is-invalid @enderror" name="Nombre_E_P_BP"  placeholder="Ejemplo: Cable DUAL con cubierta de acero inox. (uso rudo)" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm @error('No_economico') is-invalid @enderror" name="No_economico" placeholder="Ejemplo: No. AICO-001" value="{{old('No_economico')}}">
                                            @error('No_economico')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm @error('Marca') is-invalid @enderror" name="Marca" placeholder="Ejemplo: Brüder NDT" value="{{old('Marca')}}">
                                            @error('Marca')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm @error('Modelo') is-invalid @enderror" name="Modelo" placeholder="Ejemplo: DPM" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Serie</label>
                                            <input type="text" class="form-control inputForm inputForm @error('Serie') is-invalid @enderror" name="Serie" placeholder="Ejemplo: N3199" value="{{old('Serie')}}">
                                            @error('Serie')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
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
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Factura"></input>
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected" value="">Elige un Tipo</option>
                                                <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Certificado_Actual">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
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
                                            <label class="col-form-label" for="inputSuccess">Stock</label>
                                            <input type="number" class="form-control inputForm @error('Stock') is-invalid @enderror" name="Stock" placeholder="Ejemplo: 1.2.3..20.." value="{{ old('Stock') }}">
                                            @error('Stock')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Unidad</label>
                                            <input type="text" class="form-control inputForm" name="Unidad" placeholder="Ejemplo: PZ, Bote, Caja, etc" value="PZA" @if($rol!='Super Administrador' || $rol!='Administrador')readonly @endif>
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
                        <div class="tab-pane" id="tab_5">
                            <form id="blocksForm" action="{{route('general_eyc.storeBlocks')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    
                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm @error('Nombre_E_P_BP') is-invalid @enderror" name="Nombre_E_P_BP"  placeholder="Ejemplo: BLOCK ASME T= 3/4"" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm @error('No_economico') is-invalid @enderror" name="No_economico" placeholder="Ejemplo: No. ECO-B-034" value="{{old('No_economico')}}">
                                            @error('No_economico')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm @error('Marca') is-invalid @enderror" name="Marca" placeholder="Ejemplo: Brüder NDT" value="{{old('Marca')}}">
                                            @error('Marca')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm @error('Modelo') is-invalid @enderror" name="Modelo" placeholder="Ejemplo: 5-STEPS-ACERO" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Serie</label>
                                            <input type="text" class="form-control inputForm @error('Serie') is-invalid @enderror" name="Serie" placeholder="Ejemplo: 102021CUT05" value="{{old('Serie')}}">
                                            @error('Serie')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
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
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Factura"></input>
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected" value="">Elige un Tipo</option>
                                                <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Hoja de presentación</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Foto">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
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
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Certificado_Actual" >
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Plano</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Plano" >
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
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
                                            <label class="col-form-label" for="inputSuccess">Unidad</label>
                                            <input type="text" class="form-control inputForm" name="Unidad" placeholder="Ejemplo: PZ, Bote, Caja, etc" value="PZA" @if($rol!='Super Administrador' || $rol!='Administrador')readonly @endif>
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
                        <div class="tab-pane" id="tab_6">
                            <form id="herramientasForm" action="{{ route('general_eyc.storeHerramientas') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm @error('Nombre_E_P_BP') is-invalid @enderror" name="Nombre_E_P_BP"  placeholder="Ejemplo: Sonda cableada regular" value="{{old('Nombre_E_P_BP')}}">
                                            @error('Nombre_E_P_BP')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm @error('No_economico') is-invalid @enderror" name="No_economico" placeholder="Ejemplo: No. AD-003" value="{{old('No_economico')}}">
                                            @error('No_economico')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm @error('Marca') is-invalid @enderror" name="Marca" placeholder="Ejemplo: DeFelsko" value="{{old('Marca')}}">
                                            @error('Marca')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm @error('Modelo') is-invalid @enderror" name="Modelo" placeholder="Ejemplo: FS" value="{{old('Modelo')}}">
                                            @error('Modelo')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Serie</label>
                                            <input type="text" class="form-control inputForm @error('Serie') is-invalid @enderror" name="Serie" placeholder="Ejemplo: 190776" value="{{old('Serie')}}">
                                            @error('Serie')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
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
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Factura"></input>
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected" value="">Elige un Tipo</option>
                                                <option value="DISPONIBLE" {{ old('Disponibilidad_Estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                                <option value="NO DISPONIBLE" {{ old('Disponibilidad_Estado') == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA" {{ old('Disponibilidad_Estado') == 'FUERA DE SERVICIO/BAJA' ? 'selected' : '' }}>FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Garantía</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Garantia" >
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ficha técnica</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Foto" >
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado Actual</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Certificado_Actual" >
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Plano</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="Plano" >
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
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
                                            <label class="col-form-label" for="inputSuccess">Unidad</label>
                                            <input type="text" class="form-control inputForm" name="Unidad" placeholder="Ejemplo: PZ, Bote, Caja, etc" value="PZA" @if($rol!='Super Administrador' || $rol!='Administrador')readonly @endif>
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
                        <div class="tab-pane" id="tab_7">
                            <form id="kitForm" action="{{ route('GuardarKits.agregarKits') }}" method="post" enctype="multipart/form-data" >
                                @csrf
                                <div class="box">
                                        <div class="d-flex justify-content-between align-items-center mb-3">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Nombre</label>
                                                    <input type="text" class="form-control inputForm" name="Nombre" placeholder="Ejemplo: Kit de Liquidos" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Prueba</label>
                                                    <input type="text" class="form-control inputForm" name="Prueba" placeholder="Ejemplo: Liquidos" required>
                                                </div>
                                            </div>

                                        </div>
                                </div>  
                                
                                <br>
                                <div class="alert alert-info alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-info"></i> Importante</h5>
                                    <p>Con el botón agregar, elige los equipos y consumibles para armar el KIT</p>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <button id="actualizarTablaBtn" type="button" class="btn btn-warning">
                                            <i class="fas fa-sync-alt"></i>
                                            <span>Actualizar tabla</span>
                                        </button>
                                    </div>
                                </div>
                                    <!-- Tabla de Elementos Disponibles -->
                                <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Num. Económico</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>NS</th>
                                                <th>Stock</th>
                                                <th>Disponibilidad</th>
                                                <th>Fecha calibración</th>
                                                <th>Ver</th>
                                                <th>Agregar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($generalConCertificados as $general_eyc)
                                            @php //dump($general_eyc->Disponibilidad_Estado);
                                            @endphp
                                            <tr data-id="{{ $general_eyc->idGeneral_EyC }}">
                                                <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                                                <td scope="row">{{$general_eyc->No_economico}}</td>
                                                <td scope="row">{{$general_eyc->Marca}}</td>
                                                <td scope="row">{{$general_eyc->Modelo}}</td>
                                                <td scope="row">{{$general_eyc->Serie}}</td>
                                                <td scope="row">{{$general_eyc->almacen->Stock}}</td>
                                                @if($general_eyc->Disponibilidad_Estado=='DISPONIBLE')
                                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-success">Disponible<i class="fa fa-check" aria-hidden="true"></i></td>
                                                    @elseif($general_eyc->Disponibilidad_Estado=='Equipo Disponible')
                                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-success">Equipo Disponible<i class="fa fa-check" aria-hidden="true"></i></td>
                                                    @elseif($general_eyc->Disponibilidad_Estado=='NO DISPONIBLE' )
                                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-warning">No Disponible<i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td>
                                                    @elseif($general_eyc->Disponibilidad_Estado=='Equipo Fuera de Servicio')
                                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-warning">Equipo Fuera de Servicio<i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td>
                                                    @elseif($general_eyc->Disponibilidad_Estado=='FUERA DE SERVICIO/BAJA')
                                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-danger">Fuera de servicio<i class="fa fa-ban" aria-hidden="true"></i></td>
                                                    @elseif($general_eyc->Disponibilidad_Estado=='Equipo en Resguardo')
                                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-danger">Equipo en Resguardo<i class="fa fa-ban" aria-hidden="true"></i></td>
                                                    @elseif($general_eyc->Disponibilidad_Estado=='En Servicio')
                                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-warning" style="color:#ff8800; border:1 px;">En Servicio <i class="far fa-clock" aria-hidden="true"></i></td>
                                                    @elseif($general_eyc->Disponibilidad_Estado=='ESPERA DE DATO')
                                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-info">Espera de Dato<i class="far fa-clock" aria-hidden="true"></i></td>
                                                @endif

                                                @if($general_eyc->certificados)
                                                    @if($general_eyc->Tipo == 'EQUIPOS' || $general_eyc->Tipo == 'BLOCK Y PROBETA')
                                                            @if($general_eyc->certificados->Fecha_calibracion == '2001-01-01')
                                                                <td scope="row">SIN FECHA ASIGNADA</td>
                                                                @else
                                                                <td scope="row">{{$general_eyc->certificados->Fecha_calibracion}}</td>
                                                            @endif
                                                        @else
                                                        <td scope="row">N/A</td>
                                                    @endif
                                                @endif

                                                <td scope="row">
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
                                <br>
                                <div class="alert alert-info alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-info"></i> Importante</h5>
                                    <p>Estos son los elementos que has agregado al nuevo KIT</p>
                                </div>
                                <br>
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
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                </table>

                                <div class="container">
                                        <div class="float-right">
                                            <button type="submit" id="btnFinalizarkit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>

                                        <div class="float-left">
                                        <button type="submit" class="btn btn-info bg-success" id="guardarContinuarKits" data-submit-type="guardar-continuar">Guardar y continuar</button>
                                        </div>
                                </div>

                            </form>
                        </div><!--"class="tab-pane" id="tab_7""-->

                        <div class="tab-pane" id="tab_8">

                            <div class="d-flex justify-content-center" style="min-height: 10vh;">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <form id="importarExcelForm" enctype="multipart/form-data">
                                            @csrf
                                            <label class="col-form-label" for="inputSuccess">IMPORTAR DATOS DEL EXCEL</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="archivo" required>
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                            <div class="text-center mt-3">
                                                <button type="button" id="btnImportar" class="btn btn-info bg-primary">Importar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div><!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div><!-- /.card -->   
        </div><!-- class="col-sm-12" -->
    </div><!--  class="row justify-content-center" -->
</div><!-- class="container" -->     
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
<!--<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>-->


<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script src="{{ asset('js/Alta_Equipos.js') }}"></script>
<script>
$(document).ready(function() {
    function setDisponibilidadBgColor(select) {
        var val = $(select).val();
        var bg = '';
        var color = 'white';
        // Solo para opciones del laboratorio
        if (val === 'Equipo Disponible' || val === 'Nuevo') {
            bg = '#28a745'; // verde
            color = 'white';
        } else if (val === 'Equipo Fuera de Servicio' || val === 'Usado') {
            bg = '#eeff07ff'; // amarillo
            color = 'black';
        } else if (val === 'En Servicio') {
            bg = '#dca735'; // naranja
            color = 'white';
        }else if (val === 'Equipo en Resguardo' || val === 'Terminado') {
            bg = '#dc3545'; // rojo
            color = 'white';
        } else {
            bg = 'white';
            color = 'black';
        }
        $(select).css({
            'background-color': bg,
            'color': color
        });
    }

    $('select[name="Disponibilidad_Estado"]').each(function() {
        setDisponibilidadBgColor(this);
    }).on('change', function() {
        setDisponibilidadBgColor(this);
    });
});

document.getElementById('btnImportar').addEventListener('click', function () {
    // Crear un objeto FormData con el formulario
    var form = document.getElementById('importarExcelForm');
    var formData = new FormData(form);
    var btnImportar = document.getElementById('btnImportar');

        // Deshabilitar el botón
        btnImportar.disabled = true;

    // Realizar la solicitud AJAX
    $.ajax({
        url: '{{ route('importar.EyC') }}', // Ruta que recibe el archivo en el servidor
        type: 'POST',
        data: formData, // Los datos del formulario
        processData: false, // Evitar que jQuery procese los datos
        contentType: false, // Evitar que jQuery establezca el Content-Type
        success: function(response) {
            // Mostrar mensaje de éxito usando SweetAlert2
            Swal.fire({
                title: '¡Importación Exitosa!',
                text: response.success, // Mensaje de la respuesta JSON
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });

            // Resetear el campo de archivo después de la importación exitosa
            $('input[type="file"]').val(''); // Resetea el campo de tipo file

            // Habilitar el botón
            btnImportar.disabled = false;
        },
        error: function(error) {
            // Manejar errores con SweetAlert2
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al importar los datos.',
                icon: 'error',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#d33'
            });
            // Habilitar el botón
            btnImportar.disabled = false;
        }
    });
});

</script>

@endsection
