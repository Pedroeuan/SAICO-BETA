
@extends('adminlte::page')

@section('title', 'Editar Reporte')

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

@section('content')

    @if($Nombre_Formato == 'FOR-PINS-04-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-04_01')
    @elseif($Nombre_Formato == 'FOR-PINS-05-01')
        @include('Reportes.PINS.Edit.FOR-PINS-05_01')
    @elseif($Nombre_Formato == 'FOR-PINS-06-01')
        @include('Reportes.PINS.Edit.FOR-PINS-06_01')
    @elseif($Nombre_Formato == 'FOR-PINS-07-01')
        @include('Reportes.PINS.Edit.FOR-PINS-07_01')
    @elseif($Nombre_Formato == 'FOR-PINS-08-01')
        @include('Reportes.PINS.Edit.FOR-PINS-08_01')
    @elseif($Nombre_Formato == 'FOR-PINS-09-01')
        @include('Reportes.PINS.Edit.FOR-PINS-09_01')
    @elseif($Nombre_Formato == 'FOR-PINS-10-01')
        @include('Reportes.PINS.Edit.FOR-PINS-10_01')
    @elseif($Nombre_Formato == 'FOR-PINS-11-01')
        @include('Reportes.PINS.Edit.FOR-PINS-11_01')
    @elseif($Nombre_Formato == 'FOR-PINS-12-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-12_01')
    @elseif($Nombre_Formato == 'FOR-PINS-13-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-13_01')
    @elseif($Nombre_Formato == 'FOR-PINS-14-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-14_01')
    @elseif($Nombre_Formato == 'FOR-PINS-15-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-15_01')
    @elseif($Nombre_Formato == 'FOR-PINS-16-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-16_01')
    @elseif($Nombre_Formato == 'FOR-PINS-17-01')
        @include('Reportes.PINS.Edit.FOR-PINS-17_01')
    @elseif($Nombre_Formato == 'FOR-PINS-18-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-18_01')
    @elseif($Nombre_Formato == 'FOR-PINS-19-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-19_01')
    @elseif($Nombre_Formato == 'FOR-PINS-19-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-19_01')
    @elseif($Nombre_Formato == 'FOR-PINS-20-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-20_01')
    @elseif($Nombre_Formato == 'FOR-PINS-21-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-21_01')
    @elseif($Nombre_Formato == 'FOR-PINS-22-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-22_01')
    @elseif($Nombre_Formato == 'FOR-PINS-23-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-23_01')
    @elseif($Nombre_Formato == 'FOR-PINS-24-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-24_01')
    @elseif($Nombre_Formato == 'FOR-PINS-25-01') 
        @include('Reportes.PINS.Edit.FOR-PINS-25_01')
    @elseif($Nombre_Formato == 'FOR-PINS-03-02') 
        @include('Reportes.PINS.Edit.FOR-PINS-03_02')
    @elseif($Nombre_Formato == 'FOR-PINS-11-02')
        @include('Reportes.PINS.Edit.FOR-PINS-11_02')
    @elseif($Nombre_Formato == 'FOR-PINS-05-02') 
        @include('Reportes.PINS.Edit.FOR-PINS-05_02')
    @elseif($Nombre_Formato == 'FOR-02-PRO-INS-15') 
        @include('Reportes.INS.Edit.FOR-02-PRO-INS-15')
    @elseif($Nombre_Formato == 'FOR-03-PRO-INS-15') 
        @include('Reportes.INS.Edit.FOR-03-PRO-INS-15')
    @elseif($Nombre_Formato == 'FOR-PINS-17-01_01') 
        @include('Reportes.PINS.Edit.FOR-PINS-17-01_01')
        /*IM*/
    @elseif($Nombre_Formato == 'FOR-PIMP-07_B/01') 
        @include('Reportes.IM.Edit.FOR-PIMP-07_B_01')
    @endif
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
@if($Nombre_Formato == 'FOR-PIMP-07_B/01')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="{{ asset('js/Reportes_Edit.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('FOR-PIMP-07_B_01');
    if (!form) return;

    const detallesGenerales = @json($Detalles_Generales ?? []);
    const datosEquipo = @json($Datos_Equipo ?? []);
    const firmas = @json($Firmas ?? []);

    function setValue(name, value) {
        if (value === undefined || value === null) return;

        const field = form.elements[name];
        if (!field) return;

        if (field instanceof RadioNodeList) {
            field.value = value;
            return;
        }

        field.value = value;
    }

    Object.keys(detallesGenerales).forEach(function (key) {
        setValue('Detalles_Generales[' + key + ']', detallesGenerales[key]);
    });

    Object.keys(datosEquipo).forEach(function (key) {
        setValue('Datos_Equipo[' + key + ']', datosEquipo[key]);
    });

    if (firmas.numFirmas) {
        setValue('numFirmas', firmas.numFirmas);
        const numFirmasSelect = document.getElementById('numFirmas');
        if (numFirmasSelect) {
            numFirmasSelect.dispatchEvent(new Event('change'));
        }
    }

    ['Firmas_Reportes1', 'Firmas_Reportes2', 'Firmas_Reportes3', 'Firmas_Reportes4'].forEach(function (grupo) {
        Object.keys(firmas).forEach(function (key) {
            setValue(grupo + '[' + key + ']', firmas[key]);
        });
    });
});
</script>
@endif
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
