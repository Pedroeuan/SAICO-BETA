
@extends('adminlte::page')

@section('title', 'Servicio')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
        table {
            width: 100%; /* Opcional: Para que ocupe todo el ancho disponible */
            border-collapse: collapse; /* Elimina los espacios entre bordes */
        }

        table th, table td {
            text-align: center; /* Centra el texto horizontalmente */
            vertical-align: middle; /* Centra el texto verticalmente */
            padding: 8px; /* Espaciado interno para mayor claridad */
        }

        table input {
            text-align: center; /* Centra el texto dentro de los inputs */
            box-sizing: border-box; /* Garantiza que los inputs respeten los bordes */
        }
        #addRowBtn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #addRowBtn:hover {
            background-color: #0056b3;
        }
    </style>
@endsection

@section('content')
<br>
<br>
<br>
<br>
<h3 align="center">Registro de Pruebas</h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="Seleccion" action="" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="inputSuccess">Pruebas</label>
                                        <select class="form-control inputForm" name="Prueba" id="PruebaSelect" required>
                                            <option value="">Seleccione una Prueba</option>
                                            @foreach ($Pruebas as $Prueba)
                                                <option value="{{ $Prueba->idPrueba }}" {{ $Prueba->Nombre == $service ? 'selected' : '' }}>
                                                    {{ $Prueba->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Prueba')
                                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="inputSuccess">Norma Código</label>
                                        <select class="form-control inputForm" name="NormaCodigo" id="NormaCodigoSelect" required>
                                        </select>
                                        @error('NormaCodigo')
                                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                        @enderror
                                    </div>
                                </div>
                                                                    
                                    <p>
                                    <p>
                                    <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>

                                        <!--<div class="float-left">
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarEquipos">Guardar y continuar</button>
                                        </di>-->
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </section>
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
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
    document.getElementById('Seleccion').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    document.addEventListener('DOMContentLoaded', function () {
    const pruebaSelect = document.getElementById('PruebaSelect');
    const normaSelect = document.getElementById('NormaCodigoSelect');

        pruebaSelect.addEventListener('change', function () {
            const pruebaId = this.value;

            // Limpia las opciones del segundo select
            normaSelect.innerHTML = '<option value="">Seleccione una Norma</option>';

            if (pruebaId) {
                fetch(`/Obtener/normas/${pruebaId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(norma => {
                                const option = document.createElement('option');
                                option.value = norma.idPrueba; // Ajusta el campo según la estructura de tu modelo
                                option.textContent = norma.Nombre; // Ajusta según lo que quieras mostrar
                                normaSelect.appendChild(option);
                            });
                        } else {
                            normaSelect.innerHTML = '<option value="">No hay normas disponibles</option>';
                        }
                    })
                    .catch(error => console.error('Error al obtener las normas:', error));
            }
        });

                // Dispara el evento 'change' en el select 'PruebaSelect' al cargar la página
                if (pruebaSelect.value) {
            pruebaSelect.dispatchEvent(new Event('change'));
        }
    });

    </script>
@endsection


