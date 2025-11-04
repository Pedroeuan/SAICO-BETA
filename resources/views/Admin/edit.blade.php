
@extends('adminlte::page')

@section('title', 'Usuario')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
<style>
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }
</style>
@endsection

@section('content')
<br>  
<br>
<br>
<br>
<br>
<form id="UsuarioForm" method="post" enctype="multipart/form-data" action="{{ route('editUsuarios.update', ['id' => $id]) }}">
    @csrf
    <section class="content">

        <div class="card">
            <div class="card-body row">
                <div class="col-5 text-center d-flex align-items-center justify-content-center">
                    <div class>
                        <h2>Edición de datos del usuario</h2>
                        <br>
                        <img src="{{ asset('images/usuario.png') }}" alt="logo-aico" width="340" height="350">
                    </div>
                </div>

                    <div class="col-7">

                        <div class="form-group">
                            <label for="NombreUsuario">Nombre de Usuario</label>
                            <input type="text" class="form-control @error('NombreUsuario') is-invalid @enderror" placeholder="Nombre del usuario" id="NombreUsuario" name="NombreUsuario" value="{{ $Usuario->name }}">
                            @error('NombreUsuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="inputEmail">Correo</label>
                            <input type="email" class="form-control @error('CorreoUsuario') is-invalid @enderror" placeholder="Correo del usuario" name="CorreoUsuario" value="{{ $Usuario->email }}">
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
                                @if($rol=='Administrador')
                                <!--<option value="Super Administrador" @if($Usuario->rol == 'Super Administrador') selected="selected" @endif> Super Administrador</option>-->
                                <option value="Administrador" @if($Usuario->rol == 'Administrador') selected="selected" @endif> Administrador</option>
                                <option value="Cliente" @if($Usuario->rol == 'Cliente') selected="selected" @endif> Cliente</option>
                                <option value="Ventas" @if($Usuario->rol == 'Ventas') selected="selected" @endif> Ventas</option>
                                <option value="Técnicos" @if($Usuario->rol == 'Técnicos') selected="selected" @endif> Técnicos</option>
                                <option value="Planeación" @if($Usuario->rol == 'Planeación') selected="selected" @endif> Planeación</option>
                                <option value="Equipos" @if($Usuario->rol == 'Equipos') selected="selected" @endif> Equipos</option>
                                <option value="Laboratorio" @if($Usuario->rol == 'Laboratorio') selected="selected" @endif> Laboratorio</option>
                                <option value="Tics" @if($Usuario->rol == 'Tics') selected="selected" @endif> Tics</option>
                                @else
                                <option value="Super Administrador" @if($Usuario->rol == 'Super Administrador') selected="selected" @endif> Super Administrador</option>
                                <option value="Administrador" @if($Usuario->rol == 'Administrador') selected="selected" @endif> Administrador</option>
                                <option value="Cliente" @if($Usuario->rol == 'Cliente') selected="selected" @endif> Cliente</option>
                                <option value="Ventas" @if($Usuario->rol == 'Ventas') selected="selected" @endif> Ventas</option>
                                <option value="Técnicos" @if($Usuario->rol == 'Técnicos') selected="selected" @endif> Técnicos</option>
                                <option value="Planeación" @if($Usuario->rol == 'Planeación') selected="selected" @endif> Planeación</option>
                                <option value="Equipos" @if($Usuario->rol == 'Equipos') selected="selected" @endif> Equipos</option>
                                <option value="Laboratorio" @if($Usuario->rol == 'Laboratorio') selected="selected" @endif> Laboratorio</option>
                                <option value="Tics" @if($Usuario->rol == 'Tics') selected="selected" @endif> Tics</option>
                                @endif

                            </select>
                            @error('RolUsuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="inputSubject">Estatus</label>
                            <select class="form-control select2 @error('Estatus') is-invalid @enderror" style="width: 100%;" name="Estatus">
                                <option selected disabled>Selecciona un Estatus</option>
                                @if($Usuario->Estatus == 'ALTA')
                                <option value="ALTA" @if($Usuario->Estatus == 'ALTA') selected="selected" @endif>ALTA</option>
                                <option value="BAJA" @if($Usuario->Estatus == 'BAJA') selected="selected" @endif>BAJA</option>
                                @elseif($Usuario->Estatus == 'ALTA2')
                                <option value="ALTA2" @if($Usuario->Estatus == 'ALTA2') selected="selected" @endif>ALTA</option>
                                <option value="BAJA" @if($Usuario->Estatus == 'BAJA') selected="selected" @endif>BAJA</option>
                                @elseif($Usuario->Estatus == 'ALTA3')
                                <option value="ALTA3" @if($Usuario->Estatus == 'ALTA3') selected="selected" @endif>ALTA</option>
                                <option value="BAJA" @if($Usuario->Estatus == 'BAJA') selected="selected" @endif>BAJA</option>
                                @elseif($Usuario->Estatus == 'ALTA4')
                                <option value="ALTA4" @if($Usuario->Estatus == 'ALTA4') selected="selected" @endif>ALTA</option>
                                <option value="BAJA" @if($Usuario->Estatus == 'BAJA') selected="selected" @endif>BAJA</option>
                                @elseif($Usuario->Estatus == 'ALTA5')
                                <option value="ALTA5" @if($Usuario->Estatus == 'ALTA5') selected="selected" @endif>ALTA</option>
                                <option value="BAJA" @if($Usuario->Estatus == 'BAJA') selected="selected" @endif>BAJA</option>
                                @else
                                <option value="BAJA" @if($Usuario->Estatus == 'BAJA') selected="selected" @endif>BAJA</option>
                                <option value="ALTA" @if($Usuario->Estatus == 'ALTA') selected="selected" @endif>ALTA</option>
                                @endif 
                            </select>
                            @error('Estatus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Actualizar">
                        </div>
                    </div>

                
            </div>
        </div>

    </section>
</form>
        
@stop

@section('js')
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script>
/*Prevenir el Enter*/
document.getElementById('UsuarioForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });
</script>

@endsection

