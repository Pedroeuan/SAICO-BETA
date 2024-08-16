
@extends('adminlte::page')

@section('title', 'Usuario')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
@endsection

@section('content')
<br>  
<br>
<br>
<br>
<br>
<section class="content">

<div class="card">
    <div class="card-body row">
        <div class="col-5 text-center d-flex align-items-center justify-content-center">
            <div class>
                <h2>Registrar un nuevo usuario</h2>
                <br>
                <img src="{{ asset('images/usuario.png') }}" alt="logo-aico" width="340" height="350">
            </div>
        </div>
        <div class="col-7">
            <div class="form-group">
                <label for="inputName">Nombre</label>
                <input type="text" class="form-control" placeholder="Nombre del usuario" name="NombreUsuario">
            </div>
            <div class="form-group">
                <label for="inputEmail">Correo</label>
                <input type="email" class="form-control" placeholder="Correo del usuario" name="CorreoUsuario">
            </div>
            <div class="form-group">
                <label for="inputSubject">Contraseña</label>
                <input type="password" class="form-control" placeholder="Contraseña" name="ContraseñaUsuario" >
            </div>
            <div class="form-group">
                <label for="inputSubject">Repetir contraseña</label>
                <input type="password" class="form-control" placeholder="Repetir contraseña" name="RepetirContraseña">
            </div>
            <div class="form-group">
                <label for="inputSubject">Perfil</label>
                <select class="form-control select2"  style="width: 100%;" name="RolUsuario">
                    <option selected disabled>Selecciona un perfil</option>
                    <option>Administrador</option>
                    <option>Cliente</option>
                    <option>Ventas</option>
                    <option>Técnicos</option>
                    <option>Planeación</option>
                    <option>Equipos</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registrar">
            </div>
        </div>
    </div>
</div>
</section>
        
@stop

