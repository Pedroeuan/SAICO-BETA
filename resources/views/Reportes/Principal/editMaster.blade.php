
@extends('adminlte::page')

@section('title', 'Crear Reporte')

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

    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }

    </style>
@endsection

{{-- @section('content')
@if($Nombre_Formato == 'FOR-02-PRO-INS-10') 
    @include('Reportes.INS.Edit.FOR-02-PRO-INS-10')
@endif
@stop --}}

@section('content')
@if($Nombre_Formato == 'FOR-01-PRO-INS-02') 
    @include('Reportes.INS.Edit.FOR-01-PRO-INS-02')
{{-- @elseif($Nombre_Formato == 'FOR-01-PRO-INS-03') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-03')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-04') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-04')
@elseif($Nombre_Formato == 'FOR-02-PRO-INS-04') 
    @include('Reportes.INS.Create.FOR-02-PRO-INS-04')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-05') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-05')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-06') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-06')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-07') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-07')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-08') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-08')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-09') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-09')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-10') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-10') --}}
@elseif($Nombre_Formato == 'FOR-02-PRO-INS-10') 
    @include('Reportes.INS.Edit.FOR-02-PRO-INS-10')
{{-- @elseif($Nombre_Formato == 'FOR-01-PRO-INS-12') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-12')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-13') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-13')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-15') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-15')
@elseif($Nombre_Formato == 'FOR-02-PRO-INS-15') 
    @include('Reportes.INS.Create.FOR-02-PRO-INS-15')
@elseif($Nombre_Formato == 'FOR-03-PRO-INS-15') 
    @include('Reportes.INS.Create.FOR-03-PRO-INS-15')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-16') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-16')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-17') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-17')
@elseif($Nombre_Formato == 'FOR-01-PRO-INS-18') 
    @include('Reportes.INS.Create.FOR-01-PRO-INS-18') --}}
@endif
@endsection


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

$(document).ready(function() {
        var rowCount = 0;

        function updateRowNumbers() {
            $('#Norma_Codigo tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
            rowCount = $('#Norma_Codigo tbody tr').length;
        }

        $('#addRowBtn').click(function() {
            rowCount++;
            var newRow =`<tr>
            <td>${rowCount}</td>
            <td><input type="text" class="form-control" name="codigo[]" placeholder="Codigo o Norma Aplicable"></td>
            <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
            </tr>`;
            $('#Norma_Codigo tbody').append(newRow);
        });

        $('#Norma_Codigo').on('click', '.btnEliminar', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });
    });

    $('#Prueba_Norma_Codigo').submit(function(event) {
        if ($('#Norma_Codigo tbody tr').length === 0) {
            event.preventDefault();
            Swal.fire({
                title: 'Advertencia',
                text: 'Debe agregar al menos una norma o código aplicable.',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    /*Prevenir el Enter*/
    document.getElementById('Prueba_Norma_Codigo').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    </script>
@endsection


