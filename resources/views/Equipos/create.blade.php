
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
<br>
<h3 align="center"> Formulario de Alta de datos</h3>
<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center">
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Equipos</a></li>
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
                        <div class="tab-pane active" id="tab_1">
                            <form id="equiposForm" action="{{route('general_eyc.storeEquipos')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Ejemplo: Yugo">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número Económico</label>
                                            <input type="text" class="form-control inputForm" name="No_economico" placeholder="Ejemplo: ECO-001">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Marca</label>
                                            <input type="text" class="form-control inputForm" name="Marca" placeholder="Ejemplo: MANGAFLUX">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Modelo</label>
                                            <input type="text" class="form-control inputForm" name="Modelo" placeholder="Ejemplo: DPM">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No.Serie</label>
                                            <input type="text" class="form-control inputForm" name="Serie" placeholder="Ejemplo: N3199">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ubicación</label>
                                            <input type="text" class="form-control inputForm" name="Ubicacion" placeholder="Ejemplo: OFICINA">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Almacenamiento</label>
                                            <input type="text" class="form-control inputForm" name="Almacenamiento" placeholder="Ejemplo: TEMPERATURA AMBIENTE, SIN POLVO, SIN HUMEDAD E INDIRECTO AL SOL">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" ></input>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE">DISPONIBLE</option>
                                                <option value="NO DISPONIBLE">NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA">FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Hoja de presentación</label>
                                            <input type="file" class="form-control inputForm" name="Foto">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No de certificado</label>
                                            <input type="text" class="form-control inputForm" name="No_certificado" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado actual</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ultima calibración</label>
                                            <input type="date" class="form-control inputForm" id="fecha" name="Fecha_calibracion">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Próxima calibración</label>
                                            <input type="date" class="form-control inputForm" name="Prox_fecha_calibracion">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SAT</label>
                                            <input type="text" class="form-control inputForm" name="SAT" placeholder="Ejemplo: 41116500">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">BMPRO</label>
                                            <input type="text" class="form-control inputForm" name="BMPRO" placeholder="Ejemplo: 5K010014">
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
                                            <textarea class="form-control is-waning" id="inputSuccess" name="Comentario" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto."></textarea>
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
                            <!-- Contenido de la primera pestaña -->
                        <div class="tab-pane" id="tab_2">
                            <form id="consumiblesForm" action="{{route('general_eyc.storeConsumibles')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Nombre</label>
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Enter ...">
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
                                            <label class="col-form-label" for="inputSuccess">Lote</label>
                                            <input type="text" class="form-control inputForm" name="Lote" placeholder="Enter ...">
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
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE">DISPONIBLE</option>
                                                <option value="NO DISPONIBLE">NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA">FUERA DE SERVICIO/BAJA</option>
                                            </select>
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
                                            <label class="col-form-label" for="inputSuccess">Ficha técnica</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No de certificado</label>
                                            <input type="text" class="form-control inputForm" name="No_certificado" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado actual</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha Caducidad</label>
                                            <input type="date" class="form-control inputForm" name="Fecha_calibracion">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Stock</label>
                                            <input type="number" class="form-control inputForm" name="Stock" placeholder="Enter ...">
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
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Enter ...">
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
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE">DISPONIBLE</option>
                                                <option value="NO DISPONIBLE">NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA">FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="Enter ...">
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
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Enter ...">
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
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE">DISPONIBLE</option>
                                                <option value="NO DISPONIBLE">NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA">FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Hoja de presentación</label>
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
                                            <label class="col-form-label" for="inputSuccess">No Certificado</label>
                                            <input type="text" class="form-control inputForm" name="No_Certificado" placeholder="Enter ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado de calibración</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="Enter ..." multiple>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Plano</label>
                                            <input type="file" class="form-control inputForm" name="Plano" placeholder="Enter ..." multiple>
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
                                            <input type="text" class="form-control inputForm" name="Nombre_E_P_BP"  placeholder="Enter ...">
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
                                            <label class="col-form-label" for="inputSuccess">Factura</label>
                                            <input type="file" class="form-control inputForm" name="Factura" placeholder="Enter ..."></input>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Disponibilidad</label>
                                            <select class="form-control select2" style="width: 100%;" name="Disponibilidad_Estado">
                                                <option selected="selected">Elige un Tipo</option>
                                                <option value="DISPONIBLE">DISPONIBLE</option>
                                                <option value="NO DISPONIBLE">NO DISPONIBLE</option>
                                                <option value="FUERA DE SERVICIO/BAJA">FUERA DE SERVICIO/BAJA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Garantía</label>
                                            <input type="file" class="form-control inputForm" name="Garantia" placeholder="Enter ...">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Ficha técnica</label>
                                            <input type="file" class="form-control inputForm" name="Foto" placeholder="Enter ...">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Certificado Actual</label>
                                            <input type="file" class="form-control inputForm" name="Certificado_Actual" placeholder="Enter ..." multiple>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Plano</label>
                                            <input type="file" class="form-control inputForm" name="Plano" placeholder="Enter ..." multiple>
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
                                            <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Tipo" value="HERRAMIENTAS">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Comentario</label>
                                            <textarea class="form-control is-waning" name="Comentario" id="inputSuccess" placeholder="Enter ..."></textarea>
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

                                </div> <!-- DIV IMPORTANTE, no quitar, se desajusta la tabla del inventario-->
                                
                                <h5 align="center">Inventario Disponible</h5>
                                    <!-- Tabla de Elementos Disponibles -->
                                <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
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
                                                <td>{{ $general_eyc->certificados ? $general_eyc->certificados->Fecha_calibracion : 'N/A' }}</td>
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


<script>
/*Solicitud*/
new DataTable('#tablaJs');

let dataTable;

function initializeDataTable() {
    // Destruir el DataTable si ya está inicializado
    if ($.fn.DataTable.isDataTable('#tablaJs')) {
        dataTable.destroy();
    }
    //Inicializar el DataTable
    dataTable = new DataTable('#tablaJs');
}

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

//<!--GUARDAR Y CONTINUAR-->
    /*KITS*/
    document.addEventListener('DOMContentLoaded', function() {
    // Evento click para el botón "Guardar y continuar"
    document.getElementById('guardarContinuarKits').addEventListener('click', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de manera convencional

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
</script>

@endsection
