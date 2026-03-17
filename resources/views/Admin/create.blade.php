
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
                            <label for="NombreUsuario">Nombre de Usuario cliente</label>
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
                                @if($rol=='Administrador')
                                    <!--<option value="Administrador" {{ old('RolUsuario') == 'Administrador' ? 'selected' : '' }}>Administrador</option>-->
                                    <option value="Cliente" {{ old('RolUsuario') == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                                    <option value="Ventas" {{ old('RolUsuario') == 'Ventas' ? 'selected' : '' }}>Ventas</option>
                                    <option value="Técnicos" {{ old('RolUsuario') == 'Técnicos' ? 'selected' : '' }}>Técnicos</option>
                                    <option value="Planeación" {{ old('RolUsuario') == 'Planeación' ? 'selected' : '' }}>Planeación</option>
                                    <option value="Equipos" {{ old('RolUsuario') == 'Equipos' ? 'selected' : '' }}>Equipos</option>
                                    <option value="Laboratorio" {{ old('RolUsuario') == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                                    <option value="Tics" {{ old('RolUsuario') == 'Tics' ? 'selected' : '' }}>Tics</option>
                                    <option value="SGI" {{ old('RolUsuario') == 'SGI' ? 'selected' : '' }}>SGI</option>
                                @else
                                    <option value="Super Administrador" {{ old('RolUsuario') == 'Super Administrador' ? 'selected' : '' }}>Super Administrador</option>
                                    <option value="Administrador" {{ old('RolUsuario') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                    <option value="Cliente" {{ old('RolUsuario') == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                                    <option value="Ventas" {{ old('RolUsuario') == 'Ventas' ? 'selected' : '' }}>Ventas</option>
                                    <option value="Técnicos" {{ old('RolUsuario') == 'Técnicos' ? 'selected' : '' }}>Técnicos</option>
                                    <option value="Planeación" {{ old('RolUsuario') == 'Planeación' ? 'selected' : '' }}>Planeación</option>
                                    <option value="Equipos" {{ old('RolUsuario') == 'Equipos' ? 'selected' : '' }}>Equipos</option>
                                    <option value="Laboratorio" {{ old('RolUsuario') == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                                    <option value="Tics" {{ old('RolUsuario') == 'Tics' ? 'selected' : '' }}>Tics</option>
                                    <option value="SGI" {{ old('RolUsuario') == 'SGI' ? 'selected' : '' }}>SGI</option>
                                @endif
                            </select>
                            @error('RolUsuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Estatus">Estatus</label>
                            <input type="text" class="form-control @error('Estatus') is-invalid @enderror" placeholder="ALTA" id="Estatus" name="Estatus" value="ALTA" readonly>
                            @error('Estatus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <hr>

                        <h5>Documentación para Vehículos</h5>
                        <div class="form-group">
                            <label>Número de Licenica</label>
                            <input type="text" name="licencia_numero" class="form-control" placeholder="Número de licencia">
                        </div>

                        <div class="form-group">
                            <label>Fecha vencimeinto licencia</label>
                            <input type="date" name="licencia_vencimeinto" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label>Subir PDF Licencia</label>
                            <input type="file" name="licencia_pdf" class="form_control" accept="application/pdf">
                        </div>

                        <div class="form-group">
                            <label>Subir CV (PDF)</label>
                            <input type="file" name="cv_pdf" class="form-cotrol" accept="application/pdf">
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

    // Guardar datos en localStorage al escribir
document.querySelectorAll('#UsuarioForm input, #UsuarioForm textarea, #UsuarioForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('UsuarioForm_' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#UsuarioForm input, #UsuarioForm textarea, #UsuarioForm select').forEach(function(input) {
        let value = localStorage.getItem('UsuarioForm_' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('UsuarioForm').addEventListener('submit', function() {
    document.querySelectorAll('#UsuarioForm input, #UsuarioForm textarea, #UsuarioForm select').forEach(function(input) {
        localStorage.removeItem('UsuarioForm_' + input.name);
    });
});
</script>
@endsection


