@extends('adminlte::page')

@section('title', 'Crear Solicitud AD')

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

<!-- form start -->
    <div class="card-body">
        <form id="form-solicitud" method="POST" action="{{ route('ADsolicitud.store') }}">
            @csrf
            <div class="row">

        <div class="card">
            <div class="card-body row">

                <div class="col-md-4 mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="estatus" class="form-label">Estatus</label>
                    <input type="text" class="form-control" id="estatus" name="estatus" value="PENDIENTE" readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="TEMA" class="form-label">Tema Principal</label>
                    <input type="text" class="form-control" id="TEMA" name="Tema" placeholder="Ejemplo: SERVICIO PROTEXA" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="comentario" class="form-label">Comentario</label>
                    <input type="text" class="form-control" id="comentario" name="comentario" placeholder="Observaciones..." required>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Guardar Solicitud
                    </button>
                </div>

            </div>
        </form>
    </div>

@stop

@section('css')
    {{-- Estilos DataTables + SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
@stop

@section('js')
    {{-- Librerías necesarias --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

    </script>
@stop
