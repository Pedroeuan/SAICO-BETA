
@extends('adminlte::page')

@section('title', 'Crear Reporte')

@section('css')
<!--datatable -->
<!-- Select2 CSS -->

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
    /*PND*/
    @if($Nombre_Formato == 'FOR-PINS-03-02') 
        @include('Reportes.PINS.Create.FOR-PINS-03_02')
    @elseif($Nombre_Formato == 'FOR-PINS-04-01') 
        @include('Reportes.PINS.Create.FOR-PINS-04_01')
    @elseif($Nombre_Formato == 'FOR-PINS-05-01') 
        @include('Reportes.PINS.Create.FOR-PINS-05_01')
    @elseif($Nombre_Formato == 'FOR-PINS-05-02') 
        @include('Reportes.PINS.Create.FOR-PINS-05_02')
    @elseif($Nombre_Formato == 'FOR-PINS-06-01') 
        @include('Reportes.PINS.Create.FOR-PINS-06_01')
    @elseif($Nombre_Formato == 'FOR-PINS-07-01') 
        @include('Reportes.PINS.Create.FOR-PINS-07_01')
    @elseif($Nombre_Formato == 'FOR-PINS-08-01') 
        @include('Reportes.PINS.Create.FOR-PINS-08_01')
    @elseif($Nombre_Formato == 'FOR-PINS-09-01') 
        @include('Reportes.PINS.Create.FOR-PINS-09_01')
    @elseif($Nombre_Formato == 'FOR-PINS-10-01') 
        @include('Reportes.PINS.Create.FOR-PINS-10_01')
    @elseif($Nombre_Formato == 'FOR-PINS-11-01') 
        @include('Reportes.PINS.Create.FOR-PINS-11_01')
    @elseif($Nombre_Formato == 'FOR-PINS-11-02')
        @include('Reportes.PINS.Create.FOR-PINS-11_02')
    @elseif($Nombre_Formato == 'FOR-PINS-12-01') 
        @include('Reportes.PINS.Create.FOR-PINS-12_01')
    @elseif($Nombre_Formato == 'FOR-PINS-13-01') 
        @include('Reportes.PINS.Create.FOR-PINS-13_01')
    @elseif($Nombre_Formato == 'FOR-PINS-14-01') 
        @include('Reportes.PINS.Create.FOR-PINS-14_01')
    @elseif($Nombre_Formato == 'FOR-PINS-15-01') 
        @include('Reportes.PINS.Create.FOR-PINS-15_01')
    @elseif($Nombre_Formato == 'FOR-PINS-16-01') 
        @include('Reportes.PINS.Create.FOR-PINS-16_01')
    @elseif($Nombre_Formato == 'FOR-PINS-17-01')
        @include('Reportes.PINS.Create.FOR-PINS-17_01')
    @elseif($Nombre_Formato == 'FOR-PINS-17-01_01') 
        @include('Reportes.PINS.Create.FOR-PINS-17-01_01')
    @elseif($Nombre_Formato == 'FOR-PINS-18-01') 
        @include('Reportes.PINS.Create.FOR-PINS-18_01')
    @elseif($Nombre_Formato == 'FOR-PINS-19-01') 
        @include('Reportes.PINS.Create.FOR-PINS-19_01')
    @elseif($Nombre_Formato == 'FOR-PINS-19-01') 
        @include('Reportes.PINS.Create.FOR-PINS-19_01')
    @elseif($Nombre_Formato == 'FOR-PINS-20-01') 
        @include('Reportes.PINS.Create.FOR-PINS-20_01')
    @elseif($Nombre_Formato == 'FOR-PINS-21-01') 
        @include('Reportes.PINS.Create.FOR-PINS-21_01')
    @elseif($Nombre_Formato == 'FOR-PINS-22-01') 
        @include('Reportes.PINS.Create.FOR-PINS-22_01')
    @elseif($Nombre_Formato == 'FOR-PINS-23-01') 
        @include('Reportes.PINS.Create.FOR-PINS-23_01')
    @elseif($Nombre_Formato == 'FOR-PINS-24-01') 
        @include('Reportes.PINS.Create.FOR-PINS-24_01')
    @elseif($Nombre_Formato == 'FOR-PINS-25-01') 
        @include('Reportes.PINS.Create.FOR-PINS-25_01')

    {{--@elseif($Nombre_Formato == 'FOR-02-PRO-INS-15') 
        @include('Reportes.INS.Create.FOR-02-PRO-INS-15')
    @elseif($Nombre_Formato == 'FOR-03-PRO-INS-15') 
        @include('Reportes.INS.Create.FOR-03-PRO-INS-15')--}}

    /*IM*/
    @elseif($Nombre_Formato == 'FOR-PIMP-02_B/03') 
        @include('Reportes.IM.Create.FOR-PIMP-02_B_03')
    @elseif($Nombre_Formato == 'FOR-PIMP-02_B/04') 
        @include('Reportes.IM.Create.FOR-PIMP-02_B_04')
    @elseif($Nombre_Formato == 'FOR-PIMP-03_B/01') 
        @include('Reportes.IM.Create.FOR-PIMP-03_B_01')
    @elseif($Nombre_Formato == 'FOR-PIMP-04/02') 
        @include('Reportes.IM.Create.FOR-PIMP-04_02')
    @elseif($Nombre_Formato == 'FOR-PIMP-04/03') 
        @include('Reportes.IM.Create.FOR-PIMP-04_03')
    @elseif($Nombre_Formato == 'FOR-PIMP-05/01') 
        @include('Reportes.IM.Create.FOR-PIMP-05_01')
    @elseif($Nombre_Formato == 'FOR-PIMP-05_B/01') 
        @include('Reportes.IM.Create.FOR-PIMP-05_B_01')
    @elseif($Nombre_Formato == 'FOR-PIMP-06_B/01') 
        @include('Reportes.IM.Create.FOR-PIMP-06_B_01')
    @elseif($Nombre_Formato == 'FOR-PIMP-07_B/01') 
        @include('Reportes.IM.Create.FOR-PIMP-07_B_01')
    @endif
@stop


@section('js')
<!-- Incluye jQuery -->
<!--datatable -->
<!-- Select2 JS -->
<!--sweet alert -->

<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

@endsection
