
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
<br>  
<br>
<br>
 <!-- form start -->
 <form role="form">
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">

                <div class="form-group">
                  <label>Nombre equipo:</label>
                  <input class="form-control" type="text" name="Nombre_EyC" placeholder="Ejemplo: Yugo">
                </div>
                <!-- /.form-group -->
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>No economico:</label>
                  <input class="form-control" type="text" name="No_economico" placeholder="Ejemplo: ECO-001">
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Serie:</label>
                  <input class="form-control" type="text" name="Serie" placeholder="Ejemplo: N3199">
                </div>
                <!-- /.form-group -->
              </div>

               <!-- /.col -->
               <div class="col-md-6">
                <div class="form-group">
                  <label>Marca:</label>
                  <input class="form-control" type="text" name="Marca" placeholder="Ejemplo: MANGAFLUX">
                </div>
                <!-- /.form-group -->
              </div>

              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Modelo:</label>
                  <input class="form-control" type="text" name="Modelo" placeholder="Ejemplo: DPM">
                </div>
                <!-- /.form-group -->
              </div>

                <!-- /.col -->
                <div class="col-md-6">
                <div class="form-group">
                  <label>Ubicación:</label>
                  <input class="form-control" type="text" name="Ubicacion" placeholder="Ejemplo: OFICINA">
                </div>
                <!-- /.form-group -->
              </div>

                <!-- /.col -->
                <div class="col-md-6">
                <div class="form-group">
                  <label>Almacenamiento:</label>
                  <input class="form-control" type="text" name="Almacenamiento" placeholder="Ejemplo: TEMPERATURA AMBIENTE, SIN POLVO, SIN HUMEDAD E INDIRECTO AL SOL">
                </div>
                <!-- /.form-group -->
              </div>

              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Comentario:</label>
                  <textarea class="form-control" id="exampleFormControlTextarea1" rows="1" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto."></textarea>
                </div>
                <!-- /.form-group -->
              </div>

               <!-- /.col -->
               <div class="col-md-6">
                <div class="form-group">
                  <label>SAT:</label>
                  <input class="form-control" type="text" name="SAT" placeholder="Ejemplo: 41116500">
                </div>
                <!-- /.form-group -->
              </div>

              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>BMPRO:</label>
                  <input class="form-control" type="text" name="BMPRO" placeholder="Ejemplo: 5K010014">
                </div>
                <!-- /.form-group -->
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Factura:</label>
                  <input class="form-control" type="file" name="Factura" placeholder="Ejemplo: 5K010014">
                </div>
              </div>

              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Destino:</label>
                  <input class="form-control" type="text" name="Destino">
                </div>
                <!-- /.form-group -->
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Tipo:</label>
                  <select class="form-control select2" style="width: 100%;">
                    <option selected="selected">Elige un tipo</option>
                    <option value="">EQUIPOS</option>
                    <option value="">BLOCK Y PROBETA</option>
                    <option value="">ACCESORIOS</option>
                    <option value="">COMPLEMENTOS</option>
                    <option value="">CONSUMIBLES</option>
                  </select>
                </div>
        </div>

        <!-- /.col -->
        <div class="col-md-6">
                <div class="form-group">
                  <label>Disponibilidad:</label>
                  <input class="form-control" type="text" name="Disponibilidad">
                </div>
                <!-- /.form-group -->
              </div>

              <div class="col-md-6">
                <button type="submit" class="btn btn-primary">GUARDAR</button>
              </div>

              </div>
            </div>
          </div>
        </form>
        @stop

