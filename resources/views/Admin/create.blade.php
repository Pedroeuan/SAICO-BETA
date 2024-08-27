
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
<form id="UsuarioForm" method="post" enctype="multipart/form-data" action="{{route('registro.storeUsuarios')}}">
    @csrf
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
                            <label for="NombreUsuario">Nombre de Usuario</label>
                            <input type="text" class="form-control @error('NombreUsuario') is-invalid @enderror" placeholder="Nombre del usuario" id="NombreUsuario" name="NombreUsuario" value="{{ old('NombreUsuario') }}">
                            @error('NombreUsuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="inputEmail">Correo</label>
                            <input type="email" class="form-control @error('CorreoUsuario') is-invalid @enderror" placeholder="Correo del usuario" name="CorreoUsuario" value="{{old('CorreoUsuario')}}">
                            @error('CorreoUsuario')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="inputSubject">Contraseña</label>
                            <input type="password" class="form-control @error('ContrasenaUsuario') is-invalid @enderror" placeholder="Contraseña" name="ContrasenaUsuario">
                            @error('ContrasenaUsuario')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="inputSubject">Repetir contraseña</label>
                            <input type="password" class="form-control @error('RepetirContrasena') is-invalid @enderror" placeholder="Repetir contraseña" name="RepetirContrasena">
                            @error('RepetirContrasena')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="inputSubject">Rol</label>
                            <select class="form-control select2 @error('RolUsuario') is-invalid @enderror" style="width: 100%;" name="RolUsuario">
                                <option selected disabled>Selecciona un perfil</option>
                                <option value="Super Administrador" {{ old('RolUsuario') == 'Super Administrador' ? 'selected' : '' }}>Super Administrador</option>
                                <option value="Administrador" {{ old('RolUsuario') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                <option value="Cliente" {{ old('RolUsuario') == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                                <option value="Ventas" {{ old('RolUsuario') == 'Ventas' ? 'selected' : '' }}>Ventas</option>
                                <option value="Técnicos" {{ old('RolUsuario') == 'Técnicos' ? 'selected' : '' }}>Técnicos</option>
                                <option value="Planeación" {{ old('RolUsuario') == 'Planeación' ? 'selected' : '' }}>Planeación</option>
                                <option value="Equipos" {{ old('RolUsuario') == 'Equipos' ? 'selected' : '' }}>Equipos</option>
                            </select>
                            @error('RolUsuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Registrar">
                        </div>
                    </div>

                
            </div>
        </div>

    </section>
</form>
        
@stop

